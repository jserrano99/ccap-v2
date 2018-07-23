<?php

/**
 * Description of BaseDatos
 *
 * @author jluis
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ceco
 *
 * @ORM\Table(name="ccap_base_datos" )
 * @ORM\Entity
 */
class BaseDatos {

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
     * @ORM\Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="maquina", type="string", length=255, nullable=true)
     */
    private $maquina;

    /**
     * @var string
     *
     * @ORM\Column(name="puerto", type="integer", nullable=true)
     */
    private $puerto;

    /**
     * @var string
     *
     * @ORM\Column(name="servidor", type="string",length=255,  nullable=true)
     */
    private $servidor;

    /**
     * @var string
     *
     * @ORM\Column(name="esquema", type="string",length=255,  nullable=true)
     */
    private $esquema;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=255, nullable=true)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string",length=255,  nullable=true)
     */
    private $password;

    /**
     * @var TipoBaseDatos|null
     *
     * @ORM\ManyToOne(targetEntity="TipoBaseDatos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_bd_id", referencedColumnName="id")
     * })
     */
    private $tipoBaseDatos;
    
    /**
     * @var string
     *
     * @ORM\Column(name="activa", type="string",length=1,  nullable=true)
     */
    private $activa;

    
    /**
     * @var string
     *
     * @ORM\Column(name="areas", type="string",length=1,  nullable=true)
     */
    private $areas;

    /**
     * @var Edificio|null
     *
     * @ORM\ManyToOne(targetEntity="Edificio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edificio_id", referencedColumnName="id")
     * })
     */
    private $edificio;
    
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
     * Set alias
     *
     * @param string $alias
     *
     * @return BaseDatos
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set maquina
     *
     * @param string $maquina
     *
     * @return BaseDatos
     */
    public function setMaquina($maquina)
    {
        $this->maquina = $maquina;

        return $this;
    }

    /**
     * Get maquina
     *
     * @return string
     */
    public function getMaquina()
    {
        return $this->maquina;
    }

    /**
     * Set puerto
     *
     * @param integer $puerto
     *
     * @return BaseDatos
     */
    public function setPuerto($puerto)
    {
        $this->puerto = $puerto;

        return $this;
    }

    /**
     * Get puerto
     *
     * @return integer
     */
    public function getPuerto()
    {
        return $this->puerto;
    }

    /**
     * Set servidor
     *
     * @param string $servidor
     *
     * @return BaseDatos
     */
    public function setServidor($servidor)
    {
        $this->servidor = $servidor;

        return $this;
    }

    /**
     * Get servidor
     *
     * @return string
     */
    public function getServidor()
    {
        return $this->servidor;
    }

    /**
     * Set esquema
     *
     * @param string $esquema
     *
     * @return BaseDatos
     */
    public function setEsquema($esquema)
    {
        $this->esquema = $esquema;

        return $this;
    }

    /**
     * Get esquema
     *
     * @return string
     */
    public function getEsquema()
    {
        return $this->esquema;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return BaseDatos
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return BaseDatos
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set activa
     *
     * @param string $activa
     *
     * @return BaseDatos
     */
    public function setActiva($activa)
    {
        $this->activa = $activa;

        return $this;
    }

    /**
     * Get activa
     *
     * @return string
     */
    public function getActiva()
    {
        return $this->activa;
    }

    /**
     * Set areas
     *
     * @param string $areas
     *
     * @return BaseDatos
     */
    public function setAreas($areas)
    {
        $this->areas = $areas;

        return $this;
    }

    /**
     * Get areas
     *
     * @return string
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * Set tipoBaseDatos
     *
     * @param \AppBundle\Entity\TipoBaseDatos $tipoBaseDatos
     *
     * @return BaseDatos
     */
    public function setTipoBaseDatos(\AppBundle\Entity\TipoBaseDatos $tipoBaseDatos = null)
    {
        $this->tipoBaseDatos = $tipoBaseDatos;

        return $this;
    }

    /**
     * Get tipoBaseDatos
     *
     * @return \AppBundle\Entity\TipoBaseDatos
     */
    public function getTipoBaseDatos()
    {
        return $this->tipoBaseDatos;
    }

    /**
     * Set edificio
     *
     * @param \AppBundle\Entity\Edificio $edificio
     *
     * @return BaseDatos
     */
    public function setEdificio(\AppBundle\Entity\Edificio $edificio = null)
    {
        $this->edificio = $edificio;

        return $this;
    }

    /**
     * Get edificio
     *
     * @return \AppBundle\Entity\Edificio
     */
    public function getEdificio()
    {
        return $this->edificio;
    }
}
