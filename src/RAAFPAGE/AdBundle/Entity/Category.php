<?php

namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package RAAFPAGE\AdBundle\Entity
 * @ORM\Table(name="category")
 * @ORM\entity(repositoryClass="RAAFPAGE\AdBundle\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $display;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $slug;

    /**
     * @ORM\Column(name="display_priority", type="integer", length=10, nullable=false, options={"default": "0"})
     */
    protected $displayPriority;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @param string $display
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


    /**
     * @return integer
     */
    public function getDisplayPriority()
    {
        return $this->displayPriority;
    }

    /**
     * @param integer $displayPriority
     * @return Category
     */
    public function setDisplayPriority($displayPriority)
    {
        $this->displayPriority = $displayPriority;

        return $this;
    }
}
