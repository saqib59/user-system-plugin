 (function($){

  var stripe = Stripe('pk_test_1JuvkUR59bbgIbN9y2Bdb3Ot00OhFzqmQb');
    
    var elements = stripe.elements();

    var style = {
  base: {
    color: '#32325d',
    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    },
    ':-webkit-autofill': {
      color: '#32325d',
    },
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a',
    ':-webkit-autofill': {
      color: '#fa755a',
    },
  }
};

    var cardNumberElement = elements.create('cardNumber', {
        style: style,
        placeholder: 'Enter card number',
    });
    cardNumberElement.mount('#card-number-element');


    var cardExpiryElement = elements.create('cardExpiry', {
        style: style,
        placeholder: 'Expiry date',
    });
    cardExpiryElement.mount('#card-expiry-element');


    var cardCvcElement = elements.create('cardCvc', {
        style: style,
        placeholder: 'CVC number',
    });
    cardCvcElement.mount('#card-cvc-element');


    function setOutcome(result) {
            var successElement = document.querySelector('.success');
            var errorElement = document.querySelector('.error');
            successElement.classList.remove('visible');
            errorElement.classList.remove('visible');

            if (result.token) {
                // In this example, we're simply displaying the token
                successElement.querySelector('.token').textContent = result.token.id;
                successElement.classList.add('visible');

                $('input[name=token]').val(result.token.id);


                success_init();

                // In a real integration, you'd submit the form with the token to your backend server
                //var form = document.querySelector('form');
                //form.querySelector('input[name="token"]').setAttribute('value', result.token.id);
                //form.submit();
            } else if (result.error) {
                errorElement.textContent = result.error.message;
                errorElement.classList.add('visible');
            }
    }

    cardNumberElement.on('change', function(event) {
        setOutcome(event);
    });


        //$('#stripeForm').submit(function(e){
        $('#stripeForm').submit(function(e){
            e.preventDefault();
            //alert('working properly button');
            var postal_code_value = $('#postal-code').val();
            //alert(postal_code_value);

            var options = {
                //address_zip: document.getElementById('postal-code').value,
                address_zip: postal_code_value,
            };
            stripe.createToken(cardNumberElement, options).then(setOutcome);

            

        });

        $('#stripe_form').submit(function(e){
            e.preventDefault();
            //alert('working properly button');
            var postal_code_value = $('#postal-code').val();
            //alert(postal_code_value);

            var options = {
                //address_zip: document.getElementById('postal-code').value,
                address_zip: postal_code_value,
            };
            stripe.createToken(cardNumberElement, options).then(setOutcome);

        });

        function success_init(){
            //var stripeForm = $('#stripeForm').serialize();
            var stripeForm = $('#stripe_form').serialize();
            var token = $('input[name=token]').val();
            //alert(token);
            //alert(ajaxadmin.ajaxurl);
            $('#donate').append('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
            $('#donate').prop('disabled',true);
            //var amount = $("#otherinput").val();
            //alert(amount);    
            $.ajax({
                type: 'post',
                url: the_ajax_script.ajaxurl,
                data: stripeForm,
                dataType : 'json',
                success: function (response) {

                    $('.fa.fa-spinner.fa-spin').remove();
                    $('#donate').prop('disabled',false);

                    console.log(response);
                    
                     if(response.message){
                        //$(".sucess").show();
                        window.location.href = response.redirect_uri;
                        //alert('Your Payment Has Been Charged!');
                        
                    } 
                   if(response.error){
                        alert(response.message);
                    }
                    // else{
                    //     alert('Your Form Has Been Submitted Successfully!');
                    //     location.reload();
                    // }

                   

                },
                error : function(errorThrown){
                    console.log(errorThrown);
                }
            });


        }
})(jQuery)