<?php

namespace src\utils\sql;

// city queries

define("select_all_cities", "SELECT * 
                        FROM city;
                    ");

define("select_city_by_id", "SELECT *
                        FROM city 
                            WHERE id = :id;
                        ");

define("insert_city", "INSERT INTO city 
                (name, population, size, capital)
            VALUES
                (:name, :population, :size, :capital);
        ");

define("update_city", "UPDATE city
            SET 
                name = :name,
                population  = :population,
                size = :size,
                capital = :capital
            WHERE id = :id;
        ");

define("delete_city", "DELETE FROM city
            WHERE id = :id;
        ");


// category queries

define("select_all_categories", "SELECT * 
                        FROM category;
                    ");

define("select_category_by_id", "SELECT *
                        FROM category 
                            WHERE id = :id;
                        ");

define("insert_category", "INSERT INTO category 
                (name, parent_id)
            VALUES
                (:name, :parent_id);
        ");

define("update_category", "UPDATE category
            SET 
                name = :name,
                parent_id  = :parent_id
            WHERE id = :id;
        ");

define("update_categories_no_parent", "UPDATE category
            SET 
                parent_id  = 0
            WHERE parent_id = :parent_id;
        ");

define("delete_category", "DELETE FROM category
            WHERE id = :id;
        ");

define("delete_category_events_by_cat_id", "DELETE
                        FROM event_category 
                            WHERE category_id = :category_id;
                        ");

// event queries

// TODO: fix this query
define("select_all_events_with_cat", "
SELECT e.*, c.id as city_id, c.name as city_name, GROUP_CONCAT(ct.id) as category_ids
FROM event as e
         INNER JOIN city c on e.city_id = c.Id
         INNER JOIN event_category ec on e.id = ec.event_id
         INNER JOIN category ct on ct.Id = ec.category_id;
");

define("select_event_with_cat_by_id", "
SELECT e.*, c.id as city_id, c.name as city_name, GROUP_CONCAT(ct.id) as category_ids
FROM event as e
         INNER JOIN city c on e.city_id = c.Id
         INNER JOIN event_category ec on e.id = ec.event_id
         INNER JOIN category ct on ct.Id = ec.category_id
WHERE e.id = :event_id");

define("insert_event", "INSERT INTO event 
                (name, organizer, description, city_id, time_start, time_end)
            VALUES
                (:name, :organizer, :description, :city_id, :time_start, :time_end);
        ");

define("insert_event_category", "INSERT INTO event_category 
                (event_id, category_id)
            VALUES
                (:event_id, :category_id);
        ");

define("update_event", "UPDATE event
            SET 
                name = :name,
                organizer  = :organizer,
                city_id = :city_id,
                time_start = :time_start,
                time_end = :time_end
            WHERE id = :id;
        ");

define("delete_event", "DELETE FROM event
            WHERE id = :id;
        ");

define("delete_event_categories", "DELETE FROM event_category
            WHERE event_id = :event_id;
        ");