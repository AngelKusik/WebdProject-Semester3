<?php
    /*
    * Name: Angelica Kusik
    * Date: September 21, 2022
    Last Updated: October 5
    * Course: Webd 3203-01
    */

    //require ("contants.php"); -- For testing purposes only
    
    //DB FUNCTIONS
    //Description: This file contains all functions related to the database

    /*********************************************************************************************************/

    /*
    * db_connect
    * Description: Establishes the connection with the database
    */
    function db_connect() {
        return pg_connect("host=".DB_HOST." port=".DB_PORT." dbname=".DATABASE." user=".DB_ADMIN." password=".DB_PASSWORD);

    }
    //Store the connection in a variable to facilitate passing it along to other elements.
    $conn = db_connect();

    /********************************************************************************************************/
    //PREPARED STATEMENTS

    //1) This statement selects an specific user from the database 
    $user_select_stmt = pg_prepare($conn, 'user_retrieve', 'SELECT * FROM users WHERE EmailAddress = $1');

    //2) This statement selects all users from the database - For testing purposes only
    $user_select_all = pg_prepare($conn, 'user_retrieve_all', 'SELECT * FROM users');

    //3) This statement updates the last login timestamp according to the current time when the user last logged in.
    $update_last_login = pg_prepare($conn, 'update_last_login', 'UPDATE users SET LastAccess = $1 WHERE EmailAddress = $2');

    //Return all user record from the database - For testing purposes only
    // $results = pg_execute($conn, 'user_retrieve_all', array()); //array has to be passed, even if there is no parameters for it

    //     if (pg_num_rows($results) > 0)
    //     {
    //         //Print all records
    //         for($i = 0; $i < pg_num_rows($results); $i++)
    //         {
    //             $user = pg_fetch_assoc($results, $i);
    //             dump($user);
    //         }
    //     }

    /********************************************************************************************************/
    /*
    * user_select
    * Description: Takes in the user email and checks the database for a matching record
    */
    function user_select($email_id) 
    { //takes in the user email as a parameter and checks on the database if there is a matching record.
        global $conn;
        //Execute the prepared statement by passing in the connection, the statement, and the email
        //Note: Here we can use the email to identify the user because the email is defined as unique on the database, so 
        //it can only return one record back.
        $results = pg_execute($conn, 'user_retrieve', array($email_id));

        if (pg_num_rows($results)==1)
        {
            //If a record is found, use pg_fecth_assoc to retrieve it, and save it on user
            $user = pg_fetch_assoc($results,0);
            return $user;
        }
        else
        {
            //If a record was not found, return false
            return false;
        }
    }

    /********************************************************************************************************/
    /*
    * user_authenticate
    * Description: Takes in the user email and plain password, and using the user_select funtion,
    * checks if the email is registered on the dabase, and if so, if the password entered matches the password
    * recorded on the database for that user. Returns all user records if true, or false if email or password doesn't validate
    */
    function user_authenticate($plain_password, $email_id) 
    {        
        //Check on the database if there is a record for the email entered using the user_select function
        $user = user_select($email_id);

        
        if($user && password_verify($plain_password, $user['password']))
        {
            //if user is validated, update last login time
            user_update_login_time($email_id);
            return $user;
        }
        else
        {
            return false;
        }
    }

    /********************************************************************************************************/
    /*
    * user_update_login_time
    * Description: Wehn user successfully logs in, updates the value of lastAccess to the current time
    * the user logged in.
    */

    function user_update_login_time($email) //current time is the $now variable at the sign in page $current_time,
    {
        global $conn;
        global $now;

        pg_execute($conn, 'update_last_login', array($now, $email));

    }

    /****************************************************************************/

    /*
     * LAB 2 FUNCTIONS
     * 
     */
    //Prepare statement to insert a sales person into the database
    $user_insert = pg_prepare($conn, 'user_insert', "INSERT INTO users(EmailAddress, Password, FirstName, LastName, EnrolDate, Type) VALUES($1, $2, $3, $4, $5, 'a')");
    
    function insert_user($email, $password, $first_name, $last_name, $enrol_date){
        global $conn;
        //execute the statement - Make sure the password is being hashed
        return pg_execute($conn, 'user_insert', array($email, password_hash($password, PASSWORD_BCRYPT), $first_name, $last_name, $enrol_date));
    }

    // $user_type_select = pg_prepare($conn, "user_type_select", "SELECT * FROM users WHERE Type=$1");
    // function user_type_select($type){
    //     global $conn;
    //     //$user_select = pg_prepare()
    //     return pg_execute($conn, "user_type_select", array($type))
    // }


?>