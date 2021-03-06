<?php

/**
 * Description of Plaza
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use ComunBundle\Entity\SincroLog;
use Doctrine\ORM\Mapping as ORM;
use MaestrosBundle\Entity\CatFp;
use MaestrosBundle\Entity\CatGen;
use MaestrosBundle\Entity\Modalidad;
use MaestrosBundle\Entity\Turno;

/**
 * Plaza
 *
 * @ORM\Table(name="ccap_plazas",
 *            uniqueConstraints={@ORM\UniqueConstraint(name="cias_uk", columns={"cias"})},
 *            indexes={@ORM\Index(name="idx001", columns={"uf_id"}),
 *                     @ORM\Index(name="idx002", columns={"pa_id"}),
 *                     @ORM\Index(name="idx003", columns={"catgen_id"}),
 *                     @ORM\Index(name="idx004", columns={"catfp_id"}),
 *                     @ORM\Index(name="idx005", columns={"moa_id"})}
 *           )
 * @ORM\Entity(repositoryClass="CostesBundle\Repository\PlazaRepository")
 */
class Plaza
{

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
	 * @ORM\Column(name="cias", type="string", length=11, nullable=true)
	 */
	private $cias;

	/**
	 * @var Uf|null
	 *
	 * @ORM\ManyToOne(targetEntity="Uf")
	 * @ORM\JoinColumn(name="uf_id", referencedColumnName="id")
	 */
	private $uf;

	/**
	 * @var Pa|null
	 *
	 * @ORM\ManyToOne(targetEntity="Pa")
	 * @ORM\JoinColumn(name="pa_id", referencedColumnName="id")
	 */
	private $pa;

	/**
	 * @var \MaestrosBundle\Entity\CatGen|null
	 *
	 * @ORM\ManyToOne(targetEntity="MaestrosBundle\Entity\CatGen")
	 * @ORM\JoinColumn(name="catgen_id", referencedColumnName="id")
	 */
	private $catGen;

	/**
	 * @var \MaestrosBundle\Entity\Modalidad|null
	 *
	 * @ORM\ManyToOne(targetEntity="MaestrosBundle\Entity\Modalidad")
	 * @ORM\JoinColumn(name="moa_id", referencedColumnName="id")
	 */
	private $modalidad;

	/**
	 * @var \MaestrosBundle\Entity\CatFp|null
	 *
	 * @ORM\ManyToOne(targetEntity="MaestrosBundle\Entity\CatFp")
	 * @ORM\JoinColumn(name="catfp_id", referencedColumnName="id")
	 */
	private $catFp;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="plantilla", type="string", length=1, nullable=true)
	 */
	private $plantilla;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="turno", type="string", length=1, nullable=true)
	 */
	private $turno;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="horNormal", type="string", length=1, nullable=true)
	 */
	private $horNormal;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ficticia", type="string", length=1, nullable=true)
	 */
	private $ficticia;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="refuerzo", type="string", length=1, nullable=true)
	 */
	private $refuerzo;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="f_amortiza", type="date", nullable=true)
	 */
	private $fAmortiza;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="f_creacion", type="date", nullable=true)
	 */
	private $fCreacion;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
	 */
	private $observaciones;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cupequi", type="string", length=1, nullable=true)
	 */
	private $cupequi;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="colaboradora", type="string", length=1, nullable=true)
	 */
	private $colaboradora;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="orden", type="integer", nullable=true)
	 */
	private $orden;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="amortizada", type="string", length=1, nullable=true)
	 */
	private $amortizada;

	/**
	 * @var \ComunBundle\Entity\SincroLog|null
	 *
	 * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\SincroLog")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="sincro_log_id", referencedColumnName="id")
	 * })
	 */
	private $sincroLog;

	/**
	 * @var Ceco| null
	 *
	 * @ORM\ManyToOne(targetEntity="Ceco")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="ceco_actual_id", referencedColumnName="id")
	 * })
	 */
	private $cecoActual;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="h1ini", type="time", nullable=true)
	 */
	private $h1ini;
	/**
	 * @var \Datetime
	 *
	 * @ORM\Column(name="h1fin", type="time", nullable=true)
	 */
	private $h1fin;
	/**
	 * @var \Datetime
	 *
	 * @ORM\Column(name="h2ini", type="time", nullable=true)
	 */
	private $h2ini;
	/**
	 * @var \Datetime
	 *
	 * @ORM\Column(name="h2fin", type="time", nullable=true)
	 */
	private $h2fin;

	/**
	 * @var \MaestrosBundle\Entity\Turno|null
	 *
	 * @ORM\ManyToOne(targetEntity="MaestrosBundle\Entity\Turno")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="turno_id", referencedColumnName="id")
	 * })
	 */
	private $turnof;

	/**
	 * @var \CostesBundle\Entity\UnidadOrganizativa
	 *
	 * @ORM\ManyToOne(targetEntity="CostesBundle\Entity\UnidadOrganizativa")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="unidad_organizativa_id", referencedColumnName="id")
	 * })
	 */
	private $unidadOrganizativa;


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->cias;
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
	 * Set cias.
	 *
	 * @param string|null $cias
	 *
	 * @return Plaza
	 */
	public function setCias($cias = null)
	{
		$this->cias = $cias;

		return $this;
	}

	/**
	 * Get cias.
	 *
	 * @return string|null
	 */
	public function getCias()
	{
		return $this->cias;
	}

	/**
	 * Set plantilla.
	 *
	 * @param string|null $plantilla
	 *
	 * @return Plaza
	 */
	public function setPlantilla($plantilla = null)
	{
		$this->plantilla = $plantilla;

		return $this;
	}

	/**
	 * Get plantilla.
	 *
	 * @return string|null
	 */
	public function getPlantilla()
	{
		return $this->plantilla;
	}

	/**
	 * Set turno.
	 *
	 * @param string|null $turno
	 *
	 * @return Plaza
	 */
	public function setTurno($turno = null)
	{
		$this->turno = $turno;

		return $this;
	}

	/**
	 * Get turno.
	 *
	 * @return string|null
	 */
	public function getTurno()
	{
		return $this->turno;
	}

	/**
	 * Set horNormal.
	 *
	 * @param string|null $horNormal
	 *
	 * @return Plaza
	 */
	public function setHorNormal($horNormal = null)
	{
		$this->horNormal = $horNormal;

		return $this;
	}

	/**
	 * Get horNormal.
	 *
	 * @return string|null
	 */
	public function getHorNormal()
	{
		return $this->horNormal;
	}

	/**
	 * Set ficticia.
	 *
	 * @param string|null $ficticia
	 *
	 * @return Plaza
	 */
	public function setFicticia($ficticia = null)
	{
		$this->ficticia = $ficticia;

		return $this;
	}

	/**
	 * Get ficticia.
	 *
	 * @return string|null
	 */
	public function getFicticia()
	{
		return $this->ficticia;
	}

	/**
	 * Set refuerzo.
	 *
	 * @param string|null $refuerzo
	 *
	 * @return Plaza
	 */
	public function setRefuerzo($refuerzo = null)
	{
		$this->refuerzo = $refuerzo;

		return $this;
	}

	/**
	 * Get refuerzo.
	 *
	 * @return string|null
	 */
	public function getRefuerzo()
	{
		return $this->refuerzo;
	}

	/**
	 * Set fAmortiza.
	 *
	 * @param \DateTime|null $fAmortiza
	 *
	 * @return Plaza
	 */
	public function setFAmortiza($fAmortiza = null)
	{
		$this->fAmortiza = $fAmortiza;

		return $this;
	}

	/**
	 * Get fAmortiza.
	 *
	 * @return \DateTime|null
	 */
	public function getFAmortiza()
	{
		return $this->fAmortiza;
	}

	/**
	 * Set fCreacion.
	 *
	 * @param \DateTime|null $fCreacion
	 *
	 * @return Plaza
	 */
	public function setFCreacion($fCreacion = null)
	{
		$this->fCreacion = $fCreacion;

		return $this;
	}

	/**
	 * Get fCreacion.
	 *
	 * @return \DateTime|null
	 */
	public function getFCreacion()
	{
		return $this->fCreacion;
	}

	/**
	 * Set observaciones.
	 *
	 * @param string|null $observaciones
	 *
	 * @return Plaza
	 */
	public function setObservaciones($observaciones = null)
	{
		$this->observaciones = $observaciones;

		return $this;
	}

	/**
	 * Get observaciones.
	 *
	 * @return string|null
	 */
	public function getObservaciones()
	{
		return $this->observaciones;
	}

	/**
	 * Set cupequi.
	 *
	 * @param string|null $cupequi
	 *
	 * @return Plaza
	 */
	public function setCupequi($cupequi = null)
	{
		$this->cupequi = $cupequi;

		return $this;
	}

	/**
	 * Get cupequi.
	 *
	 * @return string|null
	 */
	public function getCupequi()
	{
		return $this->cupequi;
	}

	/**
	 * Set colaboradora.
	 *
	 * @param string|null $colaboradora
	 *
	 * @return Plaza
	 */
	public function setColaboradora($colaboradora = null)
	{
		$this->colaboradora = $colaboradora;

		return $this;
	}

	/**
	 * Get colaboradora.
	 *
	 * @return string|null
	 */
	public function getColaboradora()
	{
		return $this->colaboradora;
	}

	/**
	 * Set orden.
	 *
	 * @param int|null $orden
	 *
	 * @return Plaza
	 */
	public function setOrden($orden = null)
	{
		$this->orden = $orden;

		return $this;
	}

	/**
	 * Get orden.
	 *
	 * @return int|null
	 */
	public function getOrden()
	{
		return $this->orden;
	}

	/**
	 * Set amortizada.
	 *
	 * @param string|null $amortizada
	 *
	 * @return Plaza
	 */
	public function setAmortizada($amortizada = null)
	{
		$this->amortizada = $amortizada;

		return $this;
	}

	/**
	 * Get amortizada.
	 *
	 * @return string|null
	 */
	public function getAmortizada()
	{
		return $this->amortizada;
	}

	/**
	 * Set uf.
	 *
	 * @param \CostesBundle\Entity\Uf|null $uf
	 *
	 * @return Plaza
	 */
	public function setUf(Uf $uf = null)
	{
		$this->uf = $uf;

		return $this;
	}

	/**
	 * Get uf.
	 *
	 * @return \CostesBundle\Entity\Uf|null
	 */
	public function getUf()
	{
		return $this->uf;
	}

	/**
	 * Set pa.
	 *
	 * @param \CostesBundle\Entity\Pa|null $pa
	 *
	 * @return Plaza
	 */
	public function setPa(Pa $pa = null)
	{
		$this->pa = $pa;

		return $this;
	}

	/**
	 * Get pa.
	 *
	 * @return \CostesBundle\Entity\Pa|null
	 */
	public function getPa()
	{
		return $this->pa;
	}

	/**
	 * Set catGen.
	 *
	 * @param \MaestrosBundle\Entity\CatGen|null $catGen
	 *
	 * @return Plaza
	 */
	public function setCatGen(CatGen $catGen = null)
	{
		$this->catGen = $catGen;

		return $this;
	}

	/**
	 * Get catGen.
	 *
	 * @return \MaestrosBundle\Entity\CatGen|null
	 */
	public function getCatGen()
	{
		return $this->catGen;
	}

	/**
	 * Set modalidad.
	 *
	 * @param \MaestrosBundle\Entity\Modalidad|null $modalidad
	 *
	 * @return Plaza
	 */
	public function setModalidad(Modalidad $modalidad = null)
	{
		$this->modalidad = $modalidad;

		return $this;
	}

	/**
	 * Get modalidad.
	 *
	 * @return \MaestrosBundle\Entity\Modalidad|null
	 */
	public function getModalidad()
	{
		return $this->modalidad;
	}

	/**
	 * Set catFp.
	 *
	 * @param \MaestrosBundle\Entity\CatFp|null $catFp
	 *
	 * @return Plaza
	 */
	public function setCatFp(CatFp $catFp = null)
	{
		$this->catFp = $catFp;

		return $this;
	}

	/**
	 * Get catFp
	 *
	 * @return \MaestrosBundle\Entity\CatFp|\MaestrosBundle\Entity\CatFp|null
	 */
	public function getCatFp()
	{
		return $this->catFp;
	}

	/**
	 * Set sincroLog.
	 *
	 * @param \ComunBundle\Entity\SincroLog|null $sincroLog
	 *
	 * @return Plaza
	 */
	public function setSincroLog(SincroLog $sincroLog = null)
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


	/**
	 * Set cecoActual.
	 *
	 * @param \CostesBundle\Entity\Ceco|null $cecoActual
	 *
	 * @return Plaza
	 */
	public function setCecoActual(Ceco $cecoActual = null)
	{
		$this->cecoActual = $cecoActual;

		return $this;
	}

	/**
	 * Get cecoActual.
	 *
	 * @return \CostesBundle\Entity\Ceco|null
	 */
	public function getCecoActual()
	{
		return $this->cecoActual;
	}

    /**
     * Set h1ini.
     *
     * @param \DateTime|null $h1ini
     *
     * @return Plaza
     */
    public function setH1ini($h1ini = null)
    {
        $this->h1ini = $h1ini;

        return $this;
    }

    /**
     * Get h1ini.
     *
     * @return \DateTime|null
     */
    public function getH1ini()
    {
        return $this->h1ini;
    }

    /**
     * Set h1fin.
     *
     * @param \DateTime|null $h1fin
     *
     * @return Plaza
     */
    public function setH1fin($h1fin = null)
    {
        $this->h1fin = $h1fin;

        return $this;
    }

    /**
     * Get h1fin.
     *
     * @return \DateTime|null
     */
    public function getH1fin()
    {
        return $this->h1fin;
    }

    /**
     * Set h2ini.
     *
     * @param \DateTime|null $h2ini
     *
     * @return Plaza
     */
    public function setH2ini($h2ini = null)
    {
        $this->h2ini = $h2ini;

        return $this;
    }

    /**
     * Get h2ini.
     *
     * @return \DateTime|null
     */
    public function getH2ini()
    {
        return $this->h2ini;
    }

    /**
     * Set h2fin.
     *
     * @param \DateTime|null $h2fin
     *
     * @return Plaza
     */
    public function setH2fin($h2fin = null)
    {
        $this->h2fin = $h2fin;

        return $this;
    }

    /**
     * Get h2fin.
     *
     * @return \DateTime|null
     */
    public function getH2fin()
    {
        return $this->h2fin;
    }

    /**
     * Set turnof.
     *
     * @param \MaestrosBundle\Entity\Turno|null $turnof
     *
     * @return Plaza
     */
    public function setTurnof(Turno $turnof = null)
    {
        $this->turnof = $turnof;

        return $this;
    }

    /**
     * Get turnof.
     *
     * @return \MaestrosBundle\Entity\Turno|null
     */
    public function getTurnof()
    {
        return $this->turnof;
    }

    /**
     * Set unidadOrganizativa.
     *
     * @param \CostesBundle\Entity\UnidadOrganizativa|null $unidadOrganizativa
     *
     * @return Plaza
     */
    public function setUnidadOrganizativa(UnidadOrganizativa $unidadOrganizativa = null)
    {
        $this->unidadOrganizativa = $unidadOrganizativa;

        return $this;
    }

    /**
     * Get unidadOrganizativa.
     *
     * @return \CostesBundle\Entity\UnidadOrganizativa|null
     */
    public function getUnidadOrganizativa()
    {
        return $this->unidadOrganizativa;
    }
}
