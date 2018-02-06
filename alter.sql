ALTER TABLE gs_gastos MODIFY id_ggs INTEGER NULL;
ALTER TABLE lc_caja add sobrante INTEGER  NULL;
ALTER TABLE lc_caja MODIFY id_cj_ggs INTEGER NULL;

ALTER TABLE art_lote ADD id_moneda INTEGER  NULL;
ALTER TABLE art_lote ADD FOREIGN KEY (id_moneda) REFERENCES art_moneda(id_moneda) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE art_moneda MODIFY valor DEC(15,2) NOT NULL;

ALTER TABLE us_sueldos add aguinaldo BOOLEAN  NOT NULL;
ALTER TABLE us_sl_liquidacion add fecha_hasta DATETIME  NOT NULL;


///24/01/2018

DROP TABLE IF EXISTS art_unico, art_venta;

CREATE TABLE  art_gmedio_pago (
     id_gmedio_pago INTEGER AUTO_INCREMENT NOT NULL,
     id_medio_pago INTEGER NOT NULL,
     rg_detalle VARCHAR(100) NOT NULL,
     FOREIGN KEY (id_medio_pago) REFERENCES art_venta_medio_pago(id_medio_pago) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_gmedio_pago)
     ) ENGINE=InnoDB;

CREATE TABLE  art_venta (
     id_venta INTEGER AUTO_INCREMENT NOT NULL,
     fecha_hora DATETIME NOT NULL,
     id_usuario INTEGER NOT NULL,
     id_promo INTEGER,
     id_gmedio_pago INTEGER NOT NULL,
     total VARCHAR(100) NOT NULL,
     cuotas VARCHAR(100),
     id_cambio INTEGER,
     FOREIGN KEY (id_gmedio_pago) REFERENCES art_gmedio_pago(id_gmedio_pago) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_promo) REFERENCES art_venta_promo(id_promo) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_cambio) REFERENCES art_venta(id_venta) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_venta)
     ) ENGINE=InnoDB;

CREATE TABLE  art_gunico (
     id_gunico INTEGER AUTO_INCREMENT NOT NULL,
     id_lote_local INTEGER NOT NULL,
     rg_detalle VARCHAR(150) NOT NULL,
     FOREIGN KEY (id_lote_local) REFERENCES art_lote_local(id_lote_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_gunico)
     ) ENGINE=InnoDB;

CREATE TABLE  art_unico (
     id_unico INTEGER AUTO_INCREMENT NOT NULL,
     id_gunico INTEGER NOT NULL,
     id_venta INTEGER NOT NULL,
     FOREIGN KEY (id_gunico) REFERENCES art_gunico(id_gunico) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_venta) REFERENCES art_venta(id_venta) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_unico)
     ) ENGINE=InnoDB;

ALTER TABLE art_unico CHANGE id_lote_local ig_gunico INTEGER NOT NULL;
ALTER TABLE art_unico ADD FOREIGN KEY (ig_gunico) REFERENCES art_gunico(ig_gunico) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE art_venta CHANGE id_medio_pago id_gmedio_pago INTEGER NOT NULL;
ALTER TABLE art_venta ADD FOREIGN KEY (id_gmedio_pago) REFERENCES art_gmedio_pago(id_gmedio_pago) ON DELETE NO ACTION ON UPDATE CASCADE;

//25/01/2018
ALTER TABLE art_gunico add rg_detalle VARCHAR(150) NOT NULL;
ALTER TABLE art_gmedio_pago add rg_detalle VARCHAR(150) NOT NULL;

//30/01/2018
ALTER TABLE art_gunico add cantidad INTEGER  NOT NULL;

//31/01/2018
AGREGADO EN CASSARO CHOPP
ALTER TABLE us_sueldos add aguinaldo BOOLEAN  NOT NULL;
ALTER TABLE us_sl_liquidacion add fecha_hasta DATETIME  NOT NULL;

//01/0/2018
AGREGADO EN MOTOMATCH

DROP TABLE IF EXISTS art_unico, art_venta;

CREATE TABLE  art_gmedio_pago (
     id_gmedio_pago INTEGER AUTO_INCREMENT NOT NULL,
     id_medio_pago INTEGER NOT NULL,
     rg_detalle VARCHAR(100) NOT NULL,
     FOREIGN KEY (id_medio_pago) REFERENCES art_venta_medio_pago(id_medio_pago) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_gmedio_pago)
     ) ENGINE=InnoDB;

CREATE TABLE  art_venta (
     id_venta INTEGER AUTO_INCREMENT NOT NULL,
     fecha_hora DATETIME NOT NULL,
     id_usuario INTEGER NOT NULL,
     id_promo INTEGER,
     id_gmedio_pago INTEGER NOT NULL,
     total VARCHAR(100) NOT NULL,
     cuotas VARCHAR(100),
     id_cambio INTEGER,
     FOREIGN KEY (id_gmedio_pago) REFERENCES art_gmedio_pago(id_gmedio_pago) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuarios) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_promo) REFERENCES art_venta_promo(id_promo) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_cambio) REFERENCES art_venta(id_venta) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_venta)
     ) ENGINE=InnoDB;

CREATE TABLE  art_gunico (
     id_gunico INTEGER AUTO_INCREMENT NOT NULL,
     id_lote_local INTEGER NOT NULL,
     rg_detalle VARCHAR(150) NOT NULL,
     cantidad INTEGER NOT NULL,
     FOREIGN KEY (id_lote_local) REFERENCES art_lote_local(id_lote_local) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_gunico)
     ) ENGINE=InnoDB;

CREATE TABLE  art_unico (
     id_unico INTEGER AUTO_INCREMENT NOT NULL,
     id_gunico INTEGER NOT NULL,
     id_venta INTEGER NOT NULL,
     FOREIGN KEY (id_gunico) REFERENCES art_gunico(id_gunico) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_venta) REFERENCES art_venta(id_venta) ON DELETE NO ACTION ON UPDATE CASCADE,
     KEY (id_unico)
     ) ENGINE=InnoDB;

//Agregado MOTOMATCH 

ALTER TABLE art_venta add rg_detalle VARCHAR(50) NULL;
