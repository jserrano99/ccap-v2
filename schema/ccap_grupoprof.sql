CREATE TABLE ccap.ccap_grupoprof ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	codigo               varchar(1)  NOT NULL  ,
	descripcion          varchar(25)  NOT NULL  ,
	importe              decimal(10,0)  NOT NULL  ,
	exento_ss            varchar(1)    ,
	muface_escala        varchar(4)    ,
	sal_base             decimal(10,0)    ,
	codigo2              varchar(2)    ,
	enuso                varchar(1)    ,
	CONSTRAINT pk_control_ceco_grupoprof PRIMARY KEY ( id ),
	CONSTRAINT idx001 UNIQUE ( codigo ) 
 );

INSERT INTO ccap.ccap_grupoprof( id, codigo, descripcion, importe, exento_ss, muface_escala, sal_base, codigo2, enuso ) VALUES ( 1, 'A', 'GRUPO PROFESIONAL A1', 4308, 'N', '200 ', 110905, 'A1', 'S' ); 
INSERT INTO ccap.ccap_grupoprof( id, codigo, descripcion, importe, exento_ss, muface_escala, sal_base, codigo2, enuso ) VALUES ( 2, 'B', 'GRUPO PROFESIONAL A2', 3512, 'N', null, 96887, 'A2', 'S' ); 
INSERT INTO ccap.ccap_grupoprof( id, codigo, descripcion, importe, exento_ss, muface_escala, sal_base, codigo2, enuso ) VALUES ( 3, 'C', 'GRUPO PROFESIONAL C1', 2658, 'N', null, 72723, 'C1', 'S' ); 
INSERT INTO ccap.ccap_grupoprof( id, codigo, descripcion, importe, exento_ss, muface_escala, sal_base, codigo2, enuso ) VALUES ( 4, 'D', 'GRUPO PROFESIONAL C2', 1808, 'N', null, 60525, 'C2', 'S' ); 
INSERT INTO ccap.ccap_grupoprof( id, codigo, descripcion, importe, exento_ss, muface_escala, sal_base, codigo2, enuso ) VALUES ( 5, 'E', 'GRUPO PROFESIONAL E', 1361, 'N', null, 55396, 'E ', 'S' ); 
INSERT INTO ccap.ccap_grupoprof( id, codigo, descripcion, importe, exento_ss, muface_escala, sal_base, codigo2, enuso ) VALUES ( 6, 'F', 'GRUPO PROF. LABORAL A', 3782, 'N', null, null, null, 'S' ); 
