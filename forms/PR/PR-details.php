<?php $servicetype = $_GET['servicetype']; ?>

<?php if ($servicetype == 'I') { ?>
    <!--Item Details-->
    <!--==========================================================-->
    <!--<div class="table-responsive" style="height: 180px; width:100%; border: solid lightblue 1px;">-->
	<style>
	table th {
		position: -webkit-sticky; 
		position: sticky;
		top: 0;
		z-index: 10; 
		background: #fff;
		outline: thin solid lightblue;
	}
	</style>
		<table width="100%" border="1" id="tblDetails" bordercolor="lightblue">
            <thead>
                <tr>
                    <th style="min-width:50px; height:30px;">&nbsp;Row #</th>
                    <th style="min-width:200px;">&nbsp;Item Code</th>
                    <th style="min-width:400px;">&nbsp;Item Name</th>
                    <th style="min-width:200px;" class="hidden">&nbsp;Bar Code</th>
                    <th style="min-width:90px;">&nbsp;Required Qty</th>
					<th style="min-width:100px;">&nbsp;Whse / Project</th>
					<th style="min-width:90px;">&nbsp;Info Price</th>
					<th style="min-width:70px;">&nbsp;Discount</th>
                    <th style="min-width:200px;" class="hidden">&nbsp;UoM</th>
                    <th style="min-width:90px;" class="hidden">&nbsp;UoM Name</th>
                    <th style="min-width:70px;" class="hidden">&nbsp;Currency</th>
					<th style="min-width:150px;">&nbsp;Tax Code</th>
					<th style="min-width:90px;">&nbsp;Gross Price</th>
					
                    <th style="min-width:200px;" class="hidden">&nbsp;Tax Amount</th>
                    <th style="min-width:90px;">&nbsp;Total (LC)</th>
					<th style="min-width:200px;">&nbsp;Remarks</th>
                    <th style="min-width:200px;" class="hidden">Gross Total</th>
                    <th style="min-width:200px;" class="hidden">Line No.</th>
                    <th style="min-width:200px;" class="hidden">Free Text</th>
                </tr>
            </thead>
            <tbody>
                <!--DOM-->

            </tbody>
        </table>
   <!--</div>-->

    <!--End Item Details-->
    <!--==========================================================-->
<?php } else { ?>
    <!--Service Details-->
    <!--==========================================================-->
    	<table width="100%" border="1" id="tblDetails" bordercolor="lightblue">
            <thead>
                <tr>
                    <th style="min-width:50px; height:30px;">&nbsp;Row #</th>
                    <th style="min-width:200px;">&nbsp;Remarks</th>
                    <th style="min-width:100px;">&nbsp;Account Code</th>
                    <th style="min-width:250px;">&nbsp;Account Name</th>
                    <th style="min-width:90px;">&nbsp;Unit Price</th>
                    <th style="min-width:150px;">&nbsp;Vat Group</th>
                    <th style="min-width:90px;">&nbsp;Gross Price</th>
                    <th style="min-width:90px;">&nbsp;Tax Amount</th>
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