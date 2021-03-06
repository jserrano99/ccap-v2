<?php

include_once __DIR__ . '/../../vendor/autoload.php';

/**
 * @param $id
 * @return array|null
 */
function selectUnidadOrganizativaById($id) {
	global $JanoControl;
	try {
		$sentencia = "select t1.id, t1.codigo, t1.descripcion, t1.tipo_unidad_id "
			." , t2.descripcion as tipo_unidad, t1.dependencia_id, t3.descripcion as dependencia"
			." from ccap_unidad_organizativa as t1 "
			." left join ccap_tipo_unidad as t2 on t2.id = t1.tipo_unidad_id"
			." left join ccap_unidad_organizativa as t3 on t3.id = t1.dependencia_id"
			." where t1.id = :id";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);

		return $res;

	} catch (PDOException $ex) {
		return null;
	}
}
/**
 * @param $alta
 * @return array|null
 */
function selectSitAdmByAlta($alta) {
	global $JanoUnif,$JanoControl;
	try {
		$sentencia = " select t1.tipo, t1.fini, t1.ffin from moviausen as t1  "
			." inner join ausencias as t2  on t1.tipo = t2.codigo "
			." where t1.alta = :alta "
			. "  and t2.csituadm = 'S'" ;
		$query = $JanoUnif->prepare($sentencia);
		$params =  [":alta" => $alta ];
		$query->execute($params);
		$ausencias = $query->fetchAll(PDO::FETCH_ASSOC);

		$sentencia = " select id from gums_ausencias where codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $ausencias[0]["TIPO"]];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			$return["id"] = $res["id"];
			$return["fini"] = $ausencias[0]['FINI'];
			$return["ffin"] = $ausencias[0]['FFIN'];
		} else {
			return null;
		}
		return  $return;

	} catch (PDOException $ex){
		return null;
	}
}

/**
 * @return array|null
 */
function selectEdificioAll()
{
	global $JanoControl;
	try {
		$query = " select * from comun_edificio where area = 'S' ";
		$query = $JanoControl->prepare($query);
		$query->execute();
		$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);
		return $EdificioAll;
	} catch (PDOException $ex) {
		echo "*** PDOERROR EN SELECT COMUN_EDIFICIO " . $ex->getMessage() . "\n";
		return null;
	}
}

/* * ****************************************
 * CONEXIÓN Y DESCONXESIÓN A BASE DE DATOS *
 * ***************************************** */

/**
 * @global \PDO $JanoControl
 * @param int   $id
 * @return array
 */
function selectAltasById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.*, t2.codigo as movipat,t3.codigo as modocupa, t4.codigo as modopago from gums_altas as t1 "
			. " right join gums_movipat as t2 on t2.id = t1.movipat_id "
			. " right join gums_modocupa as t3 on t3.id = t1.modocupa_id"
			. " right join gums_modopago as t4 on t4.id = t1.modopago_id"
			. " where t1.id = :id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE GUMS_ALTAS PARA ID= " . $id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo " ***PDOERROR EN GUMS_ALTAS PARA ID=" . $id . " ERROR=" . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO $JanoControl
 * @param int   $id
 * @return array
 */
function selectCatFpById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select * from gums_catfp where "
			. " id = :id";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE CATFP ID=" . $id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN CATFP  ID=" . $id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global       $JanoControl
 * @param string $catfp
 * @return \MaestrosBundle\Entity\CatFp;
 */
function selectCatFp($catfp)
{
	global $JanoControl;
	if ($catfp == null)
		return null;
	try {
		$sentencia = " select * from gums_catfp where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $catfp];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE CATFP=" . $catfp . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN CATFP=" . $catfp . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO  $JanoControl
 * @param string $modalidad
 * @return int
 */
function selectModalidad($modalidad)
{
	global $JanoControl;
	try {
		$sentencia = " select id from gums_moa where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => trim($modalidad)];
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

/**
 * @param array $datosConexion
 * @return \PDO
 */
function conexionPDO($datosConexion)
{
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
	} catch (PDOException $e) {
		echo "**PDOERROR EN CONEXION: " . $cadena . " MENSAJE ERROR: " . $e->getMessage() . " \n";
		return null;
	}
	return $conexion;
}

/**
 * ******************************************************************************
 * Función jano_ctrol() establece la conexión a la base de datos mysql de control
 * *****************************************************************************
 */

/**
 * @return \PDO
 */
function jano_ctrl()
{

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

		//echo "CONEXIÓN A CCAP CORRECTA= " . $cadena . " ** \n";
	} catch (PDOException $e) {
		$error = $e->getMessage();
		echo $error . " \n";
		return null;
	}
	return $conn;
}

/**
 * @global \PDO  $JanoControl
 * @param string $tipo
 * @return array
 */
function selectBaseDatosAreas($tipo)
{
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
		$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $ex) {
		$error = $ex->getMessage();
		echo $error . " \n";
		return null;
	}
	return $resultSet;
}

/**
 * @global \PDO  $JanoControl
 * @param string $tipo
 * @param string $areas
 * @return array|null
 */
function selectBaseDatos($tipo, $areas)
{
	global $JanoControl;
	try {
		$sql = "select id, alias, maquina, puerto, servidor, esquema, usuario, password"
			. " from comun_base_datos "
			. " where tipo_bd_id = :tipo and activa = 'S' and areas = :areas ";

		$query = $JanoControl->prepare($sql);
		$params = [":tipo" => $tipo,
			":areas" => $areas];
		$query->execute($params);
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

/**
 * @global \PDO  $JanoControl
 * @param string $tipo
 * @param string $edificio
 * @return array
 */
function selectBaseDatosEdificio($tipo, $edificio)
{
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
		if ($resultSet) {
			return $resultSet;
		} else {
			echo "**ERROR NO EXISTE BASE DE DATOS PARA TIPO= " . $tipo . " EDIFICIO= " . $edificio . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN comun_base_datos PARA TIPO= " . $tipo . " EDIFICIO= " . $edificio . "\n" . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global       $JanoControl
 * @param string $edificio
 * @return int
 */
function selectEdificioId($edificio)
{
	global $JanoControl;
	try {
		$sql = "select id from comun_edificio "
			. " where codigo = :edificio ";
		$query = $JanoControl->prepare($sql);
		$params = [":edificio" => $edificio];
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

/**
 * @global \PDO   $JanoControl
 * @param integer $ceco_id
 * @return \CostesBundle\Entity\Ceco;
 */
function selectCeco($ceco_id)
{
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

/**
 * @global \PDO  $JanoControl
 * @param string $codigo
 * @return array
 */
function SelectCecobyCodigo($codigo)
{
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

/**
 * @global \PDO  $JanoInteid
 * @param string $codigo_uni
 * @return boolean
 */
function insertEqCeco($codigo_uni)
{
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

/**
 * @global \PDO  $JanoInteid
 * @param string $codigo_uni
 * @return boolean
 */
function deleteEqCeco($codigo_uni)
{
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

/**
 * @global \PDO $JanoControl
 * @param int   $edificio
 * @return int
 */
function selectEdificio($edificio)
{
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
		echo "***Error en función selectEdificio, edificio=" . $edificio . " " . $ex->getMessage();
		return null;
	}
}

/**
 * @global \PDO  $JanoControl
 * @param string $da
 * @return int
 */
function selectDa($da)
{
	global $JanoControl;
	try {
		$query = $JanoControl->prepare("select id from comun_da where codigo = :da");
		$params = [":da" => $da];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return ($res["id"]);
		} else {
			echo "** ERROR NO EXISTE DIRECCIÓN ASISTENCIAL DA: " . $da . "\n";
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

/**
 * @global \PDO  $JanoUnif
 * @param string $codigo
 * @return array
 */
function selectCentro($codigo)
{
	global $JanoUnif;
	try {
		$query = $JanoUnif->prepare(" select  * from centros where codigo = :codigo");
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return ($res);
		} else {
			echo "**ERROR NO EXISTE CENTROS EN BD UNIFICADA CODIGO: " . $codigo . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR en función selectCentros centros codigo=" . $codigo . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO  $JanoControl
 * @param string $codigoUni
 * @return int
 */
function existeCentro($codigoUni)
{
	global $JanoControl;
	try {
		$query = $JanoControl->prepare("select id from ccap_uf where uf = :codigo_uni");
		$params = [":codigo_uni" => $codigoUni];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);

		if ($res) {
			return ($res["id"]);
		} else {
			echo "**ERROR NO EXISTE CCAP_UF CODIGO= " . $codigoUni . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo " Error en función existeCentro, ccap_uf uf=" . $codigoUni . " " . $ex->getMessage();
		return null;
	}
}

/**
 * @global \PDO  $JanoControl
 * @param string $catgen
 * @return array
 */
function selectCatGen($catgen)
{
	global $JanoControl;

	try {
		$sentencia = " select * from gums_catgen where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $catgen];
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

/**
 * @global \PDO  $JanoControl
 * @param string $catanexo
 * @return int
 */
function selectCatAnexo($catanexo)
{
	global $JanoControl;

	try {
		$sentencia = " select id from gums_catanexo where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $catanexo];
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

/**
 * @global \PDO  $JanoControl
 * @param string $codigo
 * @return int|null
 */
function selectEpiAcc($codigo)
{
	global $JanoControl;
	if ($codigo == null)
		return null;

	try {
		$sentencia = " select id from gums_epiacc where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res["id"];
		} else {
			echo "***ERROR NO EXISTE EPIACC=" . $codigo . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR PDO EN EPIACC=" . $codigo . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO  $JanoControl
 * @param string $codigo
 * @return int
 */
function selectTipoIlt($codigo)
{
	global $JanoControl;
	if ($codigo == null)
		return null;


	try {
		$sentencia = " select id from gums_tipo_ilt where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res["id"];
		} else {
			echo "***ERROR NO EXISTE GUMS-TIPO_ILT=" . $codigo . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR PDO EN GUMS_TIPO_ILT=" . $codigo . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO  $JanoControl
 * @param string $codigo
 * @return int
 */
function selectFco($codigo)
{
	global $JanoControl;
	if ($codigo == null)
		return null;

	try {
		$sentencia = " select id from gums_fco where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res["id"];
		} else {
			echo "***ERROR NO EXISTE GUMS_FCO=" . $codigo . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR PDO EN GUMS_FCO =" . $codigo . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $ocupacion
 * @return int|null
 */
function selectOcupacion($ocupacion)
{
	global $JanoControl;
	if ($ocupacion == null)
		return null;

	try {
		$sentencia = " select id from gums_ocupacion where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $ocupacion];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res["id"];
		} else {
			echo "**ERROR NO EXISTE OCUPACION =" . $ocupacion . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR PDO EN OCUPACION =" . $ocupacion . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param string $grupocot
 * @return integer|null
 */
function selectGrupoCot($grupocot)
{
	global $JanoControl;

	try {
		$sentencia = " select id from gums_grupocot where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $grupocot];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res["id"];
		} else {
			echo "**ERROR NO EXISTE GRUPO COTIZACIÓN (GRUPOCOT) CODIGO= " . $grupocot . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR PDO EN GRUPO COTIZACION (GRUPOCOT) CODIGO=" . $grupocot . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param string $grupoProf
 * @return int|null
 */
function selectGrupoProf($grupoProf)
{
	global $JanoControl;

	try {
		$sentencia = " select id from gums_grupoprof where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $grupoProf];
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

/**
 * @param string $grupoCobro
 * @return int|null
 */
function selectGrupoCobro($grupoCobro)
{
	global $JanoControl;

	try {
		$sentencia = " select id from gums_grc where "
			. " codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $grupoCobro];
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

/**
 * @param int $grupoCobro_id
 * @return array|null
 */
function selectGrupoCobroById($grupoCobro_id)
{
	global $JanoControl;

	try {
		$sentencia = " select t1.*, t4.codigo as grupocot, "
			. " t7.codigo as ocupacion, t8.codigo as epiacc "
			. " from gums_grc as t1  "
			. " right join gums_grupocot as t4 on t4.id = t1.grupocot_id"
			. " right join gums_ocupacion as t7 on t7.id = t1.ocupacion_id"
			. " right join gums_epiacc as t8 on t8.id = t1.epiacc_id"
			. " where t1.id = :id ";

		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $grupoCobro_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo " ERROR NO EXISTE GRUPOCOBRO ID= (" . $grupoCobro_id . ") \n";
			return null;
		}
	} catch (PDOException $ex) {
		echo " PDOERROR PDO EN GRUPOCOBRO ID =(" . $grupoCobro_id . ") " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $uf
 * @return array|null
 */

function selectUf($uf)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.*, t2.codigo as edificio, t3.codigo as da, t2.gerencia as gerencia "
			. " from ccap_uf as t1  "
			. " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
			. " inner join comun_da as t3 on t3.id = t1.da_id "
			. " where t1.uf = :uf ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":uf" => $uf];
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

/**
 * @param int $id
 * @return \CostesBundle\Entity\Uf|null
 */
function selectUfById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.*, t2.codigo as edificio, t3.codigo as da, t2.gerencia as gerencia  "
			. " from ccap_uf as t1  "
			. " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
			. " inner join comun_da as t3 on t3.id = t1.da_id "
			. " where t1.id = :id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);

		if ($res) {
			return $res;
		} else {
			return null;
		}
	} catch (PDOException $ex) {
		echo " ERROR PDO EN UF=" . $id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $codigo
 * @return mixed|null
 */
function selectUfByCodigo($codigo)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.*  from ccap_uf as t1 where t1.uf = :codigo ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);

		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE UF codigo=(" . $codigo . ") \n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR UF codigo=(" . $codigo . ") " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $codigo
 * @return mixed|null
 */
function selectPaByCodigo($codigo)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.*  from ccap_pa as t1 where t1.pa = :codigo ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);

		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE PA codigo=(" . $codigo . ") \n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR PA codigo=(" . $codigo . ") " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $pa
 * @return mixed|null
 */
function selectPa($pa)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.*, t2.codigo as edificio, t3.codigo as da, t2.gerencia as gerencia "
			. " from ccap_pa as t1  "
			. " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
			. " inner join comun_da as t3 on t3.id = t1.da_id "
			. " where t1.pa = :pa ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":pa" => $pa];
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

/**
 * @param int $id
 * @return array|null
 */
function selectPaById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.*, t2.codigo as edificio, t3.codigo as da, t2.gerencia as gerencia  "
			. " from ccap_pa as t1  "
			. " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
			. " inner join comun_da as t3 on t3.id = t1.da_id "
			. " where t1.id = :id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);

		if ($res) {
			return $res;
		} else {
			return null;
		}
	} catch (PDOException $ex) {
		echo " ***PDOERROR EN CCAP_PA PUNTO ASISTENCIAL ID=" . $id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $categ_id
 * @return array|null
 */
function selectCateg($categ_id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.*, t2.codigo as catgen, t3.codigo as catanexo, t4.codigo as grupocot ,t5.codigo as grupoprof"
			. ",t6.codigo as grupocobro, t7.codigo as ocupacion, t8.codigo as epiacc from gums_categ as t1  "
			. " right join gums_catgen as t2 on t2.id = t1.catgen_id"
			. " right join gums_catanexo as t3 on t3.id = t1.catanexo_id"
			. " right join gums_grupocot as t4 on t4.id = t1.grupocot_id"
			. " right join gums_grupoprof as t5 on t5.id = t1.grupoprof_id"
			. " right join gums_grc as t6 on t6.id = t1.grupocobro_id"
			. " right join gums_ocupacion as t7 on t7.id = t1.ocupacion_id"
			. " right join gums_epiacc as t8 on t8.id = t1.epiacc_id"
			. " where t1.id = :categ_id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":categ_id" => $categ_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE gums_categ PARA ID= " . $categ_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo " ***PDOERROR EN gums_categ ID=" . $categ_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param string $codigo
 * @return int|null
 */
function selectCategId($codigo)
{
	global $JanoControl;
	try {
		$sentencia = " select id from gums_categ as t1  where t1.codigo = :codigo ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res["id"];
		} else {
			echo "**ERROR NO EXISTE gums_categ PARA CODIGO= " . $codigo . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo " ***PDOERROR EN gums_categ CODIGO=" . $codigo . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $id
 * @return array|null
 */
function selectPlazaById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.id, t1.cias, t2.uf, t4.codigo as modalidad,"
			. " t3.pa, t5.codigo as catgen, t1.ficticia, t1.refuerzo, "
			. " t6.codigo as catfp, t1.cupequi, t1.plantilla, t1.f_amortiza, t1.colaboradora, t1.f_creacion, t7.codigo as edificio, t1.observaciones,t1.horNormal "
			. " ,t8.codigo as ceco, t2.edificio_id,t1.catgen_id, t1.catfp_id"
			. " ,t1.h1ini, t1.h1fin, t1.h2ini, t1.h2fin"
			. " ,t1.turno_id , t9.codigo as turno, t1.unidad_organizativa_id"
			. " from ccap_plazas as t1 "
			. " left join ccap_uf as t2 on t2.id = t1.uf_id "
			. " left join ccap_pa as t3 on t3.id = t1.pa_id "
			. " left join gums_moa as t4 on t4.id = t1.moa_id"
			. " left join gums_catgen as t5 on t5.id = t1.catgen_id"
			. " left join gums_catfp as t6 on t6.id = t1.catfp_id"
			. " left join comun_edificio as t7 on t7.id = t2.edificio_id"
			. " left join ccap_cecos as t8 on t8.id = t1.ceco_actual_id"
			. " left join gums_turno as t9 on t9.id = t1.turno_id"
			. " where t1.id = :id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			return null;
		}
	} catch (PDOException $ex) {
		echo " ***PDOERROR EN CCAP_PLAZA ID=" . $id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO $JanoControl
 * @param int   $id
 * @return array
 */
function selectAusenciaById($id)
{
	global $JanoControl;
	try {
		$sentencia = "select t1.*, t2.codigo as ocupacion, t3.codigo as epiacc, t4.codigo as tipo_ilt "
			. " , t5.codigo as fco , t6.codigo as movipat, t7.codigo as modocupa, t8.codigo as ocupacion_new  "
			. " from gums_ausencias as t1 "
			. " left join gums_ocupacion as t2 on t1.ocupacion_id = t2.id "
			. " left join gums_epiacc as t3 on t1.epiacc_id = t3.id "
			. " left join gums_tipo_ilt as t4 on t1.tipo_ilt_id = t4.id "
			. " left join gums_fco as t5 on t1.fco_id = t5.id "
			. " left join gums_movipat as t6 on t1.movipat_id = t6.id "
			. " left join gums_modocupa as t7 on t1.modocupa_id = t7.id "
			. " left join gums_ocupacion as t8 on t1.ocupacion_new_id = t8.id "
			. " where t1.id = :id";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		return $res;
	} catch (PDOException $ex) {
		echo "*** PDOERROR EN SELECT GUMS_AUSENCIA ID= (" . $id . ") ERROR= " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param string $codigo
 * @param string $tipo
 * @return \PDO|null
 */
function conexionEdificio($codigo, $tipo)
{
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

/**
 * @param string $cias
 * @return array|null
 */
function selectPlazabyCias($cias)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.id, t1.cias, t2.uf, t4.codigo as modalidad,"
			. " t3.pa, t5.codigo as catgen, t1.ficticia, t1.refuerzo, "
			. " t6.codigo as catfp, t1.cupequi, t1.plantilla, t1.f_amortiza, t1.colaboradora, t1.f_creacion, t7.codigo as edificio, t1.observaciones,t1.horNormal "
			. " ,t8.codigo as ceco, t1.turno"
			. " ,t1.h1ini, t1.h1fin, t1.h2ini, t1.h2fin"
			. " from ccap_plazas as t1 "
			. " left join ccap_uf as t2 on t2.id = t1.uf_id "
			. " left join ccap_pa as t3 on t3.id = t1.pa_id "
			. " left join gums_moa as t4 on t4.id = t1.moa_id"
			. " left join gums_catgen as t5 on t5.id = t1.catgen_id"
			. " left join gums_catfp as t6 on t6.id = t1.catfp_id"
			. " left join comun_edificio as t7 on t7.id = t2.edificio_id"
			. " left join ccap_cecos as t8 on t8.id = t1.ceco_actual_id"
			. " where t1.cias = :cias";
		$query = $JanoControl->prepare($sentencia);
		$params = [":cias" => $cias];
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

/**
 * @param int $altas_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqAltas($altas_id, $edificio_id)
{
	global $JanoControl;
	try {
		$sentencia = " select codigo_loc from gums_eq_altas "
			. " where edificio_id = :edificio_id and altas_id = :altas_id and enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":altas_id" => $altas_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "NO EXISTE EQUIVALENCIA(EQ_ALTAS) AUSENCIA_ID = " . $altas_id . " EDIFICIO_ID = " . $edificio_id . " NO SE TRATA \n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN EQUIVALENCIA(EQ_ALTAS) AUSENCIA_ID = " . $altas_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $ausencia_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqAusencia($ausencia_id, $edificio_id)
{
	global $JanoControl;
	try {
		$sentencia = " select codigo_loc from gums_eq_ausencias "
			. " where edificio_id = :edificio_id and ausencia_id = :ausencia_id and enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":ausencia_id" => $ausencia_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "NO EXISTE EQUIVALENCIA(EQ_AUSENCIA) AUSENCIA_ID = " . $ausencia_id . " EDIFICIO_ID = " . $edificio_id . " NO SE TRATA \n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN EQUIVALENCIA(EQ_AUSENCIA) AUSENCIA_ID = " . $ausencia_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $categ_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqCateg($categ_id, $edificio_id)
{
	global $JanoControl;
	try {
		$sentencia = " select codigo_loc from gums_eq_categ "
			. " where edificio_id = :edificio_id and categ_id = :categ_id and enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":categ_id" => $categ_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "NO EXISTE EQUIVALENCIA(EQ_CATEG) CATEG_ID = " . $categ_id . " EDIFICIO_ID = " . $edificio_id . " NO SE TRATA \n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN EQUIVALENCIA(EQ_CATEG) CATEG_ID = " . $categ_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO $JanoControl
 * @param int   $id
 * @return array
 */
function selectEqAltasById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.id as id, t1.codigo_loc as codigo_loc , t3.codigo as codigo_uni "
			. ", t1.edificio_id, t2.codigo as edificio "
			. ", t1.altas_id as altas_id "
			. " from gums_eq_altas as t1"
			. " inner join gums_altas as t3 on t3.id = t1.altas_id "
			. " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
			. " where t1.id = :id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE GUMS_EQ_ALTAS EQAUSENCIA_ID = " . $id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN GUMS_EQ_ALTAS EQAUSENCIA_ID = " . $id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO $JanoControl
 * @param int   $id
 * @return array
 */
function selectEqAusenciaById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.id as id, t1.codigo_loc as codigo_loc , t3.codigo as codigo_uni "
			. ", t1.edificio_id, t2.codigo as edificio "
			. ", t1.ausencia_id as ausencia_id "
			. " from gums_eq_ausencias as t1"
			. " inner join gums_ausencias as t3 on t3.id = t1.ausencia_id "
			. " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
			. " where t1.id = :id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE GUMS_EQ_AUSENCIAS EQAUSENCIA_ID = " . $id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN GUMS_EQ_AUSENCIAS EQAUSENCIA_ID = " . $id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @global \PDO $JanoControl
 * @param int   $id
 * @return array
 */
function selectEqCategById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.id as id, t1.codigo_loc as codigo_loc , t3.codigo as codigo_uni "
			. ", t1.edificio_id, t2.codigo as edificio "
			. ", t1.categ_id as categ_id "
			. " from gums_eq_categ as t1"
			. " inner join gums_categ as t3 on t3.id = t1.categ_id "
			. " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
			. " where t1.id = :id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE gums_eq_categ ID = " . $id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN gums_eq_categ ID = " . $id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param string $codigo
 * @param string $edificio
 * @param string $vista
 * @return string|null
 */
function selectEqCentro($codigo, $edificio, $vista)
{
	global $JanoInte;
	try {
		$sentencia = " select codigo_loc from eq_centros  "
			. " where edificio = :edificio and codigo_uni = :codigo and vista = :vista and enuso = 'S'";
		$query = $JanoInte->prepare($sentencia);
		$params = [":edificio" => $edificio,
			":codigo" => $codigo,
			":vista" => $vista];
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

/**
 * @param int $catgen_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqCatGen($catgen_id, $edificio_id)
{
	global $JanoControl;
	try {
		$sentencia = " select codigo_loc from gums_eq_catgen "
			. " where edificio_id = :edificio_id and catgen_id = :catgen_id and enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":catgen_id" => $catgen_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**NO EXISTE EQUIVALENCIA(EQ_CATGEN) PARA CATGEN_ID " . $catgen_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "****PDOERROR EN EQUIVALENCIA(EQ_CATGEN) PARA CATGEN_ID " . $catgen_id . " EDIFICIO_ID = " . $edificio_id . "\n" . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $catfp_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqCatFp($catfp_id, $edificio_id)
{
	global $JanoControl;
	if ($catfp_id == null)
		return null;

	try {
		$sentencia = " select codigo_loc from gums_eq_catfp "
			. " where edificio_id = :edificio_id and catfp_id = :catfp_id and enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":catfp_id" => $catfp_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(GUMS_EQ_CATFP) PARA CATFP_ID = " . $catfp_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(GUMS_EQ_CATFP) CATFP_ID = " . $catfp_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $turno_id
 * @param int $edificio_id
 * @return null
 */
function selectEqTurno($turno_id, $edificio_id)
{
	global $JanoControl;
	if ($turno_id == null)
		return null;

	try {
		$sentencia = " select codigo_loc from gums_eq_turno "
			. " where edificio_id = :edificio_id and turno_id = :turno_id and enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":turno_id" => $turno_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(GUMS_EQ_TURNO) PARA TURNO_ID = " . $turno_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(GUMS_EQ_TURNO) TURNO_ID = " . $turno_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $catanexo_id
 * @param $edificio_id
 * @return null
 */
function selectEqCatAnexo($catanexo_id, $edificio_id)
{
	global $JanoControl;
	try {
		$sentencia = " select codigo_loc from gums_eq_catanexo "
			. " where edificio_id = :edificio_id and catanexo_id = :catanexo_id  and enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":catanexo_id" => $catanexo_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_CATANEXO) CATANEXO_ID = " . $catanexo_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_CATANEXO) CATANEXO_ID = " . $catanexo_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $grupocot_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqGrupoCot($grupocot_id, $edificio_id)
{
	global $JanoControl;
	try {
		$sentencia = " select codigo_loc from gums_eq_grupocot "
			. " where edificio_id = :edificio_id and  grupocot_id = :grupocot_id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":grupocot_id" => $grupocot_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_GRUPOCOT) PARA GRUPOCOT_ID = " . $grupocot_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_GRUPOCOT) PARA GRUPOCOT_ID = " . $grupocot_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $grupocobro_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqGrupoCobro($grupocobro_id, $edificio_id)
{
	global $JanoControl;

	try {
		$sentencia = " select codigo_loc from gums_eq_grc "
			. " where edificio_id = :edificio_id and grupocobro_id = :grupocobro_id and enuso = 'S'";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":grupocobro_id" => $grupocobro_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_GRC) PARA GRUPOCOBRO_ID = " . $grupocobro_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_GRC) PARA GRUPOCOBRO_ID = " . $grupocobro_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $id
 * @return mixed|null
 */
function selectEqGrupoCobroById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select t1.id as id, t1.codigo_loc as codigo_loc , t3.codigo as codigo_uni "
			. ", t1.edificio_id, t2.codigo as edificio "
			. ", t1.grupoCobro_id as grupoCobro_id "
			. " from gums_eq_grc as t1"
			. " inner join gums_grc as t3 on t3.id = t1.grupoCobro_id "
			. " inner join comun_edificio as t2 on t2.id = t1.edificio_id "
			. " where t1.id = :id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE gums_eq_grc ID = " . $id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN gums_eq_grc ID = " . $id . " " . $ex->getMessage() . "\n";
		return null;
	}
}


/**
 * @param $grupoprof_id
 * @param $edificio_id
 * @return null
 */
function selectEqGrupoProf($grupoprof_id, $edificio_id)
{
	global $JanoControl;
	try {
		$sentencia = " select codigo_loc from gums_eq_grupoprof "
			. " where edificio_id = :edificio_id and grupoprof_id = :grupoprof_id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":grupoprof_id" => $grupoprof_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_GRUPOPROF) PARA GRUPOPROF_ID= " . $grupoprof_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_GRUPOPROF) PARA GRUPOPROF_ID= " . $grupoprof_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $ocupacion_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqOcupacion($ocupacion_id, $edificio_id)
{
	global $JanoControl;
	if ($ocupacion_id == null)
		return null;
	try {
		$sentencia = " select codigo_loc from gums_eq_ocupacion "
			. " where edificio_id = :edificio_id and ocupacion_id = :ocupacion_id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":ocupacion_id" => $ocupacion_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_OCUPACION) PARA OCUPACION_ID= " . $ocupacion_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_OCUPACION) PARA OCUPACION_ID= " . $ocupacion_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $tipo_ilt_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqTipoIlt($tipo_ilt_id, $edificio_id)
{
	global $JanoControl;
	if ($tipo_ilt_id == null)
		return null;
	try {
		$sentencia = " select codigo_loc from gums_eq_tipo_ilt "
			. " where edificio_id = :edificio_id and tipo_ilt_id = :tipo_ilt_id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":tipo_ilt_id" => $tipo_ilt_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_TIPO_ILT) PARA TIPO_ILT_ID= " . $tipo_ilt_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_TIPO_ILT) PARA TIPO_ILT_ID= " . $tipo_ilt_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $epiacc_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqEpiAcc($epiacc_id, $edificio_id)
{
	global $JanoControl;
	if ($epiacc_id == null)
		return null;
	try {
		$sentencia = " select codigo_loc from gums_eq_epiacc "
			. " where edificio_id = :edificio_id and epiacc_id = :epiacc_id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":epiacc_id" => $epiacc_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_EPIACC) PARA EPIACC_ID= " . $epiacc_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_EPIACC) PARA EPIACC_ID= " . $epiacc_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param string $codigo
 * @return int|null
 */
function selectMoviPat($codigo)
{
	global $JanoControl;
	if ($codigo == null)
		return null;

	try {
		$sentencia = " select id from gums_movipat where codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		return $res["id"];
	} catch (PDOException $ex) {
		echo "** ERROR EN SELECT GUMS_MOVIPAT CODIGO= " . $codigo . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param string $codigo
 * @return int|null
 */
function selectModOcupa($codigo)
{
	global $JanoControl;
	if ($codigo == null)
		return null;
	try {
		$sentencia = " select id from gums_modocupa where codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		return $res["id"];
	} catch (PDOException $ex) {
		echo "**ERROR EN SELECT GUMS_MODOCUPA CODIGO= " . $codigo . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param string $codigo
 * @return int|null
 */
function selectModoPago($codigo)
{
	global $JanoControl;
	try {
		$sentencia = " select id from gums_modopago where codigo = :codigo";
		$query = $JanoControl->prepare($sentencia);
		$params = [":codigo" => $codigo];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		return $res["id"];
	} catch (PDOException $ex) {
		echo "** ERROR EN SELECT GUMS_MODOPAGO CODIGO= " . $codigo . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $modocupa_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqModOcupa($modocupa_id, $edificio_id)
{
	global $JanoControl;
	if ($modocupa_id == null)
		return null;
	try {
		$sentencia = " select codigo_loc from gums_eq_modocupa "
			. " where edificio_id = :edificio_id and modocupa_id = :modocupa_id";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":modocupa_id" => $modocupa_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_MODOCUPA) PARA MODOCUPA_ID= " . $modocupa_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_MODOCUPA) PARA MODOCUPA_ID= " . $modocupa_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $fco_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqFco($fco_id, $edificio_id)
{
	global $JanoControl;
	if ($fco_id == null)
		return null;
	try {
		$sentencia = " select codigo_loc from gums_eq_fco "
			. " where edificio_id = :edificio_id and fco_id = :fco_id";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":fco_id" => $fco_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_FCO) PARA FCO_ID= " . $fco_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN EQUIVALENCIA(EQ_FCO) PARA FCO_ID= " . $fco_id . " EDIFICIO_ID = " . $edificio_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $movipat_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqMoviPat($movipat_id, $edificio_id)
{
	global $JanoControl;
	if ($movipat_id == null)
		return null;
	try {
		$sentencia = " select codigo_loc from gums_eq_movipat "
			. " where edificio_id = :edificio_id and movipat_id = :movipat_id and enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":movipat_id" => $movipat_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_MOVIPAT) PARA MOVIPAT_ID= " . $movipat_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "****PDOERROR EN SELECT EQUIVALENCIA(EQ_MOVIPAT) PARA MOVIPAT_ID= " . $movipat_id . " ERROR:" . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $modopago_id
 * @param int $edificio_id
 * @return string|null
 */
function selectEqModoPago($modopago_id, $edificio_id)
{
	global $JanoControl;
	try {
		$sentencia = " select codigo_loc from gums_eq_modopago "
			. " where edificio_id = :edificio_id and modopago_id = :modopago_id ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":edificio_id" => $edificio_id,
			":modopago_id" => $modopago_id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res['codigo_loc'];
		} else {
			echo "**ERROR NO EXISTE EQUIVALENCIA(EQ_MODOPAGO) PARA MODOPAGO_ID= " . $modopago_id . " EDIFICIO_ID = " . $edificio_id . "\n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN SELECT EQUIVALENCIA(EQ_MODOPAGO) PARA MODOPAGO_ID= " . $modopago_id . " " . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param int $id
 * @return array|null
 */
function selectCecoCiasById($id)
{
	global $JanoControl;
	try {
		$sentencia = " select * from ccap_cecocias where id = :id";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $id];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return $res;
		} else {
			echo "**ERROR NO EXISTE CCAP_CECOCIAS PARA ID= (" . $id . ") \n";
			return null;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR CCAP_CECOCIAS PARA ID= (" . $id . ") ERROR=" . $ex->getMessage() . "\n";
		return null;
	}
}
