CREATE TABLE archivos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    carpeta_id INT NULL,
    nombre VARCHAR(255) NOT NULL,
    tamano BIGINT NOT NULL,
    ruta_archivo TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (carpeta_id) REFERENCES carpetas(id)
);