<?php

/**
 * Description of Pa Unidad Funcional
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ComunBundle\Entity\Edificio;
use ComunBundle\Entity\Da;

/**
 * Pa
 *
 * @ORM\Table(name="ccap_pa" 
 *           ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_pa", columns={"pa"})}
 *            )
 * @ORM\Entity(repositoryClass="CostesBundle\Repository\PaRepository")
 */
class Pa {

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
     * @ORM\Column(name="pa", type="string", length=6, nullable=false)
     */
    private $pa;

    /**
     * @var ComunBundle\Entity\Edificio|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\Edificio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edificio_id", referencedColumnName="id")
     * })
     */
    private $edificio;
 
    /**
     * @var ComunBundle\Entity\DA|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\Da")
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
     * @ORM\Column(name="enuso", type="string", length=1, nullable=true)
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
     * @var ComunBundle\Entity\SincroLog|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\SincroLog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sincro_log_id", referencedColumnName="id")
     * })
     */

    private $sincroLog;


    public function __toString() {
        return $this->descripcion.' ('.$this->oficial.') ';
    }


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
     * Set pa.
     *
     * @param string $pa
     *
     * @return Pa
     */
    public function setPa($pa)
    {
        $this->pa = $pa;

        return $this;
    }

    /**
     * Get pa.
     *
     * @return string
     */
    public function getPa()
    {
        return $this->pa;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return Pa
     */
    public function setDescripcion($descripcion = null)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string|null
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set oficial.
     *
     * @param string|null $oficial
     *
     * @return Pa
     */
    public function setOficial($oficial = null)
    {
        $this->oficial = $oficial;

        return $this;
    }

    /**
     * Get oficial.
     *
     * @return string|null
     */
    public function getOficial()
    {
        return $this->oficial;
    }

    /**
     * Set enuso.
     *
     * @param string|null $enuso
     *
     * @return Pa
     */
    public function setEnuso($enuso = null)
    {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso.
     *
     * @return string|null
     */
    public function getEnuso()
    {
        return $this->enuso;
    }

    /**
     * Set autogestion.
     *
     * @param string|null $autogestion
     *
     * @return Pa
     */
    public function setAutogestion($autogestion = null)
    {
        $this->autogestion = $autogestion;

        return $this;
    }

    /**
     * Get autogestion.
     *
     * @return string|null
     */
    public function getAutogestion()
    {
        return $this->autogestion;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime|null $fechaCreacion
     *
     * @return Pa
     */
    public function setFechaCreacion($fechaCreacion = null)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime|null
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fcBaja.
     *
     * @param \DateTime|null $fcBaja
     *
     * @return Pa
     */
    public function setFcBaja($fcBaja = null)
    {
        $this->fcBaja = $fcBaja;

        return $this;
    }

    /**
     * Get fcBaja.
     *
     * @return \DateTime|null
     */
    public function getFcBaja()
    {
        return $this->fcBaja;
    }

    /**
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return Pa
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
     * Set da.
     *
     * @param \ComunBundle\Entity\Da|null $da
     *
     * @return Pa
     */
    public function setDa(\ComunBundle\Entity\Da $da = null)
    {
        $this->da = $da;

        return $this;
    }

    /**
     * Get da.
     *
     * @return \ComunBundle\Entity\Da|null
     */
    public function getDa()
    {
        return $this->da;
    }

    /**
     * Set sincroLog.
     *
     * @param \ComunBundle\Entity\SincroLog|null $sincroLog
     *
     * @return Pa
     */
    public function setSincroLog(\ComunBundle\Entity\SincroLog $sincroLog = null)
    {
        $this->sincroLog = $sincroLog;

        return $this;
    }

    /**
     * Get sincroLog.
     *
     * @return \ComunBundle\Entity\SincroLog|null
     */
    public function getSincroLog()
    {
        return $this->sincroLog;
    }
}
