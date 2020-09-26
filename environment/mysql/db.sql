drop database call2r;

create database call2r;

use call2r;

create table tb_sla
(
    id_sla int auto_increment
        primary key,
    p1     int null,
    p2     int null,
    p3     int null,
    p4     int null,
    p5     int null
);

INSERT INTO call2r.tb_sla (p1, p2, p3, p4, p5) VALUES (1, 1, 1, 1, 1);
INSERT INTO call2r.tb_sla (p1, p2, p3, p4, p5) VALUES (2, 4, 8, 16, 32);
INSERT INTO call2r.tb_sla (p1, p2, p3, p4, p5) VALUES (3, 9, 27, 40, 50);

create table tb_company
(
    id_company  int auto_increment
        primary key,
    active      tinyint(1)   null,
    cnpj        char(14)     null,
    description longtext     null,
    mother      tinyint(1)   null,
    name        varchar(255) null,
    sla_id      int          null,
    constraint cnpj
        unique (cnpj),
    constraint tb_company_ibfk_1
        foreign key (sla_id) references tb_sla (id_sla)
);

create index sla_id
    on tb_company (sla_id);

INSERT INTO call2r.tb_company (active, cnpj, name, mother, description, sla_id) VALUES (1, '29958828000139', 'Empresa Belo', 1, 'Empresa mãe', 1);
INSERT INTO call2r.tb_company (active, cnpj, name, mother, description, sla_id) VALUES (1, '30831938000114', 'Empresa Excelente', 0, 'Empresa suporte', 2);
INSERT INTO call2r.tb_company (active, cnpj, name, mother, description, sla_id) VALUES (1, '69705663000108', 'Empresa Florescente', 0, 'Empresa suporte', 3);

create table tb_section
(
    id_section int auto_increment
        primary key,
    name       varchar(255) null,
    constraint name
        unique (name)
);

INSERT INTO call2r.tb_section (name) VALUES ('Falha de conexão');
INSERT INTO call2r.tb_section (name) VALUES ('Inconsistência de dados');
INSERT INTO call2r.tb_section (name) VALUES ('Vendas');

create table tb_company_section
(
    id_company int null,
    id_section int null,
    constraint tb_company_section_ibfk_1
        foreign key (id_company) references tb_company (id_company),
    constraint tb_company_section_ibfk_2
        foreign key (id_section) references tb_section (id_section)
);

create index id_company
    on tb_company_section (id_company);

create index id_section
    on tb_company_section (id_section);

INSERT INTO call2r.tb_company_section (id_company, id_section) VALUES (2, 1);
INSERT INTO call2r.tb_company_section (id_company, id_section) VALUES (2, 2);
INSERT INTO call2r.tb_company_section (id_company, id_section) VALUES (3, 3);

create table tb_category
(
    id_category int auto_increment
        primary key,
    id_company  int          null,
    title       varchar(255) null,
    constraint tb_category_ibfk_1
        foreign key (id_company) references tb_company (id_company)
);

create index id_company
    on tb_category (id_company);

INSERT INTO call2r.tb_category (id_company, title) VALUES (1, 'Banco de dados');
INSERT INTO call2r.tb_category (id_company, title) VALUES (2, 'Infraestrutura');
INSERT INTO call2r.tb_category (id_company, title) VALUES (3, 'Vendas');

create table tb_article
(
    id_article  int auto_increment
        primary key,
    id_company  int          null,
    title       varchar(255) null,
    description longtext     null,
    constraint tb_article_ibfk_1
        foreign key (id_company) references tb_company (id_company)
);

create index id_company
    on tb_article (id_company);

INSERT INTO call2r.tb_article (id_company, title, description) VALUES (2, 'Erro no banco de dados', 'In faucibus ex nisl, sed varius sapien ultrices eget. Curabitur porta, ipsum ac laoreet laoreet, nibh odio vehicula dui, vel maximus justo sem quis lectus. Proin porttitor porta quam, nec lacinia ligula eleifend ut. Vivamus sit amet auctor metus. Aliquam imperdiet posuere orci et auctor. Cras vitae interdum sem. Nullam nec magna ac metus luctus tempor sit amet a dolor. Duis eget magna tortor. Proin at massa euismod, congue mauris luctus, luctus augue. Nulla ut sagittis orci. Sed finibus purus non sem blandit blandit. Cras ullamcorper egestas velit, non ultricies ante sollicitudin vitae. Sed sed blandit urna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.');
INSERT INTO call2r.tb_article (id_company, title, description) VALUES (2, 'Erro de infraestrutura', 'Praesent volutpat turpis in tortor vulputate maximus. Nullam libero metus, vehicula vel nisi id, porta iaculis lectus. Donec in orci vel risus convallis tincidunt. Maecenas et nisl vel turpis commodo tincidunt. Duis hendrerit massa a ex vehicula egestas. Proin consequat nisi dui, elementum ultrices velit tristique in. In dignissim mollis eleifend. Praesent pellentesque eu purus non aliquam. Integer a finibus metus. Ut at convallis leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin dapibus congue justo eget pretium. Donec suscipit ligula varius ante venenatis pharetra. Sed in iaculis magna, quis imperdiet nisl. Nam a consectetur sem.');
INSERT INTO call2r.tb_article (id_company, title, description) VALUES (3, 'Erro de vendas', 'Ut dapibus aliquam lectus, non fermentum ex vestibulum eleifend. Sed sed blandit urna, eget tempor velit. Mauris ut sodales risus. Nulla fermentum hendrerit diam, sed venenatis urna mollis id. Suspendisse placerat, tortor ac pretium interdum, ante metus venenatis erat, ut aliquam ipsum turpis ut neque. Nulla lorem nibh, cursus tincidunt consectetur in, tincidunt et lorem. Integer quis accumsan ligula. Etiam non dapibus purus. Aenean lobortis diam ipsum, sed fermentum risus eleifend in. Suspendisse vel tincidunt tortor. Etiam porttitor dignissim lacus, nec pellentesque urna dignissim non. Donec sed purus vulputate, fringilla velit in, congue nunc.');

create table tb_article_category
(
    id_article  int null,
    id_category int null,
    constraint tb_article_category_ibfk_1
        foreign key (id_article) references tb_article (id_article),
    constraint tb_article_category_ibfk_2
        foreign key (id_category) references tb_category (id_category)
);

create index id_article
    on tb_article_category (id_article);

create index id_category
    on tb_article_category (id_category);

INSERT INTO call2r.tb_article_category (id_article, id_category) VALUES (1, 1);
INSERT INTO call2r.tb_article_category (id_article, id_category) VALUES (2, 2);
INSERT INTO call2r.tb_article_category (id_article, id_category) VALUES (3, 3);

create table tb_user
(
    id_user    int auto_increment
        primary key,
    cpf        varchar(11)                                                                                       null,
    name       varchar(255)                                                                                      null,
    password   varchar(255)                                                                                      null,
    email      varchar(255)                                                                                      null,
    image      varchar(255)                                                                                      null,
    role       enum ('ROLE_SUPPORT', 'ROLE_CLIENT', 'ROLE_MANAGER_CLIENT', 'ROLE_MANAGER_SUPPORT', 'ROLE_ADMIN') null,
    birthdate  datetime                                                                                          null,
    active     tinyint(1)                                                                                        null,
    id_company int                                                                                               null,
    constraint tb_user_ibfk_1
        foreign key (id_company) references tb_company (id_company)
);

create index id_company
    on tb_user (id_company);

INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('03792186403', 'Alessandro', '$2y$10$55ajY9Zbr38aphiRvkAh6ulg2mUSnESwY79bGFTBl1pZ3NLdXaq9C', 'invalid_email_for_user_1@email.com.br', null, 'ROLE_ADMIN', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('02147633313', 'Alexandre', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_2@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('33646266503', 'Dieval', '$2y$10$mFCdrS417lhZN7m9yWe/BeBFhEl2/EY6XNh6OWpoC4v735.4YKGLu', 'invalid_email_for_user_3@email.com.br', null, 'ROLE_MANAGER_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('42884245189', 'Jaime', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_4@email.com.br', null, 'ROLE_SUPPORT', '2000-01-01 00:00:00', 1, 2);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('64879657310', 'Jeroniza', '$2y$10$mFCdrS417lhZN7m9yWe/BeBFhEl2/EY6XNh6OWpoC4v735.4YKGLu', 'invalid_email_for_user_5@email.com.br', null, 'ROLE_MANAGER_SUPPORT', '2000-01-01 00:00:00', 1, 2);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('70776461818', 'João', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_6@email.com.br', null, 'ROLE_SUPPORT', '2000-01-01 00:00:00', 1, 3);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('13823085239', 'Luiz', '$2y$10$mFCdrS417lhZN7m9yWe/BeBFhEl2/EY6XNh6OWpoC4v735.4YKGLu', 'invalid_email_for_user_7@email.com.br', null, 'ROLE_MANAGER_SUPPORT', '2000-01-01 00:00:00', 1, 3);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('53041544016', 'Mario', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_8@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('19334819006', 'Rafael', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_9@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('01599462044', 'Razer', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_10@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('46777890025', 'Roberto', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_11@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('64112540019', 'Sandramara', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_12@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('65232927035', 'Rafaela', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_13@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('97394372065', 'Mauro', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_14@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('68266886032', 'Pedro', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_15@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);
INSERT INTO call2r.tb_user (cpf, name, password, email, image, role, birthdate, active, id_company) VALUES ('46114325052', 'Andreia', '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG', 'invalid_email_for_user_16@email.com.br', null, 'ROLE_CLIENT', '2000-01-01 00:00:00', 1, 1);

create table tb_request_status
(
    id_request_status int auto_increment
        primary key,
    name              varchar(255) null,
    constraint name
        unique (name)
);

INSERT INTO call2r.tb_request_status (name) VALUES ('Aguardando suporte');
INSERT INTO call2r.tb_request_status (name) VALUES ('Aprovado');
INSERT INTO call2r.tb_request_status (name) VALUES ('Cancelado');
INSERT INTO call2r.tb_request_status (name) VALUES ('Em atendimento');
INSERT INTO call2r.tb_request_status (name) VALUES ('Esperando usuário');
INSERT INTO call2r.tb_request_status (name) VALUES ('Finalizado');

create table tb_request
(
    id_request   int auto_increment
        primary key,
    id_status    int                                null,
    id_company   int                                null,
    requested_by int                                null,
    assigned_to  int                                null,
    title        varchar(255)                       null,
    created_at   datetime default CURRENT_TIMESTAMP null,
    updated_at   datetime default CURRENT_TIMESTAMP null,
    finished_at  datetime                           null,
    section      varchar(255)                       null,
    priority     int                                null,
    description  longtext                           null,
    constraint tb_request_ibfk_1
        foreign key (id_status) references tb_request_status (id_request_status),
    constraint tb_request_ibfk_2
        foreign key (id_company) references tb_company (id_company),
    constraint tb_request_ibfk_3
        foreign key (requested_by) references tb_user (id_user),
    constraint tb_request_ibfk_4
        foreign key (assigned_to) references tb_user (id_user)
);

create index assigned_to
    on tb_request (assigned_to);

create index id_company
    on tb_request (id_company);

create index id_status
    on tb_request (id_status);

create index requested_by
    on tb_request (requested_by);

INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 3, null, 'Problema de conexão', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Falha de conexão', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 2, null, 'Problema de conexão', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Falha de conexão', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 3, null, 'Problema de conexão', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Falha de conexão', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 2, null, 'Problema de conexão', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Falha de conexão', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 3, null, 'Problema de conexão', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Falha de conexão', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 2, null, 'Problema de conexão', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Falha de conexão', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 3, null, 'Problema de inconsistências de dados', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Inconsistência de dados', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 2, null, 'Problema de inconsistências de dados', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Inconsistência de dados', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 3, null, 'Problema de inconsistências de dados', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Inconsistência de dados', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 2, 2, null, 'Problema de inconsistências de dados', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Inconsistência de dados', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 3, 3, null, 'Problema de vendas', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Vendas', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 3, 2, null, 'Problema de vendas', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Vendas', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 3, 3, null, 'Problema de vendas', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Vendas', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 3, 2, null, 'Problema de vendas', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Vendas', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 3, 3, null, 'Problema de vendas', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Vendas', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 3, 2, null, 'Problema de vendas', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Vendas', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');
INSERT INTO call2r.tb_request (id_status, id_company, requested_by, assigned_to, title, created_at, updated_at, finished_at, section, priority, description) VALUES (1, 3, 3, null, 'Problema de vendas', (now() -  INTERVAL 3 HOUR ), (now() -  INTERVAL 3 HOUR ), null, 'Vendas', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin erat eget aliquet sollicitudin. Sed elementum luctus ex eget laoreet. Praesent non consequat urna, vitae congue nulla. Donec vestibulum lobortis leo, id accumsan justo gravida id. Vivamus dictum eleifend odio eu luctus. Curabitur imperdiet dolor a nisl venenatis, eleifend iaculis magna scelerisque. Proin faucibus volutpat fringilla. Nulla eleifend, orci ac commodo porttitor, eros justo gravida tortor, ut egestas eros dui nec nibh. Phasellus turpis arcu, consequat non congue vitae, condimentum sed leo. Pellentesque ac ipsum nunc.');

create table tb_request_log
(
    id_log     int auto_increment
        primary key,
    message    longtext                           null,
    created_at datetime default CURRENT_TIMESTAMP null,
    command    varchar(255)                       null
);
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('O chamado foi criado <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'init');
INSERT INTO call2r.tb_request_log (message, created_at, command) VALUES ('Chamado esta em aguardando atendimento <br><br> Por : Dieval <br> Trabalha em: Empresa mãe', (now() -  INTERVAL 3 HOUR ), 'awaitingSupport');

create table tb_requests_logs
(
    log_id     int not null,
    request_id int not null,
    primary key (log_id, request_id),
    constraint tb_requests_logs_tb_request_id_request_fk
        foreign key (request_id) references tb_request (id_request),
    constraint tb_requests_logs_tb_request_log_id_log_fk
        foreign key (log_id) references tb_request_log (id_log)
);

INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (1, 1);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (2, 1);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (3, 2);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (4, 2);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (5, 3);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (6, 3);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (7, 4);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (8, 4);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (9, 5);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (10, 5);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (11, 6);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (12, 6);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (13, 7);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (14, 7);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (15, 8);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (16, 8);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (17, 9);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (18, 9);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (19, 10);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (20, 10);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (21, 11);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (22, 11);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (23, 12);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (24, 12);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (25, 13);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (26, 13);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (27, 14);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (28, 14);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (29, 15);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (30, 15);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (31, 16);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (32, 16);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (33, 17);
INSERT INTO call2r.tb_requests_logs (log_id, request_id) VALUES (34, 17);
