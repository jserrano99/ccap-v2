<?php

/**
 * Description of AsginacionDirecta
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * CatFp
 *
 * @ORM\Table(name="ccap_asignacion_directa"
 * *           ,uniqueConstraints={@ORM\UniqueConstraint(name="codigoUf78_uk", columns={"codigo_uf_78"})}
 *           )
 * @ORM\Entity
 */
class AsignacionDirecta {

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
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_uf_78", type="string", length=2, nullable=false)
     */
    private $codigoUf78;

    /**
     * @var Ceco|null
     *
     * @ORM\ManyToOne(targetEntity="Ceco")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ceco_id", referencedColumnName="id")
     * })
     */
    private $ceco;

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
     * Set codigoUf78
     *
     * @param string codigoUf78
     *
     * @return AsignacionDirecta
     */
    public function setCodigoUf78($codigoUf78)
    {
        $this->codigoUf78 = $codigoUf78;

        return $this;
    }

    /**
     * Get codigoUf78
     *
     * @return string
     */
    public function getCodigoUf78()
    {
        return $this->codigoUf78;
    }

    /**
     * Set decripcion
     *
     * @param string $decripcion
     *
     * @return AsignacionDirecta
     */
    public function setDecripcion($decripcion)
    {
        $this->decripcion = $decripcion;

        return $this;
    }

    /**
     * Get decripcion
     *
     * @return string
     */
    public function getDecripcion()
    {
        return $this->decripcion;
    }
    
    /**
     * __toString
     *
     * @return string
     */
    public function __toString() {
        return $this->decripcion;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return AsignacionDirecta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set Ceco
     *
     * @param Ceco
     *
     * @return AsignacionDirecta
     */
    public function setCeco(Ceco $ceco=null)
    {
        $this->ceco = $ceco;

        return $this;
    }

    /**
     * Get ceco
     *
     * @return Ceco|null
     */
    public function getCeco()
    {
        return $this->ceco;
    }
}
