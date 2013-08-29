<?php

class acf_field_flexible_link extends acf_field_page_link
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
		);


		// do not delete!
    acf_field::__construct();

    // settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);

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
		<label for=""><?php _e("Allow freeform?",'acf-flexible_link'); ?></label>
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
	  echo '<label for="' . $field['id'] . '">Enter a URL</label>';
	  echo '<input type="text" id="' . $field['id'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" value="' . $field['value'] . '"' . ( $field['freeform'] ? '' : ' readonly' ) . '>';

	  if ( $field['use_pages'] )
	  {
  	  echo '<p class="howto toggle-arrow" id="internal-toggle" style="background-image:url(/wp-includes/images/toggle-arrow.png);padding-left:18px;">Or link to existing content</p>';
  	  $field['id'] = '';
  	  $field['name'] = '';
  	  $field['class'] .= '-select';

  		parent::create_field($field);
	  }

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


	function format_value_for_api( $value, $post_id, $field )
	{
		if( !$value OR $value == 'null' )
		{
			return false;
		}

		return $value;
	}

}


// create field
new acf_field_flexible_link();

?>
