<?php

/**
 * Description of EqAltas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqGrupoProf
 *
 * @ORM\Table(name="gums_eq_grupoProf" 
 *           )
 * @ORM\Entity
 */
class EqGrupoProf {

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
     * @var ComunBundle\Entity\Edificio|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\Edificio")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edificio_id", referencedColumnName="id")
     * })
     */
    private $edificio;

    /**
     * @var GrupoProf|null
     *
     * @ORM\ManyToOne(targetEntity="GrupoProf")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupoprof_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $grupoProf;

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
     * @return EqGrupoProf
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
     * @return EqGrupoProf
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
     * Set grupoProf.
     *
     * @param \MaestrosBundle\Entity\GrupoProf|null $grupoProf
     *
     * @return EqGrupoProf
     */
    public function setGrupoProf(\MaestrosBundle\Entity\GrupoProf $grupoProf = null)
    {
        $this->grupoProf = $grupoProf;

        return $this;
    }

    /**
     * Get grupoProf.
     *
     * @return \MaestrosBundle\Entity\GrupoProf|null
     */
    public function getGrupoProf()
    {
        return $this->grupoProf;
    }
    
}
