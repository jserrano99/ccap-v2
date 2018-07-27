<?php

/**
 * Description of Excepcion
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ceco
 *
 * @ORM\Table(name="ccap_excepciones"
 *           ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_cecoReal", columns={"ceco_real_id"})}
 *           ,indexes={@ORM\Index(name="idx001", columns={"ceco_excepcion_id"})}
 
 * )
 * @ORM\Entity
 */
class Excepcion {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Ceco|null
     *
     * @ORM\ManyToOne(targetEntity="Ceco")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ceco_real_id", referencedColumnName="id")
     * })
     */
    private $cecoReal;

    /**
     * @var Ceco|null
     *
     * @ORM\ManyToOne(targetEntity="Ceco")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ceco_excepcion_id", referencedColumnName="id")
     * })
     */
    private $cecoExcepcion;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set cecoReal|null
     *
     * @param \CostesBundle\Entity\Ceco $cecoReal
     *
     * @return Excepcion
     */
    public function setCecoReal(\CostesBundle\Entity\Ceco $cecoReal = null) {
        $this->cecoReal = $cecoReal;

        return $this;
    }

    /**
     * Get cecoReal
     *
     * @return \CostesBundle\Entity\Ceco|null
     */
    public function getCecoReal() {
        return $this->cecoReal;
    }

    /**
     * Set cecoExcepcion
     *
     * @param \CostesBundle\Entity\Ceco $cecoExcepcion
     *
     * @return Excepcion
     */
    public function setCecoExcepcion(\CostesBundle\Entity\Ceco $cecoExcepcion = null) {
        $this->cecoExcepcion = $cecoExcepcion;

        return $this;
    }

    /**
     * Get cecoExcepcion
     *
     * @return \CostesBundle\Entity\Ceco|null
     */
    public function getCecoExcepcion() {
        return $this->cecoExcepcion;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }

}
