<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bajas
 *
 * @ORM\Table(name="gums_bajas"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\BajasRepository")
 */
class Bajas {

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
     * @ORM\Column(name="codigo", type="string", length=3, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=true)
     */
    private $descripcion;

    /**
     * @var ComunBundle\Entity\SincroLog|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\SincroLog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sincro_log_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $sincroLog;

    /**
     * @var string
     *
     * @ORM\Column(name="red", type="string", length=2, nullable=true)
     */
    private $red;

    /**
     * @var string
     *
     * @ORM\Column(name="h_cese", type="string", length=254, nullable=true)
     */
    private $hCese;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=false)
     */
    private $enuso;
    
    /**
     * @var string
     *
     * @ORM\Column(name="motivo_inem", type="string", length=2, nullable=true)
     */
    private $motivoInem;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="motivobajarptid", type="integer",  nullable=true)
     */
    private $motivoBajaRptId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mbarpt_codigo", type="string", length=10, nullable=true)
     */
    private $mbaRptCodigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mbarpt_descripcion", type="string", length=100, nullable=true)
     */
    private $mbaRptDescripcion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="aviso", type="string", length=1, nullable=false)
     */
    private $aviso;
    
    


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
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return Bajas
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return Bajas
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
     * Set red.
     *
     * @param string|null $red
     *
     * @return Bajas
     */
    public function setRed($red = null)
    {
        $this->red = $red;

        return $this;
    }

    /**
     * Get red.
     *
     * @return string|null
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * Set hCese.
     *
     * @param string|null $hCese
     *
     * @return Bajas
     */
    public function setHCese($hCese = null)
    {
        $this->hCese = $hCese;

        return $this;
    }

    /**
     * Get hCese.
     *
     * @return string|null
     */
    public function getHCese()
    {
        return $this->hCese;
    }

    /**
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return Bajas
     */
    public function setEnuso($enuso)
    {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso.
     *
     * @return string
     */
    public function getEnuso()
    {
        return $this->enuso;
    }

    /**
     * Set motivoInem.
     *
     * @param string|null $motivoInem
     *
     * @return Bajas
     */
    public function setMotivoInem($motivoInem = null)
    {
        $this->motivoInem = $motivoInem;

        return $this;
    }

    /**
     * Get motivoInem.
     *
     * @return string|null
     */
    public function getMotivoInem()
    {
        return $this->motivoInem;
    }

    /**
     * Set motivoBajaRptId.
     *
     * @param int|null $motivoBajaRptId
     *
     * @return Bajas
     */
    public function setMotivoBajaRptId($motivoBajaRptId = null)
    {
        $this->motivoBajaRptId = $motivoBajaRptId;

        return $this;
    }

    /**
     * Get motivoBajaRptId.
     *
     * @return int|null
     */
    public function getMotivoBajaRptId()
    {
        return $this->motivoBajaRptId;
    }

    /**
     * Set mbaRptCodigo.
     *
     * @param string|null $mbaRptCodigo
     *
     * @return Bajas
     */
    public function setMbaRptCodigo($mbaRptCodigo = null)
    {
        $this->mbaRptCodigo = $mbaRptCodigo;

        return $this;
    }

    /**
     * Get mbaRptCodigo.
     *
     * @return string|null
     */
    public function getMbaRptCodigo()
    {
        return $this->mbaRptCodigo;
    }

    /**
     * Set mbaRptDescripcion.
     *
     * @param string|null $mbaRptDescripcion
     *
     * @return Bajas
     */
    public function setMbaRptDescripcion($mbaRptDescripcion = null)
    {
        $this->mbaRptDescripcion = $mbaRptDescripcion;

        return $this;
    }

    /**
     * Get mbaRptDescripcion.
     *
     * @return string|null
     */
    public function getMbaRptDescripcion()
    {
        return $this->mbaRptDescripcion;
    }

    /**
     * Set aviso.
     *
     * @param string $aviso
     *
     * @return Bajas
     */
    public function setAviso($aviso)
    {
        $this->aviso = $aviso;

        return $this;
    }

    /**
     * Get aviso.
     *
     * @return string
     */
    public function getAviso()
    {
        return $this->aviso;
    }

    /**
     * Set sincroLog.
     *
     * @param \ComunBundle\Entity\SincroLog|null $sincroLog
     *
     * @return Bajas
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
    
    
    public function __toString() {
        return '('.$this->codigo.') '.$this->descripcion;
    }
}
