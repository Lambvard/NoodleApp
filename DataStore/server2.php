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

if(isset($_POST['fetch_flavour_data_app'])){
	$location=$_SESSION['location'];

	$aggre_data=[];
	$FlavourCountAggreg=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[aggregate_data_upload]");
	$n=1;
		while($FlavourCount_d=sqlsrv_fetch_array($FlavourCountAggreg,SQLSRV_FETCH_ASSOC)){



			$aggre_data[].='
							
							
							<tr>
								<td>'.$n.'</td>
								<td>'.$FlavourCount_d['aggre_cadre'].'</td>
								<td>'.$FlavourCount_d['aggre_qty'].'</td>
								<td>'.$FlavourCount_d['aggre_month'].'</td>
								<td>'.$FlavourCount_d['aggre_year'].'</td>


							</tr>

				';
				$n=$n+1;
		
		}
		echo json_encode($aggre_data);

}
elseif(isset($_POST['fetchstafflist'])){
	$monD=$_POST['monD'];
	$f=explode('-', $monD);
	$mon=$f[1];
	$year_pick=$f[0];
	$location=$_SESSION['location'];
	$type=$_POST['type'];

	$staff_list=array();
	$staff_list_data=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[uploaded_staff_list] where month='$mon' and year='$year_pick' and location='$location' and type='$type'");
	//$mn=1;
		while($staff_listd=sqlsrv_fetch_array($staff_list_data,SQLSRV_FETCH_ASSOC)){

			$staff_list[]= $staff_listd;


				}
			
		echo json_encode($staff_list);

/*

'<tr>
								<td>'.$mn.'</td>
								<td>'.$staff_listd['staffid'].'</td>
								<td>'.$staff_listd['cadre'].'</td>
								<td>'.$staff_listd['qty'].'</td>
								<td>'.$staff_listd['month'].'</td>
								<td>'.$staff_listd['status'].'</td>
								<td>'.$staff_listd['all_status'].'</td>
								<td>'.$staff_listd['issue_date'].'</td>
								<td>'.$staff_listd['out_date'].'</td>


								


							</tr>';
							$mn=$mn+1;


*/

}
elseif(isset($_POST['staffload'])){
$location=$_POST['location'];

$staff_load=array();
	$staff_load_fetch=sqlsrv_query($db_conn,"SELECT staffid,cadre,qty,month from [distribution].[dbo].[uploaded_staff_list] where location='$location'");
	//$mn=1;
		while($staff_lo=sqlsrv_fetch_array($staff_load_fetch,SQLSRV_FETCH_ASSOC)){

			$staff_load[]=$staff_lo;

		}
			
		echo json_encode($staff_load);






}
elseif(isset($_POST['reject_staff_emp'])){

		$rejection_reason=$_POST['reason'];
		$rejection_id=$_POST['reject_id'];
		$document_id=$_POST['document_id'];
		$d="Reject";
		$location=$_POST['location'];

		$date=date('Y-m-d');
		$month=date("m");
		$year=date("Y");

		$get_id=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[login] where email='$rejection_id'");
		$get_id_d=sqlsrv_fetch_array($get_id,SQLSRV_FETCH_ASSOC);
		$get_app_loc=$get_id_d['location'];
		$get_app_Dept=$get_id_d['Dept'];
		

$get_id_pre=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[approval_table] where HR_id='$rejection_id' and document_id='$document_id' and HR_app in ('Approve','Reject') and location='$location'");
	$get_id_d_pre=sqlsrv_has_rows($get_id_pre);

	if($get_id_d_pre>0){

		echo json_encode("You have previously rejected this transaction");
	}else{



//HR_app,HR_app_date,reason  ,$d,$date,$rejection_reason
		$insert_into_app="INSERT INTO [distribution].[dbo].[approval_table] (document_id,HR_id,HR_app,HR_app_date,reason,month,year,location) values(?,?,?,?,?,?,?,?)";
		$insert_into_app_prepare=sqlsrv_prepare($db_conn,$insert_into_app,array($document_id,$rejection_id,$d,$date,$rejection_reason,$month,$year,$location));

		sqlsrv_execute($insert_into_app_prepare);


		echo json_encode("Rejected successfully");

}

}

elseif(isset($_POST['Approved_staff_emp'])){

		$approved_id=base64_decode($_POST['approved_id']);
		$document_id=base64_decode($_POST['document_id']);
		$d="Approve";

		$date=date('Y-m-d');
		$month=date("m");
		$year=date("Y");

		$get_id=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[login] where email='$approved_id'");
		$get_id_d=sqlsrv_fetch_array($get_id,SQLSRV_FETCH_ASSOC);
		$get_app_loc=$get_id_d['location'];
		$get_app_Dept=$get_id_d['Dept'];
		
//HR_app,HR_app_date,reason  ,$d,$date,$rejection_reason


	$get_id_pre=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[approval_table] where HR_id='$approved_id' and document_id='$document_id' and HR_app in ('Approve','Reject') and location='$location'");
	$get_id_d_pre=sqlsrv_has_rows($get_id_pre);

	if($get_id_d_pre>0){

		echo json_encode("You have previously approved this transaction");
	}
	else{
		$insert_into_app="INSERT INTO [distribution].[dbo].[approval_table] (document_id,HR_id,HR_app,HR_app_date,month,year,location) values(?,?,?,?,?,?,?)";
		$insert_into_app_prepare=sqlsrv_prepare($db_conn,$insert_into_app,array($document_id,$approved_id,$d,$date,$month,$year,$location));

		sqlsrv_execute($insert_into_app_prepare);

		echo json_encode("Approved successfully");

}

}

elseif(isset($_POST['aggregate_data'])){
	$idd=$_POST['id_rg'];
	//$location=$_SESSION['location'];
	$mn_th=date('m');
	$yr_th=date('Y');
// where doc_id='$idd' where doc_id='$idd'
	//aggre_location='$location' and
	$staff_aggre_d=array();
	$staff_aggre_data=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[aggregate_data_upload] where doc_id='$idd' and aggre_month='$mn_th' and aggre_year='$yr_th'");
	while($staff_listd=sqlsrv_fetch_array($staff_aggre_data,SQLSRV_FETCH_ASSOC)){

			$staff_aggre_d[]=$staff_listd;

		}
			
		echo json_encode($staff_aggre_d);



}

elseif(isset($_POST['centraladminlogin'])){

	$email_cntral=$_POST['email'];
	$password_cntrl=$_POST['password'];
	
	$check_connectcentral=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[distribution_login] where username='$email_cntral' and password='$password_cntrl'");
		$countcentral=sqlsrv_has_rows($check_connectcentral);
		if($countcentral>0){
				$check_connect_fed=sqlsrv_fetch_array($check_connectcentral,SQLSRV_FETCH_ASSOC);
				//session_start();
				//$get_document_doc=sqlsrv_query($db_conn,"SELECT * FROM ");

				$_SESSION['user']=$check_connect_fed['staffid'];
				$_SESSION['location']=$check_connect_fed['location'];

				echo json_encode("Connected successfully");
				
		}else{
			echo json_encode("You are typing an invalid credentials");
		}

	

}
elseif(isset($_POST['pullallreport'])){
	$mon_central=date('m');
	$yr_central=date('Y');
	$location=$_SESSION['location'];

		$date_tak=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where month='$mon_central' and year='$yr_central' and location='$location' and final_data in ('Processed','Start')");

		$dh=array();

		while ($dat=sqlsrv_fetch_array($date_tak,SQLSRV_FETCH_ASSOC)) {
			$dh[]=$dat;
					
					
		}


echo json_encode($dh);
}

elseif(isset($_POST['employeeRecord'])){
		$id=$_POST['id'];
		//$location=$_SESSION['location'];
		$mon=date('m');
		$year_d=date('Y');

		$date_list=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where month='$mon' and year='$year_d' and id='$id'");
		$dh_list=array();

		while ($dat=sqlsrv_fetch_array($date_list,SQLSRV_FETCH_ASSOC)) {
			$dh_list[]=$dat;

					
					
		}


echo json_encode($dh_list);
}

elseif(isset($_POST['approveNoodles'])){
	$app_mail=trim($_POST['app_mail']);
	$app_id=trim($_POST['app_id']);
	$type_data=trim($_POST['type_data']);
	$loc=trim($_POST['chj_loc']);
	$appr="Approved";
	$date_hg=date("Y-m-d-h:i:s");
	$date_mon=date('m');
	$date_year=date('Y');
	$f_data="processed";
	//$location=$_SESSION['location'];
	//and location='$location' and
//and close_status  not in ('Aprroved','Rejected') and close_date  not in ('Approved','Rejected') and type='$type_data' and approval_id='$app_mail'
	$approval_data_distribution=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[approval_table] where  document_id='$app_id' and approval_id='$app_mail' and close_status  not in ('Aprroved','Rejected') and close_date  not in ('Approved','Rejected') ");

	//SELECT * from [distribution].[dbo].[approval_table] where document_id='$app_id' and approval_id='$app_mail' and close_status  not in ('Aprroved','Rejected') and close_date  not in ('Approved','Rejected')
	$approval_data_distribution_d=sqlsrv_has_rows($approval_data_distribution);

	if($approval_data_distribution_d>'0'){
		echo json_encode("You have previously approved or rejected this transaction");
	}else{	
	//$update_sqlDD=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[approval_table] set close_status='$appr', close_date='$date_hg' where document_id='$app_id' and approval_id='$app_mail' and month='$date_mon' and year='$date_year' and location='$location'");


//$appr=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[aggregate_data_upload] where  id='$app_id' ");
//$appr_d=sqlsrv_fetch_array($appr,SQLSRV_FETCH_ASSOC);
//$location=$appr['location'];


	$update_sqlDD=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[approval_table] set close_status='$appr', final_data='$f_data', close_date='$date_hg' where document_id='$app_id' and approval_id='$app_mail' and type='$type_data'");

	$update_map_product_cadre=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[map_product_cadre] set final_data='$f_data' where  year='$date_year' and month='$date_mon' and location='$loc' and type='$type_data'");
	$update_list=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[uploaded_staff_list] set final_data='$f_data' where type='$type_data' and year='$date_year' and month='$date_mon' and location='$loc' and id='$app_id'");
	$update_flavour=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[aggregate_flavour_data] set final_data='$f_data' where type='$type_data' and year_data='$date_year' and month_data='$date_mon' and location='$loc' and doc_id='$app_id'");

$update_aggre=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[aggregate_data_upload] set final_data='$f_data' where type='$type_data' and aggre_year='$date_year' and aggre_month='$date_mon' and aggre_location='$loc' and doc_id='$app_id'");

$update_upl=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[upload_file_data] set final_data='$f_data' where type='$type_data' and data_year='$date_year' and data_mon='$date_mon' and location='$loc' and id='$app_id'");


	
	if($update_sqlDD==TRUE and $update_map_product_cadre==TRUE and $update_list==TRUE and $update_flavour==TRUE and $update_upl==TRUE and $update_aggre==TRUE ){
			echo json_encode("You have successfully approved the distribution of ".date('m-Y'));
			
		}else{
			echo json_encode("I encounteres error while saving your approval, please contact the administrator");
		}
	}
}

elseif(isset($_POST['total_visitor'])){
$current_use_month=date('m');
$current_use_year=date('Y');
$loEmp=$_POST['loc_idEmp'];
$location=$_SESSION['location'];

$empCount=[];
//where month='$current_use_month' and year='$current_use_year' and location='$loEmp'
$totalemployee=sqlsrv_query($db_conn,"SELECT sum(qty) as countemployee FROM [distribution].[dbo].[uploaded_staff_list] where month='$current_use_month' and year='$current_use_year' and location='$location' and id='$loEmp'");
	$totalemployeecount=sqlsrv_fetch_array($totalemployee,SQLSRV_FETCH_ASSOC);


//where month='$current_use_month' and year='$current_use_year' and location='$loEmp'
$issuedemployee=sqlsrv_query($db_conn,"SELECT count(qty) as issuecount FROM [distribution].[dbo].[uploaded_staff_list] where status='issued' month='$current_use_month' and year='$current_use_year' and location='$location'  and id='$loEmp'");
	$tissuedemployeecount=sqlsrv_fetch_array($issuedemployee,SQLSRV_FETCH_ASSOC);


//where month='$current_use_month' and year='$current_use_year' and location='$loEmp'
$checkoutemployee=sqlsrv_query($db_conn,"SELECT count(*) as checkoutcount FROM [distribution].[dbo].[uploaded_staff_list] where status='checkout' month='$current_use_month' and year='$current_use_year' and location='$location'  and id='$loEmp'");
	$checkoutemployeecount=sqlsrv_fetch_array($checkoutemployee,SQLSRV_FETCH_ASSOC);

	$empCount['allEmployee']=$totalemployeecount['countemployee'];
	$empCount['IssuedEmployee']=$tissuedemployeecount['issuecount'];
	$empCount['checkoutEmployee']=$checkoutemployeecount['checkoutcount'];
	

	echo json_encode($empCount);




}
elseif(isset($_POST['getStaffInfo_sec'])){
	$staf_employ=$_POST['staf_employ'];
	$location=$_SESSION['location'];

$view_data=array();
	$get_staff_details_noodles=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where staffid='$staf_employ' and location='$location'");
	$get_staff_details_noodles_d=sqlsrv_fetch_array($get_staff_details_noodles,SQLSRV_FETCH_ASSOC);

	$get_name_department=sqlsrv_query($db_conn,"SELECT * FROM iou.dbo.staffdetails where staffid='$staf_employ' and stafflocation='$location'");
	$get_name_department_d=sqlsrv_fetch_array($get_name_department,SQLSRV_FETCH_ASSOC);



	$view_data['location_issued']=$get_staff_details_noodles_d['location'];
	$view_data['date_issued']=$get_staff_details_noodles_d['issue_date'];
	$view_data['qty_issued']=$get_staff_details_noodles_d['qty'];
	$view_data['id_issued']=$staf_employ;
	$view_data['name_issued']=$get_name_department_d['surname']." ".$get_name_department_d['firstname']." ".$get_name_department_d['othernames'];
	$view_data['dept_issued']=$get_name_department_d['Dept'];
	$view_data['buttonData']=$get_staff_details_noodles_d['status'];
	//$view_data['location_issued']=$get_staff_details_noodles_d['location'];
	//$view_data['location_issued']=$get_staff_details_noodles_d['location'];






	echo json_encode($view_data);
}
elseif(isset($_POST['updateData_use'])){
	$updated_dataid=$_POST['id_id'];
	$chety="checkout";
	$mon=date('m');
	$yr=date('Y');
	$checkout_date=date("Y-m-d h:i:s");
	$location=$_SESSION['location'];


	$check_previous=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where staffid='$updated_dataid' and status='$chety' and month='$mon' and year='$yr' and location='$location'");
	$check_previous_count=sqlsrv_has_rows($check_previous);

	if($check_previous_count>0){
		echo json_encode("You have previously check out this transaction for Employee ".$updated_dataid." for this month");
	}else{
	
	$updated_data_r=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[uploaded_staff_list] set status='$chety',out_date='$checkout_date' where staffid='$updated_dataid' and month='$mon' and year='$yr' and location='$location'");

	if($updated_data_r==TRUE){
			echo json_encode($mon." monthly noodles successfully checked out for ".$updated_dataid);	
	}


}

}

elseif(isset($_POST['secdata_record'])){

			$date_g=date("Y-m-d");
			$use_mon=date('m');
			$use_year=date('Y');
			$location=$_SESSION['location'];
			//dater ='$date_g'and
			$d_f=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where  location='$location' and status in('Issued','checkout') and month='$use_mon' and year='$use_year' and final_data in ('Start','Processed')");

			$sec_report_data=array();
			
			//$c=1;
			while($d=sqlsrv_fetch_array($d_f,SQLSRV_FETCH_ASSOC)){
				//echo '<tr style="background-color:white; color:black; font-size:10px; white-space:wrap;"><td>'.$c.'</td><td>'.$d['staffid'].'</td><td>'.$d['cadre'].'</td><td>'.$d['qty'].'</td><td>'.$d['location'].'</td><td>'.$d['month'].'</td><td>'.$d['day'].'</td><td>'.$d['date'].'</td><td>'.$d['year'].'</td><td>'.$d['status'].'</td><td>'.$d['all_status'].'</td><td>'.$d['issue_date'].'</td><td>'.$d['out_date'].'</td><td><button class="dataneed" value='.$d['staffid'].' style="background-color:brown; color:white;"><i class="icofont-eye-open "  style=""></i></button></td></tr>';

				//$c=$c+1;

				$sec_report_data[]=$d;
			}
echo json_encode($sec_report_data);


}

elseif(isset($_POST['rejectNoodles'])){
	$app_mail=$_POST['app_mail'];
	$app_id=$_POST['app_id'];
	$rej_reason=$_POST['reasonforrejection'];
	$appr="Rejected";
	$date_hg=date("Y-m-d-h:i:s");
	//$location=$_SESSION['location'];

	$approval_data_distribution=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[approval_table] where document_id='$app_id' and approval_id='$app_mail' and close_status is not null and close_date is not null");
	$approval_data_distribution_d=sqlsrv_has_rows($approval_data_distribution);

	if($approval_data_distribution_d>0){
		echo json_encode("You have previously approved or rejected this transaction");
	}else{

	
	$update_sqlDDrej=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[approval_table] set close_status='$appr', close_date='$date_hg',reason='$rej_reason' where document_id='$app_id' and approval_id='$app_mail' ");
	#$update_sql_data=sqlsrv_prepare($db_conn,$update_sqlDD,array(,,,));
	//set close_status='$appr', close_date='$date_hg' WHERE document_id='$app_id' and approval_id='$app_mail'
	#$update_sql_data_r=sqlsrv_execute($update_sql_data);

		if($update_sqlDDrej==FALSE){
			echo json_encode("I encounteres error while saving your approval, please contact the administrator");
		}else{
		echo json_encode("You have successfully rejected the distribution of ".date('m-Y'));
		}
	}
}

elseif(isset($_POST['count_issued'])){
$current_use_month=date('m');
$current_use_year=date('Y');
$loEmp=$_POST['loc_idEmp'];
$location=$_SESSION['location'];
$id=$_POST['id'];
$type_noodles=$_POST['type_noodles'];

$empCount=[];
//count all employee uploaded
//where month='$current_use_month' and year='$current_use_year' and location='$loEmp'
$totalemployee=sqlsrv_query($db_conn,"SELECT count(*) as countemployee FROM [distribution].[dbo].[uploaded_staff_list] where month='$current_use_month' and year='$current_use_year' and location='$location' and id='$id' and type='$type_noodles' and final_data in ('Start','Processed')");
	$totalemployeecount=sqlsrv_fetch_array($totalemployee,SQLSRV_FETCH_ASSOC);
	//$_SESSION['id_doc']=$totalemployeecount['id'];

//sum of all employee qty uploaded
	$totalemployee_all=sqlsrv_query($db_conn,"SELECT sum(cast(uploaded_staff_list.qty as int)) as qtyall FROM [distribution].[dbo].[uploaded_staff_list] where month='$current_use_month' and year='$current_use_year' and location='$loEmp' and id='$id' and type='$type_noodles' and final_data in ('Start','Processed')");
	$totalemployeecount_all=sqlsrv_fetch_array($totalemployee_all,SQLSRV_FETCH_ASSOC);


//count all employee issued and checkedout quantity
//where month='$current_use_month' and year='$current_use_year' and location='$loEmp'
$issuedemployee=sqlsrv_query($db_conn,"SELECT count(*) as issuecount FROM [distribution].[dbo].[uploaded_staff_list] where status in('issued','checkout') and month='$current_use_month' and year='$current_use_year' and location='$location' and id='$id' and type='$type_noodles' and final_data in ('Start','Processed')");
	$tissuedemployeecount=sqlsrv_fetch_array($issuedemployee,SQLSRV_FETCH_ASSOC);

//count all employee pending collection quantity
//where month='$current_use_month' and year='$current_use_year' and location='$loEmp'
$checkoutemployee=sqlsrv_query($db_conn,"SELECT SUM (CAST (uploaded_staff_list.qty as int)) as checkoutcount FROM [distribution].[dbo].[uploaded_staff_list] where month='$current_use_month' and year='$current_use_year' and location='$location' and id='$id' and type='$type_noodles' and status is null and final_data in ('Start','Processed')");
	$checkoutemployeecount=sqlsrv_fetch_array($checkoutemployee,SQLSRV_FETCH_ASSOC);
//sum all employee collected quantity
	$get_all_collected_issued=sqlsrv_query($db_conn,"SELECT SUM (cast( uploaded_staff_list.qty as int))  as totaliss from [distribution].[dbo].[uploaded_staff_list] where Status in('Issued','checkout') and month='$current_use_month' and year='$current_use_year' and location='$location' and id='$id' and type='$type_noodles' and final_data in ('Start','Processed')");

$set_session=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where month='$current_use_month' and year='$current_use_year' and location='$location' and id='$id' and type='$type_noodles' and final_data in ('Start','Processed')");
$set_session_get=sqlsrv_fetch_array($set_session,SQLSRV_FETCH_ASSOC);
$_SESSION['doc_id']=$id;
//$set_session_get['id'];


	$checkoutemployeecount_issued=sqlsrv_fetch_array($get_all_collected_issued,SQLSRV_FETCH_ASSOC);


	$empCount['allEmployee']=$totalemployeecount['countemployee'];
	$empCount['IssuedEmployee']=$tissuedemployeecount['issuecount'];
	$empCount['checkoutEmployee']=$checkoutemployeecount['checkoutcount'];
	$empCount['countemployeeall']=$totalemployeecount_all['qtyall'];
	$empCount['totaliss']=$checkoutemployeecount_issued['totaliss'];
	

	echo json_encode($empCount);




}
elseif(isset($_POST['aggregate_data_skuDetails'])){
	$idd=$_POST['id_rg'];
	//$location=$_SESSION['location'];
	$mn_th=date('m');
	$yr_th=date('Y');
// where doc_id='$idd' where doc_id='$idd'
	//and location='$location'
	$staff_sd=array();
	$staff_sku_aggre=sqlsrv_query($db_conn,"SELECT cadre,flavour,total_flavour_qty from [distribution].[dbo].[aggregate_flavour_data] where month_data='$mn_th' and doc_id='$idd' and year_data='$yr_th' and final_data in ('Start','Processed')  group by cadre,flavour,total_flavour_qty");
	while($staff_ld=sqlsrv_fetch_array($staff_sku_aggre,SQLSRV_FETCH_ASSOC)){

			$staff_sd[]=$staff_ld;

		}
			
		echo json_encode($staff_sd);



}

?>

