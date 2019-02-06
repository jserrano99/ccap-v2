<?php

include_once __DIR__ . '/../funcionesDAO.php';

/**
 * @param $fecha
 * @return array|null
 */
function selectMovialtaByCias($fecha)
{
	global $JanoUnif, $cias;
	try {
		$sentencia = " select t3.cip, t3.dni, t3.ape12, t3.nombre, t2.codigo, t2.falta, t2.fbaja, t2.cip,"
		." t4.descrip as causa_alta, t5.descrip as causa_baja "
			. " from movialta as t2 "
			. " inner join trab as t3 on t3.cip = t2.cip "
			. " inner join altas as t4 on t4.codigo = t2.altas"
			. " left join bajas as t5 on t5.codigo = t2.baja"
			. " where t2.cias = :cias and (t2.fbaja is null or t2.fbaja > :fecha)";
		$query = $JanoUnif->prepare($sentencia);
		$params = [":cias" => $cias, ":fecha" => $fecha];
		$query->execute($params);
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		return $res;

	} catch (PDOException $ex) {
		return null;
	}
}

/**
 *
 */
function main()
{
	global $JanoControl, $cias, $fecha;
	$Plaza = selectPlazabyCias($cias);
	$MovialtaAll = selectMovialtaByCias($fecha);

	try {
		$sql = "delete from ccap_temp_altas where 1";
		$query = $JanoControl->prepare($sql);
		$query->execute();
	}catch (PDOException $ex) {
		echo "PDOERROR en delete ccap_temp_altas" .$ex->getMessage()." \n";
		exit(1);
	}

	if ($MovialtaAll != null) {
		foreach ($MovialtaAll as $Movialta) {
			try {
				$ausencia= selectSitAdmByAlta($Movialta["CODIGO"]);
				if (!$ausencia) {
					$ausencia["id"] = null;
					$ausencia["fini"] = null;
					$ausencia["ffin"] = null;
				}

				$sql = 'insert into ccap_temp_altas (cip, dni,f_alta, f_baja, nombre, plaza_id, causa_alta, causa_baja,'
					. ' ausencia_id, fini, ffin ) values '
					. '(:cip, :dni, :f_alta, :f_baja, :nombre, :plaza_id, :causa_alta, :causa_baja, '
					. ' :ausencia_id, :fini, :ffin) ';
				$query = $JanoControl->prepare($sql);
				$params = [':cip' => $Movialta["CIP"],
					':dni' => $Movialta["DNI"],
					':f_alta' => $Movialta["FALTA"],
					':f_baja' => $Movialta["FBAJA"],
					':nombre' => trim($Movialta["APE12"]) . ', ' . trim($Movialta["NOMBRE"]),
					":plaza_id" => $Plaza["id"],
					":causa_alta" => $Movialta["CAUSA_ALTA"],
					":causa_baja" => $Movialta["CAUSA_BAJA"],
					":ausencia_id" => $ausencia["id"],
					":fini" => $ausencia["fini"],
					":ffin" => $ausencia["ffin"]];
				$res = $query->execute($params);

				if ($res == 0) {
					echo "error en insert";
				}
				echo " insert " . $Movialta["CIP"] . $Movialta["APE12"] . ', ' . $Movialta["NOMBRE"] . "\n";
			} catch (PDOException $ex) {
				echo "ERROR PDO " . $ex->getMessage() . "\n";
			}
		}
	}
	return;
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$JanoControl = jano_ctrl();

if (!$JanoControl) {
	exit(1);
}

$modo = $argv[1];
$cias = $argv[2];
$fecha = $argv[3];

if ($modo == 'REAL') {
	$tipobd = 2;
	$JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
} else {
	$tipobd = 1;
	$JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
}
main();
exit;
