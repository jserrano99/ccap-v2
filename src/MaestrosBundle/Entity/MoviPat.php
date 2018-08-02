<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MoviPat
 *
 * @ORM\Table(name="gums_movipat"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\MoviPatRepository")
 */
class MoviPat {

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
     * @ORM\Column(name="descrip", type="string", length=25, nullable=true)
     */
    private $descrip;

    /**
     * @var string
     *
     * @ORM\Column(name="cif", type="string", length=9, nullable=true)
     */
    private $cif;

    /**
     * @var decimal
     *
     * @ORM\Column(name="pat_contin", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $patContin;

    /**
     * @var decimal
     *
     * @ORM\Column(name="obr_contin", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $obrContin;

    /**
     * @var decimal
     *
     * @ORM\Column(name="pat_he", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $patHe;

    /**
     * @var decimal
     *
     * @ORM\Column(name="obr_he", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $obrHe;

    /**
     * @var decimal
     *
     * @ORM\Column(name="pat_acc", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $patAcc;

    /**
     * @var decimal
     *
     * @ORM\Column(name="obr_acc", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $obrAcc;

    /**
     * @var decimal
     *
     * @ORM\Column(name="pat_fp", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $patFp;

    /**
     * @var decimal
     *
     * @ORM\Column(name="obr_fp", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $obrFp;

    /**
     * @var decimal
     *
     * @ORM\Column(name="fogasa", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $fogasa;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroseg", type="string", length=14, nullable=true)
     */
    private $numeroSeg;

    /**
     * @var string
     *
     * @ORM\Column(name="empresa", type="string", length=50, nullable=true)
     */
    private $empresa;

    /**
     * @var decimal
     *
     * @ORM\Column(name="pat_munpal", type="decimal", precision=5, scale= 3 , nullable=true)
     */
    private $patMunpal;

    /**
     * @var decimal
     *
     * @ORM\Column(name="obr_munpal", type="decimal", precision=5, scale= 3 , nullable=true)
     */
    private $obrMunpal;

    /**
     * @var decimal
     *
     * @ORM\Column(name="pat_integra", type="decimal", precision=5, scale= 3 , nullable=true)
     */
    private $patIntegra;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=false, options={"default":"S"})
     */
    private $enUso;

    /**
     * @var string
     *
     * @ORM\Column(name="clave", type="string", length=2, nullable=true,options={"default":"08"})
     */
    private $clave;

    /**
     * @var string
     *
     * @ORM\Column(name="eventual", type="string", length=1, nullable=false, options={"default":"N"})
     */
    private $eventual;

    /**
     * @var decimal
     *
     * @ORM\Column(name="porcent", type="decimal", precision=7, scale= 3 , nullable=true)
     */
    private $porcent;

    /**
     * @var decimal
     *
     * @ORM\Column(name="pat_acc_ant", type="decimal", precision=5, scale= 2 , nullable=true)
     */
    private $patAccAnt;

    /**
     * @var string
     *
     * @ORM\Column(name="forzar_l00", type="string", length=1, nullable=true, options={"default":"N"})
     */
    private $forzarL00;

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
     * @return MoviPat
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
     * Set descrip.
     *
     * @param string|null $descrip
     *
     * @return MoviPat
     */
    public function setDescrip($descrip = null) {
        $this->descrip = $descrip;

        return $this;
    }

    /**
     * Get descrip.
     *
     * @return string|null
     */
    public function getDescrip() {
        return $this->descrip;
    }

    /**
     * Set cif.
     *
     * @param string|null $cif
     *
     * @return MoviPat
     */
    public function setCif($cif = null) {
        $this->cif = $cif;

        return $this;
    }

    /**
     * Get cif.
     *
     * @return string|null
     */
    public function getCif() {
        return $this->cif;
    }

    /**
     * Set patContin.
     *
     * @param string|null $patContin
     *
     * @return MoviPat
     */
    public function setPatContin($patContin = null) {
        $this->patContin = $patContin;

        return $this;
    }

    /**
     * Get patContin.
     *
     * @return string|null
     */
    public function getPatContin() {
        return $this->patContin;
    }

    /**
     * Set obrContin.
     *
     * @param string|null $obrContin
     *
     * @return MoviPat
     */
    public function setObrContin($obrContin = null) {
        $this->obrContin = $obrContin;

        return $this;
    }

    /**
     * Get obrContin.
     *
     * @return string|null
     */
    public function getObrContin() {
        return $this->obrContin;
    }

    /**
     * Set patHe.
     *
     * @param string|null $patHe
     *
     * @return MoviPat
     */
    public function setPatHe($patHe = null) {
        $this->patHe = $patHe;

        return $this;
    }

    /**
     * Get patHe.
     *
     * @return string|null
     */
    public function getPatHe() {
        return $this->patHe;
    }

    /**
     * Set obrHe.
     *
     * @param string|null $obrHe
     *
     * @return MoviPat
     */
    public function setObrHe($obrHe = null) {
        $this->obrHe = $obrHe;

        return $this;
    }

    /**
     * Get obrHe.
     *
     * @return string|null
     */
    public function getObrHe() {
        return $this->obrHe;
    }

    /**
     * Set patAcc.
     *
     * @param string|null $patAcc
     *
     * @return MoviPat
     */
    public function setPatAcc($patAcc = null) {
        $this->patAcc = $patAcc;

        return $this;
    }

    /**
     * Get patAcc.
     *
     * @return string|null
     */
    public function getPatAcc() {
        return $this->patAcc;
    }

    /**
     * Set obrAcc.
     *
     * @param string|null $obrAcc
     *
     * @return MoviPat
     */
    public function setObrAcc($obrAcc = null) {
        $this->obrAcc = $obrAcc;

        return $this;
    }

    /**
     * Get obrAcc.
     *
     * @return string|null
     */
    public function getObrAcc() {
        return $this->obrAcc;
    }

    /**
     * Set patFp.
     *
     * @param string|null $patFp
     *
     * @return MoviPat
     */
    public function setPatFp($patFp = null) {
        $this->patFp = $patFp;

        return $this;
    }

    /**
     * Get patFp.
     *
     * @return string|null
     */
    public function getPatFp() {
        return $this->patFp;
    }

    /**
     * Set obrFp.
     *
     * @param string|null $obrFp
     *
     * @return MoviPat
     */
    public function setObrFp($obrFp = null) {
        $this->obrFp = $obrFp;

        return $this;
    }

    /**
     * Get obrFp.
     *
     * @return string|null
     */
    public function getObrFp() {
        return $this->obrFp;
    }

    /**
     * Set fogasa.
     *
     * @param string|null $fogasa
     *
     * @return MoviPat
     */
    public function setFogasa($fogasa = null) {
        $this->fogasa = $fogasa;

        return $this;
    }

    /**
     * Get fogasa.
     *
     * @return string|null
     */
    public function getFogasa() {
        return $this->fogasa;
    }

    /**
     * Set numeroSeg.
     *
     * @param string|null $numeroSeg
     *
     * @return MoviPat
     */
    public function setNumeroSeg($numeroSeg = null) {
        $this->numeroSeg = $numeroSeg;

        return $this;
    }

    /**
     * Get numeroSeg.
     *
     * @return string|null
     */
    public function getNumeroSeg() {
        return $this->numeroSeg;
    }

    /**
     * Set empresa.
     *
     * @param string|null $empresa
     *
     * @return MoviPat
     */
    public function setEmpresa($empresa = null) {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa.
     *
     * @return string|null
     */
    public function getEmpresa() {
        return $this->empresa;
    }

    /**
     * Set patMunpal.
     *
     * @param string|null $patMunpal
     *
     * @return MoviPat
     */
    public function setPatMunpal($patMunpal = null) {
        $this->patMunpal = $patMunpal;

        return $this;
    }

    /**
     * Get patMunpal.
     *
     * @return string|null
     */
    public function getPatMunpal() {
        return $this->patMunpal;
    }

    /**
     * Set obrMunpal.
     *
     * @param string|null $obrMunpal
     *
     * @return MoviPat
     */
    public function setObrMunpal($obrMunpal = null) {
        $this->obrMunpal = $obrMunpal;

        return $this;
    }

    /**
     * Get obrMunpal.
     *
     * @return string|null
     */
    public function getObrMunpal() {
        return $this->obrMunpal;
    }

    /**
     * Set patIntegra.
     *
     * @param string|null $patIntegra
     *
     * @return MoviPat
     */
    public function setPatIntegra($patIntegra = null) {
        $this->patIntegra = $patIntegra;

        return $this;
    }

    /**
     * Get patIntegra.
     *
     * @return string|null
     */
    public function getPatIntegra() {
        return $this->patIntegra;
    }

    /**
     * Set enUso.
     *
     * @param string $enUso
     *
     * @return MoviPat
     */
    public function setEnUso($enUso) {
        $this->enUso = $enUso;

        return $this;
    }

    /**
     * Get enUso.
     *
     * @return string
     */
    public function getEnUso() {
        return $this->enUso;
    }

    /**
     * Set clave.
     *
     * @param string|null $clave
     *
     * @return MoviPat
     */
    public function setClave($clave = null) {
        $this->clave = $clave;

        return $this;
    }

    /**
     * Get clave.
     *
     * @return string|null
     */
    public function getClave() {
        return $this->clave;
    }

    /**
     * Set eventual.
     *
     * @param string $eventual
     *
     * @return MoviPat
     */
    public function setEventual($eventual) {
        $this->eventual = $eventual;

        return $this;
    }

    /**
     * Get eventual.
     *
     * @return string
     */
    public function getEventual() {
        return $this->eventual;
    }

    /**
     * Set porcent.
     *
     * @param string|null $porcent
     *
     * @return MoviPat
     */
    public function setPorcent($porcent = null) {
        $this->porcent = $porcent;

        return $this;
    }

    /**
     * Get porcent.
     *
     * @return string|null
     */
    public function getPorcent() {
        return $this->porcent;
    }

    /**
     * Set patAccAnt.
     *
     * @param string|null $patAccAnt
     *
     * @return MoviPat
     */
    public function setPatAccAnt($patAccAnt = null) {
        $this->patAccAnt = $patAccAnt;

        return $this;
    }

    /**
     * Get patAccAnt.
     *
     * @return string|null
     */
    public function getPatAccAnt() {
        return $this->patAccAnt;
    }

    /**
     * Set forzarL00.
     *
     * @param string|null $forzarL00
     *
     * @return MoviPat
     */
    public function setForzarL00($forzarL00 = null) {
        $this->forzarL00 = $forzarL00;

        return $this;
    }

    /**
     * Get forzarL00.
     *
     * @return string|null
     */
    public function getForzarL00() {
        return $this->forzarL00;
    }

    public function __toString() {
        return $this->descrip. '('. $this->codigo.')';
    }

}
