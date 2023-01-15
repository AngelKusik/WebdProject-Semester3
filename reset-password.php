<?php
/*
 * Name: Angelica Kusik
 * Date: November 23, 2022
 * Last Updated: November 24
 * Course: Webd 3203-01
 */

$file = "reset-password.php";
$date = "November 23, 2022";
$title = "Reset Password";
$description = "A page with a form where admin users can update a salesperson password.";

include "./includes/header.php";

//Variables Declarations
$today = date("Ymd");
$now = date("Y-m-d G:i:s");
$handle = fopen("./logs/".$today."_log.txt", 'a');
$error_message = "";
$message = "";
$email_address = "";



//Check user has a session and if user is an ADMIN
//Only admin users can access this page! If user is not an admin, redirect to the sign-in page
if(!(isset($_SESSION['user'])&&($_SESSION['user']['type']==ADMIN))){
    //if user doesn't have a valid session  redirect to sign-in page
    redirect_user("sign-in.php");
}

//if user has a valid session, save user info on a variable
$user = $_SESSION['user'];


//Collect the data from the form
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email_address = trim($_POST['inputEmailAddress']);

    //Validate email address
    $is_valid = user_select($email_address);

    if ($is_valid == false){
        //if email_address was not found on the database, set variable to blank and display an error message
        $error_message = "The email (" . $email_address . ") does not belong to any salesperson.</br>";
        $email_address = "";
    }
    else {
        //if email is valid and salesperson was successfully identified, save user info into appropriate variable
        $salesperson_to_reset =  $is_valid;
    }
      
    //if everything is valid
    if($error_message == "") {
        //carry on

        //Reset user password
        $new_password = reset_password($salesperson_to_reset);
        set_message("The password for user " .$email_address. " was succesfully reset at " .$now. ".");

        //Register on the logs that password was successfully reset
        fwrite($handle, "Password reset at ".$now." for user " .$email_address. " by user " .$user['firstname']. ".\n");

        //Simulate sending an email to the salesperson informing about the password reset by writing it
        //in the logs
        fwrite($handle, "To: ".$email_address." \nFrom: " .$user['emailaddress']. 
                        " \nSubject: Password Reset \nDear " .$salesperson_to_reset['firstname']. 
                        ",\nAs requested, your password was succesfully reset at ".$now. " by " .$user['emailaddress'].
                        ".\nYour temporary password is: " .$new_password. "\nPlease update your password next time you log-in. Thank you!");

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
        fwrite($handle, "Failed attempt to reset salesperson password by user: ".$user['firstname']." at ".$now.".\n");
    
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
$form_reset_password = array(
    array(
     "type" => "email",
     "name"=>"inputEmailAddress",
     "value"=>$email_address,
     "label"=>"Email Address"
    ),
    array(
      "type" => "submit",
      "name"=>"",
      "value"=>"",
      "label"=>"Reset Password"
    )
);
  
display_Form($form_reset_password);

?>

<?php
include "./includes/footer.php";
?>





