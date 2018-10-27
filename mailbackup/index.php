<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

header('Content-Type: application/json');
include('../application/config/email.php');
include('Backup.php');
include('PHPMailer/class.smtp.php');
include "PHPMailer/class.phpmailer.php"; 

if(!isset($_REQUEST['token']) || $_REQUEST['token'] !== 'yRQYnWzskCZUxPwaQupWkiUzKELZ49eM7oWxAQK_ZXw'){
	$return['success'] = false;
	$return['message'] = "Lỗi xác thực!";
	echo json_encode($return); die;
}
$Send_name = $_REQUEST['name'];
$Token = $_REQUEST['token'];

/**
*************************** Backup dữ liệu ******************************
*/
$folder = realpath(dirname(__FILE__)).'\dbBackup';
$objBackup = new Backup($db,$folder);
$file_sql = $objBackup->backupTables();
if(!$file_sql){
	$return['success'] = false;
	$return['message'] = "Backup dữ liệu bị lỗi!";
	echo json_encode($return); die;
}
$path_file = $folder.'\\'.$file_sql.".gz";

/**
*************************** Gửi Email ******************************
*/
$nFrom = "Hệ thống gửi mail phần mềm T-sales";    //mail duoc gui tu dau, thuong de ten cong ty ban
$mFrom = 'toanph155@gmail.com';  //dia chi email cua ban 
$mPass = 'efyvn@123';       //mat khau email cua ban
$nTo = 'Anh Huấn'; //Ten nguoi nhan
$mTo = $email['toMail'];   //dia chi nhan mail
$mail = new PHPMailer();
$body = 'Người gửi: '.$Send_name;   // Noi dung email
$title = 'Sao lưu dữ liệu phần mềm T-sales vào lúc '.date('d/m/Y h:i');
$mail->IsSMTP();             
$mail->CharSet  = "utf-8";
$mail->SMTPDebug  = 0;   // enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;    // enable SMTP authentication
$mail->SMTPSecure = "ssl";   // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";    // sever gui mail.
$mail->Port       = 465;         // cong gui mail de nguyen
// xong phan cau hinh bat dau phan gui mail
$mail->Username   = $mFrom;  // khai bao dia chi email
$mail->Password   = $mPass;              // khai bao mat khau
$mail->SetFrom($mFrom, $nFrom);
$mail->AddReplyTo('toanph@efy.com.vn', 'toanph@efy.com.vn'); //khi nguoi dung phan hoi se duoc gui den email nay
$mail->Subject    = $title;// tieu de email 
$mail->MsgHTML($body);// noi dung chinh cua mail se nam o day.
$mail->AddAddress($mTo, $nTo);
$mail->addAttachment($path_file);
// thuc thi lenh gui mail 

//unlink($path_file);

if(!$mail->Send()) {
    $return['success'] = false;
	$return['message'] = "Gửi mail bị lỗi!";
	unlink($path_file);
	echo json_encode($return); die;
} else {
    $return['success'] = true;
	$return['message'] = "Backup và gửi email thành công!";
	unlink($path_file);
	echo json_encode($return); die;
}


?>