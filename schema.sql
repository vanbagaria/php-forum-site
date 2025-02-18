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

CREATE TABLE forum_threads (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Unique identifier for each thread
    topic_id INT NOT NULL,                       -- Foreign key to link to the forum_topics table
    user_id INT NOT NULL,                        -- ID of the user who created the thread
    title VARCHAR(255) NOT NULL,                 -- Title of the thread
    content TEXT NOT NULL,                        -- Initial content of the thread
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp for when the thread was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Timestamp for when the thread was last updated
    FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE, -- Foreign key constraint
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Assuming you have a users table
);

INSERT INTO forum_threads (topic_id, user_id, title, content) VALUES
(1, 1, 'First Thread Title', 'This is the initial message of the thread.');


