$(window).load(function() {


})//end window.load

function reload() {
    location.reload();
}

//Select Item Table Row Click
$(document.body).on('dblclick','#tblUsersPortal tbody > tr',function(){

	var UserId = $(this).children('td.item-0').text();
	var Name = $(this).children('td.item-1').text();
	var UserCode = $(this).children('td.item-2').text();
	//var UserPass = $(this).children('td.item-3').text();
	var Department = $(this).children('td.item-4').text();
	var SapUser = $(this).children('td.item-5').text();
	var SapPass = $(this).children('td.item-6').text();
	var Status = $(this).children('td.item-7').text();
	var Module = $(this).children('td.item-8').text();
	var position = $(this).children('td.item-9').text();
	var manufacturer = $(this).children('td.item-10').text();
	var empid = $(this).children('td.item-14').text();
	var Manu = $(this).children('td.item-15').text();
	var ToEmail = $(this).children('td.item-16').text();
	var PerEmail = $(this).children('td.item-17').text();
	var ShowDetails = $(this).children('td.item-18').text();
	
	$('input[name=txtUserId]').val(UserId);
	$('input[name=txtName]').val(Name);
	$('input[name=UserCode]').val(UserCode);
	//$('input[name=UserPass]').val(UserPass);
	$('select[name=Department]').val(Department);
	$('input[name=SapCode]').val(SapUser);
	$('input[name=SapPass]').val(SapPass);
	$('select[name=Status]').val(Status);
	$('select[name=Position]').val(position);
	$('select[name=Manufacturer]').val(manufacturer);
	$('input[name=txtEmployeeId]').val(empid);
	$('textarea[name=txtToEmail]').val(ToEmail);
	$('textarea[name=txtPerEmail]').val(PerEmail);
	$('select[name=selDatabase]').val(Manu);
	$('select[name=selShowDetails]').val(ShowDetails);
	
	$('#txtUserId').prop('disabled', true);
	$('#UM').prop('checked', false);
	$('#PR').prop('checked', false);
	$('#PO').prop('checked', false);
	$('#GRPO').prop('checked', false);
	$('#SO').prop('checked', false);
	$('#DR').prop('checked', false);
	$('#GI').prop('checked', false);
	$('#GR').prop('checked', false);
	$('#IT').prop('checked', false);
	$('#ITR').prop('checked', false);
	$('#PRAP').prop('checked', false);
	$('#PRAP2').prop('checked', false);
	$('#POAP').prop('checked', false);
	$('#SOAP').prop('checked', false);
	$('#SOAPP').prop('checked', false);
	$('#SOAPP2').prop('checked', false);
	$('#INV_RPT').prop('checked', false);
	$('#SC').prop('checked', false);
	$('#RSI').prop('checked', false);
	
	$('#-1').prop('checked', false);
	$('#1').prop('checked', false);
	$('#2').prop('checked', false);
	$('#3').prop('checked', false);
	$('#4').prop('checked', false);
	$('#5').prop('checked', false);
	$('#6').prop('checked', false);
	$('#7').prop('checked', false);
	$('#8').prop('checked', false);
	$('#9').prop('checked', false);
	
	Manu = Manu.split(',');
		
	var fLen, i;
	fLen = Manu.length;
	for (i = 0; i < fLen; i++) {
		$('#'+Manu[i]+'').prop('checked', true);
	}
	
	Module = Module.split(';');
		
	var fLen, i;
	fLen = Module.length;
	for (i = 0; i < fLen; i++) {
		$('#'+Module[i]+'').prop('checked', true);
	}
		
	document.getElementById('btnSave').style.visibility = 'hidden';
	document.getElementById('btnUpdate').style.visibility = 'visible';
	document.getElementById('btnRemove').style.visibility = 'visible';
	//End Details Item
})
//Select Item Table Row Click

$(document).ready(function() {
	
	$('#window-title').text('User Management');
	
	//Intialize Modal
	$('#modal-load-init').modal('show');

	//Global Variables
	var cback = 1;
	var activemod = '';
	var mode = 'Add';
	var servicetype = 'I';
	var activewhs = '';
	//End Global Variables

	document.getElementById('btnUpdate').style.visibility = 'hidden';
	document.getElementById('btnRemove').style.visibility = 'hidden';
	
	//Initialize Title
	$('#mod-title').text('User Management');
	//End Initialize Title

    //Load Department
    $('select[name=Department]').html('<option>Loading...</option>');
    $('select[name=Department]').load('../../proc/views/UM/vw_department.php',function(){

    })
    //ENd Load Department
	
	//Load Position
    $('select[name=Position]').html('<option>Loading...</option>');
    $('select[name=Position]').load('../../proc/views/UM/vw_position.php',function(){

    })
    //ENd Load Position
	
	//Load Manufacturer
    $('select[name=Manufacturer]').html('<option>Loading...</option>');
    $('select[name=Manufacturer]').load('../../proc/views/UM/vw_manufacturer.php',function(){

    })
    //ENd Load Manufacturer
    
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

	
	//Load Owner Data
    $('#EmployeeModal').on('shown.bs.modal', function () {

        $('#OwnerCont').html('<p class="text-center"><img src=../../img/ajax-loader.gif /> Loading...</p>');
        $('#OwnerCont').load('../../proc/views/UM/vw_employee.php?CardType=C', function () {
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
                                url: '../../proc/views/UM/vw_employee-load.php',
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
    $('#EmployeeModal').on('hide.bs.modal', function () {
        $('#OwnerCont').empty();

    })
    //End Clear Owner Data


    //Add Keypress on Owner MOdal
    $('#EmployeeModal').keydown(function (e) {
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
        $('input[name=txtName]').val(owner);
        $('input[name=txtEmployeeId]').val(employeecode);
        $('#EmployeeModal').modal('hide');
    })
    //End Select Acct Table Row Click

    //Search Owner
    $(document.body).on('keyup', 'input[name=OwnerSearch]', function () {

        var searchVal = $(this).val().toLowerCase();
        $('#OwnerCont table tbody').html('<tr><td class="text-center" colspan="7"><p><img src=../../img/ajax-loader.gif /></p></td></tr>');
        $('#OwnerCont table tbody').load('../../proc/views/UM/vw_employee-load.php?srchval=' + encodeURI(searchVal));
    })
    //End Search Owner
    
    //Owner Code bind
    $(document.body).on('blur', 'input[name=txtOwner]', function () {
        var owner = checkownercode($(this).val()).split(';');
        $('input[name=txtName]').val(owner[0]);
        $('input[name=txtEmployeeId]').val(owner[1]);
    });

    //End Owner modal

	//DELETE
	$(document.body).on('click','#btnRemove',function(e){
		
		var err = 0;
    	var errmsg = '';
		var userid = $('input[name=txtUserId]').val();
		
		if(err == 0){
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/UM/exec-deleteum.php',
                data: {
            			userid : userid

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
            console.log(res[1]);

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
	//DELETE
	
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
    	
    	var usercode = $('input[name=UserCode]').val();
    	var name = $('input[name=txtName]').val();
    	var empid = $('input[name=txtEmployeeId]').val();
    	var password = $('input[name=UserPass]').val();
    	
    	var department = $('select[name=Department]').val();
    	var position = $('select[name=Position]').val();
    	var manufacturer = $('select[name=Manufacturer]').val();
    	var sapuser = $('input[name=SapCode]').val();
    	var sappass = $('input[name=SapPass]').val();

    	var status = $('select[name=Status]').val();
    	var userid = $('input[name=txtUserId]').val();
    	var toemail = $('textarea[name=txtToEmail]').val();
    	var peremail = $('textarea[name=txtPerEmail]').val();
    	var selDatabase = $('select[name=selDatabase]').val();
    	var selShowDetails = $('select[name=selShowDetails]').val();
		
		var myMan = new Array();
		$('input[name="Manu[]"]:checked').each(function() {
			myMan.push($(this).val());
		});
		
		var myCheckboxes = new Array();
		$('input[name="Module[]"]:checked').each(function() {
			myCheckboxes.push($(this).val());
		});

    	
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
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/UM/exec-updateum.php',
                data: {
            			usercode : usercode,
						name : name,
						password : password,
						department : department,
						sapuser : sapuser,
						sappass : sappass,
						status : status,
						myCheckboxes : myCheckboxes,
						myMan : myMan,
						userid : userid,
						position : position,
						manufacturer : manufacturer,
						empid : empid,
						toemail : toemail,
						selDatabase : selDatabase,
						selShowDetails : selShowDetails,
						peremail : peremail

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
    	
    	var usercode = $('input[name=UserCode]').val();
    	var empid = $('input[name=txtEmployeeId]').val();
    	var name = $('input[name=txtName]').val();
    	var password = $('input[name=UserPass]').val();
    	
    	var department = $('select[name=Department]').val();
    	var position = $('select[name=Position]').val();
    	var manufacturer = $('select[name=Manufacturer]').val();
    	var sapuser = $('input[name=SapCode]').val();
    	var sappass = $('input[name=SapPass]').val();
    	var status = $('select[name=Status]').val();
    	var toemail = $('textarea[name=txtToEmail]').val();
		var peremail = $('textarea[name=txtPerEmail]').val();
		var peremail = $('textarea[name=txtPerEmail]').val();
		var selDatabase = $('select[name=selDatabase]').val();
		var selShowDetails = $('select[name=selShowDetails]').val();
		
		var myMan = new Array();
		$('input[name="Manu[]"]:checked').each(function() {
			myMan.push($(this).val());
		});

    	var myCheckboxes = new Array();
		$('input[name="Module[]"]:checked').each(function() {
			myCheckboxes.push($(this).val());
		});
    	

    	
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
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/UM/exec-saveum.php',
                data: {
            			myMan : myMan,
            			myCheckboxes : myCheckboxes,
                		usercode : usercode,
						name : name,
						password : password,
						department : department,
						sapuser : sapuser,
						sappass : sappass,
						status : status,
						position : position,
						manufacturer : manufacturer,
						empid : empid,
						toemail : toemail,
						selDatabase : selDatabase,
						selShowDetails : selShowDetails,
						peremail : peremail
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

	 $(document.body).on('click','#btnChangePass',function(e){
    	alert('change');
    	var cpassword = $('input[name=UserPass]').val();
    	
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
    		//Show Loading Modal
	    	$('#modal-load-init').modal('show');
	    	//End Show Loading Modal

    		//Save Data
    		$.ajax({
                type: 'POST',
                url: '../../proc/exec/UM/exec-savecp.php',
                data: {
            			cpassword : cpassword
						
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
							location.replace('../../forms/UM/CP.php');
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