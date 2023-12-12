DROP SCHEMA IF EXISTS lbaw2392 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2392;
SET search_path TO lbaw2392;

--
-- Drop any existing tables.
--

DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;
DROP TABLE IF EXISTS questions CASCADE;
DROP TABLE IF EXISTS answers CASCADE;
DROP TABLE IF EXISTS users CASCADE;

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
  remember_token VARCHAR,
  profile_picture varchar not null default 'default.png'
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


-- Table: questions
DROP TABLE IF EXISTS questions;
CREATE TABLE IF NOT EXISTS questions (
    id SERIAL PRIMARY KEY,
    title VARCHAR NOT NULL,
    content VARCHAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    score INTEGER DEFAULT (0) NOT NULL,
    edited BOOLEAN DEFAULT (FALSE) NOT NULL,
    user_id INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: questions_images
DROP TABLE IF EXISTS question_images;
CREATE TABLE IF NOT EXISTS question_images (
    id SERIAL PRIMARY KEY,
    picture_path VARCHAR NOT NULL,
    question_id INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: answers
DROP TABLE IF EXISTS answers;
CREATE TABLE IF NOT EXISTS answers (
    question_id INTEGER NOT NULL,
    id SERIAL PRIMARY KEY,
    title VARCHAR NOT NULL, 
    content VARCHAR NOT NULL, 
    correct BOOLEAN NOT NULL DEFAULT (FALSE), 
    score INTEGER DEFAULT (0) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    edited BOOLEAN NOT NULL DEFAULT (FALSE),
    user_id INTEGER NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE ON UPDATE CASCADE,
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
DROP TABLE IF EXISTS comments_answers;
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

-- Table: question_tag
DROP TABLE IF EXISTS question_tag;
CREATE TABLE IF NOT EXISTS question_tag (
    question_id INTEGER,
    tag_id INTEGER,
    PRIMARY KEY (question_id, tag_id),
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
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
    FOREIGN KEY (comment_id) REFERENCES comments_questions(id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: notification_comments_answer
DROP TABLE IF EXISTS notification_comments_answer;
CREATE TABLE IF NOT EXISTS notification_comments_answer (
    id_notification INTEGER NOT NULL,
    comment_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, comment_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (comment_id) REFERENCES comments_answers(id) ON DELETE SET NULL ON UPDATE CASCADE
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
  'Mestre Fu',
  'admin@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')


INSERT INTO users VALUES (
  DEFAULT,
  'João Silva',
  'jsilva@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); 



INSERT INTO users VALUES (
  DEFAULT,
  'Lourenço Silva',
  'b@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); 

-- Question 1
INSERT INTO questions VALUES (DEFAULT, 'If algorithms had personalities, which one would be your best friend?', 'Imagine spending a day with your favorite algorithm and discuss its characteristics.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1);

-- Question 2
INSERT INTO questions VALUES (DEFAULT, 'In a coding language competition, which language would win and why?', 'Debate the strengths and weaknesses of programming languages as if they were competing in a contest.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1);

-- Question 3
INSERT INTO questions VALUES (DEFAULT, 'If you could implement a feature in the real world, what would it be?', 'Discuss a computer science feature you''d love to bring into our daily lives.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 2);

-- Question 4
INSERT INTO questions VALUES (DEFAULT, 'If bugs were physical creatures, what would they look like?', 'Imagine the appearance and characteristics of the pesky bugs that you encounter in your code.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1);

-- Question 5
INSERT INTO questions VALUES (DEFAULT, 'If software could dream, what would be its wildest dream?', 'Explore the imaginative world of software and its fantastical dreams.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 2);

-- Question 6
INSERT INTO questions VALUES (DEFAULT, 'In a cyberpunk future, what role would you play?', 'Envision yourself in a futuristic cyberpunk world and discuss your role in the digital landscape.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 2);

-- Question 7
INSERT INTO questions VALUES (DEFAULT, 'If you could create a virtual reality experience, what would it be about?', 'Design a virtual reality experience related to computer science or engineering.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1);

-- Question 8
INSERT INTO questions VALUES (DEFAULT, 'If robots had emotions, how would you console a sad robot?', 'Explore the emotional side of artificial intelligence and discuss ways to comfort a robot in distress.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1);

-- Question 9
INSERT INTO questions VALUES (DEFAULT, 'If you could invent a new programming paradigm, what would it be called?', 'Introduce a groundbreaking programming paradigm and discuss its principles.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1);

-- Question 10
INSERT INTO questions VALUES (DEFAULT, 'If your laptop could speak, what tech secrets would it reveal?', 'Imagine the secrets your laptop might spill about your tech habits and experiences.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 2);

-- Question 11
INSERT INTO questions VALUES (DEFAULT, 'In a futuristic tech competition, what would be your winning invention?', 'Present your groundbreaking tech invention for a futuristic competition.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 2);

-- Question 12
INSERT INTO questions VALUES (DEFAULT, 'If you could attend a lecture by any tech pioneer, who would it be and why?', 'Choose a tech pioneer and discuss the insights you''d hope to gain from attending their lecture.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 2);

-- Question 13
INSERT INTO questions VALUES (DEFAULT, 'If you were a computer virus, what would be your mission?', 'Explore the motivations and objectives of a fictional computer virus that you create.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 2);

-- Question 14
INSERT INTO questions VALUES (DEFAULT, 'If you could program your dreams, what would your coding nightmare look like?', 'Imagine the coding challenges and nightmares you might encounter in a dream-like programming world.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1);

-- Question 15
INSERT INTO questions VALUES (DEFAULT, 'If software engineers had a superhero team, what powers would each member possess?', 'Assemble your dream superhero team of software engineers and describe their unique powers.', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1);


-- Tag 1
INSERT INTO tags VALUES (DEFAULT, 'Technology');

-- Tag 2
INSERT INTO tags VALUES (DEFAULT, 'Cooking');

-- Tag 3
INSERT INTO tags VALUES (DEFAULT, 'Cars');

-- Tag 4
INSERT INTO tags VALUES (DEFAULT, 'Embroidery');