<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File as Filer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Content", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $content;

    /**
     * @Assert\NotBlank
     * @Assert\File(
     *     maxSize = "30M",
     *     mimeTypes = {
     *         "video/mp4",
     *         "video/mpeg",
     *         "video/webm",
     *         "image/jpg",
     *         "image/bmp",
     *         "image/webp",
     *         "image/png",
     *         "image/jpeg",
     *     },
     *     maxSizeMessage="Le poids de la pièce jointe est trop important. Le poids maximum accepté est {{ limit }}.",
     *     mimeTypesMessage= "Ce type de fichier n'est pas prit en charge."
     * )
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(Content $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(?Filer $file)
    {
        $this->file = $file;
        return $this;
    }
}
