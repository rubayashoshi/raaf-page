<?php
namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RAAFPAGE\AdBundle\Entity\AdType
 *
 * @ORM\Table(name="ad_type")
 * @ORM\Entity()
 */
class AdType
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @ORM\ManyToMany(targetEntity="Property", mappedBy="AdTypes")
     **/
    private $properties;

    public function __construct() {
        $this->properties = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param mixed $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }
}