<?php

if(isset($_POST['name'])){
  $name = $_POST['name'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  
  $headers = "MIME-Version: 1.0" . "\r\n";

// More headers
  $headers .= 'From: <no-reply@collap.com>' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";
 
  $message = "My name is ".$name." mobile no. ".$mobile." and email-id ".$email."
  				\n \n This email is sent by Bull Dog \r\n";
// message & attachment
  
  $to = "rahul@blueteam.in";
  $subject = "New Sign Up on Bull Dog";
  
  mail($to,$subject,$message,$headers);				
}
?> 
