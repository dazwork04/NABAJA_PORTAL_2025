$(window).load(function() {

	//Trigger Base Entry
    $('input[name=txtBaseEntry]').trigger('keyup');
	//End Trigger BaseEntry

})//end window.load

function reload() {
    location.reload();
}

function getinput()
{
		var cars = 1;
		var text = "";
		var i;
		for (i = 0; i < cars; i++) { 
			var name="<br><input type='text' id='check' name='CheckSerial[]'>";
			$("#inputs").append(name);
		}

}

function getless()
{
	
	$('#check').remove();
		/* var cars = 1;
		var text = "";
		var i;
		
		for (i = 0; i < cars; i--) { 
			var name="<br><input type='text' name='CheckSerial[]'>";
			$("#inputs").append(name);
		} */

}

function getDocCur(selDocCur)
	{
		var x = selDocCur.value;
		
		if(x == 'C') {
			//Load Currency
			$('select[name=selDocCur]').html('<option>Loading...</option>');
			$('select[name=selDocCur]').load('../../proc/views/APV/vw_doccur.php', function () {

			})
		}
		else {
			$('#selDocCur')
			.empty()
		;
		}
	}
	
$(document).ready(function() 
{

	$('#window-title').text('A/P Invoice');
	
	//Intialize Modal
	$('#modal-load-init').modal('show');

	//Global Variables
	var cback = 1;
	var activemod = '';
	var mode = 'Add';
	var servicetype = 'I';
	var activewhs = '';
	//End Global Variables

	//Initialize Datetimepicker
	$('#txtPostingDate').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
	})
	$('#txtDeliveryDate').datetimepicker({
	    format: 'MM/DD/YYYY',
	})

	$('#txtDocDate').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
    })
	$('#txtGRPODeliveryDate').datetimepicker({
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
    })


	//Initialize Title
	$('#mod-title').text('A/P Invoice');
	//End Initialize Title

	
    //Load Grpo Series 
    $('#btnSeries').html('Loading...')
    $('#SeriesList').load('../../proc/views/APV/vw_series.php?objtype=18',function(){
      $('#btnSeries').html($('.series:first').attr('val-seriesname'));
      $('#btnSeries').attr('series-val',$('.series:first').attr('val-series'));
      $('#btnSeries').attr('bplid-val',$('.series:first').attr('val-bplid'));
      $('input[name=txtDocNo]').val($('.series:first').attr('val-nextnum'));
    });
	
	$('select[name=txtSalesEmployee]').html('<option>Loading...</option>');
	$('select[name=txtSalesEmployee]').load('../../proc/views/APV/vw_salesemployee.php');
	
	$('select[name=txtPayment]').html('<option>Loading...</option>');
	$('select[name=txtPayment]').load('../../proc/views/APV/vw_paymentterms.php');
	
	//=======================================================================================================
	//Javascript Code here
	//=======================================================================================================

	//Load Owner Data
    $('#OwnerModal').on('shown.bs.modal', function () {

        $('#OwnerCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#OwnerCont').load('../../proc/views/APV/vw_employee.php?CardType=C', function () {
            //Add Scroll Function 
            $('#OwnerCont .table-responsive').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#OwnerCont table tbody > tr:last').children('td').eq(0).text();
                        var ctr = 0;

                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');


                            $.ajax({
                                type: 'POST',
                                url: '../../proc/views/APV/vw_employee-load.php',
                                data: 'itemcode=' + itemcode,
                                success: function (html) {

                                    $('#OwnerCont table tbody').append(html);
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
        $('input[name=OwnerSearch]').focus();
    })
    //End Load Owner Data

    //Clear Owner Data
    $('#OwnerModal').on('hide.bs.modal', function () {
        $('#OwnerCont').empty();

    })
    //End Clear Owner Data


    //Add Keypress on Owner MOdal
    $('#OwnerModal').keydown(function (e) {
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
                if ($('#tblOwner tbody').find('tr.selected-whs').index() >= 0) {
                    $('tr.selected-whs').next().trigger('click');

                    //$('#WhsCont > .table-responsive').scrollTop(10);
                } else {
                    $('#tblOwner tbody > tr:first').trigger('click');
                }
                //End
                break;



            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });

    //End Add Keypress on Business Partner Modal


    //Highlight Item Table Row Click
    $(document.body).on('click', '#tblOwner tbody > tr', function (e) {


        highlight('#tblOwner', this);

    })
    //End Highlight Item Table Row Click



    //Select Acct Table Row Click
    $(document.body).on('dblclick', '#tblRequester tbody > tr', function () {
        var owner = $(this).children('td.item-2').text() + ", " + $(this).children('td.item-1').text();
        var employeecode = $(this).children('td.item-3').text();
        $('input[name=txtOwner]').val(owner);
        $('input[name=txtOwnerCode]').val(employeecode);
        $('#OwnerModal').modal('hide');
    })
    //End Select Acct Table Row Click

    //Search Owner
    $(document.body).on('keyup', 'input[name=OwnerSearch]', function () {

        var searchVal = $(this).val().toLowerCase();
        $('#OwnerCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#OwnerCont table tbody').load('../../proc/views/APV/vw_employee-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search Owner
    
    //Owner Code bind
    $(document.body).on('blur', 'input[name=txtOwner]', function () {
        var owner = checkownercode($(this).val()).split(';');
        $('input[name=txtOwner]').val(owner[0]);
        $('input[name=txtOwnerCode]').val(owner[1]);
    });

    //End Owner modal

    //On change series 
    $(document.body).on('click','.series',function(){
      $('#btnSeries').html($(this).attr('val-seriesname'));
      $('#btnSeries').attr('series-val',$(this).attr('val-series'));
      $('#btnSeries').attr('bplid-val',$(this).attr('val-bplid'));
      $('input[name=txtDocNo]').val($(this).attr('val-nextnum'));
    })
   
 	$(document.body).on('change','select[name=cmbServiceType]',function(){
		

		servicetype = $(this).val();
		if(servicetype == 'I'){
			//==============
			//* Item
			//==============
			$('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /></p>');
			$('#ModDetails').load('../../forms/APV/APV-details.php?servicetype=I',function(){
				//Clear value Total Before Discount
				$('input[name=TotBefDisc]').val('');
				//End Clear value Total Before Discount
				cback = 0;
			});
			//==============
			//* End Item
			//==============
		}else if(servicetype == 'S'){
			//==============
			//* Service
			//==============
			$('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
			$('#ModDetails').load('../../forms/APV/APV-details.php?servicetype=S',function(){
				//Clear value Total Before Discount
				$('input[name=TotBefDisc]').val('');
				//End Clear value Total Before Discount
				cback = 0;
			});
			//==============
			//* End Service
			//==============
		}

		

	})
	//End Service Type Change

	//Add Row
	$(document.body).on('click','#btnAddRow',function(){
		$(this).prop('disabled',true);
		
		var rowno = 0;
		rowno = ($('#tblDetails tbody tr:last').find('td.rowno').text() == '')? 1 : parseFloat($('#tblDetails tbody tr:last').find('td.rowno').text()) + 1;
		//End row number
		if(servicetype == 'I'){
			//Item Type
			$(this).load('../../forms/APV/APV-details-row.php?servicetype=I',function(result){
				$('#tblDetails tbody').append(result);

				
				//Set Row Number
				$('#tblDetails tbody tr:last').find('td.rowno').html('<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> ' + rowno);
				//End Set Row Number

				//Set Warehouse Details
				$('#tblDetails tbody tr:last').find('input.warehouse').val('01');
				/* $('#tblDetails tbody tr:last').find('input.itemcode').val($('.selected-det').find('input.itemcode').val());
				$('#tblDetails tbody tr:last').find('input.itemname').val($('.selected-det').find('input.itemname').val());
				$('#tblDetails tbody tr:last').find('input.qty').val($('.selected-det').find('input.qty').val());
				$('#tblDetails tbody tr:last').find('input.uom').val($('.selected-det').find('input.uom').val());
				$('#tblDetails tbody tr:last').find('input.price').val($('.selected-det').find('input.price').val());
				$('#tblDetails tbody tr:last').find('input.taxcode').val($('.selected-det').find('input.taxcode').val());
				$('#tblDetails tbody tr:last').find('input.discount').val($('.selected-det').find('input.discount').val());
				$('#tblDetails tbody tr:last').find('input.grossprice').val($('.selected-det').find('input.grossprice').val()); */
				$('#tblDetails tbody tr:last').find('input.qty').focus();
				//End Set Warehouse Details

				//Set Header Fixed
				//$("#tblDetails").tableHeadFixer({"left" : 4});
				//End Set Header Fixed
				$(this).empty();
				$(this).prop('disabled',false);
			})
			//End Item Type

		}else{
			//Service Type
			$(this).load('../../forms/APV/APV-details-row.php?servicetype=S',function(result){
				$('#tblDetails tbody').append(result);

				//Set Row Number
				$('#tblDetails tbody tr:last').find('td.rowno').html('<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> ' + rowno);
				//End Set Row Number
				$('#tblDetails tbody tr:last').find('textarea.remarks').focus();
				//Set Header Fixed
				//$("#tblDetails").tableHeadFixer({"left" : 4});
				//End Set Header Fixed
				$(this).empty();
				$(this).prop('disabled',false);
			})
			//End Service Type

		}
		
	})
	//End Add Row



	//Add Free Text
	$(document.body).on('click','#btnFreeText',function(){
		$(this).prop('disabled',true);
		//generate row number
		var rowno = 0;
		rowno = ($('#tblDetails tbody tr:last').find('td.rowno').text() == '')? 1 : parseFloat($('#tblDetails tbody tr:last').find('td.rowno').text()) + 1;
		//End row number
		if(servicetype == 'I'){
			//Item Type
			$(this).load('../../forms/APV/APV-details-row.php?servicetype=I&freetext=1',function(result){

				$('#tblDetails tbody').append(result);

				
				//Set Row Number
				$('#tblDetails tbody tr:last').find('td.rowno').html(rowno);
				//End Set Row Number

				

				//Set Header Fixed
				//$("#tblDetails").tableHeadFixer({"left" : 4});
				//End Set Header Fixed
				$(this).empty();
				$(this).prop('disabled',false);
			})
			//End Item Type

		}
		
	})
	//End Add Free Text

	//Delete Row
	$(document.body).on('click','#btnDelRow',function(){
		$('.selected-det').remove();
		$('input[name=TotBefDisc]').trigger('keyup');

		//Reloop to adjust row number
		var rowno = 1;
		$('#tblDetails tbody tr').each(function(){
			ftext = $(this).find('.ftext').text();
			
			if(ftext == 'Y'){
				$(this).find('td.rowno').html(rowno);
			}else{
				$(this).find('td.rowno').html('<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> ' + rowno);
			}
			
			rowno += 1;
		})
		//End Reloop to adjust row number
	})

	//End Delete Row
	$(document.body).on('click', '#btnDelRow1', function () 
	{
		var lineid = $('.selected-det').find('input.lineid').val();
		
		if(lineid == '')
		{
			$('.selected-det').remove();
				
			var rowno = 1;
			$('#tblWTAXCodeList tbody tr').each(function () 
			{
				$(this).find('td.rowno').html(rowno);
				
				rowno += 1;
			});
		}
	});

		//---------------------------tblWTAXCodeList
	
	//Add selected class on row when focused on input
	$(document.body).on('focus','#tblWTAXCodeList input, #tblWTAXCodeList select, #tblWTAXCodeList textarea, #tblWTAXCodeList button', function(){
		if (window.event.ctrlKey) {
        
	    	$(this).closest('tr').css("background-color", "lightgray");
	    	$(this).closest('tr').addClass('selected-det');
	  	}else{
		    $('.selected-det').map(function(){
		      $(this).removeClass('selected-det');
		    })

		    $('#tblWTAXCodeList tbody > tr').css("background-color", "transparent");
		    $(this).closest('tr').css("background-color", "lightgray");
		    $(this).closest('tr').addClass('selected-det');
	  	}
	})
	//End Add selected class on row when focused on input



	//Add selected class on row when click on tr
	$(document.body).on('click','#tblWTAXCodeList tbody > tr > td.rowno', function(){
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

		    $('#tblWTAXCodeList tbody > tr').css("background-color", "transparent");
		    $(this).closest('tr').css("background-color", "lightgray");
		    $(this).closest('tr').addClass('selected-det');
	  	}
	})
	//End Add selected class on row when click on tr


	//Add selected class on row when input-group-addon is click
	$(document.body).on('click','#tblWTAXCodeList > tbody .input-group-addon',function(){
		$('.selected-det').map(function(){
			$(this).removeClass('selected-det');
			$(this).css("background-color", "transparent");
			
	    })
		$(this).closest('tr').css("background-color", "lightgray");
		$(this).closest('tr').addClass('selected-det');
	})
	//End Add selected class on row when input-group-addon is click

	//Add selected class on row when focused on input
	$(document.body).on('focus','#tblDetails input, #tblDetails select, #tblDetails textarea', function(){
		if (window.event.ctrlKey) {
        
	    	$(this).closest('tr').css("background-color", "lightgray");
	    	$(this).closest('tr').addClass('selected-det');
	  	}else{
		    $('.selected-det').map(function(){
		      $(this).removeClass('selected-det');
		    })

		    $('#tblDetails tbody > tr').css("background-color", "transparent");
		    $(this).closest('tr').css("background-color", "lightgray");
		    $(this).closest('tr').addClass('selected-det');
	  	}
	})
	//End Add selected class on row when focused on input



	//Add selected class on row when click on tr
	$(document.body).on('click','#tblDetails tbody > tr > td.rowno', function(){
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

		    $('#tblDetails tbody > tr').css("background-color", "transparent");
		    $(this).closest('tr').css("background-color", "lightgray");
		    $(this).closest('tr').addClass('selected-det');
	  	}
	})
	//End Add selected class on row when click on tr


	//Add selected class on row when input-group-addon is click
	$(document.body).on('click','#tblDetails > tbody .input-group-addon',function(){
		$('.selected-det').map(function(){
			$(this).removeClass('selected-det');
			$(this).css("background-color", "transparent");
			
	    })
		$(this).closest('tr').css("background-color", "lightgray");
		$(this).closest('tr').addClass('selected-det');
	})
	//End Add selected class on row when input-group-addon is click


	//Load Item
	$('#ItemModal').on('shown.bs.modal',function(){

		
		$('#ItemCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#ItemCont').load('../../proc/views/APV/vw_itemlist.php',function(){
			//Add Scroll Function 
		    $('#ItemCont .table-responsive').bind('scroll', function(){
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
                                url: '../../proc/views/APV/vw_itemlist-load.php',
                                data: {
									itemcode : itemcode
								},
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

		})
		$('input[name=ItemSearch]').focus();
			
	})
	//End Load Item

	//Clear Item
	$('#ItemModal').on('hide.bs.modal',function(){
		$('#ItemCont').empty();

	})
	//End Clear Item


	//Add Keypress on Item MOdal
	$('#ItemModal').keydown(function(e) {
		    switch(e.which) {
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
		        	if($('#tblItem tbody').find('tr.selected-whs').index() >= 0){
		        		$('tr.selected-whs').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblItem tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;



		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//End Add Keypress on Item Modal


	//Highlight Item Table Row Click
	$(document.body).on('click','#tblItem tbody > tr',function(e){

		
		highlight('#tblItem',this);

	})
	//End Highlight Item Table Row Click



	//Select Item Table Row Click
	$(document.body).on('dblclick','#tblItem tbody > tr',function(){

		var itemcode = $(this).children('td.item-1').text();
		var itemname = $(this).children('td.item-2').text();
		var invntryuom = $(this).children('td.item-4').text();
		var whs = $(this).children('td.item-8').text();
		var qty = 1;

		//Load Item Price per BP
		var listnum = $('input[name=txtListNum]').val();
		var result = '';
		
		var form_data =
				{
					listnum : listnum,
					itemcode : itemcode
				};
				
			$.ajax({
			type: "POST",
			
			url: "../../proc/views/APV/vw_pricelist.php",
			data: form_data,
			success: function(html)
				{
					result = html;
					$('.selected-det').find('input.price').val(result);
				}
			});
				
		$('#ItemModal').modal('hide');


		//Details Item
		$('.selected-det').find('input.itemcode').val(itemcode);
		$('.selected-det').find('input.itemname').val(itemname);
		$('.selected-det').find('input.uom').val(invntryuom);
		$('.selected-det').find('input.qty').val(qty);
		$('.selected-det').find('input.price').focus();
		$('.selected-det').find('input.warehouse').val('01');
		//End Details Item
	});
	//End Select Item Table Row Click

	//Search Item
	$(document.body).on('keyup','input[name=ItemSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
        $('#ItemCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#ItemCont table tbody').load('../../proc/views/APV/vw_itemlist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Item


	//Item Code Bind
	$(document.body).on('blur','#tblDetails .itemcode',function(){
		var item = checkitemcode($(this).val()).split(';');
		//item[0] - ItemCode
		//item[1] - ItemName
		//item[2] - OnHand
		//item[3] - InvntryUom
		//item[4] - BuyUnitMsr
		//item[5] - ManBtchNum
		//item[6] - NumInBuy

		//Details Item
		if($.trim(item[0]) == ''){
			$('.selected-det').find('input.itemcode').val('');
			$('.selected-det').find('input.itemname').val('');
			//$('.selected-det').find('input.itemcode').focus();
		}else{
			$('.selected-det').find('input.itemname').val(item[1]);
			$('.selected-det').find('input.uom').val(item[3]);
		}
		
		//End Details Item
		
	})
	//End Item Code Bind
	
		//Load WithHolding Tax
	$('#WTaxCodeModal').on('shown.bs.modal',function()
	{
		var vendor = $('input[name=txtVendor]').val();
		
		$('#WTaxCodeCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#WTaxCodeCont').load('../../proc/views/APV/vw_withholdingtaxtable.php?vendorcode=' + encodeURI(vendor) ,function()
		{
			
		});
		
		$('input[name=WTaxCodeSearch]').focus();
			
	});
	
	$('#WTaxCodeModal').on('hide.bs.modal',function()
	{
		$('#WTaxCodeCont').empty();
	});
	
	$(document.body).on('dblclick', '#tblWTaxCode tbody > tr', function () 
	{
		var linetotal = 0;
		var TotalTaxableAmount = 0;
		var servicetype = $('select[name=cmbServiceType]').val();
		
		$('#tblDetails tbody tr').each(function(i) 
		{  
			if($(this).find('select.withholdingtax').val() == 1)
			{
				if(servicetype == 'I')
				{
					linetotal += parseFloat($(this).find('input.linetotal').val().replace(/,/g,''));
				}
				else
				{
					linetotal += parseFloat($(this).find('input.price').val().replace(/,/g,''));
				}
			}
		});
		
		TotalTaxableAmount = parseFloat(linetotal) - parseFloat($('input[name=txtDiscAmtF]').val().replace(/,/g,''));
		
		var rowno = 0;
        rowno = ($('#tblWTAXCodeList tbody tr:last').find('td.rowno').text() == '') ? 1 : parseFloat($('#tblWTAXCodeList tbody tr:last').find('td.rowno').text()) + 1;
		
		var wtaxcode = $(this).children('td.item-1').text();
		var wtaxname = $(this).children('td.item-2').text();
        var rate = $(this).children('td.item-3').text();
        var category = $(this).children('td.item-4').text();
        var txtTotalPaymentDue = $('input[name=txtTotalPaymentDue]').val().replace(/,/g,'');
        
		$('#WTaxCodeModal').modal('hide');
		
		$('#tblWTAXCodeList tbody').append('<tr><td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">' + rowno + '</td>'+
																	'<td style="padding-top: 2px;  padding-bottom: 2px;"><input type="hidden" class="lineid" value=""><input type="hidden" class="wtcode" value="' + wtaxcode +'">&nbsp;' + wtaxcode +'</td>'+
																	'<td style="padding-top: 2px;  padding-bottom: 2px;"><input type="hidden" class="wtname" value="' + wtaxname +'">&nbsp;' + wtaxname +'</td>'+
																	'<td style="padding-top: 2px;  padding-bottom: 2px;"><input type="hidden" class="rate" value="' + rate +'">&nbsp;' + rate +'</td>'+
																	'<td style="padding-top: 2px;  padding-bottom: 2px;"><input type="type" class="taxableamount" value="' +parseFloat(TotalTaxableAmount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') +'"></td>'+
																	'<td style="padding: 0px;" valign="middle"><center><button type="button" class="btn-danger" id="btnDelRow1"><i class="fa fa-times"></i></button></center></td></tr>');
		
		var WTAXAmount = parseFloat(TotalTaxableAmount) * parseFloat(rate/100);
		$('input[name=txtWTaxAmount]').val(formatMoney2(WTAXAmount));	

		if(category == 'I')
		{
			var TotalWTax = parseFloat(txtTotalPaymentDue) - parseFloat(WTAXAmount);
			$('input[name=txtTotalPaymentDue]').val(formatMoney2(TotalWTax));
		}
	});


	//Load Warehouse
	$('#WhsModal').on('shown.bs.modal',function(){
			
			$('#WhsCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
			$('#WhsCont').load('../../proc/views/APV/vw_whslist.php',function(){

			});
			
			$('input[name=WhsSearch]').focus();
	})
	//End Load Warehouse

	//Clear Warehouse
	$('#WhsModal').on('hide.bs.modal',function(){
		$('#WhsCont').empty();

	})
	//End Clear warehouse

	//Add Keypress on Whs MOdal
	$('#WhsModal').keydown(function(e) {
		    switch(e.which) {
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
		        	if($('#tblWhs tbody').find('tr.selected-whs').index() >= 0){
		        		$('tr.selected-whs').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblWhs tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;



		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//End
	
	
	//Highlight Warehouse Table Row Click
	$(document.body).on('click','#tblWhs tbody > tr',function(e){

		
		highlight('#tblWhs',this);

	})
	//End Highlight Warehouse Table Row Click



	
	//Select Warehouse Table Row Click
	$(document.body).on('dblclick','#tblWhs tbody > tr',function(){

		var warehouse = $(this).children('td.item-2').text();
		var warehousecode = $(this).children('td.item-1').text();
		
		$('#WhsModal').modal('hide');

		//$('#WhsModal').on('hidden.bs.modal',function(){
			
			if(activewhs == undefined){
				//Details Warehouse
				$('.selected-det').find('input.warehouse').val(warehousecode);
				
				//End Details Warehouse
			}else{
				//Header Warehouse

				$('input[name=txtWarehouse]').val(warehouse);
				$('input[name=txtWarehouse]').attr('aria-whscode',warehousecode);
				//End Header Warehouse

				if(confirm('Do you want to update warehouse details?')){
					$('input.warehouse').val(warehousecode);
				}
			}
			
		
		//})
	})
	//End Select Warehouse Table Row Click


	//Check active whs
	$(document.body).on('click','.input-group-addon',function(){
		activewhs = $(this).parent().find('input').attr('name');
	})
	//End Check active whs


	//Whs Code Bind
	$(document.body).on('blur','#tblDetails .warehouse',function(){
		var whs = checkwhs($(this).val()).split(';');
		//whs[0] - WhsCode
		//whs[1] - WhsName
		

		//Details Item
		if($.trim(whs[0]) == ''){
			$('.selected-det').find('input.warehouse').val('');
			
		}else{
			$('.selected-det').find('input.warehouse').val(whs[0]);
		}
		
		//End Details Item
		
	})
	//End Whs Code Bind


	//Search Whs
	$(document.body).on('keyup','input[name=WhsSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
        $('#WhsCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#WhsCont table tbody').load('../../proc/views/APV/vw_whslist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Whs

	//---------------------------------------------------------------- Department Modal ----------------------------------------------------//

	//Load Department
	$('#DepartmentModal').on('shown.bs.modal',function()
	{
		$('#DepartmentCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#DepartmentCont').load('../../proc/views/APV/vw_deptlist.php');
		
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
        $('#DepartmentCont table tbody').load('../../proc/views/APV/vw_deptlist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Department
	
	//---------------------------------------------------------------- Project Modal ----------------------------------------------------//
	
	$('#ProjectModal').on('shown.bs.modal',function()
	{
		$('#ProjectCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#ProjectCont').load('../../proc/views/APV/vw_projlist.php');
		
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

	//End
	
	
	//Highlight Project Table Row Click
	$(document.body).on('click','#tblProject tbody > tr',function(e){

		
		highlight('#tblProject',this);

	})
	//End Highlight Project Table Row Click
	
	$(document.body).on('dblclick','#tblProject tbody > tr',function()
	{
		var projectcode = $(this).children('td.item-1').text();
		var projectname = $(this).children('td.item-2').text();
		
		$('#ProjectModal').modal('hide');

		$('.selected-det').find('input.projectcode').val(projectcode);
		$('.selected-det').find('input.projectname').val(projectname);
	
	});
	//End Select Project Table Row Click
	
	//Search Project
	$(document.body).on('keyup','input[name=ProjectSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
        $('#ProjectCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#ProjectCont table tbody').load('../../proc/views/APV/vw_projlist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Department
	
	//---------------------------------------------------------------- Employee Modal ----------------------------------------------------//
	
	$('#EmployeeModal').on('shown.bs.modal',function()
	{
		$('#EmployeeCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#EmployeeCont').load('../../proc/views/APV/vw_emplist.php');
		
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
	$(document.body).on('click','#tblEmployee tbody > tr',function(e){

		
		highlight('#tblEmployee',this);

	})
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
	$(document.body).on('keyup','input[name=EmployeeSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
        $('#EmployeeCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#EmployeeCont table tbody').load('../../proc/views/APV/vw_emplist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Employee

		//---------------------------------------------------------------- Equipment Modal ----------------------------------------------------//
	
		$('#EquipmentModal').on('shown.bs.modal',function()
		{
			$('#EquipmentCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
			$('#EquipmentCont').load('../../proc/views/APV/vw_equiplist.php');
			
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
			$('#EquipmentCont table tbody').load('../../proc/views/APV/vw_equiplist-load.php?srchval=' + encodeURI(searchVal));
		})
		//End Search Equipment
	

	//Compute Line Total
	$(document.body).on('keyup','.qty',function(){
		$('.selected-det').find('input.linetotal').trigger('keyup');

		//GrossPrice
		computeGPAutoTrigger();
		//End Gross Price
	})
	//End Compute Line Total


	//Compute Line Total
	$(document.body).on('blur','.price',function(){
		
		var price = $('.selected-det').find('input.price').val().replace(/,/g, '');
		if(servicetype == 'I'){
			$('.selected-det').find('input.linetotal').trigger('keyup');
			//GrossPrice
			computeGPAutoTrigger();
			//End Gross Price
		}else{
			//GrossPrice
			computeGPAutoTrigger();
			//End Gross Price

			//Compute LineTotal
			$('input[name=TotBefDisc]').trigger('keyup');
			//End Compute Line Total
		}
		
		if(price != '')
		{
			$('.selected-det').find('input.price').val(formatMoney8(parseFloat(price)));
		}
		
	})

	//End Compute Line Total


	//Compute Line Total
	$(document.body).on('keyup','.discount',function(){
		$('.selected-det').find('input.linetotal').trigger('keyup');

		//GrossPrice
		computeGPAutoTrigger();
		//End Gross Price
	})

	//End Compute Line Total



	//Compute Details Line Total
	$(document.body).on('keyup','.linetotal',function(){
		var qty = $('.selected-det').find('input.qty').val();
		var price = $('.selected-det').find('input.price').val();
		var discount = $('.selected-det').find('input.discount').val();

		$('.selected-det').find('input.linetotal').val(computeLineTotal(qty,price,discount));

		if(servicetype == 'I'){
			$('input[name=TotBefDisc]').trigger('keyup');
		}
	})

	//End Compute Line Total



	//Compute Footer Line Total
	$(document.body).on('keyup','input[name=TotBefDisc]',function(){
		
		if(servicetype == 'I'){
			$('input[name=TotBefDisc]').val(computeTotalAmount('linetotal'));
			$('input[name=txtDiscAmtF]').trigger('keyup');
			$('input[name=txtTotalPaymentDue]').trigger('keyup');	
		}else{
			$('input[name=TotBefDisc]').val(computeTotalAmount('price'));
			$('input[name=txtDiscAmtF]').trigger('keyup');
			$('input[name=txtTotalPaymentDue]').trigger('keyup');
		}
		
	})

	//End Compute Footer Line Total






	//Compute GrossPrice
	$(document.body).on('blur','.grossprice',function(e){

		computeUPAutoTrigger();

	})

	//End Compute GrossPrice


	//Compute GrossPrice
	$(document.body).on('change','.taxcode',function(){
		
		
		//GrossPrice
		computeGPAutoTrigger();
		//End Gross Price
		
	})

	//End Compute GrossPrice
	
	$('#CtrlAcctModal').on('shown.bs.modal',function()
	{
		
		$('#CtrlAcctCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#CtrlAcctCont').load('../../proc/views/APV/vw_ctrlacctlist.php',function(){
			//Add Scroll Function 
		    $('#CtrlAcctCont .table-responsive').bind('scroll', function()
			{
		        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
		              if($(this).scrollTop() > 0){
		                var itemcode = $('#CtrlAcctCont table tbody > tr:last').children('td').eq(0).text();
		                var ctr = 0;

		                $('#itm-loader').each(function () {
		                  ctr += 1;
		                });
		                if(ctr == 0){
		                  $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		                	
		                  
		                  $.ajax({
		                        type: 'POST',
		                        url: '../../proc/views/APV/vw_ctrlacctlist-load.php',
		                        data: {
									itemcode : itemcode
								},
		                        success: function(html){

		                          $('#CtrlAcctCont table tbody').append(html);                
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
		
		$('#CtrlAcctSearch').focus();     
	})
	//End Load Accounts

	//Clear Accounts
	$('#CtrlAcctModal').on('hide.bs.modal',function()
	{
		$('#CtrlAcctCont').empty();
	});
	//End Clear Accounts

	//Add Keypress on Acct MOdal
	$('#CtrlAcctModal').keydown(function(e) 
	{
		switch(e.which) 
		{
			case 13: //Enter
				$('#tblCtrlAcct tbody > tr:first').trigger('dblclick');
			break;
			
			default: return; // exit this handler for other keys
		}
		e.preventDefault(); // prevent the default action (scroll / move caret)
	});
	//End Add Keypress on Acct Modal

	//Highlight Item Table Row Click
	$(document.body).on('click','#tblCtrlAcct tbody > tr',function(e)
	{
		highlight('#tblCtrlAcct',this);
	});
	//End Highlight Item Table Row Click

	//Select Acct Table Row Click
	$(document.body).on('dblclick','#tblCtrlAcct tbody > tr',function()
	{
		var acctname = $(this).children('td.item-1').text();
		var acctcode = $(this).children('td.item-2').text();
		var acct = $(this).children('td.item-3').text();
		$('#CtrlAcctModal').modal('hide');

		$('input[name=txtCtlAcctCode]').val(acctcode);
		$('input[name=txtCtlAcctName]').val(acctname);
		//Details Item
		/* $('.selected-det').find('input.acctcode').val(acctcode);
		$('.selected-det').find('input.acctname').val(acctname);
		$('.selected-det').find('input.acctcode').focus(); */

		//Add Account Code
		/* $('.selected-det').find('input.acctcode').attr('aria-acctcode',acct); */
		//End Add Account Code
		//End Details Item
	});
	//End Select Acct Table Row Click

	//Search Acct
	$(document.body).on('keyup','input[name=CtrlAcctSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
        $('#CtrlAcctCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#CtrlAcctCont table tbody').load('../../proc/views/APV/vw_ctrlacctlist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Acct
	
	//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	//Load Accounts
	$('#AcctModal').on('shown.bs.modal',function(){
		
		$('#AcctCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#AcctCont').load('../../proc/views/APV/vw_acctlist.php',function(){
			//Add Scroll Function 
		    $('#AcctCont .table-responsive').bind('scroll', function(){
		        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
		              if($(this).scrollTop() > 0){
		                var itemcode = $('#AcctCont table tbody > tr:last').children('td').eq(0).text();
		                var ctr = 0;

		                $('#itm-loader').each(function () {
		                  ctr += 1;
		                });
		                if(ctr == 0){
		                  $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		                	
		                  
		                  $.ajax({
		                        type: 'POST',
		                        url: '../../proc/views/APV/vw_acctlist-load.php',
		                        data: {
									itemcode : itemcode
								},
		                        success: function(html){

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
	})
	//End Load Accounts

	//Clear Accounts
	$('#AcctModal').on('hide.bs.modal',function(){
		$('#AcctCont').empty();

	})
	//End Clear Accounts

	//Add Keypress on Acct MOdal
	$('#AcctModal').keydown(function(e) {
		    switch(e.which) {
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
		        	if($('#tblAcct tbody').find('tr.selected-whs').index() >= 0){
		        		$('tr.selected-whs').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblAcct tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;



		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//End Add Keypress on Acct Modal


	//Highlight Item Table Row Click
	$(document.body).on('click','#tblAcct tbody > tr',function(e){

		
		highlight('#tblAcct',this);

	})
	//End Highlight Item Table Row Click



	//Select Acct Table Row Click
	$(document.body).on('dblclick','#tblAcct tbody > tr',function(){

		var acctname = $(this).children('td.item-1').text();
		var acctcode = $(this).children('td.item-2').text();
		var acct = $(this).children('td.item-3').text();
		$('#AcctModal').modal('hide');


		//Details Item
		$('.selected-det').find('input.acctcode').val(acctcode);
		$('.selected-det').find('input.acctname').val(acctname);
		$('.selected-det').find('input.acctcode').focus();

		//Add Account Code
		$('.selected-det').find('input.acctcode').attr('aria-acctcode',acct);
		//End Add Account Code
		//End Details Item
		
		

	})
	//End Select Acct Table Row Click



	//Search Acct
	$(document.body).on('keyup','input[name=AcctSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
        $('#AcctCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#AcctCont table tbody').load('../../proc/views/APV/vw_acctlist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Acct



	//Acct Code Bind
	$(document.body).on('blur','#tblDetails .acctcode',function(){
		var acct = checkacctcode($(this).val()).split(';');
		//acct[0] - AcctName
		//acct[1] - FormatCode
		//acct[2] - AcctCode
		

		//Details Item
		if($.trim(acct[0]) == ''){
			$('.selected-det').find('input.acctcode').val('');
			$('.selected-det').find('input.acctname').val('');
			//$('.selected-det').find('input.itemcode').focus();
		}else{
			$('.selected-det').find('input.acctcode').val(acct[1]);
			$('.selected-det').find('input.acctname').val(acct[0]);
			//Add Account Code
			$('.selected-det').find('input.acctcode').attr('aria-acctcode',acct[2]);
			//End Add Account Code
		}
		
		//End Details Item
		
	})
	//End Acct Code Bind



	//Load Inventory Data
	$('#InvDataModal').on('shown.bs.modal',function(){
		var itemcode = $('.selected-det').find('input.itemcode').val();
		
		if(itemcode != ''){
			$('#InvDataCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
			$('#InvDataCont').load('../../proc/views/APV/vw_invdatalist.php?itemcode=' + itemcode,function(){

			});
		}else{
			$('#InvDataCont').html('<h2 class="text-center">NO RESULT!</h2>');
		}
	})
	//End Load Inventory Data



	//Clear Inventory Data
	$('#InvDataModal').on('hide.bs.modal',function(){
		$('#InvDataCont').empty();

	})
	//End Clear Inventory Data




	//Find Document
	$(window).keydown(function(e) {
		
	    if(e.keyCode == 70 && e.ctrlKey){
	    	//Ctrl + f
	    	$('#DocumentModal').modal('show');
	    	e.preventDefault();
	    }else if(e.keyCode == 65 && e.ctrlKey){
	    	//ctrl + a
	    	//$('#DocumentModal').modal('show');
	    	//e.preventDefault();
	    	//alert('asdf')
	    }
	    //e.preventDefault(); // prevent the default action (scroll / move caret)
	});

	//End Find Document

	//Load Documents
	$('#DocumentModal').on('shown.bs.modal',function(){
			
		$('#DocumentCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#DocumentCont').load('../../proc/views/APV/vw_doclist.php?servicetype=' + encodeURI(servicetype),function(){
			//Add Scroll Function 
		    $('#DocumentCont .table-responsive').bind('scroll', function(){
		        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
		              if($(this).scrollTop() > 0){
		                var itemcode = $('#DocumentCont table tbody > tr:last').children('td').eq(0).text();
		                var ctr = 0;

		                $('#itm-loader').each(function () {
		                  ctr += 1;
		                });
		                if(ctr == 0){
		                  $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		                	
		                  
		                  $.ajax({
		                        type: 'POST',
		                        url: '../../proc/views/APV/vw_doclist-load.php',
		                        data: {
									itemcode : itemcode
								},
		                        success: function(html){

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
	$('#DocumentModal').on('hide.bs.modal',function(){
		$('#DocumentCont').empty();

		
	})
	//End Clear Document List



	//Add Keypress on DOcument MOdal
	$('#DocumentModal').keydown(function(e) {
		    switch(e.which) {
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
		        	if($('#tblDocument tbody').find('tr.selected-whs').index() >= 0){
		        		$('tr.selected-whs').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblDocument tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;



		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//End Add Keypress on Document Modal


	//Highlight Document Table Row Click
	$(document.body).on('click','#tblDocument tbody > tr',function(e){

		highlight('#tblDocument',this);

	})
	//End Highlight Document Table Row Click



	//Search Document
	$(document.body).on('keyup','input[name=DocumentSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
		
        $('#DocumentCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#DocumentCont table tbody').load('../../proc/views/APV/vw_doclist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Document


	//Select Document Table Row Click
	$(document.body).on('dblclick','#tblDocument tbody > tr',function(){

		var docentry = $(this).children('td.item-1').text();
		
		$('#DocumentModal').modal('hide');

		$('input[name=txtDocEntry]').val(docentry);
		$('input[name=txtDocEntry]').trigger('keyup');
		

		
	})
	//End Select Document Table Row Click


	//Populate Data
	$(document.body).on('keyup','input[name=txtDocEntry]',function(){
		var docentry = $(this).val();

		//Get PR data using JSON
		$.getJSON('../../proc/views/APV/vw_getdocumentdata.php?docentry=' + docentry, function(data) {
            /* data will hold the php array as a javascript object */
            
           		$('#modal-load-init').modal('show');
           		//$('#tblDetails tbody').empty();	
	            $.each(data, function(key, val) {

	            	//Populate Header
	            	if(val.DocStatus == 'C')
					{
						$('#btnSeries').html(val.SeriesName);
					    $('#btnSeries').attr('series-val',val.Series);
					    $('#btnSeries').attr('bplid-val',val.BPLId);
					    $('#btnSeries').prop('disabled',true);
					    $('#btnSeriesDD').prop('disabled',true);
						
	            		$('input[name=txtDocNo]').val(val.DocNum).prop('disabled',true);
		            	$('input[name=txtVendor]').val(val.CardCode).prop('disabled',true);
		            	$('input[name=txtName]').val(val.CardName).prop('disabled',true);
		            	$('input[name=txtContactPerson]').val(val.Name).prop('disabled',true);
		            	$('input[name=txtVendorRefNo]').val(val.NumAtCard).prop('disabled',true);
		            	$('select[name=txtPayment]').val(val.GroupNum).prop('disabled',true);
						$('textarea[name=txtRemarksF]').val(val.Comments).prop('disabled',true);
		            	$('input[name=txtPostingDate]').val(val.DocDate).prop('disabled',true);
		            	$('input[name=txtDeliveryDate]').val(val.DocDueDate).prop('disabled',true);
		            	$('input[name=txtDocDate]').val(val.TaxDate).prop('disabled',true);
		            	$('input[name=txtCtlAcctCode]').val(val.CtlAccount).prop('disabled',true);
		            	$('input[name=txtCtlAcctName]').val(val.CtrlAcctName).prop('disabled',true);
						
		            	$('input[name=txtWarehouse]').prop('disabled',true);
		            	if(val.Canceled == 'Canceled')
						{
							$('input[name=txtDocStatus]').val(val.Canceled);
						}
						else
						{
							$('input[name=txtDocStatus]').val('Closed');
						}
						
						$('select[name=txtSalesEmployee]').val(val.SlpCode).prop('disabled', true);
						$('input[name=txtOwner]').val(val.employeename).prop('disabled', true);
						$('input[name=txtOwnerCode]').val(val.OwnerCode);
		            	$('select[name=cmbServiceType]').val(val.DocType).trigger('change').prop('disabled',true);
						
						$('select[name=selCurSource]').val(val.CurSource).prop('disabled', true);
						$('select[name=selDocCur]').html('<option value="'+val.DocCur+'" >'+val.DocCur+'</option>').prop('disabled',true);
						$('input[name=txtDocRate]').val(val.DocRate).prop('disabled', true);
		            	
						$('textarea[name=txtShipTo]').val(val.Address2).prop('disabled',true);
						$('textarea[name=txtBillTo]').val(val.Address).prop('disabled',true);
						
						$('input[name=txtListNum]').val(val.ListNum).prop('disabled',true);

						
		            	setTimeout(function(){
		            		$('input[name=TotBefDisc]').val(val.TotBefDisc);
			            	$('input[name=txtDiscPercentF]').val(val.DiscPrcnt).prop('disabled',true);
			            	$('input[name=txtDiscAmtF]').val(val.DiscSum);
			            	$('input[name=txtTaxF]').val(val.VatSum);
			            	$('input[name=txtWTaxAmount]').val(val.WTSum);
			            	$('input[name=txtTotalPaymentDue]').val(val.DocTotal);
		            	},1000)
		            	

		            	$('#btnCancelDoc').prop('disabled',true);
		            	$('#btnCloseDoc').prop('disabled',true);
		            	$('#btnPrint').prop('disabled',false);
						$('#btnCpy').prop('disabled',true);
		            	$('#btnCpyFrm').prop('disabled',true);
		            	disablebuttons(true);
						
						$('#btnSave').addClass('hidden');
						$('#btnCpyFrm').addClass('hidden');
					}
					else
					{
		           		$('#btnSeries').html(val.SeriesName);
					    $('#btnSeries').attr('series-val',val.Series);
					    $('#btnSeries').attr('bplid-val',val.BPLId);
					    $('#btnSeries').prop('disabled',true);
					    $('#btnSeriesDD').prop('disabled',true);
						
		           		$('input[name=txtDocNo]').val(val.DocNum);
		            	$('input[name=txtVendor]').val(val.CardCode).prop('disabled',true);
		            	$('input[name=txtName]').val(val.CardName).prop('disabled',true);
		            	$('input[name=txtContactPerson]').val(val.Name).prop('disabled',true);
		            	$('input[name=txtVendorRefNo]').val(val.NumAtCard).prop('disabled',true);
		            	$('select[name=txtPayment]').val(val.GroupNum).prop('disabled',true);
						$('textarea[name=txtRemarksF]').val(val.Comments).prop('disabled',false);
		            	$('input[name=txtPostingDate]').val(val.DocDate).prop('disabled',true);
		            	$('input[name=txtDeliveryDate]').val(val.DocDueDate).prop('disabled',true);
		            	$('input[name=txtDocDate]').val(val.TaxDate).prop('disabled',true);
						$('input[name=txtCtlAcctCode]').val(val.CtlAccount).prop('disabled',true);
		            	$('input[name=txtCtlAcctName]').val(val.CtrlAcctName).prop('disabled',true);
						
				    	$('input[name=txtWarehouse]').prop('disabled',true);
		            	$('input[name=txtDocStatus]').val('Open');
						$('select[name=txtSalesEmployee]').val(val.SlpCode).prop('disabled', false);
						$('input[name=txtOwner]').val(val.employeename).prop('disabled', false);
						$('input[name=txtOwnerCode]').val(val.OwnerCode);
		            	$('select[name=cmbServiceType]').val(val.DocType).trigger('change').prop('disabled',true);
						
						$('select[name=selCurSource]').val(val.CurSource).prop('disabled', true);
						$('select[name=selDocCur]').html('<option value="'+val.DocCur+'" >'+val.DocCur+'</option>').prop('disabled',true);
						$('input[name=txtDocRate]').val(val.DocRate).prop('disabled', true);

						$('textarea[name=txtShipTo]').val(val.Address2).prop('disabled',false);
						$('textarea[name=txtBillTo]').val(val.Address).prop('disabled',false);
						
						$('input[name=txtListNum]').val(val.ListNum).prop('disabled',true);

						setTimeout(function(){
			            	$('input[name=TotBefDisc]').val(val.TotBefDisc);
			            	$('input[name=txtDiscPercentF]').val(val.DiscPrcnt).prop('disabled',true);
			            	$('input[name=txtDiscAmtF]').val(val.DiscSum);
			            	$('input[name=txtTaxF]').val(val.VatSum);
							$('input[name=txtWTaxAmount]').val(val.WTSum);
			            	$('input[name=txtTotalPaymentDue]').val(val.DocTotal);

			            },500)
						
						$('#btnCancelDoc').prop('disabled',false);
		            	$('#btnCloseDoc').prop('disabled',false);
		            	$('#btnPrint').prop('disabled',false);
						$('#btnCpy').prop('disabled',false);
		            	$('#btnCpyFrm').prop('disabled',false);
						
						$('#btnSave').addClass('hidden');
						$('#btnCpyFrm').addClass('hidden');
						
		            	disablebuttons(true);
		           	}
		            
	            	//End Populate Header
	            
	            })
				
				//Populate Details
	            setTimeout(function(){
	            	populatedet(docentry,function(){
            			//$('input[name=TotBefDisc]').trigger('keyup')
		            	$('#modal-load-init').modal('hide');
	            		
	            		
	            	});
					
					populatewithholding(docentry,function()
					{
						$('#modal-load-init').modal('hide');
					});
	            },500)
            	
            	//End Populate Details
	            
				
				

				
			
			
			
        });
		//End Get PR data using JSON
	})
	//End Populate Data
	
	
	//Load Business Partner
	$('#BPModal').on('shown.bs.modal',function(){
		
		$('#BPCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#BPCont').load('../../proc/views/APV/vw_bplist.php?CardType=S',function(){
			//Add Scroll Function 
		    $('#BPCont .table-responsive').bind('scroll', function(){
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
                                url: '../../proc/views/APV/vw_bplist-load.php',
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
		    })
		    //End Add Scroll Function
		});
		
		$('input[name=BPSearch]').focus();
	})
	//End Load Business Partner

	//Clear Business Partner Data
	$('#BPModal').on('hide.bs.modal',function(){
		$('#BPCont').empty();

	})
	//End Clear Business Partner Data


	//Add Keypress on Business Partner MOdal
	$('#BPModal').keydown(function(e) 
	{
		switch(e.which) 
		{
			case 13: //Enter
				$('#tblBP tbody > tr:first').trigger('dblclick');
			break;
			
			default: return; // exit this handler for other keys
		}
		e.preventDefault(); // prevent the default action (scroll / move caret)
	});

	//End Add Keypress on Business Partner Modal


	//Highlight Item Table Row Click
	$(document.body).on('click','#tblBP tbody > tr',function(e){

		
		highlight('#tblBP',this);

	})
	//End Highlight Item Table Row Click



	//Select Acct Table Row Click
	$(document.body).on('dblclick','#tblBP tbody > tr',function()
	{
		var BPCode = $(this).children('td.item-1').text();
		var BPName = $(this).children('td.item-2').text();
		var Balance = $(this).children('td.item-3').text();
		var ContactPerson = $(this).children('td.item-4').text();
		var Payment = $(this).children('td.item-5').text();
		var Currency = $(this).children('td.item-6').text();
		var ListNum = $(this).children('td.item-7').text();
		var CardCode = $(this).children('td.item-8').text();
		var CardName = $(this).children('td.item-9').text();
		
		$('input[name=txtVendor]').val(BPCode);
		$('input[name=txtName]').val(BPName);
		$('input[name=txtContactPerson]').val(ContactPerson);
		$('select[name=txtPayment]').val(Payment);
		$('input[name=txtListNum]').val(ListNum);
		$('input[name=txtCtlAcctCode]').val(CardCode);
		$('input[name=txtCtlAcctName]').val(CardName);
		
		if(Currency == '##') 
		{
			$('select[name=selCurSource]').val('C').prop('disabled', false);
			
			$('select[name=selDocCur]').html('<option>Loading...</option>');
			$('select[name=selDocCur]').load('../../proc/views/APV/vw_doccur.php', function () {

			})
		}
		else 
		{
			$('select[name=selCurSource]').val('C').prop('disabled', true);
			$('select[name=selDocCur]').html('<option value="'+Currency+'" >'+Currency+'</option>').prop('disabled',true);
		}
		
		//Load Ship To
		$('textarea[name=txtShipTo]').html('Loading...');
		$('textarea[name=txtShipTo]').load('../../proc/views/APV/vw_shipto.php?cardcode=' + encodeURI(BPCode));
		
		//Load Bill To
		$('textarea[name=txtBillTo]').html('Loading...');
		$('textarea[name=txtBillTo]').load('../../proc/views/APV/vw_billto.php?cardcode=' + encodeURI(BPCode));
		
		
		$('#BPModal').modal('hide');


		
	

	})
	//End Select Acct Table Row Click

	//Search BP
	$(document.body).on('keyup','input[name=BPSearch]',function(){

		var searchVal = $(this).val().toLowerCase();
		
        $('#BPCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#BPCont table tbody').load('../../proc/views/APV/vw_bplist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search BP


	//BP Code Bind
	$(document.body).on('blur','input[name=txtVendor]',function(){
		var bp = checkbpcode($(this).val()).split(';');
		//bp[0] - CardCode
		//bp[1] - CardName
		//bp[2] - Balance
		//bp[3] - Contact Person
		$('input[name=txtVendor]').val(bp[0]);
		$('input[name=txtName]').val(bp[1]);
		$('input[name=txtContactPerson]').val(bp[3]);

		
		
	})
	//End BP Code Bind
	
	$(document.body).on('blur','input[name=txtVendorRefNo]',function()
	{
		var refno = $(this).val();
		
		if(refno != '')
		{
			$.ajax({
				type: 'POST',
				url: '../../proc/views/APV/vw_refno.php',
				data: 
				{
					refno : refno
				},
				success: function (data) 
				{
					if(data != 0)
					{
						notie.alert(2, 'Warning! Reference no. is already used.', 10);
					}
				}
			});
		}
	});

	//Trigger Discount Amount Footer to compute 
	$(document.body).on('keyup','input[name=txtDiscPercentF]',function(){

		$('input[name=txtDiscAmtF]').trigger('keyup');
	})
	//End Trigger Discount Amount Footer to compute

	

	//Compute Discount Amount Footer
	$(document.body).on('keyup','input[name=txtDiscAmtF]',function(){
		
		var totalbefdisc = $('input[name=TotBefDisc]').val();
		var discount = $('input[name=txtDiscPercentF]').val();

		$('input[name=txtDiscAmtF]').val(computeDiscountAmt(totalbefdisc,discount));
		$('input[name=txtTaxF]').trigger('keyup');
		$('input[name=txtTotalPaymentDue]').trigger('keyup');
		
	})

	//Compute Tax Amount Details
	$(document.body).on('keyup','.taxamount',function(){
		if (servicetype == 'I') {
			var linetotal = $('.selected-det').find('input.linetotal').val();
			var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
			$('.selected-det').find('input.taxamount').val(computeTaxAmt(linetotal,taxrate));

			$('input[name=txtTaxF]').trigger('keyup');
		}else{
			var linetotal = $('.selected-det').find('input.price').val();
			var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
			$('.selected-det').find('input.taxamount').val(computeTaxAmt(linetotal,taxrate));

			$('input[name=txtTaxF]').trigger('keyup');
		}
		
		
	})
	//End Compute Tax Amount Details


	//Compute Tax Amount Footer
	$(document.body).on('keyup','input[name=txtTaxF]',function(){
		var totaltax = computeTotalAmount('taxamount');
		var discount = $('input[name=txtDiscPercentF]').val();
		
		$('input[name=txtTaxF]').val(computeTaxAmtFooter(totaltax,discount));

		$('input[name=txtTotalPaymentDue]').trigger('keyup');	
		
	})

	//End Tax Amount Footer

	//COmpute Total Payment Due
	$(document.body).on('keyup','input[name=txtTotalPaymentDue]',function(){
		var totalbefdisc = $('input[name=TotBefDisc]').val();
		var discount = $('input[name=txtDiscAmtF]').val();
		var totaltaxamt = $('input[name=txtTaxF]').val();
		$('input[name=txtTotalPaymentDue]').val(computeTPaymentDue(totalbefdisc,discount,totaltaxamt));


	})
	//End COmpute Total Payment Due



	//Load Base Document
	$(document.body).on('keyup','input[name=txtBaseEntry]',function(){
		var basentry = $(this).val();
		
		if(basentry != ''){
			//Show Base Document
			//Get PR data using JSON
			$.getJSON('../../proc/views/APV/vw_getpodata.php?docentry=' + basentry, function(data) {
	            /* data will hold the php array as a javascript object */
	           
	            
           		//$('#modal-load-init').modal('show');
           		$.each(data, function(key, val) {
	            	//Populate Header
	            	if(val.DocStatus == 'C'){

	            		$('input[name=txtVendor]').val(val.CardCode).prop('disabled',true);
		            	$('input[name=txtName]').val(val.CardName).prop('disabled',true);
		            	$('input[name=txtContactPerson]').val(val.Name).prop('disabled',true);
		            	$('input[name=txtVendorRefNo]').val(val.NumAtCard).prop('disabled',true);
		            	$('select[name=txtPayment]').val(val.GroupNum).prop('disabled',true);

		            	$('input[name=txtPostingDate]').val(val.DocDate).prop('disabled',true);
		            	$('input[name=txtDeliveryDate]').val(val.DocDueDate).prop('disabled',true);
		            	$('input[name=txtDocDate]').val(val.TaxDate).prop('disabled',true);
		            	
		            	$('input[name=txtDocStatus]').val('Closed');
		            	$('select[name=cmbServiceType]').val(val.DocType).trigger('change').prop('disabled',true);
						
						$('select[name=txtSalesEmployee]').val(val.SlpCode).prop('disabled', true);
						$('input[name=txtOwner]').val(val.employeename).prop('disabled', true);
						$('input[name=txtOwnerCode]').val(val.OwnerCode);
		            	
						$('select[name=selCurSource]').val(val.CurSource).prop('disabled', true);
						$('select[name=selDocCur]').html('<option value="'+val.DocCur+'" >'+val.DocCur+'</option>').prop('disabled',true);
						$('input[name=txtDocRate]').val(val.DocRate).prop('disabled', true);
						$('input[name=txtCtlAcctCode]').val(val.CtlAccount).prop('disabled',true);
		            	$('input[name=txtCtlAcctName]').val(val.CtrlAcctName).prop('disabled',true);
						
		            	$('textarea[name=txtRemarksF]').val(val.Comments + ' ' + val.NumAtCard).prop('disabled',true);
						
						$('input[name=txtListNum]').val(val.ListNum).prop('disabled',true);
						
		            	$('textarea[name=txtShipTo]').val(val.Address2).prop('disabled',true);
		            	$('textarea[name=txtBillTo]').val(val.Address).prop('disabled',true);
		            	setTimeout(function(){
		            		$('input[name=TotBefDisc]').val(val.TotBefDisc);
			            	$('input[name=txtDiscPercentF]').val(val.DiscPrcnt).prop('disabled',true);
			            	$('input[name=txtDiscAmtF]').val(val.DiscSum);
			            	$('input[name=txtTaxF]').val(val.VatSum);
			            	$('input[name=txtTotalPaymentDue]').val(val.DocTotal);
		            	},1000)
		            	
		            	disablebuttons(true);
		           	}else{
		           		
		            	$('input[name=txtVendor]').val(val.CardCode).prop('disabled',false);
		            	$('input[name=txtName]').val(val.CardName).prop('disabled',false);
		            	$('input[name=txtContactPerson]').val(val.Name).prop('disabled',false);
		            	$('input[name=txtVendorRefNo]').val(val.NumAtCard).prop('disabled',false);
		            	$('select[name=txtPayment]').val(val.GroupNum).prop('disabled',false);

		            	$('input[name=txtPostingDate]').val(val.DocDate).prop('disabled',false);
		            	$('input[name=txtDeliveryDate]').val(val.DocDueDate).prop('disabled',false);
		            	$('input[name=txtDocDate]').val(val.TaxDate).prop('disabled',false);
						
		            	$('input[name=txtDocStatus]').val('Open');
		            	$('select[name=cmbServiceType]').val(val.DocType).trigger('change').prop('disabled',false);
						
						$('select[name=txtSalesEmployee]').val(val.SlpCode).prop('disabled', false);
						$('input[name=txtOwner]').val(val.employeename).prop('disabled', false);
						$('input[name=txtOwnerCode]').val(val.OwnerCode);
						
						$('select[name=selCurSource]').val(val.CurSource).prop('disabled', false);
						$('select[name=selDocCur]').html('<option value="'+val.DocCur+'" >'+val.DocCur+'</option>').prop('disabled',false);
						$('input[name=txtDocRate]').val(val.DocRate).prop('disabled', false);
						$('input[name=txtCtlAcctCode]').val(val.CtlAccount).prop('disabled',true);
		            	$('input[name=txtCtlAcctName]').val(val.CtrlAcctName).prop('disabled',true);

		            	$('input[name=txtListNum]').val(val.ListNum).prop('disabled',true);
						
						$('textarea[name=txtRemarksF]').val(val.Comments + ' ' + val.NumAtCard).prop('disabled',false);
						$('textarea[name=txtShipTo]').val(val.Address2).prop('disabled',false);
		            	$('textarea[name=txtBillTo]').val(val.Address).prop('disabled',false);
		            	setTimeout(function(){
			            	$('input[name=TotBefDisc]').val(val.TotBefDisc);
			            	$('input[name=txtDiscPercentF]').val(val.DiscPrcnt).prop('disabled',false);
			            	$('input[name=txtDiscAmtF]').val(val.DiscSum);
			            	$('input[name=txtTaxF]').val(val.VatSum);
			            	$('input[name=txtTotalPaymentDue]').val(val.DocTotal);

			            },500)

		            	
		           	}
		            
	            	//End Populate Header

	            }) // End each
				
				//Populate Details
	            setTimeout(function(){
	            	$('#modal-load-init').modal('hide');
	            	$('#modal-load-init').modal('show');
	            	populatedetPO(basentry,function(){
            			//$('input[name=TotBefDisc]').trigger('keyup')
		            	$('#modal-load-init').modal('hide');
	            		
	            		
	            	});
	            },500)
            	
            	//End Populate Details
	            
	        }) // End GetJSON

			//End Show Base Document
		}else{

		}

	})
	//End Load Base Document
	
	//Load Base Document
	$(document.body).on('keyup','input[name=txtDocEntryAn]',function(){
		var basentry = $(this).val();
		
		if(basentry != ''){
			//Show Base Document
			//Get PR data using JSON
			$.getJSON('../../proc/views/APV/vw_getpodata.php?docentry=' + basentry, function(data) {
	            /* data will hold the php array as a javascript object */
	           
	            
           		//$('#modal-load-init').modal('show');
           		$.each(data, function(key, val) {
	            	//Populate Header
	            	if(val.DocStatus == 'C'){

	            		$('input[name=txtVendor]').val(val.CardCode).prop('disabled',true);
		            	$('input[name=txtName]').val(val.CardName).prop('disabled',true);
		            	$('input[name=txtContactPerson]').val(val.Name).prop('disabled',true);
		            	$('input[name=txtVendorRefNo]').val(val.NumAtCard).prop('disabled',true);
		            	$('select[name=txtPayment]').val(val.GroupNum).prop('disabled',true);

		            	$('input[name=txtPostingDate]').val(val.TaxDate).prop('disabled',true);
		            	$('input[name=txtDeliveryDate]').val(val.DocDueDate).prop('disabled',true);
		            	$('input[name=txtDocDate]').val(val.DocDate).prop('disabled',true);
		            	$('input[name=txtDocStatus]').val('Closed');
		            	$('select[name=cmbServiceType]').val(val.DocType).trigger('change').prop('disabled',true);
						
						$('select[name=txtSalesEmployee]').val(val.SlpCode).prop('disabled', true);
						$('input[name=txtOwner]').val(val.employeename).prop('disabled', true);
						$('input[name=txtOwnerCode]').val(val.OwnerCode);
						$('select[name=selCurSource]').val(val.CurSource).prop('disabled', true);
						$('select[name=selDocCur]').html('<option value="'+val.DocCur+'" >'+val.DocCur+'</option>').prop('disabled',true);
						$('input[name=txtDocRate]').val(val.DocRate).prop('disabled', true);
						$('input[name=txtCtlAcctCode]').val(val.CtlAccount).prop('disabled',true);
		            	$('input[name=txtCtlAcctName]').val(val.CtrlAcctName).prop('disabled',true);
						
		            	$('textarea[name=txtRemarksF]').val(val.Comments + ' ' + val.NumAtCard).prop('disabled',true);
						
						$('input[name=txtListNum]').val(val.ListNum).prop('disabled',true);
						
		            	$('textarea[name=txtShipTo]').val(val.Address2).prop('disabled',true);
		            	$('textarea[name=txtBillTo]').val(val.Address).prop('disabled',true);
		            	setTimeout(function(){
		            		$('input[name=TotBefDisc]').val(val.TotBefDisc);
			            	$('input[name=txtDiscPercentF]').val(val.DiscPrcnt).prop('disabled',true);
			            	$('input[name=txtDiscAmtF]').val(val.DiscSum);
			            	$('input[name=txtTaxF]').val(val.VatSum);
			            	$('input[name=txtTotalPaymentDue]').val(val.DocTotal);
		            	},1000)
		            	
		            	disablebuttons(true);
		           	}else{
		           		
		            	$('input[name=txtVendor]').val(val.CardCode).prop('disabled',false);
		            	$('input[name=txtName]').val(val.CardName).prop('disabled',false);
		            	$('input[name=txtContactPerson]').val(val.Name).prop('disabled',false);
		            	$('input[name=txtVendorRefNo]').val(val.NumAtCard).prop('disabled',false);
		            	$('select[name=txtPayment]').val(val.GroupNum).prop('disabled',false);

		            	$('input[name=txtPostingDate]').val(val.TaxDate).prop('disabled',false);
		            	$('input[name=txtDeliveryDate]').val(val.DocDueDate).prop('disabled',false);
		            	$('input[name=txtDocDate]').val(val.DocDate).prop('disabled',false);
		            	$('input[name=txtDocStatus]').val('Open');
		            	$('select[name=cmbServiceType]').val(val.DocType).trigger('change').prop('disabled',false);
						
						$('select[name=txtSalesEmployee]').val(val.SlpCode).prop('disabled', false);
						$('input[name=txtOwner]').val(val.employeename).prop('disabled', false);
						$('input[name=txtOwnerCode]').val(val.OwnerCode);
						
						$('select[name=selCurSource]').val(val.CurSource).prop('disabled', false);
						$('select[name=selDocCur]').html('<option value="'+val.DocCur+'" >'+val.DocCur+'</option>').prop('disabled',false);
						$('input[name=txtDocRate]').val(val.DocRate).prop('disabled', false);

		            	$('input[name=txtListNum]').val(val.ListNum).prop('disabled',true);
						
						$('textarea[name=txtRemarksF]').val(val.Comments + ' ' + val.NumAtCard).prop('disabled',false);
						$('textarea[name=txtShipTo]').val(val.Address2).prop('disabled',false);
		            	$('textarea[name=txtBillTo]').val(val.Address).prop('disabled',false);
		            	setTimeout(function(){
			            	$('input[name=TotBefDisc]').val(val.TotBefDisc);
			            	$('input[name=txtDiscPercentF]').val(val.DiscPrcnt).prop('disabled',false);
			            	$('input[name=txtDiscAmtF]').val(val.DiscSum);
			            	$('input[name=txtTaxF]').val(val.VatSum);
			            	$('input[name=txtTotalPaymentDue]').val(val.DocTotal);

			            },500)

		            	
		           	}
		            
	            	//End Populate Header

	            }) // End each
				
				//Populate Details
	            setTimeout(function(){
	            	$('#modal-load-init').modal('hide');
	            	$('#modal-load-init').modal('show');
	            	populatedetPO(basentry,function(){
            			//$('input[name=TotBefDisc]').trigger('keyup')
		            	$('#modal-load-init').modal('hide');
	            		
	            		
	            	});
	            },500)
            	
            	//End Populate Details
	            
	        }) // End GetJSON

			//End Show Base Document
		}else{

		}

	})
	//End Load Base Document



	//Print Document
	/* $(document.body).on('click','#btnPrint',function(){
		var docentry = $('input[name=txtDocEntry]').val();
		var servicetype = $('select[name=cmbServiceType]').val();
		if(docentry != '')
		{
			if(servicetype == 'I')
			{
				window.open("../../report/APV/apv-report.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
			}
			else
			{
				window.open("../../report/APV/apv-report-s.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
			}
		}
	}) */
	//End Print Document
	
	$(document.body).on('click','#btnSelectPrint > li > a', function () 
	{
  		var href = $(this).attr('href');
		var docentry = $('input[name=txtDocEntry]').val();
		
  		if(href == '#APV')
      {
        window.open("../../report/APV/apv-report.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
        // if(docentry != '')
        // {
        //   if(servicetype == 'I')
        //   {
        //     window.open("../../report/APV/apv-report-item.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
        //   }
        //   else
        //   {
        //     window.open("../../report/APV/apv-report-service.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
        //   }
        // }
      }
		
		if(href == '#APV1')
		{
			$('#APVRangeModal').modal('show');
			 
		
			//window.open("../../report/APV/apv-report-service1.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
		}			
    });
	
	$('#APVRangeModal').on('shown.bs.modal', function () 
	{
		$('input[name=txtAPVFrom]').focus();
	});

    $(document.body).on('click', '#btnCopyFrom > li > a', function () 
	{
        var href = $(this).attr('href');
        var docentry = $('input[name=txtDocEntry]').val();
        if (href == '#GRPO') 
		{
            $('#POModal').modal('show');
        }   
    })

    $('#POModal').on('shown.bs.modal', function () {

        $('#POCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        var vendor = $('input[name=txtVendor]').val();
		
        $('#POCont').load('../../proc/views/APV/vw_podoclist.php?vendor=' + encodeURI(vendor), function () {
            //Add Scroll Function 
            $('#POCont .table-responsive').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 20) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#POCont table tbody > tr:last').children('td').eq(0).text();
                        var ctr = 0;

                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');


                            $.ajax({
                                type: 'POST',
                                url: '../../proc/views/APV/vw_podoclist-load.php?vendor=' + encodeURI(vendor),
                                data: {
									itemcode : itemcode
								},
                                success: function (html) {

                                    $('#POCont table tbody').append(html);
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
        $('input[name=POSearch]').focus();
    })
    //End Load PO Documents

    //Clear PO Document List
    $('#POModal').on('hide.bs.modal', function () {
        $('#POCont').empty();


    })
    //End PO Clear Document List



    //Add Keypress on DOcument MOdal
    $('#POModal').keydown(function (e) {
        switch (e.which) {
            case 13: //Enter
                $('#btnChoose').trigger('click');
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
                if ($('#tblPODocument tbody').find('tr.selected-whs').index() >= 0) {
                    $('tr.selected-whs').next().trigger('click');

                    //$('#WhsCont > .table-responsive').scrollTop(10);
                } else {
                    $('#tblPODocument tbody > tr:first').trigger('click');
                }
                //End
                break;



            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });

    //End Add Keypress on Document Modal

    //Search Document
    $(document.body).on('keyup', 'input[name=POSearch]', function () {
        var searchVal = $(this).val().toLowerCase();
        var vendor = $('input[name=txtVendor]').val();
		
        $('#POCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#POCont table tbody').load('../../proc/views/APV/vw_podoclist-load.php?srchval=' + encodeURI(searchVal) + '&vendor=' + encodeURI(vendor));
    })
    //End Search Document

    //Search PO Document
    $(document.body).on('keyup', 'input[name=POSearch]', function () {
        //var searchVal = $(this).val().toLowerCase();

        //Search multiple
        var $rows = $('#POCont table tbody tr');
        //$('#POCont').prepend('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Searching...</p>');

        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);


        }).hide();
        //End Search Multiple

    })
    //End Search PO Document


    //Highlight PR Table Row Click
    $(document.body).on('click', '#tblPODocument tbody > tr', function (e) {


        highlightmultiple('#tblPODocument', this);

    })
    //End Highlight PR Table Row Click
	
	//Select Document Table Row Click
	$(document.body).on('dblclick','#tblPODocument tbody > tr',function(){

		var docentry = $(this).children('td.item-1').text();
		
		$('#POModal').modal('hide');
		
		$('input[name=txtBaseEntry]').val(docentry);
		$('input[name=txtBaseEntry]').trigger('keyup');
		//window.open("../APV/APV.php?BaseEntry=" + docentry, "", "width=1130,height=550,left=220,top=110");
  		
		/* $('input[name=txtDocEntryAn]').val(docentry);
		$('input[name=txtDocEntryAn]').trigger('keyup'); */
		
	})

	$('#SerialModal').on('shown.bs.modal',function(){

		var qty = $('.selected-det').find('input.qty').val();
		var serialno = $('.selected-det').find('input.serialno').val();
		
		/* if(serialno != '')
		{
			var cars = 1;
			var text = "";
			var i;
			for (i = 0; i < serialno.length; i++) { 
				var name="<br><input type='text' name='CheckSerial[]' value=" + serialno + ">";
				$("#inputs").append(name);
			}
		} */
		
	})
	//SELECT Serial
	
	$(document.body).on('click', '#btnSerialNo', function(){
		var mySerialNo = new Array();
		$('input[name="CheckSerial[]"]').each(function() {
			mySerialNo.push($(this).val());
		});
		
		var SerialLength = mySerialNo.length;
		
		$('.selected-det').find('input.serialno').val(mySerialNo);
		$('.selected-det').find('input.qty').val(SerialLength);
		
		$('#SerialModal').modal('hide');
	});
	//SELECT Serial
	
	$(document.body).on('click','#btnAPVListVIEW',function(e)
	{
		$('#resView').empty();
		$('#resView').append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		
		var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtAPVListFrom = $('input[name=txtAPVListFrom]').val();
    	var txtAPVListTo = $('input[name=txtAPVListTo]').val();
		
		if(err == 0)
		{
			$.ajax({
				type: 'POST',
				url: '../../report/apv/rpt_view.php',
				data: {
					txtDateFrom : txtDateFrom,
					txtDateTo : txtDateTo,
					txtAPVListFrom : txtAPVListFrom,
					txtAPVListTo : txtAPVListTo
				},
				success: function (html) 
				{
					$('#resView').html(html);
				}
			});
		}
	});
	
	$('#APVListModal').on('shown.bs.modal',function()
	{
		$('#resView').append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		
		var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtAPVListFrom = $('input[name=txtAPVListFrom]').val();
    	var txtAPVListTo = $('input[name=txtAPVListTo]').val();
		
		if(err == 0)
		{
			$.ajax({
				type: 'POST',
				url: '../../report/apv/rpt_view.php',
				data: {
					txtDateFrom : txtDateFrom,
					txtDateTo : txtDateTo,
					txtAPVListFrom : txtAPVListFrom,
					txtAPVListTo : txtAPVListTo
				},
				success: function (html) 
				{
					$('#resView').html(html);
				}
			});
		}
	});
	
	$('#APVListModal').on('hide.bs.modal',function()
	{
		$('#resView').empty();
	});
	
	$(document.body).on('dblclick','#tblView tbody > tr',function()
	{
		var docentry = $(this).children('td.item-0').text();
		
		$('#APVListModal').modal('hide');

		$('input[name=txtDocEntry]').val(docentry);
		$('input[name=txtDocEntry]').trigger('keyup');
	});
	
	$(document.body).on('click','#tblView tbody > tr',function(e)
	{
		highlight('#tblView',this);
	});
	
	$(document.body).on('click','#btnAPVListPDF',function(e)
	{
    	var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtAPVListFrom = $('input[name=txtAPVListFrom]').val();
    	var txtAPVListTo = $('input[name=txtAPVListTo]').val();

		$('.apvlistrequired').each(function()
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
			window.open("../../report/apv/apvlist-report.php?txtAPVListFrom=" + encodeURI(txtAPVListFrom) + "&txtAPVListTo=" + encodeURI(txtAPVListTo) + "&txtDateFrom=" + encodeURI(txtDateFrom) + "&txtDateTo=" + encodeURI(txtDateTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });
	
	$(document.body).on('click','#btnAPVListEXCEL',function(e)
	{
    	var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtAPVListFrom = $('input[name=txtAPVListFrom]').val();
    	var txtAPVListTo = $('input[name=txtAPVListTo]').val();
    	
			$('.apvlistrequired').each(function()
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
			window.open("../../report/apv/apvlist-excel.php?txtAPVListFrom=" + encodeURI(txtAPVListFrom) + "&txtAPVListTo=" + encodeURI(txtAPVListTo) + "&txtDateFrom=" + encodeURI(txtDateFrom) + "&txtDateTo=" + encodeURI(txtDateTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });
	
	$(document.body).on('click','#btnAPVRangeGenerate',function(e)
	{
    	var err = 0;
    	var errmsg = '';
    	var txtAPVFrom = $('input[name=txtAPVFrom]').val();
    	var txtAPVTo = $('input[name=txtAPVTo]').val();
    	
			$('.apvrangerequired').each(function()
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
			window.open("../../report/APV/apv-report-service1.php?txtAPVFrom=" + encodeURI(txtAPVFrom) + "&txtAPVTo=" + encodeURI(txtAPVTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });
	 
	//Save GRPO
    $(document.body).on('click','#btnSaveAPV',function(e){
    	

    	var err = 0;
    	var errmsg = '';
    	var basentry = $('input[name=txtBaseEntry]').val();
    	var vendor = $('input[name=txtVendor]').val();
    	var contactperson = $('input[name=txtContactPerson]').val();
    	var numatcard = $('input[name=txtVendorRefNo]').val();
    	var paymentterms = $('select[name=txtPayment]').val();

    	var postingdate = $('input[name=txtPostingDate]').val();
    	var deliverydate = $('input[name=txtDeliveryDate]').val();
    	var documentdate = $('input[name=txtDocDate]').val();
		
    	var remarks = $('textarea[name=txtRemarksF]').val();

    	var discPercent = isFinite($('input[name=txtDiscPercentF]').val())? $('input[name=txtDiscPercentF]').val() : 0;
    	var tpaymentdue = $('input[name=txtTotalPaymentDue]').val();
    	var series = $('#btnSeries').attr('series-val');
    	var bplid = $('#btnSeries').attr('bplid-val');
		
		var selDocCur = $('select[name=selDocCur]').val();
		var selCurSource = $('select[name=selCurSource]').val();
		var txtDocRate = $('input[name=txtDocRate]').val();
		var txtDocRef = $('input[name=txtDocRef]').val();

    	var salesemployee = $('select[name=txtSalesEmployee]').val();
        var owner = $('input[name=txtOwnerCode]').val();
		
		var shipto = $('textarea[name=txtShipTo]').val();
		var billto = $('textarea[name=txtBillTo]').val();
		var txtCtlAcctCode = $('input[name=txtCtlAcctCode]').val();
		var txtCtlAcctName = $('input[name=txtCtlAcctName]').val();
    	
		
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


    	//Check if there's a row
    	if(servicetype == 'I'){
	    	if(err == 0){
	    		err = 1;
	    		errmsg = 'No item/s to process!';
				$('.itemcode').each(function(){
		    		err = 0;
		    		
		    		return false;
		    	})

		    }
		}else{
			if(err == 0){
	    		err = 1;
	    		errmsg = 'No item/s to process!';
				$('.remarks').each(function(){
		    		err = 0;
		    		
		    		return false;
		    	})

		    }
		}
	    //Check if there's a row
	 

    	//Check if qty fields are blank
    	
	    //Check if qty fields are blank



    	//Collect Details
		var json = '{';
		var otArr = [];
		var tbl2 = $('#tblDetails tbody tr').each(function(i) {  

		   
		        x = $(this).children();
		        var itArr = [];
		    
		        //x.each(function() {
		        if(servicetype == 'I'){
		        	//Item Type
		        	if($(this).find('td.ftext').text() == 'N'){
			        	itArr.push('"' + $(this).find('input.itemcode').val() + '"');
		        		itArr.push('"' + $(this).find('input.qty').val().replace(/,/g,'') + '"');
		        		itArr.push('"' + $(this).find('input.price').val().replace(/,/g,'') + '"');
		        		itArr.push('"' + $(this).find('input.warehouse').val() + '"');
		        		itArr.push('"' + $(this).find('select.taxcode').val() + '"');
		        		itArr.push('"' + $(this).find('input.discount').val().replace(/,/g,'') + '"');
		        		itArr.push('"' + $(this).find('input.grossprice').val().replace(/,/g,'') + '"');
		        		itArr.push('"' + $(this).find('input.taxamount').val().replace(/,/g,'') + '"');
		        		itArr.push('"' + $(this).find('input.linetotal').val().replace(/,/g,'') + '"');
		        		itArr.push('"' + $(this).find('input.grosstotal').val().replace(/,/g,'') + '"');
		        		itArr.push('"N"');
		        		itArr.push('"' + $(this).find('input.lineno').val().replace(/,/g,'') + '"');
						itArr.push('"' + $(this).find('input.serialno').val() + '"');
						itArr.push('"' + $(this).find('input.itemdetails').val().replace(/,/g, '') + '"');
						itArr.push('"' + $(this).find('input.departmentcode').val().replace(/,/g, '') + '"');
						itArr.push('"' + $(this).find('input.projectcode').val().replace(/,/g, '') + '"');
						itArr.push('"' + $(this).find('input.employeecode').val().replace(/,/g, '') + '"');
						itArr.push('"' + $(this).find('input.equipmentcode').val().replace(/,/g, '') + '"');
						itArr.push('"' + $(this).find('select.withholdingtax').val().replace(/,/g, '') + '"');
		        	}else{
		        		//Free Text
		        		itArr.push('"' + $(this).find('textarea.remarks').val().replace(/"/g,'\\"') + '"');
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
		        		itArr.push('"' + $(this).find('input.lineno').val().replace(/,/g,'') + '"');
		        		//End Free Text
		        	}
		        	//End Item Type
		        }else{
		        	//Service Type
		        	itArr.push('"' + $(this).find('textarea.remarks').val().replace(/"/g, '\'') + '"');
	        		itArr.push('"' + $(this).find('input.acctcode').attr('aria-acctcode') + '"');
	        		itArr.push('"' + $(this).find('input.price').val().replace(/,/g,'') + '"');
	        		itArr.push('"' + $(this).find('select.taxcode').val() + '"');
	        		itArr.push('"' + $(this).find('input.grossprice').val().replace(/,/g,'') + '"');
	        		itArr.push('"' + $(this).find('input.taxamount').val().replace(/,/g,'') + '"');
	        		itArr.push('"' + $(this).find('input.lineno').val().replace(/,/g,'') + '"');
					itArr.push('"' + $(this).find('input.departmentcode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.projectcode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.employeecode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.equipmentcode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('select.withholdingtax').val().replace(/,/g, '') + '"');
	        		
		        	//End Service Type

		        }
		        		
		            
		        //});
		    
		        otArr.push('"' + i + '": [' + itArr.join(',') + ']'); 
		     
		});
		//PARSE ALL SCRIPT
		json += otArr.join(",") + '}';
		//End Collect  Details

		var json1 = '{';
        var otArr1 = [];
        var tbl21 = $('#tblWTAXCodeList tbody tr').each(function (i) 
		{
            x = $(this).children();
            var itArr1 = [];

            if(servicetype == 'I') 
			{
				itArr1.push('"' + $(this).find('input.wtcode').val().replace(/,/g, '') + '"');
			}
			else
			{
				itArr1.push('"' + $(this).find('input.wtcode').val().replace(/,/g, '') + '"');
			}
			otArr1.push('"' + i + '": [' + itArr1.join(',') + ']'); 
			
		});
		json1 += otArr1.join(",") + '}';


    	if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/APV/exec-saveapv.php',
                data: {
                		json : json.replace(/(\r\n|\n|\r)/gm, ''),
                		json1 : json1.replace(/(\r\n|\n|\r)/gm, ''),
                		basentry : basentry,
                		vendor : vendor,
						contactperson : contactperson,
						numatcard : numatcard,
						paymentterms : paymentterms,
						postingdate : postingdate,
						deliverydate : deliverydate,
						documentdate : documentdate,
						remarks : remarks,
						discPercent : discPercent,
						series : series,
						bplid : bplid,
						servicetype : servicetype,
						tpaymentdue : tpaymentdue,
						salesemployee: salesemployee,
						txtDocRef: txtDocRef,
						owner: owner,
						selDocCur: selDocCur,
						selCurSource : selCurSource,
						txtDocRate : txtDocRate,
						txtCtlAcctCode : txtCtlAcctCode,
						txtCtlAcctName : txtCtlAcctName,
						shipto : shipto,
						billto : billto

                },
                success: function(html){
					
					res = html.split('*');
					if(res[0] == 'true'){
						//Alert Success
						notie.alert(1, res[1], 10);
						//End

						//Refresh the page
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/APV/APV.php');
						},2000)
						//End Refresh the page
					}
					else
					{
						//Alert when error
						 notie.alert(3, res[1], 10);
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
           
			notie.alert(3, errmsg, 10);
            //End
    	}
    })
    //End Save GRPO
	
	//Canceled SO
    $(document.body).on('click','#btnCancelAPV',function(e){
    	
		var err = 0;
    	var errmsg = '';
		var docentry = $('input[name=txtDocEntry]').val();
    	
		
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


    	//Check if there's a row
    	if(servicetype == 'I'){
	    	if(err == 0){
	    		err = 1;
	    		errmsg = 'No item/s to process!';
				$('.itemcode').each(function(){
		    		err = 0;
		    		
		    		return false;
		    	})

		    }
		}else{
			if(err == 0){
	    		err = 1;
	    		errmsg = 'No item/s to process!';
				$('.remarks').each(function(){
		    		err = 0;
		    		
		    		return false;
		    	})

		    }
		}
	    //Check if there's a row
	 

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
		}
	    //Check if qty fields are blank
		
    	if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/APV/exec-cancelapv.php',
                data: {
                	docentry : docentry
				},
                success: function(html){
					
					res = html.split('*');
					if(res[0] == 'true'){
						//Alert Success
						notie.alert(1, res[1], 10);
						//End

						//Refresh the page
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/APV/APV.php');
						},2000)
						//End Refresh the page
						
						

					}else{
						//Alert when error
						notie.alert(3, res[1], 10);
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
            notie.alert(3, errmsg, 10);
            //End
    	}
    })
    //End Canceled SO
	
	//Closed SO
    $(document.body).on('click','#btnCloseAPV',function(e){
    	
		var err = 0;
    	var errmsg = '';
		var docentry = $('input[name=txtDocEntry]').val();
    	
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


    	//Check if there's a row
    	if(servicetype == 'I'){
	    	if(err == 0){
	    		err = 1;
	    		errmsg = 'No item/s to process!';
				$('.itemcode').each(function(){
		    		err = 0;
		    		
		    		return false;
		    	})

		    }
		}else{
			if(err == 0){
	    		err = 1;
	    		errmsg = 'No item/s to process!';
				$('.remarks').each(function(){
		    		err = 0;
		    		
		    		return false;
		    	})

		    }
		}
	    //Check if there's a row
	 

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
		}
	    //Check if qty fields are blank
		
    	if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/APV/exec-closeAPV.php',
                data: {
                	docentry : docentry
				},
                success: function(html){
					
					res = html.split('*');
					if(res[0] == 'true'){
						//Alert Success
						notie.alert(1, res[1], 10);
						//End

						//Refresh the page
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/APV/APV.php');
						},2000)
						//End Refresh the page
						
						

					}else{
						//Alert when error
						notie.alert(3, res[1], 10);
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
            notie.alert(3, errmsg, 10);
            //End
    	}
    })
    //End Closed SO

	//FUNCTION AREA
	//=============================================================
	//Highlight function
	function highlight(tablename,tablerow){
		
		    $('.selected-whs').map(function(){
		      $(this).removeClass('selected-whs');
		    })

		    $(tablename + ' tbody > tr').css("background-color", "transparent");
		    $(tablerow).css("background-color", "lightgray");
		    $(tablerow).addClass('selected-whs');
	  	
	}
	//End Highlight function
	
		function highlightmultiple(tablename,tablerow){
		if (window.event.ctrlKey) {

			//Check if selected
			if($(tablerow).hasClass('selected-whs')){
				$(tablerow).css("background-color", "transparent");
				$(tablerow).removeClass('selected-whs');
			}else{
				$(tablerow).css("background-color", "lightgray");
	    		$(tablerow).addClass('selected-whs');
			}
			//End
	    	
	  	}else{
		    $('.selected-whs').map(function(){
		      $(this).removeClass('selected-whs');
		    })

		    $(tablename + ' tbody > tr').css("background-color", "transparent");
		    $(tablerow).css("background-color", "lightgray");
		    $(tablerow).addClass('selected-whs');
	  	}
	}


	
	//Bind Item Code
	function checkitemcode(itemcode){
	  var result = '';
	  
	   $.ajax({
	        type: 'POST',
	        url: '../../proc/views/APV/vw_checkitemcode.php',
	        async: false,
	        data: {
				itemcode : itemcode
			},
	        success: function(html){

	          result = html;
	          
	        }

	    });
		
	  return result;

	}
	//End Bind Item Code



	//Bind Whs Code
	function checkwhs(whscode){
	  var result = '';
	  
	   $.ajax({
	        type: 'POST',
	        url: '../../proc/views/APV/vw_checkwhscode.php',
	        async: false,
	        data: {
				whscode : whscode
			},
	        success: function(html){

	          result = html;
	          
	        }

	    });
		
	  return result;

	}
	//End Bind Whs Code


	//Bind Acct Code
	function checkacctcode(acctcode){
	  var result = '';
	  
	   $.ajax({
	        type: 'POST',
	        url: '../../proc/views/APV/vw_checkacctcode.php',
	        async: false,
	        data: {
				acctcode : acctcode
			},
			success: function(html){

	          result = html;
	          
	        }

	    });
		
	  return result;

	}
	//End Bind Acct Code


	//Bind BP Code
	function checkbpcode(bpcode){
	  var result = '';
	  
	   $.ajax({
	        type: 'POST',
	        url: '../../proc/views/APV/vw_checkbpcode.php',
	        async: false,
	        data: {
				bpcode : bpcode
			},
			success: function(html){

	          result = html;
	          
	        }

	    });
		
	  return result;

	}
	//End Bind BP Code




	// Compute Line Total
	function computeLineTotal(qty,price,discount){
	  qty = isNaN(parseFloat(qty.replace(/,/g,'')))? 0: parseFloat(qty.replace(/,/g,''));
	  price = isNaN(parseFloat(price.replace(/,/g,'')))? 0 : parseFloat(price.replace(/,/g,''));
	  discount = isNaN(parseFloat(discount.replace(/,/g,'')))? 0 : parseFloat(discount.replace(/,/g,''));
	  var total = ((qty * price) * (100 - discount)/100);

	  return formatMoney(total);
	}
	//End Compute Line Total



	//Compute Total Amount 
	function computeTotalAmount(cls){
	  var linetotal = 0.00;

	  $('.'+cls).each(function(){
	    if(isNaN(parseFloat($(this).val().replace(/,/g,'')))){
	      linetotal += 0;
	    }else{
	      linetotal += parseFloat($(this).val().replace(/,/g,''));
	    }
	      
	  })

	  return formatMoney(linetotal);
	}
	//End Compute Total Amount



	// Compute Gross Price 
	function computeGrossPrice(price,taxrate,discount){

	  price = isNaN(parseFloat(price.replace(/,/g,'')))? 0 : parseFloat(price.replace(/,/g,''));
	  taxrate = isNaN(parseFloat(taxrate.replace(/,/g,'')))? 0 : parseFloat(taxrate.replace(/,/g,''));
	  discount = isNaN(parseFloat(discount.replace(/,/g,'')))? 0 : parseFloat(discount.replace(/,/g,''));

	  var total = (price * (1 + (taxrate/100))) * ((100 - discount)/100);
	  return formatMoney2(total);
	}
	// End Compute Gross Price 


	// Compute Gross Total *
	function computeGrossTotal(grossprice,qty){

	  grossprice = isNaN(parseFloat(grossprice.replace(/,/g,'')))? 0 : parseFloat(grossprice.replace(/,/g,''));
	  qty = isNaN(parseFloat(qty.replace(/,/g,'')))? 0 : parseFloat(qty.replace(/,/g,''));
	 
	  var total = (grossprice * qty);
	  return formatMoney(total);
	}
	// End Compute Gross Total


	// Compute Discount Amount
	function computeDiscountAmt(totalbefdisc,discount){
	  totalbefdisc = isNaN(parseFloat(totalbefdisc.replace(/,/g,'')))? 0 : parseFloat(totalbefdisc.replace(/,/g,''));
	  discount = isNaN(parseFloat(discount.replace(/,/g,'')))? 0 : parseFloat(discount.replace(/,/g,''));
	  var total = totalbefdisc * (discount/100);
	  return formatMoney(total);
	}
	// End Compute Discount Amount


	// Compute Tax Amount Details 
	function computeTaxAmt(linetotal,taxrate){

	  linetotal = isNaN(parseFloat(linetotal.replace(/,/g,'')))? 0 : parseFloat(linetotal.replace(/,/g,''));
	  taxrate = isNaN(parseFloat(taxrate.replace(/,/g,'')))? 0 : parseFloat(taxrate.replace(/,/g,''));
	 
	  var total = (linetotal * (taxrate/100));
	  
	  return formatMoney(total);
	}
	// End Compute Tax Amount Details 



	// Compute Tax Amount Footer 
	function computeTaxAmtFooter(totaltax,discount){
	  totaltax = isNaN(parseFloat(totaltax.replace(/,/g,'')))? 0: parseFloat(totaltax.replace(/,/g,''));
	  discount = isNaN(parseFloat(discount.replace(/,/g,'')))? 0 : parseFloat(discount.replace(/,/g,''));
	  var total = totaltax * ((100 - discount)/100);
	  return formatMoney(total);
	}
	// Compute Tax Amount Footer




	// Compute Total Amount Footer 

	function computeTPaymentDue(totalbefdisc,discount,totaltaxamt){
	  totalbefdisc = isNaN(parseFloat(totalbefdisc.replace(/,/g,'')))? 0 : parseFloat(totalbefdisc.replace(/,/g,''));
	  discount = isNaN(parseFloat(discount.replace(/,/g,'')))? 0 : parseFloat(discount.replace(/,/g,''));
	  totaltaxamt = isNaN(parseFloat(totaltaxamt.replace(/,/g,'')))? 0 : parseFloat(totaltaxamt.replace(/,/g,''));

	  var total = (totalbefdisc - discount) + totaltaxamt;
	  return formatMoney(total);
	}
	// End Compute Total Amount Footer 

	

	


	//Format Number
	function formatMoney(n) {
	    return n.toLocaleString().split(".")[0] + "."
	        + n.toFixed(2).split(".")[1];
	}
	//end Format Number

	//Format Number
	function formatMoney2(n) {
	    return n.toLocaleString().split(".")[0] + "."
	        + n.toFixed(4).split(".")[1];
	}
	
	function formatMoney8(n) 
	{
	    return n.toLocaleString().split(".")[0] + "."
	        + n.toFixed(8).split(".")[1];
	}
	//end Format Number


	//Disable buttons
	function disablebuttons(param){
		//Disable Buttons
		$('#btnAddRow').prop('disabled',param);
		$('#btnDelRow').prop('disabled',param);
		$('#btnSave').prop('disabled',param);
		$('#btnFreeText').prop('disabled',param);
		//End Disable Buttons
		
	}
	//End Disable buttons

	function populatedetPOMulti(docentry,callback) {


        $('#tblDetails tbody').load('../../proc/views/APV/vw_podetailsdata-multi.php?docentry=' + encodeURI(docentry), function (result) {


            callback();


        })

    }
	
	function populatewithholding(docentry,callback)
	{
		$('#tblWTAXCodeList tbody').load('../../proc/views/APV/vw_withholdingtaxtablelist.php?docentry=' + docentry,function(result)
		{
			callback();
		});
	}
	
	//Add Rows for Population
	function populatedet(docentry,callback){

			
		$('#tblDetails tbody').load('../../proc/views/APV/vw_documentdetailsdata.php?docentry=' + docentry,function(result){
			
			//Set Row Number
			//$('#tblDetails tbody tr:last').find('td.rowno').html('<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> ' + rowno);
			//End Set Row Number

			//Set Warehouse Details
			//$('#tblDetails tbody tr:last').find('input.warehouse').val($('input[name=txtWarehouse]').attr('aria-whscode'));
			//End Set Warehouse Details

			//Set Header Fixed
			//$("#tblDetails").tableHeadFixer({"left" : 4});
			//End Set Header Fixed
			
			//rowno = ($('#tblDetails tbody tr:last').find('td.rowno').text() == '')? 1 : parseFloat($('#tblDetails tbody tr:last').find('td.rowno').text()) + 1;
			callback();
			
			
		})
	
	}
	//End Add row for population



	//Populate PO with PR Data Details
	function populatedetPO(docentry,callback){

			
		$('#tblDetails tbody').load('../../proc/views/APV/vw_podetailsdata.php?docentry=' + docentry,function(result){
			
			
			callback();
			
			
		})
	
	}
	//End Populate PO with PR Data Details


	// Compute Unit Price 
	function computeUnitPrice(price,taxrate){

	  price = isNaN(parseFloat(price.replace(/,/g,'')))? 0 : parseFloat(price.replace(/,/g,''));
	  taxrate = isNaN(parseFloat(taxrate.replace(/,/g,'')))? 0 : parseFloat(taxrate.replace(/,/g,''));
	  

	  var total = (price / (1 + (taxrate/100)));
	  return formatMoney2(total);
	}
	// End Compute Unit Price 


	function computeGPAutoTrigger(){
		//if($('.selected-det').find('input.price').val() != ''){
			if(servicetype == 'I'){
				var price = $('.selected-det').find('input.price').val();
				var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
				var discount = $('.selected-det').find('input.discount').val();
				var qty = $('.selected-det').find('input.qty').val();

				//Gross Price
				$('.selected-det').find('input.grossprice').val(computeGrossPrice(price,taxrate,discount));
				//End Gross Price

				//Compute Tax Amount
				$('.selected-det').find('input.taxamount').trigger('keyup')
				//End Compute Tax Amount

				var grossprice = $('.selected-det').find('input.grossprice').val();

				//Gross Total
				$('.selected-det').find('input.grosstotal').val(computeGrossTotal(grossprice,qty));
				//End Gross Total
			}else{

				var price = $('.selected-det').find('input.price').val();
				var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
				var discount = '0';


				//Gross Price
				$('.selected-det').find('input.grossprice').val(computeGrossPrice(price,taxrate,discount));
				//End Gross Price

				//Compute Tax Amount
				$('.selected-det').find('input.taxamount').trigger('keyup')
				//End Compute Tax Amount


			} // End Service Type
		//}// ENd checking
	}// End ComputeGPAutoTrigger


	function computeUPAutoTrigger(){
		
			if(servicetype == 'I'){
				var grossprice = $('.selected-det').find('input.grossprice').val();
				//var price = $('.selected-det').find('input.price').val();
				var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
				var discount = $('.selected-det').find('input.discount').val();
				var qty = $('.selected-det').find('input.qty').val();

				//Gross Price
				if(parseFloat($('.selected-det').find('input.price').val()) > 0){

				}else{
					$('.selected-det').find('input.price').val(computeUnitPrice(grossprice,taxrate));
				}
				//End Gross Price

				//Compute Line Total
				$('.selected-det').find('input.linetotal').trigger('keyup');
				//End Compute Line Total

				//Compute Tax Amount
				$('.selected-det').find('input.taxamount').trigger('keyup')
				//End Compute Tax Amount

				

				//Gross Total
				$('.selected-det').find('input.grosstotal').val(computeGrossTotal(grossprice,qty));
				//End Gross Total
			}else{
				var grossprice = $('.selected-det').find('input.grossprice').val();
				//var price = $('.selected-det').find('input.price').val();
				var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
				var discount = '0';


				//Gross Price
				if(parseFloat($('.selected-det').find('input.price').val()) > 0){

				}else{
					$('.selected-det').find('input.price').val(computeUnitPrice(grossprice,taxrate));
				}
				//End Gross Price

				//Compute Tax Amount
				$('.selected-det').find('input.taxamount').trigger('keyup')
				//End Compute Tax Amount


			} // End Service Type
		
	}// End ComputeGPAutoTrigger




	// CUSTOMIZED RIGHT CLICK ON WINDOW
	// Trigger action when the contexmenu is about to be shown
	$(document).bind("contextmenu", function (event) {
	    
	    // Avoid the real one
	    //Uncomment if done
	    //event.preventDefault();
	    
	    // Show contextmenu
	    $(".custom-menu").finish().toggle(100).
	    
	    // In the right position (the mouse)
	    css({
	        top: event.pageY + "px",
	        left: event.pageX + "px"
	    });
	});


	// If the document is clicked somewhere
	$(document).bind("mousedown", function (e) {
	    
	    // If the clicked element is not the menu
	    if (!$(e.target).parents(".custom-menu").length > 0) {
	        
	        // Hide it
	        $(".custom-menu").hide(100);
	    }
	});


	// If the menu element is clicked
	$(".custom-menu li").click(function(){
	    
	    // This is the triggered action name
	    switch($(this).attr("data-action")) {
	        
	        // A case for each action. Your actions here
	        case "first": alert("first"); break;
	        case "second": alert("second"); break;
	        case "third": alert("third"); break;
	    }
	  
	    // Hide it AFTER the action was triggered
	    $(".custom-menu").hide(100);
	  });
	// END CUSTOMIZED RIGHT CLICK ON WINDOW
	

	//END FUNCTION AREA
	//=============================================================


	/*
	$(document.body).on('click','#btnTest',function(){
		
		alert(computeRoundedMA(3210.67));


	})

	function computeRoundedMA(num){
		num = String(num).replace(/,/g,'');
		var index = num.indexOf('.');
		var lastinteger = parseFloat(num.substring(index -1,index));
		
		if (lastinteger <= 4) {
		   num = num.substring(0,index-1) + '5';
		   
		}else if (lastinteger > 4) {
		   num = Math.round((parseFloat(num.substring(0,index)) / 10)) + '0';
		   
		}
		return formatMoney(parseFloat(num));
	}
	*/

	//=======================================================================================================
	//End javascript Code
	//=======================================================================================================
	//Hide Intialize Modal after loading all the javascript
	
	var readyStateCheckInterval = setInterval(function() {
	    if (document.readyState === "complete") {
	        clearInterval(readyStateCheckInterval);
	        $('#modal-load-init').modal('hide');

	        $('#modal-load-init').on('hidden.bs.modal',function(){
	        	
	        })

	        //Trigger change on cmbServiceType
	        $('select[name=cmbServiceType]').trigger('change');
	        //End Trigger change on cmbServiceType


	        
	    }
	}, 10);
	
	
})//end document.ready