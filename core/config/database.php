<?php

/**
 * DB_TYPE - the type of database (only mysql supported currently).
 * 
 * DB_SERVER_NAME - IP address or URI of database server.
 * 
 * DB_USERNAME - username used for database server authentication.
 * 
 * DB_PASSWORD - password used for database server authentication.
 * 
 * DB_PORT - port that connection to database server is made through.
 * 
 * DB_NAME - the name of the database that will be used on the server.
 * 
 * DSN - the data source name to connect to the database
**/

define(DB_TYPE, 'mysql');

define(DB_SERVER_NAME, 'localhost');

define(DB_USERNAME, 'khonsa');

define(DB_PASSWORD, 'khonsa');

define(DB_PORT, '3306');

define(DB_NAME, 'khonsa');

define(DSN, sprintf('%s:dbname=%s;host=%s;port=%s', DB_TYPE, DB_NAME, DB_SERVER_NAME, DB_PORT));