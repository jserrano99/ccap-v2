<?php

include_once __DIR__ . '/../funcionesDAO.php';
/**
 * @param $conexion
 * @param $cias
 * @return bool
 */
function deleteCecoCias($conexion, $cias)
{
	try {
		$sentencia = "delete from cecocias where cias =  :cias";
		$query = $conexion->prepare($sentencia);
		$params = [":cias" => $cias];
		$res =$query->execute($params);
		if ($res) {
			return true;
		} else {
			return false;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR SELECT CECOCIAS CIAS= (" . $cias . ") ERROR= " . $ex->getMessage() . " \n";
		return false;
	}
}

function main()
{
global $cias, $JanoUnif,$JanoControl,$tipobd;

	deleteCecoCias($JanoUnif,$cias);

	deletePlaza($JanoUnif,$cias);

	$query = " select * from comun_edificio where area = 'S' ";
	$query = $JanoControl->prepare($query);
	$query->execute();
	$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

	foreach ($EdificioAll as $Edificio) {
		$conexion = conexionEdificio($Edificio["codigo"], $tipobd);
		deleteCecoCias($conexion,$cias);
		deletePlaza($conexion,$cias);
	}

	return true;
}

/**
 * @param $conexion
 * @param $cias
 * @return bool
 */
function deletePlaza($conexion, $cias)
{
	global $gblError;
	try {
		$sentencia = "delete from plazas where cias = :cias ";
		$query = $conexion->prepare($sentencia);

		$params = [":cias" => $cias];
		$ins = $query->execute($params);
		echo "==> PLAZA CIAS= (" . $cias. ") ELIMINADA EN LA BASE DE DATOS \n";
		return true;

	} catch (PDOException $ex) {
		echo "***PDOERROR EN DELETE PLAZAS CIAS= (" . $cias . ") \n" . $ex->getMessage() . "\n";
		$gblError = 1;
		return false;
	}
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
$JanoControl = jano_ctrl();

if (!$JanoControl) {
	exit(1);
}

$modo = $argv[1];
$cias = $argv[2];
$gblError = 0;

if ($modo == 'REAL') {
	echo " ENTORNO : PRODUCCIÓN \n";
	$tipobd = 2;
	$JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
} else {
	echo " ENTORNO : VALIDACIÓN \n";
	$tipobd = 1;
	$JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
}


echo " ==> PLAZA A ELIMINAR : CIAS= (.". $cias.') \N';

main();


echo "  +++++++++++ TERMINA PROCESO DELETE PLAZA +++++++++++++ \n";
exit($gblError);