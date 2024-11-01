<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php if ( ! empty( $_POST ) && ! wp_verify_nonce( $_REQUEST['wp_create_nonce'], 'image-management-add-nonce' ) )  { die('<p>Security check failed.</p>'); } ?>
<div class="wrap">
<?php
$tch_errors = array();
$tch_success = '';
$tch_error_found = FALSE;

// Preset the form fields
$form = array(
	'tch_viewport' => '',
	'tch_width' => '',
	'tch_height' => '',
	'tch_display' => '',
	'tch_controls' => '',
	'tch_interval' => '',
	'tch_intervaltime' => '',
	'tch_duration' => '',
	'tch_folder' => '',
	'tch_random' => '',
	'tch_id' => ''
);

// Form submitted, check the data
if (isset($_POST['tch_form_submit']) && $_POST['tch_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('tch_form_add');
	
	$form['tch_viewport'] = isset($_POST['tch_viewport']) ? sanitize_text_field($_POST['tch_viewport']) : '';
	if ($form['tch_viewport'] == '')
	{
		$tch_errors[] = __('Please enter slider width. only number.', 'tiny-carousel-horizontal-slider');
		$tch_error_found = TRUE;
	}
	if(!is_numeric($form['tch_viewport'])) { $form['tch_viewport'] = 500; }

	$form['tch_width'] = isset($_POST['tch_width']) ? sanitize_text_field($_POST['tch_width']) : '';
	$returnvalue = TinyCarousel_validation::num_val($form['tch_width']);
	if ($form['tch_width'] == '' || $returnvalue == "invalid")
	{
		$tch_errors[] = __('Please enter the image width. only number.', 'tiny-carousel-horizontal-slider');
		$tch_error_found = TRUE;
	}
	
	$form['tch_height'] = isset($_POST['tch_height']) ? sanitize_text_field($_POST['tch_height']) : '';
	$returnvalue = TinyCarousel_validation::num_val($form['tch_height']);
	if ($form['tch_height'] == '' || $returnvalue == "invalid")
	{
		$tch_errors[] = __('Please enter the image height. only number.', 'tiny-carousel-horizontal-slider');
		$tch_error_found = TRUE;
	}
	
	$form['tch_display'] = isset($_POST['tch_display']) ? sanitize_text_field($_POST['tch_display']) : '';
	if ($form['tch_display'] == '')
	{
		$tch_errors[] = __('Please enter the display. only number.', 'tiny-carousel-horizontal-slider');
		$tch_error_found = TRUE;
	}
	if(!is_numeric($form['tch_viewport'])) { $form['tch_viewport'] = 1; }
	
	$form['tch_controls'] = isset($_POST['tch_controls']) ? sanitize_text_field($_POST['tch_controls']) : '';
	$form['tch_controls'] = TinyCarousel_validation::val_tf($form['tch_controls']);
	
	$form['tch_interval'] = isset($_POST['tch_interval']) ? sanitize_text_field($_POST['tch_interval']) : '';
	$form['tch_interval'] = TinyCarousel_validation::val_tf($form['tch_interval']);
	
	$form['tch_intervaltime'] = isset($_POST['tch_intervaltime']) ? sanitize_text_field($_POST['tch_intervaltime']) : '';
	if(!is_numeric($form['tch_intervaltime'])) { $form['tch_intervaltime'] = 1500; }
	
	$form['tch_duration'] = isset($_POST['tch_duration']) ? sanitize_text_field($_POST['tch_duration']) : '';
	if(!is_numeric($form['tch_duration'])) { $form['tch_duration'] = 1000; }
	
	$form['tch_folder'] = isset($_POST['tch_folder']) ? sanitize_text_field($_POST['tch_folder']) : '';
	if ($form['tch_folder'] == '')
	{
		$tch_errors[] = __('Please select the image folder location.', 'tiny-carousel-horizontal-slider');
		$tch_error_found = TRUE;
	}
	
	$form['tch_random'] = isset($_POST['tch_random']) ? sanitize_text_field($_POST['tch_random']) : '';
	$form['tch_random'] = TinyCarousel_validation::val_yn($form['tch_random']);

	//	No errors found, we can add this Group to the table
	if ($tch_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".TinyCarouselTable."`
			(`tch_viewport`, `tch_width`, `tch_height`, `tch_display`, `tch_controls`, `tch_interval`, `tch_intervaltime`, `tch_duration`, `tch_folder`, `tch_random`)
			VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			array($form['tch_viewport'], $form['tch_width'], $form['tch_height'], $form['tch_display'], $form['tch_controls'], $form['tch_interval'], $form['tch_intervaltime'], $form['tch_duration'], $form['tch_folder'], $form['tch_random'])
		);
		$wpdb->query($sql);
		
		$tch_success = __('New details was successfully added.', 'tiny-carousel-horizontal-slider');
		
		// Reset the form fields
		$form = array(
			'tch_viewport' => '',
			'tch_width' => '',
			'tch_height' => '',
			'tch_display' => '',
			'tch_controls' => '',
			'tch_interval' => '',
			'tch_intervaltime' => '',
			'tch_duration' => '',
			'tch_folder' => '',
			'tch_random' => '',
			'tch_id' => ''
		);
	}
}

if ($tch_error_found == TRUE && isset($tch_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $tch_errors[0]; ?></strong></p>
	</div>
	<?php
}

if ($tch_error_found == FALSE && strlen($tch_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $tch_success; ?> 
		<a href="<?php echo TinyCarousel_ADMIN_URL; ?>"><?php _e('Click here', 'tiny-carousel-horizontal-slider'); ?></a> <?php _e('to view the details', 'tiny-carousel-horizontal-slider'); ?></strong></p>
	</div>
	<?php
}

?>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Tiny carousel horizontal slider', 'tiny-carousel-horizontal-slider'); ?></h2>
	<form name="tch_form" method="post" action="#" onsubmit="return tch_submit()"  >
      <h3><?php _e('Add details', 'tiny-carousel-horizontal-slider'); ?></h3>
      
		<!--<label for="tag-title"><?php //_e('Slider width', 'tiny-carousel-horizontal-slider'); ?></label>-->
		<input name="tch_viewport" type="hidden" id="tch_viewport" value="500" maxlength="4" />
		<!--<p><?php //_e('Enter your galley width. (Ex: 500)', 'tiny-carousel-horizontal-slider'); ?></p>-->
		
		<label for="tag-title"><?php _e('Image width', 'tiny-carousel-horizontal-slider'); ?></label>
		<input name="tch_width" type="text" id="tch_width" value="" maxlength="4" />
		<p><?php _e('Enter your image width, You should upload same size images in the folder. (Ex: 200)', 'tiny-carousel-horizontal-slider'); ?></p>
		
		<label for="tag-title"><?php _e('Image height', 'tiny-carousel-horizontal-slider'); ?></label>
		<input name="tch_height" type="text" id="tch_height" value="" maxlength="4" />
		<p><?php _e('Enter your image height, You should upload same size images in the folder. (Ex: 150)', 'tiny-carousel-horizontal-slider'); ?></p>
	  
	  	<!--<label for="tag-title"><?php //_e('Image display', 'tiny-carousel-horizontal-slider'); ?></label>-->
		<input name="tch_display" type="hidden" id="tch_display" value="1" maxlength="4" />
		<!--<p><?php //_e('Enter how many images you want to move at a time. (Ex: 1)', 'tiny-carousel-horizontal-slider'); ?></p>-->
		
		<label for="tag-title"><?php _e('Controls', 'tiny-carousel-horizontal-slider'); ?></label>
		<select name="tch_controls" id="tch_controls">
			<option value='true'>True</option>
			<option value='false'>False</option>
		</select>
		<p><?php _e('Want to use the Left, Right arrow button in your gallery?', 'tiny-carousel-horizontal-slider'); ?></p>
		
		<label for="tag-title"><?php _e('Auto interval', 'tiny-carousel-horizontal-slider'); ?></label>
		<select name="tch_interval" id="tch_interval">
			<option value='true'>True</option>
			<option value='false'>False</option>
		</select>
		<p><?php _e('Want to add auto interval to move one image from another?', 'tiny-carousel-horizontal-slider'); ?></p>
		
		<label for="tag-title"><?php _e('Interval time', 'tiny-carousel-horizontal-slider'); ?></label>
		<input name="tch_intervaltime" type="text" id="tch_intervaltime" value="1500" maxlength="4" />
		<p><?php _e('Auto interval time in millisecond. (Ex: 1500)', 'tiny-carousel-horizontal-slider'); ?></p>
		
		<label for="tag-title"><?php _e('Animation Duration', 'tiny-carousel-horizontal-slider'); ?></label>
		<input name="tch_duration" type="text" id="tch_duration" value="1000" maxlength="4" />
		<p><?php _e('Animation duration in millisecond. (Ex: 1000)', 'tiny-carousel-horizontal-slider'); ?></p>
		
		<label for="tag-title"><?php _e('Random display', 'tiny-carousel-horizontal-slider'); ?></label>
		<select name="tch_random" id="tch_random">
			<option value='YES'>YES</option>
			<option value='NO'>NO</option>
		</select>
		<p><?php _e('Do you want to display images in random order?', 'tiny-carousel-horizontal-slider'); ?></p>
		
		<label for="tag-title"><?php _e('Image folder location', 'tiny-carousel-horizontal-slider'); ?></label>
		<input name="tch_folder" type="text" id="tch_folder" value="" size="100" maxlength="1024" />
		<p><?php _e('Example: wp-content/plugins/tiny-carousel-horizontal-slider/images/', 'tiny-carousel-horizontal-slider'); ?></p>
	  
      <input name="tch_id" id="tch_id" type="hidden" value="">
      <input type="hidden" name="tch_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Insert Details', 'tiny-carousel-horizontal-slider'); ?>" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button add-new-h2" onclick="tch_redirect()" value="<?php _e('Cancel', 'tiny-carousel-horizontal-slider'); ?>" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button add-new-h2" onclick="tch_help()" value="<?php _e('Help', 'tiny-carousel-horizontal-slider'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('tch_form_add'); ?>
	  <input type="hidden" name="wp_create_nonce" id="wp_create_nonce" value="<?php echo wp_create_nonce( 'image-management-add-nonce' ); ?>"/>
    </form>
</div>
<p class="description">
	<?php _e('Check official website for more information', 'tiny-carousel-horizontal-slider'); ?>
	<a target="_blank" href="<?php echo TinyCarousel_FAV; ?>"><?php _e('click here', 'tiny-carousel-horizontal-slider'); ?></a>
</p>
</div>