=== Series Index ===
Contributors: azurecurve
Tags: Series,Posts,Index
Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/series-index/
Donate link: https://development.azurecurve.co.uk/support-development/
Requires at least: 1.0.0
Tested up to: 1.0.0
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays Index of Series Posts using series-index Shortcode. This plugin is multi-site compatible and contains an inbuilt show/hide toggle.

== Description ==
Displays Index of Series Posts using series-index shortcode. This plugin is multi-site compatible and integrates with the Toggle Show/Hide plugin from azurecurve. The shortcode can be used on posts and pages; the format of the index and other options are user configurable through an Admin page.

Two custom fields (Series and Series Position) need to be added to each post in the series; these fields are used for selecting the posts and ordering them when the index is displayed; series index post should be Series Position of 0 (this is used for determining the link for the title).

Shortcode [series-index] is placed in the post where you want the index.

Shortcode [series-index-link] can be used in a post a link back to the series index (Series Position = 0) post using the series title as the link text; [series-index-link]alternative text[/series-index-link] to display text different to the series title. title attribute can be used to create link to other series index post; e.g. [series-index-link title='Implementing Jet Reports'].

== Installation ==
To install the RSS Feed plugin:
* Download the plugin from <a href='https://github.com/azurecurve/azrcrv-series-index/'>GitHub</a>.
* Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
* Activate the plugin.
* Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Changelog ==
Changes and feature additions for the Series Index plugin:
= 1.0.1 =
* Update azurecurve menu for easier maintenance.
* Move require of azurecurve menu below security check.
= 1.0.0 =
* First version for ClassicPress forked from azurecurve Series Index WordPress Plugin.
* Remove toggle and integrate with Toggle Show/Hide plugin.

== Frequently Asked Questions ==
= Can I translate this plugin? =
* Yes, the .pot fie is in the plugins languages folder and can also be downloaded from the plugin page on https://development.azurecurve.co.uk/; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).
= Is this plugin compatible with both WordPress and ClassicPress? =
* This plugin is developed for ClassicPress, but will likely work on WordPress.