<?php 
    /*
    * Name: Angelica Kusik
    * Date: September 21, 2022
    * Course: Webd 3203-01
    */

    /*** VALIDATION ***/
    define("MAXIMUM_EMAIL_LENGTH", 255);
    define("MAX_FIRST_NAME_LENGTH", 128);
    define("MAX_LAST_NAME_LENGTH", 128);
    //Note: I defined the constants below based on my own
    //criteria - TODO: Check if is there any requirements regarding this
    define("MINIMUM_PASSWORD_LENGTH", 6);
    define("MAXIMUM_PASSWORD_LENGTH", 15);

    /*** COOKIES ***/
    define("COOKIE_LIFESPAN", "2592000"); //Note: 60x60x24x30 = 2592000 aka 1 month

    /*** USER TYPES ***/
    define("ADMIN", 's'); //admin
    define("AGENT", 'a'); //salesperson
    define("CLIENT", 'c'); //client
    define("PENDING", 'p');
    define("DISABLED", 'd'); 

    /*** DATABASE CONSTANTS FOR LOCAL DB***/
    define("DB_HOST", "127.0.0.1"); 
    define("DATABASE", "kusika_db");
    define("DB_ADMIN", "kusika");
    define("DB_PORT", "5432");
    define("DB_PASSWORD", "140810")
    
    /*** DATABASE CONSTANTS FOR OPENTECH***/
    // define("DB_HOST", "127.0.0.1"); 
    // define("DATABASE", "kusika_db");
    // define("DB_ADMIN", "kusika");
    // define("DB_PORT", "5432");
    // define("DB_PASSWORD", "angel140810") 

?>