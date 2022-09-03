CREATE TABLE userbase (
    user_id INT NOT NULL AUTO_INCREMENT,
    user_email VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_major VARCHAR(255) NOT NULL,
    PRIMARY KEY (user_id) 
);