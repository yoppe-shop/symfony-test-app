<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsDescription
 *
 * @ORM\Table(name="products_description", indexes={@ORM\Index(name="idx_products_name", columns={"products_name"})})
 * @ORM\Entity
 */
class ProductsDescription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="products_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $productsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="language_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $languageId;

    /**
     * @var string
     *
     * @ORM\Column(name="products_name", type="string", length=255, nullable=false)
     */
    private $productsName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="products_description", type="text", length=65535, nullable=true)
     */
    private $productsDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="products_short_description", type="text", length=65535, nullable=true)
     */
    private $productsShortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="products_keywords", type="string", length=255, nullable=true)
     */
    private $productsKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="products_meta_title", type="text", length=65535, nullable=false)
     */
    private $productsMetaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="products_meta_description", type="text", length=65535, nullable=false)
     */
    private $productsMetaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="products_meta_keywords", type="text", length=65535, nullable=false)
     */
    private $productsMetaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="products_url", type="string", length=255, nullable=true)
     */
    private $productsUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_viewed", type="integer", nullable=true)
     */
    private $productsViewed = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="products_order_description", type="text", length=65535, nullable=true)
     */
    private $productsOrderDescription;



    /**
     * Set productsName
     *
     * @param string $productsName
     *
     * @return ProductsDescription
     */
    public function setProductsName($productsName)
    {
        $this->productsName = $productsName;

        return $this;
    }

    /**
     * Get productsName
     *
     * @return string
     */
    public function getProductsName()
    {
        return $this->productsName;
    }

    /**
     * Set productsDescription
     *
     * @param string $productsDescription
     *
     * @return ProductsDescription
     */
    public function setProductsDescription($productsDescription)
    {
        $this->productsDescription = $productsDescription;

        return $this;
    }

    /**
     * Get productsDescription
     *
     * @return string
     */
    public function getProductsDescription()
    {
        return $this->productsDescription;
    }

    /**
     * Set productsShortDescription
     *
     * @param string $productsShortDescription
     *
     * @return ProductsDescription
     */
    public function setProductsShortDescription($productsShortDescription)
    {
        $this->productsShortDescription = $productsShortDescription;

        return $this;
    }

    /**
     * Get productsShortDescription
     *
     * @return string
     */
    public function getProductsShortDescription()
    {
        return $this->productsShortDescription;
    }

    /**
     * Set productsKeywords
     *
     * @param string $productsKeywords
     *
     * @return ProductsDescription
     */
    public function setProductsKeywords($productsKeywords)
    {
        $this->productsKeywords = $productsKeywords;

        return $this;
    }

    /**
     * Get productsKeywords
     *
     * @return string
     */
    public function getProductsKeywords()
    {
        return $this->productsKeywords;
    }

    /**
     * Set productsMetaTitle
     *
     * @param string $productsMetaTitle
     *
     * @return ProductsDescription
     */
    public function setProductsMetaTitle($productsMetaTitle)
    {
        $this->productsMetaTitle = $productsMetaTitle;

        return $this;
    }

    /**
     * Get productsMetaTitle
     *
     * @return string
     */
    public function getProductsMetaTitle()
    {
        return $this->productsMetaTitle;
    }

    /**
     * Set productsMetaDescription
     *
     * @param string $productsMetaDescription
     *
     * @return ProductsDescription
     */
    public function setProductsMetaDescription($productsMetaDescription)
    {
        $this->productsMetaDescription = $productsMetaDescription;

        return $this;
    }

    /**
     * Get productsMetaDescription
     *
     * @return string
     */
    public function getProductsMetaDescription()
    {
        return $this->productsMetaDescription;
    }

    /**
     * Set productsMetaKeywords
     *
     * @param string $productsMetaKeywords
     *
     * @return ProductsDescription
     */
    public function setProductsMetaKeywords($productsMetaKeywords)
    {
        $this->productsMetaKeywords = $productsMetaKeywords;

        return $this;
    }

    /**
     * Get productsMetaKeywords
     *
     * @return string
     */
    public function getProductsMetaKeywords()
    {
        return $this->productsMetaKeywords;
    }

    /**
     * Set productsUrl
     *
     * @param string $productsUrl
     *
     * @return ProductsDescription
     */
    public function setProductsUrl($productsUrl)
    {
        $this->productsUrl = $productsUrl;

        return $this;
    }

    /**
     * Get productsUrl
     *
     * @return string
     */
    public function getProductsUrl()
    {
        return $this->productsUrl;
    }

    /**
     * Set productsViewed
     *
     * @param integer $productsViewed
     *
     * @return ProductsDescription
     */
    public function setProductsViewed($productsViewed)
    {
        $this->productsViewed = $productsViewed;

        return $this;
    }

    /**
     * Get productsViewed
     *
     * @return integer
     */
    public function getProductsViewed()
    {
        return $this->productsViewed;
    }

    /**
     * Set productsOrderDescription
     *
     * @param string $productsOrderDescription
     *
     * @return ProductsDescription
     */
    public function setProductsOrderDescription($productsOrderDescription)
    {
        $this->productsOrderDescription = $productsOrderDescription;

        return $this;
    }

    /**
     * Get productsOrderDescription
     *
     * @return string
     */
    public function getProductsOrderDescription()
    {
        return $this->productsOrderDescription;
    }

    /**
     * Set productsId
     *
     * @param integer $productsId
     *
     * @return ProductsDescription
     */
    public function setProductsId($productsId)
    {
        $this->productsId = $productsId;

        return $this;
    }

    /**
     * Get productsId
     *
     * @return integer
     */
    public function getProductsId()
    {
        return $this->productsId;
    }

    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return ProductsDescription
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
}
