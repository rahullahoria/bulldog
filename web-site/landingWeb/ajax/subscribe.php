<?php

if(isset($_POST['email'])){
  
  $email = $_POST['email'];
  
  
  $headers = "MIME-Version: 1.0" . "\r\n";

// More headers
  $headers .= 'From: <no-reply@collap.com>' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";
 
  $message = "My email-id is ".$email."  and i subscribed for Bull Dog
  				\n \n This email is sent by Bull Dog \r\n";
// message & attachment
  
  $to = "rahul@blueteam.in";
  $subject = "New Subsciption on Bull Dog";
  
  mail($to,$subject,$message,$headers);				
}
?> 
