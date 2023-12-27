
<?php

include('db.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

require 'vendor/autoload.php';


$mail = new PHPMailer();

if(isset($_POST['userDatafordistribution'])){


	$staffid_user=$_POST['inputstaffid'];
	$type_noodles=$_POST['type_noodles'];
	$st="on";
	$cur_status="Activate";
	$location_data_use=$_SESSION['location'];
	$id=$_POST['id'];
	$monb=date('m');
	$yearb=date('Y');
	$ta="processed";
	$status ="Closed";
	//and month='$monb' or all_status='Half' and status is null 
	$check_staff=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list]  where uploaded_staff_list.Staffid='$staffid_user' and id='$id' and location='$location_data_use'  and year='$yearb' and final_data='$ta' and month='$monb' and type='$type_noodles' and (all_status = 'Half' OR all_status IS NULL) ");// and status !='$status' or status in ('Issued','Checkout')
	$check_staff_2=sqlsrv_fetch_array($check_staff,SQLSRV_FETCH_ASSOC);
	$check_staff_count=sqlsrv_has_rows($check_staff);
		$check_staff_store=[];
	if($check_staff_count>0){
			$check_staff_store['fullname']=$check_staff_2['employee_name'];
			$check_staff_store['staffid']=$staffid_user;
			$check_staff_store['loc']=$check_staff_2['location'];
			$check_staff_store['dept']=$check_staff_2['dept'];
			$check_staff_store['cadre']=$check_staff_2['cadre'];



		
			}else{
				 $check_staff_store['error_r']="notconnected";

			}
				


echo json_encode($check_staff_store);

}
elseif(isset($_POST['saveissuanceData'])){

	$stid=$_POST['staffid'];
	$stafflo=$_POST['stafflo'];
	$staffnameissue=$_POST['stafidname'];
	$type_noodles=$_POST['type_noodles'];
	$issuer=$_POST['issuer'];
	$id=$_POST['id'];
	$mon_to_use=date("m");
	$mon_year=date("Y");
	$appv="Approved";
	$issued_date_use=date('Y-m-d-h:i:s');
	$stahg="Issued";
	$cadre_issued=$_POST['cadre'];
	$location=$_SESSION['location'];
	$pr="Processed";

			$get_approval_count=sqlsrv_query($db_conn," SELECT count(*) as DataApp FROM [distribution].[dbo].[approval_table] where month='$mon_to_use' and close_status='$appv' and year='$mon_year' and location='$location' and final_data='processed'  and type='$type_noodles' and document_id='$id'");
			$get_approval_count_count=sqlsrv_fetch_array($get_approval_count,SQLSRV_FETCH_ASSOC);

			if($get_approval_count_count['DataApp']>2){

#and all_status !='Full'

				$check_before=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where staffid='$stid' and month='$mon_to_use' and location='$location'and year='$mon_year' and status ='$stahg'  and final_data='$pr'  and type='$type_noodles' and all_status ='Full' and issue_date is not null");
				$check_before_d=sqlsrv_has_rows($check_before);
				if($check_before_d>0){
					echo json_encode("You have previously issued a product or the product have been returned for the month");
				}else{

			$get_cadre=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where staffid='$stid' and id='$id'");
					$get_cadre_data=sqlsrv_fetch_array($get_cadre,SQLSRV_FETCH_ASSOC);
					$use_cadre=$get_cadre_data['cadre'];
				
		$get_product_dis=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[map_product_cadre] where cadre='$use_cadre' and location='$location' and month='$mon_to_use' and year='$mon_year' and final_data='$pr' and type='$type_noodles' and availability='Available'");


				while($sc=sqlsrv_fetch_array($get_product_dis,SQLSRV_FETCH_ASSOC)){
					$flavour_save=$sc['flavour'];
					#$gh[]=$sc;
#and location='$location_data' and month='$d_month' and year='$d_year' and final_data='$final_data' and type='$type_noodles'
				$get_before_collected=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[collect_track_table] where  staffid='$stid' and items_collected='$flavour_save' and cadre='$use_cadre' and location='$location' and id='$id'");
				$get_count=sqlsrv_has_rows($get_before_collected);
				if($get_count<1){
				$get_save_data=sqlsrv_query($db_conn,"INSERT INTO [distribution].[dbo].[collect_track_table] (staffid,cadre,items_collected,location,month,year,type,final_data,id,trans_date) values('$stid','$use_cadre','$flavour_save','$location','$mon_to_use','$mon_year','$type_noodles','$pr','$id','$issued_date_use')");


				}
			}



			$check_all=sqlsrv_query($db_conn,"SELECT count(*) as trackQty from [distribution].[dbo].[collect_track_table] where  staffid='$stid' and cadre='$use_cadre' and location='$location' and id='$id'");
			$check_all_issued=sqlsrv_fetch_array($check_all,SQLSRV_FETCH_ASSOC);
			$all_issed=$check_all_issued['trackQty'];


			$check_mapped_items=sqlsrv_query($db_conn,"SELECT count(*) as MappedQty from [distribution].[dbo].[aggregate_flavour_data] where  cadre='$use_cadre' and location='$location' and doc_id='$id'");
			$check_mapped_items_r=sqlsrv_fetch_array($check_mapped_items,SQLSRV_FETCH_ASSOC);
			$all_issed_mapped=$check_mapped_items_r['MappedQty'];


			if($all_issed<$all_issed_mapped){
				$all_status='Half';
			}else{
				$all_status='Full';
			}


				$update_issuence=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[uploaded_staff_list] set issue_date='$issued_date_use',status='$stahg',employee_name='$staffnameissue',issuer='$issuer',all_status='$all_status' where staffid='$stid' and month='$mon_to_use' and location='$location' and final_data='$pr' and type='$type_noodles' and id='$id'");

				#$deduct_data=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[map_product_cadre] where cadre='$cadre_issued' and month='$mon_to_use' and year='$mon_year' and location='$location' and final_data='processed' and type='$type_noodles'");

				/*while($deduct_data_check=sqlsrv_fetch_array($deduct_data,SQLSRV_FETCH_ASSOC)){
					$identified_flavour=$deduct_data_check['flavour'];
					$identified_flavour_qty=$deduct_data_check['qty'];

					$get_current_number=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[aggregate_flavour_data] where cadre='$cadre_issued' and flavour='$identified_flavour' and month='$mon_to_use' and year='$mon_year' and location='$location' and final_data='processed' and type='$type_noodles'");

					#$get_old_value=sqlsrv_query($db_conn,"SELECT ISNULL(issued_quantity,0) as oldquantity from [distribution].[dbo].[aggregate_flavour_data]  where  cadre='$cadre_issued' and flavour='$identified_flavour' and month_data='$mon_to_use' and year_data='$mon_year' and location='$location' and final_data='processed' and type='$type_noodles'");
				#	$get_old_value_id=sqlsrv_fetch_array($get_old_value,SQLSRV_FETCH_ASSOC);
					#$old_data_here=$get_old_value_id['oldquantity'];
				#	$new_data_here=$old_data_here+$identified_flavour_qty;

					#$update_flavour_qty=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[aggregate_flavour_data] set issued_quantity=$new_data_here where  cadre='$cadre_issued' and flavour='$identified_flavour' and month='$mon_to_use' and year='$mon_year' and location='$location' and type='$type_noodles'");

				}
*/






						//}

					



				


			//	$prep_issu=sqlsrv_prepare($db_conn,$update_issuence,array($issued_date_use,$stahg,$mon_to_use,$stid,$stafflo));
				//if(sqlsrv_execute($prep_issu)==TRUE){
					//echo json_encode("Product successfully issued to ".$stid." at exactly".$issued_date_use);

				//}
					echo json_encode("Product issued successfully to ".$staffnameissue." (".$stid.") on ".$issued_date_use);

}




			}else{
			echo json_encode("You cannot perform this transaction, one of the principals have not authorized the issuance of this products");
			}

			

}elseif(isset($_POST['userDatafordistribution_flavour'])){

	$staffid_user=$_POST['inputstaffid'];
	$location_data=$_POST['location_issed'];
	$type_noodles=$_POST['type_noodles'];
	$st="on";
	$cur_status="Activate";
	$d_month=date('m');
	$d_year=date('Y');
	$final_data="processed";
	$id=$_POST['id'];


	//get the product and quantity and id='$id_of_doc'
	$gh=array();

$get_staff_cadre_data=sqlsrv_query($db_conn,"SELECT * from distribution.[dbo].[uploaded_staff_list] where location='$location_data' and staffid='$staffid_user' and final_data='$final_data'");	
$get_staff_cadre_data_d=sqlsrv_fetch_array($get_staff_cadre_data,SQLSRV_FETCH_ASSOC);
$identified_staff_cadre=$get_staff_cadre_data_d['cadre'];
#$doc_id_to=$get_staff_cadre_data_d['id'];
$get_product_dis=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[map_product_cadre] where cadre='$identified_staff_cadre' and location='$location_data' and month='$d_month' and year='$d_year' and final_data='$final_data' and type='$type_noodles' and availability='Available'");


				while($sc=sqlsrv_fetch_array($get_product_dis,SQLSRV_FETCH_ASSOC)){
					$flavour_save=$sc['flavour'];
					#$gh[]=$sc;
#and location='$location_data' and month='$d_month' and year='$d_year' and final_data='$final_data' and type='$type_noodles'
				$get_before_collected=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[collect_track_table] where  staffid='$staffid_user' and items_collected='$flavour_save' and cadre='$identified_staff_cadre' and location='$location_data' and id='$id'");
				$get_count=sqlsrv_has_rows($get_before_collected);
				if($get_count<1){
					$gh[]=$sc;
				}

	}
echo json_encode($gh);
}
elseif(isset($_POST['logoutissuance'])){

				session_destroy();

	echo json_encode("Logout");
}

elseif(isset($_POST['staff_return_list'])){
	$d_month=date('m');
	$d_year=date('Y');
	$type=$_POST['type_noo'];
	$doc_data=$_POST['doc_data'];
	$ghd=[];
	$status="Closed";
//where location='$location_data' and month='$d_month' and year='$d_year'
	//and status='closed'
	$return_data=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[uploaded_staff_list] where id='$doc_data' and type='$type' and status is null or status='$status' and final_data in ('processed')");
	//location='$location' and month='$d_month' and year='$d_year' and status is null and type='monthly' 

	while($fc=sqlsrv_fetch_array($return_data,SQLSRV_FETCH_ASSOC)){
	$ghd[]=$fc;

	}
echo json_encode($ghd);

}

elseif(isset($_POST['staff_return_list_dept'])){
	$d_month=date('m');
	$d_year=date('Y');
	//$location=$_POST['location'];
	$doc_data=$_POST['doc_data'];
	$ghdd=array();
	// and status='closed'
	// where location='$location' and month='$d_month' and year='$d_year'
//where location='$location_data' and month='$d_month' and year='$d_year'
	$return_data_dept=sqlsrv_query($db_conn,"SELECT Dept, sum(qty) as Quantity from [distribution].[dbo].[uploaded_staff_list] where id='$doc_data' and status is null or status='closed' and final_data in ('processed')  group by Dept");
//,cadre,status//cadre,, status 
	while($fcd=sqlsrv_fetch_array($return_data_dept,SQLSRV_FETCH_ASSOC)){
	$ghdd[]=$fcd;

	}
echo json_encode($ghdd);

}elseif(isset($_POST['staff_return_list_sku'])){
	$d_month=date('m');
	$d_year=date('Y');
	//$location=$_POST['location'];
	$doc_data=$_POST['doc_data'];
//where location='$location_data' and month='$d_month' and year='$d_year'

	$get_type=sqlsrv_query($db_conn,"SELECT type,final_data,location from uploaded_staff_list where id='$doc_data'  ");
	$ytpe=sqlsrv_fetch_array($get_type,SQLSRV_FETCH_ASSOC);
	$type_ch=$ytpe['type'];
	$sta=$ytpe['final_data'];
	$location=$ytpe['location'];
	$stat="Closed";
	$ghdsku=array();
	$return_datasku=sqlsrv_query($db_conn," SELECT map_product_cadre.flavour,sum(map_product_cadre.qty) as Quantity from [distribution].[dbo].[uploaded_staff_list]
  JOIN [dbo].[map_product_cadre] ON (uploaded_staff_list.cadre=map_product_cadre.cadre and uploaded_staff_list.location=map_product_cadre.location
   and uploaded_staff_list.type=map_product_cadre.type and uploaded_staff_list.month=map_product_cadre.month and uploaded_staff_list.year=map_product_cadre.year) where uploaded_staff_list.location='$location' and  uploaded_staff_list.status is null and uploaded_staff_list.month='$d_month' and uploaded_staff_list.year='$d_year'  and uploaded_staff_list.id='$doc_data' and uploaded_staff_list.type='$type_ch' and uploaded_staff_list.final_data in('processed')  group by map_product_cadre.flavour ");


	/*SELECT map_product_cadre.flavour,sum(map_product_cadre.qty) as Quantity from [distribution].[dbo].[uploaded_staff_list]
  JOIN [dbo].[map_product_cadre] ON (uploaded_staff_list.cadre=map_product_cadre.cadre and uploaded_staff_list.location=map_product_cadre.location
   and uploaded_staff_list.type=map_product_cadre.type and uploaded_staff_list.month=map_product_cadre.month) where uploaded_staff_list.location='$location' and uploaded_staff_list.month='$d_month' and uploaded_staff_list.year='$d_year'  and uploaded_staff_list.id='$doc_data' and uploaded_staff_list.type='$type_ch'  and uploaded_staff_list.status='$stat' or  uploaded_staff_list.status is null and uploaded_staff_list.final_data in('processed')  group by map_product_cadre.flavour */






//and uploaded_staff_list.status ='$stat' and uploaded_staff_list.final_data='$sta' 
	while($fcsku=sqlsrv_fetch_array($return_datasku,SQLSRV_FETCH_ASSOC)){
	$ghdsku[]=$fcsku;

	}
echo json_encode($ghdsku);

}
elseif(isset($_POST['retunr_approval_data'])){
	$d_month=date('m');
	$d_year=date('Y');
	$location=$_SESSION['location'];
	$user=$_SESSION['user'];
	$doc_data=$_POST['doc_data'];
	$re_type=$_POST['type'];
	$return="Closed";
	//$type=$_POST['type'];
	//$re_type='Monthly';


	//$getDoc=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where id='$doc_data'");
	//$getDoc_d=sqlsrv_fetch_array($getDoc,SQLSRV_FETCH_ASSOC);
	//$re_type=$getDoc_d['type'];
	//and type='$re_type' 
	//and month='$d_month' and year='$d_year' [distribution].[dbo].[return_track_table]

	$data_return=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[return_track_table] where location='$location' and doc_id='$doc_data' and status ='$return' and type='$re_type' and final_data='End'");
	$data_return_count=sqlsrv_has_rows($data_return);
if($data_return_count>0){
		echo json_encode("You have previously returned and approved a return for this transaction");
}else{


$check_approval=sqlsrv_query($db_conn,"SELECT count(*) as number_of_approval from [distribution].[dbo].[approval_table] where document_id='$doc_data' ");
$get_check_approval=sqlsrv_fetch_array($check_approval,SQLSRV_FETCH_ASSOC);
if($get_check_approval['number_of_approval']=='3'){



$user_d=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[distribution_login] where staffid='$user'");
$user_d_name=sqlsrv_fetch_array($user_d,SQLSRV_FETCH_ASSOC);
$user_loc=$user_d_name['location'];
$sender_email=$user_d_name['username'];


$user_d_app=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[login] where location='$user_loc' and Dept='WSHE'");
$user_d_app_name=sqlsrv_fetch_array($user_d_app,SQLSRV_FETCH_ASSOC);
$reciver_mail=$user_d_app_name['email'];
$reciver_name=$user_d_app_name['Fullname'];
$id_convert=$sender_email."-".rand(10,999999999)."-".$user_loc."-".$reciver_mail."-".$d_month."-".$d_year."-".rand(1,19898989898);
$id_convert_new=$id_convert."-||||-".$doc_data;
$id_return_track=base64_encode($id_convert_new);


//	$hr_id=$hr."-lad-".$doc_id_data;

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



     // Server settings
                                      // TCP port to connect to

    // Recipients
    $mail->setFrom('it.notifications@dufil.com', 'DUFIL FORMS');
    $mail->addAddress('tunde.afolabi@dufil.com', "Test Data"); 
    //$mail->addAddress($whse, "Test Data");      // Add a recipient
    $mail->addReplyTo('it.notifications@dufil.com', 'DUFIL FORMS');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    
    
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'NOODLES DISTRIBUTION RETURN FROM '.$user_d_name['Name'];
    $mail->Body    = nl2br("Dear ".$reciver_name.", \n\n Noodles return request have been triggered by ".$user_d_name['Name']." for you to approve the end of Noodles ditribution for the month and also serve as a request to return the remaining quantity not collected. Click <a href='https://forms.dufil.com/distribution/documents/returnapproval.php?lambda=".$id_return_track."'>Click Here to Approve</a> to view and grant the approval");
    $mail->AltBody = " This mail is to be send to a recepient in Dufil Prima Foods Ltd";
	//$mail->send();
    if(!$mail->send()){
    	echo json_encode("Error sending the mail, contact your system Administrator". $mail->ErrorInfo);
    }else{
    	
    	$dg=date("Y-m-d-H:i:s");
    
    	$insert_data_to_return="INSERT INTO [distribution].[dbo].[return_track_table] (sender_mail,receiver_mail,location,month,year,id_track_return,type,date_dt,doc_id) VALUES(?,?,?,?,?,?,?,?,?)";
    	$insert_data_to_return_pre=sqlsrv_prepare($db_conn,$insert_data_to_return,array($sender_email,$reciver_mail,$user_loc,$d_month,$d_year,$id_return_track,$re_type,$dg,$doc_data));
    	$insert_data_to_return_exe=sqlsrv_execute($insert_data_to_return_pre);

    	$update_staff_list=sqlsrv_query($db_conn,"UPDATE uploaded_staff_list set status='$return' where id='$doc_data' and type='$re_type' and status is null");
    	sqlsrv_execute($update_staff_list);
    	$update_return=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[return_track_table] set status='$return' where doc_id='$doc_data' and status is null");
    	sqlsrv_execute($update_return);

    	echo json_encode('Mail sent');
   }
 


}else{
	echo json_encode("This transaction is pending approval before you start issuance");

}



		
	}

	

}

elseif(isset($_POST['Approve_return_data'])){
	$location=$_POST['location'];
	$type=$_POST['type'];
	$d_month=date('m');
	$d_year=date('Y');
	$close="Closed";
	$ret="End";
	$update_id_id=$_POST['doc_data'];
	//$update=sqlsrv_query($db_conn,"SELECT id FROM [distribution].[dbo].[upload_file_data] where id='$doc_data' and  data_mon='$d_month' and data_year='$d_year'  and location='$location'");
	//$update_id=sqlsrv_fetch_array($update,SQLSRV_FETCH_ASSOC);
	//$update_id_id=$update_id['id'];
	//update to close all return noodles
 //data_mon='$d_month' and data_year='$d_year'
$check_previous_close=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[upload_file_data] where id='$update_id_id'and final_data='$ret' and type='$type'");
$check_previous_close_d=sqlsrv_has_rows($check_previous_close);
if($check_previous_close_d>0){
echo json_encode("You have previously closed this transaction");
}else{

	$return_noodles="UPDATE [distribution].[dbo].[upload_file_data] set status=? ,final_data=?  where id=?";
	$return_noodles_prepare=sqlsrv_prepare($db_conn,$return_noodles,array($close,$ret,$update_id_id));

	$return_noodles_list="UPDATE [distribution].[dbo].[uploaded_staff_list] set final_data=? where id=? ";
	$return_noodles_prepare_list=sqlsrv_prepare($db_conn,$return_noodles_list,array($ret,$update_id_id));

	$return_="UPDATE [distribution].[dbo].[return_track_table] set status=?,final_data=?  where doc_id=?";
	$return_n=sqlsrv_prepare($db_conn,$return_,array($close,$ret,$update_id_id));

	$return_aggre="UPDATE [distribution].[dbo].[aggregate_data_upload] set final_data=?  where doc_id=?";
	$return_n_aggre=sqlsrv_prepare($db_conn,$return_aggre,array($ret,$update_id_id));


	$return_flavour="UPDATE [distribution].[dbo].[aggregate_flavour_data] set final_data=?  where doc_id=?";
	$return_n_flavour=sqlsrv_prepare($db_conn,$return_flavour,array($ret,$update_id_id));

	$return_approval="UPDATE [distribution].[dbo].[approval_table] set final_data=?  where document_id=?";
	$return_n_approval=sqlsrv_prepare($db_conn,$return_approval,array($ret,$update_id_id));

	$return_product_map="UPDATE [distribution].[dbo].[map_product_cadre] set final_data=?  where final_data in (select final_data from [distribution].[dbo].[map_product_cadre] JOIN [distribution].[dbo].[cadre_table] on [map_product_cadre].map_id=[cadre_table].q_id)";
	$return_n_product_map=sqlsrv_prepare($db_conn,$return_product_map,array($ret,$update_id_id));

//and sqlsrv_execute($return_n_product_map)==TRUE
	//and sqlsrv_execute($return_n_approval)==TRUE
	//and sqlsrv_execute($return_n_flavour)==TRUE
	//and sqlsrv_execute($return_n_aggre)==TRUE 


	if(sqlsrv_execute($return_noodles_prepare_list)==TRUE and sqlsrv_execute($return_noodles_prepare)==TRUE  and sqlsrv_execute($return_n)==TRUE and sqlsrv_execute($return_n_aggre)==TRUE  and sqlsrv_execute($return_n_flavour)==TRUE and sqlsrv_execute($return_n_approval)==TRUE and sqlsrv_execute($return_n_product_map)==TRUE){
		echo json_encode($type." Noodles distribution for the month of ".$d_month." have been successfully closed and any unissued quantity should be retured");

	}else{
		echo json_encode("Error while trying to close,".$type." noodles distribution for the month".$d_month.", Incase of any difficulties, contact your Administrator");
	}
}

}
elseif(isset($_POST['reject_return_data'])){
	echo json_encode("Under construction");
}


elseif(isset($_POST['cadre_data_pull'])){
			$mon=date('m');
			$yr=date('Y');
			$location=$_POST['location'];
			$cadre_data_pull_out=[];
			$fetch_carde=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table] where location='$location' and month='$mon' and year='$yr'");
			while ($fc=sqlsrv_fetch_array($fetch_carde,SQLSRV_FETCH_ASSOC)){
				$cadre_data_pull_out[]=$fc;
			/*	echo '

							<tr>
								<td>'.$fc['month'].'</td>
								<td>'.$fc['cadre'].'</td>
								<td>'.$fc['location'].'</td>
								<td>'.$fc['Qty'].'</td>
								<td>'.$fc['type'].'</td>
								
								<td><button class="individual_item" value='.$fc['q_id'].' style="background-color:green; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"><i class="icofont-eye-open "  style=""></i></button></td>
								<td><button class="delete_individual_item_sku" value='.$fc['q_id'].' style="background-color:red; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"><i class="icofont-ui-delete "  style=""></i></button></td>


								




							</tr>

				';*/
			}

echo json_encode($cadre_data_pull_out);
}elseif(isset($_POST['staff_available'])){

	$location=$_POST['location'];
	$doc_data=$_POST['doc_data'];
	$type_noo=$_POST['type_noo'];
	$year=date('Y');
	$mon=date('m');

	$availa=sqlsrv_query($db_conn,"SELECT flavour,avail FROM [distribution].[dbo].[aggregate_flavour_data] where location='$location' and year_data='$year' and month_data='$mon' and type='$type_noo' and doc_id='$doc_data' group by flavour,avail");
	$data_avail=[];
	while($dat_avail=sqlsrv_fetch_array($availa,SQLSRV_FETCH_ASSOC)){
		$data_avail[]=$dat_avail;
	}
	echo json_encode($data_avail);

}elseif(isset($_POST['get_data_to'])){
	$item_availability_id=$_POST['item_availability_id'];
	$doc_data=$_POST['doc_data'];
	$location=$_POST['location'];
	$type_noo=$_POST['type_noo'];
	$mon=date('m');
	$year=date('Y');
	$final_data="processed";

	$chech_avail=sqlsrv_query($db_conn,"SELECT flavour FROM [distribution].[dbo].[aggregate_flavour_data] where flavour='$item_availability_id' and final_data='processed'  and doc_id ='$doc_data' and avail='Available' group by flavour");

	$chech_avail_r=sqlsrv_has_rows($chech_avail);

	if($chech_avail_r>0){
		
		$update_data=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[map_product_cadre] SET availability = null where flavour='$item_availability_id' and month='$mon' and year='$year' and final_data='$final_data' and type='$type_noo' and location='$location'");
		$chech_avail=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[aggregate_flavour_data] set avail = null where flavour='$item_availability_id' and final_data='processed'  and doc_id ='$doc_data'");
		echo json_encode("Product has been sent to unavailable");


	}else{

			$update_data=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[map_product_cadre] SET availability = 'Available' where flavour='$item_availability_id' and month='$mon' and year='$year' and final_data='$final_data' and type='$type_noo' and location='$location'");
		$chech_avail=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[aggregate_flavour_data] set avail = 'Available' where flavour='$item_availability_id' and final_data='processed'  and doc_id ='$doc_data'");

		#$update_data=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[map_product_cadre] SET availability='Available' where item_id='$item_availability_id'");
		echo json_encode("Product has been sent to available");
	}

}

elseif(isset($_POST['check_collected_data'])){

	$location=$_POST['location'];
	$doc_data =$_POST['doc_data'];
	$type_noo=$_POST['type_noo'];
	$staff_to_check=$_POST['staff_to_check'];

	$ftech=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[collect_track_table] WHERE staffid='$staff_to_check' and id='$doc_data' and location='$location' and type='$type_noo'");
	$gfd=[];
	while($ftech_r=sqlsrv_fetch_array($ftech,SQLSRV_FETCH_ASSOC)){
		$gfd[]=$ftech_r;

	}

echo json_encode($gfd);
}
?>








