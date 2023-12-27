<?php 
session_start(); 
if (!isset($_SESSION['user']) && !isset($_SESSION['location'])) {
    header("Location: ../index.php");
    exit(); // It's a good practice to add an exit after a header redirect
} 

	include('../DataStore/db.php');
//	echo $_SESSION['user']."<br>";
	//echo $_SESSION['location']."<br>";
?>

<?php//echo $_SESSION['user'];?>
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
<link rel="stylesheet" type="text/css" href="../resources/ico/icofont.css">
 
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
	<title>Dashboard</title>

	<script type="text/javascript">
		$(document).ready(function(){
			setTimeout(reload_Dist_status,100);
			//alert("Yes");
			$('#Configuration').click(function(){
				//alert("Yes");
			$('#show').load('configuration.php');
			});
			$('#report').click(function(){
			$('#show').load('report.php');
			});
			$('#Upload').click(function(){
				$('#show').load('upload.php');
			});
			$('#home').click(function(){
				location.reload();
			});
			$('#apprv').click(function(){
				$('#show').load('Sendforapproval.php');
			});
			$('#logout').click(function(){
				

				$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{logout_id:1},
					dataType:'JSON',
					success:function(ev){
						alert(ev);
						window.location.href='../index.php';
					}
				});
			});


		
/*
			setTimeout($('#viewupdate').dataTable({
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

        columns:[

        			{}

        	]
			}),100);

*/
				var loc_front='<?php echo $_SESSION['location']; ?>';
			$.ajax({

					url:'../DataStore/server.php',
					method:'POST',
					data:{defualt_approval_list:1,loc_front:loc_front},
					dataType:'JSON',
					success:function(ev_b){
						//alert(ev_b);

						$('#viewupdate').DataTable().destroy();

			var table = $('#viewupdate').dataTable({
				
        data:ev_b,
        columns:[

        			{data:'app_id'},
        			{data:'approval_id'},
        			{data:'sent_date'},
        			{data:'month'},
        			{data:'type'},
        			{data:'close_status'},
        			{data:'close_date'}

        	]
			});


					}



			});














function reload_Dist_status(){
				var loc_id='<?php echo $_SESSION['location']; ?>';
				//alert(loc_id);
			$.ajax({
    			url:'../DataStore/server.php',
    			method:'POST',
    			data:{allrefresh:1,loc_id:loc_id},
    			dataType:'JSON',
    			success:function(evdsRe){
    				//alert(evdsRe);

    				$('#allsheet').html(evdsRe.allsheet);
    				$('#collectedsheet').html(evdsRe.collected);
    				$('#issued').html(evdsRe.issued);
    					console.log(evdsRe.allsheet/evdsRe.collected);
    				var perf=(evdsRe.issued/evdsRe.allsheet)*100;

    				$('#preft').html(parseFloat(perf).toFixed(2));
    				},
    				complete:function(){
    					setTimeout(reload_Dist_status,1000);
    				}

    		});

		}


		});
	</script>
</head>

<body style="background-image: url('../images/back1.jpg'); background-size: cover; font-size:12px;">
	<section class="container-fluid">
		
		<div class="row">
			
			<div class="col-sm-2" style="background-color:white; height: 755px;">
			
				<ul class="list-group">
			
				<div align="center">
<div class="row" align="center">
	<span style="font-weight:bolder; color:brown; font-size: 70px;"><i class="icofont-user"></i></span><br>
	<div align="center" style="font-weight:bolder;"><?php echo $_SESSION['Fullname']; ?></div>
</div>
<div class="list-group" style="margin-left: 1px;" align="center">
 
  <li class="list-group-item d-flex btn btn mt-2" style="color: brown; border:0px; border-radius:0px 0px 0p 0px;" id="home" style="border: 0px;color:black;"><i class="icofont-ui-home" style="font-size:30px;"></i>Dashboard</li>
  <li class="list-group-item d-flex btn btn mt-2" style="color: brown; border:0px; border-radius:0px 0px 0p 0px;" id="Configuration" style="border:0px; color: purple;"><i class="icofont-tools" style="font-size:30px;"></i>Configuration</li>
<li class="list-group-item d-flex btn btn mt-2" style="color: brown;  border:0px;border-radius:0px 0px 0p 0px;" id="Upload" style="border:0px; color:blue;"><i class="icofont-cloud-upload" style="font-size:30px;"></i>Upload</li>

  <li class="list-group-item d-flex btn btn mt-2" style="color: brown; border:0px;border-radius:0px 0px 0p 0px;" id="apprv" style="border:0px;color:gold;"><i class="icofont-loop" style="font-size:30px;"></i><label>Approval</label></li>
  <li class="list-group-item d-flex btn btn mt-2" style="color: brown;  border:0px;border-radius:0px 0px 0p 0px;" id="report" style="border:0px;color:green;"><i class="icofont-document-folder" style="font-size:30px; font-weight: bolder;"></i>Report</li>
  <li class="list-group-item d-flex btn btn mt-2" style="color: brown;  border:0px;border-radius:0px 0px 0p 0px;" id="logout" style="border:0px;color:red;"><i class="icofont-logout" style="font-size:30px;"></i>Logout</li>
</div>


			</ul>

		
</div>


	<div class="col-sm-10" id="show" style="background-color: rgba(0, 9, 28, 0.11);">
	<div class="row">
		<div class="col-sm-4 float-left" style="font-weight:bolder;color:red;font-size:18px;">
			<?php echo '<span ><i class="icofont-location-pin"></i></span>   '. $_SESSION['location']; ?>
		</div>
		<div class="col-sm-3" style="font-weight:bolder;color:blue;font-size:18px;"> <?php echo '<span ><i class="icofont-ui-timer"></i></span>   '.date("Y-m-d"); ?>	</div>
		<div class="col-sm-3">	</div>
		<div class="col-sm-2">	</div>
		
	</div>


		
		
<div class="mt-2">
		<div class="row mt-2" style="margin-left:10px; ">
<div class="card" style="width: 13rem;" style="margin-left: 5px;background-color: purple; ">
  <div class="card-body" align="center">
    <h5 class="card-title">Total Distribution</h5>
    <!--<h6 class="card-subtitle mb-2 text-muted"><i class="icofont-database-remove" style="font-size:100px;"></i></h6>-->
    <div >
      <span id="allsheet" style="font-size:30px; font-weight: bolder;">0</span>
    </div>
 
  </div>
</div>
<div class="card" style="width: 13rem;margin-left: 5px; background-color:greenyellow;">
  <div class="card-body" align="center">
    <h5 class="card-title">Current Month</h5>
    
   <!-- <h6 class="card-subtitle mb-2 text-muted"><i class="icofont-eye" style="font-size:100px;"></i></h6>-->
    <div style="font-size:30px; font-weight: bolder;">

    	<?php

    		echo date('M');
    	?>
   
    </div>
  </div>
</div>


<div class="card" style="width: 13rem;margin-left: 5px; background-color: aquamarine;">
  <div class="card-body" align="center">
    <h5 class="card-title">Pending Collection</h5>
    
   <!-- <h6 class="card-subtitle mb-2 text-muted"><i class="icofont-folder" style="font-size:100px;"></i></h6>-->
    <div >
   <span id="collectedsheet" style="font-size:30px; font-weight: bolder;">0</span>
    </div>
  </div>
</div>


<div class="card" style="width: 13rem;margin-left: 5px; background-color: gold;">
  <div class="card-body" align="center">
    <h5 class="card-title">Issued</h5>
    
   <!-- <h6 class="card-subtitle mb-2 text-muted"><i class="icofont-folder-open" style="font-size:100px;"></i></h6>-->
<div style="font-size:30px; font-weight: bolder;">
   	<span id="issued">0</span>

</div>

  </div>
</div>






<div class="card" style="width: 15rem;margin-left: 5px;">
  <div class="card-body" align="center">
    <h5 class="card-title">Completion</h5>
    
   <!-- <h6 class="card-subtitle mb-2 text-muted"><i class="icofont-folder-open" style="font-size:100px;"></i></h6>-->
<div style="font-size:30px; font-weight: bolder;">
   	<span id="preft">0</span>

</div>

  </div>
</div>



<!--
<div class="card" style="width: 15rem;margin-left: 5px;">
  <div class="card-body" align="center">
    <h5 class="card-title">Current Month</h5>
    
    <h6 class="card-subtitle mb-2 text-muted"><i class="icofont-folder-open" style="font-size:100px;"></i></h6>
  </div>
</div>
-->




</div>
<!-- Begining of the report -->
<section class="container-fluid mt-2">
	<table class="table col-sm-8" id="viewupdate" class="display" style="width:100%; font-size: 12px;">
		<thead style="background-color:brown; color: white;"><th>Department</th><th>Approver</th><th>Date</th><th>Month</th><th>Transaction</th><th>Status</th><th>Date</th></thead>
		
	</table>
</section>
<!-- End of the report -->		
		</div>



	</div>





</div>
</section>


</body>
</html>

