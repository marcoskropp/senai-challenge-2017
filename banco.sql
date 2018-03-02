create database challenge;
use challenge;
create table usuarios(
	id int not null primary key auto_increment,
    nome varchar(100),
    email varchar(100),
    senha varchar(100)
);

create table maps(
	id int not null primary key auto_increment,
	lat double,
    lng double
);