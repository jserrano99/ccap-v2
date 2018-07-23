CREATE TABLE ccap.ccap_ocupacion ( 
	id                   int  NOT NULL  AUTO_INCREMENT,
	codigo               varchar(1)  NOT NULL  ,
	descripcion          varchar(60)  NOT NULL  ,
	CONSTRAINT pk_control_ceco_ocupacion PRIMARY KEY ( id ),
	CONSTRAINT idx001 UNIQUE ( codigo ) 
 );
 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 1, 'A', 'Personal en trabajos exclusivos de oficina' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 2, 'B', 'Personal que debe desplazarse durante su jornada laboral' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 3, 'C', 'Trabajadores en periodo de baja por IT' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 4, 'D', 'Personal de obras y trabajos de construcción en general' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 5, 'E', 'Conductores de vehículo de transporte (< a 3,5 Tm)' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 6, 'F', 'Conductores de vehículo de transporte (> a 3,5 Tm)' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 7, 'G', 'Personal de limpieza en general' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 8, 'H', 'Personal de seguridad' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 9, 'I', 'Personal de vuelo' ); 
INSERT INTO ccap.ccap_ocupacion( id, codigo, descripcion ) VALUES ( 10, 'Z', 'Actividades sanitarias, servicios sociales' ); 
