<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProductsAttribute
 *
 * @ORM\Table(name="products_attributes", indexes={@ORM\Index(name="idx_products_id", columns={"products_id"}), @ORM\Index(name="idx_options", columns={"options_id", "options_values_id"})})
 * @ORM\Entity
 */
class ProductsAttribute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="products_attributes_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productsAttributesId;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_id", type="integer", nullable=false)
     */
    private $productsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="options_id", type="integer", nullable=false)
     */
    private $optionsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="options_values_id", type="integer", nullable=false)
     */
    private $optionsValuesId;

    /**
     * @var string
     *
     * @ORM\Column(name="options_values_price", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $optionsValuesPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="price_prefix", type="string", length=1, nullable=false)
     */
    private $pricePrefix;

    /**
     * @var string
     *
     * @ORM\Column(name="attributes_model", type="string", length=64, nullable=true)
     */
    private $attributesModel;

    /**
     * @var integer
     *
     * @ORM\Column(name="attributes_stock", type="integer", nullable=true)
     */
    private $attributesStock;

    /**
     * @var string
     *
     * @ORM\Column(name="options_values_weight", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $optionsValuesWeight;

    /**
     * @var string
     *
     * @ORM\Column(name="weight_prefix", type="string", length=1, nullable=false)
     */
    private $weightPrefix;

    /**
     * @var integer
     *
     * @ORM\Column(name="sortorder", type="integer", nullable=true)
     */
    private $sortorder;

    /**
     * @var string
     *
     * @ORM\Column(name="attributes_ean", type="string", length=64, nullable=true)
     */
    private $attributesEan;

    /**
     * @var integer
     *
     * @ORM\Column(name="attributes_vpe_id", type="integer", nullable=false)
     */
    private $attributesVpeId;

    /**
     * @var string
     *
     * @ORM\Column(name="attributes_vpe_value", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $attributesVpeValue;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productsAttributes")
     * @ORM\JoinColumn(name="products_id", referencedColumnName="products_id")
     */
    private $product;

    /**
     * @ORM\ManyToMany(targetEntity="ProductsOption", mappedBy="productsAttributes")
     * @ORM\JoinTable(name="products_attributes"),
     * joinColumns={
     *     @ORM\JoinColumn(name="products_options_id", referencedColumnName="options_id")
     * }
     * )
     */
     private $productsOptions;

    /**
     * @ORM\ManyToMany(targetEntity="ProductsOptionsValue", mappedBy="productsAttributes")
     * @ORM\JoinTable(name="products_attributes"),
     * joinColumns={
     *     @ORM\JoinColumn(name="products_options_values_id", referencedColumnName="options_values_id")
     * }
     * )
     */
     private $productsOptionsValues;

    public function __construct()
    {
        $this->productsOptions = new ArrayCollection();
        $this->productsOptionsValues = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->attributesId;
    }

    /**
     * Set productsId
     *
     * @param integer $productsId
     *
     * @return ProductsAttributes
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
     * Set optionsId
     *
     * @param integer $optionsId
     *
     * @return ProductsAttributes
     */
    public function setOptionsId($optionsId)
    {
        $this->optionsId = $optionsId;

        return $this;
    }

    /**
     * Get optionsId
     *
     * @return integer
     */
    public function getOptionsId()
    {
        return $this->optionsId;
    }

    /**
     * Set optionsValuesId
     *
     * @param integer $optionsValuesId
     *
     * @return ProductsAttributes
     */
    public function setOptionsValuesId($optionsValuesId)
    {
        $this->optionsValuesId = $optionsValuesId;

        return $this;
    }

    /**
     * Get optionsValuesId
     *
     * @return integer
     */
    public function getOptionsValuesId()
    {
        return $this->optionsValuesId;
    }

    /**
     * Set optionsValuesPrice
     *
     * @param string $optionsValuesPrice
     *
     * @return ProductsAttributes
     */
    public function setOptionsValuesPrice($optionsValuesPrice)
    {
        $this->optionsValuesPrice = $optionsValuesPrice;

        return $this;
    }

    /**
     * Get optionsValuesPrice
     *
     * @return string
     */
    public function getOptionsValuesPrice()
    {
        return $this->optionsValuesPrice;
    }

    /**
     * Set pricePrefix
     *
     * @param string $pricePrefix
     *
     * @return ProductsAttributes
     */
    public function setPricePrefix($pricePrefix)
    {
        $this->pricePrefix = $pricePrefix;

        return $this;
    }

    /**
     * Get pricePrefix
     *
     * @return string
     */
    public function getPricePrefix()
    {
        return $this->pricePrefix;
    }

    /**
     * Set attributesModel
     *
     * @param string $attributesModel
     *
     * @return ProductsAttributes
     */
    public function setAttributesModel($attributesModel)
    {
        $this->attributesModel = $attributesModel;

        return $this;
    }

    /**
     * Get attributesModel
     *
     * @return string
     */
    public function getAttributesModel()
    {
        return $this->attributesModel;
    }

    /**
     * Set attributesStock
     *
     * @param integer $attributesStock
     *
     * @return ProductsAttributes
     */
    public function setAttributesStock($attributesStock)
    {
        $this->attributesStock = $attributesStock;

        return $this;
    }

    /**
     * Get attributesStock
     *
     * @return integer
     */
    public function getAttributesStock()
    {
        return $this->attributesStock;
    }

    /**
     * Set optionsValuesWeight
     *
     * @param string $optionsValuesWeight
     *
     * @return ProductsAttributes
     */
    public function setOptionsValuesWeight($optionsValuesWeight)
    {
        $this->optionsValuesWeight = $optionsValuesWeight;

        return $this;
    }

    /**
     * Get optionsValuesWeight
     *
     * @return string
     */
    public function getOptionsValuesWeight()
    {
        return $this->optionsValuesWeight;
    }

    /**
     * Set weightPrefix
     *
     * @param string $weightPrefix
     *
     * @return ProductsAttributes
     */
    public function setWeightPrefix($weightPrefix)
    {
        $this->weightPrefix = $weightPrefix;

        return $this;
    }

    /**
     * Get weightPrefix
     *
     * @return string
     */
    public function getWeightPrefix()
    {
        return $this->weightPrefix;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     *
     * @return ProductsAttributes
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder
     *
     * @return integer
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set attributesEan
     *
     * @param string $attributesEan
     *
     * @return ProductsAttributes
     */
    public function setAttributesEan($attributesEan)
    {
        $this->attributesEan = $attributesEan;

        return $this;
    }

    /**
     * Get attributesEan
     *
     * @return string
     */
    public function getAttributesEan()
    {
        return $this->attributesEan;
    }

    /**
     * Set attributesVpeId
     *
     * @param integer $attributesVpeId
     *
     * @return ProductsAttributes
     */
    public function setAttributesVpeId($attributesVpeId)
    {
        $this->attributesVpeId = $attributesVpeId;

        return $this;
    }

    /**
     * Get attributesVpeId
     *
     * @return integer
     */
    public function getAttributesVpeId()
    {
        return $this->attributesVpeId;
    }

    /**
     * Set attributesVpeValue
     *
     * @param string $attributesVpeValue
     *
     * @return ProductsAttributes
     */
    public function setAttributesVpeValue($attributesVpeValue)
    {
        $this->attributesVpeValue = $attributesVpeValue;

        return $this;
    }

    /**
     * Get attributesVpeValue
     *
     * @return string
     */
    public function getAttributesVpeValue()
    {
        return $this->attributesVpeValue;
    }

    /**
     * Get productsAttributesId
     *
     * @return integer
     */
    public function getProductsAttributesId()
    {
        return $this->productsAttributesId;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }
    public function getProductsOptions()
    {
        return $this->productsOptions;
    }

    public function hasProductsOption(ProductsOption $productsOption)
    {
        return $this->productsOptions->contains($productsOption);
    }

    public function addProductsOption(ProductsOption $productsOption)
    {
        $this->productsOptions->add($productsOption);
    }

    public function removeProductsOption(ProductsOption $productsOption)
    {
        $this->productsOptions->removeElement($productsOption);
    }

    public function clearProductsOptions()
    {
        $this->productsOptions->clear();
    }

    public function getProductsOptionsValues()
    {
        return $this->productsOptionsValues;
    }

    public function hasProductsOptionsValue(ProductsOptionsValue $productsOptionsValue)
    {
        return $this->productsOptionsValues->contains($productsOptionsValue);
    }

    public function addProductsOptionsValue(ProductsOption $productsOptionsValue)
    {
        $this->productsOptionsValues->add($productsOptionsValue);
    }

    public function removeProductsOptionsValue(ProductsOptionsValue $productsOptionsValue)
    {
        $this->productsOptionsValues->removeElement($productsOptionsValue);
    }

    public function clearProductsOptionsValues()
    {
        $this->productsOptionsValues->clear();
    }
}
