<?php include_once('../../include/head-forms.php');


?>


<div id="page-wrapper">

<!-- CONTENT SECTION -->
<!--=============================================================================================-->
<div class="container-fluid">

	<form class="form-horizontal" id="PRForm">
    <div class="form-group">
      <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">ID: </label>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <input readonly="" type="text" class="form-control input-sm" id="txtDocEntry" name="txtDocEntry">
      </div>

      
    </div>

		<div class="form-group">
			<label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">SAP User: </label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div id="txtUserCont" class="input-group">
          <input type="text" readonly class="form-control input-sm" id="txtUser" name="txtUser">
          <span class="input-group-addon" data-toggle="modal" data-target="#UserModal"><span class="glyphicon glyphicon-list"></span></span>
        </div>
	  	</div>

      <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">Department: </label>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <select class="form-control input-sm" id="cmbDepartment" name="cmbDepartment">
          <!--DOM-->
        </select>
      </div>

		</div>


		
		<div class="form-group">
			<label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">User Code: </label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<input type="text" class="form-control input-sm required" id="txtUserCode" name="txtUserCode">
		  </div>

      <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">SAP Username: </label>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <input type="text" class="form-control input-sm required" id="txtSAPUser" name="txtSAPUser">
      </div>
		  
		</div>


    <div class="form-group">
      <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">Name: </label>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <input type="text" class="form-control input-sm required" id="txtName" name="txtName">
      </div>

      <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">SAP Password: </label>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <input type="password" class="form-control input-sm required" id="txtSAPPass" name="txtSAPPass">
      </div>

       
    </div>

    <div class="form-group">
      <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">Password: </label>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <input type="password" class="form-control input-sm required" id="txtPassword" name="txtPassword">
      </div>


      <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">User Type: </label>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <select class="form-control input-sm" id="cmbUserType" name="cmbUserType">
          <option value="ADMIN">Admin</option>
          <option value="EMP">Employee</option>
        </select>
      </div>


      
    </div>


    <div class="form-group">
      <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">Repeat Password: </label>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <input type="password" class="form-control input-sm required" id="txtRepeatPassword" name="txtRepeatPassword">
      </div>


     <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">Role: </label>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <select class="form-control input-sm" id="cmbRole" name="cmbRole">
          <option></option>
          <!--DOM-->
        </select>
      </div>

      
    </div>


    

    <div id="ModDetails">
      <!--DOM-->
    </div>

    <br/>
    <div class="form-group">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type="button" class="btn btn-primary btn-sm" id="btnSelect" value="Select All">
        <input type="button" class="btn btn-danger btn-sm" id="btnClear" value="Clear Selection">
            
      </div>
    </div>
    


		



		


    <!--Form Footer-->
    <!--==========================================================-->
    <div class="navbar navbar-fixed-bottom">
      <div class="form-group">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-10 col-md-offset-10 col-sm-offset-10 col-xs-offset-10">
          <input type="button" class="btn btn-default btn-sm hidden" id="btnUpdate" value="Update">
          <input type="button" class="btn btn-primary btn-sm" id="btnSave" value="Save">
          
        </div>
      </div>
    </div>
    <!--End Form Footer-->
    <!--==========================================================-->

    <div id="addrowcont"><!--DOM--></div>

	</form>
	<!--End form-horizontal-->
	<!--==========================================================-->

</div>
    <!-- /.container-fluid -->
    <!-- CONTENT SECTION -->
    <!--=============================================================================================-->


<!-- MODAL SECTION -->
<!--=============================================================================================-->
<!-- loading modal  -->
<div id="modal-load-init" class="modal fade" data-keyboard="false" data-backdrop="static">
<div class="modal-dialog">
  <div class="modal-content">
    
    <div class="modal-header">
      <h4 class="modal-load-title">Processing Please wait...</h4>
    </div>
    <div class="modal-body">
      <div class="progress progress-striped active">
        <div id="progBar" class="progress-bar" style="width: 100%"></div>
      </div>

      <br />

    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /loading modal -->





<!-- Document List Modal  -->
<div class="modal fade bs-example-modal-lg" id="UserModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">User List</h4>

        <div class="row">

            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <input type="text" name="UserSearch" class="form-control input-sm" placeholder="Search..." />
            </div>
        </div>
        
      </div>
      <div class="modal-body" id="UserCont">
                                   <!--DOM-->
      </div>
      
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>
<!-- /Document List modal -->


<!-- Web User List Modal  -->
<div class="modal fade bs-example-modal-lg" id="WebUserModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">User List</h4>

        <div class="row">

            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <input type="text" name="WebUserSearch" class="form-control input-sm" placeholder="Search..." />
            </div>
        </div>
        
      </div>
      <div class="modal-body" id="WebUserCont">
                                   <!--DOM-->
      </div>
      
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>
<!-- /Web User modal -->



<!-- END MODAL SECTION -->
<!--=============================================================================================-->

<!--THis will show upon right click-->
<ul class='custom-menu'>
  <li data-action="second">Close</li>
  <li data-action="first">Cancel</li>
</ul>
<!--End THis will show upon right click-->

 </div>
<!-- /#page-wrapper -->

<?php include_once('../../include/close-body-forms.php') ?>
<script src="../../js/UM/um.js"></script>

