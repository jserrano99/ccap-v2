<?php

/**
 * Description of Ceco
 *
 * @author jluis
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ceco
 *
 * @ORM\Table(name="ccap_cecos"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CecoRepository")
 */
class Ceco {

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
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=11, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="sociedad", type="string", length=4, nullable=true)
     */
    private $sociedad;

    /**
     * @var string
     *
     * @ORM\Column(name="division", type="string", length=4, nullable=true)
     */
    private $division;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=true)
     */
    private $enuso;

    public function getId() {
        return $this->id;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getSociedad() {
        return $this->sociedad;
    }

    public function getDivision() {
        return $this->division;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
        return $this;
    }

    public function setSociedad($sociedad) {
        $this->sociedad = $sociedad;
        return $this;
    }

    public function setDivision($division) {
        $this->division = $division;
        return $this;
    }

    public function __toString() {
        return $this->descripcion.' ('.$this->codigo.')';
    }


    /**
     * Set enuso
     *
     * @param string $enuso
     *
     * @return Ceco
     */
    public function setEnuso($enuso)
    {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso
     *
     * @return string
     */
    public function getEnuso()
    {
        return $this->enuso;
    }
}
