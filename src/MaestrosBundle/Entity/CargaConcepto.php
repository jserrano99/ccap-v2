<?php

/**
 * Description of CargaConcepto (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Edificio
 *
 * @ORM\Table(name="gums_carga_conceptoS" 
 *           )
 * @ORM\Entity
 */
class CargaConcepto {

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
     * @ORM\Column(name="codigo_uni", type="string", length=3, nullable=false)
     */
    private $codigoUni;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=false)
     */
    private $descripcion;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="area_origen", type="string", length=2, nullable=false)
     */
    private $areaOrigen;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_loc", type="string", length=3, nullable=false)
     */
    private $codigo_loc;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a1", type="string", length=3, nullable=true)
     */
    private $codigo_a1;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a2", type="string", length=3, nullable=true)
     */
    private $codigo_a2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a3", type="string", length=3, nullable=true)
     */
    private $codigo_a3;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a4", type="string", length=3, nullable=true)
     */
    private $codigo_a4;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a5", type="string", length=3, nullable=true)
     */
    private $codigo_a5;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a6", type="string", length=3, nullable=true)
     */
    private $codigo_a6;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a7", type="string", length=3, nullable=true)
     */
    private $codigo_a7;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a8", type="string", length=3, nullable=true)
     */
    private $codigo_a8;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a9", type="string", length=3, nullable=true)
     */
    private $codigo_a9;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a10", type="string", length=3, nullable=true)
     */
    private $codigo_a10;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a11", type="string", length=3, nullable=true)
     */
    private $codigo_a11;
    /**
     * @var string
     *
     * @ORM\Column(name="codigo_a0", type="string", length=3, nullable=true)
     */
    private $codigo_a0;

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
     * Set codigoUni.
     *
     * @param string $codigoUni
     *
     * @return CargaConcepto
     */
    public function setCodigoUni($codigoUni)
    {
        $this->codigoUni = $codigoUni;

        return $this;
    }

    /**
     * Get codigoUni.
     *
     * @return string
     */
    public function getCodigoUni()
    {
        return $this->codigoUni;
    }

    /**
     * Set descripcion.
     *
     * @param string $descripcion
     *
     * @return CargaConcepto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set areaOrigen.
     *
     * @param string $areaOrigen
     *
     * @return CargaConcepto
     */
    public function setAreaOrigen($areaOrigen)
    {
        $this->areaOrigen = $areaOrigen;

        return $this;
    }

    /**
     * Get areaOrigen.
     *
     * @return string
     */
    public function getAreaOrigen()
    {
        return $this->areaOrigen;
    }

    /**
     * Set codigoLoc.
     *
     * @param string $codigoLoc
     *
     * @return CargaConcepto
     */
    public function setCodigoLoc($codigoLoc)
    {
        $this->codigo_loc = $codigoLoc;

        return $this;
    }

    /**
     * Get codigoLoc.
     *
     * @return string
     */
    public function getCodigoLoc()
    {
        return $this->codigo_loc;
    }

    /**
     * Set codigoA01.
     *
     * @param string|null $codigoA01
     *
     * @return CargaConcepto
     */
    public function setCodigoA01($codigoA01 = null)
    {
        $this->codigo_a1 = $codigoA01;

        return $this;
    }

    /**
     * Get codigoA01.
     *
     * @return string|null
     */
    public function getCodigoA01()
    {
        return $this->codigo_a1;
    }

    /**
     * Set codigoA02.
     *
     * @param string|null $codigoA02
     *
     * @return CargaConcepto
     */
    public function setCodigoA02($codigoA02 = null)
    {
        $this->codigo_a2 = $codigoA02;

        return $this;
    }

    /**
     * Get codigoA02.
     *
     * @return string|null
     */
    public function getCodigoA02()
    {
        return $this->codigo_a2;
    }

    /**
     * Set codigoA03.
     *
     * @param string|null $codigoA03
     *
     * @return CargaConcepto
     */
    public function setCodigoA03($codigoA03 = null)
    {
        $this->codigo_a3 = $codigoA03;

        return $this;
    }

    /**
     * Get codigoA03.
     *
     * @return string|null
     */
    public function getCodigoA03()
    {
        return $this->codigo_a3;
    }

    /**
     * Set codigoA04.
     *
     * @param string|null $codigoA04
     *
     * @return CargaConcepto
     */
    public function setCodigoA04($codigoA04 = null)
    {
        $this->codigo_a4 = $codigoA04;

        return $this;
    }

    /**
     * Get codigoA04.
     *
     * @return string|null
     */
    public function getCodigoA04()
    {
        return $this->codigo_a4;
    }

    /**
     * Set codigoA05.
     *
     * @param string|null $codigoA05
     *
     * @return CargaConcepto
     */
    public function setCodigoA05($codigoA05 = null)
    {
        $this->codigo_a5 = $codigoA05;

        return $this;
    }

    /**
     * Get codigoA05.
     *
     * @return string|null
     */
    public function getCodigoA05()
    {
        return $this->codigo_a5;
    }

    /**
     * Set codigoA06.
     *
     * @param string|null $codigoA06
     *
     * @return CargaConcepto
     */
    public function setCodigoA06($codigoA06 = null)
    {
        $this->codigo_a6 = $codigoA06;

        return $this;
    }

    /**
     * Get codigoA06.
     *
     * @return string|null
     */
    public function getCodigoA06()
    {
        return $this->codigo_a6;
    }

    /**
     * Set codigoA07.
     *
     * @param string|null $codigoA07
     *
     * @return CargaConcepto
     */
    public function setCodigoA07($codigoA07 = null)
    {
        $this->codigo_a7 = $codigoA07;

        return $this;
    }

    /**
     * Get codigoA07.
     *
     * @return string|null
     */
    public function getCodigoA07()
    {
        return $this->codigo_a7;
    }

    /**
     * Set codigoA08.
     *
     * @param string|null $codigoA08
     *
     * @return CargaConcepto
     */
    public function setCodigoA08($codigoA08 = null)
    {
        $this->codigo_a8 = $codigoA08;

        return $this;
    }

    /**
     * Get codigoA08.
     *
     * @return string|null
     */
    public function getCodigoA08()
    {
        return $this->codigo_a8;
    }

    /**
     * Set codigoA09.
     *
     * @param string|null $codigoA09
     *
     * @return CargaConcepto
     */
    public function setCodigoA09($codigoA09 = null)
    {
        $this->codigo_a9 = $codigoA09;

        return $this;
    }

    /**
     * Get codigoA09.
     *
     * @return string|null
     */
    public function getCodigoA09()
    {
        return $this->codigo_a9;
    }

    /**
     * Set codigoA10.
     *
     * @param string|null $codigoA10
     *
     * @return CargaConcepto
     */
    public function setCodigoA10($codigoA10 = null)
    {
        $this->codigo_a10 = $codigoA10;

        return $this;
    }

    /**
     * Get codigoA10.
     *
     * @return string|null
     */
    public function getCodigoA10()
    {
        return $this->codigo_a10;
    }

    /**
     * Set codigoA11.
     *
     * @param string|null $codigoA11
     *
     * @return CargaConcepto
     */
    public function setCodigoA11($codigoA11 = null)
    {
        $this->codigo_a11 = $codigoA11;

        return $this;
    }

    /**
     * Get codigoA11.
     *
     * @return string|null
     */
    public function getCodigoA11()
    {
        return $this->codigo_a11;
    }

    /**
     * Set codigoA12.
     *
     * @param string|null $codigoA12
     *
     * @return CargaConcepto
     */
    public function setCodigoA12($codigoA12 = null)
    {
        $this->codigo_a0 = $codigoA12;

        return $this;
    }

    /**
     * Get codigoA12.
     *
     * @return string|null
     */
    public function getCodigoA12()
    {
        return $this->codigo_a0;
    }
}
