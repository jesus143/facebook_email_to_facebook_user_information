<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Facebook Api Email Checker!</title> 

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

 	<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js" ></script>
 	<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" ></script>
 	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" >

	<script type="text/javascript">
		$(document).ready(function() {
		    $('#facebook-profile-table').DataTable();
		} );
	</script> 

	<link rel="stylesheet" type="text/css" href="<?php echo $_SERVER['HTTP_HOST']; ?>/public/css/style.css">	
 
</head>
<body> 
 
<div class="container" style="padding:20px;"> 
 	<div class="alert alert-info">Facebook Email Checker!</div> 
	 

	<form method="POST" action="" > 

		<input type="text" name="full_name" class="form-control" placeholder="Full Name" value="" /> <br> 

		<textarea name="email_list" class="form-control" placeholder="Separate emails with comma." style="height:100px"></textarea>
		<br>
		<input type="submit" name="submit" value="Submit" class="alert btn-success" />
	</form> 

	<br> 

 	<div class="panel panel-default"> 
	    <div class="panel-body">
			<table id="facebook-profile-table" class="display" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		                <th style="width:100px">Profile Picture</th>
		                <th>Name/Email</th>
		                <th>Visit Messenger</th> 
		            </tr>
		        </thead>
		        <tfoot>
		            <tr> 
		                <th>Profile Picture</th>
		                <th>Name/Email</th>
		                <th>Visit Messenger</th>  
		            </tr>
		        </tfoot>
		        <tbody> 
					        <tr>
				                <td>
									<div class="media">
									  <div class="media-left"> 
									  	<iframe src="http://localhost/testing/socialgrower/facebook-email-converter-custom/debug/test/facebook-result.php"></iframe>
									  </div>
									</div>
				                </td>
				                <td> 
									<div class="media-body">
									    <h4 class="media-heading">This is email</h4> 
									</div> 
				                </td>
				                <td> 
					                	<a href="https://www.messenger.com/t/" target="_blank">
											<!-- <button class="fb-messenger-button"></button> --> 
											<img src="http://suite.social/coder/emails/messenger.png" style="height:51px;" />   
										</a>  
				                </td> 
				            </tr> 

		        </tbody>
		    </table>
	    </div>   
	</div>  
</div>
</body>
</html>