<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModOcupa
 *
 * @ORM\Table(name="gums_modocupa"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\ModOcupaRepository")
 */
class ModOcupa {

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
     * @ORM\Column(name="codigo", type="string", length=1, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descrip", type="string", length=25, nullable=true)
     */
    private $descrip;

    /**
     * @var string
     *
     * @ORM\Column(name="fie", type="string", length=1, nullable=false)
     */
    private $fie;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return ModOcupa
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Set descrip.
     *
     * @param string|null $descrip
     *
     * @return ModOcupa
     */
    public function setDescrip($descrip = null) {
        $this->descrip = $descrip;

        return $this;
    }

    /**
     * Get descrip.
     *
     * @return string|null
     */
    public function getDescrip() {
        return $this->descrip;
    }

    /**
     * Set fie.
     *
     * @param string $fie
     *
     * @return ModOcupa
     */
    public function setFie($fie) {
        $this->fie = $fie;

        return $this;
    }

    /**
     * Get fie.
     *
     * @return string
     */
    public function getFie() {
        return $this->fie;
    }

    public function __toString() {
        return $this->descrip. ' ('. $this->codigo.')';
    }

}
