<?php

//https://mailtrap.io/blog/html-form-send-email/
//https://www.google.com/recaptcha

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust the relative path if necessary


$Myemail = 'philippe.koulouris@gmail.com';

$errors = [];
$errorMessage = ' ';
$successMessage = ' ';

//Captcha keys
$siteKey = '6Le5o7AqAAAAAEQsnm90SvPU4KZxBAxEOb-iSwbx';
$secret = 'KEY CODE HERE';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput ($_POST['name']);
    $email = sanitizeInput ($_POST['email']);
    $message = sanitizeInput ($_POST ['message']);
    $recaptchaResponse = sanitizeInput ($_POST['g-recaptcha-response']);

    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}";
    $verify = json_decode(file_get_contents($recaptchaUrl));

    if (!$verify->success) {
        $errors [ ] = 'Recaptcha failed';
    }
    if (empty ($name)) {
        $errors [ ] = 'Name is empty';
    }
    if (empty ($email)) {
        $errors [ ] = 'Email is empty';
    } else if (!filter_var($email, filter: FILTER_VALIDATE_EMAIL)) {
        $errors [ ] = 'Email is invalid';
    }
    if (empty ($message)) {
        $errors [ ] = 'Message is empty';
}

    if (!empty ($errors)) {
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red; '>{$allErrors}</p>";
    } else {
        $toEmail = 'myemail@example.com';
        $emailSubject = 'New email from your contact form';
/* echo 'sending ...';
if (!empty($_POST))
{
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  if (empty($name)) {
      $errors[] = 'Name is empty';
  }

  if (empty($email)) {
      $errors[] = 'Email is empty';
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Email is invalid';

  }

  if (empty($message)) {
      $errors[] = 'Message is empty';
  }

  if (!empty($errors)) {
      $allErrors = join ('<br/>', $errors);
      $errorMessage = "<p style='color: red; '>{$allErrors}</p>";
  } else {
      $fromEmail = 'anyname@example.com';
      $emailSubject = 'New email from your contact form';
*/


      // Create a new PHPMailer instance
      $mail = new PHPMailer(exceptions: true);

//disable the authentication process (optional for troubleshooting)
      $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

      try {
            // Configure the PHPMailer instance
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '34068da005e343';
            $mail->Password = '8a5a31438f1023';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
           
            // Set the sender, recipient, subject, and body of the message 
          
            $mail->addAddress($Myemail);
            $mail->setFrom($email);
            $mail->Subject = $emailSubject;
            $mail->isHTML( isHtml: true);
            $mail->Body = "<p>Name: {$name}</p><p>Email: {$email}</p><p>Message: {$message}</p>";
         
            // Send the message
            $mail->send () ;
            $successMessage = "<p style='color: green; '>Thank you for contacting us :)</p>";
       // Redirect to the contact form page after a successful email
       header("Location: http://127.0.0.1:5500/public/Ginkjo.html#contactUs");
       exit(); // Make sure to exit after the redirect to stop further processing
   } catch (Exception $e) {
       $errorMessage = "<p style='color: red;'>Something went wrong. Please contact philippe.koulouris@gmail.com.</p>";
       $errorMessage .= "<p>Error: " . $mail->ErrorInfo . "</p>";  // Display PHPMailer's error message
   }
}
}

function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

?>