<?php

namespace ComunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CargaFichero
 * @ORM\Table(name="comun_carga_fichero")
 * @ORM\Entity
 */
class CargaFichero {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="fecha_carga", type="datetime", nullable=true)
     */
    private $fechaCarga;

    /**
     * @var string
     *
     * @ORM\Column(name="tabla", type="string", length=255, nullable=false)
     */
    private $tabla;
    /**
     * @var string
     *
     * @ORM\Column(name="fichero", type="string", length=255, nullable=false)
     */
    private $fichero;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="fichero_log", type="string", length=255, nullable=true)
     */
    private $ficheroLog;

    /**
     * @var EstadoCargaInicial|null
     *
     * @ORM\ManyToOne(targetEntity="EstadoCargaInicial",  cascade={"persist"})
     *   @ORM\JoinColumn(name="estado_carga_inicial_id", referencedColumnName="id")
     */
    private $estadoCargaInicial;

    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;
	/**
	 * @var
	 */
	private $proceso;

	/**
	 * @var
	 */
	private $orden;
	/**
	 * @var
	 */
	private $numDep;
	/**
	 * @var
	 */
	private $modulo;
	/**
	 * @var
	 */
	private $check;

	/**
	 * @return string
	 */
    public function __toString() {
        return $this->descripcion;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

	/**
	 * Set fechaCarga.
	 *
	 * @param \DateTime|null $fechaCarga
	 * @return \ComunBundle\Entity\CargaFichero
	 */
    public function setFechaCarga($fechaCarga = null) {
        $this->fechaCarga = $fechaCarga;

        return $this;
    }

    /**
     * Get fechaCarga.
     *
     * @return \DateTime|null
     */
    public function getFechaCarga() {
        return $this->fechaCarga;
    }

	/**
	 * Set tabla.
	 *
	 * @param string $tabla
	 * @return \ComunBundle\Entity\CargaFichero
	 */
    public function setTabla($tabla) {
        $this->tabla = $tabla;

        return $this;
    }

    /**
     * Get tabla.
     *
     * @return string
     */
    public function getTabla() {
        return $this->tabla;
    }

	/**
	 * Set descripcion.
	 *
	 * @param string $descripcion
	 * @return \ComunBundle\Entity\CargaFichero
	 */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

	/**
	 * Set proceso.
	 *
	 * @param string $proceso
	 * @return \ComunBundle\Entity\CargaFichero
	 */
    public function setProceso($proceso) {
        $this->proceso = $proceso;

        return $this;
    }

    /**
     * Get proceso.
     *
     * @return string
     */
    public function getProceso() {
        return $this->proceso;
    }

	/**
	 * Set orden.
	 *
	 * @param int|null $orden
	 * @return \ComunBundle\Entity\CargaFichero
	 */
    public function setOrden($orden = null) {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return int|null
     */
    public function getOrden() {
        return $this->orden;
    }

	/**
	 * Set numDep.
	 *
	 * @param int|null $numDep
	 * @return \ComunBundle\Entity\CargaFichero
	 */
    public function setNumDep($numDep = null) {
        $this->numDep = $numDep;

        return $this;
    }

    /**
     * Get numDep.
     *
     * @return int|null
     */
    public function getNumDep() {
        return $this->numDep;
    }

	/**
	 * Set estadoCargaInicial.
	 *
	 * @param \ComunBundle\Entity\EstadoCargaInicial|null $estadoCargaInicial
	 * @return \ComunBundle\Entity\CargaFichero
	 */
    public function setEstadoCargaInicial(EstadoCargaInicial $estadoCargaInicial = null) {
        $this->estadoCargaInicial = $estadoCargaInicial;

        return $this;
    }

    /**
     * Get estadoCargaInicial.
     *
     * @return \ComunBundle\Entity\EstadoCargaInicial|null
     */
    public function getEstadoCargaInicial() {
        return $this->estadoCargaInicial;
    }

	/**
	 * Set modulo.
	 *
	 * @param \ComunBundle\Entity\Modulo|null $modulo
	 * @return \ComunBundle\Entity\CargaFichero
	 */
    public function setModulo(Modulo $modulo = null) {
        $this->modulo = $modulo;

        return $this;
    }

    /**
     * Get modulo.
     *
     * @return \ComunBundle\Entity\Modulo|null
     */
    public function getModulo() {
        return $this->modulo;
    }

	/**
	 * @param $ficheroLog
	 * @return $this
	 */
    public function setFicheroLog($ficheroLog) {
        $this->ficheroLog = $ficheroLog;

        return $this;
    }

    /**
     * Get ficheroLog.
     *
     * @return string
     */
    public function getFicheroLog() {
        return $this->ficheroLog;
    }


    /**
     * Set check.
     *
     * @param bool $check
     *
     * @return $this
     */
    public function setCheck($check)
    {
        $this->check = $check;

        return $this;
    }

    /**
     * Get check.
     *
     * @return bool
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * Set fichero.
     *
     * @param string $fichero
     *
     * @return CargaFichero
     */
    public function setFichero($fichero)
    {
        $this->fichero = $fichero;

        return $this;
    }

    /**
     * Get fichero.
     *
     * @return string
     */
    public function getFichero()
    {
        return $this->fichero;
    }

    /**
     * Set usuario.
     *
     * @param \ComunBundle\Entity\Usuario|null $usuario
     *
     * @return CargaFichero
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
}
