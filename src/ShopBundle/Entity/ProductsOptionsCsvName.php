<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsOptionsCsvName
 *
 * @ORM\Table(name="products_options_csv_names")
 * @ORM\Entity
 */
class ProductsOptionsCsvName
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
     * @var string
     *
     * @ORM\Column(name="products_options_csv_name", type="string", length=255, nullable=false)
     */
    private $productsOptionsCsvName = '';

    /**
     * Set productsOptionsCsvName
     *
     * @param string $productsOptionsCsvName
     *
     * @return ProductsOptionsCsv
     */
    public function setProductsOptionsCsvName($productsOptionsCsvName)
    {
        $this->productsOptionsCsvName = $productsOptionsCsvName;

        return $this;
    }

    /**
     * Get productsOptionsCsvName
     *
     * @return string
     */
    public function getProductsOptionsCsvName()
    {
        return $this->productsOptionsCsvName;
    }

    /**
     * Set productsOptionsId
     *
     * @param integer $productsOptionsId
     *
     * @return ProductsOption
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
}
