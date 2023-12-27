<?php

session_start(); 
if (!isset($_SESSION['user']) && !isset($_SESSION['location'])) {
    header("Location: ../index.php");
    exit(); // It's a good practice to add an exit after a header redirect
} 

$location=$_SESSION['location'];

include('../DataStore/db.php'); ?>

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
$('#productmaptable').DataTable().destroy();

$.ajax({

						url:'../DataStore/server.php',
						method:'POST',
						data:{fetch_all_map_product:1},
						dataType:'JSON',
						success:function(dvf_record){
						//	alert(dvf_record);
							//	$('#productmaptable').dataTable().destroy();
							$('#productmaptable').DataTable().destroy();
							var table=$('#productmaptable').dataTable({
								data:dvf_record,
								columns:[
												{data:'cadre'},
												{data:'flavour'},
												{data:'qty'},
												{data:'type'},
												{data:'month'},
												{data:'day'},
												

									]

							});



						}


				});






















$('#cadretable').DataTable();
		$('#cadre_id').click(function(){

var location='<?php echo $_SESSION['location']; ?>';

//alert(location);

				$.ajax({
					url:'../DataStore/server3.php',
					method:'POST',
					data:{cadre_data_pull:1,location:location},
					dataType:'JSON',
					success:function(gjgj){
						console.log(gjgj);
						$('#cadretable').DataTable().destroy();
						var table=$('#cadretable').DataTable({
									data:gjgj,
										columns:[
											{data:'month'},
											{data:'cadre'},
											{data:'location'},
											{data:'Qty'},
											{data:'type'},
											{
            // Add a button for viewing
            data: null,
            render: function (data, type, row) {
                return '<button class="individual_item" value="'+data.q_id+'" style="background-color:green; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"><i class="icofont-eye-open "  style=""></i></button>';
            }
        },
        {
            // Add a button for deleting
            data: null,
            render: function (data, type, row) {
                return '<button class="delete_individual_item_sku" value="'+data.q_id+'" style="background-color:red; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"><i class="icofont-ui-delete "  style=""></i></button>';
            }
        }



										]

								});


					}



										});



			//var main_cadretable=$('#cadretable').dataTable();


/*
				$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{fetch_cadre_data:1},
					dataType:'JSON',
					success:function(fetch_cadre){

						//alert(fetch_flavour);

						//$('#cadretype').append(fetch_cadre);

					}

				});

				$('#flavourData').html();*/

				$('#cadredata').dialog({
				//resizable:false,
					title:'Cadre Data Configuration',
					height:620,
					width:1250,
					modal: true,
					dialogClass: 'no-close',
		//autoOpen:false,
					closeOnEscape: false,
				});

			/*$('#yrly').change(function(){
					if($('#yrly').is(':checked')){
					$('#yrly').prop('checked', true);
					alert($('#yrly').val());
					}else{
						alert($('#yrly').val());
					}
				});*/

			$('#saveCadre').click(function(){
				//alert("Yes");


				var cadretype= $('#cadretype').val();
				var cadreNum=$('#cadreNum').val();
				var location='<?php echo $_SESSION['location']; ?>';
				var yrly=$('#yrly').is(":checked");

				//alert(yrly);
				
				if(cadretype =="" || cadreNum == "" || location==""){

					alert("All fields are required");

				}else{

				$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{cadre_data:1,cadretype:cadretype,cadreNum:cadreNum,location:location,yrly:yrly},
					dataType:'JSON',
					success:function(cad){
					
						//alert(cad.cadreuse);
						if(cad.success=='Saved Successfully'){
							$('#additem').css('display','block');
							$('#cadretrack').val(cad.cadreuse);
								alert(cad.success);
								//	alert("I am here");


					$.ajax({
					url:'../DataStore/server3.php',
					method:'POST',
					data:{cadre_data_pull:1,location:location},
					dataType:'JSON',
					success:function(gjgj){
						console.log(gjgj);
							$('#cadretable').DataTable().destroy();
						var table=$('#cadretable').DataTable({
									data:gjgj,
									columns:[
											{data:'month'},
											{data:'cadre'},
											{data:'location'},
											{data:'Qty'},
											{data:'type'},
											{
            // Add a button for viewing
            data: null,
            render: function (data, type, row) {
                return '<button class="individual_item" value="'+row.q_id+'"  style="background-color:green; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"><i class="icofont-eye-open "  style=""></i></button>';
            }
        },
        {
            // Add a button for deleting
            data: null,
            render: function (data, type, row) {
                return '<button class="delete_individual_item_sku" value="'+row.q_id+'" style="background-color:red; border: 1px solid white; border-radius:5px 10px 5px 10px; color:white;"><i class="icofont-ui-delete "  style=""></i></button>';
            }
        }



										]


								});


					}



										});




								//location.reload();
								//$('#example').DataTable().clear().draw();
								
								//setInterval(main_cadretable,10000);

							//alert("I git here");

						}else if(cad.old_file=='You have previously created same for this product'){
							//alert(cad.old_file);
							alert	("You have previously created same for this product");

						}
						

						
						//$('#cadretable').ajax.reload();


					}
				});
			}

			});

			});


$('#addnewproduct').click(function(){


				$('#newProduct').dialog({
					//resizable:false,
					title:'Add new product',
					height:600,
					width:650,
					modal: true,
					dialogClass: 'no-close',
		//autoOpen:false,
					closeOnEscape: false,
				});




			});




$('#mapproduct').click(function(){
	$('#productmaptable').DataTable().destroy();
	$('#productmaptable').dataTable();

			$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{fetch_cadre_data:1},
					dataType:'JSON',
					success:function(fetch_cadre_pro){
							//alert(fetch_cadre_pro);
						//var table=$('#productmaptable').dataTable();

						//alert(fetch_flavour);

						//$('#cadretrack').append(fetch_cadre_pro);

					}

				});






				$('#mapproduct_pane').dialog({
					//resizable:false,
					title:'Map product',
					height:650,
					width:1000,
					modal: true,
					dialogClass: 'no-close',
		//autoOpen:false,
					closeOnEscape: false,
				});
			});



$('#viewreport').click(function(){
				$('#report_pane').dialog({
					//resizable:false,
					title:'View report',
					height:500,
					width:650,
					modal: true,
					dialogClass: 'no-close',
		//autoOpen:false,
					closeOnEscape: false,
				});
			});


	
/*
	$('#yearlyData').change(function(){
					if($('#yearlyData').is(':checked')){
					$('#yearlyData').prop('checked', true);
					//alert($('#yearlyData').val());
					}else{

					}
				});

*/
	$('#assign_data').click(function(){
		//$('#productmaptable').DataTable().ajax.reload();
	//$('#productmaptable').dataTable().destroy();
		var cadre_qtyData=$('#cadretrack').val();
		var flavourData=$('#flavourData').val();
		var cadreQty=$('#cadreQty').val();
		var location='<?php echo $_SESSION['location']; ?>';
		//var yearlyData=$('#yearlyData').val();
		 var yearlyData = $("#yearlyData").is(":checked");
		//alert(yearlyData);


				$.ajax({

						url:'../DataStore/server.php',
						method:'POST',
						data:{fetch_all_map_product:1},
						dataType:'JSON',
						success:function(dvf_record){
						//	alert(dvf_record);
							//	$('#productmaptable').dataTable().destroy();
							$('#productmaptable').DataTable().destroy();
							var table=$('#productmaptable').dataTable({
								data:dvf_record,
								columns:[
												{data:'cadre'},
												{data:'flavour'},
												{data:'qty'},
												{data:'type'},
												{data:'month'},
												{data:'day'},
												

									]

							});



						}


				});






		if(cadre_qtyData=="" || flavourData=="" || cadreQty=="" || location==""){
			alert("All fields are requiredssss");
		}else{
//alert(location);
			$.ajax({
						url:'../DataStore/server.php',
						method:'POST',
						data:{save_data_cadre:1,cadre_qtyData:cadre_qtyData,flavourData:flavourData,cadreQty:cadreQty,location:location,yearlyData:yearlyData},
						dataType:'JSON',
						success:function(dv_d){
						//if(dv_d="Saved Successfully"){
							alert(dv_d);
						 
							$('#cadretrack').val('');
							$('#flavourData').val('');
							$('#cadreQty').val('');
							$('#yearlyData').val('');

						//	}else if(dv_d.overflow_save="You cannot add more than the required flavour"){
							//alert(dv_d.overflow_save);
						//}

						}
			});


		}


	});


//$('.individual_item').click(function(){
	$(document).on('click', '.individual_item', function() {
//	$('#cadretable tbody').on('click', '.individual_item', function () {


//	alert("Yes, I am here");
	//$('#tableindividualData').dataTable().clear().destroy();
	//$('#tableindividualData').dataTable();
	
	//alert(id_unique);
	$('#showcadredetals').dialog({
					//resizable:false,
					title:'Sku Configuration details',
					height:400,
					width:880,
					modal: true,
					dialogClass: 'no-close',
		//autoOpen:false,
					closeOnEscape: false,
				});

	//	
				var id_unique=$(this).val();
//	var id_unique = table.row($(this).closest('tr')).data();
				//alert(id_unique);
				$.ajax({
						url:'../DataStore/server.php',
						method:'POST',
						data:{fetch_data_cadre:1,id_unique:id_unique},
						dataType:'JSON',
						success:function(res_data){
							//alert(res_data);
						$('#tableindividualData').DataTable().destroy();	
						var table=$('#tableindividualData').dataTable({
									responsive:true,
									data:res_data,
									columns: [
   								 {data: 'flavour'},
   								 {data: 'cadre'},
   								 {data: 'qty'},
   								 {data: 'month'},
   								 {data: 'year'},
    						
]

							});

						}
				});
	

});





$(document).on('click', '.delete_individual_item_sku', function() {
	//alert("Yes yes");
	//$('#cadretable tbody').on('click', '.delete_individual_item_sku', function () {
	var delete_cadre=$(this).val();

    console.log('View button clicked for q_id: ' + delete_cadre);

	if(confirm("You are about to delete a cadre record. Note this will delete the SKU assigned to this cadre")){
	//alert(delete_cadre);


		$.ajax({
				url:'../DataStore/server.php',
				method:'POST',
				data:{deleteCadreData:1,delete_cadre:delete_cadre},
				dataType:'JSON',
				success:function(deleteCadre){
					alert(deleteCadre);
					location.reload();
				}




		});

	}else{
		alert("You have canceled the transaction. No changes were made to the record");
	}

});











});

			
		
	</script>
</head>
<body>


<section class="container-fluid">
	<div class="row">
		<div class="col-sm-12 mt-5">
			<label style="margin-top: 20px; font-size: 40px; font-weight: bolder; color: black;" class="text text">Configuration </label>
			<hr>



<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" id="cadre_id">Cadre Settings</a>
  </li>
<!--  <li class="nav-item">
    <a class="nav-link" id="addnewproduct">Add Product</a>
  </li>-->
  <li class="nav-item">
    <a class="nav-link" id="mapproduct">Map Product</a>
  </li>
 <!-- <li class="nav-item">
    <a class="nav-link" id="viewreport" >View Report</a>
  </li>-->
</ul>





		</div>
	</div>
</section>

<!-- Cadre Setting Page -->
<section  id="cadredata" style="display: none;">
	<div class="row col-sm-12">
				<div class="col-sm-5">
			<label>Cadre Type</label>
			<select class="form-control col-sm-5" id="cadretype">
				<option></option>

				<?php 

								$get_data_out=sqlsrv_query($db_conn,"SELECT cadre FROM [distribution].[dbo].[cadre_list]");
								while($get_data_out_d=sqlsrv_fetch_array($get_data_out,SQLSRV_FETCH_ASSOC)){
									echo '<option>'.$get_data_out_d['cadre'].'</option>';
								}

						?>
	
			</select>
		</div>

		
		<div class="col-sm-3">
				<label>Qty</label>
			<input type="Number" name="" class="form-control col-sm-3" min="1" id="cadreNum">
			<div span style="color: red; font-size: 12px;">Check for Yearly product:<input type="checkbox" id="yrly" class=""></div>
		</div>
		<div class="col-sm-1">
			<div><br></div>
			<button class="btn btn-danger float-end" id="saveCadre">Save</button>
		</div>

		

</div>

<hr>
<div class="mt-5" style="">
	<table class="table table-stripped mt-5" id="cadretable" class="display" style="width:100%">
		<thead><th>Month</th><th>Cadre</th><th>Location</th><th>Qty</th><th>Type</th><th>View</th><th>Delete</th></thead>

		<?php
		

//<td><button style="background-color:brown; border-radius:5px 10px 5px 10px; border: 1px solid white; color:white;" class="individual_item" value='.$fc['q_id'].' style=""><i class="icofont-ui-edit"></i></button></td>
		?>
		
	</table>

<!--<div class="float-end"><button class="btn btn-secondary" id="refresh">Refresh</button></div>-->

</div>	
		
			
			
			<!--<input type="text" name="" class="form-control">-->
		
			
			
			
	
					
			
		

				

	
	
</section>
<!-- Cadre Setting Page -->



<!-- New Product Setting Page -->
<section  id="newProduct" style="display: none;">
	<div class="col-sm-12 ">
	<div class="row">

		<div class="form-group">
			<label>Register new Product</label>
			<input type="text" name="" class="form-control" placeholder="Enter new SKU">
		</div>





		
	</div>





















	</div>		
				

	</div>
	
</section>
<!-- CadNew Product Setting Page -->





<!-- Map Product Setting Page -->
<section  id="mapproduct_pane" style="display:none;">
	<div class="col-sm-12 ">
		


<hr>
	<div id="additem" class="mt-2" style="">
		<label style="font-size:20px; color: brown;">Select SKUs and qty for each cadre </label>
		<div class="row">
			<div class="col-sm-3">
					<label>Cadre</label>

					<select class="form-control" id="cadretrack">
						<option></option>

						<?php 

								$get_data_out=sqlsrv_query($db_conn,"SELECT cadre FROM [distribution].[dbo].[cadre_list]");
								while($get_data_out_d=sqlsrv_fetch_array($get_data_out,SQLSRV_FETCH_ASSOC)){
									echo '<option>'.$get_data_out_d['cadre'].'</option>';
								}

						?>
						
						
					</select>

				<!--<input type="text" name="" readonly required class="form-control col-sm-2" placeholder="Cadre" id="cadretrack">-->

			</div>
			<div class="col-sm-3">
					<label>SKU</label>
				<select class="form-control" required id="flavourData">
				<option></option>

						<?php 
						
								$get_data_out=sqlsrv_query($db_conn,"SELECT sku FROM [distribution].[dbo].[product_table] ");
								while($get_data_out_d=sqlsrv_fetch_array($get_data_out,SQLSRV_FETCH_ASSOC)){
									echo '<option>'.$get_data_out_d['sku'].'</option>';
								}

						?>

				</select>

			</div>

			<div class="col-sm-3">
				<label>Qty</label>
				<input type="Number" name="" class="form-control" required placeholder="Qty" id="cadreQty" min="1" style="1">
				<div class="mt-1" style="color:red; font-size: 12px;">Check for Yearly product: <input type="checkbox" id="yearlyData" class=""></div>

			</div>


			<div class="col-sm-1 float-end">
				<label></label>
				<button class="btn btn-danger" id="assign_data">Assign</button>
			</div>
		


			<div class="mt-5">

			<table class="table" class="display" style="width:100%" id ="productmaptable">
				<thead>
					
					<th>Cadre</th>
					<th>SKU</th>
					<th>Qty</th>
					<th>Type</th>
					<th>Month</th>
					<th>Day</th>
				</thead>





			</table>


			</div>
		</div>

		


































				

	</div>
	
</section>
<!-- Map Product Setting Page -->







<!-- Report Setting Page -->
<section  id="report_pane" style="display: none;">
	<div class="col-sm-6 ">
		
				

	</div>
	
</section>
<!-- Report Product Setting Page -->





<section  id="showcadredetals" style="display: none;">
	<div class="col-sm-12">
	<div class="row"></div>		
				
		<table class="table" id="tableindividualData"  style="width: 100%;">
			<thead>
				
				<th>SKU</th>
				<th>Cadre</th>
				<th>Qty</th>
				<th>Month</th>
				<th>Year</th>
				
			</thead>

			
		</table>

<script type="text/javascript">
	//$.each(ev_app_gpass, function(i, value) {
    //     				$('#apprvmall').append($('<option>').text(value.fullname_app_gpass).attr('value', value.fullname_app_gpass));
      //   				$('#selectmail').html(value.email_app);

</script>



	</div>
	
</section>

</body>
</html>