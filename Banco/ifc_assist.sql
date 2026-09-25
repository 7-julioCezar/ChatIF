create database ifc_assist;
use ifc_assist;

create table usuario(
id_usuario int primary key auto_increment,
nome VARCHAR(60),
data_cadastro date
);
alter table usuario
ADD email varchar(100);

alter table usuario 
add idade date;

alter table usuario 
modify idade int;

alter table usuario
drop column data_cadastro;

alter table usuario
modify email varchar(100) unique;
alter table usuario
drop column email;

 
create table login(
id_login int primary key auto_increment,
id_usuario int,
email varchar(100) unique,
senha varchar(25),
foreign key (id_usuario) references usuario(id_usuario)
);

create table categoria(
id_categoria int primary key auto_increment,
nome_categoria varchar(60),
descricao text);


create table pergunta(
id_pergunta int primary key auto_increment,
pergunta text,
resposta text,
palavras_chave varchar(255),
id_categoria int,
criado_por varchar(100),
data_criacao date
);

