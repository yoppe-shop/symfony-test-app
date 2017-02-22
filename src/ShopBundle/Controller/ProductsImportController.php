<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductsAttribute;
use ShopBundle\Entity\ProductsOption;
use ShopBundle\Entity\ProductsOptionsValue;

class ProductsImportController extends Controller
{
    protected $csvFieldSep;
    /**
    * Fields without 'products_' for easier csv handling:
    */
    protected $productsFields = array();
    /**
    * Fields including products_ for storing in db:
    */
    protected $dbProductsFields = array();
    /**
    * Fields without 'products_' for easier csv handling:
    */
    protected $productsDescriptionFields = array();
    /**
    * Fields including products_ for storing in db:
    */
    protected $dbProductsDescriptionFields = array();
    protected $productsOptions = array();
    /**
    * Available languages in the shop:
    */
    protected $languages = array();

    protected $errors = array();

    public function __construct()
    {
        $this->csvFieldSep = ";";
    }

    /**
     * @Route("products_import")
     */
    public function indexAction()
    {
        //echo "Dir-Separator: " . DIR_SEP . "<br />";
        return $this->render('ShopBundle:ProductsImport:csv_import.html.twig');
    }

    /**
     * @Route("products_import/csv_import", name="products_csv_import")
     */
    public function csvImportAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

$csv = file_get_contents($this->get('kernel')->getRootDir() . DIR_SEP . ".." . DIR_SEP . "src" . DIR_SEP . "ShopBundle" . DIR_SEP . "Resources" . DIR_SEP . "testFiles" . DIR_SEP . "product_test1.csv");
        $em = $this->getDoctrine()->getManager('mysql');

        /**
        * Fetch the data arrays with the products´ data:
        */
        $products = $this->getCsvProductData($em, $csv);

        if (empty($this->errors)) {
            /**
            * Save products data:
            */
            $this->saveProducts($em, $products);
        }
        else {
            print_r($this->errors());
        }

        return $this->render('ShopBundle:ProductsImport:csv_import.html.twig', [
        ]);
    }

    protected function getCsvProductData($em, &$csv)
    {
        /**
        * Initialize error array
        */
        $this->errors = array();

        /**
        * Returns Arrays $product, $productsDescription, $productsImage, $attributes
        */
        $fieldArrays = array();

        /**
        * Replace '\r' by ''
        */
        $this->initializeCsvFile($csv);

        /**
        * Get an array of lines:
        */
        $lines = $this->getLines($csv);

        $titlesOfIndexes = $this->titlesOfIndexes($lines[0]);

        $numIndexes = count($titlesOfIndexes);

        /**
        * Create an array of the structure of the titles:
        */
        $structureArray = $this->createStructureArray($em, $titlesOfIndexes);

        if(!empty($this->errors))
        {
            return false;
        }
        unset($lines[0]);

        $products = array();
        $lineNo = 1;

        foreach($lines  as $line) {
            $products[]= $this->createProductsDataArray($em, $structureArray, $line, $numIndexes, $lineNo++);
        }

        return $products;
    }

    protected function initializeCsvFile(&$csv)
    {
        $csv = str_replace("\r", "", $csv);
    }

    protected function getLines(&$csv)
    {
        $lines = explode("\n", $csv);
        foreach ($lines as $key => $line) {
            if (empty($line)) {
                unset($lines[$key]);
            }
        } 
        return $lines;
    }

    protected function createProductsDataArray($em, &$fields, &$line, $numIndexes, $lineNo)
    {
        $values = explode($this->csvFieldSep, $line);

        if (count($values) != $numIndexes) {
            $this->errors[] = 'Zeile ' . $lineNo . ': Die Anzahl der Werte stimmt nicht mit der Schlüsselanzahl überein.';
            return false;
        }
        $product = array();

        foreach ($fields['product'] as $index => $field) {
            if ($values[$index] != '') {
                $product[$field['key']] = $values[$index];
            }
        }
        foreach ($fields['productsDescription'] as $index => $field) {
            if ($values[$index] != '') {
                $productsDescription[$field['key']][$field['langId']] = $values[$index]; 
            }
        }
        foreach ($fields['attributes'] as $index => $field) {
            if ($values[$index] != '') {
                $attributes[$field['optionsId']][$field['subKey']][$field['langId']] = $values[$index]; 
            }
        }
        return ['product' => $product, 'productsDescription' => $productsDescription, 'attributes' => $attributes];
    }

    protected function titlesOfIndexes($line0) {
        return explode($this->csvFieldSep, $line0);
    }

    protected function createStructureArray($em, $titleOfIndexes)
    {
        /**
        * Title of indexes:
        * ['model',
        * 'ean',
        * 'image', 
        * 'price',
        * 'weight',
        * 'status',
        * 'manufacturers_id',
        * 'manufacturers_model',
        * 'manufacturers_ean',
        * 'name.de',
        * 'description.de',
        * 'short_description.de',
        * 'a.farbe.de',
        * 'a.farbe.en',
        * 'a.material.de',
        * 'a.marke.de',
        * 'a.groesse.de',
        * 'a.gewicht.de',
        * 'a.einsatzart.de',
        * 'a.weitere_informationen.de',
        * 'a.gewicht_mit_verpackung.de']
        */

        /**
        * Three sub arrays for the structure array:
        */
        $product = array();
        $productsDescription = array();
        $attributes = array();

        /**
        * Following results as entity:
        */
        $this->productsFields = $this->productsFields($em);
        $this->productsDescriptionFields = $this->productsDescriptionFields($em);
        $this->productsOptions = $this->productsOptions($em);
        $this->languages = $this->languages($em);

        /**
        * Not the real products options name, but the option names for the CSV list 
        * ['farbe', 'material', ...]
        */
        $poCsvNames = $this->getPoCsvNames($em);

        /**
        * ['en' => 1, 'de' => 2]
        */
        $this->langIds = $this->getlangIds();

        foreach ($titleOfIndexes as $key => $fieldString) {
            if(substr($fieldString, 0, 3) == 'a+.') {
                $attributes[$key]= $this->createAttributesField($poCsvNames, $fieldString, '+');
            }
            elseif(substr($fieldString, 0, 2) == 'a.') {
                $attributes[$key]= $this->createAttributesField($poCsvNames, $fieldString, '');
            }
            elseif(substr($fieldString, 0, 3) == 'a-.') {
                $attributes[$key]= $this->createAttributesField($poCsvNames, $fieldString, '-');           
            }
            elseif(strpos($fieldString, '.') !== false) {
                $productsDescription[$key]= $this->createProductsDescriptionField($this->productsDescriptionFields, $fieldString);
            }
            else {
                $product[$key]=$this->createProductsField($this->productsFields, $fieldString);
            }
        }
        return ['product' => $product, 'productsDescription' => $productsDescription, 'attributes' => $attributes];
    }

    protected function productsFields($em)
    {
        $productsFields = $em->getClassMetadata('ShopBundle:Product')->getColumnNames();

        return array_map(function($el) {
            $newEl = str_replace('products_', '', $el);
            $this->dbProductsFields[$newEl] = $el;
            return $newEl;
        }, $productsFields);
    }

    protected function productsDescriptionFields($em)
    {
        $productsDescriptionFields = $em->getClassMetadata('ShopBundle:ProductsDescription')->getColumnNames();
        return array_map(function($el) {
            $newEl = str_replace('products_', '', $el);
            $this->dbProductsDescriptionFields[$newEl] = $el;
            return $newEl;
        }, $productsDescriptionFields);
    }

    protected function productsOptions($em)
    {
        $productsOptions = $em
            ->createQuery('
                SELECT po
                FROM ShopBundle:ProductsOption po 
                WHERE po.languageId = 2
                ORDER BY po.productsOptionsId ASC
            ')
            ->getResult();
        return $productsOptions;
    }

    protected function languages($em)
    {
        $languages = $em
            ->createQuery('
                SELECT l 
                FROM ShopBundle:Language l 
                ORDER BY l.languagesId ASC
            ')
            ->getResult();
        return $languages;
    }

    protected function createProductsField(&$productsFields, $key) {
        $product = array();
        if (in_array($key, $productsFields)) {
            $product = ['key' => $key, 'value' => ''];
        }
        else {
            $this->errors[]= 'Products: \'' . $key . '\' ist als Schlüssel nicht zugelassen.';
        }
        return $product;
    }

    protected function createProductsDescriptionField(&$productsDescriptionFields, $key)
    {
        $productsDescription = array();
        $langItem = $this->langItem($key);
        if (in_array($langItem['item'], $productsDescriptionFields)) {
            $productsDescription = ['key' => $langItem['item'], 'langId' => $langItem['lang'], 'value' => ''];
        }
        else {
            $this->errors[]= 'ProductsDescription: \'' . $langItem['item'] . '\' ist als Schlüssel nicht zugelassen.';
        }
        return $productsDescription;
    }

    protected function createAttributesField(&$poCsvNames, $key, $action)
    {
        /**
        * Initialize return array
        */
        $attributeArray = array();

        /**
        * Cut the beginning 'a+.' off:
        */
        $attribute = substr($key, 2 + strlen($action));

        /**
        * Make an array $langKeyItem = ['lang' => <langCode>, 'item' => <attributeName>]
        */
        $langKeyItem = $this->langKeyItem($attribute);
        if (isset($poCsvNames[$langKeyItem['item']])) {
            $attributeArray = ['key' => $langKeyItem['item'], 'optionsId' => $poCsvNames[$langKeyItem['item']], 'subKey' => $langKeyItem['subKey'], 'langId' => $langKeyItem['lang'], 'action' => $action, 'value' => ''];
        }
        else {
            $this->errors[]= 'Attribut: \'' . $langItem['item'] . '\' ist als Schlüssel nicht zugelassen.';
        }
        return $attributeArray;
    }

    protected function langItem($key)
    {
        $pos = strpos($key, '.');
        if ($pos !== false) {
            if (!isset($this->langIds[substr($key, $pos + 1)])) {
                $this->errors[] = 'Die Sprache mit dem Code \'' . substr($key, $pos + 1) . '\' gibt es nicht.';
            }
            else {
                return [ 'lang' => $this->langIds[substr($key, $pos + 1)], 'item' => substr($key, 0, $pos)];
            }
        }
        return [ 'lang' => '2', 'item' => $key];
    }

    protected function langKeyItem($key)
    {
        if (substr_count($key, '.') > 1) {
            $splitKey = explode('.', $key);
            $result = $this->langItem($splitKey[0] . '.' . $splitKey[2]);
            return array_merge($result, ['subKey' => $splitKey[1]]);
        }
        else {
            return array_merge($this->langItem($key), ['subKey' => '0']);
        }
    }

    protected function getPoCsvNames($em)
    {
        $poCsvNames = array();
        $result = $em
            ->createQuery('
                SELECT pocn.productsOptionsId, pocn.productsOptionsCsvName 
                FROM ShopBundle:ProductsOptionsCsvName pocn
            ')
            ->getResult();
        foreach($result as $pocn) {
            $poCsvNames[$pocn['productsOptionsCsvName']] = $pocn['productsOptionsId'];
        }

        return $poCsvNames;
    }

    protected function getLangIds()
    {
        $langIds = array();
        foreach ($this->languages as $language) {
            $langIds[$language->getCode()] = $language->getLanguagesId();
        }
        return $langIds;
    }

    protected function saveProducts($em, $products)
    {
        foreach ($products as $product) {
            $this->saveProduct($em, $product);
if(!isset($product['product']['id'])) $product['product']['id']='1';
            $this->saveAttributes($em, $product['product']['id'], $product['attributes']);
        }
    }

    protected function saveProduct($em, $productData)
    {
return true;
$debug = $this->get('debug');
        $product = $em
        ->getRepository('ShopBundle:Product')
        ->findOneByProductsModel($productData['product']['model']);

        $productsOption = $em
            ->createQuery('
                SELECT po
                FROM ShopBundle:ProductsOption po 
                WHERE po.productsOptionsId=2
                AND po.languageId=2 
            ')
            ->getResult();
        // $debug->pr($productsOption);

        $productsOptionsValue = $em
            ->getRepository('ShopBundle:ProductsOptionsValue')
            ->findOneByProductsOptionsValuesId(3);
        // $debug->pr($productsOptionsValue);

        $productsAttribute = new ProductsAttribute();
        $productsAttribute->setOptionsValuesPrice('0.00');
        $productsAttribute->setPricePrefix('0');
        $productsAttribute->setOptionsValuesWeight('0.0');
        $productsAttribute->setWeightPrefix('0');
        $productsAttribute->setAttributesVpeId('0.0');
        $productsAttribute->setAttributesVpeValue('0');
        // $productsAttribute->setProductsOption($productsOption[0]);
        $product->addProductsAttribute($productsAttribute);
        $productsAttribute->setProduct($product);
        $productsAttribute->setOptionsId($productsOption[0]->getProductsOptionsId());
        $productsAttribute->setOptionsValuesId($productsOptionsValue->getProductsOptionsValuesId());
        // $productsAttribute->setProductsOptionsValue($productsOptionsValue);
        //$em->persist($product);
        //$em->flush();
        $result = $em
            ->createQuery("
                SELECT p, pa, po, pov
                FROM ShopBundle:Product p 
                LEFT JOIN p.productsAttributes pa 
                LEFT JOIN ShopBundle:ProductsOption po WITH po.productsOptionsId=pa.optionsId AND po.languageId = '2'
                LEFT JOIN ShopBundle:ProductsOptionsValue pov WITH pov.productsOptionsValuesId=pa.optionsValuesId AND pov.languageId='2'
            ")
            ->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_OBJECT);
        $newArray = array();
        foreach($result as $object)
        {
            $newArray[get_class($object)][] = $object;
        }
        $debug->pr($newArray, 5);
       // $debug->pr($result[2], 4);
        exit;
    }

    protected function saveAttributes($em, $productsId, $attributes)
    {
        $this->createProductsOptionsValuesIds($em, $attributes);
    }

    protected function createProductsOptionsValuesIds($em, &$attributes)
    {
        $debug = $this->get('debug');
        if ($attributes) {
            $selectFrom = '
                SELECT pov.productsOptionsValuesId
                FROM ShopBundle:ProductsOptionsValue pov
            ';
            $toWrite = false;
            foreach ($attributes as $key0 => $attributeArray) {
                foreach ($attributeArray as $key1 => $attribute) {
                    $joins = array();
                    $where = '';
                    $i = 0;
                    foreach ($attribute as $langId => $item) {
                        if ($i == 0) {
                            $where = " WHERE pov.productsOptionsValuesName='" . $item . "' AND pov.languageId='" . $langId . "'";
                        }
                        else {
                            $joins[]= "INNER JOIN ShopBundle:ProductsOptionsValue pov" . $i . " WITH pov" . $i . ".productsOptionsValuesId=pov.productsOptionsValuesId AND pov" . $i . ".productsOptionsValuesName= '" . $item . "' AND pov" . $i . ".languageId = '" . $langId . "'";
                        }
                        $i++;
                    }
                    $query = $selectFrom . 
                             implode("\n", $joins) . 
                             $where
                    ;
                    $result = $em
                        ->createQuery($query)
                        ->getResult();
                    if (!empty($result)) {
                        $attributes[$key0][$key1] = intval($result[0]['productsOptionsValuesId']);
                    }
                    else {
                        $toWrite = true;
                    }
                }
            }
        $debug->pr($attributes);
            if ($toWrite) {
                $em->getConnection()->beginTransaction();
                $maxId = $this->getMaxId($em, 'ShopBundle:ProductsOptionsValue', 'productsOptionsValuesId');
                foreach ($attributes as $key0 => $attributeArray) {
                    foreach ($attributeArray as $key1 => $attribute) {
                        if (is_array($attribute)) {
                            foreach ($attribute as $langId => $item) {
                                $pov = new ProductsOptionsValue();
                                $pov->setProductsOptionsValuesId(++$maxId);
                                $pov->setLanguageId((int) $langId);
                                $pov->setProductsOptionsValuesName($item);
                                $em->persist($pov);
                            }
                            $attributes[$key0][$key1] = $maxId;
                        }
                    }
                }
                $em->flush();
                $em->getConnection()->commit();
            }
        }
echo "<hr>";
$debug->pr($attributes);
    }

    protected function getMaxId($em, $entity, $field)
    {
        $result = $em
            ->createQuery("
                SELECT MAX(e." . $field . ") AS maxId
                FROM " . $entity . " e
            ")
            ->getResult()
        ;
        $maxId = $result[0]['maxId'];
        echo "MaxId: " . $maxId . "<br>";
        return $maxId;
    }
}
