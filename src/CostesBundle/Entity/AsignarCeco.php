<?php

/**
 * Description of CecoCias
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CostesBundle\Entity\Ceco;
use CostesBundle\Entity\Plaza;

class AsignarCeco {

    /**
     * @var string
     *
     */
    private $codigoCalculado;

    /**
     * @var Ceco|null
     *
     * @ORM\ManyToOne(targetEntity="Ceco")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ceco_calculado_id", referencedColumnName="id")
     * })
     */
    private $cecoCalculado;

    /**
     * @var Ceco|null
     *
     * @ORM\ManyToOne(targetEntity="Ceco")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ceco_informado_id", referencedColumnName="id")
     * })
     */
    private $cecoInformado;

    /**
     * @var Plaza|null
     *
     * @ORM\OneToOne(targetEntity="Plaza")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plaza_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $plaza;

    /*
     * @return Ceco|null
     */

    public function getCecoCalculado() {
        return $this->cecoCalculado;
    }

    /*
     * @return Ceco|null
     */

    public function getCecoInformado() {
        return $this->cecoInformado;
    }

    /*
     * @return Plaza|null
     */

    public function getPlaza() {
        return $this->plaza;
    }

    /**
     * Set cecoCalculado
     *
     * @param Ceco|null
     *
     * @return AsignarCeco
     */
    public function setCecoCalculado($cecoCalculado) {
        $this->cecoCalculado = $cecoCalculado;
        return $this;
    }

    /**
     * Set cecoInformado
     *
     * @param Ceco|null
     *
     * @return AsignarCeco
     */
    public function setCecoInformado($cecoInformado) {
        $this->cecoInformado = $cecoInformado;
        return $this;
    }

    public function setPlaza(Plaza $plaza) {
        $this->plaza = $plaza;
        return $this;
    }
    public function getCodigoCalculado() {
        return $this->codigoCalculado;
    }

    public function setCodigoCalculado($codigoCalculado) {
        $this->codigoCalculado = $codigoCalculado;
        return $this;
    }


}
