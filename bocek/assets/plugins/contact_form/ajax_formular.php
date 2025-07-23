<?php
//Settings
$email_to = "petr@bocek1.cz";
$error = true;

//Filters
$jmeno = filter_var( mb_substr($_POST["jmeno"] ?? "", 0, 64) );
$email = filter_var( mb_substr($_POST["email"] ?? "", 0, 128), FILTER_VALIDATE_EMAIL);
$telefon = filter_var( mb_substr($_POST["telefon"] ?? "", 0, 64) );
$zprava = filter_var( mb_substr($_POST["zprava"] ?? "", 0, 2048) );
//$souhlas = filter_var( mb_substr($_POST["souhlas"] , 0, 64) );

//automatický souhlas
$_POST["souhlas"] = "true";

$souhlas = false;
if (($_POST["souhlas"] ?? "") === "true") {
  $souhlas = true;
};

if ($souhlas) {
  if ($email) {
    // the subject
    $subject = mb_encode_mimeheader("Nová zpráva z webu", 'UTF-8');

    // the message
    $msg = "Nová zpráva z formuláře na webu:".
    "\n\nJméno: " . $jmeno . 
    "\nE-mail: " . $email . 
    "\nTelefon: " . $telefon . 
    "\nSouhlas se zpracováním osobních údajů: ano (" . date("d.m.Y H:i:s"). ")" . 
    "\nZpráva:\n\n" . $zprava;

    //e-mail headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    // send email
    if (mail($email_to, $subject, $msg, $headers)) {
      $error = false;
    }
  }
}

if ($error) {
  echo 0;
} else {
  echo 1;
}
?>