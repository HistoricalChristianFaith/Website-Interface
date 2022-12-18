import requests
from bs4 import BeautifulSoup
import unicodedata
import html
import json
import sys
import pathlib

from io import StringIO
from html.parser import HTMLParser

import os, shutil;
import tomlkit
import sqlite3
import uuid
import csv

database_file_location = 'data.sqlite'

try:
    sqliteConnection = sqlite3.connect(database_file_location)
    cursor = sqliteConnection.cursor()
    print("Database created and Successfully Connected to SQLite")

    cursor.execute('''CREATE TABLE IF NOT EXISTS "bible_kjv" (
        "book" VARCHAR,
        "txt_location" INTEGER,
        "txt" TEXT
    )
    ;''')
    cursor.execute('''CREATE INDEX bible_kjv_book ON bible_kjv (book);''')
    cursor.execute('''CREATE INDEX bible_kjv_txt_location ON bible_kjv (txt_location);''')
    
    kjvfile = open('bible_kjv.csv')
    kjvcontents = csv.reader(kjvfile)

    sqlite_insert_query = """INSERT INTO bible_kjv
                        (book, txt_location, txt) 
                        VALUES (?, ?, ?)"""
    cursor.executemany(sqlite_insert_query, kjvcontents)
    sqliteConnection.commit()
    print("Total", cursor.rowcount, "Records inserted successfully")
    sqliteConnection.commit()
    cursor.close()

except sqlite3.Error as error:
    print("Error:", error)
finally:
    if sqliteConnection:
        sqliteConnection.close()
        print("The SQLite connection is closed")