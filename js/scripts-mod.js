//Alert Function
function showAlert(itemClass, itemMessage, hideAlert){
   	var item = '<div class="alert alert-dismissable ' + itemClass + '"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + itemMessage + '</div>';
    $('div#alert-content').html(item);

    if(hideAlert){
    	//Hide alert after 5 seconds
    	setTimeout(function(){
    		$('div#alert-content div.alert').addClass('bounceOut');
    	}, 5000);
    	//Empty the div after the effect
    	setTimeout(function(){
    		$('div#alert-content').html('');
    	}, 6000);
    }

}

//Reset Form
function resetForm(itemSelector){
   $(itemSelector).data('bootstrapValidator').resetForm(true);
}

//Show Modal //STILL DBUGING
function modalPref(modalName, modalID, modalActive){
	$(modalID + ' h4.modal-title').html(modalName);
    //$(modalID).modal(modalActive);
}





//showAlert('has-error', 'Error!', true) sample