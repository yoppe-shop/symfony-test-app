<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="languages", uniqueConstraints={@ORM\UniqueConstraint(name="idx_code", columns={"code"})})
 * @ORM\Entity
 */
class Language
{
    /**
     * @var integer
     *
     * @ORM\Column(name="languages_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $languagesId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=64, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="directory", type="string", length=32, nullable=true)
     */
    private $directory;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     */
    private $sortOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="language_charset", type="text", length=65535, nullable=false)
     */
    private $languageCharset;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="status_admin", type="integer", nullable=false)
     */
    private $statusAdmin = '1';



    /**
     * Get languagesId
     *
     * @return integer
     */
    public function getLanguagesId()
    {
        return $this->languagesId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Language
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Language
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set directory
     *
     * @param string $directory
     *
     * @return Language
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return Language
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set languageCharset
     *
     * @param string $languageCharset
     *
     * @return Language
     */
    public function setLanguageCharset($languageCharset)
    {
        $this->languageCharset = $languageCharset;

        return $this;
    }

    /**
     * Get languageCharset
     *
     * @return string
     */
    public function getLanguageCharset()
    {
        return $this->languageCharset;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Language
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set statusAdmin
     *
     * @param integer $statusAdmin
     *
     * @return Language
     */
    public function setStatusAdmin($statusAdmin)
    {
        $this->statusAdmin = $statusAdmin;

        return $this;
    }

    /**
     * Get statusAdmin
     *
     * @return integer
     */
    public function getStatusAdmin()
    {
        return $this->statusAdmin;
    }
}
