<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ThemeRepository")
 */
class Theme
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="description")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Opus", mappedBy="theme")
     */
    private $opus;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->opus = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return Theme
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add opus
     *
     * @param \AppBundle\Entity\Opus $opus
     * @return Theme
     */
    public function addOpus(\AppBundle\Entity\Opus $opus)
    {
        $this->opus[] = $opus;

        return $this;
    }

    /**
     * Remove opus
     *
     * @param \AppBundle\Entity\Opus $opus
     */
    public function removeOpus(\AppBundle\Entity\Opus $opus)
    {
        $this->opus->removeElement($opus);
    }

    /**
     * Get opus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOpus()
    {
        return $this->opus;
    }
}
