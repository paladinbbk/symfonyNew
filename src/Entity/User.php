<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Paladin\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="author")
     */
    private $authorArticles;

    public function __construct()
    {
        parent::__construct();
        $this->authorArticles = new ArrayCollection();
        // your own logic
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @return Collection|Article[]
     */
    public function getAuthorArticles(): Collection
    {
        return $this->authorArticles;
    }

    public function addAuthorArticle(Article $authorArticle): self
    {
        if (!$this->authorArticles->contains($authorArticle)) {
            $this->authorArticles[] = $authorArticle;
            $authorArticle->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthorArticle(Article $authorArticle): self
    {
        if ($this->authorArticles->contains($authorArticle)) {
            $this->authorArticles->removeElement($authorArticle);
            // set the owning side to null (unless already changed)
            if ($authorArticle->getAuthor() === $this) {
                $authorArticle->setAuthor(null);
            }
        }

        return $this;
    }
}