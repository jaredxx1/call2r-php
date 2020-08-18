drop database call2r;

create database call2r;

use call2r;

-- Company
create table tb_sla (
    id_sla int auto_increment primary key,
    p1 int,
    p2 int,
    p3 int,
    p4 int,
    p5 int
);

create table tb_company (
    id_company int auto_increment primary key,
    active boolean,
    cnpj char(14),
    description varchar(255),
    mother boolean,
    name varchar(255),
    sla_id int,
    FOREIGN KEY (sla_id) REFERENCES tb_sla (id_sla),
    UNIQUE KEY (cnpj)
);

create table tb_section (
    id_section int auto_increment primary key,
    name varchar(255),
    UNIQUE KEY (name)
);

create table tb_company_section (
    id_company int,
    id_section int,
    FOREIGN KEY (id_company) REFERENCES tb_company (id_company),
    FOREIGN KEY (id_section) REFERENCES tb_section (id_section)
);

create table tb_user (
    id_user int auto_increment primary key,
    cpf varchar(11) null,
    name varchar(255) null,
    password varchar(255) null,
    email varchar(255) null,
    image varchar(255) null,
    role enum (
        'ROLE_SUPPORT',
        'ROLE_CLIENT',
        'ROLE_MANAGER_CLIENT',
        'ROLE_MANAGER_SUPPORT',
        'ROLE_ADMIN'
    ) null,
    birthdate datetime null,
    active tinyint(1) null,
    id_company int null,
    FOREIGN KEY (id_company) REFERENCES tb_company (id_company)
);

-- Article
create table tb_article (
    id_article int auto_increment primary key,
    id_company int,
    title varchar(255),
    description varchar(255),
    FOREIGN KEY (id_company) REFERENCES tb_company (id_company)
);

create table tb_category (
    id_category int auto_increment primary key,
    id_company int,
    title varchar(255),
    FOREIGN KEY (id_company) REFERENCES tb_company (id_company)
);

create table tb_article_category (
    id_article int,
    id_category int,
    FOREIGN KEY (id_article) REFERENCES tb_article (id_article),
    FOREIGN KEY (id_category) REFERENCES tb_category (id_category)
);

-- Request
create table tb_request_status (
    id_request_status int auto_increment primary key,
    name varchar(255) unique
);

insert into
    tb_request_status(name)
values
    ('Aguardando suporte'),
    ('Em atendimento'),
    ('Esperando usuário'),
    ('Aprovado'),
    ('Cancelado'),
    ('Finalizado');

create table tb_request (
    id_request int auto_increment primary key,
    id_status int,
    id_company int,
    requested_by int,
    assigned_to int,
    title varchar(255),
    created_at datetime default now() null,
    updated_at datetime default now() null,
    finished_at datetime,
    section varchar(255),
    priority int,
    description longtext,
    FOREIGN KEY (id_status) REFERENCES tb_request_status (id_request_status),
    FOREIGN KEY (id_company) REFERENCES tb_company (id_company),
    FOREIGN KEY (requested_by) REFERENCES tb_user (id_user),
    FOREIGN KEY (assigned_to) REFERENCES tb_user (id_user)
);

create table tb_request_log (
    id_log int auto_increment,
    message longtext null,
    created_at datetime default NOW() null,
    command varchar(255),
    constraint tb_request_log_pk primary key (id_log)
);

create table tb_requests_logs (
    log_id int not null,
    request_id int not null,
    constraint tb_requests_logs_log_pk primary key (log_id, request_id),
    constraint tb_requests_logs_tb_request_id_request_fk foreign key (request_id) references tb_request (id_request),
    constraint tb_requests_logs_tb_request_log_id_log_fk foreign key (log_id) references tb_request_log (id_log)
);

INSERT INTO
    call2r.tb_sla (id_sla, p1, p2, p3, p4, p5)
VALUES
    (1, 1, 1, 1, 1, 1);

INSERT INTO
    call2r.tb_sla (id_sla, p1, p2, p3, p4, p5)
VALUES
    (2, 1, 2, 3, 4, 5);

INSERT INTO
    call2r.tb_sla (id_sla, p1, p2, p3, p4, p5)
VALUES
    (3, 1, 2, 3, 4, 5);

INSERT INTO
    call2r.tb_company (
        id_company,
        active,
        cnpj,
        description,
        mother,
        name,
        sla_id
    )
VALUES
    (
        1,
        1,
        '72572151000125',
        'Mother compnay',
        1,
        'mother company',
        1
    );

INSERT INTO
    call2r.tb_company (
        id_company,
        active,
        cnpj,
        description,
        mother,
        name,
        sla_id
    )
VALUES
    (
        2,
        1,
        '3434567150222',
        'Support company 1',
        0,
        'Support company 1',
        2
    );

INSERT INTO
    call2r.tb_company (
        id_company,
        active,
        cnpj,
        description,
        mother,
        name,
        sla_id
    )
VALUES
    (
        3,
        1,
        '2434567150222',
        'Support company 2',
        0,
        'Support company 2',
        3
    );

INSERT INTO
    call2r.tb_section (id_section, name)
VALUES
    (1, 'section 1');

INSERT INTO
    call2r.tb_section (id_section, name)
VALUES
    (2, 'section 2');

INSERT INTO
    call2r.tb_section (id_section, name)
VALUES
    (3, 'section 3');

INSERT INTO
    call2r.tb_company_section (id_company, id_section)
VALUES
    (2, 1);

INSERT INTO
    call2r.tb_company_section (id_company, id_section)
VALUES
    (2, 2);

INSERT INTO
    call2r.tb_company_section (id_company, id_section)
VALUES
    (3, 3);

INSERT INTO
    call2r.tb_user (
        id_user,
        cpf,
        name,
        password,
        email,
        image,
        role,
        birthdate,
        active,
        id_company
    )
VALUES
    (
        1,
        '00000000000',
        'user admin',
        '$2y$10$55ajY9Zbr38aphiRvkAh6ulg2mUSnESwY79bGFTBl1pZ3NLdXaq9C',
        'user@email.com.br',
        null,
        'ROLE_ADMIN',
        '1998-12-21',
        1,
        1
    );

INSERT INTO
    call2r.tb_user (
        id_user,
        cpf,
        name,
        password,
        email,
        image,
        role,
        birthdate,
        active,
        id_company
    )
VALUES
    (
        2,
        '00000000001',
        'user client',
        '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG',
        'user1@email.com.br',
        null,
        'ROLE_CLIENT',
        '1999-09-24',
        1,
        1
    );

INSERT INTO
    call2r.tb_user (
        id_user,
        cpf,
        name,
        password,
        email,
        image,
        role,
        birthdate,
        active,
        id_company
    )
VALUES
    (
        3,
        '00000000002',
        'user manager client',
        '$2y$10$mFCdrS417lhZN7m9yWe/BeBFhEl2/EY6XNh6OWpoC4v735.4YKGLu',
        'user2@email.com.br',
        null,
        'ROLE_MANAGER_CLIENT',
        '1999-09-24',
        1,
        1
    );

INSERT INTO
    call2r.tb_user (
        id_user,
        cpf,
        name,
        password,
        email,
        image,
        role,
        birthdate,
        active,
        id_company
    )
VALUES
    (
        4,
        '00000000003',
        'user support',
        '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG',
        'user3@email.com.br',
        null,
        'ROLE_SUPPORT',
        '1999-09-24',
        1,
        2
    );

INSERT INTO
    call2r.tb_user (
        id_user,
        cpf,
        name,
        password,
        email,
        image,
        role,
        birthdate,
        active,
        id_company
    )
VALUES
    (
        5,
        '00000000004',
        'user manager support',
        '$2y$10$mFCdrS417lhZN7m9yWe/BeBFhEl2/EY6XNh6OWpoC4v735.4YKGLu',
        'user4@email.com.br',
        null,
        'ROLE_MANAGER_SUPPORT',
        '1999-09-24',
        1,
        2
    );

INSERT INTO
    call2r.tb_user (
        id_user,
        cpf,
        name,
        password,
        email,
        image,
        role,
        birthdate,
        active,
        id_company
    )
VALUES
    (
        6,
        '00000000005',
        'user support',
        '$2y$10$myscLaALeJIgJ251Sn9D.eU9CSNK5yooqeMMBmhxuuLVW46nc9AfG',
        'user5@email.com.br',
        null,
        'ROLE_SUPPORT',
        '1999-09-24',
        1,
        3
    );

INSERT INTO
    call2r.tb_user (
        id_user,
        cpf,
        name,
        password,
        email,
        image,
        role,
        birthdate,
        active,
        id_company
    )
VALUES
    (
        7,
        '00000000006',
        'user manager support',
        '$2y$10$mFCdrS417lhZN7m9yWe/BeBFhEl2/EY6XNh6OWpoC4v735.4YKGLu',
        'user6@email.com.br',
        null,
        'ROLE_MANAGER_SUPPORT',
        '1999-09-24',
        1,
        3
    );