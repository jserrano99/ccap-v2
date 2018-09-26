<?php

namespace MaestrosBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * TipoIlt
 *
 * @ORM\Table(name="gums_tipo_ilt"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\TipoIltRepository")
 */

class TipoIlt {
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
     * @ORM\Column(name="codigo", type="string", length=1, nullable=false)
     */
    private $codigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=25, nullable=true)
     */
    private $descripcion;

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
     * @return TipoIlt
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
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return TipoIlt
     */
    public function setDescripcion($descripcion = null)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string|null
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    public function __toString() {
        return $this->descripcion;
    }
}
