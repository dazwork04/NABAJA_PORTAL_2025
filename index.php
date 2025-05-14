<?php 
//include_once('sbo-common/script.php');
//Create Database and Table if not exist    
//createDatabase();
//End Create
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nabaja Land Corporation</title>
	
	<!-- Icon -->
	<link rel="icon" href="logo-ico.ico">

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bootstrap/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="bootstrap/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bootstrap/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<style>
	img {
		opacity: 0.5;
		filter: alpha(opacity=50); /* For IE8 and earlier */
	}
	</style>

</head>

<body>

    <div class="container">
		<div class="row">
            <div class="col-lg-4 col-md-4" style="text-align:center;">
				<!--<img src="img/Logo.jpg" alt="" width="1000px" height="200px">-->
            </div>
			<div class="col-lg-4 col-md-4">
                <form id="sign-in-form" role="form">
					<div id="sign-in-form-content" 
					<div class="login-panel panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">Please Sign In</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12 col-md-12">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" id="sizing-addon2"><i class="fa fa-user"></i></span>
											<input type="text" class="form-control" placeholder="Username" name="username" autofocus>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" id="sizing-addon2"><i class="fa fa-lock"></i></span>
											<input type="password" class="form-control" placeholder="Password" name="password">
										</div>
									</div>
								  </div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12">
									<div class="form-group">
										<a href="#" title="Advance option" data-toggle="collapse" data-target="#server" class="text-info"><i class="fa fa-cog fa-lg" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>
						
							<div id="server" class="collapse" aria-expanded="true">
								<div class="row">
									<div class="col-lg-12 col-md-12" id="ServerList-group">
										<div class="form-group has-feedback">
											<label class="control-label sr-only" for="inputGroupSuccess4">Input group with success</label>
												<div class="input-group">
													<span class="input-group-addon" id="sizing-addon2"><span class="fa fa-server"></span></span>
													<select class="form-control" placeholder="Server List" name="ServerList" data-bv-field="ServerList"></select><i class="form-control-feedback" data-bv-icon-for="ServerList" style="display: none;"></i>
												</div>
											<small data-bv-validator="notEmpty" data-bv-validator-for="ServerList" class="help-block" style="display: none;">Server is required.</small>
										</div>
									</div>
								</div>
								<div class="row">	
									<div class="col-lg-12 col-md-12" id="dbList-group">
										<div class="form-group has-feedback">
											<label class="control-label sr-only" for="inputGroupSuccess4">Input group with success</label>
											<div class="input-group">
												<span class="input-group-addon" id="sizing-addon2"><span class="fa fa-database"></span></span>
												<select class="form-control" placeholder="Server List" name="dbList">
												<option value="">-Select-</option>
												</select>
												<i class="form-control-feedback" style="display: none;"></i>
											</div>
										</div>
									</div>
								</div><!--End row-->
							</div><!--End collapse in-->
						  
							<div id="alert-content"></div>
						  
							<div class="row">
								<div class="col-lg-12 col-md-12">
									<button type="submit" class="btn btn-default">Sign In</button>
								</div>
							</div>
							<!-- <a href="frm-reg-new-server.php" title="Create new server" class="text-info col-lg-12 col-md-12 col-sm-4 col-xs-4 col-lg-offset-3 form-control-static" style="text-decoration:underline"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i> New Server</a>
							Panel Body Ends Here -->
						</div>
					</div>
					</div>
				</form>
            </div>
			<div class="col-lg-4 col-md-4" style="text-align:center;">
                
            </div>
        </div>
    </div>
	
	<script src="dist/js/jquery-2.1.3.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="dist/js/bootstrapValidator.min.js"></script>
    <script src="js/scripts-mod.js"></script>
    

     <!--Notification DIV Javascript-->
    <script src="notificationdiv/notie.js"></script>
    <!--End-->

    <script src="js/login.js"></script>

    <!-- jQuery 
    <script src="bootstrap/vendor/jquery/jquery.min.js"></script>-->

    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bootstrap/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="bootstrap/dist/js/sb-admin-2.js"></script>

</body>

</html>
