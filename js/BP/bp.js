function reload() {
    location.reload();
}
$(document).ready(function() {

	$('#modal-load-init').modal('show');

	$('#window-title').text('Business Partner');
	$('#mod-title').text('Business Partner');
    
	$(document.body).on('change', 'select[name=selCategory]', function () 
	{
		var selCategory = $(this).val();
		
		var selCategoryval = $('#selCategory option:selected').attr('category-val');
		
		$('select[name=selGroup]').html('<option>Loading...</option>');
		$('select[name=selGroup]').load('../../proc/views/BP/vw_group.php?selCategory=' + encodeURI(selCategory) +  '&selCategoryval=' + selCategoryval);
	});
	
	$('#BPModal').on('shown.bs.modal',function()
	{
		
		$('#BPCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#BPCont').load('../../proc/views/BP/vw_bplist.php?CardType=C',function()
		{
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
                                url: '../../proc/views/BP/vw_bplist-load.php',
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
	$('#BPModal').keydown(function(e) {
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
		        	if($('#tblBP tbody').find('tr.selected-whs').index() >= 0){
		        		$('tr.selected-whs').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblBP tbody > tr:first').trigger('click');
		        	}
		        	//End
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
		var cardtype = $(this).children('td.item-8').text();
		var frozenfor = $(this).children('td.item-9').text();
		var groupcode = $(this).children('td.item-10').text();
		var selCategory = '';
		
		if(cardtype == 'C')
		{
			var cardtype1 = 0;
		}
		else
		{
			var cardtype1 = 1;
		}
		
		if(frozenfor == 'Y')
		{
			var frozenfor1 = 1;
		}
		else
		{
			var frozenfor1 = 0
		}

		$('input[name=txtBPCode]').val(BPCode);
		$('input[name=txtBPName]').val(BPName);
		$('select[name=selCategory]').val(cardtype1);
		$('select[name=selStatus]').val(frozenfor1);
		
		$('select[name=selGroup]').html('<option>Loading...</option>');
		$('select[name=selGroup]').load('../../proc/views/BP/vw_group.php?selCategory=' + encodeURI(selCategory) + '&selCategoryval=' + cardtype, 
		function () {
			$('select[name=selGroup]').val(groupcode);
		});
		
		$('#btnSave').addClass('hidden');
		$('#btnUpdate').removeClass('hidden');
		
		$('#BPModal').modal('hide');
		
	})
	//End Select Acct Table Row Click

	//Search BP
	$(document.body).on('keyup','input[name=BPSearch]',function(){
		
		var searchVal = $(this).val().toLowerCase();
        $('#BPCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#BPCont table tbody').load('../../proc/views/BP/vw_bplist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search BP
	
    $(document.body).on('click','#btnSaveBP',function(e){
    	
    	var err = 0;
    	var errmsg = '';
		
		var selCategory = $('select[name=selCategory]').val();
		var txtBPCode = $('input[name=txtBPCode]').val();
		var txtBPName = $('input[name=txtBPName]').val();
		var selGroup = $('select[name=selGroup]').val();
		var selStatus = $('select[name=selStatus]').val();
		
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
                url: '../../proc/exec/BP/exec-save.php',
                data: {
                		selCategory : selCategory,
                		txtBPCode : txtBPCode,
						txtBPName : txtBPName,
						selGroup : selGroup,
						selStatus : selStatus
				},
                success: function(html)
				{
					//alert(html);
					res = html.split('*');
					
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 3);
						
						setTimeout(function(){
							location.replace('../../forms/BP/BP.php');
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
		
		var selCategory = $('select[name=selCategory]').val();
		var txtBPCode = $('input[name=txtBPCode]').val();
		var txtBPName = $('input[name=txtBPName]').val();
		var selGroup = $('select[name=selGroup]').val();
		var selStatus = $('select[name=selStatus]').val();
		
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
                url: '../../proc/exec/BP/exec-update.php',
                data: {
                		selCategory : selCategory,
                		txtBPCode : txtBPCode,
						txtBPName : txtBPName,
						selGroup : selGroup,
						selStatus : selStatus
				},
                success: function(html)
				{
					//alert(html);
					res = html.split('*');
					
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 3);
						
						setTimeout(function(){
							location.replace('../../forms/BP/BP.php');
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
	    	$('#BPModal').modal('show');
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