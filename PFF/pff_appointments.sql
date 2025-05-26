CREATE DATABASE pff_appointments;

USE pff_appointments;

CREATE TABLE users (
    userID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fname VARCHAR(30) NOT NULL,
    lname VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    userPW VARCHAR(255) NOT NULL
);

Create Table pets (
petID int primary key auto_increment not null,
petName varchar(30) not null,
  ownerID INT NOT NULL,
FOREIGN KEY (ownerID) REFERENCES users(userID)
);

INSERT INTO users (userID, fname, lname, email, userPW) VALUES (1, 'John', 'Doe', 'john.doe@example.com', 'some_hashed_password');

CREATE TABLE appointments (
    appID INT AUTO_INCREMENT PRIMARY KEY,
    service VARCHAR(50),
    name VARCHAR(100),
    phone VARCHAR(15),
    email VARCHAR(100),
    appointment_date DATE,
     petID INT NOT NULL,
    FOREIGN KEY (petID) REFERENCES pets(petID),
    userID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES users(userID)
);
ALTER TABLE appointments ADD status VARCHAR(50) DEFAULT 'Pending';

CREATE TABLE reviews ( 
reviewID int primary key auto_increment not null,
fname varchar(30) not null,
lname varchar(30) not null,
feedback text not null,
date date
);

Create Table pets (
petID int primary key auto_increment not null,
petName varchar(30) not null,
  ownerID INT NOT NULL,
FOREIGN KEY (ownerID) REFERENCES users(userID)
);