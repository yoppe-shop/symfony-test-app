<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsOptionsValue
 *
 * @ORM\Table(name="products_options_values")
 * @ORM\Entity
 */
class ProductsOptionsValue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="products_options_values_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $productsOptionsValuesId = '0';

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
     * @ORM\Column(name="products_options_values_name", type="string", length=255, nullable=false)
     */
    private $productsOptionsValuesName = '';

    /**
    * @ORM\ManyToMany(targetEntity="Product")
    * @ORM\JoinTable(name="products_attributes",
    * joinColumns={
    *     @ORM\JoinColumn(name="options_values_id", referencedColumnName="products_options_values_id")
    * },
    * inverseJoinColumns={
    *     @ORM\JoinColumn(name="products_id", referencedColumnName="products_id")
    * }
    * )
    */
    protected $product;

    /**
     * Set productsOptionsValuesName
     *
     * @param string $productsOptionsValuesName
     *
     * @return ProductsOptionsValues
     */
    public function setProductsOptionsValuesName($productsOptionsValuesName)
    {
        $this->productsOptionsValuesName = $productsOptionsValuesName;

        return $this;
    }

    /**
     * Get productsOptionsValuesName
     *
     * @return string
     */
    public function getProductsOptionsValuesName()
    {
        return $this->productsOptionsValuesName;
    }

    /**
     * Set productsOptionsValuesId
     *
     * @param integer $productsOptionsValuesId
     *
     * @return ProductsOptionsValues
     */
    public function setProductsOptionsValuesId($productsOptionsValuesId)
    {
        $this->productsOptionsValuesId = $productsOptionsValuesId;

        return $this;
    }

    /**
     * Get productsOptionsValuesId
     *
     * @return integer
     */
    public function getProductsOptionsValuesId()
    {
        return $this->productsOptionsValuesId;
    }

    /**
     * Set languageId
     *
     * @param integer $languageId
     *
     * @return ProductsOptionsValues
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

    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return ProductsOptionsValue
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }
}
