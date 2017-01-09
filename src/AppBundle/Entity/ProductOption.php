<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="product_options")
*/

class ProductOption
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id = 0;

    /**
    * @ORM\Column(name="name", type="string", length=255)
    */
    protected $name = "";

    /**
     * @ORM\ManyToOne(targetEntity="ProductAttribute", inversedBy="productOptions")
     */
    protected $productAttributes;

    public function __toString()
    {
        return $this->name();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setProductAttribute(ProductAttribute $productAttribute)
    {
        $this->productAttribute = $productAttribute;
    }
}
