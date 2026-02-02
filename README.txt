# EZ-VERSE – UPI Payment Gateway for WooCommerce

EZ-VERSE is a secure and lightweight **UPI payment gateway for WooCommerce** that allows merchants to accept UPI payments **directly into their own UPI ID** with **zero transaction charges**.

No middlemen.  
No percentage cuts.  
Fast, direct settlements.

---

## 🔌 Plugin Information

- **Plugin Name:** EZ-VERSE
- **Plugin Slug:** ez-verse
- **Payment Method ID:** ezverse-upi
- **Text Domain:** ezverse-woocommerce
- **Author:** Akash Chakraborty
- **Website:** https://ezverse.in
- **License:** GPL-2.0+

---

## ✨ Features

- ✅ Accept UPI payments (GPay, PhonePe, Paytm, BHIM, etc.)
- ✅ Direct settlement to your **own UPI ID**
- ✅ **Zero per-transaction charges**
- ✅ Secure HMAC SHA-256 API signing
- ✅ Automatic order confirmation via **Webhook**
- ✅ Backup payment verification on Thank You page
- ✅ Compatible with:
  - WooCommerce Classic Checkout
  - WooCommerce Blocks Checkout
- ✅ PHP 8.0 / 8.1 / 8.2 compatible
- ✅ Built-in debug logging for testing

---

## 📦 Requirements

- WordPress 6.0+
- WooCommerce 7.0+
- PHP 8.0+
- Active EZ-VERSE account

---

## 📥 Installation

1. Upload the plugin folder to:
2. Activate **EZ-VERSE** from **WordPress → Plugins**
3. Navigate to:

4. Enter your credentials:
- Client ID
- Client Secret
5. Enable the gateway and save settings

---

## ⚙️ Configuration

### Required Fields

- **Client ID** – Provided by EZ-VERSE
- **Client Secret** – Used for secure API signing
- **Webhook URL** – Auto-generated and read-only

⚠️ Make sure your webhook URL is publicly accessible.

---

## 🔐 Security

- All payment requests are signed using **HMAC SHA-256**
- Webhook signatures are verified before updating orders
- Invalid or tampered requests are rejected automatically

---

## 🔁 Payment Flow

1. Customer places an order
2. EZ-VERSE creates a payment via API
3. Customer is redirected to secure UPI payment page
4. Payment confirmation occurs via:
- ✅ Webhook (primary)
- 🔁 Order status check on Thank You page (fallback)
5. Order is automatically marked as **Paid / Completed**

---

## 🧪 Debug & Logging

For testing and staging environments, detailed logs are written to:


Logs include:
- Payment initiation
- API raw responses
- Validation errors
- Webhook callbacks
- Payment success & failure states

⚠️ Recommended to disable or rotate logs on production sites.

---

## 🧩 WooCommerce Blocks Support

EZ-VERSE fully supports **WooCommerce Blocks Checkout**.

- Uses a consistent payment method ID
- Works seamlessly with modern checkout flows

---

## 🛠 Developer Notes

- Gateway Type: Redirect + Webhook
- Fallback payment verification included
- Fully compatible with PHP 8.2 strict mode
- Follows WooCommerce payment gateway standards

---

## 📝 Changelog

### v1.0.1
- Initial public release
- Secure UPI payment flow
- Webhook & fallback status verification
- WooCommerce Blocks support
- Debug logging added

---

## 📄 License

This plugin is licensed under **GPL-2.0+**  
You are free to modify and redistribute under the same license.

---

## 👨‍💻 Author & Support

**Akash Chakraborty**  
🌐 https://ezverse.in  
📧 ezstore2405@gmail.com  

For subscriptions, API access, and support, visit the EZ-VERSE website.

---

## ⭐ Feedback

If you find EZ-VERSE useful, consider leaving a ⭐️ review or sharing feedback to help improve the plugin.

---

**Built with ❤️ for WooCommerce & UPI**

