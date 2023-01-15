<?php
  
/*
 * Name: Angelica Kusik
 * Date: September 21, 2022
 * Last Updated: December 5
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
  else if (user_select($email) || client_select_email($email)){
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

/*
* validate_phone_number
* Validates the phone number to ensure it was entered,
* its a number, and it doesn't exceed the max number of characters allowed in the database
*/
function validate_phone_number($phone_number, &$error)
{
  $is_valid = true;
  //check if phone number is not blank
  if(!isset($phone_number) || strlen($phone_number) == 0)
  {
    //if so, display an error message
    $error .= "Phone number cannot be blank.<br/>";
    $is_valid = false;
  }//check if phone number is a number.
  else if(!(is_numeric($phone_number)))
  {
    //if not display an error message.
    $error .= "Phone number must be a number. You entered: " . $phone_number . ".<br/>";
    $is_valid = false;
  }//check if it's within the max characters range.
  else if(!(strlen($phone_number) <= MAXIMUM_PHONE_NUMBER_LENGTH))
  {
    //if so, display an error message
    $error .= "Phone number must be less than " . MAXIMUM_PHONE_NUMBER_LENGTH . " characters, " . $phone_number . " is too long.<br/>";
    $is_valid = false;
  }
  return $is_valid;
}

/*
* validate_sales_id
* Validates if user selected a salesperson if user is an admin and set
* sales_id equal to the user id if user is a salesperson
*/
function validate_sales_id($sales_id, &$error)
{
  //check if user selected a salesperson 
  if($sales_id < 0)
  {
    //if sales_id is a negative number it means user didn't select a salesperson
    //Note: default value for select box is -1 (the value of the label)
    $error .= "You must select the salesperson associated with the client.<br/>";
  }
}

/*
* validate_logo_path
* Validates if logo was uploaded, if logo type is valid, and if logo size
* is within max size allowed
*/
function validate_logo_path(&$error)
{
  $is_valid = true;
  //check if user selected a salesperson 
  if($_FILES['uploadFileName']['error'] != 0)
  {
    //if logo was not successfully uploaded display an error message
    $error .= "A problem has occured while uploading your file.<br/>";
    $is_valid = false;
  }
  else if ($_FILES['uploadFileName']['type'] != "image/jpeg"
  &&$_FILES['uploadFileName']['type'] != "image/pjpeg"
  &&$_FILES['uploadFileName']['type'] != "image/gif"
  &&$_FILES['uploadFileName']['type'] != "image/png")
  {
    //if logo is not in valid format, display an error message
    $error .= "Client logo must be of type JPEG, GIF, or PNG.<br/>";
    $is_valid = false;
  }
  else if($_FILES['uploadFileName']['size'] > MAXIMUM_LOGO_SIZE)
  {
    //if logo size exceed max size, display an error message
    $error .= "File selected is too big, file must be smaller than 3MB.<br/>";
    $is_valid = false;
  }
  return $is_valid;
}

/*
* validate_client_id
* Validates if user selected a client and if client sales_id matches
* the sales id of the selected salesperson
*/
function validate_client_id($sales_id, $client_id, &$error)
{
  //check if client id is set 
  if($client_id < 0)
  {
    //if client_id is a negative number it means user didn't select a client
    //Note: default value for select box is -1 (the value of the label)
    $error .= "You must select a client.<br/>";
  }
  else
  {
    //check if selected salesperson id matches client's sales_id:
    
    //get client sales_id:
    $client_sales_id = client_sales_id($client_id);

    if($sales_id != $client_sales_id)
    {
      $error .= "Client does not belong to selected salesperson.</br>";
    }
  }
}

/*
* validate_client_id
* Validates if user selected a client and if client sales_id matches
* the sales id of the selected salesperson
*/
function validate_call_datetime($call_date_time, &$error)
{
  $is_valid = true;
  //check if date & time is set 
  if(!isset($call_date_time) || strlen($call_date_time) == 0)
  {
    $error .= "You must enter the call date and time.<br/>";
    $is_valid = false;
  }
  return $is_valid;
}

/*
* validate_call_description
* Validates if user entered call description
*/
function validate_call_description($call_description, &$error)
{
  $is_valid = true;
  //check if date & time is set 
  if(!isset($call_description) || strlen($call_description) == 0)
  {
    $error .= "You must enter the call description.<br/>";
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
  echo "&copy; Mapple Inc " . date("Y");
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

/*
  * flash_error
  * Description: Checks if there is a error message on the session, if so retrieves it, 
  * saves it on a variable and removes it from the session.
  * 
  */
function flash_error()
{
  $error = "";
  if(isset($_SESSION['error']))
  {
    $error = $_SESSION['error'];
    echo "<h2>" .$error . "</h2>";
    unset($_SESSION['error']);
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

   /*
    * display_Form
    * Dinamically displays the forms according to the parameters being passed through
    * an associative array.
    */

  function display_Form($arrayForm){
    //Start the form
    echo '<form class="form-signin" enctype="multipart/form-data" method="POST" action="'.$_SERVER['PHP_SELF'].'">';
    // Display header message
    echo ' <h1 class="h3 mb-3 font-weight-normal">Please fill the following:</h1>';
    
    //Create each input field:
    foreach($arrayForm as $element)
    {
      //Create input fields of given type:
      if($element['type']=="text"||$element['type']=="email"||$element['type']=="password"||$element['type']=="phone"||$element['type'] == "datetime-local")
      {
        echo '<label for="'.$element['name'].'" class="sr-only">'.$element['label'].'</label>';

        echo'<input type="'.$element['type'].'" id="'.$element['name'].'" name ="'.$element['name'].'" value ="'.$element['value'].'" class="form-control" placeholder="'.$element['label'].'"  autofocus>';
      }
      //Create buttons of given type
      elseif($element['type']=="submit" || $element['type']=="reset")
      {
        echo ' <button class="btn btn-lg btn-primary btn-block" type="'.$element['type'].'">'.$element['label'].'</button>';
      }
      //Create select fields:
      elseif($element['type']=="select")
      {
        //if the user is an Admin, display the select box, so the user can select the salesperson 
        //associated with the client being entered.
        if(isset($_SESSION['user'])&&($_SESSION['user']['type']==ADMIN))
        {
          //check the input type
          if($element['name']== 'inputSalesperson'){
            //create a select field
            echo '<label for="'.$element['name'].'" class="sr-only">'.$element['label'].'</label>';
            echo' <select  name="'.$element['name'].'"id="'.$element['name'].'" type="'.$element['type'].'" class="form-control form-control-lg pb-1 pt-1" >';

            //Create the default option
            echo '<option class="lg form-control form-control-lg mb-1 pb-1" value="-1">Select Salesperson</option>';

            //Get all salesperson from database
            $result = user_type_select(AGENT);
            
            //Create option for each salesperson registered on the database
            for($i = 0; $i < pg_num_rows($result); $i++)
            {
              $salesperson = pg_fetch_assoc($result, $i);
              echo '<option class="lg form-control form-control-lg mb-1 pb-1" value="'.$salesperson['id'].'">'.$salesperson['firstname'].' '.$salesperson['lastname']. ', ' .$salesperson['emailaddress']. ', ID: '.$salesperson['id']. '</option>';
            }
            echo '</select>';
          }
          elseif($element['name']== 'inputClient'){

            //create a select field
            echo '<label for="'.$element['name'].'" class="sr-only">'.$element['label'].'</label>';
            echo' <select  name="'.$element['name'].'"id="'.$element['name'].'" type="'.$element['type'].'" class="form-control form-control-lg pb-1 pt-1" >';

            //Create the default option
            echo '<option class="lg form-control form-control-lg mb-1 pb-1" value="-1">Select Client</option>';

            //Get all clients from the database
            $result = client_select_all(); 

            //Create option for each client registered on the database
            for($i = 0; $i < pg_num_rows($result); $i++)
            {
              $clients = pg_fetch_assoc($result, $i);
              echo '<option class="lg form-control form-control-lg mb-1 pb-1" value="'.$clients['id'].'">'.$clients['firstname'].' '.$clients['lastname'].' - Client Salesperson: '.$clients['sales_id']. '</option>';
            }
            echo '</select>';
          }
          
        }
        //Check the user type, if the user is a salesperson (aka AGENT) display the combo box but
        elseif(isset($_SESSION['user'])&&($_SESSION['user']['type']==AGENT)&& $element['name']== 'inputClient') 
        {
          //create a select field
          echo '<label for="'.$element['name'].'" class="sr-only">'.$element['label'].'</label>';
          echo' <select  name="'.$element['name'].'"id="'.$element['name'].'" type="'.$element['type'].'" class="form-control form-control-lg pb-1 pt-1" >';

          //Create the default option
          echo '<option class="lg form-control form-control-lg mb-1 pb-1" value="-1">Select Client</option>';

          //get the current salesperson id from the session
          $current_salesperson = $_SESSION['user'];
          $id = $current_salesperson['id'];
          //Get the clients that belong to the the current salesperson logged in
          $result = client_type_select($id);


          //Create option for each client 
          for($i = 0; $i < pg_num_rows($result); $i++)
          {
            $clients = pg_fetch_assoc($result, $i);
            echo '<option class="lg form-control form-control-lg mb-1 pb-1" value='.$clients['id'].'>'.$clients['firstname'].' '.$clients['lastname'].', Phone number: '.$clients['phonenumber']. '</option>';
          }
          echo '</select>';
        }
        
      }
      elseif($element['type']=="file"){
        //Create the file field
        echo '<label for="'.$element['name'].'" class="sr-only">'.$element['label'].'</label>';
        echo'<input type="'.$element['type'].'" id="'.$element['name'].'" name ="'.$element['name'].'" value ="'.$element['value'].'" class="form-control" placeholder="'.$element['label'].'"  autofocus>';
      }
    }
    //Close the form
    echo "</form>";
  }

  /*************************************************************************/
  /*
   * LAB 3 FUNCTIONS
   */

   /*
    * display_table
    * Dinamically displays the tables and table pagination
    *
    */

    function display_table($array_table_fields, $client_select_all, $agent_count, $page)
    {
      //Create table
      echo '<div class="table-responsive">';
      echo '<table class="table table-striped table-sm">';
      echo '<thead>';
      echo '<tr>';

      //Create the table header
      foreach($array_table_fields as $key => $value){
        echo '<th>'.$value.'</th>';
      }
      echo '</tr>';
      echo '</thead>';

      //Create table body
      echo '<tbody>';
      for ($i = 0; $i < COUNT($client_select_all); $i++){
        echo '<tr>';
        foreach($client_select_all[$i] as $key1 => $value1){
          if($key1 == 'logo_path'){
            echo '<td> <img src="'.$value1.'" alt="NO LOGO AVAILABLE" width="30"> </td>';
          }
          else if($key1 == 'active'){
            //dump($client_select_all);
            //Create the form for the radio buttons to indicate if salesperson is active or inactive
            echo '<td>';
            echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
            echo '<div>';

            //if enable is true, check the active radio button
            if($value1 == 't'){
              echo '<input type="radio" id="'.$key.'-Active" name="active['.$client_select_all[$i]['emailaddress'].']" value="Active" checked >';
              echo '<label for="'.$key.'-Active"> Active </label>';
              echo '</div>';
              echo '<div>';
              echo '<input type="radio" id="'.$key.'-Inactive" name="active['.$client_select_all[$i]['emailaddress'].']" value="Inactive" >';
              echo '<label for="'.$key.'-Inactive"> Inactive </label>';
            }
            else{ //if enable is false, check the inactive radio button
              echo '<input type="radio" id="'.$key.'-Active" name="active['.$client_select_all[$i]['emailaddress'].']" value="Active" >';
              echo '<label for="'.$key.'-Active"> Active </label>';
              echo '</div>';
              echo '<div>';
              echo '<input type="radio" id="'.$key.'-Inactive" name="active['.$client_select_all[$i]['emailaddress'].']" value="Inactive" checked >';
              echo '<label for="'.$key.'-Inactive"> Inactive </label>';

            }
            //Close the form and the cell
            echo '</div>';
            echo '<input type="submit" value="Update" />';
            echo '</form>';
            echo '</td>';

          }
          else {
            echo '<td>'.$value1.'</td>';
          }
        }
        echo '</tr>';
      }

      //close table
      echo '</tbody>';
      echo '</table>';

      //Create page navigation, aka pagination
      echo '<nav aria-label="Page Navigation"';
      echo '<ul class="pagination">';

      if($page <= 1){
        echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
      }
      else {
        echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.($page-1).'">Previous</a></li>';
      }
      //echo '<li class="page-item"><a class="page-link" href="#">Previous</a></li>';
      for ($i = 0; $i < $agent_count/RECORDS; $i++){
        echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.($i+1).'">'.($i+1).'</a></li>';
      }

      if($page >= $agent_count/RECORDS){
        echo '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
      }
      else {
        echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.($page+1).'">Next</a></li>';
      }

      //echo '<li class="page-item"><a class="page-link" href="#">Next</a></li>';

      //Close pagination
      echo '</ul>';
      echo '</nav>';
      echo '</div>';
    }

?>
