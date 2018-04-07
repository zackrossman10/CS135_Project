DROP DATABASE IF EXISTS TEST_SCIAC;

CREATE DATABASE TEST_SCIAC;

GRANT ALL PRIVILEGES ON TEST_SCIAC.* to zack@localhost IDENTIFIED BY 'rossman';

USE TEST_SCIAC;

CREATE TABLE FIELD_PLAYERS(
  playerid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cap_num VARCHAR(256) NOT NULL,
  name VARCHAR(256) NOT NULL,
  team VARCHAR(256) NOT NULL,
  shots INT NOT NULL,
  assists INT NOT NULL,
  points INT NOT NULL,
  exclusions INT NOT NULL,
  drawn_exc INT NOT NULL,
  steals INT NOT NULL
);

CREATE TABLE GOALIES(
  goalieid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cap_num VARCHAR(256) NOT NULL,
  name VARCHAR(256) NOT NULL,
  team VARCHAR(256) NOT NULL,
  goals_allowed INT NOT NULL,
  saves INT NOT NULL,
  steals INT NOT NULL
);

CREATE TABLE GAMEURLS(
  gameid INT NOT NULL PRIMARY KEY,
  urls VARCHAR(256) NOT NULL
);

CREATE TABLE USERS(
  userid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  password VARCHAR(256) NOT NULL,
  name VARCHAR(256) NOT NULL
);

CREATE TABLE TEAMS(
  teamid INT NOT NULL,
  name VARCHAR(256) NOT NULL,
  fp1id INT NOT NULL,
  fp2id INT NOT NULL,
  fp3id INT NOT NULL,
  fp4id INT NOT NULL,
  fp5id INT NOT NULL,
  fp6id INT NOT NULL,
  gid INT NOT NULL,
  FOREIGN KEY (teamid) REFERENCES USERS (userid),
  FOREIGN KEY (fp1id) REFERENCES FIELD_PLAYERS (playerid),
  FOREIGN KEY (fp2id) REFERENCES FIELD_PLAYERS (playerid),
  FOREIGN KEY (fp3id) REFERENCES FIELD_PLAYERS (playerid),
  FOREIGN KEY (fp4id) REFERENCES FIELD_PLAYERS (playerid),
  FOREIGN KEY (fp5id) REFERENCES FIELD_PLAYERS (playerid),
  FOREIGN KEY (fp6id) REFERENCES FIELD_PLAYERS (playerid),
  FOREIGN KEY (gid) REFERENCES GOALIES (goalieid)
);
