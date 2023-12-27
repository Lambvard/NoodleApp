<?php
include('../DataStore/db.php');

//session_start();
//if(!isset($_SESSION['user_id'])){
	//header("Location: ../../index.php?id=invalidlogin");
//}
//echo "I will be printing here for all transactions";

//$locate_track__=$_SESSION['locate_track'];
//$locate_track__=$_SESSION['locate_track'];
//$act="Activate";
//echo $locate_track__;

//$nowdate=Date("Y-m-d");
//$get_staff_data=sqlsrv_query($db_connection,"SELECT * FROM IOU.dbo.staffdetails where stafflocation='$locate_track__' and cur_status='$act'");

//echo $locate_track__;	
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
<link rel="../stylesheet" type="text/css" href="../resources/ico/icofont.css">
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

<style type="text/css">
	
	.ui-dialog-titlebar-close {
   display: none;
}
</style>


    <script type="text/javascript">
    	$(document).ready(function(){
    			setTimeout(reload_status,100);
		setTimeout(
		$('#reporttable').DataTable({

			scrollY: '50vh',
        scrollCollapse: true,
        paging: false,
			dom: 'Blfrtip',
        buttons: [
            {extend:'copy',className:'btn-danger'},
            {extend:'csv',className:'btn-info'},
            {extend:'excel',className:'btn-secondary'},
            {extend:'pdf',className:'btn-primary'},
        ]
		})
		,100);
    		


		function reload_status(){
				var loc_idEmp='<?php echo "surulere"; ?>';
			$.ajax({
    			url:'../DataStore/server2.php',
    			method:'POST',
    			data:{total_visitor:1,loc_idEmp:loc_idEmp},
    			dataType:'JSON',
    			success:function(evds){
    				//alert(evds);
    				$('#totalData').html(evds.allEmployee);
    				$('#totalIssued').html(evds.IssuedEmployee);
    				$('#totalout').html(evds.checkoutEmployee);
    				
    			},
    				complete:function(){
    					setTimeout(reload_status,10000);
    				}

    		});

		}








$('.dataneed').on('click',function(){
	var staf_employ=$(this).val();
//alert(sele);
	$.ajax({
    			url:'../DataStore/server2.php',
    			method:'POST',
    			data:{getStaffInfo_sec:1,staf_employ:staf_employ},
    			dataType:'JSON',
    			success:function(staf_employ_fed){
    			//	alert(staf_employ_fed);

    				if(staf_employ_fed.buttonData=="checkout"){

    				}

	   				$('#empissue').html(staf_employ_fed.id_issued);
    				$('#fullnameissue').html(staf_employ_fed.name_issued);
    				$('#deptissue').html(staf_employ_fed.dept_issued);
    				$('#locissue').html(staf_employ_fed.location_issued);
    				$('#dateissue').html(staf_employ_fed.date_issued);
    				$('#qtyissue').html(staf_employ_fed.qty_issued);
    				//alert(ind.whoto);
    				//alert(ind.status);
    				



  //  				$('#idfind').val(sele);
	//alert(sele);

	$('#diag').dialog({
		resizable:false,
		height:500,
		width:650,
		modal: true,
		dialogClass: 'no-close',
		//autoOpen:false,
		closeOnEscape: false,
   
		//color:gold


		buttons:{

				'Check out':function () {

			var id_id=$('#empissue').html();
			//alert(id_id);

					$.ajax({
    			url:'../DataStore/server2.php',
    			method:'POST',
    			data:{updateData_use:1,id_id:id_id},
    			dataType:'JSON',
    			success:function(update_result){
    				alert(update_result);
    				location.reload();

					
    				//$('#Tag').html(ind.tagnumber);
    				//$('#num').html(ind.num);
    				//$('#type').html(ind.typeofuser);    				





    			}
    				

    		});
    		},


    		close:function () {
				//e.preventDefault();
				$('#approver').html("");
    			$('#status').html("");
    			$('#tapprover').html("");
    			$('#timerin').html("");
    			$('#timerout').html("");
    				$('#diag').dialog("destroy");
			}
	



		}
		
    		});












}







	});
	




	
});
/*

$('#chng').click(function(){
	var sd=$('#idfind').val();
	alert(sd);


		$.ajax({
    			url:'../Data/server2.php',
    			method:'POST',
    			data:{appvIn:1,sd:sd},
    			dataType:'JSON',
    			success:function(appv){
    				alert(appv);
    				
    			}
    				

    		});


});





















			}

*/



    		
    	});
    </script>

</head>
<body >

<section class="container">
<div class="row" >


<div class="card alert alert-primary" style="width: 10rem;  margin-right: 10px;" id="">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Total</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:40px;" align="center" >
    	<span id="totalData"></span>
    </div>
  </div>
</div>


<div class="card alert alert-success" style="width: 10rem; margin-right: 10px;" id="Supplier_but">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Issued</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:40px;" align="center" >
    	<span id="totalIssued"></span>
    </div>
  </div>
</div>




<div class="card alert alert-secondary" style="width: 10rem;  margin-right: 10px;" id="contractor_but">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Exited</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:40px;" align="center" >
    	<span id="totalout"></span>
    </div>
  </div>
</div>





</div>
<i style="color:gold;">Real time Dashboard for Employee Noodle Distribution</i>
</section>
<section class="container-fluidn">

<div class="col-sm-10">

	<div class="mt-1" id="" style=" font-size: 12px; width: 100%; height: 500px; color: white;">
		
		<table class="table table-hover" id="reporttable" style="width:100%; color: white;">
			<thead style="background-color:gold; color: black; font-size: 9px;"><td>SN</td><td>EMPLOYEE NAME</td><td>EMPLOYEE ID</td><td>CADRE</td><td>QTY</td><td>LOCATION</td><td>MONTH</td><td>DAY</td><td>DATE</td><td>YEAR</td><td>STATUS</td><td>ALL STATUS</td><td>COLLECTION DATE</td><td>OUT DATE</td><td>View</td></thead>

			<?php 
			$date_g=date("Y-m-d");
			$use_mon=date('m');
			$use_year=date('Y');
			//dater ='$date_g'and
			$d_f=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[uploaded_staff_list] where  location='SURULERE' and status in('Issued','checkout') and month='$use_mon' and year='$use_year'");
			
			$c=1;
			while($d=sqlsrv_fetch_array($d_f,SQLSRV_FETCH_ASSOC)){
				echo '<tr style="background-color:white; color:black; font-size:10px; white-space:wrap;"><td>'.$c.'</td><td>'.$d['employee_name'].'</td><td>'.$d['staffid'].'</td><td>'.$d['cadre'].'</td><td>'.$d['qty'].'</td><td>'.$d['location'].'</td><td>'.$d['month'].'</td><td>'.$d['day'].'</td><td>'.$d['date'].'</td><td>'.$d['year'].'</td><td>'.$d['status'].'</td><td>'.$d['all_status'].'</td><td>'.$d['issue_date'].'</td><td>'.$d['out_date'].'</td><td><button class="dataneed" value='.$d['staffid'].' style="background-color:brown; color:white;"><i class="icofont-eye-open "  style=""></i></button></td></tr>';

				$c=$c+1;
			}
//<a href="#"><i class="icofont-check-circled" style="color:red; font-size:18px;"></i></a>

//data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo"
			//<i class="icofont-eye-open "  style="color:gold; font-size: 20px;"></i>
			?>


		</table>


	</div>
	


</div>





<div class="container">
	
<!-- Start of modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="row">
       	<table class="table table-hover">
       		<tr><td>Name</td><td><? echo "Name Here";?></td></tr>
       		<tr><td>Total Entry as Visitor</td><td><? echo "Count of entry as visitor here";?></td></tr>
       		<tr><td>Total Entry as Supplier</td><td><? echo "Count of entry as Supplier here";?></td></tr>
       		<tr><td>Total Entry as Contactor</td><td><? echo "Count of entry as Contractor here";?></td></tr>
       		<tr><td>Total hour spent as visitor</td><td><? echo "Total hour spend as visitor";?></td></tr>
       		<tr><td>Total hour spent as contractor</td><td><? echo "Total hour spend as contractor";?></td></tr>
       		<tr><td>Total hour spent as Supplier</td><td><? echo "Total hour spend as Supplier";?></td></tr>
       		<tr><td>Last Appearance Date</td><td><? echo "Last Appearance Date";?></td></tr>
       		<tr><td>Last Host</td><td><? echo "Last host";?></td></tr>
       	
       	</table>
       </div>
      </div>
      <div class="modal-footer">
<!--        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>

<!-- End of modal-->



</div>



<!-- Dialog -->
<section class="col-sm-8">
	
<div class="" id="diag" style="color: gold; display: none;" title="Noodle Issuance Details">
<div class="col-sm-8">
			



<table class="table col-sm-8" style="z-index:1; background-color: rgba(255, 255, 255, 0.8);">
	<tr><td></td><td><input type="hidden" class="" id="idfind"></td></tr>
	<tr><td>EMPLOYEE ID:</td><td><span  id="empissue"></span></td></tr>
	<tr><td>FULLNAME:</td><td><span id="fullnameissue"></span></td></tr>
	<tr><td>DEPT:</td><td><span  id="deptissue"></span></td></tr>
	<tr><td>LOCATION:</td><td><span  id="locissue"></span></td></tr>
	<tr><td>DATE ISSUED:</td><td><span id="dateissue"></span></td></tr>
	<tr><td>QTY:</td><td><span id="qtyissue"></span></td></tr>
	<tr>
	
	
</table>








		</div>



</div>

</section>
<!-- Dialog -->

</section>

</body>
</html>