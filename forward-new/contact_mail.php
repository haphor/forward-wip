<?php

  header("Access-Control-Allow-Origin: *");
  
  $fname = $_POST['fullName'];
  $cname = $_POST['courseName'];
  $email = $_POST['userEmail'];
  $pname = $_POST['phoneName'];

  
  // $fromName = 'Access Marathon';
  // $fromEmail = 'emmanuel.afolabi@anakle.com';
  $toEmail = $email;
  
  //Sanitize input data using PHP filter_var().
  $first_name      = filter_var($_POST["buddiesName"], FILTER_SANITIZE_STRING);
  $subject = "Forward Application";
  
  $buddyMessage = "Testing logic";
  
  $separator = md5(time());

  // carriage return type (RFC)
  $eol = "\r\n";
  
  // main header (multipart mandatory)
  // $mailHeaders = "From: " . $fromName . " <" . $email . ">" . $eol;
  $mailHeaders = "From: " . $fname . " <" . $email . ">" . $eol;
  $mailHeaders .= "MIME-Version: 1.0" . $eol;
  $mailHeaders .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
  $mailHeaders .= "Content-Transfer-Encoding: 7bit" . $eol;

  $message = '<html><body>';
  $message .= "<p style='margin-bottom: 20px'>This is an application form the Forward By Anakle\'s website.</p>";
  $message .= "<table rules='all' style='border-color: #666;' cellpadding='10'>";
  $message .= "<tr style='background: #eee;'><td><strong>Name: </strong> </td><td>" . strip_tags($fname) . "</td></tr>";
  $message .= "<tr><td><strong>Course: </strong> </td><td>" . strip_tags($cname) . "</td></tr>";
  $message .= "<tr style='background: #eee;'><td><strong>Email: </strong> </td><td>" . strip_tags($email) . "</td></tr>";
  $message .= "<tr><td><strong>Phone: </strong> </td><td>" . strip_tags($pname) . "</td></tr>";
  $message .= "</table>";
  $message .= "</body></html>";

  
  
  // message
  $body = "--" . $separator . $eol;
  $body .= "Content-type:text/html; charset=utf-8\n";
  $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
  $body .= $message . $eol;
  $body .= "--" . $separator . "--";


  if(mail($toEmail, $subject, $body, $mailHeaders)) {
      $output = json_encode(array('type'=>'message', 'text' => 'Thank you! Your application has been submitted.'));
      die($output);
  } else {
      $output = json_encode(array('type'=>'error', 'text' => 'Application could not be completed! Please try again later.'));
      die($output);
  } 

?>