
CREATE TABLE balance (
    id char, value int
);

INSERT INTO balance(id, value) VALUES
('A',500),
('B',500);

ALTER TABLE balance ADD CONSTRAINT CHECK_VALUE_NOT_NETAGTIVE CHECK (value >= 0)