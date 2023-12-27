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



if(isset($_POST['uploadData'])){
	$month=$_POST['mon'];
	$type=$_POST['type'];
	$sp=explode('-', $month);
	$data_mon=$sp[1];
	$year=$sp[0];
	$location=$_SESSION['location'];
	$final_data="Start";


	$type_of_transaction=$type;

	$filetype = ['xls', 'xlsx'];
	$file_location='../uploads/';
	$file=basename($_FILES["fileuploaddata"]["name"]);
	$file_path=$file_location.$file;
	$check_ext=strtolower(pathinfo($file_path,PATHINFO_EXTENSION));

	if(!in_array($check_ext, $filetype)){
		die ("File type is not excel");
	//	echo $file;

	}else{
		
		
		if($type_of_transaction=='End of Year Product'){
		$check_year=sqlsrv_query($db_conn,"SELECT count(*) as year_count from distribution.dbo.upload_file_data where data_year='$year' and location='$location' and type='$type_of_transaction'");
		$check_year_before=sqlsrv_fetch_array($check_year,SQLSRV_FETCH_ASSOC);

		if($check_year_before['year_count']>=1){
			echo $type." data has been previously uploaded for the year of ".$year." kindly get approval to upload another instance";

		}else{
			//echo $type." data has been previously uploaded for the year of ".$year." kindly get approval to upload another instance";


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

//check to know if there is a previous account for cadre;

	$select_if_cadre_available=sqlsrv_query($db_conn,"SELECT count(*) as cadreData_item from [distribution].[dbo].[cadre_table] where year='$data_year' and location='$location' and type='$type_of_transaction'");
	$select_if_cadre_available_count=sqlsrv_fetch_array($select_if_cadre_available,SQLSRV_FETCH_ASSOC);


	$select_if_flavour_available=sqlsrv_query($db_conn,"SELECT count(*) as flavourData_item from [distribution].[dbo].[map_product_cadre] where location='$location' and type='$type_of_transaction'");
	$select_if_flavour_available_count=sqlsrv_fetch_array($select_if_flavour_available,SQLSRV_FETCH_ASSOC);




	if($select_if_cadre_available_count['cadreData_item']>='4' and $select_if_flavour_available_count['flavourData_item']>='4'){	


	$uploadData_in="INSERT INTO distribution.dbo.[upload_file_data] (Status,data_time,data_date,data_mon,data_year,data_loc,id,file_name_in,location,type,final_data) values(?,?,?,?,?,?,?,?,?,?,?)";
	$prepare_data=sqlsrv_prepare($db_conn,$uploadData_in,array($status,$data_time,$data_date,$data_mon,$data_year,$data_loc,$id,$file,$location,$type_of_transaction,$final_data));
				if(sqlsrv_execute($prepare_data)==TRUE){



					$file_to_work_on=$_FILES["fileuploaddata"]["tmp_name"];
					$default_loc="../uploads/".$file;


					//$current_sheet=$sheet->load($default_loc);


					$spreadsheet = IOFactory::load($default_loc);
					$sheetData = $spreadsheet->getActiveSheet()->toArray();
					//null, true, true, true

						unset($sheetData[0]);
						foreach ($sheetData as $row) {
							$staffid=trim($row[1]);
							$fullname=trim($row[2]);
							$dept=trim($row[3]);
							$cadre=trim($row[4]);
							$type=trim($type_of_transaction);
							$final_data=$final_data;
   						$saveFile="INSERT INTO distribution.dbo.[uploaded_staff_list](staffid,employee_name,dept,cadre,date,month,year,id,location,type,final_data) values(?,?,?,?,?,?,?,?,?,?,?)";
						$saveFile_pre=sqlsrv_prepare($db_conn,$saveFile,array($staffid,$fullname,$dept,$cadre,$data_date,$data_mon,$data_year,$id,$location,$type,$final_data));
								sqlsrv_execute($saveFile_pre);
							   						
							}
			
		



							// update table for all staff

					$update_data="UPDATE [distribution].[dbo].[uploaded_staff_list] set qty=(SELECT cadre_table.Qty from [distribution].[dbo].[cadre_table] where cadre_table.cadre=uploaded_staff_list.cadre and month='$data_mon' and year='$data_year' and location='$location' and type='$type_of_transaction') where id='$id'";
					$update_cadre_table="UPDATE [distribution].[dbo].[cadre_table]  set id=? where month=? and year=? and location=? and type=?";
					$update_data_d_cadre=sqlsrv_prepare($db_conn,$update_cadre_table,array($id,$data_mon,$data_year,$location,$type_of_transaction));
					sqlsrv_execute($update_data_d_cadre);	





					//"UPDATE [distribution].[dbo].[uploaded_staff_list] set qty=(SELECT cadre_table.Qty from [distribution].[dbo].[cadre_table] where cadre_table.cadre=uploaded_staff_list.cadre  and year='$data_year' and location='$location' and type='$type_of_transaction') where  id='$id' ";

//"UPDATE [distribution].[dbo].[uploaded_staff_list] set qty=(SELECT cadre_table.Qty from [distribution].[dbo].[cadre_table] where cadre_table.cadre=uploaded_staff_list.cadre and month='$data_mon' and year='$data_year' and location='$location' and type='$type_of_transaction' and final_data='$final_data') ";


					$update_data_d=sqlsrv_prepare($db_conn,$update_data,array($data_mon,$year));
					sqlsrv_execute($update_data_d);	

					$test_d=array();

					//SELECT uploaded_staff_list.cadre,count(*) as qty,sum (cast( uploaded_staff_list.qty as int)) as total from [distribution].[dbo].[uploaded_staff_list]	JOIN [distribution].[dbo].[cadre_table] ON (uploaded_staff_list.cadre=[cadre_table].cadre AND uploaded_staff_list.location=[cadre_table].location AND uploaded_staff_list.type=cadre_table.type) where [cadre_table].type='$type_of_transaction' and [cadre_table].location='$location' and [cadre_table].year='$data_year' and [aggregate_data_upload].doc_id='$id' and [cadre_table].type='$type_of_transaction' GROUP BY uploaded_staff_list.cadre,cadre_table.Qty



	$select_all=sqlsrv_query($db_conn,"SELECT uploaded_staff_list.cadre,count(*) as qty,sum (cast( uploaded_staff_list.qty as int)) as total
from [distribution].[dbo].[uploaded_staff_list]JOIN [distribution].[dbo].[cadre_table] ON (uploaded_staff_list.cadre=[cadre_table].cadre AND uploaded_staff_list.location=[cadre_table].location and uploaded_staff_list.id=[cadre_table].id) where [cadre_table].month='$data_mon' and [cadre_table].location='$location'
 and [cadre_table].year='$data_year' and [cadre_table].type='$type_of_transaction' GROUP BY uploaded_staff_list.cadre,cadre_table.Qty");
 					while($select_all_pre=sqlsrv_fetch_array($select_all,SQLSRV_FETCH_ASSOC)){
						$save_aggregate=  "INSERT into [distribution].[dbo].[aggregate_data_upload] (aggre_cadre,aggre_qty,aggre_total_count,aggre_month,aggre_year,aggre_location,doc_id,type,final_data) values(?,?,?,?,?,?,?,?,?)";
						$save_aggregate_pre=sqlsrv_prepare($db_conn,$save_aggregate,array($select_all_pre['cadre'],$select_all_pre['qty'],$select_all_pre['total'],$data_mon,$data_year,$location,$id,$type_of_transaction,$final_data));
						sqlsrv_execute($save_aggregate_pre);
						
					}

					// update flavour
		//$select_all_flavour=sqlsrv_query($db_conn," SELECT cadre,flavour,sum(qty) as flavour_qty, aggregate_data_upload.aggre_qty *sum(qty) as total_flavour_qty,doc_id,aggregate_data_upload.type from [distribution].[dbo].[map_product_cadre] JOIN [distribution].[dbo].[aggregate_data_upload] on (map_product_cadre.cadre=aggregate_data_upload.aggre_cadre and map_product_cadre.location=aggregate_data_upload.aggre_location and aggregate_data_upload.type=map_product_cadre.type) where [map_product_cadre].type='$type_of_transaction' and [map_product_cadre].year='$data_year' and [map_product_cadre].location='$location' and [map_product_cadre].final_data='$final_data' group by cadre, flavour,aggre_qty,doc_id,aggregate_data_upload.type order by cadre");



//SELECT cadre,flavour,sum(qty) as flavour_qty, aggregate_data_upload.aggre_qty *sum(qty) as total_flavour_qty,doc_id,aggregate_data_upload.type from [distribution].[dbo].[map_product_cadre] JOIN [distribution].[dbo].[aggregate_data_upload] on (map_product_cadre.cadre=aggregate_data_upload.aggre_cadre and map_product_cadre.location=aggregate_data_upload.aggre_location and aggregate_data_upload.type=map_product_cadre.type ) where [map_product_cadre].type='End of Year Product' and [map_product_cadre].year='2023' and [map_product_cadre].location='ota-Noodles'  group by cadre, flavour,aggre_qty,doc_id,aggregate_data_upload.type order by cadre

					$available="Available";
		$select_all_flavour=sqlsrv_query($db_conn," SELECT cadre,flavour,sum(qty) as flavour_qty, aggregate_data_upload.aggre_qty *sum(qty) as total_flavour_qty,doc_id,aggregate_data_upload.type from [distribution].[dbo].[map_product_cadre] JOIN [distribution].[dbo].[aggregate_data_upload] on (map_product_cadre.cadre=aggregate_data_upload.aggre_cadre and map_product_cadre.location=aggregate_data_upload.aggre_location and aggregate_data_upload.type=map_product_cadre.type) where [map_product_cadre].month='$data_mon' and year='$data_year' and location='$location'  and [map_product_cadre].type='$type_of_transaction' group by cadre, flavour,aggre_qty,doc_id,aggregate_data_upload.type order by cadre");

			$fds=array();
			while($select_all_d=sqlsrv_fetch_array($select_all_flavour,SQLSRV_FETCH_ASSOC)){
				//$fds[]=$select_all_d;

				$save_aggregate_flavour="INSERT INTO [distribution].[dbo].[aggregate_flavour_data] (cadre,flavour,flavour_qty,total_flavour_qty,month_data,year_data,location,doc_id,type,final_data,avail) values(?,?,?,?,?,?,?,?,?,?,?)";
			$save_aggregate_pre_flavour=sqlsrv_prepare($db_conn,$save_aggregate_flavour,array($select_all_d['cadre'],$select_all_d['flavour'],$select_all_d['flavour_qty'],$select_all_d['total_flavour_qty'],$data_mon,$data_year,$location,$select_all_d['doc_id'],$type_of_transaction,$final_data,$available));
			sqlsrv_execute($save_aggregate_pre_flavour);
			}

	echo "Data Uploaded Successfully";
}else{
	echo "Error while uploading data, Please Contact the System Administrator";
								}
}else{
		echo "You have not added the quantity of product for each Cadre or the flavour for each cadre, Use the settings menu to add this and re-upload".$select_if_cadre_available_count['cadreData_item'];
	}

}
}







	

		}else{


			//cehck the if the data exist before
		$check=sqlsrv_query($db_conn,"SELECT count(*) as month_count from distribution.dbo.upload_file_data where data_mon='$data_mon' and data_year='$year' and location='$location' and type='$type_of_transaction' and final_data in ('End','processed','Start')");
		$check_before=sqlsrv_fetch_array($check,SQLSRV_FETCH_ASSOC);

		if($check_before['month_count']>0){
			echo $type." data has been previously uploaded Product for the month of  ".$data_mon." kindly get approval to upload another instance";

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
	//$monthly="Monthly";

	$id="Temp-".date("Y-m-d").rand(2,9999);

//check to know if there is a previous account for cadre;

	$select_if_cadre_available=sqlsrv_query($db_conn,"SELECT count(*) as cadreData_item from [distribution].[dbo].[cadre_table] where month='$data_mon' and year='$data_year' and location='$location' and type='$type_of_transaction'");
	$select_if_cadre_available_count=sqlsrv_fetch_array($select_if_cadre_available,SQLSRV_FETCH_ASSOC);


	$select_if_flavour_available=sqlsrv_query($db_conn,"SELECT count(*) as flavourData_item from [distribution].[dbo].[map_product_cadre] where month='$data_mon' and location='$location' and type='$type_of_transaction'");
	$select_if_flavour_available_count=sqlsrv_fetch_array($select_if_flavour_available,SQLSRV_FETCH_ASSOC);




	if($select_if_cadre_available_count['cadreData_item']>='4' and $select_if_flavour_available_count['flavourData_item']>='4'){	


	$uploadData_in="INSERT INTO distribution.dbo.[upload_file_data] (Status,data_time,data_date,data_mon,data_year,data_loc,id,file_name_in,location,type,final_data) values(?,?,?,?,?,?,?,?,?,?,?)";
	$prepare_data=sqlsrv_prepare($db_conn,$uploadData_in,array($status,$data_time,$data_date,$data_mon,$data_year,$data_loc,$id,$file,$location,$type_of_transaction,$final_data));
				if(sqlsrv_execute($prepare_data)==TRUE){



					$file_to_work_on=$_FILES["fileuploaddata"]["tmp_name"];
					$default_loc="../uploads/".$file;


					//$current_sheet=$sheet->load($default_loc);


					$spreadsheet = IOFactory::load($default_loc);
					$sheetData = $spreadsheet->getActiveSheet()->toArray();
					//null, true, true, true

						unset($sheetData[0]);
						foreach ($sheetData as $row) {
							$staffid=trim($row[1]);
							$fullname=trim($row[2]);
							$dept=trim($row[3]);
							$cadre=trim($row[4]);
							$type=$type_of_transaction;		
   						$saveFile="INSERT INTO distribution.dbo.[uploaded_staff_list](staffid,employee_name,dept,cadre,date,month,year,id,location,type,final_data) values(?,?,?,?,?,?,?,?,?,?,?)";
						$saveFile_pre=sqlsrv_prepare($db_conn,$saveFile,array($staffid,$fullname,$dept,$cadre,$data_date,$data_mon,$data_year,$id,$location,$type_of_transaction,$final_data));
								sqlsrv_execute($saveFile_pre);
							   						
							}
			
			//while($select_flavour=sqlsrv_fetch_array($select_all_flavour,SQLSRV_FETCH_ASSOC)){
			//	$fds[]=$select_flavour;

			//$save_aggregate_flavour="INSERT INTO [distribution].[dbo].[aggregate_flavour_data] (cadre,flavour,flavour_qty,total_flavour_qty,month_data,year_data,location,doc_id) values(?,?,?,?,?,?,?,?)";
			//$save_aggregate_pre_flavour=sqlsrv_prepare($db_conn,$save_aggregate_flavour,array($select_all_pre_flavour['cadre'],$select_all_pre_flavour['flavour'],$select_all_pre_flavour['flavour_qty'],$select_all_pre_flavour['total_flavour_qty'],$data_mon,$data_year,$location,$select_all_pre_flavour['doc_id']));
			//sqlsrv_execute($save_aggregate_pre_flavour);
						
				//	}
		//echo json_encode($fds);






							// update table for all staff

					$update_data="UPDATE [distribution].[dbo].[uploaded_staff_list] set qty=(SELECT cadre_table.Qty from [distribution].[dbo].[cadre_table] where cadre_table.cadre=uploaded_staff_list.cadre and month='$data_mon' and year='$data_year' and location='$location' and type='$type_of_transaction') where id='$id'";
					$update_data_d=sqlsrv_prepare($db_conn,$update_data,array($data_mon,$year));
					sqlsrv_execute($update_data_d);	



$update_cadre_table="UPDATE [distribution].[dbo].[cadre_table]  set id=? where month=? and year=? and location=? and type=?";
					$update_data_d_cadre=sqlsrv_prepare($db_conn,$update_cadre_table,array($id,$data_mon,$data_year,$location,$type_of_transaction));
					sqlsrv_execute($update_data_d_cadre);	



					$test_d=array();
					$select_all=sqlsrv_query($db_conn," SELECT uploaded_staff_list.cadre,count(*) as qty,sum (cast( uploaded_staff_list.qty as int)) as total from [distribution].[dbo].[uploaded_staff_list]
  						JOIN [distribution].[dbo].[cadre_table] ON (uploaded_staff_list.cadre=[cadre_table].cadre AND uploaded_staff_list.location=[cadre_table].location and uploaded_staff_list.id=[cadre_table].id) where [cadre_table].month='$data_mon' and [cadre_table].location='$location' and [cadre_table].year='$data_year' and [cadre_table].type='$type_of_transaction' GROUP BY uploaded_staff_list.cadre,cadre_table.Qty");
 					while($select_all_pre=sqlsrv_fetch_array($select_all,SQLSRV_FETCH_ASSOC)){
						$save_aggregate=  "INSERT into [distribution].[dbo].[aggregate_data_upload] (aggre_cadre,aggre_qty,aggre_total_count,aggre_month,aggre_year,aggre_location,doc_id,type,final_data) values(?,?,?,?,?,?,?,?,?)";
						$save_aggregate_pre=sqlsrv_prepare($db_conn,$save_aggregate,array($select_all_pre['cadre'],$select_all_pre['qty'],$select_all_pre['total'],$data_mon,$data_year,$location,$id,$type_of_transaction,$final_data));
						sqlsrv_execute($save_aggregate_pre);
						
					}

					// update flavour
					$available="Available";
		$select_all_flavour=sqlsrv_query($db_conn," SELECT cadre,flavour,sum(qty) as flavour_qty, aggregate_data_upload.aggre_qty *sum(qty) as total_flavour_qty,doc_id from [distribution].[dbo].[map_product_cadre] JOIN [distribution].[dbo].[aggregate_data_upload] on (map_product_cadre.cadre=aggregate_data_upload.aggre_cadre and map_product_cadre.location=aggregate_data_upload.aggre_location) where [map_product_cadre].month='$data_mon' and year='$data_year' and location='$location' and [aggregate_data_upload].doc_id='$id' and [map_product_cadre].type='$type_of_transaction' group by cadre, flavour,aggre_qty,doc_id order by cadre");
			$fds=array();
			while($select_all_d=sqlsrv_fetch_array($select_all_flavour,SQLSRV_FETCH_ASSOC)){
				//$fds[]=$select_all_d;

				$save_aggregate_flavour="INSERT INTO [distribution].[dbo].[aggregate_flavour_data] (cadre,flavour,flavour_qty,total_flavour_qty,month_data,year_data,location,doc_id,type,final_data,avail) values(?,?,?,?,?,?,?,?,?,?,?)";
			$save_aggregate_pre_flavour=sqlsrv_prepare($db_conn,$save_aggregate_flavour,array($select_all_d['cadre'],$select_all_d['flavour'],$select_all_d['flavour_qty'],$select_all_d['total_flavour_qty'],$data_mon,$data_year,$location,$select_all_d['doc_id'],$type_of_transaction,$final_data,$available));
			sqlsrv_execute($save_aggregate_pre_flavour);
			}


							echo "Data Uploaded Successfully";
								}else{
							echo "Error while uploading data, Please Contact the System Administrator";
								}
	}else{
		echo "You have not added the quantity of product for each Cadre or the flavour for each cadre, Use the settings menu to add this and re-upload";
	}


		}else{
			echo "Location not found";
}
		//echo $file_loc;
	}
}

}

//$date.


//echo $file;
	

	//echo $file;
}else if(isset($_POST['login'])){
	$email=$_POST['email'];
	$password=$_POST['password'];
	$fb="";
	$role="user";
	$check_connect=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.login where email='$email' and password='$password' and role='$role'");
		$count=sqlsrv_has_rows($check_connect);
		if($count>0){
				$check_connect_fed=sqlsrv_fetch_array($check_connect,SQLSRV_FETCH_ASSOC);
				//session_start();
				$_SESSION['user']=$check_connect_fed['StaffID'];
				$_SESSION['location']=$check_connect_fed['location'];
				$_SESSION['Fullname']=$check_connect_fed['Fullname'];
				
				$fb="Connected successfully";
				
		}else{
			$fb="You are typing an invalid credentials";
		}

	echo json_encode($fb);
}else if(isset($_POST['logout_id'])) {

	session_destroy();

	echo json_encode("Account logged out successfully");

	// code...
}elseif (isset($_POST['delete_data'])) {
	$staffid=$_POST['staff_id'];
	$id=$_POST['id'];
	$data_date=date("Y-m-d");
	$data_time=date("h:i:s");
	$location=$_SESSION['location'];

	$delete_data= sqlsrv_query($db_conn,"DELETE distribution.dbo.[upload_file_data] where id='$id' and location='$location'");
	
	echo json_encode("Deleted Successfully");	



	//$DaleteData_in="INSERT INTO [distribution].[dbo].[delete_log] (delete_id,del_date,del_time) values(?,?,?)";
	

	
	// code...
}elseif(isset($_POST['cadre_data'])){
	$cadretype=$_POST['cadretype'];
	$cadreNum=$_POST['cadreNum'];
	$yearly=$_POST['yrly'];
	
	$dater=date("Y-m-d H:i:s");
	//$location=$_SESSION['location'];
	$id="Data".rand(3,10000).date("Y-m-d-H:i:s").$cadretype;
	//$location="Location";
	$month=date("m");
	$year=date("Y");
	$location=$_SESSION['location'];
		$data_d=[];
		$yrly="End of Year Product";
		$monthly="Monthly";


$data_d['old_file']="";
$data_d['success']="";
$data_d['cadreuse']="";
$data_d['error']="";
	if($yearly=="true"){
		//echo json_encode("Yes, You want to enable yearly");


	$carde_check=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table] where cadre='$cadretype' and location='$location'  and year='$year' and type='$yrly'");
		$carde_check_count=sqlsrv_has_rows($carde_check);
		if($carde_check_count>0){
			$data_d['old_file']="You have previously created same for this product";

		}else{
		
	$cadre_data_d="INSERT INTO distribution.dbo.[cadre_table] (cadre,Qty,dater,month,q_id,location,year,type) values(?,?,?,?,?,?,?,?)";
	$cadre_data_dd=sqlsrv_prepare($db_conn,$cadre_data_d,array($cadretype,$cadreNum,$dater,$month,$id,$location,$year,$yrly));
	if(sqlsrv_execute($cadre_data_dd)===TRUE){
		$data_d['success']= 'Saved Successfully';
		$data_d['cadreuse']=$cadretype;

		//$fetc=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table] where month='$month' and cadre='$cadretype' and location='$location' and year='$year'");
		//while($fetc_d=sqlsrv_fetch_array($fetc,SQLSRV_FETCH_ASSOC)){
		//	$data_d[]=$fetc_d;
		//}

	}else{
		$data_d['error']= "Error Saving";
	}
	//echo json_encode($data_d);
}






	}else{



	$carde_check=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table] where month='$month' and cadre='$cadretype' and location='$location' and year='$year' and type='monthly'");
		$carde_check_count=sqlsrv_has_rows($carde_check);
		if($carde_check_count>0){
			$data_d['old_file']="You have previously created same for this product";

		}else{
		
	$cadre_data_d="INSERT INTO distribution.dbo.[cadre_table] (cadre,Qty,dater,month,q_id,location,year,type) values(?,?,?,?,?,?,?,?)";
	$cadre_data_dd=sqlsrv_prepare($db_conn,$cadre_data_d,array($cadretype,$cadreNum,$dater,$month,$id,$location,$year,$monthly));
	if(sqlsrv_execute($cadre_data_dd)===TRUE){
		$data_d['success']= 'Saved Successfully';
		$data_d['cadreuse']=$cadretype;

		/*$fetc=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table] where month='$month' and cadre='$cadretype' and location='$location' and year='$year'");
		while($fetc_d=sqlsrv_fetch_array($fetc,SQLSRV_FETCH_ASSOC)){
			$data_d[]=$fetc_d;
		}
		*/
	}else{
		$data_d['error']= "Error Saving";
	}
}

//echo json_encode($data_d);
}
echo json_encode($data_d);
}
elseif(isset($_POST['refresh_cadre'])){
		$yu=array();
		$location=$_SESSION['location'];

	$refresh_data_cadre=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.[cadre_table] where location='$location'");
	while ($refresh_data_cadre_d=sqlsrv_fetch_array($refresh_data_cadre,SQLSRV_FETCH_ASSOC)) {
		$yu=$refresh_data_cadre_d;
	}
	echo json_encode($yu);
}
elseif(isset($_POST['viewDataView'])){
	$dataView=$_POST['dataView'];
	$location=$_SESSION['location'];
		
	$y=array();
	$dataView_d=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[upload_file_data] where id='$dataView' and location='$location' ");
	$dataView_dd=sqlsrv_fetch_array($dataView_d,SQLSRV_FETCH_ASSOC);
	

$path=$dataView_dd['file_name_in'];
$location="../uploads/";
$path_d=scandir($location);
$pattern = $location .$path;
//$path_dd = array_diff($path_d, array('.', '..'));



	echo json_encode($pattern);
}

elseif(isset($_POST['save_data_cadre'])){

		$cadre_qtyData=$_POST['cadre_qtyData'];
		$flavourData=$_POST['flavourData'];
		$cadreQty=$_POST['cadreQty'];
		$location=$_POST['location'];
		$date=date("Y-m-d");
		$month=date("m");
		$day=date("d");
		$year=date("Y");
		$year_data=$_POST['yearlyData'];
		$yrly="End of Year Product";
		$monthly="Monthly";
		$batch_track='Available';

		$typeoftrans="";

		if($year_data=="true"){
			$typeoftrans="End of Year Product";


		$count_data=sqlsrv_query($db_conn,"SELECT CAST (Qty AS INT) AS Qty,q_id from [distribution].[dbo].[cadre_table] where cadre='$cadre_qtyData'  and location='$location' and year='$year' and type='$yrly'");

		if(sqlsrv_has_rows($count_data)>0){
			$count_data_pick=sqlsrv_fetch_array($count_data,SQLSRV_FETCH_ASSOC);
		$count_current_cadre=$count_data_pick['Qty'];
		$current_id=$count_data_pick['q_id'];

		$count_data_flavour=sqlsrv_query($db_conn,"SELECT SUM(CAST(qty as INT)) as qty from distribution.dbo.[map_product_cadre] where cadre='$cadre_qtyData' and month='$month' and location='$location' and type='$yrly'");
		$count_data_flavour_pick=sqlsrv_fetch_array($count_data_flavour,SQLSRV_FETCH_ASSOC);
		$count_current_flavour_pick_real=$count_data_flavour_pick['qty'];
		$count_current_flavour_pick=(int)$count_current_flavour_pick_real+(int)$cadreQty;
		$map_id=$current_id;
				
		$id_d="Data_temp ".rand(3,10000).date("Y-m-d-h:i:s").$cadre_qtyData;
		//$d_save=[];
		if($count_current_flavour_pick>$count_current_cadre OR $cadreQty>$count_current_cadre){
			//$d_save['overflow_save']="You cannot add more than the required flavour";
			echo json_encode('You cannot add more than the required flavour');
		}else{
		
		$dtat="INSERT INTO distribution.dbo.[map_product_cadre] (cadre,flavour,qty,map_id,item_id,month,year,day,location,type,availability) values (?,?,?,?,?,?,?,?,?,?,?)";
		$dtat_d=sqlsrv_prepare($db_conn,$dtat,array($cadre_qtyData,$flavourData,$cadreQty,$map_id,$id_d,$month,$year,$day,$location,$typeoftrans,$batch_track));
		$che=sqlsrv_execute($dtat_d);
		if($che==TRUE){
			//$d_save['success_save']="Saved Successfully";
			echo json_encode('Saved Successfully');

		}else{
			//$d_save['error_save']="Error while saving";
			echo json_encode('Error while saving');
		}
	}//end of if check quantity match
	

		}else{ //end of checking data cadre creation

			echo json_encode("You have not created cadre for this account");
		}

		













		}else{
			$typeoftrans="Monthly";

					$count_data=sqlsrv_query($db_conn,"SELECT CAST (Qty AS INT) AS Qty,q_id from [distribution].[dbo].[cadre_table] where cadre='$cadre_qtyData' and month='$month' and location='$location' and year='$year' and  type='$monthly'");

		if(sqlsrv_has_rows($count_data)>0){
			$count_data_pick=sqlsrv_fetch_array($count_data,SQLSRV_FETCH_ASSOC);
		$count_current_cadre=$count_data_pick['Qty'];
		$current_id=$count_data_pick['q_id'];

		$count_data_flavour=sqlsrv_query($db_conn,"SELECT SUM(CAST(qty as INT)) as qty from distribution.dbo.[map_product_cadre] where cadre='$cadre_qtyData' and month='$month' and location='$location' and type='$monthly'");
		$count_data_flavour_pick=sqlsrv_fetch_array($count_data_flavour,SQLSRV_FETCH_ASSOC);
		$count_current_flavour_pick_real=$count_data_flavour_pick['qty'];
		$count_current_flavour_pick=(int)$count_current_flavour_pick_real+(int)$cadreQty;
		$map_id=$current_id;
				
		$id_d="Data_temp ".rand(3,10000).date("Y-m-d-h:i:s").$cadre_qtyData;
		//$d_save=[];
		if($count_current_flavour_pick>$count_current_cadre OR $cadreQty>$count_current_cadre){
			//$d_save['overflow_save']="You cannot add more than the required flavour";
			echo json_encode('You cannot add more than the required flavour');
		}else{
		
		$dtat="INSERT INTO distribution.dbo.[map_product_cadre] (cadre,flavour,qty,map_id,item_id,month,year,day,location,type) values (?,?,?,?,?,?,?,?,?,?)";
		$dtat_d=sqlsrv_prepare($db_conn,$dtat,array($cadre_qtyData,$flavourData,$cadreQty,$map_id,$id_d,$month,$year,$day,$location,$typeoftrans));
		$che=sqlsrv_execute($dtat_d);
		if($che==TRUE){
			//$d_save['success_save']="Saved Successfully";
			echo json_encode('Saved Successfully');

		}else{
			//$d_save['error_save']="Error while saving";
			echo json_encode('Error while saving');
		}
	}//end of if check quantity match
	

		}else{ //end of checking data cadre creation

			echo json_encode("You have not created cadre for this account");
		}

		

		}
		




		//echo json_encode($d_save);
}

elseif(isset($_POST['fetch_data_cadre'])){
	$location=$_SESSION['location'];

	$id_uniq=$_POST['id_unique'];
	$id_r=array();
	$count_uniq=sqlsrv_query($db_conn,"SELECT flavour,cadre,qty,month,year from [distribution].[dbo].[map_product_cadre] where map_id='$id_uniq' and location='$location'");
	while($count_count_uniq_r=sqlsrv_fetch_array($count_uniq,SQLSRV_FETCH_ASSOC)){
		$id_r[]=$count_count_uniq_r;
		
	}
	echo json_encode($id_r);
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
			$location=$_POST['location'];

			$fetch_carde=sqlsrv_query($db_conn,"SELECT * FROM distribution.dbo.[cadre_table] where location='$location'");
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

}

elseif(isset($_POST['fetch_flavour_data'])){
	$fla_data='';
	$FlavourCount=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[product_table]");
		while($FlavourCount_d=sqlsrv_fetch_array($FlavourCount,SQLSRV_FETCH_ASSOC)){
		$fla_data.='<option>'.$FlavourCount_d['sku']."</option>";
		}
		echo json_encode($fla_data);

}







elseif(isset($_POST['fetch_all_map_product'])){
			$location=$_SESSION['location'];
			$mn=date('m');
			$yr=date('Y');
		$dh_product_map=array();
		$date_tak_map=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[map_product_cadre] where location='$location' and month='$mn'and year='$yr'");
	//	$bv=1;
		while ($dat_map=sqlsrv_fetch_array($date_tak_map,SQLSRV_FETCH_ASSOC)) {
			$dh_product_map[]=$dat_map;	
					
		}

	echo json_encode($dh_product_map);

/*'<tr>
								<td>'.$dat_map['cadre'].'</td>
								<td>'.$dat_map['flavour'].'</td>
								<td>'.$dat_map['qty'].'</td>
								<td>'.$dat_map['month'].'</td>
								<td>'.$dat_map['day'].'</td>
								</tr>

								';*/
}
elseif(isset($_POST['fetchData_for_mon'])){

/*uploaded_staff_list.cadre,
	$fla_data_mon='';
	$FlavourCount=sqlsrv_query($db_conn,"SELECT uploaded_staff_list.status,month,sum (cast( uploaded_staff_list.qty as int)) as qty from [distribution].[dbo].[uploaded_staff_list] where month='$data_mon' group by uploaded_staff_list.cadre,uploaded_staff_list.status");
		while($FlavourCount_rd=sqlsrv_fetch_array($FlavourCount,SQLSRV_FETCH_ASSOC)){
		$fla_data_mon[]=$FlavourCount_rd;
		}
	echo json_encode($fla_data_mon);
	*/

}


elseif(isset($_POST['individual_item_delete_now'])){
		$id_to_delete=$_POST['del_id_data'];
		$mon_to_use=date('m');
		$appv="Approved";
		$location=$_SESSION['location'];
		$mon_year=date('Y');
			$check_approval_status=sqlsrv_query($db_conn," SELECT count(*) as countAppDel FROM [distribution].[dbo].[approval_table] where month='$mon_to_use' and close_status='$appv' and year='$mon_year' and location='$location' and document_id='$id_to_delete'");
			$check_approval_status_dataDel=sqlsrv_fetch_array($check_approval_status,SQLSRV_FETCH_ASSOC);

			if($check_approval_status_dataDel['countAppDel']>0){

				echo json_encode("You cannot delete a transaction that has been approved by at least one of the approver. Contact your Administrator for solution");

			}else{

		$delete_item_in_database="DELETE [distribution].[dbo].[upload_file_data] where id=? and location=?";
		//$delete_item_in_database_cadre="DELETE [distribution].[dbo].[cadre_table] where month=? and year=?";
		$delete_flavour_aggregate_data="DELETE [distribution].[dbo].[aggregate_flavour_data] where doc_id=? and location=?";
		$delete_approval_data="DELETE [distribution].[dbo].[approval_table] where document_id=? and location=?";


		$delete_item_in_database_list="DELETE [distribution].[dbo].[uploaded_staff_list] where id=? and location=?";

		$delete_item_in_database_aggree="DELETE [distribution].[dbo].[aggregate_data_upload] where doc_id=? and aggre_location=?";

		//$delete_item_in_database_flavour="DELETE [distribution].[dbo].[map_product_cadre] where month=? and year=? and location=?" ;

		$delete_prepare=sqlsrv_prepare($db_conn,$delete_item_in_database,array($id_to_delete,$location));
		$delete_approval=sqlsrv_prepare($db_conn,$delete_approval_data,array($id_to_delete,$location));
		$delete_prepare_flavour_aggregate=sqlsrv_prepare($db_conn,$delete_flavour_aggregate_data,array($id_to_delete,$location));
		$delete_prepare_aggre=sqlsrv_prepare($db_conn,$delete_item_in_database_aggree,array($id_to_delete,$location));
		$delete_prepare_list=sqlsrv_prepare($db_conn,$delete_item_in_database_list,array($id_to_delete,$location));
		//$delete_prepare_list_cadre=sqlsrv_prepare($db_conn,$delete_item_in_database_cadre,array($mon_to_use,$mon_year));
		//$delete_item_in_database_flavour_d=sqlsrv_prepare($db_conn,$delete_item_in_database_flavour,array($mon_to_use,$mon_year,$location));

// AND sqlsrv_execute($delete_prepare_list_cadre)==TRUE AND sqlsrv_execute($delete_item_in_database_flavour_d)==TRUE
		

	if(sqlsrv_execute($delete_prepare)==TRUE AND sqlsrv_execute($delete_prepare_aggre)==TRUE AND sqlsrv_execute($delete_prepare_list)==TRUE AND sqlsrv_execute($delete_prepare_flavour_aggregate)==TRUE AND sqlsrv_execute($delete_approval)==TRUE){
			echo json_encode("Deleted Successfully");
		}else{
			echo json_encode("Error while trying to delete this transaction, contact your Administrator");
		}
}
	
}
elseif(isset($_POST['individual_item_view_now'])){
		$id_report=$_POST['download_id_data'];
		$location=$_SESSION['location'];

	$reportData_all=array();
	$reportData_all_d=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[uploaded_staff_list] where id='$id_report' and location='$location'");
		while($reD=sqlsrv_fetch_array($reportData_all_d,SQLSRV_FETCH_ASSOC)){
		$reportData_all[]=$reD;
		}
		echo json_encode($reportData_all);
}

elseif(isset($_POST['allrefresh'])){
				$location=$_POST['loc_id'];
				$mon=date('m');
    			$yr=date('Y');
			$report_freq=array();
			$fet_all=sqlsrv_query($db_conn,"SELECT SUM (CAST (uploaded_staff_list.qty as int)) as qty FROM [distribution].[dbo].[uploaded_staff_list] where month='$mon' and year='$yr'and location='$location' and final_data in ('start','processed')");
    		$fet_all_get=sqlsrv_fetch_array($fet_all,SQLSRV_FETCH_ASSOC);
    		$get_all_collected=sqlsrv_query($db_conn,"SELECT SUM (cast( uploaded_staff_list.qty as int))  as total from [distribution].[dbo].[uploaded_staff_list] where Status is null and month='$mon' and year='$yr' and location='$location' and final_data in ('start','processed')");
    		$get_all_collected_data=sqlsrv_fetch_array($get_all_collected,SQLSRV_FETCH_ASSOC);
	    	$get_all_collectedd=sqlsrv_query($db_conn,"SELECT SUM (cast( uploaded_staff_list.qty as int))  as total from [distribution].[dbo].[uploaded_staff_list] where Status  in ('Issued','Checkout') and month='$mon' and year='$yr'and location='$location' and final_data='processed' ");
    		$get_all_collected_datad=sqlsrv_fetch_array($get_all_collectedd,SQLSRV_FETCH_ASSOC);//and status !='Closed'
    		


    		$report_freq['issued']=$get_all_collected_datad['total']; 	

    		$report_freq['collected']=$get_all_collected_data['total'];
      		$report_freq['allsheet']=$fet_all_get['qty'];

    	//	$reportall=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[uploaded_staff_list]");
//$reportall

    			echo json_encode($report_freq);

}
elseif(isset($_POST['defualt_approval_list'])){
	$location=$_POST['loc_front'];
//where location='$location'
	$get_all_approval=sqlsrv_query($db_conn,"SELECT app_id,approval_id,close_status,close_date,month,sent_date,type FROM [distribution].[dbo].[approval_table] where location='$location' order by month,type DESC");
	$bnm=array();
	while($bn=sqlsrv_fetch_array($get_all_approval,SQLSRV_FETCH_ASSOC)){
		$bnm[]=$bn;

	}
echo json_encode($bnm);
}
elseif(isset($_POST['cadre_data_show'])){
	$location=$_SESSION['location'];
	$mon=date('m');
	$yr=date('Y');
	$get_cadre_data_show=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[cadre_table] where location='$location' and month='$mon' and year='$yr'");
	$bn_cadre=array();
	while($bn_card=sqlsrv_fetch_array($get_cadre_data_show,SQLSRV_FETCH_ASSOC)){
		$bn_cadre[]=$bn_card;

	}
echo json_encode($bn_cadre);





}

elseif(isset($_POST['deleteCadreData'])){
	$location=$_SESSION['location'];
	$idtodelete=$_POST['delete_cadre'];

	$chek_before_delete=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[cadre_table] where q_id='$idtodelete' and location='$location'");
	$chek_before_delete_count=sqlsrv_fetch_array($chek_before_delete,SQLSRV_FETCH_ASSOC);
	$monDt=$chek_before_delete_count['month'];
	$monLocation=$chek_before_delete_count['location'];
	$monYear=$chek_before_delete_count['year'];
	$type_data=$chek_before_delete_count['type'];
	//SELECT * FROM [distribution].[dbo].[approval_table] WHERE month='$monDt' and year='$monYear' and location='$monLocation'
	$check_if_send_for_approavl=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[approval_table] join [distribution].[dbo].[uploaded_staff_list] ON approval_table.month=uploaded_staff_list.month and uploaded_staff_list.year=approval_table.year where uploaded_staff_list.location ='$monLocation' and approval_table.type='$type_data'");
	$ch=sqlsrv_has_rows($check_if_send_for_approavl);
	
	if($ch>0){
		echo json_encode("You cannot delete this data, it has been uploaded or sent for approval and all necessary update has been run. You can only delete cadre or SKU mapped to it if you have not uploaded or sent for approval. You can delete the uploaded data if you have not sent for approval, then retry. Incase of any difficulties,contact your Administrator");
	}else{

	sqlsrv_query($db_conn,"DELETE [distribution].[dbo].[uploaded_staff_list] where month='$monDt' and year='$monYear' and location='$monLocation' and type='$type_data'");
	sqlsrv_query($db_conn,"DELETE [distribution].[dbo].[map_product_cadre] where map_id='$idtodelete' and location='$monLocation' and type='$type_data'");
	sqlsrv_query($db_conn,"DELETE [distribution].[dbo].[cadre_table] where q_id='$idtodelete' and location='$monLocation' and type='$type_data'");
	sqlsrv_query($db_conn,"DELETE [distribution].[dbo].[upload_file_data] where location='$monLocation' and type='$type_data' and data_year='$monYear' and data_mon='$monDt'");

			echo json_encode("Records uploaded,Cadre and SKU mapped to it have been been deleted successfully");


	}

	


}
?>