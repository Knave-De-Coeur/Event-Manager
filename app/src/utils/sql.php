<?php

namespace src\utils\sql;

// city queries

define("select_all_cities", "SELECT * 
                        FROM Cities;
                    ");

define("select_city_by_id", "SELECT *
                        FROM Cities 
                            WHERE Id = ?;
                        ");

define("insert_city", "INSERT INTO Cities 
                (Name, Population, Size, Capital)
            VALUES
                (:name, :population, :size, :capital);
        ");

define("update_city", "UPDATE Cities
            SET 
                Name = :name,
                Population  = :population,
                Size = :size,
                Capital = :capital
            WHERE id = :id;
        ");

define("delete_city", "DELETE FROM Cities
            WHERE Id = :id;
        ");


// category queries

define("select_all_categories", "SELECT * 
                        FROM Categories;
                    ");

define("select_category_by_id", "SELECT *
                        FROM Categories 
                            WHERE Id = ?;
                        ");

define("insert_category", "INSERT INTO Categories 
                (Name, ParentId)
            VALUES
                (:name, :parentId);
        ");

define("update_category", "UPDATE Categories
            SET 
                Name = :name,
                ParentId  = :parentId
            WHERE id = :id;
        ");

define("delete_category", "DELETE FROM Categories
            WHERE Id = :id;
        ");

// event queries

// TODO: fix this query
define("select_all_events_with_cat", "
SELECT e.*, city.Id as city_id, city.Name as city_name, GROUP_CONCAT(c.Id) as categories
FROM Events as e
         INNER JOIN Cities city on e.CityId = city.Id
         INNER JOIN EventCategories ec on e.Id = ec.EventId
         INNER JOIN Categories c on c.Id = ec.CategoryId;
");

define("select_event_with_cat_by_id", "
SELECT e.*, city.Id as city_id, city.Name as city_name, GROUP_CONCAT(c.Id) as categories
FROM Events e
         INNER JOIN Cities city on e.CityId = city.Id
         INNER JOIN EventCategories ec on e.Id = ec.EventId
         INNER JOIN Categories c on c.Id = ec.CategoryId
WHERE e.Id = :event_id");

define("insert_event", "INSERT INTO Events 
                (Name, Organizer, CityId, TimeStart, TimeEnd)
            VALUES
                (:name, :organizer, :city_id, :time_start, :time_end);
        ");

define("insert_event_category", "INSERT INTO EventCategories 
                (EventId, CategoryId)
            VALUES
                (:event_id, :category_id);
        ");

define("update_event", "UPDATE Events
            SET 
                Name = :name,
                Organizer  = :organizer,
                CityId = :city_id,
                Timestart = :time_start,
                TimeEnd = :time_end
            WHERE Id = :id;
        ");

define("delete_event", "DELETE FROM Events
            WHERE Id = :id;
        ");

define("delete_event_category", "DELETE FROM EventCategories
            WHERE EventId = :event_id;
        ");