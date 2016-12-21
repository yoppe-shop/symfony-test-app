<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="product_attributes")
*/

class ProductAttribute
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id = 0;

    /**
    * @ORM\Column(name="product_id", type="integer")
    */
    protected $productId = 0;

    /**
    * @ORM\Column(name="product_option_id", type="integer")
    */
    protected $productOptionId = 0;

    /**
    * @ORM\Column(name="product_option_value_id", type="integer")
    */
    protected $productOptionValueId = 0;

    public function __toString()
    {
        return $this->name();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }
    public function getProductOptionId()
    {
        return $this->productOptionId;
    }

    public function setProductOptionId($productOptionId)
    {
        $this->productOptionId = $productOptionId;
    }
    public function getProductOptionValueId()
    {
        return $this->productOptionValueId;
    }

    public function setProductOptionValueId($productOptionValueId)
    {
        $this->productOptionValueId = $productOptionValueId;
    }
}
