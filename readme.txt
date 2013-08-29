=== Advanced Custom Fields: Flexible Link Field ===
Contributors: iamkalley
Tags:
Requires at least: 3.4
Tested up to: 3.3.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a URL field for version 4. Allows for freeform entering or selecting from a list of site posts.

== Description ==

{{description}}

= Compatibility =

This add-on will work with:

* version 4 and up
* version 3 and bellow

== Installation ==

This add-on can be treated as both a WP plugin and a theme include.

= Plugin =
1. Copy the 'acf-flexible_link' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

= Include =
1.	Copy the 'acf-flexible_link' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2.	Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-flexible_link.php file)

`
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
	include_once('acf-flexible_link/acf-flexible_link.php');
}
`

== Changelog ==

= 0.0.1 =
* Initial Release.
