<?php

/**
 * Description of EpiAcc
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EpiAcc
 *
 * @ORM\Table(name="ccap_epiacc", 
 *            indexes={@ORM\Index(name="idx001", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\EpiAccRepository")
 */
class EpiAcc {

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
     * @ORM\Column(name="codigo", type="string", length=4, nullable=false)
     */
    private $codigo;

    /**
     * @var decimal
     *
     * @ORM\Column(name="ilt", type="decimal", nullable=false)
     */
    private $ilt;

    /**
     * @var decimal
     *
     * @ORM\Column(name="ims", type="decimal", nullable=false)
     */
    private $ims;

    /**
     * @var decimal
     *
     * @ORM\Column(name="ilt_ant", type="decimal", nullable=false)
     */
    private $iltAnt;

    /**
     * @var decimal
     *
     * @ORM\Column(name="ims_ant", type="decimal", nullable=false)
     */
    private $imsAnt;

    

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
     * Set codigo
     *
     * @param string $codigo
     *
     * @return EpiAcc
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

    /**
     * Set ilt
     *
     * @param string $ilt
     *
     * @return EpiAcc
     */
    public function setIlt($ilt)
    {
        $this->ilt = $ilt;

        return $this;
    }

    /**
     * Get ilt
     *
     * @return string
     */
    public function getIlt()
    {
        return $this->ilt;
    }

    /**
     * Set ims
     *
     * @param string $ims
     *
     * @return EpiAcc
     */
    public function setIms($ims)
    {
        $this->ims = $ims;

        return $this;
    }

    /**
     * Get ims
     *
     * @return string
     */
    public function getIms()
    {
        return $this->ims;
    }

    /**
     * Set iltAnt
     *
     * @param string $iltAnt
     *
     * @return EpiAcc
     */
    public function setIltAnt($iltAnt)
    {
        $this->iltAnt = $iltAnt;

        return $this;
    }

    /**
     * Get iltAnt
     *
     * @return string
     */
    public function getIltAnt()
    {
        return $this->iltAnt;
    }

    /**
     * Set imsAnt
     *
     * @param string $imsAnt
     *
     * @return EpiAcc
     */
    public function setImsAnt($imsAnt)
    {
        $this->imsAnt = $imsAnt;

        return $this;
    }

    /**
     * Get imsAnt
     *
     * @return string
     */
    public function getImsAnt()
    {
        return $this->imsAnt;
    }
    
    public function __toString() {
        return $this->codigo;
    }
}
