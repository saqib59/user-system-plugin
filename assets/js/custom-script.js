jQuery(document).ready(function ($) {

    $( "#login-form" ).submit(function( e ) {

        e.preventDefault();

        let user_email = $("input[name='user_system_email']").val().trim();

        let user_password = $("input[name='user_system_password']").val().trim();

        let user_remember = $("input[name='user_system_remember']").is( ":checked" );

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
           
            $(".button-submit").append('<i class="fa fa-circle-o-notch fa-spin" style="font-size:20px"></i>');
            $(".button-submit").prop("disabled",true);
            $.ajax({
                type:"POST",
                url: ajaxpath.ajaxurl,
                data: serialize_form,
                dataType : 'json',
                success:function(response) {
                    $(".button-submit").children().remove();
                    $(".button-submit").prop("disabled",false);
                    console.log(response);
                    if(response.status){
                        $("#login-msg").text('You are logged in').css("color","green");
                        window.location.href = response.redirect_url;
                        // $("#submit-venue-btn").hide();
                        // window.location.href = response.redirect_url;
                    } 
                    else{
                         $("#login-msg").text(response.error).css("color","red");

                    }

                },

            });
        }

    });

    $( "#register-form" ).submit(function( e ) {

        e.preventDefault();

        let user_name = $("input[name='user-name']").val().trim();
        let user_email = $("input[name='user-email']").val().trim();
        let user_password = $("input[name='user-password']").val().trim();
        var errors = 0;
         if(user_name == '' || user_name.trim().length == 0){
            errors += 1;
        }

        if(user_email == '' || user_email.trim().length == 0){
            errors += 1;

        }
        if(user_password == '' || user_password.trim().length == 0){
            errors += 1;
        }
        if(errors == 0){ 
            $form = $( "#register-form" );
            $("#reg-btn").append('<i class="fa fa-circle-o-notch fa-spin" style="font-size:20px"></i>');
            $("#reg-btn").prop("disabled",true);
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
                    $("#reg-btn").children().remove();
                    $("#reg-btn").prop("disabled",false);
                    console.log(response);
                    if(response.status){
                    $("#register-msg").text(response.error).css("color","green");
                    window.location.href = response.redirect_url;
                    // $("#submit-venue-btn").hide();
                    // window.location.href = response.redirect_url;
                    } 
                    else{
                        $("#register-msg").text(response.error).css("color","red");
                    }
                },
            });
        }

        else if(errors > 0){

                $("#register-msg").text('One / More fields are empty').css("color","red");

        }

    });

    



    



});