create database agenda;

use agenda;

create TABLE grupo (
    id       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome     VARCHAR(50)  NOT NULL
);

 create table contato(
     id int not null auto_increment primary key,
     idGrupo int not null,
     nome varchar(50),
     telefone varchar(20)
);

ALTER TABLE contato 
ADD CONSTRAINT fk_contato_grupo 
FOREIGN KEY (idGrupo) 
REFERENCES grupo(id) 
--ON DELETE RESTRICT 
--ON UPDATE CASCADE
;

--###################################################################################
show tables;
insert into grupo (nome) values('família');
insert into grupo (nome) values('trabalho'),('amigos');

insert into contato (idGrupo,nome,telefone) values(1,'Paulo','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(1,'Pedro','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(1,'Thiago','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(1,'Judas','(45) 98765-4321');

insert into contato (idGrupo,nome,telefone) values(2,'Neymar','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(2,'Pelé','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(2,'Cristiano Ronaldo','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(2,'Messi','(45) 98765-4321');

insert into contato (idGrupo,nome,telefone) values(3,'Danilo Gentilli','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(3,'Thais Carla','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(3,'Willian Boner','(45) 98765-4321');
insert into contato (idGrupo,nome,telefone) values(3,'Fatima Bernardes','(45) 98765-4321');

select c.id,c.nome,c.telefone,g.nome as grupo
from contato c
left join grupo g on(c.idGrupo=g.id);

update grupo set
nome='Colegas de Trabalho'
where (id=2);