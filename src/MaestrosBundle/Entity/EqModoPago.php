<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqModoPago
 *
 * @ORM\Table(name="gums_eq_modopago" 
 *           )
 * @ORM\Entity
 */
class EqModoPago {

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
     * @var ModoPago|null
     *
     * @ORM\ManyToOne(targetEntity="ModoPago")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modopago_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $modoPago;

        /**
     * @var enuso
     * 
     * @ORM\Column(name="enuso",type="string",length=1,nullable=false)
     */
    private $enuso;


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
     * @return EqModoPago
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
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return EqModoPago
     */
    public function setEnuso($enuso)
    {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso.
     *
     * @return string
     */
    public function getEnuso()
    {
        return $this->enuso;
    }

    /**
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return EqModoPago
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
     * Set modoPago.
     *
     * @param \MaestrosBundle\Entity\ModoPago|null $modoPago
     *
     * @return EqModoPago
     */
    public function setModoPago(\MaestrosBundle\Entity\ModoPago $modoPago = null)
    {
        $this->modoPago = $modoPago;

        return $this;
    }

    /**
     * Get modoPago.
     *
     * @return \MaestrosBundle\Entity\ModoPago|null
     */
    public function getModoPago()
    {
        return $this->modoPago;
    }
}
