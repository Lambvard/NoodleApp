<?php 

include('../DataStore/db.php');
session_start();

if (!isset($_SESSION['user']) && !isset($_SESSION['location'])) {
    header("Location: ../index.php");
    exit(); // It's a good practice to add an exit after a header redirect
} 
//$loca=$_SESSION['location'];
//echo $loca;
$hr="HR/ADMIN";
$whs="WHSE";
$stat="uploaded";
$location=$_SESSION['location'];

$data=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.upload_file_data where location='$location'");
$st=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.upload_file_data where Status='$stat' and location='$location'");

//$data_HR=sqlsrv_query($db_conn,"SELECT * from [IOU].[dbo].[Staffdetailsvmsold] where Dept='HR/ADMIN' ");

//echo $loca;
 ?>

<!DOCTYPE html>
<html>
<head>
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
<script src="../resources/util/pdfmake-0.2.7/pdfmake.min.js"></script>
<script src="../resources/util/pdfmake-0.2.7/vfs_fonts.js"></script>
<script src="../resources/util/DataTables-1.13.4/js/jquery.dataTables.min.js"></script>
<script src="../resources/util/AutoFill-2.5.3/js/dataTables.autoFill.min.js"></script>
<script src="../resources/util/Buttons-2.3.6/js/dataTables.buttons.min.js"></script>
<script src="../resources/util/Buttons-2.3.6/js/buttons.html5.min.js"></script>
<script src="../resources/util/Buttons-2.3.6/js/buttons.print.min.js"></script>
<script src="../resources/util/DateTime-1.4.1/js/dataTables.dateTime.min.js"></script>
<script src="../resources/util/FixedColumns-4.2.2/js/dataTables.fixedColumns.min.js"></script>
<script src="../resources/util/Responsive-2.4.1/js/dataTables.responsive.min.js"></script>
<script src="../resources/util/Select-1.6.2/js/dataTables.select.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<title></title>

	<script type="text/javascript">
		$(document).ready(function(){

			var table= $('#stada').dataTable();

			//setTimeout(table.ajax.reload(),200);

			$('#saveupload').submit(function(e){

				//alert("I am here");
				e.preventDefault();
				var formDataD= new FormData(this);
				formDataD.append('uploadData',1);

				var mon=$('#mon').val();
				var type=$('#type').val();
				var file=$('#fileuploaddata').val();

				if(mon=="" || type=="" || file==""){
					alert("All fields are required");
				}else{

					//alert("I got to this place");
						$('#saveupload').attr('disabled',true);
				$.ajax({
					url:"../DataStore/server.php",
					method:"POST",
					data:formDataD,
					//DataType:'JSON',
					processData: false,
          			contentType: false,
          			cache: false,
					success:function(evdse){
						//alert("Yes I am here");
						alert(evdse);
						if(evdse=="Data Uploaded Successfully"){
							$('#filesave').attr('disabled',true);
							$('#aprovalpane').css('display','block');
							location.reload();
						}else{
							$('#filesave').css('display','block');
						}
						
						
					}
				});

			}
			});

			$('.Data').click(function(){
				var id=$('.data_cat').val();
				

				$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{delete_data:1,staff_id:staff_id,id:id},
					DataType:'JSON',
					success:function(ves){
						alert(ves);

					}
				});
			});



			$('.dataView').click(function(){
				var dataView=$('.data_cat').val();
				$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{viewDataView:1,dataView:dataView},
					DataType:'JSON',
					success:function(ves){
						alert(ves);

					}
				});

			});



			$('#ApproveData').click(function(){
				var hr_data=$('#hr').val();
				var whse_data=$('#whse').val();
				var month=$('#mon_app').val();

				$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{app_data:1,hr_data:hr_data,whse_data:whse_data,month:month},
					DataType:'JSON',
					success:function(dfdf){
						alert(dfdf);
						location.reload();

					}
				});
			});

			

			$('.individual_item_delete').click(function(){
				var del_id_data=$(this).val();
				//alert(del_id_data);
				if(confirm("You are about to delete a previously uploaded data, Are you sure of this?")){
					$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{individual_item_delete_now:1,del_id_data:del_id_data},
					dataType:'JSON',
					success:function(delete_item){
						alert(delete_item);
						location.reload();

					}
				});

				}else{
					alert("I cannot proceed with this transaction. Thanks");
				}

			});

			$('.individual_item_download').click(function(){
			//	$('#tableallreportupload').dataTable().clear().destroy();
				$('#report_all').dialog({
				//resizable:false,
					title:'All Report for Download',
					height:600,
					width:1000,
					modal: true,
					dialogClass: 'no-close',
		//autoOpen:false,
					closeOnEscape: false,
				});

				var download_id_data=$(this).val();

				$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{individual_item_view_now:1,download_id_data:download_id_data},
					dataType:'JSON',
					success:function(download_item){
						$('#tableallreportupload').DataTable().destroy();
						var table=$('#tableallreportupload').dataTable({

						scrollY: '50vh',
       					scrollCollapse: true,
        				paging: false,
						dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],
							data:download_item,
							columns:[
								{data:'staffid'},
								{data:'employee_name'},
								{data:'dept'},
								{data:'cadre'},
								{data:'type'},
								{data:'qty'},
								{data:'month'},
								{data:'date'}


								]


						});


						
					}
				});


				
			});

			$('.individual_item_view').click(function(){
				var del_id_data=$(this).val();
				alert(del_id_data);
				alert("View the Item");
			});

		});
	</script>
</head>
<body>


<section class="container-fluid">
	<div class="row">
		<div class="col-sm-12 mt-5 float-end">
		<label style="margin-top: 20px; font-size: 40px; font-weight: bolder; color: black;" class="text text-">Monthly Product Upload</label>
			<div class="row">
			<a href="../Template/monthlynoodlestemplate.xlsx" download>	<label style="font-weight: 30px;">Download Template</label></a>

			</div>

			<hr>
			<div class="row">
			<div class="col-sm-5">
				<form id="saveupload" enctype="multipart/form-data" >
				<div class="">
					<label style="font-weight:bolder; font-size: 16px;">Select Month</label>
					<input type="month" name="mon" id="mon" class="form-control" required>
				</div>
				
				<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;">Type</label>
					<select class="form-control" id="type" name="type" required>
						<option></option>
						<option>Monthly</option>
						<option>End of Year Product</option>
					</select>
				</div>
				<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;" >Upload (remember to use exact template)</label>
					<input type="file" name="fileuploaddata" id="fileuploaddata" class="form-control" required>
				</div>
				<div class="">
					
					<input type="submit" name="filesave" id="filesave" class="btn btn-primary float-end mt-3" value="Save" required>
				</div>
			</form>
			</div>
			<div class="col-sm-5">
		<label style="font-weight:bold; font-size:15px;">Current month status</label>
		<table class="table table-striped" id="stada" style="font-size:12px;">
			<thead class="alert alert-danger" style="color:black;"><tr><td>Status</td><td>Type</td><td>Date</td><td>Month</td><td>Delete</td><td>View</td></tr></thead>

			<?php

				while ($df=sqlsrv_fetch_array($data,SQLSRV_FETCH_ASSOC)) {
					echo '<tbody><tr><td>'.$df['Status'].'</td><td>'.$df['type'].'</td><td>'.$df['data_date'].'</td><td>'.$df['data_mon'].'</td>
					<td><button class="individual_item_delete" value='.$df['id'].' style="font-size:11px; border:0px;background-color:red; border-radius: 1px 10px 1px 10px; color:white;"><i class="icofont-ui-delete"  style=""></i></button></td>
					<td><button class="individual_item_download" value='.$df['id'].' style="font-size:11px; border:0px;background-color:blue; border-radius: 1px 10px 1px 10px; color:white;"><i class="icofont-eye-open "  style=""></i></button></td>
					
					';




					
				}

//<td><button class="btn btn Data" id="del_id"><i class="icofont-ui-delete"><input type="hidden" class="data_del" value="'.$df['id'].'"></i></button></td>

//<td><button class="individual_item_view" value='.$df['id'].' style="font-size:11px; border:0px;background-color:green; border-radius: 1px 10px 1px 10px; color:white;;"><i class="icofont-eye-open "  style=""></i></button></td>


//<td><button class="btn btn"><i class="icofont-cloud-download"><input type="hidden" class="data_cat" value="'.$df['id'].'"></i></button></td>
//<td><button class="btn btn dataView"><i class="icofont-eye-open"><input type="hidden" class="data_cat" value="'.$df['id'].'"></i></button></td></tr></tbody>
			?>
		</table>


		
	</div>
		




		</div>

	</div>


<section  id="report_all" style="display:none ;">
	<div class="col-sm-12 ">

		<table class="table" class="display" style="width:100%" id="tableallreportupload">
			<thead>
				<th>STAFFID</th>
				<th>FULLNAME</th>
				<th>DEPT</th>
				<th>CADRE</th>
				<th>TRANSACTION</th>
				<th>QTY</th>
				<th>MONTH</th>
				<th>DATE</th>
			
			</thead>
		</table>
		
				

	</div>
	
</section>


	



	
</section>

</body>
</html>