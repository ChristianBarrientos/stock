
--PROVEEDOR
CREATE TABLE  us_prvd_contacto_tel (
     id_contacto_tel INTEGER AUTO_INCREMENT NOT NULL,
     nro_caracteristica INTEGER NOT NULL,
     nro_telefono INTEGER NOT NULL,
     KEY (id_contacto_tel)
     ) ENGINE=InnoDB;

CREATE TABLE  us_prvd_fecha_ab (
     id_fecha_ab INTEGER AUTO_INCREMENT NOT NULL,
     alta DATE NOT NULL,
     baja DATE NOT NULL,
     KEY (id_fecha_ab)
     ) ENGINE=InnoDB;

CREATE TABLE  us_prvd_foto (
     id_foto INTEGER AUTO_INCREMENT NOT NULL,
     path_foto VARCHAR(100),
     KEY (id_foto)
     ) ENGINE=InnoDB;

CREATE TABLE  prvd_datos (
     id_datos_prvd INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(100),
     cuit VARCHAR(100),
     id_fecha_ab INTEGER,
     id_foto INTEGER,
     FOREIGN KEY (id_fecha_ab) REFERENCES us_prvd_fecha_ab(id_fecha_ab) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_foto) REFERENCES us_prvd_foto(id_foto) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_datos_prvd)
     ) ENGINE=InnoDB;

CREATE TABLE  us_prvd_contacto (
     id_contacto INTEGER AUTO_INCREMENT NOT NULL,
     id_contacto_tel INTEGER NOT NULL,
     direccion VARCHAR(100),
     correo VARCHAR(50),
     FOREIGN KEY (id_contacto_tel) REFERENCES us_prvd_contacto_tel(id_contacto_tel) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_contacto)
     ) ENGINE=InnoDB;

CREATE TABLE  prvd_provedor (
     id_provedor INTEGER AUTO_INCREMENT NOT NULL,
     id_contacto INTEGER NOT NULL,
     id_datos_prvd INTEGER NOT NULL,
     FOREIGN KEY (id_contacto) REFERENCES us_prvd_contacto(id_contacto) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_datos_prvd) REFERENCES prvd_datos(id_datos_prvd) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_provedor)
     ) ENGINE=InnoDB;
--FIN PROVEEDOR

-- LOCAL


CREATE TABLE  mp_pais (
     id_pais INTEGER AUTO_INCREMENT NOT NULL,
     valor VARCHAR(100) NOT NULL,
     KEY (id_pais)
     ) ENGINE=InnoDB;

CREATE TABLE  mp_provincia (
     id_provincia INTEGER AUTO_INCREMENT NOT NULL,
     valor VARCHAR(100) NOT NULL,
     id_pais INTEGER NOT NULL,
     FOREIGN KEY (id_pais) REFERENCES mp_pais(id_pais) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_provincia)
     ) ENGINE=InnoDB;

CREATE TABLE  mp_localidad (
     id_localidad INTEGER AUTO_INCREMENT NOT NULL,
     valor VARCHAR(100) NOT NULL,
     id_provincia INTEGER NOT NULL,
     id_pais INTEGER NOT NULL,
     FOREIGN KEY (id_pais) REFERENCES mp_pais(id_pais) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_provincia) REFERENCES mp_provincia(id_provincia) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_localidad)
     ) ENGINE=InnoDB;

CREATE TABLE  mp_zona (
     id_zona INTEGER AUTO_INCREMENT NOT NULL,
     id_pais INTEGER NOT NULL,
     id_provincia INTEGER NOT NULL,
     id_localidad INTEGER NOT NULL,
     direccion VARCHAR(100) NOT NULL,
     FOREIGN KEY (id_zona) REFERENCES us_prvd_contacto(id_zona) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_zona)
     ) ENGINE=InnoDB;

CREATE TABLE  art_local (
     id_local INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     descripcion VARCHAR(100) NOT NULL,
     id_zona INTEGER NOT NULL,
     FOREIGN KEY (id_zona) REFERENCES mp_zona(id_zona) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_local)
     ) ENGINE=InnoDB;

CREATE TABLE  mp_punto (
     id_punto INTEGER AUTO_INCREMENT NOT NULL,
     latitud INTEGER,
     longitud INTEGER,
     id_zona INTEGER NOT NULL,
     FOREIGN KEY (id_zona) REFERENCES mp_zona(id_zona) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_punto)
     ) ENGINE=InnoDB;

CREATE TABLE  mp_market (
     id_market INTEGER AUTO_INCREMENT NOT NULL,
     id_punto INTEGER,
     titulo VARCHAR(50) NOT NULL, 
     FOREIGN KEY (id_punto) REFERENCES mp_punto(id_punto) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_market)
     ) ENGINE=InnoDB;

CREATE TABLE  us_mark (
     id_market INTEGER,
     id_usuario INTEGER,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;
-- FIN LOCAL

-- USUARIOS
CREATE TABLE  us_datos (
     id_datos INTEGER AUTO_INCREMENT NOT NULL,,
     nombre VARCHAR(50) NOT NULL, 
     apellido VARCHAR(50) NOT NULL, 
     fecha_nac DATE NOT NULL, 
     dni INTEGER (8) NOT NULL,
     id_foto INTEGER NOT NULL,
     genero ENUM('M','F'),
     id_fecha_ab INTEGER NOT NULL,
     FOREIGN KEY (id_foto) REFERENCES us_prvd_foto(id_foto) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_fecha_ab) REFERENCES us_prvd_fecha_ab(id_fecha_ab) ON DELETE NO ACTION ON UPDATE CASCADE
     key(id_datos)
     ) ENGINE=InnoDB;

CREATE TABLE  usuarios (
     id_usuarios INTEGER AUTO_INCREMENT NOT NULL,
     id_datos INTEGER NOT NULL,
     id_contacto INTEGER NOT NULL,
     titulo VARCHAR(50) NOT NULL, 
     acceso ENUM('ADMIN', 'OPER'),
     usuario VARCHAR (15) NOT NULL,
     pass VARCHAR(20) NOT NULL,
     id_local INTEGER NOT NULL,
     FOREIGN KEY (id_datos) REFERENCES us_datos(id_datos) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_usuarios)
     ) ENGINE=InnoDB;
-- FIN USUARIOS
-- CODIGO BARRA
CREATE TABLE  art_codigo_barra (
     id_cb INTEGER AUTO_INCREMENT NOT NULL,,
     cb INTEGER,
     KEY (id_cb)
     ) ENGINE=InnoDB;
-- FIN CODIGO BARRA

-- GRUPO CATEGORIAS
CREATE TABLE  art_sub_categoria (
     id_sub_categoria INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     valor VARCHAR(100) NOT NULL,
     descripcion VARCHAR(100),
     KEY (id_sub_categoria)
     ) ENGINE=InnoDB;

CREATE TABLE  art_grupo_sub_categoria (
     id_gsct INTEGER AUTO_INCREMENT NOT NULL,
     id_sub_categoria INTEGER,
     FOREIGN KEY (id_sub_categoria) REFERENCES art_sub_categoria(id_sub_categoria) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_gsct)
     ) ENGINE=InnoDB;

CREATE TABLE  art_categoria (
     id_categoria INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     valor VARCHAR(100) NOT NULL,
     descripcion VARCHAR(100),
     id_gsct INTEGER,
     FOREIGN KEY (id_gsct) REFERENCES art_grupo_sub_categoria(id_gsct) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_categoria)
     ) ENGINE=InnoDB;

CREATE TABLE  art_grupo_categoria (
     id_gc INTEGER AUTO_INCREMENT NOT NULL,
     id_categoria INTEGER NOT NULL,
     FOREIGN KEY (id_categoria) REFERENCES art_categoria(id_categoria) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_gc)
     ) ENGINE=InnoDB;
-- FIN GRUPO CATEGORIAS
-- CARGA LOTE
CREATE TABLE  art_carga (
     id_carga INTEGER AUTO_INCREMENT NOT NULL,
     fecha_hora DATETIME NOT NULL,
     id_local INTEGER NOT NULL,
     id_usuario INTEGER NOT NULL,
     FOREIGN KEY (id_local) REFERENCES art_local(id_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_carga)
     ) ENGINE=InnoDB;
-- FIN CARGA LOTE

CREATE TABLE  art_lote (
     id_lote INTEGER AUTO_INCREMENT NOT NULL,
     id_provedor INTEGER NOT NULL,
     cantidad INTEGER NOT NULL,
     id_local INTEGER NOT NULL,
     id_cb INTEGER NOT NULL,
     id_gc INTEGER NOT NULL,
     id_carga INTEGER NOT NULL,
     FOREIGN KEY (id_provedor) REFERENCES prvd_provedor(id_provedor) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_local) REFERENCES art_local(id_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_cb) REFERENCES art_codigo_barra(id_cb) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_gc) REFERENCES art_grupo_categoria(id_gc) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_carga) REFERENCES art_carga(id_carga) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_lote)
     ) ENGINE=InnoDB;

-- VENTA ARTICULO UNICO
CREATE TABLE  art_venta (
     id_venta INTEGER AUTO_INCREMENT NOT NULL,
     fecha_hora DATETIME NOT NULL,
     id_local INTEGER NOT NULL,
     id_usuario INTEGER NOT NULL,
     FOREIGN KEY (id_local) REFERENCES art_local(id_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_venta)
     ) ENGINE=InnoDB;

CREATE TABLE  art_unico (
     id_unico INTEGER AUTO_INCREMENT NOT NULL,
     id_lote INTEGER NOT NULL,
     id_venta INTEGER NOT NULL,
     FOREIGN KEY (id_lote) REFERENCES art_lote(id_lote) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_venta) REFERENCES art_venta(id_venta) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_unico)
     ) ENGINE=InnoDB;
-- FIN VENTA ARTICULO UNICO

-- ARTICULO
CREATE TABLE  art_marca (
     id_marca INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     descripcion VARCHAR(100) NOT NULL,
     id_lote INTEGER NOT NULL,
     FOREIGN KEY (id_lote) REFERENCES art_lote(id_lote) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_marca)
     ) ENGINE=InnoDB;

CREATE TABLE  art_articulo (
     id_articulo INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(100),
     id_marca INTEGER NOT NULL,
     FOREIGN KEY (id_marca) REFERENCES art_marca(id_marca) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_articulo)
     ) ENGINE=InnoDB;

-- FIN ARTICULO

