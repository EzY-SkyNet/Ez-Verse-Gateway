# EZ-VERSE – UPI Payment Gateway for WooCommerce

EZ-VERSE is a secure, lightweight **UPI payment gateway for WooCommerce**. It empowers merchants to accept UPI payments **directly into their own UPI ID** with **zero transaction charges** and no intermediaries. The plugin emphasizes direct settlements, security, and seamless integration with WooCommerce.

---

## 🔌 Plugin Information

EZ-VERSE is designed for WooCommerce merchants who wish to accept UPI payments directly and efficiently. Below is a summary of the plugin’s main technical details:

| Parameter          | Value                              |
|--------------------|------------------------------------|
| **Plugin Name**    | EZ-VERSE                           |
| **Plugin Slug**    | ez-verse                           |
| **Payment Method** | ezverse-upi                        |
| **Text Domain**    | ezverse-woocommerce                |
| **Author**         | Akash Chakraborty                  |
| **Website**        | [https://ezverse.in](https://ezverse.in) |
| **License**        | GPL-2.0+                           |

---

## ✨ Features

EZ-VERSE offers a robust set of features for modern WooCommerce stores:

- ✅ Accepts UPI payments (GPay, PhonePe, Paytm, BHIM, etc.)
- ✅ Funds are settled directly to your **own UPI ID**
- ✅ **Zero per-transaction charges** (no gateway fees)
- ✅ Secure HMAC SHA-256 API signing for all communications
- ✅ Automatic order confirmation via **Webhook**
- ✅ Backup payment verification on the Thank You page
- ✅ Compatible with:
  - WooCommerce Classic Checkout
  - WooCommerce Blocks Checkout
- ✅ Supports PHP 8.0, 8.1, and 8.2
- ✅ Built-in debug logging for easy testing and troubleshooting

---

## 📦 Requirements

To use EZ-VERSE, ensure your environment meets the following requirements:

- WordPress 6.0 or higher
- WooCommerce 7.0 or higher
- PHP 8.0 or higher
- An active EZ-VERSE account

---

## 📥 Installation

Follow these steps to install and set up the EZ-VERSE plugin:

1. Upload the plugin folder to your WordPress installation.
2. Activate **EZ-VERSE** from the **WordPress → Plugins** admin menu.
3. Navigate to WooCommerce payment settings.
4. Enter your EZ-VERSE credentials:
   - Client ID
   - Client Secret
5. Enable the gateway and save your settings.

---

## ⚙️ Configuration

You must provide certain details for the plugin to function:

### Required Fields

- **Client ID** – Provided by EZ-VERSE.
- **Client Secret** – Used for secure API signing.
- **Webhook URL** – Auto-generated and read-only.

> ⚠️ Ensure your webhook URL is publicly accessible so payment confirmations are received.

---

## 🔐 Security

Security is a core aspect of EZ-VERSE:

- All payment requests are signed using **HMAC SHA-256** for integrity.
- The plugin verifies webhook signatures before updating any order status.
- The system rejects any invalid or tampered requests automatically.

---

## 🔁 Payment Flow

The plugin orchestrates UPI payments securely via the following process:

1. Customer places an order.
2. EZ-VERSE creates a payment through its API.
3. Customer is redirected to a secure UPI payment page.
4. Payment confirmation occurs via:
   - ✅ Webhook (primary)
   - 🔁 Status check on the Thank You page (fallback)
5. Order is automatically updated as **Paid / Completed**.

### Payment Flow Diagram

```mermaid
flowchart TD
    Customer[Customer Places Order]
    API[EZ-VERSE API Creates Payment]
    Redirect[Customer Redirected to UPI Payment Page]
    Webhook[Webhook Payment Confirmation]
    Fallback[Order Status Check on Thank You Page]
    Paid[Order Marked as Paid / Completed]

    Customer --> API
    API --> Redirect
    Redirect --> Webhook
    Redirect --> Fallback
    Webhook --> Paid
    Fallback --> Paid
```

---

## 🧪 Debug & Logging

EZ-VERSE includes detailed debug logging for testing and troubleshooting:

- Logs payment initiation events
- Records raw API responses
- Captures validation errors
- Logs webhook callbacks
- Tracks payment success and failure states

> ⚠️ It is recommended to disable or rotate logs on production sites to maintain security and privacy.

---

## 🧩 WooCommerce Blocks Support

EZ-VERSE is fully compatible with **WooCommerce Blocks Checkout**. It uses a consistent payment method ID and works seamlessly with both classic and modern WooCommerce checkout flows.

---

## 🛠 Developer Notes

- **Gateway Type**: Combination of Redirect & Webhook
- Includes fallback payment verification
- Fully compatible with PHP 8.2 (strict mode)
- Adheres to WooCommerce payment gateway standards and best practices

---

## 📝 Changelog

### v1.0.1

- Initial public release
- Secure UPI payment flow
- Webhook & fallback status verification
- WooCommerce Blocks support
- Debug logging

---

## 📄 License

EZ-VERSE is released under the **GPL-2.0+** license. You can modify and redistribute the plugin under the same license terms.

---

## 👨‍💻 Author & Support

- **Author**: Akash Chakraborty  
- 🌐 [https://ezverse.in](https://ezverse.in)  
- 📧 ezstore2405@gmail.com  

For subscriptions, API access, and support, visit the [EZ-VERSE website](https://ezverse.in).

---

## ⭐ Feedback

If you find EZ-VERSE helpful, please consider leaving a review or sharing your feedback to help improve the plugin.

---

**Built with ❤️ for WooCommerce & UPI**

---

```card
{
    "title": "Zero Transaction Charges",
    "content": "EZ-VERSE lets you accept UPI payments directly with no per-transaction fees. Settle funds instantly to your own UPI ID."
}
```

```card
{
    "title": "Primary + Fallback Payment Confirmation",
    "content": "EZ-VERSE uses both webhook and on-site status checks to ensure order completion is reliable and accurate."
}
```

```card
{
    "title": "Security Best Practices",
    "content": "All transactions are HMAC SHA-256 signed and every webhook is verified before updating order status."
}
```
