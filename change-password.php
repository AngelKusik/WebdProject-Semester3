<?php
/*
 * Name: Angelica Kusik
 * Date: November 9, 2022
 * Last Updated: November 9
 * Course: Webd 3203-01
 */

$file = "change-password.php";
$date = "November 9, 2022";
$title = "Change Password";
$description = "A page with a form where users can change their password.";

include "./includes/header.php";

//Variables Declarations
$today = date("Ymd");
$now = date("Y-m-d G:i:s");
$handle = fopen("./logs/".$today."_log.txt", 'a');
$error_message = "";
$message = "";
$email_address = "";
$password = "";
$new_password = "";
$confirm_new_password = "";


//before we do anything check the session to see if user is validated, if not send user back to sign-in page
if(!isset($_SESSION['user']))
{
    //Call the redirect_user funtion and pass in the URL
    redirect_user('sign-in.php');
}

//if user has a valid session, save user info on a variable
$user = $_SESSION['user'];



//Collect the data from the form
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $password = trim($_POST['inputPassword']);
    $new_password = trim($_POST['inputNewPassword']);
    $confirm_new_password = trim($_POST['inputConfirmNewPassword']);


    //Validate password

    //validate if password entered matches password saved on the database for this user
    $is_valid = confirm_password($password, $error_message);

    if ($is_valid == false){
        //if password is invalid set it to blank
        $password = "";
    }

    $is_valid = validate_password($new_password, $error_message);

    if ($is_valid == false){
        //if new_password is invalid, set both new_password and confirm_new_password to blank
        $new_password = "";
        $confirm_new_password = "";
      }
      else { 
        //if new_password is valid, check if confirm_new_password matches it
        $is_valid = validate_password_match($new_password, $confirm_new_password, $error_message);
        if ($is_valid == false){
          //if passwords don't match, set them both to blank
          $new_password = "";
          $confirm_new_password = "";
        }
    }

    //if all of this works:
    //update user password and substitute it for the new hashed password
    //send user back to the dashboard with a message

    if($error_message == "") {
        //carry on

        //Update user password
        update_password($new_password, $user['id']);
        set_message("Congratulations " .$user['firstname']. ", your password was succesfully updated at " .$now. ".");

        //Register on the logs that password was successfully changed
        fwrite($handle, "Password successfully changed ".$today." for user " .$user['firstname']. ".\n");

        //Close the log file
        fclose($handle);

        //Now transfer user to dashboard page using the redirect_user function
        redirect_user('dashboard.php');

        //And flush the buffer
        ob_flush();  

    }
    else {
        $error_message .= "</br> Please try again";
        //set message equal to error message because the variable message is the one that gets displayed to the user
        set_message($error_message);
    
        //Register on the logs failed attempt to register new salesperson
        fwrite($handle, "Failed attempt to change password by user: ".$user['firstname']." at ".$now.".\n");
    
        //Close the log file
        fclose($handle);
    
      }
}

//flash message - to display error messages and success changing password messages
if(is_message())
{
    echo "<h2>" .get_message() . "</h2>";
}

flash_error();

//function to dinamically display the form
$form_password = array(
    array(
      "type" => "password",
      "name"=>"inputPassword",
      "value"=>"",
      "label"=>"Current Password"
    ),
    array(
      "type" => "password",
      "name"=>"inputNewPassword",
      "value"=>"",
      "label"=>"New Password"
    ),
    array(
      "type" => "password",
      "name"=>"inputConfirmNewPassword",
      "value"=>"",
      "label"=>"Confirm New Password"
    ),
    array(
      "type" => "submit",
      "name"=>"",
      "value"=>"",
      "label"=>"Submit"
    ),
    array(
      "type" => "reset",
      "name"=>"",
      "value"=>"",
      "label"=>"Reset"
    )
);
  
display_Form($form_password);

?>

<?php
include "./includes/footer.php";
?>





