<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Content;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class)
            ->add('title')
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'label'])
            ->add('pj', FileType::class, array(
                "mapped" => false, 'required' => false,
            ))
            ->add('send', SubmitType::class, ['label' => 'Envoyer'])
            ->add('save', SubmitType::class, ['label' => 'Continuer plus tard'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
