<?php

namespace AppBundle\Servicios;

/**
 * Description of EscribeLog
 *
 * @author jluis_local
 */
class CalculaCeco {

    private $cias;
    
    private $pa;
    
    private $uf;
    
    private $cecoCalculado;
    
    private $entityManager;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->entityManager = $em;
    }


    public function calculaCeco() {
        
        $codigoUf = $this->uf->getOficial();
        $codigoPa = $this->pa->getOficial();
        $da = $this->uf->getDa()->getCodigo();
        $cias = $this->cias;
        $tipoUnidadFuncional = substr($codigoUf, 6, 2); //Posición 7-8 del codigo oficial de Uf
        $gerencia = substr($codigoUf, 2, 2); // posición 5-6 del Código Oficial
        $zonaBasica = substr($codigoUf, 4, 2); // posición 5-6 del Código Oficial de Uf
        $dispersion = substr($codigoUf, 9, 1); // Posición 10 del Código Oficial Uf
        $tipoPuesto = substr($cias, 6, 2); // Posición 7-8 del cias es el TIPO DE PUESTO 

        $CecoCalculado = $this->asignacionDirecta($tipoUnidadFuncional);
        if ($CecoCalculado != null) {
            return $CecoCalculado->getCodigo();
        }

        /*
         * $CecoCalculado = $this->asignacionDirecta($tipoUnidadFuncional);
         *
        if ($CecoCalculado != null) {
            $this->cecoCalculado =  $CecoCalculado->getCodigo();
        }
*/
        $Posicion1 = 'P'; // Constante 
        $Posicion2 = $da;
        $Posicion34 = $gerencia;
        $Posicion56 = $zonaBasica;
        $CecoCalculado = $Posicion1 . $Posicion2 . $Posicion34 . $Posicion56;
        switch ($tipoUnidadFuncional) {
            case '00': // SERVICIOS CENTRALES PENDIENTE DE REVISAR CODIFICACIÓN 
                $CecoCalculado = 'XXXXXXXXXX';
                break;
            case '20':
            case '21':
                $Posicion78 = '20';
                $Posicion9 = $dispersion;
                /*
                 * MIR-EIR TIENEN UN CENTRO DE COSTE FIJO PGAPC004(5580)
                 */
                if ($tipoPuesto == '25') {
                    $CecoCalculado = 'PGAPC004';
                } else {
                    $Posicion10 = $this->verTipoPuesto($tipoPuesto);
                    $CecoCalculado = $CecoCalculado . $Posicion78 . $Posicion9 . $Posicion10;
                }
                break;
            case '23':
                $CecoCalculado = $CecoCalculado . '23SA';
                break;
            case '24':
                $CecoCalculado = $CecoCalculado . '23PC';
                break;
            case '25': // Riesgos Laborales 
                $Posicion = substr($codigoUf, 8, 2); // Posición 9-10 del Código Oficial Uf
                $Posicion2 = 'P';
                $Posicion34 = 'RL';
                $Posicion56 = '00';
                $Posicion78 = '00';
                if ($codigoUf910 == '09') {
                    $Posicion910 = 'CC';
                } else {
                    $Posicion910 = $codigoUf910;
                }
                $CecoCalculado = $Posicion1 .
                        $Posicion2 .
                        $Posicion34 .
                        $Posicion56 .
                        $Posicion78 .
                        $Posicion910;
                break;
            case '28':
                $CecoCalculado = $CecoCalculado . '28FI';
                break;
            case '29':
                $CecoCalculado = $CecoCalculado . '29PO';
                break;
            case '30':
            case '31':
                $CecoCalculado = $CecoCalculado . '30SB';
                break;
            case '32':
                $CecoCalculado = $CecoCalculado . '32TS';
                break;
            case '33':
                $CecoCalculado = $CecoCalculado . '33AE';
                break;
            case '36':
                $CecoCalculado = $CecoCalculado . '36ES';
                break;
            case '37':
                $CecoCalculado = $CecoCalculado . '2OPA';
                break;
            case '51':
                $CecoCalculado = $CecoCalculado . '68OC';
                break;
            default :
                $CecoCalculado = 'XXXXXXXXXX';
        }

        return $CecoCalculado;
        
    }

    public function asignacionDirecta($codigoUf78) {
        
        $AsignacionDirecta_repo = $this->entityManager->getRepository("AppBundle:AsignacionDirecta");

        $AsignacionDirecta = $AsignacionDirecta_repo->createQueryBuilder('u')
                        ->where("u.codigoUf78 = :codigoUf78")
                        ->setParameter("codigoUf78", $codigoUf78)
                        ->getQuery()->getResult();

        if ($AsignacionDirecta) {
            return $AsignacionDirecta[0]->getCeco();
        } else {
            return null;
        }
    }

    public function verTipoPuesto($tipoPuesto) {
        switch ($tipoPuesto) {
            case '01':
                return 'M';
            case '02':
                return 'P';
            case '06':
            case '07':
            case '12':
            case '13':
                return 'E';
            case '14':
            case '16':
            case '70':
            case '71':
                return 'A';
            default:
                return null;
        }
    }

    
    public function getCias() {
        return $this->cias;
    }

    public function setCias($cias) {
        $this->cias = $cias;
        return $this;
    }

    public function getPa() {
        return $this->pa;
    }

    public function getUf() {
        return $this->uf;
    }

    public function setPa($pa) {
        $this->pa = $pa;
        return $this;
    }

    public function setUf($uf) {
        $this->uf = $uf;
        return $this;
    }
    public function getCecoCalculado() {
        return $this->cecoCalculado;
    }

    public function setCecoCalculado($cecoCalculado) {
        $this->cecoCalculado = $cecoCalculado;
        return $this;
    }

    public function getEntityManager() {
        return $this->entityManager;
    }

    public function setEntityManager($entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }



}
