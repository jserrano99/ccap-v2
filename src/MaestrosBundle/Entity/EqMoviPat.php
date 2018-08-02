<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqMoviPat
 *
 * @ORM\Table(name="gums_eq_movipat" 
 *           )
 * @ORM\Entity
 */
class EqMoviPat {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var ComunBundle\Entity\Edificio|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\Edificio")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edificio_id", referencedColumnName="id",)
     * })
     */
    private $edificio;

    /**
     * @var codigoLoc
     *
     * @ORM\Column(name="codigo_loc", type="string", length=3, nullable=false)
     */
    private $codigoLoc;

    /**
     * @var MoviPat|null
     *
     * @ORM\ManyToOne(targetEntity="MoviPat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="movipat_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $moviPat;

    /**
     * @var enUso
     * 
     * @ORM\Column(name="enuso",type="string",length=1,nullable=false)
     */
    private $enUso;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codigoLoc.
     *
     * @param string $codigoLoc
     *
     * @return EqMoviPat
     */
    public function setCodigoLoc($codigoLoc)
    {
        $this->codigoLoc = $codigoLoc;

        return $this;
    }

    /**
     * Get codigoLoc.
     *
     * @return string
     */
    public function getCodigoLoc()
    {
        return $this->codigoLoc;
    }

    /**
     * Set enUso.
     *
     * @param string $enUso
     *
     * @return EqMoviPat
     */
    public function setEnUso($enUso)
    {
        $this->enUso = $enUso;

        return $this;
    }

    /**
     * Get enUso.
     *
     * @return string
     */
    public function getEnUso()
    {
        return $this->enUso;
    }

    /**
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return EqMoviPat
     */
    public function setEdificio(\ComunBundle\Entity\Edificio $edificio = null)
    {
        $this->edificio = $edificio;

        return $this;
    }

    /**
     * Get edificio.
     *
     * @return \ComunBundle\Entity\Edificio|null
     */
    public function getEdificio()
    {
        return $this->edificio;
    }

    /**
     * Set moviPat.
     *
     * @param \MaestrosBundle\Entity\MoviPat|null $moviPat
     *
     * @return EqMoviPat
     */
    public function setMoviPat(\MaestrosBundle\Entity\MoviPat $moviPat = null)
    {
        $this->moviPat = $moviPat;

        return $this;
    }

    /**
     * Get moviPat.
     *
     * @return \MaestrosBundle\Entity\MoviPat|null
     */
    public function getMoviPat()
    {
        return $this->moviPat;
    }
}
