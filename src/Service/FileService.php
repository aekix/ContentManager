<?php

namespace App\Service;

use App\Entity\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileService
{
    private $params;
    private $filesystem;

    public function __construct(Filesystem $filesystem, ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->filesystem = $filesystem;
    }

    public function upload(File $pj)
    {
// Varibles.
        $file = $pj->getFile();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $basePath = $this->params->get('upload_directory');
        $pj->setType($file->guessExtension());

// Move file.
        $file->move($basePath, $fileName);
        $pj->setPath('/upload/' . $fileName);
        $pj->setFile(null);
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $pj->setName($safeFilename);
        return $pj;
    }
}