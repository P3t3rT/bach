<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PartRepository")
 */
class Part
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true, name="partnumber")
     */
    private $partnumber;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, name="parttype")
     */
    private $parttype;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="title")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="strength")
     */
    private $strength;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Audiotrack", mappedBy="part")
     */
    private $part;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Opus", inversedBy="part")
     * @ORM\JoinColumn(name="opus_id", referencedColumnName="id")
     */
    private $opus;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->part = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set partnumber
     *
     * @param integer $partnumber
     * @return Part
     */
    public function setPartnumber($partnumber)
    {
        $this->partnumber = $partnumber;

        return $this;
    }

    /**
     * Get partnumber
     *
     * @return integer 
     */
    public function getPartnumber()
    {
        return $this->partnumber;
    }

    /**
     * Set parttype
     *
     * @param string $parttype
     * @return Part
     */
    public function setParttype($parttype)
    {
        $this->parttype = $parttype;

        return $this;
    }

    /**
     * Get parttype
     *
     * @return string 
     */
    public function getParttype()
    {
        return $this->parttype;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Part
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set strength
     *
     * @param string $strength
     * @return Part
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength
     *
     * @return string 
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Add part
     *
     * @param \AppBundle\Entity\Audiotrack $part
     * @return Part
     */
    public function addPart(\AppBundle\Entity\Audiotrack $part)
    {
        $this->part[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param \AppBundle\Entity\Audiotrack $part
     */
    public function removePart(\AppBundle\Entity\Audiotrack $part)
    {
        $this->part->removeElement($part);
    }

    /**
     * Get part
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Set opus
     *
     * @param \AppBundle\Entity\Opus $opus
     * @return Part
     */
    public function setOpus(\AppBundle\Entity\Opus $opus = null)
    {
        $this->opus = $opus;

        return $this;
    }

    /**
     * Get opus
     *
     * @return \AppBundle\Entity\Opus 
     */
    public function getOpus()
    {
        return $this->opus;
    }
}
