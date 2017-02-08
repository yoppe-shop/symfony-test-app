<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
* @ORM\Entity
* @ORM\Table(name="product_option_values", uniqueConstraints={@UniqueConstraint(name="product_option_values_pkey", columns={"id", "language_id"})})
*/

class PlainProductOptionValue
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id = 0;

    /**
    * @ORM\Column(name="language_id", type="integer")
    */
    protected $languageId = 2;

    /**
    * @ORM\Column(name="name", type="string", length=255)
    */
    protected $name = "";

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
    }
}
