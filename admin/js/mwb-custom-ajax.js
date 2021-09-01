jQuery(document).ready(function ($) {

    var mwb_exist_select_box = $(document).find('.mwb_settings_area select:last');
    var mwb_no_of_payment_active = mwb_custom_payment_gateway_ajax_obj.no_of_payment_active;

    /*=========================================================
    =            No of select box in setting area       =
    ===========================================================*/

    if (mwb_exist_select_box.length !== 0) {

        var mwb_get_last_box_in_php = mwb_exist_select_box.attr('data-selected-box-id');

        var mwb_no_of_select_box_for_image = parseInt(mwb_get_last_box_in_php);
        var mwb_no_of_select_box = parseInt(mwb_get_last_box_in_php) + 1;

        if (mwb_no_of_payment_active == mwb_no_of_select_box) {
            $(".mwb_heading_box").hide();

        }
        $(".mwb_submit_button").show();

    } else {
        var mwb_no_of_select_box = 0;
        var mwb_no_of_select_box_for_image = 0;
    }
    // console.log($(document).find('.mwb_settings_area select:last'));


    /*==========================================================
    =           Append form html to setting tab       =
    ============================================================*/
    $(document).on('click', '.mwb_heading_box', function () {
        $('form#mwb-multi-payment-method-select select').each(function () {
            // console.log($(this).val());
        });
        var mwb_active_payment_gateways = mwb_custom_payment_gateway_ajax_obj.payment_gateways;


        $(".mwb_html_append").append("<div class='mwb_settings_area'><div class='mwb-settings__label'>payment method</div><div class='mwb-settings__field'><div class='mwb-settings__field-row'><div class='mwb-settings__field-col--left'><select id='payment_gateway_listing_" + mwb_no_of_select_box + "' name='payment_gateway_listing_" + mwb_no_of_select_box + "' data-selected-box-id='" + mwb_no_of_select_box + "' ><option value='-1'>None</option></select><div class='mwb-settings__link-wrap'><input type='hidden' class='mwb_image_id' name = 'mwb_image_id' value='' ><a href='#' class='mwb-upl mwb-btn--secondary'>Upload image</a><a href='#' class='mwb-rmv mwb-btn--secondary' style='display:none'>Remove image</a></div></div><div class='mwb-settings__field-col--right'><input type='hidden' class='mwb-img' id='mwb-img_" + mwb_no_of_select_box + "' name='mwb-img_" + mwb_no_of_select_box + "' value=''><div class='mwb-upl__img'></div></div></div></div></div>");


        var selectbox = '#payment_gateway_listing_' + mwb_no_of_select_box;

        $.each(mwb_active_payment_gateways, function (key, value) {
            $(selectbox)
                .append($("<option></option>")
                    .attr("value", key)
                    .text(value));
        });
        $(".mwb_submit_button").show();


        mwb_no_of_select_box++;

        if (mwb_no_of_payment_active == mwb_no_of_select_box) {
            $(".mwb_heading_box").hide();

        }

    });


    /*=====================================================
    =            Upload image for payment tab       =
    =======================================================*/

    $(document).on('click', '.mwb-upl', function (e) {

        e.preventDefault();

        var button = $(this);
        button.hide().siblings().show();
        console.log(button);
        custom_uploader = wp.media({
            title: 'Insert image',
            library: {
                type: 'image'
            },
            button: {
                text: 'Use this image' // button label text
            },
            multiple: false
        }).on('select', function () { // it also has "open" and "close" events
            var attachment = custom_uploader.state().get('selection').first().toJSON();

            button.closest('.mwb-rmv__img-link').siblings('input.mwb-img').attr('value',attachment.id ) ;
            button.closest('.mwb-settings__field-col--left').siblings().children('div.mwb-upl__img').siblings('input.mwb-img').attr('value',attachment.id );
            button.closest('.mwb-settings__field-col--left').siblings().children('div.mwb-upl__img').show().html('<img  src="' + attachment.url + '">');
            button.closest('.mwb-rmv__img-link').html('<a href="#" class="mwb-rmv mwb-btn--secondary" style="display:block">Remove image</a>');

        }).open();

    });


    /*=====================================================
    =            Remove image for payment tab       =
    =======================================================*/

    $('body').on('click', '.mwb-rmv', function (e) {

        e.preventDefault();

        var button = $(this);
        button.hide().siblings().show();

        button.closest('.mwb-rmv__img-link').siblings('input.mwb-img').attr('value','' );
        button.closest('.mwb-settings__field-col--left').siblings().children('div.mwb-upl__img').hide();
        button.closest('.mwb-rmv__img-link').html('<a href="#" class="mwb-upl mwb-btn--secondary">Upload image</a>');
        // button.hide();
    });

    /*=====================================================
    =            Delete payment Method       =
    =======================================================*/
    
    $(document).on('click', '.mwb_delete_payment_method', function (e) {
        if (confirm('Are you sure want to delete this payment method ?')) {

            var mwb_id = $(this).closest('.mwb-settings__link-wrap').siblings('select').attr('data-selected-box-id');
        
            var data = {
                action: 'mwb_delete_payment_method',
                index_deleted: mwb_id,
                mwb_nonce: mwb_custom_payment_gateway_ajax_obj.mwb_payment_setting_nonce,
            };
            $.ajax({

                url: mwb_custom_payment_gateway_ajax_obj.ajax_url,
                type: "POST",
                data: data,
                dataType: 'html',
                success: function (response) {
                    if ($('body').hasClass('mwb-modal--close')) {
                        $('body').removeClass('mwb-modal--close');
                    }
                    $('body').addClass('mwb-modal--open');

                    $('.mwb-delete__msg-btn--close').on('click', function () {
                        console.log('asdg');
                        setTimeout(function () {
                            $('body').removeClass('mwb-modal--open');
                        }, 350);
                        $('body').addClass('mwb-modal--close');
                    });


                    // $('.mwb_delete_msg_display').show();

                    mwb_no_of_select_box = mwb_no_of_select_box - 1;
                    

                    $('.mwb_form_wrapper').html(response);
                    if (mwb_no_of_select_box == 0) {
                        $('.mwb_submit_button').hide();
                        $('.mwb_form_wrapper').html('<a href="#" class="mwb_heading_box mwb-btn--secondary">add new field </a><div class="mwb_question"><div class="mwb_html_append"></div></div>');
                    }

                }
            });
        }
        

    });

});

