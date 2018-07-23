<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\EstadoUsuario;

/**
 * Usuario
 *
 * @ORM\Table(name="ccap_usuario")
 * @ORM\Entity
 */
class Usuario implements UserInterface {

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
     * @ORM\Column(name="codigo", type="string", length=255, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    
    /**
     * @var string
     *
     * @ORM\Column(name="perfil", type="string", length=255, nullable=true)
     */
    private $perfil;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var EstadoUsuario\null
     *
     * @ORM\ManyToOne(targetEntity="EstadoUsuario")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_usuario_id", referencedColumnName="id")
     * })
     */
    private $estadoUsuario;

    // FUNCIONES PARA LA GESTIÓN DE USUARIOS DE SYMFONY 

    public function getUsername() {
        $this->codigo;
    }

    public function getSalt() {
        return null;
    }

    public function getRoles() {
        return array($this->getPerfil());
    }

    public function eraseCredentials() {
        
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Usuario
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return Usuario
     */
    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos() {
        return $this->apellidos;
    }

    /**
     * Set perfil
     *
     * @param string $perfil
     *
     * @return Usuario
     */
    public function setPerfil($perfil) {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get perfil
     *
     * @return string
     */
    public function getPerfil() {
        return $this->perfil;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Usuario
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Get EstadoUsuario
     *
     * @return EstadoUsuario|null
     */
    public function getEstadoUsuario() {
        return $this->estadoUsuario;
    }

    /**
     * Set EstadoUsuario
     *
     * @return Usuario
     */
    public function setEstadoUsuario(EstadoUsuario $estadoUsuario) {
        $this->estadoUsuario = $estadoUsuario;
        return $this;
    }

    public function __toString() {
        return $this->apellidos . ', ' . $this->nombre;
    }

}
