
CREATE TABLE ccap.ccap_estado_usuario ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	descripcion          varchar(255)    ,
	CONSTRAINT pk_ccap_estado_usuario PRIMARY KEY ( id )
 );

 INSERT INTO ccap.ccap_estado_usuario( id, descripcion ) VALUES ( 1, 'Activo' ); 
 INSERT INTO ccap.ccap_estado_usuario( id, descripcion ) VALUES ( 2, 'Bloqueado' ); 
INSERT INTO ccap.ccap_estado_usuario( id, descripcion ) VALUES ( 3, 'Cambia Password' ); 
