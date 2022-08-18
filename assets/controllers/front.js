import $ from 'jquery';
global.$ = global.jQuery = $;
import '../styles/styles.scss';
window.bootstrap = require('bootstrap/dist/js/bootstrap');
import toastr from 'toastr';
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            offset: 74,
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

    //Search
    $("#menuSearch").keyup(function() {
        //Assigning search box value to javascript variable named as "name".
        const search = $('#menuSearch').val();
        $("#results").empty();
        if (search.length > 2) {
            $.ajax({
                url: "/search-products?q="+search,
                cache: false,
                type: "GET",
                success: function(html){
                    $("#results").append(html);
                }
            });
        }
    });
    function loadRemoveButton() {
        $('.jsRemoveProductFromCart').on('click', function() {
            let productId = $(this).data('id');
            let envase = null;
            if ($(this).data('envase') == 1) {
                envase = productId;
                productId = null;
            }
            const productData = {'id': productId, 'envase': envase, 'size': 0 };
            $.ajax({
                type: "POST",
                url: '/update-cart',
                data:  JSON.stringify(productData),
                success: function(response) {
                    $('#cartModal').modal('hide');
                    $('.cartContent').html(response);
                    toastr.success('Carrito actualizado', 'Producto eliminado');
                    loadRemoveButton();
                }
            });
        });
        $('.jsEditProductFromCart').on('click', function() {
            let productId = $(this).data('id');
            const divisible = $(this).data('divisible') == true;
            let size = 0;
            let envase = null;
            if (divisible) {
                size = $(this).parent().parent().find('.jsSize option:selected').val();
            } else {
                size = $(".jsEditProductFromCartInput_"+productId).val();
                if ($(".jsEditProductFromCartInput_"+productId).data('envase')) {
                    envase = productId;
                    productId = null;
                }
            }


            const productData = {'id': productId, 'envase': envase, 'size': size };
            $.ajax({
                type: "POST",
                url: '/update-cart?removeOld=true',
                data:  JSON.stringify(productData),
                success: function(response) {
                    $('#cartModal').modal('hide');
                    $('.cartContent').html(response);
                    toastr.success('Carrito actualizado', 'Producto eliminado');
                    loadRemoveButton();
                }
            });
        });
    }
    loadRemoveButton();

    $('.jsAddToCart').on('click', function(){
        const productId = $(this).data('id');
        const envaseOption = $(this).parent().find('.jsEnvaseSelect option:selected');
        let envaseId = "";
        if (typeof envaseOption.data('envase') !== typeof undefined && envaseOption.data('envase') !== false) {
            envaseId = envaseOption.data('envase');
        }
        let sizeOption = $(this).parent().find('.jsSize option:selected').val();
        if (typeof sizeOption === 'undefined') {
            sizeOption = $('#productQuantity').val();
        }
        const productData = {'id': productId, 'envase': envaseId, 'size': sizeOption };
        $.ajax({
            type: "POST",
            url: '/update-cart',
            data:  JSON.stringify(productData),
            success: function(response) {
                $('.cartContent').html(response);
                toastr.success('Carrito actualizado', 'Producto agregado correctamente');
                loadRemoveButton();
            }
        });
    });

    $('#openCart').on('click', function(){
        $('#cartModal').modal('show');
    });
});