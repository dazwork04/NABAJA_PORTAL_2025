<?php $servicetype = 'I'; ?>

<?php if ($servicetype == 'I') { ?>
    <!--Item Details-->
    <!--==========================================================-->
    <table width="100%" border="1" id="tblDetails" bordercolor="lightblue">
            <thead>
                <tr>
					<th style="min-width:50px; height:30px;">&nbsp;Row #</th>
					<th style="min-width:200px;">&nbsp;Item Code</th>
					<th style="min-width:300px;">&nbsp;Item Name</th>
					<th style="min-width:50px;">&nbsp;Quantity</th>
					<th style="min-width:50px;">&nbsp;Item Cost</th>
					<th style="min-width:50px;">&nbsp;Warehouse</th>
					<th style="min-width:50px;" class="hidden">&nbsp;Total (LC)</th>
					<th style="min-width:50px;">&nbsp;Account Code</th>
					<th style="min-width:50px;" class="hidden">&nbsp;Total</th>
					<th style="min-width:50px;"></th>
                </tr>
            </thead>
            <tbody>
                <!--DOM-->

            </tbody>
        </table>
    <!--End Item Details-->
    <!--==========================================================-->
<?php } else { ?>
    <!--Service Details-->
    <!--==========================================================-->
		<table width="100%" border="1" id="tblDetails" bordercolor="lightblue">
            <thead>
                <tr>
                    <th style="min-width:50px;">Row #</th>
                    <th style="min-width:200px;">Remarks</th>
                    <th style="min-width:200px;">Account Code</th>
                    <th style="min-width:250px;">Account Name</th>
                    <th style="min-width:250px;">Item</th>
                    <th style="min-width:250px;">Vat Group</th>
                    <th style="min-width:250px;">Gross Price</th>
                    <th style="min-width:200px;">Tax Amount</th>
                    <th style="min-width:200px;" class="hidden">Line No.</th>
                </tr>
            </thead>
            <tbody>
                <!--DOM-->		
            </tbody>
        </table>
  

    <!--End Service Details-->
    <!--==========================================================-->

<?php } ?>