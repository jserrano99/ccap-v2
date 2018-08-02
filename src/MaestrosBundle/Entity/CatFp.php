<?php

/**
 * Description of CatFp
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * CatFp
 *
 * @ORM\Table(name="ccap_catfp"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\CatFpRepository")
 */
class CatFp {

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
     * @ORM\Column(name="codigo", type="string", length=4, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=40, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="enuso", type="string", length=1, nullable=true)
     */
    private $enuso;

    /**
     * @var ComunBundle\Entity\SincroLog|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\SincroLog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sincro_log_id", referencedColumnName="id")
     * })
     */

    private $sincroLog;
    

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
     * @param string|null $codigo
     *
     * @return CatFp
     */
    public function setCodigo($codigo = null)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string|null
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
     * @return CatFp
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

    /**
     * Set enuso.
     *
     * @param string|null $enuso
     *
     * @return CatFp
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
     * Set sincroLog.
     *
     * @param \ComunBundle\Entity\SincroLog|null $sincroLog
     *
     * @return CatFp
     */
    public function setSincroLog(\ComunBundle\Entity\SincroLog $sincroLog = null)
    {
        $this->sincroLog = $sincroLog;

        return $this;
    }

    /**
     * Get sincroLog.
     *
     * @return \ComunBundle\Entity\SincroLog|null
     */
    public function getSincroLog()
    {
        return $this->sincroLog;
    }
    public function __toString() {
        return $this->descripcion;
    }
}
