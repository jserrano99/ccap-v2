<?php
/*
 * obtener todos los registros de movialta para un cias determinado
 */

function selectMovialtabyCias($cias) {
    global $JanoUnif;
    try {
        $sentencia = " select t1.cip, t3.dni, t3.nombre, t3.ape12, t1.codigo, t1.cias, "
                . " t2.fini, t2.uf, t2.p_asist, t2.cecos, t2.id as cca_id, t1.falta, t1.fbaja from movialta as t1 "
                . " inner join cca  as t2 on t2.alta = t1.codigo "
                . " inner join trab as t3 on t3.cip = t1.cip "
                . " where t1.cias = :cias";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":cias" => $cias);
        $query->execute($params);
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            echo "**NO EXISTEN MOVIALTAS PARA ESTE CIAS=" . $cias . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN MOVIALTA CIAS= " . $cias . " ERROR = " . $ex->getMessage() . "\n";
        return false;
    }
}

function actualizaCcaArea($conexion, $ceco, $cca_id) {
    try {
        $sentencia = " update cca set cecos = :ceco where id = :cca_id";
        $query = $conexion->prepare($sentencia);
        $params = array(":ceco" => $ceco, ":cca_id" => $cca_id);
        $res = $query->execute($params);
        if (!$res) {
            echo "**ERROR EN UPDATE CCA ID= " . $cca_id . "\n";
            return null;
        }
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE CCA ID= " . $cca_id . " ERROR = " . $ex->getMessage() . "\n";
        return null;
    }
}

function actualizaCcaUnif($ceco, $cca_id) {
    global $JanoUnif;
    try {
        $sentencia = " update cca set cecos = :ceco where id = :cca_id";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":ceco" => $ceco, ":cca_id" => $cca_id);
        $res = $query->execute($params);
        if (!$res) {
            echo "**ERROR EN UPDATE CCA UNIF ID= " . $cca_id . "\n";
            return null;
        }
        echo "===>Modificado CECO para cca id= ".$cca_id. " ceco: ".$ceco."\n";
        
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE CCA UNIF ID= " . $cca_id . " ERROR = " . $ex->getMessage() . "\n";
        return null;
    }
}

function asignacionCeco($Plaza) {

    /*
     * Tratamiento para la base de datos unificada
     */
    $MovialtaAll = selectMovialtabyCias($Plaza["cias"]);

    foreach ($MovialtaAll as $movialta) {
        echo "==>Tratamiento DNI=" . $movialta["DNI"]
        . "  NOMBRE: " . $movialta["NOMBRE"] . " " . $movialta["APE12"]
                . " F.ALTA: ".$movialta["FALTA"]. " F.BAJA: ".$movialta["FBAJA"]
        . " \n";
        $unifOk = actualizaCcaUnif($Plaza["ceco"], $movialta["CCA_ID"]);
        if (!$unifOk) {
            echo "**ERROR EN LA ACTUALIZACIÓN DE CCA EN LA BASE DE DATOS UNIFICADA CIAS=" . $Plaza["cias"] . "*** \n";
            return null;
        }
     
        $unifOk = actualizaCcaUnif($Plaza["ceco"], $movialta["CCA_ID"]);
     
    }
    return true;
}
