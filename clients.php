<?php
/*
 * Name: Angelica Kusik
 * Date: October 20, 2022
 * Last Updated: November 7
 * Course: Webd 3203-01
 */

$file = "clients.php";
$date = "October 20, 2022";
$title = "Client Registration";
$description = "Registration page for a salesperson";

include "./includes/header.php";

//Variables Declarations
$today = date("Ymd");
$now = date("Y-m-d G:i:s");
$handle = fopen("./logs/".$today."_log.txt", 'a');
$error_message = "";
$message = "";
$first_name = "";
$last_name = "";
$email_address = "";
$phone_number = "";
$logo_path = "";
$salesperson_id  = "";
$user = $_SESSION['user'];



//Check if user has a session (doesn't matter if it's as a salesperson or admin)
if(!(isset($_SESSION['user']))){
  //if user doesn't, redirect to sign-in page
  redirect_user("sign-in.php");
}

//Collect the data from the form
if($_SERVER['REQUEST_METHOD'] == "POST"){
  $first_name = trim($_POST['inputFirstName']);
  $last_name = trim($_POST['inputLastName']);
  $email_address = trim($_POST['inputEmail']);
  $phone_number = trim($_POST['inputPhoneNumber']);
  //check user type:
  if(isset($_SESSION['user'])&&($_SESSION['user']['type']==ADMIN))
  {
    //if user is an Admin get the salesperson value from the form
    $salesperson_id = trim($_POST['inputSalesperson']);
  
  }
  elseif(isset($_SESSION['user'])&&($_SESSION['user']['type']==AGENT))
  {
    //if user is a salesperson, set sales_id equal to user's id
    $salesperson_id = $user['id'];
  }

  //Validation

  //Validate first name
  $is_valid = validate_first_name($first_name, $error_message);

  if ($is_valid == false){
    //if first name is not valid, reset it
    $first_name = "";
  }

  //validate last name
  $is_valid = validate_last_name($last_name, $error_message);

  if ($is_valid == false){
    //if last name is not valid, reset it
    $last_name = "";
  }

  //validate email address
  $is_valid = validate_email_address($email_address, $error_message);

  if ($is_valid == false){
    //if email is not valid, reset it
    $email_address = "";
  }

  //validate phone number 
  $is_valid = validate_phone_number($phone_number, $error_message);

  if ($is_valid == false){
    //if password1 is invalid, set both password1 and password2 to blank
    $phone_number = "";
  } 

  //validate sales_id:
  validate_sales_id($salesperson_id, $error_message);

  //validate logo_path
  $is_valid = validate_logo_path($error_message);

  if($is_valid == true){
    //if file is valid, move the temp file to its permanent home
    $logo_path = "./files_uploaded/".$email_address."_new_file.jpeg";
    move_uploaded_file($_FILES['uploadFileName']['tmp_name'], $logo_path);
    //here in a "subfolder" sub-directory with a file name "new_file.jpeg" ??
  }
  else{
    //if logo path is not valid, set it to blank
    $logo_path = "";
  }

 
  //Once all validation is completed and all inputs are valid
  if($error_message == "") {
    //carry on

    //Use a try & catch block to handle any unexpected issues while inserting the client on the database
    try
    {
      //create prepared statement
      client_insert($email_address, $first_name, $last_name, $phone_number, $logo_path, $salesperson_id);
      set_message("Congratulations. The client " .$first_name . " " . $last_name . " was succesfully registered."); 

      //reset the variables
      $first_name = "";
      $last_name = "";
      $email_address = "";
      $phone_number = "";
      $logo_path = "";

      //Register on the logs that new client was successfully added
      fwrite($handle, "New client ".$first_name. " " .$last_name." was successfully added at " .$now. " by the user: ".$user['firstname'].".\n");

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

//flash message - to display error messages and successful login message 
if(is_message())
{
    echo "<h3>" . get_message() . "</h3>";
    remove_message();
}

?>


<?php

//function to dinamically display the form
$form_client = array(
  array(
    "type" => "text",
    "name"=>"inputFirstName",
    "value"=>$first_name,
    "label"=>"First Name"
  ),
  array(
    "type" => "text",
    "name"=>"inputLastName",
    "value"=>$last_name,
    "label"=>"Last Name"
  ),
  array(
    "type" => "email",
    "name"=>"inputEmail",
    "value"=>$email_address,
    "label"=>"Email Address"
  ),
  array(
    "type" => "phone",
    "name"=>"inputPhoneNumber",
    "value"=>$phone_number,
    "label"=>"Phone Number"
  ),
  array(
    "type" => "file",
    "name"=>"uploadFileName",
    "value"=>$logo_path,
    "label"=>"Select a file for upload"
  ),
  array(
    "type" => "select",
    "name"=>"inputSalesperson",
    "value"=>$salesperson_id,
    "label"=>"Select Salesperson"
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
//Call the display form function
display_Form($form_client);
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
    "emailaddress" => "Email Address",
    "firstname" => "First Name",
    "lastname" => "Last Name",
    "phonenumber" => "Phone Number",
    "logo_path" => "Logo"
  ),
  client_select_all_table($page),
  client_count(),
  $page,
  array(
    true => "Active",
    false => "Inactive"
  )
);

?>


<?php
include "./includes/footer.php";
?>

