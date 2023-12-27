<?php 

session_start(); 
if (!isset($_SESSION['user']) && !isset($_SESSION['location'])) {
    header("Location: ../index.php");
    exit(); // It's a good practice to add an exit after a header redirect
} 


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
	<title></title>

	<script type="text/javascript">
		$(document).ready(function(){
			//$('#').dataTable();
			$('#but_select').on('click',function(){
				var monD=$('#mon_select').val();
				var type=$('#type_select').val();
				//alert(monD);
				//alert(type);


					$.ajax({

				url:'../DataStore/server2.php',
					method:'POST',
					data:{fetchstafflist:1,monD:monD,type:type},
					dataType:'JSON',
					success:function(fetch_all_staff_id){

				if ($.fn.DataTable.isDataTable('#repDataview')) {
       				 $('#repDataview').DataTable().destroy();
   						}


			
						//alert(fetch_all_staff_id);
				var table=$('#repDataview').dataTable({
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
		data:fetch_all_staff_id,
        columns:[
        					{data:'staffid'},
        					{data:'employee_name'},
        					{data:'dept'},
        					{data:'cadre'},
        					{data:'qty'},
        					{data:'month'},
        					{data:'type'},
        					{data:'status'},
        					{data:'issue_date'},
        					{data:'out_date'},

        	]

						});
						//$('#reporttabledata tbody').html(fetch_all_staff_id);
					
						
				}
			});


		//$('#rep').dataTable();








			});



		
		//$('#rep').DataTable({

		
		//});

			
		});
	</script>
</head>
<body>
<section class="container-fluid">
	<div class="row">

		<div class="col-sm-12 mt-2">
			<label style="margin-top: 20px; font-size: 40px; font-weight: bolder; color: black;" class="text text-">Distribution Status Report</label>

			<div>
		
		<div class="row ">
			<div class="col-sm-3">
				<label style="font-weight: bolder;">Select Month</label>
			<input type="month" class="col-sm-3 form-control" style="margin-bottom: 10px;" id="mon_select"></div>
						<div class="col-sm-3">
							<label style="font-weight: bolder;">Select Transaction Type</label>
			<select  class="col-sm-3 form-control" style="margin-bottom: 10px;" id="type_select">
				<option>Monthly</option>
				<option>End of Year Product</option>
			</select></div>


			<div class="col-sm-2">
							<label style="font-weight: bolder;"></label>
			<button  class="col-sm-2 form-control btn btn-primary" id="but_select">View</button></div>
		<!--	<select  class="col-sm-3 form-control" style="margin-bottom: 10px;" id="type_select">
				<option></option>
			</select>
			-->
		</div>

	</div>
	<div class="" id="" style="height: 600px;">
			<table class="table" id="repDataview" class="display" style="width:100%;">
				<thead class="alert" style="background-color: brown; color: white;"><td>StaffID</td><td>Employee Name</td><td>Dept</td><td>Cadre</td><td>Qty</td><td>Month</td><td>Transaction</td><td>Status</td><td>Issued Date</td><td>Checkout Date</td></thead>

				<tbody></tbody>
				
			</table>
</div>
		</div>
	</div>
</section>


</body>
</html>