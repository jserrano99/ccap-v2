<?php

include_once __DIR__ . '/../funcionesDAO.php';

/**
 * 
 * @global type $JanoUnif
 * @global type $cias
 * @global type $fecha
 * @return type
 */
function selectMovialtaByCias (){
        global $JanoUnif, $cias, $fecha;
    try {
        $sentencia = " select t3.cip, t3.dni, t3.ape12, t3.nombre, t2.codigo, t2.falta, t2.fbaja, t2.cip "
                   . " from movialta as t2 "
                   . " inner join trab as t3 on t3.cip = t2.cip "
                   ." where t2.cias = :cias and t2.fbaja >= :fecha ";
        $query = $JanoUnif->prepare($sentencia);
        $params = [":cias" => $cias,
                        ":fecha" => $fecha];
        $query->execute($params);
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        return $res;
        
    } catch (PDOException $ex) {
        echo "****PDOERROR EN SELECT MOVIALTA BASE DE DATOS UNIFICADA PARA CIAS =(".$cias.") FECHA AMORTIZACIÓN= (".$fecha. ") \n";
        echo " ERROR= ( ".$ex->getMessage().") \n";
        exit(1);
    }
}
/**
 * 
 * @global type $JanoUnif
 * @global type $cias
 * @global type $fecha
 * @return type
 */
function selectCcaByCias() {
    global $JanoUnif, $cias, $fecha;
    try {
        $sentencia = "select t3.cip, t3.dni, t3.ape12, t3.nombre, t2.codigo, t2.falta, t2.fbaja, t1.alta, t1.uf, t1.p_asist, t1.fini, t1.ffin  "
                   . " from cca as t1  "
                   . " inner join movialta as t2 on t2.codigo = t1.alta"
                   . " inner join trab as t3 on t3.cip = t2.cip "
                   . " where t1.cias = :cias and t1.ffin >= :fecha ";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":cias" => $cias,
                        ":fecha" => $fecha );
        $query->execute($params);
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        return $res;
        
    } catch (PDOException $ex) {
        echo "****PDOERROR EN SELECT CCA BASE DE DATOS UNIFICADA PARA CIAS =(".$cias.") FECHA AMORTIZACIÓN= (".$fecha. ") \n";
        echo " ERROR=(".$ex->getMessage().") \n";
        exit(1);
    }
}

/**
 * 
 * @global type $JanoUnif
 * @global type $cias
 * @global type $fecha
 * @return type
 */
function main() {
    global $JanoUnif, $cias, $fecha;
    
    $resCca = selectCcaByCias();
    if ($resCca != null) {
        echo "==> CIAS = (".$cias. ") tiene puestos (CCA) asignados para esta fecha: (".$fecha.") \n";
        foreach ($resCca as  $Cca ) {
            echo "--> CIP= (".$Cca["CIP"]. ") "
                . " DNI= (".$Cca["DNI"].") "
                . "NOMBRE= (".trim($Cca["APE12"]).", ".trim($Cca["NOMBRE"]).") "
                . " MOVIALTA: F.ALTA= (".$Cca["FALTA"].") "
                . "F.BAJA= (".$Cca["FBAJA"].") "
                . "UF= (".$Cca["UF"].") "
                    ."P_ASIST= (".$Cca["P_ASIST"].") "
                    ." CCA: F.INI= (".$Cca["FINI"].") "
                    ." F.FIN= (".$Cca["FFIN"].") \n";
        }
        return (1);
    }
    $resMovialta = selectMovialtaByCias();
    
if ($resMovialta != null) {
        echo "==> CIAS = (".$cias. ") tiene altas (MOVIALTA) asignados para esta fecha: (".$fecha.") \n";
        foreach ($resMovialta as  $Movialta ) {
            echo "--> CIP= (".$Movialta["CIP"]. ") "
                . " DNI= (".$Movialta["DNI"].") "
                . "NOMBRE= (".trim($Movialta["APE12"]).", ".trim($Movialta["NOMBRE"]).") "
                . " MOVIALTA: F.ALTA= (".$Movialta["FALTA"].") "
                . "F.BAJA= (".$Movialta["FBAJA"].") \n";
        }
        return (1);
    }
    
    return (0);
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 0;
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$modo = $argv[1];
$cias = $argv[2];
$fecha = $argv[3];

echo "\n\n ==> COMPROBACION AMORTIZACIÓN PARA CIAS =(".$cias.") FECHA AMORTIZACIÓN = (".$fecha.") \n";

if ($modo == 'REAL') {
    echo " ENTORNO : PRODUCCIÓN \n";
    $tipobd = 2;
    $JanoInte = conexionPDO(selectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
} else {
    echo " ENTORNO : VALIDACIÓN \n";
    $tipobd = 1;
    $JanoInte = conexionPDO(selectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
}

$resultado = main();

echo "==> FINALIZACIÓN COMPROBACIÓN RESULTADO= (".$resultado.") \n";

exit($resultado);
