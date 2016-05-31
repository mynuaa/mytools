<?

if (!defined('IN_MYTOOLS')) {
	header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
	echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL {$_SERVER['PHP_SELF']} was not found on this server.</p>
</body></html>
EOF;
	die();
}

require_once 'include_libs/PHPMailer/PHPMailerAutoload.php';

$content = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

$m_address = $content['m_address'];
$m_showname = $content['m_showname'];
$m_subject = $content['m_subject'];
$m_body = $content['m_body'];

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "smtp.nuaa.edu.cn";
$mail->SMTPAuth = true;
$mail->Username = "zfj";
$mail->Password = "zfjmail.2014";
$mail->Port = 25;
$mail->From = "zfj@nuaa.edu.cn";
$mail->FromName = "纸飞机南航青年网络社区";
$mail->AddAddress($m_address, $m_showname);
$mail->IsHTML(true);
$mail->Subject = $m_subject;
$mail->Body = $m_body;

if (!$mail->Send()) echo $mail->ErrorInfo;
else echo "success";
