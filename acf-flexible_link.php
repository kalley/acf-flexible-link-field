<?php
/*
Plugin Name: Advanced Custom Fields: Flexible Link Field
Plugin URI: https://github.com/kalley/acf-flexible-field
Description: Allows the user to add a link to an external site or search within the specified post types for a page to link to
Version: 1.0.0
Author: Kalley Powell
Author URI:
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


class acf_field_flexible_link_plugin
{
	/*
	*  Construct
	*
	*  @description:
	*  @since: 3.6
	*  @created: 1/04/13
	*/

	function __construct()
	{
		// set text domain
		/*
		$domain = 'acf-flexible_link';
		$mofile = trailingslashit(dirname(__File__)) . 'lang/' . $domain . '-' . get_locale() . '.mo';
		load_textdomain( $domain, $mofile );
		*/


		// version 4+
		add_action('acf/register_fields', array($this, 'register_fields'));
	}

	/*
	*  register_fields
	*
	*  @description:
	*  @since: 3.6
	*  @created: 1/04/13
	*/

	function register_fields()
	{
		include_once('flexible_link.php');
	}

}

new acf_field_flexible_link_plugin();