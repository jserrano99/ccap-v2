<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concepto
 *
 * @ORM\Table(name="gums_conceptos"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\ConceptoRepository")
 */
class Concepto {

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
     * @ORM\Column(name="descrip", type="string", length=25, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="irpf", type="string", length=1, nullable=true)
     */
    private $irpf;

    /**
     * @var string
     *
     * @ORM\Column(name="segsoc", type="string", length=1, nullable=true)
     */
    private $segsoc;

    /**
     * @var string
     *
     * @ORM\Column(name="extra", type="string", length=1, nullable=true)
     */
    private $extra;

    /**
     * @var string
     *
     * @ORM\Column(name="vacac", type="string", length=1, nullable=true)
     */
    private $vacac;

    /**
     * @var decimal
     *
     * @ORM\Column(name="importe", type="decimal", precision=13, scale=3, nullable=false,options={"default":0})
     */
    private $importe;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=1, nullable=false, options={"default":"+"})
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="acum", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $acum;

    /**
     * @var string
     *
     * @ORM\Column(name="incre", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $incre;

    /**
     * @var string
     *
     * @ORM\Column(name="huelga", type="string", length=1, nullable=true)
     */
    private $huelga;

    /**
     * @var string
     *
     * @ORM\Column(name="devengo", type="string", length=1, nullable=true)
     */
    private $devengo;

    /**
     * @var string
     *
     * @ORM\Column(name="clave190", type="string", length=3, nullable=true)
     */
    private $clave190;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_concepto", type="string", length=1, nullable=true)
     */
    private $tipoConcepto;

    /**
     * @var string
     *
     * @ORM\Column(name="cupo_acu", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $cupoAcu;

    /**
     * @var string
     *
     * @ORM\Column(name="cupo_cd", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $cupoCd;

    /**
     * @var string
     *
     * @ORM\Column(name="ret_judicial", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $retJudicial;

    /**
     * @var string
     *
     * @ORM\Column(name="trieniocupo", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $trienioCupo;

    /**
     * @var string
     *
     * @ORM\Column(name="recupera_it", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $recuperaIt;

    /**
     * @var decimal
     *
     * @ORM\Column(name="porcentaje_extra", type="decimal", precision=8, scale=4, nullable=false,options={"default":100})
     */
    private $porcentajExtra;

    /**
     * @var string
     *
     * @ORM\Column(name="gasto173", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $gasto173;

    /**
     * @var string
     *
     * @ORM\Column(name="mayor_carga", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $mayorCarga;

    /**
     * @var string
     *
     * @ORM\Column(name="mayor_carga_grc", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $mayorCargaGrc;

    /**
     * @var string
     *
     * @ORM\Column(name="sabados", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $sabados;

    /**
     * @var string
     *
     * @ORM\Column(name="variable_irpf", type="string", length=1, nullable=true)
     */
    private $variableIrpf;

    /**
     * @var string
     *
     * @ORM\Column(name="mejora_it", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $mejoraIt;

    /**
     * @var string
     *
     * @ORM\Column(name="cobraenextra", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $cobraEnExtra;

    /**
     * @var string
     *
     * @ORM\Column(name="conceptorpt_codigo", type="string", length=10, nullable=true)
     */
    private $conceptoRptCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="conrpt_descripcion", type="string", length=100, nullable=true)
     */
    private $conRptDescripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="conceptorptid", type="integer", nullable=true)
     */
    private $conceptoRptId;

    /**
     * @var decimal
     *
     * @ORM\Column(name="porcen_extra_ant", type="decimal", precision=8, scale=4, nullable=false,options={"default":100})
     */
    private $porcenExtraAnt;

    /**
     * @var string
     *
     * @ORM\Column(name="exc_retencion", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $excRetencion;

    /**
     * @var string
     *
     * @ORM\Column(name="variable_decre", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $variableDecre;

    /**
     * @var string
     *
     * @ORM\Column(name="integro_mit", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $integroMit;

    /**
     * @var string
     *
     * @ORM\Column(name="salario", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $salario;

    /**
     * @var string
     *
     * @ORM\Column(name="complemento", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $complemento;

    /**
     * @var string
     *
     * @ORM\Column(name="at_continuada", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $atContinuada;

    /**
     * @var string
     *
     * @ORM\Column(name="turnicidad", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $turnicidad;

    /**
     * @var string
     *
     * @ORM\Column(name="descuenta_it", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $descuentaIt;

    /**
     * @var string
     *
     * @ORM\Column(name="codigocre", type="string", length=4, nullable=true)
     */
    private $codigocre;

    /**
     * @var string
     *
     * @ORM\Column(name="enespecie", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $enEspecie;

    /**
     * @var decimal
     *
     * @ORM\Column(name="reduccion", type="decimal", precision=5, scale=2, nullable=false,options={"default":0})
     */
    private $reduccion;

    /**
     * @var string
     *
     * @ORM\Column(name="it190", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $it190;

    /**
     * @var string
     *
     * @ORM\Column(name="rbmuface", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $rbMuface;

    /**
     * @var string
     *
     * @ORM\Column(name="rbmuface2", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $rbMuface2;

    /**
     * @var string
     *
     * @ORM\Column(name="descanso", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $descanso;

    /**
     * @var string
     *
     * @ORM\Column(name="ceco_concepto", type="string", length=2, nullable=true)
     */
    private $cecoConcepto;

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
    public function getId() {
        return $this->id;
    }

    /**
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return Concepto
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Set descripcion.
     *
     * @param string $descripcion
     *
     * @return Concepto
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
     * Set irpf.
     *
     * @param string|null $irpf
     *
     * @return Concepto
     */
    public function setIrpf($irpf = null) {
        $this->irpf = $irpf;

        return $this;
    }

    /**
     * Get irpf.
     *
     * @return string|null
     */
    public function getIrpf() {
        return $this->irpf;
    }

    /**
     * Set segsoc.
     *
     * @param string|null $segsoc
     *
     * @return Concepto
     */
    public function setSegsoc($segsoc = null) {
        $this->segsoc = $segsoc;

        return $this;
    }

    /**
     * Get segsoc.
     *
     * @return string|null
     */
    public function getSegsoc() {
        return $this->segsoc;
    }

    /**
     * Set extra.
     *
     * @param string|null $extra
     *
     * @return Concepto
     */
    public function setExtra($extra = null) {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra.
     *
     * @return string|null
     */
    public function getExtra() {
        return $this->extra;
    }

    /**
     * Set vacac.
     *
     * @param string|null $vacac
     *
     * @return Concepto
     */
    public function setVacac($vacac = null) {
        $this->vacac = $vacac;

        return $this;
    }

    /**
     * Get vacac.
     *
     * @return string|null
     */
    public function getVacac() {
        return $this->vacac;
    }

    /**
     * Set importe.
     *
     * @param string $importe
     *
     * @return Concepto
     */
    public function setImporte($importe) {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe.
     *
     * @return string
     */
    public function getImporte() {
        return $this->importe;
    }

    /**
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return Concepto
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo.
     *
     * @return string
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Set acum.
     *
     * @param string $acum
     *
     * @return Concepto
     */
    public function setAcum($acum) {
        $this->acum = $acum;

        return $this;
    }

    /**
     * Get acum.
     *
     * @return string
     */
    public function getAcum() {
        return $this->acum;
    }

    /**
     * Set incre.
     *
     * @param string $incre
     *
     * @return Concepto
     */
    public function setIncre($incre) {
        $this->incre = $incre;

        return $this;
    }

    /**
     * Get incre.
     *
     * @return string
     */
    public function getIncre() {
        return $this->incre;
    }

    /**
     * Set huelga.
     *
     * @param string|null $huelga
     *
     * @return Concepto
     */
    public function setHuelga($huelga = null) {
        $this->huelga = $huelga;

        return $this;
    }

    /**
     * Get huelga.
     *
     * @return string|null
     */
    public function getHuelga() {
        return $this->huelga;
    }

    /**
     * Set devengo.
     *
     * @param string|null $devengo
     *
     * @return Concepto
     */
    public function setDevengo($devengo = null) {
        $this->devengo = $devengo;

        return $this;
    }

    /**
     * Get devengo.
     *
     * @return string|null
     */
    public function getDevengo() {
        return $this->devengo;
    }

    /**
     * Set clave190.
     *
     * @param string|null $clave190
     *
     * @return Concepto
     */
    public function setClave190($clave190 = null) {
        $this->clave190 = $clave190;

        return $this;
    }

    /**
     * Get clave190.
     *
     * @return string|null
     */
    public function getClave190() {
        return $this->clave190;
    }

    /**
     * Set tipoConcepto.
     *
     * @param string|null $tipoConcepto
     *
     * @return Concepto
     */
    public function setTipoConcepto($tipoConcepto = null) {
        $this->tipoConcepto = $tipoConcepto;

        return $this;
    }

    /**
     * Get tipoConcepto.
     *
     * @return string|null
     */
    public function getTipoConcepto() {
        return $this->tipoConcepto;
    }

    /**
     * Set cupoAcu.
     *
     * @param string $cupoAcu
     *
     * @return Concepto
     */
    public function setCupoAcu($cupoAcu) {
        $this->cupoAcu = $cupoAcu;

        return $this;
    }

    /**
     * Get cupoAcu.
     *
     * @return string
     */
    public function getCupoAcu() {
        return $this->cupoAcu;
    }

    /**
     * Set cupoCd.
     *
     * @param string $cupoCd
     *
     * @return Concepto
     */
    public function setCupoCd($cupoCd) {
        $this->cupoCd = $cupoCd;

        return $this;
    }

    /**
     * Get cupoCd.
     *
     * @return string
     */
    public function getCupoCd() {
        return $this->cupoCd;
    }

    /**
     * Set retJudicial.
     *
     * @param string $retJudicial
     *
     * @return Concepto
     */
    public function setRetJudicial($retJudicial) {
        $this->retJudicial = $retJudicial;

        return $this;
    }

    /**
     * Get retJudicial.
     *
     * @return string
     */
    public function getRetJudicial() {
        return $this->retJudicial;
    }

    /**
     * Set trienioCupo.
     *
     * @param string $trienioCupo
     *
     * @return Concepto
     */
    public function setTrienioCupo($trienioCupo) {
        $this->trienioCupo = $trienioCupo;

        return $this;
    }

    /**
     * Get trienioCupo.
     *
     * @return string
     */
    public function getTrienioCupo() {
        return $this->trienioCupo;
    }

    /**
     * Set recuperaIt.
     *
     * @param string $recuperaIt
     *
     * @return Concepto
     */
    public function setRecuperaIt($recuperaIt) {
        $this->recuperaIt = $recuperaIt;

        return $this;
    }

    /**
     * Get recuperaIt.
     *
     * @return string
     */
    public function getRecuperaIt() {
        return $this->recuperaIt;
    }

    /**
     * Set porcentajExtra.
     *
     * @param string $porcentajExtra
     *
     * @return Concepto
     */
    public function setPorcentajExtra($porcentajExtra) {
        $this->porcentajExtra = $porcentajExtra;

        return $this;
    }

    /**
     * Get porcentajExtra.
     *
     * @return string
     */
    public function getPorcentajExtra() {
        return $this->porcentajExtra;
    }

    /**
     * Set gasto173.
     *
     * @param string $gasto173
     *
     * @return Concepto
     */
    public function setGasto173($gasto173) {
        $this->gasto173 = $gasto173;

        return $this;
    }

    /**
     * Get gasto173.
     *
     * @return string
     */
    public function getGasto173() {
        return $this->gasto173;
    }

    /**
     * Set mayorCarga.
     *
     * @param string $mayorCarga
     *
     * @return Concepto
     */
    public function setMayorCarga($mayorCarga) {
        $this->mayorCarga = $mayorCarga;

        return $this;
    }

    /**
     * Get mayorCarga.
     *
     * @return string
     */
    public function getMayorCarga() {
        return $this->mayorCarga;
    }

    /**
     * Set mayorCargaGrc.
     *
     * @param string $mayorCargaGrc
     *
     * @return Concepto
     */
    public function setMayorCargaGrc($mayorCargaGrc) {
        $this->mayorCargaGrc = $mayorCargaGrc;

        return $this;
    }

    /**
     * Get mayorCargaGrc.
     *
     * @return string
     */
    public function getMayorCargaGrc() {
        return $this->mayorCargaGrc;
    }

    /**
     * Set sabados.
     *
     * @param string $sabados
     *
     * @return Concepto
     */
    public function setSabados($sabados) {
        $this->sabados = $sabados;

        return $this;
    }

    /**
     * Get sabados.
     *
     * @return string
     */
    public function getSabados() {
        return $this->sabados;
    }

    /**
     * Set variableIrpf.
     *
     * @param string|null $variableIrpf
     *
     * @return Concepto
     */
    public function setVariableIrpf($variableIrpf = null) {
        $this->variableIrpf = $variableIrpf;

        return $this;
    }

    /**
     * Get variableIrpf.
     *
     * @return string|null
     */
    public function getVariableIrpf() {
        return $this->variableIrpf;
    }

    /**
     * Set mejoraIt.
     *
     * @param string $mejoraIt
     *
     * @return Concepto
     */
    public function setMejoraIt($mejoraIt) {
        $this->mejoraIt = $mejoraIt;

        return $this;
    }

    /**
     * Get mejoraIt.
     *
     * @return string
     */
    public function getMejoraIt() {
        return $this->mejoraIt;
    }

    /**
     * Set cobraEnExtra.
     *
     * @param string $cobraEnExtra
     *
     * @return Concepto
     */
    public function setCobraEnExtra($cobraEnExtra) {
        $this->cobraEnExtra = $cobraEnExtra;

        return $this;
    }

    /**
     * Get cobraEnExtra.
     *
     * @return string
     */
    public function getCobraEnExtra() {
        return $this->cobraEnExtra;
    }

    /**
     * Set conceptoRptCodigo.
     *
     * @param string|null $conceptoRptCodigo
     *
     * @return Concepto
     */
    public function setConceptoRptCodigo($conceptoRptCodigo = null) {
        $this->conceptoRptCodigo = $conceptoRptCodigo;

        return $this;
    }

    /**
     * Get conceptoRptCodigo.
     *
     * @return string|null
     */
    public function getConceptoRptCodigo() {
        return $this->conceptoRptCodigo;
    }

    /**
     * Set conRptDescripcion.
     *
     * @param string|null $conRptDescripcion
     *
     * @return Concepto
     */
    public function setConRptDescripcion($conRptDescripcion = null) {
        $this->conRptDescripcion = $conRptDescripcion;

        return $this;
    }

    /**
     * Get conRptDescripcion.
     *
     * @return string|null
     */
    public function getConRptDescripcion() {
        return $this->conRptDescripcion;
    }

    /**
     * Set conceptoRptId.
     *
     * @param int|null $conceptoRptId
     *
     * @return Concepto
     */
    public function setConceptoRptId($conceptoRptId = null) {
        $this->conceptoRptId = $conceptoRptId;

        return $this;
    }

    /**
     * Get conceptoRptId.
     *
     * @return int|null
     */
    public function getConceptoRptId() {
        return $this->conceptoRptId;
    }

    /**
     * Set porcenExtraAnt.
     *
     * @param string $porcenExtraAnt
     *
     * @return Concepto
     */
    public function setPorcenExtraAnt($porcenExtraAnt) {
        $this->porcenExtraAnt = $porcenExtraAnt;

        return $this;
    }

    /**
     * Get porcenExtraAnt.
     *
     * @return string
     */
    public function getPorcenExtraAnt() {
        return $this->porcenExtraAnt;
    }

    /**
     * Set excRetencion.
     *
     * @param string $excRetencion
     *
     * @return Concepto
     */
    public function setExcRetencion($excRetencion) {
        $this->excRetencion = $excRetencion;

        return $this;
    }

    /**
     * Get excRetencion.
     *
     * @return string
     */
    public function getExcRetencion() {
        return $this->excRetencion;
    }

    /**
     * Set variableDecre.
     *
     * @param string $variableDecre
     *
     * @return Concepto
     */
    public function setVariableDecre($variableDecre) {
        $this->variableDecre = $variableDecre;

        return $this;
    }

    /**
     * Get variableDecre.
     *
     * @return string
     */
    public function getVariableDecre() {
        return $this->variableDecre;
    }

    /**
     * Set integroMit.
     *
     * @param string $integroMit
     *
     * @return Concepto
     */
    public function setIntegroMit($integroMit) {
        $this->integroMit = $integroMit;

        return $this;
    }

    /**
     * Get integroMit.
     *
     * @return string
     */
    public function getIntegroMit() {
        return $this->integroMit;
    }

    /**
     * Set salario.
     *
     * @param string $salario
     *
     * @return Concepto
     */
    public function setSalario($salario) {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Get salario.
     *
     * @return string
     */
    public function getSalario() {
        return $this->salario;
    }

    /**
     * Set complemento.
     *
     * @param string $complemento
     *
     * @return Concepto
     */
    public function setComplemento($complemento) {
        $this->complemento = $complemento;

        return $this;
    }

    /**
     * Get complemento.
     *
     * @return string
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * Set atContinuada.
     *
     * @param string $atContinuada
     *
     * @return Concepto
     */
    public function setAtContinuada($atContinuada) {
        $this->atContinuada = $atContinuada;

        return $this;
    }

    /**
     * Get atContinuada.
     *
     * @return string
     */
    public function getAtContinuada() {
        return $this->atContinuada;
    }

    /**
     * Set turnicidad.
     *
     * @param string $turnicidad
     *
     * @return Concepto
     */
    public function setTurnicidad($turnicidad) {
        $this->turnicidad = $turnicidad;

        return $this;
    }

    /**
     * Get turnicidad.
     *
     * @return string
     */
    public function getTurnicidad() {
        return $this->turnicidad;
    }

    /**
     * Set descuentaIt.
     *
     * @param string $descuentaIt
     *
     * @return Concepto
     */
    public function setDescuentaIt($descuentaIt) {
        $this->descuentaIt = $descuentaIt;

        return $this;
    }

    /**
     * Get descuentaIt.
     *
     * @return string
     */
    public function getDescuentaIt() {
        return $this->descuentaIt;
    }

    /**
     * Set codigocre.
     *
     * @param string|null $codigocre
     *
     * @return Concepto
     */
    public function setCodigocre($codigocre = null) {
        $this->codigocre = $codigocre;

        return $this;
    }

    /**
     * Get codigocre.
     *
     * @return string|null
     */
    public function getCodigocre() {
        return $this->codigocre;
    }

    /**
     * Set enEspecie.
     *
     * @param string $enEspecie
     *
     * @return Concepto
     */
    public function setEnEspecie($enEspecie) {
        $this->enEspecie = $enEspecie;

        return $this;
    }

    /**
     * Get enEspecie.
     *
     * @return string
     */
    public function getEnEspecie() {
        return $this->enEspecie;
    }

    /**
     * Set reduccion.
     *
     * @param string $reduccion
     *
     * @return Concepto
     */
    public function setReduccion($reduccion) {
        $this->reduccion = $reduccion;

        return $this;
    }

    /**
     * Get reduccion.
     *
     * @return string
     */
    public function getReduccion() {
        return $this->reduccion;
    }

    /**
     * Set it190.
     *
     * @param string $it190
     *
     * @return Concepto
     */
    public function setIt190($it190) {
        $this->it190 = $it190;

        return $this;
    }

    /**
     * Get it190.
     *
     * @return string
     */
    public function getIt190() {
        return $this->it190;
    }

    /**
     * Set rbMuface.
     *
     * @param string $rbMuface
     *
     * @return Concepto
     */
    public function setRbMuface($rbMuface) {
        $this->rbMuface = $rbMuface;

        return $this;
    }

    /**
     * Get rbMuface.
     *
     * @return string
     */
    public function getRbMuface() {
        return $this->rbMuface;
    }

    /**
     * Set rbMuface2.
     *
     * @param string $rbMuface2
     *
     * @return Concepto
     */
    public function setRbMuface2($rbMuface2) {
        $this->rbMuface2 = $rbMuface2;

        return $this;
    }

    /**
     * Get rbMuface2.
     *
     * @return string
     */
    public function getRbMuface2() {
        return $this->rbMuface2;
    }

    /**
     * Set descanso.
     *
     * @param string $descanso
     *
     * @return Concepto
     */
    public function setDescanso($descanso) {
        $this->descanso = $descanso;

        return $this;
    }

    /**
     * Get descanso.
     *
     * @return string
     */
    public function getDescanso() {
        return $this->descanso;
    }

    /**
     * Set cecoConcepto.
     *
     * @param string|null $cecoConcepto
     *
     * @return Concepto
     */
    public function setCecoConcepto($cecoConcepto = null) {
        $this->cecoConcepto = $cecoConcepto;

        return $this;
    }

    /**
     * Get cecoConcepto.
     *
     * @return string|null
     */
    public function getCecoConcepto() {
        return $this->cecoConcepto;
    }

    public function __toString() {
        return $this->descripcion . " (" . $this->codigo . ")";
    }


    /**
     * Set sincroLog.
     *
     * @param \ComunBundle\Entity\SincroLog|null $sincroLog
     *
     * @return Concepto
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
