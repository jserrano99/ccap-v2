<?php

/**
 * Description of Categ
 *
 * @author jluis
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categ
 *
 * @ORM\Table(name="ccap_categ"
 *  *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *            ,indexes={@ORM\Index(name="idx001", columns={"codigo"})}
 *           )
 * @ORM\Entity
 */
class Categ {

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
     * @ORM\Column(name="codigo", type="string", length=4, nullable=false)
     */
    private $codigo;

    /**
     * @var CatGen|null
     *
     * @ORM\ManyToOne(targetEntity="CatGen")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="catgen_id", referencedColumnName="id")
     * })
     */
    private $catGen;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="fsn", type="string", length=1, nullable=false)
     */
    private $fsn;

    /**
     * @var CatAnexo|null
     *
     * @ORM\ManyToOne(targetEntity="CatAnexo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="catAnexo_id", referencedColumnName="id")
     * })
     */
    private $catAnexo;

    
    /**
     * @var GrupoCot|null
     *
     * @ORM\ManyToOne(targetEntity="GrupoCot")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupocot_id", referencedColumnName="id")
     * })
     */
    private $grupoCot;

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
     * @var GrupoProf|null
     *
     * @ORM\ManyToOne(targetEntity="GrupoProf")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupoprof_id", referencedColumnName="id")
     * })
     */
    private $grupoProf;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=false)
     */
    private $enuso;

    /**
     * @var string
     *
     * @ORM\Column(name="categ_orden", type="string", length=2, nullable=true)
     */
    private $categOrden;

    /**
     * @var GrupoCobro|null
     *
     * @ORM\ManyToOne(targetEntity="GrupoCobro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupocobro_id", referencedColumnName="id")
     * })
     */
    private $grupoCobro;

    /**
     * @var string
     *
     * @ORM\Column(name="categoriarptid", type="integer", nullable=true)
     */
    private $categoriarptid;

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
     * @var string
     *
     * @ORM\Column(name="catrpt_codigo", type="string",length=10, nullable=true)
     */
    private $catrptCodigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="catrpt_descripcion", type="string",length=100, nullable=true)
     */
    private $catrptDescripcion;
    
    /**
     * @var Boolean
     *
     * @ORM\Column(name="mir", type="boolean", nullable=true)
     */
    private $mir;
    
    /**
     * @var string
     *
     * @ORM\Column(name="categ_sms", type="string",length=5, nullable=true)
     */
    private $categSms;
    
    /**
     * @var string
     *
     * @ORM\Column(name="grupo_tit", type="string",length=1, nullable=true)
     */
    private $grupoTit;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prof_san", type="string",length=2, nullable=true)
     */
    private $profSan;
    
    /**
     * @var string
     *
     * @ORM\Column(name="directivo", type="string",length=1, nullable=true)
     */
    private $directivo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cno2011", type="string",length=4, nullable=true)
     */
    private $cno2011;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ceco_personal", type="string",length=2, nullable=true)
     */
    private $cecoPersonal;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ceco_categoria", type="string",length=3, nullable=true)
     */
    private $cecoCategoria;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipocat", type="string",length=2, nullable=true)
     */
    private $tipoCat;
    
    /**
     * @var string
     *
     * @ORM\Column(name="condicionado", type="string",length=1, nullable=false)
     */
    private $condicionado;
    
    /**
     * @var string
     *
     * @ORM\Column(name="id_grupocat", type="integer", nullable=true)
     */
    private $idGrupoCat;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Categ
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
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
     * @param string $descripcion
     *
     * @return Categ
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fsn
     *
     * @param string $fsn
     *
     * @return Categ
     */
    public function setFsn($fsn)
    {
        $this->fsn = $fsn;

        return $this;
    }

    /**
     * Get fsn
     *
     * @return string
     */
    public function getFsn()
    {
        return $this->fsn;
    }

    

    /**
     * Set enuso
     *
     * @param string $enuso
     *
     * @return Categ
     */
    public function setEnUso($enuso)
    {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso
     *
     * @return string
     */
    public function getEnUso()
    {
        return $this->enuso;
    }

    /**
     * Set categOrden
     *
     * @param string $categOrden
     *
     * @return Categ
     */
    public function setCategOrden($categOrden)
    {
        $this->categOrden = $categOrden;

        return $this;
    }

    /**
     * Get categOrden
     *
     * @return string
     */
    public function getCategOrden()
    {
        return $this->categOrden;
    }

    /**
     * Set categoriarptid
     *
     * @param integer $categoriarptid
     *
     * @return Categ
     */
    public function setCategoriarptid($categoriarptid)
    {
        $this->categoriarptid = $categoriarptid;

        return $this;
    }

    /**
     * Get categoriarptid
     *
     * @return integer
     */
    public function getCategoriarptid()
    {
        return $this->categoriarptid;
    }

    /**
     * Set catrptCodigo
     *
     * @param string $catrptCodigo
     *
     * @return Categ
     */
    public function setCatrptCodigo($catrptCodigo)
    {
        $this->catrptCodigo = $catrptCodigo;

        return $this;
    }

    /**
     * Get catrptCodigo
     *
     * @return string
     */
    public function getCatrptCodigo()
    {
        return $this->catrptCodigo;
    }

    /**
     * Set catrptDescripcion
     *
     * @param string $catrptDescripcion
     *
     * @return Categ
     */
    public function setCatrptDescripcion($catrptDescripcion)
    {
        $this->catrptDescripcion = $catrptDescripcion;

        return $this;
    }

    /**
     * Get catrptDescripcion
     *
     * @return string
     */
    public function getCatrptDescripcion()
    {
        return $this->catrptDescripcion;
    }

    /**
     * Set mir
     *
     * @param boolean $mir
     *
     * @return Categ
     */
    public function setMir($mir)
    {
        $this->mir = $mir;

        return $this;
    }

    /**
     * Get mir
     *
     * @return boolean
     */
    public function getMir()
    {
        return $this->mir;
    }

    /**
     * Set categSms
     *
     * @param string $categSms
     *
     * @return Categ
     */
    public function setCategSms($categSms)
    {
        $this->categSms = $categSms;

        return $this;
    }

    /**
     * Get categSms
     *
     * @return string
     */
    public function getCategSms()
    {
        return $this->categSms;
    }

    /**
     * Set grupoTit
     *
     * @param string $grupoTit
     *
     * @return Categ
     */
    public function setGrupoTit($grupoTit)
    {
        $this->grupoTit = $grupoTit;

        return $this;
    }

    /**
     * Get grupoTit
     *
     * @return string
     */
    public function getGrupoTit()
    {
        return $this->grupoTit;
    }

    /**
     * Set profSan
     *
     * @param string $profSan
     *
     * @return Categ
     */
    public function setProfSan($profSan)
    {
        $this->profSan = $profSan;

        return $this;
    }

    /**
     * Get profSan
     *
     * @return string
     */
    public function getProfSan()
    {
        return $this->profSan;
    }

    /**
     * Set directivo
     *
     * @param string $directivo
     *
     * @return Categ
     */
    public function setDirectivo($directivo)
    {
        $this->directivo = $directivo;

        return $this;
    }

    /**
     * Get directivo
     *
     * @return string
     */
    public function getDirectivo()
    {
        return $this->directivo;
    }

    /**
     * Set cno2011
     *
     * @param string $cno2011
     *
     * @return Categ
     */
    public function setCno2011($cno2011)
    {
        $this->cno2011 = $cno2011;

        return $this;
    }

    /**
     * Get cno2011
     *
     * @return string
     */
    public function getCno2011()
    {
        return $this->cno2011;
    }

    /**
     * Set cecoPersonal
     *
     * @param string $cecoPersonal
     *
     * @return Categ
     */
    public function setCecoPersonal($cecoPersonal)
    {
        $this->cecoPersonal = $cecoPersonal;

        return $this;
    }

    /**
     * Get cecoPersonal
     *
     * @return string
     */
    public function getCecoPersonal()
    {
        return $this->cecoPersonal;
    }

    /**
     * Set cecoCategoria
     *
     * @param string $cecoCategoria
     *
     * @return Categ
     */
    public function setCecoCategoria($cecoCategoria)
    {
        $this->cecoCategoria = $cecoCategoria;

        return $this;
    }

    /**
     * Get cecoCategoria
     *
     * @return string
     */
    public function getCecoCategoria()
    {
        return $this->cecoCategoria;
    }

    /**
     * Set tipoCat
     *
     * @param string $tipoCat
     *
     * @return Categ
     */
    public function setTipoCat($tipoCat)
    {
        $this->tipoCat = $tipoCat;

        return $this;
    }

    /**
     * Get tipoCat
     *
     * @return string
     */
    public function getTipoCat()
    {
        return $this->tipoCat;
    }

    /**
     * Set condicionado
     *
     * @param string $condicionado
     *
     * @return Categ
     */
    public function setCondicionado($condicionado)
    {
        $this->condicionado = $condicionado;

        return $this;
    }

    /**
     * Get condicionado
     *
     * @return string
     */
    public function getCondicionado()
    {
        return $this->condicionado;
    }

    /**
     * Set idGrupoCat
     *
     * @param integer $idGrupoCat
     *
     * @return Categ
     */
    public function setIdGrupoCat($idGrupoCat)
    {
        $this->idGrupoCat = $idGrupoCat;

        return $this;
    }

    /**
     * Get idGrupoCat
     *
     * @return integer
     */
    public function getIdGrupoCat()
    {
        return $this->idGrupoCat;
    }

    /**
     * Set catGen
     *
     * @param \AppBundle\Entity\CatGen $catGen
     *
     * @return Categ
     */
    public function setCatGen(\AppBundle\Entity\CatGen $catGen = null)
    {
        $this->catGen = $catGen;

        return $this;
    }

    /**
     * Get catGen
     *
     * @return \AppBundle\Entity\CatGen|null
     */
    public function getCatGen()
    {
        return $this->catGen;
    }

    /**
     * Set catAnexo
     *
     * @param \AppBundle\Entity\CatAnexo $catAnexo
     *
     * @return Categ
     */
    public function setCatAnexo(\AppBundle\Entity\CatAnexo $catAnexo = null)
    {
        $this->catAnexo = $catAnexo;

        return $this;
    }

    /**
     * Get catAnexo
     *
     * @return \AppBundle\Entity\CatAnexo|null
     */
    public function getCatAnexo()
    {
        return $this->catAnexo;
    }

    /**
     * Set grupoCot
     *
     * @param \AppBundle\Entity\GrupoCot $grupoCot
     *
     * @return Categ
     */
    public function setGrupoCot(\AppBundle\Entity\GrupoCot $grupoCot = null)
    {
        $this->grupoCot = $grupoCot;

        return $this;
    }

    /**
     * Get grupoCot
     *
     * @return \AppBundle\Entity\GrupoCot|null
     */
    public function getGrupoCot()
    {
        return $this->grupoCot;
    }

    /**
     * Set grupoProf
     *
     * @param \AppBundle\Entity\GrupoProf $grupoProf
     *
     * @return Categ
     */
    public function setGrupoProf(\AppBundle\Entity\GrupoProf $grupoProf = null)
    {
        $this->grupoProf = $grupoProf;

        return $this;
    }

    /**
     * Get grupoProf
     *
     * @return \AppBundle\Entity\GrupoProf|null
     */
    public function getGrupoProf()
    {
        return $this->grupoProf;
    }

    /**
     * Set grupoCobro
     *
     * @param \AppBundle\Entity\GrupoCobro $grupoCobro
     *
     * @return Categ
     */
    public function setGrupoCobro(\AppBundle\Entity\GrupoCobro $grupoCobro = null)
    {
        $this->grupoCobro = $grupoCobro;

        return $this;
    }

    /**
     * Get grupoCobro
     *
     * @return \AppBundle\Entity\GrupoCobro|null
     */
    public function getGrupoCobro()
    {
        return $this->grupoCobro;
    }

    /**
     * Set ocupacion
     *
     * @param \AppBundle\Entity\Ocupacion $ocupacion
     *
     * @return Categ
     */
    public function setOcupacion(\AppBundle\Entity\Ocupacion $ocupacion = null)
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    /**
     * Get ocupacion
     *
     * @return \AppBundle\Entity\Ocupacion|null
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }

    /**
     * Set epiAcc
     *
     * @param \AppBundle\Entity\EpiAcc $epiAcc
     *
     * @return Categ
     */
    public function setEpiAcc(\AppBundle\Entity\EpiAcc $epiAcc = null)
    {
        $this->epiAcc = $epiAcc;

        return $this;
    }

    /**
     * Get epiAcc
     *
     * @return \AppBundle\Entity\EpiAcc|null
     */
    public function getEpiAcc()
    {
        return $this->epiAcc;
    }
}
