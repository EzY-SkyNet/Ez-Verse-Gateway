<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ezverse.in/
 * @since      1.0.1
 *
 * @package    Ezverse_WooCommerce
 * @subpackage Ezverse_WooCommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ezverse_WooCommerce
 * @subpackage Ezverse_WooCommerce/public
 * @author     Ez-Verse <support@upigateway.com>
 */
class Ezverse_WooCommerce_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Generate and save UUID if not already saved
		$this->generate_and_save_uuid();
	}


    /**
     * Generate and save UUID in plugin options.
     *
     * @since    1.0.1
     */
    private function generate_and_save_uuid()
    {
        // Check if UUID is already saved
        $uuid = get_option('Ezverse_WooCommerce_uuid');
        if (!$uuid) {
            $uuid = wp_generate_uuid4();
            update_option('Ezverse_WooCommerce_uuid', $uuid);
        }
    }

    private function generate_mixed_order_id()
{
    $host = parse_url(home_url(), PHP_URL_HOST);
    $host = preg_replace('/^www\./', '', $host);

    // Domain prefix (TE)
    $prefix = strtoupper(substr($host, 0, 2));

    // Random parts
    $alpha1 = strtoupper(wp_generate_password(3, false, false)); // DHD
    $number = wp_rand(10000, 99999); // 45624
    $alpha2 = strtoupper(wp_generate_password(3, false, false)); // DCH

    return "{$prefix}_{$alpha1}{$number}{$alpha2}";
}

public function save_public_order_id($order)
{
    // Prevent regeneration if already exists
    if ($order->get_meta('_public_order_id')) {
        return;
    }

    $order->update_meta_data(
        '_public_order_id',
        $this->generate_mixed_order_id()
    );
}

public function add_public_order_id_column($columns)
{
    $columns['public_order_id'] = 'Public Order ID';
    return $columns;
}

public function render_public_order_id_column($column, $post_id)
{
    if ($column === 'public_order_id') {
        $order = wc_get_order($post_id);
        echo esc_html($order->get_meta('_public_order_id'));
    }
}

public function search_by_public_order_id( $query ) {
    global $pagenow, $typenow;

    if (
        ! is_admin() ||
        $pagenow !== 'edit.php' ||
        $typenow !== 'shop_order' ||
        empty( $_GET['s'] )
    ) {
        return;
    }

    $search = sanitize_text_field( $_GET['s'] );

    // Only trigger for our format
    if ( strpos( $search, '_' ) === false ) {
        return;
    }

    $query->set( 'meta_query', [
        [
            'key'     => '_public_order_id',
            'value'   => $search,
            'compare' => '='
        ]
    ] );

    $query->set( 's', '' ); // disable default search
}

public function replace_order_number($order_number, $order)
{
    $public_id = $order->get_meta('_public_order_id');
    return $public_id ? $public_id : $order_number;
}

public function template_redirect()
{
    if (!isset($_GET['order_id'])) {
        return;
    }

    $order_id = absint($_GET['order_id']);
    $order = wc_get_order($order_id);

    if (!$order) {
        return;
    }

    wp_safe_redirect($order->get_checkout_order_received_url());
    exit;
}

    
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ezverse_WooCommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ezverse_WooCommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ez-verse-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ezverse_WooCommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ezverse_WooCommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ez-verse-public.js', array('jquery'), $this->version, false);
	}


	public function woocommerce_payment_gateways($methods)
	{
		$methods[] = 'UPI_Payment_Gateway';
		return $methods;
	}

}

add_action('plugins_loaded', function () {

	class UPI_Payment_Gateway extends WC_Payment_Gateway
	{

    private $base_url = 'https://ezverse.in';

	
    public string $client_id = '';
    public string $client_secret = '';
    public string $default_email = '';
    
    /**
     * Generate signature in plugin options.
     *
     * @since    1.0.1
     */
    
    private function fetch_user_profile()
{
    $timestamp = time();

    $signature = hash_hmac(
        'sha256',
        $this->client_id . '|' . $timestamp,
        $this->client_secret
    );

    $response = wp_remote_post(
        $this->base_url . '/api/v4/user',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-TIMESTAMP'  => $timestamp,
                'X-SIGNATURE'  => $signature,
            ],
            'body'    => wp_json_encode([
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
            ]),
            'timeout' => 15,
        ]
    );

    if (is_wp_error($response)) {
        return $response;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (
        empty($body['status']) ||
        empty($body['data'])
    ) {
        return new WP_Error('invalid_api', 'Invalid user API response');
    }

    return $body['data'];
}


public function process_admin_options()
{
    // Let WooCommerce save fields first
    parent::process_admin_options();

    // Reload saved options
    $this->init_settings();
    $this->client_id     = $this->get_option('client_id');
    $this->client_secret = $this->get_option('client_secret');

    if (!$this->client_id || !$this->client_secret) {
        delete_option('ezverse_profile');
        return;
    }

    $profile = $this->fetch_user_profile();

    if (is_wp_error($profile)) {
        delete_option('ezverse_profile');
        WC_Admin_Settings::add_error(
            __('Ez-Verse verification failed.', 'text-domain')
        );
        return;
    }

    update_option('ezverse_profile', [
        'verified'        => true,
        'firstname'       => $profile['firstname'],
        'lastname'        => $profile['lastname'],
        'email'           => $profile['email'],
        'image'           => $profile['image'],
        'plan_name'       => $profile['plan_name'],
        'plan_expired_at' => $profile['plan_expired_at'],
    ]);

    WC_Admin_Settings::add_message(
        __('Ez-Verse account verified successfully.', 'text-domain')
    );
}

	/**
     * Generate signature in plugin options.
     *
     * @since    1.0.1
     */
	private function ezverse_signature(array $parts)
    {
    return hash_hmac(
        'sha256',
        implode('|', $parts),
        $this->client_secret
    );
    }

		public function __construct()
		{

			$this->id = 'ez-verse'; 
			$this->icon = plugins_url('icon/icon.gif', __FILE__); 
			$this->has_fields = false; 
			$this->title = __('Ez-Verse', 'text-domain'); 
			$this->method_title = __('Ez-Verse', 'text-domain'); 
			$this->method_description = __('Custom UPI Payment', 'text-domain'); 
			$this->init_form_fields();		
			$this->init_settings();
			$this->title = $this->get_option('title');
			$this->description = $this->get_option('description');
			$this->enabled = $this->get_option('enabled');
			$this->client_id =  $this->get_option('client_id');
			$this->client_secret =  $this->get_option('client_secret');
			$this->default_email =  $this->get_option('default_email');
            $uuid = get_option('Ezverse_WooCommerce_uuid');
			add_action('woocommerce_api_ez-verse-' . $uuid, array($this, 'check_h_payment_response'));
			if (is_admin()) {
				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
			}
			add_action('woocommerce_blocks_payment_method_type_registration', array($this, 'register_payment_method'));

		}

		public function register_payment_method($payment_method_registry)
		{
			$payment_method_registry->register(
				array(
					'name'              => $this->id,
					'label'             => $this->title,
					'icon'              => $this->icon,
					'can_make_payment'  => array($this, 'can_make_payment'),
				)
			);
		}

		public function can_make_payment()
		{
			
			return true;
		}

		public function check_h_payment_response()
{
    $timestamp = $_SERVER['HTTP_X_TIMESTAMP'] ?? null;
    $signature = $_SERVER['HTTP_X_SIGNATURE'] ?? null;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$timestamp || !$signature || empty($input['order_id'])) {
        wp_die('Invalid webhook', '', 401);
    }

    $expected = $this->ezverse_signature([
        $this->client_id,
        $input['order_id'],
        $input['amount'],
        $timestamp,
    ]);

    if (!hash_equals($expected, $signature)) {
        wp_die('Signature mismatch', '', 401);
    }

    $public_id = sanitize_text_field($input['order_id']);

$orders = wc_get_orders([
    'meta_key'   => '_public_order_id',
    'meta_value' => $public_id,
    'limit'      => 1,
]);

$order = $orders ? $orders[0] : false;

if (!$order) {
    wp_die('Order not found', '', 404);
}

    if ($input['status'] === 'SUCCESS') {

        $utr = $input['utr'] ?? null;

        $order->payment_complete($utr);
        $order->add_order_note(
            'EZVERSE payment SUCCESS via webhook. UTR: ' . ($utr ?: 'N/A'),
            true
        );

        if ($utr) {
            update_post_meta($order->get_id(), '_ezverse_utr', $utr);
        }

    } else {

        $order->update_status('failed');
        $order->add_order_note('EZVERSE payment FAILED via webhook', true);
    }

    echo json_encode(['status' => true]);
    wp_die();
}


		public function init_form_fields()
		{

            $profile  = get_option('ezverse_profile');
            $verified = !empty($profile['verified']);

           if ($verified) {

             $image = !empty($profile['image']) ? esc_url($profile['image']) : '';

             $this->form_fields = [
        'profile_card' => [
            'title' => __('Ez-Verse Account', 'text-domain'),
            'type'  => 'title',
            'description' => '
                <div class="ezverse-card">
                    <img src="' . $image . '" style="width:64px;height:64px;border-radius:50%;margin-bottom:10px;" />
                    <br>
                    <strong>' . esc_html($profile['firstname'] . ' ' . $profile['lastname']) . '</strong><br>
                    <small>' . esc_html($profile['email']) . '</small><br><br>
                    <strong>Plan:</strong> ' . esc_html($profile['plan_name']) . '<br>
                    <strong>Expires:</strong> ' . esc_html($profile['plan_expired_at']) . '<br><br>
                    <a href="#" class="button" id="ezverse-edit">Edit credentials</a>
                </div>',
        ],
    ];

    return;
}

			$this->form_fields = array(
				'enabled' => array(
					'title'       => __('Enable/Disable', 'text-domain'),
					'label'       => __('Enable Ez-Verse', 'text-domain'),
					'type'        => 'checkbox',
					'description' => __('This enable the Ez-Verse which allow to accept payment through UPI.', 'text-domain'),
					'default'     => 'no',
					'desc_tip'    => true
				),
				'title' => array(
					'title'       => __('Title', 'text-domain'),
					'type'        => 'text',
					'description' => __('This controls the title which the user sees during checkout.', 'text-domain'),
					'default'     => __('Ez-Verse', 'text-domain'),
					'desc_tip'    => true,
				),
				'description' => array(
					'title'       => __('Description', 'text-domain'),
					'type'        => 'textarea',
					'description' => __('This controls the description which the user sees during checkout.', 'text-domain'),
					'default'     => __('Ez-Verse.', 'text-domain'),
				),

				'client_id' => array(
					'title'       => __('Client ID', 'text-domain'),
					'type'        => 'text'
				),

				'client_secret' => array(
					'title'       => __('Client Secret', 'text-domain'),
					'type'        => 'text'
				),

				'default_email' => array(
					'title'       => __('Default Email', 'text-domain'),
					'type'        => 'text',
					'description' => __('Default email is used when user is not logged in and making payment.', 'text-domain'),
				),

				'ipn' => array(
					'title' => 'Webhook URL',
					'type' => 'hidden',
					'description' => '' . site_url('wc-api/ez-verse-' . get_option('Ezverse_WooCommerce_uuid'))
				),


			);
		}

		public function process_payment($order_id)
        {
             global $woocommerce;

             $order = wc_get_order($order_id);
             $timestamp = time();
        	 $phone = $order->get_billing_phone();
             $phone = preg_replace('/\D+/', '', (string) $phone);
             $phone = substr($phone, -10);
             if (strlen($phone) !== 10) {
             $phone = '9999999999'; 
        }
    $public_order_id = $order->get_meta('_public_order_id');

    $payload = [
        'client_id'       => $this->client_id,
        'client_secret'   => $this->client_secret,
        'order_id'        => $public_order_id,
        'amount'          => (float) $order->get_total(),
        'customer_name'   => substr($order->get_formatted_billing_full_name(), 0, 255),
        'customer_mobile' => $phone,
        'redirect_url'    => $order->get_checkout_order_received_url(),
        'remark1'         => get_bloginfo('name'),
        'remark2'         => home_url(),
    ];

    $signature = $this->ezverse_signature([
        $this->client_id,
        $payload['order_id'],
        $payload['amount'],
        $timestamp,
    ]);

    $response = wp_remote_post(
        $this->base_url . '/api/v2/create-order',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-TIMESTAMP'  => $timestamp,
                'X-SIGNATURE'  => $signature,
            ],
            'body'    => json_encode($payload),
            'timeout' => 20,
        ]
    );

    if (is_wp_error($response)) {
        return [
            'result'   => 'failure',
            'messages' => 'EZVERSE connection failed',
        ];
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (
        !is_array($body) ||
        empty($body['status']) ||
        empty($body['result']) ||
        !is_array($body['result']) ||
        empty($body['result']['payment_url'])
    ) {
        $error_message = 'Payment failed';

        if (isset($body['message'])) {
            if (is_string($body['message'])) {
                $error_message = $body['message'];
            } elseif (is_array($body['message'])) {
                $error_message = implode(', ', array_map('strval', $body['message']));
            }
        }

        return [
            'result'   => 'failure',
            'messages' => $error_message,
        ];
    }

    update_post_meta($order_id, '_ezverse_order_id', $payload['order_id']);
    $order->add_order_note('EZVERSE payment initiated');
    $woocommerce->cart->empty_cart();

    return [
        'result'   => 'success',
        'redirect' => $body['result']['payment_url'],
    ];
    }



		
	   public function check_payment_status($order_id)
    {
    $order = wc_get_order($order_id);

    if (!$order || $order->is_paid()) {
        return;
    }

    $timestamp = time();
    
    $public_order_id = $order->get_meta('_public_order_id');

    $payload = [
        'client_id'     => $this->client_id,
        'client_secret' => $this->client_secret,
        'order_id'      => $public_order_id,
    ];

    $signature = $this->ezverse_signature([
        $this->client_id,
        $payload['order_id'],
        $timestamp,
    ]);

    $response = wp_remote_post(
        $this->base_url . '/api/v2/check-order-status',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-TIMESTAMP'  => $timestamp,
                'X-SIGNATURE'  => $signature,
            ],
            'body' => json_encode($payload),
            'timeout' => 15,
        ]
    );

    if (is_wp_error($response)) {
        return;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (
        !empty($body['status']) &&
        !empty($body['result']['order_status']) &&
        $body['result']['order_status'] === 'SUCCESS'
    ) {
        $utr = $body['result']['utr'] ?? null;

        $order->payment_complete($utr);
        $order->add_order_note(
            'EZVERSE payment SUCCESS via status check. UTR: ' . ($utr ?: 'N/A'),
            true
        );

        if ($utr) {
            update_post_meta($order->get_id(), '_ezverse_utr', $utr);
        }
    }

    }

	}

    add_action('woocommerce_thankyou', function ($order_id) {

    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    
    if ($order->get_payment_method() !== 'ez-verse') {
        return;
    }

    
    if ($order->is_paid()) {
        return;
    }

    if (!class_exists('UPI_Payment_Gateway')) {
        return;
    }

    $gateway = new UPI_Payment_Gateway();
    $gateway->check_payment_status($order_id);
});


add_action('wp_ajax_ezverse_reset_profile', function () {

    delete_option('ezverse_profile');

    wp_send_json_success();
});



add_action('admin_enqueue_scripts', function ($hook) {

    // Only load on WooCommerce settings
    if ($hook !== 'woocommerce_page_wc-settings') {
        return;
    }

    wp_enqueue_script(
        'ezverse-admin',
        plugin_dir_url(__FILE__) . 'admin/js/ez-verse-admin.js',
        ['jquery'],
        '1.0.1',
        true
    );

    wp_localize_script(
        'ezverse-admin',
        'ezverseAdmin',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ezverse_reset_nonce'),
        ]
    );
});
});
