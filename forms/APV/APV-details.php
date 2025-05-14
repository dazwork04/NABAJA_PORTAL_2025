<?php $servicetype = $_GET['servicetype']; ?>

<?php if($servicetype == 'I'){ ?>
	<!--Item Details-->
	<!--==========================================================-->
		<table width="100%" border="1" id="tblDetails" bordercolor="lightblue">
			<thead>
				<tr>
					<th style="min-width:50px; height:30px;">&nbsp;Row #</th>
					<th style="min-width:200px;">&nbsp;Item Code</th>
					<th style="min-width:300px;">&nbsp;Item Name</th>
					<th style="min-width:100px;">&nbsp;Quantity</th>
					<th style="min-width:100px;">&nbsp;Uom</th>
					<th style="min-width:100px;">&nbsp;Price</th>
					<th style="min-width:100px;">&nbsp;Warehouse</th>
					<th style="min-width:100px;">&nbsp;Tax Code</th>
					<th style="min-width:100px;">&nbsp;Discount</th>
					<th style="min-width:100px;">&nbsp;Gross Price</th>
					<th style="min-width:100px;" class="hidden">&nbsp;Tax Amount</th>
					<th style="min-width:100px;">&nbsp;Line Total</th>
					<th style="min-width:150px;">&nbsp;Remarks</th>
					<th style="min-width:100px;" class="hidden">&nbsp;Gross Total</th>
					<th style="min-width:100px;">&nbsp;WTax</th>
					<th style="min-width:200px;" class="hidden">&nbsp;Department</th>
					<th style="min-width:200px;">&nbsp;Project</th>
					<th style="min-width:200px;" class="hidden">&nbsp;Employees</th>
					<th style="min-width:200px;" class="hidden">&nbsp;Equipment</th>
					<th style="min-width:20px;" class="hidden">&nbsp;Line No.</th>
					<th style="min-width:20px;" class="hidden">&nbsp;Free Text</th>
					
				</tr>
			</thead>
			<tbody>
				<!--DOM-->
						
			</tbody>
		</table>

	<!--End Item Details-->
	<!--==========================================================-->
<?php }else{ ?>
	<!--Service Details-->
	<!--==========================================================-->
	<table width="100%" border="1" id="tblDetails" bordercolor="lightblue">
			<thead>
				<tr>
                    <th style="min-width:50px; height:30px;">&nbsp;Row #</th>
                    <th style="min-width:200px;">&nbsp;Remarks</th>
                    <th style="min-width:100px;">&nbsp;Account Code</th>
                    <th style="min-width:250px;">&nbsp;Account Name</th>
                    <th style="min-width:100px;">&nbsp;Unit Price</th>
                    <th style="min-width:150px;">&nbsp;Vat Group</th>
                    <th style="min-width:100px;">&nbsp;Gross Price</th>
                    <th style="min-width:100px;">&nbsp;Tax Amount</th>
					<th style="min-width:100px;">&nbsp;WTax</th>
					<th style="min-width:200px;" class="hidden">&nbsp;Department</th>
					<th style="min-width:200px;">&nbsp;Project</th>
					<th style="min-width:200px;" class="hidden">&nbsp;Employees</th>
					<th style="min-width:200px;" class="hidden">&nbsp;Equipment</th>
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