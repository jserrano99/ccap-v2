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
use MaestrosBundle\Entity\Ausencia;

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
	 * @var string
	 * @ORM\Column(name="causa_alta", type="string", length=250, nullable=true)
	 */
	private $causaAlta;
	/**
	 * @var string
	 * @ORM\Column(name="causa_baja", type="string", length=250, nullable=true)
	 */
	private $causaBaja;

	/**
	 * @var \MaestrosBundle\Entity\Ausencia
	 *
	 * @ORM\ManyToOne(targetEntity="MaestrosBundle\Entity\Ausencia")
	 * @ORM\JoinColumn(name="ausencia_id", referencedColumnName="id")
	 */
	private $ausencia;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="fini", type="date", nullable=true)
	 */
	private $fini;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="ffin", type="date", nullable=true)
	 */
	private $ffin;



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

    /**
     * Set causaAlta.
     *
     * @param string $causaAlta
     *
     * @return TempAltas
     */
    public function setCausaAlta($causaAlta)
    {
        $this->causaAlta = $causaAlta;

        return $this;
    }

    /**
     * Get causaAlta.
     *
     * @return string
     */
    public function getCausaAlta()
    {
        return $this->causaAlta;
    }

    /**
     * Set causaBaja.
     *
     * @param string $causaBaja
     *
     * @return TempAltas
     */
    public function setCausaBaja($causaBaja)
    {
        $this->causaBaja = $causaBaja;

        return $this;
    }

    /**
     * Get causaBaja.
     *
     * @return string
     */
    public function getCausaBaja()
    {
        return $this->causaBaja;
    }

    /**
     * Set ausencia.
     *
     * @param \MaestrosBundle\Entity\Ausencia|null $ausencia
     *
     * @return TempAltas
     */
    public function setAusencia(Ausencia $ausencia = null)
    {
        $this->ausencia = $ausencia;

        return $this;
    }

    /**
     * Get ausencia.
     *
     * @return \MaestrosBundle\Entity\Ausencia|null
     */
    public function getAusencia()
    {
        return $this->ausencia;
    }

    /**
     * Set fini.
     *
     * @param \DateTime $fini
     *
     * @return TempAltas
     */
    public function setFini($fini)
    {
        $this->fini = $fini;

        return $this;
    }

    /**
     * Get fini.
     *
     * @return \DateTime
     */
    public function getFini()
    {
        return $this->fini;
    }

    /**
     * Set ffin.
     *
     * @param \DateTime $ffin
     *
     * @return TempAltas
     */
    public function setFfin($ffin)
    {
        $this->ffin = $ffin;

        return $this;
    }

    /**
     * Get ffin.
     *
     * @return \DateTime
     */
    public function getFfin()
    {
        return $this->ffin;
    }
}
