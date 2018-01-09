ALTER TABLE gs_gastos MODIFY id_ggs INTEGER NULL;
ALTER TABLE lc_caja add sobrante INTEGER  NULL;
ALTER TABLE lc_caja MODIFY id_cj_ggs INTEGER NULL;

ALTER TABLE art_lote ADD id_moneda INTEGER  NULL;
ALTER TABLE art_lote ADD FOREIGN KEY (id_moneda) REFERENCES art_moneda(id_moneda) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE art_moneda MODIFY valor DEC(15,2) NOT NULL;

ALTER TABLE us_sueldos add aguinaldo BOOLEAN  NOT NULL;
ALTER TABLE us_sl_liquidacion add fecha_hasta DATETIME  NOT NULL;