<?php
/*
 * Name: Angelica Kusik
 * Date: October 5, 2022
 * Last Updated: October 17
 * Course: Webd 3203-01
 */

$file = "salesperson.php";
$date = "October 5, 2022";
$title = "Salesperson Registration";
$description = "Registration page for a sales person";

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
$password1 = "";
$password2 = "";


//Check if admin user has a valid session, otherwise redirect user to sign-in page 
//Only admin user can access this page!
if(!(isset($_SESSION['user']['type'])&&($_SESSION['user']['type']==ADMIN))){
  //display a message on sign-in page explaining why user was redirected
  set_message("To access the Salesperson Registration page you must log in as an Admin.");
  //if user doesn't have a valid session or user is not type admin 
  redirect_user("sign-in.php");
}

//Collect the data from the form
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $first_name = trim($_POST['inputFirstName']);
    $last_name = trim($_POST['inputLastName']);
    $email_address = trim($_POST['inputEmail']);
    $password1 = trim($_POST['inputPassword1']);
    $password2 = trim($_POST['inputPassword2']);


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

  //validate password
  $is_valid = validate_password($password1, $error_message);

  if ($is_valid == false){
    //if password1 is invalid, set both password1 and password2 to blank
    $password1 = "";
    $password2 = "";
  }
  else {
    //if password1 is valid, check if password2 matches it
    $is_valid = validate_password_match($password1, $password2, $error_message);
    if ($is_valid == false){
      //if passwords don't match, set them both to blank
      $password1 = "";
      $password2 = "";
    }
  }

  //Once all validation is completed and all inputs are valid
  if($error_message == "") {
    //carry on

    //create prepared statement
    insert_user($email_address, $password1, $first_name, $last_name, $now);
    set_message("Congratulations. The user " .$first_name . " " . $last_name . " was succesfully register as a sales person.");

    //Register on the logs that new salesperson was successfully added
    //fwrite($handle, "New salesperson ".$first_name. " " .$last_name." was successfully added at " .$now. " by ".$_SESSION['firstname'].".\n");

    //Close the log file
    //fclose($handle);
    //===================TODO: why when we redirect from salesperson to sign-in it a)doesn't show
    // ================== the error message, b)shows navbar as if user is loged in but doesn't redirect
    // ================== to dashboard, c) why logout button doesn't fuck work, 
  }
  else {
    $error_message .= "</br> Please try again";
    //set message equal to error message because the variable message is the one that gets displayed to the user
    set_message($error_message);

    //Register on the logs that new salesperson was successfully added
    //fwrite($handle, "Failed attempt to add new salesperson  by user: ".$_SESSION['firstname']." at ".$now.".\n");

    //Close the log file
    //fclose($handle);

  }
}

//flash message - to display error messages and successful login message
if(is_message())
{
    echo "<h3>" . get_message() . "</h3>";
    remove_message();
}


//function to dinamically display the form
$form_user = array(
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
    "type" => "password",
    "name"=>"inputPassword1",
    "value"=>"",
    "label"=>"Password"
  ),
  array(
    "type" => "password",
    "name"=>"inputPassword2",
    "value"=>"",
    "label"=>"Confirm Password"
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

display_Form($form_user);

?>


<?php
include "./includes/footer.php";
?>