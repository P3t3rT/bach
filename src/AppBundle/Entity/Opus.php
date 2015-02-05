<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Opus
 *
 * @ORM\Table(indexes={@ORM\Index(name="opus_idx", columns={"opus"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\OpusRepository")
 */
class Opus
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
     * @var string
     *
     * @ORM\Column(name="opus", type="string", length=10)
     */
    private $opus;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_first_performance", type="date", nullable=true)
     */
    private $dateFirstPerformance;

    /**
     * @var string
     *
     * @ORM\Column(name="text_url", type="string", length=255, nullable=true)
     */
    private $textUrl;

    /**
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="opus")
     * @ORM\JoinColumn(name="theme_id", referencedColumnName="id")
     */
    protected $theme;

    /**
     * @ORM\OneToMany(targetEntity="Part", mappedBy="opus")
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
     * Set opus
     *
     * @param string $opus
     * @return Opus
     */
    public function setOpus($opus)
    {
        $this->opus = $opus;

        return $this;
    }

    /**
     * Get opus
     *
     * @return string
     */
    public function getOpus()
    {
        return $this->opus;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Opus
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
     * Set dateFirstPerformance
     *
     * @param \DateTime $dateFirstPerformance
     * @return Opus
     */
    public function setDateFirstPerformance($dateFirstPerformance)
    {
        $this->dateFirstPerformance = $dateFirstPerformance;

        return $this;
    }

    /**
     * Get dateFirstPerformance
     *
     * @return \DateTime
     */
    public function getDateFirstPerformance()
    {
        return $this->dateFirstPerformance;
    }

    /**
     * Set textUrl
     *
     * @param string $textUrl
     * @return Opus
     */
    public function setTextUrl($textUrl)
    {
        $this->textUrl = $textUrl;

        return $this;
    }

    /**
     * Get textUrl
     *
     * @return string
     */
    public function getTextUrl()
    {
        return $this->textUrl;
    }

    /**
     * Set theme
     *
     * @param \AppBundle\Entity\Theme $theme
     * @return Opus
     */
    public function setTheme(Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \AppBundle\Entity\Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @return mixed
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * @param mixed $part
     */
    public function setPart($part)
    {
        $this->part = $part;
    }

    /**
     * Add part
     *
     * @param \AppBundle\Entity\Part $part
     * @return Opus
     */
    public function addPart(Part $part)
    {
        $this->part[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param \AppBundle\Entity\Part $part
     */
    public function removePart(Part $part)
    {
        $this->part->removeElement($part);
    }
}
