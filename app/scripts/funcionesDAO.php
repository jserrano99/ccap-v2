<?php

require_once '../vendor/autoload.php';

/* * ****************************************
 * CONEXIÓN Y DESCONXESIÓN A BASE DE DATOS *
 * ***************************************** */

function selectCatFp($catfp) {
    global $JanoControl;
    try {
        $sentencia = " select * from ccap_catfp where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $catfp);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            echo "**ERROR NO EXISTE CATFP=" . $catfp . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN CATFP=" . $catFp . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectModalidad($modalidad) {
    global $JanoControl;
    try {
        $sentencia = " select id from ccap_modalidad where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => trim($modalidad));
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo "**ERROR NO EXISTE MODALIDAD=" . $modalidad . "*" . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN MODALIDAD=" . $modalidad . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function conexionPDO($datosConexion) {
    global $ERROR, $sdterr;
    $conexion = 0;
    $host = $datosConexion['maquina'];
    $service = $datosConexion['puerto'];
    $database = $datosConexion['esquema'];
    $server = $datosConexion['servidor'];
    $protocol = "onsoctcp";
    $user = $datosConexion['usuario'];
    $pass = $datosConexion['password'];

    $cadena = "informix:host=" . $host
            . "; service=" . $service
            . "; database=" . $database
            . "; server=" . $server
            . "; protocol=" . $protocol;
    try {
        $conexion = new \PDO($cadena, $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$conexion->setAttribute(PDO);
        echo 'CONEXIÓN GENERADA CORRECTAMENTE: ' . $cadena . "\n";
    } catch (PDOException $e) {
        echo "**PDOERROR EN CONEXION: " . $cadena . " MENSAJE ERROR: " . $e->getMessage() . " \n";
        return null;
    }
    return $conexion;
}

/* * ***********************************************************************************
 * Función jano_ctrol() establece la conexión a la base de datos mysql de control de Jano
 * ********************************************************************************** */

function jano_ctrl() {
    $filename = __DIR__ . '/../config/parameters.yml';
    $parametros = \Symfony\Component\Yaml\Yaml::parseFile($filename);
    $host_name = $parametros["parameters"]["database_host"];
    $database = $parametros["parameters"]["database_name"];
    $user_name = $parametros["parameters"]["database_user"];
    $password = $parametros["parameters"]["database_password"];

    try {
        $cadena = "mysql:host=" . $host_name
                . "; dbname=" . $database;

        $conn = new PDO($cadena, $user_name, $password);
        $conn->setAttribute(PDO::ATTR_PERSISTENT, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "CONEXIÓN A CCAP CORRECTA= " . $cadena . " ** \n";
    } catch (PDOException $e) {
        $error = $e->getMessage();
        echo $error . " \n";
        return null;
    }
    return $conn;
}

function selectBaseDatosAreas($tipo) {
    global $JanoControl;
    try {
        $sql = "select t1.id "
                . " ,t1.alias "
                . " ,t1.maquina"
                . " ,t1.puerto"
                . " ,t1.servidor"
                . " ,t1.esquema"
                . " ,t1.usuario"
                . " ,t1.password "
                . " ,t2.codigo as edificio "
                . " from comun_base_datos  as t1 "
                . " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
                . " where tipo_bd_id = :tipo and activa = 'S' and areas = 'S'";
        $query = $JanoControl->prepare($sql);
        $params = [":tipo" => $tipo];
        $query->execute($params);
        $resultSet = $query->fetchALL(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        $error = $ex->getMessage();
        echo $error . " \n";
        return null;
    }
    return $resultSet;
}

function selectBaseDatos($tipo, $areas) {
    global $JanoControl;
    try {
        $sql = "select id, alias, maquina, puerto, servidor, esquema, usuario, password"
                . " from comun_base_datos "
                . " where tipo_bd_id = :tipo and activa = 'S' and areas = :areas ";

        $query = $JanoControl->prepare($sql);
        $params = [":tipo" => $tipo,
            ":areas" => $areas];
        $res = $query->execute($params);
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);
        if (count($resultSet) == 0) {
            return null;
        } else {
            return $resultSet;
        }
    } catch (PDOException $ex) {
        $error = $ex->getMessage();
        echo $error . " \n";
        return null;
    }
}

function selectBaseDatosEdificio($tipo, $edificio) {
    global $JanoControl;
    try {
        $sql = "select t1.id, t1.alias, t1.maquina, t1.puerto, t1.servidor, t1.esquema, t1.usuario, t1. password, t2.codigo as edificio"
                . " from comun_base_datos  as t1 "
                . "inner join comun_edificio as t2 on t2.id = t1.edificio_id"
                . " where t1.tipo_bd_id = :tipo and t1.activa = 'S' and "
                . "t2.codigo = :edificio ";

        $query = $JanoControl->prepare($sql);
        $params = [":tipo" => $tipo,
            ":edificio" => $edificio];
        $query->execute($params);
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);
        if (count($resultSet) == 0) {
            echo "**ERROR NO EXISTE BASE DE DATOS PARA TIPO= " . $tipo . " EDIFICIO= " . $edificio . "\n";
            return null;
        } else {
            return $resultSet;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN comun_base_datos PARA TIPO= " . $tipo . " EDIFICIO= " . $edificio . "\n" . $ex->getMessage() . "\n";
        return null;
    }
}

function selectEdificioId($edificio) {
    global $JanoControl;
    try {
        $sql = "select id from comun_edificio "
                . " where codigo = :edificio ";
        $query = $JanoControl->prepare($sql);
        $params = array(":edificio" => $edificio);
        $query->execute($params);
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);
        if (count($resultSet) == 0) {
            return null;
        } else {
            return $resultSet["id"];
        }
    } catch (PDOException $ex) {
        $error = $ex->getMessage();
        echo $error . " \n";
        return null;
    }
}

function selectCeco($ceco_id) {
    global $JanoControl;
    try {
        $sql = "select id as id"
                . " ,sociedad as sociedad"
                . " ,division as division"
                . " ,codigo as codigo"
                . " ,descripcion as descripcion "
                . " from ccap_cecos"
                . " where id = :ceco_id";
        $query = $JanoControl->prepare($sql);
        $params = [":ceco_id" => $ceco_id];
        $query->execute($params);
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);
        return $resultSet;
    } catch (PDOException $ex) {
        $error = $ex->getMessage();
        echo $error . " \n";
        return null;
    }
}

function SelectCecobyCodigo($codigo) {
    global $JanoControl;
    try {
        $sql = "select id as id"
                . " ,sociedad as sociedad"
                . " ,division as division"
                . " ,codigo as codigo"
                . " ,descripcion as descripcion "
                . " from ccap_cecos"
                . " where codigo= :codigo";
        $query = $JanoControl->prepare($sql);
        $params = [":codigo" => $codigo];
        $query->execute($params);
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);
        return $resultSet;
    } catch (PDOException $ex) {
        $error = $ex->getMessage();
        echo $error . " \n";
        return null;
    }
}

function insertEqCeco($codigo_uni) {
    global $JanoInte;
    try {
        $sentencia = "insert into eq_cecos (codigo_uni) values (:codigo_uni)";
        $query = $JanoInte->prepare($sentencia);
        $params = [":codigo_uni" => $codigo_uni];
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "**ERROR YA EXISTE EN EQ_CECO CODIGO= " . $codigo_uni . "\n";
            return null;
        }
        echo " INSERCION CORRECTA EN JANO_INTE (EQ_CECOS) CECO= " . $codigo_uni . " \n";
        return true;
    } catch (PDOException $ex) {
        if ($ex->getCode() == 23000) {
            echo "**PDOERROR Ya exite en EQ_CECO CODIGO= " . $codigo_uni . "\n";
        } else {
            echo "**PDOERROR EN EQ_CECO CODIGO= " . $codigo_uni . " " . $ex->getMessage() . "\n";
        }
        return false;
    }
}

function deleteEqCeco($codigo_uni) {
    global $JanoInte;
    try {
        $sentencia = " delete from eq_cecos where codigo_uni = :codigo_uni";
        $query = $JanoInte->prepare($sentencia);
        $params = [":codigo_uni" => $codigo_uni];
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "**ERROR EN EL BORRADO EN JANO_INTE(EQ_CECO) CECO= " . $codigo_uni . "\n";
            return null;
        }
        echo " BORRADO CORRECTO EN JANO_INTE CECO= " . $codigo_uni . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN EL BORRADO EN JANO_INTE(EQ_CECO) CECO= " . $codigo_uni . " " . $ex->getMessage() . "\n";
        return false;
    }
}

function selectEdificio($edificio) {
    global $JanoControl;
    try {
        $query = $JanoControl->prepare("select id from comun_edificio where codigo = :edificio");
        $params = [":edificio" => $edificio];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return ($res["id"]);
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " Error en función selectEdificio, edificio=" . $edificio . " " . $ex->getMessage();
        return null;
    }
}

function selectDa($da) {
    global $JanoControl;
    try {
        $query = $JanoControl->prepare("select id from comun_da where codigo = :da");
        $params = [":da" => $da];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return ($res["id"]);
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " Error en función selectDa, da=" . $da . " " . $ex->getMessage();
        return null;
    }
}

/*
 * Función para obtener los datos del puntos asistencial de la tabla 
 * centros de la base de datos unificada 
 */

function selectCentro($codigo) {
    global $JanoUnif;
    try {
        $query = $JanoUnif->prepare(" select  * from centros where codigo = :codigo");
        $params = [":codigo" => $codigo];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return ($res);
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " Error en función selectCentros centros codigo=" . $codigo . " " . $ex->getMessage();
        return null;
    }
}

function existeCentro($codigoUni) {
    global $JanoControl;
    try {
        $query = $JanoControl->prepare("select id from ccap_uf where uf = :codigo_uni");
        $params = [":codigo_uni" => $codigoUni];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            return ($res["id"]);
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " Error en función existeCentro, ccap_uf uf=" . $codigoUni . " " . $ex->getMessage();
        return null;
    }
}

function selectCatGen($catgen) {
    global $JanoControl;

    try {
        $sentencia = " select * from ccap_catgen where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $catgen);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            echo " ERROR NO EXISTE CATGEN=" . $catgen . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR PDO EN CATGEN=" . $catgen . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectCatAnexo($catanexo) {
    global $JanoControl;

    try {
        $sentencia = " select id from ccap_catanexo where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $catanexo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo " ERROR NO EXISTE CATANEXO=" . $catanexo . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR PDO EN CATANEXO=" . $catanexo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectEpiAcc($epiacc) {
    global $JanoControl;

    try {
        $sentencia = " select id from ccap_epiacc where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $epiacc);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo " ERROR NO EXISTE EPIACC=" . $epiacc . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR PDO EN EPIACC=" . $epiacc . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectOcupacion($ocupacion) {
    global $JanoControl;

    try {
        $sentencia = " select id from ccap_ocupacion where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $ocupacion);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo " ERROR NO EXISTE OCUPACION =" . $ocupacion . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR PDO EN OCUPACION =" . $ocupacion . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectGrupoCot($grupoCot) {
    global $JanoControl;

    try {
        $sentencia = " select id from ccap_grupocot where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $grupoCot);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo " ERROR NO EXISTE GRUPOCOT =" . $grupoCot . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR PDO EN GRUPOCOT =" . $grupoCot . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectGrupoProf($grupoProf) {
    global $JanoControl;

    try {
        $sentencia = " select id from ccap_grupoprof where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $grupoProf);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo " ERROR NO EXISTE GRUPOPROF =" . $grupoProf . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR PDO EN GRUPOPROF =" . $grupoProf . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectGrupoCobro($grupoCobro) {
    global $JanoControl;

    try {
        $sentencia = " select id from ccap_grc where "
                . " codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $grupoCobro);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo " ERROR NO EXISTE GRUPOCOBRO =" . $grupoCobro . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR PDO EN GRUPOCOBRO =" . $grupoCobro . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectUf($uf) {
    global $JanoControl;
    try {
        $sentencia = " select t1.*, t2.codigo as edificio, t3.codigo as da "
                . " from ccap_uf as t1  "
                . " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
                . " inner join comun_da as t3 on t3.id = t1.da_id "
                . " where t1.uf = :uf ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":uf" => $uf);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            return $res;
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " ERROR PDO EN UF=" . $uf . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectPa($pa) {
    global $JanoControl;
    try {
        $sentencia = " select t1.*, t2.codigo as edificio, t3.codigo as da "
                . " from ccap_pa as t1  "
                . " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
                . " inner join comun_da as t3 on t3.id = t1.da_id "
                . " where t1.pa = :pa ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":pa" => $pa);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            return $res;
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " ***PDOERROR EN CCAP_PA PUNTO ASISTENCIAL=" . $pa . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectCateg($categ_id) {
    global $JanoControl;
    try {
        $sentencia = " select t1.*, t2.codigo as catgen, t3.codigo as catanexo, t4.codigo as grupocot ,t5.codigo as grupoprof"
                . ",t6.codigo as grupocobro, t7.codigo as ocupacion, t8.codigo as epiacc from ccap_categ as t1  "
                . " right join ccap_catgen as t2 on t2.id = t1.catgen_id"
                . " right join ccap_catanexo as t3 on t3.id = t1.catAnexo_id"
                . " right join ccap_grupocot as t4 on t4.id = t1.grupocot_id"
                . " right join ccap_grupoprof as t5 on t5.id = t1.grupoprof_id"
                . " right join ccap_grc as t6 on t6.id = t1.grupocobro_id"
                . " right join ccap_ocupacion as t7 on t7.id = t1.ocupacion_id"
                . " right join ccap_epiacc as t8 on t8.id = t1.epiacc_id"
                . " where t1.id = :categ_id ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":categ_id" => $categ_id);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {

            return $res;
        } else {
            echo "**ERROR NO EXISTE CCAP_CATEG PARA ID= " . $categ_id . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " ***PDOERROR EN CCAP_CATEG ID=" . $categ_id . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectCategId($codigo) {
    global $JanoControl;
    try {
        $sentencia = " select id from ccap_categ as t1  "
                . " where t1.codigo = :codigo ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo "**ERROR NO EXISTE CCAP_CATEG PARA CODIGO= " . $codigo . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " ***PDOERROR EN CCAP_CATEG CODIGO=" . $codigo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectPlaza($plaza_id) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id, t1.cias, t2.uf, t4.codigo as modalidad,"
                . " t3.pa, t5.codigo as catgen, t1.ficticia, t1.refuerzo, "
                . " t6.codigo as catfp, t1.cupequi, t1.plantilla, t1.f_amortiza, t1.colaboradora, t1.f_creacion, t7.codigo as edificio, t1.observaciones,t1.horNormal "
                . " ,t8.codigo as ceco, t1.turno"
                . " from ccap_plazas as t1 "
                . " inner join ccap_uf as t2 on t2.id = t1.uf_id "
                . " inner join ccap_pa as t3 on t3.id = t1.pa_id "
                . " inner join ccap_modalidad as t4 on t4.id = t1.modalidad_id"
                . " inner join ccap_catgen as t5 on t5.id = t1.catgen_id"
                . " inner join ccap_catfp as t6 on t6.id = t1.catfp_id"
                . " inner join comun_edificio as t7 on t7.id = t2.edificio_id"
                . " left join ccap_cecos as t8 on t8.id = t1.ceco_id"
                . " where t1.id = :plaza_id ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":plaza_id" => $plaza_id);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " ***PDOERROR EN CCAP_PLAZA ID=" . $plaza_id . " " . $ex->getMessage() . "\n";
        return null;
    }
} 

function conexionEdificio($codigo, $tipo) {
    $BasesDatos = selectBaseDatosEdificio($tipo, $codigo);
    if ($BasesDatos) {
        $datosConexion["maquina"] = $BasesDatos["maquina"];
        $datosConexion["puerto"] = $BasesDatos["puerto"];
        $datosConexion["servidor"] = $BasesDatos["servidor"];
        $datosConexion["esquema"] = $BasesDatos["esquema"];
        $datosConexion["usuario"] = $BasesDatos["usuario"];
        $datosConexion["password"] = $BasesDatos["password"];

        $conexion = conexionPDO($datosConexion);
        return $conexion;
    } else {
        return null;
    }
}

function selectPlazabyCias($cias) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id, t1.cias, t2.uf, t4.codigo as modalidad,"
                . " t3.pa, t5.codigo as catgen, t1.ficticia, t1.refuerzo, "
                . " t6.codigo as catfp, t1.cupequi, t1.plantilla, t1.f_amortiza, t1.colaboradora, t1.f_creacion, t7.codigo as edificio, t1.observaciones,t1.horNormal "
                . " ,t8.codigo as ceco, t1.turno"
                . " from ccap_plazas as t1 "
                . " inner join ccap_uf as t2 on t2.id = t1.uf_id "
                . " inner join ccap_pa as t3 on t3.id = t1.pa_id "
                . " inner join ccap_modalidad as t4 on t4.id = t1.modalidad_id"
                . " inner join ccap_catgen as t5 on t5.id = t1.catgen_id"
                . " inner join ccap_catfp as t6 on t6.id = t1.catfp_id"
                . " inner join comun_edificio as t7 on t7.id = t2.edificio_id"
                . " left join ccap_cecos as t8 on t8.id = t1.ceco_id"
                . " where t1.cias = :cias";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":cias" => $cias);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " ***PDOERROR EN CCAP_PLAZA CIAS=" . $cias . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectEqCateg($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_categ "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_CATEG CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN EQ_CATEG CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectEqCategById($id) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id as id, t1.codigo_loc as codigo_loc , t3.codigo as codigo_uni "
                . ", t1.edificio_id, t2.codigo as edificio "
                . ", t1.categ_id as categ_id "
                . " from ccap_eq_categ as t1"
                . " inner join ccap_categ as t3 on t3.id = t1.categ_id "
                . " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
                . " where t1.id = :id ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":id" => $id);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            echo "**ERROR NO EXISTE CCAP_EQ_CATEG ID = " . $id . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN CCAP_EQ_CATEG ID = " . $id . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectEqCentro($codigo, $edificio, $vista) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_centros  "
                . " where edificio = :edificio and codigo_uni = :codigo and vista = :vista";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo,
            ":vista" => $vista);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_CENTROS DE CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "VISTA= " . $vista . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_CENTROS CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectEqCatGen($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_catgen "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_CATGEN DE CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_CATEN CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage();
        return null;
    }
}

function selectEqCatFp($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_catfp "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_CATFP DE CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_CATFP CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage();
        return null;
    }
}

function selectEqTurno($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_turno "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_TURNO DE CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_TURNO CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage();
        return null;
    }
}

function selectEqCatAnexo($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_catanexo "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_CATANEXO CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_CATANEXO CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage();
        return null;
    }
}

function selectEqGrupoCot($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_grupcot "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_GRUPCOT DE CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_GRUPCOT CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage();
        return null;
    }
}

/*
  function selectEqEpiAcc($codigo,$edificio) {
  try {
  $sentencia = " select codigo_loc from eq_epiacc "
  ." where edificio = :edificio and codigo_uni = :codigo ";
  $query = $JanoInte->prepare($sentencia);
  $params = array(":edificio" => $edificio ,
  ":codigo" => $codigo);
  $query->execute($params);
  $res = $query->fetch(PDO::FETCH_ASSOC);
  if ( $res ) {
  return $res;
  } else {
  echo " ERROR NO EXISTE eq_epiacc DE CODIGO_UNI = ". $codigo ." EDIFICIO = ". $edificio."\n";
  return null;
  }
  } catch (PDOException $ex) {
  echo " PDOERROR EN eq_epiacc CODIGO_UNI = ". $codigo ." EDIFICIO = ". $edificio;
  return null;
  }
  }
 */

function selectEqGrupoCobro($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_grc "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_GRC DE CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_GRC CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage();
        return null;
    }
}

function selectEqGrupoProf($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_grupoprof "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_GRUPOPROF DE CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_GRUPOPROF CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage();
        return null;
    }
}

function selectEqOcupacion($codigo, $edificio) {
    global $JanoInte;
    try {
        $sentencia = " select codigo_loc from eq_ocupacion "
                . " where edificio = :edificio and codigo_uni = :codigo ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res['CODIGO_LOC'];
        } else {
            echo "**ERROR NO EXISTE EQ_OCUPACION DE CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN EQ_OCUPACION CODIGO_UNI = " . $codigo . " EDIFICIO = " . $edificio . " " . $ex->getMessage();
        return null;
    }
}
