$(document).ready(function() 
{
	$('#window-title').text('Cost Center');
	$('#mod-title').text('Cost Center');
	
	$('#modal-load-init').modal('show');

	var cback = 1;
	var activemod = '';
	var mode = 'Add';
	var servicetype = 'I';
	var activewhs = '';
	
	$('#txtEffectiveDate').datetimepicker(
	{
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
	});
	
	//Find Document
	$(window).keydown(function(e) 
	{
	    if(e.keyCode == 70 && e.ctrlKey)
		{
	    	//Ctrl + f
	    	$('#DocumentModal').modal('show');
	    	e.preventDefault();
	    }
		else if(e.keyCode == 65 && e.ctrlKey)
		{
	    	location.reload();
	    }
	    //e.preventDefault(); // prevent the default action (scroll / move caret)
	});
	//End Find Document

	//Load Documents
	$('#DocumentModal').on('shown.bs.modal',function()
	{
		$('#DocumentCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#DocumentCont').load('../../proc/views/CC/vw_doclist.php?servicetype=' + encodeURI(servicetype),function()
		{
		    $('#DocumentCont .table-responsive').bind('scroll', function()
			{
		        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight)
				{
		            if($(this).scrollTop() > 0)
					{
						var itemcode = $('#DocumentCont table tbody > tr:last').children('td').eq(0).text();
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
		                        url: '../../proc/views/CC/vw_doclist-load.php',
		                        data: {
									itemcode : itemcode
								},
		                        success: function(html)
								{
									$('#DocumentCont table tbody').append(html);                
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
		$('input[name=DocumentSearch]').focus();
	});

	//Clear Document List
	$('#DocumentModal').on('hide.bs.modal',function()
	{
		$('#DocumentCont').empty();
	});
	//End Clear Document List

	//Add Keypress on DOcument MOdal
	$('#DocumentModal').keydown(function(e) 
	{
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

	//Highlight Document Table Row Click
	$(document.body).on('click','#tblDocument tbody > tr',function(e)
	{
		highlight('#tblDocument',this);
	});
	//End Highlight Document Table Row Click

	//Search Document
	$(document.body).on('keyup','input[name=DocumentSearch]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#DocumentCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#DocumentCont table tbody').load('../../proc/views/CC/vw_doclist-load.php?srchval=' + encodeURI(searchVal));
	});
	//End Search Document


	//Select Document Table Row Click
	$(document.body).on('dblclick','#tblDocument tbody > tr',function()
	{
		var txtPrcCode = $(this).children('td.item-1').text();
		var txtPrcName = $(this).children('td.item-3').text();
		var txtEffectiveDate = $(this).children('td.item-5').text();
		var selDimension = $(this).children('td.item-6').text();
		var chkActive = $(this).children('td.item-7').text();
		
		$('#DocumentModal').modal('hide');

		$('input[name=txtPrcCode]').val(txtPrcCode).prop('disabled',true);
		$('input[name=txtPrcName]').val(txtPrcName);
		$('input[name=txtEffectiveDate]').val(txtEffectiveDate);
		$('select[name=selDimension]').val(selDimension);	
		
		if(chkActive == 'Y')
		{
			$('input[name=ChkActive]').prop('checked', true);
		}
		else
		{
			$('input[name=ChkActive]').prop('checked', false);
		}
		
		$('#btnSave').addClass('hidden');
		$('#btnUpdate').removeClass('hidden');
		$('#btnCancelDoc').prop('disabled', false);
	});
	//End Select Document Table Row Click

	//Print Document
	$(document.body).on('click','#btnPrint',function()
	{
		var docentry = $('input[name=txtDocEntry]').val();
		var servicetype = $('select[name=cmbServiceType]').val();
		if(docentry != '')
		{
			
		}
	});
	//End Print Document
	
	$(document.body).on('click','#btnNew',function(e)
	{
		location.reload();
	});
	
    $(document.body).on('click','#btnSaveCC',function(e)
	{
    	var err = 0;
    	var errmsg = '';
    	var txtPrcCode = $('input[name=txtPrcCode]').val();
    	var txtPrcName = $('input[name=txtPrcName]').val();
    	var selDimension = $('select[name=selDimension]').val();
    	var txtEffectiveDate = $('input[name=txtEffectiveDate]').val();
		var ChkActive = 'N';
		
		if($('input[name=ChkActive]').prop('checked') == true)
		{
			ChkActive = 'Y';
		}
    	
    	$('.required').each(function()
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
    		$('#modal-load-init').modal('show');
	    
			$.ajax({
                type: 'POST',
                url: '../../proc/exec/CC/exec-save.php',
                data: {
                		txtPrcCode : txtPrcCode,
                		txtPrcName : txtPrcName,
                		selDimension : selDimension,
                		txtEffectiveDate : txtEffectiveDate,
                		ChkActive : ChkActive
                },
                success: function(html)
				{
					res = html.split('*');
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 10);
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/CC/CC.php');
						},2000);
					}
					else
					{
						notie.alert(3, res[1], 10);
					}

					$('#modal-load-init').modal('hide');
			    }
            });
    	}
		else
		{
    		notie.alert(3, errmsg, 10);
        }
    });
	
	$(document.body).on('click','#btnUpdate',function(e)
	{
    	var err = 0;
    	var errmsg = '';
    	var txtPrcCode = $('input[name=txtPrcCode]').val();
    	var txtPrcName = $('input[name=txtPrcName]').val();
    	var selDimension = $('select[name=selDimension]').val();
    	var txtEffectiveDate = $('input[name=txtEffectiveDate]').val();
		var ChkActive = 'N';
		
		if($('input[name=ChkActive]').prop('checked') == true)
		{
			ChkActive = 'Y';
		}
    	
    	$('.required').each(function()
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
    		$('#modal-load-init').modal('show');
	    
			$.ajax({
                type: 'POST',
                url: '../../proc/exec/CC/exec-update.php',
                data: {
                		txtPrcCode : txtPrcCode,
                		txtPrcName : txtPrcName,
                		selDimension : selDimension,
                		txtEffectiveDate : txtEffectiveDate,
                		ChkActive : ChkActive
                },
                success: function(html)
				{
					res = html.split('*');
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 10);
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/CC/CC.php');
						},2000);
					}
					else
					{
						notie.alert(3, res[1], 10);
					}

					$('#modal-load-init').modal('hide');
			    }
            });
    	}
		else
		{
    		notie.alert(3, errmsg, 10);
        }
    });
    
	//Canceled SO
    $(document.body).on('click','#btnCancelCC',function(e)
	{
    	var err = 0;
    	var errmsg = '';
    	var txtPrcCode = $('input[name=txtPrcCode]').val();
    	var txtPrcName = $('input[name=txtPrcName]').val();
    	var selDimension = $('select[name=selDimension]').val();
    	var txtEffectiveDate = $('input[name=txtEffectiveDate]').val();
		var ChkActive = 'N';
		
		if($('input[name=ChkActive]').prop('checked') == true)
		{
			ChkActive = 'Y';
		}
    	
    	$('.required').each(function()
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
    		$('#modal-load-init').modal('show');
	    
			$.ajax({
                type: 'POST',
                url: '../../proc/exec/CC/exec-remove.php',
                data: {
                		txtPrcCode : txtPrcCode,
                		txtPrcName : txtPrcName,
                		selDimension : selDimension,
                		txtEffectiveDate : txtEffectiveDate,
                		ChkActive : ChkActive
                },
                success: function(html)
				{
					res = html.split('*');
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 10);
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/CC/CC.php');
						},2000);
					}
					else
					{
						notie.alert(3, res[1], 10);
					}

					$('#modal-load-init').modal('hide');
			    }
            });
    	}
		else
		{
    		notie.alert(3, errmsg, 10);
        }
    })
    //End Canceled SO
	
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
	
	//Add Rows for Population
	function populatedet(docentry,callback)
	{
		$('#tblDetails tbody').load('../../proc/views/JE/vw_documentdetailsdata.php?docentry=' + docentry,function(result)
		{
			callback();
		});
	}
	//End Add row for population

	// CUSTOMIZED RIGHT CLICK ON WINDOW
	// Trigger action when the contexmenu is about to be shown
	$(document).bind("contextmenu", function (event) 
	{
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
	$(document).bind("mousedown", function (e) 
	{
	    // If the clicked element is not the menu
	    if (!$(e.target).parents(".custom-menu").length > 0) 
		{
	        // Hide it
	        $(".custom-menu").hide(100);
	    }
	});

	// If the menu element is clicked
	$(".custom-menu li").click(function()
	{
	    // This is the triggered action name
	    switch($(this).attr("data-action")) 
		{
			// A case for each action. Your actions here
	        case "first": alert("first"); break;
	        case "second": alert("second"); break;
	        case "third": alert("third"); break;
	    }
	  
	    // Hide it AFTER the action was triggered
	    $(".custom-menu").hide(100);
	 });
	// END CUSTOMIZED RIGHT CLICK ON WINDOW
	
	$(document.body).on('keyup','.debit',function()
	{
		var totaldebit = computeTotal('debit');
		
		$('input[name=txtDebit]').val(totaldebit);
	});
	
	$(document.body).on('keyup','.credit',function()
	{
		var totalcredit = computeTotal('credit');
		
		$('input[name=txtCredit]').val(totalcredit);
	});
	
	function computeTotal(cls)
	{
		var total = 0.00;

		$('.'+cls).each(function()
		{
			if(isNaN(parseFloat($(this).val().replace(/,/g,''))))
			{
				total += 0;
			}
			else
			{
				total += parseFloat($(this).val().replace(/,/g,''));
			}
	    });
		
		return formatMoney(total);
	}
	
	//=======================================================================================================
	//End javascript Code
	//=======================================================================================================
	//Hide Intialize Modal after loading all the javascript
	
	var readyStateCheckInterval = setInterval(function() 
	{
	    if (document.readyState === "complete") 
		{
	        clearInterval(readyStateCheckInterval);
	        $('#modal-load-init').modal('hide');
	    }
	}, 10);
	
	
})//end document.ready