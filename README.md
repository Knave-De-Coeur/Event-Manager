# Event-Manager
Php based event manager back end.

## Overview
Build a basic event management app to track event venues in Malta. Before registering venues, the app will be used to populate and manage the list of cities and venue categories.

### Back-end requirements;
- PHP without any framework
- MySQL or PostgreSQL for persistent storage
- Redis for caching purposes

### Front-end requirements;
- Vue.js or AngularJS

### Description;
- The user should be able to list, add, edit and delete cities and categories.
- Categories can have multi-level infinite subcategories.
- Events need to be assigned to one city.
- Events can be classified with multiple categories.
- Every action should be driven by an API you created.


## Instructions

- clone repo, step into the directory and run following command (free up ports accordingly):
```
docker-compose up -d
```

- This should set-up the schema in a mysql with a bit of dummy data, the schema is the following:

### <u>Events table: </u>

| Column      | Type         | 
|-------------|--------------|
| id          | int (pk)     |
| name        | varchar(20)  |
| organizer   | varchar(20)  |
| description | varchar(255) |
| city_id     | int (fk)     |
| time_start  | datetime     |
| time_end    | datetime     |


### <u>Categories table: </u>

| Column    | Type         |
|-----------|--------------|
| id        | int (pk)     |
| name      | varchar(20)  |
| parent_id | varchar(20)  |

### <u>Event Categories table: </u>

| Column    | Type         |
|-----------|--------------|
| id        | int (pk)     |
| name      | varchar(20)  |
| parent_id | varchar(20)  |

### <u>City table: </u>

| Column     | Type        |
|------------|-------------|
| id         | int (pk)    |
| name       | varchar(20) |
| population | int         |
| size       | int         |
| capital    | tinyint(1)  |

- Server will be loaded on `localhost:8080`
- Now the following endpoints should be exposed

```
Event endpoints:

GET - http://localhost:8080/events
GET - http://localhost:8080/event/{{event_id}}
POST - http://localhost:8080/event
PUT - http://localhost:8080/event/{{event_id}}
DELETE - http://localhost:8080/event/{{event_id}}

payloads example :
{
    "name": "Jazz Event 2",
    "organizer": "Jazz Cat",
    "description": "This is a jazz event",
    "time_start": "2023-07-14 20:00:00",
    "time_end": "2023-07-14 23:00:00",
    "city_id": 3,
    "category_ids": [3]
}

```

```
City endpoints:

GET - http://localhost:8080/cities
GET - http://localhost:8080/city/{{city_id}}
POST - http://localhost:8080/city
PUT - http://localhost:8080/city/{{city_id}}
DELETE - http://localhost:8080/city/{{city_id}}

Payload Example:

{
    "name": "Rabat",
    "population": 5000,
    "size": 2,
    "capital": 1
}
```
```
Category endpoints:

GET - http://localhost:8080/categories
GET - http://localhost:8080/category/{{category_id}}
POST - http://localhost:8080/category
PUT - http://localhost:8080/category/{{category_id}}
DELETE - http://localhost:8080/category/{{category_id}}

Payload Example:

{
    "name": "Hard Rock",
    "parent_id": 1
}
```
