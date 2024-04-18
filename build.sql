DROP TABLE IF EXISTS rating CASCADE;
DROP TABLE IF EXISTS User_Ingredients CASCADE;
DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
userid INT auto_increment,
username VARCHAR (255),
useremail VARCHAR(255) UNIQUE,
firstname VARCHAR (255),
lastname VARCHAR (255),
bio TEXT,
profilepic VARCHAR (225),
userpass VARCHAR (255),
PRIMARY KEY (userid)
) ENGINE = innodb;

CREATE TABLE User_Ingredients (
  name VARCHAR(100) NOT NULL,
  userid VARCHAR(100) NOT NULL,
  PRIMARY KEY (name, userid),
  CONSTRAINT FK_user_ing FOREIGN KEY (userid) REFERENCES users(useremail) ON DELETE CASCADE
 ) ENGINE = innodb;

CREATE TABLE rating (
    recipeid VARCHAR(255),
    userid VARCHAR(255),
    rating INT,
    recipelabel VARCHAR(255),
    recipeimage VARCHAR(2000),
    comment VARCHAR(255) DEFAULT NULL,
    CONSTRAINT FK_user FOREIGN KEY (userid) REFERENCES users(useremail),
    CONSTRAINT PK_rating PRIMARY KEY (recipeid, userid)
);
