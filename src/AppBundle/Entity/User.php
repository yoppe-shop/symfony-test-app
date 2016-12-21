<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="users")
*/

class User
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id = 0;

    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $email = '';

    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $password = '';

    /**
    * @ORM\OneToMany(targetEntity="Article", mappedBy="user")
    */
    protected $articles;

    public function __toString()
    {
        return $this->getEmail();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function clearArticles()
    {
        $this->articles->clear;
    }

    public function addArticle(Article $article)
    {
        $this->articles->add($article);
    }

    public function hasArticle(Article $article)
    {
        return $this->articles->contains($article);
    }

    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

    public function getArticles()
    {
        return $this->articles;
    }
}
