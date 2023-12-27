<? header('X-Content-Type-Options: nosniff');?>
<? header("Content-Security-Policy: frame-ancestors 'self'"); ?>
<? header('X-Frame-Options: SAMEORIGIN'); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="util/mine.css">
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="resources/pdfmake.min.js"></script>
	<script type="text/javascript" src="resources/buttons.html5.min.js"></script>
	<script type="text/javascript" src="resources/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="resources/jquery-3.5.1.min.js"></script>
	<script type="text/javascript" src="resources/jquery.dataTables.css"></script>
	<script type="text/javascript" src="resources/jquery.dataTables.min.js"></script>
	<title>Noodle Distribution Portal</title>
</head>

<script type="text/javascript">
	
	$(document).ready(function(){
		$('#loginid').click(function(){
			var email=$('#email').val();
			var password=$('#password').val();

				//alert(password);
			if(email=="" || password==""){
				alert("All fields are required");

			}else{
				$.ajax({
					url:'DataStore/server.php',
					method:'POST',
					dataType:'JSON',
					data:{login:1,email:email,password:password},
					success:function(e){
				if(e=="Connected successfully"){
					alert(e);
					window.location.href='documents/DashboardPage.php';
					}else{
					alert(e);
					}
				}
				});
			}
		});
	});
</script>

<body style="background-image: url('images/back.jpg'); background-size: cover; background-color:rgba(245, 40, 145, 0.9);">

<section class="container">
	<div class="col-sm-4 offset-sm-4" style="background-color: rgba(57, 18, 101, 0.9); margin-top: 100px; height:415px; border-radius: 8px 8px 8px 8px;">
	<div class="col-sm-10 offset-sm-1" style="color: white; font-size: 35px; font-family:;"><strong style="margin-top:40px;">Product Distribution</strong></div>
	<div class="row" style="margin-top: px;">

		<div class="col-sm-10 offset-sm-1 mt-1">
			<div class="col-sm-10 mt-3" style="color:white; font-size: 30px;"><strong>Login</strong>
			</div>

		<div class="form-group mt-3">
			<input type="email" name="" id="email" placeholder="Input email ID" class="form-control">
		</div>

		<div class="form-group mt-3">
			<input type="password" name="" id="password" placeholder="Input Password" class="form-control" required="required">
		</div>

	<div class="form-group mt-3">
	<button class="btn btn-success" id="loginid">Login</button>
	</div>
	</div>
	</div>
	</div>	
</section>





</body>
</html>