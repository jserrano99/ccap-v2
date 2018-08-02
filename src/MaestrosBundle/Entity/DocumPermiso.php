<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumPermiso
 *
 * @ORM\Table(name="gums_docum_permiso", 
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\DocumPermisoRepository")
 */
class DocumPermiso {

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
     * @ORM\Column(name="codigo", type="string",length=3, nullable=false)
     * 
     */
    private $codigo;
    
    /**
     * @var text 
     * 
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     * 
     */
    private $observaciones;
    
    /**
     * @var integer 
     * 
     * @ORM\Column(name="url_id", type="integer", nullable=false)
     * 
     */
    private $urlId;
    
    

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
     * @return DocumPermiso
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
     * Set observaciones.
     *
     * @param string|null $observaciones
     *
     * @return DocumPermiso
     */
    public function setObservaciones($observaciones = null)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones.
     *
     * @return string|null
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set urlId.
     *
     * @param int $urlId
     *
     * @return DocumPermiso
     */
    public function setUrlId($urlId)
    {
        $this->urlId = $urlId;

        return $this;
    }

    /**
     * Get urlId.
     *
     * @return int
     */
    public function getUrlId()
    {
        return $this->urlId;
    }
    
    public function __toString() {
        return $this->observaciones;
    }
}
