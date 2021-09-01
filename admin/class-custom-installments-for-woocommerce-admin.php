<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Custom_Installments_For_Woocommerce
 * @subpackage Custom_Installments_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Installments_For_Woocommerce
 * @subpackage Custom_Installments_For_Woocommerce/admin
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Custom_Installments_For_Woocommerce_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-installments-for-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-installments-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Callback function for adding setting panel.
	 *
	 * @return void
	 */
	public function mwb_setting_admin_page() {
		// add top level menu page.
		add_menu_page(
			'', // Page Title.
			'Payment Setting', // Menu Title.
			'manage_options', // Capability.
			'mwb_plugin', // Page slug.
			array( $this, 'admin_page_html' ), // Callback to print html.
			'dashicons-bank',
			3
		);
	}
	/**
	 * Callback function for html on setting page.
	 *
	 * @return void
	 */
	public function admin_page_html() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Get the active tab from the $_GET param.
		$default_tab = null;
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab;

		?>
		<!-- Our admin page content should all be inside .wrap -->
		<div class="wrap">
			<!-- Print the page title -->
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<!-- Here are our tabs -->
			<nav class="nav-tab-wrapper">
				<a href="?page=mwb_plugin" class="nav-tab <?php if ( $tab === null ) : ?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'PAYMENT GATEWAY', '' ); ?></a>
			</nav>

			<div class="tab-content">
				<?php
				switch ( $tab ) :
					default:
						require_once plugin_dir_path( __FILE__ ) . 'partials/mwb-custom-general-setting.php';
						break;
				endswitch;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Callback function for creating setting field in general settings tab
	 *
	 * @return void
	 */
	public function mwb_installment_settings_field() {
		woocommerce_wp_text_input(
			array(
				'id'          => 'mwb_installment_percentage_price',
				'value'       => get_post_meta( get_the_ID(), 'mwb_installment_percentage_price', true ),
				'label'       => esc_html( 'EMI rate for installments' ),
				'description' => 'This will calculate rate of interest based on product price.',
				'desc_tip'    => 'true',
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => 'mwb_installment_breakdown',
				'value'       => get_post_meta( get_the_ID(), 'mwb_installment_breakdown', true ),
				'label'       => esc_html( 'Installment Breakdown' ),
				'placeholder' => esc_html( 'Use | for number of installments.' ),
				'description' => 'This will show no of months for installments.You have to write as 4|6|9 .',
				'desc_tip'    => 'true',
			)
		);
	}
	/**
	 * Callback function for saving installment fields
	 *
	 * @param int    $id comments.
	 * @param object $post comments.
	 * @return void
	 */
	public function mwb_save_installment_settings_field( $id, $post ){
		$mwb_installments     = isset( $_POST['mwb_installment_breakdown'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_installment_breakdown'] ) ) : '';
		$mwb_percentage_price = isset( $_POST['mwb_installment_percentage_price'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_installment_percentage_price'] ) ) : '';
			update_post_meta( $id, 'mwb_installment_percentage_price', $mwb_percentage_price );
			update_post_meta( $id, 'mwb_installment_breakdown', $mwb_installments );

	}
	/**
	 * Callback function for creating custom tab in woocommerce product panel.
	 *
	 * @param array $tabs comments.
	 * @return array
	 */
	public function mwb_custom_product_settings_tabs( $tabs ) {

		$tabs['custom_installments_tab'] = array(
			'label'    => 'Installment Info',
			'target'   => 'mwb_custom_product_data',
			'class'    => array( 'show_if_simple', 'show_if_virtual', 'show_if_grouped', 'show_if_external' ),
			'priority' => 21,
		);
		return $tabs;

	}
	/**
	 * Callback function for data in created tab in product panel.
	 *
	 * @return void
	 */
	public function mwb_custom_product_panels(){

		global $post;

		echo '<div id="mwb_custom_product_data" class="panel woocommerce_options_panel hidden">';
		$mwb_installments_breakdown      = ( get_post_meta( get_the_ID(), 'mwb_installment_breakdown', true ) );
		$mwb_percentage_price_added      = intval( get_post_meta( get_the_ID(), 'mwb_installment_percentage_price', true ) );
		$mwb_price_symbol                = get_woocommerce_currency_symbol();
		$price_regular                   = get_post_meta( get_the_ID(), '_regular_price', true );
		$price_sale                      = get_post_meta( get_the_ID(), '_sale_price', true );
		$mwb_take_price_for_installments = ! empty( $price_sale ) ? $price_sale : $price_regular;
		$mwb_interset_price              = ( $mwb_percentage_price_added * $mwb_take_price_for_installments ) / 100;
		$mwb_total_price                 = $mwb_take_price_for_installments + $mwb_interset_price;
		$show_price                      = $mwb_price_symbol . $mwb_total_price;
		if ( isset( $mwb_percentage_price_added ) && ! empty( $mwb_percentage_price_added ) ) {
			if ( ! empty( $mwb_interset_price ) ) {
				?>
				<div class="mwb_total_price mwb-total__installment">
					<div class="mwb-total__installment-title"><h3><?php esc_html_e( 'Total Price', 'custom-installments-for-woocommerce' ); ?></h3></div>
					<div class="mwb-total__installment-price"><p><?php esc_html_e( $show_price, 'custom-installments-for-woocommerce' ); ?></p></div>
				</div>
				<?php
				update_post_meta( $post->ID, 'mwb_total_installment_price', $mwb_total_price );
			}
			$mwb_installments_breakdown = explode( '|', ( $mwb_installments_breakdown ) );
			$mwb_array_installment_price_both = array();
			foreach ( $mwb_installments_breakdown as $value ) {
				$mwb_converting_to_int_value                                      = intval( $value );
				$mwb_price_per_installments                                       = $mwb_total_price / $mwb_converting_to_int_value;
				$mwb_array_installment_price_both[ $mwb_converting_to_int_value ] = round( $mwb_price_per_installments, 2 );
			}
			if ( is_array( $mwb_array_installment_price_both ) && ! empty( $mwb_array_installment_price_both ) ) {
				update_post_meta( $post->ID, 'mwb_array_installment_price_both', $mwb_array_installment_price_both );
				echo '<div class="mwb_installment_price_wrapper">';
				?>
				<input type="hidden" name="mwb_array_installment_price_both" id="mwb_array_installment_price_both" value="<?php esc_html_e( $mwb_array_installment_price_both, 'custom-installments-for-woocommerce') ?>">
				<table>
					<tr>
						<th><?php esc_html_e( 'Installments', 'custom-installments-for-woocommerce' ); ?></th>
						<th><?php esc_html_e( 'Price', 'custom-installments-for-woocommerce' ); ?></th>
					</tr>
					<?php
					foreach ( $mwb_array_installment_price_both as $mwb_array_installment => $mwb_array_price ) {
						?>
						<tr>	
							<td class="mwb_installment_price_ mwb_installment_div">
								<?php
									esc_html_e( $mwb_array_installment . ' months', 'custom-installments-for-woocommerce' );
								?>
							</td>
							<td class="mwb_installment_price_ mwb_price_div">
								<?php
									$mwb_array_price = $mwb_price_symbol . $mwb_array_price;
									esc_html_e( $mwb_array_price, 'custom-installments-for-woocommerce' );
								?>
							</td>
						</tr>	
						<?php
					}
					?>
				</table>
				<?php
				echo '</div>';
			}
			echo '</div>';

		} else {
			echo '<p>Please enter <strong>EMI</strong> rate and installments</p>';
			echo '</div>';
		}
	}

	/**
	 * Callback function for delete payment method.
	 *
	 * @return void
	 */
	public function mwb_delete_payment_method() {
		$mwb_ajax_nonce_verification = check_ajax_referer( 'mwb_payment_gateways_nonce', 'mwb_nonce' );
		$mwb_index_to_delete         = $_POST['index_deleted'];
		if ( $mwb_ajax_nonce_verification ) {
			$mwb_all_detail_saved_in_option_table = get_option( 'mwb_all_detail_saved_for_gateways', array() );
			if ( is_array( $mwb_all_detail_saved_in_option_table ) && ! empty( $mwb_all_detail_saved_in_option_table ) ) {
				unset( $mwb_all_detail_saved_in_option_table[ $mwb_index_to_delete ] );
				$mwb_all_detail_saved_in_option_table = array_values( $mwb_all_detail_saved_in_option_table );
				update_option( 'mwb_all_detail_saved_for_gateways', $mwb_all_detail_saved_in_option_table );

			}
			$mwb_all_detail_saved_rearranged = get_option( 'mwb_all_detail_saved_for_gateways', array() );
			if ( is_array( $mwb_all_detail_saved_rearranged ) && ! empty( $mwb_all_detail_saved_rearranged ) ) {
				require_once plugin_dir_path( __FILE__ ) . 'partials/mwb-delete-payment-method-setting.php';
			}
		}

		wp_die();
	}
	/**
	 * Callback function for order edit page work.
	 *
	 * @param object $item_id comment.
	 * @param object $item comment.
	 * @return void
	 */
	public function add_admin_order_item_custom_fields( $item_id, $item ) {
		$product              = $item->get_product();
		$mwb_total_price_inst = $product->get_meta( 'mwb_total_installment_price' );
		$mwb_order_price      = $item->get_total();
		// New added if quantity is more than 1.
		$mwb_order_price          = ( $item->get_quantity() ) == 1 ? $mwb_order_price : $mwb_order_price / ( $item->get_quantity() );
		$mwb_inst_price_breakdown = $product->get_meta( 'mwb_array_installment_price_both' );
		if ( $product->is_on_sale() ) {
			$mwb_sale_price = $product->get_sale_price();
		}
		$mwb_regular_price = $product->get_regular_price();
		$mwb_price_symbol  = get_woocommerce_currency_symbol();

		if ( $mwb_order_price == $mwb_total_price_inst ) {
			if ( ! empty( $mwb_total_price_inst ) && ! empty( $mwb_inst_price_breakdown ) ) {
				?>

				<div class="mwb_table_installment_breakdown">
					<div class="mwb_total_price">
						<table>
							<tr>
								<th><?php esc_html_e( 'Regular Price', 'custom-installments-for-woocommerce' ); ?></th>
								<th><?php esc_html_e( 'Sale Price', 'custom-installments-for-woocommerce' ); ?></th>
								<th><?php esc_html_e( 'Product Price with installment', 'custom-installments-for-woocommerce' ); ?></th>
							</tr>
							<tr>
								<td><?php esc_html_e( $mwb_price_symbol . $mwb_regular_price, 'custom-installments-for-woocommerce' ); ?></td>
								<td>
									<?php
										$mwb_sale_price = ( ! empty( $mwb_sale_price ) ) ? $mwb_price_symbol . $mwb_sale_price : 'Not set';
										esc_html_e( $mwb_sale_price, 'custom-installments-for-woocommerce' );
									?>
								</td>
								<td><?php esc_html_e( $mwb_price_symbol . $mwb_total_price_inst, 'custom-installments-for-woocommerce' ); ?></td>
							</tr>
						</table>
					</div>
					<table>
						<tr>
							<th><?php esc_html_e( 'Installments', 'custom-installments-for-woocommerce' ); ?></th>
							<th><?php esc_html_e( 'Price', 'custom-installments-for-woocommerce' ); ?></th>
						</tr>
						<?php
						foreach ( $mwb_inst_price_breakdown as $mwb_array_installment => $mwb_array_price ) {
							?>
							<tr>
								<td class="mwb_installment_price_ mwb_installment_div">
									<?php
										esc_html_e( $mwb_array_installment . ' months', 'custom-installments-for-woocommerce' );
									?>
								</td>
								<td class="mwb_installment_price_ mwb_price_div">
									<?php
										$mwb_array_price = $mwb_price_symbol . $mwb_array_price;
										esc_html_e( $mwb_array_price, 'custom-installments-for-woocommerce' );
									?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
				<?php
			}
		}
	}

}
