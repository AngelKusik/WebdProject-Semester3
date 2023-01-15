<?php
/*
 * Name: Angelica Kusik
 * Date: October 21, 2022
 * Last Updated: November 7
 * Course: Webd 3203-01
 */

$file = "calls.php";
$date = "October 20, 2022";
$title = "Call Registration";
$description = "Registration page for client calls";

include "./includes/header.php";

//Variables Declarations
$today = date("Ymd");
$now = date("Y-m-d G:i:s");
$handle = fopen("./logs/".$today."_log.txt", 'a');
$error_message = "";
$message = "";
$salesperson_id  = "";
$client_id  = "";
$call_date_time  = "";
$call_description  = "";
$user = $_SESSION['user'];



//Check if user has a session (doesn't matter if it's as a salesperson or admin)
if(!(isset($_SESSION['user']))){
  //if user doesn't, redirect to sign-in page
  redirect_user("sign-in.php");
}

//Collect the data from the form
if($_SERVER['REQUEST_METHOD'] == "POST"){
    //check user type:
    if(isset($_SESSION['user'])&&($_SESSION['user']['type']==ADMIN))
    {
      $salesperson_id = trim($_POST['inputSalesperson']);
    
    }
    elseif(isset($_SESSION['user'])&&($_SESSION['user']['type']==AGENT))
    {
      //if user is a salesperson, set sales_id equal to user's id
      $salesperson_id = $user['id'];
    }
    $client_id = trim($_POST['inputClient']);
    $call_date_time = trim($_POST['inputDate']);
    $call_description = trim($_POST['inputCallDescription']);


    //Validation

    //validate sales_id:
    validate_sales_id($salesperson_id, $error_message);

    //validate client_id
    validate_client_id($salesperson_id, $client_id, $error_message);

    //validate call date & time
    $is_valid = validate_call_datetime($call_date_time, $error_message);

    if ($is_valid == false){
        //if date & time is not valid, reset it
        $call_date_time = "";
    }

    //validate call description
    $is_valid = validate_call_description($call_description, $error_message);

    if ($is_valid == false){
        //if call_description is not valid, reset it
        $call_description = "";
    }

    //Once all validation is completed and all inputs are valid
    if($error_message == "") {
        //carry on

        //Use a try & catch block to handle any unexpected issues while inserting the call on the database
        try
        {
            //create prepared statement
            insert_call($client_id, $call_date_time,  $call_description); 
            //get the client information
            $client = client_select($client_id);
            set_message("The call to client " .$client['firstname']. " " . $client['lastname']. " was succesfully registered."); 

            //reset all variables
            $call_date_time  = "";
            $call_description  = "";

            //Register on the logs that new client was successfully added
            fwrite($handle, "A call to client " .$client['firstname']. " " . $client['lastname']. " was succesfully registered by user: " .$user['firstname']. " at " .$now. ".\n");

            //Close the log file
            fclose($handle); 

        }
        catch (Exception $e)
        {
        $error_message = "An unexpected error has occured: " .$e->getMessage(). "\n";
        }
    }
    else {
        $error_message .= "</br> Please try again";
        //set message equal to error message because the variable message is the one that gets displayed to the user
        set_message($error_message);

        //Register on the logs that new salesperson was successfully added
        fwrite($handle, "Failed attempt to add new client by user: ".$user['firstname']." at ".$now.".\n");

        //Close the log file
        fclose($handle);
    }
}

//flash message - to display error messages and successful register 
if(is_message())
{
    echo "<h3>" . get_message() . "</h3>";
    remove_message();
}

?>


<?php

//function to dinamically display the form
$form_calls = array(
array(
    "type" => "select",
    "name"=>"inputSalesperson",
    "value"=>$salesperson_id,
    "label"=>"Select Salesperson Id"
    ),
  array(
    "type" => "select",
    "name"=>"inputClient",
    "value"=>$client_id,
    "label"=>"Select Client Id"
  ),
  array(
    "type" => "datetime-local",
    "name"=>"inputDate",
    "value"=>$call_date_time,
    "label"=>"Date and Time"
  ),
  array(
    "type" => "text",
    "name"=>"inputCallDescription",
    "value"=>$call_description,
    "label"=>"Call Description"
  ),
  array(
    "type" => "submit",
    "name"=>"",
    "value"=>"",
    "label"=>"Register"
  ),
  array(
    "type" => "reset",
    "name"=>"",
    "value"=>"",
    "label"=>"Reset"
  )
);

display_Form($form_calls);

//Set pagination to first page (default)
$page = 1;
//If another page is selected on the pagination, set page equal to the selected page
if(isset($_GET['page'])){
  $page = $_GET['page'];
}

//Call the display_table funtion to display results from database
display_table(
  array(
    "id" => "ID",
    "client_id" => "Client ID",
    "call_time" => "Call Time",
    "call_description" => "Call Description"
  ),
  call_select_all_table($page),
  call_count(),
  $page,
  array(
    true => "Active",
    false => "Inactive"
  )
);

?>
