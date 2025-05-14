$(window).load(function () {

//Trigger Base Entry
    $('input[name=txtBaseEntry]').trigger('keyup');
    //End Trigger BaseEntry
$('#window-title').text('Receipt');
})//end window.load

function reload() {
    location.reload();
}

function isNumberKey(event)
{
	var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) 
	{
        return true;
    } 
	else if ( key < 48 || key > 57 ) 
	{
        return false;
    }
	else 
	{
    	return true;
    }
}

$(document).ready(function () {

//Intialize Modal
    $('#modal-load-init').modal('show');
    //Global Variables
    var cback = 1;
    var activemod = '';
    var mode = 'Add';
    var servicetype = 'I';
    var activewhs = '';
    var isSinglePR = true;
    //End Global Variables

    //Initialize Datetimepicker
	$('#txtPostingDate').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
	})
	$('#txtDueDate').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
	})

	$('#txtDocDate').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
    })
	
	$('#txtCheckDueDate').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
    })
	
	$('#txtTransferDate').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
    })
	
	$('#txtValidUntil').datetimepicker({
	    format: 'MM/DD/YYYY',
    })
	
	$('#window-title').text('Receipt');
    //Initialize Title
    $('#mod-title').text('Receipt');
    //End Initialize Title


    //Load Grpo Series 
    $('#btnSeries').html('Loading...')
    $('#SeriesList').load('../../proc/views/IP/vw_series.php?objtype=24', function () {
        $('#btnSeries').html($('.series:first').attr('val-seriesname'));
        $('#btnSeries').attr('series-val', $('.series:first').attr('val-series'));
        $('#btnSeries').attr('bplid-val', $('.series:first').attr('val-bplid'));
        $('input[name=txtDocNo]').val($('.series:first').attr('val-nextnum'));
    });
    //End Load Grpo Series
	
	$('select[name=selPaymentMethod]').html('<option>Loading...</option>');
		$('select[name=selPaymentMethod]').load('../../proc/views/IP/vw_paymentmethod.php');
		
		$('select[name=selCreditCardName]').html('<option>Loading...</option>');
		$('select[name=selCreditCardName]').load('../../proc/views/IP/vw_creditcardname.php');
		
		$('select[name=txtCountry]').html('<option>Loading...</option>');
		$('select[name=txtCountry]').load('../../proc/views/IP/vw_countries.php');
		
		$('select[name=txtSalesEmployee]').html('<option>Loading...</option>');
		$('select[name=txtSalesEmployee]').load('../../proc/views/IP/vw_salesemployee.php');
		
		$('select[name=txtPayment]').html('<option>Loading...</option>');
		$('select[name=txtPayment]').load('../../proc/views/IP/vw_paymentterms.php');
			
	loadDefaultAccounts();
    
	$(document.body).on('change','input[name=radCategory]',function()
	{
		radCategory = $(this).val();
		
		if(radCategory == 'Account')
		{
			$('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /></p>');
			$('#ModDetails').load('../../forms/IP/IP-details1.php',function()
			{
				$('#tblDetails tbody tr:last').find('select.taxgroup').html('<option>Loading...</option>');
				$('#tblDetails tbody tr:last').find('select.taxgroup').load('../../proc/views/IP/vw_taxgroup.php');
			});
			
			$('#btnSelectAll').addClass('hidden');
			$('#btnDeselectAll').addClass('hidden');
			$('#btnAddInSequence').addClass('hidden');
			
			$('#btnAddRow').removeClass('hidden');
			$('#btnDelRow').removeClass('hidden');
			
			$('#trPaymentAccount').addClass('hidden');
			$('#trOpenBalance').addClass('hidden');
			
			$('#trNetTotal').removeClass('hidden');
			$('#trTotalTax').removeClass('hidden');
			
			$('input[name=txtTotalAmountDue]').val('');
			$('input[name=txtOpenBalance]').val('');
			
			//$('#trBpCode').addClass('hidden');
			//$('#trBpName').addClass('hidden');
			$('#trBillTo').addClass('hidden');
		}
		else
		{
			$('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /></p>');
			$('#ModDetails').load('../../forms/IP/IP-details.php');
			
			$('#btnSelectAll').removeClass('hidden');
			$('#btnDeselectAll').removeClass('hidden');
			$('#btnAddInSequence').removeClass('hidden');
			
			$('#btnAddRow').addClass('hidden');
			$('#btnDelRow').addClass('hidden');
			
			$('#trPaymentAccount').removeClass('hidden');
			$('#trOpenBalance').removeClass('hidden');
			
			$('#trNetTotal').addClass('hidden');
			$('#trTotalTax').addClass('hidden');
			
			$('input[name=txtTotalAmountDue]').val(computeTotalAmountDue());
			
			//$('#trBpCode').removeClass('hidden');
			//$('#trBpName').removeClass('hidden');
			$('#trBillTo').removeClass('hidden');
		}
	});

	//Credit Card Name to load GL Account
	$(document.body).on('change','select[name=selCreditCardName]',function()
	{
		CreditCard = $(this).val();
		
		$('input[name=txtGLAccountCreditCard').html('Loading...');
		
		$('input[name=txtGLAccountCreditCard]').load('../../proc/views/IP/vw_creditcardglaccount.php?creditcard=' + CreditCard , function (data) 
		{
			$('input[name=txtGLAccountCreditCard]').val(data).prop('disabled',true);
		});
	})	
	//End Credit Card Name to load GL Account

    $(document.body).on('change', '#txtCountry', function () 
	{
        var selectedcountry = $(this).val();
		
        $('select[name=txtBankName]').html('<option>Loading...</option>');
        $('select[name=txtBankName]').load('../../proc/views/IP/vw_banks.php?countrycode=' + selectedcountry , function () {

        })
        $('select[name=txtBankName]').trigger('change');
        $('select[name=txtBranch]').trigger('change');
    });

    $(document.body).on('change', '#txtBankName', function () {
        var selectedcountry = $('select[name=txtCountry]').val();
        var selectedbankcode = $(this).val();
        var selectedcustomer = $('input[name=txtCustomer]').val();
		
        $('select[name=txtBranch]').html('<option>Loading...</option>');
        $('select[name=txtBranch]').load('../../proc/views/IP/vw_bankbranches.php?countrycode=' + selectedcountry
                + '&bankcode=' + selectedbankcode
                + '&customercode=' + selectedcustomer
				
                , function () {
                });
        $('select[name=txtBranch]').trigger('change');
    });

    $(document.body).on('change', '#txtBranch', function () {
        var checkaccount = $('option:selected', this).attr('aria-checkaccount');
        $('input[name=txtCheckAccount]').val(checkaccount);
    });

    //On change series 
    $(document.body).on('click', '.series', function () {
        $('#btnSeries').html($(this).attr('val-seriesname'));
        $('#btnSeries').attr('series-val', $(this).attr('val-series'));
        $('#btnSeries').attr('bplid-val', $(this).attr('val-bplid'));
        $('input[name=txtDocNo]').val($(this).attr('val-nextnum'));
    })
    //End On change series

    //Auto Delivery Date 
    $(document.body).on("dp.change", "#txtPostingDateCont", function (e) {
        var dateselected = $('input[name=txtPostingDate]').val();
        var d = new Date(dateselected);
        var weekday = new Array(7);
        weekday[0] = 0;
        weekday[1] = 1;
        weekday[2] = 2;
        weekday[3] = 3;
        weekday[4] = 4;
        weekday[5] = 5;
        weekday[6] = 6;
        $('input[name=txtDueDate]').val(addDays(dateselected, 30));
        //alert(weekday[d.getDay()])

    })
    //End Auto Delivery Date

    loadDetails();
    //Service Type Change
    function loadDetails() 
	{
		
        $('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /></p>');
        $('#ModDetails').load('../../forms/IP/IP-details.php', function () {
            //Clear value Total Before Discount
            $('input[name=txtPaymentOnAccount]').val('');
            $('input[name=txtTotalAmountDue]').val('');
            $('input[name=txtOpenBalance]').val('');
            //End Clear value Total Before Discount
            cback = 0;
        });
    }
    
    function loadDefaultAccounts()
    {
        var defaultaccounts = getdefaultaccounts();
        $('input[name=txtGLAccountCheck]').val(defaultaccounts[0]);
        $('input[name=txtGLAccountCheckName]').val(defaultaccounts[1]);
        $('input[name=txtGLAccountCash]').val(defaultaccounts[2]);
        $('input[name=txtGLAccountCashName]').val(defaultaccounts[3]);
    }

    //Add Row
    $(document.body).on('click', '#btnAddRow', function () 
	{
        $(this).prop('disabled', true);
        
        var rowno = 0;
        rowno = ($('#tblDetails tbody tr:last').find('td.rowno').text() == '') ? 1 : parseFloat($('#tblDetails tbody tr:last').find('td.rowno').text()) + 1;
        //End row number
        if (servicetype == 'I') {
            //Item Type
            $(this).load('../../forms/IP/IP-details-row.php?servicetype=I', function (result) {
                $('#tblDetails tbody').append(result);
             
                $('#tblDetails tbody tr:last').find('td.rowno').html(rowno);
				$('#tblDetails tbody tr:last').find('select.taxgroup').html('<option>Loading...</option>');
				$('#tblDetails tbody tr:last').find('select.taxgroup').load('../../proc/views/IP/vw_taxgroup.php');
               
                $(this).empty();
                $(this).prop('disabled', false);
            })
            //End Item Type

        } else {
            //Service Type
            $(this).load('../../forms/IP/IP-details-row.php?servicetype=S', function (result) {
                $('#tblDetails tbody').append(result);
                //Set Row Number
                $('#tblDetails tbody tr:last').find('td.rowno').html('<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> ' + rowno);
                //End Set Row Number

                //Set Header Fixed
                //$("#tblDetails").tableHeadFixer({"left" : 4});
                //End Set Header Fixed
                $(this).empty();
                $(this).prop('disabled', false);
            })
            //End Service Type

        }
    });
    //End Add Row



    //Add Free Text
    $(document.body).on('click', '#btnFreeText', function () {
        $(this).prop('disabled', true);
        //generate row number
        var rowno = 0;
        rowno = ($('#tblDetails tbody tr:last').find('td.rowno').text() == '') ? 1 : parseFloat($('#tblDetails tbody tr:last').find('td.rowno').text()) + 1;
        //End row number
        if (servicetype == 'I') {
            //Item Type
            $(this).load('../../forms/IP/IP-details-row.php?servicetype=I&freetext=1', function (result) {

                $('#tblDetails tbody').append(result);
                //Set Row Number
                $('#tblDetails tbody tr:last').find('td.rowno').html(rowno);
                //End Set Row Number



                //Set Header Fixed
                //$("#tblDetails").tableHeadFixer({"left" : 4});
                //End Set Header Fixed
                $(this).empty();
                $(this).prop('disabled', false);
            })
            //End Item Type

        }

    })
    //End Add Free Text

    //Delete Row
    $(document.body).on('click', '#btnDelRow', function () {
        $('.selected-det').remove();
        $('input[name=TotBefDisc]').trigger('keyup');
        //Reloop to adjust row number
        var rowno = 1;
        $('#tblDetails tbody tr').each(function () {
            ftext = $(this).find('.ftext').text();
            if (ftext == 'Y') {
                $(this).find('td.rowno').html(rowno);
            } else {
                $(this).find('td.rowno').html('<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> ' + rowno);
            }

            rowno += 1;
        })
        //End Reloop to adjust row number
    })

    //End Delete Row



    //Add selected class on row when focused on input
    $(document.body).on('focus', '#tblDetails input, #tblDetails select, #tblDetails textarea', function () {
        if (window.event.ctrlKey) {

            $(this).closest('tr').css("background-color", "lightgray");
            $(this).closest('tr').addClass('selected-det');
        } else {
            $('.selected-det').map(function () {
                $(this).removeClass('selected-det');
            })

            $('#tblDetails tbody > tr').css("background-color", "transparent");
            $(this).closest('tr').css("background-color", "lightgray");
            $(this).closest('tr').addClass('selected-det');
        }
    })
    //End Add selected class on row when focused on input



    //Add selected class on row when click on tr
    $(document.body).on('click', '#tblDetails tbody > tr > td.rowno', function () {
        if (window.event.ctrlKey) {

            //Check if selected
            if ($(this).closest('tr').hasClass('selected-det')) {
                $(this).closest('tr').css("background-color", "transparent");
                $(this).closest('tr').removeClass('selected-det');
            } else {
                $(this).closest('tr').css("background-color", "lightgray");
                $(this).closest('tr').addClass('selected-det');
            }
            //End

        } else {
            $('.selected-det').map(function () {
                $(this).removeClass('selected-det');
            })

            $('#tblDetails tbody > tr').css("background-color", "transparent");
            $(this).closest('tr').css("background-color", "lightgray");
            $(this).closest('tr').addClass('selected-det');
        }
    })
    //End Add selected class on row when click on tr


    //Add selected class on row when input-group-addon is click
    $(document.body).on('click', '#tblDetails > tbody .input-group-addon', function () {
        $('.selected-det').map(function () {
            $(this).removeClass('selected-det');
            $(this).css("background-color", "transparent");
        })
        $(this).closest('tr').css("background-color", "lightgray");
        $(this).closest('tr').addClass('selected-det');
    })
    //End Add selected class on row when input-group-addon is click


    //Load Item
    $('#ItemModal').on('shown.bs.modal', function () {


        $('#ItemCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#ItemCont').load('../../proc/views/IP/vw_itemlist.php', function () {
            //Add Scroll Function 
            $('#ItemCont .table-responsive').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 20) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#ItemCont table tbody > tr:last').children('td').eq(0).text();
                        var ctr = 0;
                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
                            $.ajax({
                                type: 'POST',
                                url: '../../proc/views/IP/vw_itemlist-load.php',
                                data: 'itemcode=' + itemcode,
                                success: function (html) {

                                    $('#ItemCont table tbody').append(html);
                                    $('#itm-loader').each(function () {
                                        $(this).remove();
                                    });
                                }
                            });
                        }

                    }
                }
            })
            //End Add Scroll Function

        });
        $('input[name=ItemSearch]').focus();
    })
    //End Load Item

    //Clear Item
    $('#ItemModal').on('hide.bs.modal', function () {
        $('#ItemCont').empty();
    })
    //End Clear Item


    //Add Keypress on Item MOdal
    $('#ItemModal').keydown(function (e) {
        switch (e.which) {
            case 13: //Enter
                $('tr.selected-whs').trigger('dblclick');
                break;
            case 37: // left
                break;
            case 38: // up
                $('tr.selected-whs').prev().trigger('click');
                break;
            case 39: // right
                break;
            case 40: // down
                var index = $('tr.selected-whs').index();
                //Check if selected
                if ($('#tblItem tbody').find('tr.selected-whs').index() >= 0) {
                    $('tr.selected-whs').next().trigger('click');
                    //$('#WhsCont > .table-responsive').scrollTop(10);
                } else {
                    $('#tblItem tbody > tr:first').trigger('click');
                }
                //End
                break;
            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });
    //End Add Keypress on Item Modal


    //Highlight Item Table Row Click
    $(document.body).on('click', '#tblItem tbody > tr', function (e) {


        highlight('#tblItem', this);
    })
    //End Highlight Item Table Row Click



    //Select Item Table Row Click
    $(document.body).on('dblclick', '#tblItem tbody > tr', function () 
	{
        var itemcode = $(this).children('td.item-1').text();
        var itemname = $(this).children('td.item-2').text();
        var invntryuom = $(this).children('td.item-4').text();
        $('#ItemModal').modal('hide');

        $('.selected-det').find('input.itemcode').val(itemcode);
        $('.selected-det').find('input.itemname').val(itemname);
        $('.selected-det').find('input.uom').val(invntryuom);
      
        $('.selected-det').find('input.barcode').val('');
        $('.selected-det').find('input.itemcode').focus();
    });
 
    $(document.body).on('click', '.input-group-addon', function () 
	{
        activewhs = $(this).parent().find('input').attr('name');
    })
    //End Check active whs


    //Whs Code Bind
    $(document.body).on('blur', '#tblDetails .warehouse', function () {
        var whs = checkwhs($(this).val()).split(';');
        //whs[0] - WhsCode
        //whs[1] - WhsName


        //Details Item
        if ($.trim(whs[0]) == '') {
            $('.selected-det').find('input.warehouse').val('');
        } else {
            $('.selected-det').find('input.warehouse').val(whs[0]);
        }

        //End Details Item

    })
    //End Whs Code Bind

    //Compute Line Total
    $(document.body).on('blur', '.price', function () 
	{
	        $('.selected-det').find('input.linetotal').trigger('keyup');
    });

    //End Compute Line Total

    //Compute Details Line Total
    $(document.body).on('keyup', '.linetotal', function () {
        var qty = $('.selected-det').find('input.qty').val();
        var price = $('.selected-det').find('input.price').val();
        var discount = $('.selected-det').find('input.discount').val();
        $('.selected-det').find('input.linetotal').val(computeLineTotal(qty, price, discount));
        if (servicetype == 'I') {
            $('input[name=TotBefDisc]').trigger('keyup');
        }
    })

    //Compute Footer Line Total
    $(document.body).on('keyup', 'input[name=TotBefDisc]', function () 
	{

        if (servicetype == 'I') {
            $('input[name=TotBefDisc]').val(computeTotalAmount('linetotal'));
            $('input[name=txtDiscAmtF]').trigger('keyup');
            $('input[name=txtTotalPaymentDue]').trigger('keyup');
        } else {
            $('input[name=TotBefDisc]').val(computeTotalAmount('price'));
            $('input[name=txtDiscAmtF]').trigger('keyup');
            $('input[name=txtTotalPaymentDue]').trigger('keyup');
        }

    })

    //Compute GrossPrice
    $(document.body).on('blur', '.grossprice', function (e) {

        computeUPAutoTrigger();
    })
    //End Compute GrossPrice

    //Compute GrossPrice
    $(document.body).on('change', '.taxcode', function () {

        //GrossPrice
        computeGPAutoTrigger();
        //End Gross Price
    })
	
		//---------------------------------------------------------------- Department Modal ----------------------------------------------------//

	//Load Department
	$('#DepartmentModal').on('shown.bs.modal',function()
	{
		$('#DepartmentCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#DepartmentCont').load('../../proc/views/IP/vw_deptlist.php');
		
		$('input[name=DepartmentSearch]').focus();	
	});
	//End Load Department

	//Clear Department
	$('#DepartmentModal').on('hide.bs.modal',function()
	{
		$('#DepartmentCont').empty();
	});
	//End Clear Department
	
	$('#DepartmentModal').keydown(function(e) {
		    switch(e.which) {
		    	case 13: //Enter
		    		$('tr.selected-dept').trigger('dblclick');
		    	break;
		        case 37: // left
		        break;

		        case 38: // up
		        	$('tr.selected-dept').prev().trigger('click');
		        break;

		        case 39: // right
		        break;

		        case 40: // down
		        	var index = $('tr.selected-dept').index();
		        	
		        	//Check if selected
		        	if($('#tblDepartment tbody').find('tr.selected-dept').index() >= 0){
		        		$('tr.selected-dept').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblDepartment tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;



		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//End
	
	
	//Highlight Department Table Row Click
	$(document.body).on('click','#tblDepartment tbody > tr',function(e){

		
		highlight('#tblDepartment',this);

	})
	//End Highlight Department Table Row Click
	
	$(document.body).on('dblclick','#tblDepartment tbody > tr',function()
	{
		var departmentcode = $(this).children('td.item-1').text();
		var departmentname = $(this).children('td.item-2').text();
		
		$('#DepartmentModal').modal('hide');

		$('.selected-det').find('input.departmentcode').val(departmentcode);
		$('.selected-det').find('input.departmentname').val(departmentname);
	
	});
	//End Select Warehouse Table Row Click
	
	//Search Department
	$(document.body).on('keyup','input[name=DepartmentSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
        $('#DepartmentCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#DepartmentCont table tbody').load('../../proc/views/IP/vw_deptlist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Department
	
		//---------------------------------------------------------------- Project Modal ----------------------------------------------------//
	
	$('#ProjectModal').on('shown.bs.modal',function()
	{
		$('#ProjectCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#ProjectCont').load('../../proc/views/IP/vw_projlist.php');
		
		$('input[name=ProjectSearch]').focus();	
	});
	//End Load Department

	//Clear Department
	$('#ProjectModal').on('hide.bs.modal',function()
	{
		$('#ProjectCont').empty();
	});
	//End Clear Department
	
	$('#ProjectModal').keydown(function(e) {
		    switch(e.which) {
		    	case 13: //Enter
		    		$('tr.selected-proj').trigger('dblclick');
		    	break;
		        case 37: // left
		        break;

		        case 38: // up
		        	$('tr.selected-proj').prev().trigger('click');
		        break;

		        case 39: // right
		        break;

		        case 40: // down
		        	var index = $('tr.selected-proj').index();
		        	
		        	//Check if selected
		        	if($('#tblProject tbody').find('tr.selected-proj').index() >= 0){
		        		$('tr.selected-proj').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblProject tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;



		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	$(document.body).on('click','#tblProject tbody > tr',function(e)
	{
		highlight('#tblProject',this);
	});
	
	$(document.body).on('dblclick','#tblProject tbody > tr',function()
	{
		var projectcode = $(this).children('td.item-1').text();
		var projectname = $(this).children('td.item-2').text();
		
		$('#ProjectModal').modal('hide');

		$('input[name=txtPrjCode]').val(projectcode);	
		$('input[name=txtPrjName]').val(projectname);	
	});
	//End Select Project Table Row Click
	
	//Search Project
	$(document.body).on('keyup','input[name=ProjectSearch]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#ProjectCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#ProjectCont table tbody').load('../../proc/views/IP/vw_projlist-load.php?srchval=' + encodeURI(searchVal));
	});
	//End Search Department
	
	//---------------------------------------------------------------- Employee Modal ----------------------------------------------------//
	
	$('#EmployeeModal').on('shown.bs.modal',function()
	{
		$('#EmployeeCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#EmployeeCont').load('../../proc/views/IP/vw_emplist.php');
		
		$('input[name=EmployeeSearch]').focus();	
	});
	//End Load Employee

	//Clear Employee
	$('#EmployeeModal').on('hide.bs.modal',function()
	{
		$('#EmployeeCont').empty();
	});
	//End Clear Employee
	
	$('#EmployeeModal').keydown(function(e) {
		    switch(e.which) {
		    	case 13: //Enter
		    		$('tr.selected-emp').trigger('dblclick');
		    	break;
		        case 37: // left
		        break;

		        case 38: // up
		        	$('tr.selected-emp').prev().trigger('click');
		        break;

		        case 39: // right
		        break;

		        case 40: // down
		        	var index = $('tr.selected-emp').index();
		        	
		        	//Check if selected
		        	if($('#tblEmployee tbody').find('tr.selected-emp').index() >= 0){
		        		$('tr.selected-emp').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblEmployee tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;



		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//End
	
	
	//Highlight Employee Table Row Click
	$(document.body).on('click','#tblEmployee tbody > tr',function(e)
	{
		highlight('#tblEmployee',this);
	});
	//End Highlight Employee Table Row Click
	
	$(document.body).on('dblclick','#tblEmployee tbody > tr',function()
	{
		var employeecode = $(this).children('td.item-1').text();
		var employeename = $(this).children('td.item-2').text();
		
		$('#EmployeeModal').modal('hide');

		$('.selected-det').find('input.employeecode').val(employeecode);
		$('.selected-det').find('input.employeename').val(employeename);
	});
	//End Select Project Table Row Click
	
	//Search Employee
	$(document.body).on('keyup','input[name=EmployeeSearch]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#EmployeeCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#EmployeeCont table tbody').load('../../proc/views/IP/vw_emplist-load.php?srchval=' + encodeURI(searchVal));
	});
	//End Search Employee

		//---------------------------------------------------------------- Equipment Modal ----------------------------------------------------//
	
	$('#EquipmentModal').on('shown.bs.modal',function()
	{
		$('#EquipmentCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#EquipmentCont').load('../../proc/views/IP/vw_equiplist.php');
		
		$('input[name=EquipmentSearch]').focus();	
	});
	//End Load Equipment

	//Clear Equipment
	$('#EquipmentModal').on('hide.bs.modal',function()
	{
		$('#EquipmentCont').empty();
	});
	//End Clear Equipment
	
	$('#EquipmentModal').keydown(function(e) {
			switch(e.which) {
				case 13: //Enter
					$('tr.selected-emp').trigger('dblclick');
				break;
				case 37: // left
				break;

				case 38: // up
					$('tr.selected-emp').prev().trigger('click');
				break;

				case 39: // right
				break;

				case 40: // down
					var index = $('tr.selected-emp').index();
					
					//Check if selected
					if($('#tblEquipment tbody').find('tr.selected-emp').index() >= 0){
						$('tr.selected-emp').next().trigger('click');

						//$('#WhsCont > .table-responsive').scrollTop(10);
					}else{
						$('#tblEquipment tbody > tr:first').trigger('click');
					}
					//End
				break;



				default: return; // exit this handler for other keys
			}
			e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//End
		
	
	//Highlight Equipment Table Row Click
	$(document.body).on('click','#tblEquipment tbody > tr',function(e){

		
		highlight('#tblEquipment',this);

	})
	//End Highlight Equipment Table Row Click
	
	$(document.body).on('dblclick','#tblEquipment tbody > tr',function()
	{
		var employeecode = $(this).children('td.item-1').text();
		var employeename = $(this).children('td.item-2').text();
		
		$('#EquipmentModal').modal('hide');

		$('.selected-det').find('input.equipmentcode').val(employeecode);
		$('.selected-det').find('input.equipmentname').val(employeename);
	
	});
	//End Select Equipment Table Row Click
		
	//Search Equipment
	$(document.body).on('keyup','input[name=EquipmentSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
		$('#EquipmentCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
		$('#EquipmentCont table tbody').load('../../proc/views/IP/vw_equiplist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Equipment

    //Load Accounts
    $('#AcctModal').on('shown.bs.modal', function () {
		
        $('#AcctCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#AcctCont').load('../../proc/views/IP/vw_acctlist.php', function () {
            //Add Scroll Function 
            $('#AcctCont .table-responsive').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 20) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#AcctCont table tbody > tr:last').children('td').eq(0).text();
                        var ctr = 0;
                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
                            $.ajax({
                                type: 'POST',
                                url: '../../proc/views/IP/vw_acctlist-load.php',
                                data: {
									itemcode : itemcode
								},
                                success: function (html) {

                                    $('#AcctCont table tbody').append(html);
                                    $('#itm-loader').each(function () {
                                        $(this).remove();
                                    });
                                }
                            });
                        }

                    }
                }
            })
            //End Add Scroll Function
        });
        $('input[name=AcctSearch]').focus();
    })
    //End Load Accounts

    //Clear Accounts
    $('#AcctModal').on('hide.bs.modal', function () {
        $('#AcctCont').empty();
    })
    //End Clear Accounts

    //Add Keypress on Acct MOdal
    $('#AcctModal').keydown(function (e) {
        switch (e.which) {
            case 13: //Enter
                $('tr.selected-whs').trigger('dblclick');
                break;
            case 37: // left
                break;
            case 38: // up
                $('tr.selected-whs').prev().trigger('click');
                break;
            case 39: // right
                break;
            case 40: // down
                var index = $('tr.selected-whs').index();
                //Check if selected
                if ($('#tblAcct tbody').find('tr.selected-whs').index() >= 0) {
                    $('tr.selected-whs').next().trigger('click');
                    //$('#WhsCont > .table-responsive').scrollTop(10);
                } else {
                    $('#tblAcct tbody > tr:first').trigger('click');
                }
                //End
                break;
            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });
    //End Add Keypress on Acct Modal


    //Highlight Item Table Row Click
    $(document.body).on('click', '#tblAcct tbody > tr', function (e) {


        highlight('#tblAcct', this);
    })
    //End Highlight Item Table Row Click



    //Select Acct Table Row Click
    $(document.body).on('dblclick', '#tblAcct tbody > tr', function () {

        var acctname = $(this).children('td.item-1').text();
        var acctcode = $(this).children('td.item-2').text();
        var acct = $(this).children('td.item-3').text();
        $('#AcctModal').modal('hide');
        //Details Item
        if ($('#CashTab').hasClass("active"))
        {
            $('input[name=txtGLAccountCash]').val(acctcode);
            $('input[name=txtGLAccountCashName]').val(acctname);
        }
        else if ($('#BankTransferTab').hasClass("active"))
        {
            $('input[name=txtGLAccountBankTransfer]').val(acctcode);
            $('input[name=txtGLAccountBankTransferName]').val(acctname);
        }
        else if ($('#CheckTab').hasClass("active"))
        {
            $('input[name=txtGLAccountCheck]').val(acctcode);
            $('input[name=txtGLAccountCheckName]').val(acctname);
        }
    })
    //End Select Acct Table Row Click



    //Search Acct
    $(document.body).on('keyup', 'input[name=AcctSearch]', function () {
        var searchVal = $(this).val().toLowerCase();
		
        $('#AcctCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#AcctCont table tbody').load('../../proc/views/IP/vw_acctlist-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search Acct



    //Acct Code Bind
    $(document.body).on('blur', '#tblDetails .acctcode', function () 
	{
        var acct = checkacctcode($(this).val()).split(';');
    
		if ($.trim(acct[0]) == '') 
		{
            $('.selected-det').find('input.acctcode').val('');
            $('.selected-det').find('input.acctname').val('');
            //$('.selected-det').find('input.itemcode').focus();
        }
		else 
		{
            $('.selected-det').find('input.acctcode').val(acct[1]);
            $('.selected-det').find('input.acctname').val(acct[0]);
            //Add Account Code
            $('.selected-det').find('input.acctcode').attr('aria-acctcode', acct[2]);
            //End Add Account Code
        }
    });

   	$('#AcctModal1').on('shown.bs.modal',function()
	{
		$('#AcctCont1').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#AcctCont1').load('../../proc/views/OP/vw_acctlist1.php',function()
		{
			$('#AcctCont1 .table-responsive').bind('scroll', function()
			{
				if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight)
				{
					if($(this).scrollTop() > 0)
					{
						var itemcode = $('#AcctCont1 table tbody > tr:last').children('td').eq(0).text();
						var ctr = 0;

						$('#itm-loader').each(function () 
						{
						  ctr += 1;
						});
						
						if(ctr == 0)
						{
							$(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
							
							$.ajax({
								type: 'POST',
								url: '../../proc/views/OP/vw_acctlist-load1.php',
								data: 
								{
									itemcode : itemcode
								},
								success: function(html)
								{
									$('#AcctCont1 table tbody').append(html);                
									$('#itm-loader').each(function () 
									{
										$(this).remove();   
									});
								}
							});
						}
					}
		        }
		    });
		});
		
		$('input[name=AcctSearch1]').focus();
	});
	
	$('#AcctModal1').on('hide.bs.modal',function()
	{
		$('#AcctCont1').empty();
	});

	$('#AcctModal1').keydown(function(e) 
	{
		    switch(e.which) 
			{
		    	case 40: // down
		        	var index = $('tr.selected-act').index();
		        	
		        	//Check if selected
		        	if($('#tblAcct1 tbody').find('tr.selected-act').index() >= 0)
					{
		        		$('tr.selected-act').next().trigger('click');
					}
					else
					{
		        		$('#tblAcct1 tbody > tr:first').trigger('click');
		        	}
		        break;

		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
	});

	//Highlight Item Table Row Click
	$(document.body).on('click','#tblAcct1 tbody > tr',function(e)
	{
		highlight('#tblAcct1',this);
	});
	//End Highlight Item Table Row Click

	//Select Acct Table Row Click
	$(document.body).on('dblclick','#tblAcct1 tbody > tr',function()
	{
		var acctname = $(this).children('td.item-1').text();
		var acctcode = $(this).children('td.item-2').text();
		var acct = $(this).children('td.item-3').text();
		
		$('#AcctModal1').modal('hide');

		$('.selected-det').find('input.acctcode').val(acctcode);
		$('.selected-det').find('input.acctname').val(acctname);
		$('.selected-det').find('input.cat').val('ACCT');
		$('.selected-det').find('input.debit').focus();

		$('.selected-det').find('input.acctcode').attr('aria-acctcode',acct);
	});
	//End Select Acct Table Row Click

	//Search Acct
	$(document.body).on('keyup','input[name=AcctSearch1]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#AcctCont1 table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#AcctCont1 table tbody').load('../../proc/views/OP/vw_acctlist-load1.php?srchval=' + encodeURI(searchVal));
	});
	//End Search Acct
	
    //Find Document
    $(window).keydown(function (e) {

        if (e.keyCode == 70 && e.ctrlKey) {
            //Ctrl + f
            $('#DocumentModal').modal('show');
            e.preventDefault();
        } else if (e.keyCode == 65 && e.ctrlKey) {
            //ctrl + a
            //$('#DocumentModal').modal('show');
            //e.preventDefault();
            //alert('asdf')
        }
        //e.preventDefault(); // prevent the default action (scroll / move caret)
    });
    //End Find Document

    //Load Documents
    $('#DocumentModal').on('shown.bs.modal', function () {
		
        $('#DocumentCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#DocumentCont').load('../../proc/views/IP/vw_doclist.php', function () {
            //Add Scroll Function 
            $('#DocumentCont .table-responsive').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 20) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#DocumentCont table tbody > tr:last').children('td').eq(0).text();
                        var ctr = 0;
                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
                            $.ajax({
                                type: 'POST',
                                url: '../../proc/views/IP/vw_doclist-load.php',
                                data: {
									itemcode : itemcode
								},
                                success: function (html) {

                                    $('#DocumentCont table tbody').append(html);
                                    $('#itm-loader').each(function () {
                                        $(this).remove();
                                    });
                                }
                            });
                        }

                    }
                }
            })
            //End Add Scroll Function
        });
        $('input[name=DocumentSearch]').focus();
    })
    //End Load Documents

    //Clear Document List
    $('#DocumentModal').on('hide.bs.modal', function () {
        $('#DocumentCont').empty();
    })
    //End Clear Document List



    //Add Keypress on DOcument MOdal
    $('#DocumentModal').keydown(function (e) {
        switch (e.which) {
            case 13: //Enter
                $('tr.selected-whs').trigger('dblclick');
                break;
            case 37: // left
                break;
            case 38: // up
                $('tr.selected-whs').prev().trigger('click');
                break;
            case 39: // right
                break;
            case 40: // down
                var index = $('tr.selected-whs').index();
                //Check if selected
                if ($('#tblDocument tbody').find('tr.selected-whs').index() >= 0) {
                    $('tr.selected-whs').next().trigger('click');
                    //$('#WhsCont > .table-responsive').scrollTop(10);
                } else {
                    $('#tblDocument tbody > tr:first').trigger('click');
                }
                //End
                break;
            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });
    //End Add Keypress on Document Modal


    //Highlight Document Table Row Click
    $(document.body).on('click', '#tblDocument tbody > tr', function (e) {


        highlight('#tblDocument', this);
    })
    //End Highlight Document Table Row Click



    //Search Document
    $(document.body).on('keyup', 'input[name=DocumentSearch]', function () {
        var searchVal = $(this).val().toLowerCase();
		
        $('#DocumentCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#DocumentCont table tbody').load('../../proc/views/IP/vw_doclist-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search Document


    //Select Document Table Row Click
    $(document.body).on('dblclick', '#tblDocument tbody > tr', function () {

        var docentry = $(this).children('td.item-1').text();
        $('#DocumentModal').modal('hide');
        $('input[name=txtDocEntry]').val(docentry);
        $('input[name=txtDocEntry]').trigger('keyup');
    })
    //End Select Document Table Row Click


    //Populate Data
    $(document.body).on('keyup', 'input[name=txtDocEntry]', function () 
	{
        var docentry = $(this).val();
        var DocType = '';
        
        $.getJSON('../../proc/views/IP/vw_getdocumentdata.php?docentry=' + docentry , function (data) {
            /* data will hold the php array as a javascript object */
            $('#modal-load-init').modal('show');
            //$('#tblDetails tbody').empty();	
            $.each(data, function (key, val) 
			{
				if(val.Canceled == 'Y')
				{
					$('#btnCancelDoc').prop('disabled', true);
				}
				else
				{
					$('#btnCancelDoc').prop('disabled', false);
				}
				
				$('#btnSeries').html(val.SeriesName);
				$('#btnSeries').attr('series-val', val.Series);
				$('#btnSeries').attr('bplid-val', val.BPLId);
				$('#btnSeries').prop('disabled', true);
				$('#btnSeriesDD').prop('disabled', true);
				
				$('input[name=txtDocNo]').val(val.DocNum).prop('disabled', true);
				$('input[name=txtCustomer]').val(val.CardCode).prop('disabled', true);
				$('input[name=txtName]').val(val.CardName).prop('disabled', true);
				$('textarea[name=txtBillTo]').val(val.Address).prop('disabled', true);

				$('select[name=selContactPerson]').val(val.CntctCode).prop('disabled', true);
				$('input[name=txtPostingDate]').val(val.TaxDate).prop('disabled', true);
				$('input[name=txtDueDate]').val(val.DocDueDate).prop('disabled', true);
				$('input[name=txtDocDate]').val(val.DocDate).prop('disabled', true);
				$('input[name=txtReference]').val(val.CounterRef).prop('disabled', true);
				
				$('input[name=txtPaymentOnAccount]').val(val.NoDocSum).prop('disabled', true);
				$('input[name=txtPrjName]').val(val.PrjName).prop('disabled', true);
				$("input[name=radCategory][value='" + val.DocType + "'").val(val.DocType).trigger('change').prop('checked',true);
				
				DocType = val.DocType;
				
				$('input[name=radCategory]').prop('disabled',true);
					
				if(val.CheckSum != 0 && val.CheckSum != '')	
                {
					//check
					$('input[name=txtGLAccountCheck]').val(val.CheckAcct).prop('disabled', true);
					var checkglacctname = checkacctcode(val.CheckAcct).split(';')[0];
					$('input[name=txtGLAccountCheckName]').val(checkglacctname).prop('disabled', true);
					$('input[name=txtCheckDueDate]').val(val.CheckDueDate).prop('disabled', true);

					//country, bank, branch
					var selectedcountry = val.CountryCod;
					var selectedbankcode = val.BankCode;
					var selectedcustomer = val.CardCode;
					
					$('select[name=txtCountry]').html('<option>Loading...</option>');
					$('select[name=txtCountry]').load('../../proc/views/IP/vw_countries.php?countrycode=' + val.CountryCod , function () {

					});
					$('select[name=txtCountry]').prop('disabled', true);

					$('select[name=txtBankName]').html('<option>Loading...</option>');
					$('select[name=txtBankName]').load('../../proc/views/IP/vw_banks.php?countrycode=' + selectedcountry
							+ '&bankcode=' + selectedbankcode
							
							, function () {

							});
					$('select[name=txtBankName]').prop('disabled', true);

					$('select[name=txtBranch]').html('<option>Loading...</option>');
					$('select[name=txtBranch]').load('../../proc/views/IP/vw_bankbranches.php?countrycode=' + selectedcountry
							+ '&bankcode=' + selectedbankcode
							+ '&customercode=' + selectedcustomer
							+ '&branch=' + val.Branch
							
							, function () {
							});
					$('select[name=txtBranch]').prop('disabled', true);
					$('.acctcodeCont .input-group-addon').addClass('hidden');
					$('input[name=txtCheckAccount]').val(val.AcctNum).prop('disabled', true);
					$('input[name=txtCheckNo]').val(val.CheckNum).prop('disabled', true);
					$('input[name=txtCheckAmount]').val(val.CheckSum).prop('disabled', true);
					
					$('#CheckTab').addClass('active in');
					$('#CreditCardTab').removeClass('active in');
					$('#BankTransferTab').removeClass('active in');
					$('#CashTab').removeClass('active in');
					
					$('#CheckTabLi').addClass('active');
					$('#BankTransferTabLi').removeClass('active');
					$('#CreditCardTabLi').removeClass('active');
					$('#CashTabLi').removeClass('active');
				}
				
				if(val.CheckSum == 0 || val.CheckSum == '')	
				{
					//check
					$('input[name=txtGLAccountCheck]').val('').prop('disabled', true);
					$('input[name=txtGLAccountCheckName]').val('').prop('disabled', true);
					$('input[name=txtCheckDueDate]').val('').prop('disabled', true);

					$('select[name=txtCountry]').html('<option>-Select-</option>');
					$('select[name=txtCountry]').prop('disabled', true);

					$('select[name=txtBankName]').html('<option>-Select-</option>');
					$('select[name=txtBankName]').prop('disabled', true);

					$('select[name=txtBranch]').html('<option>-Select-</option>');
					$('select[name=txtBranch]').prop('disabled', true);
					$('.acctcodeCont .input-group-addon').addClass('hidden');
					$('input[name=txtCheckAccount]').val('').prop('disabled', true);
					$('input[name=txtCheckNo]').val('').prop('disabled', true);
					$('input[name=txtCheckAmount]').val('').prop('disabled', true);
				}
				
				if(val.CashSum != 0 && val.CashSum != '')
				{
					//cash
					$('input[name=txtGLAccountCash]').val(val.CashAcct).prop('disabled', true);
					var cashglacctname = checkacctcode(val.CashAcct).split(';')[0];
					$('input[name=txtGLAccountCashName]').val(cashglacctname).prop('disabled', true);
					$('input[name=txtTotalCash]').val(val.CashSum).prop('disabled', true);
					
					$('#CashTab').addClass('active in');
					$('#CreditCardTab').removeClass('active in');
					$('#BankTransferTab').removeClass('active in');
					$('#CheckTab').removeClass('active in');
					
					$('#CashTabLi').addClass('active');
					$('#BankTransferTabLi').removeClass('active');
					$('#CreditCardTabLi').removeClass('active');
					$('#CheckTabLi').removeClass('active');
				}
				
				if(val.CashSum == 0 || val.CashSum == '')
				{
					$('input[name=txtGLAccountCash]').val('').prop('disabled', true);
					$('input[name=txtGLAccountCashName]').val('').prop('disabled', true);
					$('input[name=txtTotalCash]').val('').prop('disabled', true);
				}
				
				if(val.TrsfrSum != 0 && val.TrsfrSum != '')	
                {
					//bank transfer
					$('input[name=txtGLAccountBankTransfer]').val(val.TrsfrAcct).prop('disabled', true);
					var banktransferglacctname = checkacctcode(val.TrsfrAcct).split(';')[0];
					$('input[name=txtGLAccountBankTransferName]').val(banktransferglacctname).prop('disabled', true);
					$('input[name=txtTransferDate]').val(val.TrsfrDate).prop('disabled', true);
					$('input[name=txtBankTransferReference]').val(val.TrsfrRef).prop('disabled', true);
					$('input[name=txtTotalBankTransfer]').val(val.TrsfrSum).prop('disabled', true);
					
					$('#BankTransferTab').addClass('active in');
					$('#CreditCardTab').removeClass('active in');
					$('#CashTab').removeClass('active in');
					$('#CheckTab').removeClass('active in');
					
					$('#BankTransferTabLi').addClass('active');
					$('#CashTabLi').removeClass('active');
					$('#CreditCardTabLi').removeClass('active');
					$('#CheckTabLi').removeClass('active');
				}
				
				if(val.TrsfrSum == 0 || val.TrsfrSum == '')	
				{
					//bank transfer
					$('input[name=txtGLAccountBankTransfer]').val('').prop('disabled', true);
					$('input[name=txtGLAccountBankTransferName]').val('').prop('disabled', true);
					$('input[name=txtTransferDate]').val('').prop('disabled', true);
					$('input[name=txtBankTransferReference]').val('').prop('disabled', true);
					$('input[name=txtTotalBankTransfer]').val('').prop('disabled', true);
				}
				
				if(val.creditsum != 0 && val.creditsum != '')	
				{
					//credit card
					$('select[name=selCreditCardName]').val(val.creditcard).prop('disabled', true);
					$('input[name=txtGLAccountCreditCard]').val(val.creditaccount).prop('disabled', true);
					$('input[name=txtCreditCardNo]').val(val.cardno).prop('disabled', true);
					$('input[name=txtValidUntil]').val(val.cardvalid).prop('disabled', true);
					$('input[name=txtIdNo]').val(val.owneridno).prop('disabled', true);
					$('input[name=txtTelephoneNo]').val(val.ownerphone).prop('disabled', true);
					$('select[name=selPaymentMethod]').val(val.paymentmethod).prop('disabled', true);
					$('input[name=txtVoucherNo]').val(val.voucherno).prop('disabled', true);
					$('input[name=txtAmountDue]').val(val.creditsumt2).prop('disabled', true);
					$('input[name=txtPartialPayment]').val(val.firstpayment).prop('disabled', true);
					
					$('#CreditCardTab').addClass('active in');
					$('#BankTransferTab').removeClass('active in');
					$('#CashTab').removeClass('active in');
					$('#CheckTab').removeClass('active in');
					
					$('#CreditCardTabLi').addClass('active');
					$('#CashTabLi').removeClass('active');
					$('#BankTransferTabLi').removeClass('active');
					$('#CheckTabLi').removeClass('active');
				}
				
				if(val.creditsum == 0 || val.creditsum == '')	
				{
					//credit card
					$('select[name=selCreditCardName]').val('').prop('disabled', true);
					$('input[name=txtGLAccountCreditCard]').val('').prop('disabled', true);
					$('input[name=txtCreditCardNo]').val('').prop('disabled', true);
					$('input[name=txtValidUntil]').val('').prop('disabled', true);
					$('input[name=txtIdNo]').val('').prop('disabled', true);
					$('input[name=txtTelephoneNo]').val('').prop('disabled', true);
					$('select[name=selPaymentMethod]').val('').prop('disabled', true);
					$('input[name=txtVoucherNo]').val('').prop('disabled', true);
					$('input[name=txtAmountDue]').val('').prop('disabled', true);
					$('input[name=txtPartialPayment]').val('').prop('disabled', true);
				}
				
				//footer
				$('input[name=txtComments]').val(val.Comments).prop('disabled', true);
				$('input[name=txtJournalRemarks]').val(val.JrnlMemo).prop('disabled', true);
				$('input[name=txtTotalAmountDue]').val(val.DocTotal).prop('disabled', true);
				$('input[name=txtOpenBalance]').val(val.OpenBal).prop('disabled', true);
				
				disablebuttons(true);
            });

            //Populate Details
            setTimeout(function () 
			{
				populatedet(docentry, function () 
				{
                    $('#modal-load-init').modal('hide');
					
					if(DocType == 'Account')
					{
						$('input[name=txtNetTotal]').trigger('keyup');
						$('input[name=txtTotalTax]').trigger('keyup');
					}
                });
				
				populatecreditcard(docentry, function () 
				{
                   
                });
            }, 500)

        });
    });
 
    $('#BPModal').on('shown.bs.modal', function () 
	{
		$('#BPCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#BPCont').load('../../proc/views/IP/vw_bplist.php?CardType=C', function () {
            //Add Scroll Function 
            $('#BPCont .table-responsive').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 20) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#BPCont table tbody > tr:last').children('td').eq(0).text();
                        var ctr = 0;
                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
                            $.ajax({
                                type: 'POST',
                                url: '../../proc/views/IP/vw_bplist-load.php',
                                data: {
									itemcode : itemcode
								},
                                success: function (html) {

                                    $('#BPCont table tbody').append(html);
                                    $('#itm-loader').each(function () {
                                        $(this).remove();
                                    });
                                }
                            });
                        }

                    }
                }
            });
        });
        $('input[name=BPSearch]').focus();
    });
 
    $('#BPModal').on('hide.bs.modal', function () 
	{
        $('#BPCont').empty();
    });
 
    $('#BPModal').keydown(function (e) 
	{
        switch (e.which) 
		{
            case 13: //Enter
                $('tr.selected-whs').trigger('dblclick');
                break;
            case 37: // left
                break;
            case 38: // up
                $('tr.selected-whs').prev().trigger('click');
                break;
            case 39: // right
                break;
            case 40: // down
                var index = $('tr.selected-whs').index();
                //Check if selected
                if ($('#tblBP tbody').find('tr.selected-whs').index() >= 0) {
                    $('tr.selected-whs').next().trigger('click');
                    //$('#WhsCont > .table-responsive').scrollTop(10);
                } else {
                    $('#tblBP tbody > tr:first').trigger('click');
                }
                //End
                break;
            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });
 
    $(document.body).on('click', '#tblBP tbody > tr', function (e) 
	{
        highlight('#tblBP', this);
    });
  
    $(document.body).on('dblclick', '#tblBP tbody > tr', function () 
	{
        var BPCode = $(this).children('td.item-1').text();
        var bp = checkbpcode(BPCode).split(';');
		var radCategory = $("input[name=radCategory]:checked").val();
			
		$('input[name=txtCustomer]').val(BPCode);
		$('input[name=txtName]').val(bp[1]);
		$('select[name=selContactPerson]').html('Loading...');
		$('select[name=selContactPerson]').load('../../proc/views/IP/vw_contactlist.php?cardcode=' + BPCode );
		
		$('textarea[name=txtBillTo]').html('Loading...');
		$('textarea[name=txtBillTo]').load('../../proc/views/IP/vw_billto.php?cardcode=' + BPCode );
		
		$('input[name=txtJournalRemarks]').val('Receipt - ' + BPCode);
		$('#BPModal').modal('hide');
				
		if(radCategory == 'Vendor')
		{
			populateARList(BPCode);
		}
    });

    $(document.body).on('keyup', 'input[name=BPSearch]', function () 
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#BPCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#BPCont table tbody').load('../../proc/views/IP/vw_bplist-load.php?srchval=' + encodeURI(searchVal) );
    });

    $(document.body).on('blur', 'input[name=txtCustomer]', function () 
	{
		var radCategory = $("input[name=radCategory]:checked").val();
			
		if(radCategory == 'Vendor')
		{
			var bp = checkbpcode($(this).val()).split(';');
			
			//bp[0] - CardCode
			//bp[1] - CardName
			//bp[2] - Balance
			//bp[3] - Contact Person
			$('input[name=txtCustomer]').val(bp[0]);
			$('input[name=txtName]').val(bp[1]);
			$('input[name=txtContactPerson]').val(bp[3]);
			$('textarea[name=txtBillTo]').val(bp[4]);

			populateARList(bp[0], function () { });
			$('input[name=txtJournalRemarks]').val('Receipt - ' + BPCode);
		}
    });

    $('#CashModal').on('shown.bs.modal', function () 
	{
		$('#CashCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#CashCont').load('../../proc/views/IP/vw_cash.php', function () 
		{
			
        });
        $('input[name=txtTotalCash]').focus();
    });
	
    $('#CashModal').on('hide.bs.modal', function () 
	{
        $('#CashCont').empty();
    });
    
    $(document.body).on('click', '#tblCash tbody > tr', function (e) 
	{
        highlight('#tblCash', this);
    });
	
	$(document.body).on('click', '#tblCreditCard tbody > tr', function (e) 
	{
        highlight('#tblCreditCard', this);
    });
	
	$(document.body).on('dblclick', '#tblCreditCard tbody > tr', function (e) 
	{
		$('select[name=selCreditCardName]').val($(this).find('input.creditcardname').val());
		$('input[name=txtGLAccountCreditCard]').val($(this).find('input.glaaccountcreditcard').val());
        $('input[name=txtCreditCardNo]').val($(this).find('input.creditcardno').val());
        $('input[name=txtValidUntil]').val($(this).find('input.validuntil').val());
        $('input[name=txtAmountDue]').val(formatMoney(parseFloat($(this).find('input.amountdue').val())));
        $('input[name=txtVoucherNo]').val($(this).find('input.voucherno').val());
		
		$('#btnSaveCreditCard').addClass('hidden');
		$('#btnNewCreditCard').removeClass('hidden');
	});
	
	$(document.body).on('dblclick', '#tblCash tbody > tr', function () {

        var CashCode = $(this).children('td.item-1').text();
        var CashName = $(this).children('td.item-2').text();
        var Balance = $(this).children('td.item-3').text();
        var ContactPerson = $(this).children('td.item-4').text();
		
        $('input[name=txtCustomer]').val(CashCode);
        $('input[name=txtName]').val(CashName);
        $('input[name=txtContactPerson]').val(ContactPerson);
        $('#CashModal').modal('hide');
        populateARList(CashCode, function () { });
    })
    //End Select Acct Table Row Click

    //Search Cash
    $(document.body).on('keyup', 'input[name=CashSearch]', function () {
		
        var searchVal = $(this).val().toLowerCase();
        $('#CashCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#CashCont table tbody').load('../../proc/views/IP/vw_Cashlist-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search Cash

    //Print Document
    $(document.body).on('click', '#btnPrint', function () 
	{
        var docentry = $('input[name=txtDocEntry]').val();
		
        if (docentry != '') {

            window.open("../../report/IP/ip-report.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
        }
    })
    //End Print Document

    $(document.body).on('blur', '#txtCheckAmount', function () 
	{
		var radCategory = $("input[name=radCategory]:checked").val();
		var txtCheckAmount = $('input[name=txtCheckAmount]').val().replace(/,/g, '') == '' ? '' : $('input[name=txtCheckAmount]').val().replace(/,/g, '');
		
		if(radCategory == 'Vendor')
		{
			$('input[name=txtTotalAmountDue]').val(computeTotalAmountDue());
			var openBalance = computeTotalAmountDueNumeric() - computeTotalAmountAppliedNumeric();
			$('input[name=txtOpenBalance]').val(formatMoney(openBalance));
		}
		
		if(txtCheckAmount != '')
		{
			$('input[name=txtCheckAmount]').val(formatMoney(parseFloat(txtCheckAmount)));
		}
    });
	
    $(document.body).on('blur', '#txtTotalBankTransfer', function () 
	{
		var radCategory = $("input[name=radCategory]:checked").val();
		var txtTotalBankTransfer = $('input[name=txtTotalBankTransfer]').val().replace(/,/g, '') == '' ? '' : $('input[name=txtTotalBankTransfer]').val().replace(/,/g, '');
		
		if(radCategory == 'Vendor')
		{
			$('input[name=txtTotalAmountDue]').val(computeTotalAmountDue());
			var openBalance = computeTotalAmountDueNumeric() - computeTotalAmountAppliedNumeric();
			$('input[name=txtOpenBalance]').val(formatMoney(openBalance));
		}
		
		if(txtTotalBankTransfer != '')
		{
			$('input[name=txtTotalBankTransfer]').val(formatMoney(parseFloat(txtTotalBankTransfer)));
		}
    });
	
    $(document.body).on('blur', '#txtTotalCash', function () 
	{
		var radCategory = $("input[name=radCategory]:checked").val();
		var txtTotalCash = $('input[name=txtTotalCash]').val().replace(/,/g, '') == '' ? '' : $('input[name=txtTotalCash]').val().replace(/,/g, '');
		
		if(radCategory == 'Vendor')
		{
			$('input[name=txtTotalAmountDue]').val(computeTotalAmountDue());
			var openBalance = computeTotalAmountDueNumeric() - computeTotalAmountAppliedNumeric();
			$('input[name=txtOpenBalance]').val(formatMoney(openBalance));
		}
		
		if(txtTotalCash != '')
		{
			$('input[name=txtTotalCash]').val(formatMoney(parseFloat(txtTotalCash)));
		}
    });
	
	$(document.body).on('blur', '#txtAmountDue', function () 
	{
		var txtAmountDue = $('input[name=txtAmountDue]').val().replace(/,/g, '') == '' ? '' : $('input[name=txtAmountDue]').val().replace(/,/g, '');
		/* $('input[name=txtTotalAmountDue]').val(computeTotalAmountDue());
		
		var openBalance = computeTotalAmountDueNumeric() - computeTotalAmountAppliedNumeric();
		$('input[name=txtOpenBalance]').val(formatMoney(openBalance));
		 */
		if(txtAmountDue != '')
		{
			$('input[name=txtAmountDue]').val(formatMoney(parseFloat(txtAmountDue)));
		}
	});


    $(document.body).on('click', '#btnSelectAll', function (e) 
	{
        var tbl2 = $('#tblDetails tbody tr').each(function (i) {
            $(this).find('input.itemselected').prop('checked', true);
        });
    });


    $(document.body).on('click', '#btnDeselectAll', function (e) 
	{
        var tbl2 = $('#tblDetails tbody tr').each(function (i) {
            $(this).find('input.itemselected').prop('checked', false);
            $(this).find('input.totalpayment').val(($(this).find('input.balancedue').val()));
        });
    });
	
    $(document.body).on('change', '.itemselected', function (e) 
	{
        if ($(this).prop('checked') == false)
        {
            $(this).parent().parent().find('input.totalpayment').val($(this).parent().parent().find('input.balancedue').val());
        }

        var openBalance = computeTotalAmountDueNumeric() - computeTotalAmountAppliedNumeric();
        $('input[name=txtOpenBalance]').val(formatMoney(openBalance));
    });

    $(document.body).on('change', '.totalpayment', function (e) 
	{
		var totalpayment = $(this).val();
        $(this).parent().parent().find('input.itemselected').prop('checked', true);
        var openBalance = computeTotalAmountDueNumeric() - computeTotalAmountAppliedNumeric();
        $('input[name=txtOpenBalance]').val(formatMoney(openBalance));
		$(this).val(formatMoney(parseFloat(totalpayment)));
    });
	
    $(document.body).on('click', '#btnAddInSequence', function (e) 
	{
        var amountDue = computeTotalAmountDueNumeric();
        while (amountDue > 0)
        {
            var tbl2 = $('#tblDetails tbody tr').each(function (i) {
                var balancedue = $(this).find('input.balancedue').val();
                balancedue = isNaN(parseFloat(balancedue.replace(/,/g, ''))) ? 0 : parseFloat(balancedue.replace(/,/g, ''));
                if (balancedue <= amountDue)
                {
                    $(this).find('input.itemselected').prop('checked', true);
                    $(this).find('input.totalpayment').val(balancedue.toFixed(2)).change();
                    amountDue = amountDue - balancedue;
                }
                else
                {
                    $(this).find('input.itemselected').prop('checked', true);
                    $(this).find('input.totalpayment').val(amountDue.toFixed(2)).change();
                    amountDue = 0;
                }

                if (amountDue === 0)
                {
                    return false;
                }
            });
        }
    });
	
	$(document.body).on('click', '#btnDelRow1', function () 
	{
		var lineid = $('.selected-det').find('input.lineid').val();
		
		if(lineid == '')
		{
			$('.selected-det').remove();
				
			var rowno = 1;
			$('#tblCreditCard tbody tr').each(function () 
			{
				$(this).find('td.rowno').html(rowno);
				
				rowno += 1;
			});
			
			$('input[name=txtTotalAmountDue]').val(computeTotalAmountDue());
		
			var openBalance = computeTotalAmountDueNumeric() - computeTotalAmountAppliedNumeric();
			$('input[name=txtOpenBalance]').val(formatMoney(openBalance));
		}
	});
	
	//Add selected class on row when input-group-addon is click
	$(document.body).on('click','#tblCreditCard > tbody .input-group-addon',function(){
		$('.selected-det').map(function(){
			$(this).removeClass('selected-det');
			$(this).css("background-color", "transparent");
			
	    })
		$(this).closest('tr').css("background-color", "lightgray");
		$(this).closest('tr').addClass('selected-det');
	});
	//End Add selected class on row when input-group-addon is click

	//Add selected class on row when focused on input
	$(document.body).on('focus','#tblCreditCard input, #tblCreditCard select, #tblCreditCard textarea, #tblCreditCard button', function(){
		if (window.event.ctrlKey) {
        
	    	$(this).closest('tr').css("background-color", "lightgray");
	    	$(this).closest('tr').addClass('selected-det');
	  	}else{
		    $('.selected-det').map(function(){
		      $(this).removeClass('selected-det');
		    })

		    $('#tblCreditCard tbody > tr').css("background-color", "transparent");
		    $(this).closest('tr').css("background-color", "lightgray");
		    $(this).closest('tr').addClass('selected-det');
	  	}
	})
	//End Add selected class on row when focused on input



	//Add selected class on row when click on tr
	$(document.body).on('click','#tblCreditCard tbody > tr > td.rowno', function(){
		if (window.event.ctrlKey) {

			//Check if selected
			if($(this).closest('tr').hasClass('selected-det')){
				$(this).closest('tr').css("background-color", "transparent");
				$(this).closest('tr').removeClass('selected-det');
			}else{
				$(this).closest('tr').css("background-color", "lightgray");
	    		$(this).closest('tr').addClass('selected-det');
			}
			//End
	    	
	  	}else{
		    $('.selected-det').map(function(){
		      $(this).removeClass('selected-det');
		    })

		    $('#tblCreditCard tbody > tr').css("background-color", "transparent");
		    $(this).closest('tr').css("background-color", "lightgray");
		    $(this).closest('tr').addClass('selected-det');
	  	}
	})
	//End Add selected class on row when click on tr


	//Add selected class on row when input-group-addon is click
	$(document.body).on('click','#tblCreditCard > tbody .input-group-addon',function(){
		$('.selected-det').map(function(){
			$(this).removeClass('selected-det');
			$(this).css("background-color", "transparent");
			
	    })
		$(this).closest('tr').css("background-color", "lightgray");
		$(this).closest('tr').addClass('selected-det');
	})
	
	$(document.body).on('keyup','input[name=txtNetTotal]',function()
	{
		var radCategory = $("input[name=radCategory]:checked").val();
		
		if(radCategory == 'Account')
		{
			$("input[name=txtNetTotal]").val(computeTotalAmount('price'));
		}
	});
	
	$(document.body).on('keyup','input[name=txtTotalTax]',function()
	{
		var radCategory = $("input[name=radCategory]:checked").val();
		var price = 0;
		var rate = 0;
		var totaltax = 0;
		
		
		if(radCategory == 'Account')
		{
			$('#tblDetails tbody tr').each(function (i) 
			{
				price = isNaN(parseFloat($(this).find('input.price').val().replace(/,/g,'')))? 0 : parseFloat($(this).find('input.price').val().replace(/,/g,''));
				rate = isNaN(parseFloat($(this).find('select.taxgroup option:selected').attr('val-rate'))) ? 0 :  parseFloat($(this).find('select.taxgroup option:selected').attr('val-rate'));
				totaltax += price * ( rate / 100);	
			});
		}
		
		$("input[name=txtTotalTax]").val(formatMoney(parseFloat(totaltax)));
	});
	
	$(document.body).on('keyup','input[name=txtTotalAmountDue]',function()
	{
		var radCategory = $("input[name=radCategory]:checked").val();
		var txtNetTotal =  isNaN(parseFloat($("input[name=txtNetTotal").val().replace(/,/g,''))) ? 0 : parseFloat($("input[name=txtNetTotal").val().replace(/,/g,''));
		var txtTotalTax = isNaN(parseFloat($("input[name=txtTotalTax").val().replace(/,/g,''))) ? 0 : parseFloat($("input[name=txtTotalTax").val().replace(/,/g,''));
		var TotalAmountDue = txtNetTotal + txtTotalTax;
		
		if(radCategory == 'Account')
		{
			$("input[name=txtTotalAmountDue]").val(formatMoney(parseFloat(TotalAmountDue)));
		}
	});
	
	$(document.body).on('blur','.price',function()
	{
		var price = $('.selected-det').find('input.price').val().replace(/,/g,'');	
		
		$('input[name=txtNetTotal]').trigger('keyup');
		$('input[name=txtTotalTax]').trigger('keyup');
		$('input[name=txtTotalAmountDue]').trigger('keyup');
		
		if(price != 0 || price != '')
		{
			$('.selected-det').find('input.price').val(formatMoney(parseFloat(price)));
		}
	});
	
	$(document.body).on('change','.taxgroup',function()
	{
		$('input[name=txtNetTotal]').trigger('keyup');
		$('input[name=txtTotalTax]').trigger('keyup');
		$('input[name=txtTotalAmountDue]').trigger('keyup');
	});
	
	$(document.body).on('click','#btnReceiptListVIEW',function(e)
	{
		$('#resView').empty();
		$('#resView').append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		
		var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtReceiptListFrom = $('input[name=txtReceiptListFrom]').val();
    	var txtReceiptListTo = $('input[name=txtReceiptListTo]').val();
		
		if(err == 0)
		{
			$.ajax({
				type: 'POST',
				url: '../../report/ip/rpt_view.php',
				data: {
					txtDateFrom : txtDateFrom,
					txtDateTo : txtDateTo,
					txtReceiptListFrom : txtReceiptListFrom,
					txtReceiptListTo : txtReceiptListTo
				},
				success: function (html) 
				{
					$('#resView').html(html);
				}
			});
		}
	});
	
	$('#ReceiptListModal').on('shown.bs.modal',function()
	{
		$('#resView').append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		
		var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtReceiptListFrom = $('input[name=txtReceiptListFrom]').val();
    	var txtReceiptListTo = $('input[name=txtReceiptListTo]').val();
		
		if(err == 0)
		{
			$.ajax({
				type: 'POST',
				url: '../../report/ip/rpt_view.php',
				data: {
					txtDateFrom : txtDateFrom,
					txtDateTo : txtDateTo,
					txtReceiptListFrom : txtReceiptListFrom,
					txtReceiptListTo : txtReceiptListTo
				},
				success: function (html) 
				{
					$('#resView').html(html);
				}
			});
		}
	});
	
	$('#ReceiptListModal').on('hide.bs.modal',function()
	{
		$('#resView').empty();
	});
	
	$(document.body).on('dblclick','#tblView tbody > tr',function()
	{
		var docentry = $(this).children('td.item-0').text();
		
		$('#ReceiptListModal').modal('hide');

		$('input[name=txtDocEntry]').val(docentry);
		$('input[name=txtDocEntry]').trigger('keyup');
	});
	
	$(document.body).on('click','#tblView tbody > tr',function(e)
	{
		highlight('#tblView',this);
	});
	
	$(document.body).on('click','#btnReceiptListPDF',function(e)
	{
    	var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtReceiptListFrom = $('input[name=txtReceiptListFrom]').val();
    	var txtReceiptListTo = $('input[name=txtReceiptListTo]').val();
    	
		$('.receiptlistrequired').each(function()
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
			window.open("../../report/ip/iplist-report.php?txtReceiptListFrom=" + encodeURI(txtReceiptListFrom) + "&txtReceiptListTo=" + encodeURI(txtReceiptListTo) + "&txtDateFrom=" + encodeURI(txtDateFrom) + "&txtDateTo=" + encodeURI(txtDateTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });
	
	$(document.body).on('click','#btnReceiptListEXCEL',function(e)
	{
    	var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtReceiptListFrom = $('input[name=txtReceiptListFrom]').val();
    	var txtReceiptListTo = $('input[name=txtReceiptListTo]').val();
    	
		$('.receiptlistrequired').each(function()
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
			window.open("../../report/ip/iplist-excel.php?txtReceiptListFrom=" + encodeURI(txtReceiptListFrom) + "&txtReceiptListTo=" + encodeURI(txtReceiptListTo) + "&txtDateFrom=" + encodeURI(txtDateFrom) + "&txtDateTo=" + encodeURI(txtDateTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });
	
	$(document.body).on('click', '#btnNewCreditCard', function (e) 
	{
		$('select[name=selCreditCardName]').val('');
		$('input[name=txtGLAccountCreditCard]').val('');
        $('input[name=txtAmountDue]').val('');
   	
		$('#btnSaveCreditCard').removeClass('hidden');
		$('#btnNewCreditCard').addClass('hidden');
	});
	
	$(document.body).on('click', '#btnSaveCreditCard', function (e) 
	{
		var err = 0;
        var errmsg = '';
		var rowno = 0;
        rowno = ($('#tblCreditCard tbody tr:last').find('td.rowno').text() == '') ? 1 : parseFloat($('#tblCreditCard tbody tr:last').find('td.rowno').text()) + 1;
		
		var creditcardname1 = '';
		var creditcardname = $('select[name=selCreditCardName]').val();
		if(creditcardname != '')
		{
			var creditcardname1 = $('select[name=selCreditCardName] option:selected').attr('val-cardname');
		}
        var glaaccountcreditcard = $('input[name=txtGLAccountCreditCard]').val();
        var creditcardno = $('input[name=txtCreditCardNo]').val();
        var validuntil = $('input[name=txtValidUntil]').val();
        var idno = $('input[name=txtIdNo]').val();
        var telephoneno = $('input[name=txtTelephoneNo]').val();
        var paymentmethod = $('select[name=selPaymentMethod]').val();
        var amountdue = $('input[name=txtAmountDue]').val().replace(/,/g, '');
        var nopayments = $('input[name=txtNoPayments]').val();
        var partialpayment = $('input[name=txtPartialPayment]').val();
        var addpayment = $('input[name=txtAddPayment]').val();
        var voucherno = $('input[name=txtVoucherNo]').val();
        var transactiontype = $('input[name=txtTransactionType]').val();
		
		$('.creditcardrequired').each(function () 
		{
			if ($(this).val() == '') 
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
			$('#tblCreditCard tbody').append('<tr><td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">' + rowno + '</td>' +
																						'<td>'+ creditcardname1+'</td>' +
																						'<td class="hidden"><input type="hidden" class="lineid" value=""><input class="creditcardname hidden" value="'+ creditcardname+'"></td>' +
																						'<td class="hidden"><input class="glaaccountcreditcard" value="'+ glaaccountcreditcard+'"></td>' +
																						'<td class="hidden"><input class="creditcardno" value="'+ creditcardno+'"></td>' +
																						'<td class="hidden"><input class="validuntil" value="'+ validuntil+'"></td>' +
																						'<td class="hidden"><input class="amountdue" value="'+ amountdue+'"></td>' +
																						'<td class="hidden"><input class="voucherno" value="'+ voucherno+'"></td>' +
																						'<td style="padding: 0px;" valign="middle"><center><button type="button" class="btn-danger" id="btnDelRow1"><i class="fa fa-times"></i></button></center></td>' +
																													'</tr>');
																													
			$('select[name=selCreditCardName]').val('');
			$('input[name=txtGLAccountCreditCard]').val('');
			$('input[name=txtAmountDue]').val('');
			
			var radCategory = $("input[name=radCategory]:checked").val();
			
			if(radCategory == 'Vendor')
			{
				$('input[name=txtTotalAmountDue]').val(computeTotalAmountDue());
			
				var openBalance = computeTotalAmountDueNumeric() - computeTotalAmountAppliedNumeric();
				$('input[name=txtOpenBalance]').val(formatMoney(openBalance));
			}
			else
			{
				
			}
		} 
		else 
		{
            notie.alert(3, errmsg, 3);
        }
	});
	
    $(document.body).on('click', '#btnSaveIP', function (e) {
        var err = 0;
        var errmsg = '';
        var basentry = $('input[name=txtBaseEntry]').val();
        var customer = $('input[name=txtCustomer]').val();
        var name = $('input[name=txtName]').val();
        var billto = $('textarea[name=txtBillTo]').val();
        var postingdate = $('input[name=txtPostingDate]').val();
        var duedate = $('input[name=txtDueDate]').val();
        var documentdate = $('input[name=txtDocDate]').val();
        var contactperson = $('select[name=selContactPerson]').val();
        var project = $('input[name=txtProject]').val();
        var reference = $('input[name=txtReference]').val();
        var remarks = $('input[name=txtComments]').val();
		
        //cash fields
        var totalcash = $('input[name=txtTotalCash]').val().replace(/,/g, '');
        var primaryformitem = $('select[name=txtPrimaryFormItem]').val();
        var glaccountcash = $('input[name=txtGLAccountCash]').val();
		
        //bank transfer fields
        var totalbanktransfer = $('input[name=txtTotalBankTransfer]').val().replace(/,/g, '');
        var banktransferreference = $('input[name=txtBankTransferReference]').val();
        var transferdate = $('input[name=txtTransferDate]').val();
        var glaccountbanktransfer = $('input[name=txtGLAccountBankTransfer]').val();
		
        //check fields
		var totalcheck = $('input[name=txtCheckAmount]').val().replace(/,/g, '');
        var checkno = $('input[name=txtCheckNo]').val();
        var checkaccount = $('input[name=txtCheckAccount]').val();
        var checkbranch = $('select[name=txtBranch]').val();
        var checkbank = $('select[name=txtBankName]').val();
        var checkcountry = $('select[name=txtCountry]').val();
        var checkduedate = $('input[name=txtCheckDueDate]').val();
        var glaccountcheck = $('input[name=txtGLAccountCheck]').val();
		
		//creditcard fields
		/* var creditcardname = $('select[name=selCreditCardName]').val();
        var glaaccountcreditcard = $('input[name=txtGLAccountCreditCard]').val();
        var creditcardno = $('input[name=txtCreditCardNo]').val();
        var validuntil = $('input[name=txtValidUntil]').val();
        var idno = $('input[name=txtIdNo]').val();
        var telephoneno = $('input[name=txtTelephoneNo]').val();
        var paymentmethod = $('select[name=selPaymentMethod]').val();
        var amountdue = $('input[name=txtAmountDue]').val().replace(/,/g, '');
        var nopayments = $('input[name=txtNoPayments]').val();
        var partialpayment = $('input[name=txtPartialPayment]').val();
        var addpayment = $('input[name=txtAddPayment]').val();
        var voucherno = $('input[name=txtVoucherNo]').val(); */
        var transactiontype = $('input[name=txtTransactionType]').val();
		var txtPrjCode = $('input[name=txtPrjCode]').val();
		
        var series = $('#btnSeries').attr('series-val');
        var bplid = $('#btnSeries').attr('bplid-val');
		
		var radCategory = $("input[name=radCategory]:checked").val();
		var urlstr = '';
       
		if(radCategory == 'Vendor')
		{
			$('.required').each(function () {

				if ($(this).val() == '') {

					$(this).parent().addClass('has-error');
					err += 1;
					errmsg = 'Please complete all the required field/s!';
				} else {
					$(this).parent().removeClass('has-error');
				}
			});
		}
		var jsoncredit = '{';
        var otArr = [];
        var tbl2 = $('#tblCreditCard tbody tr').each(function (i) 
		{
		    x = $(this).children();
            var itArr = [];
			
				itArr.push('"' + $(this).find('input.creditcardname').val() + '"');
				itArr.push('"' + $(this).find('input.glaaccountcreditcard').val() + '"');
				itArr.push('"' + $(this).find('input.creditcardno').val() + '"');
				itArr.push('"' + $(this).find('input.validuntil').val() + '"');
				itArr.push('"' + $(this).find('input.amountdue').val().replace(/,/g, '') + '"');
				itArr.push('"' + $(this).find('input.voucherno').val() + '"');
				itArr.push('"' + $(this).find('input.lineid').val() + '"');

				otArr.push('"' + i + '": [' + itArr.join(',') + ']');
        });
        jsoncredit += otArr.join(",") + '}';

        var json = '{';
        var otArr = [];
        var tbl2 = $('#tblDetails tbody tr').each(function (i) {
            x = $(this).children();
            var itArr = [];
			
			if(radCategory == 'Account')
			{
				itArr.push('"' + $(this).find('input.acctcode').val() + '"');
				itArr.push('"' + $(this).find('input.docremarks').val() + '"');
				itArr.push('"' + $(this).find('select.taxgroup').val() + '"');
				itArr.push('"' + $(this).find('input.price').val().replace(/,/g, '') + '"');
				itArr.push('"' + $(this).find('input.departmentcode').val().replace(/,/g, '') + '"');
				itArr.push('"' + $(this).find('input.employeecode').val().replace(/,/g, '') + '"');
				itArr.push('"' + $(this).find('input.equipmentcode').val().replace(/,/g, '') + '"');

				otArr.push('"' + i + '": [' + itArr.join(',') + ']');
			}
			else
			{
				if ($(this).find('input.itemselected').prop('checked') == true)
				{
					itArr.push('"' + $(this).find('input.documentno').val() + '"');
					itArr.push('"' + $(this).find('input.cashdiscountpercent').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.totalpayment').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.departmentcode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.employeecode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.equipmentcode').val().replace(/,/g, '') + '"'); 
					itArr.push('"' + $(this).find('input.objtype').val().replace(/,/g, '') + '"');
          

					otArr.push('"' + i + '": [' + itArr.join(',') + ']');
				}
			}
        });
        json += otArr.join(",") + '}';
		
		urlstr = '../../proc/exec/IP/exec-saveip.php';

        if (err == 0) {
            //Show Loading Modal
            $('#modal-load-init').modal('show');
            //End Show Loading Modal

            //Save Data
            $.ajax({
                type: 'POST',
                url: urlstr,
                data: {
                    json: json.replace(/(\r\n|\n|\r)/gm, ''),
                    jsoncredit: jsoncredit.replace(/(\r\n|\n|\r)/gm, ''),
                    basentry: basentry,
					radCategory: radCategory,
					txtPrjCode: txtPrjCode,
                    customer: customer,
                    name: name,
                    billto: billto,
                    contactperson: contactperson,
                    postingdate: postingdate,
                    duedate: duedate,
                    documentdate: documentdate,
                    project: project,
                    reference: reference,
                    remarks: remarks,
                    //cash fields
                    totalcash: totalcash > 0 ? totalcash : 0,
                    primaryformitem: primaryformitem,
                    glaccountcash: glaccountcash,
                    //bank transfer fields
                    totalbanktransfer: totalbanktransfer > 0 ? totalbanktransfer : 0,
                    banktransferreference: banktransferreference,
                    transferdate: transferdate,
                    glaccountbanktransfer: glaccountbanktransfer,
                    //check fields
                    totalcheck: totalcheck,
                    checkno: checkno,
                    checkaccount: checkaccount,
                    checkbranch: checkbranch,
                    checkbank: checkbank,
                    checkcountry: checkcountry,
                    checkduedate: checkduedate,
                    glaccountcheck: glaccountcheck,
					//creditcard
					/* creditcardname : creditcardname,
					glaaccountcreditcard : glaaccountcreditcard,
					creditcardno : creditcardno,
					validuntil : validuntil,
					idno : idno,
					telephoneno : telephoneno,
					paymentmethod : paymentmethod,
					amountdue : amountdue,
					nopayments : nopayments,
					partialpayment : partialpayment,
					addpayment : addpayment,
					voucherno : voucherno, */
					transactiontype : transactiontype,
                    series: series,
                    
                    bplid: bplid
                },
                success: function (html) {

                    res = html.split('*');
                    if (res[0] == 'true') {
                        //Alert Success
                        notie.alert(1, res[1], 3);
                        //End

                        //Refresh the page

                        disablebuttons(true)
                        setTimeout(function () {
                            location.replace('../../forms/IP/IP.php');
                        }, 2000)
                        //End Refresh the page



                    } else {
                        //Alert when error
                        notie.alert(3, res[1], 3);
                        //End

                    }

                    //Hide Loading Modal
                    $('#modal-load-init').modal('hide');
                    //End Hide Loading Modal

                },
                error: function () {
                    //showAlert('alert-danger animated bounceIn', 'Something went wrong!');

                },
                beforeSend: function () {
                    //showAlert('alert-success animated bounceIn', 'Authenticating. Please wait...');
                }
            });
            //End Save Data

        } else {

            //Alert when error
            notie.alert(3, errmsg, 3);
            //End
        }
    })
    //End Save IP
	
	$(document.body).on('click','#btnCancelIP',function(e){
    	
		var err = 0;
    	var errmsg = '';
		var docentry = $('input[name=txtDocEntry]').val();
		
    	
    	//Check if fields are blank
    	/* $('.required').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	//End Check if fields are blank

    	//Check if qty fields are blank
    	if(servicetype == 'I'){
	    	if(err == 0){
	    		
				$('.numericvalidate').each(function(){
		    		if(parseFloat($(this).val()) <= 0){
		    			
		    			$(this).parent().addClass('has-error');
		    			err += 1;
		    			errmsg = 'Quantity and Price must be greater than 0!';
		    		}else{
		    			$(this).parent().removeClass('has-error');
		    		}
		    	})
	    		

		    }
		} */
	    //Check if qty fields are blank
		
    	if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/IP/exec-cancelip.php',
                data: {
                	docentry : docentry
				},
                success: function(html){
					
					res = html.split('*');
					if(res[0] == 'true'){
						//Alert Success
						notie.alert(1, res[1], 3);
						//End

						//Refresh the page
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/IP/IP.php');
						},2000)
						//End Refresh the page
						
						

					}else{
						//Alert when error
						notie.alert(3, res[1], 3);
						//End

					}

					//Hide Loading Modal
			    	$('#modal-load-init').modal('hide');
			    	//End Hide Loading Modal

                },
                error: function(){
                  //showAlert('alert-danger animated bounceIn', 'Something went wrong!');

                },
                beforeSend:function(){
                  //showAlert('alert-success animated bounceIn', 'Authenticating. Please wait...');
                }
            });
    		//End Save Data

    	}else{
    		
    		//Alert when error
            notie.alert(3, errmsg, 3);
            //End
    	}
    })

    //Update IP
    $(document.body).on('click', '#btnUpdate', function (e) {

        var err = 0;
        var errmsg = '';
        var basentry = $('input[name=txtBaseEntry]').val();
        var docentry = $('input[name=txtDocEntry]').val();
        var customer = $('input[name=txtCustomer]').val();
        var contactperson = $('input[name=txtContactPerson]').val();
        var numatcard = $('input[name=txtCustomerRefNo]').val();
        var paymentterms = $('select[name=txtPayment]').val();
        var postingdate = $('input[name=txtPostingDate]').val();
        var duedate = $('input[name=txtDueDate]').val();
        var documentdate = $('input[name=txtDocDate]').val();
        var requestingbusinessunit = $('select[name=txtRequestingBusinessUnit]').val();
        var remarks = $('textarea[name=txtRemarksF]').val();
        var discPercent = isFinite($('input[name=txtDiscPercentF]').val()) ? $('input[name=txtDiscPercentF]').val() : 0;
        var tpaymentdue = $('input[name=txtTotalPaymentDue]').val();
        var series = $('#btnSeries').attr('series-val');
        var bplid = $('#btnSeries').attr('bplid-val');
        var salesemployee = $('select[name=txtSalesEmployee]').val();
        var owner = $('input[name=txtOwnerCode]').val();
        var urlstr = '';
        //Check if fields are blank
        $('.required').each(function () {

            if ($(this).val() == '') {

                $(this).parent().addClass('has-error');
                err += 1;
                errmsg = 'Please complete all the required field/s!';
            } else {
                $(this).parent().removeClass('has-error');
            }
        })
        //End Check if fields are blank


        //Check if there's a row
        if (servicetype == 'I') {
            if (err == 0) {
                err = 1;
                errmsg = 'No item/s to process!';
                $('.itemcode').each(function () {
                    err = 0;
                    return false;
                })

            }
        } else {
            if (err == 0) {
                err = 1;
                errmsg = 'No item/s to process!';
                $('.remarks').each(function () {
                    err = 0;
                    return false;
                })

            }
        }
        //Check if there's a row


        //Check if qty fields are blank
        if (servicetype == 'I') {
            if (err == 0) {

                $('.numericvalidate').each(function () {
                    if (parseFloat($(this).val()) <= 0) {

                        $(this).parent().addClass('has-error');
                        err += 1;
                        errmsg = 'Quantity and Price must be greater than 0!';
                    } else {
                        $(this).parent().removeClass('has-error');
                    }
                })


            }
        }
        //Check if qty fields are blank

        //Collect Details
        var json = '{';
        var otArr = [];
        var tbl2 = $('#tblDetails tbody tr').each(function (i) {


            x = $(this).children();
            var itArr = [];
            //x.each(function() {
            if (servicetype == 'I') {
                //Item Type
                if ($(this).find('td.ftext').text() == 'N') {
                    itArr.push('"' + $(this).find('input.itemcode').val() + '"');
                    itArr.push('"' + $(this).find('input.qty').val().replace(/,/g, '') + '"');
                    itArr.push('"' + $(this).find('input.price').val().replace(/,/g, '') + '"');
                    itArr.push('"' + $(this).find('input.warehouse').val() + '"');
                    itArr.push('"' + $(this).find('select.taxcode').val() + '"');
                    itArr.push('"' + $(this).find('input.discount').val().replace(/,/g, '') + '"');
                    itArr.push('"' + $(this).find('input.grossprice').val().replace(/,/g, '') + '"');
                    itArr.push('"' + $(this).find('input.taxamount').val().replace(/,/g, '') + '"');
                    itArr.push('"' + $(this).find('input.linetotal').val().replace(/,/g, '') + '"');
                    itArr.push('"' + $(this).find('input.grosstotal').val().replace(/,/g, '') + '"');
                    itArr.push('"N"');
                    itArr.push('"' + $(this).find('input.lineno').val().replace(/,/g, '') + '"');
                    //New fields 20170501
                    itArr.push('"' + $(this).find('input.barcode').val() + '"');
                    itArr.push('"' + $(this).find('input.weightlive').val().replace(/,/g, '') + '"');
                    itArr.push('"' + $(this).find('input.priceperkg').val().replace(/,/g, '') + '"');
                    itArr.push('"' + $(this).find('input.branchesoutlets').val() + '"');
                    itArr.push('"' + $(this).find('input.truckplatenumber').val() + '"');
                } 
				else 
				{
                    //Free Text
                    itArr.push('"' + $(this).find('textarea.remarks').val() + '"');
                    itArr.push('"None"');
                    itArr.push('"None"');
                    itArr.push('"None"');
                    itArr.push('"None"');
                    itArr.push('"None"');
                    itArr.push('"None"');
                    itArr.push('"None"');
                    itArr.push('"None"');
                    itArr.push('"None"');
                    itArr.push('"Y"');
                    itArr.push('"' + $(this).find('input.lineno').val().replace(/,/g, '') + '"');
                    itArr.push('');
                    itArr.push('');
                    //End Free Text
                }
                //End Item Type
            } else {
                //Service Type
                itArr.push('"' + $(this).find('textarea.remarks').val() + '"');
                itArr.push('"' + $(this).find('input.acctcode').attr('aria-acctcode') + '"');
                itArr.push('"' + $(this).find('input.price').val().replace(/,/g, '') + '"');
                itArr.push('"' + $(this).find('select.taxcode').val() + '"');
                itArr.push('"' + $(this).find('input.grossprice').val().replace(/,/g, '') + '"');
                itArr.push('"' + $(this).find('input.taxamount').val().replace(/,/g, '') + '"');
                itArr.push('"' + $(this).find('input.lineno').val().replace(/,/g, '') + '"');
                //End Service Type

            }


            //});

            otArr.push('"' + i + '": [' + itArr.join(',') + ']');
        });
        //PARSE ALL SCRIPT
        json += otArr.join(",") + '}';
        //End Collect  Details

        if (err == 0) {
            //Show Loading Modal
            $('#modal-load-init').modal('show');
            //End Show Loading Modal

            //Save Data
            $.ajax({
                type: 'POST',
                url: '../../proc/exec/IP/exec-updateip.php',
                data: {
                    json: json.replace(/(\r\n|\n|\r)/gm, '[newline]'),
                    basentry: basentry,
                    customer: customer,
                    contactperson: contactperson,
                    numatcard: numatcard,
                    paymentterms: paymentterms,
                    postingdate: postingdate,
                    duedate: duedate,
                    documentdate: documentdate,
                    requestingbusinessunit: requestingbusinessunit,
                    remarks: remarks,
                    discPercent: discPercent,
                    series: series,
                    bplid: bplid,
                    servicetype: servicetype,
                    tpaymentdue: tpaymentdue,
                    salesemployee: salesemployee,
                    owner: owner,
                    docentry: docentry

                },
                success: function (html) {
//                    alert(html);
                    res = html.split('*');
                    if (res[0] == 'true') {
                        //Alert Success
                        notie.alert({ type: 1, text: res[1], time: 2 })
                        //End

                        //Refresh the page

                        disablebuttons(true)
                        setTimeout(function () {
                            location.replace('../../forms/IP/IP.php');
                        }, 2000)
                        //End Refresh the page



                    } else {
                        //Alert when error
                        notie.alert({ type: 3, text: res[1], time: 2 })
                        //End

                    }

                    //Hide Loading Modal
                    $('#modal-load-init').modal('hide');
                    //End Hide Loading Modal

                },
                error: function () {
                    //showAlert('alert-danger animated bounceIn', 'Something went wrong!');

                },
                beforeSend: function () {
                    //showAlert('alert-success animated bounceIn', 'Authenticating. Please wait...');
                }
            });
            //End Save Data

        } else {

            //Alert when error
            notie.alert({ type: 3, text: errmsg, time: 2 })
            //End
        }
    })
    //End Update IP

    function highlight(tablename, tablerow) 
	{
		$(tablename + ' tbody > tr').css("background-color", "transparent");
        $(tablerow).css("background-color", "lightgray");
    }

    function checkacctcode(acctcode)
	{
        var result = '';
		
        $.ajax({
            type: 'POST',
            url: '../../proc/views/IP/vw_checkacctcode.php',
            async: false,
            data:  {
				acctcode : acctcode
			},
            success: function (html) {

                result = html;
            }

        });
        return result;
    }

    function checkbpcode(bpcode) 
	{
        var result = '';
		
        $.ajax({
            type: 'POST',
            url: '../../proc/views/IP/vw_checkbpcode.php',
            async: false,
            data: {
				bpcode : bpcode
			},
            success: function (html) 
			{
				result = html;
            }

        });
        return result;
    }
    //End Bind BP Code

    function getdefaultaccounts()
    {
		var result = '';
        $.ajax({
            type: 'POST',
            url: '../../proc/views/IP/vw_getdefaultaccounts.php',
            async: false,
			data : {
				
			},
            success: function (html) 
			{
                result = html;
            }
        });
        return result.split(';');
    }

    function computeTotalAmount(cls) 
	{
        var linetotal = 0.00;
        $('.' + cls).each(function () 
		{
            if (isNaN(parseFloat($(this).val().replace(/,/g, '')))) {
                linetotal += 0;
            } 
			else 
			{
                linetotal += parseFloat($(this).val().replace(/,/g, ''));
            }

        });
        return formatMoney(linetotal);
    }
 
    function formatMoney(n) 
	{
        return n.toLocaleString().split(".")[0] + "."
                + n.toFixed(2).split(".")[1];
    }

    function formatMoney2(n) 
	{
        return n.toLocaleString().split(".")[0] + "."
                + n.toFixed(4).split(".")[1];
    }

    function disablebuttons(param) 
	{
        $('#btnSave').prop('disabled', param);
        $('#btnSelectAll').prop('disabled', param);
        $('#btnDeselectAll').prop('disabled', param);
        $('#btnAddInSequence').prop('disabled', param);
    }
	
	
    function populatecreditcard(docentry, callback) 
	{
        $('#tblCreditCard tbody').load('../../proc/views/IP/vw_creditcarddetailsdata.php?docentry=' + docentry , function (result) 
		{
			callback();
        });

    }

    function populatedet(docentry, callback) 
	{
        $('#tblDetails tbody').load('../../proc/views/IP/vw_documentdetailsdata.php?docentry=' + docentry , function (result) 
		{
			callback();
        });

    }

    function populateARList(customer, callback)
    {
        $('#tblDetails tbody').load('../../proc/views/IP/vw_ardetailsdata.php?customer=' + customer , function (result) {
            callback();
        });
    }

    function computeTotalAmountDue()
    {
        var cash = $('input[name=txtTotalCash]').val();
        var banktransfer = $('input[name=txtTotalBankTransfer]').val();
        var check = $('input[name=txtCheckAmount]').val();
		var amountdue = computeTotalAmount('amountdue');
        cash = isNaN(parseFloat(cash.replace(/,/g, ''))) ? 0 : parseFloat(cash.replace(/,/g, ''));
        banktransfer = isNaN(parseFloat(banktransfer.replace(/,/g, ''))) ? 0 : parseFloat(banktransfer.replace(/,/g, ''));
        check = isNaN(parseFloat(check.replace(/,/g, ''))) ? 0 : parseFloat(check.replace(/,/g, ''));
        amountdue = isNaN(parseFloat(amountdue.replace(/,/g, ''))) ? 0 : parseFloat(amountdue.replace(/,/g, ''));
        var total = cash + banktransfer + check + amountdue;
        return formatMoney(total);
    }

    function computeTotalAmountDueNumeric()
    {
        var cash = $('input[name=txtTotalCash]').val();
        var banktransfer = $('input[name=txtTotalBankTransfer]').val();
        var check = $('input[name=txtCheckAmount]').val();
		var amountdue = computeTotalAmount('amountdue');
        cash = isNaN(parseFloat(cash.replace(/,/g, ''))) ? 0 : parseFloat(cash.replace(/,/g, ''));
        banktransfer = isNaN(parseFloat(banktransfer.replace(/,/g, ''))) ? 0 : parseFloat(banktransfer.replace(/,/g, ''));
        check = isNaN(parseFloat(check.replace(/,/g, ''))) ? 0 : parseFloat(check.replace(/,/g, ''));
        amountdue = isNaN(parseFloat(amountdue.replace(/,/g, ''))) ? 0 : parseFloat(amountdue.replace(/,/g, ''));
        var total = cash + banktransfer + check + amountdue;
        return total;
    }

    function computeTotalAmountAppliedNumeric()
    {
        var total = 0;
        var tbl2 = $('#tblDetails tbody tr').each(function (i) {
            if ($(this).find('input.itemselected').prop('checked') == true)
            {
                var totalPayment = $(this).find('input.totalpayment').val();
                total += isNaN(parseFloat(totalPayment.replace(/,/g, ''))) ? 0 : parseFloat(totalPayment.replace(/,/g, ''));
            }
        });
        return total;
    }

    //=======================================================================================================
    //End javascript Code
    //=======================================================================================================
    //Hide Intialize Modal after loading all the javascript

    var readyStateCheckInterval = setInterval(function () 
	{
        if (document.readyState === "complete") 
		{
            clearInterval(readyStateCheckInterval);
            $('#modal-load-init').modal('hide');
            $('#modal-load-init').on('hidden.bs.modal', function () 
			{

            });

            $('select[name=cmbServiceType]').trigger('change');
        }
    }, 10);
})//end document.ready