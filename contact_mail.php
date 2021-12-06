<?php
  header("Access-Control-Allow-Origin: *");
  include("config.php");


//   name, email, phone, age, gender, education, location, laptop, about, interest, question
//   CREATE TABLE IF NOT EXISTS entry (
//     id     INT(11)  NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR (200) DEFAULT NULL,
//     email VARCHAR (200) DEFAULT NULL,
//     phone VARCHAR(50) DEFAULT NULL,
//     age VARCHAR(150) DEFAULT NULL,
//     gender VARCHAR(150) DEFAULT NULL,
//     education VARCHAR(100) DEFAULT NULL,
//     location VARCHAR(100) DEFAULT NULL,
//     laptop VARCHAR(150) DEFAULT NULL,
//     about VARCHAR (255) DEFAULT NULL,
//     interest VARCHAR (255) DEFAULT NULL,
//     question VARCHAR (255) DEFAULT NULL,
//     time TIMESTAMP
// )
  
  
  $query = $db_conn->prepare("SELECT * FROM entry where email = '" . $_POST["userEmail"] . "'");
  $query->execute();
  $count = $query->rowCount();
  
  if($count > 0) {
      
    echo '<p class="error">Registration could not be completed! Email is already in use.</p>';
    $type = "error";
    
  } else {
      
    $fname = $_POST['fullName'];
    $email = $_POST['userEmail'];
    $pname = $_POST['phone'];
    $aname = $_POST['age'];
    $gname = $_POST['gender'];
    $ename = $_POST['education'];
    $lname = $_POST['location'];
    $lapname = $_POST['laptop'];
    $mname = trim($_POST['message']);
    $iname = trim($_POST['interest']);
    $qname = trim($_POST['question']);
    
    $stmt=$db_conn->prepare('INSERT INTO entry(name, email, phone, age, gender, education, location, laptop, about, interest, question) VALUES (:first, :ename, :tel, :old, :sex, :edu, :loc, :lap, :sms, :why, :answer)');
    $stmt->bindParam(':first', $fname);
    $stmt->bindParam(':ename', $email);
    $stmt->bindParam(':tel', $pname);
    $stmt->bindParam(':old', $aname);
    $stmt->bindParam(':sex', $gname);
    $stmt->bindParam(':edu', $ename);
    $stmt->bindParam(':loc', $lname);
    $stmt->bindParam(':lap', $lapname);
    $stmt->bindParam(':sms', $mname);
    $stmt->bindParam(':why', $iname);
    $stmt->bindParam(':answer', $qname);
    
    if($stmt->execute()){	
  
  
      header("Access-Control-Allow-Origin: *");
  
      $toEmail = "emmanuel.afolabi@anakle.com";
      // $toEmail = "hr@anakle.com";
      
      //Sanitize input data using PHP filter_var().
      $first_name      = filter_var($_POST["fullName"], FILTER_SANITIZE_STRING);
      $subject = "Forward Application";
      
      $separator = md5(time());
      
      // carriage return type (RFC)
      $eol = "\r\n";
      
      // main header (multipart mandatory)
      $mailHeaders = "From: " . $_POST["fullName"] . " <" .$_POST["userEmail"] . ">" . $eol;
      $mailHeaders .= "MIME-Version: 1.0" . $eol;
      $mailHeaders .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
      $mailHeaders .= "Content-Transfer-Encoding: 7bit" . $eol;
      
      $message = '<html><body>';
      $message .= "<p style='margin-bottom: 20px'>We have a new application entry from Forward By Anakle's website.</p>";
      $message .= "<table rules='all' style='border-color: #666;' cellpadding='10'>";
      $message .= "<tr style='background: #eee;'><td><strong>Full Name: </strong> </td><td>" . strip_tags($_POST["fullName"]) . "</td></tr>";
      $message .= "<tr><td><strong>Email: </strong> </td><td>" . strip_tags($_POST["userEmail"]) . "</td></tr>";
      $message .= "<tr style='background: #eee;'><td><strong>Phone: </strong> </td><td>" . strip_tags($_POST["phone"]) . "</td></tr>";
      $message .= "<tr><td><strong>Age: </strong> </td><td>" . strip_tags($_POST["age"]) . "</td></tr>";
      $message .= "<tr style='background: #eee;'><td><strong>Gender: </strong> </td><td>" . strip_tags($_POST["gender"]) . "</td></tr>";
      $message .= "<tr><td><strong>Highest level of education? </strong> </td><td>" . strip_tags($_POST["education"]) . "</td></tr>";
      $message .= "<tr style='background: #eee;'><td><strong>Where in Lagos do you reside? </strong> </td><td>" . strip_tags($_POST["location"]) . "</td></tr>";
      $message .= "<tr><td><strong>Do you have a laptop? </strong> </td><td>" . strip_tags($_POST["laptop"]) . "</td></tr>";
      $message .= "<tr style='background: #eee;'><td><strong>Tell us about yourself: </strong> </td><td>" . strip_tags($_POST["message"]) . "</td></tr>";
      $message .= "<tr><td><strong>Why are you interested in brands and marketing? </strong> </td><td>" . strip_tags($_POST["interest"]) . "</td></tr>";
      $message .= "<tr style='background: #eee;'><td><strong>Superman and Captain America are running for President of Nigeria. Who are you supporting and why? </strong> </td><td>" . strip_tags($_POST["question"]) . "</td></tr>";
      $message .= "</table>";
      $message .= "</body></html>";
      
      // message
      $body = "--" . $separator . $eol;
      $body .= "Content-type:text/html; charset=utf-8\n";
      $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
      $body .= $message . $eol;
      $body .= "--" . $separator . "--";
      
      // Applicant Email COnfiguration
      
      $adminName = "Forward By Anakle";
      $applicantEmail = $_POST["userEmail"];
      $appSubject = "Application For Forward By Anakle's Traning";
      
      // main header (multipart mandatory)
      $appHeaders = "From: " . $adminName . " <" . $toEmail . ">" . $eol;
      $appHeaders .= "MIME-Version: 1.0" . $eol;
      $appHeaders .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
      $appHeaders .= "Content-Transfer-Encoding: 7bit" . $eol;
      
      $appMessage = '<html><body>';
      $appMessage .= "<p style='margin-bottom: 20px'>Awesome! You have successfully applied for the next batch of our training. <br>We will contact you via mail or phone for further information.</p>";
      $appMessage .= "<table rules='all' style='border-color: #666;' cellpadding='10'>";
      $appMessage .= "<tr style='background: #eee;'><td><strong> Full Name: </strong> </td><td>" . strip_tags($_POST["fullName"]) . "</td></tr>";
      $appMessage .= "<tr><td><strong>Email: </strong> </td><td>" . strip_tags($_POST["userEmail"]) . "</td></tr>";
      $appMessage .= "<tr style='background: #eee;'><td><strong>Phone: </strong> </td><td>" . strip_tags($_POST["phone"]) . "</td></tr>";
      $appMessage .= "</table>";
      $appMessage .= "</body></html>";
      
      // message
      $appBody = "--" . $separator . $eol;
      $appBody .= "Content-type:text/html; charset=utf-8\n";
      $appBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
      $appBody .= $appMessage . $eol;
      $appBody .= "--" . $separator . "--";
  
      if(mail($toEmail, $subject, $body, $mailHeaders)) {
        mail($applicantEmail, $appSubject, $appBody, $appHeaders);
        echo '<p class="success">Thank you! Your application has been submitted.</p>';
      } else {
        echo '<p class="error">Application could not be subbmitted! Please try again later.';
      }

    }else { }  

  }
?>
