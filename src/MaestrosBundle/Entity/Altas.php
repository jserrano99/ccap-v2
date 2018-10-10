<?php

namespace MaestrosBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Altas
 *
 * @ORM\Table(name="gums_altas"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\AltasRepository")
 */

class Altas {
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
     * @var string
     *
     * @ORM\Column(name="btc_mcon_codigo", type="string", length=2, nullable=true)
     */
    private $btcMconCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="btc_tipocon", type="string", length=1, nullable=true)
     */
    private $btcTipoCon;

    /**
     * @var ModOcupa|null
     *
     * @ORM\ManyToOne(targetEntity="ModOcupa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modocupa_id", referencedColumnName="id")
     * })
     */
    private $modOcupa;
    
    /**
     * @var ModoPago|null
     *
     * @ORM\ManyToOne(targetEntity="ModoPago")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modopago_id", referencedColumnName="id")
     * })
     */
    private $modoPago;
    
    /**
     * @var string
     *
     * @ORM\Column(name="subaltas_afi", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $subAltasAfi;

    /**
     * @var MoviPat|null
     *
     * @ORM\ManyToOne(targetEntity="MoviPat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="movipat_id", referencedColumnName="id")
     * })
     */
    private $moviPat;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="subaltas_certi", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $subAltasCerti;

    /**
     * @var string
     *
     * @ORM\Column(name="certificar", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $certificar;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $enuso;
    
    /**
     * @var string
     *
     * @ORM\Column(name="motivoaltarptid", type="integer", length=10, nullable=true)
     */
    private $motivoAltaRptId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="malrpt_codigo", type="string", length=10, nullable=true)
     */
    private $malRptCodigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="malrpt_descripcion", type="string", length=100, nullable=true)
     */
    private $malRptDescripcion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="l13", type="string", length=1, nullable=true,  options={"default":"S"})
     */
    private $l13;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rel_juridica", type="string", length=2, nullable=true)
     */
    private $relJuridica;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pagar_tramo", type="string", length=1, nullable=true,  options={"default":"N"})
     */
    private $pagarTramo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="destino", type="string", length=1, nullable=true,  options={"default":"N"})
     */
    private $destino;
    

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
     * @return Altas
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
     * Set descripcion
     *
     * @param string|null $descripcion
     *
     * @return Altas
     */
    public function setDescripcion($descripcion = null)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string|null
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set btcMconCodigo.
     *
     * @param string|null $btcMconCodigo
     *
     * @return Altas
     */
    public function setBtcMconCodigo($btcMconCodigo = null)
    {
        $this->btcMconCodigo = $btcMconCodigo;

        return $this;
    }

    /**
     * Get btcMconCodigo.
     *
     * @return string|null
     */
    public function getBtcMconCodigo()
    {
        return $this->btcMconCodigo;
    }

    /**
     * Set btcTipoCon.
     *
     * @param string|null $btcTipoCon
     *
     * @return Altas
     */
    public function setBtcTipoCon($btcTipoCon = null)
    {
        $this->btcTipoCon = $btcTipoCon;

        return $this;
    }

    /**
     * Get btcTipoCon.
     *
     * @return string|null
     */
    public function getBtcTipoCon()
    {
        return $this->btcTipoCon;
    }

    /**
     * Set subAltasAfi.
     *
     * @param string $subAltasAfi
     *
     * @return Altas
     */
    public function setSubAltasAfi($subAltasAfi)
    {
        $this->subAltasAfi = $subAltasAfi;

        return $this;
    }

    /**
     * Get subAltasAfi.
     *
     * @return string
     */
    public function getSubAltasAfi()
    {
        return $this->subAltasAfi;
    }

    /**
     * Set subAltasCerti.
     *
     * @param string $subAltasCerti
     *
     * @return Altas
     */
    public function setSubAltasCerti($subAltasCerti)
    {
        $this->subAltasCerti = $subAltasCerti;

        return $this;
    }

    /**
     * Get subAltasCerti.
     *
     * @return string
     */
    public function getSubAltasCerti()
    {
        return $this->subAltasCerti;
    }

    /**
     * Set certificar.
     *
     * @param string $certificar
     *
     * @return Altas
     */
    public function setCertificar($certificar)
    {
        $this->certificar = $certificar;

        return $this;
    }

    /**
     * Get certificar.
     *
     * @return string
     */
    public function getCertificar()
    {
        return $this->certificar;
    }

    /**
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return Altas
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
     * Set motivoAltaRptId.
     *
     * @param int|null $motivoAltaRptId
     *
     * @return Altas
     */
    public function setMotivoAltaRptId($motivoAltaRptId = null)
    {
        $this->motivoAltaRptId = $motivoAltaRptId;

        return $this;
    }

    /**
     * Get motivoAltaRptId.
     *
     * @return int|null
     */
    public function getMotivoAltaRptId()
    {
        return $this->motivoAltaRptId;
    }

    /**
     * Set malRptCodigo.
     *
     * @param string|null $malRptCodigo
     *
     * @return Altas
     */
    public function setMalRptCodigo($malRptCodigo = null)
    {
        $this->malRptCodigo = $malRptCodigo;

        return $this;
    }

    /**
     * Get malRptCodigo.
     *
     * @return string|null
     */
    public function getMalRptCodigo()
    {
        return $this->malRptCodigo;
    }

    /**
     * Set malRptDescripcion.
     *
     * @param string|null $malRptDescripcion
     *
     * @return Altas
     */
    public function setMalRptDescripcion($malRptDescripcion = null)
    {
        $this->malRptDescripcion = $malRptDescripcion;

        return $this;
    }

    /**
     * Get malRptDescripcion.
     *
     * @return string|null
     */
    public function getMalRptDescripcion()
    {
        return $this->malRptDescripcion;
    }

    /**
     * Set l13.
     *
     * @param string $l13
     *
     * @return Altas
     */
    public function setL13($l13)
    {
        $this->l13 = $l13;

        return $this;
    }

    /**
     * Get l13.
     *
     * @return string
     */
    public function getL13()
    {
        return $this->l13;
    }

    /**
     * Set relJuridica.
     *
     * @param string|null $relJuridica
     *
     * @return Altas
     */
    public function setRelJuridica($relJuridica = null)
    {
        $this->relJuridica = $relJuridica;

        return $this;
    }

    /**
     * Get relJuridica.
     *
     * @return string|null
     */
    public function getRelJuridica()
    {
        return $this->relJuridica;
    }

    /**
     * Set pagarTramo.
     *
     * @param string|null $pagarTramo
     *
     * @return Altas
     */
    public function setPagarTramo($pagarTramo = null)
    {
        $this->pagarTramo = $pagarTramo;

        return $this;
    }

    /**
     * Get pagarTramo.
     *
     * @return string|null
     */
    public function getPagarTramo()
    {
        return $this->pagarTramo;
    }

    /**
     * Set destino.
     *
     * @param string|null $destino
     *
     * @return Altas
     */
    public function setDestino($destino = null)
    {
        $this->destino = $destino;

        return $this;
    }

    /**
     * Get destino.
     *
     * @return string|null
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * Set modOcupa.
     *
     * @param \MaestrosBundle\Entity\ModOcupa|null $modOcupa
     *
     * @return Altas
     */
    public function setModOcupa(\MaestrosBundle\Entity\ModOcupa $modOcupa = null)
    {
        $this->modOcupa = $modOcupa;

        return $this;
    }

    /**
     * Get modOcupa.
     *
     * @return \MaestrosBundle\Entity\ModOcupa|null
     */
    public function getModOcupa()
    {
        return $this->modOcupa;
    }

    /**
     * Set modoPago.
     *
     * @param \MaestrosBundle\Entity\ModoPago|null $modoPago
     *
     * @return Altas
     */
    public function setModoPago(\MaestrosBundle\Entity\ModoPago $modoPago = null)
    {
        $this->modoPago = $modoPago;

        return $this;
    }

    /**
     * Get modoPago.
     *
     * @return \MaestrosBundle\Entity\ModoPago|null
     */
    public function getModoPago()
    {
        return $this->modoPago;
    }

    /**
     * Set moviPat.
     *
     * @param \MaestrosBundle\Entity\MoviPat|null $moviPat
     *
     * @return Altas
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
    
    public function __toString() {
        return $this->descrip. ' ('. $this->codigo.')';
    }


    /**
     * Set sincroLog.
     *
     * @param \ComunBundle\Entity\SincroLog|null $sincroLog
     *
     * @return Altas
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
