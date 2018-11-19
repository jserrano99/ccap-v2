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
        global $JanoUnif, $cias;
    try {
        $sentencia = " select t3.cip, t3.dni, t3.ape12, t3.nombre, t2.codigo, t2.falta, t2.fbaja, t2.cip "
                   . " from movialta as t2 "
                   . " inner join trab as t3 on t3.cip = t2.cip "
                   ." where t2.cias = :cias ";
        $query = $JanoUnif->prepare($sentencia);
        $params = [":cias" => $cias];
        $query->execute($params);
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        return $res;
        
    } catch (PDOException $ex) {
        echo "****PDOERROR EN SELECT MOVIALTA BASE DE DATOS UNIFICADA PARA CIAS =(".$cias.")\n";
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
function main() {
    global $cias;

    $resMovialta = selectMovialtaByCias();
    
if ($resMovialta != null) {
        echo "==> CIAS = (".$cias. ") tiene altas (MOVIALTA) asignados \n";
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

echo "\n\n ==> COMPROBACION DELETE PARA CIAS =(".$cias.") \n";

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
