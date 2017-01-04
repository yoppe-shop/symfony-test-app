<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="products")
*/

class Product
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
    protected $model = '';

    /**
    * @ORM\Column(type="datetime")
    */
    protected $created;

    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $name = '';

    /**
    * @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="product")
    */
    protected $productAttributes;

    public function __construct()
    {
        $this->productAttributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCreated()
    {
        return \DateTime($this->created);
    }

    public function setCreated($created)
    {
        $this->created = new \DateTime($created);
    }

    public function getProductAttributes()
    {
        return $this->productAttributes;
    }

    public function hasProductAttribute(ProductAttribute $productAttribute)
    {
        return $this->productAttributes->contains($productAttribute);
    }

    public function addProductAttribute(ProductAttribute $productAttribute)
    {
        $this->productAttributes->add($productAttribute);
    }

    public function removeProductAttribute(ProductAttribute $productAttribute)
    {
        $this->productAttributes->add($productAttribute);
    }

    public function clearProductAttributes()
    {
        $this->productAttributes->clear();
    }
}
