<? header('X-Content-Type-Options: nosniff');?>
<? header("Content-Security-Policy: frame-ancestors 'self'"); ?>
<? header('X-Frame-Options: SAMEORIGIN'); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


<link href="resources/util/DataTables-1.13.4/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="resources/util/AutoFill-2.5.3/css/autoFill.dataTables.css" rel="stylesheet"/>
<link href="resources/util/Buttons-2.3.6/css/buttons.dataTables.min.css" rel="stylesheet"/>
<link href="resources/util/DateTime-1.4.1/css/dataTables.dateTime.min.css" rel="stylesheet"/>
<link href="resources/util/FixedColumns-4.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet"/>
<link href="resources/util/Responsive-2.4.1/css/responsive.dataTables.min.css" rel="stylesheet"/>
<link href="resources/util/Select-1.6.2/css/select.dataTables.min.css" rel="stylesheet"/>
 
<script src="resources/util/jQuery-3.6.0/jquery-3.6.0.min.js"></script>
<script src="resources/util/JSZip-2.5.0/jszip.min.js"></script>
<script src="resources/util/pdfmake-0.2.7/pdfmake.min.js"></script>
<script src="resources/util/pdfmake-0.2.7/vfs_fonts.js"></script>
<script src="resources/util/DataTables-1.13.4/js/jquery.dataTables.min.js"></script>
<script src="resources/util/AutoFill-2.5.3/js/dataTables.autoFill.min.js"></script>
<script src="resources/util/Buttons-2.3.6/js/dataTables.buttons.min.js"></script>
<script src="resources/util/Buttons-2.3.6/js/buttons.html5.min.js"></script>
<script src="resources/util/Buttons-2.3.6/js/buttons.print.min.js"></script>
<script src="resources/util/DateTime-1.4.1/js/dataTables.dateTime.min.js"></script>
<script src="resources/util/FixedColumns-4.2.2/js/dataTables.fixedColumns.min.js"></script>
<script src="resources/util/Responsive-2.4.1/js/dataTables.responsive.min.js"></script>
<script src="resources/util/Select-1.6.2/js/dataTables.select.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<title>Central Distribution</title>
</head>

<script type="text/javascript">
	
	$(document).ready(function(){
		$('#logindistribution').click(function(){
			var email=$('#email').val();
			var password=$('#password').val();

				//alert(password);
			if(email=="" || password==""){
				alert("All fields are required");

			}else{
				$.ajax({
					url:'DataStore/server2.php',
					method:'POST',
					dataType:'JSON',
					data:{centraladminlogin:1,email:email,password:password},
					success:function(e){
						alert(e);
					if(e=="You are typing an invalid credentials"){
					alert(e);
					}else{
					window.location.href='documents/centraldisplayissuance.php';
					}
				}
				});
			}
		});
	});
</script>

<body style="background-image: url('images/ba.jpg'); background-size: cover;">

<section class="container-fluid">
	<div class="row">
	<div class="col-sm-4" style="margin-top:100px;" >
	<div class="col-sm-10 offset-sm-1" style="color: ; font-size: 35px; font-family:Secular One;"><strong style="margin-top:40px;">Product Distribution</strong></div>
	<div class="row" style="margin-top: px;">

		<div class="col-sm-10 offset-sm-1 mt-1">
			<div class="col-sm-10 mt-3" style="color:; font-size: 30px;font-family:Times New Roman;"><strong>Login</strong>
			</div>

		<div class="form-group mt-3">
			<input type="email" name="" id="email" placeholder="Input email ID" class="form-control">
		</div>

		<div class="form-group mt-3">
			<input type="password" name="" id="password" placeholder="Input Password" class="form-control" required="required">
		</div>

	<div class="form-group mt-3">
	<button class="btn btn-success" id="logindistribution">Login</button>
	</div>
	</div>
	</div>
	</div>

	<div class="col-md-8" style="background-color: brown;height:100vh; background-image:url('images/transform2.png'); background-size: cover;">



		<div class="container row" style="color: white;">
			

			<span style="font-size:60px; margin-top:250px; font-weight: bolder;">Your Imagination is real</span>
			<div class="col-sm-8" style="font-size: 40px; margin-top: -15px; color: gold; font-weight:bolder;" align="center">Let us help you</div>

		</div>
		
	</div>	
</div>
</section>

</body>
</html>