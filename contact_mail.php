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




  //check if its an ajax request, exit if not
  if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    $output = json_encode(array( //create JSON data
      'type'=>'error',
      'text' => 'Sorry Request must be Ajax POST'
    ));
    die($output); //exit script outputting json data
  }
  
  
  $query = $db_conn->prepare("SELECT * FROM entry where email = '" . $_POST["userEmail"] . "'");
  $query->execute();
  $count = $query->rowCount();
  
  if($count > 0) {
      
    echo '<p class="error">Registration could not be completed! Email is already in use.</p>';
    $type = "error";
    
  } else {
      
    //Sanitize input data using PHP filter_var().
    $fname = filter_var($_POST['fullName'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['userEmail'], FILTER_SANITIZE_EMAIL);
    $pname = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
    $aname = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gname = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $ename = filter_var($_POST['education'], FILTER_SANITIZE_STRING);
    $lname = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $lapname = filter_var($_POST['laptop'], FILTER_SANITIZE_STRING);
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
      
      //Recepient Email Address
      $to_email     = "emmanuel.ola.afolabi@gmail.com";
      $subject      = "Forward Application";

      
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
      $message .= "<tr style='background: #eee;'><td style='max-width: 250px;'><strong>Superman and Captain America are running for President of Nigeria. Who are you supporting and why? </strong> </td><td>" . strip_tags($_POST["question"]) . "</td></tr>";
      $message .= "</table>";
      $message .= "</body></html>";


      $file_attached = false;
      if(isset($_FILES['file_attach'])) //check uploaded file
      {
        //get file details we need
        $file_tmp_name    = $_FILES['file_attach']['tmp_name'];
        $file_name        = $_FILES['file_attach']['name'];
        $file_size        = $_FILES['file_attach']['size'];
        $file_type        = $_FILES['file_attach']['type'];
        $file_error       = $_FILES['file_attach']['error'];



        //exit script and output error if we encounter any
        if($file_error>0)
        {
          $mymsg = array(
          1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
          2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
          3=>"The uploaded file was only partially uploaded",
          4=>"No file was uploaded",
          6=>"Missing a temporary folder" );
            
          $output = json_encode(array('type'=>'error', 'text' => $mymsg[$file_error]));
          die($output);
        }
          
        //read from the uploaded file & base64_encode content for the mail
        $handle = fopen($file_tmp_name, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $encoded_content = chunk_split(base64_encode($content));
        //now we know we have the file for attachment, set $file_attached to true
        $file_attached = true;
        
      }



      if($file_attached) //continue if we have the file
      {
        
        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (RFC)
        $eol = "\r\n";

        // main header (multipart mandatory)
        $headers = "From: " . $_POST["fullName"] . " <" .$_POST["userEmail"] . ">" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol;

        // message
        $body .= "--" . $separator . $eol;
        $body .= "Content-type:text/html; charset=utf-8\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $message . $eol;

        // attachment
        $body .= "--" . $separator . $eol;
        $body  .= "Content-Type:".$file_type." ";
        $body .= "Content-Type: application/octet-stream; name=\"" . $file_name . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment; filename=\"".$file_name."\"". $eol;
        $body .= $encoded_content . $eol;
        $body .= "--" . $separator . "--";
        
      } else {
        
        $eol = "\r\n";
        
        $headers = "From: Fromname <info@fromemail.com>" . $eol;
        $headers .= "Reply-To: ". strip_tags($email_address) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $body .= $message . $eol;

      }

      // Applicant Email COnfiguration
      
      $adminName = "Forward By Anakle";
      $applicantEmail = $_POST["userEmail"];
      $appSubject = "Application For Forward By Anakle's Intership Program";
      
      // main header (multipart mandatory)
      $appHeaders = "From: " . $adminName . " <" . $to_email . ">" . $eol;
      $appHeaders .= "MIME-Version: 1.0" . $eol;
      $appHeaders .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
      $appHeaders .= "Content-Transfer-Encoding: 7bit" . $eol;
      
      $appMessage = '<html><body>';
      $appMessage .= "<p style='margin-bottom: 20px'>Awesome! You have successfully applied for the next batch of our intership program. <br>We will contact you via email or phone for further information.</p>";
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



      $send_mail = mail($to_email, $subject, $body, $headers);

      if(!$send_mail)
      {
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => 'Could not be subbmitted! Please try again later.'));
        die($output);
      } else {
        mail($applicantEmail, $appSubject, $appBody, $appHeaders);
        $output = json_encode(array('type'=>'message', 'text' => 'Thank you! Your application has been submitted.'));
        die($output);
      }

    }else { }  

  }
?>
