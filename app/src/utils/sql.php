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
