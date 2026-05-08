Technical Implementation Plan For The Kudos App

For our App we have two entities User and Kudos, one user can have many kudos 

ENTITIES:
1. User:
    • UserID (primary-key)
    • First name
    • Last name
    • Username
    • Email
    • password
    • Profile-pic

2. Kudos:
    • KudosID (primary-key)
    • SenderID (foreign-key)
    • Msg-Content
    • DateTime


Route-Map
Authentication:  /login
Registration: /sign-up
Dashboard: /dashboard
Kudos Crud: /kudos/show, /kudos/edit/id  , /kudos/new, /kudos/delete


Components needed:
1. Security to secure the sessions(fake posts), to prevent sql attacks
2. Forms to get input from the user
3. Validator to validate user input
4. Doctrine to connect the Entities to the database
5. The MakerBundle to automate the creation of the crud, entities and controllers
6. Migrations: to actually create the database and for version control of the database structure
7. Controller: to handle the requests and the crud operations


Task Assignment:

1. Create a symfony webApp:
      
      -Initialize a new Symfony project


2. Create the Entities:
      2.1 The User Entity:
                  -make:Entity 
                  -Add all the properties( User Id, First Name, Last name, Username, password, Email)
                  -The user can have many kudos( One to many relationship)
                  
      2.2 The Kudos:
                  -make:Entity
                  -Add properties(KudosID, senderId,message content, DateTime(created at) 
                  -A kudos can have 1 sender (ManyToOne relationship with the user entity)


3. Migrations:
      -make: migration
      -run the migration to create the DB tables
4. Repository:
	-To fetch data from the database

5. services:define the business logic
	- Create and save kudos 
	- can’t give kudos to yourself

6. Controller:
	- make:Controller for the Entities
	- crud operations for both Entities


7. routing:
	-define routes for the http requests
8. Forms:
	- make:form to get input from the user
	- validate and sanitize user input


9. Fixtures:
	- To create dummy data for the database
	- to help test the routes actually work
10. Templates:
		-The design for the pages



