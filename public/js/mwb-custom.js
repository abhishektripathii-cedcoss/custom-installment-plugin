jQuery(document).ready(function ($) {
    console.log('hi');

    $(document).on('click', '.mwb_open_modal_wrapper', function () {
        if ($('body').hasClass('mwb-modal__close')) {
            $('body').removeClass('mwb-modal__close');
        }
        $('body').addClass('mwb-modal__open');

    });
    $(document).on('click', '.mwb-price-box__close', function () {
        setTimeout(function () {
            $('body').removeClass('mwb-modal__open');
        }, 250);
        $('body').addClass('mwb-modal__close');
    });
    $(document).on('click', '.mwb-payment-gateway__button', function () {
        setTimeout(function () {
            $('body').removeClass('mwb-modal__open');
        }, 250);
        $('body').addClass('mwb-modal__close');
    });
});