$(window).load(function() {
	//Intialize Modal
	$('#modal-load-init').modal('show');


	//Global Variables
	var cback = 1;
	var activemod = '';
	var mode = 'Add';
	//End Global Variables

	$('select[name=selCompany]').html('<option>Loading...</option>');
    $('select[name=selCompany]').load('proc/views/PR/vw_company.php', function () 
	{
	/* 	var CompanyDb = $(this).val();
		
		$('select[name=txtSalesEmployee]').html('<option>Loading...</option>');
		$('select[name=txtSalesEmployee]').load('../../proc/views/APV/vw_salesemployee.php?CompanyDb=' + CompanyDb);
		
		$('select[name=txtPayment]').html('<option>Loading...</option>');
		$('select[name=txtPayment]').load('../../proc/views/APV/vw_paymentterms.php?CompanyDb=' + CompanyDb); */
	});

	//=======================================================================================================
	//Javascript Code here
	//=======================================================================================================
	
	//Side Items Click - Load Form
	$(document.body).on('click','.side-nav > li a',function(){
		//Load Modal
		//$('#modal-load-init').modal('show');
		//End Load Modal

		//Variables
		activemod = $(this).attr('href');
		//End Variables

		//remove class
		$('.sidemod').children('li').removeClass('active');
		//add class
		$(this).parent().addClass('active');

		if(activemod == '#SalesQuotation'){
			window.open("forms/SQ/SQ.php", "", "");
			cback = 0;
		}else if(activemod == '#SalesOrder'){
			window.open("forms/SO/SO.php", "", "");
			cback = 0;
		}else if(activemod == '#Delivery'){
			window.open("forms/DR/DR.php", "", "");
			cback = 0;
		}else if(activemod == '#PurchaseRequest'){
			window.open("forms/PR/PR.php", "", "");
			cback = 0;
		}else if(activemod == '#PurchaseOrder'){
			window.open("forms/PO/PO.php", "", "");
			cback = 0;
		}else if(activemod == '#Grpo'){
			window.open("forms/GRPO/GRPO.php", "", "");
			cback = 0;
		}else if(activemod == '#GoodsIssue'){
			window.open("forms/GI/GI.php", "", "");
			cback = 0;
		}else if(activemod == '#GoodsReceipt'){
			window.open("forms/GR/GR.php", "", "");
			cback = 0;
		}else if(activemod == '#InventoryTransfer'){
			window.open("forms/IT/IT.php", "", "");
			cback = 0;
			}else if(activemod == '#InventoryTransferRequest'){
			window.open("forms/ITR/ITR.php", "", "");
			cback = 0;
		}else if(activemod == '#UserManagement'){
			window.open("forms/UM/UM.php", "", "");
			cback = 0;
		}else if(activemod == '#RandP'){
			window.open("forms/RP/RP.php", "", "");
			cback = 0;
		
		}else if(activemod == '#AppPO'){
			window.open("forms/PO/AppPO.php", "", "");
			cback = 0;
		
		}else if(activemod == '#AppPR'){
			window.open("forms/PR/PR-Approval1.php", "", "");
			cback = 0;
		}else if(activemod == '#AppPR2'){
			window.open("forms/PR/PR-Approval2.php", "", "");
			cback = 0;
		
		}else if(activemod == '#AppSO'){
			window.open("forms/SO/AppPO.php", "", "");
			cback = 0;
		}else if(activemod == '#AppSO2'){
			window.open("forms/SO/AppSO.php", "", "");
			cback = 0;
		}else if(activemod == '#AppSO3'){
			window.open("forms/SO/AppSO2.php", "", "");
			cback = 0;
		}else if(activemod == '#CP'){
			window.open("forms/UM/CP.php", "", "");
			cback = 0;
		}else if(activemod == '#INV_RPT'){
			window.open("forms/INV_RPT/InvRpt.php", "", "");
			cback = 0;
		}else if(activemod == '#ServiceCall'){
			window.open("forms/SC/SC.php", "", "");
			cback = 0;
		}else if(activemod == '#logs'){
			window.open("forms/LOGS/logs.php", "", "");
			cback = 0;
		}else if(activemod == '#IP'){
			window.open("forms/IP/IP.php", "", "");
			cback = 0;
		}else if(activemod == '#SI'){
			window.open("forms/SI/SI.php", "", "");
			cback = 0;
		}else if(activemod == '#APV'){
			window.open("forms/APV/APV.php", "", "");
			cback = 0;
		}else if(activemod == '#OP'){
			window.open("forms/OP/OP.php", "", "");
			cback = 0;
		}else if(activemod == '#BP'){
			window.open("forms/BP/BP.php", "", "");
			cback = 0;
		}else if(activemod == '#ITM'){
			window.open("forms/ITM/ITM.php", "", "");
			cback = 0;
		}else if(activemod == '#CV'){
			window.open("forms/UM/CV.php", "", "");
			cback = 0;
		}else if(activemod == '#APDP'){
			window.open("forms/APDP/APDP.php", "", "");
			cback = 0;
		}else if(activemod == '#JE'){
			window.open("forms/JE/JE.php", "", "");
			cback = 0;
		}else if(activemod == '#CC'){
			window.open("forms/CC/CC.php", "", "");
			cback = 0;
		}else if(activemod == '#PS'){
			window.open("forms/PS/PS.php", "", "");
			cback = 0;
		}else if(activemod == '#APCM'){
			window.open("forms/APCM/APCM.php", "", "");
			cback = 0;
		}else if(activemod == '#ARDP'){
			window.open("forms/ARDP/ARDP.php", "", "");
			cback = 0;
		}else if(activemod == '#ARCM'){
			window.open("forms/ARCM/ARCM.php", "", "");
			cback =0;
		}
    
		
	})
	//End side Items Click - Load Form
	
	//=======================================================================================================
	//End javascript Code
	//=======================================================================================================
	//Hide Intialize Modal after loading all the javascript
	
	var readyStateCheckInterval = setInterval(function() {
	    if (document.readyState === "complete") {
	        clearInterval(readyStateCheckInterval);
	        $('#modal-load-init').modal('hide');
	    }
	}, 10);


})//end window.load




$(document).ready(function() {
	//Initialize Datetimepicker
	$('#txtDateTo').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
	})
	
	//Initialize Datetimepicker
	$('#txtDateFrom').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
	})
	
	$('select[name=selWhse]').html('<option>Loading...</option>');
    $('select[name=selWhse]').load('proc/views/it/vw_warehouse.php');
	
	$('select[name=selItemGroup]').html('<option>Loading...</option>');
    $('select[name=selItemGroup]').load('proc/views/it/vw_itemgroup.php');
	
	$(document.body).on('click','#btnGrpt',function(){
		
		var err = 0;
    	var errmsg = '';
		var datefrom = $('input[name=txtDateFrom]').val();
		var dateto = $('input[name=txtDateTo]').val();
		
		//Check if fields are blank
    	$('.required').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	//End Check if fields are blank
		
		if(err == 0){
			window.open("report/rpt/prm-report.php?datefrom=" + encodeURI(datefrom) + "&dateto=" + encodeURI(dateto), "", "width=1130,height=550,left=220,top=110");
		}
	});
	
	$(document.body).on('click','#btnInvRpt',function(){
		
		var err = 0;
    	var errmsg = '';
		var datefrom = $('input[name=txtDateFromInv]').val();
		var dateto = $('input[name=txtDateToInv]').val();
		
		//Check if fields are blank
    	$('.required').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	//End Check if fields are blank
		
		if(err == 0){
			window.open("report/rpt/inv-report.php?datefrom=" + encodeURI(datefrom) + "&dateto=" + encodeURI(dateto), "", "width=1130,height=550,left=220,top=110");
		}
	});
	
	$(document.body).on('click','#btnInsExe',function(){
		
		var err = 0;
    	var errmsg = '';
		var selWhse = $('select[name=selWhse]').val();
		var selItemGroup = $('select[name=selItemGroup]').val();
		
		//Check if fields are blank
    	$('.insrequired').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	//End Check if fields are blank
		
		if(err == 0){
			window.open("report/exe/ins-report.php?selWhse=" + encodeURI(selWhse) + "&selItemGroup=" + encodeURI(selItemGroup), "", "width=1130,height=550,left=220,top=110");
		}
	});
	
	$(document.body).on('click','#btnInsRpt',function(){
		
		var err = 0;
    	var errmsg = '';
		var selWhse = $('select[name=selWhse]').val();
		var selItemGroup = $('select[name=selItemGroup]').val();
		
		//Check if fields are blank
    	$('.insrequired').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	//End Check if fields are blank
		
		if(err == 0){
			window.open("report/rpt/ins-report.php?selWhse=" + encodeURI(selWhse) + "&selItemGroup=" + encodeURI(selItemGroup), "", "width=1130,height=550,left=220,top=110");
		}
	});
	
	$(document.body).on('click','#btnExcel',function(){
		
		var err = 0;
    	var errmsg = '';
		var datefrom = $('input[name=txtDateFrom]').val();
		var dateto = $('input[name=txtDateTo]').val();
		
		//Check if fields are blank
    	$('.required').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	//End Check if fields are blank
		
		if(err == 0){
			window.open("report/SRpt/sr-excel-report.php?datefrom=" + encodeURI(datefrom) + "&dateto=" + encodeURI(dateto), "", "width=1130,height=550,left=220,top=110");
		}
	});

	$(document.body).on('click','#btnEwt2307',function()
	{
		var err = 0;
    	var errmsg = '';
		var txtAPVRefNo = $('input[name=txtAPVRefNo]').val();
		var txtAuthoRep = $('input[name=txtAuthoRep]').val();
		var txtDesignation = $('input[name=txtDesignation]').val();
		var txtTIN = $('input[name=txtTIN]').val();
		
		var host = window.location.hostname;
		
		
    	$('.ewtrequired').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	//End Check if fields are blank
		
		if(err == 0)
		{
			if(host == '115.147.59.139')
			{
				window.open("http://115.147.59.139:44395/SAPCrystalReport/Report/EWT2307/" + encodeURI(txtAPVRefNo) + "/" + encodeURI(txtAuthoRep) + "/" + encodeURI(txtDesignation) + "/" + encodeURI(txtTIN), "", "width=1130,height=550,left=220,top=110");
			}
			else
			{
				window.open("http://pcdc-svr:44395/SAPCrystalReport/Report/EWT2307/" + encodeURI(txtAPVRefNo) + "/" + encodeURI(txtAuthoRep) + "/" + encodeURI(txtDesignation) + "/" + encodeURI(txtTIN), "", "width=1130,height=550,left=220,top=110");
			}
		}
	});
	
	$(document.body).on('click','#btnAPAging',function()
	{
		var err = 0;
    	var errmsg = '';
		var txtAsOfDate = $('input[name=txtAsOfDate]').val();
		
		var json = '';
        var otArr = [];
        var tbl2 = $('#tblListofvendor tbody tr').each(function (i) 
		{
            x = $(this).children();
            var itArr = [];
            if ($(this).find('input.itemselected').prop('checked') == true)
            {
				itArr.push('' + $(this).find('input.itemselected').val() + '');
               
                otArr.push('[' + itArr.join(',') + ']');
            }
        });
        
		json += otArr.join(",");
        
		if(json == '')
		{
			err += 1;
            errmsg = 'Please select one or more supplier.';
		}
		
		var host = window.location.hostname;
		
		$('.asofdateaprequired').each(function()
		{
    		if($(this).val() == '')
			{
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}
			else
			{
    			$(this).parent().removeClass('has-error');
    		}
    	});
		
		if(err == 0)
		{
			if(host == '115.147.59.139')
			{
				
				window.open("http://115.147.59.139:44395/SAPCrystalReport/Report/APAging/" + json + "/" + encodeURI(txtAsOfDate), "", "width=1130,height=550,left=220,top=110");
			}
			else
			{
				window.open("http://pcdc-svr:44395/SAPCrystalReport/Report/APAging/" + json + "/" + encodeURI(txtAsOfDate), "", "width=1130,height=550,left=220,top=110");
			}
		}
		else
		{
			notie.alert(3, errmsg, 3);
		}
	});
	
	$(document.body).on('click','#btnARAging',function()
	{
		var err = 0;
    	var errmsg = '';
		var txtAsOfDate = $('input[name=txtAsOfDate]').val();
		
		var json = '';
        var otArr = [];
        var tbl2 = $('#tblListofcustomer tbody tr').each(function (i) 
		{
            x = $(this).children();
            var itArr = [];
            if ($(this).find('input.itemselected').prop('checked') == true)
            {
				itArr.push('' + $(this).find('input.itemselected').val() + '');
               
                otArr.push('[' + itArr.join(',') + ']');
            }
        });
        
		json += otArr.join(",");
        
		if(json == '')
		{
			err += 1;
            errmsg = 'Please select one or more customer.';
		}
		
		var host = window.location.hostname;
		
		$('.asofdatearrequired').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	//End Check if fields are blank
		
		if(err == 0)
		{
			if(host == '115.147.59.139')
			{
				
				window.open("http://115.147.59.139:44395/SAPCrystalReport/Report/ARAging/" + json + "/" + encodeURI(txtAsOfDate), "", "width=1130,height=550,left=220,top=110");
			}
			else
			{
				window.open("http://pcdc-svr:44395/SAPCrystalReport/Report/ARAging/" + json + "/" + encodeURI(txtAsOfDate), "", "width=1130,height=550,left=220,top=110");
			}
		}
		else
		{
			notie.alert(3, errmsg, 3);
		}
	});
	
	$('#apaging-modal').on('shown.bs.modal',function()
	{
		$('#ListVendor').html('<p class="text-center"><img src=img/ajax-loader.gif /> Loading...</p>');
		$('#ListVendor').load('proc/views/vw_listofvendor.php');
	});
	
	$('#araging-modal').on('shown.bs.modal',function()
	{
		$('#ListCustomer').html('<p class="text-center"><img src=img/ajax-loader.gif /> Loading...</p>');
		$('#ListCustomer').load('proc/views/vw_listofcustomer.php');
	});
	
	$(document.body).on('click', '#selectAll', function (e) 
	{
		$('input:checkbox').prop('checked', this.checked);    
    });
})//end window.load