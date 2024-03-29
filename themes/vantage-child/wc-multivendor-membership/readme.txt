﻿=== WooCommerce Memberships for Multivendor Marketplace ===
Contributors: wclovers
Tags: woocommerce membership, subscription, members, multivendor marketplace, multi vendor 
Donate link: https://www.paypal.me/wclovers/25usd
Requires at least: 4.4
Tested up to: 4.9
WC requires at least: 3.0
WC tested up to: 3.2.0
Requires PHP: 5.6
Stable tag: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple woocommerce memberships plugin for offering free and premium subscription for your multi-vendor marketplace - WC Marketplace, WC Vendors & Dokan.

== Description ==
A simple woocommerce memberships plugin for offering FREE AND PREMIUM SUBSCRIPTION for your multi-vendor marketplace (WC Marketplace, WC Vendors, WC Product Vendors & Dokan).

You may set up unlimited membership levels (example: free, silver, gold etc) with different pricing plan, capabilities and commission.

[youtube https://www.youtube.com/watch?v=CVRQnrm7nC0]

= Pay for Product Option =

This will allow you to setup a system where vendor has to pay before adding a product -

[youtube https://youtu.be/WwFHorx93Fw]

> Documentations:-
>
> [WCFM - Membership](https://wclovers.com/knowledgebase/wcfm-membership/)
> [Video Tutorials](https://wclovers.com/wcfm-tutorials/)

> Experience membership subscription here -
>
> [Membership Demo](http://wcvendors.wcfmdemos.com/vendor-membership/)

= HAVE SUPPORT OF MY MULTI-VENDOR =

WooCommerce Multivaendor Membership works with all popular marketplace add-ons -

* WC Marketplace
* WC Vendors and WC Vednors PRO
* Dokan Lite and Dokan PRO
* WooCommerce Product Vendors

= HAVE FREE AND PAID MEMBERSHIPS =

You can configure it to have free and/or paid memberships on your site. Paid membership payment is handled securely via PayPal.

Both one time and recurring/subscription payments are supported.

= HAVE SUPPORT OF MY PAYMENT GATEWAYS =

You can setup membsership subsctiptions with all flexibilities and with different intgegrated payment options as well - 

* FREE
* PayPal
* Bank Transfer
* Stripe

Are you missing your payment gateway then setup using WC Products -

[youtube https://youtu.be/SfOMIxNfr3w]

= HAVE DIFFERENT COMMISSION MODEL =

You can configure different commission structure for each membership level. Fixed and percent both are supported.

= HAVE DIFFERENT CAPABILITIES =

You can assign different capability module for each membership level.

You may create totally different capability group with all flexibilities (example: product limit, categories, product types etc) for each membership level.

But this is not directly part of this add-on, Capability modules are come from [WCFM - Groups & Staffs](http://wclovers.com/product/woocommerce-frontend-manager-groups-staffs/). 

= MEMBERSHIP DETAILS = 

Your vednors will have full details of their membership under their WCFM dashboard.

They may also change their subscription plan any time - upgrade or downgrade.

= WHAT ABOUT NON-VENDOR USERS = 

Any user of the site (except Administrator and Shop manager) may apply for membership subscription.

Just to mention, it works as a add-on for [WooCommerce Frontend Manager](https://wordpress.org/plugins/wc-frontend-manager).

= Translations =

- Potuguese (Thanks to Rafael Sartori)
- Spanish   (Thanks to @ffthernandez)
- German    (Thanks to Ciao)

= Feedback = 

All we want is love. We are extremely responsive about support requests - so if you face a problem or find any bugs, shoot us a mail or post it in the support forum, and we will respond within 6 hours(during business days). If you get the impulse to rate the plugin low because it is not working as it should, please do wait for our response because the root cause of the problem may be something else. 

It is extremely disheartening when trigger happy users downrate a plugin for no fault of the plugin. 

Feel free to reach us either via our [support forum](https://wclovers.com/forums) or [WordPress.org](https://wordpress.org/support/plugin/wc-multivendor-membership), happy to serve anything you looking for. 

Really proud to serve and enhance [WooCommerce](http://woocommerce.com).

Be with us ... Team [WC Lovers](https://wclovers.com)

== Installation ==

= Minimum Requirements =

* WordPress 4.7 or greater
* WooCommerce 3.0 or greater
* PHP version 5.6 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of WooCommerce Frontend Manager, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "WooCommerce Multivendor Membership" and click Search Plugins. Once you've found our eCommerce plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading our eCommerce plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== FAQ ==

NONE.

== Screenshots ==

1. Membership Plan Table
2. Pay for Product
3. Membership Dashboard
4. Membership Subscription Types
5. Membership Recurring Subscription with Trial
6. New Membership
7. Membership Commission setup
8. Membership Capability Group
9. Membership Table Styling
10. Membership Thank You Content
11. Membership Payment Page
12. Membership Thank You Page
13. Membership Registration Page
14. Membership General Settings
15. Membership Feature List Set up
16. Membership Welcome Email
17. Membership Details - Cancel & Upgrade

== Changelog ==

= 2.2.0 =
* Feature   - Membership change on WC Subscription staus change option added
* Feature   - Membership auto-renew on WC Subscription renew option added
* Feature   - Existing members extend their existing subscription option added
* Enhance   - Vendor registration using WC checkout Billing address auto-fill using pre-defind store address  
* Enhance   - Is valid membership check added
* Enhance   - Existing users vendor registration address and phone number field auto-populate using billing address and phone data
* Tweak     - On Membership change/renew expiry date update as per vendors already saved renewal date
* Tweak     - WC deprecated "get_checkout_url" function replace with "wc_get_checkout_url" 
* Tweak     - Membership manage from setting page option added - enable by filter "wcfm_is_allow_membership_manage_under_setting"
* Fixed     - WCFM Marketplace membership expiry not working issue resolved
* Fixed     - WCFM Marketplace membership commission not working issue resolved

= 2.1.4 =
* Feature   - Vendor Registration form Google Captcha support added
* Feature   - Membership plan subscribe for only once restriction option added
* Feature   - Vendor registration form field "First Name", "Last Name", "User Name" manage option added  
* Enhance   - Registration form WC standard Username and email validation added

= 2.1.3 =
* Feature   - Membership next renewal date update option added
* Tweak     - Disable vendor products status chage to "draft", may restrict by return false to this filter "wcfm_is_allow_disable_vendor_product_draft" 
* Fixed     - Membership expiration reminder notification at wrong time issue resolved

= 2.1.2 =
* Feature   - [WCFM - Marketplace](https://wordpress.org/plugins/wc-multivendor-marketplace/) compatibility added
* Feature   - WCFM Marketplace commission rule support added
* Fixed     - On membership change profile information reset issue resolved

= 2.1.1 =
* Feature   - Custom plan page support added
* Enhance   - Plan details variable "{plan_details}" support added in welcome email
* Enhance   - Reminder notification disable option added
* Fixed     - Thank You content save quote issue resolved 

= 2.1.0 =
* Feature   - Free membership expiry limit option added
* Feature   - One time membership expiry limit option added
* Enhance   - WCfM 4.2.0 compatibility added
* Fixed     - Stripe class conflict issue resolved
* Fixed     - On membership change next payment schedule reset issue resolved
* Fixed     - Membership Expiry billing period count check issue resolved

= 2.0.7 =
* Feature   - Without membership vendor registration option added
* Enhance   - WCfM 4.1.8 compatibility added
* Enhance   - Subscribe short code button vendor current plan check added
* Enhance   - Vendor Approval require global rule setting added
* Tweak     - Multi-vendor plugin missing restriction added
* Fixed     - Approval pending vendor can re-apply issue resolved 

= 2.0.6 =
* Fixed     - On vendor approval user data missing issue resolved 
* Fixed     - Email verification code send though setting disabled issue resolved

= 2.0.5 =
* Enhance   - Memberhsip cancel product status change WPML compatibility added
* Enhance   - Memberhsip expiry product status change WPML compatibility added
* Tweak     - On subscription cancel or expire old membership data removed 
* Tweak     - Membership notification merge with WCfM Notification module
* Fixed     - On vednor disable membership & group user count reset issue resolved
* Fixed     - Disable vendor re-subscription issue resolved 
* Fixed     - Due to hidden plans membership table display issue resolved

= 2.0.4 =
* Feature   - Memberhsip hide form plan table option added
* Tweak     - Disable vendors allowed to subscribe membership plans
* Fixed     - Subscribe button short code "subscribe_now" parameter not working issue resolved 

= 2.0.3 =
* Feature   - Subscribe Now button label define option added
* Feature   - Subscription Next Payment notification option added
* Feature   - Membership Expiry Rules option added
* Enhance   - Subscribe button short code "background" parameter added to define own button background color
* Enhance   - Subscribe button short code "color" parameter added to define own button color
* Fixed     - Repetative renewal notification issue resolved 

= 2.0.2 =
* Feature   - Application Reject notification added
* Feature   - Membership change notification added
* Enhance   - Subscribe button short code "subscribe_now" parameter added to define own button label
* Enhance   - Subscribe button short code button custom style support added
* Fixed     - Vendor setting data reset on membership change issue resolved 
* Fixed     - WC Marketplace membership wise commission update issue resolved
* Fixed     - Some minor CSS issues resolved

= 2.0.1 =
* Feature   - Application Reject Rule option added
* Fixed     - Membership Cancel Email send in text format issue resolved 

= 2.0.0 =
* Feature   - Pay for Product limit module added - [Documentation](https://youtu.be/WwFHorx93Fw)
* Feature   - Membership renewal reminder notification setting option added
* Feature   - Membership cancel rules defined
* Feature   - Membership Features are now dragable to re-arrange
* Feature   - Registration custom fields are now dragable to re-arrange
* Enhance   - Membership cancel rules implemented
* Enhance   - Membership table WPML string translation compatibility added
* Enhance   - Registration custom fields WPML string translation compatibility added
* Enhance   - Registration custom fields Toolset user fields compatibility added
* Fixed     - Membership table display responsive issue resolved
* Fixed     - Custom CSS unlink PHP warning issue resolved 
* Fixed     - Some CSS issues resolved

= 1.3.2 =
* Feature   - Registration email verification option added
* Enhance   - WC 3.4.0 compatibility added
* Tweak     - Free and Cancel membership replaced by Basic Memebrship option
* Tweak     - Membership setting page re-arranged
* Fixed     - Membership table display CSS issues resolved

= 1.3.1 =
* Enhance   - WCfM 4.1.0 compatibility added
* Enhance   - WC Vendors 2.0 compatibility added

= 1.3.0 =
* Feature   - Membership purchase using WC Product / Checkout option added, now you may use any WC payment options. (https://youtu.be/SfOMIxNfr3w)

= 1.2.4 =
* Fixed   - Stripe recurring PHP warning issue resolved

= 1.2.3 =
* Fixed   - Stripe class redifined from other plugins issue resolved
* Fixed   - Plan price empty PHP warning issue resolved

= 1.2.2 =
* Feature - Stripe payment support added
* Enhance - Membership emails wrap with WC email template
* Enhance - Translations updated
* Fixed   - Membership table long price display issue resolved
* Fixed   - Free membership registration CSS incuded in all pages issue resolved
* Fixed   - Some spelling corrected

= 1.2.1 =
* Feature - Vendor store phone static field support added
* Feature - Membership subscribe button short code "[wcfmvm_subscribe id="*"]" support added
* Enhance - WCfM 4.0.0 compatibility added
* Fixed   - Membership plan change issue resolved
* Fixed   - On user delete membership delete issue resolved
* Fixed   - Membership registration state field NULL issue resolved
* Fixed   - Membership pay mode display issue resolved

= 1.2.0 =
* Feature - Free membership registration separate page added
* Feature - "wcfm_vendor_registration" short code added 
* Enhance - Membership conditional delete option added
* Enahnce - "wcfm_membership_price_display" filter added for membership price display modification
* Enhamce - Admin now allowed to view Memberships page

= 1.1.9 =
* Feature - Registration Terms & Condition check support added 
* Enhance - Free membership payment step change to "Confirmation"
* Enahnce - Vendor approval pop-up changed to colorbox

= 1.1.8 =
* Fixed - Translation loading issue resolved

= 1.1.7 =
* Feature - WCFM vendor custom badges compatiblity added
* Enhance - Spanish (Thanks to @ffthernandez) translation added

= 1.1.6 =
* Feature - Membership registration store address field support added
* Enhance - Portuguese(Brazil) translation added
* Fixed   - Membership subscription page WC block JS missing issue resolved

= 1.1.5 =
* Fixed   - Membership plan change old membership users list update issue resolved
* Fixed   - Membership plan change old group users list update issue resolved
* Fixed   - Email content save escape charatater issue resolved

= 1.1.4 =
* Feature - Membership Payment terms option added
* Fixed   - Membership table description display issue resolved

= 1.1.3 =
* Feature - Membership section added in vendor manage page
* Enhance - Registration additional info displayed at admin Vendor Details & vendor Profile page
* Fixed   - Free trial period issue resolved
* Fixed   - Without feature membership table display issue resolved
* Fixed   - Membership table description display issue resolved
* Fixed   - Too many membership option box display issue resolved

= 1.1.2 =
* Feature - Membership section added in vendor manage page
* Enhance - Registration additional info displayed at admin Vendor Details & vendor Profile page
* Fixed   - Without feature membership table display issue resolved
* Fixed   - Membership table description display issue resolved
* Fixed   - Too many membership option box display issue resolved

= 1.1.1 =
* Feature - Membership Subscription pending approval option added

= 1.0.4 =
* Feature - Membership plan change & upgrade option added
* Feature - New subscription Admin Email notification added
* Feature - Membership subscription cancel opton added
* Fixed   - Subsciption process steps responsive issue resolved

= 1.0.3 =
* Feature - Bank Transfer payment option added
* Fixed   - Membership table mobile view issue resolved
* Fixed   - Membership spell typo error fixed

= 1.0.2 =
* Enhance - Membership Plan table redefined

= 1.0.1 =
* Basic set up version release

= 1.0.0 =
* Initial version release

== Upgrade Notice ==

= 2.2.0 =
* Feature   - Membership change on WC Subscription staus change option added
* Feature   - Membership auto-renew on WC Subscription renew option added
* Feature   - Existing members extend their existing subscription option added
* Enhance   - Vendor registration using WC checkout Billing address auto-fill using pre-defind store address  
* Enhance   - Is valid membership check added
* Enhance   - Existing users vendor registration address and phone number field auto-populate using billing address and phone data
* Tweak     - On Membership change/renew expiry date update as per vendors already saved renewal date
* Tweak     - WC deprecated "get_checkout_url" function replace with "wc_get_checkout_url" 
* Tweak     - Membership manage from setting page option added - enable by filter "wcfm_is_allow_membership_manage_under_setting"
* Fixed     - WCFM Marketplace membership expiry not working issue resolved
* Fixed     - WCFM Marketplace membership commission not working issue resolved