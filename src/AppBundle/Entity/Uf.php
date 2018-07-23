<?php

/**
 * Description of Uf Unidad Funcional
 *
 * @author jluis
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Uf
 *
 * @ORM\Table(name="ccap_uf"
 *           ,uniqueConstraints={@ORM\UniqueConstraint(name="uf_uk", columns={"uf"})}
 *           )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UfRepository")
 */
class Uf {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=6, nullable=false)
     */
    private $uf;

    /**
     * @var Edificio|null
     *
     * @ORM\ManyToOne(targetEntity="Edificio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edificio_id", referencedColumnName="id")
     * })
     */
    private $edificio;

    /**
     * @var DA|null
     *
     * @ORM\ManyToOne(targetEntity="Da")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="da_id", referencedColumnName="id")
     * })
     */
    private $da;
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="oficial", type="string", length=10, nullable=true)
     */
    private $oficial;

    /**
     * @var string
     *
     * @ORM\Column(name="en_uso", type="string", length=1, nullable=true)
     */
    private $enuso;

    /**
     * @var string
     *
     * @ORM\Column(name="autogestion", type="string", length=1, nullable=true)
     */
    private $autogestion;

    /**
     * @var Date
     *
     * @ORM\Column(name="fecha_creacion", type="date", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @var Date
     *
     * @ORM\Column(name="fecha_baja", type="date", nullable=true)
     */
    private $fcBaja;
    

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
     * Set uf
     *
     * @param string $uf
     *
     * @return Uf
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Get uf
     *
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Uf
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set oficial
     *
     * @param string $oficial
     *
     * @return Uf
     */
    public function setOficial($oficial)
    {
        $this->oficial = $oficial;

        return $this;
    }

    /**
     * Get oficial
     *
     * @return string
     */
    public function getOficial()
    {
        return $this->oficial;
    }

    /**
     * Set enuso
     *
     * @param string $enuso
     *
     * @return Uf
     */
    public function setEnUso($enuso)
    {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso
     *
     * @return string
     */
    public function getEnUso()
    {
        return $this->enuso;
    }

    /**
     * Set autogestion
     *
     * @param string $autogestion
     *
     * @return Uf
     */
    public function setAutogestion($autogestion)
    {
        $this->autogestion = $autogestion;

        return $this;
    }

    /**
     * Get autogestion
     *
     * @return string
     */
    public function getAutogestion()
    {
        return $this->autogestion;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Uf
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fcBaja
     *
     * @param \DateTime $fcBaja
     *
     * @return Uf
     */
    public function setFcBaja($fcBaja)
    {
        $this->fcBaja = $fcBaja;

        return $this;
    }

    /**
     * Get fcBaja
     *
     * @return \DateTime
     */
    public function getFcBaja()
    {
        return $this->fcBaja;
    }

    /**
     * Set edificio
     *
     * @param \AppBundle\Entity\Edificio $edificio
     *
     * @return Uf
     */
    public function setEdificio(\AppBundle\Entity\Edificio $edificio = null)
    {
        $this->edificio = $edificio;

        return $this;
    }

    /**
     * Get edificio
     *
     * @return \AppBundle\Entity\Edificio
     */
    public function getEdificio()
    {
        return $this->edificio;
    }

    /**
     * Set da
     *
     * @param \AppBundle\Entity\Da $da
     *
     * @return Uf
     */
    public function setDa(\AppBundle\Entity\Da $da = null)
    {
        $this->da = $da;

        return $this;
    }

    /**
     * Get da
     *
     * @return \AppBundle\Entity\Da
     */
    public function getDa()
    {
        return $this->da;
    }
    
    public function __toString() {
        return $this->descripcion.' ('.$this->oficial.') ';
    }
}
