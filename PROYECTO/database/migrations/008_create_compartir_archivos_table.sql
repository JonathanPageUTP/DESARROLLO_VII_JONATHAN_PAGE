CREATE TABLE compartir_archivos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    archivo_id INT NOT NULL,
    usuario_propietario_id INT NOT NULL,
    usuario_compartido_id INT NULL,
    permiso VARCHAR(20) DEFAULT 'lectura',
    enlace_publico VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (archivo_id) REFERENCES archivos(id),
    FOREIGN KEY (usuario_propietario_id) REFERENCES usuarios(id),
    FOREIGN KEY (usuario_compartido_id) REFERENCES usuarios(id)
);