<?php

/**
 * Description of GrupoProf
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoProf
 *
 * @ORM\Table(name="gums_grupoprof"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
*          , indexes={@ORM\Index(name="idx001", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\GrupoProfRepository")
 */
class GrupoProf {

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
     * @ORM\Column(name="codigo", type="string", length=1, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=25, nullable=false)
     */
    private $descripcion;

    /**
     * @var decimal
     *
     * @ORM\Column(name="importe", type="decimal",  nullable=false)
     */
    private $importe;

    /**
     * @var string
     *
     * @ORM\Column(name="exento_ss", type="string", length=1, nullable=true)
     */
    private $exentoSs;

    /**
     * @var string
     *
     * @ORM\Column(name="muface_escala", type="string", length=4, nullable=true)
     */
    private $mufaceEscala;

    /**
     * @var decimal
     *
     * @ORM\Column(name="sal_base", type="decimal",  nullable=true)
     */
    private $salBase;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo2", type="string", length=2, nullable=true)
     */
    private $codigo2;


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
     * @return GrupoProf
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
     * @return GrupoProf
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
     * Set importe
     *
     * @param string $importe
     *
     * @return GrupoProf
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return string
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set exentoSs
     *
     * @param string $exentoSs
     *
     * @return GrupoProf
     */
    public function setExentoSs($exentoSs)
    {
        $this->exentoSs = $exentoSs;

        return $this;
    }

    /**
     * Get exentoSs
     *
     * @return string
     */
    public function getExentoSs()
    {
        return $this->exentoSs;
    }

    /**
     * Set mufaceEscala
     *
     * @param string $mufaceEscala
     *
     * @return GrupoProf
     */
    public function setMufaceEscala($mufaceEscala)
    {
        $this->mufaceEscala = $mufaceEscala;

        return $this;
    }

    /**
     * Get mufaceEscala
     *
     * @return string
     */
    public function getMufaceEscala()
    {
        return $this->mufaceEscala;
    }

    /**
     * Set salBase
     *
     * @param string $salBase
     *
     * @return GrupoProf
     */
    public function setSalBase($salBase)
    {
        $this->salBase = $salBase;

        return $this;
    }

    /**
     * Get salBase
     *
     * @return string
     */
    public function getSalBase()
    {
        return $this->salBase;
    }

    /**
     * Set codigo2
     *
     * @param string $codigo2
     *
     * @return GrupoProf
     */
    public function setCodigo2($codigo2)
    {
        $this->codigo2 = $codigo2;

        return $this;
    }

    /**
     * Get codigo2
     *
     * @return string
     */
    public function getCodigo2()
    {
        return $this->codigo2;
    }
    
    public function __toString() {
        return $this->descripcion;
    }
    
}
