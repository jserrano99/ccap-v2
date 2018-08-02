<?php

include_once __DIR__ . '/../funcionesDAO.php';

/*
 * función para obtener todos los datos de la tabla de control de plazas 
 */


/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de las PLAZAS (CIAS), partiendo de los movimientos existenes en la tabla 
 * inte_plazas de la base de datos unificada, 
 * ** */

echo " ++++++ COMIENZA PROCESO CARGA INICIAL DE  PLAZAS +++++++++++ \n";
/*
 * Conexión a la base de datos de Control en Mysql 
 */
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}
/*
 * recogemos el parametro para ver si estamos en pruebas en validación o en producción
 */
$tipo = $argv[1];
$gblError = 0;

if ($tipo == 'REAL') {
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    echo "==> ENTORNO: PRODUCCIÓN \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    echo "==> ENTORNO: VALIDACIÓN \n";
}

try {
    $sentencia = "select * from plazas";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "***PDOERROR EN SELECT PLAZAS " . $ex->getMessage() . "\n";
    exit(1);
}

echo "==>Número de Plazas a cargar : " . count($resultSet) . "\n";

foreach ($resultSet as $row) {
    $plaza = selectPlaza($row["CIAS"]);

    if ($plaza) {
        //update;
    } else {
        try {
            $sentencia = " insert into ccap_plazas "
                    . " (cias, uf_id, pa_id, catgen_id, catfp_id, plantilla,f_creacion, f_amortiza, modalidad_id"
                    . " ,ficticia, refuerzo, colaboradora, observaciones,amortizada,orden )"
                    . " values "
                    . "(:cias, :uf_id, :pa_id, :catgen_id, :catfp_id, :plantilla, :f_creacion, :f_amortiza, :modalidad_id"
                    . " ,:ficticia, :refuerzo, :colaboradora, :observaciones, :amortizada, :orden )";
            $query = $JanoControl->prepare($sentencia);
            $UF = selectUf($row["UF"]);
            if ($UF == null) {
                echo "**ERROR NO EXISTE UNIDAD FUNCIONAL " . $row["UF"];
                $gblError = 1;
            }
            $PA = selectPa($row["P_ASIST"]);
            if ($PA == null) {
                echo "**ERROR NO EXISTE PUNTO ASISTENCIAL " . $row["PA"];
                $gblError = 1;
            }
            $CATGEN = selectCatGen($row["CATGEN"]);
            $CATFP = selectCatFp($row["CATFP"]);
            $orden = substr($row["cias"], 9, 2);
            if ($row["F_AMORTIZA"] == null) {
                $amortizada = 'N';
            } else {
                $amortizada = 'S';
            }
            $params = array(":cias" => $row["CIAS"],
                ":uf_id" => $UF["id"],
                ":pa_id" => $PA["id"],
                ":catgen_id" => $CATGEN["id"],
                ":catfp_id" => $CATFP["id"],
                ":plantilla" => $row["PLANTILLA"],
                ":f_amortiza" => $row["F_AMORTIZA"],
                ":modalidad_id" => selectModalidad($row["MODALIDAD"]),
                ":f_creacion" => $row["FCREACION"],
                ":ficticia" => $row["FICTICIA"],
                ":colaboradora" => $row["COLABORADORA"],
                ":refuerzo" => $row["REFUERZO"],
                ":observaciones" => $row["OBSERVACIONES"],
                ":amortizada" => $amortizada,
                ":orden" => $orden);
            $insert = $query->execute($params);

            if ($insert == 0) {
                echo "**ERROR EN INSERT PLAZA CIAS=" . $row["CIAS"] . "\n";
                $gblError = 1;
            } else {
                echo "==>GENERADA PLAZA=" . $row["CIAS"] . " UF=" . $row["UF"] . " PA=" . $row["P_ASIST"] . "\n";
            }
        } catch (PDOException $ex) {
            echo "**PDOERROR EN INSERT PLAZA CIAS=" . $row["CIAS"] . " \n". " ERROR = ".$ex->getMessage() . "\n";
                $gblError = 1;
        }
    }
}
echo " +++++ TERMINA PROCESO CARGA INICIAL PLAZAS ++++ \n";
exit($gblError);
