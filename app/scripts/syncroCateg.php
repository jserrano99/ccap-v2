<?php

include_once __DIR__ . '/funcionesDAO.php';

function existeCateg($codigo) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id from ccap_categ as t1 "
                . " where t1.codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR NO EN SELECT CATEG=" . $codigo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de los puntos asistenciales, partiendo de la tabla centros de la
 * base de datos unificada y la eq_centros de la base de datos intermedia 
 * ** */
echo " +++++++++++ COMIENZA PROCESO SINCRONIZACIÓN CATEG +++++++++++ \n";
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

if ($tipo == 'REAL') {
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    echo " **** PRODUCCIÓN **** \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    echo " ++++ VALIDACIÓN ++++ \n";
}

$queryCateg = " select * from categ ";
$query = $JanoUnif->prepare($queryCateg);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
/*
 * Comprobamos que existe el código Unificado en la base de datos de control, si no existe
 * accedemos a la tabla centros de la base de datos unificada, cogemos los datos y hacemos el insert 
 * en la base de datos de control 
 */

foreach ($resultSet as $row) {
    $codigo = $row['CODIGO'];
    //var_dump($row);
    $existeCateg = existeCateg($codigo);
    if (!$existeCateg) {
        try {
            $sentencia = " insert into ccap_categ ("
                         ."  catgen_id "
                         ." ,codigo "
                         ." ,descripcion"
                         ." ,fsn"
                         ." ,catAnexo_id"
                         ." ,grupocot_id"
                         ." ,grupoprof_id"
                         ." ,grupocobro_id"
                         ." ,ocupacion_id"
                         ." ,epiacc_id"
                         ." ,enuso"
                         ." ,categ_orden"
                         ." ,categoriarptid"
                         ." ,catrpt_codigo"
                         ." ,catrpt_descripcion"
                         ." ,mir"
                         ." ,categ_sms"
                         ." ,grupo_tit"
                         ." ,prof_san"
                         ." ,directivo"
                         ." ,cno2011"
                         ." ,ceco_personal"
                         ." ,ceco_categoria"
                         ." ,tipocat"
                         ." ,condicionado"
                         ." ,id_grupocat "
                         ." ) values ( "
                         ."  :catgen_id "
                         ." ,:codigo"
                         ." ,:descripcion"
                         ." ,:fsn"
                         ." ,:catanexo_id"
                         ." ,:grupocot_id"
                         ." ,:grupoprof_id"
                         ." ,:grupocobro_id"
                         ." ,:ocupacion_id"
                         ." ,:epiacc_id"
                         ." ,:enuso"
                         ." ,:categ_orden"
                         ." ,:categoriarptid"
                         ." ,:catrpt_codigo"
                         ." ,:catrpt_descripcion"
                         ." ,:mir"
                         ." ,:categ_sms"
                         ." ,:grupo_tit"
                         ." ,:prof_san"
                         ." ,:directivo"
                         ." ,:cno2011"
                         ." ,:ceco_personal"
                         ." ,:ceco_categoria"
                         ." ,:tipocat"
                         ." ,:condicionado"
                         ." ,:id_grupocat )";
    
          $insert = $JanoControl->prepare($sentencia);
          $catgen = selectCatGen($row['CATGEN']);
          $catanexo_id = selectCatAnexo($row['CATANEXO']);
          $grupoprof_id = selectGrupoProf($row['GRUPOPROF']);
          $grupocot_id = selectGrupoCot($row['GRUPCOT']);
          $ocupacion_id = selectOcupacion($row['OCUPACION']);
          $epiacc_id = selectEpiAcc($row['EPIACC']);
          $grupocobro_id = selectGrupoCobro($row['GRUPOCOBRO']);
          
          $params = array(
                     ":catgen_id" => $catgen["id"],
                     ":codigo" => $row['CODIGO'] ,
                     ":descripcion"=> $row['DESCRIP'],
                     ":fsn"=> $row['FSN'],
                     ":catanexo_id"=> $catanexo_id,
                     ":grupocot_id"=> $grupocot_id,
                     ":grupoprof_id"=> $grupoprof_id,
                     ":grupocobro_id"=> $grupocobro_id,
                     ":ocupacion_id"=> $ocupacion_id,
                     ":epiacc_id"=> $epiacc_id,
                     ":enuso"=> $row['ENUSO'],
                     ":categ_orden"=> $row['CATEG_ORDEN'],
                     ":grupocobro_id"=> $grupocobro_id,
                     ":categoriarptid"=> $row['CATEGORIARPTID'],
                     ":catrpt_codigo"=> $row['CATRPT_CODIGO'],
                     ":catrpt_descripcion"=> $row['CATRPT_DESCRIPCION'],
                     ":mir"=> $row['MIR'],
                     ":categ_sms"=> $row['CATEG_SMS'],
                     ":grupo_tit"=> $row['GRUPO_TIT'],
                     ":prof_san"=> $row['PROF_SAN'],
                     ":directivo"=> $row['DIRECTIVO'],
                     ":cno2011"=> $row['CNO2011'],
                     ":ceco_personal"=> $row['CECO_PERSONAL'],
                     ":ceco_categoria"=> $row['CECO_CATEGORIA'],
                     ":tipocat"=> $row['TIPOCAT'],
                     ":condicionado"=> $row['CONDICIONADO'],
                     ":id_grupocat" => $row['ID_GRUPOCAT']);
            $res=$insert->execute($params);
            if ($res == 0) {
                echo " (1)ERROR EN INSERT ccap_CATEG \n";
            } else {
                echo " CREADO CATEG:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
            }
        } catch (PDOException $ex) {
            echo " PDOERROR EN INSERT ccap_CATEG ERROR=" . $ex->getMessage();
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO SYNCRONIZACIÓN CATGEN  +++++++++++++ \n";
exit(0);
