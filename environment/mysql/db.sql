create database call2r;

use call2r;

create table tb_company(
        id_company int auto_increment primary key ,
        active boolean,
        cnpj char(14),
        description varchar(255),
        mother boolean,
        name varchar(255),
        UNIQUE KEY (cnpj)
);