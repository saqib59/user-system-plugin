jQuery(document).ready(function ($) {

    var stripe = Stripe('pk_test_lDPpRAgbyAWQWRjMYvRIiCul00OtxCF5tu');
    var elements = stripe.elements();

    var style = {
        base: {
            iconColor: '#666EE8',
            color: '#31325F',
            lineHeight: '40px',
            fontWeight: 300,
            fontFamily: 'Helvetica Neue',
            fontSize: '15px',
            '::placeholder': {
                color: '#CFD7E0',
            },
        },
    };
    var cardNumberElement = elements.create('cardNumber', {
        style: style,
        placeholder: 'Card number',
    });
    cardNumberElement.mount('#card-number-element');

    var cardExpiryElement = elements.create('cardExpiry', {
        style: style,
        placeholder: 'Expiry date',
    });
    cardExpiryElement.mount('#card-expiry-element');

    var cardCvcElement = elements.create('cardCvc', {
        style: style,
        placeholder: 'CVC',
    });
    cardCvcElement.mount('#card-cvc-element');


    function setOutcome(result) {
        var successElement = document.querySelector('.success');
        var errorElement = document.querySelector('.error');
        successElement.classList.remove('visible');
        errorElement.classList.remove('visible');

        if (result.token) {
            // In this example, we're simply displaying the token
            // successElement.querySelector('.token').textContent = result.token.id;
            // successElement.classList.add('visible');

            // In a real integration, you'd submit the form with the token to your backend server
            //alert(result.token.id);
            $('input[name="stripeToken"]').val(result.token.id);
            
            stripeCharge();
            
        } else if (result.error) {
            errorElement.textContent = result.error.message;
            errorElement.classList.add('visible');
        }
    }

    cardNumberElement.on('change', function (event) {
        setOutcome(event);
    });

    document.querySelector('#pay-btn').addEventListener('click', function (e) {
        console.log('form is working');
        var options = {
            address_zip: document.getElementById('postal-code').value,
        };
        stripe.createToken(cardNumberElement, options).then(setOutcome);
    });

    function stripeCharge() {    
        $form = $('#stripe-payment-form');
        $.ajax({
            type: 'post',
            url: ajaxpath.ajaxurl,
            data: $form.serialize(),
            dataType : 'json',
         /*   beforeSend: function (response) {
                // $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                //$thisbutton.addClass('added').removeClass('loading');
            },*/
            success: function (response) {
                console.log(response);
                if(response.status){
                    alert('Your Payment Has Been Charged!');
                    // alert(response.message);
                    //console(response.message);
                    window.location.href = response.redirect_url;
                } else{
                    alert(response.error);
                }

            },
        });
    }
})