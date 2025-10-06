<?php

namespace App\Entity;

use App\Repository\MangaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Enum\MangaGenre;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MangaRepository::class)]
class Manga
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'mangas')]
    private Collection $authors;

    #[Assert\Isbn(type: 'isbn13')]
    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $isbn = null;

    #[Assert\Url()]
    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $cover = null;

    #[ORM\ManyToOne(inversedBy: 'mangas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Editor $editor = null;

    #[Assert\Length(min: 20)]
    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $plot = null;

    #[Assert\NotBlank()]
    #[ORM\Column]
    private ?int $pageNumber = null;

    #[Assert\NotBlank()]
    #[ORM\Column(enumType: MangaGenre::class)]
    private ?MangaGenre $genre = null;

    #[Assert\NotBlank()]
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "favoriteMangas")]
    private Collection $fans;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->fans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }
        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->authors->removeElement($author);
        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;
        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): static
    {
        $this->cover = $cover;
        return $this;
    }

    public function getEditor(): ?Editor
    {
        return $this->editor;
    }

    public function setEditor(?Editor $editor): static
    {
        $this->editor = $editor;
        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string $plot): static
    {
        $this->plot = $plot;
        return $this;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): static
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    public function getGenre(): ?MangaGenre
    {
        return $this->genre;
    }

    public function setGenre(MangaGenre $genre): static
    {
        $this->genre = $genre;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFans(): Collection
    {
        return $this->fans;
    }

    public function addFan(User $user): self
    {
        if (!$this->fans->contains($user)) {
            $this->fans->add($user);
            $user->addFavoriteManga($this);
        }
        return $this;
    }

    public function removeFan(User $user): self
    {
        if ($this->fans->removeElement($user)) {
            $user->removeFavoriteManga($this);
        }
        return $this;
    }
}
