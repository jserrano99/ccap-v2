<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqEpiAcc
 *
 * @ORM\Table(name="gums_eq_epiacc" 
 *           )
 * @ORM\Entity
 */
class EqEpiAcc {

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
     * @ORM\Column(name="codigo_loc", type="string", length=3, nullable=false)
     */
    private $codigoLoc;

    /**
     * @var EpiAcc|null
     *
     * @ORM\ManyToOne(targetEntity="EpiAcc")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="epiacc_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $epiAcc;

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
     * @return EqEpiAcc
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
     * @return EqEpiAcc
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
     * @return EqEpiAcc
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
     * Set epiAcc.
     *
     * @param \MaestrosBundle\Entity\EpiAcc|null $epiAcc
     *
     * @return EqEpiAcc
     */
    public function setEpiAcc(\MaestrosBundle\Entity\EpiAcc $epiAcc = null)
    {
        $this->epiAcc = $epiAcc;

        return $this;
    }

    /**
     * Get epiAcc.
     *
     * @return \MaestrosBundle\Entity\EpiAcc|null
     */
    public function getEpiAcc()
    {
        return $this->epiAcc;
    }
}
