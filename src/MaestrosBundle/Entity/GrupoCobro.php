<?php

/**
 * Description of GrupoCobro
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoCobro
 *
 * @ORM\Table(name="gums_grc"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *         ,   indexes={@ORM\Index(name="idx001", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\GrupoCobroRepository")
 */
class GrupoCobro {

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
     * @ORM\Column(name="descripcion", type="string", length=25, nullable=false)
     */
    private $descripcion;

    /**
     * @var EpiAcc|null
     *
     * @ORM\ManyToOne(targetEntity="EpiAcc")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="epiacc_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $epiAcc;

    /**
     * @var GrupoCot|null
     *
     * @ORM\ManyToOne(targetEntity="GrupoCot")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupocot_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $grupoCot;

	/**
	 * @var GrupoProf|null
	 *
	 * @ORM\ManyToOne(targetEntity="MaestrosBundle\Entity\GrupoProf")
	 * * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="grupoprof_id", referencedColumnName="id",onDelete="CASCADE")
	 * })
	 */
	private $grupoProf;

	/**
     * @var integer
     *
     * @ORM\Column(name="nivel", type="integer", length=5, nullable=false)
     */
    private $nivel;

    /**
     * @var integer
     *
     * @ORM\Column(name="horas", type="integer", nullable=false)
     */
    private $horas;

    /**
     * @var string
     *
     * @ORM\Column(name="grupob", type="string", length=1, nullable=false)
     */
    private $grupob;

    /**
     * @var string
     *
     * @ORM\Column(name="apd", type="string", length=1, nullable=false)
     */
    private $apd;

    /**
     * @var string
     *
     * @ORM\Column(name="refuerzo", type="string", length=1, nullable=false)
     */
    private $refuerzo;

    /**
     * @var string
     *
     * @ORM\Column(name="persinsueldo", type="string", length=1, nullable=false)
     */
    private $perSinSueldo;

    /**
     * @var string
     *
     * @ORM\Column(name="cobra_nomina", type="string", length=1, nullable=false)
     */
    private $cobraNomina;

    /**
     * @var string
     *
     * @ORM\Column(name="cotiza_ss", type="string", length=1, nullable=false)
     */
    private $cotizaSs;

    /**
     * @var string
     *
     * @ORM\Column(name="prodtsi", type="string", length=1, nullable=true)
     */
    private $prodtsi;

    /**
     * @var string
     *
     * @ORM\Column(name="liq_extra", type="string", length=1, nullable=false)
     */
    private $liq_extra;

    /**
     * @var string
     *
     * @ORM\Column(name="liq_vacaciones", type="string", length=1, nullable=false)
     */
    private $liq_vacaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="retribucion", type="string", length=1, nullable=false)
     */
    private $retribucion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=1, nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="minimo_fijo", type="string", length=1, nullable=false)
     */
    private $minimoFijo;

    /**
     * @var string
     *
     * @ORM\Column(name="minimo_interino", type="string", length=1, nullable=false)
     */
    private $minimoInterino;

    /**
     * @var string
     *
     * @ORM\Column(name="minimo_eventual", type="string", length=1, nullable=false)
     */
    private $minimoEventual;

    /**
     * @var integer
     *
     * @ORM\Column(name="minimo_ev", type="integer",nullable=false)
     */
    private $minimoEv;

    /**
     * @var integer
     *
     * @ORM\Column(name="horas_anuales", type="integer",nullable=false)
     */
    private $horasAnuales;

    /**
     * @var integer
     *
     * @ORM\Column(name="horas_sabados", type="integer",nullable=false)
     */
    private $horasSabados;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_vacaciones", type="integer",nullable=true)
     */
    private $mediaVacaciones;
    
    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=false)
     */
    private $enuso;

    /**
     * @var string
     *
     * @ORM\Column(name="excluir_plpage", type="string", length=1, nullable=false)
     */
    private $excluirPlPage;

    /**
     * @var Ocupacion|null
     *
     * @ORM\ManyToOne(targetEntity="Ocupacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ocupacion_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $ocupacion;

    /**
     * @var string
     *
     * @ORM\Column(name="grcrpt_codigo", type="string", length=10, nullable=true)
     */
    private $grcRptCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="grcrpt_descripcion", type="string", length=100, nullable=true)
     */
    private $grcRptDescripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="grcrptid", type="integer",  nullable=true)
     */
    private $grcRptId;
    

    /**
     * @var string
     *
     * @ORM\Column(name="personal", type="string", length=1, nullable=false)
     */
    private $personal;

    /**
     * @var string
     *
     * @ORM\Column(name="peac", type="string", length=1, nullable=false)
     */
    private $peac;
    
    /**
     * @var string
     *
     * @ORM\Column(name="excluir_extra", type="string", length=1, nullable=false)
     */
    private $excluir_extra;
    
    /**
     * @var string
     *
     * @ORM\Column(name="asumedia", type="string", length=1, nullable=true)
     */
    private $asuMedia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="extra_por_horas", type="string", length=1, nullable=false)
     */
    private $extraPorHoras;
    
    /**
     * @var string
     *
     * @ORM\Column(name="asumedia_periodo", type="string", length=1, nullable=false)
     */
    private $asuMediaPeriodo;
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
     * @return GrupoCobro
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
     * @param string $descripcion
     *
     * @return GrupoCobro
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set nivel.
     *
     * @param int $nivel
     *
     * @return GrupoCobro
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel.
     *
     * @return int
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set horas.
     *
     * @param int $horas
     *
     * @return GrupoCobro
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas.
     *
     * @return int
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set grupob.
     *
     * @param string $grupob
     *
     * @return GrupoCobro
     */
    public function setGrupob($grupob)
    {
        $this->grupob = $grupob;

        return $this;
    }

    /**
     * Get grupob.
     *
     * @return string
     */
    public function getGrupob()
    {
        return $this->grupob;
    }

    /**
     * Set apd.
     *
     * @param string $apd
     *
     * @return GrupoCobro
     */
    public function setApd($apd)
    {
        $this->apd = $apd;

        return $this;
    }

    /**
     * Get apd.
     *
     * @return string
     */
    public function getApd()
    {
        return $this->apd;
    }

    /**
     * Set refuerzo.
     *
     * @param string $refuerzo
     *
     * @return GrupoCobro
     */
    public function setRefuerzo($refuerzo)
    {
        $this->refuerzo = $refuerzo;

        return $this;
    }

    /**
     * Get refuerzo.
     *
     * @return string
     */
    public function getRefuerzo()
    {
        return $this->refuerzo;
    }

    /**
     * Set perSinSueldo.
     *
     * @param string $perSinSueldo
     *
     * @return GrupoCobro
     */
    public function setPerSinSueldo($perSinSueldo)
    {
        $this->perSinSueldo = $perSinSueldo;

        return $this;
    }

    /**
     * Get perSinSueldo.
     *
     * @return string
     */
    public function getPerSinSueldo()
    {
        return $this->perSinSueldo;
    }

    /**
     * Set cobraNomina.
     *
     * @param string $cobraNomina
     *
     * @return GrupoCobro
     */
    public function setCobraNomina($cobraNomina)
    {
        $this->cobraNomina = $cobraNomina;

        return $this;
    }

    /**
     * Get cobraNomina.
     *
     * @return string
     */
    public function getCobraNomina()
    {
        return $this->cobraNomina;
    }

    /**
     * Set cotizaSs.
     *
     * @param string $cotizaSs
     *
     * @return GrupoCobro
     */
    public function setCotizaSs($cotizaSs)
    {
        $this->cotizaSs = $cotizaSs;

        return $this;
    }

    /**
     * Get cotizaSs.
     *
     * @return string
     */
    public function getCotizaSs()
    {
        return $this->cotizaSs;
    }

    /**
     * Set prodtsi.
     *
     * @param string|null $prodtsi
     *
     * @return GrupoCobro
     */
    public function setProdtsi($prodtsi = null)
    {
        $this->prodtsi = $prodtsi;

        return $this;
    }

    /**
     * Get prodtsi.
     *
     * @return string|null
     */
    public function getProdtsi()
    {
        return $this->prodtsi;
    }

    /**
     * Set liqExtra.
     *
     * @param string $liqExtra
     *
     * @return GrupoCobro
     */
    public function setLiqExtra($liqExtra)
    {
        $this->liq_extra = $liqExtra;

        return $this;
    }

    /**
     * Get liqExtra.
     *
     * @return string
     */
    public function getLiqExtra()
    {
        return $this->liq_extra;
    }

    /**
     * Set liqVacaciones.
     *
     * @param string $liqVacaciones
     *
     * @return GrupoCobro
     */
    public function setLiqVacaciones($liqVacaciones)
    {
        $this->liq_vacaciones = $liqVacaciones;

        return $this;
    }

    /**
     * Get liqVacaciones.
     *
     * @return string
     */
    public function getLiqVacaciones()
    {
        return $this->liq_vacaciones;
    }

    /**
     * Set retribucion.
     *
     * @param string $retribucion
     *
     * @return GrupoCobro
     */
    public function setRetribucion($retribucion)
    {
        $this->retribucion = $retribucion;

        return $this;
    }

    /**
     * Get retribucion.
     *
     * @return string
     */
    public function getRetribucion()
    {
        return $this->retribucion;
    }

    /**
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return GrupoCobro
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set minimoFijo.
     *
     * @param string $minimoFijo
     *
     * @return GrupoCobro
     */
    public function setMinimoFijo($minimoFijo)
    {
        $this->minimoFijo = $minimoFijo;

        return $this;
    }

    /**
     * Get minimoFijo.
     *
     * @return string
     */
    public function getMinimoFijo()
    {
        return $this->minimoFijo;
    }

    /**
     * Set minimoInterino.
     *
     * @param string $minimoInterino
     *
     * @return GrupoCobro
     */
    public function setMinimoInterino($minimoInterino)
    {
        $this->minimoInterino = $minimoInterino;

        return $this;
    }

    /**
     * Get minimoInterino.
     *
     * @return string
     */
    public function getMinimoInterino()
    {
        return $this->minimoInterino;
    }

    /**
     * Set minimoEventual.
     *
     * @param string $minimoEventual
     *
     * @return GrupoCobro
     */
    public function setMinimoEventual($minimoEventual)
    {
        $this->minimoEventual = $minimoEventual;

        return $this;
    }

    /**
     * Get minimoEventual.
     *
     * @return string
     */
    public function getMinimoEventual()
    {
        return $this->minimoEventual;
    }

    /**
     * Set minimoEv.
     *
     * @param int $minimoEv
     *
     * @return GrupoCobro
     */
    public function setMinimoEv($minimoEv)
    {
        $this->minimoEv = $minimoEv;

        return $this;
    }

    /**
     * Get minimoEv.
     *
     * @return int
     */
    public function getMinimoEv()
    {
        return $this->minimoEv;
    }

    /**
     * Set horasAnuales.
     *
     * @param int $horasAnuales
     *
     * @return GrupoCobro
     */
    public function setHorasAnuales($horasAnuales)
    {
        $this->horasAnuales = $horasAnuales;

        return $this;
    }

    /**
     * Get horasAnuales.
     *
     * @return int
     */
    public function getHorasAnuales()
    {
        return $this->horasAnuales;
    }

    /**
     * Set horasSabados.
     *
     * @param int $horasSabados
     *
     * @return GrupoCobro
     */
    public function setHorasSabados($horasSabados)
    {
        $this->horasSabados = $horasSabados;

        return $this;
    }

    /**
     * Get horasSabados.
     *
     * @return int
     */
    public function getHorasSabados()
    {
        return $this->horasSabados;
    }

    /**
     * Set mediaVacaciones.
     *
     * @param int $mediaVacaciones
     *
     * @return GrupoCobro
     */
    public function setMediaVacaciones($mediaVacaciones)
    {
        $this->mediaVacaciones = $mediaVacaciones;

        return $this;
    }

    /**
     * Get mediaVacaciones.
     *
     * @return int
     */
    public function getMediaVacaciones()
    {
        return $this->mediaVacaciones;
    }

    /**
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return GrupoCobro
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
     * Set excluirPlPage.
     *
     * @param string $excluirPlPage
     *
     * @return GrupoCobro
     */
    public function setExcluirPlPage($excluirPlPage)
    {
        $this->excluirPlPage = $excluirPlPage;

        return $this;
    }

    /**
     * Get excluirPlPage.
     *
     * @return string
     */
    public function getExcluirPlPage()
    {
        return $this->excluirPlPage;
    }

    /**
     * Set grcRptCodigo.
     *
     * @param string|null $grcRptCodigo
     *
     * @return GrupoCobro
     */
    public function setGrcRptCodigo($grcRptCodigo = null)
    {
        $this->grcRptCodigo = $grcRptCodigo;

        return $this;
    }

    /**
     * Get grcRptCodigo.
     *
     * @return string|null
     */
    public function getGrcRptCodigo()
    {
        return $this->grcRptCodigo;
    }

    /**
     * Set grcRptDescripcion.
     *
     * @param string|null $grcRptDescripcion
     *
     * @return GrupoCobro
     */
    public function setGrcRptDescripcion($grcRptDescripcion = null)
    {
        $this->grcRptDescripcion = $grcRptDescripcion;

        return $this;
    }

    /**
     * Get grcRptDescripcion.
     *
     * @return string|null
     */
    public function getGrcRptDescripcion()
    {
        return $this->grcRptDescripcion;
    }

    /**
     * Set grcRptId.
     *
     * @param int|null $grcRptId
     *
     * @return GrupoCobro
     */
    public function setGrcRptId($grcRptId = null)
    {
        $this->grcRptId = $grcRptId;

        return $this;
    }

    /**
     * Get grcRptId.
     *
     * @return int|null
     */
    public function getGrcRptId()
    {
        return $this->grcRptId;
    }

    /**
     * Set personal.
     *
     * @param string $personal
     *
     * @return GrupoCobro
     */
    public function setPersonal($personal)
    {
        $this->personal = $personal;

        return $this;
    }

    /**
     * Get personal.
     *
     * @return string
     */
    public function getPersonal()
    {
        return $this->personal;
    }

    /**
     * Set peac.
     *
     * @param string $peac
     *
     * @return GrupoCobro
     */
    public function setPeac($peac)
    {
        $this->peac = $peac;

        return $this;
    }

    /**
     * Get peac.
     *
     * @return string
     */
    public function getPeac()
    {
        return $this->peac;
    }

    /**
     * Set excluirExtra.
     *
     * @param string $excluirExtra
     *
     * @return GrupoCobro
     */
    public function setExcluirExtra($excluirExtra)
    {
        $this->excluir_extra = $excluirExtra;

        return $this;
    }

    /**
     * Get excluirExtra.
     *
     * @return string
     */
    public function getExcluirExtra()
    {
        return $this->excluir_extra;
    }

    /**
     * Set asuMedia.
     *
     * @param string|null $asuMedia
     *
     * @return GrupoCobro
     */
    public function setAsuMedia($asuMedia = null)
    {
        $this->asuMedia = $asuMedia;

        return $this;
    }

    /**
     * Get asuMedia.
     *
     * @return string|null
     */
    public function getAsuMedia()
    {
        return $this->asuMedia;
    }

    /**
     * Set extraPorHoras.
     *
     * @param string $extraPorHoras
     *
     * @return GrupoCobro
     */
    public function setExtraPorHoras($extraPorHoras)
    {
        $this->extraPorHoras = $extraPorHoras;

        return $this;
    }

    /**
     * Get extraPorHoras.
     *
     * @return string
     */
    public function getExtraPorHoras()
    {
        return $this->extraPorHoras;
    }

    /**
     * Set asuMediaPeriodo.
     *
     * @param string $asuMediaPeriodo
     *
     * @return GrupoCobro
     */
    public function setAsuMediaPeriodo($asuMediaPeriodo)
    {
        $this->asuMediaPeriodo = $asuMediaPeriodo;

        return $this;
    }

    /**
     * Get asuMediaPeriodo.
     *
     * @return string
     */
    public function getAsuMediaPeriodo()
    {
        return $this->asuMediaPeriodo;
    }

    /**
     * Set epiAcc.
     *
     * @param \MaestrosBundle\Entity\EpiAcc|null $epiAcc
     *
     * @return GrupoCobro
     */
    public function setEpiAcc(\MaestrosBundle\Entity\EpiAcc $epiAcc = null)
    {
        $this->epiAcc = $epiAcc;

        return $this;
    }

    /**
     * Get epiAcc.
     *
     * @return \MaestrosBundle\Entity\EpiAcc|null
     */
    public function getEpiAcc()
    {
        return $this->epiAcc;
    }

    /**
     * Set GrupoCot.
     *
     * @param \MaestrosBundle\Entity\GrupoCot|null $GrupoCot
     *
     * @return GrupoCobro
     */
    public function setGrupoCot(\MaestrosBundle\Entity\GrupoCot $grupoCot = null)
    {
        $this->grupoCot = $grupoCot;

        return $this;
    }

    /**
     * Get GrupoCot.
     *
     * @return \MaestrosBundle\Entity\GrupoCot|null
     */
    public function getGrupoCot()
    {
        return $this->grupoCot;
    }

    /**
     * Set ocupacion.
     *
     * @param \MaestrosBundle\Entity\Ocupacion|null $ocupacion
     *
     * @return GrupoCobro
     */
    public function setOcupacion(\MaestrosBundle\Entity\Ocupacion $ocupacion = null)
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    /**
     * Get ocupacion.
     *
     * @return \MaestrosBundle\Entity\Ocupacion|null
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }
    
    public function __toString() {
        return '('.$this->codigo.') '.$this->descripcion;
    }

    /**
     * Set sincroLog.
     *
     * @param \ComunBundle\Entity\SincroLog|null $sincroLog
     *
     * @return GrupoCobro
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

    /**
     * Set grupoProf.
     *
     * @param \MaestrosBundle\Entity\GrupoProf|null $grupoProf
     *
     * @return GrupoCobro
     */
    public function setGrupoProf(\MaestrosBundle\Entity\GrupoProf $grupoProf = null)
    {
        $this->grupoProf = $grupoProf;

        return $this;
    }

    /**
     * Get grupoProf.
     *
     * @return \MaestrosBundle\Entity\GrupoProf|null
     */
    public function getGrupoProf()
    {
        return $this->grupoProf;
    }
}
