<?php
$form_user = array(
  array(
    "type" => "text",
    "name"=>"inputFirstName",
    "value"=>"",
    "label"=>"First Name"
  ),
  array(
    "type" => "text",
    "name"=>"inputLastName",
    "value"=>"",
    "label"=>"Last Name"
  ),
  array(
    "type" => "email",
    "name"=>"inputEmail",
    "value"=>"",
    "label"=>"Email Address"
  ),
  array(
    "type" => "phone",
    "name"=>"inputPhoneNumber",
    "value"=>"",
    "label"=>"Phone Number"
  ),
  array(
    "type" => "select",
    "name"=>"inputSalesPerson",
    "value"=>"",
    "label"=>""
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