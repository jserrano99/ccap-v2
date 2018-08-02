<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqTipoIlt
 *
 * @ORM\Table(name="gums_eq_tipo_ilt" 
 *           )
 * @ORM\Entity
 */
class EqTipoIlt {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var ComunBundle\Entity\Edificio|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\Edificio")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edificio_id", referencedColumnName="id")
     * })
     */
    private $edificio;

    /**
     * @var codigoLoc
     *
     * @ORM\Column(name="codigo_loc", type="string", length=1, nullable=false)
     */
    private $codigoLoc;

    /**
     * @var TipoIlt|null
     *
     * @ORM\ManyToOne(targetEntity="TipoIlt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipoIlt_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $tipoIlt;
    
    /**
     * @var enUso
     * 
     * @ORM\Column(name="enuso",type="string",length=1,nullable=false)
     */
    private $enUso;



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
     * Set codigoLoc.
     *
     * @param string $codigoLoc
     *
     * @return EqTipoIlt
     */
    public function setCodigoLoc($codigoLoc)
    {
        $this->codigoLoc = $codigoLoc;

        return $this;
    }

    /**
     * Get codigoLoc.
     *
     * @return string
     */
    public function getCodigoLoc()
    {
        return $this->codigoLoc;
    }

    /**
     * Set enUso.
     *
     * @param string $enUso
     *
     * @return EqTipoIlt
     */
    public function setEnUso($enUso)
    {
        $this->enUso = $enUso;

        return $this;
    }

    /**
     * Get enUso.
     *
     * @return string
     */
    public function getEnUso()
    {
        return $this->enUso;
    }

    /**
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return EqTipoIlt
     */
    public function setEdificio(\ComunBundle\Entity\Edificio $edificio = null)
    {
        $this->edificio = $edificio;

        return $this;
    }

    /**
     * Get edificio.
     *
     * @return \ComunBundle\Entity\Edificio|null
     */
    public function getEdificio()
    {
        return $this->edificio;
    }

    /**
     * Set tipoIlt.
     *
     * @param \MaestrosBundle\Entity\TipoIlt|null $tipoIlt
     *
     * @return EqTipoIlt
     */
    public function setTipoIlt(\MaestrosBundle\Entity\TipoIlt $tipoIlt = null)
    {
        $this->tipoIlt = $tipoIlt;

        return $this;
    }

    /**
     * Get tipoIlt.
     *
     * @return \MaestrosBundle\Entity\TipoIlt|null
     */
    public function getTipoIlt()
    {
        return $this->tipoIlt;
    }
}
