

CREATE TABLE  us_prvd_contacto_tel (
     id_contacto_tel INTEGER AUTO_INCREMENT NOT NULL,
     nro_caracteristica INTEGER ,
     nro_telefono INTEGER ,
     KEY (id_contacto_tel)
     ) ENGINE=InnoDB;

CREATE TABLE  us_prvd_fecha_ab (
     id_fecha_ab INTEGER AUTO_INCREMENT NOT NULL,
     alta DATE,
     baja DATE,
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
     id_contacto_tel INTEGER,
     direccion VARCHAR(100),
     correo VARCHAR(50),
     FOREIGN KEY (id_contacto_tel) REFERENCES us_prvd_contacto_tel(id_contacto_tel) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_contacto)
     ) ENGINE=InnoDB;

CREATE TABLE  prvd_provedor (
     id_provedor INTEGER AUTO_INCREMENT NOT NULL,
     id_contacto INTEGER ,
     id_datos_prvd INTEGER ,
     descripcion VARCHAR(100),
     FOREIGN KEY (id_contacto) REFERENCES us_prvd_contacto(id_contacto) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_datos_prvd) REFERENCES prvd_datos(id_datos_prvd) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_provedor)
     ) ENGINE=InnoDB;





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
     FOREIGN KEY (id_pais) REFERENCES mp_pais(id_pais) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_provincia) REFERENCES mp_provincia(id_provincia) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_localidad) REFERENCES mp_localidad(id_localidad) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_zona)
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


CREATE TABLE  us_datos (
     id_datos INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL, 
     apellido VARCHAR(50) NOT NULL, 
     fecha_nac DATE , 
     dni INTEGER (8) ,
     id_foto INTEGER NOT NULL,
     genero ENUM('M','F'),
     id_fecha_ab INTEGER ,
     FOREIGN KEY (id_foto) REFERENCES us_prvd_foto(id_foto) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_fecha_ab) REFERENCES us_prvd_fecha_ab(id_fecha_ab) ON DELETE NO ACTION ON UPDATE CASCADE,
     key(id_datos)
     ) ENGINE=InnoDB;

CREATE TABLE  usuarios (
     id_usuarios INTEGER AUTO_INCREMENT NOT NULL,
     id_datos INTEGER ,
     id_contacto INTEGER ,
     acceso ENUM('ADMIN', 'OPER'),
     usuario VARCHAR (15) NOT NULL,
     pass VARCHAR(20) NOT NULL,
     FOREIGN KEY (id_datos) REFERENCES us_datos(id_datos) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_contacto) REFERENCES us_prvd_contacto(id_contacto) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_usuarios)
     ) ENGINE=InnoDB;


CREATE TABLE  us_prvd (
     id_us_prvd INTEGER AUTO_INCREMENT NOT NULL,
     id_usuarios INTEGER NOT NULL,
     id_provedor INTEGER NOT NULL,
     FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_provedor) REFERENCES prvd_provedor(id_provedor) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_us_prvd)
     ) ENGINE=InnoDB;

CREATE TABLE  gs_subgasto (
     id_sub_gasto INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     valor INTEGER,
     descripcion VARCHAR(50),
     fecha_hora DATETIME NOT NULL,
     condicion ENUM('+','-'),
     KEY (id_sub_gasto)
     ) ENGINE=InnoDB;

CREATE TABLE  gs_gsub_gasto (
     id_gsub_gasto INTEGER AUTO_INCREMENT NOT NULL,
     id_sub_gasto INTEGER NOT NULL,
     KEY (id_gsub_gasto),
     FOREIGN KEY (id_sub_gasto) REFERENCES gs_subgasto(id_sub_gasto) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  gs_gasto_unico (
     id_gasto_unico INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     valor INTEGER,
     fecha_hora DATETIME,
     id_gsub_gasto INTEGER,
     habilitado BOOLEAN NOT NULL,
     descripcion VARCHAR(50),
     KEY (id_gasto_unico),
     FOREIGN KEY (id_gsub_gasto) REFERENCES gs_gsub_gasto(id_gsub_gasto) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  gs_grupo (
     id_ggs INTEGER AUTO_INCREMENT NOT NULL,
     id_gasto_unico INTEGER NOT NULL,
     FOREIGN KEY (id_gasto_unico) REFERENCES gs_gasto_unico(id_gasto_unico) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_ggs)
     ) ENGINE=InnoDB;

CREATE TABLE  gs_descripcion (
     id_gs_des INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     descripcion VARCHAR(50),
     KEY (id_gs_des)
     ) ENGINE=InnoDB;

CREATE TABLE  gs_gastos (
     id_gasto INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     id_gs_des INTEGER NOT NULL,
     id_ggs INTEGER NOT NULL,
     habilitado BOOLEAN NOT NULL,
     KEY (id_gasto),
     FOREIGN KEY (id_gs_des) REFERENCES gs_descripcion(id_gs_des) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_ggs) REFERENCES gs_grupo(id_ggs) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  us_ggs (
     id_us_ggs INTEGER AUTO_INCREMENT NOT NULL,
     id_gasto INTEGER NOT NULL,
     KEY (id_us_ggs),
     FOREIGN KEY (id_gasto) REFERENCES gs_gastos(id_gasto) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  us_gmv (
     id_gmv INTEGER AUTO_INCREMENT NOT NULL,
     id_gs_mv INTEGER NOT NULL,
     KEY (id_gmv),
     FOREIGN KEY (id_gs_mv) REFERENCES gs_gasto_unico(id_gasto_unico) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  us_sueldos (
     id_sueldo INTEGER AUTO_INCREMENT NOT NULL,
     id_usuario INTEGER NOT NULL,
     id_gmv INTEGER NOT NULL,
     basico INTEGER NOT NULL,
     KEY (id_sueldo),
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_gmv) REFERENCES us_gmv(id_gmv) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  cj_ggs (
     id_cj_ggs INTEGER AUTO_INCREMENT NOT NULL,
     id_cj_gasto INTEGER NOT NULL,
     KEY (id_cj_ggs),
     FOREIGN KEY (id_cj_gasto) REFERENCES gs_gastos(id_gasto) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  lc_caja (
     id_caja INTEGER AUTO_INCREMENT NOT NULL,
     id_cj_ggs INTEGER NOT NULL,
     estado ENUM('A','C'),
     fecha_hora_apertura DATETIME NOT NULL,
     fecha_hora_cierre DATETIME NOT NULL,
     monto INTEGER,
     KEY (id_caja),
     FOREIGN KEY (id_cj_ggs) REFERENCES cj_ggs(id_cj_ggs) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  lc_gcj (
     id_gcj INTEGER AUTO_INCREMENT NOT NULL,
     id_caja INTEGER,
     KEY (id_gcj),
     FOREIGN KEY (id_caja) REFERENCES lc_caja(id_caja) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  us_gastos (
     id_us_gasto INTEGER AUTO_INCREMENT NOT NULL,
     id_usuario INTEGER NOT NULL,
     id_us_ggs INTEGER,
     KEY (id_us_gasto),
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_us_ggs) REFERENCES us_ggs(id_us_ggs) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  us_ggs_fuerte (
     id_ggs_fuerte INTEGER AUTO_INCREMENT NOT NULL,
     id_gasto_fuerte INTEGER,
     KEY (id_ggs_fuerte),
     FOREIGN KEY (id_gasto_fuerte) REFERENCES gs_gastos(id_gasto) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  us_cjf_unica (
     id_cjf_unica INTEGER AUTO_INCREMENT NOT NULL,
     monto INTEGER,
     fecha_hora DATETIME NOT NULL,
     id_ggs_fuerte INTEGER,
     descripcion VARCHAR(50),
     KEY (id_cjf_unica),
     FOREIGN KEY (id_ggs_fuerte) REFERENCES us_ggs_fuerte(id_ggs_fuerte) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  us_gcjf_unica (
     id_gcjf_unica INTEGER AUTO_INCREMENT NOT NULL,
     id_cjf_unica INTEGER,
     KEY (id_gcjf_unica),
     FOREIGN KEY (id_cjf_unica) REFERENCES us_cjf_unica(id_cjf_unica) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  us_cj_fuerte (
     id_cj_fuerte INTEGER AUTO_INCREMENT NOT NULL,
     id_usuario INTEGER NOT NULL,
     id_gcjf_unica INTEGER,
     KEY (id_cj_fuerte),
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_gcjf_unica) REFERENCES us_gcjf_unica(id_gcjf_unica) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  art_local (
     id_local INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     descripcion VARCHAR(100) NOT NULL,
     id_zona INTEGER,
     id_gcj INTEGER,
     FOREIGN KEY (id_zona) REFERENCES mp_zona(id_zona) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_local)
     ) ENGINE=InnoDB;

CREATE TABLE  us_local (
     id_usuarios_local INTEGER AUTO_INCREMENT NOT NULL,
     id_usuarios INTEGER NOT NULL,
     id_local INTEGER NOT NULL,
     FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_local) REFERENCES art_local(id_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_usuarios_local)
     ) ENGINE=InnoDB;


CREATE TABLE  us_mark (
     id_market INTEGER AUTO_INCREMENT NOT NULL,
     id_usuarios INTEGER NOT NULL,
     KEY (id_market),
     FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE  art_codigo_barra (
     id_cb INTEGER (13)AUTO_INCREMENT NOT NULL,
     cb INTEGER,
     KEY (id_cb)
     ) ENGINE=InnoDB;


CREATE TABLE  art_categoria (
     id_categoria INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     valor VARCHAR(100),
     descripcion VARCHAR(100),
     KEY (id_categoria)
     ) ENGINE=InnoDB;

CREATE TABLE  art_grupo_categoria (
     id_gc INTEGER AUTO_INCREMENT NOT NULL,
     id_categoria INTEGER,
     FOREIGN KEY (id_categoria) REFERENCES art_categoria(id_categoria) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_gc)
     ) ENGINE=InnoDB;

CREATE TABLE  art_carga (
     id_carga INTEGER AUTO_INCREMENT NOT NULL,
     fecha_hora DATETIME NOT NULL,
     id_usuario INTEGER NOT NULL,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_carga)
     ) ENGINE=InnoDB;

CREATE TABLE  art_marca (
     id_marca INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(50) NOT NULL,
     descripcion VARCHAR(100),
     KEY (id_marca)
     ) ENGINE=InnoDB;

CREATE TABLE  art_articulo (
     id_articulo INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(100),
     descripcion VARCHAR(200),
     KEY (id_articulo)
     ) ENGINE=InnoDB;


CREATE TABLE  art_tipo (
     id_tipo INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(100),
     descripcion VARCHAR(100),
     KEY (id_tipo)
     ) ENGINE=InnoDB;

CREATE TABLE  art_conjunto (
     id_art_conjunto INTEGER AUTO_INCREMENT NOT NULL,
     id_articulo INTEGER NOT NULL,
     id_marca INTEGER,
     id_tipo INTEGER NOT NULL,
     FOREIGN KEY (id_articulo) REFERENCES art_articulo(id_articulo) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_marca) REFERENCES art_marca(id_marca) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_tipo) REFERENCES art_tipo(id_tipo) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_art_conjunto)
     ) ENGINE=InnoDB;



CREATE TABLE art_fotos (
     id_art_fotos INTEGER AUTO_INCREMENT NOT NULL,
     id_foto INTEGER NOT NULL,
     FOREIGN KEY (id_foto) REFERENCES us_prvd_foto(id_foto) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_art_fotos)
     ) ENGINE=InnoDB;

CREATE TABLE  art_lote (
     id_lote INTEGER AUTO_INCREMENT NOT NULL,
     id_art_conjunto INTEGER,
     id_provedor INTEGER ,
     cantidad_total INTEGER NOT NULL,
     id_cb INTEGER, 
     id_gc INTEGER,
     descripcion VARCHAR(100),
     id_art_fotos INTEGER,
     precio_base DEC(15,2),
     importe DEC(15,2),
     FOREIGN KEY (id_art_conjunto) REFERENCES art_conjunto(id_art_conjunto) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_provedor) REFERENCES prvd_provedor(id_provedor) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_cb) REFERENCES art_codigo_barra(id_cb) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_gc) REFERENCES art_grupo_categoria(id_gc) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_art_fotos) REFERENCES art_fotos(id_art_fotos) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_lote)
     ) ENGINE=InnoDB;

CREATE TABLE  art_lote_local (
     id_lote_local INTEGER AUTO_INCREMENT NOT NULL,
     id_lote INTEGER NOT NULL,
     id_local INTEGER,
     cantidad_parcial INTEGER NOT NULL,
     id_carga INTEGER NOT NULL,
     FOREIGN KEY (id_lote) REFERENCES art_lote(id_lote) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_local) REFERENCES art_local(id_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_carga) REFERENCES art_carga(id_carga) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_lote_local)
     ) ENGINE=InnoDB;

CREATE TABLE lote_us (
     id_lote_us INTEGER AUTO_INCREMENT NOT NULL,
     id_usuario INTEGER NOT NULL,
     id_lote INTEGER NOT NULL,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_lote) REFERENCES art_lote(id_lote) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_lote_us)
     ) ENGINE=InnoDB;

CREATE TABLE art_venta_medio_fechas (
     id_fechas_medio INTEGER AUTO_INCREMENT NOT NULL,
     fecha_hora_inicio DATE NOT NULL,
     fecha_hora_fin DATE NOT NULL,
     KEY (id_fechas_medio)
     ) ENGINE=InnoDB;

CREATE TABLE art_venta_medio_dias (
     id_dias_medio INTEGER AUTO_INCREMENT NOT NULL,
     dias VARCHAR(100) NOT NULL,
     KEY (id_dias_medio)
     ) ENGINE=InnoDB;

CREATE TABLE art_venta_medio_descripcion (
     id_medio_descripcion INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(100) NOT NULL,
     descripcion VARCHAR(100) NOT NULL,
     KEY (id_medio_descripcion)
     ) ENGINE=InnoDB;

CREATE TABLE art_venta_medio (
     id_medio INTEGER AUTO_INCREMENT NOT NULL,
     nombre VARCHAR(100) NOT NULL,
     descripcion INTEGER,
     descuento INTEGER,
     id_fechas_medio INTEGER,
     id_dias_medio INTEGER,
     id_usuario INTEGER,
     FOREIGN KEY (id_fechas_medio) REFERENCES art_venta_medio_fechas(id_fechas_medio) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_dias_medio) REFERENCES art_venta_medio_dias(id_dias_medio) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (descripcion) REFERENCES art_venta_medio_descripcion(id_medio_descripcion) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_medio)
     ) ENGINE=InnoDB;

CREATE TABLE  art_venta (
     id_venta INTEGER AUTO_INCREMENT NOT NULL,
     fecha_hora DATETIME NOT NULL,
     id_usuarios INTEGER NOT NULL,
     id_medio INTEGER NOT NULL,
     total VARCHAR(100) NOT NULL,
     cuotas VARCHAR(100),
     id_cambio INTEGER,
     FOREIGN KEY (id_medio) REFERENCES art_venta_medio(id_medio) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_cambio) REFERENCES art_venta(id_venta) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_venta)
     ) ENGINE=InnoDB;

CREATE TABLE  art_no_venta (
     id_no_venta INTEGER AUTO_INCREMENT NOT NULL,
     fecha_hora DATETIME NOT NULL,
     id_usuarios INTEGER NOT NULL,
     id_lote_local INTEGER NOT NULL,
     FOREIGN KEY (id_usuarios) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_lote_local) REFERENCES art_lote_local(id_lote_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_no_venta)
     ) ENGINE=InnoDB;

CREATE TABLE  art_unico (
     id_unico INTEGER AUTO_INCREMENT NOT NULL,
     id_lote_local INTEGER NOT NULL,
     id_venta INTEGER NOT NULL,
     FOREIGN KEY (id_lote_local) REFERENCES art_lote_local(id_lote_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_venta) REFERENCES art_venta(id_venta) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_unico)
     ) ENGINE=InnoDB;

CREATE TABLE  us_acceso (
     id_acceso INTEGER AUTO_INCREMENT NOT NULL,
     id_local INTEGER NOT NULL,
     id_usuario INTEGER NOT NULL,
     fecha_hora_inicio DATETIME NOT NULL,
     fecha_hora_fin DATETIME,
     FOREIGN KEY (id_local) REFERENCES art_local(id_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_acceso)
     ) ENGINE=InnoDB;





