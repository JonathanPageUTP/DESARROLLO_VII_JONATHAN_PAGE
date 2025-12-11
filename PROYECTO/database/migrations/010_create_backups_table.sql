CREATE TABLE backups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    estado VARCHAR(20) DEFAULT 'en_progreso',
    tamano_total BIGINT DEFAULT 0,
    ruta_backup TEXT NOT NULL,
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_fin TIMESTAMP NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);