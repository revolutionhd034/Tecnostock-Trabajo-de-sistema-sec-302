DROP DATABASE IF EXISTS tecnostock;

CREATE DATABASE tecnostock
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE tecnostock;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    estado TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 0,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    id_categoria INT NOT NULL,

    FOREIGN KEY (id_categoria)
        REFERENCES categorias(id_categoria)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE movimientos (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tipo ENUM('ENTRADA', 'SALIDA') NOT NULL,
    cantidad INT NOT NULL,
    observacion VARCHAR(255),
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,

    FOREIGN KEY (id_producto)
        REFERENCES productos(id_producto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO categorias (nombre, descripcion) VALUES
    ('Periféricos', 'Mouse, teclados y otros periféricos'),
    ('Almacenamiento', 'Dispositivos de almacenamiento'),
    ('Audio', 'Audífonos, parlantes y otros dispositivos de audio');

INSERT INTO usuarios (nombre, correo, contraseña, estado) VALUES
    (
        'Encargado Inventario',
        'encargado@tecnostock.cl',
        '123456',
        1
    );

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P001',
    'Mouse inalámbrico',
    'Producto de prueba para TecnoStock',
    9990,
    2,
    5,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Periféricos'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P002',
    'Teclado mecánico',
    'Teclado mecánico USB',
    29990,
    10,
    4,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Periféricos'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P003',
    'Mouse gamer',
    'Mouse gamer con conexión USB',
    15990,
    8,
    3,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Periféricos'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P004',
    'Pendrive 64GB',
    'Pendrive USB de 64GB',
    8990,
    15,
    5,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Almacenamiento'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P005',
    'Disco SSD 500GB',
    'Unidad de almacenamiento SSD de 500GB',
    39990,
    3,
    5,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Almacenamiento'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P006',
    'Disco externo 1TB',
    'Disco duro externo de 1TB',
    54990,
    7,
    3,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Almacenamiento'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P007',
    'Audífonos Bluetooth',
    'Audífonos inalámbricos Bluetooth',
    19990,
    12,
    4,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Audio'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P008',
    'Parlante Bluetooth',
    'Parlante portátil con conexión Bluetooth',
    24990,
    2,
    4,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Audio'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P009',
    'Audífonos Gamer',
    'Audífonos gamer con micrófono',
    27990,
    6,
    3,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Audio'
LIMIT 1;

INSERT INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, estado, id_categoria)
SELECT
    'P010',
    'Teclado inalámbrico',
    'Teclado inalámbrico para computador',
    18990,
    3,
    5,
    1,
    id_categoria
FROM categorias
WHERE nombre = 'Periféricos'
LIMIT 1;

INSERT INTO movimientos
    (tipo, cantidad, observacion, id_producto, id_usuario)
SELECT
    'ENTRADA',
    2,
    'Carga inicial de prueba',
    p.id_producto,
    u.id_usuario
FROM productos p
JOIN usuarios u
    ON u.correo = 'encargado@tecnostock.cl'
WHERE p.codigo = 'P001'
LIMIT 1;