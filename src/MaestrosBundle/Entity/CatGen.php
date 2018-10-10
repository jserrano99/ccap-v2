<?php

/**
 * Description of CatGen
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * CatGen
 *
 * @ORM\Table(name="gums_catgen"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\CatGenRepository")
 */
class CatGen {

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
     * @ORM\Column(name="codigo", type="string", length=2, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=40, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="btc_tbol_codigo", type="string", length=2, nullable=true)
     */
    private $btcTbolCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=true)
     */
    private $enuso;

    /**
     * @var string
     *
     * @ORM\Column(name="plan_org", type="string", length=3, nullable=true)
     */
    private $planOrg;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_insalud", type="string", length=4, nullable=true)
     */
    private $codInsalud;

    /**
     * @var string
     *
     * @ORM\Column(name="des_insalud", type="string", length=50, nullable=true)
     */
    private $desInsalud;

    /**
     * @var string
     *
     * @ORM\Column(name="especialidad", type="string", length=50, nullable=true)
     */
    private $especialidad;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_sms", type="string", length=5, nullable=true)
     */
    private $codigoSms;

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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return CatGen
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return CatGen
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set btcTbolCodigo
     *
     * @param string $btcTbolCodigo
     *
     * @return CatGen
     */
    public function setBtcTbolCodigo($btcTbolCodigo) {
        $this->btcTbolCodigo = $btcTbolCodigo;

        return $this;
    }

    /**
     * Get btcTbolCodigo
     *
     * @return string
     */
    public function getBtcTbolCodigo() {
        return $this->btcTbolCodigo;
    }

    /**
     * Set enuso
     *
     * @param string $enuso
     *
     * @return CatGen
     */
    public function setEnuso($enuso) {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso
     *
     * @return string
     */
    public function getEnuso() {
        return $this->enuso;
    }

    /**
     * Set planOrg
     *
     * @param string $planOrg
     *
     * @return CatGen
     */
    public function setPlanOrg($planOrg) {
        $this->planOrg = $planOrg;

        return $this;
    }

    /**
     * Get planOrg
     *
     * @return string
     */
    public function getPlanOrg() {
        return $this->planOrg;
    }

    /**
     * Set codInsalud
     *
     * @param string $codInsalud
     *
     * @return CatGen
     */
    public function setCodInsalud($codInsalud) {
        $this->codInsalud = $codInsalud;

        return $this;
    }

    /**
     * Get codInsalud
     *
     * @return string
     */
    public function getCodInsalud() {
        return $this->codInsalud;
    }

    /**
     * Set desInsalud
     *
     * @param string $desInsalud
     *
     * @return CatGen
     */
    public function setDesInsalud($desInsalud) {
        $this->desInsalud = $desInsalud;

        return $this;
    }

    /**
     * Get desInsalud
     *
     * @return string
     */
    public function getDesInsalud() {
        return $this->desInsalud;
    }

    /**
     * Set especialidad
     *
     * @param string $especialidad
     *
     * @return CatGen
     */
    public function setEspecialidad($especialidad) {
        $this->especialidad = $especialidad;

        return $this;
    }

    /**
     * Get especialidad
     *
     * @return string
     */
    public function getEspecialidad() {
        return $this->especialidad;
    }

    /**
     * Set codigoSms
     *
     * @param string $codigoSms
     *
     * @return CatGen
     */
    public function setCodSms($codigoSms) {
        $this->codigoSms = $codigoSms;

        return $this;
    }

    /**
     * Get codigoSms
     *
     * @return string
     */
    public function getCodSms() {
        return $this->codigoSms;
    }

    public function __toString() {
        return $this->descripcion." (".$this->codigo.")";
    }



    /**
     * Set codigoSms.
     *
     * @param string|null $codigoSms
     *
     * @return CatGen
     */
    public function setCodigoSms($codigoSms = null)
    {
        $this->codigoSms = $codigoSms;

        return $this;
    }

    /**
     * Get codigoSms.
     *
     * @return string|null
     */
    public function getCodigoSms()
    {
        return $this->codigoSms;
    }

    /**
     * Set sincroLog.
     *
     * @param \ComunBundle\Entity\SincroLog|null $sincroLog
     *
     * @return CatGen
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
