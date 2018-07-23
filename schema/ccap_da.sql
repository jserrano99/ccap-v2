CREATE TABLE ccap.ccap_da ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	codigo               varchar(1)  NOT NULL  ,
	descripcion          varchar(255)    ,
	CONSTRAINT pk_control_ceco_da PRIMARY KEY ( id )
 );

INSERT INTO ccap.ccap_da( id, codigo, descripcion ) VALUES ( 1, '0', 'Dirección Asistencial Centro' ); 
INSERT INTO ccap.ccap_da( id, codigo, descripcion ) VALUES ( 2, '1', 'Dirección Asistencial Norte' ); 
INSERT INTO ccap.ccap_da( id, codigo, descripcion ) VALUES ( 3, '2', 'Dirección Asistencial Este' ); 
INSERT INTO ccap.ccap_da( id, codigo, descripcion ) VALUES ( 4, '3', 'Dirección Asistencial Sureste' ); 
INSERT INTO ccap.ccap_da( id, codigo, descripcion ) VALUES ( 5, '4', 'Dirección Asistencial Sur' ); 
INSERT INTO ccap.ccap_da( id, codigo, descripcion ) VALUES ( 6, '5', 'Dirección Asistencial Oeste' ); 
INSERT INTO ccap.ccap_da( id, codigo, descripcion ) VALUES ( 7, '6', 'Dirección Asistencial Noroeste' ); 
INSERT INTO ccap.ccap_da( id, codigo, descripcion ) VALUES ( 8, '9', 'Servicios Centrales' ); 
