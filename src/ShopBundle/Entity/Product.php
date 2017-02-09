<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="products", indexes={@ORM\Index(name="idx_products_date_added", columns={"products_date_added"}), @ORM\Index(name="idx_products_model", columns={"products_model"}), @ORM\Index(name="idx_products_status", columns={"products_status"})})
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="products_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productsId;

    /**
     * @var string
     *
     * @ORM\Column(name="products_ean", type="string", length=128, nullable=true)
     */
    private $productsEan;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_quantity", type="integer", nullable=false)
     */
    private $productsQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_shippingtime", type="integer", nullable=false)
     */
    private $productsShippingtime;

    /**
     * @var string
     *
     * @ORM\Column(name="products_model", type="string", length=64, nullable=true)
     */
    private $productsModel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="group_permission_0", type="boolean", nullable=false)
     */
    private $groupPermission0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="group_permission_1", type="boolean", nullable=false)
     */
    private $groupPermission1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="group_permission_2", type="boolean", nullable=false)
     */
    private $groupPermission2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="group_permission_3", type="boolean", nullable=false)
     */
    private $groupPermission3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="group_permission_4", type="boolean", nullable=false)
     */
    private $groupPermission4;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_sort", type="integer", nullable=false)
     */
    private $productsSort = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="products_image", type="string", length=254, nullable=false)
     */
    private $productsImage;

    /**
     * @var string
     *
     * @ORM\Column(name="products_price", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $productsPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="products_discount_allowed", type="decimal", precision=4, scale=2, nullable=false)
     */
    private $productsDiscountAllowed = '0.00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="products_date_added", type="datetime", nullable=false)
     */
    private $productsDateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="products_last_modified", type="datetime", nullable=true)
     */
    private $productsLastModified;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="products_date_available", type="datetime", nullable=true)
     */
    private $productsDateAvailable;

    /**
     * @var string
     *
     * @ORM\Column(name="products_weight", type="decimal", precision=6, scale=3, nullable=false)
     */
    private $productsWeight;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_status", type="integer", nullable=false)
     */
    private $productsStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_tax_class_id", type="integer", nullable=false)
     */
    private $productsTaxClassId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_template", type="string", length=64, nullable=true)
     */
    private $productTemplate;

    /**
     * @var string
     *
     * @ORM\Column(name="options_template", type="string", length=64, nullable=true)
     */
    private $optionsTemplate;

    /**
     * @var integer
     *
     * @ORM\Column(name="manufacturers_id", type="integer", nullable=true)
     */
    private $manufacturersId;

    /**
     * @var string
     *
     * @ORM\Column(name="products_manufacturers_model", type="string", length=64, nullable=true)
     */
    private $productsManufacturersModel;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_ordered", type="integer", nullable=false)
     */
    private $productsOrdered = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="products_fsk18", type="integer", nullable=false)
     */
    private $productsFsk18 = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="products_vpe", type="integer", nullable=false)
     */
    private $productsVpe;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_vpe_status", type="integer", nullable=false)
     */
    private $productsVpeStatus = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="products_vpe_value", type="decimal", precision=15, scale=4, nullable=false)
     */
    private $productsVpeValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="products_startpage", type="integer", nullable=false)
     */
    private $productsStartpage = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="products_startpage_sort", type="integer", nullable=false)
     */
    private $productsStartpageSort = '0';

    /**
    * @ORM\OneToMany(targetEntity="ProductsAttribute", mappedBy="product", cascade="persist")
    */
    private $productsAttributes;

    /**
    * @ORM\ManyToMany(targetEntity="ProductsOption")
    * @ORM\JoinTable(name="products_attributes",
    * joinColumns={
    *     @ORM\JoinColumn(name="products_id", referencedColumnName="products_id")
    * },
    * inverseJoinColumns={
    *     @ORM\JoinColumn(name="options_id", referencedColumnName="products_options_id")
    * }
    * )
    */
    protected $productsOptions;

    public function __construct()
    {
        $this->productsAttributes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->productsModel;
    }

    /**
     * Set productsEan
     *
     * @param string $productsEan
     *
     * @return Products
     */
    public function setProductsEan($productsEan)
    {
        $this->productsEan = $productsEan;

        return $this;
    }

    /**
     * Get productsEan
     *
     * @return string
     */
    public function getProductsEan()
    {
        return $this->productsEan;
    }

    /**
     * Set productsQuantity
     *
     * @param integer $productsQuantity
     *
     * @return Products
     */
    public function setProductsQuantity($productsQuantity)
    {
        $this->productsQuantity = $productsQuantity;

        return $this;
    }

    /**
     * Get productsQuantity
     *
     * @return integer
     */
    public function getProductsQuantity()
    {
        return $this->productsQuantity;
    }

    /**
     * Set productsShippingtime
     *
     * @param integer $productsShippingtime
     *
     * @return Products
     */
    public function setProductsShippingtime($productsShippingtime)
    {
        $this->productsShippingtime = $productsShippingtime;

        return $this;
    }

    /**
     * Get productsShippingtime
     *
     * @return integer
     */
    public function getProductsShippingtime()
    {
        return $this->productsShippingtime;
    }

    /**
     * Set productsModel
     *
     * @param string $productsModel
     *
     * @return Products
     */
    public function setProductsModel($productsModel)
    {
        $this->productsModel = $productsModel;

        return $this;
    }

    /**
     * Get productsModel
     *
     * @return string
     */
    public function getProductsModel()
    {
        return $this->productsModel;
    }

    /**
     * Set groupPermission0
     *
     * @param boolean $groupPermission0
     *
     * @return Products
     */
    public function setGroupPermission0($groupPermission0)
    {
        $this->groupPermission0 = $groupPermission0;

        return $this;
    }

    /**
     * Get groupPermission0
     *
     * @return boolean
     */
    public function getGroupPermission0()
    {
        return $this->groupPermission0;
    }

    /**
     * Set groupPermission1
     *
     * @param boolean $groupPermission1
     *
     * @return Products
     */
    public function setGroupPermission1($groupPermission1)
    {
        $this->groupPermission1 = $groupPermission1;

        return $this;
    }

    /**
     * Get groupPermission1
     *
     * @return boolean
     */
    public function getGroupPermission1()
    {
        return $this->groupPermission1;
    }

    /**
     * Set groupPermission2
     *
     * @param boolean $groupPermission2
     *
     * @return Products
     */
    public function setGroupPermission2($groupPermission2)
    {
        $this->groupPermission2 = $groupPermission2;

        return $this;
    }

    /**
     * Get groupPermission2
     *
     * @return boolean
     */
    public function getGroupPermission2()
    {
        return $this->groupPermission2;
    }

    /**
     * Set groupPermission3
     *
     * @param boolean $groupPermission3
     *
     * @return Products
     */
    public function setGroupPermission3($groupPermission3)
    {
        $this->groupPermission3 = $groupPermission3;

        return $this;
    }

    /**
     * Get groupPermission3
     *
     * @return boolean
     */
    public function getGroupPermission3()
    {
        return $this->groupPermission3;
    }

    /**
     * Set groupPermission4
     *
     * @param boolean $groupPermission4
     *
     * @return Products
     */
    public function setGroupPermission4($groupPermission4)
    {
        $this->groupPermission4 = $groupPermission4;

        return $this;
    }

    /**
     * Get groupPermission4
     *
     * @return boolean
     */
    public function getGroupPermission4()
    {
        return $this->groupPermission4;
    }

    /**
     * Set productsSort
     *
     * @param integer $productsSort
     *
     * @return Products
     */
    public function setProductsSort($productsSort)
    {
        $this->productsSort = $productsSort;

        return $this;
    }

    /**
     * Get productsSort
     *
     * @return integer
     */
    public function getProductsSort()
    {
        return $this->productsSort;
    }

    /**
     * Set productsImage
     *
     * @param string $productsImage
     *
     * @return Products
     */
    public function setProductsImage($productsImage)
    {
        $this->productsImage = $productsImage;

        return $this;
    }

    /**
     * Get productsImage
     *
     * @return string
     */
    public function getProductsImage()
    {
        return $this->productsImage;
    }

    /**
     * Set productsPrice
     *
     * @param string $productsPrice
     *
     * @return Products
     */
    public function setProductsPrice($productsPrice)
    {
        $this->productsPrice = $productsPrice;

        return $this;
    }

    /**
     * Get productsPrice
     *
     * @return string
     */
    public function getProductsPrice()
    {
        return $this->productsPrice;
    }

    /**
     * Set productsDiscountAllowed
     *
     * @param string $productsDiscountAllowed
     *
     * @return Products
     */
    public function setProductsDiscountAllowed($productsDiscountAllowed)
    {
        $this->productsDiscountAllowed = $productsDiscountAllowed;

        return $this;
    }

    /**
     * Get productsDiscountAllowed
     *
     * @return string
     */
    public function getProductsDiscountAllowed()
    {
        return $this->productsDiscountAllowed;
    }

    /**
     * Set productsDateAdded
     *
     * @param \DateTime $productsDateAdded
     *
     * @return Products
     */
    public function setProductsDateAdded($productsDateAdded)
    {
        $this->productsDateAdded = $productsDateAdded;

        return $this;
    }

    /**
     * Get productsDateAdded
     *
     * @return \DateTime
     */
    public function getProductsDateAdded()
    {
        return $this->productsDateAdded;
    }

    /**
     * Set productsLastModified
     *
     * @param \DateTime $productsLastModified
     *
     * @return Products
     */
    public function setProductsLastModified($productsLastModified)
    {
        $this->productsLastModified = $productsLastModified;

        return $this;
    }

    /**
     * Get productsLastModified
     *
     * @return \DateTime
     */
    public function getProductsLastModified()
    {
        return $this->productsLastModified;
    }

    /**
     * Set productsDateAvailable
     *
     * @param \DateTime $productsDateAvailable
     *
     * @return Products
     */
    public function setProductsDateAvailable($productsDateAvailable)
    {
        $this->productsDateAvailable = $productsDateAvailable;

        return $this;
    }

    /**
     * Get productsDateAvailable
     *
     * @return \DateTime
     */
    public function getProductsDateAvailable()
    {
        return $this->productsDateAvailable;
    }

    /**
     * Set productsWeight
     *
     * @param string $productsWeight
     *
     * @return Products
     */
    public function setProductsWeight($productsWeight)
    {
        $this->productsWeight = $productsWeight;

        return $this;
    }

    /**
     * Get productsWeight
     *
     * @return string
     */
    public function getProductsWeight()
    {
        return $this->productsWeight;
    }

    /**
     * Set productsStatus
     *
     * @param integer $productsStatus
     *
     * @return Products
     */
    public function setProductsStatus($productsStatus)
    {
        $this->productsStatus = $productsStatus;

        return $this;
    }

    /**
     * Get productsStatus
     *
     * @return integer
     */
    public function getProductsStatus()
    {
        return $this->productsStatus;
    }

    /**
     * Set productsTaxClassId
     *
     * @param integer $productsTaxClassId
     *
     * @return Products
     */
    public function setProductsTaxClassId($productsTaxClassId)
    {
        $this->productsTaxClassId = $productsTaxClassId;

        return $this;
    }

    /**
     * Get productsTaxClassId
     *
     * @return integer
     */
    public function getProductsTaxClassId()
    {
        return $this->productsTaxClassId;
    }

    /**
     * Set productTemplate
     *
     * @param string $productTemplate
     *
     * @return Products
     */
    public function setProductTemplate($productTemplate)
    {
        $this->productTemplate = $productTemplate;

        return $this;
    }

    /**
     * Get productTemplate
     *
     * @return string
     */
    public function getProductTemplate()
    {
        return $this->productTemplate;
    }

    /**
     * Set optionsTemplate
     *
     * @param string $optionsTemplate
     *
     * @return Products
     */
    public function setOptionsTemplate($optionsTemplate)
    {
        $this->optionsTemplate = $optionsTemplate;

        return $this;
    }

    /**
     * Get optionsTemplate
     *
     * @return string
     */
    public function getOptionsTemplate()
    {
        return $this->optionsTemplate;
    }

    /**
     * Set manufacturersId
     *
     * @param integer $manufacturersId
     *
     * @return Products
     */
    public function setManufacturersId($manufacturersId)
    {
        $this->manufacturersId = $manufacturersId;

        return $this;
    }

    /**
     * Get manufacturersId
     *
     * @return integer
     */
    public function getManufacturersId()
    {
        return $this->manufacturersId;
    }

    /**
     * Set productsManufacturersModel
     *
     * @param string $productsManufacturersModel
     *
     * @return Products
     */
    public function setProductsManufacturersModel($productsManufacturersModel)
    {
        $this->productsManufacturersModel = $productsManufacturersModel;

        return $this;
    }

    /**
     * Get productsManufacturersModel
     *
     * @return string
     */
    public function getProductsManufacturersModel()
    {
        return $this->productsManufacturersModel;
    }

    /**
     * Set productsOrdered
     *
     * @param integer $productsOrdered
     *
     * @return Products
     */
    public function setProductsOrdered($productsOrdered)
    {
        $this->productsOrdered = $productsOrdered;

        return $this;
    }

    /**
     * Get productsOrdered
     *
     * @return integer
     */
    public function getProductsOrdered()
    {
        return $this->productsOrdered;
    }

    /**
     * Set productsFsk18
     *
     * @param integer $productsFsk18
     *
     * @return Products
     */
    public function setProductsFsk18($productsFsk18)
    {
        $this->productsFsk18 = $productsFsk18;

        return $this;
    }

    /**
     * Get productsFsk18
     *
     * @return integer
     */
    public function getProductsFsk18()
    {
        return $this->productsFsk18;
    }

    /**
     * Set productsVpe
     *
     * @param integer $productsVpe
     *
     * @return Products
     */
    public function setProductsVpe($productsVpe)
    {
        $this->productsVpe = $productsVpe;

        return $this;
    }

    /**
     * Get productsVpe
     *
     * @return integer
     */
    public function getProductsVpe()
    {
        return $this->productsVpe;
    }

    /**
     * Set productsVpeStatus
     *
     * @param integer $productsVpeStatus
     *
     * @return Products
     */
    public function setProductsVpeStatus($productsVpeStatus)
    {
        $this->productsVpeStatus = $productsVpeStatus;

        return $this;
    }

    /**
     * Get productsVpeStatus
     *
     * @return integer
     */
    public function getProductsVpeStatus()
    {
        return $this->productsVpeStatus;
    }

    /**
     * Set productsVpeValue
     *
     * @param string $productsVpeValue
     *
     * @return Products
     */
    public function setProductsVpeValue($productsVpeValue)
    {
        $this->productsVpeValue = $productsVpeValue;

        return $this;
    }

    /**
     * Get productsVpeValue
     *
     * @return string
     */
    public function getProductsVpeValue()
    {
        return $this->productsVpeValue;
    }

    /**
     * Set productsStartpage
     *
     * @param integer $productsStartpage
     *
     * @return Products
     */
    public function setProductsStartpage($productsStartpage)
    {
        $this->productsStartpage = $productsStartpage;

        return $this;
    }

    /**
     * Get productsStartpage
     *
     * @return integer
     */
    public function getProductsStartpage()
    {
        return $this->productsStartpage;
    }

    /**
     * Set productsStartpageSort
     *
     * @param integer $productsStartpageSort
     *
     * @return Products
     */
    public function setProductsStartpageSort($productsStartpageSort)
    {
        $this->productsStartpageSort = $productsStartpageSort;

        return $this;
    }

    /**
     * Get productsStartpageSort
     *
     * @return integer
     */
    public function getProductsStartpageSort()
    {
        return $this->productsStartpageSort;
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

    public function getProductsAttributes()
    {
        return $this->productsAttributes;
    }

    public function hasProductsAttribute(ProductsAttribute $productsAttribute)
    {
        return $this->productsAttributes->contains($productAttribute);
    }

    public function addProductsAttribute(ProductsAttribute $productsAttribute)
    {
        $this->productsAttributes->add($productsAttribute);
    }

    public function removeProductsAttribute(ProductsAttribute $productsAttribute)
    {
        $this->productsAttributes->removeElement($productsAttribute);
    }

    public function clearProductsAttributes()
    {
        $this->productsAttributes->clear();
    }

    public function getProductsOptions()
    {
        return $this->productsOptions;
    }
}
