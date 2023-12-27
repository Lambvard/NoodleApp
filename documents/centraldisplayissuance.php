<?php session_start(); 
if (!isset($_SESSION['user']) && !isset($_SESSION['location'])) {
    header("Location: ../centraldistribution.php");
    exit();
  }
include("../datastore/db.php");

$location_issed=$_SESSION['location'];
$user_issued=$_SESSION['user'];
//var_dump($user_issued);
//var_dump($location_issed);
//echo $user_issued;
//echo $_SESSION['doc_id'];
$mon=date("m");
$yr=date("Y");
//$_SESSION['doc_id'];

$user=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[distribution_login] where staffid='$user_issued'");
$user_pick=sqlsrv_fetch_array($user,SQLSRV_FETCH_ASSOC);
$user_use=$user_pick['username'];

//"adeniji.adeniyi@dufil.com";
//$user_pick['username'];
$get_current_doc=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[upload_file_data] where location='$location_issed' and data_mon='$mon' and data_year='$yr' and  final_data in ('Processed','Start') ");
$get_current_doc_d=sqlsrv_fetch_array($get_current_doc,SQLSRV_FETCH_ASSOC);
//$test_data=$get_current_doc_d['final_data'];

//var_dump($get_current_doc_d);
if($get_current_doc_d==null){
$get_current_doc=sqlsrv_query($db_conn,"SELECT TOP 1 * FROM [distribution].[dbo].[upload_file_data] where location='$location_issed'  order by data_date DESC ");
$get_current_doc_d=sqlsrv_fetch_array($get_current_doc,SQLSRV_FETCH_ASSOC);
//and data_mon='$mon' and data_year='$yr' and  final_data ='End'

$gf=$get_current_doc_d['id'];
$type_noodles=$get_current_doc_d['type'];


}else{

$gf=$get_current_doc_d['id'];
$type_noodles=$get_current_doc_d['type'];
//$user_use=$user_pick['username'];
}



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
	<title>Dashboard</title>

	<script type="text/javascript">
		$(document).ready(function(){

				setTimeout(reload_status,100);


		function reload_status(){
				var loc_idEmp='<?php echo $location_issed; ?>';
				var id='<?php echo 	$gf; ?>';
	var type_noodles='<?php echo $type_noodles; ?>';
	//alert(id);
			$.ajax({
    			url:'../DataStore/server2.php',
    			method:'POST',
    			data:{count_issued:1,loc_idEmp:loc_idEmp,id:id,type_noodles:type_noodles},
    			dataType:'JSON',
    			success:function(evds_d){
    				//alert(evds);

    			
    				$('#totalData').html(evds_d.allEmployee);
    				$('#totalIssued').html(evds_d.IssuedEmployee);
    				$('#totalout').html(evds_d.checkoutEmployee);
    				$('#allscount').html(evds_d.countemployeeall);
    				$('#allscountissued').html(evds_d.totaliss);



    				
    				
    			},
    				complete:function(){
    					setTimeout(reload_status,5000);
    				}

    		});

		}










				$('#issueDatanow').css('display','block');
				$('#showtable').css('display','none');
				$('#displayall').css('display','none');
				$('#returnData').css('display','none');
	
			$('#issuereport').click(function(){
				//alert("Yes. I am checking");
				$('#issueDatanow').css('display','none');	

				$.ajax({
					url:'../DataStore/server2.php',
					method:'POST',
					data:{pullallreport:1},
					dataType:'JSON',
					success:function(dfv){
						//alert(dfv);	
						$('#reporttablecentral').DataTable().destroy();
									$('#showtable').css('display','block');		
											
						var table = $('#reporttablecentral').DataTable({

									dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],


									data:dfv,
									columns:[
											{data:'staffid'},
											{data:'employee_name'},
											{data:'cadre'},
											{data:'dept'},
											{data:'type'},
											{data:'qty'},
											{data:'month'},
											{data:'status'},
											{data:'all_status'},
											{data:'issue_date'},
											{data:'out_date'},
											{data:null,



											render: function (data, type, row) {
                return '<button class="view_item btn btn" value="'+row.staffid+'" style="background-color:green; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"></button>';
            }



										}

							]



						});

					}
				});
			});




				$('#issuedata').click(function(){
				$('#issueDatanow').css('display','block');
				$('#showtable').css('display','none');
				$('#displayall').css('display','none');
				$('#returnData').css('display','none');

			});







//view staff details


$('#viewdata').click(function(){
	var inputstaffid=$('#inputstaffid').val();
	var location_issed='<?php echo $location_issed; ?>';
	var id='<?php echo 	$gf; ?>';
	var type_noodles='<?php echo $type_noodles; ?>';

//alert(id);



	if(inputstaffid==""){
		alert("Enter a valid Employee ID");
	}else{

			$.ajax({
						url:'../DataStore/server3.php',
					method:'POST',
					data:{userDatafordistribution:1,inputstaffid:inputstaffid,location_issed:location_issed,id:id,type_noodles:type_noodles},
					dataType:'JSON',
					success:function(allreport){

							if(allreport.error_r=="notconnected"){
								alert("Invalid Employee ID or the product has been returned for the current period");
								$('#displayall').css('display','none');

							}else{

						$('#displayall').css('display','block');
						$('#Return_dialog').css('display','none');
						$('#employeename').html(allreport.fullname);
						$('#employeeid').html(allreport.staffid);
						$('#employeelocation').html(allreport.loc);
						$('#employeedepartment').html(allreport.dept);
						$('#employeecadre').html(allreport.cadre);
						//$('#listflavour').dataTable();
						//console.log(allreport.productlist);
					//	alert(inputstaffid);
					$.ajax({
					url:'../DataStore/server3.php',
					method:'POST',
					data:{userDatafordistribution_flavour:1,inputstaffid:inputstaffid,location_issed:location_issed,id:id,type_noodles:type_noodles,id:id},
					dataType:'JSON',
					success:function(allreport_flavour){
					//alert(allreport_flavour.flavour);
						$('#listflavour').DataTable().destroy();
								var table=$('#listflavour').DataTable({

									dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],

										data:allreport_flavour,
										columns:[
											{data:'flavour'},
											{data:'qty'}

									]


							});



					}





									

						});
						

					}
				}

			});




	}

});


//end of view staff Details

$('#return_noodle').click(function(){
				//alert("I am here?");

				$('#Return_dialog').dialog({
					'title':'Noodles Return Prompt',
					height:650,
					width:700,
					resizable:false,

				});
			



$('#viewRecord').click(function(){

	$('#PendingCollectionData').dialog({
		//height:400,
		width:880,
		title:'List of Pending Staffs that are yet to collect',
	});
	
	});

$('#viewSku').click(function(){
	alert("yes SKU");
});




});







$('#issuebutton').click(function(){

	var staffid=$('#employeeid').html();
	var stafflo=$('#employeelocation').html();
	var stafidname=$('#employeename').html();
	var cadre=$('#employeecadre').html();
	var type_noodles='<?php echo $type_noodles; ?>';
	var id='<?php echo $gf; ?>';
	var issuer='<?php echo $user_use; ?>'; 
//	alert(stafidname);


	$.ajax({

					url:'../DataStore/server3.php',
					method:'POST',
					data:{saveissuanceData:1,staffid:staffid,stafflo:stafflo,stafidname:stafidname,cadre:cadre,type_noodles:type_noodles,id:id,issuer:issuer},
					dataType:'JSON',
					success:function(allreport){
						alert(allreport);
						location.reload();

					}


	});




});



$('#issuerlogout').click(function(){

	$.ajax({

					url:'../DataStore/server3.php',
					method:'POST',
					data:{logoutissuance:1},
					dataType:'JSON',
					success:function(logout){
					alert("Logout successfully");
					window.location.href='../centraldistribution.php';
						}


	});
					
});




$('#emplist').click(function(){
	$('#skuanalysis_d').css('display','none');
		$('#deptAnalysis_data').css('display','none');
	$('#emplist_page').css('display','block');
	$('#dataApprovereturn').css('display','block');

	var location='<?php echo $_SESSION['location']; ?>';
	var doc_data='<?php echo $gf; ?>';
	var type_noo='<?php echo $type_noodles; ?>';
	
	$.ajax({
					url:'../DataStore/server3.php',
					method:'POST',
					data:{staff_return_list:1,location:location,doc_data:doc_data,type_noo:type_noo},
					dataType:'JSON',
					success:function(retrunemployee){
							$('#empreturnData').DataTable().destroy();
								var table=$('#empreturnData').DataTable({

									dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],


										data:retrunemployee,
										columns:[
											{data:'staffid'},
											{data:'employee_name'},
											{data:'dept'},
											{data:'cadre'},
											{data:'location'},
											{data:'qty'}

									]


							});


						}


	});



});







$('#deptAnalysis').click(function(){
	$('#skuanalysis_d').css('display','none');
		$('#deptAnalysis_data').css('display','block');
	$('#emplist_page').css('display','none');
	$('#dataApprovereturn').css('display','block');
		var location='<?php echo $_SESSION['location']; ?>';
		var doc_data='<?php echo $gf; ?>';

	$.ajax({
					url:'../DataStore/server3.php',
					method:'POST',
					data:{staff_return_list_dept:1,location:location,doc_data:doc_data},
					dataType:'JSON',
					success:function(retrunemployee){
							$('#deptAnalysis_pagetable').DataTable().destroy();
								var table=$('#deptAnalysis_pagetable').DataTable({

									dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],

										data:retrunemployee,
										columns:[
											{data:'Dept'},
											//{data:'cadre'},
											{data:'Quantity'}
										//	{data:'status'}

									]


							});


						}


	});



});


$('#skuanalysis').click(function(){
	$('#skuanalysis_d').css('display','block');
		$('#deptAnalysis_data').css('display','none');
	$('#emplist_page').css('display','none');
	$('#dataApprovereturn').css('display','block');
	//alert("Yes, I am here");
		var location='<?php echo $_SESSION['location']; ?>';
		var doc_data='<?php echo $gf; ?>'
	//	alert(doc_data);

	$.ajax({



						url:'../DataStore/server3.php',
					method:'POST',
					data:{staff_return_list_sku:1,location:location,doc_data:doc_data},
					dataType:'JSON',
					success:function(retrunemployee){
							$('#skuanalysis_table').DataTable().destroy();
								var table=$('#skuanalysis_table').DataTable({
									dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],

										data:retrunemployee,
										columns:[
											{data:'flavour'},
											{data:'Quantity'}
										

									]


							});


						}

	});


});


$('#dataApprovereturn').click(function(){

	if(confirm("You are about to close the distribution of noodles for the current period and also send a mail for approval. Note!, Issuance will stop immediately you send this mail")){
	//	alert("Yes, I am here");

	var doc_data='<?php echo $gf; ?>';
	var type='<?php echo $type_noodles; ?>';
	//alert(doc_data);
		$.ajax({
			url:'../DataStore/server3.php',
					method:'POST',
					data:{retunr_approval_data:1,doc_data:doc_data,type:type},
					dataType:'JSON',
					success:function(retunr_approval_data_d){

						if(retunr_approval_data_d=="You have previously returned and approved a return for this transaction"){
						alert(retunr_approval_data_d);
					}else{
						alert("Mail sent successfully");
					}

					}




		});



	}else{
		alert("I am cheking");
	}
});











$('#avail').click(function(){

	$('#AvailableItem').dialog({
		width:1000,
		height:600,
		title:'Items Availability Check',

	});
	var location='<?php echo $_SESSION['location']; ?>';
	var doc_data='<?php echo $gf; ?>';
	var type_noo='<?php echo $type_noodles; ?>';
	//alert(type_noo);
	$.ajax({

					url:'../DataStore/server3.php',
					method:'POST',
					data:{staff_available:1,location:location,doc_data:doc_data,type_noo:type_noo},
					dataType:'JSON',
					success:function(avaiData){
							$('#AvailableItemtable').DataTable().destroy();
								var table=$('#AvailableItemtable').DataTable({
									dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],

										data:avaiData,
										columns:[
											{data:'flavour'},
											{data:'avail'},
											{data:null,

								render: function (data, type, row) {
                return '<button class="availability_item" value="'+data.flavour+'" style="background-color:green; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"><i class="icofont-eye-open "  style="">Choose</i></button>';
            }


										}
										

									]


							});


						}


	});

/*
	$.ajax({



						url:'../DataStore/server3.php',
					method:'POST',
					data:{staff_return_list_sku:1,location:location,doc_data:doc_data},
					dataType:'JSON',
					success:function(retrunemployee){
							$('#AvailableItemtable').DataTable().destroy();
								var table=$('#AvailableItemtable').DataTable({
									dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],

										data:retrunemployee,
										columns:[
											{data:'flavour'},
											{data:'Quantity'}
										

									]


							});


						}

	});*/

//$('#availabilitydataview').css('display','block');



});



	$(document).on('click', '.availability_item', function() {
	//alert($(this).val());
	if(confirm("You are about to change the availability of Items or SKU")){
		//alert("I will work on this ooo");

		var item_availability_id=$(this).val();
		var location='<?php echo $_SESSION['location']; ?>';
		var doc_data='<?php echo $gf; ?>';
		var type_noo='<?php echo $type_noodles; ?>';
		//alert(item_availability_id);

		$.ajax({

	
					url:'../DataStore/server3.php',
					method:'POST',
					data:{get_data_to:1,item_availability_id:item_availability_id,doc_data:doc_data,location:location,type_noo:type_noo},
					dataType:'JSON',
					success:function(dataavail){
						
						alert(dataavail);
						//window.location.href='./DashboardPage.php';

					}




		});





	}else{
		alert("Transaction cancelled");
	}
});



	$(document).on('click', '.view_item', function() {
var staff_to_check=$(this).val();

$('#viewItemData').dialog({
		width:600,
		height:500,
		title:'Items Collected'

	});


var location='<?php echo $_SESSION['location']; ?>';
	var doc_data='<?php echo $gf; ?>';
	var type_noo='<?php echo $type_noodles; ?>';

	//alert(type_noo);
	$.ajax({

					url:'../DataStore/server3.php',
					method:'POST',
					data:{check_collected_data:1,location:location,doc_data:doc_data,type_noo:type_noo,staff_to_check:staff_to_check},
					dataType:'JSON',
					success:function(avaiDataD){
							$('#viewItemDataData').DataTable().destroy();
								var table=$('#viewItemDataData').DataTable({
									dom: 'Blfrtip',
        						buttons: [
           							 {extend:'copy',className:'btn-danger'},
           							 {extend:'csv',className:'btn-info'},
           							 {extend:'excel',className:'btn-secondary'},
           							 {extend:'pdf',className:'btn-primary'},
       								 ],

										data:avaiDataD,
										columns:[
											{data:'items_collected'},
											{data:'trans_date'},
																		

									]


							});


						}


	});



});

			
		});
	</script>
</head>

<body style="background-size: cover; background-color: rgba(245, 234, 241, 0.8); font-size: 14px;">
	<section class="container">
			

	<div class="col-sm-10 offset-sm-1" align="center">
		<label style="font-size: 40px; font-weight: bolder;">NOODLES ISSUANCE PORTAL</label>
		
	</div>
</section>
<section class="container-fluid">
<div class="row" style="width:60%; margin: auto;">
<div class="card alert alert-primary" style="width: 10rem;  margin-right: 10px;" id="">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Employee</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:28px; font-weight: bolder;" align="center" >
    	<span id="totalData"></span>
    </div>
  </div>
</div>

<div class="card alert alert-primary" style="width: 10rem;  margin-right: 10px;" id="">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Total</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:28px; font-weight: bolder;" align="center" >
    	<span id="allscount"></span>
    </div>
  </div>
</div>




<div class="card alert alert-success" style="width: 10rem; margin-right: 10px;" id="Supplier_but">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Issued</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:28px; font-weight:bolder;" align="center" >
    	<span id="totalIssued"></span>
    </div>
  </div>
</div>
<div class="card alert alert-primary" style="width: 10rem;  margin-right: 10px;" id="">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Total Issued</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:28px; font-weight: bolder;" align="center" >
    	<span id="allscountissued"></span>
    </div>
  </div>
</div>


<div class="card alert alert-secondary" style="width: 10rem;  margin-right: 10px;" id="contractor_but">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Pending</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:28px;font-weight: bolder;" align="center" >
    	<span id="totalout"></span>
    </div>
  </div>
</div>

<!--

<div class="card alert alert-secondary" style="width: 10rem;  margin-right: 10px;" id="contractor_but">
  <div class="card-body">
    <div class="card-title" align="center" style="font-weight: bold;">Pending</div>
    <h6 class="card-subtitle mb-2 text-muted"></h6>
    <div style="font-size:40px;" align="center" >
    	<span id="totalout"></span>
    </div>
  </div>
</div>

-->
</div>



	<div class=" container">

<div class="row">
		<div class="row">
				<div class="col-sm-6"><span style="font-weight:bolder;">Name:</span> <?php echo $user_pick['Name']; ?> </div>
				<div class="col-sm-6"><?php echo $_SESSION['location']; ?></div>
			</div>

<ul class="nav nav-tabs">
	  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="#" id="avail">Available Product</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="#" id="issuedata">Issue</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#" id="issuereport">Report</a>
  </li>
    <li class="nav-item">
    <a class="nav-link" href="#" id="return_noodle">Return</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#" id="issuerlogout">Logout</a>
  </li>

</ul>
</div>

</div>




<div class=" container" id="showtable">
	<table class="table" id="reporttablecentral" style="width:100%;">
		<thead style="background-color:brown; color: white;">
			<th>staffid</th>
			<th>Employee Name</th>
			<th>cadre</th>
			<th>Dept</th>
			<th>qty</th>
			<th>Type</th>
			<th>month</th>
			<th>status</th>
			<th>Issuance level</th>
			<th>issue_date</th>
			<th>out_date</th>
			<th>View</th>
			</thead>




	</table>
</div>
	


<div class="container" id="issueDatanow" >
	<div class="row">
		<div class="col-sm-4">
		<label style="font-size:30px;">Product Issuance Page </label>


<div class=" mt-3">
	<label style="font-weight:bolder;">Enter Employee ID</label>

<div class="">
	<input type="text" name="" placeholder="Enter Employee ID" class="form-control mt-1" id="inputstaffid">
</div>

<div class="">
	<button class="btn btn-danger mt-1" id="viewdata">View</button>
</div>


</div>
</div>


<div class="col-sm-6" style="display:none;" id="displayall">
<div class="form-group" >
	<span style="font-size:30px; font-weight:bolder;">Employee Details</span>
	<table class="table col-sm-6">
		<tr><td>Name:</td><td><label id="employeename"></label></td></tr>
		<tr><td>Employee ID:</td><td><label id="employeeid"></label></td></tr>
		<tr><td>Location:</td><td><label id="employeelocation"></label></td></tr>
		<tr><td>Department:</td><td><label id="employeedepartment"></label></td></tr>
		<tr><td>Cadre:</td><td><label id="employeecadre"></label></td></tr>

	</table>
</div>




<div class="" id="Return_dialog">
	<div class="alert alert-danger">You are about to return the remaining quantity of Noodles assigned for distribution. Kindly read and be sure of this transaction. This will automatically return the Noodles and prevent you from issuing it. </div>


	<?php
$ty=date("Y");
$ty_mon=date("m");
	$get_data_out_for=sqlsrv_query($db_conn,"SELECT sum(qty) as qty FROM [distribution].[dbo].[uploaded_staff_list]  where location='$location_issed' and year='$ty' and month='$ty_mon' and type='$type_noodles' and final_data = 'processed'");
	$get_data_out_for_id=sqlsrv_fetch_array($get_data_out_for,SQLSRV_FETCH_ASSOC);

	$get_issued=sqlsrv_query($db_conn,"SELECT sum(qty) as q_issued FROM [distribution].[dbo].[uploaded_staff_list]  where location='$location_issed' and year='$ty' and month='$ty_mon' and status in ('checkout','issued') and type='$type_noodles' and final_data  = 'processed'");
	$get_issued_data=sqlsrv_fetch_array($get_issued,SQLSRV_FETCH_ASSOC);

$get_pending=sqlsrv_query($db_conn,"SELECT sum(qty) as q_pending FROM [distribution].[dbo].[uploaded_staff_list]  where location='$location_issed' and year='$ty' and month='$ty_mon' and status is null and type='$type_noodles' and final_data='processed'");
	$get_pending_data=sqlsrv_fetch_array($get_pending,SQLSRV_FETCH_ASSOC);

	$get_return=sqlsrv_query($db_conn,"SELECT sum(qty) as q_retrun FROM [distribution].[dbo].[uploaded_staff_list]  where location='$location_issed' and year='$ty' and month='$ty_mon' and status is null and type='$type_noodles' and final_data='processed'");
	$get_return_data=sqlsrv_fetch_array($get_return,SQLSRV_FETCH_ASSOC);

	$fg=$gf;
	$get_close_status=sqlsrv_query($db_conn,"SELECT * from [distribution].[dbo].[upload_file_data] where id='$gf' ");
	$get_close=sqlsrv_fetch_array($get_close_status,SQLSRV_FETCH_ASSOC);



	 ?>


	<div class="mt-3">
	
		<table class="table" align="left">
			<tr><td>Date:</td><td><?php echo date("Y-m-d"); ?></td></tr>
			<tr><td>Location:</td><td><?php echo $location_issed; ?></td></tr>
			<tr><td>Total Uploaded:</td><td><?php echo $get_data_out_for_id['qty'];?></td></tr>
			<tr><td>Total Issued:</td><td><?php echo $get_issued_data['q_issued'];?></td></tr>
			<tr><td>Total Pending:</td><td><?php echo $get_pending_data['q_pending'];?></td></tr>
			<tr><td>Total Return:</td><td><?php echo $get_return_data['q_retrun'];?></td></tr>
			<tr><td>Return Status:</td><td><?php echo $get_close['final_data'];?></td></tr>

		<!--	<tr><td><button class="btn btn-primary" id="viewRecord">View Pending record</button></td><td><button  class="btn btn-danger" id="viewSku">View Pending SKU</button></td></tr>-->
		</table>
<div class="row">


<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="#" id="skuanalysis">SKU Analysis</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#" id="deptAnalysis">Department Analysis</a>
  </li>
    <li class="nav-item">
    <a class="nav-link" href="#" id="emplist">Employee List</a>
  </li>
  <!--<li class="nav-item">
    <a class="nav-link" href="#" id="issuerlogout">Logout</a>
  </li>
-->

</ul>


<!--
	<button class="btn btn-success col-sm-4 offset-sm-4">Send for Return Approval</button>-->
</div>


<div class="row">
	
	<div class="col-sm-12">
		

	
		<div class="col-sm-12" id="emplist_page" style="display: none;">
<table class="table" class="display" style="width:100%;" id="empreturnData">				<thead class="alert alert-danger">
					<th>StaffID</th>
					<th>Employee Name</th>
					<th>Dept</th>
					<th>Cadre</th>
					<th>Location</th>
					<th>Qty</th>

				</thead>

			</table>


		</div>
<div class="col-sm-12" id="deptAnalysis_data" style="display:none;">
	<table class="table" class="display" style="width:100%;" id="deptAnalysis_pagetable">
				
				<thead class="alert alert-danger">
					<th>Dept</th>
				<!--	<th>Cadre</th>-->
					<th>Quantity</th>
					<!--<th>Return</th>-->
				</thead>

			</table>






		</div>









<div class="col-sm-12" id="skuanalysis_d" style="display:none;">
	<table class="table" class="display" style="width:100%;" id="skuanalysis_table">
				
				<thead class="alert alert-danger">
					<th>Flavour</th>
					<th>Quantity</th>
					

				</thead>

			</table>






		</div>








	</div>

	<div class="row">
	<div class="col-sm-4">
		<button class="btn btn-danger" style="display:none;" id="dataApprovereturn">Send Approve</button>
	</div>
</div>

</div>














	</div>

</div>









<div class="row">
	
	<table class="table" id="listflavour" class="display" style="width:100%; ">
		<thead style="background-color:brown; color:white;">
			<th>SKU</th>
			<th>Qty</th>
		</thead>
	</table>
</div>

<div>
	<button id="issuebutton" class="btn btn-danger">Approve</button>

</div>


</div>


	</div>




<div class=" container" id="PendingCollectionDataD" style="display:none;">
	<table class="table" id="PendingCollectionData" style="width:100%;">
		<thead style="background-color:brown; color: white;">
			<th>staffid</th>
			<th>Employee Name</th>
			<th>cadre</th>
			<th>qty</th>
			<th>month</th>
			<th>status</th>
			<!--<th>appro_date</th>-->
			<th>issue_date</th>
			<th>out_date</th>
			</thead>




	</table>
</div>
	




<div class=" container" id="AvailableItem" style="display:none;">
	<table class="table" id="AvailableItemtable" style="width:100%;">
		<thead style="background-color:brown; color: white;">
			<th>Product</th>
			<th>Status</th>
			<th>But</th>
		</table>
</div>
	




<div class=" container" id="viewItemData" style="display:none;">
	<table class="table" id="viewItemDataData" style="width:100%;">
		<thead style="background-color:brown; color: white;">
			<th>Product</th>
			<th>Date/Time</th>
			
		</table>
</div>
	


</div>




	
</div>












</section>


</body>
</html>

