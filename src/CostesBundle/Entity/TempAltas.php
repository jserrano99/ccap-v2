<?php
/**
 * Created by PhpStorm.
 * User: jluis_local
 * Date: 20/11/2018
 * Time: 13:39
 */

namespace CostesBundle\Entity;

use CostesBundle\Entity\Plaza;
use Doctrine\ORM\Mapping as ORM;
/**
 * Plaza
 *
 * @ORM\Table(name="ccap_temp_altas")
 *
 * @ORM\Entity(repositoryClass="CostesBundle\Repository\TempAltasRepository")
 */
class TempAltas
{
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
	 * @ORM\JoinColumn(name="plaza_id", referencedColumnName="id")
	 */
	private $plaza;
	/**
	 * @var integer
	 * @ORM\Column(name="cip", type="string", nullable=false)
	 */
	private $cip;
	/**
	 * @var string
	 * @ORM\Column(name="dni", type="string", length=10, nullable=false)
	 */
	private $dni;
	/**
	 * @var string
	 * @ORM\Column(name="nombre", type="string", length=250, nullable=false)
	 */
	private $nombre;
	/**
	 * @var date
	 * @ORM\Column(name="f_alta", type="date", nullable=false)
	 */
	private $fAlta;
	/**
	 * @var date
	 * @ORM\Column(name="f_baja", type="date", nullable=true)
	 */
	private $fBaja;

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
     * Set cip.
     *
     * @param string $cip
     *
     * @return TempAltas
     */
    public function setCip($cip)
    {
        $this->cip = $cip;

        return $this;
    }

    /**
     * Get cip.
     *
     * @return string
     */
    public function getCip()
    {
        return $this->cip;
    }

    /**
     * Set dni.
     *
     * @param string $dni
     *
     * @return TempAltas
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni.
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return TempAltas
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fAlta.
     *
     * @param \DateTime $fAlta
     *
     * @return TempAltas
     */
    public function setFAlta($fAlta)
    {
        $this->fAlta = $fAlta;

        return $this;
    }

    /**
     * Get fAlta.
     *
     * @return \DateTime
     */
    public function getFAlta()
    {
        return $this->fAlta;
    }

    /**
     * Set fBaja.
     *
     * @param \DateTime|null $fBaja
     *
     * @return TempAltas
     */
    public function setFBaja($fBaja = null)
    {
        $this->fBaja = $fBaja;

        return $this;
    }

    /**
     * Get fBaja.
     *
     * @return \DateTime|null
     */
    public function getFBaja()
    {
        return $this->fBaja;
    }

    /**
     * Set plaza.
     *
     * @param \CostesBundle\Entity\Plaza|null $plaza
     *
     * @return TempAltas
     */
    public function setPlaza(\CostesBundle\Entity\Plaza $plaza = null)
    {
        $this->plaza = $plaza;

        return $this;
    }

    /**
     * Get plaza.
     *
     * @return \CostesBundle\Entity\Plaza|null
     */
    public function getPlaza()
    {
        return $this->plaza;
    }
}
