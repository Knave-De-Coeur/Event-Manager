<?php

namespace Src\Utils\sql;

// city queries

define("SELECT_CITIES", "SELECT * 
                        FROM city;
                    ");

define("SELECT_CITY_BY_ID", "SELECT *
                        FROM city 
                            WHERE id = :id;
                        ");

define("INSERT_CITY", "INSERT INTO city 
                (name, population, size, capital)
            VALUES
                (:name, :population, :size, :capital);
        ");

define("UPDATE_CITY", "UPDATE city
            SET 
                name = :name,
                population  = :population,
                size = :size,
                capital = :capital
            WHERE id = :id;
        ");

define("DELETE_CITY", "DELETE FROM city
            WHERE id = :id;
        ");


// category queries

define("SELECT_ALL_CATEGORIES", "
SELECT c.id, c.name, c2.name as parent_name, c2.id as parent_id 
FROM category as c 
LEFT JOIN category c2 on c.parent_id = c2.id;
");

define("SELECT_CATEGORIES_BY_ID", "
SELECT c.id, c.name, c2.name as parent_name, c2.id as parent_id
FROM category as c
LEFT JOIN category c2 on c.parent_id = c2.id
WHERE c.id = :id;
");

define("INSERT_CATEGORY", "INSERT INTO category 
                (name, parent_id)
            VALUES
                (:name, :parent_id);
        ");

define("UPDATE_CATEGORY", "UPDATE category
            SET 
                name = :name,
                parent_id  = :parent_id
            WHERE id = :id;
        ");

define("UPDATE_CATEGORIES_WITH_NO_PARENT", "UPDATE category
            SET 
                parent_id  = 0
            WHERE parent_id = :parent_id;
        ");

define("DELETE_CATEGORY", "DELETE FROM category
            WHERE id = :id;
        ");

define("DELETE_CAT_EVENT_BY_CATEGORY_ID", "DELETE
                        FROM event_category 
                            WHERE category_id = :category_id;
                        ");

// event queries

define("SELECT_ALL_EVENTS", "
SELECT
    e.id,
    e.name,
    e.organizer,
    e.description,
    e.time_start,
    e.time_end,
    c.id as city_id,
    c.name as city_name,
    GROUP_CONCAT(ct.id) as category_ids,
    GROUP_CONCAT(ct.name) as category_names
FROM event as e
         INNER JOIN city c on e.city_id = c.Id
         LEFT JOIN event_category ec on ec.event_id = e.id
         LEFT JOIN category ct on ct.Id = ec.category_id
GROUP BY e.id;
");

define("SELECT_EVENT_BY_ID", "
SELECT
    e.id,
    e.name,
    e.organizer,
    e.description,
    e.time_start,
    e.time_end,
    c.id as city_id,
    c.name as city_name,
    GROUP_CONCAT(ct.id) as category_ids,
    GROUP_CONCAT(ct.name) as category_names
FROM event as e
         INNER JOIN city c on e.city_id = c.Id
         LEFT JOIN event_category ec on ec.event_id = e.id
         LEFT JOIN category ct on ct.Id = ec.category_id
WHERE e.id = :event_id
GROUP BY e.id;");

define("INSERT_EVENT", "INSERT INTO event 
                (name, organizer, description, city_id, time_start, time_end)
            VALUES
                (:name, :organizer, :description, :city_id, :time_start, :time_end);
        ");

define("INSERT_EVENT_CATEGORY", "INSERT INTO event_category 
                (event_id, category_id)
            VALUES
                (:event_id, :category_id);
        ");

define("UPDATE_EVENT", "UPDATE event
            SET 
                name = :name,
                organizer  = :organizer,
                description = :description,
                city_id = :city_id,
                time_start = :time_start,
                time_end = :time_end
            WHERE id = :id;
        ");

define("DELETE_EVENT", "DELETE FROM event
            WHERE id = :id;
        ");

define("DELETE_EVENT_CATEGORIES", "DELETE FROM event_category
            WHERE event_id = :event_id;
        ");