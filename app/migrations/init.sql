CREATE DATABASE IF NOT EXISTS events;

CREATE TABLE IF NOT EXISTS Cities (
    Id INT(11) NOT NULL AUTO_INCREMENT,
    Name VARCHAR(20) NOT NULL,
    Population INT(6),
    Size INT(6),
    Capital boolean,
    PRIMARY KEY (Id)
) ENGINE=INNODB;

INSERT INTO Cities (Id, Name, Population, Size, Capital) VALUES
(1, 'Sliema', 10000, 3, false),
(2, 'St Julians', 12000, 2, false),
(3, 'Valletta', 9000, 4, true);

CREATE TABLE IF NOT EXISTS Categories (
    Id INT(11) NOT NULL AUTO_INCREMENT,
    ParentId INT(11) NOT NULL DEFAULT '0',
    Name VARCHAR(20) NOT NULL,
    PRIMARY KEY (Id)
) ENGINE=INNODB;

INSERT INTO Categories (Id, ParentId, Name) VALUES
(1, 0, 'Rock'),
(2, 0, 'Metal'),
(3, 0, 'Jazz'),
(4, 1, 'Hard Rock'),
(5, 1, 'Classic Rock'),
(6, 2, 'Thrash Metal'),
(7, 2, 'Death Metal'),
(8, 2, 'Black Metal');

CREATE TABLE IF NOT EXISTS Events (
    Id INT(11) NOT NULL AUTO_INCREMENT,
    Name VARCHAR(20) NOT NULL,
    Organizer VARCHAR(20) NOT NULL,
    CityId INT NOT NULL,
    TimeStart DATETIME NOT NULL,
    TimeEnd DATETIME NOT NULL,
    PRIMARY KEY (Id),
    FOREIGN KEY (CityId)
        REFERENCES Cities(Id)
    ON DELETE CASCADE
) ENGINE=INNODB;

INSERT INTO Events (Id, Name, Organizer, CityId, TimeStart, TimeEnd) VALUES
(1, 'Event 1', 'Alex Mifsud', 3, '2023-06-03 20:00:00', '2023-06-04 01:00:00'),
(2, 'Event 2', 'Alex Mifsud', 1, '2023-04-21 20:00:00', '2023-04-23 23:00:00'),
(3, 'Event 3', 'Alex Mifsud', 2, '2023-09-20 20:00:00', '2023-09-20 01:00:00');

CREATE TABLE IF NOT EXISTS EventCategories (
   EventId int NOT NULL,
   CategoryId int NOT NULL,
   FOREIGN KEY (CategoryId)
       REFERENCES Categories(Id),
   FOREIGN KEY (EventId)
       REFERENCES Events(Id),
   INDEX eventcategory (EventId,CategoryId)
) ENGINE=INNODB;

INSERT INTO EventCategories (EventId, CategoryId) VALUES
(1, 2),
(1, 3),
(2, 4),
(3, 8);
