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
6. Migrations: for version control of the database structure
7. Controller


Task Assignment:


