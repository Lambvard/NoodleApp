
<?php 
session_start(); 
if (!isset($_SESSION['user']) && !isset($_SESSION['location'])) {
    header("Location: ../index.php");
    exit(); // It's a good practice to add an exit after a header redirect
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
	<title>ISSUANCE</title>

	<script type="text/javascript">
		$(document).ready(function(){
			alert("Yes");
		});
</script>


<body>


<div class="container" id="issueDatanow" >

	<div class="container-fluid row">
		<label style="font-size:30px;">Product Issuance Page </label>
<div class="col-sm-5 mt-3">
	<label style="font-weight:bolder;">Enter Employee ID</label>

<div class="">
	<input type="text" name="" placeholder="Enter Employee ID" class="form-control mt-1" id="inputstaffid">
</div>



<div class="row" style="display:;">
	
<div class="mt-2">
	<label style="font-weight:bolder;">Employee ID:</label>
	<label id="enployeeid"></label>

</div>

<div class="mt-2">
	<label style="font-weight:bolder;">Name:</label>
	<label id="enployeename"></label>
</div>

<div class="mt-2">
	<label style="font-weight:bolder;">Department:</label>
	<label id="enployeedepartment"></label>
</div>

<div class="mt-2">
	<label style="font-weight:bolder;">Location:</label>
	<label id="enployeelocation"></label>

</div>

<div class="mt-2">
	<label style="font-weight:bolder;">Cadre:</label>
	<label id="enployeecadre"></label>
</div>
<div class="mt-2">
	<label style="font-weight:bolder;">Item Issued:</label>
	<label id="enployeeissued"></label>

</div>



</div>

</div>

</div>