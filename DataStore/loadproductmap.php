<?php
include('db.php');
session_start();

$location=$_SESSION['location'];

$date_tak=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list]");
		$dh=array();

		while ($dat=sqlsrv_fetch_array($date_tak,SQLSRV_FETCH_ASSOC)) {
			$dh[]=$dat;

					
					
		}


echo json_encode($dh);
















?>