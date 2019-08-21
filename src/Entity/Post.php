<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(name="published_at", type="datetime", nullable=true)
     * @Assert\DateTime
     */
    private $publishedAt;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\DateTime
     * @Assert\NotBlank
     */
    private $createdAt;

    /**
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     * @Assert\DateTime
     */
    private $modifiedAt;


    /**
     * Post constructor.
     * @param string|null $title
     * @param string|null $content
     * @throws Exception
     */
    public function __construct(?string $title, ?string $content)
    {
        $this->setTitle($title)
            ->setContent($content)
            ->setCreatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function publish(): void
    {
        if (!$this->getPublishedAt()) {
            $this->setPublishedAt(new \DateTime());
        }
    }
}
