$(document).ready(function() 
{
	$('#window-title').text('Journal Entry');
	$('#mod-title').text('Journal Entry');
	
	$('#modal-load-init').modal('show');

	var cback = 1;
	var activemod = '';
	var mode = 'Add';
	var servicetype = 'I';
	var activewhs = '';
	
	$('#txtRefDate').datetimepicker(
	{
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
	});
	
	$('#txtDueDate').datetimepicker(
	{
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
	});

	$('#txtTaxDate').datetimepicker(
	{
	    format: 'MM/DD/YYYY',
		defaultDate: new Date()
    });
	
    $('#txtDocNo').html('Loading...')
    $('#txtDocNo').load('../../proc/views/JE/vw_series.php?objtype=30',function()
	{
		$('input[name=txtDocNo]').val($('.series:first').attr('val-nextnum'));
    });
	
	$('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /></p>');
	$('#ModDetails').load('../../forms/JE/JE-details.php',function()
	{
		/* $('#tblDetails tbody tr').each(function(i) 
		{
			$(this).closest('tr').addClass('selected-det');
			$(this).find('select.taxgroup').html('<option>Loading...</option>');
			$(this).find('select.taxgroup').load('../../proc/views/JE/vw_taxgroup.php');
			$(this).removeClass('selected-det');
		}); */
		cback = 0;
	});
	
	$('#txtAutomaticTax').change(function() 
	{
		if($(this).is(":checked")) 
		{
			$('input[name=txtManageWTax]').prop('disabled', false);
			
			$('#tblDetails tbody tr').each(function(i) 
			{
				$(this).closest('tr').addClass('selected-det');
				$(this).find('select.taxgroup').html('<option>Loading...</option>');
				$(this).find('select.taxgroup').load('../../proc/views/JE/vw_taxgroup.php');
				$(this).removeClass('selected-det');
			});
        }
		else
		{
			$('input[name=txtManageWTax]').prop('disabled', true);
			$('input[name=txtManageWTax]').prop('checked', false);
			
			$('#tblDetails tbody tr').each(function(i) 
			{
				$(this).closest('tr').addClass('selected-det');
				$(this).find('select.taxgroup').html('<option>-Select-</option>');
				$(this).find('select.wtax').html('<option>-Select-</option>');
				$(this).removeClass('selected-det');
			});
		}
    });
	
	$('#txtManageWTax').change(function() 
	{
		if($(this).is(":checked")) 
		{
			$('#tblDetails tbody tr').each(function(i) 
			{
				$(this).closest('tr').addClass('selected-det');
				$(this).find('select.wtax').html('<option>Loading...</option>');
				$(this).find('select.wtax').load('../../proc/views/JE/vw_wtax.php');
				$(this).removeClass('selected-det');
			});
        }
		else
		{
			$('#tblDetails tbody tr').each(function(i) 
			{
				$(this).closest('tr').addClass('selected-det');
				$(this).find('select.wtax').html('<option>-Select-</option>');
				$(this).removeClass('selected-det');
			});
		}
    });
	
	$(document.body).on('change','.taxgroup',function()
	{
		var docentry = '';
		var itemindex = $('.selected-det').find('input.item_index').val();
		var debit = $('.selected-det').find('input.debit').val().replace(/,/g, '');
		var credit = $('.selected-det').find('input.credit').val().replace(/,/g, '');
		var taxgroup = $('.selected-det').find('select.taxgroup').val();
		var taxrate = $('.selected-det').find('select.taxgroup option:selected').attr('val-rate');
		var acctcode = $('.selected-det').find('select.taxgroup option:selected').attr('val-acctcode');
		var acctname = $('.selected-det').find('select.taxgroup option:selected').attr('val-acctname');
		
		var taxamountdebit = 0;
		var taxamountcredit = 0;
		
		if(debit != '' || debit != 0)
		{
			var taxamountdebit = debit * (taxrate/100);
		}
		
		if(credit != '' || credit != 0)
		{
			var taxamountcredit = credit * (taxrate/100);
		}
		
		var count = 0;
		
		$('#tblDetails tbody > tr ').each(function()
		{
			var $currentRow = $(this);
			var $lineno = $currentRow.find(".lineno").val();
			var $itemindex1 = $currentRow.find(".item_index").val();
			
			if(itemindex == $itemindex1 && $lineno == 1)
			{
				count+=1;		
			}
		});
		
		if(count == 0)
		{
			$('#btnAddRow').load('../../proc/views/JE/vw_addtaxrow.php?itemindex='+encodeURI(itemindex)+'&taxgroup='+encodeURI(taxgroup)+'&acctcode='+encodeURI(acctcode)+'&acctname='+encodeURI(acctname),function(result)
			{
				$('#tblDetails tbody').append(result);
				$('#tblDetails tbody tr:last').find('input.debit').val(formatMoney(taxamountdebit));
				$('#tblDetails tbody tr:last').find('input.credit').val(formatMoney(taxamountcredit));
				
				$('.selected-det').find('input.debit').trigger('keyup');
				$('.selected-det').find('input.credit').trigger('keyup');
			});
			$('#btnAddRow').empty();
		}
		else
		{
			$('#tblDetails tbody > tr ').each(function()
			{
				var $currentRow = $(this);
				var $lineno = $currentRow.find(".lineno").val();
				var $itemindex1 = $currentRow.find(".item_index").val();
				
				if(itemindex == $itemindex1 && $lineno == 1)
				{
					$currentRow.find(".acctcode").val(acctcode);
					$currentRow.find(".acctname").val(acctname);
					$currentRow.find(".taxgroup").html('<option value="">'+taxgroup+'</option>');
					
					if(taxamountdebit == 0)
					{
						$currentRow.find(".debit").val('');
					}
					else
					{
						$currentRow.find(".debit").val(formatMoney(taxamountdebit));
					}
					
					if(taxamountcredit == 0)
					{
						$currentRow.find(".credit").val('');
					}
					else
					{
						$currentRow.find(".credit").val(formatMoney(taxamountcredit));
					}
				}
			});
		}
	});
	
	$(document.body).on('click','#btnAddRow',function()
	{
		$(this).prop('disabled',true);
		var rowno = 0;
		
		if ($('#tblDetails tbody tr').length == 0) 
		{
			rowno = 1;
		}
		else
		{
			rowno = ($('#tblDetails tbody tr:last').find('input.item_index').val() == '') ? 1 : parseFloat($('#tblDetails tbody tr:last').find('input.item_index').val()) + 1;
		}
		
			$(this).load('../../forms/JE/JE-details-row.php',function(result)
			{
				$('#tblDetails tbody').append(result);
				$('#tblDetails tbody tr:last').find('td.rowno').html(rowno);
				$('#tblDetails tbody tr:last').find('input.item_index').val(rowno);
				
				if($('input[name=txtAutomaticTax]').prop('checked') == true)
				{
					$('#tblDetails tbody tr:last').find('select.taxgroup').html('<option>Loading...</option>');
					$('#tblDetails tbody tr:last').find('select.taxgroup').load('../../proc/views/JE/vw_taxgroup.php');
				}
				
				if($('input[name=txtAutomaticTax]').prop('checked') == true)
				{
					$('#tblDetails tbody tr:last').find('select.wtax').html('<option>Loading...</option>');
					$('#tblDetails tbody tr:last').find('select.wtax').load('../../proc/views/JE/vw_wtax.php');
				}
				
				$(this).empty();
				$(this).prop('disabled',false);
			});
	});
	
	$(document.body).on('click','#btnDelRow',function()
	{
		$('.selected-det').remove();
		
		var rowno = 1;
		$('#tblDetails tbody tr').each(function()
		{
			ftext = $(this).find('.ftext').text();
			
			if(ftext == 'Y')
			{
				$(this).find('td.rowno').html(rowno);
			}
			else
			{
				$(this).find('td.rowno').html(rowno);
			}
			rowno += 1;
		});
		
		$('.selected-det').find('input.debit').trigger('keyup');
		$('.selected-det').find('input.credit').trigger('keyup');
		
		$('#btnAddRow').empty();
	});

	//Add selected class on row when focused on input
	$(document.body).on('focus','#tblDetails input, #tblDetails select, #tblDetails textarea', function()
	{
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
	});
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
	$(document.body).on('keyup','input[name=ProjectSearch]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#ProjectCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#ProjectCont table tbody').load('../../proc/views/APV/vw_projlist-load.php?srchval=' + encodeURI(searchVal));
	});
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
        $('#EmployeeCont table tbody').load('../../proc/views/APV/vw_emplist-load.php?srchval=' + encodeURI(searchVal));
	});
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

	$('#AcctModal').on('shown.bs.modal',function()
	{
		$('#AcctCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#AcctCont').load('../../proc/views/JE/vw_acctlist.php',function()
		{
			$('#AcctCont .table-responsive').bind('scroll', function()
			{
				if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight)
				{
					if($(this).scrollTop() > 0)
					{
						var itemcode = $('#AcctCont table tbody > tr:last').children('td').eq(0).text();
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
								url: '../../proc/views/JE/vw_acctlist-load.php',
								data: 
								{
									itemcode : itemcode
								},
								success: function(html)
								{
									$('#AcctCont table tbody').append(html);                
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
		
		$('input[name=AcctSearch]').focus();
	});
	
	$('#AcctModal').on('hide.bs.modal',function()
	{
		$('#AcctCont').empty();
	});

	$('#AcctModal').keydown(function(e) 
	{
		    switch(e.which) 
			{
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

	//Highlight Item Table Row Click
	$(document.body).on('click','#tblAcct tbody > tr',function(e)
	{
		highlight('#tblAcct',this);
	});
	//End Highlight Item Table Row Click

	//Select Acct Table Row Click
	$(document.body).on('dblclick','#tblAcct tbody > tr',function()
	{
		var acctname = $(this).children('td.item-1').text();
		var acctcode = $(this).children('td.item-2').text();
		var acct = $(this).children('td.item-3').text();
		
		$('#AcctModal').modal('hide');

		$('.selected-det').find('input.acctcode').val(acctcode);
		$('.selected-det').find('input.acctname').val(acctname);
		$('.selected-det').find('input.cat').val('ACCT');
		$('.selected-det').find('input.debit').focus();

		$('.selected-det').find('input.acctcode').attr('aria-acctcode',acct);
	});
	//End Select Acct Table Row Click

	//Search Acct
	$(document.body).on('keyup','input[name=AcctSearch]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#AcctCont table tbody').html('<tr><td class="text-center" colspan="2"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#AcctCont table tbody').load('../../proc/views/JE/vw_acctlist-load.php?srchval=' + encodeURI(searchVal));
	});
	//End Search Acct

	//Acct Code Bind
	$(document.body).on('blur','#tblDetails .acctcode',function()
	{
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
		else if(e.keyCode == 9 && e.ctrlKey)
		{
	    	$('#BPModal').modal('show');
	    	e.preventDefault();
	    }
		
		else if(e.keyCode == 13 && e.ctrlKey)
		{
	    	$('#btnSave').trigger('click');
		
			$('.btnyes').focus();
			e.preventDefault();
			
			
	    }
	    //e.preventDefault(); // prevent the default action (scroll / move caret)
	});

	//End Find Document

	//Load Documents
	$('#DocumentModal').on('shown.bs.modal',function()
	{
		$('#DocumentCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#DocumentCont').load('../../proc/views/JE/vw_doclist.php?servicetype=' + encodeURI(servicetype),function()
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
		                        url: '../../proc/views/JE/vw_doclist-load.php',
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
        $('#DocumentCont table tbody').load('../../proc/views/JE/vw_doclist-load.php?srchval=' + encodeURI(searchVal));
	});
	//End Search Document


	//Select Document Table Row Click
	$(document.body).on('dblclick','#tblDocument tbody > tr',function()
	{
		var docentry = $(this).children('td.item-1').text();
		
		$('#DocumentModal').modal('hide');

		$('input[name=txtDocEntry]').val(docentry);
		$('input[name=txtDocEntry]').trigger('keyup');
	});
	//End Select Document Table Row Click

		//Load Documents
	$('#RecurringModal').on('shown.bs.modal',function()
	{
		$('#RecurringCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#RecurringCont').load('../../proc/views/JE/vw_doclist-recurring.php?servicetype=' + encodeURI(servicetype),function()
		{
		    $('#RecurringCont .table-responsive').bind('scroll', function()
			{
		        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight)
				{
		            if($(this).scrollTop() > 0)
					{
						var itemcode = $('#RecurringCont table tbody > tr:last').children('td').eq(0).text();
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
		                        url: '../../proc/views/JE/vw_doclist-load-recurring.php',
		                        data: {
									itemcode : itemcode
								},
		                        success: function(html)
								{
									$('#RecurringCont table tbody').append(html);                
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
		$('input[name=RecurringSearch]').focus();
	});

	//Clear Document List
	$('#RecurringModal').on('hide.bs.modal',function()
	{
		$('#RecurringCont').empty();
	});
	//End Clear Document List

	//Add Keypress on DOcument MOdal
	$('#RecurringModal').keydown(function(e) 
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
		        	if($('#tblRecurring tbody').find('tr.selected-whs').index() >= 0){
		        		$('tr.selected-whs').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblRecurring tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;
		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//Highlight Document Table Row Click
	$(document.body).on('click','#tblRecurring tbody > tr',function(e)
	{
		highlight('#tblRecurring',this);
	});
	//End Highlight Document Table Row Click

	//Search Document
	$(document.body).on('keyup','input[name=RecurringSearch]',function()
	{
		var searchVal = $(this).val().toLowerCase();
		
        $('#RecurringCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#RecurringCont table tbody').load('../../proc/views/JE/vw_doclist-load-recurring.php?srchval=' + encodeURI(searchVal));
	});
	//End Search Document


	//Select Document Table Row Click
	$(document.body).on('dblclick','#tblRecurring tbody > tr',function()
	{
		var docentry = $(this).children('td.item-1').text();
		
		$('#RecurringModal').modal('hide');

		$('input[name=txtRecurring]').val(docentry);
		$('input[name=txtRecurring]').trigger('keyup');
	});
	//End Select Document Table Row Click
	
	$(document.body).on('keyup','input[name=txtRecurring]',function()
	{
		var docentry = $(this).val();
		
		$('#modal-load-init').modal('show');
		
		$.getJSON('../../proc/views/JE/vw_getdocumentdata.php?docentry=' + docentry, function(data) 
		{
			$('#modal-load-init').modal('show');
            $.each(data, function(key, val) 
			{
				/* if(val.AutoWT == 'Y')
				{
					$('input[name=txtManageWTax]').prop('checked',true);
					$('input[name=txtManageWTax]').prop('disabled',false);
				}
				else
				{
					$('input[name=txtManageWTax]').prop('checked',false);
				}
				
				if(val.AutoVAT == 'Y')
				{
					$('input[name=txtAutomaticTax]').prop('disabled',false);
					$('input[name=txtAutomaticTax]').prop('checked',true);
				}
				else
				{
					$('input[name=txtAutomaticTax]').prop('disabled',true);
					$('input[name=txtAutomaticTax]').prop('checked',false);
				} */
			});
				
			setTimeout(function()
			{
				populaterecurring(docentry,function()
				{
					$('#tblDetails tbody tr').each(function(i) 
					{
						$(this).closest('tr').addClass('selected-det');
						//var taxgroup = $(this).find('select.taxgroup').val();
						/* alert(taxgroup);
						if(taxgroup != '')
						{
							$(this).find('select.taxgroup').html('<option>Loading...</option>');
							$(this).find('select.taxgroup').load('../../proc/views/JE/vw_taxgroup.php', function()
							{
								//$(this).find('select.taxgroup').val('IVAT-N');
								//$("#someselect option[value=somevalue]").prop("selected", true)
								//$(this).find('select.taxgroup option').val('IVAT-N').attr("selected",true);
								//$('input[name=txtOwner]').val(val.employeename).prop('disabled', true);
								//$('.selected-det').find('select.taxgroup').val('IVAT-N')​.attr('selected', true)​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​;
								//$('.selected-det').find('select.taxgroup option[value="' + taxgroup + '"]').attr("selected",true);
								//$("#cars option[value='" + make + "']").attr("selected","selected");
								
								//$('#select1').append(`<option value="${optionValue}">
                                   //    ${optionText}
                                  //</option>`);
								
								$(this).find('select.taxgroup').append('<option value="IVAT-N">IVAT-N</option>');
							});
						} */
						
						$(this).find('input.debit').trigger('keyup');
						$(this).find('input.credit').trigger('keyup');
						$(this).removeClass('selected-det');
					});
					
					$('#modal-load-init').modal('hide');
				});
			},500);
        });
	});

	//Populate Data
	$(document.body).on('keyup','input[name=txtDocEntry]',function()
	{
		var docentry = $(this).val();
		
		
		$.getJSON('../../proc/views/JE/vw_getdocumentdata.php?docentry=' + docentry, function(data) 
		{
			$('#modal-load-init').modal('show');
            $.each(data, function(key, val) 
			{
				$('input[name=txtDocNo]').val(val.TransId).prop('disabled',true);
				$('input[name=txtRefDate]').val(val.RefDate).prop('disabled',true);
				$('input[name=txtDueDate]').val(val.DueDate).prop('disabled',false);
				$('input[name=txtTaxDate]').val(val.TaxDate).prop('disabled',true);
				
				if(val.AutoWT == 'Y')
				{
					$('input[name=txtManageWTax]').prop('checked',true);
				}
				else
				{
					$('input[name=txtManageWTax]').prop('checked',false);
				}
				
				if(val.AutoVAT == 'Y')
				{
					$('input[name=txtAutomaticTax]').prop('disabled',true);
					$('input[name=txtAutomaticTax]').prop('checked',true);
				}
				else
				{
					$('input[name=txtAutomaticTax]').prop('disabled',true);
					$('input[name=txtAutomaticTax]').prop('checked',false);
				}
				
				$('input[name=txtRefNo]').val(val.Ref1).prop('disabled', false);
				$('textarea[name=txtRemarks]').val(val.Memo).prop('disabled', false);
				
				if(val.CANCELED == 'Y')
				{
					$('#btnCancelDoc').prop('disabled',true);
				}
				else
				{
					$('#btnCancelDoc').prop('disabled',false);
				}
					
				$('#btnPrint').prop('disabled',false);
				
				$('#btnAddRow').addClass('hidden');
				$('#btnDelRow').addClass('hidden');
				$('#btnUpdate').removeClass('hidden');
				$('#btnSave').addClass('hidden');
			});
				
			setTimeout(function()
			{
				populatedet(docentry,function()
				{
					$('#tblDetails tbody tr').each(function(i) 
					{
						$(this).closest('tr').addClass('selected-det');
						$(this).find('input.debit').trigger('keyup');
						$(this).find('input.credit').trigger('keyup');
						$(this).removeClass('selected-det');
					});
					
					$('#modal-load-init').modal('hide');
				});
			},500);
        });
	});
	
	//Load Business Partner
	$('#BPModal').on('shown.bs.modal',function(){
		
		$('#BPCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#BPCont').load('../../proc/views/JE/vw_bplist.php?CardType=S',function(){
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
                                url: '../../proc/views/JE/vw_bplist-load.php',
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
	$('#BPModal').on('hide.bs.modal',function()
	{
		$('#BPCont').empty();
	});
	//End Clear Business Partner Data

	//Add Keypress on Business Partner MOdal
	$('#BPModal').keydown(function(e) 
	{
		switch(e.which) 
		{
			case 13: //Enter
				$('tr.selected-bp').trigger('dblclick');
			break;
			case 37: // left
			break;

			case 38: // up
				$('tr.selected-bp').prev().trigger('click');
			break;

			case 39: // right
			break;

			case 40: // down
				var index = $('tr.selected-bp').index();
				
				if($('#tblBP tbody').find('tr.selected-bp').index() >= 0)
				{
					$('tr.selected-bp').next().trigger('click');
				}
				else
				{
					$('#tblBP tbody > tr:first').trigger('click');
				}
				break;

			default: return; // exit this handler for other keys
		}
		e.preventDefault(); // prevent the default action (scroll / move caret)
	});
	//End Add Keypress on Business Partner Modal

	//Highlight Item Table Row Click
	$(document.body).on('click','#tblBP tbody > tr',function(e)
	{
		highlight('#tblBP',this);
	});
	//End Highlight Item Table Row Click

	//Select Acct Table Row Click
	$(document.body).on('dblclick','#tblBP tbody > tr',function()
	{
		var BPCode = $(this).children('td.item-1').text();
		var BPName = $(this).children('td.item-2').text();
		
		$('#BPModal').modal('hide');
		
		$('.selected-det').find('input.acctcode').val(BPCode);
		$('.selected-det').find('input.acctname').val(BPName);
		$('.selected-det').find('input.cat').val('BP');
		$('.selected-det').find('input.debit').focus();

		$('.selected-det').find('input.acctcode').attr('aria-acctcode',BPName);
		
	});
	//End Select Acct Table Row Click

	//Search BP
	$(document.body).on('keyup','input[name=BPSearch]',function(){

		var searchVal = $(this).val().toLowerCase();
		
        $('#BPCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#BPCont table tbody').load('../../proc/views/JE/vw_bplist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search BP


	//BP Code Bind
	$(document.body).on('blur','input[name=txtVendor]',function()
	{
		var bp = checkbpcode($(this).val()).split(';');
		//bp[0] - CardCode
		//bp[1] - CardName
		//bp[2] - Balance
		//bp[3] - Contact Person
		$('input[name=txtVendor]').val(bp[0]);
		$('input[name=txtName]').val(bp[1]);
		$('input[name=txtContactPerson]').val(bp[3]);
	});
	//End BP Code Bind

	//Print Document
	$(document.body).on('click','#btnPrint',function()
	{
		var docentry = $('input[name=txtDocEntry]').val();
		if(docentry != '')
		{
			window.open("../../report/JE/je-report.php?docentry=" + encodeURI(docentry), "", "width=1130,height=550,left=220,top=110");
		}
	});
	//End Print Document
	
	$(document.body).on('click','#btnNew',function(e)
	{
		location.reload();
	});
	
	$('#WTaxCodeModal').on('shown.bs.modal',function()
	{
		var BPCode = '';
		var debit = '';
		var credit = '';
		var amount = 0;
		var debitcredit = 0;
		
		$('#tblDetails tbody > tr ').each(function()
		{
			var $currentRow = $(this);
			var cat = $currentRow.find(".cat").val();
			var bp = $currentRow.find(".acctcode").val();
			var wtax = $currentRow.find(".wtax").val();
			
			if(wtax == 'Y')
			{
				debit = $currentRow.find(".debit").val().replace(/,/g, '');
				credit = $currentRow.find(".credit").val().replace(/,/g, '');
			}
			
			if(cat == 'BP')
			{
				BPCode = $currentRow.find(".acctcode").val();
			}
		});
		
		if(debit != 0 || debit != '')
		{
			amount = debit;
			debitcredit = 1;
		}
		else
		{
			amount = credit;
			debitcredit = 0;
		}
		
		$('#WTaxCodeCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#WTaxCodeCont').load('../../proc/views/JE/vw_withholdingtaxtable.php?BPCode='+encodeURI(BPCode)+'&amount='+encodeURI(amount)+'&debitcredit='+encodeURI(debitcredit));
		
		$('input[name=WTaxCodeSearch]').focus();
			
	});
	
	$('#WTaxCodeModal').on('hide.bs.modal',function()
	{
		$('#WTaxCodeCont').empty();
	});
	
	$(document.body).on('dblclick', '#tblWTaxCode tbody > tr', function () 
	{
		var account = $(this).children('td.item-6').text();
		var accountname = $(this).children('td.item-7').text();
		var amount = $(this).children('td.item-8').text();
		var debitcredit = $(this).children('td.item-9').text();
		var rowno = 0;
		
		rowno = ($('#tblDetails tbody tr:last').find('td.rowno').text() == '')? 1 : parseFloat($('#tblDetails tbody tr:last').find('td.rowno').text()) + 1;
		
		$('#btnAddRow').load('../../proc/views/JE/vw_addwtaxrow.php',function(result)
		{
			$('#tblDetails tbody').append(result);
			$('#tblDetails tbody tr:last').find('td.rowno').html(rowno);
			$('#tblDetails tbody tr:last').find('input.acctcode').val(account);
			$('#tblDetails tbody tr:last').find('input.acctname').val(accountname);
			
			if($('input[name=txtAutomaticTax]').prop('checked') == true)
			{
				$('#tblDetails tbody tr:last').find('select.taxgroup').html('<option>Loading...</option>');
				$('#tblDetails tbody tr:last').find('select.taxgroup').load('../../proc/views/JE/vw_taxgroup.php');
			}
			
			if($('input[name=txtAutomaticTax]').prop('checked') == true)
			{
				$('#tblDetails tbody tr:last').find('select.wtax').html('<option>Loading...</option>');
				$('#tblDetails tbody tr:last').find('select.wtax').load('../../proc/views/JE/vw_wtax.php');
			}
				
			if(debitcredit == 1)
			{
				$('#tblDetails tbody tr:last').find('input.credit').val(amount);
			}
			else
			{
				$('#tblDetails tbody tr:last').find('input.debit').val(amount);
			}
			
			$('.selected-det').find('input.debit').trigger('keyup');
			$('.selected-det').find('input.credit').trigger('keyup');
		});
		
		$('#WTaxCodeModal').modal('hide');
		$('#btnAddRow').empty();
	});
	
	$(document.body).on('click','#btnJEListVIEW',function(e)
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
				url: '../../report/je/rpt_view.php',
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
				url: '../../report/je/rpt_view.php',
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
	
	$(document.body).on('click','#btnJEListPDF',function(e)
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
			window.open("../../report/je/jelist-report.php?txtRefListFrom=" + encodeURI(txtRefListFrom) + "&txtRefListTo=" + encodeURI(txtRefListTo) + "&txtDateFrom=" + encodeURI(txtDateFrom) + "&txtDateTo=" + encodeURI(txtDateTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });
	
	$(document.body).on('click','#btnJEListEXCEL',function(e)
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
			window.open("../../report/je/jelist-excel.php?txtRefListFrom=" + encodeURI(txtRefListFrom) + "&txtRefListTo=" + encodeURI(txtRefListTo) + "&txtDateFrom=" + encodeURI(txtDateFrom) + "&txtDateTo=" + encodeURI(txtDateTo), "", "width=1130,height=550,left=220,top=110");
		}
		else
		{
			notie.alert(3, errmsg, 2);
		}	
    });
	
    $(document.body).on('click','#btnSaveJE',function(e)
	{
    	var err = 0;
    	var errmsg = '';
    	var txtRefNo = $('input[name=txtRefNo]').val();
    	var txtRemarks = $('textarea[name=txtRemarks]').val();
    	var txtRefDate = $('input[name=txtRefDate]').val();
    	var txtDueDate = $('input[name=txtDueDate]').val();
    	var txtTaxDate = $('input[name=txtTaxDate]').val();
		var txtAutomaticTax = $('input[name=txtAutomaticTax]').prop('checked') == true ? 1 : 0;
		var txtManageWTax = $('input[name=txtManageWTax]').prop('checked') == true ? 1 : 0;
		
		var countwtax = 0;
		var countwtaxindex = 0;
		
		$('#tblDetails tbody > tr ').each(function()
		{
			var $currentRow = $(this);
			var wtax = $currentRow.find(".wtax").val();
			var cat = $currentRow.find(".cat").val();
			var wtaxindex = $currentRow.find(".wtaxindex").val();
			
			if(cat == 'BP')
			{
				var BPCode = $currentRow.find(".acctcode").val();
			}
			else
			{
				var BPCode = '';
			}
			
			if(wtax == 'Y')
			{
				countwtax+=1;		
			}
			
			if(wtaxindex == 1)
			{
				countwtaxindex+=1;		
			}
		});
		
		if(countwtax != 0 && countwtaxindex == 0)
		{
			$('#WTaxCodeModal').modal('show');
		}
		else
		{
		
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
			
			var json = '{';
			var otArr = [];
			var tbl2 = $('#tblDetails tbody tr').each(function(i) 
			{  
				x = $(this).children();
				var itArr = [];
				
				if($(this).find('input.lineno').val() == 0)
				{
					
					itArr.push('"' + $(this).find('input.acctcode').val() + '"');
					itArr.push('"' + $(this).find('input.debit').val().replace(/,/g,'') + '"');
					itArr.push('"' + $(this).find('input.credit').val().replace(/,/g,'') + '"');						
					itArr.push('"' + $(this).find('input.departmentcode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.projectcode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.employeecode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.equipmentcode').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.cat').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('select.taxgroup').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('select.wtax').val().replace(/,/g, '') + '"');
					itArr.push('"' + $(this).find('input.linememo').val().replace(/,/g, '') + '"');
					
					otArr.push('"' + i + '": [' + itArr.join(',') + ']'); 
				}
			});
			
			json += otArr.join(",") + '}';
			
			if(err == 0)
			{
				$('#modal-load-init').modal('show');
			
				$.ajax({
					type: 'POST',
					url: '../../proc/exec/JE/exec-save.php',
					data: {
							json : json.replace(/(\r\n|\n|\r)/gm, ''),
							txtRefNo : txtRefNo,
							txtRemarks : txtRemarks,
							txtRefDate : txtRefDate,
							txtDueDate : txtDueDate,
							txtTaxDate : txtTaxDate,
							txtAutomaticTax : txtAutomaticTax,
							txtManageWTax : txtManageWTax
					},
					success: function(html)
					{
						res = html.split('*');
						if(res[0] == 'true')
						{
							notie.alert(1, res[1], 10);
							
							disablebuttons(true)
							setTimeout(function(){
								location.replace('../../forms/JE/JE.php');
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
		}
    });
	
	$(document.body).on('click','#btnUpdate',function(e)
	{
    	var err = 0;
    	var errmsg = '';
    	var txtDocEntry = $('input[name=txtDocEntry]').val();
    	var txtRefNo = $('input[name=txtRefNo]').val();
    	var txtRemarks = $('textarea[name=txtRemarks]').val();
    	var txtRefDate = $('input[name=txtRefDate]').val();
    	var txtDueDate = $('input[name=txtDueDate]').val();
    	var txtTaxDate = $('input[name=txtTaxDate]').val();
    	
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
    	
    	var json = '{';
		var otArr = [];
		var tbl2 = $('#tblDetails tbody tr').each(function(i) 
		{  
			x = $(this).children();
			var itArr = [];
			
			itArr.push('"' + $(this).find('input.acctcode').val() + '"');
			itArr.push('"' + $(this).find('input.debit').val().replace(/,/g,'') + '"');
			itArr.push('"' + $(this).find('input.credit').val().replace(/,/g,'') + '"');						
			itArr.push('"' + $(this).find('input.departmentcode').val().replace(/,/g, '') + '"');
			itArr.push('"' + $(this).find('input.projectcode').val().replace(/,/g, '') + '"');
			itArr.push('"' + $(this).find('input.employeecode').val().replace(/,/g, '') + '"');
			itArr.push('"' + $(this).find('input.equipmentcode').val().replace(/,/g, '') + '"');
			
			otArr.push('"' + i + '": [' + itArr.join(',') + ']'); 
		     
		});
		
		json += otArr.join(",") + '}';
		
		if(err == 0)
		{
    		$('#modal-load-init').modal('show');
	    
			$.ajax({
                type: 'POST',
                url: '../../proc/exec/JE/exec-update.php',
                data: {
                		json : json.replace(/(\r\n|\n|\r)/gm, ''),
                		txtDocEntry : txtDocEntry,
                		txtRefNo : txtRefNo,
                		txtRemarks : txtRemarks,
                		txtRefDate : txtRefDate,
                		txtDueDate : txtDueDate,
                		txtTaxDate : txtTaxDate
                },
                success: function(html)
				{
					res = html.split('*');
					if(res[0] == 'true')
					{
						notie.alert(1, res[1], 10);
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/JE/JE.php');
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
    $(document.body).on('click','#btnCancelJE',function(e)
	{
    	
		var err = 0;
    	var errmsg = '';
		var docentry = $('input[name=txtDocEntry]').val();
    	
    	$('.required').each(function()
		{
    		if($(this).val() == '')
			{
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
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/JE/exec-cancel.php',
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
							location.replace('../../forms/JE/JE.php');
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
	
	//Add Rows for Population
	function populatedet(docentry,callback)
	{
		$('#tblDetails tbody').load('../../proc/views/JE/vw_documentdetailsdata.php?docentry=' + docentry,function(result)
		{
			callback();
		});
	}
	
	function populaterecurring(docentry,callback)
	{
		$('#tblDetails tbody').load('../../proc/views/JE/vw_documentdetailsdata-recurring.php?docentry=' + docentry,function(result)
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
		var debit = $('.selected-det').find('input.debit').val().replace(/,/g, '');
		var taxrate = $('.selected-det').find('select.taxgroup option:selected').attr('val-rate');
		var itemindex = $('.selected-det').find('input.item_index').val();
		
		var taxamount = debit * (taxrate/100);
		
		$('#tblDetails tbody > tr ').each(function()
		{
			var $currentRow = $(this);
			var $lineno = $currentRow.find(".lineno").val();
			var $itemindex1 = $currentRow.find(".item_index").val();
			
			if(itemindex == $itemindex1 && $lineno == 1)
			{
				if(taxamount == 0)
				{
					$currentRow.find(".debit").val('');
				}
				else
				{
					$currentRow.find(".debit").val(formatMoney(taxamount));
				}
			}
		});
		
		$('input[name=txtDebit]').val(totaldebit);
	});
	
	$(document.body).on('blur','.debit',function()
	{
		var debit = $('.selected-det').find('input.debit').val().replace(/,/g, '') * 1;
		
		if(debit == '' || debit == 0)
		{
			$('.selected-det').find('input.debit').val('');
		}
		else
		{
			$('.selected-det').find('input.debit').val(formatMoney(debit));
		}
	});
	
	$(document.body).on('keyup','.credit',function()
	{
		var totalcredit = computeTotal('credit');
		var credit = $('.selected-det').find('input.credit').val().replace(/,/g, '');
		var taxrate = $('.selected-det').find('select.taxgroup option:selected').attr('val-rate');
		var itemindex = $('.selected-det').find('input.item_index').val();
		
		var taxamount = credit * (taxrate/100);
		
		$('#tblDetails tbody > tr ').each(function()
		{
			var $currentRow = $(this);
			var $lineno = $currentRow.find(".lineno").val();
			var $itemindex1 = $currentRow.find(".item_index").val();
			
			if(itemindex == $itemindex1 && $lineno == 1)
			{
				if(taxamount == 0)
				{
					$currentRow.find(".credit").val('');
				}
				else
				{
					$currentRow.find(".credit").val(formatMoney(taxamount));
				}
			}
		});
		
		$('input[name=txtCredit]').val(totalcredit);
	});
	
	$(document.body).on('blur','.credit',function()
	{
		var credit = $('.selected-det').find('input.credit').val().replace(/,/g, '') * 1;
		
		if(credit == '' || credit == 0)
		{
			$('.selected-det').find('input.credit').val('');
		}
		else
		{
			$('.selected-det').find('input.credit').val(formatMoney(credit));
		}
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