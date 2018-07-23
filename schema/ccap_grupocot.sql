CREATE TABLE ccap.ccap_grupocot ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	codigo               varchar(3)  NOT NULL  ,
	descripcion          varchar(25)  NOT NULL  ,
	enuso                varchar(1)  NOT NULL  ,
	CONSTRAINT pk_control_ceco_grupocot PRIMARY KEY ( id ),
	CONSTRAINT idx001 UNIQUE ( codigo ) 
 );

INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 1, '001', '001', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 2, '002', '002', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 3, '003', '003', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 4, '004', '004', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 5, '005', '005', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 6, '006', '006', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 7, '007', '007', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 8, '008', '008', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 9, '009', '009', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 10, '010', '010', 'S' ); 
INSERT INTO ccap.ccap_grupocot( id, codigo, descripcion, enuso ) VALUES ( 11, '011', '011', 'S' ); 
