
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 1, 1, '', 'da', 'manual', 1, 1, 'Catalogo de Direcciones Asistenciales', 0, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 2, 1, '', 'base_datos', 'manual', 4, 1, 'Catálogo de Conexiones a las Bases de Datos', 2, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 3, 1, '', 'edificios', 'manual', 2, 1, 'Catálogo de Edificios', 0, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 4, 1, '', 'tipo_bd', 'manual', 3, 1, 'Tipos de Bases de Datos', 0, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 5, 1, '', 'uf', 'costes/cargaUf.php', 5, 2, 'Unidades Funcionales', 1, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 6, 1, '', 'pa', 'costes/cargaPa.php', 6, 2, 'Puntos Asistenciales', 1, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 7, 1, '', 'catfp', 'maestros/cargaCatFp.php', 7, 3, 'Categorías Fp', null, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 8, 1, '', 'modalidad', 'manual', 8, 3, 'Catálogo Modalidades', null, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 9, 1, '', 'catgen', 'maestros/cargaCatGen.php', 9, 3, 'Categorías Generales', null, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 10, 1, '', 'plazas', 'costes/cargaPlazas.php', 10, 2, 'Plazas', 5, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 12, 1, '', 'cecos', 'costes/cargaCecos.php', 11, 2, 'Centros de Coste', null, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 13, 1, '', 'cecocias', 'costes/cargaCecoCias.php', 12, 3, 'Asignación de Ceco a Plazas', 0, '' ); 
INSERT INTO ccap.comun_carga_inicial( id, estado_carga_inicial_id, fecha_carga, tabla, proceso, orden, modulo_id, descripcion, numDep, fichero_log ) VALUES ( 14, 1, '', 'categ', 'maestros/cargaCateg.php', 13, 3, 'Categorías Profesionales', 1, '' ); 
