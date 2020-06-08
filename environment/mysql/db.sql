create database call2r;

use call2r;

create table tb_sla(
    id_sla int auto_increment primary key ,
    p1 int,
    p2 int,
    p3 int,
    p4 int,
    p5 int
);

create table tb_company(
    id_company int auto_increment primary key ,
    active boolean,
    cnpj char(14),
    description varchar(255),
    mother boolean,
    name varchar(255),
    sla_id int,
    FOREIGN KEY(sla_id) REFERENCES tb_sla(id_sla),
    UNIQUE KEY (cnpj)
);

create table  tb_section(
    id_section int auto_increment primary key,
    name varchar(255),
    priority int
);

create table tb_company_section(
    id_company int,
    id_section int,
    FOREIGN KEY (id_company) REFERENCES  tb_company(id_company),
    FOREIGN KEY (id_section) REFERENCES  tb_section(id_section),
    PRIMARY KEY (id_company, id_section)
);