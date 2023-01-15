<?php
  
/*
 * Name: Angelica Kusik
 * Date: September 21, 2022
 * Last Updated: October 17, 2022
 * Course: Webd 3203-01
 */

//FUNCTIONS
//Description: This file contains all non-database related functions

/***************************************************************************************/

/*
* validate_email_address
* Validates the email address entered by the user on the login page to ensure it's not
* blank, invalid, or exceeds the max number of characters allowed on the database.
* Returns a boolean indicating whether the validation succeeded, and the error messages
* if it failed (using a pointer)
*/
function validate_email_address($email, &$error)
{
  $is_valid = true;
  //check if email is not blank
  if(!isset($email) || strlen($email) == 0)
  {
    //if so, display an error message
    $error .= "You did not enter an email address.<br/>";
    $is_valid = false;
  }//check if email lenght is valid
  else if(!(strlen($email) <= MAXIMUM_EMAIL_LENGTH))
  {
    //if email is too long display an error message
    $error .= "'" . $email . "' is too long. Please enter an email with up to " . MAXIMUM_EMAIL_LENGTH . " characters.<br/>";
    $is_valid = false;
  }//check if email structure is valid using the filter_var function
  else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    //if email is not valid display an error message
    $error .= "'" . $email . "' is not a valid email address.<br/>";
    $is_valid = false;
  }//Check if this email already exists on the database (email must be unique)
  else if (user_select($email)){
    $error .= "This email (" . $email . ") already exists.</br>";
    $is_valid = false;
  }

  return $is_valid;
}

/****************************************************************************************/
/*
* validate_password
* Validates if a password was entered by the user, and if so, if it's within the Min and Max numbers of
* characters allowed in the database.
*/

function validate_password($password, &$error)
{
  $is_valid = true;
  if(!isset($password) || strlen($password) == 0)
  {
    $error .= "You must enter a password.";
    $is_valid = false;
  }//if user entered a password, check if the number of characters is within the min range
  //using the string lenght function.
  else if(!(strlen($password) >= MINIMUM_PASSWORD_LENGTH))
  {
    //if not, display an error message
    $error .= "Your password must be at least " . MINIMUM_PASSWORD_LENGTH . " characters.<br/>";
    $is_valid = false;
  }//if user entered a password, check if the number of characters is within the max range
  //using the string lenght function.
  else if (!(strlen($password) <= MAXIMUM_PASSWORD_LENGTH))
  {
    //if not, display an error message
    $error .= "Your password cannot be more than " . MAXIMUM_PASSWORD_LENGTH . " characters.<br/>";
    $is_valid = false;
  }
  return $is_valid;
}

/*
* validate_password_match
* Compares if the password and confirm password fields have the exact same password.
*/

function validate_password_match($password, $confirm_password, &$error)
{
  $is_valid = true;
  //check if password and confirm_password are the same using the
  //string comparisson function. If the function return 0 it means strings are equal.
  if(strcmp($password, $confirm_password) != 0)
  {
    //if passwords are not the same display an error message
    $error .= "The password and confirm password were not the same.<br/>";
    $is_valid = false;
  }
  return $is_valid;

}

/***LAB 2 VALIDATION ***/

/*
* validate_first_name
* Validates the first name to ensure it's not blank, a number, or
* exceeds the max number of characters allowed on the database
*/
function validate_first_name($first_name, &$error)
{
  $is_valid = true;
  //check if first name is not blank
  if(!isset($first_name) || strlen($first_name) == 0)
  {
    //if so, display an error message
    $error .= "Firt name cannot be blank.<br/>";
    $is_valid = false;
  }//check if first name is not a number.
  else if(is_numeric($first_name))
  {
    //if first name is a number display an error message.
    $error .= "First name cannot be a number. You entered: " . $first_name . ".<br/>";
    $is_valid = false;
  }//check if it's within the max characters range.
  else if(!(strlen($first_name) <= MAX_FIRST_NAME_LENGTH))
  {
    //if so, display an error message
    $error .= "First name must be less than " . MAX_FIRST_NAME_LENGTH . " characters, " . $first_name . " is too long.<br/>";
    $is_valid = false;
  }
  return $is_valid;
}

/*
* validate_last_name
* Validates the first name to ensure it's not blank, a number, or
* exceeds the max number of characters allowed on the database
*/
function validate_last_name($last_name, &$error)
{
  $is_valid = true;
  //check if last name is not blank
  if(!isset($last_name) || strlen($last_name) == 0)
  {
    //if so, display an error message
    $error .= "Last name cannot be blank.<br/>";
    $is_valid = false;
  }//check if last name is not a number.
  else if(is_numeric($last_name))
  {
    //if first name is a number display an error message.
    $error .= "Last name cannot be a number. You entered: " . $last_name . ".<br/>";
    $is_valid = false;
  }//check if it's within the max characters range.
  else if(!(strlen($last_name) <= MAX_LAST_NAME_LENGTH))
  {
    //if so, display an error message
    $error .= "Last name must be less than " . MAX_LAST_NAME_LENGTH . " characters, " . $last_name . " is too long.<br/>";
    $is_valid = false;
  }
  return $is_valid;
}

/****************************************************************************************/
/*
* handle_failed_login
* When a login attempt fails, registers attempt on the logs and creates an error message
* to inform user of what went wrong.
*/
function handle_failed_login($handle, $now, $email_address, $error_message)
{
  //If login failed, register unsuccessfull attempt in the logs
  fwrite($handle, "Failed sign in attempt at " .$now. " User " .$email_address. ".\n");

  //Close the log file
  fclose($handle);

  //if email is not valid, display an error message informing the user
  set_message($error_message);
}

/****************************************************************************************/

/*Displays the copyright information on the footer of the page. */
function displayCopyrightInfo()
{
  //Display the copyright symbol, author's name and retrieves
  //the current year from the database.
  echo "&copy; Angelica Kusik " . date("Y");
}

/*************************************************************************************/
/*
  * redirect_user
  * Description: Redirects user to another page depending on the URL being passed
  */
function redirect_user($url)
{
  //redirect user
  header('Location:'.$url);
  //flush the buffer
  ob_flush();
}

/*************************************************************************************/
//MESSAGE RELATED FUNCTIONS
/*
  * set_message
  * Description: Sets the value of a message (for session)
  * 
  */
function set_message($msg)
{
  $_SESSION['message'] = $msg;
}
/*
  * get_message
  * Description: Returns the value of session message
  * 
  */
function get_message()
{
  return $_SESSION['message'];

}
/*
  * is_message
  * Description: Checks the session for messages, if there is a message returns true, otherwise false
  * 
  */
function is_message()
{
  //Here the conditional operator '?' is being used for the sake of keeping the code short, but this has the same effect as using an if & else statement.
  return isset($_SESSION['message'])?true:false;
}
/*
  * remove_message
  * Description: Removes message from session
  * 
  */
function remove_message()
{
  unset($_SESSION['message']);
}
/*
  * flash_message
  * Description: Checks if there is a message on session, if so, saves the value
  * of the message on a variable and removes message from session
  * 
  */
function flash_message()
{
  $message = "";
  if(is_message())
  {
    $message = get_message();
    remove_message();
  }

}

/***********************************************************************************************************/
  /*
    * dump
    * Description: Displays the value of any variable, including the variable's layers, in a formated
    * way (with white space). Useful for debuggging.
    * 
    */
  function dump($arg)
  {
      echo "<pre>";
      print_r($arg);
      echo "</pre>";
  }

  /*************************************************************************/
  /*
   * LAB 2 FUNCTIONS
   */

  function display_Form($arrayForm){
    echo '<form class="form-signin" method="POST" action="'.$_SERVER['PHP_SELF'].'">';
    echo ' <h1 class="h3 mb-3 font-weight-normal">Please fill the details</h1>';
 
    foreach($arrayForm as $element)
    {
      if($element['type']=="text"||$element['type']=="email"||$element['type']=="password")//||
      //($element['type']== "phone" || $element['type'] == "datetime-local")
      {
        echo '<label for="'.$element['name'].'" class="sr-only">'.$element['label'].'</label>';

        echo'<input type="'.$element['type'].'" id="'.$element['name'].'" name ="'.$element['name'].'" class="form-control" value ="'.$element['value'].'" placeholder="'.$element['label'].'"  autofocus>';
      }
      elseif($element['type']=="submit" || $element['type']=="reset")
      {
        echo ' <button class="btn btn-lg btn-primary btn-block" type="'.$element['type'].'">'.$element['label'].'</button>';
      }

    }
    echo "</form>";
  }

?>