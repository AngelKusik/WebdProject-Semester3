<?php
/*
 * Name: Angelica Kusik
 * Date: September, 27, 2022
 * Webd 
 * 
 */
include "./includes/header.php";

//end current user session
session_unset();
session_destroy(); 

//and store logout information on the log
$today = date("Ymd");
$now = date("Y-m-d G:i:s"); //NOTE: Make this into a function to avoid repetion
$handle = fopen("./logs/".$today."_log.txt", 'a');
fwrite($handle, "You sucessfully log out at " .$today. ". User " .$email_address. ".\n");
fclose($handle);

//Create a message to inform user that they successfully logout
set_message("You sucessfully log out.");

//start a new session: It will transfer user back to sign-in page
header('Location:sign-in.php');

?>