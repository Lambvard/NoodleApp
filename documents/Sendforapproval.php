<?php 
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['location'])) {
    header("Location: ../index.php");
    exit(); // It's a good practice to add an exit after a header redirect
}



	include('../DataStore/db.php');


//$loca=$_SESSION['location'];
//echo $loca;
$hr="HR/ADMIN";
$whs="WSHE";
$FM="FM";
$stat="uploaded";
$location=$_SESSION['location'];
$chosen_mon=date('m');
$chosen_year=date('Y');
$role_use="Admin";
//$data_HR=sqlsrv_query($db_conn,"SELECT * from [IOU].[dbo].[Staffdetailsvmsold] where Dept='$hr' and Stafflocation='$loca'");
//$data_WHSE=sqlsrv_query($db_conn,"SELECT * from [IOU].[dbo].[Staffdetailsvmsold] where  Dept='$whs' and  Stafflocation='$loca' ");

$data_HR=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.[login] where Dept='$hr' and location='$location' and role='$role_use' ");
$data_WHSE=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.[login] where  Dept='$whs' and location='$location' and role='$role_use'");
$data_FM=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.[login] where  Dept='$FM' and location='$location' and role='$role_use' ");
$st=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.upload_file_data where Status='$stat' and location='$location' and data_mon='$chosen_mon' and data_year='$chosen_year'");
$st_r=sqlsrv_query($db_conn,"SELECT * from distribution.dbo.upload_file_data where Status='$stat' and location='$location' and data_mon='$chosen_mon' and data_year='$chosen_year'");
$d=sqlsrv_fetch_array($st,SQLSRV_FETCH_ASSOC);

//echo $sts['month'];


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

			$('#ApproveData').click(function(){
				var hr_data=$('#hr').val();
				var whse_data=$('#whse').val();
				var fm_data=$('#fm').val();
				
				var monthData=$('#mon_app').val();
				var type=$('#trans_app').val();
				//alert(monthData)


				if(hr_data=="" || whse_data=="" || fm_data=="" || monthData=="" || type==""){
					alert("All fields are required");
				}else{
				$('#ApproveData').attr('disabled',true);
				$.ajax({
					url:'../DataStore/server4.php',
					method:'POST',
					data:{app_data:1,hr_data:hr_data,whse_data:whse_data,monthData:monthData,fm_data:fm_data,type:type},
					dataType:'JSON',
					success:function(dfdf){
						//alert("Mail sent successfully");
						alert(dfdf);
						location.reload();

					}
				});
			}
			});


				//pull all cadre record
/*
				$('#refresh').click(function(){
					$.ajax({
						url:'../DataStore/server.php',
						method:'POST',
						data:{refresh_cadre:1},
						DataType:'JSON',
						success:function(re){
								alert(re);
							$('#cadretable').dataTable({
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
                    				{ re:'month'},
                    				{ re:'cadre'},
                    				{ re:'Qty'},
                    				
                    		]

						});

						}
					});
				});


			//end pull all cadre record






			$('#cadre_id').click(function(){
				$('#cadredata').dialog({
					//resizable:false,
					title:'Cadre Data Configuration',
					height:500,
					width:650,
					modal: true,
					dialogClass: 'no-close',
		//autoOpen:false,
					closeOnEscape: false,
				});

			$('#saveCadre').click(function(){
				//alert("Yes");

				var cadretype= $('#cadretype').val();
				var cadreNum=$('#cadreNum').val();



				if(cadretype =="" || cadreNum == ""){

					alert("All fields are required");

				}else{

				$.ajax({
					url:'../DataStore/server.php',
					method:'POST',
					data:{cadre_data:1,cadretype:cadretype,cadreNum:cadreNum},
					DataType:'JSON',
					success:function(cad){
						alert(cad);

					}
				});
			}

			});

			});


$('#addnewproduct').click(function(){
				$('#newProduct').dialog({
					//resizable:false,
					title:'Add new product',
					height:500,
					width:650,
					modal: true,
					dialogClass: 'no-close',
		//autoOpen:false,
					closeOnEscape: false,
				});




			});




$('#mapproduct').click(function(){
				$('#mapproduct_pane').dialog({
					//resizable:false,
					title:'Map product',
					height:500,
					width:650,
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




*/




			
		});
	</script>
</head>
<body>
	<section class="container-fluid">
	<div class="row">



	<div class="col-sm-6 mt-5">
		
	<label style="font-weight:bolder; font-size: 30px;">Approval Page</label>

	<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;">Select FM Approval</label>
					<select class="form-control" id="fm" name="type" required>
						<option></option>

						<?php

				while ($dfFM=sqlsrv_fetch_array($data_FM,SQLSRV_FETCH_ASSOC)) {
					//echo '<option>'.$dfHR['Surname']." ".$dfHR['Firstname']." ". $dfHR['Othernames']."|||".$dfHR['staff_mail'].'</option>';
					echo '<option>'.$dfFM['email'].'</option>';
				}


		?>


					</select>

	</div>



		<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;">Select HR Approval</label>
					<select class="form-control" id="hr" name="type" required>
						<option></option>

						<?php

				while ($dfHR=sqlsrv_fetch_array($data_HR,SQLSRV_FETCH_ASSOC)) {
					//echo '<option>'.$dfHR['Surname']." ".$dfHR['Firstname']." ". $dfHR['Othernames']."|||".$dfHR['staff_mail'].'</option>';
					echo '<option>'.$dfHR['email'].'</option>';
				}


		?>


					</select>

	</div>


<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;">Select Warehouse Approval</label>
					<select class="form-control" id="whse" name="type" required>
						<option></option>
						
							<?php

				while ($dfWSHE=sqlsrv_fetch_array($data_WHSE,SQLSRV_FETCH_ASSOC)) {
					//echo '<option>'.$dfWSHE['Surname']." ".$dfWSHE['Firstname']." ". $dfWSHE['Othernames']."|||".$dfWSHE['staff_mail'].'</option>';

					echo '<option>'.$dfWSHE['email'].'</option>';
				}


		?>



					</select>
				</div>


<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;"> Transaction Type</label>
	<select  class="form-control" id="trans_app" name="type"  required>
						
						
<?php
			//$df=date("Y");
				while ($sts=sqlsrv_fetch_array($st_r,SQLSRV_FETCH_ASSOC)) {
					echo '<option>'.$sts['type'].'</option>';
				}
	

		?>

</select>
				
				</div>




<div class="mt-2">
		<label style="font-weight:bolder; font-size: 16px;">Month</label>
		<input type="text" class="form-control" id="mon_app" name="type" value="<?php echo $d['data_mon']."-".$d['data_year']; ?>"  readonly required>
						
						



				
				</div>

			<input  type="submit" name="ApproveData" id="ApproveData" class="btn btn-success float-end mt-3" value="Send for Approval" required>








	</div>

	
	
</section>

<!--




<div style="margin-top:50px; display:none;" id="aprovalpane">

				<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;">Select HR Approval</label>
					<select class="form-control" id="hr" name="type" required>
						<option></option>
		<?php

			//	while ($dfHR=sqlsrv_fetch_array($data_HR,SQLSRV_FETCH_ASSOC)) {
			//		echo '<option>'.$dfHR['Surname']." ".$dfHR['Firstname']." ". $dfHR['Othernames']."|||".$dfHR['staff_mail'].'</option>';
			//	}


		?>
						
						
					</select>
				</div>

				<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;">Select Warehouse Approval</label>
					<select class="form-control" id="whse" name="type" required>
						<option></option>
						
							<?php

			//	while ($dfWSHE=sqlsrv_fetch_array($data_WHSE,SQLSRV_FETCH_ASSOC)) {
			//		echo '<option>'.$dfWSHE['Surname']." ".$dfWSHE['Firstname']." ". $dfWSHE['Othernames']."|||".$dfWSHE['staff_mail'].'</option>';
			//	}


		?>



					</select>
				</div>


				<div class="mt-2">
					<label style="font-weight:bolder; font-size: 16px;">Month</label>
					<select class="form-control" id="mon_app" name="type" required>
						<option></option>
						
<?php

				//while ($d=sqlsrv_fetch_array($st,SQLSRV_FETCH_ASSOC)) {
				//	echo '<option>'.$d['data_mon'].'</option>';
				//}


		?>


					</select>
				</div>

			<input  type="submit" name="ApproveData" id="ApproveData" class="btn btn-success float-end mt-3" value="Send for Approval" required>

</div>




			</div>





	-->
<!-- Report Product Setting Page -->



</body>
</html>