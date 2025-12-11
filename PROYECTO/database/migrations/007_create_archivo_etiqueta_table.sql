CREATE TABLE archivo_etiqueta (
    archivo_id INT NOT NULL,
    etiqueta_id INT NOT NULL,
    PRIMARY KEY (archivo_id, etiqueta_id),
    FOREIGN KEY (archivo_id) REFERENCES archivos(id),
    FOREIGN KEY (etiqueta_id) REFERENCES etiquetas(id)
);