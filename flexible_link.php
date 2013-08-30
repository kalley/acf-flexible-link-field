<?php

class acf_field_flexible_link extends acf_field
{
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options


	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/

	function __construct()
	{
		// vars
		$this->name = 'flexible_link';
		$this->label = __('Flexible Link');
		$this->category = __("Basic",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'post_type' => array('all'),
			'multiple' => 0,
			'allow_null' => 1,
			'freeform' => 1,
			'use_pages' => 1,
			'depth' => null,
		);


		// do not delete!
    parent::__construct();

    // settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);

	}

	/*
	*  load_field()
	*
	*  This filter is appied to the $field after it is loaded from the database
	*
	*  @type filter
	*  @since 3.6
	*  @date 23/01/13
	*
	*  @param $field - the field array holding all the field options
	*
	*  @return $field - the field array holding all the field options
	*/

	function load_field( $field )
	{

		// validate post_type
		if( !$field['post_type'] || !is_array($field['post_type']) || in_array('', $field['post_type']) )
		{
			$field['post_type'] = array( 'all' );
		}


		// return
		return $field;
	}

	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function create_field( $field )
	{
	  $url = isset($field['value']['url']) ? $field['value']['url'] : '';
	  $post_id = isset($field['value']['post_id']) ? $field['value']['post_id'] : '';
	  if ( $post_id AND $post_id !== 'null' )
	  {
  	  $url = get_permalink($post_id);
	  }

	  echo '<label for="' . $field['id'] . '">Enter a URL</label>';
	  echo '<input type="text" id="' . $field['id'] . 'url" class="' . $field['class'] . '" name="' . $field['name'] . '[url]" value="' . $url . '"' . ( $field['freeform'] ? '' : ' readonly' ) . '>';

	  if ( $field['use_pages'] )
	  {
  	  echo '<p class="howto toggle-arrow" id="internal-toggle" style="background-image:url(/wp-includes/images/toggle-arrow.png);padding-left:18px;">Or link to existing content</p>';
  	  $field['id'] .= 'post_id';
  	  $field['name'] .= '[post_id]';
  	  $field['class'] .= '-select';
  	  $field['type'] = 'post_object';
  	  $field['value'] = $post_id;

  	  add_filter('acf/fields/post_object/query/name=' . $field['name'], array($this, 'apply_depth'), 10, 2);

  		do_action('acf/create_field', $field );
	  }

	}


	/**
	 * Used in the post_object query name
	 *
	 * @access public
	 * @param mixed $args - an array used in get_posts or get_pages
	 * @param mixed $field - an array holding all the field's data
	 * @return void
	 */

	function apply_depth($args, $field)
	{
	  global $wpdb;

	  if ( $field['depth'] )
	  {
	    $post_types = $field['post_type'];
  		if( in_array('all', $post_types) )
  		{
  			$post_types = apply_filters('acf/get_post_types', array());
  		}
  		$post_types = '"' . implode('","', $post_types) . '"';
	    $andwhere = "AND post_type IN ($post_types) AND post_status IN ('publish','private','draft','inherit','future')";
	    $stmt = "SELECT ID FROM $wpdb->posts WHERE post_parent = 0 $andwhere";

	    for ( $i = 1; $i < $field['depth'] + 1; $i++ )
	    {
  	    $stmt = "SELECT ID FROM $wpdb->posts WHERE post_parent IN ($stmt) $andwhere";
	    }

  	  $ids = $wpdb->get_col($stmt);

  	  $args['exclude'] = implode(',', $ids);
	  }

  	return $args;
	}


	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function create_options( $field )
	{
	  $key = $field['name'];

		?>
<tr class="field_option field_option_<?php echo $this->name; ?> field_option_<?php echo $this->name; ?>_toggle">
	<td class="label">
		<label for=""><?php _e("Select from Pages?",'acf-flexible_link'); ?></label>
		<p class="description">Select "No" if you want this to just be a URL field</p>
	</td>
	<td>
		<?php

		do_action('acf/create_field', array(
			'type'	=>	'radio',
			'name'	=>	'fields['.$key.'][use_pages]',
			'value'	=>	$field['use_pages'],
			'choices'	=>	array(
				1	=>	__("Yes",'acf'),
				0	=>	__("No",'acf'),
			),
			'layout'	=>	'horizontal',
		));

		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label for=""><?php _e("Post Type",'acf'); ?></label>
	</td>
	<td>
		<?php

		$choices = array(
			'all'	=>	__("All",'acf')
		);
		$choices = apply_filters('acf/get_post_types', $choices);


		do_action('acf/create_field', array(
			'type'	=>	'select',
			'name'	=>	'fields['.$key.'][post_type]',
			'value'	=>	$field['post_type'],
			'choices'	=>	$choices,
			'multiple'	=>	1,
		));

		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label for=""><?php _e("Limit Depth of Pages",'acf'); ?></label>
		<p class="description">Leave empty to get all</p>
	</td>
	<td>
		<?php

		$choices = array(
			'all'	=>	__("All",'acf')
		);
		$choices = apply_filters('acf/get_post_types', $choices);


		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][depth]',
			'value'	=>	$field['depth'],
			'choices'	=>	$choices,
			'multiple'	=>	1,
		));

		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label for=""><?php _e("Allow freeform?",'acf-flexible_link'); ?></label>
		<p class="description">Let the user copy/paste a URL</p>
	</td>
	<td>
		<?php

		do_action('acf/create_field', array(
			'type'	=>	'radio',
			'name'	=>	'fields['.$key.'][freeform]',
			'value'	=>	$field['freeform'],
			'choices'	=>	array(
				1	=>	__("Yes",'acf'),
				0	=>	__("No",'acf'),
			),
			'layout'	=>	'horizontal',
		));

		?>
	</td>
</tr>

    <?php

	}


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		wp_register_script( 'acf-field-group-flexible_link', $this->settings['dir'] . 'js/field-group.js', array('acf-field-group'), $this->settings['version']);

		// scripts
		wp_enqueue_script(array(
			'acf-field-group-flexible_link',
		));
	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used


		// register acf scripts
		wp_register_script('acf-input-flexible_link', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version']);
		wp_register_style('acf-input-flexible_link', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version']);


		// scripts
		wp_enqueue_script(array(
			'acf-input-flexible_link',
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-flexible_link',
		));

	}

  /*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

	function format_value_for_api( $value, $post_id, $field )
	{
		if ( ! $value ) {
  		return false;
		}
		$url = $value['url'];
		if ( isset($value['post_id']) AND $value['post_id'] AND $value['post_id'] !== 'null' )
		{
  		$url = get_permalink($value['post_id']);
		}
		return $url;
	}


	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field($field, $post_id)
	{
		if ( ! $field['use_pages'] )
		{
  		$field['freeform'] = true;
		}

		return $field;
	}

}


// create field
new acf_field_flexible_link();

?>
