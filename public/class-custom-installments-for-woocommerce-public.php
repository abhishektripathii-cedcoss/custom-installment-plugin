<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Custom_Installments_For_Woocommerce
 * @subpackage Custom_Installments_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Custom_Installments_For_Woocommerce
 * @subpackage Custom_Installments_For_Woocommerce/public
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Custom_Installments_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Installments_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Installments_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-installments-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Installments_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Installments_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-installments-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Callback function for showing modal on single product page.
	 *
	 * @return void
	 */
	public function mwb_modal_with_price_description() {
		$mwb_total_installment_price          = get_post_meta( get_the_ID(), 'mwb_total_installment_price', true );
		$mwb_installment_breakdown_with_price = get_post_meta( get_the_ID(), 'mwb_array_installment_price_both', true );
		$mwb_payment_gateway_detail           = get_option( 'mwb_all_detail_saved_for_gateways', array() );
		$mwb_price_symbol                     = get_woocommerce_currency_symbol();

		if ( isset( $mwb_total_installment_price ) && ! empty( $mwb_total_installment_price ) ) {
			$handle = 'mwb-custom-js-for-modal';
			$list   = 'enqueued';
			if ( wp_script_is( $handle, $list ) ) {
				return;
			} else {
				wp_enqueue_script( 'mwb-custom-js-for-modal', plugins_url( 'js/mwb-custom.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
			}
			echo "<div class='mwb_open_modal_wrapper' ><span class='mwb_open_modal'><a>Installments</a></span></div>";
			?>
			<div class="mwb_price_box_wrapper">
				<div class="mwb-price-box__content-wrap">
					<div class="mwb-price-box__header">
						<h3 class="mwb-price-box__header-title"><?php esc_html_e( 'Installment options', 'custom-installments-for-woocommerce' ); ?></h3>
						<span class="mwb-price-box__close">x</span>
					</div>
					<div class="mwb_installment_price_wrapper mwb-installment__price-table--wrap">
						<table class="mwb-installment__price-table">
							<tr>
								<td><?php esc_html_e( 'Installments Price', 'custom-installments-for-woocommerce' ); ?></td>
								<td><?php esc_html_e( $mwb_price_symbol . $mwb_total_installment_price , '' ); ?></td>
							</tr>
							<?php
							foreach ( $mwb_installment_breakdown_with_price as $mwb_installments => $mwb_price_per_month ) {
							?>
								<tr>
									<td  class="mwb_installments_month" ><?php esc_html_e( $mwb_installments . ' months', 'custom-installments-for-woocommerce' ); ?></td>
									<td class="mwb_price_per_month"><?php esc_html_e( $mwb_price_symbol . $mwb_price_per_month . '/mo', 'custom-installments-for-woocommerce' ); ?></td>
								</tr>
								<?php
							}
							?>
						</table>
					</div>
					<div class="mwb_payment_gateways_wrapper mwb-payment-gateway">
						<h3 class="mwb_modal_cards_title mwb-payment-gateway__title"><?php esc_html_e( 'Valid Payment Methods', '' ); ?></h3>
						<div class="mwb_modal_cards_text mwb-payment-gateway__desc"><?php esc_html_e( 'Payment Methods on which emi option is available.', 'custom-installments-for-woocommerce' ); ?></div>
						<div class="mwb-payment-gateway__icon-wrap">
							<?php
							foreach ( $mwb_payment_gateway_detail as $mwb_key => $mwb_value ) {
								foreach ( $mwb_value as $mwb_paymeent_method => $mwb_icon_id ) {
									?>
								<div class="mwb_payment_gateways mwb-payment-gateway__icon" >
									<?php
										echo wp_get_attachment_image( $mwb_icon_id );
									?>
								</div>
									<?php
								}
							}
							?>
						</div>
						<div class="mwb-payment-gateway__button-wrap">
							<a  class="mwb-payment-gateway__button"><?php esc_html_e( 'ok', 'custom-installments-for-woocommerce' ); ?></a>
						</div>
					</div>
				</div>
			</div>
			<?php

		}
	}

	/**
	 * Callback function for changing prices.
	 *
	 * @param object $cart comment.
	 * @return void
	 */
	public function mwb_add_custom_price( $cart ) {
		// This is necessary for WC 3.0+.
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		// Avoiding hook repetition (when using price calculations for example | optional).
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}

		$chosen_gateway_at_checkout = WC()->session->get( 'chosen_payment_method' );
		$mwb_saved_gateway          = get_option( 'mwb_all_detail_saved_for_gateways', array() );
		/**
		 * Function for checking key exist or not.
		 *
		 * @param array  $array comment.
		 * @param string $key_search comment.
		 * @return string
		 */
		function findKey( $array, $key_search ) {
			foreach ( $array as $key => $item ) {
				foreach ( $item as $key2 => $item2 ){
					if ( $key2 == $key_search ) {
						return true;
					}
				}
			}
			return false;
		}
		$mwb_gateway_saved_for_emi = findKey( $mwb_saved_gateway, $chosen_gateway_at_checkout );
		if ( $mwb_gateway_saved_for_emi && is_checkout() ) {

			foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
				$installment_rate = get_post_meta( $cart_item['data']->get_id(), 'mwb_total_installment_price', true );
				if ( $installment_rate ) {

					$cart_item['data']->set_price( $installment_rate );

				}
			}
		}
	}

}
