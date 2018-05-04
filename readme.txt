=== Plugin Name ===
Contributors: Zaglov, imbaa
Tags: affiliate
Requires at least: 4.5.0
Stable tag: 1.9.2

Affilipus Plug-In

== Description ==

Affilipus Plug-In

== Changelog ==

= 1.9.2 =

* Removed Plugin Updater

= 1.9.1 =

* Removed further dependencies
* Removed Webgains as it never was finished

= 1.9.0 =

* Removed the necessity of a license

= 1.8.0 =

* Fixed some major compatibility issues with third party plug-ins

= 1.7.22 =

* Deisplay temporary products in Backend

= 1.7.15 =

* Bugfixes

= 1.7.14 =

* Fixed Problems with affilinet which occured when only one online shop is available

= 1.7.13 =

* Shop Namen können nun im Backend angepasst werden
* Button Beschriftung für Produkte mit Preisvergleich angepasst. Sobald ein Produkt ein Vergleichsprodukt hat, kann der Text des BuyButtons nicht mehr extern angepasst werden und lautet "Jetzt bei XXX ansehen"
* Bugfixes bei Produkt Aktualisierungen

= 1.7.12 =

* Fixed problems with custom procuts

= 1.7.11 =

* Fixed problems with product search

= 1.7.10 =

* Fixed Config of price list

= 1.7.9 =

* Minor bugfixes

= 1.7.8 =

* Kleine Verbesserungen Preisvergleich

= 1.7.7 =

* Preisvergleich automatisch ausklappen
* Ausgabe des Affiliate Partner Namens in Templates möglich machen

= 1.7.6 =

* Fix: Preisvergleich Sortierung

= 1.7.5 =

* Notice Fixes

= 1.7.4 =

* Enable Templates for Review Box

= 1.7.3 =

* Fixed „price not available"

= 1.7.2 =

* Fixed frontend errors

= 1.7.1 =

* Fixed notices

= 1.7.0 =

* Preisvergleich Funktion öffentlich verfügbar

= 1.6.7 =

* Display Subproducts in backend
* Preistabelle Konfigurationsmöglichkeiten
* Preisvergleich Ausgabe in Vergleichstabellen



= 1.6.6 =

* Add Subproducts to a product

= 1.6.5 =

* Affilinet Bugfixes

= 1.6.3 =

* Licensing improvements

= 1.6.0 =

* Affilipus Product Box Shortcode

= 1.5.14 =

* Fixed: When changing an affilipus slug you had to safe twice so that the rewrite rules would be applied. Now you don't

= 1.5.13 =

* Reworked licensing routine to handle downtimes of license servers and EDD problems better

= 1.5.12 =

* Updated Smarty Template Engine

= 1.5.11=

* Removed Support for PHP 5.4 - get rid of that shit!

= 1.5.9 =

* Bugfix: Quickedit löscht Vergleichstabellen Werte
* Bugfix: Produktbild verursache Fehler bei CDN-Bilden
* Load Amazon Reviews iframe asynchronously

= 1.5.8 =

* Fixed: If you remove an license key and insert a new one the plugin would throw errors until you refresh the page. now it shouldn't

= 1.5.7 =

* Probably fixed issues with creating folders on some servers

= 1.5.6 =

* Maybe fixed issues with creating folders on some servers

= 1.5.5.7 =

* Removed Debug Information

= 1.5.5.6 =

* Minor improvements Cronjob Routines

= 1.5.5.4 =

* Fixed: Comments

= 1.5.5.3 =

* Amazon Requests will pause for one second so there should be less problems with Rate Limits by AMAZON. Products which are loaded through ASIN will take longer to load on first run though.

= 1.5.5.2 =

* Minor fix

= 1.5.5 =

* Fixed problems caused by products not available through ASIN

= 1.5.4 =

* Optimized temporary products
* Preloading Product Data via wp_cron
* Added Mass-Test Utility in Debug Mode

= 1.5.3 =

* Minor Bugfixes
* Do not display Amazon Errors if you are not admin

= 1.5.2 =

* Fixed Cronjob issues
* Minor improvements

= 1.5.1 =

* Fixed issues with activation

= 1.5.0 =

* Seperated product images from post thumbnails
* Fixed: notices on custom Products
* Fixed: Product count would count hidden products. Not it won't
* Fixed: Shortcode Generator Product Selector
* Fixed: Error Output for Topseller only for Admins
* Fixed: Zanox API Test
* Reworked Settings Page
* Prepared parts of the plugin for translation

= 1.4.5 =

* Edit EANs in Backend and add new ones if you want

= 1.4.4=

* Fix: Redirect to Settings if no credentials provided for Zanox

= 1.4.0 =

* Fix: Affilinet Cronjob would not execute through wp_cron, now it does
* Improvement: reduced the amount of requests made to amazon on shortcodes which rely on ASIN
* Added Affilinet Tutorial to Affilinet settings page
* Improvement: Sort Products in shortcode generator alphabetically

= 1.3.10 =

* Fixes

= 1.3.9 =

* Fixed: Grids with ASINs fix (empty spaces caused problems)
* Performance

= 1.3.8 =

* Changed default amazon price to offering_price
* New garbage collection for temporary data
* Added flush of wp_cache on affilipus cache flush

= 1.3.7 =

* The Amazon Prime Logo can now be hidden in the Amazon Partner Settings
* Bugfix: Product feeds would throw a 404, now they should not.

= 1.3.6 =

* Webgains API for Early Access Users

= 1.3.5 =

* Bugfixes

= 1.3.4 =

* Bugfixes

= 1.3.3 =

* Fixed Price List Shortcode

= 1.3.2 =

* Bugfixes

= 1.3.1 =

* Bugfixes

= 1.3.0 =

* Disable Cross Prices in Settings
* Changed Styling of Price Table
* Prepared preloading of Affilipus Data
* Fixed: Products in Loop - Shortcodes should now work

= 1.2.2 =

* Bugfixes

= 1.2.1 =

* Partner in Shop umbenannt in Preistabelle

= 1.2.0 =

* Product Grids via ASIN
* reworked temporary import
* Some changes under the hood

= 1.1.5 =

* Fixes

= 1.1.4 =

* Fixed Default Settings for Topseller Buy Button

= 1.1.3 =

* styling Adjustments

= 1.1.2 =

* styling Adjustments

= 1.1.1 =

* Fix: Spaces in Shortcodes (Product IDs) could cause problems
* Fix: Grid: Country was selectable although it shouldn't have been
* Fix: Grid: if the product picture would not link to anything, no product picture is displayed

= 1.1.0 =

* Product Grid Shortcode 3xX

= 1.0.2 =

* Fixed Notices

= 1.0.1 =

* Comptable Feature List Styling

= 1.0 =

* Affilinet Schnittstelle

= 0.24.0 =

* Object Caching für Affilipus Produktdaten

= 0.23.4 =

* Style Fixes

= 0.23.3 =

* Very important stability fixes
* Prepared for bigger comptables - Verfügbar für Early Access User

= 0.23.2 =

* Amazon:Fixed fetching of product brand
* Affilinet (Early Access): Bugfixes
* Affilinet (Early Access): Update Products in packs of 50
* Template Editor: Removed warnings
* Minor changes to admin interface

= 0.23.0 =

* Faster product search and refresh
* Backend Overview changes - prepared for Affilinet
* Changes to Price Table Template (Logo Path) - prepared for Affilinet
* Importer: only show additional product pictures if there are any available pictures
* Stylefix: linked amazon prime logo
* Enable Beta Features by license key

= 0.22.4 =

* Minor change to Comptables

= 0.22.3 =

* Du magst den Effekt, wenn Bilder den Rahmen einer Produktbox verlassen? Sorry - der ist jetzt wieder weg.

= 0.22.2 =

* Optimized Price import

= 0.22.1 =

* Flush Topsellers before Refresh of Prices

= 0.22.0 =

* Faster refetch of amazon prices. Up to 1.000% (one-fucking-thousand!) faster.

= 0.21.13 =

* Comptable Styling
* Comptable deliver image size large as default through CDN

= 0.21.12 =

* Fixed Affilinet Dashboard Output


= 0.21.11 =

* Update Procedure over SSL

= 0.21.10 =

* Really minor changes not worth mentioning

= 0.21.9 =

* Optimized Table Delivery
* Product Box Bulletpoint Styling
* Encrypt all API-Calls through SSL

= 0.21.8 =

* prepared Affilinet Product search

= 0.21.7 =

* Code Cleanup Backend
* Enabled Smarty Templates in Backend

= 0.21.5 =

* Performance Optimization in Backend

= 0.21.4 =

* Hotfix: In some cases price update might fuck up product date (Price and Reflink), Now it can not...
* Prepared for faster price update on Amazon

= 0.21.3 =

* Enable/Disable Prime Logo in tables
* Fixed: Display price Button

= 0.21.2 =

* Performance Optimierung

= 0.21.1=

* Sort Topsellers by Sales Rank

= 0.21.0 =

* Fallback if selected price is not available on update
* New Setting for Topseller: Display product features yes/no
* Deactivate prices in comptables through shortcode or setting
* Prime Logo in Comptables
* Link on Prime Logo in Comptables

= 0.20.4 =

* Load Stylesheets with version number
* Fixed activation routine
* Fixed: Topsellers could not display Features
* Fixed: ASINS with blank spaces in shortcode would cause problems on loading topsellers
* Fixed: Products with blank spaces in shortcode would cause problems on loading topsellers
* Fixed: Topsellers with ASINS as Value with products which can not be found would fail
* Fixed: Topsellers with products would cause problems if product is not in database or is not an affilipus product

= 0.20.3 =

* Fixed an error which would cause the wrong price to be displayed if a product is on sale
* Fixed an error which would cause cross prices not to display on topseller lists

= 0.20.2 =

* Fixed sorting of products via ASIN in individual topseller lists

= 0.20.1 =

* Fixed cache on individual topsellers
* Fixed fetching of individual topsellers

= 0.20.0 =

* Verlinkungsoptionen für Vergleichstabellen
* Übersichtsseite/Archivseite für alle Produkte um 404 Fehler zu reduzieren
* Modified stylesheet loading

= 0.19.15 =

* Added additional information for price refetch cron
* Vergleichstabellen Styling überarbeitet


= 0.19.14 =

* Cache Hotfix

= 0.19.13 =

* More Decimals for custom prices
* Fixed Product Backend for new Products
* Fixed Button Problem

= 0.19.12 =

* Produktwidget Hinweis: nur veröffentlichte Produkte
* Vergleichstabellen Bildstyling

= 0.19.10 =

* Notice Fixes in Backend

= 0.19.9 =

* Minor style changes

= 0.19.7 =

* Minor style changes

= 0.19.6 =

* Hotfix: Templates would not display due to handling of empty spaces in code

= 0.19.5 =

* Changed star-rating classes to imbaf-star-rating

= 0.19.4 =

* Import Products from Amazon even if they don't have an EAN code

= 0.19.3 =

* Enable upgradable Licenses and license keys without product name

= 0.19.2=

*Disable mail notifications on price updates if prices are missing

= 0.19.1 =

* Minor changes don't even want to talk about it. Deal with it.

= 0.19.0 =

* Fixed: Product Box Prime Logo Link
* Fixed: CDN Pictures would not display or be imported if no main picture is set in API
* Import Amazon Reviews on Price Update
* Import Amazon Reviews on Product Import
* Shortcode [affilipus_amazon_reviews]

= 0.18.9 =

* Fixed sideload stylesheet procedure
* New Shortcode: affilipus_button

= 0.18.8 =

* Enable Shortcodes in Feature List
* Updated Price Refetch Function with combined notifications
* Bugfix: Changing Affilipus license key would throw an error, no it won't


= 0.18.7 =

* Reworked updater

= 0.18.6 =

* Autoloading restructured
* Affilinet Admin Interface prepared
* Fixed: Deactivation Routine
* Enable/Disable link on title in product widget
* Set target to product page or partner page on title in product widget
* Set rel follow or nofollow on title link in product widget
* Set target to _self or _blank for title link in product widget
* Set target to product page or partner page on product price in product widget
* Set rel follow or nofollow on product price link in product widget
* Set target to _self or _blank for product price link in product widget
* Set target to product page or partner page on prime logo in product widget
* Set rel follow or nofollow on prime logo link in product widget
* Set target to _self or _blank for prime logo link in product widget
* Shortcode Generator autoloading fix

= 0.18.5 =

* Cronjob now outputs the shop's country from where the data was tried to be pulled
* Prepared Backend for Affilinet Partner

= 0.18.4 =

* Changed folder creation routine to wp_mkdir_p

= 0.18.3 =

* Widget: Image Size fix

= 0.18.2 =

* Widget Hotfix

= 0.18.1 =

* Compatibility Check Fix

= 0.18.0 =

* Enabled Template Caching
* Overhauled License routine
* Widget Product Recommendation: Select Product through dropdown
* Set external links to review on follow/nofollow (default follow)
* Display crutial info for admins only
* Enable product_box shortcode inside of default product page
* Bugfix: deactivation of affilipus styles did not work anymore
* Recommendation Widget with advanced settings
* Additional options for product boxes and toplists

= 0.17.6 =

* Bugfixes: Price calculation
* Bugfixes: Notices would prevent custom properties from beeing displayed in backend

= 0.17.5 =

* Color scheme for Grades in comptable
* Updated Shortcode Information
* Fixed some PHP exceptions

= 0.17.4 =

* Fixed: PHP Notices in Shortcodes
* Fixed: Refetch Prices Cronjob

= 0.17.3 =

* Fixed: Warning: Invalid argument supplied for foreach() in /www/htdocs/w0121612/xxxxxx/wp-content/plugins/affilipus/library/classes/Affilipus/imbafShortcodes.class.php on line 538

= 0.17.2 =

* Fixed: Affilipus Reflink Defaults
* Fixed: List styling for topseller lists
* New pussys inside
* Fixed: Topseller List setting / rating
* Fixed: Product Box Settings
* Comptable Template Fix

= 0.17.1 =

* Fixed: Cronjob via WP-Cron
* Fixed: global ASIN Exclude in Topseller lists
* Fixed: topseller list could display empty elements
* Fixed: Decimal values for number input fields should work now
* Fixed: Shortcode generator - display defaults from user settings
* Fixed: Buy Button Icon Fix
* Fixed: Product Box Template
* Display Product id in product metabox
* Extended debug information
* Fixed global Settings

= 0.17.0 =

* Assign Products a link to an external review
* Replace Permalink to product if it as linked to an external review

= 0.16.1 =

* Typo: Prefix = Präfix
* Fixed: Distance in Review Buttons in Product Boxes

= 0.16.0 =

* Display Products in Post Loop
* Reduced database footprint
* Changed system requirement check
* Fixed: Comptable highlight in shortcode generator can now be used

= 0.15.4 =

* Enable Smarty Caching

= 0.15.3 =

* Bugfixes
* Remove toplist indicator through shortcode parameter

= 0.15.2 =

* Text fixes

= 0.15.1 =

* Disable toplist label by shortcode

= 0.15.0 =

* Bugfixies
* Stylefixes
* Fixed: CDN-Pictures
* Added Icons to comparisment tables
* Added Icons to buy button and review button in comptables
* Display review buttons in topslists and product boxes


= 0.14.3 =

* Bugfix: Wrong parameters for number input in backend
* Fix: Set Buy Button Links in comptables to nofollow
* Fix: Set Buy Button links to noffollow

= 0.14.2 =

* Hotfix: Product Box link to Product Buy page broken

= 0.14.1 =

* Minor styling adjustments

= 0.14.0 =

* Comptable: prefixes
* Comptable: grades as value (1-6)
* Display Product Rating in Product Box
* Display Product Rating in Topseller List
* Fix: Handling for missing products in comptables
* More Precise star rating
* Save number of ratings for review
* Display number of ratings in comptable, product box and toplist

= 0.13.1 =

* Hotfix: Compatibility Issues with affiliate toolkit

= 0.13.0 =

* Highlight products in comptable through highlight parameter
* Product Recommendation Widget (Alpha Version)

= 0.12.1 =

* Bugfix in imbafAdmin.class

= 0.12.0 =

* Choose which Font-Awesome Icon should be display near the buy button in add2cart-button, buy-button, product-box and topseller-list

= 0.11.4 =

* Hotfix: Temporary products would not link to the shop in the title section

= 0.11.3 =

* Bugfix: Display affiliate name for custom products
* Bugfix: Removed debug output which occured when CDN pictures were set to „not prefered"
* Bugfix: affilipus_pricelists displayed wrong under certain circumstances. they shouldn't anymore
* Bugfix: Functionality for output of CDN pictures improved
* Minor optimizations

= 0.11.2 =

* Improved installation routine

= 0.11.1 =

* E-Mail Notifications for missing prices on price refetch cronjob
* Product boxes and toplists which are linked to product pages will no longer link to product pages if product pages are disabled
* Review Buttons in comparisment tables are no longer displayed if product pages are deactivated
* Product Boxes can now link to the review instead of the shop in the title
* CDN Pictures can be activated as the prefered medium for all shortcodes. If CDN Pictures are present, there will be delivered. If not the post thumbnail will be used instead.

= 0.10.1 =

* Deaktivierbare Produktseiten
* Fix: Temporary products will not be highlighted as already imported in product import dialog

= 0.10.0 =

* Rename pictures on import
* Modified refetch price procedure. Prices will not update if there are no prices for the product present
* Product and package dimensions will always be imported it available

= 0.9.3 =

* Moved debug information to external page

= 0.9.2 =

* Minor Bugfixes
* Enabled PHP 5.4 for testing purposes
* Minor changes to interface
* Deleted subproducts will be moved to trash now instead of beeing deleted completelly

= 0.9.0 =

* Toplists with custom products
* Change name of partner in custom products
* New comparisment values for products
* Added Debug Helper script to track down compatibility issues

= 0.8.6 =

* Added new debug information
* Fixed some issues with templates

= 0.8.5 =

* This release prepares your Affilipus installation for the 0.9.0 update with custom products.
* Fix: Product Box too short if picture too high - not anymore
* Change: Display local Product pictures in Product Boxes and Toplists if available. If not try to load one from CDN


= 0.8.4 =

* Fix: Comment Shortcode Filters adjusted

= 0.8.3.3 =

* Fix: Template Editor Folder check error message added.
* Template Editor tries to create folders if missing.

= 0.8.3.2 =

* More usefull error messages on plugin activation/installation

= 0.8.3.1 =

* Fixed: Amazon.com Price table would not display us-flag.
* Improved mobile comptable styling

= 0.8.3 =

* Moved custom templates to wp-content/affilipus/templates_custom
* Responsive Tables

= 0.8.2 =

* Bugfix: Add2Cart Button Template Link
* Bugfix: Add2Cart Button Link Generator
* Improvement: Price import refetches prices of oldest products first
* Styling improvements
* Bufixes: Shortcode Generator
* Shortcode Generator: reset button
* Shortcode Generator: copy to clipboard
* Refetch prices every hour

= 0.8.1 =

* Comptable with description of compared values
* Bugfix: Toplists via „term" would always display 'product not found'

= 0.8.0 =

* Topseller with ASINS
* Topseller with product-ids

= 0.7.8 =

* Bugfixes
* Import Prime Status
* Display Prime Status
* Import delivery information


= 0.7.7 =

* templates_c path changed to wp-content/affilipus/templates_c

= 0.7.6 =

* Stylefixes

= 0.7.5 =

* New styles
* Bugfixes

= 7.0.0 =

* Removed import of subproducts
* Enabled: „Hierarchical Products“
* Shortcode Generator: detailed settings through dynamic select-fields
* Product Boxes: change appearance of product boxes through shortcode generator

= 0.6.2 =

* Fixed: Produkt veröffentlicht importieren
* Fixed: improved handling of products with only one feature (Amazon)
* Fixed: errors in activation routine fixed

= 0.6.1 =

* Fixed Problem with Settings Page
* Execute Shortcodes in Comments


= 0.6.0 =

* Shortcode Generator: Default values are now not integrated inside the shortcode through the shortcode generator to keep the shortcodes as short as possible.
* Shortcode/Generator: Configurable default Values for certain shortcodes

= 0.5.5 =

* Fixed: in some cases Product Boxes or other templates would just return „content" instead of the template. This Issue should be fixed now.

= 0.5.4 =

* Compatibility Check on activation

= 0.5.3 =

* Bugfixes

= 0.5.2 =

* Bugfixes

= 0.5.1 =

* Star-Rating in Comparisment Tables
* Define Amazon Associate Tag per Amazon

= 0.5.0 =

* User defined Product properties
* Comparisement tables
* Amazon Secret Key will now not be displayed in the backend


= 0.4.3 =

* Amazon: Short Reflinks
* Reflink Shortcode: Title Tag
* Fallback function for missing Products
* Overhauled Data Normalization

= 0.4.2 =

* Hotfix: Product Links broken

= 0.4.1 =

* Bugfixes

= 0.4.0 =

* Release of Shortcode Generator

= 0.3.5 =

* Bugifxes

= 0.3.4 =

* Basic functionallity to create reviews

= 0.3.3 =

* Fixed: Product Search broken
* Fixed: Brands, Types and Tags for Products now show in Navigation Interface

= 0.3.2 =

* Affilipus Statistiken Widget

= 0.3.1 =

* Prepared shortcode generator
* Metabox: Shortcode List

= 0.3.0 =

* Removed EDD-Updater due to compatibility issues

= 0.2.9b =

* You can activate and deactivate licenses in backend
* Updated Update Routine
* Create new Templates from Backend

= 0.2.8b =

* Fixed Product Features Metabox in Backend
* Fixed: Default Template not loaded if custom Template missing
* Templates added for following shortcodes: BuyButton, add2cart Button, Feature List, Price List
* Added Affilipus Tools (Flush Cache)
* Display imported CDN pictures in backend and prevent double-import
* Affiliate Links are reparsed after changing the Amazon Credentials


= 0.2.7b =

* Template Editor

= 0.2.5b =

* Add2Cart Button

= 0.2.4b =

* Anpassungen der Shortcodes
* Jetzt kaufen Button

= 0.2.2b =

* Redirection to License Page if no License present
* Features are now sortable
* CDN-Pictures can now be imported inside of a product
* Globally exclude ASINS in Topseller Lists
* Locally exclude ASINS in Topseller Lists

= 0.2.1b =

* Amazon Reflink in Product Box rel="nofollow"
* Price Format changed

= 0.2.0b =

* Added Filter to Affilipus Output to create Paragraph Tags etc.
* Amazon Links auf nofollow gesetzt

= 0.1.9b =

* Produktbox with ASIN [affilipus_product_box asin="B019FA3JVW" button_text="Jetzt kaufen*"]
* Bugfix: Product Shortlink Generation was always for German amazon. It isn't anymore
* Cleaner Codebase
* Optimization: Automatic Price Update improved
* Minor Changes on wordings
* Short-Shortcodes use afp_shortcode_name instead of affilipus_shortcode_name

= 0.1.8b =

* Small improvements in Backend
* Feature: Change display price after product import

= 0.1.7b =

* Geduld wird mit d geschrieben, nicht mit t
* Feature: Background Product import
* Feature: Go to partner's product page from product search
* Improvement: Validation of credentials on Amazon API-Settings Page
* Improvement: Amazon can now be accessed with IAM-Users
* Licensing: Licensing basics integrated

= 0.1.6b =

* Feature: Amazon Toplist
* Feature: Metabox with Affilipus Shortcodes in Backend
* Settings: Deactivate affilipus Styling on demand
* Settings: Deactivate Google Fonts which are loaded through affilipus
* Change: Amazon prices will only be updated if the last fetched price is older than 60 minutes
* Change: Amazon price update will not fail because the API is accessed to quickly
* Fix: Affilipus Crons now work properly
* Background: Obsolete topseller Data will be removed via cron
* Change: On Product Deactivation

= 0.1.5b =

* Feature Prepared: Amazon Toplist data retrieval and caching
* Feature Prepared: Imported Picture counter

* Change: Feature Liste does not show a headline anymore if none specified in shortcode
* Change: Removed add new Product from Product List
* Change: Products -> New redirects to Affiliate Partners Page
* Feature: Slug for Products can be changed
* Feature: Slug for Product Types can be changed by user
* Feature: Slug für Product Brand can be changed by user
* Feature: Slug for Product Tag can be changed by user
* Feature: Standard Beschreibung über [affilipus_default_description] anzeigbar
* Feature: Affilipus Product Box hinzugefügt [affilipus_product product="1234"]
* Added Partner Overview Page with actual partners

= 0.1.4b =

* Affilipus now Sends Debug Info to Server on Activation
* Fixed: Product images could not be imported if php.alow_url_fopen not enabled

= 0.1.3b =

* Improvement: Search button disabled until search querie is entered

= 0.1.2b =

* Fixed: Product search not working with error reporting enabled

= 0.1.1b =

* Fixed: No Preview Problem
* Fixed: Rewrite rule issue on activation
* Fix/Workaround: If affillipus image import fails, WordPress sideload function will be used - but renaming files won't be possible

= 0.1.0b =

* Minor Changes

= 0.0.9b =

* Better support for older PHP-Versions

= 0.0.8b =

* Paginierung auch im unteren Bereich der Ergebnisse eingebaut
* Shortcode Hinweise im Backend verbessert
* Kürzere Links bei Amazon Produktseiten
* Kompatibilitätscheck eingefügt
* Kleinere Optimierungsarbeiten
* Autoloading für Klassen
* Amazon API Test Ausgabe für normale Menschen angepasst
* Hinweis auf bereits importierte Produkte im Backend

= 0.0.7b =
* Kleinere Anpassungen um Beta-Tests beginnen zu können

= 0.0.6 =
* Hotfix: Aktivierung

= 0.0.5 =
* Hotfix: Ausgabe

= 0.0.4 =
* Hotfix: Picture Import fixed

= 0.0.3 =
* Hotfix: Amazon Settings could not be saved to wordpress


== Upgrade Notice ==

= 0.0.7b =
Erste Beta-Version für geschlossene Gruppe

= 0.0.4 =
Hotfix: Picture Import fixed

= 0.0.3 =
Hotfix: Amazon Settings could not be saved to WordPress