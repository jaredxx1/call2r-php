create database call2r;

use call2r;

-- Company

create table tb_sla
(
    id_sla int auto_increment primary key,
    p1     int,
    p2     int,
    p3     int,
    p4     int,
    p5     int
);

create table tb_company
(
    id_company  int auto_increment primary key,
    active      boolean,
    cnpj        char(14),
    description varchar(255),
    mother      boolean,
    name        varchar(255),
    sla_id      int,
    FOREIGN KEY (sla_id) REFERENCES tb_sla (id_sla),
    UNIQUE KEY (cnpj)
);

create table tb_section
(
    id_section int auto_increment primary key,
    name       varchar(255),
    priority   int,
    UNIQUE KEY (name)
);

create table tb_company_section
(
    id_company int,
    id_section int,
    FOREIGN KEY (id_company) REFERENCES tb_company (id_company),
    FOREIGN KEY (id_section) REFERENCES tb_section (id_section)
);

create table tb_user
(
    id_user    int auto_increment primary key,
    cpf        varchar(11)                                      null,
    password   varchar(255)                                     null,
    email      varchar(255)                                     null,
    role       enum ('ROLE_USER', 'ROLE_ADMIN', 'ROLE_MANAGER') null,
    birthdate  date                                             null,
    active     tinyint(1)                                       null,
    id_company int                                              null,
    FOREIGN KEY (id_company) REFERENCES tb_company (id_company)
);

-- Article

create table tb_article(
    id_article int auto_increment primary key,
    id_company int,
    title varchar(255),
    description varchar(255),
    FOREIGN KEY (id_company) REFERENCES tb_company(id_company)
);

create table tb_category
(
    id_category int auto_increment primary key,
    id_company int,
    title       varchar(255),
    FOREIGN KEY (id_company) REFERENCES tb_company(id_company)
);

create table tb_article_category
(
    id_article int,
    id_category int,
    FOREIGN KEY (id_article) REFERENCES tb_article (id_article),
    FOREIGN KEY (id_category) REFERENCES tb_category (id_category)
);

create table tb_request_status (
    id_request_status int auto_increment primary key,
    name varchar(255) unique
);

create table tb_request
(
    id_request   int auto_increment primary key,
    id_status    int,
    id_company int,
    requested_by int,
    assigned_to  int,
    title        varchar(255),
    created_at   datetime default now() null,
    updated_at   datetime default now() null,
    finished_at  datetime,
    section      varchar(255),
    priority     int,
    description  longtext,
    FOREIGN KEY (id_status) REFERENCES tb_request_status (id_request_status),
    FOREIGN KEY (id_company) REFERENCES tb_company (id_company),
    FOREIGN KEY (requested_by) REFERENCES tb_user (id_user),
    FOREIGN KEY (assigned_to) REFERENCES tb_user (id_user)
);

create table tb_request_log
(
    id_log int auto_increment,
    message longtext null,
    created_at datetime default NOW() null,
    request_id int null,
    constraint tb_request_log_pk
        primary key (id_log),
    constraint tb_request_log_tb_request_id_request_fk
        foreign key (request_id) references tb_request (id_request)
);

insert into tb_request_status(name)
values ('Aguardando suporte'),
       ('Em atendimento'),
       ('Esperando usuário'),
       ('Aprovado'),
       ('Cancelado');