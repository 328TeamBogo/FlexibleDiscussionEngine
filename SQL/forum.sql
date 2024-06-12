-- Forum database tables.

-- Create the users table
CREATE TABLE users (

    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email    VARCHAR(255) NOT NULL,
    status   INT DEFAULT 0

);

-- Create the discussions table
CREATE TABLE discussions (

    id         INT AUTO_INCREMENT PRIMARY KEY,
    topic      VARCHAR(255) NOT NULL,
    title      VARCHAR(255) NOT NULL,
    user_id    INT,
    status     INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id)

);

-- Create the posts table
CREATE TABLE posts (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    discussion_id INT,
    user_id       INT,
    message       TEXT NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status        INT DEFAULT 1,
    FOREIGN KEY (discussion_id) REFERENCES discussions (id),
    FOREIGN KEY (user_id) REFERENCES users (id)

);