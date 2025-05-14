$(document).ready(function() {
//*******************
//Public Variables  *
//*******************

var defserver = '';
var defDb = '';


defserver = getCookie('server');
defDb = getCookie('database');

//***********************
//End Public Variables  *
//***********************


//Check if server and database are set
if(defserver != ''){
}else{
  $('#server').removeClass('collapse');
  $('#server').addClass('collapse-in');
}
//End Check if server and database are set

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

    //Login Validation
    $('#sign-in-form').bootstrapValidator({
        feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
        },    
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'Username is required.'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Password is required.'
                    }
                }
            },

            dbList: {
                validators: {
                    notEmpty: {
                        message: 'Database is required.'
                    }
                }
            }

        },
        submitHandler: function(validator, form, submitButton) {
            var sqluser = $('select[name=ServerList] option:selected').attr('aria-sqluser');
            var sqlpass = $('select[name=ServerList] option:selected').attr('aria-sqlpass');
            var server = $('select[name=ServerList] option:selected').attr('aria-server');
            var dbver = $('select[name=ServerList] option:selected').attr('aria-dbver');
            var port = $('select[name=ServerList] option:selected').attr('aria-port');
            $.ajax({
                type: 'POST',
                url: 'proc/exec/exec-login-users.php',
                data: $('#sign-in-form').serialize() + '&sqluser=' + sqluser + '&sqlpass=' + sqlpass + '&server=' + server +
                        '&dbver=' + dbver + '&port=' + port,
                success: function(html){
                    
                  res = html.split('*');
                  if(res[0] == 'true'){

                    setCookie('server',$('select[name=ServerList]').val(),365);
                    setCookie('database',$('select[name=dbList]').val(),365);

                    //Alert Success
                    notie.alert(1, 'Successfull! Redirecting..', 3);
                    //End


                    setTimeout(function(){
                        window.location='home.php';
                    },2000)
                    
                   

                  }else{
                    //Alert when error
                    notie.alert(3, res[1], 3);
                    //End

                    //showAlert('alert-danger animated bounceIn', html);
                    //resetForm('#sign-in-form');
                  }

                },
                error: function(){
                  
                  showAlert('alert-danger animated bounceIn', 'Something went wrong!');

                },
                beforeSend:function(){
                  
                  showAlert('alert-success animated bounceIn', 'Authenticating. Please wait...');
                }
            });

            
        }
    });
    //End of Validation





    //Login Validation
    $('#server-form').bootstrapValidator({
        feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
        },    
        fields: {
            ServerName: {
                validators: {
                    notEmpty: {
                        message: 'Server Name is required.'
                    }
                }
            },
            Port: {
                validators: {
                    notEmpty: {
                        message: 'Port is required.'
                    }
                }
            },

            DBUser: {
                validators: {
                    notEmpty: {
                        message: 'DB Username is required.'
                    }
                }
            },


            DBPass: {
                validators: {
                    notEmpty: {
                        message: 'DB Password is required.'
                    }
                }
            }




        },
        submitHandler: function(validator, form, submitButton) {
            //Disable or input and button inside form
            $("#server-form :input,button").prop('readonly', true);
            //End
            $.ajax({
                type: 'POST',
                url: 'proc/exec/exec-reg-new-server.php',
                data: $('#server-form').serialize(),
                success: function(html){
                   res = html.split('*');
                    

                  if(res[0] == 'true'){
                    //Alert Success
                    notie.alert(1, 'Successfully Created!' + res[1], 3);
                    //End
                    
                    //Enable or input and button inside form
                    $("#server-form :input,button").prop('readonly', false);
                    //End

                    resetForm('#server-form');

                  }else{
                    

                    //Alert when error
                    notie.alert(3, res[1], 3);
                    //End

                    //Enable or input and button inside form
                    $("#server-form :input,button").prop('readonly', false);
                    //End

                    //showAlert('alert-danger animated bounceIn', html);
                    resetForm('#server-form');
                  }

                },
                error: function(){
                  showAlert('alert-danger animated bounceIn', 'Something went wrong!');
                },
                beforeSend:function(){
                  showAlert('alert-success animated bounceIn', 'Authenticating. Please wait...');
                }
            });

            
        }
    });
    //End of Validation


    //Load Server List
    $('select[name=ServerList]').html('<option>Loading...</option>');
    $('select[name=ServerList]').load('proc/views/vw_load-serverlist.php',function(){
        //Set Default value for Server
        if(defserver != ''){
            $('select[name=ServerList] option[value='+defserver+']').prop('selected',true).trigger("change");
        }
        
        //End Set Default value for Server
    })
    //End Load Server List


    //Server List on change
    $(document.body).on('change','select[name=ServerList]',function(){
        //Disable or input and button inside form
        $("#sign-in-form :input").prop('readonly', true);
        $("#sign-in-form :button,select").prop('disabled', true);
        
        //End
        id = $(this).val();
        //Load Database List
        $('select[name=dbList]').html('<option>Loading...</option>');
        $('select[name=dbList]').load('proc/views/vw_load-db.php?ID=' + id,function(){
            //Enable or input and button inside form
            $("#sign-in-form :input").prop('readonly', false);
            $("#sign-in-form :button").prop('disabled', false);
            $("#sign-in-form :button,select").prop('disabled', false);;
            
            //End

            //Set Default value for dbList
            if(defDb != ''){
                $('select[name=dbList] option[value='+defDb+']').prop('selected',true).trigger("change");
            }
            //End Set Default value for dbList

            
        })
        //End Load Database List
    })
    //End Server List on change





}) // End document.ready



