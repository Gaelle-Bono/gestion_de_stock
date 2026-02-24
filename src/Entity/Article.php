<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    #[ORM\OneToMany(targetEntity: StockArticle::class, mappedBy: 'article')]
    private Collection $stockArticles;


    public function __construct()
    {
        $this->stockArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): static
    {
        $this->structure = $structure;

        return $this;
    }

    public function getStockArticles(): Collection
    {
        return $this->stockArticles;
    }

    public function addStockArticle(StockArticle $stockArticle): static
    {
        if (!$this->stockArticles->contains($stockArticle)) {
            $this->stockArticles->add($stockArticle);
            $stockArticle->setArticle($this);
        }

        return $this;
    }

    public function removeStockArticle(StockArticle $stockArticle): static
    {
        if ($this->stockArticles->removeElement($stockArticle)) {
            // set the owning side to null (unless already changed)
            if ($stockArticle->getArticle() === $this) {
                $stockArticle->setArticle(null);
            }
        }

        return $this;
    }
}
