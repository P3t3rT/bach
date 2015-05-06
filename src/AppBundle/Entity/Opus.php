<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\OpusRepository")
 * @ORM\Table(indexes={@ORM\Index(name="opus_idx", columns={"opus"})})
 */
class Opus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, name="opus")
     */
    private $opus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="title")
     */
    private $title;

    /**
     * @ORM\Column(type="date", nullable=true, name="date_first_performance")
     */
    private $dateFirstPerformance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="text_url")
     */
    private $textUrl;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Part", mappedBy="opus")
     */
    private $part;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Theme", inversedBy="opus")
     * @ORM\JoinColumn(name="theme_id", referencedColumnName="id")
     */
    private $theme;
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
     * Add part
     *
     * @param \AppBundle\Entity\Part $part
     * @return Opus
     */
    public function addPart(\AppBundle\Entity\Part $part)
    {
        $this->part[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param \AppBundle\Entity\Part $part
     */
    public function removePart(\AppBundle\Entity\Part $part)
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
     * Set theme
     *
     * @param \AppBundle\Entity\Theme $theme
     * @return Opus
     */
    public function setTheme(\AppBundle\Entity\Theme $theme = null)
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
}
