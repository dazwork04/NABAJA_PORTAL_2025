$(window).load(function () {

    //Trigger Base Entry
    $('input[name=txtBaseEntry]').trigger('keyup');
    //End Trigger BaseEntry
	
	$('#window-title').text('Goods Issue');

})//end window.load

function reload() {
    location.reload();
}

$(document).ready(function () {

    //Intialize Modal
    $('#modal-load-init').modal('show');

	$('#window-title').text('Goods Issue');


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
	   format: 'MM/DD/YYYY'
	})

	$('#txtDocDate').datetimepicker({
	  format: 'MM/DD/YYYY',
		defaultDate: new Date()
    })
	
    //End Initialize Datetimepicker


    //Initialize Title
    $('#mod-title').text('Goods Issue');
    //End Initialize Title


    //Load Gi Series 
    $('#btnSeries').html('Loading...')
    $('#SeriesList').load('../../proc/views/GI/vw_series.php?objtype=60', function () {
        $('#btnSeries').html($('.series:first').attr('val-seriesname'));
        $('#btnSeries').attr('series-val', $('.series:first').attr('val-series'));
        $('#btnSeries').attr('bplid-val', $('.series:first').attr('val-bplid'));
        $('input[name=txtDocNo]').val($('.series:first').attr('val-nextnum'));
    });
    //End Load Gi Series

	$('select[name=txtPriceList]').html('<option>Loading...</option>');
	$('select[name=txtPriceList]').load('../../proc/views/GI/vw_pricelist.php');
	
    $(document.body).on('click', '.series', function () {
        $('#btnSeries').html($(this).attr('val-seriesname'));
        $('#btnSeries').attr('series-val', $(this).attr('val-series'));
        $('#btnSeries').attr('bplid-val', $(this).attr('val-bplid'));
        $('input[name=txtDocNo]').val($(this).attr('val-nextnum'));
    })

    $(document.body).on('change', 'select[name=cmbServiceType]', function () {


        servicetype = $(this).val();
        if (servicetype == 'I') {
            //==============
            //* Item
            //==============
            $('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /></p>');
            $('#ModDetails').load('../../forms/GI/GI-details.php?servicetype=I', function () {
                //Clear value Total Before Discount
                $('input[name=TotBefDisc]').val('');
                //End Clear value Total Before Discount
                cback = 0;
            });
            //==============
            //* End Item
            //==============
        } else if (servicetype == 'S') {
            //==============
            //* Service
            //==============
            $('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
            $('#ModDetails').load('../../forms/GI/GI-details.php?servicetype=S', function () {
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
    $(document.body).on('click', '#btnAddRow', function () {
        $(this).prop('disabled', true);
        //generate row number
        var rowno = 0;
        rowno = ($('#tblDetails tbody tr:last').find('td.rowno').text() == '') ? 1 : parseFloat($('#tblDetails tbody tr:last').find('td.rowno').text()) + 1;
        //End row number
        if (servicetype == 'I') {
            //Item Type
            $(this).load('../../forms/GI/GI-details-row.php?servicetype=I', function (result) {
                $('#tblDetails tbody').append(result);
                //Set Row Number
                $('#tblDetails tbody tr:last').find('td.rowno').html('<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> ' + rowno);
                //End Set Row Number

                //Set Warehouse Details
                $('#tblDetails tbody tr:last').find('input.warehouse').val('W01-000');
				$('#tblDetails tbody tr:last').find('input.itemcode').focus();
                //End Set Warehouse Details

                //Set Header Fixed
                //$("#tblDetails").tableHeadFixer({"left" : 4});
                //End Set Header Fixed
                $(this).empty();
                $(this).prop('disabled', false);
            })
            //End Item Type

        } else {
            //Service Type
            $(this).load('../../forms/GI/GI-details-row.php?servicetype=S', function (result) {
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

    })
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
            $(this).load('../../forms/GI/GI-details-row.php?servicetype=I&freetext=1', function (result) {

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
        $('#ItemCont').load('../../proc/views/GI/vw_itemlist.php', function () {
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
                                url: '../../proc/views/GI/vw_itemlist-load.php',
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
        var accountcode = checkitemcode(itemcode).split(';')[9];
        var txtPostingDate = $('input[name=txtPostingDate]').val();
		
		var form_data =
		{
			itemcode : itemcode,
			txtPostingDate : txtPostingDate
		};
			
		$.ajax({
		type: "POST",
		url: "../../proc/views/gi/vw_poglaccount.php",
		data: form_data,
		success: function(html)
			{
				result = html;
				if(result != '')
				{
					$('.selected-det').find('input.acctcode').val(result);
				}
			}
		});

        $('#ItemModal').modal('hide');


        //Details Item
        $('.selected-det').find('input.itemcode').val(itemcode);
        $('.selected-det').find('input.itemname').val(itemname);
        $('.selected-det').find('input.uom').val(invntryuom);
        $('.selected-det').find('input.qty').focus();
        //$('.selected-det').find('input.acctcode').val(accountcode);
        //$('.selected-det').find('input.acctcode').attr('aria-acctcode', accountcode);
        //End Details Item
    });
    //End Select Item Table Row Click

    //Search Item
    $(document.body).on('keyup', 'input[name=ItemSearch]', function () {
        var searchVal = $(this).val().toLowerCase();
		
        $('#ItemCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#ItemCont table tbody').load('../../proc/views/GI/vw_itemlist-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search Item


    //Item Code Bind
    $(document.body).on('blur', '#tblDetails .itemcode', function () {
        var item = checkitemcode($(this).val()).split(';');
        //item[0] - ItemCode
        //item[1] - ItemName
        //item[2] - OnHand
        //item[3] - InvntryUom
        //item[4] - BuyUnitMsr
        //item[5] - ManBtchNum
        //item[6] - NumInBuy

        //Details Item
        if ($.trim(item[0]) == '') 
		{
            $('.selected-det').find('input.itemcode').val('');
            $('.selected-det').find('input.itemname').val('');
            //$('.selected-det').find('input.itemcode').focus();
            $('.selected-det').find('input.acctcode').val('');
        } 
		else 
		{
            $('.selected-det').find('input.itemname').val(item[1]);
            $('.selected-det').find('input.uom').val(item[3]);
            //$('.selected-det').find('input.acctcode').val(item[9]);
            //$('.selected-det').find('input.acctcode').attr('aria-acctcode', item[9]);
			var itemcode = item[0];
	
			var form_data =
			{
				itemcode : itemcode
			};
				
			$.ajax({
			type: "POST",
			url: "../../proc/views/gi/vw_poglaccount.php",
			data: form_data,
			success: function(html)
				{
					result = html;
					if(result != '')
					{
						$('.selected-det').find('input.acctcode').val(result);
					}
				}
			});
        }

        //End Details Item

    })
    //End Item Code Bind



    //Load Warehouse
    $('#WhsModal').on('shown.bs.modal', function () {
		
        $('#WhsCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#WhsCont').load('../../proc/views/GI/vw_whslist.php', function () {

        });
    })
    //End Load Warehouse

    //Clear Warehouse
    $('#WhsModal').on('hide.bs.modal', function () {
        $('#WhsCont').empty();

    })
    //End Clear warehouse

    //Add Keypress on Whs MOdal
    $('#WhsModal').keydown(function (e) {
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
                if ($('#tblWhs tbody').find('tr.selected-whs').index() >= 0) {
                    $('tr.selected-whs').next().trigger('click');

                    //$('#WhsCont > .table-responsive').scrollTop(10);
                } else {
                    $('#tblWhs tbody > tr:first').trigger('click');
                }
                //End
                break;



            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    });

    //End


    //Highlight Warehouse Table Row Click
    $(document.body).on('click', '#tblWhs tbody > tr', function (e) {


        highlight('#tblWhs', this);

    })
    //End Highlight Warehouse Table Row Click




    //Select Warehouse Table Row Click
    $(document.body).on('dblclick', '#tblWhs tbody > tr', function () {

        var warehouse = $(this).children('td.item-2').text();
        var warehousecode = $(this).children('td.item-1').text();

        $('#WhsModal').modal('hide');

        //$('#WhsModal').on('hidden.bs.modal',function(){

        if (activewhs == undefined) {
            //Details Warehouse
            $('.selected-det').find('input.warehouse').val(warehousecode);

            //End Details Warehouse
        } else {
            //Header Warehouse

            $('input[name=txtWarehouse]').val(warehouse);
            $('input[name=txtWarehouse]').attr('aria-whscode', warehousecode);
            //End Header Warehouse

            if (confirm('Do you want to update warehouse details?')) {
                $('input.warehouse').val(warehousecode);
            }
        }


        //})
    })
    //End Select Warehouse Table Row Click


    //Check active whs
    $(document.body).on('click', '.input-group-addon', function () {
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


    //Search Whs
    $(document.body).on('keyup', 'input[name=WhsSearch]', function () {
        var searchVal = $(this).val().toLowerCase();
		
        $('#WhsCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#WhsCont table tbody').load('../../proc/views/GI/vw_whslist-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search Whs

    //Load Barcode lookup
    $('#BarcodeModal').on('shown.bs.modal', function () {
        var detitemcode = $('.selected-det').find('input.itemcode').val();
        $('#BarcodeCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#BarcodeCont').load('../../proc/views/PR/vw_barcodelist.php', 'itemcode=' + detitemcode, function () {
            //Add Scroll Function 
            $('#BarcodeCont .table-responsive').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#BarcodeCont table tbody > tr:last').children('td').eq(0).text();
                        var barcode = $('#BarcodeCont table tbody > tr:last').children('td').eq(1).text();
                        var ctr = 0;

                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');


                            $.ajax({
                                type: 'GET',
                                url: '../../proc/views/PR/vw_barcodelist-load.php',
                                data: 'itemcode=' + itemcode + '&barcode=' + barcode,
                                success: function (html) {

                                    $('#BarcodeCont table tbody').append(html);
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

        $('input[name=BarcodeSearch]').focus();

    });


//Clear Barcode
    $('#BarcodeModal').on('hide.bs.modal', function () {
        $('#BarcodeCont').empty();

    });
    //End Clear Barcode


    //Add Keypress on Barcode MOdal
    $('#BarcodeModal').keydown(function (e) {
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

    });
    //End Highlight Item Table Row Click



    //Select Item Table Row Click
    $(document.body).on('dblclick', '#tblBarcode tbody > tr', function () {

        var barcode = $(this).children('td.item-2').text();


        $('#BarcodeModal').modal('hide');


        //Details Item
        $('.selected-det').find('input.barcode').val(barcode);
        $('.selected-det').find('input.barcode').focus();
        //End Details Item



    });
    //End Select Item Table Row Click

    //Search Barcode
    $(document.body).on('keyup', 'input[name=BarcodeSearch]', function () {
        var searchVal = $(this).val().toLowerCase();
        var detitemcode = $('.selected-det').find('input.itemcode').val();
        $('#BarcodeCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#BarcodeCont table tbody').load('../../proc/views/PR/vw_barcodelist-load.php', "srchval=" + encodeURI(searchVal) + "&itemcode=" + encodeURIComponent(detitemcode));
    });


    //Compute Line Total
    $(document.body).on('keyup', '.qty', function () {
        $('.selected-det').find('input.linetotal').trigger('keyup');

        //GrossPrice
        computeGPAutoTrigger();
        //End Gross Price
    })
    //End Compute Line Total


    //Compute Line Total
    $(document.body).on('blur', '.price', function () {


        if (servicetype == 'I') {
            $('.selected-det').find('input.linetotal').trigger('keyup');
            //GrossPrice
            computeGPAutoTrigger();
            //End Gross Price
        } else {
            //GrossPrice
            computeGPAutoTrigger();
            //End Gross Price

            //Compute LineTotal
            $('input[name=TotBefDisc]').trigger('keyup');
            //End Compute Line Total
        }

    })

    //End Compute Line Total


    //Compute Line Total
    $(document.body).on('keyup', '.discount', function () {
        $('.selected-det').find('input.linetotal').trigger('keyup');

        //GrossPrice
        computeGPAutoTrigger();
        //End Gross Price
    })

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

    //End Compute Line Total



    //Compute Footer Line Total
    $(document.body).on('keyup', 'input[name=TotBefDisc]', function () {

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

    //End Compute Footer Line Total






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

    //End Compute GrossPrice


    //Load Accounts
    $('#AcctModal').on('shown.bs.modal', function () {
		
        $('#AcctCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#AcctCont').load('../../proc/views/GI/vw_acctlist.php', function () {
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
		                        url: '../../proc/views/GI/vw_acctlist-load.php',
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
        $('.selected-det').find('input.acctcode').val(acctcode);
        $('.selected-det').find('input.acctname').val(acctname);
        $('.selected-det').find('input.acctcode').focus();

        //Add Account Code
        $('.selected-det').find('input.acctcode').attr('aria-acctcode', acct);
        //End Add Account Code
        //End Details Item



    })
    //End Select Acct Table Row Click



    //Search Acct
    $(document.body).on('keyup', 'input[name=AcctSearch]', function () {
        var searchVal = $(this).val().toLowerCase();
		
        $('#AcctCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#AcctCont table tbody').load('../../proc/views/GI/vw_acctlist-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search Acct



    //Acct Code Bind
    $(document.body).on('blur', '#tblDetails .acctcode', function () {
        var acct = checkacctcode($(this).val()).split(';');
        //acct[0] - AcctName
        //acct[1] - FormatCode
        //acct[2] - AcctCode


        //Details Item
        if ($.trim(acct[0]) == '') {
            $('.selected-det').find('input.acctcode').val('');
            $('.selected-det').find('input.acctname').val('');
            //$('.selected-det').find('input.itemcode').focus();
        } else {
            $('.selected-det').find('input.acctcode').val(acct[1]);
            $('.selected-det').find('input.acctname').val(acct[0]);
            //Add Account Code
            $('.selected-det').find('input.acctcode').attr('aria-acctcode', acct[2]);
            //End Add Account Code
        }

        //End Details Item

    })
    //End Acct Code Bind



    //Load Inventory Data
    $('#InvDataModal').on('shown.bs.modal', function () {
        var itemcode = $('.selected-det').find('input.itemcode').val();
		
        if (itemcode != '') {
            $('#InvDataCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
            $('#InvDataCont').load('../../proc/views/GI/vw_invdatalist.php?itemcode=' + itemcode, function () {

            });
        } else {
            $('#InvDataCont').html('<h2 class="text-center">NO RESULT!</h2>');
        }
    })
    //End Load Inventory Data



    //Clear Inventory Data
    $('#InvDataModal').on('hide.bs.modal', function () {
        $('#InvDataCont').empty();

    })
    //End Clear Inventory Data




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
        $('#DocumentCont').load('../../proc/views/GI/vw_doclist.php', function () {
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
		                        url: '../../proc/views/GI/vw_doclist-load.php',
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
	//Load Documents GR
    $('#GRModal').on('shown.bs.modal', function () {
		
		$('#DocumentContGR').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#DocumentContGR').load('../../proc/views/GI/vw_doclistGR.php', function () {
            //Add Scroll Function 
            $('#DocumentContGR .table-responsive').bind('scroll', function(){
		        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
		              if($(this).scrollTop() > 0){
		                var itemcode = $('#DocumentContGR table tbody > tr:last').children('td').eq(0).text();
		                var ctr = 0;

		                $('#itm-loader').each(function () {
		                  ctr += 1;
		                });
		                if(ctr == 0){
		                  $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		                	
		                  
		                  $.ajax({
		                        type: 'POST',
		                        url: '../../proc/views/GI/vw_doclist-loadGR.php',
		                        data: {
									itemcode : itemcode
								},
		                        success: function(html){

		                          $('#DocumentContGR table tbody').append(html);                
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
    //End Load Documents GR
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
        $('#DocumentCont table tbody').load('../../proc/views/GI/vw_doclist-load.php?srchval=' + encodeURI(searchVal));
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
	
	//Select Document Table Row Click
    $(document.body).on('dblclick', '#tblDocumentGR tbody > tr', function () {

        var docentry = $(this).children('td.item-1').text();

        $('#GRModal').modal('hide');

        $('input[name=txtBaseEntry]').val(docentry);
        $('input[name=txtBaseEntry]').trigger('keyup');



    })
    //End Select Document Table Row Click

    //Populate Data
    $(document.body).on('keyup', 'input[name=txtDocEntry]', function () {
        var docentry = $(this).val();
		
        //Get PR data using JSON
        $.getJSON('../../proc/views/GI/vw_getdocumentdata.php?docentry=' + docentry, function (data) {
            /* data will hold the php array as a javascript object */

            $('#modal-load-init').modal('show');
            //$('#tblDetails tbody').empty();	
            $.each(data, function (key, val) {

                //Populate Header
                if (val.DocStatus == 'C') {

                    $('#btnSeries').html(val.SeriesName);
                    $('#btnSeries').attr('series-val', val.Series);
                    $('#btnSeries').attr('bplid-val', val.BPLId);
                    $('#btnSeries').prop('disabled', true);
                    $('#btnSeriesDD').prop('disabled', true);
					
                    $('input[name=txtDocNo]').val(val.DocNum).prop('disabled', true);
                    $('select[name=txtPriceList]').html('<option>Loading...</option>');
                    $('select[name=txtPriceList]').load('../../proc/views/GI/vw_pricelist.php', function () {
                        $('select[name=txtPriceList] option[value="' + val.GroupNum + '"]').prop("selected", "selected");
                    });
                    $('select[name=txtPriceList]').prop('disabled', true);
                    $('select[name=txtIssueType]').html('<option>Loading...</option>');
                    
					$('select[name=txtRGoodsIssue]').val(val.rgoods).prop('disabled', true);
					$('input[name=txtSORef]').val(val.soref).prop('disabled', true);
                    
					$('input[name=txtPostingDate]').val(val.TaxDate).prop('disabled', true);
                    $('input[name=txtDocDate]').val(val.DocDate).prop('disabled', true);
                    $('input[name=txtRef2]').val(val.Ref2).prop('disabled', true);
                    $('textarea[name=txtRemarksF]').val(val.Comments).prop('disabled', true);
                    $('input[name=txtJournalRemarks]').val(val.JrnlMemo).prop('disabled', true);
                  
                    $('input[name=txtDocStatus]').val('Closed');
					
					$('#btnPrint').prop('disabled', false);	

                    disablebuttons(true);
                } else {
                    $('#btnSeries').html(val.SeriesName);
                    $('#btnSeries').attr('series-val', val.Series);
                    $('#btnSeries').attr('bplid-val', val.BPLId);
                    $('#btnSeries').prop('disabled', true);
                    $('#btnSeriesDD').prop('disabled', true);
					
                    $('input[name=txtDocNo]').val(val.DocNum).prop('disabled', true);
                    $('select[name=txtPriceList]').html('<option>Loading...</option>');
                    $('select[name=txtPriceList]').load('../../proc/views/GI/vw_pricelist.php', function () {
                        $('select[name=txtPriceList] option[value="' + val.GroupNum + '"]').prop("selected", "selected");
                    });
                    $('select[name=txtPriceList]').prop('disabled', true);
                    
					$('select[name=txtRGoodsIssue]').val(val.rgoods).prop('disabled', true);
					$('input[name=txtSORef]').val(val.soref).prop('disabled', true);

                    $('input[name=txtPostingDate]').val(val.TaxDate).prop('disabled', true);
                    $('input[name=txtDocDate]').val(val.DocDate).prop('disabled', true);
                    $('input[name=txtRef2]').val(val.Ref2).prop('disabled', true);
                    $('textarea[name=txtRemarksF]').val(val.Comments).prop('disabled', true);
                    $('input[name=txtJournalRemarks]').val(val.JrnlMemo).prop('disabled', true);

                    $('input[name=txtDocStatus]').val('Closed');
					
					$('#btnPrint').prop('disabled', false);	

                    disablebuttons(true);
                }

                //End Populate Header

            })

            //Populate Details
            setTimeout(function () {
                populatedet(docentry,function () {
                    //$('input[name=TotBefDisc]').trigger('keyup')
                    $('#modal-load-init').modal('hide');


                });
            }, 500)

            //End Populate Details








        });
        //End Get PR data using JSON
    })
    //End Populate Data

	//End Populate Data
    //Load Business Partner
    $('#BPModal').on('shown.bs.modal', function () {

        $('#BPCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#BPCont').load('../../proc/views/GI/vw_bplist.php?CardType=S', function () {
            //Add Scroll Function 
            $('#BPCont .table-responsive').bind('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
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
                                url: '../../proc/views/GI/vw_bplist-load.php',
                                data: 'itemcode=' + itemcode,
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
    })
    //End Load Business Partner

    //Clear Business Partner Data
    $('#BPModal').on('hide.bs.modal', function () {
        $('#BPCont').empty();

    })
    //End Clear Business Partner Data


    //Add Keypress on Business Partner MOdal
    $('#BPModal').keydown(function (e) {
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

    //End Add Keypress on Business Partner Modal


    //Highlight Item Table Row Click
    $(document.body).on('click', '#tblBP tbody > tr', function (e) {


        highlight('#tblBP', this);

    })
    //End Highlight Item Table Row Click



    //Select Acct Table Row Click
    $(document.body).on('dblclick', '#tblBP tbody > tr', function () {

        var BPCode = $(this).children('td.item-1').text();
        var BPName = $(this).children('td.item-2').text();
        var Balance = $(this).children('td.item-3').text();
        var ContactPerson = $(this).children('td.item-4').text();
        $('input[name=txtVendor]').val(BPCode);
        $('input[name=txtName]').val(BPName);
        $('input[name=txtContactPerson]').val(ContactPerson);
        $('#BPModal').modal('hide');





    })
    //End Select Acct Table Row Click

    //Search BP
    $(document.body).on('keyup', 'input[name=BPSearch]', function () {

        var searchVal = $(this).val().toLowerCase();
        $('#BPCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#BPCont table tbody').load('../../proc/views/GI/vw_bplist-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search BP


    //BP Code Bind
    $(document.body).on('blur', 'input[name=txtVendor]', function () {
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


    //Trigger Discount Amount Footer to compute 
    $(document.body).on('keyup', 'input[name=txtDiscPercentF]', function () {

        $('input[name=txtDiscAmtF]').trigger('keyup');
    })
    //End Trigger Discount Amount Footer to compute



    //Compute Discount Amount Footer
    $(document.body).on('keyup', 'input[name=txtDiscAmtF]', function () {

        var totalbefdisc = $('input[name=TotBefDisc]').val();
        var discount = $('input[name=txtDiscPercentF]').val();

        $('input[name=txtDiscAmtF]').val(computeDiscountAmt(totalbefdisc, discount));
        $('input[name=txtTaxF]').trigger('keyup');
        $('input[name=txtTotalPaymentDue]').trigger('keyup');

    })

    //End Discount Amount Footer




    //Compute Tax Amount Details
    $(document.body).on('keyup', '.taxamount', function () {
        if (servicetype == 'I') {
            var linetotal = $('.selected-det').find('input.linetotal').val();
            var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
            $('.selected-det').find('input.taxamount').val(computeTaxAmt(linetotal, taxrate));

            $('input[name=txtTaxF]').trigger('keyup');
        } else {
            var linetotal = $('.selected-det').find('input.price').val();
            var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
            $('.selected-det').find('input.taxamount').val(computeTaxAmt(linetotal, taxrate));

            $('input[name=txtTaxF]').trigger('keyup');
        }


    })
    //End Compute Tax Amount Details


    //Compute Tax Amount Footer
    $(document.body).on('keyup', 'input[name=txtTaxF]', function () {
        var totaltax = computeTotalAmount('taxamount');
        var discount = $('input[name=txtDiscPercentF]').val();

        $('input[name=txtTaxF]').val(computeTaxAmtFooter(totaltax, discount));

        $('input[name=txtTotalPaymentDue]').trigger('keyup');

    })

    //End Tax Amount Footer

    //COmpute Total Payment Due
    $(document.body).on('keyup', 'input[name=txtTotalPaymentDue]', function () {
        var totalbefdisc = $('input[name=TotBefDisc]').val();
        var discount = $('input[name=txtDiscAmtF]').val();
        var totaltaxamt = $('input[name=txtTaxF]').val();
        $('input[name=txtTotalPaymentDue]').val(computeTPaymentDue(totalbefdisc, discount, totaltaxamt));


    })
    //End COmpute Total Payment Due


    //Load Base Document
    $(document.body).on('keyup', 'input[name=txtBaseEntry2]', function () {
        var basentry = $(this).val();

        if (basentry != '') {
            //Show Base Document
            //Get PR data using JSON
            $.getJSON('../../proc/views/GI/vw_getpodata.php?docentry=' + basentry, function (data) {
                /* data will hold the php array as a javascript object */


                //$('#modal-load-init').modal('show');
                $.each(data, function (key, val) {
                    //Populate Header
                    if (val.DocStatus == 'C') {
                        $('input[name=txtVendor]').val(val.CardCode).prop('disabled', true);
                        $('input[name=txtName]').val(val.CardName).prop('disabled', true);
                        $('input[name=txtContactPerson]').val(val.Name).prop('disabled', true);
                        $('input[name=txtRefNo]').val(val.NumAtCard).prop('disabled', true);
                        $('select[name=txtPriceList]').val(val.GroupNum).prop('disabled', true);

                        $('input[name=txtPostingDate]').val(val.TaxDate).prop('disabled', true);
                        $('input[name=txtDeliveryDate]').val(val.DocDueDate).prop('disabled', true);
                        $('input[name=txtDocDate]').val(val.DocDate).prop('disabled', true);
                        $('input[name=txtDocStatus]').val('Closed');
                        $('select[name=cmbServiceType]').val(val.DocType).trigger('change').prop('disabled', true);

                        $('textarea[name=txtRemarksF]').val(val.Comments).prop('disabled', true);
                        setTimeout(function () {
                            $('input[name=TotBefDisc]').val(val.TotBefDisc);
                            $('input[name=txtDiscPercentF]').val(val.DiscPrcnt).prop('disabled', true);
                            $('input[name=txtDiscAmtF]').val(val.DiscSum);
                            $('input[name=txtTaxF]').val(val.VatSum);
                            $('input[name=txtTotalPaymentDue]').val(val.DocTotal);
                        }, 1000)





                        disablebuttons(true);
                    } else {




                        $('input[name=txtVendor]').val(val.CardCode).prop('disabled', false);
                        $('input[name=txtName]').val(val.CardName).prop('disabled', false);
                        $('input[name=txtContactPerson]').val(val.Name).prop('disabled', false);
                        $('input[name=txtRefNo]').val(val.NumAtCard).prop('disabled', false);

                        $('select[name=txtPriceList]').val(val.GroupNum).prop('disabled', false);

                        $('input[name=txtPostingDate]').val(val.TaxDate).prop('disabled', false);
                        $('input[name=txtDeliveryDate]').val(val.DocDueDate).prop('disabled', false);
                        $('input[name=txtDocDate]').val(val.DocDate).prop('disabled', false);
                        $('input[name=txtDocStatus]').val('Open');
                        $('select[name=cmbServiceType]').val(val.DocType).trigger('change').prop('disabled', false);

                        $('textarea[name=txtRemarksF]').val(val.Comments).prop('disabled', false);
                        setTimeout(function () {
                            $('input[name=TotBefDisc]').val(val.TotBefDisc);
                            $('input[name=txtDiscPercentF]').val(val.DiscPrcnt).prop('disabled', false);
                            $('input[name=txtDiscAmtF]').val(val.DiscSum);
                            $('input[name=txtTaxF]').val(val.VatSum);
                            $('input[name=txtTotalPaymentDue]').val(val.DocTotal);

                        }, 500)


                    }

                    //End Populate Header

                }) // End each

                //Populate Details
                setTimeout(function () {
                    $('#modal-load-init').modal('hide');
                    $('#modal-load-init').modal('show');
                    populatedetPO(basentry, function () {
                        //$('input[name=TotBefDisc]').trigger('keyup')
                        $('#modal-load-init').modal('hide');


                    });
                }, 500)

                //End Populate Details

            }) // End GetJSON

            //End Show Base Document
        } else {

        }

        $('input[name=DocumentSearch]').focus();
    })
    //End Load Base Document



    //Print Document
    $(document.body).on('click', '#btnPrint', function () {
        var docentry = $('input[name=txtDocEntry]').val();
		
        if (docentry != '') {

            window.open("../../report/GI/gi-report.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
        }
    })
    //End Print Document
	
	$('#SerialModal').on('shown.bs.modal',function(){

		var ItemCodeSerial = $('.selected-det').find('input.itemcode').val();
		var lineno = $('.selected-det').find('input.lineno').val();
		var docno = $('#txtDocNo').val();
		
		if(lineno == '')
		{
			$.ajax({
			type: "POST",
			url: "../../proc/views/GI/vw_itemcode-serial.php",
			data:'ItemCodeSerial='+ItemCodeSerial,
			success: function(data)
				{
					$("#SerialCont").html(data);
				}
			});
		
		}
		else
		{
			
			$.ajax({
			type: "POST",
			url: "../../proc/views/GI/vw_itemcode-serial.php",
			data:'ItemCodeSerial='+ItemCodeSerial,
			success: function(data)
				{
					$("#SerialCont").html(data);
				}
			});
			
			
			 var form_data =
				{
					lineno : lineno,
					docno : docno
				};
				
			$.ajax({
			type: "POST",
			url: "../../proc/views/GI/vw_setserial.php",
			data: form_data,
			success: function(data)
				{
					$("#SerialList").html(data);
				}
			});
		}
		
		
		
			
	})
	//SELECT Serial
	
	$(document.body).on('click', '#btnSerialNo', function(){
		var mySerialNo = new Array();
		$('input[name="CheckSerial[]"]:checked').each(function() {
			mySerialNo.push($(this).val());
		});
		var SerialLength = mySerialNo.length;
		
		$('.selected-det').find('input.serialno').val(mySerialNo);
		$('.selected-det').find('input.qty').val(SerialLength);
		
		$('#SerialModal').modal('hide');
	});
	//SELECT Serial
	
	$(document.body).on('click','#btnRRListVIEW',function(e)
	{
		$('#resViewGRPO').empty();
		$('#resViewGRPO').append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		
		var err = 0;
    	var errmsg = '';
		var txtDateFromGRPO = $('input[name=txtDateFromGRPO]').val();
    	var txtDateToGRPO = $('input[name=txtDateToGRPO]').val();
    	var txtRRListFrom = $('input[name=txtRRListFrom]').val();
    	var txtRRListTo = $('input[name=txtRRListTo]').val();
		
		if(err == 0)
		{
			$.ajax({
				type: 'POST',
				url: '../../report/gi/rpt_view_grpo.php',
				data: {
					txtDateFromGRPO : txtDateFromGRPO,
					txtDateToGRPO : txtDateToGRPO,
					txtRRListFrom : txtRRListFrom,
					txtRRListTo : txtRRListTo
				},
				success: function (html) 
				{
					$('#resViewGRPO').html(html);
				}
			});
		}
	});
	
	$('#RRListModal').on('shown.bs.modal',function()
	{
		$('#resViewGRPO').append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		
		var err = 0;
    	var errmsg = '';
		var txtDateFromGRPO = $('input[name=txtDateFromGRPO]').val();
    	var txtDateToGRPO = $('input[name=txtDateToGRPO]').val();
    	var txtRRListFrom = $('input[name=txtRRListFrom]').val();
    	var txtRRListTo = $('input[name=txtRRListTo]').val();
		
		if(err == 0)
		{
			$.ajax({
				type: 'POST',
				url: '../../report/gi/rpt_view_grpo.php',
				data: {
					txtDateFromGRPO : txtDateFromGRPO,
					txtDateToGRPO : txtDateToGRPO,
					txtRRListFrom : txtRRListFrom,
					txtRRListTo : txtRRListTo
				},
				success: function (html) 
				{
					$('#resViewGRPO').html(html);
				}
			});
		}
	});
	
	$('#RRListModal').on('hide.bs.modal',function()
	{
		$('#resViewGRPO').empty();
	});
	
	$(document.body).on('dblclick','#tblViewGRPO tbody > tr',function()
	{
		var docentry = $(this).children('td.item-0').text();
		
		$('#RRListModal').modal('hide');
		
		$('#tblDetails tbody').load('../../proc/views/GI/vw_grpodetailsdata.php?docentry=' + encodeURI(docentry));

		//$('input[name=txtDocEntry]').val(docentry);
		//$('input[name=txtDocEntry]').trigger('keyup');
	});
	
	$(document.body).on('click','#tblViewGRPO tbody > tr',function(e)
	{
		highlight('#tblViewGRPO',this);
	});


	$(document.body).on('click','#btnGIListVIEW',function(e)
	{
		$('#resView').empty();
		$('#resView').append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		
		var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtRefListFrom = $('input[name=txtRefListFrom]').val();
    	var txtRefListTo = $('input[name=txtRefListTo]').val();
		
		if(err == 0)
		{
			$.ajax({
				type: 'POST',
				url: '../../report/gi/rpt_view.php',
				data: {
					txtDateFrom : txtDateFrom,
					txtDateTo : txtDateTo,
					txtRefListFrom : txtRefListFrom,
					txtRefListTo : txtRefListTo
				},
				success: function (html) 
				{
					$('#resView').html(html);
				}
			});
		}
	});
	
	$('#ListModal').on('shown.bs.modal',function()
	{
		$('#resView').append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		
		var err = 0;
    	var errmsg = '';
		var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
    	var txtRefListFrom = $('input[name=txtRefListFrom]').val();
    	var txtRefListTo = $('input[name=txtRefListTo]').val();
		
		if(err == 0)
		{
			$.ajax({
				type: 'POST',
				url: '../../report/gi/rpt_view.php',
				data: {
					txtDateFrom : txtDateFrom,
					txtDateTo : txtDateTo,
					txtRefListFrom : txtRefListFrom,
					txtRefListTo : txtRefListTo
				},
				success: function (html) 
				{
					$('#resView').html(html);
				}
			});
		}
	});
	
	$('#ListModal').on('hide.bs.modal',function()
	{
		$('#resView').empty();
	});
	
	$(document.body).on('dblclick','#tblView tbody > tr',function()
	{
		var docentry = $(this).children('td.item-0').text();
		
		$('#ListModal').modal('hide');

		$('input[name=txtDocEntry]').val(docentry);
		$('input[name=txtDocEntry]').trigger('keyup');
	});
	
	$(document.body).on('click','#tblView tbody > tr',function(e)
	{
		highlight('#tblView',this);
	});
	
	$(document.body).on('click','#btnGIListPDF',function(e)
	{
    	var err = 0;
    	var errmsg = '';
    	var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
		var txtRefListFrom = $('input[name=txtRefListFrom]').val();
    	var txtRefListTo = $('input[name=txtRefListTo]').val();
    	
			$('.polistrequired').each(function()
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
			window.open("../../report/gi/gilist-report.php?txtRefListFrom=" + encodeURI(txtRefListFrom) + "&txtRefListTo=" + encodeURI(txtRefListTo) + "&txtDateFrom=" + encodeURI(txtDateFrom) + "&txtDateTo=" + encodeURI(txtDateTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });
	
	$(document.body).on('click','#btnGIListEXCEL',function(e)
	{
    	var err = 0;
    	var errmsg = '';
    	var txtDateFrom = $('input[name=txtDateFrom]').val();
    	var txtDateTo = $('input[name=txtDateTo]').val();
		var txtRefListFrom = $('input[name=txtRefListFrom]').val();
    	var txtRefListTo = $('input[name=txtRefListTo]').val();
    	
			$('.polistrequired').each(function()
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
			window.open("../../report/gi/gilist-excel.php?txtRefListFrom=" + encodeURI(txtRefListFrom) + "&txtRefListTo=" + encodeURI(txtRefListTo) + "&txtDateFrom=" + encodeURI(txtDateFrom) + "&txtDateTo=" + encodeURI(txtDateTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });

    //Save GR
    $(document.body).on('click', '#btnSave', function (e) {

        var err = 0;
        var errmsg = '';
        var basentry = $('input[name=txtBaseEntry]').val();
        var issuetype = 0;
        var priceList = $('select[name=txtPriceList]').val();
        
        var postingdate = $('input[name=txtPostingDate]').val();
        var documentdate = $('input[name=txtDocDate]').val();
        var ref2 = $('input[name=txtRef2]').val();

        var remarks = $('textarea[name=txtRemarksF]').val();
        var journalremarks = $('input[name=txtJournalRemarks]').val();

        var series = $('#btnSeries').attr('series-val');
        var bplid = $('#btnSeries').attr('bplid-val');
		
		var rgoods = $('select[name=txtRGoodsIssue]').val();
		var soref = $('input[name=txtSORef]').val();
		var servcardno = $('input[name=txtServCardNo]').val();
		
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

                    itArr.push('"' + $(this).find('input.serialno').val() + '"');
					itArr.push('"' + $(this).find('input.acctcode').val() + '"');
                } else {
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
                url: '../../proc/exec/GI/exec-savegi.php',
                data: {
                    json: json.replace(/(\r\n|\n|\r)/gm, '[newline]'),
                    basentry: basentry,
                    issuetype: issuetype,
                    ref2: ref2,
                    pricelist: priceList,
                    postingdate: postingdate,
                    documentdate: documentdate,
                    remarks: remarks,
                    journalremarks: journalremarks,
                    series: series,
                    bplid: bplid,
                    servicetype: servicetype,
                    rgoods: rgoods,
					soref : soref
                },
                success: function (html) {
                    res = html.split('*');
                    if (res[0] == 'true') {
                        //Alert Success
                        notie.alert(1, res[1], 10);
                        //End

                        //Refresh the page

                        disablebuttons(true)
                        setTimeout(function () {
                            location.replace('../../forms/GI/GI.php');
                        }, 2000)
                        //End Refresh the page



                    } else {
                        //Alert when error
                        notie.alert(3, res[1], 10);
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
           notie.alert(3, errmsg, 10);
            //End
        }
    })
    //End Save GR

    //END SAVING AREA
    //=============================================================






    //FUNCTION AREA
    //=============================================================
    //Highlight function
    function highlight(tablename, tablerow) {

        $('.selected-whs').map(function () {
            $(this).removeClass('selected-whs');
        })

        $(tablename + ' tbody > tr').css("background-color", "transparent");
        $(tablerow).css("background-color", "lightgray");
        $(tablerow).addClass('selected-whs');

    }
    //End Highlight function



    //Bind Item Code
    function checkitemcode(itemcode) {
        var result = '';
		
        $.ajax({
            type: 'POST',
            url: '../../proc/views/GI/vw_checkitemcode.php',
            async: false,
            data: {
				itemcode : itemcode
			},
            success: function (html) {

                result = html;

            }

        });

        return result;

    }
    //End Bind Item Code



    //Bind Whs Code
    function checkwhs(whscode) {
        var result = '';
		
        $.ajax({
            type: 'POST',
            url: '../../proc/views/GI/vw_checkwhscode.php',
            async: false,
            data: {
				whscode : whscode
			},
            success: function (html) {

                result = html;

            }

        });

        return result;

    }
    //End Bind Whs Code


    //Bind Acct Code
    function checkacctcode(acctcode) {
        var result = '';
		
        $.ajax({
            type: 'POST',
            url: '../../proc/views/GI/vw_checkacctcode.php',
            async: false,
            data: {
				acctcode : acctcode
			},
            success: function (html) {

                result = html;

            }

        });

        return result;

    }
    //End Bind Acct Code


    //Bind BP Code
    function checkbpcode(bpcode) {
        var result = '';
        $.ajax({
            type: 'POST',
            url: '../../proc/views/GI/vw_checkbpcode.php',
            async: false,
            data: 'bpcode=' + bpcode,
            success: function (html) {

                result = html;

            }

        });

        return result;

    }
    //End Bind BP Code




    // Compute Line Total
    function computeLineTotal(qty, price, discount) {
        qty = isNaN(parseFloat(qty.replace(/,/g, ''))) ? 0 : parseFloat(qty.replace(/,/g, ''));
        price = isNaN(parseFloat(price.replace(/,/g, ''))) ? 0 : parseFloat(price.replace(/,/g, ''));
        discount = isNaN(parseFloat(discount.replace(/,/g, ''))) ? 0 : parseFloat(discount.replace(/,/g, ''));
        var total = ((qty * price) * (100 - discount) / 100);

        return formatMoney(total);
    }
    //End Compute Line Total



    //Compute Total Amount 
    function computeTotalAmount(cls) {
        var linetotal = 0.00;

        $('.' + cls).each(function () {
            if (isNaN(parseFloat($(this).val().replace(/,/g, '')))) {
                linetotal += 0;
            } else {
                linetotal += parseFloat($(this).val().replace(/,/g, ''));
            }

        })

        return formatMoney(linetotal);
    }
    //End Compute Total Amount



    // Compute Gross Price 
    function computeGrossPrice(price, taxrate, discount) {

        price = isNaN(parseFloat(price.replace(/,/g, ''))) ? 0 : parseFloat(price.replace(/,/g, ''));
        taxrate = isNaN(parseFloat(taxrate.replace(/,/g, ''))) ? 0 : parseFloat(taxrate.replace(/,/g, ''));
        discount = isNaN(parseFloat(discount.replace(/,/g, ''))) ? 0 : parseFloat(discount.replace(/,/g, ''));

        var total = (price * (1 + (taxrate / 100))) * ((100 - discount) / 100);
        return formatMoney2(total);
    }
    // End Compute Gross Price 


    // Compute Gross Total *
    function computeGrossTotal(grossprice, qty) {

        grossprice = isNaN(parseFloat(grossprice.replace(/,/g, ''))) ? 0 : parseFloat(grossprice.replace(/,/g, ''));
        qty = isNaN(parseFloat(qty.replace(/,/g, ''))) ? 0 : parseFloat(qty.replace(/,/g, ''));

        var total = (grossprice * qty);
        return formatMoney(total);
    }
    // End Compute Gross Total


    // Compute Discount Amount
    function computeDiscountAmt(totalbefdisc, discount) {
        totalbefdisc = isNaN(parseFloat(totalbefdisc.replace(/,/g, ''))) ? 0 : parseFloat(totalbefdisc.replace(/,/g, ''));
        discount = isNaN(parseFloat(discount.replace(/,/g, ''))) ? 0 : parseFloat(discount.replace(/,/g, ''));
        var total = totalbefdisc * (discount / 100);
        return formatMoney(total);
    }
    // End Compute Discount Amount


    // Compute Tax Amount Details 
    function computeTaxAmt(linetotal, taxrate) {

        linetotal = isNaN(parseFloat(linetotal.replace(/,/g, ''))) ? 0 : parseFloat(linetotal.replace(/,/g, ''));
        taxrate = isNaN(parseFloat(taxrate.replace(/,/g, ''))) ? 0 : parseFloat(taxrate.replace(/,/g, ''));

        var total = (linetotal * (taxrate / 100));

        return formatMoney(total);
    }
    // End Compute Tax Amount Details 



    // Compute Tax Amount Footer 
    function computeTaxAmtFooter(totaltax, discount) {
        totaltax = isNaN(parseFloat(totaltax.replace(/,/g, ''))) ? 0 : parseFloat(totaltax.replace(/,/g, ''));
        discount = isNaN(parseFloat(discount.replace(/,/g, ''))) ? 0 : parseFloat(discount.replace(/,/g, ''));
        var total = totaltax * ((100 - discount) / 100);
        return formatMoney(total);
    }
    // Compute Tax Amount Footer




    // Compute Total Amount Footer 

    function computeTPaymentDue(totalbefdisc, discount, totaltaxamt) {
        totalbefdisc = isNaN(parseFloat(totalbefdisc.replace(/,/g, ''))) ? 0 : parseFloat(totalbefdisc.replace(/,/g, ''));
        discount = isNaN(parseFloat(discount.replace(/,/g, ''))) ? 0 : parseFloat(discount.replace(/,/g, ''));
        totaltaxamt = isNaN(parseFloat(totaltaxamt.replace(/,/g, ''))) ? 0 : parseFloat(totaltaxamt.replace(/,/g, ''));

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
    //end Format Number


    //Disable buttons
    function disablebuttons(param) {
        //Disable Buttons
        $('#btnAddRow').prop('disabled', param);
        $('#btnDelRow').prop('disabled', param);
        $('#btnSave').prop('disabled', param);
        $('#btnFreeText').prop('disabled', param);
        //End Disable Buttons

    }
    //End Disable buttons


    //Add Rows for Population
    function populatedet(docentry, callback) {


        $('#tblDetails tbody').load('../../proc/views/GI/vw_documentdetailsdata.php?docentry=' + docentry, function (result) {

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
	function populatedet2(docentry, callback) {


        $('#tblDetails tbody').load('../../proc/views/GI/vw_documentdetailsdataGR.php?docentry=' + docentry, function (result) {

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
    function populatedetPO(docentry, callback) {


        $('#tblDetails tbody').load('../../proc/views/GI/vw_podetailsdata.php?docentry=' + docentry, function (result) {


            callback();


        })

    }
    //End Populate PO with PR Data Details


    // Compute Unit Price 
    function computeUnitPrice(price, taxrate) {

        price = isNaN(parseFloat(price.replace(/,/g, ''))) ? 0 : parseFloat(price.replace(/,/g, ''));
        taxrate = isNaN(parseFloat(taxrate.replace(/,/g, ''))) ? 0 : parseFloat(taxrate.replace(/,/g, ''));


        var total = (price / (1 + (taxrate / 100)));
        return formatMoney2(total);
    }
    // End Compute Unit Price 


    function computeGPAutoTrigger() {
        //if($('.selected-det').find('input.price').val() != ''){
        if (servicetype == 'I') {
            var price = $('.selected-det').find('input.price').val();
            var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
            var discount = $('.selected-det').find('input.discount').val();
            var qty = $('.selected-det').find('input.qty').val();

            //Gross Price
            $('.selected-det').find('input.grossprice').val(computeGrossPrice(price, taxrate, discount));
            //End Gross Price

            //Compute Tax Amount
            $('.selected-det').find('input.taxamount').trigger('keyup')
            //End Compute Tax Amount

            var grossprice = $('.selected-det').find('input.grossprice').val();

            //Gross Total
            $('.selected-det').find('input.grosstotal').val(computeGrossTotal(grossprice, qty));
            //End Gross Total
        } else {

            var price = $('.selected-det').find('input.price').val();
            var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
            var discount = '0';


            //Gross Price
            $('.selected-det').find('input.grossprice').val(computeGrossPrice(price, taxrate, discount));
            //End Gross Price

            //Compute Tax Amount
            $('.selected-det').find('input.taxamount').trigger('keyup')
            //End Compute Tax Amount


        } // End Service Type
        //}// ENd checking
    }// End ComputeGPAutoTrigger


    function computeUPAutoTrigger() {

        if (servicetype == 'I') {
            var grossprice = $('.selected-det').find('input.grossprice').val();
            //var price = $('.selected-det').find('input.price').val();
            var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
            var discount = $('.selected-det').find('input.discount').val();
            var qty = $('.selected-det').find('input.qty').val();

            //Gross Price
            if (parseFloat($('.selected-det').find('input.price').val()) > 0) {

            } else {
                $('.selected-det').find('input.price').val(computeUnitPrice(grossprice, taxrate));
            }
            //End Gross Price

            //Compute Line Total
            $('.selected-det').find('input.linetotal').trigger('keyup');
            //End Compute Line Total

            //Compute Tax Amount
            $('.selected-det').find('input.taxamount').trigger('keyup')
            //End Compute Tax Amount



            //Gross Total
            $('.selected-det').find('input.grosstotal').val(computeGrossTotal(grossprice, qty));
            //End Gross Total
        } else {
            var grossprice = $('.selected-det').find('input.grossprice').val();
            //var price = $('.selected-det').find('input.price').val();
            var taxrate = $('.selected-det').find('select.taxcode option:selected').attr('val-rate');
            var discount = '0';


            //Gross Price
            if (parseFloat($('.selected-det').find('input.price').val()) > 0) {

            } else {
                $('.selected-det').find('input.price').val(computeUnitPrice(grossprice, taxrate));
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
    $(".custom-menu li").click(function () {

        // This is the triggered action name
        switch ($(this).attr("data-action")) {

            // A case for each action. Your actions here
            case "first":
                alert("first");
                break;
            case "second":
                alert("second");
                break;
            case "third":
                alert("third");
                break;
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

    var readyStateCheckInterval = setInterval(function () {
        if (document.readyState === "complete") {
            clearInterval(readyStateCheckInterval);
            $('#modal-load-init').modal('hide');

            $('#modal-load-init').on('hidden.bs.modal', function () {

            })

            //Trigger change on cmbServiceType
            $('select[name=cmbServiceType]').trigger('change');
            //End Trigger change on cmbServiceType



        }
    }, 10);


})//end document.ready