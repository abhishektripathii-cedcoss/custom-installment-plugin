<?php
$mwb_store_payment_gateway = get_option( 'mwb_active_payment_gateways', array() );
?>
<a href="#" class="mwb_heading_box mwb-btn--secondary"><?php esc_html_e ( 'add new field', 'custom-installments-for-woocommerce' ); ?> </a>
<div class="mwb_question">
	<div class="mwb_html_append"></div>
</div>
<?php
foreach ( $mwb_all_detail_saved_rearranged as $index => $value ) {
	foreach ( $value as $pay_key => $img_value2 ) {
		?>
		<div class="mwb_settings_area">
			<div class="mwb-settings__label">
				<?php esc_html_e( 'payment method', 'custom-installments-for-woocommerce' ); ?>
			</div>
			<div class='mwb-settings__field'>
				<div class='mwb-settings__field-row'>
					<div class='mwb-settings__field-col--left'>
						<select id="payment_gateway_listing_<?php esc_html_e( $index, 'custom-installments-for-woocommerce') ?>" name="payment_gateway_listing_<?php esc_html_e( $index, 'custom-installments-for-woocommerce') ?>" data-selected-box-id = "<?php esc_html_e( $index, 'custom-installments-for-woocommerce') ?>">
							<?php

							foreach ( $mwb_store_payment_gateway as $pay_methods_value ) { 
							?> 
								<!-- <option value="-1">None</option> -->
								<option value="<?php esc_html_e( $pay_methods_value, 'custom-installments-for-woocommerce' ); ?>" <?php if ( $pay_methods_value == $pay_key ) {
									?>selected="selected"
									<?php
											}?>>
									<?php esc_html_e( $pay_methods_value, 'custom-installments-for-woocommerce' ); ?></option>
								<?php
							}
							?>
						</select>
						<div class='mwb-settings__link-wrap'>
							<div class="mwb-rmv__img-link">
								<a href="#" class="mwb-rmv mwb-btn--secondary" style="display:block"><?php esc_html_e ( 'Remove image', 'custom-installments-for-woocommerce' ); ?></a>
							</div>
							<input type="hidden" class="mwb-img" id="mwb-image_<?php echo strval( $index ) ?>" name="mwb-img_<?php esc_html_e( ( $index ),'custom-installments-for-woocommerce') ?>" value="<?php esc_html_e( ($img_value2), 'custom-installments-for-woocommerce' ) ; ?>">

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

?>
