/*
 * Name: Angelica Kusik
 * Date: September 21, 2022
 * Last Updated: November 6
 * Course: Webd 3201-03
 */


CREATE EXTENSION IF NOT EXISTS pgcrypto; --This extension allows Postgre to bcrypt the passwords based on blowfish cipher

DROP TABLE IF EXISTS calls CASCADE;
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
    active BOOLEAN NOT NULL,
    Type VARCHAR(1)
    
);
--GRANT ALL ON users TO faculty; --When running the lab on opentech, grant access to faculty 

INSERT INTO users (EmailAddress, Password, FirstName, LastName, EnrolDate, active, Type)
VALUES ('admin@mapple.ca', crypt('123', gen_salt('bf')), 'Admin', 'Admin', '2022-09-29 19:10:25', 't', 's');

INSERT INTO users (EmailAddress, Password, FirstName, LastName, EnrolDate, active, Type)
VALUES ('professor@mapple.ca', crypt('123', gen_salt('bf')), 'Professor', 'Admin', '2022-11-30 19:10:25', 't', 's');

INSERT INTO users (EmailAddress, Password, FirstName, LastName, EnrolDate, active, Type)
VALUES ('akusik@mapple.ca', crypt('123', gen_salt('bf')), 'Angelica', 'Kusik', '2022-09-29 19:10:25', 't', 's');

INSERT INTO users (EmailAddress, Password, FirstName, LastName, EnrolDate, active, Type)
VALUES ('salesperson1@mapple.ca', crypt('123', gen_salt('bf')), 'John', 'Salesperson', '2022-09-29 19:10:25', 't', 'a');



--SELECT * FROM users; 


DROP SEQUENCE IF EXISTS clients_id_seq CASCADE; 

CREATE SEQUENCE clients_id_seq START 5000;


CREATE TABLE clients (
    Id INT PRIMARY KEY DEFAULT NEXTVAL('clients_id_seq'),
    EmailAddress VARCHAR(255) UNIQUE,
    FirstName VARCHAR(128) NOT NULL,
    LastName VARCHAR(128) NOT NULL,
    PhoneNumber VARCHAR(15),
    Logo_Path VARCHAR(255),
    Sales_Id INT NOT NULL,
    Type VARCHAR(1),
    FOREIGN KEY (Sales_Id) REFERENCES users(Id)
);
--GRANT ALL ON clients TO faculty; --When running the lab on opentech, grant access to faculty 

INSERT INTO clients(EmailAddress, FirstName, LastName, PhoneNumber, Sales_id, Type)
VALUES ('joedoe@dcmail.ca', 'Joe', 'Doe', '(123)456-7890', 1002, 'c');

INSERT INTO clients(EmailAddress, FirstName, LastName, PhoneNumber, Sales_id, Type)
VALUES ('janepage@dcmail.ca', 'Jane', 'Page', '(123)456-7890', 1002, 'c');


DROP SEQUENCE IF EXISTS calls_id_seq CASCADE; 

CREATE SEQUENCE calls_id_seq START 100;

CREATE TABLE calls (
    Id INT PRIMARY KEY DEFAULT NEXTVAL('calls_id_seq'),
    Client_Id INT NOT NULL,
    Call_Time TIMESTAMP,
    Call_Description VARCHAR(255) NOT NULL,
    FOREIGN KEY (Client_Id) REFERENCES clients(Id)
);
--GRANT ALL ON calls TO faculty; --When running the lab on opentech, grant access to faculty 

INSERT INTO calls(Client_Id, Call_Time, Call_Description)
VALUES (5001, '2022-10-21 10:10:25', 'Called the client to get credit card information for last purchase refund');

INSERT INTO calls(Client_Id, Call_Time, Call_Description)
VALUES (5001, '2022-10-21 11:10:25', 'Called the client to inform that refund was successfuly processed.');


SELECT * FROM clients;
SELECT * FROM users;
SELECT * FROM calls;