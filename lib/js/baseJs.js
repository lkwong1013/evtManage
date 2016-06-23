
function loginValidation(langType) {
	$.ajax({
	    type: 'post',
	    url: 'http://samaxxw.com/evtManage/'+langType+'/welcome/login',
	    data: $('form').serialize(),
	    success: function (msg) {
	    	 console.log(msg); 
	    	 msg = msg.replace(/\s/g, ''); 
	    	 if (msg == "login-failed") {
	    		 testBox("danger", "Login Failed",2000);
	    	 } else {
	    		 // Redirect to home page
	    		 window.location.replace(msg);
	    	 }
	    	
	//        if(msg == 1){
	//            //window.location.replace("home");
	//        }else if(msg==0){
	//            $('#login-err').html("Invalid User ID or Password!");
	//        }else{
	//           console.log(msg);   
	//        }
	        }
	    }); 

}

function msgBox(type, content, remainTime) {
	var boxId = Math.floor((Math.random() * 1000) + 1);
	var successContent = '<div class="alert alert-success box-'+boxId+'">'+
	    '<button type="button" class="close" data-dismiss="alert">x</button>'+
	    '<strong>Success! </strong>'+
	    '<span id="success-msg">'+content+'</span>'+
	    '</div>';
	var dangerContent = '<div class="alert alert-danger box-'+boxId+'">'+
	    '<button type="button" class="close" data-dismiss="alert">x</button>'+
	    '<strong>Warning! </strong>'+
	    '<span id="danger-msg">'+content+'</span>'+
	    '</div>';
	var reminderContent = '<div class="alert alert-danger box-'+boxId+'">'+
	    '<button type="button" class="close" data-dismiss="alert">x</button>'+
	    '<span id="danger-msg">'+content+'</span>'+
	    '</div>';
	var warningContent = '<div class="alert alert-warning box-'+boxId+'">'+
    '<button type="button" class="close" data-dismiss="alert">x</button>'+
    '<span id="danger-msg">'+content+'</span>'+
    '</div>';
	var infoContent = '<div class="alert alert-info box-'+boxId+'">'+
    '<button type="button" class="close" data-dismiss="alert">x</button>'+
    '<span id="danger-msg">'+content+'</span>'+
    '</div>';
		
	switch(type) {
		case "danger": $('#alertBoxContainer').append(dangerContent); 
		break;
		case "success": $('#alertBoxContainer').append(successContent);
		break;
		case "reminder": $('#alertBoxContainer').append(reminderContent);
		break;
		case "warning": $('#alertBoxContainer').append(warningContent);
		break;
		case "info": $('#alertBoxContainer').append(infoContent);
		break;		
		default: $('#alertBoxContainer').append(dangerContent);
		console.log("Alert box Error! Please Check!");
		break;
	}
	
	
//	$("#success-alert").alert();
	$(".box-"+boxId).fadeTo(remainTime, 500).slideUp(500, function(){
		//$('#alertBoxContainer').empty();	// Reset message after display
  });   
}


function testBox(type, content, remainTime) {
	msgBox(type, content, remainTime);
}



