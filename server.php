<?php

include("db.php");

if(isset($_POST['hidlog'])){
	$username=$_POST['userID'];
	$Password=$_POST['userPass'];

	$dbs=sqlsrv_query($db,"SELECT * FROM seasoning_scrap.dbo.userprofile WHERE username='$username' AND password='$Password'");
$dbss_s=sqlsrv_fetch_array($dbs,SQLSRV_FETCH_ASSOC);
			$dbss=sqlsrv_has_rows($dbs);
			if($dbss>0){
				session_start();
				$_SESSION['usersession']=$dbss_s['userID'];
				//$_SESSION['hometype']=$home;
				//echo $_SESSION['usersession'];
				header("Location:../Production/dashboard.php");

			}else{
				header("Location:../index.php?id=invaliduserpassword");
				//echo $dbss_s['superID'];

			}


}




if(isset($_POST['takemeasurement'])){

		session_start();		
		$_SESSION['shiftsession']=$_POST['shift'];
		$_SESSION['linesession']=$_POST['numberline'];
		$_SESSION['noodlesession']=$_POST['nownoodle'];
		$_SESSION['typesession']=$_POST['typenoodle'];

			header("Location:../Production/Dashboard.php?id=scalerfiled");




}

elseif(isset($_POST['takemaintenancemeasurement'])){


		session_start();		
		$_SESSION['shiftsession']=$_POST['shift'];
		$_SESSION['linesession']=$_POST['numberline'];
		$_SESSION['noodlesession']=$_POST['nownoodle'];
		$_SESSION['typesession']=$_POST['typenoodle'];
		$_SESSION['inptva']=$_POST['inval'];

			header("Location:../Production/Dashboard.php?id=maintenancefield");
		

}

elseif(isset($_POST['seasoningData'])){
		$shift_d= $_POST['shift_d'];
		//$numberline_d= $_POST['numberline_d'];
		$typenoodle_d= $_POST['typenoodle_d'];
		$valuecapture_d= $_POST['valuecapture_d'];
		$dater=date("Y-m-d");
		$timer=date("H:i:s");
		$track_date=date("Y-m-d H:i:s");

		$current_date=date("H:i:s");

		if($current_date>='07:00:00' and $current_date <= '18:59:59'){
			$use_date=date("Y-m-d");

		}else{
			//$use_date=date_sub(date('Y-m-d'),"-1 day");

			$use_date = date("Y-m-d", strtotime("-1 day"));
		}
		//$numberline_d Lines,
		
$saveScrapData="INSERT INTO dbo.transactionlog (materials,Dater,Timer,Shift,readingvalues,track_date,track_id) VALUES (?,?,?,?,?,?,?)";
$ScrapQuery=sqlsrv_prepare($db,$saveScrapData,array($typenoodle_d,$dater,$timer,$shift_d,$valuecapture_d,$track_date,$use_date));

	if(sqlsrv_execute($ScrapQuery)===TRUE){
		$dbsr_2=sqlsrv_query($db,"SELECT cast (stock as float) as stock FROM dbo.inventorystock WHERE materials='$typenoodle_d'");
		$dbsr_3=sqlsrv_fetch_array($dbsr_2,SQLSRV_FETCH_ASSOC);

	$current_stock=$dbsr_3['stock'];

	$current_stock_real=$current_stock+$valuecapture_d;

		$db_update=sqlsrv_query($db,"UPDATE dbo.inventorystock SET stock='$current_stock_real' WHERE materials='$typenoodle_d'");

		echo json_encode("Data Saved successfully for this transaction");
	}else{
		echo json_encode("I encountered error, while trying to save the data, Please contact the System Administrator for solution");
	}





}elseif(isset($_POST['saverecord'])){
		session_start();
		$supervisorID=$_SESSION['usersession'];
		$typenoodlenow=$_SESSION['typesession'];
		$numberlinenow=$_SESSION['linesession'];
		$shiftnow=$_SESSION['shiftsession'];
		$home=$_SESSION['hometype'];
		$dates=date("Y-m-d");
		$timer=date("h:i:sa");
		$readingnow=$_POST['testinput'];
		$track_date=Date("Y-m-d h:i:sa");
		
		
	$dbsr=sqlsrv_query($db,"INSERT INTO dbo.transactionlog values('$supervisorID','$typenoodlenow','$numberlinenow','$shiftnow','$dates','$timer','$track_date','$readingnow','$home')");

	$dbsr_2=sqlsrv_query($db,"SELECT * FROM dbo.inventorystock WHERE materials='$typenoodlenow'");
	$dbsr_3=sqlsrv_fetch_array($dbsr_2,SQLSRV_FETCH_ASSOC);

	$current_stock=$dbsr_3['stock'];

	$current_stock_real=$current_stock+$readingnow;

	$db_update=sqlsrv_query($db,"UPDATE dbo.inventorystock SET stock='$current_stock_real' WHERE materials='$typenoodlenow'");

//echo $current_stock_real;

header("Location:../Production/Dashboard.php?id=startmeasurementsuccess");
}
//
elseif(isset($_POST['discardreading'])){
		//$supervisors=$_POST['supervisorname'];
		echo "Yes yes yes u";
	
}elseif(isset($_POST['testsubmit'])){

}elseif(isset($_POST['resetpassword'])){
	session_start();
	$useridorig=$_SESSION['usersession']; 
	$realpassword=$_POST['oldpassword'];
	$chanpass=$_POST['newpassword'];
	$confirmPassword=$_POST['confirmpassword'];

	$chek=sqlsrv_query($db,"SELECT * FROM dbo.userprofile where SuperID='$useridorig' AND password='$realpassword'");
		$cheks=$chek->sqlsrv_has_rows();
		if($cheks>0){
			if($chanpass==$confirmPassword){
				$vfb=sqlsrv_query($db,"UPDATE dbo.userprofile SET password='$chanpass' WHERE SuperID='$useridorig' ");
					header("Location:../Production/Dashboard.php?id=successpasswordchange");

			}else{
				header("Location:../Production/Dashboard.php?id=wrongpasswordchange");
			}
		}
			else{
				header("Location:../Production/Dashboard.php?id=Invalidpassword");
			}

}

















	$get_cadre=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where staffid='$stid' and id='$id'");
					$get_cadre_data=sqlsrv_fetch_array($get_cadre,SQLSRV_FETCH_ASSOC);
					$use_cadre=$get_cadre_data['cadre'];


					$get_available=sqlsrv_query($db_conn,"SELECT flavour FROM [distribution].[dbo].[aggregate_flavour_data] where cadre='$use_cadre' and avail='Available' and doc_id='$id' group by flavour");

					$get_num_cadre=sqlsrv_has_rows($get_available);
					


					$get_all_product=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[collect_track_table] where staffid='$stid' and month='$mon_to_use' and type='$type_noodles' and final_data='processed'");
						$get_if_availabe=sqlsrv_has_rows($get_all_product);
						if($get_if_availabe>0){
							$get_all_product_id=sqlsrv_fetch_array($get_all_product,SQLSRV_FETCH_ASSOC);
							$count_id=$get_all_product_id['items_collected'];

							$get_the_prvious=$get_all_product_id[0];

						}else{
							#$array_Data='';
							

						while($get_available_d=sqlsrv_fetch_array($get_available,SQLSRV_FETCH_ASSOC)){
							$flavour_to_save=$get_available_d['flavour'];
							$get_save_data=sqlsrv_query($db_conn,"INSERT INTO [distribution].[dbo].[collect_track_table] values('$stid','$use_cadre','$flavour_to_save','$mon_to_use','$mon_year','$type_noodles','$pr','$id')");
							}


							


?>