<?php

/**
 * Description of Equivalencia
 *
 * @author jluis_local
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * Equivalencia
 *
 * @ORM\Table(name="ccap_equivalencia"
 *           ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_area_zona", columns={"area_zona"})}
             )
 * @ORM\Entity
 */
class Equivalencia {

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
     * @ORM\Column(name="area_zona", type="string", length=4, nullable=true)
     */
    private $areaZona;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=2, nullable=true)
     */
    private $codigo;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set areaZona
     *
     * @param string $areaZona
     *
     * @return Equivalencia
     */
    public function setAreaZona($areaZona)
    {
        $this->areaZona = $areaZona;

        return $this;
    }

    /**
     * Get areaZona
     *
     * @return string
     */
    public function getAreaZona()
    {
        return $this->areaZona;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Equivalencia
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
}
