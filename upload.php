<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function success($message) {
  return "<div class='alert alert-success'>$message</div>";
}
function failure($message) {
  return "<div class='alert alert-danger'>$message</div>";
}
include_once("vendor/autoload.php");

if (!isset($_FILES["fileToUpload"]["name"])) {
  $_FILES["fileToUpload"] = $_FILES["file"];
  //die(failure("File not uploaded, undefined index.".print_r($_FILES)));
}
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$enableWhitelist = TRUE;
$enablemail = FALSE;
$filetypeWhitelist = [
  "jpg", "png",
  "bmp", "gif",
  "zip", "rar",
  "7z", "mp3",
  "txt", "docx",
  "svg", "mp4",
  "avi", "mkv",
  "tgz", "gz",
];
function showArray($array) {
  $i = 0;
  $fa = "";
  while ($i < count($array)) {
    if ($i+1 == count($array)) {
        $fa = $fa."$array[$i].";
    } else {
        $fa = $fa."$array[$i], ";
    }
    $i++;
  }
  return $fa;
}
//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if(isset($_POST["upload_file"])) {
  // Check file extension
  $filetype = $filetype = substr($target_file, strrpos($target_file, ".") + 1);
  if ($enableWhitelist == TRUE && !in_array(strtolower($filetype), $filetypeWhitelist)) {
    echo failure("This filetype is not allowed.<br>
    Allowed filetypes are: ".showArray($filetypeWhitelist));
    $uploadOk = 0;
  }
  if (file_exists($target_file)) {
      echo failure("Sorry, file already exists.");
      $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 1*1000*1000*1000*20) {
      echo failure("Sorry, your file is too large.");
      $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      //echo failure("Your file was not uploaded.");
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          echo success("The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
      } else {
          echo failure("Sorry, there was an error uploading your file.");
      }
  }
} else {
  echo failure("No file chosen.");
}

if ($uploadOk == 1 && $enablemail == TRUE) {
  // Import PHPMailer classes into the global namespace
  // These must be at the top of your script, not inside a function

  // Load Composer's autoloader
  require 'vendor/autoload.php';

  // Instantiation and passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
      //Server settings
      $mail->SMTPDebug = 0;                                       // Enable verbose debug output
      $mail->isSMTP();                                            // Set mailer to use SMTP
      $mail->Host       = 'mailout.one.com;send.one.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = 'kristian@roste.org';                     // SMTP username
      $mail->Password   = '\G@\N;Z2JJf8Wtw,EEAj^oA6O9zDkcEHFtfV!5Qmh?%yIeGEpk=&\"dAFc3Od6QT';                               // SMTP password
      $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
      $mail->Port       = 587;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom('kristian@roste.org', 'Mailer');
      $mail->addAddress('kristian@roste.org', 'Kristian');     // Add a recipient
      //$mail->addAddress('ellen@example.com');               // Name is optional
      $mail->addReplyTo('kristian@roste.org', 'Webmaster');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      // Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Someone uploaded a file!';
      $mail->Body    = '
      Someone recently attempted to upload a file.<br>
      Time: '.date('Y-m-d H:m:s').'<br>
      Filename: '.$_FILES["fileToUpload"]["name"].'<br>
      Filesize: '.$_FILES["fileToUpload"]["size"].' bytes<br>
      IP: '.$_SERVER['REMOTE_ADDR'].'
      ';
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}
?>
