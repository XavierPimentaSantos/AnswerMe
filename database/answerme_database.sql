PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: Answer
DROP TABLE IF EXISTS Answer;
CREATE TABLE IF NOT EXISTS Answer (
    title TEXT NOT NULL, 
    body TEXT NOT NULL, 
    correct BOOLEAN NOT NULL, 
    score INTEGER DEFAULT (0) NOT NULL,
    answered_question INTEGER REFERENCES Question (post_id) ON DELETE CASCADE ON UPDATE CASCADE,
    post_id INTEGER REFERENCES Post (id) ON DELETE CASCADE ON UPDATE CASCADE
    PRIMARY KEY (post_id));

-- Table: Comment
DROP TABLE IF EXISTS Comment;
CREATE TABLE IF NOT EXISTS Comment (
    body TEXT NOT NULL,
    post_id INTEGER REFERENCES Post (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (post_id));

-- Table: Comment_question
DROP TABLE IF EXISTS Comment_question;
CREATE TABLE IF NOT EXISTS Comment_question (
    id_comment INTEGER REFERENCES Comment (post_id),
    id_question INTEGER REFERENCES Question (post_id),
    PRIMARY KEY (id_comment));

-- Table: Comment_answer
DROP TABLE IF EXISTS Comment_answer;
CREATE TABLE IF NOT EXISTS Comment_answer (
    id_comment INTEGER REFERENCES Comment (post_id),
    id_answer INTEGER REFERENCES Answer (post_id),
    PRIMARY KEY (id_comment));

-- Table: Following_Question
DROP TABLE IF EXISTS Following_Question;
CREATE TABLE IF NOT EXISTS Following_Question (
    user_id INTEGER REFERENCES User (id) ON DELETE CASCADE ON UPDATE CASCADE, 
    question_id INTEGER REFERENCES Question (post_id) ON DELETE CASCADE ON UPDATE CASCADE);

-- Table: Following_Tag
DROP TABLE IF EXISTS Following_Tag;
CREATE TABLE IF NOT EXISTS Following_Tag (
    user_id INTEGER REFERENCES User (id) ON DELETE SET NULL ON UPDATE CASCADE, 
    tag_id INTEGER REFERENCES Tag (id) ON DELETE CASCADE ON UPDATE CASCADE);

-- Table: Following_User
DROP TABLE IF EXISTS Following_User;
CREATE TABLE IF NOT EXISTS Following_User (
    user_id INTEGER REFERENCES User (id) ON DELETE CASCADE ON UPDATE CASCADE, 
    followed_user_id INTEGER REFERENCES User (id) ON DELETE CASCADE ON UPDATE CASCADE);

-- Table: Notification
DROP TABLE IF EXISTS Notification;
CREATE TABLE IF NOT EXISTS Notification (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL);

-- Table: Notifies
DROP TABLE IF EXISTS Notifies;
CREATE TABLE IF NOT EXISTS Notifies (
    id_notification INTEGER REFERENCES Notification (id),
    id_user INTEGER REFERENCES User (id),
    PRIMARY KEY (id_notification, id_user));

-- Table: Notification_Answer
DROP TABLE IF EXISTS Notification_Answer;
CREATE TABLE IF NOT EXISTS Notification_Answer (
    id_notification INTEGER REFERENCES Notification (id),
    answer_id INTEGER REFERENCES Answer (post_id) ON DELETE SET NULL ON UPDATE CASCADE, 
    PRIMARY KEY (id_notification));

-- Table: Notification_Comment
DROP TABLE IF EXISTS Notification_Comment;
CREATE TABLE IF NOT EXISTS Notification_Comment (
    id_notification INTEGER REFERENCES Notification (id),
    comment_id INTEGER REFERENCES Comment (post_id) ON DELETE SET NULL ON UPDATE CASCADE, 
    PRIMARY KEY (id_notification));

-- Table: Notification_Delete
DROP TABLE IF EXISTS Notification_Delete;
CREATE TABLE IF NOT EXISTS Notification_Delete (
    id_notification INTEGER REFERENCES Notification (id),
    post_id INTEGER REFERENCES Post (id) ON DELETE NO ACTION
    PRIMARY KEY (id_notification));

-- Table: Notification_Question
DROP TABLE IF EXISTS Notification_Question;
CREATE TABLE IF NOT EXISTS Notification_Question (
    id_notification INTEGER REFERENCES Notification (id), 
    question_id INTEGER REFERENCES Question (post_id) ON DELETE SET NULL ON UPDATE CASCADE,
    PRIMARY KEY (id_notification));

-- Table: Notification_User
DROP TABLE IF EXISTS Notification_User;
CREATE TABLE IF NOT EXISTS Notification_User (
    id_notification INTEGER REFERENCES Notification (id),
    following_user_id INTEGER REFERENCES Following_User (followed_user_id) ON DELETE SET NULL ON UPDATE CASCADE,
    PRIMARY KEY (id_notification));

-- Table: Notification_Vote
DROP TABLE IF EXISTS Notification_Vote;
CREATE TABLE IF NOT EXISTS Notification_Vote (
    id_notification INTEGER REFERENCES Notification (id),
    post_id INTEGER REFERENCES Post (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id_notification));

-- Table: Post_Vote
DROP TABLE IF EXISTS Post_Vote;
CREATE TABLE Post_Vote (
    user_id INTEGER REFERENCES User (id) ON DELETE CASCADE ON UPDATE CASCADE,
    post_id INTEGER REFERENCES Post (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, post_id));

-- Table: Post
DROP TABLE IF EXISTS Post;
CREATE TABLE IF NOT EXISTS Post (
    id SERIAL PRIMARY KEY, 
    creation_date DATE DEFAULT GETDATE() NOT NULL, 
    edited BOOLEAN NOT NULL, 
    user_id REFERENCES User (id) ON DELETE SET NULL ON UPDATE CASCADE);

-- Table: Question
DROP TABLE IF EXISTS Question;
CREATE TABLE IF NOT EXISTS Question (
    title TEXT NOT NULL, 
    body TEXT NOT NULL, 
    score INTEGER DEFAULT (0) NOT NULL,
    post_id INTEGER REFERENCES Post (id) ON DELETE CASCADE ON UPDATE CASCADE NOT NULL
    PRIMARY KEY (post_id));

-- Table: Settings
DROP TABLE IF EXISTS Settings;
CREATE TABLE IF NOT EXISTS Settings (
    dark_mode BOOLEAN NOT NULL, 
    hide_nation BOOLEAN NOT NULL, 
    hide_birth_date BOOLEAN NOT NULL, 
    hide_email BOOLEAN NOT NULL, 
    hide_name BOOLEAN NOT NULL,
    language BOOLEAN NOT NULL,
    user_id UNIQUE REFERENCES User (id) ON DELETE CASCADE ON UPDATE CASCADE);

-- Table: Tag
DROP TABLE IF EXISTS Tag;
CREATE TABLE IF NOT EXISTS Tag (
    id SERIAL PRIMARY KEY,
    name TEXT UNIQUE NOT NULL);

-- Table: Tagged
DROP TABLE IF EXISTS Tagged;
CREATE TABLE IF NOT EXISTS Tagged (
    id_tag INTEGER REFERENCES Tag (id),
    id_post INTEGER REFERENCES Post (id),
    PRIMARY KEY (id_tag, id_post));

-- Table: User
DROP TABLE IF EXISTS User;
CREATE TABLE IF NOT EXISTS User (
    id SERIAL PRIMARY KEY, 
    name TEXT NOT NULL, 
    username TEXT UNIQUE NOT NULL, 
    email TEXT UNIQUE NOT NULL, 
    bio TEXT, 
    birth_date DATE, 
    nationality TEXT, 
    type CHAR NOT NULL);

-- Performance Indexes

CREATE INDEX tagged_tag ON Tagged USING hash (id_tag);

CREATE INDEX comment_question ON Comment_question USING btree (id_question);
CLUSTER Comment_question USING comment_question;

CREATE INDEX comment_answer ON Comment_answer USING btree (id_answer);
CLUSTER comment_answer USING comment_answer;

CREATE INDEX question_answer ON Answer USING btree(answered_question);
CLUSTER Answer USING question_answer;

-- FTS Index

ALTER TABLE Question ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION question_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.title), 'A') ||
            setweight(to_tsvector('english', NEW.body), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.title <> OLD.title || NEW.body <> OLD.body) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A') ||
                setweight(to_tsvector('english', NEW.body), 'B')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER question_search_update
    BEFORE INSERT OR UPDATE ON question
    FOR EACH ROW
    EXECUTE PROCEDURE question_search_update();

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;

-- 10 users
INSERT INTO User (name, username, email, short_bio, birthdate, nationality, number)
VALUES
    ('John Doe', 'johndoe123', 'johndoe@example.com', 'I''m a passionate tech enthusiast who loves to explore the latest gadgets and emerging technologies. I''m always on the lookout for innovative solutions to everyday problems. I''m also an avid gamer and a coffee connoisseur.', '1990-05-15', 'USA', 2),
    ('Alice Smith', 'alicesmith456', 'alice@example.com', 'I''m an experienced software developer with a passion for coding and problem-solving. I enjoy contributing to open-source projects and mentoring junior developers. I also love to hike and explore the beauty of Canadian landscapes.', '1985-12-10', 'CAN', 3),
    ('Emma Johnson', 'emmaj', 'emma@example.com', 'I''m an avid coffee lover who roasts my own beans and experiments with unique brewing methods. I enjoy traveling the world in search of the perfect cup. I''m proud to call the UK my home.', '1992-07-23', 'GBR', 1),
    ('Michael Brown', 'mikebrown', 'mike@example.com', 'I''m a passionate travel blogger who documents my adventures in stunning detail. I love to immerse in new cultures and taste local cuisine. I''m a proud Australian with a sense of wanderlust.', '1988-03-29', 'AUS', 2),
    ('Sarah Lee', 'sarahlee22', 'sarah@example.com', 'I''m a dedicated fitness enthusiast who motivates others through my journey of physical and mental wellness. I enjoy yoga, weightlifting, and promoting a healthy lifestyle. I was born and raised in the USA.', '1995-09-04', 'USA', 1),
    ('David Kim', 'davidk', 'david@example.com', 'I''m a passionate gamer and Twitch streamer with a love for competitive esports. I enjoy exploring the world of virtual realities and sharing gaming strategies with the community. I''m proud to represent South Korea in the gaming world.', '1993-01-18', 'KOR', 3),
    ('Linda Martinez', 'lindamart', 'linda@example.com', 'I''m a culinary explorer on a mission to taste the world''s flavors. I''m a foodie at heart, and I enjoy sharing my gastronomic adventures with my followers. I''m proudly Mexican, and I embrace the vibrant culture of my homeland.', '1987-06-12', 'MEX', 2),
    ('James Wilson', 'jamesw', 'james@example.com', 'I''m an avid bookworm with a vast library of literary treasures. I enjoy discussing classic and contemporary literature. I''m always on the lookout for the next captivating novel. I''m a proud resident of the UK.', '1991-11-07', 'GBR', 3),
    ('Sophia Clark', 'sophia.c', 'sophia@example.com', 'I''m an art enthusiast and collector, and I''m passionate about discovering and promoting emerging artists. I enjoy expressing myself through various artistic mediums. I''m proudly Canadian and inspired by the country''s natural beauty.', '1986-08-31', 'CAN', 1),
    ('Daniel Davis', 'danield', 'daniel@example.com', 'I''m a dedicated hiker and nature enthusiast, and I love exploring breathtaking trails and pristine wilderness. I find peace and inspiration in the great outdoors. I''m a proud American with a deep connection to the land.', '1994-04-02', 'USA', 1);

-- 6 tags
INSERT INTO Tag (name) VALUES
    ('Technology'),
    ('Travel'),
    ('Food'),
    ('Fitness'),
    ('Art'),
    ('Literature');

-- 10 Settings
INSERT INTO Settings (dark_mode, hide_nation, hide_birth_date, hide_email, hide_name, language, user_id)
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
INSERT INTO Post (creation_date, edited, user_id)
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
INSERT INTO Question (title, body, score, post_id)
VALUES
    ('How to optimize database performance?', 'I am looking for tips and strategies to improve the performance of my database system. What are the best practices to follow?', 5, 1),
    ('How to learn a new programming language?', 'I want to learn a new programming language, but I''m not sure where to start. Any recommendations or resources?', -2, 2),
    ('What are the benefits of regular exercise?', 'I''m considering starting a regular exercise routine. Can someone explain the health benefits and how to get started?', 3, 3),
    ('How to prepare for a job interview?', 'I have an upcoming job interview, and I''m nervous. Any advice on how to prepare and perform well in the interview?', 7, 4),
    ('What is the impact of climate change on ecosystems?', 'I''m interested in understanding how climate change is affecting ecosystems worldwide. Can someone provide insights or studies on this?', -5, 5),
    ('How to create a budget for personal finance?', 'I want to manage my finances better and create a budget. Any tips on how to get started and stick to a budget plan?', 1, 6);

-- 6 Answers
INSERT INTO Answer (title, body, correct, score, answered_question, post_id)
VALUES
    ('Answer for How to optimize database performance?', 'You can optimize database performance by following these steps...', TRUE, 8, 1, 7),
    ('Answer for How to learn a new programming language?', 'Learning a new programming language can be a rewarding experience...', FALSE, 2, 2, 8),
    ('Answer for What are the benefits of regular exercise?', 'Regular exercise has numerous benefits, including improved physical health and reduced stress...', TRUE, 5, 3, 9),
    ('Answer for How to prepare for a job interview?', 'To prepare for a job interview, you should research the company...', TRUE, 6, 4, 10),
    ('Answer for What is the impact of climate change on ecosystems?', 'Climate change is causing significant shifts in ecosystems...', FALSE, -3, 5, 11),
    ('Alternative Answer for What is the impact of climate change on ecosystems?', 'The impact of climate change on ecosystems is a critical concern...', TRUE, 7, 5, 12);

-- 4 Comments
INSERT INTO Comments (body, post_id)
VALUES
    ('Great post! I found this information very helpful.', 13),
    ('I have a question about your post. Can you please clarify?', 14),
    ('This is an interesting topic. I enjoyed reading your article.', 15),
    ('Thanks for sharing your insights. I learned a lot from this.', 16);

INSERT INTO Comment_question (id_comment, id_question)
VALUES 
    (4, 14),
    (1, 15);

INSERT INTO Comment_answer (id_comment, id_answer)
VALUES
    (7, 13),
    (9, 16);

-- Tagging the questions
INSERT INTO Tagged (id_tag, id_post)
VALUES
    (3, 5),
    (1, 5),
    (1, 2),
    (4, 4),
    (5, 4),
    (6, 4);