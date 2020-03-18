<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\File;
use App\Entity\Review;
use App\Form\EditContentType;
use App\Form\NewContentType;
use App\Repository\ContentRepository;
use App\Repository\FileRepository;
use App\Repository\ReviewRepository;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/content", name="content_")
 */
class ContentController extends AbstractFOSRestController
{
    private $contentRepository;
    private $em;
    private $passwordEncoder;
    public function __construct(ContentRepository $contentRepository, EntityManagerInterface $em)
    {
        $this->contentRepository = $contentRepository;
        $this->em = $em;
    }

    /**
     * @Route("/all", name="all")
     */
    public function all(ContentRepository $contentRepository)
    {
        $contentsList = $contentRepository->findPublishedContents();

        return $this->render('content/all.html.twig', [
            'contentsOrderByDate' => $contentsList,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, ValidatorInterface $validator, FileService $fileService)
    {
        $form = $this->createForm(NewContentType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $content = new Content();

            $content->setAuthor($this->getUser());
            $content->setTitle($data->getTitle());
            $content->setEnabled(1);
            $content->setText($data->getText());
            $content->setCategory($data->getCategory());
            $form->get('save')->isClicked() ? $content->setStatus(0):  $content->setStatus(1);
            $this->em->persist($content);
            if ($form->get('pj')->getData()){
                $pj = new File();
                $pj->setFile($form->get('pj')->getData());
                $pj = $fileService->upload($pj);
                $pj->setContent($content);
                $this->em->persist($pj);
            }
            $this->em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('content/newContent.html.twig', [
            'form' => $form->createView(),
            'action' => 'Rediger'
        ]);
    }

    /**
     * @Route("/waiting", name="waiting")
     */
    public function waiting()
    {
        $waitingContent = $this->contentRepository->findBy(['publisher' => null, 'status' => 1]);

        return $this->render('content/waiting.html.twig', [
            'contents' => $waitingContent,
            'reviews' => $this->getUser()->getReviews()
        ]);
    }
    /**
     * @Route("/approval", name="approval")
     */
    public function approval(ReviewRepository $reviewRepository,ContentRepository $contentRepository, Request $request)
    {
        $user = $this->getUser();
        $content = $contentRepository->find($request->get('id'));
        $review = $reviewRepository->findOneBy(['user' => $user, 'content' => $content]);
        if ($review) {
            $review->setAccepted(1);
        }
        else {
            $review = new Review();
            $review->setUser($user);
            $review->setContent($content);
            $review->setAccepted(1);
        }
        $this->em->persist($review);
        $this->em->flush();
        $response['yes'] = $content->getNbApproval();
        $response['no'] =  count($content->getReviews()) - $content->getNbApproval();
        return (new JsonResponse($response));
    }


    /**
     * @Route("/comment", name="comment", methods="POST")
     */
    public function addComment(ContentRepository $contentRepository, Request $request)
    {
        $content = $contentRepository->find($request->get('idContent'));
        if ($request->get('text')) {

            $user = $this->getUser();
            $comment = new Comment();
            $comment->setText($request->get('text'));
            $comment->setUser($user);
            $comment->setContent($content);
            $comment->setPublishAt(new \DateTime());
            $this->em->persist($comment);
            $this->em->flush();
        }
        $comments = $content->getComments();
        $aComment = [];
        foreach ($comments as $comment) {
            $aComment[] = [
                'id' => $comment->getId(),
                'text' => $comment->getText(),
                'lastname' => $comment->getUser()->getLastname(),
                'firstname' =>$comment->getUser()->getFirstname(),
                'date' => date_format($comment->getPublishAt(), 'd/m/Y H:i:s'),
            ];
        }
        return (new JsonResponse($aComment));
    }

    /**
     * @Route("/refuse", name="refuse")
     */
    public function refuse(ReviewRepository $reviewRepository,ContentRepository $contentRepository, Request $request)
    {
        $user = $this->getUser();
        $content = $contentRepository->find($request->get('id'));
        $review = $reviewRepository->findOneBy(['user' => $user, 'content' => $content]);
        if ($review) {
            $review->setAccepted(0);
        }
        else {
            $review = new Review();
            $review->setUser($user);
            $review->setContent($content);
            $review->setAccepted(0);
        }
        $this->em->persist($review);
        $this->em->flush();
        $response['yes'] = $content->getNbApproval();
        $response['no'] =  count($content->getReviews()) - $content->getNbApproval();
        return (new JsonResponse($response));
    }
    /**
     * @Route("/publier/{id}", name="publier")
     */
    public function publier(Content $content)
    {
        $content->setPublisher($this->getUser());
        $content->setPublicationDate(new \DateTime());
        $this->em->persist($content);
        $this->em->flush();
        return $this->redirectToRoute('content_waiting');
    }

    /**
     * @Route("/refuser/{id}", name="refuser")
     */
    public function refuser(Content $content)
    {
        $content->setPublisher($this->getUser());
        $this->em->persist($content);
        $this->em->flush();
        return $this->redirectToRoute('content_waiting');
    }


    /**
     * @Route("/{id}", name="redContent")
     */
    public function  getById(Content $content, FileRepository $fileRepository)
    {
        $file = $fileRepository->findOneBy(['content' => $content->getId()]);

        return $this->render('content/read.html.twig', [
            'action' => 'Lire',
            'content' => $content,
            'file' => $file,
        ]);
    }

    /**
     * @Route("/review/{id}", name="review")
     */
    public function review(Content $content, ContentRepository $contentRepository, Request $request, ReviewRepository $reviewRepository)
    {
        $form = $this->createForm(EditContentType::class, $content);
        $form->handleRequest($request);
        $review = $reviewRepository->findOneBy(['user' => $this->getUser(), 'content' => $content]);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $content->setTitle($data->getTitle());
            $content->setText($data->getText());
            $content->setCategory($data->getCategory());
            $this->em->persist($content);
            $this->em->flush();
            $this->get('session')->getFlashBag()->add(
                'message',
                'Modifications enregistrées'
            );
        }
        return $this->render('content/review.html.twig', [
            'form' => $form->createView(),
            'action' => 'Modifier',
            'content' => $content,
            'review' => $review
        ]);
    }


}