--
-- Use a specific schema and set it as default - thingy.
--
DROP SCHEMA IF EXISTS thingy CASCADE;
CREATE SCHEMA IF NOT EXISTS thingy;
SET search_path TO thingy;

--
-- Drop any existing tables.
--
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;

--
-- Create tables.
--
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR
);

CREATE TABLE cards (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  user_id INTEGER REFERENCES users NOT NULL
);

-- Table: posts
CREATE TABLE posts (
    id SERIAL NOT NULL,
    creation_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edit_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edited BOOLEAN NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: questions
CREATE TABLE questions (
    post_id INTEGER NOT NULL,
    title VARCHAR NOT NULL,
    body VARCHAR NOT NULL, 
    score INTEGER DEFAULT (0) NOT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: answers
CREATE TABLE answers (
    answered_question INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    title VARCHAR NOT NULL, 
    body VARCHAR NOT NULL, 
    correct BOOLEAN NOT NULL, 
    score INTEGER DEFAULT (0) NOT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (answered_question) REFERENCES questions(post_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE items (
  id SERIAL PRIMARY KEY,
  card_id INTEGER NOT NULL REFERENCES cards ON DELETE CASCADE,
  description VARCHAR NOT NULL,
  done BOOLEAN NOT NULL DEFAULT FALSE
);

--
-- Insert value.
--

INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'admin@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO cards VALUES (DEFAULT, 'Things to do', 1);
INSERT INTO items VALUES (DEFAULT, 1, 'Buy milk');
INSERT INTO items VALUES (DEFAULT, 1, 'Walk the dog', true);

INSERT INTO cards VALUES (DEFAULT, 'Things not to do', 1);
INSERT INTO items VALUES (DEFAULT, 2, 'Break a leg');
INSERT INTO items VALUES (DEFAULT, 2, 'Crash the car');

-- 16 Posts
INSERT INTO posts (creation_date, edit_date, edited, user_id)
VALUES
    ('2023-10-15', '2025-01-01 00:00:00', TRUE, 5),
    ('2023-11-20', '2025-01-01 00:00:00', FALSE, 3),
    ('2024-01-25', '2025-01-01 00:00:00', TRUE, 8),
    ('2024-02-10', '2025-01-01 00:00:00', TRUE, 2),
    ('2023-12-30', '2025-01-01 00:00:00', FALSE, 6),
    ('2024-03-15', '2025-01-01 00:00:00', TRUE, 9),
    ('2023-10-20', '2025-01-01 00:00:00', FALSE, 1),
    ('2023-11-25', '2025-01-01 00:00:00', TRUE, 4),
    ('2024-04-27', '2025-01-01 00:00:00', TRUE, 7),
    ('2023-11-10', '2025-01-01 00:00:00', FALSE, 10),
    ('2024-09-05', '2025-01-01 00:00:00', TRUE, 6),
    ('2024-06-12', '2025-01-01 00:00:00', FALSE, 3),
    ('2024-05-18', '2025-01-01 00:00:00', TRUE, 2),
    ('2024-07-07', '2025-01-01 00:00:00', TRUE, 8),
    ('2024-08-14', '2025-01-01 00:00:00', FALSE, 4),
    ('2024-09-22', '2025-01-01 00:00:00', TRUE, 7);

-- 6 Questions
INSERT INTO questions (title, body, score, post_id)
VALUES
    ('How to optimize database performance?', 'I am looking for tips and strategies to improve the performance of my database system. What are the best practices to follow?', 5, 1),
    ('How to learn a new programming language?', 'I want to learn a new programming language, but I''m not sure where to start. Any recommendations or resources?', -2, 2),
    ('What are the benefits of regular exercise?', 'I''m considering starting a regular exercise routine. Can someone explain the health benefits and how to get started?', 3, 3),
    ('How to prepare for a job interview?', 'I have an upcoming job interview, and I''m nervous. Any advice on how to prepare and perform well in the interview?', 7, 4),
    ('What is the impact of climate change on ecosystems?', 'I''m interested in understanding how climate change is affecting ecosystems worldwide. Can someone provide insights or studies on this?', -5, 5),
    ('How to create a budget for personal finance?', 'I want to manage my finances better and create a budget. Any tips on how to get started and stick to a budget plan?', 1, 6);

-- 6 Answers
INSERT INTO answers (title, body, correct, score, answered_question, post_id)
VALUES
    ('Answer for How to optimize database performance?', 'You can optimize database performance by following these steps...', TRUE, 8, 1, 7),
    ('Answer for How to learn a new programming language?', 'Learning a new programming language can be a rewarding experience...', FALSE, 2, 2, 8),
    ('Answer for What are the benefits of regular exercise?', 'Regular exercise has numerous benefits, including improved physical health and reduced stress...', TRUE, 5, 3, 9),
    ('Answer for How to prepare for a job interview?', 'To prepare for a job interview, you should research the company...', TRUE, 6, 4, 10),
    ('Answer for What is the impact of climate change on ecosystems?', 'Climate change is causing significant shifts in ecosystems...', FALSE, -3, 5, 11),
    ('Alternative Answer for What is the impact of climate change on ecosystems?', 'The impact of climate change on ecosystems is a critical concern...', TRUE, 7, 5, 12);
