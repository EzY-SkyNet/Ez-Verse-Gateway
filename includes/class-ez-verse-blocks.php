<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

final class Ezverse_WooCommerce_Blocks extends AbstractPaymentMethodType implements IntegrationInterface {

    protected $name = 'ez-verse'; // Must match the gateway_id in your JS

	public function enqueue_payment_method_assets() {
        wp_register_script(
            'upi-gateway-blocks',
            plugins_url( '../public/js/blocks.js', __FILE__ ),
            [ 'wc-blocks-registry', 'wp-element', 'wp-html-entities' ],
            Ezverse_WooCommerce_VERSION,
            true
        );
    }

    public function initialize() {
        // Load necessary data or settings
    }

    public function get_payment_method_script_handles() {
        return ['upi-gateway-blocks'];
    }

    public function get_payment_method_data() {
        return [
            'title'       => 'Pay with UPI',
            'description' => 'Use your UPI app to complete payment.',
            'supports'    => ['products'],
        ];
    }
}
