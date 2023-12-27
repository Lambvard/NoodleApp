<?php

include('db.php');
			$data_mon=date('m');
			$data_year=date('Y');
			$location="SURULERE";
	
	/*	$select_all_flavour=sqlsrv_query($db_conn," SELECT cadre,flavour,sum(cast (qty as int)) as flavour_qty, cast(aggregate_data_upload.aggre_qty as int) *sum(cast (qty as int)) as total_flavour_qty,doc_id from [distribution].[dbo].[map_product_cadre] JOIN [distribution].[dbo].[aggregate_data_upload] on map_product_cadre.cadre=aggregate_data_upload.aggre_cadre where [map_product_cadre].month='10' group by cadre, flavour,aggre_qty,doc_id order by cadre");
			
		while($select_all_pre_flavour=sqlsrv_fetch_array($select_all_flavour,SQLSRV_FETCH_ASSOC)){
			$save_aggregate_flavour=sqlsrv_query($db_conn,"INSERT INTO [distribution].[dbo].[aggregate_flavour_data] (cadre,flavour,flavour_qty,total_flavour_qty,month_data,year_data,location,doc_id) values('$select_all_pre_flavour['cadre']','$select_all_pre_flavour['flavour']',$select_all_pre_flavour['flavour_qty']','$select_all_pre_flavour['total_flavour_qty']','$data_mon','$data_year','$location','$select_all_pre_flavour['doc_id']')";
			//$save_aggregate_pre_flavour=sqlsrv_prepare($db_conn,$save_aggregate_flavour,array());
			//sqlsrv_execute($save_aggregate_pre_flavour);


					

					}
		
//if(sqlsrv_execute($save_aggregate_flavour)==TRUE){
//					echo json_encode("Successfully");
//					}else{
//						 die(print_r(sqlsrv_errors(), true));
//					}
//?,?,?,?,?,?,?,?
*/


/*


$select_all_flavour=sqlsrv_query($db_conn," SELECT cadre,flavour,sum(qty) as flavour_qty, aggregate_data_upload.aggre_qty *sum(qty) as total_flavour_qty,doc_id from [distribution].[dbo].[map_product_cadre] JOIN [distribution].[dbo].[aggregate_data_upload] on map_product_cadre.cadre=aggregate_data_upload.aggre_cadre where [map_product_cadre].month='$data_mon' group by cadre, flavour,aggre_qty,doc_id order by cadre");
			//$fds=array();
			while($select_all_d=sqlsrv_fetch_array($select_all_flavour,SQLSRV_FETCH_ASSOC)){
				//$fds[]=$select_all_d;

				$save_aggregate_flavour="INSERT INTO [distribution].[dbo].[aggregate_flavour_data] (cadre,flavour,flavour_qty,total_flavour_qty,month_data,year_data,location,doc_id) values(?,?,?,?,?,?,?,?)";
			$save_aggregate_pre_flavour=sqlsrv_prepare($db_conn,$save_aggregate_flavour,array($select_all_d['cadre'],$select_all_d['flavour'],$select_all_d['flavour_qty'],$select_all_d['total_flavour_qty'],$data_mon,$data_year,$location,$select_all_d['doc_id']));
			sqlsrv_execute($save_aggregate_pre_flavour);
			}
			
			//while($select_flavour=sqlsrv_fetch_array($select_all_flavour,SQLSRV_FETCH_ASSOC)){
			//	$fds[]=$select_flavour;

			//$save_aggregate_flavour="INSERT INTO [distribution].[dbo].[aggregate_flavour_data] (cadre,flavour,flavour_qty,total_flavour_qty,month_data,year_data,location,doc_id) values(?,?,?,?,?,?,?,?)";
			//$save_aggregate_pre_flavour=sqlsrv_prepare($db_conn,$save_aggregate_flavour,array($select_all_pre_flavour['cadre'],$select_all_pre_flavour['flavour'],$select_all_pre_flavour['flavour_qty'],$select_all_pre_flavour['total_flavour_qty'],$data_mon,$data_year,$location,$select_all_pre_flavour['doc_id']));
			//sqlsrv_execute($save_aggregate_pre_flavour);
						
				//	}
		//echo json_encode($fds);

*/



			$select_all_flavour=sqlsrv_query($db_conn," SELECT cadre,flavour,sum(qty) as flavour_qty, aggregate_data_upload.aggre_qty *sum(qty) as total_flavour_qty,doc_id from [distribution].[dbo].[map_product_cadre] JOIN [distribution].[dbo].[aggregate_data_upload] on map_product_cadre.cadre=aggregate_data_upload.aggre_cadre where [map_product_cadre].month='$data_mon' group by cadre, flavour,aggre_qty,doc_id order by cadre");
			$fds=array();
			while($select_all_d=sqlsrv_fetch_array($select_all_flavour,SQLSRV_FETCH_ASSOC)){
				//$fds[]=$select_all_d;

				$save_aggregate_flavour="INSERT INTO [distribution].[dbo].[aggregate_flavour_data] (cadre,flavour,flavour_qty,total_flavour_qty,month_data,year_data,location,doc_id) values(?,?,?,?,?,?,?,?)";
			$save_aggregate_pre_flavour=sqlsrv_prepare($db_conn,$save_aggregate_flavour,array($select_all_d['cadre'],$select_all_d['flavour'],$select_all_d['flavour_qty'],$select_all_d['total_flavour_qty'],$data_mon,$data_year,$location,$select_all_d['doc_id']));
			sqlsrv_execute($save_aggregate_pre_flavour);
			}
?>