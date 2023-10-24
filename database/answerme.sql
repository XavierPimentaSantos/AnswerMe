
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
