	<?php 



	include("../datastore/db.php");?>

		<?php

			if(isset($_GET['lambda'])){
				$use_id_data=$_GET['lambda'];
			
				$use_id_data_d=base64_decode($use_id_data);
				$ind=explode('-lad-', $use_id_data_d);

				$id=$ind[1];
				$mail_id=$ind[0];

				$month_data=date("m");
				$year_data=date("Y");

				//echo $use_id_data_d."</br>";
				//echo $id;
		//$monthData="10-2023";

		//$d=explode('-',$monthData);
		//echo $d[0]."<br>";
		//echo $d[1]."<br>";
		//echo base64_decode('dHVuZGUuYWZvbGFiaUBkdWZpbC5jb20tbGFkLVRlbXAtMjAyMy0xMC0wNDU3MDE=');

$check_lock=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[approval_table] where approval_id='$mail_id' and document_id='$id' and month='$month_data' and year='$year_data'");
$check_lo=sqlsrv_fetch_array($check_lock,SQLSRV_FETCH_ASSOC);
$chj_loc=$check_lo['location'];
$docID=$check_lo['document_id'];

$get_user_name=sqlsrv_query($db_conn,"SELECT * FROM  [distribution].[dbo].[login] where email='$mail_id'");
$get_user_name_q=sqlsrv_fetch_array($get_user_name,SQLSRV_FETCH_ASSOC);
$staff_name=$get_user_name_q['Fullname'];


		$get_month_doc_details=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[upload_file_data] where data_mon='$month_data' and location='$chj_loc' and data_year='$year_data' and id='$docID'");
    $get_month_doc_details_d=sqlsrv_fetch_array($get_month_doc_details,SQLSRV_FETCH_ASSOC);

    $doc_month=$get_month_doc_details_d['data_mon'];
    $doc_year=$get_month_doc_details_d['data_year'];
    $doc_id_data=$get_month_doc_details_d['id'];
    $type_appr=$get_month_doc_details_d['type'];
    //echo $type_appr;
    //echo $doc_id_data;
   // echo $doc_month."<br>";
    //echo $doc_year."<br>";
    //echo $doc_id_data."<br>";



	//$get_previous=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[approval_table] where document_id='$id' and approval_id='$mail_id' and month='$month_data' and year='$year_data' and (close_status is not null and close_date is not null) or (close_status != 'Rejected') ");

//	$get_previous_count=sqlsrv_has_rows($get_previous);
	//if($get_previous_count>0){

	//}


				//where aggre_month='$month_data' and year='$year_data'
				//where month='$month_data' and year='$year_data'
				//$id_=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[aggregate_data_upload] ");
				//$id_all=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list]");

				//$staff_all=array();

				//$staff_aggre=array();
				//$n=1;
							
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
$('#employeeRecord').click(function(){
		$('#buttonApp').css('display','block');
		$('#alltabframe').css('display','none');
		$('#dtat').css('display','none');
		$('#skuDetails_table_view').css('display','none');
		var id= '<?php echo $id; ?>';
		

			$.ajax({
				url:'../DataStore/server2.php',
				method:'POST',
				data:{employeeRecord:1,id:id},
				dataType:'JSON',
				success:function(aggre){
					$('#viewapprovalData').css('display','block');
					$('#empltable').DataTable().destroy();
					var table = $('#empltable').DataTable({
						data:aggre,
						columns:[
							{data:'staffid'},
							{data:'cadre'},
							{data:'qty'},
							{data:'month'},

							]

					});

				}

			});



});






		$('#aggregate_pull').click(function(){
			$('#buttonApp').css('display','block');
			$('#viewapprovalData').css('display','none');
			$('#dtat').css('display','none');
			$('#skuDetails_table_view').css('display','none');
			var id_rg= '<?php echo $id; ?>';
			//var id= 
			
			$.ajax({
				url:'../DataStore/server2.php',
				method:'POST',
				data:{aggregate_data:1,id_rg:id_rg},
				dataType:'JSON',
				success:function(aggre){
					$('#alltabframe').css('display','block');
//console.log(aggre);
					if ($.fn.DataTable.isDataTable('#alltab')) {
       				 $('#alltab').DataTable().destroy();
   						}
				//	$('#alltab').dataTable().destroy();
					var table = $('#alltab').dataTable({
						data:aggre,
						columns:[
							{data:'aggre_cadre'},
							{data:'aggre_qty'},
							{data:'aggre_total_count'},
							{data:'aggre_month'},
							{data:'aggre_year'}

							]

					});

				}

			});


});




		$('#skuDetails').click(function(){
			$('#buttonApp').css('display','block');
			$('#viewapprovalData').css('display','none');
			$('#alltabframe').css('display','none');
			$('#dtat').css('display','none');
			$('#skuDetails_table_view').css('display','none');
			var id_rg= '<?php echo $id; ?>';
			//var id= 
			
			$.ajax({
				url:'../DataStore/server2.php',
				method:'POST',
				data:{aggregate_data_skuDetails:1,id_rg:id_rg},
				dataType:'JSON',
				success:function(aggreSKU){
					$('#skuDetails_table_view').css('display','block');
console.log(aggreSKU);
					if ($.fn.DataTable.isDataTable('#skuDetails_table')) {
       				 $('#skuDetails_table').DataTable().destroy();
   						}
				//	$('#alltab').dataTable().destroy();
					var table = $('#skuDetails_table').dataTable({
						data:aggreSKU,
						columns:[
							{data:'cadre'},
							{data:'flavour'},
							{data:'total_flavour_qty'}
							]

					});

				}

			});


});








//approval page


$('#approvedata').click(function(){


if(confirm("You are about to approve this transaction,If you are, click OK button, else use the cancel button")){
	//alert("Yes I will approve it for you");


		var app_id='<?php echo $id; ?>';
		var app_mail='<?php echo $mail_id; ?>';
		var type_data='<?php echo $type_appr; ?>';
		var chj_loc='<?php echo $chj_loc; ?>';

		//alert(chj_loc);
		//alert(app_mail);

		$.ajax({
				url:'../DataStore/server2.php',
				method:'POST',
				data:{approveNoodles:1,app_id:app_id,app_mail:app_mail,type_data:type_data,chj_loc:chj_loc},
				dataType:'JSON',
				success:function(appNoodles){
					//alert("Not yet");
					alert(appNoodles);
					location.reload();

				}



		});




}else{
	alert("I am not approving anything");
}

});




$('#rejectdata').click(function(){


if(confirm("You are about to reject this transaction,If you are, click OK button, else use the cancel button")){
	

	$('#reasonData').dialog({
		title:'Reason for rejection',
		resizable:false,
		height:330,
		width:250,
		modal: true,
		dialogClass: 'close',
		//autoOpen:false,
		closeOnEscape: false,
   
		//color:gold


		buttons:{

				'Confirm':function () {

				var app_id='<?php echo $id; ?>';
				var app_mail='<?php echo $mail_id; ?>';
				var reasonforrejection=$('#rejectionreason').val();
				//alert(reasonforrejection);

					$.ajax({
    			url:'../DataStore/server2.php',
    			method:'POST',
    			data:{rejectNoodles:1,app_id:app_id,app_mail:app_mail,reasonforrejection:reasonforrejection},
    			dataType:'JSON',
    			success:function(update_reject){
    				alert(update_reject);
    				location.reload();

					
    				//$('#Tag').html(ind.tagnumber);
    				//$('#num').html(ind.num);
    				//$('#type').html(ind.typeofuser);    				





    			}
    				

    		});
    		}


    		



		}
		
    		});










}else{
	alert("I am not reject anything");
}

});





	});

</script>


</head>


<!--ApprovalPage-->
<section class="container" style="margin-top: 20px;">
	<div class="col-md-6 m-auto col-sm-6 m-auto col-xs-6 col-lg-8 m-auto">

<label style="font-size:40px; font-weight: bold; color:; margin-left: 40px; margin-bottom: 20px; font-family:Acme;">PRODUCT DISTRIBUTION APPROVAL</label>

<div>


<!-- Start of the table -->

<div class="row">
	<div class="col-sm-12">
<ul class="nav nav-tabs">
  <li class="nav-item">

    <a class="nav-link active" aria-current="page" href="#" id="aggregate_pull">Aggregate</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#"  id="employeeRecord">Employee Record</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#"  id="skuDetails">SKU Analysis</a>
  </li>
  
</ul>

</div>
</div>

<div class="row mt-3" id="viewapprovalData"  style="display:none;">

<table class="table" id="empltable" class="display" style="width:100%">
<thead style="background-color:black; color:white;">
	
	<td>EMPLOYEE ID</td>
	<td>CADRE</td>
	<td>QTY</td>
	<td>MONTH</td>



</thead>

</table>
</div>
<div class="row mt-3" id="alltabframe" style="display:none;" >

<table class="table" id="alltab" style="width:100%" class="display" style="display:none;">
		<thead style="background-color:brown;color: white; font-size: 12px;">
			<th>Cadre</th>
			<th>Cadre Num</th>
			<th>Total Count</th>
			<th>Month</th>
			<th>Year</th>
		</thead>
	
</table>
</div>
</div>



<div class="row mt-3" id="skuDetails_table_view" style="display:none;" >

<table class="table" id="skuDetails_table" style="width:100%" class="display" style="display:none;">
		<thead style="background-color:brown;color: white; font-size: 12px;">
			<th>CADRE</th>
			<th>SKU</th>
			<th>QTY</th>
			
		</thead>
	
</table>
</div>



<div class="row" style="display:none;" id="buttonApp">
	<div class="col-sm-5 float-left mt-3">
		<button class="btn btn-danger" id="rejectdata">Reject</button>
		<button class="btn btn-primary" id="approvedata">Approve</button>
	</div>
	
	
</div>
<!-- Reason Popup -->




<section  id="reasonData" style="display: none;">
	<div class="col-sm-6 ">
		<textarea cols="col-sm-6 form-control" rows="8" cols="" id="rejectionreason" ></textarea>
		
				

	</div>
	
</section>

		




		<div class="row">

			<div class="col-sm-10 mt-3" style="font-weight: bolder;" id="dtat">
				
				Dear <?php echo $staff_name.",<br>";  ?><?php  echo  $type_appr;?>  Noodle distribution for the month of <?php echo date('M-Y');?> has been uploaded, and all required calculations has been done. Above are buttons to view Employee details uploaded for the month of  <?php echo date('M-Y');?> and the aggregate calculations by cadre


			</div>
		

		
		</div>		 
</html>

