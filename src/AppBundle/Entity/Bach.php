<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BachRepository")
 * @ORM\Table(name="Bach")
 */
class Bach
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="title")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="artist")
     */
    private $artist;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="conductor")
     */
    private $conductor;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, name="opus")
     */
    private $opus;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, name="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=128, nullable=true, name="album")
     */
    private $album;

    /**
     * @ORM\Column(type="integer", nullable=true, name="track")
     */
    private $track;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="ensemble")
     */
    private $ensemble;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="performer")
     */
    private $performer;

    /**
     * @ORM\Column(type="integer", nullable=true, name="part")
     */
    private $part;

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
     * Set title
     *
     * @param string $title
     * @return Bach
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
     * Set artist
     *
     * @param string $artist
     * @return Bach
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return string 
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set conductor
     *
     * @param string $conductor
     * @return Bach
     */
    public function setConductor($conductor)
    {
        $this->conductor = $conductor;

        return $this;
    }

    /**
     * Get conductor
     *
     * @return string 
     */
    public function getConductor()
    {
        return $this->conductor;
    }

    /**
     * Set opus
     *
     * @param string $opus
     * @return Bach
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
     * Set date
     *
     * @param string $date
     * @return Bach
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set album
     *
     * @param string $album
     * @return Bach
     */
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return string 
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set track
     *
     * @param integer $track
     * @return Bach
     */
    public function setTrack($track)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track
     *
     * @return integer 
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set ensemble
     *
     * @param string $ensemble
     * @return Bach
     */
    public function setEnsemble($ensemble)
    {
        $this->ensemble = $ensemble;

        return $this;
    }

    /**
     * Get ensemble
     *
     * @return string 
     */
    public function getEnsemble()
    {
        return $this->ensemble;
    }

    /**
     * Set performer
     *
     * @param string $performer
     * @return Bach
     */
    public function setPerformer($performer)
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * Get performer
     *
     * @return string 
     */
    public function getPerformer()
    {
        return $this->performer;
    }

    /**
     * Set part
     *
     * @param integer $part
     * @return Bach
     */
    public function setPart($part)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return integer 
     */
    public function getPart()
    {
        return $this->part;
    }
}
