$(window).load(function() {


})//end window.load


$(document).ready(function() {
	
	//Intialize Modal
	$('#modal-load-init').modal('show');

	//Global Variables
	var cback = 1;
	var activemod = '';
	var mode = 'Add';
	var servicetype = 'I';
	var activewhs = '';
	//End Global Variables

	//Initialize Title
	$('#mod-title').text('User Management');
	//End Initialize Title

    //Load Department
    $('select[name=cmbDepartment]').html('<option>Loading...</option>');
    $('select[name=cmbDepartment]').load('../../proc/views/UM/vw_department.php',function(){

    })
    //ENd Load Department
    
    //Load Roles
    $('select[name=cmbRole]').html('<option>Loading...</option>');
    $('select[name=cmbRole]').load('../../proc/views/UM/vw_roles.php',function(){

    })
    //ENd Load Roles


    //Load User Warehouse
    $('#ModDetails').html('<p class="text-center"><img src=../../img/ajax-loader.gif /></p>');
	$('#ModDetails').load('../../forms/UM/um-details.php',function(){
		
		cback = 0;
	});
    //ENd

	
	//=======================================================================================================
	//Javascript Code here
	//=======================================================================================================



 
	//Populate Data
	$(document.body).on('keyup','input[name=txtDocEntry]',function(){
		var docentry = $(this).val();
		
		//Get PR data using JSON
		$.getJSON('../../proc/views/UM/vw_getdocumentdata.php?docentry=' + docentry, function(data) {
            /* data will hold the php array as a javascript object */
            
            
           		$('#modal-load-init').modal('show');
           		//$('#tblDetails tbody').empty();	
	            $.each(data, function(key, val) {
	            	

	            	//Populate Header
            		$('input[name=txtUserCode]').val(val.UserCode).prop('disabled',true);
	            	$('input[name=txtName]').val(val.Name).prop('disabled',false);
	            	$('select[name=cmbDepartment]').val(val.Department).prop('disabled',false);
	            	$('input[name=txtSAPUser]').val(val.sapuser).prop('disabled',false);
	            	$('select[name=cmbUserType]').val(val.UserType).prop('disabled',false);
	            	$('select[name=cmbRole]').val(val.Roles).prop('disabled',false);

		            disablebuttons(true);
		            $('#btnUpdate').removeClass('hidden');
	            	//End Populate Header
	            	
	            })
				
				//Populate Details
	            setTimeout(function(){
	            	populatedet(docentry,function(){
		            	$('#modal-load-init').modal('hide');
	            		
	            	});
	            },500)
            	//End Populate Details

        });
		//End Get PR data using JSON
	})
	//End Populate Data

	
	//Load User
	$('#UserModal').on('shown.bs.modal',function(){

		$('#UserCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#UserCont').load('../../proc/views/UM/vw_userlist.php',function(){
			//Add Scroll Function 
		    $('#UserCont .table-responsive').bind('scroll', function(){
		        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
		              if($(this).scrollTop() > 0){
		                var userid = $('#UserCont table tbody > tr:last').children('td').eq(0).text();
		                var ctr = 0;

		                $('#itm-loader').each(function () {
		                  ctr += 1;
		                });
		                if(ctr == 0){
		                  $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		                	
		                  
		                  $.ajax({
		                        type: 'POST',
		                        url: '../../proc/views/UM/vw_userlist-load.php',
		                        data: 'userid=' + userid,
		                        success: function(html){

		                          $('#UserCont table tbody').append(html);                
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
	//End Load User

	//Clear Business Partner Data
	$('#UserModal').on('hide.bs.modal',function(){
		$('#UserCont').empty();

	})
	//End Clear Business Partner Data


	//Add Keypress on Business Partner MOdal
	$('#UserModal').keydown(function(e) {
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
		        	if($('#tblUser tbody').find('tr.selected-whs').index() >= 0){
		        		$('tr.selected-whs').next().trigger('click');

		        		//$('#WhsCont > .table-responsive').scrollTop(10);
		        	}else{
		        		$('#tblUser tbody > tr:first').trigger('click');
		        	}
		        	//End
		        break;



		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	//End Add Keypress on Business Partner Modal


	//Highlight User Table Row Click
	$(document.body).on('click','#tblUser tbody > tr',function(e){

		
		highlight('#tblUser',this);

	})
	//End Highlight User Table Row Click



	//Select User Table Row Click
	$(document.body).on('dblclick','#tblUser tbody > tr',function(){

		var UserID = $(this).children('td.item-1').text();
		var UserCode = $(this).children('td.item-2').text();
		var Name = $(this).children('td.item-3').text();
		var Department = $(this).children('td.item-4').text();
		var DepCode = $(this).children('td.item-5').text();
		$('input[name=txtUser]').val(UserCode);
		$('input[name=txtUserCode]').val(UserCode);
		$('input[name=txtName]').val(Name);
		$('select[name=cmbDepartment]').val(DepCode);
		$('#UserModal').modal('hide');


	})
	//End Select User Table Row Click

	//Search User
	$(document.body).on('keyup','input[name=UserSearch]',function(){

		var searchVal = $(this).val().toLowerCase();
        $('#UserCont table tbody').html('<tr><td class="text-center" colspan="4"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#UserCont table tbody').load('../../proc/views/UM/vw_userlist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search BP


	

	//Find Document
	$(window).keydown(function(e) {
		
	    if(e.keyCode == 70 && e.ctrlKey){
	    	//Ctrl + f
	    	$('#WebUserModal').modal('show');
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



	//Load User
	$('#WebUserModal').on('shown.bs.modal',function(){

		$('#WebUserCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		$('#WebUserCont').load('../../proc/views/UM/vw_doclist.php',function(){
			//Add Scroll Function 
		    $('#WebUserCont .table-responsive').bind('scroll', function(){
		        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
		              if($(this).scrollTop() > 0){
		                var userid = $('#WebUserCont table tbody > tr:last').children('td').eq(0).text();
		                var ctr = 0;

		                $('#itm-loader').each(function () {
		                  ctr += 1;
		                });
		                if(ctr == 0){
		                  $(this).append('<p class="text-center" id="itm-loader"><img src=../../img/ajax-loader.gif /> Loading...</p>');
		                	
		                  
		                  $.ajax({
		                        type: 'POST',
		                        url: '../../proc/views/UM/vw_doclist-load.php',
		                        data: 'userid=' + userid,
		                        success: function(html){

		                          $('#WebUserCont table tbody').append(html);                
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
	//End Load User

	//Clear Business Partner Data
	$('#WebUserModal').on('hide.bs.modal',function(){
		$('#WebUserCont').empty();

	})
	//End Clear Business Partner Data
	

	//Add Keypress on DOcument MOdal
	$('#WebUserModal').keydown(function(e) {
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
	$(document.body).on('keyup','input[name=WebUserSearch]',function(){
		var searchVal = $(this).val().toLowerCase();
        $('#WebUserCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#WebUserCont table tbody').load('../../proc/views/UM/vw_doclist-load.php?srchval=' + encodeURI(searchVal));
	})
	//End Search Document


	//Select Document Table Row Click
	$(document.body).on('dblclick','#tblDocument tbody > tr',function(){

		var docentry = $(this).children('td.item-1').text();
		
		$('#WebUserModal').modal('hide');

		$('input[name=txtDocEntry]').val(docentry);
		$('input[name=txtDocEntry]').trigger('keyup');
		
		
	})
	//End Select Document Table Row Click



	//Select All
	$(document.body).on('click','#btnSelect',function(){
		$('.whs').prop('checked',true);
	})
	//End Select All

	//Clear Selection
	$(document.body).on('click','#btnClear',function(){
		$('.whs').prop('checked',false);
	})
	//End Clear Selection






	//SAVING AREA
	//=============================================================

	//Update UM
    $(document.body).on('click','#btnUpdate',function(e){
    	var err = 0;
    	var errmsg = '';
    	
    	var usercode = $('input[name=txtUserCode]').val();
    	var name = $('input[name=txtName]').val();
    	var password = $('input[name=txtPassword]').val();
    	var rpassword = $('input[name=txtRepeatPassword]').val();

    	var department = $('select[name=cmbDepartment]').val();
    	var sapuser = $('input[name=txtSAPUser]').val();
    	var sappass = $('input[name=txtSAPPass]').val();

    	var usertype = $('select[name=cmbUserType]').val();
    	var role = $('select[name=cmbRole]').val();
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

    	if(err == 0){
	    	//Check if password match
	    	if(password != rpassword){
	    		$('input[name=txtPassword]').parent().addClass('has-error');
				$('input[name=txtRepeatPassword]').parent().addClass('has-error');
	    		err += 1;
	    		errmsg = 'Password mismatch!';

	    	}else{
	    		$('input[name=txtPassword]').parent().removeClass('has-error');
				$('input[name=txtRepeatPassword]').parent().removeClass('has-error');
	    	}

	    	//End Check if password match
	    }

    	if(err == 0){
		 	err += 1;
		    errmsg = 'Select Warehouse!';

		    //Collect Details
			var json = '{';
			var otArr = [];
			var tbl2 = $('.whs:checked').each(function(i) {  

			   
			        //x = $(this).children();
			        var itArr = [];
				    itArr.push('"' + $(this).attr('aria-whscode') + '"');
			    
			        otArr.push('"' + i + '": [' + itArr.join(',') + ']'); 
			        err = 0;
			     
			});
			//PARSE ALL SCRIPT
			json += otArr.join(",") + '}';
			//End Collect  Details
		}

		
		


    	if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/UM/exec-updateum.php',
                data: {
            			json : json.replace(/(\r\n|\n|\r)/gm, '[newline]'),
                		usercode : usercode,
						name : name,
						password : password,
						rpassword : rpassword,
						department : department,
						sapuser : sapuser,
						sappass : sappass,
						usertype : usertype,
						role : role,
						docentry : docentry

                },
                success: function(html){
					//alert(html);
					res = html.split('*');
					if(res[0] == 'true'){
						//Alert Success
						notie.alert(1, res[1], 10);
						//End

						//Refresh the page
						
						disablebuttons(true)
						setTimeout(function(){
							location.replace('../../forms/UM/UM.php');
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
	//End Update UM

	//Save PO
    $(document.body).on('click','#btnSave',function(e){
    	

    	var err = 0;
    	var errmsg = '';
    	
    	var usercode = $('input[name=txtUserCode]').val();
    	var name = $('input[name=txtName]').val();
    	var password = $('input[name=txtPassword]').val();
    	var rpassword = $('input[name=txtRepeatPassword]').val();

    	var department = $('select[name=cmbDepartment]').val();
    	var sapuser = $('input[name=txtSAPUser]').val();
    	var sappass = $('input[name=txtSAPPass]').val();

    	var usertype = $('select[name=cmbUserType]').val();
    	var role = $('select[name=cmbRole]').val();


    	

    	
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
	    	//Check if password match
	    	if(password != rpassword){
	    		$('input[name=txtPassword]').parent().addClass('has-error');
				$('input[name=txtRepeatPassword]').parent().addClass('has-error');
	    		err += 1;
	    		errmsg = 'Password mismatch!';

	    	}else{
	    		$('input[name=txtPassword]').parent().removeClass('has-error');
				$('input[name=txtRepeatPassword]').parent().removeClass('has-error');
	    	}

	    	//End Check if password match
	    }

    	if(err == 0){
		 	err += 1;
		    errmsg = 'Select Warehouse!';

		    //Collect Details
			var json = '{';
			var otArr = [];
			var tbl2 = $('.whs:checked').each(function(i) {  

			   
			        //x = $(this).children();
			        var itArr = [];
				    itArr.push('"' + $(this).attr('aria-whscode') + '"');
			    
			        otArr.push('"' + i + '": [' + itArr.join(',') + ']'); 
			        err = 0;
			     
			});
			//PARSE ALL SCRIPT
			json += otArr.join(",") + '}';
			//End Collect  Details
		}

		
		


    	if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/UM/exec-saveum.php',
                data: {
            			json : json.replace(/(\r\n|\n|\r)/gm, '[newline]'),
                		usercode : usercode,
						name : name,
						password : password,
						rpassword : rpassword,
						department : department,
						sapuser : sapuser,
						sappass : sappass,
						usertype : usertype,
						role : role

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
							location.replace('../../forms/UM/UM.php');
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
    //End Save PO

	//END SAVING AREA
	//=============================================================

	




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


	

	//Bind BP Code
	function checkbpcode(bpcode){
	  var result = '';
	   $.ajax({
	        type: 'POST',
	        url: '../../proc/views/SO/vw_checkbpcode.php',
	        async: false,
	        data: 'bpcode=' + bpcode,
	        success: function(html){

	          result = html;
	          
	        }

	    });
		
	  return result;

	}
	//End Bind BP Code




	//Disable buttons
	function disablebuttons(param){
		//Disable Buttons
		$('#btnSave').prop('disabled',param);

		//End Disable Buttons
		
	}
	//End Disable buttons





	//Add Rows for Population
	function populatedet(docentry,callback){

			
		$('#tblDetails tbody').load('../../proc/views/UM/vw_documentdetailsdata.php?docentry=' + docentry,function(result){
			

			callback();
			
			
		})
	
	}
	//End Add row for population


	


	//END FUNCTION AREA
	//=============================================================


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