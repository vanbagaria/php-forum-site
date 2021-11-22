create database forum_db;

create table users(
    id int primary key auto_increment,
    username varchar(255) not null unique,
    password varchar(255) not null,
    email varchar(255) not null unique,
    registerdate timestamp default current_timestamp
);

