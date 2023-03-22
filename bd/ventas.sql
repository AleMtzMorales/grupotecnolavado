CREATE SCHEMA `ventas` DEFAULT CHARACTER SET utf8mb4 ;

use ventas;

create table usuarios(
				id_usuario int auto_increment,
				nombre varchar(50),
				apellido varchar(50),
				email varchar(50),
				password text(50),
				fechaCaptura date,
				primary key(id_usuario)
					);

create table categorias (
				id_categoria int auto_increment,
				id_usuario int not null,
				nombreCategoria varchar(150),
				fechaCaptura date,
				primary key(id_categoria)
						);

create table imagenes(
				id_imagen int auto_increment,
				id_categoria int not null,
				nombre varchar(500),
				ruta varchar(500),
				fechaSubida date,
				primary key(id_imagen)
					);
create table articulos(
				id_producto int auto_increment,
				id_categoria int not null,
				id_imagen int not null,
				id_usuario int not null,
				nombre varchar(50),
				descripcion varchar(500),
				cantidad float,
				precio float,
				fechaCaptura date,
				primary key(id_producto)

						);

create table clientes(
				id_cliente int auto_increment,
				id_usuario int not null,
				nombre varchar(200),
				apellido varchar(200),
				direccion varchar(200),
				observaciones varchar(200),
				telefono varchar(200),
				primary key(id_cliente)
					);
-- Recuerda agregar el id de usuario por favor 
create table ventas(
				id_venta int not null,
				id_cliente int,
				id_producto int,
				id_usuario int,
				precio float,
				cantidad float,
				fechaCompra DATETIME, 
				estatus int
					);

-- Valores para el campo estatus del la tabla ventas
--	0 -> Pendiente, paid
--  1 -> Pagado,  pending
--  2 -> Liquidado,


-- Tabla de registros de venta con anticipo 
create table anticipos(
				id_anticipo int not null,
				id_cliente int,
				id_venta int,
				anticipo float,
				fechaAnticipo DATETIME,
				primary key(id_anticipo)
					);

					

-- Tabla de registros de gastos
create table egresos(
				id_egreso int auto_increment,
				id_usuario int not null,
				totalEgreso float,
				descripcion varchar(200),
				fechaEgreso DATETIME,
				primary key(id_egreso)
					);