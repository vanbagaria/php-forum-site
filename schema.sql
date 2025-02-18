create database forum_db;

create table users(
    id int primary key auto_increment,
    username varchar(255) not null unique,
    password varchar(255) not null,
    email varchar(255) not null unique,
    registerdate timestamp default current_timestamp
);

CREATE TABLE forum_topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_name VARCHAR(255) NOT NULL
);

INSERT INTO forum_topics (topic_name) VALUES
('General'),
('Games'),
('Music'),
('Animation'),
('Game Development'),
('Programming'),
('Feedback and Suggestions');

