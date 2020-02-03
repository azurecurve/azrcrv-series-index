<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Series Index
 * Description: Displays Index of Series Posts using series-index Shortcode. This plugin is multi-site compatible, contains an inbuilt show/hide toggle and supports localisation..
 * Version: 1.1.1
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/series-index
 * Text Domain: series-index
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname(__FILE__).'/pluginmenu/menu.php');
register_activation_hook(__FILE__, 'azrcrv_create_plugin_menu_si');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
register_activation_hook(__FILE__, 'azrcrv_si_set_default_options');

// add actions
add_action('admin_menu', 'azrcrv_si_create_admin_menu');
add_action('admin_post_azrcrv_si_save_options', 'azrcrv_si_save_options');
add_action('wp_enqueue_scripts', 'azrcrv_si_load_css');
//add_action('the_posts', 'azrcrv_si_check_for_shortcode');
add_action('plugins_loaded', 'azrcrv_si_load_languages');

// add filters
add_filter('plugin_action_links', 'azrcrv_si_add_plugin_action_link', 10, 2);

// add shortcodes
add_shortcode('series-index', 'azrcrv_si_display_series_index');
add_shortcode('index-of-series', 'azrcrv_si_display_index_of_series');
add_shortcode('series-index-link', 'azrcrv_si_display_series_index_link');

/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('series-index', false, $plugin_rel_path);
}

/**
 * Check if shortcode on page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_check_for_shortcode($posts){
    if (empty($posts)){
        return $posts;
	}
 
    // false because we have to search through the posts first
    $found = false;
	
	$shortcodes = array(
						'series-index','index-of-series','series-index-link'
						);
	
    // search through each post
    foreach ($posts as $post){
		foreach ($shortcodes as $shortcode){
			// check the post content for the short code
			if (has_shortcode($post->post_content, $shortcode)){
				$found = true;
				// break loop as shortcode found in page content
				break 2;
			}
		}
	}
 
    if ($found){
        azrcrv_si_load_css();
    }
    return $posts;
}

/**
 * Load CSS.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_load_css(){
	wp_register_style('azrcrv-si', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
	wp_enqueue_style('azrcrv-si', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
}

/**
 * Set default options for plugin.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_set_default_options($networkwide){
	
	$option_name = 'azrcrv-si';
	$old_option_name = 'azc_si_options';
	
	$new_options = array(
				'width' => "65%",
				'toggle_default' => 1,
				'space_before_title_separator' => 0,
				'title_separator' => ":",
				'space_after_title_separator' => 1,
				'container_before' => "<table class='azrcrv-si' style='width: %s; ' >",
				'container_after' => "</table>",
				'enable_header' => 1,
				'enable_header_link' => 1,
				'header_before' => "<tr><th class='azrcrv-si'>",
				'header_after' => "</th></tr>",
				'current_before' => "<tr><td>",
				'current_after' => "</td class='azrcrv-si'></tr>",
				'detail_before' => "<tr><td>",
				'detail_after' => "</td class='azrcrv-si'></tr>"
			);
	
	// set defaults for multi-site
	if (function_exists('is_multisite') && is_multisite()){
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide){
			global $wpdb;

			$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			$original_blog_id = get_current_blog_id();

			foreach ($blog_ids as $blog_id){
				switch_to_blog($blog_id);

				if (get_option($option_name) === false){
					if (get_option($old_option_name) === false){
						add_option($option_name, $new_options);
					}else{
						add_option($option_name, get_option($old_option_name));
					}
				}
			}

			switch_to_blog($original_blog_id);
		}else{
			if (get_option($option_name) === false){
				if (get_option($old_option_name) === false){
					add_option($option_name, $new_options);
				}else{
					add_option($option_name, get_option($old_option_name));
				}
			}
		}
		if (get_site_option($option_name) === false){
				if (get_option($old_option_name) === false){
					add_option($option_name, $new_options);
				}else{
					add_option($option_name, get_option($old_option_name));
				}
		}
	}
	//set defaults for single site
	else{
		if (get_option($option_name) === false){
				if (get_option($old_option_name) === false){
					add_option($option_name, $new_options);
				}else{
					add_option($option_name, get_option($old_option_name));
				}
		}
	}
}

/**
 * Add Series Index action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=azrcrv-si">'.esc_html__('Settings' ,'series-index').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_create_admin_menu(){
	//global $admin_page_hooks;
	
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Series Index Settings", "series-index")
						,esc_html__("Series Index", "series-index")
						,'manage_options'
						,'azrcrv-si'
						,'azrcrv_si_display_options');
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_display_options(){
	if (!current_user_can('manage_options')){
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'series-index'));
    }
	
	// Retrieve plugin configuration options from database
	$options = get_option('azrcrv-si');
	?>
	<div id="azrcrv-si-general" class="wrap">
		<fieldset>
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<?php if(isset($_GET['settings-updated'])){ ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e('Settings have been saved.', 'series-index'); ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_si_save_options" />
				<input name="page_options" type="hidden" value="width,title_separator,container_before,container_after,enable_header,enable_header_link,header_before,header_after,detail_before,detail_after" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-si', 'azrcrv-si-nonce'); ?>
				<table class="form-table">
				<tr><td colspan=2>
					<?php esc_html_e('When creating a series of posts there are two custom fields required:', 'series-index'); ?>
					
					<ol>
					<li><strong><em>Series - </em></strong> <?php esc_html_e('This is the title of the series.', 'series-index'); ?></li>
					<li><strong><em>Series Position - </em></strong><?php esc_html_e(stripslashes('This is the order the posts should be displayed in; any post which is part of the series, but should not be included in the displayed index should have a series index of <strong>0</strong>.'), 'series-index'); ?></li>
					</ol>
					<?php sprintf(esc_html_e('Place the %1$s shortcode in the post where the index should be displayed. The series index to be displayed is automatically derived from the %2$s custom field. The shortcode can be used in any post or page if the title parameter is used (see below).', 'series-index'), '<strong>[series-index]</strong>', '<strong>Series</strong>'); ?>
					
					<?php esc_html_e('There are four optional parameters which can be used for the series-index shortcode:', 'series-index'); ?>
					<ol>
					<li><strong><em>title - </em></strong><?php esc_html_e('only required if the series index is not being displayed in a post (or a page) within the series; set it to the title of the required series.', 'series-index'); ?>
					<li><strong><em>replace - </em></strong><?php esc_html_e('only required if the series title in the post name differs from the <strong>Series</strong> custom field.', 'series-index'); ?>
					<li><strong><em>width - </em></strong> <?php esc_html_e('only required if the series index should have a different width to the default specified in the settings.', 'series-index'); ?>
					
					<?php
					if (azrcrv_si_is_plugin_active('azrcrv-toggle-showhide/azrcrv-toggle-showhide.php')){
						$togglerequired = "azurecurve Toggle Show/Hide plugin.";
					}else{
						$togglerequired = "<a href='https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/'>azurecurve Toggle Show/Hide plugin</a>.";
					}
					?>
					<li><strong><em>toggle - </em></strong><?php printf(esc_html__('enables the show/hide toggle; 1 shows, 0 hides as start position. Requires %s', 'series-index'), $togglerequired); ?>
					<li><strong><em>heading - </em></strong><?php esc_html_e('only required to override the default toggle heading of "Click to show/hide <series title> Series Index".', 'series-index'); ?>
					</ol>
					
					<?php
					$tshlink = esc_html__('all standard attributes', 'series-index');
					if (azrcrv_si_is_plugin_active('azrcrv-toggle-showhide/azrcrv-toggle-showhide.php')){
						$tshlink = "<a href='admin.php?page=azrcrv-tsh'>".$tshlink."</a>";
					}
					esc_html_e(sprintf('%s required for toggle; %s are available in the toggle shortcode.', 'azurecurve Toggle Show/Hide', $tshlink), 'series-index');
					?>
					
				</td></tr>
				
				<tr><th scope="row"><label for="width"><?php esc_html_e('Index Width', 'series-index'); ?></label></th><td>
					<input type="text" name="width" value="<?php echo esc_html(stripslashes($options['width'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Specify the width of the index; e.g. 75% or 500px', 'series-index'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e("Toggle Default", "series-index"); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Toggle Default', 'series-index'); ?></span></legend>
					<label for="toggle_default"><input name="toggle_default" type="checkbox" id="toggle" value="1" <?php checked('1', $options['toggle_default']); ?> /><?php esc_html_e('Show/Hide Toggle default enabled?', 'series-index'); ?></label>
					</fieldset>
				</td></tr>
				
				<tr><th scope="row"><label for="width"><?php esc_html_e('Title Separator', 'series-index'); ?></label></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Space before title separator?', 'series-index'); ?></span></legend>
					<label for="space_before_title_separator"><input name="space_before_title_separator" type="checkbox" id="space_before_title_separator" value="1" <?php checked('1', $options['space_before_title_separator']); ?> /><?php esc_html_e('Space before?', 'series-index'); ?></label>
					<input type="text" name="title_separator" value="<?php echo esc_html(stripslashes($options['title_separator'])); ?>" class="small-text" /><legend class="screen-reader-text"><span><?php esc_html_e('Space after title separator?', 'series-index'); ?></span></legend>
					<label for="space_after_title_separator"><input name="space_after_title_separator" type="checkbox" id="space_after_title_separator" value="1" <?php checked('1', $options['space_after_title_separator']); ?> /><?php esc_html_e('Space after?', 'series-index'); ?></label>
					</fieldset>
					<p class="description" style='width: 70%; margin-left: 0; '><?php esc_html_e('If your series title is included in the post title specify the separator and surrounding spaces. e.g. <strong>Installing Microsoft Dynamics GP 2013 R2: Introduction</strong> has a separator of <strong>:</strong> with a following space so the <strong>Space before?</strong> checkbox should be unmarked, <strong>:</strong> entered in the text box and the <strong>Space after?</strong> checkbox marked.', 'series-index'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="width"><?php esc_html_e('Start/End Index', 'series-index'); ?></label></th><td>
					<input type="text" name="container_before" value="<?php echo esc_html(stripslashes($options['container_before'])); ?>" class="regular-text" /> 
					/ <input type="text" name="container_after" value="<?php echo esc_html(stripslashes($options['container_after'])); ?>" class="short-text" />
					<p class="description" style='width: 70%; margin-left: 0; '><?php esc_html_e(sprintf('Enter %s to be swapped out for value specified in width parameter otherwise 100%% will be used.', '%s'), 'series-index'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Enable Header Row', 'series-index'); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Enable Header Row', 'series-index'); ?></span></legend>
					<label for="enable_header"><input name="enable_header" type="checkbox" id="enable_header" value="1" <?php checked('1', $options['enable_header']); ?> /><?php esc_html_e('Display header row?', 'series-index'); ?></label>
					</fieldset>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Enable Header Link', 'series-index'); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Enable Header Link', 'series-index'); ?></span></legend>
					<label for="enable_header_link"><input name="enable_header_link" type="checkbox" id="enable_header_link" value="1" <?php checked('1', $options['enable_header_link']); ?> /><?php esc_html_e('Enable header link back to series index?', 'series-index'); ?></label>
					</fieldset>
				</td></tr>
				
				<tr><th scope="row"><label for="width"><?php esc_html_e('Start/End Header Row', 'series-index'); ?></label></th><td>
					<input type="text" name="header_before" value="<?php echo esc_html(stripslashes($options['header_before']));	?>" class="regular-text" /> 
					/ <input type="text" name="header_after" value="<?php echo esc_html(stripslashes($options['header_after']));	?>" class="short-text" />
				</td></tr>
				
				<tr><th scope="row"><label for="width"><?php esc_html_e('Start/End Detail Row', 'series-index'); ?></label></th><td>
					<input type="text" name="detail_before" value="<?php echo esc_html(stripslashes($options['detail_before']));	?>" class="regular-text" /> 
					/ <input type="text" name="detail_after" value="<?php echo esc_html(stripslashes($options['detail_after']));	?>" class="short-text" />
					<p class="description" style='width: 70%; margin-left: 0; '></p>
				</td></tr>
				
				<tr><th scope="row"><label for="width"><?php esc_html_e('Start/End Current Row', 'series-index'); ?></label></th><td>
					<input type="text" name="current_before" value="<?php echo esc_html(stripslashes($options['current_before']));	?>" class="regular-text" /> 
					/ <input type="text" name="current_after" value="<?php echo esc_html(stripslashes($options['current_after']));	?>" class="short-text" />
					<p class="description" style='width: 70%; margin-left: 0; '><?php esc_html_e('The current post can be formatted differently to the other detail rows.', 'series-index'); ?></p>
				</td></tr>
				
				</table>
				<input type="submit" value="Save Changes" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Save settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'series-index'));
	}

	if (! empty($_POST) && check_admin_referer('azrcrv-si', 'azrcrv-si-nonce')){	
		// Retrieve original plugin options array
		$options = get_option('azrcrv-si');
		
		$option_name = 'width';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		$option_name = 'toggle_default';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		$option_name = 'space_before_title_separator';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		$option_name = 'title_separator';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		$option_name = 'space_after_title_separator';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$allowed = azrcrv_si_get_allowed_tags();
		
		$option_name = 'container_before';
		if (isset($_POST[$option_name])){
			$options[$option_name] = wp_kses(stripslashes($_POST[$option_name]), $allowed);
		}
		
		$option_name = 'container_after';
		if (isset($_POST[$option_name])){
			$options[$option_name] = wp_kses(stripslashes($_POST[$option_name]), $allowed);
		}
		
		$option_name = 'enable_header';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'enable_header_link';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'header_before';
		if (isset($_POST[$option_name])){
			$options[$option_name] = wp_kses(stripslashes($_POST[$option_name]), $allowed);
		}
		$option_name = 'header_after';
		if (isset($_POST[$option_name])){
			$options[$option_name] = wp_kses(stripslashes($_POST[$option_name]), $allowed);
		}
		
		$option_name = 'detail_before';
		if (isset($_POST[$option_name])){
			$options[$option_name] = wp_kses(stripslashes($_POST[$option_name]), $allowed);
		}
		$option_name = 'detail_after';
		if (isset($_POST[$option_name])){
			$options[$option_name] = wp_kses(stripslashes($_POST[$option_name]), $allowed);
		}
		
		$option_name = 'current_before';
		if (isset($_POST[$option_name])){
			$options[$option_name] = wp_kses(stripslashes($_POST[$option_name]), $allowed);
		}
		$option_name = 'current_after';
		if (isset($_POST[$option_name])){
			$options[$option_name] = wp_kses(stripslashes($_POST[$option_name]), $allowed);
		}
		
		// Store updated options array to database
		update_option('azrcrv-si', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-si&settings-updated', admin_url('admin.php')));
		exit;
	}
}

/**
 * Get allowed tags.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_get_allowed_tags() {
	
    $allowed_tags = wp_kses_allowed_html();
	
    $allowed_tags['table']['class'] = 1;
    $allowed_tags['table']['style'] = 1;
    $allowed_tags['tr']['class'] = 1;
    $allowed_tags['tr']['style'] = 1;
    $allowed_tags['th']['class'] = 1;
    $allowed_tags['th']['style'] = 1;
    $allowed_tags['td']['class'] = 1;
    $allowed_tags['td']['style'] = 1;
	
    return $allowed_tags;
}

/**
 * Display series index in post.
 *
 * @since 1.0.0
 *
 */
 function azrcrv_si_display_series_index($atts, $content = null){
	// Retrieve plugin configuration options from database
	$options = get_option('azrcrv-si');
	
	$args = shortcode_atts(array(
		'title' => "",
		'replace' => "",
		'width' => stripslashes($options['width']),
		'toggle' => stripslashes($options['toggle_default']),
		'title_color' => '',
		'title_font' => '',
		'title_font_size' => '',
		'title_font_weight' => '',
		'expand' => 0,
		'border' => '',
		'bgtitle' => '',
		'bgtext' => '',
		'text_color' => '',
		'text_font' => '',
		'text_font_size' => '',
		'text_font_weight' => '',
		'image_location' => '',
	), $atts);
	$title = $args['title'];
	$replace = $args['replace'];
	$width = $args['width'];
	$toggle = $args['toggle'];
	$title_color = $args['title_color'];
	$title_font = $args['title_font'];
	$title_font_size = $args['title_font_size'];
	$title_font_weight = $args['title_font_weight'];
	$expand = (int) $args['expand'];
	$border = $args['border'];
	$bgtitle = $args['bgtitle'];
	$bgtext = $args['bgtext'];
	$text_color = $args['text_color'];
	$text_font = $args['text_font'];
	$text_font_size = $args['text_font_size'];
	$text_font_weight = $args['text_font_weight'];
	$image_location = $args['image_location'];
	
	global $wpdb;
	$post_id = get_the_ID();
	if (strlen($title)==0){
		$series_title = get_post_meta($post_id, 'Series', true);
	}else{
		$series_title = $title;
	}
	$clean_series_title = sanitize_text_field(addslashes($series_title));
	$clean_replace = sanitize_text_field(addslashes($replace));
	$post = get_post($post_id); 
	$slug = $post->post_name;
	
	$title_separator = '';
	if ($options['space_before_title_separator'] == 1){
		$title_separator .= ' ';
	}
	$title_separator .= sanitize_text_field(stripslashes($options['title_separator']));
	if ($options['space_after_title_separator'] == 1){
		$title_separator .= ' ';
	}
	
	$SQL = "SELECT p.ID AS ID,p.post_name AS post_name, p.post_title AS post_title, YEAR(post_date) AS PostYear, DATE_FORMAT(post_date, '%m') AS PostMonth FROM `".$wpdb->prefix."posts` p INNER JOIN `".$wpdb->prefix."postmeta` pm ON pm.post_id = p.id AND pm.meta_key = 'SERIES' AND pm.meta_value = '".$clean_series_title."' INNER JOIN `".$wpdb->prefix."postmeta` pmsp ON pmsp.post_id = p.id AND pmsp.meta_value <> '0' AND (pmsp.meta_key = 'SERIES POSITION' or pmsp.meta_key = 'SERIES POS') WHERE p.post_status = 'publish' ORDER BY CONVERT(pmsp.meta_value, UNSIGNED INTEGER)";
	$myrows = $wpdb->get_results($SQL);
	//echo $SQL;
	
	$rows = '';
	foreach ($myrows as $myrow){
		if (strlen($replace) == 0){
			$post_title = str_replace($clean_series_title.$title_separator, '', $myrow->post_title);
		}else{
			$post_title = str_replace($replace, '', $myrow->post_title);
		}
		if ($myrow->post_name == $slug){
			$rows .= stripslashes($options['current_before'])."<span class='azrcrv-si-index'>".esc_html($post_title)."</span>".stripslashes($options['current_after']);
		}else{
			$rows .= stripslashes($options['detail_before'])."<a href='".get_the_permalink($myrow->ID)."' class='azrcrv-si-index'>".esc_html($post_title)."</a>".stripslashes($options['detail_after']);
		}
	}
	
	$header = '';
	if ($options['enable_header'] == 1){
		if ($options['enable_header_link'] == 1){
			$SQL = $wpdb->prepare("SELECT p.ID AS ID,DATE_FORMAT(post_date, '%Y/%m') as post_date, p.post_name FROM `".$wpdb->prefix."posts` p INNER JOIN `".$wpdb->prefix."postmeta` pm ON pm.post_id = p.id AND pm.meta_key = 'SERIES' AND pm.meta_value = '%s' INNER JOIN `".$wpdb->prefix."postmeta`  pmsp ON pmsp.post_id = p.id AND pmsp.meta_value = '0' AND (pmsp.meta_key = 'SERIES POSITION' or pmsp.meta_key = 'SERIES POS') ORDER BY CONVERT(pmsp.meta_value, UNSIGNED INTEGER) LIMIT 0,1", $clean_series_title);
			
			$myrows = $wpdb->get_results($SQL);
			//echo $SQL;
			
			$series_title_link = '';
			foreach ($myrows as $myrow){
				$series_title_link = '<a href="'.get_the_permalink($myrow->ID).'" class="azrcrv-si-link">';
				$series_title_link .= $series_title;
				$series_title_link .= '</a>';
			}
		}else{
			$series_title_link = $series_title;
		}
		$header = stripslashes($options['header_before']).$series_title_link.stripslashes($options['header_after']);
	}
	
	if (azrcrv_si_is_plugin_active('azrcrv-toggle-showhide/azrcrv-toggle-showhide.php') AND ($toggle == 1)){
		$output = str_replace('%s', '100%', stripslashes($options['container_before'])).$header.$rows.stripslashes($options['container_after']);
		if (strlen($title) == 0){
			$title = esc_html__(sprintf('Click to show/hide the %s Series Index', $series_title), 'series-index');
		}
		
		if (strlen($title) > 0){ $title = "title='".$title."'"; }
		if (strlen($width) > 0){ $width = "width='".$width."'"; }
		if (strlen($title_color) > 0){ $title_color = "title_color='".$title_color."'"; }
		if (strlen($title_font) > 0){ $title_font = "title_font='".$title_font."'"; }
		if (strlen($title_font_size) > 0){ $title_font_size = "title_font_size='".$title_font_size."'"; }
		if (strlen($title_font_weight) > 0){ $title_font_weight = "title_font_weight='".$title_font_weight."'"; }
		if (strlen($border) > 0){ $border = "border='".$border."'"; }
		if (strlen($bgtitle) > 0){ $bgtitle = "bgtitle='".$bgtitle."'"; }
		if (strlen($bgtext) > 0){ $bgtext = "bgtext='".$bgtext."'"; }
		if (strlen($text_color) > 0){ $text_color = "text_color='".$text_color."'"; }
		if (strlen($text_font) > 0){ $text_font = "text_font='".$text_font."'"; }
		if (strlen($text_font_size) > 0){ $text_font_size = "text_font_size='".$text_font_size."'"; }
		if (strlen($text_font_weight) > 0){ $text_font_weight = "text_font_weight='".$text_font_weight."'"; }
		if (strlen($image_location) > 0){ $image_location = "image_location='".$image_location."'"; }
		if (strlen($expand) > 0){ $expand = "expand='".$expand."'"; }
		
		$output = do_shortcode("[toggle ".$title." ".$width." ".$title_color." ".$title_font." ".$title_font_size." ".$title_font_weight." ".$border." ".$bgtitle." ".$bgtext." ".$text_color." ".$text_font." ".$text_font_size." ".$text_font_weight." ".$image_location." ".$expand."]".$output."[/toggle]");
	}else{
		$output = str_replace('%s', esc_html($width)."; margin: auto;", stripslashes($options['container_before'])).$header.$rows.stripslashes($options['container_after']);
	}
	return $output;
}

/**
 * Display index of series.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_display_index_of_series($atts, $content = null){
	// Retrieve plugin configuration options from database
	$options = get_option('azrcrv-si');
	
	$args = shortcode_atts(array(
		'width' => stripslashes($options['width']),
		'title' => "",
		'order' => "ASC",
		'toggle' => '0',
		'title_color' => '',
		'title_font' => '',
		'title_font_size' => '',
		'title_font_weight' => '',
		'expand' => 0,
		'border' => '',
		'bgtitle' => '',
		'bgtext' => '',
		'text_color' => '',
		'text_font' => '',
		'text_font_size' => '',
		'text_font_weight' => '',
		'image_location' => '',
	), $atts);
	$title = $args['title'];
	$width = $args['width'];
	$order = $args['order'];
	$toggle = $args['toggle'];
	$title_color = $args['title_color'];
	$title_font = $args['title_font'];
	$title_font_size = $args['title_font_size'];
	$title_font_weight = $args['title_font_weight'];
	$expand = (int) $args['expand'];
	$border = $args['border'];
	$bgtitle = $args['bgtitle'];
	$bgtext = $args['bgtext'];
	$text_color = $args['text_color'];
	$text_font = $args['text_font'];
	$text_font_size = $args['text_font_size'];
	$text_font_weight = $args['text_font_weight'];
	$image_location = $args['image_location'];
	
	if ($order != 'ASC'){
		$order = 'DESC';
	}
	
	global $wpdb;
	
	$SQL = "SELECT DISTINCT p.ID AS ID,REPLACE(post_title, ': Series Index', '') AS post_title,post_name, YEAR(post_date) AS PostYear, DATE_FORMAT(post_date, '%m') AS PostMonth, pm.meta_value as Series FROM `".$wpdb->prefix."posts` p INNER JOIN `".$wpdb->prefix."postmeta` pm ON pm.post_id = p.id AND pm.meta_key = 'SERIES' INNER JOIN `".$wpdb->prefix."postmeta` pmsp ON pmsp.post_id = p.id AND pmsp.meta_value = '0' AND (pmsp.meta_key = 'SERIES POSITION' or pmsp.meta_key = 'SERIES POS') WHERE p.post_status = 'publish' ORDER BY p.post_date $order";
	$myrows = $wpdb->get_results($SQL);
	//echo $SQL;
	
	$rows = '';
	foreach ($myrows as $myrow){
		$post_title = $myrow->post_title;
		$rows .= stripslashes($options['detail_before'])."<a href='".get_the_permalink($myrow->ID)."' class='azrcrv-si-index'>".$myrow->Series."</a>".stripslashes($options['detail_after']);
	}
	
	$header = stripslashes($options['header_before']).'Index of Series'.stripslashes($options['header_after']);
	if (azrcrv_si_is_plugin_active('azrcrv-toggle-showhide/azrcrv-toggle-showhide.php') AND ($toggle == 1)){
		$output = str_replace('%s', '100%', stripslashes($options['container_before'])).$header.$rows.stripslashes($options['container_after']);
		if (strlen($title) == 0){
			$title = esc_html__('Click to show/hide the Index of Series', 'series-index');
		}
		
		if (strlen($title) > 0){ $title = "title='".$title."'"; }
		if (strlen($width) > 0){ $width = "width='".$width."'"; }
		if (strlen($title_color) > 0){ $title_color = "title_color='".$title_color."'"; }
		if (strlen($title_font) > 0){ $title_font = "title_font='".$title_font."'"; }
		if (strlen($title_font_size) > 0){ $title_font_size = "title_font_size='".$title_font_size."'"; }
		if (strlen($title_font_weight) > 0){ $title_font_weight = "title_font_weight='".$title_font_weight."'"; }
		if (strlen($border) > 0){ $border = "border='".$border."'"; }
		if (strlen($bgtitle) > 0){ $bgtitle = "bgtitle='".$bgtitle."'"; }
		if (strlen($bgtext) > 0){ $bgtext = "bgtext='".$bgtext."'"; }
		if (strlen($text_color) > 0){ $text_color = "text_color='".$text_color."'"; }
		if (strlen($text_font) > 0){ $text_font = "text_font='".$text_font."'"; }
		if (strlen($text_font_size) > 0){ $text_font_size = "text_font_size='".$text_font_size."'"; }
		if (strlen($text_font_weight) > 0){ $text_font_weight = "text_font_weight='".$text_font_weight."'"; }
		if (strlen($image_location) > 0){ $image_location = "image_location='".$image_location."'"; }
		if (strlen($expand) > 0){ $expand = "expand='".$expand."'"; }
		$output = do_shortcode("[toggle ".$title." ".$width." ".$title_color." ".$title_font." ".$title_font_size." ".$title_font_weight." ".$border." ".$bgtitle." ".$bgtext." ".$text_color." ".$text_font." ".$text_font_size." ".$text_font_weight." ".$image_location." ".$expand."]".$output."[/toggle]");
	}else{
		$output = str_replace('%s', esc_html($width), stripslashes($options['container_before'])).$header.$rows.stripslashes($options['container_after']);
	}
	return $output;
}

/**
 * Display series index link.
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_display_series_index_link($atts, $content = null){
	
	global $wpdb;
	$post_id = get_the_ID();
	
	// Retrieve plugin configuration options from database
	$options = get_option('azrcrv-si');
	
	// get shortcode attributes
	$args = shortcode_atts(array(
		'title' => "",
	), $atts);
	$title = $args['title'];
	
	// if series title not supplied get Series for post
	if (strlen($title)==0){
		$series_title = get_post_meta($post_id, 'Series', true);
	}else{
		$series_title = $title;
	}
	
	$clean_series_title = sanitize_text_field(addslashes($series_title));
	
	//select series index parent
	$SQL = $wpdb->prepare("SELECT p.ID AS ID,DATE_FORMAT(post_date, '%Y/%m') as post_date, p.post_name FROM `".$wpdb->prefix."posts` p INNER JOIN `".$wpdb->prefix."postmeta` pm ON pm.post_id = p.id AND pm.meta_key = 'SERIES' AND pm.meta_value = '%s' INNER JOIN `".$wpdb->prefix."postmeta`  pmsp ON pmsp.post_id = p.id AND pmsp.meta_value = '0' AND (pmsp.meta_key = 'SERIES POSITION' or pmsp.meta_key = 'SERIES POS') ORDER BY CONVERT(pmsp.meta_value, UNSIGNED INTEGER) LIMIT 0,1", $clean_series_title);
	
	$myrows = $wpdb->get_results($SQL);
	//echo $SQL;
	
	// create link back to series index parent
	$series_title_link = '';
	foreach ($myrows as $myrow){
		$series_title_link = '<a href="'.get_the_permalink($myrow->ID).'">';
		// check if alternative link text supplied and use if so
		if (strlen($content) == 0){
			$series_title_link .= esc_html($series_title);
		}else{ 
			$series_title_link .= $content;
		}
		$series_title_link .= '</a>';
	}
	return $series_title_link;
}

/**
 * Check if function active (included due to standard function failing due to order of load).
 *
 * @since 1.0.0
 *
 */
function azrcrv_si_is_plugin_active($plugin){
    return in_array($plugin, (array) get_option('active_plugins', array()));
}

?>