<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Part
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PartRepository")
 */
class Part
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="partnumber", type="integer")
     */
    private $partnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="parttype", type="string", length=45, nullable=true)
     */
    private $parttype;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="strength", type="string", length=255, nullable=true)
     */
    private $strength;

    /**
     * @ORM\ManyToOne(targetEntity="Opus", inversedBy="part")
     * @ORM\JoinColumn(name="opus_id", referencedColumnName="id")
     */
    protected $opus;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Audiotrack", mappedBy="part")
     */
    protected $part;

    public function __construct()
    {
        $this->part = new ArrayCollection();
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
     * @return mixed
     */
    public function getOpus()
    {
        return $this->opus;
    }

    /**
     * @param mixed $opus
     */
    public function setOpus($opus)
    {
        $this->opus = $opus;
    }

    /**
     * Add part
     *
     * @param Audiotrack $part
     * @return Part
     */
    public function addPart(Audiotrack $part)
    {
        $this->part[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param Audiotrack $part
     */
    public function removePart(Audiotrack $part)
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
}
