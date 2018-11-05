<?php

/**
 * Description of Dependencia
 *
 * @author jluis
 */

namespace ComunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Dependencia
 *
 * @ORM\Table(name="comun_dependencias")
 * @ORM\Entity
 */
class Dependencia
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
     * @var CargaInicial
     *
     * @ORM\ManyToOne(targetEntity="CargaInicial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carga_inicial_dep_id", referencedColumnName="id")
     * })
     */

    private $cargaInicialDep;

    /**
     * @var CargaInicial
     *
     * @ORM\ManyToOne(targetEntity="CargaInicial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carga_inicial_id", referencedColumnName="id")
     * })
     */
    private $cargaInicial;


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
     * Set cargaInicialDep.
     *
     * @param \ComunBundle\Entity\CargaInicial|null $cargaInicialDep
     *
     * @return Dependencia
     */
    public function setCargaInicialDep(\ComunBundle\Entity\CargaInicial $cargaInicialDep = null)
    {
        $this->cargaInicialDep = $cargaInicialDep;

        return $this;
    }

    /**
     * Get cargaInicialDep.
     *
     * @return \ComunBundle\Entity\CargaInicial|null
     */
    public function getCargaInicialDep()
    {
        return $this->cargaInicialDep;
    }

    /**
     * @param CargaInicial|null $cargaInicial
     * @return $this
     */
    public function setCargaInicial(\ComunBundle\Entity\CargaInicial $cargaInicial = null)
    {
        $this->cargaInicial = $cargaInicial;

        return $this;
    }

    /**
     * Get cargaInicial.
     *
     * @return \ComunBundle\Entity\CargaInicial|null
     */
    public function getCargaInicial()
    {
        return $this->cargaInicial;
    }
}
