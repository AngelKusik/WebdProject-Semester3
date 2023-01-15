<?php
    /*
    * Name: Angelica Kusik
    * Date: September 21, 2022
    * Last Updated: December 5
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

    //1) Selects a unique user from the database based using the user's email address (which is a primary key)
    $user_select = pg_prepare($conn, 'user_retrieve', 'SELECT * FROM users WHERE EmailAddress = $1');

    //2) Selects all users from the database - For testing purposes only
    $user_select_all = pg_prepare($conn, 'user_retrieve_all', 'SELECT * FROM users');

    //3) Updates the user's last login timestamp to the time when the user last logged in using the email address to identify the user.
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
    * Description: Checks the database for a matching record of the email address to see if the email entered
    * is unique. If a matching record is found, the email can not be used by new user.
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

    //Prepared statement to insert a salesperson into the database
    $user_insert = pg_prepare($conn, 'user_insert', "INSERT INTO users(EmailAddress, Password, FirstName, LastName, EnrolDate, active, Type) VALUES($1, $2, $3, $4, $5, $6, 'a')");
    
    /*
    * insert_user
    * Description: Inserts a new user into the database.
    */
    function insert_user($email, $password, $first_name, $last_name, $enrol_date, $active){
        global $conn;
        //execute the statement - Make sure the password is being hashed
        return pg_execute($conn, 'user_insert', array($email, password_hash($password, PASSWORD_BCRYPT), $first_name, $last_name, $enrol_date, $active));
    }

    /****************************************************************************/
    //Prepared statement to retrieve user by type (ADMIN vs AGENT)
    $user_type_select = pg_prepare($conn, "user_type_select", " SELECT * FROM users WHERE Type=$1");
    
    /*
    * user_type_select
    * Description: Returs all users from the specified type (ADMIN or AGENT - aka Salesperson)
    */
    function user_type_select($type)
    {
        global $conn;
        return pg_execute($conn, "user_type_select", array($type));
    }

    /****************************************************************************/
    //Prepared statement to retrieve all clients from a specific salesperson from the database
    $client_type_select = pg_prepare($conn, "client_type_select", " SELECT * FROM clients WHERE sales_id=$1");
    
    /*
    * client_type_select
    * Description: Returs all clients that belong to the salesperson who's id is being passed to the function
    */
    function client_type_select($id)
    {
        global $conn;
        return pg_execute($conn, "client_type_select", array($id));
    }

    /****************************************************************************/
    
    //Prepared statement to insert client into the database
    $client_insert = pg_prepare($conn, 'client_insert', "INSERT INTO clients(EmailAddress, FirstName, LastName, PhoneNumber, Logo_Path, Sales_Id, Type) VALUES($1, $2, $3, $4, $5, $6,'c')");

    /*
    * client_insert
    * Description: Inserts a new client into the database (in the clients table).
    */
    function client_insert($email, $first_name, $last_name, $phone_number, $logo_path, $salesperson_id){
        global $conn;
        //execute the statement 
        $exec = pg_execute($conn, 'client_insert', array($email, $first_name, $last_name, $phone_number, $logo_path, $salesperson_id));
        return $exec;
    }

    /****************************************************************************/

    //Prepared statement to retrieve all clients from the database
    $client_select_all = pg_prepare($conn, "client_select_all", "SELECT * FROM clients");

    /*
    * client_select_all
    * Description: Returns all clients from the database
    */
    
    function client_select_all()
    {
        global $conn;
        return pg_execute($conn, "client_select_all", array());
    }

    /****************************************************************************/

    //Prepared statement to retrieve sales_id from a specific client 
    $client_sales_id = pg_prepare($conn, "client_sales_id", "SELECT sales_id FROM clients WHERE id=$1");

    /*
    * client_sales_id
    * Description: Returns the sales id of the salesperson associated with the client who's id is being passed to the function
    * This function is used to see if client belongs to the salesperson selected on the calls form.
    */
    
    function client_sales_id($id)
    {
        global $conn;
        $results = pg_execute($conn, "client_sales_id", array($id));

        if (pg_num_rows($results)==1)
        {
            //If a record is found, use pg_fecth_result to retrieve it, and save it on client_sales_id
            $client_sales_id = pg_fetch_result($results,0); 
            return $client_sales_id;
        }
        else
        {
            //If a record was not found, return false
            return false;
        }
    }

    /****************************************************************************/
    //Prepared statement to return client based on client id
    $client_select = pg_prepare($conn, "client_retrieve", "SELECT * FROM clients WHERE id= $1");

    /*
    * client_select
    * Description: Returns a client from the database based on the client's id.
    */
    function client_select($client_id)
    {
        global $conn;

        $results = pg_execute($conn, 'client_retrieve', array($client_id));

        if (pg_num_rows($results)==1)
        {
            //If a record is found, use pg_fecth_assoc to retrieve it, and save it on user
            $client = pg_fetch_assoc($results,0);
            return $client;
        }
        else
        {
            //If a record was not found, return false
            return false;
        }
    }

    /****************************************************************************/
    //Prepared statement to check if a email exists on the clients table
    $client_select_email = pg_prepare($conn, 'client_retrieve_email', 'SELECT * FROM clients WHERE EmailAddress = $1');

     /*
    * client_select_email
    * Description: Takes in the user email and checks the database for a matching record
    */
    function client_select_email($email_id) 
    { //takes in the user email as a parameter and checks on the database if there is a matching record.
        global $conn;
        $results = pg_execute($conn, 'client_retrieve_email', array($email_id));

        if (pg_num_rows($results)==1)
        {
            //If a record is found, use pg_fecth_assoc to retrieve it, and save it on user
            $client = pg_fetch_assoc($results,0);
            return $client;
        }
        else
        {
            //If a record was not found, return false
            return false;
        }
    }

    /****************************************************************************/

    //Prepared statement to add a client call to the database
    $insert_call = pg_prepare($conn, "insert_call", "INSERT INTO calls(client_id, call_time, call_description) VALUES ($1, $2, $3)");

    /*
    * insert_call
    * Description: Inserts a call into the database.
    */
    function insert_call($client_id, $call_date_time, $call_description)
    {
        global $conn;
        return pg_execute($conn, "insert_call", array($client_id, $call_date_time, $call_description));
    }

     /****************************************************************************/

    /*
     * LAB 3 FUNCTIONS
     * 
     */
    
    /*
    * client_select_all_table
    * Description: Returns all clients from the database if user is an ADMIN or if user is a salesperson
    * returns all clients that belong to this salesperson.
    */
    function client_select_all_table($page)
    {
        global $conn;
        $result = "";
        $arr = array();

        //if user is an admin return all clients
        if ($_SESSION['user']['type']=='s'){

            $result = client_select_all();           

        }
        else{
            //if user is a salesperson, return only clients that belong to them
            $result = client_type_select($_SESSION['user']['id']);

        }

        $count = pg_num_rows($result);

        for ($i = ($page -1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++){
            
            array_push($arr, pg_fetch_assoc($result, $i));

        }
        
        return $arr;
    }

    /****************************************************************************/
    /*
    * client_count
    * Description: Returns the number of clients on the database if user is an admin, or if
    * user is a salesperson returns the number of clients that belong to this salesperson.
    */
    function client_count(){
        global $conn;
        $result = "";

        //if user is an ADMIN
        if($_SESSION['user']['type'] == 's'){

            $result = client_select_all(); 
        }
        else{
            //if user is a salesperson
            $result = client_type_select($_SESSION['user']['id']);
        }
        return pg_num_rows($result);
    }

    /****************************************************************************/
    //Prepared statement to retrieve all calls from the database
    $call_select_all = pg_prepare($conn, "call_select_all", "SELECT * FROM calls");

    /*
    * call_select_all
    * Description: Returns all calls from the database
    */
    
    function call_select_all()
    {
        global $conn;
        return pg_execute($conn, "call_select_all", array());
    }

     /****************************************************************************/
    //Prepared statement to retrieve all client calls from a specific salesperson from the database
    $call_type_select = pg_prepare($conn, "call_type_select", "SELECT calls.id, calls.client_id, calls.call_time, calls.call_description FROM calls INNER JOIN clients ON calls.client_id=clients.Id WHERE sales_id=$1");
    
    /*
    * call_type_select
    * Description: Returs all client calls that belong to the salesperson who's id is being passed to the function
    */
    function call_type_select($id)
    {
        global $conn;
        return pg_execute($conn, "call_type_select", array($id));
    }

    /****************************************************************************/

    /*
    * call_select_all_table
    * Description: Returns all calls from the database if user is an ADMIN or if user is a salesperson
    * returns all calls from the clients that belong to this salesperson.
    */
    function call_select_all_table($page)
    {
        global $conn;
        $result = "";
        $arr = array();

        //if user is an admin return all calls
        if ($_SESSION['user']['type']=='s'){

            $result = call_select_all();           
        }
        else{
            //if user is a salesperson, return only calls from their own clients
            $result = call_type_select($_SESSION['user']['id']);

        }

        $count = pg_num_rows($result);

        for ($i = ($page -1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++){
            
            array_push($arr, pg_fetch_assoc($result, $i));

        }
        
        return $arr;
    }

    /****************************************************************************/
    /*
    * call_count
    * Description: Returns the number of calls on the database if user is an admin, or if
    * user is a salesperson returns the number of calls to this salesperson clients.
    */
    function call_count(){
        global $conn;
        $result = "";

        //if user is an ADMIN
        if($_SESSION['user']['type'] == 's'){

            $result = call_select_all(); 
        }
        else{
            //if user is a salesperson
            $result = call_type_select($_SESSION['user']['id']);
        }
        return pg_num_rows($result);
    }

    /****************************************************************************/
    //Prepared statement to retrieve all salesperson from the database
    $salesperson_select_all= pg_prepare($conn, "salesperson_select_all", " SELECT id, EmailAddress, FirstName, LastName, phoneNumber, active FROM users WHERE Type='a'");
    
    /*
    * salesperson_select_all
    * Description: Returs all salesperson from the database
    */
    function salesperson_select_all()
    {
        global $conn;
        return pg_execute($conn, "salesperson_select_all", array());
    }

     /****************************************************************************/

    /*
    * salesperson_select_all_table
    * Description: Returns all salesperson from the database if user is an ADMIN. Users type
    * salesperson do not have access to salesperson page.
    */
    function salesperson_select_all_table($page)
    {
        global $conn;
        $result = "";
        $arr = array();

        //if user is an admin return all salesperson
        if ($_SESSION['user']['type']=='s'){

            $result = salesperson_select_all();        
        }

        $count = pg_num_rows($result);

        for ($i = ($page -1) * RECORDS; $i < $count && $i < $page * RECORDS; $i++){
            
            array_push($arr, pg_fetch_assoc($result, $i));

        }
        
        return $arr;
    }

    /****************************************************************************/
    /*
    * salesperson_count
    * Description: Returns the number of salesperson on the database.
    */
    function salesperson_count(){
        global $conn;
        $result = "";

        //if user is an ADMIN
        if($_SESSION['user']['type'] == 's'){

            $result = salesperson_select_all(); 
        }

        return pg_num_rows($result);
    }

    /****************************************************************************/
     /*
      * confirm_password
      * Verifies if password entered matches the password saved on the databse.
      */
    function confirm_password($password, &$error){

        $is_valid = true;

       // dump($_SESSION['user']);

        //Pull the user info from the database
        $user = user_select($_SESSION['user']['emailaddress']);

        //Get the user's current password 
        //Note: I could also get the password from the session, but I thought that it would be
        //more reliable to get it from the database.
        
        if(!($user && password_verify($password, $user['password'])))
        {
            //if password entered doesn't match password on the database
            $is_valid = false;
            $error .= "Your current password is invalid.<br/>";
        }
    
        return $is_valid;
    }

    /****************************************************************************/
    //Create a query to update the password
    $update_password = pg_prepare($conn, 'update_password', 'UPDATE users SET Password = $1 WHERE id = $2');

    /*
      * update_password
      * Changes user's password on the database.
      */
    function update_password($new_password, $user_id){
        global $conn;

         //execute the statement - Make sure the password is being hashed
         return pg_execute($conn, 'update_password', array(password_hash($new_password, PASSWORD_BCRYPT), $user_id));
    }

    /****************************************************************************/

    /*
     * LAB 4 FUNCTIONS
     * 
     */

     /*
      * reset_password
      * Resets salesperson's password to a random password and update it on the database.
      */
      function reset_password($user){
        global $conn;

        //generate new random password
        $random_password = uniqid();
        $user_id = $user['id'];

        //Update the password
        update_password($random_password, $user_id); 
        
        return $random_password;
      }

      /****************************************************************************/
      //Create a query to inactivate a salesperson
      $update_user_status = pg_prepare($conn, 'update_user_status', 'UPDATE users SET active = $1 WHERE EmailAddress = $2');

      /*
      * reset_password
      * Resets salesperson's password to a random password and update it on the database.
      */
      function update_user_status($active, $userId){
        global $conn;

        return pg_execute($conn, 'update_user_status', array($active, $userId));

      }

    




    

    






?>