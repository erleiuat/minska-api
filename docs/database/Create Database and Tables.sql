-- -------------- INITIALISIERUNG 'minska'
CREATE DATABASE IF NOT EXISTS minska DEFAULT CHARACTER SET utf8;
USE minska;


-- ---- TABLE 'user'
CREATE TABLE IF NOT EXISTS user (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Firstname           VARCHAR(255) NOT NULL,
    Lastname            VARCHAR(255) NOT NULL,
    Email               VARCHAR(89) NOT NULL,
    Email_Confirmed     BOOLEAN,
    Lang                ENUM('de', 'en') NOT NULL,
    Password            VARCHAR(255) NOT NULL,

    Stamp_Insert        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stamp_Update        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Stamp_Last_Login    TIMESTAMP NOT NULL,

    UNIQUE INDEX UNIQUE_Email (Email),
    PRIMARY KEY (ID)
);


-- ---- TABLE 'user_detail'
CREATE TABLE IF NOT EXISTS user_detail (
    User_ID             INT NOT NULL,
    Gender              ENUM('male', 'female') NOT NULL,
    Height              DOUBLE NOT NULL,
    Birthdate           DATE NOT NULL,
    Aim_Weight          DOUBLE NOT NULL,
    Aim_Date            DATE NOT NULL,

    Stamp_Insert        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stamp_Update        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (User_ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID)
);


-- ---- TABLE 'user_email_confirm'
CREATE TABLE IF NOT EXISTS user_email_confirm (
    User_ID             INT NOT NULL,
    Confirm_Code        VARCHAR(255) NOT NULL,

    Stamp_Insert        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stamp_Confirmed     TIMESTAMP,

    UNIQUE INDEX UNIQUE_Confirm_Code(Confirm_Code),

    PRIMARY KEY (User_ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID)
);


-- ---- TABLE 'user_license'
CREATE TABLE IF NOT EXISTS user_license (
    User_ID             INT NOT NULL,
    License_Code        VARCHAR(255) NOT NULL,
    Remove_Ads          BOOLEAN,
    Valid_From          TIMESTAMP,
    Valid_Till          TIMESTAMP,

    UNIQUE INDEX UNIQUE_License_Code(License_Code),

    PRIMARY KEY (User_ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID)
);


-- ---- TABLE 'weight'
CREATE TABLE IF NOT EXISTS weight (
    ID                  INT NOT NULL AUTO_INCREMENT,
    User_ID             INT NOT NULL,
    Weight              DOUBLE NOT NULL,

    Date_Weighed        DATE NOT NULL,
    Stamp_Insert        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID)
);


-- ---- TABLE 'calorie'
CREATE TABLE IF NOT EXISTS calorie (
    ID                  INT NOT NULL AUTO_INCREMENT,
    User_ID             INT NOT NULL,
    Title               VARCHAR(45) NOT NULL,
    Calories_per_100    DOUBLE NOT NULL,
    Amount              DOUBLE NOT NULL,

    Stamp_Consumed      TIMESTAMP NOT NULL,
    Stamp_Insert        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID)
);


-- ---- TABLE 'template'
CREATE TABLE IF NOT EXISTS template (
    ID                  INT NOT NULL AUTO_INCREMENT,
    User_ID             INT NOT NULL,
    Title               VARCHAR(45) NOT NULL,
    Calories_per_100    DOUBLE NOT NULL,
    Default_Amount      DOUBLE NOT NULL,
    Image               VARCHAR(255),

    Stamp_Last_Used     TIMESTAMP,
    Stamp_Insert        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stamp_Update        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID)
);
