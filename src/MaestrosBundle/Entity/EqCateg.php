<?php

/**
 * Description of EqAltas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqCateg
 *
 * @ORM\Table(name="ccap_eq_categ" 
 *           )
 * @ORM\Entity
 */
class EqCateg {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var codigoLoc
     *
     * @ORM\Column(name="codigo_loc", type="string", length=4, nullable=false)
     */
    private $codigoLoc;

    /**
     * @var Edificio|null
     *
     * @ORM\ManyToOne(targetEntity="Edificio")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edificio_id", referencedColumnName="id")
     * })
     */
    private $edificio;

    /**
     * @var Categ|null
     *
     * @ORM\ManyToOne(targetEntity="Categ")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categ_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $categ;

    
    /**
     * @var enUso
     * 
     * @ORM\Column(name="en_uso",type="string",length=1,nullable=false)
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
     * @return EqCateg
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
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return EqCateg
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
     * Set categ.
     *
     * @param \MaestrosBundle\Entity\Categ|null $categ
     *
     * @return EqCateg
     */
    public function setCateg(\MaestrosBundle\Entity\Categ $categ = null)
    {
        $this->categ = $categ;

        return $this;
    }

    /**
     * Get categ.
     *
     * @return \MaestrosBundle\Entity\Categ|null
     */
    public function getCateg()
    {
        return $this->categ;
    }

    /**
     * Set enUso.
     *
     * @param string $enUso
     *
     * @return EqCateg
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
}
