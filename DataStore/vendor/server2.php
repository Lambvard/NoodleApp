<?php
include('db.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

require 'vendor/autoload.php';


$mail = new PHPMailer();
//$sheet= new Spreadsheet();
$sheet = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

/*
if(isset($_POST['uploadData'])){
	$month=$_POST['mon'];
	$type=$_POST['type'];
	$sp=explode('-', $month);
	$data_mon=$sp[1];
	$year=$sp[0];




	$filetype = ['xls', 'xlsx'];
	$file_location='../uploads/';
	$file=basename($_FILES["fileuploaddata"]["name"]);
	$file_path=$file_location.$file;
	$check_ext=strtolower(pathinfo($file_path,PATHINFO_EXTENSION));

	if(!in_array($check_ext, $filetype)){
		die ("File type is not excel");
	//	echo $file;

	}else{
		
			//cehck the if the data exist before
		$check=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.upload_file_data where data_mon='$data_mon' and data_year='$year'");
		$check_before=sqlsrv_has_rows($check);

		if($check_before>0){
			echo "Data has been previously uploaded for the month of  ".$data_mon." kindly get approval to upload another instance";

		}else{
			//echo "Data fresh";


				$file_new_name=date("Y-m-d")."-".base64_encode(rand(2,10000000)).".xlsx";

		//$date=$_SESSION['user']."-".date("d-m-Y");
		//$file_that_i_upload_rename=rename($file,$year);
		$targetFile = $file_location.$file;
		$targetDataname=$targetFile;

		//."/".$file_new_name

		if(move_uploaded_file($_FILES["fileuploaddata"]["tmp_name"], $file_path)){
		
	$status="Uploaded";			
	$data_time=date("H:i:s");
	$data_date=date("Y-m-d");
	$data_mon=$data_mon;
	$data_year=$year;
	$data_loc=$file_path;

	$id="Temp-".date("Y-m-d").rand(2,9999);

	$uploadData_in="INSERT INTO distribution.dbo.[upload_file_data] (Status,data_time,data_date,data_mon,data_year,data_loc,id,file_name_in) values(?,?,?,?,?,?,?,?)";
	$prepare_data=sqlsrv_prepare($db_conn,$uploadData_in,array($status,$data_time,$data_date,$data_mon,$data_year,$data_loc,$id,$file));
							if(sqlsrv_execute($prepare_data)==TRUE){



					$file_to_work_on=$_FILES["fileuploaddata"]["tmp_name"];
					$default_loc="../uploads/".$file;


					//$current_sheet=$sheet->load($default_loc);


					$spreadsheet = IOFactory::load($default_loc);
					$sheetData = $spreadsheet->getActiveSheet()->toArray();
					//null, true, true, true

						unset($sheetData[0]);
						foreach ($sheetData as $row) {
							$staffid=$row[1];
							$cadre=$row[2];			
   						$saveFile="INSERT INTO distribution.dbo.[uploaded_staff_list](staffid,cadre,date,month,year,id) values(?,?,?,?,?,?)";
						$saveFile_pre=sqlsrv_prepare($db_conn,$saveFile,array($staffid,$cadre,$data_date,$data_mon,$data_year,$id));
								sqlsrv_execute($saveFile_pre);
							   						
							}


							// update table for all staff

				$update_data="UPDATE [distribution].[dbo].[uploaded_staff_list] set qty=(SELECT cadre_table.Qty from [distribution].[dbo].[cadre_table] where cadre_table.cadre=uploaded_staff_list.cadre) ";
					$update_data_d=sqlsrv_prepare($db_conn,$update_data,array($data_mon,$year));
					sqlsrv_execute($update_data_d);	
//and month='?' and year='?'
				// insert into aggregatetable
					$save_aggregate=  "INSERT into [distribution].[dbo].[aggregate_data_upload] (aggre_cadre,aggre_qty,doc_id) SELECT uploaded_staff_list.cadre,sum (cast( uploaded_staff_list.qty as int)),id from [distribution].[dbo].[uploaded_staff_list] group by uploaded_staff_list.cadre";
					$save_aggregate_data=sqlsrv_prepare($db_conn,$save_aggregate,array($data_date,$data_mon,$data_year));
					sqlsrv_execute($save_aggregate_data);

//where date=? and month='?' and year='?'



									echo "Data Uploaded Successfully";
								}else{
							echo "Error while uploading data, Please Contact the System Administrator";
								}
	
		}else{
			echo "Location not found";
		}
		//echo $file_loc;
	}

//$date.












		}


		//echo $file;
	

	//echo $file;
}else if(isset($_POST['login'])){
	$email=$_POST['email'];
	$password=$_POST['password'];
	
	$check_connect=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.login where email='$email' and password='$password'");
		$count=sqlsrv_has_rows($check_connect);
		if($count>0){
				$check_connect_fed=sqlsrv_fetch_array($check_connect,SQLSRV_FETCH_ASSOC);
				
				$_SESSION['user']=$check_connect_fed['StaffID'];
				$_SESSION['location']=$check_connect_fed['location'];
				//$fb=$_SESSION['location'];
				
		}else{
			$fb="You are typing an invalid credentials";
		}

	echo json_encode($fb);
}else if(isset($_POST['logout_id'])) {
	alert("Logout Successfully");
	// code...
}elseif (isset($_POST['delete_data'])) {
	$staffid=$_POST['staff_id'];
	$id=$_POST['id'];
	$data_date=date("Y-m-d");
	$data_time=date("h:i:s");

	$delete_data= sqlsrv_query($db_conn,"DELETE distribution.dbo.[upload_file_data] where id='$id'");
	
	echo json_encode("Deleted Successfully");	



	//$DaleteData_in="INSERT INTO [distribution].[dbo].[delete_log] (delete_id,del_date,del_time) values(?,?,?)";
	

	
	// code...
}elseif(isset($_POST['cadre_data'])){
	$cadretype=$_POST['cadretype'];
	$cadreNum=$_POST['cadreNum'];
	$dater=date("Y-m-d H:i:s");
	//$location=$_SESSION['location'];
	$id="Data".rand(3,10000).date("Y-m-d-H:i:s").$cadretype;
	$location="Location";
	$month=date("m");

		$data_d=[];
		$carde_check=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table] where month='$month' and cadre='$cadretype'");
		$carde_check_count=sqlsrv_has_rows($carde_check);
		if($carde_check_count>0){
			$data_d['old_file']="You have previously create this cadre this month";

		}else{
		
	$cadre_data_d="INSERT INTO distribution.dbo.[cadre_table] (cadre,Qty,dater,month,q_id,location) values(?,?,?,?,?,?)";
	$cadre_data_dd=sqlsrv_prepare($db_conn,$cadre_data_d,array($cadretype,$cadreNum,$dater,$month,$id,$location));
	if(sqlsrv_execute($cadre_data_dd)===TRUE){
		$data_d['success']= 'Saved Successfully';
		$data_d['cadreuse']=$cadretype;

		$fetc=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table]");
		while($fetc_d=sqlsrv_fetch_array($fetc,SQLSRV_FETCH_ASSOC)){
			$data_d[]=$fetc_d;
		}

	}else{
		$data_d['error']= "Error Saving";
	}
}
echo json_encode($data_d);
}
elseif(isset($_POST['refresh_cadre'])){
		$yu=array();

	$refresh_data_cadre=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.[cadre_table]");
	while ($refresh_data_cadre_d=sqlsrv_fetch_array($refresh_data_cadre,SQLSRV_FETCH_ASSOC)) {
		$yu=$refresh_data_cadre_d;
	}
	echo json_encode($yu);
}
elseif(isset($_POST['viewDataView'])){
	$dataView=$_POST['dataView'];
		
	$y=array();
	$dataView_d=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[upload_file_data] where id='$dataView' ");
	$dataView_dd=sqlsrv_fetch_array($dataView_d,SQLSRV_FETCH_ASSOC);
	

$path=$dataView_dd['file_name_in'];
$location="../uploads/";
$path_d=scandir($location);
$pattern = $location .$path;
//$path_dd = array_diff($path_d, array('.', '..'));



	echo json_encode($pattern);
}

elseif(isset($_POST['app_data'])){
	$hr=$_POST['hr_data'];
	$whse=$_POST['whse_data'];
	$month=$_POST['month'];




$monthlynoodles = "monthlynoodlesData.csv";
$monthlynoodles_open = fopen($monthlynoodles, 'w');
fputcsv($monthlynoodles_open, array('EMPLOYEE ID','CADRE','MONTH','DATE'));
$monthlynoodles_open_fetch=sqlsrv_query($db_conn,"SELECT staffid,cadre,qty,month,date FROM [distribution].[dbo].[uploaded_staff_list] where month='$month' and qty is not null");

	while ($d=sqlsrv_fetch_array($monthlynoodles_open_fetch,SQLSRV_FETCH_ASSOC)){
	fputcsv($monthlynoodles_open, $d);
}
	

fclose($monthlynoodles_open);





	 // Server settings
     $mail->SMTPDebug = 1;                                       // Enable verbose debug output
   $mail->isSMTP();
    //$mail->isMail();                                            // Send using SMTP
    $mail->Host       = 'smtp-mail.outlook.com';                // Set the SMTP server to send through
    $mail->SMTPSecure='TLS';
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'it.notifications@dufil.com';                      // SMTP username
    $mail->Password   = 'FormsP@$$2023';                         // SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                   // TCP port to connect to

    // Recipients
    $mail->setFrom('it.notifications@dufil.com', 'DUFIL FORMS');
    $mail->addAddress($hr, "Test Data"); 
    //$mail->addAddress($whse, "Test Data");      // Add a recipient
    $mail->addReplyTo('it.notifications@dufil.com', 'DUFIL FORMS');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');


    // Attachments
    $mail->addAttachment('monthlynoodlesData.csv');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'EMPLOYEE NOODLES DISTRIBUTION APPROVAL ';
    $mail->Body    = nl2br("Dear  ".$hr.';'."\r\n\n Employee Noodles distribution process has been initiated by, you are requested to view the sheet, approve or disapprove as required. Note: Your approval is vital for the process to be completed before issuance can start .\n\n Kindly click the link below to approve or reject his or her request
    <a href='127.0.0.1/distribution/documents/Approval_page.php?lambda=".base64_encode($hr)."'>Click Here to Approve</a>\n\n DUFIL FORMS.
    		");
    $mail->AltBody = " This mail is to be send to a recepient in Dufil Prima Foods Plc";

    $mail->send();

//END OF HR

//?lambda=".$hr."


//HR MAIL


	 // Server settings
     $mail->SMTPDebug = 1;                                       // Enable verbose debug output
   $mail->isSMTP();
    //$mail->isMail();                                            // Send using SMTP
    $mail->Host       = 'smtp-mail.outlook.com';                // Set the SMTP server to send through
    $mail->SMTPSecure='TLS';
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'it.notifications@dufil.com';                      // SMTP username
    $mail->Password   = 'FormsP@$$2023';                         // SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                  // TCP port to connect to

    // Recipients
    $mail->setFrom('it.notifications@dufil.com', 'DUFIL FORMS');
    $mail->addAddress($whse, "Test Data"); 
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
    $mail->Body    = nl2br("Dear  ".$whse.';'."\r\n\n Employee Noodles distribution process has been initiated by, you are requested to view the sheet, approve or disapprove as required. Note: Your approval is vital for the process to be completed before issuance can start .\n\n Kindly click the link below to approve or reject his or her request
    <a href='127.0.0.1/distribution/documents/approval_page.php?lambda=".base64_encode($whse).">Click Here to Approve</a>\n\n DUFIL FORMS.
    		");
    $mail->AltBody = " This mail is to be send to a recepient in Dufil Prima Foods Plc";

    $mail->send();

//END OF HR

//?lambda=".$whse."


    $get_month_doc_details=sqlsrv_query($db_conn,'SELECT * FROM [distribution].[dbo].[upload_file_data]');
    $get_month_doc_details_d=sqlsrv_fetch_array($get_month_doc_details,SQLSRV_FETCH_ASSOC);

    $doc_month=$get_month_doc_details_d['data_mon'];
    $doc_year=$get_month_doc_details_d['year'];
    $doc_id=$get_month_doc_details_d['id'];


    $insert_sql='INSERT INTO [distribution].[dbo].[approval_table] ()';



	
	echo json_encode("Mail sent Successfully for processing");  
}
elseif(isset($_POST['save_data_cadre'])){

		$cadre_qtyData=$_POST['cadre_qtyData'];
		$flavourData=$_POST['flavourData'];
		$cadreQty=$_POST['cadreQty'];
		$date=date("Y-m-d");
		$month=date("m");
		$day=date("d");




		$count_data=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[cadre_table] where cadre='$cadre_qtyData'  and month='$month'");
		$count_data_pick=sqlsrv_fetch_array($count_data,SQLSRV_FETCH_ASSOC);
		$count_current_cadre=$count_data_pick['Qty'];

		$count_data_flavour=sqlsrv_query($db_conn,"SELECT SUM(CAST(qty as INT)) as qty from distribution.dbo.[map_product_cadre] where cadre='$cadre_qtyData' and month='$month'");
		$count_data_flavour_pick=sqlsrv_fetch_array($count_data_flavour,SQLSRV_FETCH_ASSOC);
		$count_current_flavour_pick=$count_data_flavour_pick['qty'];
		$id_d="Data".rand(3,10000).date("Y-m-d-h:i:s").$cadre_qtyData;
		
		$map_id="Temp"."-".$id_d;
		$id_d="Data".rand(3,10000).date("Y-m-d-h:i:s").$cadre_qtyData;

		//if($count_current_cadre<=$count_current_flavour_pick){

		$d_save=[];
		$dtat="INSERT INTO distribution.dbo.[map_product_cadre] (cadre,flavour,qty,map_id,item_id,month,year,day) values (?,?,?,?,?,?,?,?)";
		$dtat_d=sqlsrv_prepare($db_conn,$dtat,array($cadre_qtyData,$flavourData,$cadreQty,$map_id,$id_d,$month,$date,$day));
		$che=sqlsrv_execute($dtat_d);
		if($che==TRUE){
			$d_save['success_save']= "Saved Successfully";

		}else{
			$d_save['error_save']= "Error saving";
		}
	//}//end of if check quantity match
//	else{
//		$d_save['overflow_save']= "You cannot add more than the required flavour";
//	}

		echo json_encode($d_save);
}

elseif(isset($_POST['fetch_data_cadre'])){

	$id_uniq=$_POST['id_unique'];

	//$count_uniq=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[cadre_table] where map_id='$id_uniq'");
	//	$count_count_uniq=sqlsrv_fetch_array($count_uniq,SQLSRV_FETCH_ASSOC);
		echo json_encode($id_uniq);
}
elseif(isset($_POST['fetch_cadre_data'])){
	$cad_data='';
	$cadreCount=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[cadre_list]");
		while($CadreCount_d=sqlsrv_fetch_array($cadreCount,SQLSRV_FETCH_ASSOC)){
		$cad_data.='<option>'.$CadreCount_d['cadre']."</option>";
		}
		echo json_encode($cad_data);

}
elseif(isset($_POST['pull_data_at_all_time'])){

			$datatable_data_pull='';

			$fetch_carde=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table]");
			while ($fc=sqlsrv_fetch_array($fetch_carde,SQLSRV_FETCH_ASSOC)){
				

							$datatable_data_pull.='<tr>
								<td>'.$fc['month'].'</td>
								<td>'.$fc['cadre'].'</td>
								<td>'.$fc['location'].'</td>
								<td>'.$fc['Qty'].'</td>
								<td><button style="background-color:brown; border-radius:5px 10px 5px 10px; border: 1px solid white; color:white;" class="individual_item" value='.$fc['q_id'].' style=""><i class="icofont-ui-edit"></i></button></td>
								<td><button class="individual_item" value='.$fc['q_id'].' style="background-color:green; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"><i class="icofont-eye-open "  style=""></i></button></td>

							</tr>

				';
			}


			echo json_encode($datatable_data_pull);

}*/

if(isset($_POST['fetch_flavour_data_app'])){
	//$fla_data='';
	//$FlavourCount=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[product_table]");
	//	while($FlavourCount_d=sqlsrv_fetch_array($FlavourCount,SQLSRV_FETCH_ASSOC)){
	//	$fla_data.='<option>'.$FlavourCount_d['sku']."</option>";
	//	}
		echo json_encode("I am here");

}
















?>