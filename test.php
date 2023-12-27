<?php



echo phpinfo();


?>


/*
$(document).ready(function(){
$('#employeeRecord').click(function(){
		$('#buttonApp').css('display','block');
		$('#alltabframe').css('display','none');
		$('#dtat').css('display','none');
		$('#skuDetails_table_view').css('display','none');
		var id= '<?php echo $id; ?>';
		

			$.ajax({
				url:'../DataStore/server2.php',
				method:'POST',
				data:{employeeRecord:1,id:id},
				dataType:'JSON',
				success:function(aggre){
					$('#viewapprovalData').css('display','block');
					$('#empltable').DataTable().destroy();
					var table = $('#empltable').DataTable({
						data:aggre,
						columns:[
							{data:'staffid'},
							{data:'cadre'},
							{data:'qty'},
							{data:'month'},

							]

					});

				}

			});



});






		$('#aggregate_pull').click(function(){
			$('#buttonApp').css('display','block');
			$('#viewapprovalData').css('display','none');
			$('#dtat').css('display','none');
			$('#skuDetails_table_view').css('display','none');
			var id_rg= '<?php echo $id; ?>';
			//var id= 
			
			$.ajax({
				url:'../DataStore/server2.php',
				method:'POST',
				data:{aggregate_data:1,id_rg:id_rg},
				dataType:'JSON',
				success:function(aggre){
					$('#alltabframe').css('display','block');
//console.log(aggre);
					if ($.fn.DataTable.isDataTable('#alltab')) {
       				 $('#alltab').DataTable().destroy();
   						}
				//	$('#alltab').dataTable().destroy();
					var table = $('#alltab').dataTable({
						data:aggre,
						columns:[
							{data:'aggre_cadre'},
							{data:'aggre_qty'},
							{data:'aggre_total_count'},
							{data:'aggre_month'},
							{data:'aggre_year'}

							]

					});

				}

			});


});




		$('#skuDetails').click(function(){
			$('#buttonApp').css('display','block');
			$('#viewapprovalData').css('display','none');
			$('#alltabframe').css('display','none');
			$('#dtat').css('display','none');
			$('#skuDetails_table_view').css('display','none');
			var id_rg= '<?php echo $id; ?>';
			//var id= 
			
			$.ajax({
				url:'../DataStore/server2.php',
				method:'POST',
				data:{aggregate_data_skuDetails:1,id_rg:id_rg},
				dataType:'JSON',
				success:function(aggreSKU){
					$('#skuDetails_table_view').css('display','block');
console.log(aggreSKU);
					if ($.fn.DataTable.isDataTable('#skuDetails_table')) {
       				 $('#skuDetails_table').DataTable().destroy();
   						}
				//	$('#alltab').dataTable().destroy();
					var table = $('#skuDetails_table').dataTable({
						data:aggreSKU,
						columns:[
							{data:'cadre'},
							{data:'flavour'},
							{data:'total_flavour_qty'}
							]

					});

				}

			});


});












//approval page


$('#approvedata').click(function(){


if(confirm("You are about to approve this transaction,If you are, click OK button, else use the cancel button")){
	//alert("Yes I will approve it for you");


		var app_id='<?php echo $id; ?>';
		var app_mail='<?php echo $mail_id; ?>';

		//alert(app_id);
//alert(app_mail);

		$.ajax({
				url:'../DataStore/server2.php',
				method:'POST',
				data:{approveNoodles:1,app_id:app_id,app_mail:app_mail},
				dataType:'JSON',
				success:function(appNoodles){
					//alert("Not yet");
					alert(appNoodles);
					//location.reload();

				}



		});




}else{
	alert("I am not approving anything");
}

});




$('#rejectdata').click(function(){


if(confirm("You are about to reject this transaction,If you are, click OK button, else use the cancel button")){
	

	$('#reasonData').dialog({
		title:'Reason for rejection',
		resizable:false,
		height:330,
		width:250,
		modal: true,
		dialogClass: 'close',
		//autoOpen:false,
		closeOnEscape: false,
   
		//color:gold


		buttons:{

				'Confirm':function () {

				var app_id='<?php echo $id; ?>';
				var app_mail='<?php echo $mail_id; ?>';
				var reasonforrejection=$('#rejectionreason').val();
				//alert(reasonforrejection);

					$.ajax({
    			url:'../DataStore/server2.php',
    			method:'POST',
    			data:{rejectNoodles:1,app_id:app_id,app_mail:app_mail,reasonforrejection:reasonforrejection},
    			dataType:'JSON',
    			success:function(update_reject){
    				alert(update_reject);
    				location.reload();

					
    				//$('#Tag').html(ind.tagnumber);
    				//$('#num').html(ind.num);
    				//$('#type').html(ind.typeofuser);    				





    			}
    				

    		});
    		}


    		



		}
		
    		});










}else{
	alert("I am not reject anything");
}

});





	});*/





		<div class="col-sm-12">
<ul class="nav nav-tabs">
  <li class="nav-item">

    <a class="nav-link active" aria-current="page" href="#" id="aggregate_pull">Aggregate</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#"  id="employeeRecord">Employee Record</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#"  id="skuDetails">SKU Analysis</a>
  </li>
  
</ul>

</div>
</div>

<div class="row mt-3" id="viewapprovalData"  style="display:none;">

<table class="table" id="empltable" class="display" style="width:100%">
<thead style="background-color:black; color:white;">
	
	<td>EMPLOYEE ID</td>
	<td>CADRE</td>
	<td>QTY</td>
	<td>MONTH</td>



</thead>

</table>
</div>
<div class="row mt-3" id="alltabframe" style="display:none;" >

<table class="table" id="alltab" style="width:100%" class="display" style="display:none;">
		<thead style="background-color:brown;color: white; font-size: 12px;">
			<th>Cadre</th>
			<th>Cadre Num</th>
			<th>Total Count</th>
			<th>Month</th>
			<th>Year</th>
		</thead>
	
</table>
</div>
</div>



<div class="row mt-3" id="skuDetails_table_view" style="display:none;" >

<table class="table" id="skuDetails_table" style="width:100%" class="display" style="display:none;">
		<thead style="background-color:brown;color: white; font-size: 12px;">
			<th>CADRE</th>
			<th>SKU</th>
			<th>QTY</th>
			
		</thead>
	
</table>
</div>



<div class="row">


<div class="row" style="" id="buttonApp">
	<div class="col-sm-5 float-left mt-3">
		<button class="btn btn-danger" id="rejectdata">Reject</button>
		<button class="btn btn-primary" id="approvedata">Approve</button>
	</div>
	
	
</div>


<section  id="reasonData" style="display: none;">
	<div class="col-sm-6 ">
		<textarea cols="col-sm-6 form-control" rows="8" cols="" id="rejectionreason" ></textarea>
		
				

	</div>
	
</section>













$user_d=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[distribution_login] where staffid='$user'");
$user_d_name=sqlsrv_fetch_array($user_d,SQLSRV_FETCH_ASSOC);
$user_loc=$user_d_name['location'];
$sender_email=$user_d_name['username'];


$user_d_app=sqlsrv_query($db_conn,"SELECT * FROM [distribution].[dbo].[login] where location='$user_loc' and Dept='WSHE'");
$user_d_app_name=sqlsrv_fetch_array($user_d_app,SQLSRV_FETCH_ASSOC);
$reciver_mail=$user_d_app_name['email'];
$reciver_name=$user_d_app_name['Fullname'];
$id_convert=$sender_email."-".rand(10,999999999)."-".$user_loc."-".$reciver_mail."-".$d_month."-".$d_year."-".rand(1,19898989898);
$id_convert_new=$id_convert."-||||-".$doc_data;
$id_return_track=base64_encode($id_convert_new);


//	$hr_id=$hr."-lad-".$doc_id_data;

    $mail->SMTPDebug = 1;                                       // Enable verbose debug output
   $mail->isSMTP();
    //$mail->isMail();                                            // Send using SMTP
    $mail->Host       = '192.168.6.19';                // Set the SMTP server to send through
    $mail->SMTPSecure='TLS';
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
   $mail->Username   = 'Forms';                      // SMTP username
   $mail->Password   = 'Duf19P@$$';                         // SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 25;


$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];



     // Server settings
                                      // TCP port to connect to

    // Recipients
    $mail->setFrom('it.notifications@dufil.com', 'DUFIL FORMS');
    $mail->addAddress('tunde.afolabi@dufil.com', "Test Data"); 
    //$mail->addAddress($whse, "Test Data");      // Add a recipient
    $mail->addReplyTo('it.notifications@dufil.com', 'DUFIL FORMS');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    
    
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'NOODLES DISTRIBUTION RETURN FROM '.$user_d_name['Name'];
    $mail->Body    = nl2br("Dear ".$reciver_name.", \n\n Noodles return request have been triggered by ".$user_d_name['Name']." for you to approve the end of Noodles ditribution for the month and also serve as a request to return the remaining quantity not collected. Click <a href='localhost/distribution/documents/returnapproval.php?lambda=".$id_return_track."'>Click Here to Approve</a> to view and grant the approval");
    $mail->AltBody = " This mail is to be send to a recepient in Dufil Prima Foods Ltd";
	$mail->send();
   // if(!$mail->send()){
    //	echo json_encode("Error sending the mail, contact your system Administrator". $mail->ErrorInfo);
    //}else{
    	echo json_encode('Mail sent');
    	$dg=date("Y-m-d-H:i:s");
    
    	$insert_data_to_return="INSERT INTO [distribution].[dbo].[return_track_table] (sender_mail,receiver_mail,location,month,year,id_track_return,type,date_dt,doc_id) VALUES(?,?,?,?,?,?,?,?,?)";
    	$insert_data_to_return_pre=sqlsrv_prepare($db_conn,$insert_data_to_return,array($sender_email,$reciver_mail,$user_loc,$d_month,$d_year,$id_return_track,$re_type,$dg,$doc_data));
    	$insert_data_to_return_exe=sqlsrv_execute($insert_data_to_return_pre);

    	$update_staff_list=sqlsrv_query($db_conn,"UPDATE uploaded_staff_list set status='$return' where id='$doc_data' and status is null");
    	sqlsrv_execute($update_staff_list);
    	$update_return=sqlsrv_query($db_conn,"UPDATE [distribution].[dbo].[return_track_table] set status='$return' where doc_id='$doc_data' and status is null");
    	sqlsrv_execute($update_return);
   //}
 


