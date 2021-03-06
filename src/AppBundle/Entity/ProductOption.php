<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
* @ORM\Entity
* @ORM\Table(name="product_options")
*/

class ProductOption
{
    /**
    * @ORM\Id
    * ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id = 0;

    /**
    * @ORM\Id
    * @ORM\Column(name="language_id", type="integer")
    */
    protected $languageId;

    /**
    * @ORM\Column(name="name", type="string", length=255)
    */
    protected $name = "";

    /**
    * @ORM\ManyToMany(targetEntity="Product")
    * @ORM\JoinTable(name="product_attributes",
    * joinColumns={
    *     @ORM\JoinColumn(name="product_option_id", referencedColumnName="id")
    * },
    * inverseJoinColumns={
    *     @ORM\JoinColumn(name="product_id", referencedColumnName="id")
    * }
    * )
    */
    protected $products;

    public function __toString()
    {
        return $this->name();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLanguageId()
    {
        return $this->languageId;
    }

    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

        return $this;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
