<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsOption
 *
 * @ORM\Table(name="products_options")
 * @ORM\Entity
 */
class ProductsOption
{
    /**
     * @var integer
     *
     * @ORM\Column(name="products_options_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $productsOptionsId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="language_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $languageId = "2";

    /**
     * @var string
     *
     * @ORM\Column(name="products_options_name", type="string", length=255, nullable=false)
     */
    private $productsOptionsName = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="products_options_sortorder", type="integer", nullable=false)
     */
    private $productsOptionsSortorder;

    /**
     * @ORM\ManyToOne(targetEntity="ProductsAttribute", inversedBy="productsOptions")
     * @ORM\JoinColumn(name="products_options_id", referencedColumnName="options_id")
     */
    private $productsAttribute;


    /**
     * Set productsOptionsName
     *
     * @param string $productsOptionsName
     *
     * @return ProductsOptions
     */
    public function setProductsOptionsName($productsOptionsName)
    {
        $this->productsOptionsName = $productsOptionsName;

        return $this;
    }

    /**
     * Get productsOptionsName
     *
     * @return string
     */
    public function getProductsOptionsName()
    {
        return $this->productsOptionsName;
    }

    /**
     * Set productsOptionsSortorder
     *
     * @param integer $productsOptionsSortorder
     *
     * @return ProductsOptions
     */
    public function setProductsOptionsSortorder($productsOptionsSortorder)
    {
        $this->productsOptionsSortorder = $productsOptionsSortorder;

        return $this;
    }

    /**
     * Get productsOptionsSortorder
     *
     * @return integer
     */
    public function getProductsOptionsSortorder()
    {
        return $this->productsOptionsSortorder;
    }

    /**
     * Set productsOptionsId
     *
     * @param integer $productsOptionsId
     *
     * @return ProductsOptions
     */
    public function setProductsOptionsId($productsOptionsId)
    {
        $this->productsOptionsId = $productsOptionsId;

        return $this;
    }

    /**
     * Get productsOptionsId
     *
     * @return integer
     */
    public function getProductsOptionsId()
    {
        return $this->productsOptionsId;
    }

    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return ProductsOptions
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

        return $this;
    }

    /**
     * Get languageId
     *
     * @return integer
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    public function getProductsAttribute()
    {
        return $this->productsAttribute;
    }

    public function setProductsAttribute($productsAttribute)
    {
        $this->productsAttribute = $productsAttribute;

        return $this;
    }
}
