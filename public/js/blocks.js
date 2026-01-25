import { registerPaymentMethod } from '@woocommerce/blocks-registry';

const UpiPaymentMethod = {
	name: 'ez-verse', // must match $this->name in PHP
	label: 'Pay via UPI',
	content: (
		<p>
			Use your UPI app to scan the QR code or enter the UPI ID. Complete the
			payment and then confirm.
		</p>
	),
	edit: () => null,
	canMakePayment: () => true,
	supports: {
		features: ['products'],
	},
	ariaLabel: 'UPI Payment Gateway',
};

registerPaymentMethod(UpiPaymentMethod);
