
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>DFSI</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link href="dist/css/animate.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dist/css/login.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
      <!-- Body Starts Here -->
      <div class="container">
        

        <!-- Sign In Form -->
          <form id="server-form" role="form">
            <div id="sign-in-form-content" class="col-lg-6 col-lg-offset-3">
              <div class="panel panel-default">
                <div class="panel-body">
                <!-- Panel Body -->
                  <br />
                  <br />

                  
                
                  <p class="sign-text text-center">New Server</p>
                  
                  <br />
               

                  <div class="row">
                    
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon" id="sizing-addon2"><span class="fa fa-server"></span></span>
                          <input type="text" class="form-control" placeholder="Server Name" name="ServerName">
                        </div>
                      </div>
                    </div>

                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                          <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon2"><i class="glyphicon glyphicon-hdd"></i></span>
                            <input type="text" class="form-control" placeholder="Port" name="Port">
                          </div>
                        </div>
                      </div>
                    
                  </div>



                  <div class="row">
                                        
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon" id="sizing-addon2"><i class="glyphicon glyphicon-user"></i></span>
                              <input type="text" class="form-control" placeholder="DB Username" name="DBUser">
                            </div>
                            
                          </div>
                        </div>


                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon" id="sizing-addon2"><i class="glyphicon glyphicon-lock"></i></span>
                              <input type="password" class="form-control" placeholder="DB Password" name="DBPass">
                            </div>
                            
                       
                          </div>
                        </div>

                    </div>


                    <div class="row">

                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                          <div class="form-group">
                            
                            <label class="control-label sr-only" for="inputGroupSuccess4">Input group with success</label>
                            <div class="input-group">
                              <span class="input-group-addon" id="sizing-addon2"><span class="fa fa-server"></span></span>
                              <select class="form-control" placeholder="Database Version" name="DBVersion">
                                  <option value="7">MSSQL 2012</option>
                                  <option value="4">MSSQL 2005</option>
                                  <option value="6">MSSQL 2008</option>
                                  
                                  <option value="8">MSSQL 2014</option>
                              </select>
                            </div>
                            

                          </div>
                        </div>
                    </div>

                  

               

                  <div id="alert-content"></div>
                  
                  
                  
                  <button type="submit" class="btn btn-default col-xs-3 col-md-3 col-sm-3 col-lg-3">Add</button>

                  <a href="/amort" title="Create new server" class="text-info col-lg-4 col-md-4 col-sm-4 col-xs-4 col-lg-offset-3 form-control-static" style="text-decoration:underline"><i class="fa fa-home fa-lg" aria-hidden="true"></i> Home</a>
              <!-- Panel Body Ends Here -->
              </div>
            </div>
          </div>
        </form>
      <!-- Body Ends Here -->

    </div><!--End Container-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="dist/js/jquery-2.1.3.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="dist/js/bootstrapValidator.min.js"></script>
    <script src="js/scripts-mod.js"></script>
    <script src="js/login.js"></script>

    <!--Notification DIV Javascript-->
    <script src="notificationdiv/notie.js"></script>
    <!--End-->
  </body>
</html>
