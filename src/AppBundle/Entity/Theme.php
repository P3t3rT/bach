<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Theme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ThemeRepository")
 */
class Theme
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Opus", mappedBy="theme")
     */
    protected $opus;

    public function __construct()
    {
        $this->opus = new ArrayCollection();
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
     * @param Opus $opus
     * @return Theme
     */
    public function addOpus(Opus $opus)
    {
        $this->opus[] = $opus;

        return $this;
    }

    /**
     * Remove opus
     *
     * @param Opus $opus
     */
    public function removeOpus(Opus $opus)
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
