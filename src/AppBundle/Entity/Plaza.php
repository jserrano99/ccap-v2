<?php

/**
 * Description of Plaza
 *
 * @author jluis
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Uf;
use AppBundle\Entity\Pa;
use AppBundle\Entity\CatFp;
use AppBundle\Entity\CatGen;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Plaza
 *
 * @ORM\Table(name="ccap_plazas",
 *            uniqueConstraints={@ORM\UniqueConstraint(name="cias_uk", columns={"cias"})},
 *            indexes={@ORM\Index(name="idx001", columns={"uf_id"}), 
 *                     @ORM\Index(name="idx002", columns={"pa_id"}),
 *                     @ORM\Index(name="idx003", columns={"catgen_id"}),
 *                     @ORM\Index(name="idx004", columns={"catfp_id"}),
 *                     @ORM\Index(name="idx005", columns={"modalidad_id"}),
 *                     @ORM\Index(name="idx006", columns={"ceco_id"})
 *                    }
 *           )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlazaRepository")
 */
class Plaza {

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
     *   @ORM\JoinColumn(name="uf_id", referencedColumnName="id")
     */
    private $uf;

    /**
     * @var Pa|null
     *
     * @ORM\ManyToOne(targetEntity="Pa") 
     *   @ORM\JoinColumn(name="pa_id", referencedColumnName="id")
     */
    private $pa;

    /**
     * @var CatGen|null
     *
     * @ORM\ManyToOne(targetEntity="CatGen") 
     *   @ORM\JoinColumn(name="catgen_id", referencedColumnName="id")
     */
    private $catGen;

    /**
     * @var Modalidad|null
     *
     * @ORM\ManyToOne(targetEntity="Modalidad") 
     *   @ORM\JoinColumn(name="modalidad_id", referencedColumnName="id")
     */
    private $modalidad;

    /**
     * @var CatFp|null
     *
     * @ORM\ManyToOne(targetEntity="CatFp") 
     *   @ORM\JoinColumn(name="catfp_id", referencedColumnName="id")
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
     * @var date
     *
     * @ORM\Column(name="f_amortiza", type="date", nullable=true)
     */
    private $fAmortiza;

    /**
     * @var date
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
     * @var Ceco|null
     *
     * @ORM\ManyToOne(targetEntity="Ceco")
     *   @ORM\JoinColumn(name="ceco_id", referencedColumnName="id")
     */
    private $ceco;
    
    /**
     * @var string
     *
     * @ORM\Column(name="amortizada", type="string", length=1, nullable=true)
     */
    private $amortizada;

    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set cias
     *
     * @param string $cias
     *
     * @return Plaza
     */
    public function setCias($cias) {
        $this->cias = $cias;

        return $this;
    }

    /**
     * Get cias
     *
     * @return string
     */
    public function getCias() {
        return $this->cias;
    }

    /**
     * Set plantilla
     *
     * @param string $plantilla
     *
     * @return Plaza
     */
    public function setPlantilla($plantilla) {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return string
     */
    public function getPlantilla() {
        return $this->plantilla;
    }

    /**
     * Set fAmortiza
     *
     * @param \DateTime $fAmortiza
     *
     * @return Plaza
     */
    public function setFAmortiza($fAmortiza) {
        $this->fAmortiza = $fAmortiza;

        return $this;
    }

    /**
     * Get fAmortiza
     *
     * @return \DateTime
     */
    public function getFAmortiza() {
        return $this->fAmortiza;
    }

    /**
     * Set uf
     *
     * @param \AppBundle\Entity\Uf $uf
     *
     * @return Plaza
     */
    public function setUf(\AppBundle\Entity\Uf $uf = null) {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Get uf
     *
     * @return \AppBundle\Entity\Uf
     */
    public function getUf() {
        return $this->uf;
    }

    /**
     * Set pa
     *
     * @param \AppBundle\Entity\Pa $pa
     *
     * @return Plaza
     */
    public function setPa(\AppBundle\Entity\Pa $pa = null) {
        $this->pa = $pa;

        return $this;
    }

    /**
     * Get pa
     *
     * @return \AppBundle\Entity\Pa
     */
    public function getPa() {
        return $this->pa;
    }

    /**
     * Set catGen
     *
     * @param \AppBundle\Entity\CatGen $catGen
     *
     * @return Plaza
     */
    public function setCatGen(\AppBundle\Entity\CatGen $catGen = null) {
        $this->catGen = $catGen;

        return $this;
    }

    /**
     * Get catGen
     *
     * @return \AppBundle\Entity\CatGen
     */
    public function getCatGen() {
        return $this->catGen;
    }

    /**
     * Set catFp
     *
     * @param \AppBundle\Entity\CatFp $catFp
     *
     * @return Plaza
     */
    public function setCatFp(\AppBundle\Entity\CatFp $catFp = null) {
        $this->catFp = $catFp;

        return $this;
    }

    /**
     * Get catFp
     *
     * @return \AppBundle\Entity\CatFp
     */
    public function getCatFp() {
        return $this->catFp;
    }

    public function __toString() {
        return $this->cias;
    }

    /**
     * Set modalidad
     *
     * @param \AppBundle\Entity\Modalidad $modalidad
     *
     * @return Plaza
     */
    public function setModalidad(\AppBundle\Entity\Modalidad $modalidad = null) {
        $this->modalidad = $modalidad;

        return $this;
    }

    /**
     * Get modalidad
     *
     * @return \AppBundle\Entity\Modalidad|null
     */
    public function getModalidad() {
        return $this->modalidad;
    }

    
    /**
     * Set fCreacion
     *
     * @param \DateTime $fCreacion
     *
     * @return Plaza
     */
    public function setFCreacion($fCreacion) {
        $this->fCreacion = $fCreacion;

        return $this;
    }

    /**
     * Get fCreacion
     *
     * @return \DateTime
     */
    public function getFCreacion() {
        return $this->fCreacion;
    }


    /**
     * Set cupequi
     *
     * @param string $cupequi
     *
     * @return Plaza
     */
    public function setCupequi($cupequi)
    {
        $this->cupequi = $cupequi;

        return $this;
    }

    /**
     * Get cupequi
     *
     * @return string
     */
    public function getCupequi()
    {
        return $this->cupequi;
    }

    /**
     * Set colaboradora
     *
     * @param string $colaboradora
     *
     * @return Plaza
     */
    public function setColaboradora($colaboradora)
    {
        $this->colaboradora = $colaboradora;

        return $this;
    }

    /**
     * Get colaboradora
     *
     * @return string
     */
    public function getColaboradora()
    {
        return $this->colaboradora;
    }

    /**
     * Set ficticia
     *
     * @param string $ficticia
     *
     * @return Plaza
     */
    public function setFicticia($ficticia)
    {
        $this->ficticia = $ficticia;

        return $this;
    }

    /**
     * Get ficticia
     *
     * @return string
     */
    public function getFicticia()
    {
        return $this->ficticia;
    }

    /**
     * Set refuerzo
     *
     * @param string $refuerzo
     *
     * @return Plaza
     */
    public function setRefuerzo($refuerzo)
    {
        $this->refuerzo = $refuerzo;

        return $this;
    }

    /**
     * Get refuerzo
     *
     * @return string
     */
    public function getRefuerzo()
    {
        return $this->refuerzo;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Plaza
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set horNormal
     *
     * @param string $horNormal
     *
     * @return Plaza
     */
    public function setHorNormal($horNormal)
    {
        $this->horNormal = $horNormal;

        return $this;
    }

    /**
     * Get horNormal
     *
     * @return string
     */
    public function getHorNormal()
    {
        return $this->horNormal;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return Plaza
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set turno
     *
     * @param string $turno
     *
     * @return Plaza
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return string
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set ceco
     *
     * @param \AppBundle\Entity\Ceco $ceco
     *
     * @return Plaza
     */
    public function setCeco(\AppBundle\Entity\Ceco $ceco = null)
    {
        $this->ceco = $ceco;

        return $this;
    }

    /**
     * Get ceco
     *
     * @return \AppBundle\Entity\Ceco|null
     */
    public function getCeco()
    {
        return $this->ceco;
    }

    /**
     * Set amortizada
     *
     * @param string $amortizada
     *
     * @return Plaza
     */
    public function setAmortizada($amortizada)
    {
        $this->amortizada = $amortizada;

        return $this;
    }

    /**
     * Get amortizada
     *
     * @return string
     */
    public function getAmortizada()
    {
        return $this->amortizada;
    }
}
