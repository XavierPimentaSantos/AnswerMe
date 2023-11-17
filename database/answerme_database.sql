BEGIN TRANSACTION;


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
    user_settings INTEGER,
    PRIMARY KEY (id),
    FOREIGN KEY (user_settings) REFERENCES settings(id) ON DELETE CASCADE ON UPDATE CASCADE
);



-- Table: posts
DROP TABLE IF EXISTS posts;
CREATE TABLE IF NOT EXISTS posts (
    id SERIAL NOT NULL,
    creation_date TIMESTAMP DEFAULT NOW() NOT NULL,
    edited BOOLEAN NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE);


-- Table: questions
DROP TABLE IF EXISTS questions;
CREATE TABLE IF NOT EXISTS questions (
    post_id INTEGER NOT NULL,
    title VARCHAR NOT NULL,
    body VARCHAR NOT NULL, 
    score INTEGER DEFAULT (0) NOT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Table: answers
DROP TABLE IF EXISTS answers;
CREATE TABLE IF NOT EXISTS answers (
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

-- Table: comments
DROP TABLE IF EXISTS comments;
CREATE TABLE IF NOT EXISTS comments (
    post_id INTEGER NOT NULL,
    referred_post_id INTEGER NOT NULL,
    body VARCHAR NOT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (referred_post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Table: following_questions
DROP TABLE IF EXISTS following_questions;
CREATE TABLE IF NOT EXISTS following_questions (
    user_id INTEGER NOT NULL,
    question_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, question_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE, 
    FOREIGN KEY (question_id) REFERENCES questions(post_id) ON DELETE CASCADE ON UPDATE CASCADE
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
    id_post INTEGER,
    PRIMARY KEY (id_tag, id_post),
    FOREIGN KEY (id_tag) REFERENCES tags(id),
    FOREIGN KEY (id_post) REFERENCES posts(id)
);

-- Table: following_tags
DROP TABLE IF EXISTS following_tags;
CREATE TABLE IF NOT EXISTS following_tags (
    user_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, tag_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE, 
    FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE ON UPDATE CASCADE
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
    id SERIAL,
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
    FOREIGN KEY (answer_id) REFERENCES answers(post_id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: notification_comments
DROP TABLE IF EXISTS notification_comments;
CREATE TABLE IF NOT EXISTS notification_comments (
    id_notification INTEGER NOT NULL,
    comment_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, comment_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (comment_id) REFERENCES comments(post_id) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: notification_deletes
DROP TABLE IF EXISTS notification_deletes;
CREATE TABLE IF NOT EXISTS notification_deletes (
    id_notification INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, post_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE NO ACTION
);

-- Table: notification_questions
DROP TABLE IF EXISTS notification_questions;
CREATE TABLE IF NOT EXISTS notification_questions (
    id_notification INTEGER NOT NULL,
    question_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, question_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id), 
    FOREIGN KEY (question_id) REFERENCES questions(post_id) ON DELETE SET NULL ON UPDATE CASCADE
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

-- Table: notification_votes
DROP TABLE IF EXISTS notification_votes;
CREATE TABLE IF NOT EXISTS notification_votes (
    id_notification INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    PRIMARY KEY (id_notification, post_id),
    FOREIGN KEY (id_notification) REFERENCES notifications(id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: post_votes
DROP TABLE IF EXISTS post_votes;
CREATE TABLE post_votes (
    user_id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Performance Indexes

CREATE INDEX tagged_tag ON tagged USING hash (id_tag);

CREATE INDEX comment_question ON comment_questions USING btree (id_question);
CLUSTER comment_questions USING comment_question;

CREATE INDEX comment_answer ON comment_answers USING btree (id_answer);
CLUSTER comment_answers USING comment_answer;

CREATE INDEX question_answer ON answers USING btree(answered_question);
CLUSTER answers USING question_answer;

-- FTS Index

DROP FUNCTION IF EXISTS question_search_update();
ALTER TABLE questions ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION question_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.title), 'A')
            -- setweight(to_tsvector('english', NEW.body), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.title <> OLD.title OR NEW.body <> OLD.body) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A')
                -- setweight(to_tsvector('english', NEW.body), 'B')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER question_search_trigger
    BEFORE INSERT OR UPDATE ON questions
    FOR EACH ROW
    EXECUTE PROCEDURE question_search_update();

CREATE OR REPLACE FUNCTION prevent_self_follow()
RETURNS TRIGGER AS
$BODY$
BEGIN
	IF NEW.user_id = NEW.followed_user_id THEN
    	RAISE EXCEPTION 'Users cannot follow themselves.';
	END IF;
	RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;
CREATE TRIGGER prevent_self_follow
BEFORE INSERT ON following_users
FOR EACH ROW
EXECUTE FUNCTION prevent_self_follow();

DROP FUNCTION IF EXISTS allow_comments();
CREATE FUNCTION allow_comments() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF NEW.referred_post_id IN (SELECT post_id FROM questions UNION SELECT post_id FROM answers) THEN
		RETURN NEW; -- Comment is allowed on a question or an answer
	ELSE
		RAISE EXCEPTION 'Commenting is only allowed under questions and answers.';
	END IF;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER allow_comment_trigger
	BEFORE INSERT ON comments
	FOR EACH ROW
EXECUTE FUNCTION allow_comments();


DROP FUNCTION IF EXISTS check_unique_emails();
CREATE FUNCTION check_unique_emails() RETURNS TRIGGER AS
$BODY$
BEGIN
	IF NEW.email IS NOT NULL AND EXISTS (SELECT 1 FROM users WHERE email = NEW.email AND id <> NEW.id) THEN
   	RAISE EXCEPTION 'An AnswerMe! account with this email address already exists.';
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
CREATE TRIGGER check_unique_email_trigger
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION check_unique_emails();

CREATE OR REPLACE FUNCTION mark_edited_post()
RETURNS TRIGGER AS
$BODY$
BEGIN
	IF OLD.body IS DISTINCT FROM NEW.body THEN
    	NEW.edited := TRUE;
	END IF;
	RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER mark_edited_question
AFTER UPDATE ON questions
FOR EACH ROW
WHEN (OLD.body IS DISTINCT FROM NEW.body)
EXECUTE FUNCTION mark_edited_post();
CREATE TRIGGER mark_edited_answer
AFTER UPDATE ON answers
FOR EACH ROW
WHEN (OLD.body IS DISTINCT FROM NEW.body)
EXECUTE FUNCTION mark_edited_post();
CREATE TRIGGER mark_edited_comment
AFTER UPDATE ON comments
FOR EACH ROW
WHEN (OLD.body IS DISTINCT FROM NEW.body)
EXECUTE FUNCTION mark_edited_post();

DROP FUNCTION IF EXISTS prevent_self_vote();
CREATE FUNCTION prevent_self_vote() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NEW.user_id = (SELECT user_id FROM questions WHERE post_id = NEW.post_id) THEN 
            RAISE EXCEPTION 'Users cannot vote on their own posts.';
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_self_votes_trigger
        BEFORE INSERT
        ON post_votes
        FOR EACH ROW        
 EXECUTE PROCEDURE prevent_self_vote();

CREATE OR REPLACE FUNCTION enforce_unique_username()
RETURNS TRIGGER AS
$BODY$
BEGIN
	IF NEW.username IS NOT NULL AND EXISTS (
    	SELECT 1 FROM users WHERE username = NEW.username AND id <> NEW.id
	) THEN
    	RAISE EXCEPTION 'Username "%", is already in use by another user.', NEW.username;
	END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
CREATE TRIGGER enforce_unique_username
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION enforce_unique_username();

COMMIT TRANSACTION;

-- DATABASE POPULATION 

-- 10 users
INSERT INTO users (fullname, username, email, bio, birth_date, nationality, user_type, user_password)
VALUES
    ('John Doe', 'johndoe123', 'johndoe@example.com', 'I''m a passionate tech enthusiast who loves to explore the latest gadgets and emerging technologies. I''m always on the lookout for innovative solutions to everyday problems. I''m also an avid gamer and a coffee connoisseur.', '1990-05-15', 'USA', 2, '1234'),
    ('Alice Smith', 'alicesmith456', 'alice@example.com', 'I''m an experienced software developer with a passion for coding and problem-solving. I enjoy contributing to open-source projects and mentoring junior developers. I also love to hike and explore the beauty of Canadian landscapes.', '1985-12-10', 'CAN', 3, '1234'),
    ('Emma Johnson', 'emmaj', 'emma@example.com', 'I''m an avid coffee lover who roasts my own beans and experiments with unique brewing methods. I enjoy traveling the world in search of the perfect cup. I''m proud to call the UK my home.', '1992-07-23', 'GBR', 1, '1234'),
    ('Michael Brown', 'mikebrown', 'mike@example.com', 'I''m a passionate travel blogger who documents my adventures in stunning detail. I love to immerse in new cultures and taste local cuisine. I''m a proud Australian with a sense of wanderlust.', '1988-03-29', 'AUS', 2, '1234'),
    ('Sarah Lee', 'sarahlee22', 'sarah@example.com', 'I''m a dedicated fitness enthusiast who motivates others through my journey of physical and mental wellness. I enjoy yoga, weightlifting, and promoting a healthy lifestyle. I was born and raised in the USA.', '1995-09-04', 'USA', 1, '1234'),
    ('David Kim', 'davidk', 'david@example.com', 'I''m a passionate gamer and Twitch streamer with a love for competitive esports. I enjoy exploring the world of virtual realities and sharing gaming strategies with the community. I''m proud to represent South Korea in the gaming world.', '1993-01-18', 'KOR', 3, '1234'),
    ('Linda Martinez', 'lindamart', 'linda@example.com', 'I''m a culinary explorer on a mission to taste the world''s flavors. I''m a foodie at heart, and I enjoy sharing my gastronomic adventures with my followers. I''m proudly Mexican, and I embrace the vibrant culture of my homeland.', '1987-06-12', 'MEX', 2, '1234'),
    ('James Wilson', 'jamesw', 'james@example.com', 'I''m an avid bookworm with a vast library of literary treasures. I enjoy discussing classic and contemporary literature. I''m always on the lookout for the next captivating novel. I''m a proud resident of the UK.', '1991-11-07', 'GBR', 3, '1234'),
    ('Sophia Clark', 'sophia.c', 'sophia@example.com', 'I''m an art enthusiast and collector, and I''m passionate about discovering and promoting emerging artists. I enjoy expressing myself through various artistic mediums. I''m proudly Canadian and inspired by the country''s natural beauty.', '1986-08-31', 'CAN', 1, '1234'),
    ('Daniel Davis', 'danield', 'daniel@example.com', 'I''m a dedicated hiker and nature enthusiast, and I love exploring breathtaking trails and pristine wilderness. I find peace and inspiration in the great outdoors. I''m a proud American with a deep connection to the land.', '1994-04-02', 'USA', 1, '1234');

-- 6 tags
INSERT INTO tags (name) VALUES
    ('Technology'),
    ('Travel'),
    ('Food'),
    ('Fitness'),
    ('Art'),
    ('Literature');

-- 10 settings
INSERT INTO settings (dark_mode, hide_nation, hide_birth_date, hide_email, hide_name, language, id)
VALUES
    (TRUE, TRUE, FALSE, FALSE, TRUE, TRUE, 1),
    (FALSE, TRUE, TRUE, FALSE, FALSE, TRUE, 2),
    (TRUE, FALSE, FALSE, TRUE, TRUE, FALSE, 3),
    (FALSE, TRUE, TRUE, FALSE, TRUE, TRUE, 4),
    (TRUE, FALSE, FALSE, TRUE, TRUE, FALSE, 5),
    (FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, 6),
    (TRUE, TRUE, FALSE, TRUE, TRUE, FALSE, 7),
    (TRUE, FALSE, FALSE, TRUE, FALSE, TRUE, 8),
    (FALSE, TRUE, TRUE, FALSE, FALSE, TRUE, 9),
    (TRUE, TRUE, TRUE, FALSE, FALSE, TRUE, 10);

-- 16 Posts
INSERT INTO posts (creation_date, edited, user_id)
VALUES
    ('2023-10-15', TRUE, 5),
    ('2023-11-20', FALSE, 3),
    ('2024-01-25', TRUE, 8),
    ('2024-02-10', TRUE, 2),
    ('2023-12-30', FALSE, 6),
    ('2024-03-15', TRUE, 9),
    ('2023-10-20', FALSE, 1),
    ('2023-11-25', TRUE, 4),
    ('2024-04-27', TRUE, 7),
    ('2023-11-10', FALSE, 10),
    ('2024-09-05', TRUE, 6),
    ('2024-06-12', FALSE, 3),
    ('2024-05-18', TRUE, 2),
    ('2024-07-07', TRUE, 8),
    ('2024-08-14', FALSE, 4),
    ('2024-09-22', TRUE, 7);

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

-- 4 Comments
INSERT INTO comments (post_id, referred_post_id, body)
VALUES
    (13, 7, 'Great post! I found this information very helpful.'),
    (14, 4, 'I have a question about your post. Can you please clarify?'),
    (15, 1, 'This is an interesting topic. I enjoyed reading your article.'),
    (16, 9, 'Thanks for sharing your insights. I learned a lot from this.');


-- Tagging the questions
INSERT INTO tagged (id_tag, id_post)
VALUES
    (3, 5),
    (1, 5),
    (1, 2),
    (4, 4),
    (5, 4),
    (6, 4);