<?php

namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package RAAFPAGE\AdBundle\Entity
 * @ORM\Table(name="sub_category")
 * @ORM\entity(repositoryClass="RAAFPAGE\AdBundle\Entity\SubCategoryRepository")
 */
class SubCategory
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
     * @ORM\OneToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="RAAFPAGE\AdBundle\Entity\SubCategory")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;

    /**
     * @ORM\Column(name="depth", type="integer", nullable=false)
     */
    private $depth;

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
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return SubCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param SubCategory $parent
     */
    public function setParent(SubCategory $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return integer
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param integer $depth
     * @return SubCategory
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

}
