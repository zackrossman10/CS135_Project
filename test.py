#!/usr/bin/python
# -*- coding: utf-8 -*-
# import libraries

import urllib2
from bs4 import BeautifulSoup
import csv
import sys
from time import gmtime, strftime
import datetime
from selenium import webdriver
import os
import mysql.connector

#driver to load the webpage before scraping
chromedriver = "/Users/zackrossman/Downloads/chromedriver"
os.environ["webdriver.chrome.driver"] = chromedriver
driver = webdriver.Chrome(chromedriver)

print "...Driver started..."

config = {
  'user': 'root',
  'password': 'root',
  'unix_socket': '/Applications/MAMP/tmp/mysql/mysql.sock',
  'database': 'TEST_SCIAC',
  'raise_on_warnings': True,
}

#attemp connecting to db
try:
    link = mysql.connector.connect(**config)
except:
    print "Unexpected error: Can't connect to MySQL database"

#cursor into db
cursor = link.cursor()

#get website to scrape, passed to commmand line by PHP
php_param = sys.argv[1]

#determine whether a player (cap_num and name) already exists in db
def exists(cap_num1, name1, table):
    stmt = "SELECT * FROM "+table+" WHERE cap_num = '"+cap_num1+"' AND name = '"+name1+"'"
    cursor.execute(stmt)
    row = cursor.fetchone()
    #if the query is non-empty, player exists
    if(row is not None):
        return True
    #if query is empty, player does not exist
    return False

#update a field player's stats (theye may or may not exist in db)
def insertFieldPlayer(player, team):
    string = player.get_text(separator = ",")
    array = string.split(",")
    cap_num1 = array[0]
    name1 = array[1].replace("'","")
    if(exists(cap_num1, name1, "FIELD_PLAYERS")):
        #if field player exists, update his stat totals
        field_player_query = "UPDATE FIELD_PLAYERS SET shots=shots+{}, goals=goals+{}, assists=assists+{}, points=points+{}, exclusions=exclusions+{}, drawn_exc=drawn_exc+{}, steals=steals+{} WHERE cap_num='{}' AND name='{}'".format(array[2], array[3], array[4], array[5], array[6], array[7], array[8], cap_num1, name1)
    else:
        #if field player doesn't exist, create new field player
        field_player_query = "INSERT INTO FIELD_PLAYERS (cap_num, name, team, shots, goals, assists, points, exclusions, drawn_exc, steals) VALUES ('{}', '{}', '{}', {}, {}, {}, {}, {}, {}, {})".format(cap_num1, name1, team.replace("'",""), array[2], array[3], array[4], array[5], array[6], array[7], array[8])
    cursor.execute(field_player_query)

#update a goalie's stats (theye may or may not exist in db)
def insertGoalie(player, team):
    string = player.get_text(separator = ",")
    array = string.split(",")
    cap_num1 = array[0]
    name1 = array[1].replace("'","")
    if(exists(cap_num1, name1, "GOALIES")):
        #if goalie exists, update his stat totals
        goalie_query = "UPDATE GOALIES SET goals_allowed=goals_allowed+{}, saves=saves+{}, steals=steals+{} WHERE cap_num='{}' AND name='{}'".format(array[2], array[3], array[5], cap_num1, name1)
    else:
        #if goalie doesn't exist, create new player
        goalie_query = "INSERT INTO GOALIES (cap_num, name, team, goals_allowed, saves, steals) VALUES ('{}', '{}', '{}', {}, {}, {})".format(cap_num1, name1, team.replace("'",""), array[2], array[3], array[5])
    cursor.execute(goalie_query)

#main body of the web scraper
def scrape(php_param):
    #set up web scraper using Beautiful Soup and Selenium
    driver.get(php_param)
    html = driver.page_source
    soup = BeautifulSoup(html, "html.parser")

    #print soup.prettify()
    teams = soup.findAll("div", attrs={"class" : "teamheaders"})
    teamNames = [teams[0].text.strip(), teams[1].text.strip()]
    for index in range(0,2):
        #shorten the string holding team name until there are no digits in it
        while(teamNames[index][-1:].isdigit()):
            teamNames[index] = teamNames[index][:-1]

    #find stats for both white and dark teams
    tables = ["whitestatstable", "darkstatstable"]
    #keeps track of which team the player belongs to
    team_index = 0
    for table in tables:
        players =  soup.find("table", attrs={"id": table}).find("tbody", attrs={"id": "playbyplay"}).findAll("tr")
        for player in players:
            insertFieldPlayer(player, teamNames[team_index])
        team_index+=1

    #get goalie stats
    tables = ["whitegoalietable", "darkgoalietable"]
    #keeps track of which team the player belongs to
    team_index = 0
    for table in tables:
        players =  soup.find("table", attrs={"id": table}).find("tbody", attrs={"id": "playbyplay"}).findAll("tr")
        for player in players:
            insertGoalie(player, teamNames[team_index])
        team_index+=1

    #close connection and commit executions
    cursor.close()
    link.commit()
    link.close()
    print "...Data upload complete"
    driver.quit()

#execute
scrape(php_param)
