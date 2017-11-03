INSERT INTO `us_prvd_foto`(`id_foto`, `path_foto`) 
VALUES (0,'imagenes/us_prvd/proveedor.jpg');
INSERT INTO `us_prvd_foto`(`id_foto`, `path_foto`) 
VALUES (0,'imagenes/us_prvd/usuario.jpg');
INSERT INTO `us_prvd_fecha_ab`(`id_fecha_ab`, `alta`, `baja`) 
VALUES (0,'2017-09-11',null);
INSERT INTO `us_prvd_contacto_tel`(`id_contacto_tel`, `nro_caracteristica`, `nro_telefono`)
VALUES (0,383,154528621);
INSERT INTO `us_prvd_contacto`(`id_contacto`, `id_contacto_tel`, `direccion`, `correo`) 
VALUES (0,1,'Cordoba 542','christianbarrientoss@hotmail.com');
INSERT INTO `us_datos`(`id_datos`, `nombre`, `apellido`, `fecha_nac`, `dni`, `id_foto`, `genero`, `id_fecha_ab`) 
VALUES (0,'Christian','Barrientios','1996-01-31',39014575,2,'m',1);
INSERT INTO `usuarios`(`id_usuarios`, `id_datos`, `id_contacto`, `acceso`, `usuario`, `pass`) 
VALUES (0,1,1,'ADMIN','christian','123456');
INSERT INTO `mp_pais`(`id_pais`, `valor`) VALUES (0,'Argentina');
INSERT INTO `mp_provincia`(`id_provincia`, `valor`,`id_pais`) 
VALUES (0,'Catamarca',1);
INSERT INTO `mp_localidad`(`id_localidad`, `valor`,`id_provincia`,`id_pais`) 
VALUES (0,'San Fernando del Valle de Catamarca',1,1);

INSERT INTO `art_venta_medio_fechas`(`id_fechas_medio`, `fecha_hora_inicio`, `fecha_hora_fin`) 
VALUES (1,'0000-00-00','0000-00-00');

INSERT INTO `art_venta_medio_dias`(`id_dias_medio`, `dias`) 
VALUES (1,'1&2&3&4&5&6&7');

INSERT INTO `art_venta_medio`(`id_medio`, `nombre`, `descripcion`, `descuento`, `id_fechas_medio`, `id_dias_medio`, `id_usuario`) 
VALUES (1,'Contado','Contado',0,1,1,1);



INSERT INTO `art_categoria`(`id_categoria`, `nombre`, `valor`, `descripcion`) 
VALUES (1,'Precio',null,'Precio base.');

INSERT INTO `art_categoria`(`id_categoria`, `nombre`, `valor`, `descripcion`) 
VALUES (2,'Medida',null,'Medida general.');

INSERT INTO `art_categoria`(`id_categoria`, `nombre`, `valor`, `descripcion`) 
VALUES (3,'Tarjeta',null,'Precio con tarjeta.');

INSERT INTO `art_categoria`(`id_categoria`, `nombre`, `valor`, `descripcion`) 
VALUES (4,'CreditoP',null,'Precio Credito Personal.');

INSERT INTO `art_grupo_categoria`(`id_gc`, `id_categoria`) 
VALUES (1,1);

INSERT INTO `art_grupo_categoria`(`id_gc`, `id_categoria`) 
VALUES (1,2);

INSERT INTO `art_grupo_categoria`(`id_gc`, `id_categoria`) 
VALUES (1,3);

INSERT INTO `art_grupo_categoria`(`id_gc`, `id_categoria`) 
VALUES (1,4);


INSERT INTO `art_categoria`(`id_categoria`, `nombre`, `valor`, `descripcion`) 
VALUES (5,'Color',null,'Color del articulo.');

INSERT INTO `art_grupo_categoria`(`id_gc`, `id_categoria`) 
VALUES (1,5);

INSERT INTO `art_articulo`(`id_articulo`, `nombre`,`descripcion`) 
VALUES (0,'Pantalon', 'Pantalones convencionales');
