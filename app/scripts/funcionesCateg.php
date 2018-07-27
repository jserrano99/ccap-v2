<?php

function equivalenciasCateg($CATEG, $edificio) {
    $catgen = selectEqCatGen($CATEG["catgen"], $edificio);
    $catanexo = selectEqCatAnexo($CATEG["catanexo"], $edificio);
    $grupocot = selectEqGrupoCot($CATEG["grupocot"], $edificio);
    //$epiacc = selectEqEpiAcc($CATEG["epiacc"],$edificio);
    $grupoprof = selectEqGrupoProf($CATEG["grupoprof"], $edificio);
    $grupocobro = selectEqGrupoCobro($CATEG["grupocobro"], $edificio);
    $ocupacion = selectEqOcupacion($CATEG["ocupacion"], $edificio);

    if ($catgen && $catanexo && $grupocot && $grupoprof && $grupocobro && $ocupacion) {
        $equivalenciasCateg["catgen"] = $catgen;
        $equivalenciasCateg["catanexo"] = $catanexo;
        $equivalenciasCateg["grupocot"] = $grupocot;
        $equivalenciasCateg["grupoprof"] = $grupoprof;
        $equivalenciasCateg["grupocobro"] = $grupocobro;
        $equivalenciasCateg["ocupacion"] = $ocupacion;

        echo "EQUIVALENCIAS\n";
        echo "-------------\n";
        echo " catgen = " . $CATEG["catgen"] . "/" . $catgen . "\n";
        echo " catanexo = " . $CATEG["catanexo"] . "/" . $catanexo . "\n";
        echo " grupocot = " . $CATEG["grupocot"] . "/" . $grupocot . "\n";
        echo " grupoprof = " . $CATEG["grupoprof"] . "/" . $grupoprof . "\n";
        echo " grupocobro = " . $CATEG["grupocobro"] . "/" . $grupocobro . "\n";
        echo " ocupacion = " . $CATEG["ocupacion"] . "/" . $ocupacion . "\n";

        return $equivalenciasCateg;
    } else {
        return null;
    }
}

function insertCategAreas($CATEG, $conexion, $edificio) {
    try {
        $sentencia = " insert into categ "
                . " ( codigo, catgen, descrip, fsn, catanexo, grupcot, epiacc, grupoprof, enuso, grupocobro "
                . " ,ocupacion, mir, condicionado, directivo ) values "
                . " ( :codigo, :catgen, :descrip, :fsn, :catanexo, :grupcot, :epiacc, :grupoprof, :enuso, :grupocobro "
                . " ,:ocupacion, :mir, :condicionado, :directivo )";

        $insert = $conexion->prepare($sentencia);

        $params = parametrosCateg($CATEG, $edificio);

        if (!$params)
            return false;

        $res = $insert->execute($params);
        if ($res) {
            echo " CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . " CREADA EN LA B.D. \n";
            return true;
        } else {
            echo "**ERROR EN INSERT CATEG CODIGO=" . $CATEG["codigo"] . " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CATEG CODIGO= " . $CATEG["codigo"] . " " . $CATEG["descrip"] . $ex->getMessage() . "\n";
        return false;
    }
}

function parametrosCateg($CATEG, $edificio) {

    $equivalenciasCateg = equivalenciasCateg($CATEG, $edificio);
    if (!$equivalenciasCateg) {
        echo "**ERROR AL ESTABLECER LAS EQUIVALENCIAS \n";
        return null;
    }

    $params = array(":codigo" => $CATEG["codigo"],
        ":catgen" => $equivalenciasCateg["catgen"],
        ":descrip" => $CATEG["descripcion"],
        ":fsn" => $CATEG["fsn"],
        ":catanexo" => $equivalenciasCateg["catanexo"],
        ":grupcot" => $equivalenciasCateg["grupocot"],
        ":epiacc" => $CATEG["epiacc"],
        ":grupoprof" => $equivalenciasCateg["grupoprof"],
        ":enuso" => $CATEG["enuso"],
        ":grupocobro" => $equivalenciasCateg["grupocobro"],
        ":ocupacion" => $equivalenciasCateg["ocupacion"],
        ":mir" => $CATEG["mir"],
        ":condicionado" => $CATEG["condicionado"],
        ":directivo" => $CATEG["directivo"]);

    return $params;
}
