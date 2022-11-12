CREATE TABLE users (
	id int NOT NULL AUTO_INCREMENT,
	name varchar(255),
	PRIMARY KEY (id)
);

INSERT INTO users(name) VALUES
("Oscar"),
("Felix"),
("Sean"),
("Alicia"),
("FuXi");

CREATE TABLE cards (
	id int NOT NULL AUTO_INCREMENT,
	name varchar(255),
	PRIMARY KEY (id)
);

INSERT INTO cards(name) VALUES
("Christmas"),
("Halloween"),
("Tokyo"),
("BTS");

CREATE TABLE owns (
	user_id int REFERENCES users,
	card_id int REFERENCES cards,
	balance int check (balance >= 0),
	PRIMARY KEY (user_id, card_id)
);

INSERT INTO owns VALUES
(1, 1, 500),
(1, 2, 500),
(2, 3, 500),
(3, 4, 500),
(4, 1, 500),
(5, 2, 500);

--ALTER TABLE owns ADD CONSTRAINT CHECK_VALUE_NOT_NEGATIVE CHECK (balance >= 0)