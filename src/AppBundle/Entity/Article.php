<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="articles")
*/

class Article
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id = 0;

    /**
    * @ORM\Column(type="string", length=80)
    */
    protected $title = '';

    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $teaser = '';

    /**
    * @ORM\Column(type="text")
    */
    protected $news = '';
    
    /**
    * @ORM\Column(name="created_at", type="datetime")
    */
    protected $createdAt;

    /**
    * @ORM\Column(name="publish_at", type="datetime")
    */
    protected $publishAt;

    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="articles")
    */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @ORM\JoinTable(name="tagging")
     */
    protected $tags;

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

    public function getTeaser()
    {
        return $this->teaser;
    }

    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;
    }

    public function getNews()
    {
        return $this->news;
    }

    public function setNews($news)
    {
        $this->news = $news;
    }

    public function getCreatedAt()
    {
        return new \DateTime($this->createdAt);
    }

    public function setCreatedAt($createdAt = 'now')
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getPublishAt()
    {
        return new \DateTime($this->publishAt);
    }

    public function setPublishAt($publishAt)
    {
        if (empty($publishAt)) {
            $publishAt = 'now';
        }
        $this->publishAt = new \DateTime($publishAt);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function clearTags()
    {
        $this->tags->clear();
    }

    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);
    }

    public function hasTag(Tag $tag)
    {
        return $this->tags->contains($tag);
    }

    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    public function getTags()
    {
        return $this->tags;
    }
}
