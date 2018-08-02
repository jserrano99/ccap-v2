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
     * @ORM\Column(name="descrip", type="string", length=25, nullable=true)
     */
    private $descrip;

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
     * Set descrip.
     *
     * @param string|null $descrip
     *
     * @return TipoIlt
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
    
    public function __toString() {
        return $this->descrip;
    }
}
