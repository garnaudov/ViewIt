CREATE DATABASE IF NOT EXISTS viewItdb;

USE viewItdb;

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    fn INT default NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS galleries (
    name VARCHAR(256) NOT NULL,
    owner VARCHAR(256) NOT NULL,
    PRIMARY KEY (name)
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS photos (
    id INT NOT NULL AUTO_INCREMENT,
    description VARCHAR(256) NOT NULL,
    path VARCHAR(256) NOT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS users_galleries (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(256) NOT NULL,
    gallery_name VARCHAR(256) NOT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS photos_galleries (
    photo_id VARCHAR(256) NOT NULL,
    gallery_name VARCHAR(256) NOT NULL,
    PRIMARY KEY (photo_id)
) ENGINE = INNODB;

INSERT INTO
    users (id, username, password, fn)
VALUES
    (1, 'gkarnaudov', 'password', 62276),
    (2, 'mdzhambaz', 'password', 62248),
    (3, 'yayankov', 'password', 62333),
    (4, 'ptodorova', 'password', 62424);
