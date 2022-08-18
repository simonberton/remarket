import $ from 'jquery';
global.$ = global.jQuery = $;

$(document).ready(function () {
    $('#checkout_pickupRefillments ').change(function() {
        if ($("#checkout_pickupRefillments option:selected").val() == "1") {
            $('.pickupData ').removeClass('d-none');
        }
        else {
            $('.pickupData').addClass('d-none');
        }
    });
});