=== WP Giosg ===
Contributors: cyclonecode
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VUK8LYLAN2DA6
Tags: giosg, chat, live chat, basket, customer support, woocommerce
Requires at least: 3.1.0
Tested up to: 6.0.1
Requires PHP: 5.6
Stable tag: 1.0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin integrates the live chat from giosg.com.

== Description ==

This plugin adds a script tag that will enable the live chat from [www.giosg.com](http://www.giosg.com).

The only thing that needs to be done is to add your giosg company ID under the settings page.

== Basket ==

The giosg basket can be integrated by turning on `Enable basket` from the settings page.
In giosg the currency for the basket needs to be configured to match the default currency for your
wordpress cart. At this point only **woocommerce** is supported.

= Resources =

To read more about the giosg live chat visit: [www.giosg.com](http://www.giosg.com)

== Frequently Asked Questions ==

== Support ==

If you run into any trouble, don’t hesitate to add a new topic under the support section:
[https://wordpress.org/support/plugin/wp-giosg](https://wordpress.org/support/plugin/wp-giosg)
You can also try [slack](https://join.slack.com/t/cyclonecode/shared_invite/zt-6bdtbdab-n9QaMLM~exHP19zFDPN~AQ).

== Installation ==

1. Upload the wp-giosg plugin to the **/wp-content/plugins/** directory,
2. Activate the plugin through the **Plugins** menu in WordPress.

== Upgrade Notice ==

= 1.0.6 =
Fix a bug where the basket was not marked as frozen in giosg.

= 1.0.5 =
Fixed a bug where is_plugin_active() was not defined.
Add basket script and decode cart.

== Screenshots ==

1. A closed popup with basket enabled.
2. The chat and basket shown in giosg.

== Changelog ==

= 1.0.8

- Update: short array syntax.
- Fix: Faulty clean() method in settings class.
- Fix: Remove unused code from settings class.
- Add: phpstan for development.
- Update: Bump packages.
- Remove: Singleton class.
- Add: DI Container.
