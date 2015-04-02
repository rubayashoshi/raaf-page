<?php

namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RAAFPAGE\UserBundle\Entity\Property
 *
 * @ORM\Table(name="properties")
 * @ORM\Entity()
 */
class Property
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
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $description;

    /**
     * @var Date $birthday
     *
     * @ORM\Column(name="date_available", type="date", nullable=false)
     */
    private $dateAvailable;

    /**
     * @var float $rent
     *
     * @ORM\Column(name="rent", type="decimal", precision=2)
     */
    private $rent;

    /**
     * @var int
     *
     * @ORM\Column(name="available_to_couple", type="boolean", nullable=false)
     */
    private $availableToCouple = false;

    /**
     * @var int
     *
     * @ORM\Column(name="is_agent", type="boolean", nullable=false)
     */
    private $agent = false;

    /**
     * @var int
     *
     * @ORM\Column(name="contact_phone", type="boolean", nullable=false)
     */
    private $contactPhone = false;

    /**
     * @var int
     *
     * @ORM\Column(name="contact_email", type="boolean", nullable=false)
     */
    private $contactEmail = true;

    /**
     * @var string
     * @ORM\Column(type="string", name="contact_name", length=255, nullable=false)
     */
    private $contactName;

    /**
     * @ORM\OneToMany(targetEntity="\RAAFPAGE\AdBundle\Entity\Image", mappedBy="property")
     **/
    private $images;


    /**
     * @ORM\ManyToMany(targetEntity="\RAAFPAGE\AdBundle\Entity\AdType", inversedBy="properties")
     * @ORM\JoinTable(name="ad_type_property_rel")
     **/
    private $adTypes;

    /**
     * @ORM\ManyToOne(targetEntity="\RAAFPAGE\AdBundle\Entity\PropertyType")
     * @ORM\JoinColumn(name="property_type_id", referencedColumnName="id")
     **/
    private $propertyType;

    /**
     * @ORM\OneToMany(targetEntity="\RAAFPAGE\AdBundle\Entity\AdLink", mappedBy="property")
     **/
    private $links;

    /**
     * @var enum
     * @ORM\Column(name="rent_period", type="string", columnDefinition="enum('monthly', 'weekly')")
     */
    private $rentPeriod;

    public function __construct() {
        $this->adTypes = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->links = new ArrayCollection();
    }

    /**
     * @param Image $image
     * @return Property
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * @param Image $image
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * @return ArrayCollection|Image[]
     */
    public function getImage()
    {
        return $this->images;
    }


    /**
     * @param Link $link
     * @return Property
     */
    public function addLink(Link $link)
    {
        $this->links[] = $link;

        return $this;
    }

    /**
     * @param AdLink $link
     */
    public function removeLink(AdLink $link)
    {
        $this->links->removeElement($link);
    }

    /**
     * @return ArrayCollection|Link[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param AdType $adType
     * @return Property
     */
    public function addAdType(AdType $adType)
    {
        if (!$this->adTypes->contains($adType)) {
            $this->adTypes->add($adType);
        }

        return $this;
    }

    /**
     * @param AdType $adType
     */
    public function removeAdType(AdType $adType)
    {
        $this->adTypes->removeElement($adType);
    }

    /**
     * @return ArrayCollection|AdType[]
     */
    public function getAdTypes()
    {
        return $this->adTypes;
    }

    /**
     * @return mixed
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param mixed $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return mixed
     */
    public function getAvailableToCouple()
    {
        return $this->availableToCouple;
    }

    /**
     * @param mixed $availableToCouple
     */
    public function setAvailableToCouple($availableToCouple)
    {
        $this->availableToCouple = $availableToCouple;
    }

    /**
     * @return int
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param int $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
    }

    /**
     * @return int
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param int $contactPhone
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    }

    /**
     * @return Date
     */
    public function getDateAvailable()
    {
        return $this->dateAvailable;
    }

    /**
     * @param Date $dateAvailable
     */
    public function setDateAvailable($dateAvailable)
    {
        $this->dateAvailable = $dateAvailable;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
    public function getPropertyType()
    {
        return $this->propertyType;
    }

    /**
     * @param mixed $propertyType
     */
    public function setPropertyType($propertyType)
    {
        $this->propertyType = $propertyType;
    }

    /**
     * @return float
     */
    public function getRent()
    {
        return $this->rent;
    }

    /**
     * @param float $rent
     */
    public function setRent($rent)
    {
        $this->rent = $rent;
    }

    /**
     * @return \enum
     */
    public function getRentPeriod()
    {
        return $this->rentPeriod;
    }

    /**
     * @param \enum $rentPeriod
     */
    public function setRentPeriod($rentPeriod)
    {
        $this->rentPeriod = $rentPeriod;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
