<?php
/*
 * Name: Angelica Kusik
 * Date: October 5, 2022
 * Last Updated: December 5
 * Course: Webd 3203-01
 */

$file = "salesperson.php";
$date = "October 5, 2022";
$title = "Salesperson Registration";
$description = "Registration page for a client";

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
$password1 = "";
$password2 = "";
$user = $_SESSION['user'];
$active = true;


//Check if admin user has a valid session, otherwise redirect user to sign-in page 
//Only admin users can access this page!
if(!(isset($_SESSION['user'])&&($_SESSION['user']['type']==ADMIN))){
  //if user doesn't have a valid session  redirect to sign-in page
  redirect_user("sign-in.php");
  //if user has a session but as a salesperson
  if(($_SESSION['user']['type']==AGENT)) {
    //display a message on sign-in page explaining why user was redirected
    $_SESSION['error'] = "To access the Salesperson Registration page you must log in as an Admin.";
    //and redirect
    redirect_user("sign-in.php");
  }
}

//Collect the data from the form
if($_SERVER['REQUEST_METHOD'] == "POST"){
  //Check which form data was submitted by the user:
  //If the array $_POST['active'] is set, it means updated the statues of a salesperson(s), so get the new value and
  //update it on the database accordingly
  if(isset($_POST['active'])){
    foreach($_POST['active'] as $userId => $active){
      //dump($userId);
      //dump($active);

      if($active == 'Active'){
        $user_status = 't';
      }
      else{
        $user_status = 'f';
      }

      //update salesperson status
      update_user_status($user_status, $userId);

      //Register on the logs that salesperson status was modified
      fwrite($handle, "Status update: User " .$userId. " had status updated to " .$active. " at " .$now. " by " .$user['firstname']. ".\n");

      //Close the log file
      fclose($handle);
    }
  }
  else{
    // Otherwise, it means user is trying to enter a new salesperson, so get the data from the other form
    $first_name = trim($_POST['inputFirstName']);
    $last_name = trim($_POST['inputLastName']);
    $email_address = trim($_POST['inputEmail']);
    $phone_number = trim($_POST['inputPhoneNumber']);
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
  
    //validate phone number 
    $is_valid = validate_phone_number($phone_number, $error_message);
  
    if ($is_valid == false){
      //if password1 is invalid, set both password1 and password2 to blank
      $phone_number = "";
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
  
      //Use a try & catch block to handle any unexpected issues while inserting the salesperson on the database
      try
      {
        //insert new salesperson on the database
        insert_user($email_address, $password1, $first_name, $last_name, $now, $active);
        set_message("Congratulations. The user " .$first_name . " " . $last_name . " was succesfully registered as a salesperson.");
  
        //reset variables to blank after successfully registering new salesperson
        $first_name = "";
        $last_name = "";
        $email_address = "";
        $phone_number = "";
  
        //Register on the logs that new salesperson was successfully added
        fwrite($handle, "New salesperson: " .$first_name. " " .$last_name. ", successfully added at " .$now. " by " .$user['firstname']. ".\n");
  
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
  
      //Register on the logs failed attempt to register new salesperson
      fwrite($handle, "Failed attempt to add new salesperson by user: ".$user['firstname']." at ".$now.".\n");
  
      //Close the log file
      fclose($handle);
  
    }

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
    "type" => "phone",
    "name"=>"inputPhoneNumber",
    "value"=>$phone_number,
    "label"=>"Phone Number"
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
    "EmailAddress" => "Email Address",
    "FirstName" => "First Name",
    "LastName" => "Last Name",
    "phoneNumber" => "Phone Number",
    "active"  => "Is Active?"
  ),
  salesperson_select_all_table($page),
  salesperson_count(),
  $page
);

?>


<?php
include "./includes/footer.php";
?>