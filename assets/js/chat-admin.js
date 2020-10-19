( function( $ ) {
	var timeout;

	var uid=blog.id;  // current admin/ user id
	var receiver_id =  blog.receiver_id;
	var user_role = blog.user_role;

	//var data_validate = [uid,receiver_id];
	var data_validate = [receiver_id,uid,user_role];
	//console.log(data_validate);
	// alert(uid);
	// alert(receiver_id);
	socket.emit('validate',data_validate);
	
	$('.msg_box').keypress(function(event){

		//alert($(".msg_box").val());
		if ( event.which == 13 ) {        
        	data={
        		id:uid,
        		sender_id : 1001,
        		receiver_id: receiver_id,
        		msg:$(".msg_box").val()
	        };
	        //console.log(data);
	        socket.emit('send msg',data);
         	$(".msg_box").val('');
        }
        else{
			console.log('happening');
			typing = true;
			socket.emit('typing', 'typing...');
			clearTimeout(timeout);
			timeout = setTimeout(timeoutFunction, 500);
        }
    });


	/*socket.on('validate message', (data) => {
		console.log(data);
		console.log('User Id' + data['user_id']);
		console.log('Receiver Id' + data['receiver_id']);
		//$('.user_typing').empty();
	});*/




	// Whenever the server emits 'stop typing', kill the typing message
	socket.on('stop typing', (data) => {
		$('.user_typing').empty();
	});

	function timeoutFunction() {
		typing = false;
		socket.emit("stop typing");
	}

	socket.on('typing', function(data) {
		//console.log(data);
		// console.log(data.username);
		if (data) {
			$('.user_typing').text(data.username + " is typing");
		} 
		else {
			$('.user_typing').text("");
		}
	});

	

	socket.on('user entrance',function(data){
		if(uid!="0"){
			console.log(data);
			$(".msg_history").append("<div class='col-xs-12'><h2><center>"+data.info+"</center></h2></div>");
			//console.log(data.message);
			for(var i=0;i<data.message.length;i++){
				if(data.message[i]['sender_id']==1001){
					$(".msg_history").append(
						'<div class="outgoing_msg">'+
							'<div class="sent_msg">'+
								'<p>'+data.message[i].message+'</p>'+
							'</div>'+
						'</div>'	
						);
					
				}else{
					$(".msg_history").append(

						'<div class="incoming_msg">'+
							'<div class="incoming_msg_img">'+ 
								'<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">'+ 
							'</div>'+
							'<div class="received_msg">'+
								'<div class="received_withd_msg">'+
									'<p>'+data.message[i].message+'</p>'+
								'</div>'+
							'</div>'+
						'</div>'	
						
						);
					 
				}
			}
			$(".msg_history").animate({scrollTop: $(".msg_history").get(0).scrollHeight},900);			
		
		}		 
	
	}); 

	socket.on('exit',function(data){
	    if( (data.message).trim() != "undefined"){
	    	$(".msg_history").append("<div class='col-xs-12'><h2><center>"+data.message+" is offline</center></h2></div>");
	    }
	});



	socket.on('get msg',function(data){
		//to scroll the div
		//console.log(data);
		$(".msg_history").animate({scrollTop: $(".msg_history").get(0).scrollHeight},900);
	
		if(data.sender_id==1001){
				$(".msg_history").append(
					'<div class="outgoing_msg">'+
						'<div class="sent_msg">'+
							'<p>'+data.message+'</p>'+
						'</div>'+
					'</div>'	
					);
				
			}else{
				$(".msg_history").append(

					'<div class="incoming_msg">'+
						'<div class="incoming_msg_img">'+ 
							'<img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">'+ 
						'</div>'+
						'<div class="received_msg">'+
							'<div class="received_withd_msg">'+
								'<p>'+data.message+'</p>'+
							'</div>'+
						'</div>'+
					'</div>'	
					
					);
				 
			}


	});





} )( jQuery );


