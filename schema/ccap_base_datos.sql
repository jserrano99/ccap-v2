CREATE TABLE ccap.ccap_base_datos ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	alias                varchar(255)    ,
	maquina              varchar(255)    ,
	puerto               int    ,
	servidor             varchar(255)    ,
	esquema              varchar(255)    ,
	usuario              varchar(255)    ,
	password             varchar(255)    ,
	tipo_bd_id           int    ,
	activa               varchar(1)    ,
	areas                varchar(1)    ,
	edificio_id          int    ,
	CONSTRAINT pk_control_ceco_basedatos_id PRIMARY KEY ( id ),
	CONSTRAINT `FK_677FF572E28358E` FOREIGN KEY ( tipo_bd_id ) REFERENCES ccap.ccap_tipo_bd( id ) ON DELETE NO ACTION ON UPDATE NO ACTION,
	CONSTRAINT `FK_677FF5728A652BD6` FOREIGN KEY ( edificio_id ) REFERENCES ccap.ccap_edificio( id ) ON DELETE NO ACTION ON UPDATE NO ACTION
 ) engine=InnoDB;

CREATE INDEX idx_control_ceco_basedatos_tipobd_id ON ccap.ccap_base_datos ( tipo_bd_id );

CREATE INDEX idx_ccap_base_datos_edificio_id ON ccap.ccap_base_datos ( edificio_id );

INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 1, 'valarea01', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_01', 'jano_pruebas', 'pruebas123', 1, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 2, 'valarea02', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_02', 'jano_pruebas', 'pruebas123', 1, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 3, 'valarea03', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_03', 'jano_pruebas', 'pruebas123', 1, 'N', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 4, 'valarea04', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_04', 'jano_pruebas', 'pruebas123', 1, 'N', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 5, 'valarea05', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_05', 'jano_pruebas', 'pruebas123', 1, 'N', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 6, 'valarea06', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_06', 'jano_pruebas', 'pruebas123', 1, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 7, 'valarea07', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_07', 'jano_pruebas', 'pruebas123', 1, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 8, 'valarea08', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_08', 'jano_pruebas', 'pruebas123', 1, 'N', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 9, 'valarea09', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_09', 'jano_pruebas', 'pruebas123', 1, 'N', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 10, 'valarea10', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_10', 'jano_pruebas', 'pruebas123', 1, 'N', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 11, 'valarea11', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_11', 'jano_pruebas', 'pruebas123', 1, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 12, 'valarea12', 'icmbdava003.madrid.org', 1526, 'valsaint', 'saint_12', 'jano_pruebas', 'pruebas123', 1, 'N', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 13, 'valjanointe', 'icmbdava003.madrid.org', 1528, 'ifxval', 'jano_inte', 'jano_pruebas', 'pruebas123', 1, 'S', 'I', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 14, 'valunif01', 'icmbdava003.madrid.org', 1528, 'ifxval', 'unif_01', 'jano_pruebas', 'pruebas123', 1, 'S', 'U', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 15, 'area01', 'icmbdspr004.madrid.org', 1531, 'area01', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 16, 'area02', 'icmbdspr004.madrid.org', 1532, 'area02', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 17, 'area03', 'icmbdspr004.madrid.org', 1533, 'area03', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 18, 'area04', 'icmbdspr004.madrid.org', 1534, 'area04', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 19, 'area05', 'icmbdspr004.madrid.org', 1535, 'area05', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 20, 'area06', 'icmbdspr004.madrid.org', 1536, 'area06', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 21, 'area07', 'icmbdspr004.madrid.org', 1537, 'area07', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 22, 'area08', 'icmbdspr004.madrid.org', 1538, 'area08', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 23, 'area09', 'icmbdspr004.madrid.org', 1539, 'area09', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 24, 'area10', 'icmbdspr004.madrid.org', 1540, 'area10', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 25, 'area11', 'icmbdspr004.madrid.org', 1541, 'area11', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 26, 'area unica', 'icmbdspr004.madrid.org', 1542, 'area12', 'saint', 'siga', 'cartagena', 2, 'S', 'S', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 27, 'jano_inte', 'icmbdspr004.madrid.org', 1557, 'jano_prod', 'jano_inte', 'siga', 'cartagena', 2, 'S', 'I', null ); 
INSERT INTO ccap.ccap_base_datos( id, alias, maquina, puerto, servidor, esquema, usuario, password, tipo_bd_id, activa, areas, edificio_id ) VALUES ( 28, 'unif_01', 'icmbdspr004.madrid.org', 1557, 'jano_prod', 'unif_01', 'siga', 'cartagena', 2, 'S', 'U', null ); 
