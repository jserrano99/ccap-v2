CREATE TABLE ccap.ccap_catanexo ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	codigo               varchar(2)    ,
	descripcion          varchar(40)    ,
	enuso                varchar(1)    ,
	CONSTRAINT pk_control_ceco_catanexo PRIMARY KEY ( id ),
	CONSTRAINT idx001 UNIQUE ( codigo ) 
 );

INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 1, '00', 'NO DEFINIDA              ', 'N' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 2, '01', 'MEDICOS (F.E.A./E.A.P.)  ', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 3, '02', 'ATS/DUE/MATRONAS/FISIOTER', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 4, '03', 'TECNICOS ESPECIALISTAS   ', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 5, '04', 'AUXILIARES DE ENFERMERIA ', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 6, '05', 'AUXILIARES ADMINISTRATIVO', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 7, '06', 'OTRO PERSONAL ADMINISTRAT', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 8, '07', 'CELADORES                ', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 9, '08', 'PINCHES Y OTRO PERSONAL D', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 10, '09', 'PERSONAL DE OFICIO       ', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 11, '10', 'MEDICOS DE CUPO          ', 'S' ); 
INSERT INTO ccap.ccap_catanexo( id, codigo, descripcion, enuso ) VALUES ( 12, '11', 'M.I.R./E.I.R.            ', 'S' ); 

