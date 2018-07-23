CREATE TABLE ccap.ccap_tipo_bd ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	descripcion          varchar(255)    ,
	CONSTRAINT pk_ccap_tipo_bd PRIMARY KEY ( id )
 );

INSERT INTO ccap.ccap_tipo_bd( id, descripcion ) VALUES ( 1, 'VALIDACION' ); 
INSERT INTO ccap.ccap_tipo_bd( id, descripcion ) VALUES ( 2, 'PRODUCCION' ); 
