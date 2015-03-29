<?php
namespace RAAFPAGE\FileManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Topic
 * @package RAAFPAGE\FileManagerBundle\Entity
 * @ORM\Entity
 */
class Topic
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string", length=255, nullable=true)*/
    private $title;

    /** @ORM\Column(type="text", nullable=true)*/
    private $description;

    /** @ORM\Column(type="integer", nullable=true)*/
    private $parent;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Topic
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Topic
     */
    public function setParent(Topic $topic)
    {
        $this->parent = $topic;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
