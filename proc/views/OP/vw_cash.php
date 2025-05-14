<?php include_once('../../../config/config.php');
?>
<form class="form-horizontal">
<div class="form-group">
    <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">GL Account: </label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div id="txtGLAccountCont" class="input-group">
            <input type="text" class="form-control input-sm required" id="txtGLAccount" name="txtGLAccount">
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">Primary Form Item: </label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <select class="form-control input-sm" id="txtPrimaryFormItem" name="txtPrimaryFormItem">
            <option value="1">Payments for Invoices from Customers</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-lg-2 col-md-2 col-sm-2 col-xs-2">Total Cash: </label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <input type="text" class="form-control input-sm" id="txtTotalCash" name="txtTotalCash">
    </div>
</div>
</form>
<br />