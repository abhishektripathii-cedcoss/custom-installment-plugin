<?php

$installed_payment_methods = WC()->payment_gateways->get_available_payment_gateways();
$mwb_store_payment_gateway = array();
$mwb_payment_gateway_id    = array();

foreach ( $installed_payment_methods as  $method ) {
	$mwb_payment_gateway_id[]                 = $method->id;
	$mwb_store_payment_gateway[ $method->id ] = $method->get_title();
}


if ( is_array( $mwb_store_payment_gateway ) && ! empty( $mwb_store_payment_gateway ) ) {
	$mwb_no_of_payment_method_active = sizeof( array_keys( $mwb_store_payment_gateway ) );
	update_option( 'mwb_active_payment_gateways', $mwb_store_payment_gateway );
	// Custom enqueue script for ajax.
	wp_enqueue_script( 'mwb-custom-payment-gateway-active-script', plugin_dir_url( __DIR__ ) . '/js/mwb-custom-ajax.js', array( 'jquery' ), '1.0.0', false );
	wp_localize_script(
		'mwb-custom-payment-gateway-active-script',
		'mwb_custom_payment_gateway_ajax_obj',
		array(
			'ajax_url'                  => admin_url( 'admin-ajax.php' ),
			'mwb_payment_setting_nonce' => wp_create_nonce( 'mwb_payment_gateways_nonce' ),
			'payment_gateways'          => $mwb_store_payment_gateway,
			'no_of_payment_active'      => $mwb_no_of_payment_method_active,
		)
	);

}
$mwb_active_payment_gateway_in_woocomm = get_option( 'mwb_active_payment_gateways', false );

if ( isset( $_POST['mwb_default_tab_settings_submit'] ) ) {
	$nonce = isset( $_POST['mwb_quiz_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_quiz_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce ) ) {

		$mwb_all_detail_payment_method = array();

		for ( $i = 0; $i < $mwb_no_of_payment_method_active; $i++ ) {
			$payment_selected_row_method = 'payment_gateway_listing_' . $i;
			$payment_selected_row_icon   = 'mwb-img_' . ( $i );

			$mwb_selected_payment_methods          = isset( $_POST[ $payment_selected_row_method ] ) ? sanitize_text_field( wp_unslash( $_POST[ $payment_selected_row_method ] ) ) : '';
			$mwb_image_on_selected_payment_methods = isset( $_POST[ $payment_selected_row_icon ] ) ? sanitize_text_field( wp_unslash( $_POST[ $payment_selected_row_icon ] ) ) : '';
			if ( ! empty( $mwb_selected_payment_methods ) && ! empty( $mwb_image_on_selected_payment_methods ) ) {
				$mwb_all_detail_payment_method[ $i ][ $mwb_selected_payment_methods ] = $mwb_image_on_selected_payment_methods;
			}
		}
		if ( is_array( $mwb_all_detail_payment_method ) && ! empty( $mwb_all_detail_payment_method ) ) {
			update_option( 'mwb_all_detail_saved_for_gateways', $mwb_all_detail_payment_method );

		}
	}
}
$mwb_all_detail_saved_in_option_table = get_option( 'mwb_all_detail_saved_for_gateways', array() );



?>

<form action="" method="POST" id="mwb-multi-payment-method-select">

	<div class="mwb-delete__msg-modal"><div class='mwb_delete_msg_display'><div class='mwb-delete__msg-icon'></div><p><?php esc_html_e ( 'You have successfully deleted Payment method.', 'custom-installments-for-woocommerce' ); ?></p><a href="#" class='mwb-delete__msg-btn--close mwb-btn--secondary'>ok</a></div></div>

	<div class="mwb_form_wrapper">
		<a href="#" class="mwb_heading_box mwb-btn--secondary"><?php esc_html_e ( 'add new field', 'custom-installments-for-woocommerce' ); ?> </a>
		<div class="mwb_question">
			<div class="mwb_html_append"></div>
		</div>
		<?php
		if ( is_array( $mwb_all_detail_saved_in_option_table ) && ! empty( $mwb_all_detail_saved_in_option_table ) ) {
			foreach ( $mwb_all_detail_saved_in_option_table as $index => $value ) {
				foreach ( $value as $pay_key => $img_value2 ) {
					?>
					<div class="mwb_settings_area"> 
						<div class="mwb-settings__label">
							<?php esc_html_e ( 'payment method', 'custom-installments-for-woocommerce' ); ?>
						</div>
						<div class="mwb-settings__field">
							<div class="mwb-settings__field-row">
								<div class="mwb-settings__field-col--left">
									<select id="payment_gateway_listing_<?php esc_html_e( $index, 'custom-installments-for-woocommerce') ?>" name="payment_gateway_listing_<?php esc_html_e( $index, 'custom-installments-for-woocommerce') ?>" data-selected-box-id = "<?php esc_html_e( $index, 'custom-installments-for-woocommerce') ?>">
										<?php
										foreach ( $mwb_store_payment_gateway as $pay_methods_ids => $pay_methods_value ) {
											?> 
											<option value="<?php esc_html_e( $pay_methods_ids, ''); ?>" <?php if ($pay_methods_ids == $pay_key ) { ?>selected="selected"<?php } ?>><?php esc_html_e( $pay_methods_value, ''); ?></option>
											<?php
										}
										?>
									</select>
									<div class="mwb-settings__link-wrap">
										<div class="mwb-rmv__img-link">
											<a href="#" class="mwb-rmv mwb-btn--secondary" style="display:block"><?php esc_html_e( 'Remove image', 'custom-installments-for-woocommerce' ); ?></a>
										</div>
										<input type="hidden" class="mwb-img" id="mwb-image_<?php echo strval( $index ); ?>" name="mwb-img_<?php esc_html_e( ( $index ), 'custom-installments-for-woocommerce' ); ?>" value="<?php esc_html_e( ( $img_value2 ), 'custom-installments-for-woocommerce' ); ?>">
										<div class="mwb_delete_payment_method">
											<a href="#" class="mwb-btn--secondary"><?php esc_html_e ( 'Delete payment method', 'custom-installments-for-woocommerce' ); ?></a>
										</div>
									</div>
								</div>
								<div class="mwb-settings__field-col--right">
									<div class="mwb-upl__img">
											<?php
											echo wp_get_attachment_image( (int) trim( $img_value2 ) );
											?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
		}
		?>
	</div>
	<input type="hidden" name="mwb_quiz_nonce" id="mwb_quiz_nonce" value="<?php echo esc_html( wp_create_nonce() ); ?>"> 
	<div class="mwb-submit__button-grp">
		<div class="mwb_submit_button mwb-submit__button-wrap">
			<input type='submit' class="mwb-submit__button" name='mwb_default_tab_settings_submit'>
		</div>
	</div>
</form>
