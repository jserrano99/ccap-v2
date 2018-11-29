<?php

include_once __DIR__ . '../../funcionesDAO.php';

/**
 * @return null
 */
function updateEqGrupoCobroControl($EqGrupoCobro)
{
	global $GrupoCobro, $JanoControl;
	try {
		var_dump($EqGrupoCobro);

		$sql = " update gums_eq_grc set "
			. " enuso = :enuso"
			. " where id = :id ";
		$query = $JanoControl->prepare($sql);
		$params = [":id" => $EqGrupoCobro["id"],
			":enuso" => $EqGrupoCobro["enuso"]];
		$res = $query->execute($params);
		if ($res == 0) {
			echo "**ERROR EN UPDATE GUMS_EQ_GRC CODIGO_LOC=(" . $EqGrupoCobro["codigo_loc"] . ") EDIFICIO=(" . $EqGrupoCobro["edificio"] . ") Uso= (" . $EqGrupoCobro["enuso"] . ") \n";
			return null;
		}
		echo "-->UPDATE GUMS_EQ_GRC CODIGO_LOC =(" . $EqGrupoCobro["codigo_loc"] . ") "
			. " EDIFICIO =(" . $EqGrupoCobro["edificio"] . ") "
			. " Uso= (" . $EqGrupoCobro["enuso"] . ") \n";
	} catch (PDOException $ex) {
		echo "***PDOERROR EN UPDATE GUMS_EQ_GRC CODIGO =(" . $GrupoCobro["codigo"] . ") EDIFICIO =(" . $EqGrupoCobro["edificio"] . ") \n"
			. $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $GrupoCobro
 * @param $conexion
 * @param $codigo
 * @param $edificio
 * @return bool|null
 */
function updateGrupoCobro($GrupoCobro, $conexion, $codigo, $edificio)
{
	try {
		$sentencia = " update grc set enuso = :enuso where codigo = :codigo";
		$query = $conexion->prepare($sentencia);

		$params = [":enuso" => $GrupoCobro["enuso"],
			":codigo" => $codigo];
		$update = $query->execute($params);
		if ($update == 0) {
			echo " ** ERROR EN UPDATE GRUPOCOBRO CODIGO =(" . $codigo . ") EN EDIFICIO = (" . $edificio . ") \n";
			return null;
		}
		echo " MODIFICADO GRUPOCOBRO CODIGO =(" . $codigo . ") EN EDIFICIO = (" . $edificio . ") EN USO = (" . $GrupoCobro["enuso"] . ") \n";
		return true;

	} catch (PDOException $ex) {
		echo "***PDOERROR EN UPDATE GRUPO COBRO CODIGO =(" . $codigo . ") " . $ex->getMessage() . "\n";
		exit(1);
	}
}

function insertGrupoCobro($GrupoCobro, $conexion, $edificio)
{
	try {
		$sentencia = "insert into grc "
			. "( codigo, grupcot,	epiacc,	descrip, grupoprof,	nivel, horas, grupob, apd, refuerzo, persinsueldo,"
			. "cobra_nomina, cotiza_ss,	prodtsi, liq_extra,	liq_vacaciones,	retribucion, tipo, minimo_fijo,"
			. "minimo_interino, minimo_eventual, minimo_ev,	horas_anuales, horas_sabados, media_vacaciones, "
			. "enuso, excluir_plpage, ocupacion, grcrpt_codigo, grcrpt_descripcion,	grcrptid, personal,"
			. "peac, excluir_extra, asumedia, extra_por_horas, asumedia_periodo ) values "
			. "( :codigo, :grupcot,	:epiacc, :descrip, :grupoprof, :nivel, :horas, :grupob, :apd, :refuerzo, :persinsueldo,"
			. ":cobra_nomina, :cotiza_ss, :prodtsi, :liq_extra,	:liq_vacaciones, :retribucion, :tipo, :minimo_fijo,"
			. ":minimo_interino, :minimo_eventual, :minimo_ev,	:horas_anuales, horas_sabados, :media_vacaciones,"
			. ":enuso, :excluir_plpage, :ocupacion, :grcrpt_codigo, :grcrpt_descripcion, :grcrptid, :personal,"
			. ":peac, :excluir_extra, :asumedia, :extra_por_horas, :asumedia_periodo )";
		$insert = $conexion->prepare($sentencia);
		$params = [":codigo" => $GrupoCobro["codigo"],
			":grupcot" => $GrupoCobro["grupcot"],
			":epiacc" => $GrupoCobro["epiacc"],
			":descrip" => $GrupoCobro["descripcion"],
			":grupoprof" => $GrupoCobro["grupoprof"],
			":nivel" => $GrupoCobro["nivel"],
			":horas" => $GrupoCobro["horas"],
			":grupob" => $GrupoCobro["grupob"],
			":apd" => $GrupoCobro["apd"],
			":refuerzo" => $GrupoCobro["refuerzo"],
			":persinsueldo" => $GrupoCobro["persinsueldo"],
			":cobra_nomina" => $GrupoCobro["cobra_nomina"],
			":cotiza_ss" => $GrupoCobro["cotiza_ss"],
			":prodtsi" => $GrupoCobro["prodtsi"],
			":liq_extra" => $GrupoCobro["liq_extra"],
			":liq_vacaciones" => $GrupoCobro["liq_vacaciones"],
			":retribucion" => $GrupoCobro["retribucion"],
			":tipo" => $GrupoCobro["tipo"],
			":minimo_fijo" => $GrupoCobro["minimo_fijo"],
			":minimo_interino" => $GrupoCobro["minimo_interino"],
			":minimo_eventual" => $GrupoCobro["minimo_eventual"],
			":minimo_ev" => $GrupoCobro["minimo_ev"],
			":horas_anuales" => $GrupoCobro["horas_anuales"],
			":horas_sabados" => $GrupoCobro["horas_sabados"],
			":media_vacaciones" => $GrupoCobro["media_vacaciones"],
			":enuso" => $GrupoCobro["enuso"],
			":excluir_plpage" => $GrupoCobro["excluir_plpage"],
			":ocupacion" => $GrupoCobro["ocupacion"],
			":grcrpt_codigo" => $GrupoCobro["grcrpt_codigo"],
			":grcrpt_descripcion" => $GrupoCobro[""],
			":grcrptid" => $GrupoCobro["grcrptid"],
			":personal" => $GrupoCobro["personal"],
			":peac" => $GrupoCobro["peac"],
			":excluir_extra" => $GrupoCobro["excluir_extra"],
			":asumedia" => $GrupoCobro["asumedia"],
			":extra_por_horas" => $GrupoCobro["extra_por_horas"],
			":asumedia_periodo" => $GrupoCobro["asumedia_periodo"]];

		$res = $insert->execute($params);
		if ($res == 0 ) {
			echo "***ERROR EN INSERT GRC CODIGO =(".$GrupoCobro["codigo"]. ") DESCRIPCION =(".$GrupoCobro["descripcion"].") \n";
		}
		echo "INSERT GRC CODIGO =(".$GrupoCobro["codigo"]. ") DESCRIPCION =(".$GrupoCobro["descripcion"].") EN EDIFICIO =(".$edificio.") \n";
		return true;
	} catch (PDOException $exception) {
		echo "***PDOERROR EN INSERT GRC CODIGO =(".$GrupoCobro["codigo"]. ") DESCRIPCION =(".$GrupoCobro["descripcion"].") \n"
			. "ERROR =(".$exception->getMessage().") \n";
		exit(1);
	}
}

/**
 * @return bool
 */
function main()
{
	global $actuacion, $tipobd, $eqGrupoCobro_id;


	if ($actuacion == 'ACTIVAR') {
		$EqGrupoCobro = selectEqGrupoCobroById($eqGrupoCobro_id);
		$conexion = conexionEdificio($EqGrupoCobro["edificio"], $tipobd);
		$GrupoCobro["enuso"] = 'S';
		if ($conexion) {
			updateGrupoCobro($GrupoCobro, $conexion, $EqGrupoCobro["codigo_loc"], $EqGrupoCobro["edificio"]);
			$EqGrupoCobro["enuso"] = 'S';
			updateEqGrupoCobroControl($EqGrupoCobro);
		}
	}

	if ($actuacion == 'DESACTIVAR') {
		$EqGrupoCobro = selectEqGrupoCobroById($eqGrupoCobro_id);
		$conexion = conexionEdificio($EqGrupoCobro["edificio"], $tipobd);
		$GrupoCobro["enuso"] = 'N';
		if ($conexion) {
			updateGrupoCobro($GrupoCobro, $conexion, $EqGrupoCobro["codigo_loc"], $EqGrupoCobro["edificio"]);
			$EqGrupoCobro["enuso"] = 'N';
			updateEqGrupoCobroControl($EqGrupoCobro);
		}
	}

	if ($actuacion == 'CREAR') {
		$EqGrupoCobro = selectEqGrupoCobroById($eqGrupoCobro_id);
		$conexion = conexionEdificio($EqGrupoCobro["edificio"], $tipobd);
		$GrupoCobro["codigo"] = $EqGrupoCobro["codigo_loc"];
		if ($conexion) {
			if (insertGrupoCobro($GrupoCobro, $conexion, $EqGrupoCobro["edificio"])) {
				$EqGrupoCobro["enuso"] = 'S';
				updateEqGrupoCobroControl($EqGrupoCobro);
			}
		}
	}

	return true;

}

/**
 * CUERPO PRINCIPAL DEL SCRIPT
 **/

$resultado = 0;
echo " +++++++++++ COMIENZA PROCESO SINCRONIZACIÓN GRUPO DE COBRO (GRC) ++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
	exit(1);
}

$tipo = $argv[1];
$grupoCobro_id = $argv[2];
$actuacion = $argv[3];
$eqGrupoCobro_id = $argv[4];

if ($tipo == 'REAL') {
	echo " ++++ PRODUCCIÓN ++++ \n";
	$JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
	$JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
	$tipobd = 2;
} else {
	echo " ++++ VALIDACIÓN ++++ \n";
	$JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
	$JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
	$tipobd = 1;
}


$GrupoCobro = selectGrupoCobroById($grupoCobro_id);

echo "==> GRUPO COBRO ID =(" . $GrupoCobro["id"] . ") "
	. " CODIGO =(" . $GrupoCobro["codigo"] . ") "
	. " DESCRIPCION =(" . $GrupoCobro["descripcion"] . ") "
	. "\n";

echo "==> ACTUACION= (" . $actuacion . ") EQUIVALENCIA(EQGRUPOCOBRO_ID)= " . $eqGrupoCobro_id . "\n";

$resultado = main();

echo "  +++++++++++ TERMINA PROCESO SINCRONIZACIÓN GRUPOS DE COBRO +++++++++++++ \n";
exit($resultado);
