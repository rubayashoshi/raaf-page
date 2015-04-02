<?php
namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RAAFPAGE\AdBundle\Entity\AdLink
 *
 * @ORM\Table(name="ad_link")
 * @ORM\Entity()
 */
class AdLink
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="images")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     **/
    private $property;

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
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param mixed $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }
}
