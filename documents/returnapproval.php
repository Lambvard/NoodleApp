	<?php 



	include("../datastore/db.php");?>

		<?php

			if(isset($_GET['lambda'])){
				$use_id_data_return=$_GET['lambda'];
			//echo $use_id_data_return;
				$use_id_data_d=base64_decode($use_id_data_return);
			//	echo $use_id_data_d."<br>";
				$ind=explode('-||||-', $use_id_data_d);

				$id=$ind[0];
				//echo $id."<br>";
				$doc_data=$ind[1];
				//echo $doc_data;
				$month_data=date("m");
				$year_data=date("Y");


//where id_track_return='$use_id_data_return' and return_status is not null
//$check_locr=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[return_track_table] where id_track_return='$use_id_data_return' and status is not null ");
//$check_lor=sqlsrv_has_rows($check_locr);	
//if($check_lor >0){
	//echo "You have previously approved/rejected this transaction";

//}else{//where id_track_return='$use_id_data_return'
// where id_track_return='$use_id_data_return'  and status is not null
$check_trackData=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[return_track_table] where id_track_return='$use_id_data_return' ");
$check_trackData_g=sqlsrv_fetch_array($check_trackData,SQLSRV_FETCH_ASSOC);
//var_dump($check_trackData_g);
$location=$check_trackData_g['location'];
$type_noo=$check_trackData_g['type'];
//echo $type_noo."<br>".$location;

				
//		}

}


	?>

		<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">	

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


<link href="../resources/util/DataTables-1.13.4/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="../resources/util/AutoFill-2.5.3/css/autoFill.dataTables.css" rel="stylesheet"/>
<link href="../resources/util/Buttons-2.3.6/css/buttons.dataTables.min.css" rel="stylesheet"/>
<link href="../resources/util/DateTime-1.4.1/css/dataTables.dateTime.min.css" rel="stylesheet"/>
<link href="../resources/util/FixedColumns-4.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet"/>
<link href="../resources/util/Responsive-2.4.1/css/responsive.dataTables.min.css" rel="stylesheet"/>
<link href="../resources/util/Select-1.6.2/css/select.dataTables.min.css" rel="stylesheet"/>
<script src="../resources/util/jQuery-3.6.0/jquery-3.6.0.min.js"></script>
<script src="../resources/util/JSZip-2.5.0/jszip.min.js"></script>
<!--<script src="../resources/util/pdfmake-0.2.7/pdfmake.min.js"></script>-->
<script src="../resources/util/pdfmake-0.2.7/vfs_fonts.js"></script>
<script src="../resources/util/DataTables-1.13.4/js/jquery.dataTables.min.js"></script>
<script src="../resources/util/AutoFill-2.5.3/js/dataTables.autoFill.min.js"></script>
<script src="../resources/util/Buttons-2.3.6/js/dataTables.buttons.min.js"></script>
<script src="../resources/util/Buttons-2.3.6/js/buttons.html5.min.js"></script>
<script src="../resources/util/Buttons-2.3.6/js/buttons.print.min.js"></script>
<script src="../resources/util/DateTime-1.4.1/js/dataTables.dateTime.min.js"></script>
<script src="../resources/util/FixedColumns-4.2.2/js/dataTables.fixedColumns.min.js"></script>
<!--<script src="../resources/util/Responsive-2.4.1/js/dataTables.responsive.min.js"></script>-->
<script src="../resources/util/Select-1.6.2/js/dataTables.select.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

	<title>Approval</title>


<script type="text/javascript">
	

$(document).ready(function(){





	$('#skuanalysis_d').css('display','none');
		$('#deptAnalysis_data').css('display','none');
	$('#emplist_page').css('display','block');
	$('#dataApprovereturn').css('display','block');
	
	var type_noo='<?php   echo $type_noo; ?>';
	var doc_data='<?php echo $doc_data;  ?>';

	$.ajax({
					url:'../DataStore/server3.php',
					method:'POST',
					data:{staff_return_list:1,doc_data:doc_data,type_noo:type_noo},
					dataType:'JSON',
					success:function(retrunemployee){
							$('#empreturnData').DataTable().destroy();
								var table=$('#empreturnData').DataTable({
										data:retrunemployee,
										columns:[
											{data:'staffid'},
											{data:'employee_name'},
											{data:'dept'},
											{data:'cadre'},
											{data:'location'},
											{data:'qty'},
											{data:'status'}

									]


							});


						}






});








	$('#skuanalysis_d').css('display','none');
		$('#deptAnalysis_data').css('display','block');
	$('#emplist_page').css('display','none');
	$('#dataApprovereturn').css('display','block');
	var location='<?php //echo $location;  ?>';
	var doc_data='<?php echo $doc_data;  ?>';
//alert(doc_data);
	$.ajax({
					url:'../DataStore/server3.php',
					method:'POST',
					data:{staff_return_list_dept:1,location:location,doc_data:doc_data},
					dataType:'JSON',
					success:function(retrunemployee){
							$('#deptAnalysis_pagetable').DataTable().destroy();
								var table=$('#deptAnalysis_pagetable').DataTable({
										data:retrunemployee,
										columns:[
											{data:'Dept'},
											//{data:'cadre'},
											{data:'Quantity'}
											//{data:'status'}

									]


							});


						}






});



	$('#skuanalysis_d').css('display','block');
		$('#deptAnalysis_data').css('display','none');
	$('#emplist_page').css('display','none');
	$('#dataApprovereturn').css('display','block');
	//alert("Yes, I am here");
	var location='<?php // echo $location; ?>';
	var doc_data='<?php echo $doc_data;  ?>';

	$.ajax({



						url:'../DataStore/server3.php',
					method:'POST',
					data:{staff_return_list_sku:1,location:location,doc_data:doc_data},
					dataType:'JSON',
					success:function(retrunemployee){
							$('#skuanalysis_table').DataTable().destroy();
								var table=$('#skuanalysis_table').DataTable({
										data:retrunemployee,
										columns:[
											{data:'flavour'},
											{data:'Quantity'}
										

									]


							});


						}

	});




$('#approve_return').click(function(){


if(confirm("You are about to approve the return of some quanntities of "+'<?php echo $type_noo?>'+"  that was not collected by staffs for this month")){
				var type='<?php  echo $type_noo; ?>';
				var doc_data='<?php echo $doc_data;  ?>';
		$.ajax({

					url:'../DataStore/server3.php',
					method:'POST',
					data:{Approve_return_data:1,location:location,doc_data:doc_data,type:type},
					dataType:'JSON',
					success:function(Approve_return_data_r){
						alert(Approve_return_data_r);
						location.reload()
							
						}

	});




			
}else{
	alert("This process has been aborted");
}


});

$('#diable_return').click(function(){
if(confirm("You are about to reject the return of some quanntities of monthly products that was not collected by staffs for this month")){
		

		$.ajax({

					url:'../DataStore/server3.php',
					method:'POST',
					data:{reject_return_data:1,location:location},
					dataType:'JSON',
					success:function(reject_return_data_r){
						alert(reject_return_data_r);
							
						}

	});




}else{
	alert("This process has been aborted");
}
});




});






</script>


</head>


<!--ApprovalPage-->
<section class="container" style="margin-top: 20px;">
	<div class="col-md-6 m-auto col-sm-6 m-auto col-xs-6 col-lg-8 m-auto">

<label style="font-size:40px; font-weight: bold; color:; margin-left: 40px; margin-bottom: 20px; font-family:Acme;">DISTRIBUTION RETURN APPROVAL</label>

<div>


<!-- Start of the table -->


<!-- Reason Popup -->






		




		<div class="row">
	<hr>	
			<div class="col-sm-10 mt-3" style="font-weight: bolder;" id="dtat">
		
				Sir/Ma,<div>
					<?php echo $type_noo;?> Noodle distribution for the month of <?php echo date('M-Y');?> has been completed. Below are reports of products that are not collected and due for return. Kindly approve or disapprove.


			</div>
		

		
		</div>		 


		<div class="row">
			<hr>
			<div class="col-sm-12">
				<label style="font-size: 20px; font-weight: bolder;">Analysis by Staff Details</label>
				<table class="table" class="display" style="width:100%;" id="empreturnData"><thead class="alert alert-danger">
					<th>StaffID</th>
					<th>Employee Name</th>
					<th>Dept</th>
					<th>Cadre</th>
					<th>Location</th>
					<th>Qty</th>
					<th>Status</th>

				</thead>

			</table>
			</div>

		</div>







<div class="row">
			<div class="col-sm-12">
				<hr>
				<label style="font-size: 20px; font-weight: bolder;">Analysis by Department</label>
				<table class="table" class="display" style="width:100%;" id="deptAnalysis_pagetable">
				
				<thead class="alert alert-danger">
					<th>Dept</th>
					<th>Quantity</th>
				<!--	<th>Cadre</th>
					<th>Return</th>-->
				</thead>

			</table>
			</div>

		</div>






<div class="row">
			<div class="col-sm-12">
				<hr>
				<label style="font-size: 20px; font-weight: bolder;">Analysis by SKU</label>
				<table class="table" class="display" style="width:100%;" id="skuanalysis_table">
				
				<thead class="alert alert-danger">
					<th>Flavour</th>
					<th>Quantity</th>
					

				</thead>

			</table>
			</div>

		</div>


<div class="row mt-3">
	<div class="">
		<button class="btn btn-primary" id="approve_return">Approve</button>
		<button class="btn btn-danger" id="diable_return">Reject</button>
	</div>
</div>
</html>










