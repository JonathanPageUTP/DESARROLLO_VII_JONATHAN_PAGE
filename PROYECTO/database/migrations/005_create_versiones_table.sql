CREATE TABLE versiones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    archivo_id INT NOT NULL,
    numero_version INT NOT NULL,
    tamano BIGINT NOT NULL,
    ruta_archivo TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (archivo_id) REFERENCES archivos(id)
);