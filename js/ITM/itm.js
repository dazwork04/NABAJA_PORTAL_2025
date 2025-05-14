function reload() {
    location.reload();
}
$(document).ready(function() {

	$('#modal-load-init').modal('show');

	$('#window-title').text('Item Master Data');
	$('#mod-title').text('Item Master Data');
	
	$('select[name=selGroup]').html('<option>Loading...</option>');
	$('select[name=selGroup]').load('../../proc/views/ITM/vw_group.php');
	
	$('#ItemModal').on('shown.bs.modal',function()
	{
		$('#ItemCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#ItemCont').load('../../proc/views/ITM/vw_itemlist.php',function()
		{
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
                                url: '../../proc/views/ITM/vw_itemlist-load.php',
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
		    });
		    //End Add Scroll Function

		});

		$('input[name=ItemSearch]').focus();	
	});
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

		
		var ItemCode = $(this).children('td.item-1').text();
		var ItemName = $(this).children('td.item-2').text();
		var frozenfor = $(this).children('td.item-10').text();
		var groupcode = $(this).children('td.item-9').text();
		var sellingprice = $(this).children('td.item-11').text();
		
		if(frozenfor == 'Y')
		{
			var frozenfor1 = 1;
		}
		else
		{
			var frozenfor1 = 0
		}

		$('input[name=txtItemCode]').val(ItemCode);
		$('input[name=txtItemName]').val(ItemName);
		$('select[name=selStatus]').val(frozenfor1);
		
		$('select[name=selGroup]').html('<option>Loading...</option>');
		$('select[name=selGroup]').load('../../proc/views/ITM/vw_group.php', 
		function () {
			$('select[name=selGroup]').val(groupcode);
		});
		$('input[name=txtSellingPrice]').val(sellingprice);
		
		$('#btnSave').addClass('hidden');
		$('#btnUpdate').removeClass('hidden');
			
		$('#ItemModal').modal('hide');

	})
	//End Select Item Table Row Click

	//Search Item
	$(document.body).on('keyup','input[name=ItemSearch]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#ItemCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#ItemCont table tbody').load('../../proc/views/ITM/vw_itemlist-load.php?srchval=' + encodeURI(searchVal));
	});
	//End Search Item
	
	$('#ItemModalFrom').on('shown.bs.modal',function()
	{
		
		$('#ItemContFrom').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#ItemContFrom').load('../../proc/views/ITM/vw_itemlistfrom.php',function()
		{
			$('#ItemContFrom .table-responsive').bind('scroll', function(){
		        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 20) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#ItemContFrom table tbody > tr:last').children('td').eq(0).text();
                        var ctr = 0;

                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');


                            $.ajax({
                                type: 'POST',
                                url: '../../proc/views/ITM/vw_itemlistfrom-load.php',
                                data: {
									itemcode : itemcode
								},
                                success: function (html) {

                                    $('#ItemContFrom table tbody').append(html);
                                    $('#itm-loader').each(function () {
                                        $(this).remove();
                                    });


                                }
                            });

                        }

                    }
                }
		    });
		    //End Add Scroll Function

		});

		$('input[name=ItemSearchFrom]').focus();	
	});
	//End Load Item

	
	//Clear Item
	$('#ItemModalFrom').on('hide.bs.modal',function(){
		$('#ItemContFrom').empty();

	});
	
	$(document.body).on('keyup','input[name=ItemSearchFrom]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#ItemContFrom table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#ItemContFrom table tbody').load('../../proc/views/ITM/vw_itemlistfrom-load.php?srchval=' + encodeURI(searchVal));
	});
	
	$(document.body).on('dblclick','#tblItemFrom tbody > tr',function(){

		
		var ItemCode = $(this).children('td.item-1').text();
		var ItemName = $(this).children('td.item-2').text();
		var sellingprice = $(this).children('td.item-11').text();
		
		$('input[name=txtItemCodeFrom]').val(ItemCode);
			
		$('#ItemModalFrom').modal('hide');

	});
	
	//------
	
	$('#ItemModalTo').on('shown.bs.modal',function()
	{
		
		$('#ItemContTo').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#ItemContTo').load('../../proc/views/ITM/vw_itemlistto.php',function()
		{
			$('#ItemContTo .table-responsive').bind('scroll', function(){
		        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 20) {
                    if ($(this).scrollTop() > 0) {
                        var itemcode = $('#ItemContTo table tbody > tr:last').children('td').eq(0).text();
                        var ctr = 0;

                        $('#itm-loader').each(function () {
                            ctr += 1;
                        });
                        if (ctr == 0) {
                            $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');


                            $.ajax({
                                type: 'POST',
                                url: '../../proc/views/ITM/vw_itemlistto-load.php',
                                data: {
									itemcode : itemcode
								},
                                success: function (html) {

                                    $('#ItemContTo table tbody').append(html);
                                    $('#itm-loader').each(function () {
                                        $(this).remove();
                                    });


                                }
                            });

                        }

                    }
                }
		    });
		    //End Add Scroll Function

		});

		$('input[name=ItemSearchTo]').focus();	
	});
	//End Load Item

	
	//Clear Item
	$('#ItemModalTo').on('hide.bs.modal',function(){
		$('#ItemContTo').empty();

	});
	
	$(document.body).on('keyup','input[name=ItemSearchTo]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#ItemContTo table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#ItemContTo table tbody').load('../../proc/views/ITM/vw_itemlistto-load.php?srchval=' + encodeURI(searchVal));
	});
	
	$(document.body).on('dblclick','#tblItemTo tbody > tr',function(){

		
		var ItemCode = $(this).children('td.item-1').text();
		var ItemName = $(this).children('td.item-2').text();
		var sellingprice = $(this).children('td.item-11').text();
		
		$('input[name=txtItemCodeTo]').val(ItemCode);
			
		$('#ItemModalTo').modal('hide');

	});
	
	$(document.body).on('click','#btnGenerateItems',function(e)
	{
		var err = 0;
    	var errmsg = '';
		
		var txtItemCodeFrom = $('input[name=txtItemCodeFrom]').val();
		var txtItemCodeTo = $('input[name=txtItemCodeTo]').val();
		
		if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/views/ITM/vw_generateitemlist.php',
                data: {
                		txtItemCodeFrom : txtItemCodeFrom,
                		txtItemCodeTo : txtItemCodeTo
				},
                success: function(html)
				{
					$('#resItems').html(html);
					
					$('#btnUpdatePrielist').removeClass('hidden');
					
					$('#modal-load-init').modal('hide');
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
	});
	
	$(document.body).on('click','#btnUpdatePrielist',function(e)
	{
		var err = 0;
    	var errmsg = '';
		
		
		
		var json = '{';
		var otArr = [];
		var tbl2 = $('#tblGenerateItem tbody tr').each(function(i) {  

		   
		        x = $(this).children();
		        var itArr = [];
		    
		        
				itArr.push('"' + $(this).find('input.itemcode').val() + '"');
				itArr.push('"' + $(this).find('input.itemcodeprice').val().replace(/,/g,'') + '"');
				
				otArr.push('"' + i + '": [' + itArr.join(',') + ']'); 
		     
		});
		//PARSE ALL SCRIPT
		json += otArr.join(",") + '}';
		
		if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/ITM/exec-updatepricelist.php',
                data: {
                		json : json.replace(/(\r\n|\n|\r)/gm, '')
				},
                success: function(html)
				{
					res = html.split('*');
					
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 3);
						
						/* setTimeout(function(){
							location.replace('../../forms/SO/SO.php');
						},2000); */
					}
					else
					{
						notie.alert(3, res[1], 3);
					}

					$('#modal-load-init').modal('hide');
			    },
                error: function(){
                  //showAlert('alert-danger animated bounceIn', 'Something went wrong!');

                },
                beforeSend:function(){
                  //showAlert('alert-success animated bounceIn', 'Authenticating. Please wait...');
                }
            });
    		
    	}
		else
		{
    		notie.alert(3, errmsg, 3);
        }
    });

    $(document.body).on('click','#btnSaveITM',function(e){
    	
    	var err = 0;
    	var errmsg = '';
		var selSeries = $('select[name=selSeries]').val();
		var txtItemCode = $('input[name=txtItemCode]').val();
		var txtItemName = $('input[name=txtItemName]').val();
		var selGroup = $('select[name=selGroup]').val();
		var selStatus = $('select[name=selStatus]').val();
		
		var txtSellingPrice = isNaN(parseFloat($('input[name=txtSellingPrice]').val().replace(/,/g, ''))) ? 0 : parseFloat($('input[name=txtSellingPrice]').val().replace(/,/g, ''));
    	
		$('.required').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	
		if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/ITM/exec-save.php',
                data: {
						selSeries : selSeries,
                		txtItemCode : txtItemCode,
						txtItemName : txtItemName,
						selGroup : selGroup,
						selStatus : selStatus,
						txtSellingPrice : txtSellingPrice
				},
                success: function(html)
				{
					//alert(html);
					res = html.split('*');
					
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 3);
						
						setTimeout(function(){
							location.replace('../../forms/ITM/ITM.php');
						},2000)
					}
					else
					{
						notie.alert(3, res[1], 3);
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
    
	   $(document.body).on('click','#btnUpdate',function(e){
    	
    	var err = 0;
    	var errmsg = '';
		
		var txtItemCode = $('input[name=txtItemCode]').val();
		var txtItemName = $('input[name=txtItemName]').val();
		var selGroup = $('select[name=selGroup]').val();
		var selStatus = $('select[name=selStatus]').val();
		
    	var txtSellingPrice = isNaN(parseFloat($('input[name=txtSellingPrice]').val().replace(/,/g, ''))) ? 0 : parseFloat($('input[name=txtSellingPrice]').val().replace(/,/g, ''));
    	
		$('.required').each(function(){
    		
    		if($(this).val() == ''){
    			
    			$(this).parent().addClass('has-error');
    			err += 1;
    			errmsg = 'Please complete all the required field/s!';
    		}else{
    			$(this).parent().removeClass('has-error');
    		}
    	})
    	
		if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/ITM/exec-update.php',
                data: {
                		txtItemCode : txtItemCode,
						txtItemName : txtItemName,
						selGroup : selGroup,
						selStatus : selStatus,
						txtSellingPrice : txtSellingPrice
				},
                success: function(html)
				{
					//alert(html);
					res = html.split('*');
					
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 3);
						
						setTimeout(function(){
							location.replace('../../forms/ITM/ITM.php');
						},2000)
					}
					else
					{
						notie.alert(3, res[1], 3);
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
	
	$(window).keydown(function(e) {
		
	    if(e.keyCode == 70 && e.ctrlKey)
		{
	    	$('#ItemModal').modal('show');
	    	e.preventDefault();
	    }
		else if(e.keyCode == 65 && e.ctrlKey)
		{
	    
	    }
	});
	
	var readyStateCheckInterval = setInterval(function() {
	    if (document.readyState === "complete") {
	        clearInterval(readyStateCheckInterval);
	        $('#modal-load-init').modal('hide');

	        $('#modal-load-init').on('hidden.bs.modal',function(){
	        	
	        })
		}
	}, 10);
	
	
	
	
})//end document.ready