<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

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
    * @Assert\NotBlank(message = "product.model.not_blank"))
    */
    protected $model = '';

    /**
    * @ORM\Column(type="datetime")
    */
    protected $created;

    /**
    * @ORM\Column(type="string", length=255)
    * @Assert\NotBlank(message = "Das Feld darf nicht leer bleiben!")
    */
    protected $name = '';

    /**
    * @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="product", cascade="persist")
    */
    protected $productAttributes;

    public function __construct()
    {
        $this->productAttributes = new ArrayCollection();
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
        return $this->created;
    }

    public function setCreated(\DateTime $created = null)
    {
        $this->created = $created;
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
        $this->productAttributes->removeElement($productAttribute);
    }

    public function clearProductAttributes()
    {
        $this->productAttributes->clear();
    }
}
