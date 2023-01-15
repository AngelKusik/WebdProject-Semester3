/*
 * Name: Angelica Kusik
 * Date: September 21, 2022
 * Last Updated: October 17
 * Course: Webd 3201-03
 */


CREATE EXTENSION IF NOT EXISTS pgcrypto; --This extension allows Postgre to bcrypt the passwords based on blowfish cipher

DROP TABLE IF EXISTS clients CASCADE;
DROP TABLE IF EXISTS users;
DROP SEQUENCE IF EXISTS user_id; --Here if a changed the order and had the drop sequence coming before the drop table, I would need to add the key word CASCADE to this statement to avoid errors due to dependencies.

CREATE SEQUENCE user_id START 1000;

CREATE TABLE users (
    Id INT PRIMARY KEY DEFAULT NEXTVAL('user_id'),
    EmailAddress VARCHAR(255) UNIQUE,
    Password VARCHAR(255) NOT NULL,
    FirstName VARCHAR(128) NOT NULL,
    LastName VARCHAR(128) NOT NULL,
    LastAccess TIMESTAMP,
    EnrolDate TIMESTAMP,
    phoneNumber VARCHAR(128),
    Type VARCHAR(1)
    
);
--GRANT ALL ON users TO faculty; --When running the lab on opentech, grant access to faculty 

INSERT INTO users (EmailAddress, Password, FirstName, LastName, EnrolDate, Type)
VALUES ('admin@gmail.ca', crypt('123', gen_salt('bf')), 'Admin', 'Admin', '2022-09-29 19:10:25', 's');

INSERT INTO users (EmailAddress, Password, FirstName, LastName, EnrolDate, Type)
VALUES ('akusik@dcmail.ca', crypt('123', gen_salt('bf')), 'Angelica', 'Kusik', '2022-09-29 19:10:25', 's');

INSERT INTO users (EmailAddress, Password, FirstName, LastName, EnrolDate, Type)
VALUES ('salesperson@gmail.ca', crypt('123', gen_salt('bf')), 'Sales', 'Person', '2022-09-29 19:10:25', 'a');


--SELECT * FROM users; 
--Not sure this is right and will work..


DROP SEQUENCE IF EXISTS clients_id_seq CASCADE; 

CREATE SEQUENCE clients_id_seq START 5000;


CREATE TABLE clients (
    Id INT PRIMARY KEY DEFAULT NEXTVAL('clients_id_seq'),
    EmailAddress VARCHAR(255) UNIQUE,
    FirstName VARCHAR(128) NOT NULL,
    LastName VARCHAR(128) NOT NULL,
    PhoneNumber VARCHAR(15),
    Extension INT,
    Sales_Id INT NOT NULL,
    FOREIGN KEY (Sales_Id) REFERENCES users(Id)
);
--GRANT ALL ON users TO faculty; --When running the lab on opentech, grant access to faculty 

INSERT INTO clients(EmailAddress, FirstName, LastName, PhoneNumber, Sales_id)
VALUES ('joedoe@dcmail.ca', 'Joe', 'Doe', '(123)456-7890', 1002);

INSERT INTO clients(EmailAddress, FirstName, LastName, PhoneNumber, Sales_id)
VALUES ('janepage@dcmail.ca', 'Jane', 'Page', '(123)456-7890', 1002);


SELECT * FROM clients;
SELECT * FROM users;