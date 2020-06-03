CREATE DATABASE blog;

USE blog;

SHOW TABLES;

CREATE TABLE posts(
    id integer AUTO_INCREMENT PRIMARY KEY,
    title varchar(255),
    content varchar(255)
);