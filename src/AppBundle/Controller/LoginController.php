<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Acceso;
use AppBundle\Entity\Usuario;

class LoginController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function logoutAction() {
        session_destroy();
        return $this->redirectToRoute("app_homepage");
    }

    public function loginAction() {
        $navegador = @get_browser(null, false);
        if (($navegador != null) && ($navegador->browser == "IE" && $navegador->version < 12)) {
            return $this->render('AppBundle:IE8:login8.html.twig');
        } else {
            return $this->render('login/login.html.twig');
        }
    }

    public function checkAction(Request $request) {
        $username = $_POST["usuario"];
        $password = $_POST["password"];

        $LdapAll = $this->usuarioAutenticadoLdap($username, $password);

        /*
         * Primero se identifica contra el directorio activo de sanidad 
         */
        if (!$LdapAll) {
            $status = " ERROR EN ACCESO, REVISAR USUARIO Y CONTRASEÑA ";
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("login");
        }
        $Usuario = $this->verUsuario($username);
        if (!$Usuario) {
            $Usuario = $this->creaUsuario($LdapAll, $username);
        }
        $UltimoAcceso = $this->ultimoAcceso($Usuario);
        $this->creaAcceso($Usuario);

        $this->sesion->set('usuario_nombre', $Usuario->getNombre());
        $this->sesion->set('usuario_perfil', $Usuario->getPerfil());
        if ($UltimoAcceso) {
            $this->sesion->set('ultimo_acceso', $UltimoAcceso->getFecha());
        } else {
            $this->sesion->set('ultimo_acceso', null);
        }
        return $this->render('login/acceso.html.twig');
    }

    public function ultimoAcceso($Usuario) {
        $em = $this->getDoctrine()->getManager();
        $Acceso_repo = $em->getRepository("AppBundle:Acceso");
        $AccesoAll = $AccesoAll = $Acceso_repo->createQueryBuilder('u')
                        ->where('u.usuario = :usuario')
                        ->setParameter('usuario', $Usuario)
                        ->addOrderBy('u.id', 'desc')
                        ->getQuery()->getResult();
        if ($AccesoAll) {
            return $AccesoAll[0];
        } else {
            return null;
        }
    }

    public function creaAcceso($Usuario) {
        $em = $this->getDoctrine()->getManager();
        $Acceso = new Acceso();
        $Acceso->setUsuario($Usuario);
        $Acceso->setIp($_SERVER['REMOTE_ADDR']);
        $em->persist($Acceso);
        $em->flush();
    }

    public function verUsuario($username) {
        $em = $this->getDoctrine()->getManager();
        $Usuario_repo = $em->getRepository("AppBundle:Usuario");
        $UsuarioAll = $Usuario_repo->createQueryBuilder('u')
                        ->where('u.codigo = :codigo')
                        ->setParameter('codigo', $username)
                        ->getQuery()->getResult();
        if ($UsuarioAll) {
            return $UsuarioAll[0];
        } else {
            return null;
        }
    }

    public function creaUsuario($LdapAll, $username) {
        $EM = $this->getDoctrine()->getManager();
        $EstadoUsuario_repo = $EM->getRepository("AppBundle:EstadoUsuario");
        $EstadoUsuario = $EstadoUsuario_repo->find(1);

        $Usuario = new Usuario();
        $Usuario->setCodigo($username);
        $Usuario->setNombre($LdapAll['displayname'][0]);
        $Usuario->setEmail($LdapAll['mail'][0]);
        $Usuario->setPerfil("ROLE_USER");
        $Usuario->setEstadoUsuario($EstadoUsuario);

        $EM->persist($Usuario);
        $EM->flush();
        return $Usuario;
    }

    public function usuarioAutenticadoLdap($username, $password) {
        $autenticado = false;
        $ldaprdn = $username;
        $ldappass = $password;
//$attributes_ad = array("displayName","description","cn","givenName","sn","mail","co","mobile","company","displayName");
        $attributes_ad = array("displayName", "mail");
        $servidor = "salud.madrid.org";
        $dn = "OU=CSCM,DC=salud,DC=madrid,DC=org";
        $ldapconn = ldap_connect($servidor)
                or die("No se puede conectar con el servidor LDAP.");
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
        if ($ldapconn && trim($ldappass) != "") {
            $autenticado = @ldap_bind($ldapconn, $ldaprdn . "@" . $servidor, $ldappass);
            if ($autenticado) {
                $result = ldap_search($ldapconn, $dn, "(samaccountname=$username)", $attributes_ad);
                $entries = ldap_get_entries($ldapconn, $result);
                return $entries[0];
            }
        }

        return $autenticado;
    }

}
