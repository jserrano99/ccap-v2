<?php

/**
 * Description of SincroLog
 *
 * @author jluis
 */

namespace ComunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SincroLog
 *
 * @ORM\Table(name="comun_sincro_log")
 * 
 * @ORM\Entity()
 */
class SincroLog {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="tabla", type="string",length=255,  nullable=false)
     */
    private $tabla;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_elemento", type="integer",  nullable=false)
     */
    private $idElemento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_proceso", type="datetime",  nullable=false)
     */
    private $fechaProceso;

    /**
     * @var string
     *
     * @ORM\Column(name="fichero_log", type="string", length= 255, nullable=true)
     */
    private $ficheroLog;

    /**
     * @var string
     *
     * @ORM\Column(name="script", type="string", length= 255, nullable=true)
     */
    private $script;

    /**
     * @var EstadoCargaInicial|null
     *
     * @ORM\ManyToOne(targetEntity="EstadoCargaInicial")
     *   @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
    private $estado;



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
     * Set tabla.
     *
     * @param string $tabla
     *
     * @return SincroLog
     */
    public function setTabla($tabla)
    {
        $this->tabla = $tabla;

        return $this;
    }

    /**
     * Get tabla.
     *
     * @return string
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Set idElemento.
     *
     * @param int $idElemento
     *
     * @return SincroLog
     */
    public function setIdElemento($idElemento)
    {
        $this->idElemento = $idElemento;

        return $this;
    }

    /**
     * Get idElemento.
     *
     * @return int
     */
    public function getIdElemento()
    {
        return $this->idElemento;
    }

    /**
     * Set fechaProceso.
     *
     * @param \DateTime $fechaProceso
     *
     * @return SincroLog
     */
    public function setFechaProceso($fechaProceso)
    {
        $this->fechaProceso = $fechaProceso;

        return $this;
    }

    /**
     * Get fechaProceso.
     *
     * @return \DateTime
     */
    public function getFechaProceso()
    {
        return $this->fechaProceso;
    }

    /**
     * Set ficheroLog.
     *
     * @param string|null $ficheroLog
     *
     * @return SincroLog
     */
    public function setFicheroLog($ficheroLog = null)
    {
        $this->ficheroLog = $ficheroLog;

        return $this;
    }

    /**
     * Get ficheroLog.
     *
     * @return string|null
     */
    public function getFicheroLog()
    {
        return $this->ficheroLog;
    }

    /**
     * Set script.
     *
     * @param string|null $script
     *
     * @return SincroLog
     */
    public function setScript($script = null)
    {
        $this->script = $script;

        return $this;
    }

    /**
     * Get script.
     *
     * @return string|null
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Set usuario.
     *
     * @param \ComunBundle\Entity\Usuario|null $usuario
     *
     * @return SincroLog
     */
    public function setUsuario(Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \ComunBundle\Entity\Usuario|null
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set estado.
     *
     * @param \ComunBundle\Entity\EstadoCargaInicial|null $estado
     *
     * @return SincroLog
     */
    public function setEstado(EstadoCargaInicial $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return \ComunBundle\Entity\EstadoCargaInicial|null
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
