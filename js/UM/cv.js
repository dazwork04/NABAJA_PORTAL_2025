$(window).load(function() {
$('#window-title').text('Change Void Password');
$('#mod-title').text('Change Void Password');
})//end window.load

	 $(document.body).on('click','#btnChangePass1',function(e){
    	
		var err = 0;
		
    	var cpassword = $('input[name=UserPass1]').val();
    	
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
                url: '../../proc/exec/UM/exec-savecv.php',
                data: {
            			cpassword : cpassword
						
				},
                success: function(html){
					
					res = html.split('*');
					if(res[0] == 'true'){
						//Alert Success
						notie.alert(1, res[1], 3);
						setTimeout(function(){
							location.replace('../../forms/UM/CV.php');
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
		}
    })
    //End Save PO

	