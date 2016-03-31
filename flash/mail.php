<?
$name=$_POST['name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$message=$_POST['message'];

$ToEmail = "brian@yourwebcast.com";
$ToSubject = "Message from your site";

$EmailBody =   "Name: $name\n 
				Email: $email\n
				Phone: $phone\n
				Message: $message\n";

$Message = $EmailBody;


$headers .= "Content-type: text; charset=iso-8859-1\r\n";
$headers .= "From:".$name." / ".$email."\r\n";

mail($ToEmail,$ToSubject,$Message, $headers);

?>

