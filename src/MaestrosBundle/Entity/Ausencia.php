<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ausencia
 *
 * @ORM\Table(name="gums_ausencias",
 *          uniqueConstraints={@ORM\UniqueConstraint(name="uk_ausencia_codigo", columns={"codigo"}),
                               @ORM\UniqueConstraint(name="uk_jano_codigo", columns={"jano_codigo"})})
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\AusenciaRepository")
 */


class Ausencia {
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
     * @ORM\Column(name="descrip", type="string", length=50, nullable=true)
     */
    private $descrip;

    /**
     * @var string
     *
     * @ORM\Column(name="predecible", type="string", length=1, nullable=false, options={"default":"N"}))
     */
    private $predecible;

    /**
     * @var decimal
     *
     * @ORM\Column(name="max_anual", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $maxAnual;

    /**
     * @var decimal
     *
     * @ORM\Column(name="max_total", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $maxTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="naturales", type="string", length=1, nullable=false,options={"default":"S"}))
     */
    private $naturales;

    /**
     * @var string
     *
     * @ORM\Column(name="naturales_ev", type="string", length=1, nullable=false,options={"default":"S"}))
     */
    private $naturalesEv;

    /**
     * @var string
     *
     * @ORM\Column(name="es_it", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $esIt;

    
    /**
     * @var string
     *
     * @ORM\Column(name="ctrl_horario", type="string", length=1, nullable=false,options={"default":"N"}))
     */
    private $ctrlHorario;

    /**
     * @var string
     *
     * @ORM\Column(name="sindicato", type="string", length=1, nullable=false,options={"default":"N"}))
     */
    private $sindicato;

    /**
     * @var string
     *
     * @ORM\Column(name="absentismo", type="string", length=1, nullable=false,options={"default":"N"}))
     */
    private $absentismo;

    /**
     * @var string
     *
     * @ORM\Column(name="autog", type="string", length=1, nullable=true)
     */
    private $autog;

    /**
     * @var TipoIlt|null
     *
     * @ORM\ManyToOne(targetEntity="TipoIlt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_ilt_id", referencedColumnName="id")
     * })
     */
    private $tipoIlt;

    /**
     * @var string
     *
     * @ORM\Column(name="pagotit", type="string", length=1, nullable=false,options={"default":"S"}))
     */
    private $pagotit;

    /**
     * @var string
     *
     * @ORM\Column(name="dtrab", type="string", length=1, nullable=true)
     */
    private $dtrab;

    /**
     * @var string
     *
     * @ORM\Column(name="csituadm", type="string", length=1, nullable=false)
     */
    private $csituadm;

    /**
     * @var string
     *
     * @ORM\Column(name="txtab", type="string", length=3, nullable=true)
     */
    private $txtab;

    /**
     * @var Fco|null
     *
     * @ORM\ManyToOne(targetEntity="Fco")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fco_id", referencedColumnName="id")
     * })
     */
    private $fco;

    /**
     * @var string
     *
     * @ORM\Column(name="contador", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $contador;

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
     * @var string
     *
     * @ORM\Column(name="otrosperm", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $otrosperm;

    /**
     * @var string
     *
     * @ORM\Column(name="justificar", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $justificar;

    /**
     * @var string
     *
     * @ORM\Column(name="a22", type="string", length=1, nullable=false,options={"default":"N"}))
     */
    private $a22;

    /**
     * @var string
     *
     * @ORM\Column(name="red", type="string", length=2, nullable=true)
     */
    private $red;

    /**
     * @var smallint
     *
     * @ORM\Column(name="autog_desde", type="smallint", nullable=true)
     */
    private $autogDesde;


    /**
     * @var smallint
     *
     * @ORM\Column(name="autog_hasta", type="smallint", nullable=true)
     */
    private $autogHasta;


    /**
     * @var string
     *
     * @ORM\Column(name="cambiogrc", type="string", length=3, nullable=true)
     */
    private $cambiogrc;

    /**
     * @var smallint
     *
     * @ORM\Column(name="redondeo", type="smallint", nullable=true, options={"default":0}))
     */
    private $redondeo;


    /**
     * @var string
     *
     * @ORM\Column(name="dtrabperm", type="string",length=1, nullable=true, options={"default":"S"}))
     */
    private $dtrabperm;

    /**
     * @var string
     *
     * @ORM\Column(name="btc_tipocon", type="string", length=1, nullable=true)
     */
    private $btcTipoCon;

    /**
     * @var string
     *
     * @ORM\Column(name="calculo_ffin", type="string",length=1, nullable=true, options={"default":"N"}))
     */
    private $calculoFfin;


    /**
     * @var string
     *
     * @ORM\Column(name="codigonom", type="string",length=3, nullable=true)
     */
    private $codigonom;

    /**
     * @var EpiAcc|null
     *
     * @ORM\ManyToOne(targetEntity="EpiAcc")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="epiacc_id", referencedColumnName="id")
     * })
     */
    private $epiAcc;

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
     * @var decimal
     *
     * @ORM\Column(name="porcen1", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $porcen1;

    /**
     * @var decimal
     *
     * @ORM\Column(name="didesde1", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $didesde1;

    /**
     * @var decimal
     *
     * @ORM\Column(name="dihasta1", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $dihasta1;


    /**
     * @var decimal
     *
     * @ORM\Column(name="porcen2", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $porcen2;

    /**
     * @var decimal
     *
     * @ORM\Column(name="didesde2", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $didesde2;

    /**
     * @var decimal
     *
     * @ORM\Column(name="dihasta2", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $dihasta2;

    /**
     * @var decimal
     *
     * @ORM\Column(name="porcen3", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $porcen3;

    /**
     * @var decimal
     *
     * @ORM\Column(name="didesde3", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $didesde3;

    /**
     * @var decimal
     *
     * @ORM\Column(name="dihasta3", type="decimal", precision=6, scale= 2 , nullable=true, options={"default":0}))
     */
    private $dihasta3;

    /**
     * @var string
     *
     * @ORM\Column(name="persinsu", type="string",length=1, nullable=true, options={"default":"N"}))
     */
    private $persinsu;

    /**
     * @var string
     *
     * @ORM\Column(name="cotizass", type="string",length=1, nullable=false, options={"default":"N"}))
     */
    private $cotizass;


    /**
     * @var string
     *
     * @ORM\Column(name="idbasescon", type="string",length=3, nullable=true)
     */
    private $idbasescon;

    /**
     * @var string
     *
     * @ORM\Column(name="cambiosgrc", type="string",length=3, nullable=true)
     */
    private $cambiosgrc;


    /**
     * @var integer
     *
     * @ORM\Column(name="justificante_dias", type="integer",nullable=true)
     */
    private $justificanteDias;

    /**
     * @var string
     *
     * @ORM\Column(name="proporcional", type="string",length=1, nullable=true, options={"default":"S"}))
     */
    private $proporcional;

    /**
     * @var string
     *
     * @ORM\Column(name="descu_trienios", type="string",length=1, nullable=true, options={"default":"N"}))
     */
    private $descuTrienios;


    /**
     * @var string
     *
     * @ORM\Column(name="huelga", type="string",length=1, nullable=true, options={"default":"N"}))
     */
    private $huelga;

    /**
     * @var string
     *
     * @ORM\Column(name="itinerancia", type="string",length=1, nullable=true, options={"default":"N"}))
     */
    private $itinerancia;

    /**
     * @var decimal
     *
     * @ORM\Column(name="porcen_it", type="decimal", precision=5, scale= 2 , nullable=true, options={"default":0}))
     */
    private $porcenIt;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=true,options={"default":"S"}))
     */
    private $enuso;

    /**
     * @var string
     *
     * @ORM\Column(name="reserva", type="string", length=1, nullable=true,options={"default":"S"}))
     */
    private $reserva;


    /**
     * @var integer
     *
     * @ORM\Column(name="dur_reserva", type="integer", nullable=true)
     */
    private $durReserva;

    /**
     * @var string
     *
     * @ORM\Column(name="guarda", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $guarda;

    /**
     * @var string
     *
     * @ORM\Column(name="reduccion", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $reduccion;

    
    /**
     * @var string
     *
     * @ORM\Column(name="mapturnos", type="string", length=3, nullable=true)
     */
    private $mapturnos;

    /**
     * @var string
     *
     * @ORM\Column(name="afecta_revision", type="string", length=1, nullable=true)
     */
    private $afectaRevision;


    /**
     * @var string
     *
     * @ORM\Column(name="excluir_plpage", type="string", length=1, nullable=true,options={"default":"N"})
     */
    private $excluirPlpage;

    /**
     * @var decimal
     *
     * @ORM\Column(name="max_total_h", type="decimal", precision=10, scale=3 , nullable=true,options={"default":0})
     */
    private $maxTotalH;

    /**
     * @var decimal
     *
     * @ORM\Column(name="max_anual_h", type="decimal", precision=10, scale=3 , nullable=true)
     */
    private $maxAnualH;

    /**
     * @var Ocupacion|null
     *
     * @ORM\ManyToOne(targetEntity="Ocupacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ocupacion_id", referencedColumnName="id")
     * })
     */
    private $ocupacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="ausenciasrptid", type="integer", nullable=true)
     */
    private $ausenciasrptid;


    /**
     * @var string
     *
     * @ORM\Column(name="ausrpt_codigo", type="string",length=10, nullable=true)
     */
    private $ausrptCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="ausrpt_descripcion", type="string",length=100, nullable=true)
     */
    private $ausrptDescripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="fin_red", type="string",length=10, nullable=true)
     */
    private $finRed;
    /**
     * @var string
     *
     * @ORM\Column(name="turnos", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $turnos;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cambiopuesto", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $cambiopuesto;

    
    /**
     * @var string
     *
     * @ORM\Column(name="cuenta_turnic", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $cuentaTurnic;
 
    /**
     * @var string
     *
     * @ORM\Column(name="cuenta_pago", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $cuentaPago;
 
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo_inactividad", type="string", length=2, nullable=true)
     */
    private $tipoInactividad;
 
    /**
     * @var Ocupacion|null
     *
     * @ORM\ManyToOne(targetEntity="Ocupacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ocupacion_new_id", referencedColumnName="id")
     * })
     */
    private $ocupacionNew;

    /**
     * @var string
     *
     * @ORM\Column(name="mejora_it", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $mejoraIt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="destino", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $destino;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ctact", type="string", length=1, nullable=true,options={"default":"S"}))
     */
    private $ctact;
    
    /**
     * @var string 
     *
     * @ORM\Column(name="it_contador_jano",type="string",length=1,nullable=true,options={"default":"N"}))
     */
    private $itContadorJano;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_apartado",type="string",length=1,nullable=true)
     */
    private $janoApartado;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_apd",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoApd;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_codigo",type="string",length=3,nullable=true)
     */
    private $janoCodigo;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_con2annos",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoConn2annos;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_descripcion",type="string",length=50,nullable=true)
     */
    private $janoDescripcion;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_descripseg",type="string",length=50,nullable=true)
     */
    private $janoDescripseg;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_nombrelargo",type="string",length=50,nullable=true)
     */
    private $janoNombrelargo;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_dldold",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoDldold;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_fecfin_abierta",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoFecfinAbierta;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_varold",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $JanoVarold;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_documento",type="string",length=20,nullable=true)
     */
    private $janoDocumento;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_en_horas",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoEnHoras;

    /**
     * @var date 
     *
     * @ORM\Column(name="jano_feclimant",type="date",nullable=true)
     */
    private $janoFeclimant;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_grado",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoGrado;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_grautoriza",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoGrautoriza;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_responsable",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoResponsable;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_usuario",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoUsuario;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_resto",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoResto;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_localidad",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoLocalidad;

    /**
     * @var smallint 
     *
     * @ORM\Column(name="jano_maxadelanto",type="smallint",nullable=true,options={"default":15})
     */
    private $janoMaxadelanto;

    /**
     * @var smallint 
     *
     * @ORM\Column(name="jano_maxlab",type="smallint",nullable=true,options={"default":0})
     */
    private $janoMaxlab;

    /**
     * @var smallint 
     *
     * @ORM\Column(name="jano_maxnat",type="smallint",nullable=true,options={"default":0})
     */
    private $janoMaxnat;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_mir",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoMir;

    /**
     * @var string 
     *
     * @ORM\Column(name="jano_sar",type="string",length=1,nullable=true,options={"default":"N"})
     */
    private $janoSar;

    /**
     * @var smallint 
     *
     * @ORM\Column(name="jano_suma_dias_cont",type="smallint",length=1,nullable=true,options={"default":0})
     */
    private $janoSumaDiasCont;

    /**
     * @var smallint 
     *
     * @ORM\Column(name="jano_suma_dias_disc",type="smallint",length=1,nullable=true,options={"default":0})
     */
    private $janoSumaDiasDisc;

    /**
     * @var DocumPermiso|null
     *
     * @ORM\ManyToOne(targetEntity="DocumPermiso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="jano_docum_permiso_id", referencedColumnName="id")
     * })
     */
    private $janoDocumPermiso;
    
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
     * @return Ausencia
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
     * Set descrip.
     *
     * @param string|null $descrip
     *
     * @return Ausencia
     */
    public function setDescrip($descrip = null)
    {
        $this->descrip = $descrip;

        return $this;
    }

    /**
     * Get descrip.
     *
     * @return string|null
     */
    public function getDescrip()
    {
        return $this->descrip;
    }

    /**
     * Set predecible.
     *
     * @param string $predecible
     *
     * @return Ausencia
     */
    public function setPredecible($predecible)
    {
        $this->predecible = $predecible;

        return $this;
    }

    /**
     * Get predecible.
     *
     * @return string
     */
    public function getPredecible()
    {
        return $this->predecible;
    }

    /**
     * Set maxAnual.
     *
     * @param string|null $maxAnual
     *
     * @return Ausencia
     */
    public function setMaxAnual($maxAnual = null)
    {
        $this->maxAnual = $maxAnual;

        return $this;
    }

    /**
     * Get maxAnual.
     *
     * @return string|null
     */
    public function getMaxAnual()
    {
        return $this->maxAnual;
    }

    /**
     * Set maxTotal.
     *
     * @param string|null $maxTotal
     *
     * @return Ausencia
     */
    public function setMaxTotal($maxTotal = null)
    {
        $this->maxTotal = $maxTotal;

        return $this;
    }

    /**
     * Get maxTotal.
     *
     * @return string|null
     */
    public function getMaxTotal()
    {
        return $this->maxTotal;
    }

    /**
     * Set naturales.
     *
     * @param string $naturales
     *
     * @return Ausencia
     */
    public function setNaturales($naturales)
    {
        $this->naturales = $naturales;

        return $this;
    }

    /**
     * Get naturales.
     *
     * @return string
     */
    public function getNaturales()
    {
        return $this->naturales;
    }

    /**
     * Set naturalesEv.
     *
     * @param string $naturalesEv
     *
     * @return Ausencia
     */
    public function setNaturalesEv($naturalesEv)
    {
        $this->naturalesEv = $naturalesEv;

        return $this;
    }

    /**
     * Get naturalesEv.
     *
     * @return string
     */
    public function getNaturalesEv()
    {
        return $this->naturalesEv;
    }

    /**
     * Set esIt.
     *
     * @param string|null $esIt
     *
     * @return Ausencia
     */
    public function setEsIt($esIt = null)
    {
        $this->esIt = $esIt;

        return $this;
    }

    /**
     * Get esIt.
     *
     * @return string|null
     */
    public function getEsIt()
    {
        return $this->esIt;
    }

    /**
     * Set ctrlHorario.
     *
     * @param string $ctrlHorario
     *
     * @return Ausencia
     */
    public function setCtrlHorario($ctrlHorario)
    {
        $this->ctrlHorario = $ctrlHorario;

        return $this;
    }

    /**
     * Get ctrlHorario.
     *
     * @return string
     */
    public function getCtrlHorario()
    {
        return $this->ctrlHorario;
    }

    /**
     * Set sindicato.
     *
     * @param string $sindicato
     *
     * @return Ausencia
     */
    public function setSindicato($sindicato)
    {
        $this->sindicato = $sindicato;

        return $this;
    }

    /**
     * Get sindicato.
     *
     * @return string
     */
    public function getSindicato()
    {
        return $this->sindicato;
    }

    /**
     * Set absentismo.
     *
     * @param string $absentismo
     *
     * @return Ausencia
     */
    public function setAbsentismo($absentismo)
    {
        $this->absentismo = $absentismo;

        return $this;
    }

    /**
     * Get absentismo.
     *
     * @return string
     */
    public function getAbsentismo()
    {
        return $this->absentismo;
    }

    /**
     * Set autog.
     *
     * @param string|null $autog
     *
     * @return Ausencia
     */
    public function setAutog($autog = null)
    {
        $this->autog = $autog;

        return $this;
    }

    /**
     * Get autog.
     *
     * @return string|null
     */
    public function getAutog()
    {
        return $this->autog;
    }

    /**
     * Set pagotit.
     *
     * @param string $pagotit
     *
     * @return Ausencia
     */
    public function setPagotit($pagotit)
    {
        $this->pagotit = $pagotit;

        return $this;
    }

    /**
     * Get pagotit.
     *
     * @return string
     */
    public function getPagotit()
    {
        return $this->pagotit;
    }

    /**
     * Set dtrab.
     *
     * @param string|null $dtrab
     *
     * @return Ausencia
     */
    public function setDtrab($dtrab = null)
    {
        $this->dtrab = $dtrab;

        return $this;
    }

    /**
     * Get dtrab.
     *
     * @return string|null
     */
    public function getDtrab()
    {
        return $this->dtrab;
    }

    /**
     * Set csituadm.
     *
     * @param string $csituadm
     *
     * @return Ausencia
     */
    public function setCsituadm($csituadm)
    {
        $this->csituadm = $csituadm;

        return $this;
    }

    /**
     * Get csituadm.
     *
     * @return string
     */
    public function getCsituadm()
    {
        return $this->csituadm;
    }

    /**
     * Set txtab.
     *
     * @param string|null $txtab
     *
     * @return Ausencia
     */
    public function setTxtab($txtab = null)
    {
        $this->txtab = $txtab;

        return $this;
    }

    /**
     * Get txtab.
     *
     * @return string|null
     */
    public function getTxtab()
    {
        return $this->txtab;
    }

    /**
     * Set contador.
     *
     * @param string|null $contador
     *
     * @return Ausencia
     */
    public function setContador($contador = null)
    {
        $this->contador = $contador;

        return $this;
    }

    /**
     * Get contador.
     *
     * @return string|null
     */
    public function getContador()
    {
        return $this->contador;
    }

    /**
     * Set otrosperm.
     *
     * @param string|null $otrosperm
     *
     * @return Ausencia
     */
    public function setOtrosperm($otrosperm = null)
    {
        $this->otrosperm = $otrosperm;

        return $this;
    }

    /**
     * Get otrosperm.
     *
     * @return string|null
     */
    public function getOtrosperm()
    {
        return $this->otrosperm;
    }

    /**
     * Set justificar.
     *
     * @param string|null $justificar
     *
     * @return Ausencia
     */
    public function setJustificar($justificar = null)
    {
        $this->justificar = $justificar;

        return $this;
    }

    /**
     * Get justificar.
     *
     * @return string|null
     */
    public function getJustificar()
    {
        return $this->justificar;
    }

    /**
     * Set a22.
     *
     * @param string $a22
     *
     * @return Ausencia
     */
    public function setA22($a22)
    {
        $this->a22 = $a22;

        return $this;
    }

    /**
     * Get a22.
     *
     * @return string
     */
    public function getA22()
    {
        return $this->a22;
    }

    /**
     * Set red.
     *
     * @param string|null $red
     *
     * @return Ausencia
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
     * Set autogDesde.
     *
     * @param int|null $autogDesde
     *
     * @return Ausencia
     */
    public function setAutogDesde($autogDesde = null)
    {
        $this->autogDesde = $autogDesde;

        return $this;
    }

    /**
     * Get autogDesde.
     *
     * @return int|null
     */
    public function getAutogDesde()
    {
        return $this->autogDesde;
    }

    /**
     * Set autogHasta.
     *
     * @param int|null $autogHasta
     *
     * @return Ausencia
     */
    public function setAutogHasta($autogHasta = null)
    {
        $this->autogHasta = $autogHasta;

        return $this;
    }

    /**
     * Get autogHasta.
     *
     * @return int|null
     */
    public function getAutogHasta()
    {
        return $this->autogHasta;
    }

    /**
     * Set cambiogrc.
     *
     * @param string|null $cambiogrc
     *
     * @return Ausencia
     */
    public function setCambiogrc($cambiogrc = null)
    {
        $this->cambiogrc = $cambiogrc;

        return $this;
    }

    /**
     * Get cambiogrc.
     *
     * @return string|null
     */
    public function getCambiogrc()
    {
        return $this->cambiogrc;
    }

    /**
     * Set redondeo.
     *
     * @param int|null $redondeo
     *
     * @return Ausencia
     */
    public function setRedondeo($redondeo = null)
    {
        $this->redondeo = $redondeo;

        return $this;
    }

    /**
     * Get redondeo.
     *
     * @return int|null
     */
    public function getRedondeo()
    {
        return $this->redondeo;
    }

    /**
     * Set dtrabperm.
     *
     * @param string|null $dtrabperm
     *
     * @return Ausencia
     */
    public function setDtrabperm($dtrabperm = null)
    {
        $this->dtrabperm = $dtrabperm;

        return $this;
    }

    /**
     * Get dtrabperm.
     *
     * @return string|null
     */
    public function getDtrabperm()
    {
        return $this->dtrabperm;
    }

    /**
     * Set btcTipoCon.
     *
     * @param string|null $btcTipoCon
     *
     * @return Ausencia
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
     * Set calculoFfin.
     *
     * @param string|null $calculoFfin
     *
     * @return Ausencia
     */
    public function setCalculoFfin($calculoFfin = null)
    {
        $this->calculoFfin = $calculoFfin;

        return $this;
    }

    /**
     * Get calculoFfin.
     *
     * @return string|null
     */
    public function getCalculoFfin()
    {
        return $this->calculoFfin;
    }

    /**
     * Set codigonom.
     *
     * @param string|null $codigonom
     *
     * @return Ausencia
     */
    public function setCodigonom($codigonom = null)
    {
        $this->codigonom = $codigonom;

        return $this;
    }

    /**
     * Get codigonom.
     *
     * @return string|null
     */
    public function getCodigonom()
    {
        return $this->codigonom;
    }

    /**
     * Set porcen1.
     *
     * @param string $porcen1
     *
     * @return Ausencia
     */
    public function setPorcen1($porcen1)
    {
        $this->porcen1 = $porcen1;

        return $this;
    }

    /**
     * Get porcen1.
     *
     * @return string
     */
    public function getPorcen1()
    {
        return $this->porcen1;
    }

    /**
     * Set didesde1.
     *
     * @param string $didesde1
     *
     * @return Ausencia
     */
    public function setDidesde1($didesde1)
    {
        $this->didesde1 = $didesde1;

        return $this;
    }

    /**
     * Get didesde1.
     *
     * @return string
     */
    public function getDidesde1()
    {
        return $this->didesde1;
    }

    /**
     * Set dihasta1.
     *
     * @param string $dihasta1
     *
     * @return Ausencia
     */
    public function setDihasta1($dihasta1)
    {
        $this->dihasta1 = $dihasta1;

        return $this;
    }

    /**
     * Get dihasta1.
     *
     * @return string
     */
    public function getDihasta1()
    {
        return $this->dihasta1;
    }

    /**
     * Set porcen2.
     *
     * @param string $porcen2
     *
     * @return Ausencia
     */
    public function setPorcen2($porcen2)
    {
        $this->porcen2 = $porcen2;

        return $this;
    }

    /**
     * Get porcen2.
     *
     * @return string
     */
    public function getPorcen2()
    {
        return $this->porcen2;
    }

    /**
     * Set didesde2.
     *
     * @param string $didesde2
     *
     * @return Ausencia
     */
    public function setDidesde2($didesde2)
    {
        $this->didesde2 = $didesde2;

        return $this;
    }

    /**
     * Get didesde2.
     *
     * @return string
     */
    public function getDidesde2()
    {
        return $this->didesde2;
    }

    /**
     * Set dihasta2.
     *
     * @param string $dihasta2
     *
     * @return Ausencia
     */
    public function setDihasta2($dihasta2)
    {
        $this->dihasta2 = $dihasta2;

        return $this;
    }

    /**
     * Get dihasta2.
     *
     * @return string
     */
    public function getDihasta2()
    {
        return $this->dihasta2;
    }

    /**
     * Set porcen3.
     *
     * @param string $porcen3
     *
     * @return Ausencia
     */
    public function setPorcen3($porcen3)
    {
        $this->porcen3 = $porcen3;

        return $this;
    }

    /**
     * Get porcen3.
     *
     * @return string
     */
    public function getPorcen3()
    {
        return $this->porcen3;
    }

    /**
     * Set didesde3.
     *
     * @param string $didesde3
     *
     * @return Ausencia
     */
    public function setDidesde3($didesde3)
    {
        $this->didesde3 = $didesde3;

        return $this;
    }

    /**
     * Get didesde3.
     *
     * @return string
     */
    public function getDidesde3()
    {
        return $this->didesde3;
    }

    /**
     * Set dihasta3.
     *
     * @param string $dihasta3
     *
     * @return Ausencia
     */
    public function setDihasta3($dihasta3)
    {
        $this->dihasta3 = $dihasta3;

        return $this;
    }

    /**
     * Get dihasta3.
     *
     * @return string
     */
    public function getDihasta3()
    {
        return $this->dihasta3;
    }

    /**
     * Set persinsu.
     *
     * @param string $persinsu
     *
     * @return Ausencia
     */
    public function setPersinsu($persinsu)
    {
        $this->persinsu = $persinsu;

        return $this;
    }

    /**
     * Get persinsu.
     *
     * @return string
     */
    public function getPersinsu()
    {
        return $this->persinsu;
    }

    /**
     * Set cotizass.
     *
     * @param string $cotizass
     *
     * @return Ausencia
     */
    public function setCotizass($cotizass)
    {
        $this->cotizass = $cotizass;

        return $this;
    }

    /**
     * Get cotizass.
     *
     * @return string
     */
    public function getCotizass()
    {
        return $this->cotizass;
    }

    /**
     * Set idbasescon.
     *
     * @param string|null $idbasescon
     *
     * @return Ausencia
     */
    public function setIdbasescon($idbasescon = null)
    {
        $this->idbasescon = $idbasescon;

        return $this;
    }

    /**
     * Get idbasescon.
     *
     * @return string|null
     */
    public function getIdbasescon()
    {
        return $this->idbasescon;
    }

    /**
     * Set cambiosgrc.
     *
     * @param string|null $cambiosgrc
     *
     * @return Ausencia
     */
    public function setCambiosgrc($cambiosgrc = null)
    {
        $this->cambiosgrc = $cambiosgrc;

        return $this;
    }

    /**
     * Get cambiosgrc.
     *
     * @return string|null
     */
    public function getCambiosgrc()
    {
        return $this->cambiosgrc;
    }

    /**
     * Set justificanteDias.
     *
     * @param int|null $justificanteDias
     *
     * @return Ausencia
     */
    public function setJustificanteDias($justificanteDias = null)
    {
        $this->justificanteDias = $justificanteDias;

        return $this;
    }

    /**
     * Get justificanteDias.
     *
     * @return int|null
     */
    public function getJustificanteDias()
    {
        return $this->justificanteDias;
    }

    /**
     * Set proporcional.
     *
     * @param string $proporcional
     *
     * @return Ausencia
     */
    public function setProporcional($proporcional)
    {
        $this->proporcional = $proporcional;

        return $this;
    }

    /**
     * Get proporcional.
     *
     * @return string
     */
    public function getProporcional()
    {
        return $this->proporcional;
    }

    /**
     * Set descuTrienios.
     *
     * @param string $descuTrienios
     *
     * @return Ausencia
     */
    public function setDescuTrienios($descuTrienios)
    {
        $this->descuTrienios = $descuTrienios;

        return $this;
    }

    /**
     * Get descuTrienios.
     *
     * @return string
     */
    public function getDescuTrienios()
    {
        return $this->descuTrienios;
    }

    /**
     * Set huelga.
     *
     * @param string $huelga
     *
     * @return Ausencia
     */
    public function setHuelga($huelga)
    {
        $this->huelga = $huelga;

        return $this;
    }

    /**
     * Get huelga.
     *
     * @return string
     */
    public function getHuelga()
    {
        return $this->huelga;
    }

    /**
     * Set itinerancia.
     *
     * @param string $itinerancia
     *
     * @return Ausencia
     */
    public function setItinerancia($itinerancia)
    {
        $this->itinerancia = $itinerancia;

        return $this;
    }

    /**
     * Get itinerancia.
     *
     * @return string
     */
    public function getItinerancia()
    {
        return $this->itinerancia;
    }

    /**
     * Set porcenIt.
     *
     * @param string $porcenIt
     *
     * @return Ausencia
     */
    public function setPorcenIt($porcenIt)
    {
        $this->porcenIt = $porcenIt;

        return $this;
    }

    /**
     * Get porcenIt.
     *
     * @return string
     */
    public function getPorcenIt()
    {
        return $this->porcenIt;
    }

    /**
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return Ausencia
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
     * Set reserva.
     *
     * @param string $reserva
     *
     * @return Ausencia
     */
    public function setReserva($reserva)
    {
        $this->reserva = $reserva;

        return $this;
    }

    /**
     * Get reserva.
     *
     * @return string
     */
    public function getReserva()
    {
        return $this->reserva;
    }

    /**
     * Set durReserva.
     *
     * @param int|null $durReserva
     *
     * @return Ausencia
     */
    public function setDurReserva($durReserva = null)
    {
        $this->durReserva = $durReserva;

        return $this;
    }

    /**
     * Get durReserva.
     *
     * @return int|null
     */
    public function getDurReserva()
    {
        return $this->durReserva;
    }

    /**
     * Set guarda.
     *
     * @param string|null $guarda
     *
     * @return Ausencia
     */
    public function setGuarda($guarda = null)
    {
        $this->guarda = $guarda;

        return $this;
    }

    /**
     * Get guarda.
     *
     * @return string|null
     */
    public function getGuarda()
    {
        return $this->guarda;
    }

    /**
     * Set reduccion.
     *
     * @param string|null $reduccion
     *
     * @return Ausencia
     */
    public function setReduccion($reduccion = null)
    {
        $this->reduccion = $reduccion;

        return $this;
    }

    /**
     * Get reduccion.
     *
     * @return string|null
     */
    public function getReduccion()
    {
        return $this->reduccion;
    }

    /**
     * Set mapturnos.
     *
     * @param string|null $mapturnos
     *
     * @return Ausencia
     */
    public function setMapturnos($mapturnos = null)
    {
        $this->mapturnos = $mapturnos;

        return $this;
    }

    /**
     * Get mapturnos.
     *
     * @return string|null
     */
    public function getMapturnos()
    {
        return $this->mapturnos;
    }

    /**
     * Set afectaRevision.
     *
     * @param string|null $afectaRevision
     *
     * @return Ausencia
     */
    public function setAfectaRevision($afectaRevision = null)
    {
        $this->afectaRevision = $afectaRevision;

        return $this;
    }

    /**
     * Get afectaRevision.
     *
     * @return string|null
     */
    public function getAfectaRevision()
    {
        return $this->afectaRevision;
    }

    /**
     * Set excluirPlpage.
     *
     * @param string $excluirPlpage
     *
     * @return Ausencia
     */
    public function setExcluirPlpage($excluirPlpage)
    {
        $this->excluirPlpage = $excluirPlpage;

        return $this;
    }

    /**
     * Get excluirPlpage.
     *
     * @return string
     */
    public function getExcluirPlpage()
    {
        return $this->excluirPlpage;
    }

    /**
     * Set maxTotalH.
     *
     * @param string|null $maxTotalH
     *
     * @return Ausencia
     */
    public function setMaxTotalH($maxTotalH = null)
    {
        $this->maxTotalH = $maxTotalH;

        return $this;
    }

    /**
     * Get maxTotalH.
     *
     * @return string|null
     */
    public function getMaxTotalH()
    {
        return $this->maxTotalH;
    }

    /**
     * Set maxAnualH.
     *
     * @param string|null $maxAnualH
     *
     * @return Ausencia
     */
    public function setMaxAnualH($maxAnualH = null)
    {
        $this->maxAnualH = $maxAnualH;

        return $this;
    }

    /**
     * Get maxAnualH.
     *
     * @return string|null
     */
    public function getMaxAnualH()
    {
        return $this->maxAnualH;
    }

    /**
     * Set ausenciasrptid.
     *
     * @param int|null $ausenciasrptid
     *
     * @return Ausencia
     */
    public function setAusenciasrptid($ausenciasrptid = null)
    {
        $this->ausenciasrptid = $ausenciasrptid;

        return $this;
    }

    /**
     * Get ausenciasrptid.
     *
     * @return int|null
     */
    public function getAusenciasrptid()
    {
        return $this->ausenciasrptid;
    }

    /**
     * Set ausrptCodigo.
     *
     * @param string|null $ausrptCodigo
     *
     * @return Ausencia
     */
    public function setAusrptCodigo($ausrptCodigo = null)
    {
        $this->ausrptCodigo = $ausrptCodigo;

        return $this;
    }

    /**
     * Get ausrptCodigo.
     *
     * @return string|null
     */
    public function getAusrptCodigo()
    {
        return $this->ausrptCodigo;
    }

    /**
     * Set ausrptDescripcion.
     *
     * @param string|null $ausrptDescripcion
     *
     * @return Ausencia
     */
    public function setAusrptDescripcion($ausrptDescripcion = null)
    {
        $this->ausrptDescripcion = $ausrptDescripcion;

        return $this;
    }

    /**
     * Get ausrptDescripcion.
     *
     * @return string|null
     */
    public function getAusrptDescripcion()
    {
        return $this->ausrptDescripcion;
    }

    /**
     * Set finRed.
     *
     * @param string|null $finRed
     *
     * @return Ausencia
     */
    public function setFinRed($finRed = null)
    {
        $this->finRed = $finRed;

        return $this;
    }

    /**
     * Get finRed.
     *
     * @return string|null
     */
    public function getFinRed()
    {
        return $this->finRed;
    }

    /**
     * Set turnos.
     *
     * @param string|null $turnos
     *
     * @return Ausencia
     */
    public function setTurnos($turnos = null)
    {
        $this->turnos = $turnos;

        return $this;
    }

    /**
     * Get turnos.
     *
     * @return string|null
     */
    public function getTurnos()
    {
        return $this->turnos;
    }

    /**
     * Set cambiopuesto.
     *
     * @param string|null $cambiopuesto
     *
     * @return Ausencia
     */
    public function setCambiopuesto($cambiopuesto = null)
    {
        $this->cambiopuesto = $cambiopuesto;

        return $this;
    }

    /**
     * Get cambiopuesto.
     *
     * @return string|null
     */
    public function getCambiopuesto()
    {
        return $this->cambiopuesto;
    }

    /**
     * Set cuentaTurnic.
     *
     * @param string|null $cuentaTurnic
     *
     * @return Ausencia
     */
    public function setCuentaTurnic($cuentaTurnic = null)
    {
        $this->cuentaTurnic = $cuentaTurnic;

        return $this;
    }

    /**
     * Get cuentaTurnic.
     *
     * @return string|null
     */
    public function getCuentaTurnic()
    {
        return $this->cuentaTurnic;
    }

    /**
     * Set cuentaPago.
     *
     * @param string|null $cuentaPago
     *
     * @return Ausencia
     */
    public function setCuentaPago($cuentaPago = null)
    {
        $this->cuentaPago = $cuentaPago;

        return $this;
    }

    /**
     * Get cuentaPago.
     *
     * @return string|null
     */
    public function getCuentaPago()
    {
        return $this->cuentaPago;
    }

    /**
     * Set tipoInactividad.
     *
     * @param string|null $tipoInactividad
     *
     * @return Ausencia
     */
    public function setTipoInactividad($tipoInactividad = null)
    {
        $this->tipoInactividad = $tipoInactividad;

        return $this;
    }

    /**
     * Get tipoInactividad.
     *
     * @return string|null
     */
    public function getTipoInactividad()
    {
        return $this->tipoInactividad;
    }

    /**
     * Set mejoraIt.
     *
     * @param string $mejoraIt
     *
     * @return Ausencia
     */
    public function setMejoraIt($mejoraIt)
    {
        $this->mejoraIt = $mejoraIt;

        return $this;
    }

    /**
     * Get mejoraIt.
     *
     * @return string
     */
    public function getMejoraIt()
    {
        return $this->mejoraIt;
    }

    /**
     * Set destino.
     *
     * @param string|null $destino
     *
     * @return Ausencia
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
     * Set ctact.
     *
     * @param string|null $ctact
     *
     * @return Ausencia
     */
    public function setCtact($ctact = null)
    {
        $this->ctact = $ctact;

        return $this;
    }

    /**
     * Get ctact.
     *
     * @return string|null
     */
    public function getCtact()
    {
        return $this->ctact;
    }

    /**
     * Set janoApartado.
     *
     * @param string $janoApartado
     *
     * @return Ausencia
     */
    public function setJanoApartado($janoApartado)
    {
        $this->janoApartado = $janoApartado;

        return $this;
    }

    /**
     * Get janoApartado.
     *
     * @return string
     */
    public function getJanoApartado()
    {
        return $this->janoApartado;
    }

    /**
     * Set janoApd.
     *
     * @param string|null $janoApd
     *
     * @return Ausencia
     */
    public function setJanoApd($janoApd = null)
    {
        $this->janoApd = $janoApd;

        return $this;
    }

    /**
     * Get janoApd.
     *
     * @return string|null
     */
    public function getJanoApd()
    {
        return $this->janoApd;
    }

    /**
     * Set janoCodigo.
     *
     * @param string $janoCodigo
     *
     * @return Ausencia
     */
    public function setJanoCodigo($janoCodigo)
    {
        $this->janoCodigo = $janoCodigo;

        return $this;
    }

    /**
     * Get janoCodigo.
     *
     * @return string
     */
    public function getJanoCodigo()
    {
        return $this->janoCodigo;
    }

    /**
     * Set janoDescripcion.
     *
     * @param string $janoDescripcion
     *
     * @return Ausencia
     */
    public function setJanoDescripcion($janoDescripcion)
    {
        $this->janoDescripcion = $janoDescripcion;

        return $this;
    }

    /**
     * Get janoDescripcion.
     *
     * @return string
     */
    public function getJanoDescripcion()
    {
        return $this->janoDescripcion;
    }

    /**
     * Set janoDescripseg.
     *
     * @param string|null $janoDescripseg
     *
     * @return Ausencia
     */
    public function setJanoDescripseg($janoDescripseg = null)
    {
        $this->janoDescripseg = $janoDescripseg;

        return $this;
    }

    /**
     * Get janoDescripseg.
     *
     * @return string|null
     */
    public function getJanoDescripseg()
    {
        return $this->janoDescripseg;
    }

    /**
     * Set janoNombrelargo.
     *
     * @param string|null $janoNombrelargo
     *
     * @return Ausencia
     */
    public function setJanoNombrelargo($janoNombrelargo = null)
    {
        $this->janoNombrelargo = $janoNombrelargo;

        return $this;
    }

    /**
     * Get janoNombrelargo.
     *
     * @return string|null
     */
    public function getJanoNombrelargo()
    {
        return $this->janoNombrelargo;
    }

    /**
     * Set janoDldold.
     *
     * @param string|null $janoDldold
     *
     * @return Ausencia
     */
    public function setJanoDldold($janoDldold = null)
    {
        $this->janoDldold = $janoDldold;

        return $this;
    }

    /**
     * Get janoDldold.
     *
     * @return string|null
     */
    public function getJanoDldold()
    {
        return $this->janoDldold;
    }

    /**
     * Set janoVarold.
     *
     * @param string|null $janoVarold
     *
     * @return Ausencia
     */
    public function setJanoVarold($janoVarold = null)
    {
        $this->JanoVarold = $janoVarold;

        return $this;
    }

    /**
     * Get janoVarold.
     *
     * @return string|null
     */
    public function getJanoVarold()
    {
        return $this->JanoVarold;
    }

    /**
     * Set janoDocumento.
     *
     * @param string|null $janoDocumento
     *
     * @return Ausencia
     */
    public function setJanoDocumento($janoDocumento = null)
    {
        $this->janoDocumento = $janoDocumento;

        return $this;
    }

    /**
     * Get janoDocumento.
     *
     * @return string|null
     */
    public function getJanoDocumento()
    {
        return $this->janoDocumento;
    }

    /**
     * Set janoEnHoras.
     *
     * @param string $janoEnHoras
     *
     * @return Ausencia
     */
    public function setJanoEnHoras($janoEnHoras)
    {
        $this->janoEnHoras = $janoEnHoras;

        return $this;
    }

    /**
     * Get janoEnHoras.
     *
     * @return string
     */
    public function getJanoEnHoras()
    {
        return $this->janoEnHoras;
    }

    /**
     * Set janoFeclimant.
     *
     * @param \DateTime|null $janoFeclimant
     *
     * @return Ausencia
     */
    public function setJanoFeclimant($janoFeclimant = null)
    {
        $this->janoFeclimant = $janoFeclimant;

        return $this;
    }

    /**
     * Get janoFeclimant.
     *
     * @return \DateTime|null
     */
    public function getJanoFeclimant()
    {
        return $this->janoFeclimant;
    }

    /**
     * Set janoGrado.
     *
     * @param string $janoGrado
     *
     * @return Ausencia
     */
    public function setJanoGrado($janoGrado)
    {
        $this->janoGrado = $janoGrado;

        return $this;
    }

    /**
     * Get janoGrado.
     *
     * @return string
     */
    public function getJanoGrado()
    {
        return $this->janoGrado;
    }

    /**
     * Set janoGrautoriza.
     *
     * @param string $janoGrautoriza
     *
     * @return Ausencia
     */
    public function setJanoGrautoriza($janoGrautoriza)
    {
        $this->janoGrautoriza = $janoGrautoriza;

        return $this;
    }

    /**
     * Get janoGrautoriza.
     *
     * @return string
     */
    public function getJanoGrautoriza()
    {
        return $this->janoGrautoriza;
    }

    /**
     * Set janoResponsable.
     *
     * @param string $janoResponsable
     *
     * @return Ausencia
     */
    public function setJanoResponsable($janoResponsable)
    {
        $this->janoResponsable = $janoResponsable;

        return $this;
    }

    /**
     * Get janoResponsable.
     *
     * @return string
     */
    public function getJanoResponsable()
    {
        return $this->janoResponsable;
    }

    /**
     * Set ujanoUsuario.
     *
     * @param string $ujanoUsuario
     *
     * @return Ausencia
     */
    public function setUjanoUsuario($ujanoUsuario)
    {
        $this->ujanoUsuario = $ujanoUsuario;

        return $this;
    }

    /**
     * Get ujanoUsuario.
     *
     * @return string
     */
    public function getUjanoUsuario()
    {
        return $this->ujanoUsuario;
    }

    /**
     * Set janoResto.
     *
     * @param string $janoResto
     *
     * @return Ausencia
     */
    public function setJanoResto($janoResto)
    {
        $this->janoResto = $janoResto;

        return $this;
    }

    /**
     * Get janoResto.
     *
     * @return string
     */
    public function getJanoResto()
    {
        return $this->janoResto;
    }

    /**
     * Set janoLocalidad.
     *
     * @param string $janoLocalidad
     *
     * @return Ausencia
     */
    public function setJanoLocalidad($janoLocalidad)
    {
        $this->janoLocalidad = $janoLocalidad;

        return $this;
    }

    /**
     * Get janoLocalidad.
     *
     * @return string
     */
    public function getJanoLocalidad()
    {
        return $this->janoLocalidad;
    }

    /**
     * Set janoMaxadelanto.
     *
     * @param int|null $janoMaxadelanto
     *
     * @return Ausencia
     */
    public function setJanoMaxadelanto($janoMaxadelanto = null)
    {
        $this->janoMaxadelanto = $janoMaxadelanto;

        return $this;
    }

    /**
     * Get janoMaxadelanto.
     *
     * @return int|null
     */
    public function getJanoMaxadelanto()
    {
        return $this->janoMaxadelanto;
    }

    /**
     * Set janoMaxlab.
     *
     * @param int|null $janoMaxlab
     *
     * @return Ausencia
     */
    public function setJanoMaxlab($janoMaxlab = null)
    {
        $this->janoMaxlab = $janoMaxlab;

        return $this;
    }

    /**
     * Get janoMaxlab.
     *
     * @return int|null
     */
    public function getJanoMaxlab()
    {
        return $this->janoMaxlab;
    }

    /**
     * Set janoMaxnat.
     *
     * @param int|null $janoMaxnat
     *
     * @return Ausencia
     */
    public function setJanoMaxnat($janoMaxnat = null)
    {
        $this->janoMaxnat = $janoMaxnat;

        return $this;
    }

    /**
     * Get janoMaxnat.
     *
     * @return int|null
     */
    public function getJanoMaxnat()
    {
        return $this->janoMaxnat;
    }

    /**
     * Set janoMir.
     *
     * @param string $janoMir
     *
     * @return Ausencia
     */
    public function setJanoMir($janoMir)
    {
        $this->janoMir = $janoMir;

        return $this;
    }

    /**
     * Get janoMir.
     *
     * @return string
     */
    public function getJanoMir()
    {
        return $this->janoMir;
    }

    /**
     * Set janoSar.
     *
     * @param string $janoSar
     *
     * @return Ausencia
     */
    public function setJanoSar($janoSar)
    {
        $this->janoSar = $janoSar;

        return $this;
    }

    /**
     * Get janoSar.
     *
     * @return string
     */
    public function getJanoSar()
    {
        return $this->janoSar;
    }

    /**
     * Set janoSumaDiasCont.
     *
     * @param int $janoSumaDiasCont
     *
     * @return Ausencia
     */
    public function setJanoSumaDiasCont($janoSumaDiasCont)
    {
        $this->janoSumaDiasCont = $janoSumaDiasCont;

        return $this;
    }

    /**
     * Get janoSumaDiasCont.
     *
     * @return int
     */
    public function getJanoSumaDiasCont()
    {
        return $this->janoSumaDiasCont;
    }

    /**
     * Set janoSumaDiasDisc.
     *
     * @param int $janoSumaDiasDisc
     *
     * @return Ausencia
     */
    public function setJanoSumaDiasDisc($janoSumaDiasDisc)
    {
        $this->janoSumaDiasDisc = $janoSumaDiasDisc;

        return $this;
    }

    /**
     * Get janoSumaDiasDisc.
     *
     * @return int
     */
    public function getJanoSumaDiasDisc()
    {
        return $this->janoSumaDiasDisc;
    }

    /**
     * Set tipoIlt.
     *
     * @param \MaestrosBundle\Entity\TipoIlt|null $tipoIlt
     *
     * @return Ausencia
     */
    public function setTipoIlt(\MaestrosBundle\Entity\TipoIlt $tipoIlt = null)
    {
        $this->tipoIlt = $tipoIlt;

        return $this;
    }

    /**
     * Get tipoIlt.
     *
     * @return \MaestrosBundle\Entity\TipoIlt|null
     */
    public function getTipoIlt()
    {
        return $this->tipoIlt;
    }

    /**
     * Set fco.
     *
     * @param \MaestrosBundle\Entity\Fco|null $fco
     *
     * @return Ausencia
     */
    public function setFco(\MaestrosBundle\Entity\Fco $fco = null)
    {
        $this->fco = $fco;

        return $this;
    }

    /**
     * Get fco.
     *
     * @return \MaestrosBundle\Entity\Fco|null
     */
    public function getFco()
    {
        return $this->fco;
    }

    /**
     * Set modOcupa.
     *
     * @param \MaestrosBundle\Entity\ModOcupa|null $modOcupa
     *
     * @return Ausencia
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
     * Set epiAcc.
     *
     * @param \MaestrosBundle\Entity\EpiAcc|null $epiAcc
     *
     * @return Ausencia
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
     * Set moviPat.
     *
     * @param \MaestrosBundle\Entity\MoviPat|null $moviPat
     *
     * @return Ausencia
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

    /**
     * Set ocupacion.
     *
     * @param \MaestrosBundle\Entity\Ocupacion|null $ocupacion
     *
     * @return Ausencia
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

    /**
     * Set ocupacionNew.
     *
     * @param \MaestrosBundle\Entity\Ocupacion|null $ocupacionNew
     *
     * @return Ausencia
     */
    public function setOcupacionNew(\MaestrosBundle\Entity\Ocupacion $ocupacionNew = null)
    {
        $this->ocupacionNew = $ocupacionNew;

        return $this;
    }

    /**
     * Get ocupacionNew.
     *
     * @return \MaestrosBundle\Entity\Ocupacion|null
     */
    public function getOcupacionNew()
    {
        return $this->ocupacionNew;
    }

    /**
     * Set documPermiso.
     *
     * @param \MaestrosBundle\Entity\DocumPermiso|null $documPermiso
     *
     * @return Ausencia
     */
    public function setDocumPermiso(\MaestrosBundle\Entity\DocumPermiso $documPermiso = null)
    {
        $this->documPermiso = $documPermiso;

        return $this;
    }

    /**
     * Get documPermiso.
     *
     * @return \MaestrosBundle\Entity\DocumPermiso|null
     */
    public function getDocumPermiso()
    {
        return $this->documPermiso;
    }

    /**
     * Set janoConn2annos.
     *
     * @param string $janoConn2annos
     *
     * @return Ausencia
     */
    public function setJanoConn2annos($janoConn2annos)
    {
        $this->janoConn2annos = $janoConn2annos;

        return $this;
    }

    /**
     * Get janoConn2annos.
     *
     * @return string
     */
    public function getJanoConn2annos()
    {
        return $this->janoConn2annos;
    }

    /**
     * Set janoUsuario.
     *
     * @param string $janoUsuario
     *
     * @return Ausencia
     */
    public function setJanoUsuario($janoUsuario)
    {
        $this->janoUsuario = $janoUsuario;

        return $this;
    }

    /**
     * Get janoUsuario.
     *
     * @return string
     */
    public function getJanoUsuario()
    {
        return $this->janoUsuario;
    }

    /**
     * Set janoFecfinAbierta.
     *
     * @param string|null $janoFecfinAbierta
     *
     * @return Ausencia
     */
    public function setJanoFecfinAbierta($janoFecfinAbierta = null)
    {
        $this->janoFecfinAbierta = $janoFecfinAbierta;

        return $this;
    }

    /**
     * Get janoFecfinAbierta.
     *
     * @return string|null
     */
    public function getJanoFecfinAbierta()
    {
        return $this->janoFecfinAbierta;
    }

    /**
     * Set janoDocumPermiso.
     *
     * @param \MaestrosBundle\Entity\DocumPermiso|null $janoDocumPermiso
     *
     * @return Ausencia
     */
    public function setJanoDocumPermiso(\MaestrosBundle\Entity\DocumPermiso $janoDocumPermiso = null)
    {
        $this->janoDocumPermiso = $janoDocumPermiso;

        return $this;
    }

    /**
     * Get janoDocumPermiso.
     *
     * @return \MaestrosBundle\Entity\DocumPermiso|null
     */
    public function getJanoDocumPermiso()
    {
        return $this->janoDocumPermiso;
    }

    /**
     * Set itContadorJano.
     *
     * @param string|null $itContadorJano
     *
     * @return Ausencia
     */
    public function setItContadorJano($itContadorJano = null)
    {
        $this->itContadorJano = $itContadorJano;

        return $this;
    }

    /**
     * Get itContadorJano.
     *
     * @return string|null
     */
    public function getItContadorJano()
    {
        return $this->itContadorJano;
    }

    /**
     * Set sincroLog.
     *
     * @param \ComunBundle\Entity\SincroLog|null $sincroLog
     *
     * @return Ausencia
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
