jQuery(document).ready(function ($) {


    $( "#login-form" ).submit(function( e ) {
    //$(document).on('submit', '#login-form', function (e) {
        e.preventDefault();
        // alert("Hahaha working");

        let user_email = $("input[name='user_system_email']").val().trim();
        let user_password = $("input[name='user_system_password']").val().trim();
        let user_remember = $("input[name='user_system_remember']").is( ":checked" );
     
       /* alert(user_email);
        alert(user_password);
        alert(user_remember);*/
        
        var errors = 0;
        if(user_email == '' || user_email.trim().length == 0){
            $('#user_system_email_err').text("No space please and don't leave it empty");
            errors += 1;
        }
        else{
            $('#user_system_email_err').empty();
        }

        if(user_password == '' || user_password.trim().length == 0){
            $('#user_system_password_err').text("No space please and don't leave it empty");
            errors += 1;
        }
        else{
            $('#user_system_password_err').empty();
        }

        if(errors == 0){    

            var login_form = $("#login-form");
            var serialize_form = login_form.serialize();
            $.ajax({
                type:"POST",
                url: ajaxpath.ajaxurl,
                data: serialize_form,
                success:function(response) {
                    console.log(response); 
                    // let status = response['status'];
                    // let message = response['message'];

                    // console.log(response);
                    if(status == true){
                    
                    }
                    else if(status == false){
                   
                    }

                },

            });
        }
       
    });

    $( "#venues-submit" ).submit(function( e ) {
      
        e.preventDefault();
        $form = $( "#venues-submit" );
        $.ajax({
            type: 'post',
            url: ajaxpath.ajaxurl,
            data: $form.serialize(),
            dataType : 'json',
            beforeSend: function (response) {
                // $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
             //$thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {
                console.log(response);
                if(response.status){
                    alert('Your Venue Has Been Created!');
                    window.location.href = response.redirect_url;
                   // $("#submit-venue-btn").hide();
                   // window.location.href = response.redirect_url;
                } 
                /*else{
                    alert(response.error);
                }*/

            },
        });
          /*  var venues_submit = $("#venues-submit");
            var serialize_form = venues_submit.serialize();
            $.ajax({
                type:"POST",
                url: ajaxpath.ajaxurl,
                data: serialize_form,
                success:function(response) {
                    console.log(response); 
                    // let status = response['status'];
                    // let message = response['message'];

                    // console.log(response);
                    if(status == true){

                    }
                    else if(status == false){

                    }

                },

            });*/
       

    });
    

    

});