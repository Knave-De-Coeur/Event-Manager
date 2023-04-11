CREATE DATABASE IF NOT EXISTS events;

CREATE TABLE IF NOT EXISTS city (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    population INT(6),
    size INT(6),
    capital boolean,
    PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO city (id, name, population, size, capital) VALUES
(1, 'Sliema', 10000, 3, false),
(2, 'St Julians', 12000, 2, false),
(3, 'Valletta', 9000, 4, true);

CREATE TABLE IF NOT EXISTS category (
    id INT(11) NOT NULL AUTO_INCREMENT,
    parent_id INT(11) NOT NULL DEFAULT '0',
    name VARCHAR(20) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=INNODB;

INSERT INTO category (id, parent_id, name) VALUES
(1, 0, 'Rock'),
(2, 0, 'Metal'),
(3, 0, 'Jazz'),
(4, 1, 'Hard Rock'),
(5, 1, 'Classic Rock'),
(6, 2, 'Thrash Metal'),
(7, 2, 'Death Metal'),
(8, 2, 'Black Metal');

CREATE TABLE IF NOT EXISTS event (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    organizer VARCHAR(20) NOT NULL,
    description VARCHAR(255),
    city_id INT NOT NULL,
    time_start DATETIME NOT NULL,
    time_end DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (city_id)
        REFERENCES city(id)
    ON DELETE CASCADE
) ENGINE=INNODB;

INSERT INTO event (id, name, organizer, description, city_id, time_start, time_end) VALUES
(1, 'Event 1', 'Alex Mifsud', 'Description for Event 1 at Valletta', 3, '2023-06-03 20:00:00', '2023-06-04 01:00:00'),
(2, 'Event 2', 'Alex Mifsud', 'Description for Event 2 at Sliema', 1, '2023-04-21 20:00:00', '2023-04-23 23:00:00'),
(3, 'Event 3', 'Alex Mifsud', 'Description for Event 3 at St Julian\'s', 2, '2023-09-20 20:00:00', '2023-09-20 01:00:00');

CREATE TABLE IF NOT EXISTS event_category (
   event_id int NOT NULL,
   category_id int NOT NULL,
   FOREIGN KEY (category_id)
       REFERENCES category(id),
   FOREIGN KEY (event_id)
       REFERENCES event(id),
   INDEX eventcategory (event_id,category_id)
) ENGINE=INNODB;

INSERT INTO event_category (event_id, category_id) VALUES
(1, 2),
(1, 3),
(2, 4),
(3, 8);
