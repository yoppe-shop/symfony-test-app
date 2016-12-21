<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
* @ORM\Table(name="tags")
*/

class Tag
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id = 0;

    /**
    * @ORM\Column(type="string", length=25)
    */
    protected $title = '';

    /**
    * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
    */
    protected $articles;

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId()
    {
        return $this->id;
    }


    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function addArticle($article)
    {
        $this->articles->Add($article);
    }
}
