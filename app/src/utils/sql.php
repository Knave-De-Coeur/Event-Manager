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
                ParentId  = :parentId,
            WHERE id = :id;
        ");

define("delete_category", "DELETE FROM Categories
            WHERE Id = :id;
        ");