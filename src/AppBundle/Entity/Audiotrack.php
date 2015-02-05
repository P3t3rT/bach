<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Audiotrack
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AudiotrackRepository")
 */
class Audiotrack
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
     * @ORM\Column(name="conductor", type="string", length=128)
     */
    private $conductor;

    /**
     * @var string
     *
     * @ORM\Column(name="ensemble", type="string", length=255)
     */
    private $ensemble;

    /**
     * @var string
     *
     * @ORM\Column(name="performer", type="string", length=255, nullable=true)
     */
    private $performer;

    /**
     * @var string
     *
     * @ORM\Column(name="recording_year", type="string", length=4)
     */
    private $recordingYear;

    /**
     * @var string
     *
     * @ORM\Column(name="album", type="string", length=128)
     */
    private $album;

    /**
     * @var string
     *
     * @ORM\Column(name="track", type="string", length=4)
     */
    private $track;

    /**
     * @var string
     *
     * @ORM\Column(name="track_type", type="string", length=128)
     */
    private $trackType;

    /**
     * @ORM\ManyToOne(targetEntity="Part", inversedBy="audiotrack")
     * @ORM\JoinColumn(name="part_id", referencedColumnName="id")
     */
    protected $part;

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
     * Set conductor
     *
     * @param string $conductor
     * @return Audiotrack
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
     * Set ensemble
     *
     * @param string $ensemble
     * @return Audiotrack
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
     * @return Audiotrack
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
     * Set recordingYear
     *
     * @param string $recordingYear
     * @return Audiotrack
     */
    public function setRecordingYear($recordingYear)
    {
        $this->recordingYear = $recordingYear;

        return $this;
    }

    /**
     * Get recordingYear
     *
     * @return string
     */
    public function getRecordingYear()
    {
        return $this->recordingYear;
    }

    /**
     * Set album
     *
     * @param string $album
     * @return Audiotrack
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
     * @param string $track
     * @return Audiotrack
     */
    public function setTrack($track)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track
     *
     * @return string
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set trackType
     *
     * @param string $trackType
     * @return Audiotrack
     */
    public function setTrackType($trackType)
    {
        $this->trackType = $trackType;

        return $this;
    }

    /**
     * Get trackType
     *
     * @return string
     */
    public function getTrackType()
    {
        return $this->trackType;
    }

    /**
     * Set part
     *
     * @param Part $part
     * @return Audiotrack
     */
    public function setPart(Part $part = null)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return \AppBundle\Entity\Part
     */
    public function getPart()
    {
        return $this->part;
    }
}
