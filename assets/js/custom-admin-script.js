jQuery(document).ready(function ($) {


    
	$(document).ready(function() {
	    $('#example').DataTable();
	} );
    

    /**  MODALS **/

    $(".modal-sendall").click(function(){
		$("#sendall").modal({
			backdrop: 'static',
			keyboard: false
		});

		
    });
      $(".modal-sendselected").click(function(){
		$("#sendtoselected").modal({
			backdrop: 'static',
			keyboard: false
		});

    });

	$('.single-user-id').change(function(e){
		e.preventDefault();
		var user_id =  $(this).val();
		if($(this).prop("checked")) {
			

			check_val(user_id);
		} else {
		
			uncheck_val(user_id);
			//alert('unchecked');
		}
	});

	/**  Checked Values Verification  **/
	function check_val(user_id){
		var selected_users = $('input[name=selected-users]').val();
		if(selected_users == 0){
			$('input[name=selected-users]').val(user_id);
		}
		else{
			var user_id = user_id;
			var new_selected_users = selected_users.split(","); // string to array

			var status = false;
			for(var i=0; i<new_selected_users.length; i++){
				var users_ids = new_selected_users[i];
				if(users_ids == user_id){
					status = true;
					break;
				}
			}

			if(status == true){
				return;
			}
			else{
				new_selected_users.push(user_id);
				var arr_to_str =  new_selected_users.toString();
			 	$('input[name=selected-users]').val(arr_to_str);
			}	
			//console.log(typeof selected_users);
		}	
	}
	/**  Un Checked Values Verification  **/
	function uncheck_val(user_id){
		var selected_users = $('input[name=selected-users]').val();
		var user_id = user_id;
		//alert(user_id);
		var new_selected_users = selected_users.split(","); // string to array
		var index = new_selected_users.indexOf(user_id);
		if (index > -1) {
			new_selected_users.splice(index, 1);

			if( new_selected_users.length == 0){
				$('input[name=selected-users]').val(0);
			}
			else{
				var arr_to_str =  new_selected_users.toString();
				$('input[name=selected-users]').val(arr_to_str);	
			}
		}			
	}

	/**   SEND FILES TO ALL USERS **/
	 $( "#send-files-selected" ).submit(function( e ) {

        e.preventDefault();
        var errors = 0;
   		var file_data = $('#admin-system-files').prop('files');   
		
   		if(file_data.length  == 0){
   			errors += 1;
   			$('#admin-system-files-err').text('Select At least one file to proceed');
   		}
   		else{
   			$('#admin-system-files-err').empty();
   		}

        if(errors == 0){   

        	// Files from admin
			// var form_data = new FormData(this);                  
			// form_data.append('file_admin', file_data);		
         	var selected_users = $('input[name=selected-users]').val();
         	if(selected_users == 0){
         		alert('Make Users Selection First');
         	}
         	else{
         		//alert('No errors');
         		$('input[name=selected_users]').val(selected_users);
				$.ajax({
					type: 'POST',
					url: adminpath.ajaxurl,
					data: new FormData(this),
					cache: false,
					contentType: false,
					processData: false,
					dataType: 'json',
					success: function (dataa) {
						//console.log(dataa);

						var status = dataa.status;
						if (status) {
							alert(dataa.error);
							window.location.href = dataa.redirect_url;
							
						} 
						else {
							alert(dataa.error);
						}
					},
					error: function (errorThrown) {
						console.log(errorThrown);
					}
				});


	

         	}
        }
  
    });





	/**   SEND FILES TO SELECTED USERS **/
	 $( "#send-files-all" ).submit(function( e ) {

        e.preventDefault();
        var errors = 0;
   		var file_data = $('#admin-system-allfiles').prop('files');   
		
   		if(file_data.length  == 0){
   			errors += 1;
   			$('#admin-system-allfiles-err').text('Select At least one file to proceed');
   		}
   		else{
   			$('#admin-system-allfiles-err').empty();
   		}

        if(errors == 0){   

     			var all_users = $('input[name=all-users]').val();
				$('input[name=all_users]').val(all_users);
				$.ajax({
					type: 'POST',
					url: adminpath.ajaxurl,
					data: new FormData(this),
					cache: false,
					contentType: false,
					processData: false,
					dataType: 'json',
					success: function (dataa) {
						//console.log(dataa);

						var status = dataa.status;
						if (status) {
							alert(dataa.error);
							window.location.href = dataa.redirect_url;
							
						} 
						else {
							alert(dataa.error);
						}
					},
					error: function (errorThrown) {
						console.log(errorThrown);
					}
				});


	

         	
        }
  
    });







    /**   MODAL FORMS SUBMIT **/
	$( "#send-all" ).submit(function( e ) {

		e.preventDefault();

		let message_admin = $("#all-message-admin").val();
		var errors = 0;
		if(message_admin == '' || message_admin.trim().length == 0){
			$('#all-message-err').text("No space please and don't leave it empty");
			errors += 1;
		}
		else{
			$('#all-message-err').empty();
		}

		if(errors == 0){   
			//alert('No errors');
			var all_users = $('input[name=all-users]').val();
		
			$('input[name=all_users]').val(all_users);
			var serialize_form = $(this).serialize();
			//alert(serialize_form);
			$.ajax({
				type:"POST",
				url: adminpath.ajaxurl,
				data: serialize_form,
				dataType : 'json',
				success:function(response) {
					//console.log(response);
					if(response.status){
						alert(response.error);
						window.location.href = response.redirect_url;

					} 
					else{
						alert(response.error);
					}
				},
			});
			
		}

	});

     $( "#send-to-selected" ).submit(function( e ) {

        e.preventDefault();

        let message_admin = $("#selected-message-admin").val();
        var errors = 0;
        if(message_admin == '' || message_admin.trim().length == 0){
            $('#selected-message-err').text("No space please and don't leave it empty");
            errors += 1;
        }
        else{
            $('#selected-message-err').empty();
        }
       
        if(errors == 0){   
        	//alert('No errors');
         	var selected_users = $('input[name=selected-users]').val();
         	if(selected_users == 0){
         		alert('Make Users Selection First');
         	}
         	else{
         		$('input[name=selected_users]').val(selected_users);
				var serialize_form = $(this).serialize();
				$.ajax({
					type:"POST",
					url: adminpath.ajaxurl,
					data: serialize_form,
					dataType : 'json',
					success:function(response) {
						//console.log(response);
						if(response.status){
							alert(response.error);
							window.location.href = response.redirect_url;

						} 
						else{
							alert(response.error);
						}
					},
				});

         	}

         
        }
   
    });

 	

});