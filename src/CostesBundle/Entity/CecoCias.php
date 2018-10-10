<?php

/**
 * Description of Cecocias /asignación de centro de costes a plazas 
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CostesBundle\Entity\Uf;
use CostesBundle\Entity\Pa;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Plaza
 *
 * @ORM\Table(name="ccap_cecocias")
 * @ORM\Entity(repositoryClass="CostesBundle\Repository\CecoCiasRepository")
 */
class CecoCias {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Plaza
     *
     * @ORM\ManyToOne(targetEntity="Plaza") 
     *   @ORM\JoinColumn(name="plaza_id", referencedColumnName="id")
     */
    private $plaza;

    /**
     * @var Ceco
     *
     * @ORM\ManyToOne(targetEntity="Ceco") 
     *   @ORM\JoinColumn(name="ceco_id", referencedColumnName="id")
     */
    private $ceco;

    /**
     * @var date
     *
     * @ORM\Column(name="f_inicio", type="datetime", nullable=true)
     */
    private $fInicio;

    /**
     * @var date
     *
     * @ORM\Column(name="f_fin", type="datetime", nullable=true)
     */
    private $fFin;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set fInicio.
     *
     * @param \DateTime $fInicio
     *
     * @return CecoCias
     */
    public function setFInicio($fInicio) {
        $this->fInicio = $fInicio;

        return $this;
    }

    /**
     * Get fInicio.
     *
     * @return \DateTime
     */
    public function getFInicio() {
        return $this->fInicio;
    }

    /**
     * Set fFin.
     *
     * @param \DateTime|null $fFin
     *
     * @return CecoCias
     */
    public function setFFin($fFin = null) {
        $this->fFin = $fFin;

        return $this;
    }

    /**
     * Get fFin.
     *
     * @return \DateTime|null
     */
    public function getFFin() {
        return $this->fFin;
    }

    /**
     * Set plaza.
     *
     * @param \CostesBundle\Entity\Plaza|null $plaza
     *
     * @return CecoCias
     */
    public function setPlaza(\CostesBundle\Entity\Plaza $plaza = null) {
        $this->plaza = $plaza;

        return $this;
    }

    /**
     * Get plaza.
     *
     * @return \CostesBundle\Entity\Plaza|null
     */
    public function getPlaza() {
        return $this->plaza;
    }

    /**
     * Set ceco.
     *
     * @param \CostesBundle\Entity\Ceco|null $ceco
     *
     * @return CecoCias
     */
    public function setCeco(\CostesBundle\Entity\Ceco $ceco = null) {
        $this->ceco = $ceco;

        return $this;
    }

    /**
     * Get ceco.
     *
     * @return \CostesBundle\Entity\Ceco|null
     */
    public function getCeco() {
        return $this->ceco;
    }

}
