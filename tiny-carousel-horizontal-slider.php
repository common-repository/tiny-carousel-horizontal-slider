<?php
/*
Plugin Name: Tiny Carousel Horizontal Slider
Description: This is Jquery based image horizontal slider plugin, it is using tiny carousel light weight jquery script to the slideshow.
Author: Gopi Ramasamy
Version: 8.2
Plugin URI: http://www.gopiplus.com/work/2012/05/26/tiny-carousel-horizontal-slider-wordpress-plugin/
Author URI: http://www.gopiplus.com/work/2012/05/26/tiny-carousel-horizontal-slider-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/05/26/tiny-carousel-horizontal-slider-wordpress-plugin/
Text Domain: tiny-carousel-horizontal-slider
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("TinyCarouselTable", $wpdb->prefix . "tinycarousel");
define('TinyCarousel_FAV', 'http://www.gopiplus.com/work/2012/05/26/tiny-carousel-horizontal-slider-wordpress-plugin/');

if ( ! defined( 'TinyCarousel_BASENAME' ) )
	define( 'TinyCarousel_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'TinyCarousel_PLUGIN_NAME' ) )
	define( 'TinyCarousel_PLUGIN_NAME', trim( dirname( TinyCarousel_BASENAME ), '/' ) );
	
if ( ! defined( 'TinyCarousel_PLUGIN_URL' ) )
	define( 'TinyCarousel_PLUGIN_URL', WP_PLUGIN_URL . '/' . TinyCarousel_PLUGIN_NAME );
	
if ( ! defined( 'TinyCarousel_ADMIN_URL' ) )
	define( 'TinyCarousel_ADMIN_URL', get_option('siteurl') . '/wp-admin/options-general.php?page=tiny-carousel-horizontal-slider' );

add_shortcode( 'tiny-carousel-slider', 'TinyCarousel_shortcode' );

function TinyCarousel( $atts ) 
{
	$arr = array();
	$arr["id"]=$atts;
	echo TinyCarousel_shortcode($arr);
}

function TinyCarousel_shortcode( $atts ) 
{
	global $wpdb;
	// [tiny-carousel-slider id="1"]

	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$id = $atts['id'];
	
	$sSql = "select * from ".TinyCarouselTable." where 1=1";
	if(is_numeric($id)) 
	{
		$sSql = $sSql . " and tch_id=$id";
	}

	$sSql = $sSql . " LIMIT 0,1";
	$tch = "";
	$imageli = "";
	$data = $wpdb->get_results($sSql);
	if ( ! empty($data) ) 
	{
		$data = $data[0];
		$tch_id = stripslashes($data->tch_id);
		$tch_viewport = stripslashes($data->tch_viewport);
		$tch_width = stripslashes($data->tch_width);
		$tch_height = stripslashes($data->tch_height);
		$tch_display = stripslashes($data->tch_display);
		$tch_controls = stripslashes($data->tch_controls);
		$tch_interval = stripslashes($data->tch_interval);
		$tch_intervaltime = stripslashes($data->tch_intervaltime);
		$tch_duration = stripslashes($data->tch_duration);
		$tch_folder = stripslashes($data->tch_folder);
		if (substr($tch_folder , -1) !== '/') 
		{
			$tch_folder = $tch_folder ."/";
		}
		$tch_random = stripslashes($data->tch_random);
	}
	
	if(is_dir($tch_folder))
	{
		$tch_images	= array();
		$i = 0;
		$siteurl = get_option('siteurl');
		if (substr($siteurl , -1) !== '/') 
		{
			$siteurl = $siteurl ."/";
		}
		$tch_dirhandle = opendir($tch_folder);
		while ($tch_file = readdir($tch_dirhandle)) 
		{
			$tch_file_nocaps = $tch_file;
			$tch_file = strtoupper($tch_file);
			if(!is_dir($tch_file) && ((strpos($tch_file, '.JPG')>0) or (strpos($tch_file, '.GIF')>0) or (strpos($tch_file, '.PNG')>0) or (strpos($tch_file, '.JPEG')>0)))
			{
				$tch_images[$i] = new stdClass;
				$tch_images[$i]->name	= $siteurl . $tch_folder . $tch_file_nocaps;
				$i++;
			}
		}
		
		if($tch_random == "YES")
		{
			shuffle($tch_images);
			foreach ($tch_images as $images)
			{
				$imageli = $imageli . '<li><img src="'. $images->name .'" /></li>';
			}
		}
		else
		{
			foreach ($tch_images as $images)
			{
				$imageli = $imageli . '<li><img src="'. $images->name .'" /></li>';
			}
		}
	}
	else
	{
		$tch = "folder not found<br />" . $tch_folder;
	}
	
	if($imageli <> "")
	{
$tch = $tch . "<style type='text/css' media='screen'>
#tiny-carousel-slider1 { height: 1%; margin: 30px 0 0; overflow:hidden; position: relative; padding: 0 50px 10px;   }
#tiny-carousel-slider1 .viewport { height: ".$tch_height."px; overflow: hidden; position: relative; }
#tiny-carousel-slider1 .buttons { background: #C01313; border-radius: 35px; display: block; position: absolute;
top: 40%; left: 0; width: 35px; height: 35px; color: #fff; font-weight: bold; text-align: center; line-height: 35px; text-decoration: none;
font-size: 22px; }
#tiny-carousel-slider1 .next { right: 0; left: auto;top: 40%; }
#tiny-carousel-slider1 .buttons:hover{ color: #C01313;background: #fff; }
#tiny-carousel-slider1 .disable { visibility: hidden; }
#tiny-carousel-slider1 .overview { list-style: none; position: absolute; padding: 0; margin: 0; width: ".$tch_width."px; left: 0 top: 0; }
#tiny-carousel-slider1 .overview li{ float: left; margin: 0 20px 0 0; padding: 1px; height: ".$tch_height."px; border: 1px solid #dcdcdc; width: ".$tch_width."px;}
</style>";
	
		//$tch = $tch . '<p>';
		$tch = $tch . '<div id="tiny-carousel-slider1">';
			$tch = $tch . '<a class="buttons prev" href="#">&#60;</a>';
			$tch = $tch . '<div class="viewport">';
				$tch = $tch . '<ul class="overview">';
					$tch = $tch . $imageli;
				$tch = $tch . '</ul>';
			$tch = $tch . '</div>';
			$tch = $tch . '<a class="buttons next" href="#">&#62;</a>';
		$tch = $tch . '</div>';
		//$tch = $tch . '</p>';
		
		$tch = $tch . '<script type="text/javascript">';
		$tch = $tch . 'jQuery(document).ready(function(){';
			$tch = $tch . "jQuery('#tiny-carousel-slider1').tinycarousel({ buttons: ".$tch_controls.", interval: ".$tch_interval.", intervalTime: ".$tch_intervaltime.", animationTime: ".$tch_duration." });";
		$tch = $tch . '});';
		$tch = $tch . '</script>';
	}
	return $tch;
}

function TinyCarousel_install() 
{
	global $wpdb, $wp_version;
	if($wpdb->get_var("show tables like '". TinyCarouselTable . "'") != TinyCarouselTable) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". TinyCarouselTable . "` (";
		$sSql = $sSql . "`tch_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`tch_viewport` int(11) NOT NULL default '473' ,";
		$sSql = $sSql . "`tch_width` int(11) NOT NULL default '200' ,";
		$sSql = $sSql . "`tch_height` int(11) NOT NULL default '150' ,";
		$sSql = $sSql . "`tch_display` int(11) NOT NULL default '1' ,";
		$sSql = $sSql . "`tch_controls` VARCHAR( 5 ) NOT NULL default 'true',";
		$sSql = $sSql . "`tch_interval` VARCHAR( 5 ) NOT NULL default 'true',";
		$sSql = $sSql . "`tch_intervaltime` int(11) NOT NULL default '3000' ,";
		$sSql = $sSql . "`tch_duration` int(11) NOT NULL default '2000' ,";
		$sSql = $sSql . "`tch_folder` VARCHAR( 255 ) NOT NULL,";
		$sSql = $sSql . "`tch_random` VARCHAR( 3 ) NOT NULL default 'NO',";
		$sSql = $sSql . "PRIMARY KEY ( `tch_id` )";
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		$wpdb->query($sSql);
		$IsSql = "INSERT INTO `". TinyCarouselTable . "` (`tch_folder`)"; 
		$sSql = $IsSql . " VALUES ('wp-content/plugins/tiny-carousel-horizontal-slider/images/');";
		$wpdb->query($sSql);
		$IsSql = "INSERT INTO `". TinyCarouselTable . "` (`tch_width`,`tch_height`,`tch_folder`)"; 
		$sSql = $IsSql . " VALUES (100, 75, 'wp-content/plugins/tiny-carousel-horizontal-slider/images/100x75/');";
		$wpdb->query($sSql);
	}
}

function TinyCarousel_deactivation() 
{
	// No action required.
}

function TinyCarousel_admin()
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? sanitize_text_field($_GET['ac']) : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/image-management-edit.php');
			break;
		case 'add':
			include('pages/image-management-add.php');
			break;
		case 'set':
			include('pages/widget-setting.php');
			break;
		default:
			include('pages/image-management-show.php');
			break;
	}
}

function TinyCarousel_add_to_menu() 
{
	add_options_page( __('Tiny carousel', 'tiny-carousel-horizontal-slider'), __('Tiny carousel', 'tiny-carousel-horizontal-slider'), 'manage_options', 'tiny-carousel-horizontal-slider', 'TinyCarousel_admin' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'TinyCarousel_add_to_menu');
}

function TinyCarousel_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jquery.tinycarousel.min', TinyCarousel_PLUGIN_URL .'/inc/jquery.tinycarousel.js');
	}
}   

function TinyCarousel_textdomain() 
{
	  load_plugin_textdomain( 'tiny-carousel-horizontal-slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function TinyCarousel_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'tiny-carousel-horizontal-slider':
				wp_register_script( 'tiny-carousel-adminscripts', TinyCarousel_PLUGIN_URL . '/pages/setting.js', '', '', true );
				wp_enqueue_script( 'tiny-carousel-adminscripts' );
				$tiny_carousel_select_params = array(
					'tch_width'  		=> __( 'Please enter the image width. only number.', 'tiny-carousel-select', 'tiny-carousel-horizontal-slider' ),
					'tch_height'  		=> __( 'Please enter the image height. only number.', 'tiny-carousel-select', 'tiny-carousel-horizontal-slider' ),
					'tch_display'  		=> __( 'Please enter the display. only number.', 'tiny-carousel-select', 'tiny-carousel-horizontal-slider' ),
					'tch_intervaltime'  => __( 'Please enter the interval time. only number.', 'tiny-carousel-select', 'tiny-carousel-horizontal-slider' ),
					'tch_duration'  	=> __( 'Please enter the duration. only number.', 'tiny-carousel-select', 'tiny-carousel-horizontal-slider' ),
					'tch_folder'  		=> __( 'Please enter the image folder location. Example: wp-content/plugins/tiny-carousel-horizontal-slider/images/', 'tiny-carousel-select', 'tiny-carousel-horizontal-slider' ),
					'tch_delete'  		=> __( 'Do you want to delete this record?', 'tiny-carousel-select', 'tiny-carousel-horizontal-slider' ),
				);
				wp_localize_script( 'tiny-carousel-adminscripts', 'tiny_carousel_adminscripts', $tiny_carousel_select_params );
				break;
		}
	}
}

add_action('plugins_loaded', 'TinyCarousel_textdomain');
add_action('wp_enqueue_scripts', 'TinyCarousel_add_javascript_files');
register_activation_hook(__FILE__, 'TinyCarousel_install');
register_deactivation_hook(__FILE__, 'TinyCarousel_deactivation');
add_action('admin_enqueue_scripts', 'TinyCarousel_adminscripts');

class TinyCarousel_validation
{
	public static function num_val($value)
	{
		$returnvalue = "valid";
		if( !is_numeric($value) ) 
		{ 
			$returnvalue = "invalid";
		}
		return $returnvalue;
	}
	
	public static function val_yn($value)
	{
		$returnvalue = "YES";
		if($value == "YES" || $value == "NO")
		{
			$returnvalue = $value;
		}
		return $returnvalue;
	}
	
	public static function val_tf($value)
	{
		$returnvalue = "true";
		if($value == "true" || $value == "false")
		{
			$returnvalue = $value;
		}
		return $returnvalue;
	}
}
?>