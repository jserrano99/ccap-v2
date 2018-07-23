CREATE TABLE ccap.ccap_modalidad ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	codigo               varchar(1)  NOT NULL  ,
	descripcion          varchar(60)  NOT NULL  ,
	CONSTRAINT pk_ccap_modalidad PRIMARY KEY ( id ),
	CONSTRAINT uk_codigo UNIQUE ( codigo ) 
 );

 INSERT INTO ccap.ccap_modalidad( id, codigo, descripcion ) VALUES ( 1, 'A', 'A.P.D. NO INTEGRADO' ); 
INSERT INTO ccap.ccap_modalidad( id, codigo, descripcion ) VALUES ( 2, 'E', 'NUEVO MODELO' ); 
INSERT INTO ccap.ccap_modalidad( id, codigo, descripcion ) VALUES ( 3, 'N', 'NUEVO MODELO AREA' ); 
INSERT INTO ccap.ccap_modalidad( id, codigo, descripcion ) VALUES ( 4, 'T', 'MODELO TRADICIONAL' ); 
INSERT INTO ccap.ccap_modalidad( id, codigo, descripcion ) VALUES ( 5, 'Z', 'ZONA' ); 
INSERT INTO ccap.ccap_modalidad( id, codigo, descripcion ) VALUES ( 6, 'C', 'CONSULTA' ); 
INSERT INTO ccap.ccap_modalidad( id, codigo, descripcion ) VALUES ( 7, 'L', 'LABORAL POR SENTENCIA' ); 
INSERT INTO ccap.ccap_modalidad( id, codigo, descripcion ) VALUES ( 8, 'P', 'PENSIONISTA' ); 
