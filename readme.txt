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

This field works similarly to the link popup from the WYSIWYG field, giving the user the option between entering in a URL or choosing from a list of pages
from within the Wordpress site. It works similar to the [Page Link](http://www.advancedcustomfields.com/resources/field-types/page-link/) field, giving the developer
the option to limit the post_type that will be available (though someone could just go enter in the URL directly). It also give the option to limit the depth of the
page type. Here's an example (parentheses denote depth):

*Consider this hierarchy*

 - services **(1)**
   - home **(2)**
     - plumbing **(3)**
     - repair **(3)**
   - business **(2)**
     - hvac **(3)**

So if you don't want the user to see plumbing, repair and hvac, give it a depth of 2.

You can also use this as a complete replacement for the Page Link field, given that you make the url field readonly by choosing to not allow freeform entry, while
getting the benefit of limiting the depth of the hierarchy.

= Compatibility =

This add-on will work with:

* version 4 and up

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
