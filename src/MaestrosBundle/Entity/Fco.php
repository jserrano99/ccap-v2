<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fco
 *
 * @ORM\Table(name="gums_fco"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\FcoRepository")
 */
class Fco {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var codigo
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
     * @ORM\Column(name="propietario", type="string", length=1, nullable=true,options={"default":"S"}))
     */
    private $propietario;

    /**
     * @var string
     *
     * @ORM\Column(name="soli_origen", type="string", length=1, nullable=true,options={"default":"N"}))
     */
    private $soliOrigen;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=true,options={"default":"S"}))
     */
    private $enuso;

    /**
     * @var integer
     *
     * @ORM\Column(name="fcorptid", type="integer", nullable=true )
     */
    private $fcorptid;

    /**
     * @var string
     *
     * @ORM\Column(name="fcorpt_codigo", type="string", length=10, nullable=true)
     */
    private $fcorptCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="fcorpt_descripcion", type="string", length=100, nullable=true)
     */
    private $fcorptDescripcion;


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
     * @return Fco
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
     * @return Fco
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
     * Set propietario.
     *
     * @param string|null $propietario
     *
     * @return Fco
     */
    public function setPropietario($propietario = null)
    {
        $this->propietario = $propietario;

        return $this;
    }

    /**
     * Get propietario.
     *
     * @return string|null
     */
    public function getPropietario()
    {
        return $this->propietario;
    }

    /**
     * Set soliOrigen.
     *
     * @param string|null $soliOrigen
     *
     * @return Fco
     */
    public function setSoliOrigen($soliOrigen = null)
    {
        $this->soliOrigen = $soliOrigen;

        return $this;
    }

    /**
     * Get soliOrigen.
     *
     * @return string|null
     */
    public function getSoliOrigen()
    {
        return $this->soliOrigen;
    }

    /**
     * Set enuso.
     *
     * @param string|null $enuso
     *
     * @return Fco
     */
    public function setEnuso($enuso = null)
    {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso.
     *
     * @return string|null
     */
    public function getEnuso()
    {
        return $this->enuso;
    }

    /**
     * Set fcorptid.
     *
     * @param int|null $fcorptid
     *
     * @return Fco
     */
    public function setFcorptid($fcorptid = null)
    {
        $this->fcorptid = $fcorptid;

        return $this;
    }

    /**
     * Get fcorptid.
     *
     * @return int|null
     */
    public function getFcorptid()
    {
        return $this->fcorptid;
    }

    /**
     * Set fcorptCodigo.
     *
     * @param string|null $fcorptCodigo
     *
     * @return Fco
     */
    public function setFcorptCodigo($fcorptCodigo = null)
    {
        $this->fcorptCodigo = $fcorptCodigo;

        return $this;
    }

    /**
     * Get fcorptCodigo.
     *
     * @return string|null
     */
    public function getFcorptCodigo()
    {
        return $this->fcorptCodigo;
    }

    /**
     * Set fcorptDescripcion.
     *
     * @param string|null $fcorptDescripcion
     *
     * @return Fco
     */
    public function setFcorptDescripcion($fcorptDescripcion = null)
    {
        $this->fcorptDescripcion = $fcorptDescripcion;

        return $this;
    }

    /**
     * Get fcorptDescripcion.
     *
     * @return string|null
     */
    public function getFcorptDescripcion()
    {
        return $this->fcorptDescripcion;
    }
    
    public function __toString() {
        return $this->descrip;
    }
}
