<?php
  $toEmail = "emmanuel.afolabi@anakle.com";
  
  $subject = "Internship Application";
  
  $mailHeaders = "From: " . $_POST["firstName"] . " " . $_POST["lastName"] . "<" . $_POST["userEmail"] . ">\r\n";
  
  $message = "Name: " . $_POST["firstName"] . " " . $_POST["lastName"] . ">\r\n";
  $message .= "Email: " . $_POST["email"] . "\r\n";
  $message .= "Phone: " . $_POST["phone"] . "\r\n";
  $message .= "LinkedIn:" . $_POST["linkedIn"] . "\r\n";
  $message .= "Portfolio:" . $_POST["portfolio"] . "\r\n";
  $message .= "Message:" . $_POST["message"] . "\r\n";
  $message .= "Question:" . $_POST["question"] . "\r\n";

  if(mail($toEmail, $subject, $message, $mailHeaders)) {
    print "<p class='success'>Application Sent.</p>";
  } else {
    print "<p class='Error'>Problem in Sending Mail.</p>";
  }
?>