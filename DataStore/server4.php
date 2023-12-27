<?php

include('db.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

require 'vendor/autoload.php';


$mail = new PHPMailer();
$mail_hr = new PHPMailer();
$mail_whse = new PHPMailer();
//$sheet= new Spreadsheet();
$sheet = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

if(isset($_POST['app_data'])){
    $hr=$_POST['hr_data'];
    $whse=$_POST['whse_data'];
    $monthData=$_POST['monthData'];
    $type=$_POST['type'];
    $fm=$_POST['fm_data'];
    $mn=explode('-', $monthData);
    $month=$mn[0];
    $year=$mn[1];
    $loc_d=$_SESSION['location'];
    $sentDate=date("Y-m-d h:i:s");
    $final_data="Start";




$get_month_doc_details=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[upload_file_data] where data_mon='$month' and location='$loc_d' and data_year='$year' and type='$type'");
    $get_month_doc_details_d=sqlsrv_fetch_array($get_month_doc_details,SQLSRV_FETCH_ASSOC);

    $doc_month=$get_month_doc_details_d['data_mon'];
    $doc_year=$get_month_doc_details_d['data_year'];
    $doc_id_data=$get_month_doc_details_d['id'];
    $app_date_use=date('Y-m-d');



    $get_if_previously_approved=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[approval_table] where document_id='$doc_id_data' and month='$month'and year='$year' and location='$loc_d' and close_status !='Rejected' and type='$type'");
    $get_if_previously_approved_d=sqlsrv_has_rows($get_if_previously_approved);

    if($get_if_previously_approved_d>0){
        echo json_encode("You have previously sent an approval requested on this document, in case of any difficulties, contact your Administrator ");
    }else{
    //  echo json_encode("Should I make the sending of approval");
   

//aggregate mail

/*
$monthlynoodles = "monthlynoodlesData_aggregate.csv";
$monthlynoodles_open = fopen($monthlynoodles, 'w');
fputcsv($monthlynoodles_open, array('EMPLOYEE ID','CADRE','MONTH','DATE'));
$monthlynoodles_open_fetch=sqlsrv_query($db_conn,"SELECT staffid,cadre,qty,month,date FROM [distribution].[dbo].[aggregate_data_upload] where month='$month' and qty is not null");

    while ($d=sqlsrv_fetch_array($monthlynoodles_open_fetch,SQLSRV_FETCH_ASSOC)){
    fputcsv($monthlynoodles_open, $d);
}*/
//end of aggregate mail 

$monthlynoodles = "monthlynoodlesData.csv";
$monthlynoodles_open = fopen($monthlynoodles, 'w');
fputcsv($monthlynoodles_open, array('EMPLOYEE ID','FULLNAME','CADRE','QTY','MONTH','DATE'));
$monthlynoodles_open_fetch=sqlsrv_query($db_conn,"SELECT staffid,employee_name,cadre,qty,month,date FROM [distribution].[dbo].[uploaded_staff_list] where month='$month' and qty is not null");

    while ($d=sqlsrv_fetch_array($monthlynoodles_open_fetch,SQLSRV_FETCH_ASSOC)){
    fputcsv($monthlynoodles_open, $d);
}
    

fclose($monthlynoodles_open);
    
    $hr_id=$hr."-lad-".$doc_id_data;

    $mail_hr->SMTPDebug = 1;                                       // Enable verbose debug output
   $mail_hr->isSMTP();
    //$mail->isMail();                                            // Send using SMTP
    $mail_hr->Host       = '192.168.6.19';                // Set the SMTP server to send through
    $mail_hr->SMTPSecure='TLS';
    $mail_hr->SMTPAuth   = true;                                   // Enable SMTP authentication
   $mail_hr->Username   = 'Forms';                      // SMTP username
   $mail_hr->Password   = 'Duf19P@$$';                         // SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail_hr->Port       = 25;


$mail_hr->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];



     // Server settings
                                      // TCP port to connect to

    // Recipients
    $mail_hr->setFrom('it.notifications@dufil.com', 'DUFIL FORMS');
    $mail_hr->addAddress($hr, "Test Data"); 
    //$mail->addAddress($whse, "Test Data");      // Add a recipient
    $mail_hr->addReplyTo('it.notifications@dufil.com', 'DUFIL FORMS');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');


    // Attachments
    //$mail->addAttachment('monthlynoodlesData.csv');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $hr_link=base64_encode($hr_id);
    echo $hr_id;
    // Content
    $mail_hr->isHTML(true);                                  // Set email format to HTML
    $mail_hr->Subject = 'EMPLOYEE NOODLES DISTRIBUTION APPROVAL ';
    $mail_hr->Body    = nl2br("Dear  ".$hr.';'."\r\n\n Employee Noodles distribution process for the month ".date('M-Y')." has been initiated, you are requested to view the sheet, approve or disapprove as required. Note: Your approval is vital for the process to be completed before issuance can start .\n\n Kindly click the link below to approve or reject his or her request
    <a href='https://forms.dufil.com/distribution/documents/Approval_page.php?lambda=".$hr_link."'>Click Here to Approve</a>\n\n DUFIL FORMS.
            ");
    $mail_hr->AltBody = " This mail is to be send to a recepient in Dufil Prima Foods Ltd";

    //$mail->send();
 $hrlink="HR";
 $insert_sqlhr='INSERT INTO [distribution].[dbo].[approval_table] (app_id,document_id,approval_id,approval_app_date,month,year,location,sent_date,type,final_data) values (?,?,?,?,?,?,?,?,?,?)';
 $insert_sql_preparehr=sqlsrv_prepare($db_conn,$insert_sqlhr,array($hrlink,$doc_id_data,$hr,$app_date_use,$doc_month,$doc_year,$loc_d,$sentDate,$type,$final_data));
 sqlsrv_execute($insert_sql_preparehr);
if(!$mail_hr->send()){
echo json_encode("Error sending the mail");
}else{
    echo json_encode("Mail sent Successfully");
}




    //;


//END OF HR

//?lambda=".$hr."



$wshe_id=$whse."-lad-".$doc_id_data;
    
    
    
    $mail_whse->SMTPDebug = 1;                                       // Enable verbose debug output
   $mail_whse->isSMTP();
    //$mail->isMail();                                            // Send using SMTP
    $mail_whse->Host       = '192.168.6.19';                // Set the SMTP server to send through
    $mail_whse->SMTPSecure='TLS';
    $mail_whse->SMTPAuth   = true;                                   // Enable SMTP authentication
   $mail_whse->Username   = 'Forms';                      // SMTP username
   $mail_whse->Password   = 'Duf19P@$$';                         // SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail_whse->Port       = 25;


$mail_whse->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];

                                // TCP port to connect to

    // Recipients
    $mail_whse->setFrom('it.notifications@dufil.com', 'DUFIL FORMS');
    $mail_whse->addAddress($whse, "Test Data"); 
    //$mail->addAddress($whse, "Test Data");      // Add a recipient
    $mail_whse->addReplyTo('it.notifications@dufil.com', 'DUFIL FORMS');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');


    // Attachments
     $mail_whse->addAttachment('monthlynoodlesData.csv');
   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail_whse->isHTML(true);                                  // Set email format to HTML
    $mail_whse->Subject = 'EMPLOYEE NOODLES DISTRIBUTION APPROVAL ';
    $mail_whse->Body    = nl2br("Dear  ".$whse.';'."\r\n\n Employee Noodles distribution process for the month ".date('M-Y')." has been initiated, you are requested to view the sheet, approve or disapprove as required. Note: Your approval is vital for the process to be completed before issuance can start .\n\n Kindly click the link below to approve or reject his or her request
    <a href='https://forms.dufil.com/distribution/documents/approval_page.php?lambda=".base64_encode($wshe_id)."'>Click Here to Approve</a>\n\n DUFIL FORMS.
            ");
    $mail_whse->AltBody = " This mail is to be send to a recepient in Dufil Prima Foods Ltd";

    
$warehouse="WHSE";
$insert_sqlwhse='INSERT INTO [distribution].[dbo].[approval_table] (app_id,document_id,approval_id,approval_app_date,month,year,location,sent_date,type,final_data) values (?,?,?,?,?,?,?,?,?,?)';
$insert_sql_preparewhse=sqlsrv_prepare($db_conn,$insert_sqlwhse,array($warehouse,$doc_id_data,$whse,$app_date_use,$doc_month,$doc_year,$loc_d,$sentDate,$type,$final_data));
sqlsrv_execute($insert_sql_preparewhse);   
$mail_whse->send();
//END OF HR





$fm_send=$fm."-lad-".$doc_id_data;
    
   
    $mail->SMTPDebug = 1;                                       // Enable verbose debug output
   $mail->isSMTP();
    //$mail->isMail();                                            // Send using SMTP
    $mail->Host       = '192.168.6.19';                // Set the SMTP server to send through
    $mail->SMTPSecure='TLS';
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
   $mail->Username   = 'Forms';                      // SMTP username
   $mail->Password   = 'Duf19P@$$';                         // SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 25;


$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];


    // Recipients
    $mail->setFrom('it.notifications@dufil.com', 'DUFIL FORMS');
    $mail->addAddress($fm, "Test Data"); 
    //$mail->addAddress($whse, "Test Data");      // Add a recipient
    $mail->addReplyTo('it.notifications@dufil.com', 'DUFIL FORMS');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');


    // Attachments
     $mail->addAttachment('monthlynoodlesData.csv');
   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'EMPLOYEE NOODLES DISTRIBUTION APPROVAL ';
    $mail->Body    = nl2br("Dear  ".$fm.';'."\r\n\n Employee Noodles distribution process for the month ".date('M-Y')."  has been initiated, you are requested to view the sheet, approve or disapprove as required. Note: Your approval is vital for the process to be completed before issuance can start .\n\n Kindly click the link below to approve or reject his or her request
    <a href='https://forms.dufil.com/distribution/documents/approval_page.php?lambda=".base64_encode($fm_send)."'>Click Here to Approve</a>\n\n DUFIL FORMS.
            ");
    $mail->AltBody = " This mail is to be send to a recepient in Dufil Prima Foods Ltd";

    if($mail->send()){
        echo json_encode("Mail sent Successfully for processing");
    }

$factorymanager="FM";
$insert_sqlFm='INSERT INTO [distribution].[dbo].[approval_table] (app_id,document_id,approval_id,approval_app_date,month,year,location,sent_date,type,final_data) values (?,?,?,?,?,?,?,?,?,?)';
$insert_sql_prepareFM=sqlsrv_prepare($db_conn,$insert_sqlFm,array($factorymanager,$doc_id_data,$fm,$app_date_use,$doc_month,$doc_year,$loc_d,$sentDate,$type,$final_data));
sqlsrv_execute($insert_sql_prepareFM);
//END OF HR

 
 }


    
     
}





?>