--
-- Use a specific schema and set it as default - thingy.
--
DROP SCHEMA IF EXISTS lbaw2392 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2392;
SET search_path TO lbaw2392;

--
-- Drop any existing tables.
--
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;
DROP TABLE IF EXISTS questions CASCADE;
DROP TABLE IF EXISTS answers CASCADE;

--
-- Create tables.
--
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  username VARCHAR UNIQUE,
  bio VARCHAR,
  birth_date DATE,
  nationality VARCHAR,
  user_type CHAR(1) NOT NULL DEFAULT '1',
  remember_token VARCHAR
);

CREATE TABLE cards (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  user_id INTEGER REFERENCES users NOT NULL
);

CREATE TABLE items (
  id SERIAL PRIMARY KEY,
  card_id INTEGER NOT NULL REFERENCES cards ON DELETE CASCADE,
  description VARCHAR NOT NULL,
  done BOOLEAN NOT NULL DEFAULT FALSE
);

-- Table: questions
CREATE TABLE questions (
    id SERIAL PRIMARY KEY,
    title VARCHAR NOT NULL,
    content VARCHAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Table: settings
DROP TABLE IF EXISTS settings;
CREATE TABLE IF NOT EXISTS settings (
    id SERIAL,
    dark_mode BOOLEAN NOT NULL, 
    hide_nation BOOLEAN NOT NULL, 
    hide_birth_date BOOLEAN NOT NULL, 
    hide_email BOOLEAN NOT NULL, 
    hide_name BOOLEAN NOT NULL,
    language BOOLEAN NOT NULL,
    PRIMARY KEY (id)
);

-- Table: users
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    id SERIAL NOT NULL,
    fullname VARCHAR NOT NULL,
    username VARCHAR UNIQUE NOT NULL,
    user_password VARCHAR NOT NULL,
    email VARCHAR UNIQUE NOT NULL,
    bio VARCHAR,
    birth_date DATE,
    nationality VARCHAR,
    user_type CHAR NOT NULL,
    remember_token VARCHAR
);

-- Table: questions
DROP TABLE IF EXISTS questions;
CREATE TABLE IF NOT EXISTS questions (
    id SERIAL PRIMARY KEY,
    title VARCHAR NOT NULL,
    content VARCHAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    score INTEGER DEFAULT (0) NOT NULL,
    edited BOOLEAN DEFAULT (0) NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: answers
DROP TABLE IF EXISTS answers;
CREATE TABLE IF NOT EXISTS answers (
    answered_question INTEGER NOT NULL,
    id SERIAL NOT NULL,
    title VARCHAR NOT NULL, 
    body VARCHAR NOT NULL, 
    correct BOOLEAN NOT NULL, 
    score INTEGER DEFAULT (0) NOT NULL,
    creation_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edit_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edited BOOLEAN NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (answered_question) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: comments_question
DROP TABLE IF EXISTS comments_questions;
CREATE TABLE IF NOT EXISTS comments_questions (
    id SERIAL NOT NULL,
    referred_question_id INTEGER NOT NULL,
    body VARCHAR NOT NULL,
    creation_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edit_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edited BOOLEAN NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (referred_question_id) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: comments_answer
DROP TABLE IF EXISTS comments_answer;
CREATE TABLE IF NOT EXISTS comments_answers (
    id SERIAL NOT NULL,
    referred_answer_id INTEGER NOT NULL,
    body VARCHAR NOT NULL,
    creation_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edit_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edited BOOLEAN NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (referred_answer_id) REFERENCES answers(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: following_questions
DROP TABLE IF EXISTS following_questions;
CREATE TABLE IF NOT EXISTS following_questions (
    user_id INTEGER NOT NULL,
    question_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, question_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE, 
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: tags
DROP TABLE IF EXISTS tags;
CREATE TABLE IF NOT EXISTS tags (
    id SERIAL,
    name VARCHAR UNIQUE NOT NULL,
    PRIMARY KEY (id)
);

-- Table: tagged
DROP TABLE IF EXISTS tagged;
CREATE TABLE IF NOT EXISTS tagged (
    id_tag INTEGER,
    id_question INTEGER,
    PRIMARY KEY (id_tag, id_question),
    FOREIGN KEY (id_tag) REFERENCES tags(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_question) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: following_tags
DROP TABLE IF EXISTS following_tags;
CREATE TABLE IF NOT EXISTS following_tags (
    user_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, tag_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE, 
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: following_users
DROP TABLE IF EXISTS following_users;
CREATE TABLE IF NOT EXISTS following_users (
    user_id INTEGER NOT NULL,
    followed_user_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, followed_user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE, 
    FOREIGN KEY (followed_user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: notifications
DROP TABLE IF EXISTS notifications;
CREATE TABLE IF NOT EXISTS notifications (
    id SERIAL NOT NULL,
    name VARCHAR NOT NULL,
    PRIMARY KEY (id)
);

-- Table: notifies
DROP TABLE IF EXISTS notifies;
CREATE TABLE IF NOT EXISTS notifies (
    id_notification INTEGER NOT NULL,
    id_user INTEGER NOT NULL,
    PRIMARY KEY (id_notification, id_user),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (id_user) REFERENCES users(id)
);

-- Table: notification_answers
DROP TABLE IF EXISTS notification_answers;
CREATE TABLE IF NOT EXISTS notification_answers (
    id_notification INTEGER NOT NULL,
    answer_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, answer_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (answer_id) REFERENCES answers(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: notification_comments_question
DROP TABLE IF EXISTS notification_comments_question;
CREATE TABLE IF NOT EXISTS notification_comments_question (
    id_notification INTEGER NOT NULL,
    comment_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, comment_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (comment_id) REFERENCES comments_question(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: notification_comments_answer
DROP TABLE IF EXISTS notification_comments_answer;
CREATE TABLE IF NOT EXISTS notification_comments_answer (
    id_notification INTEGER NOT NULL,
    comment_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, comment_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (comment_id) REFERENCES comments_answer(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: notification_deletes_question
DROP TABLE IF EXISTS notification_deletes_question;
CREATE TABLE IF NOT EXISTS notification_deletes_question (
    id_notification INTEGER NOT NULL,
    question_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, question_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE NO ACTION
);

-- Table: notification_deletes_answer
DROP TABLE IF EXISTS notification_deletes_answer;
CREATE TABLE IF NOT EXISTS notification_deletes_answer (
    id_notification INTEGER NOT NULL,
    answer_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, answer_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (answer_id) REFERENCES answers(id) ON DELETE NO ACTION
);

-- Table: notification_questions
DROP TABLE IF EXISTS notification_questions;
CREATE TABLE IF NOT EXISTS notification_questions (
    id_notification INTEGER NOT NULL,
    question_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, question_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id), 
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: notification_users
DROP TABLE IF EXISTS notification_users;
CREATE TABLE IF NOT EXISTS notification_users (
    id_notification INTEGER NOT NULL,
    following_user_id INTEGER NOT NULL,
    followed_user_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, following_user_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (following_user_id, followed_user_id) REFERENCES following_users(user_id, followed_user_id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: notification_question_votes
DROP TABLE IF EXISTS notification_question_votes;
CREATE TABLE IF NOT EXISTS notification_question_votes (
    id_notification INTEGER NOT NULL,
    question_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, question_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: notification_answer_votes
DROP TABLE IF EXISTS notification_answer_votes;
CREATE TABLE IF NOT EXISTS notification_answer_votes (
    id_notification INTEGER NOT NULL,
    answer_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, answer_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (answer_id) REFERENCES answers(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: question_votes
DROP TABLE IF EXISTS question_votes;
CREATE TABLE IF NOT EXISTS question_votes (
    user_id INTEGER NOT NULL,
    question_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, question_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: answer_votes
DROP TABLE IF EXISTS answer_votes;
CREATE TABLE IF NOT EXISTS answer_votes (
    user_id INTEGER NOT NULL,
    answer_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, answer_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (answer_id) REFERENCES answers(id) ON DELETE CASCADE ON UPDATE CASCADE
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


INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'a@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); 



INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'b@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); 

INSERT INTO cards VALUES (DEFAULT, 'Things to do', 1);
INSERT INTO items VALUES (DEFAULT, 1, 'Buy milk');
INSERT INTO items VALUES (DEFAULT, 1, 'Walk the dog', true);

INSERT INTO cards VALUES (DEFAULT, 'Things not to do', 1);
INSERT INTO items VALUES (DEFAULT, 2, 'Break a leg');
INSERT INTO items VALUES (DEFAULT, 2, 'Crash the car');


INSERT INTO questions VALUES (DEFAULT, 'How to do this?', 'I have no idea');
