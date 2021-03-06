<?php
/**
 * Plugin Name: Better Search Widget
 * Plugin URI: http://jamessocol.com/projects/better-search-widget.php
 * Description: Creates a more flexible search widget, with a title and everything. Lets you customize the title, button value, and field length.
 * Version: 1.1.0
 * Author: James Socol
 * Author URI: http://jamessocol.com/
 * 
 * Translations:
 *     de_DE: Marco Jung <info@mjml.de>
 *     en_US: James Socol <me@jamessocol.com>
 *     fr_FR: James Socol <me@jamessocol.com>
 */

/*

Copyright (c) 2008  James Socol  (email : me@jamessocol.com)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/
 
$js_bsw_domain = 'better-search-widget';

/**
 * The actual widget.
 * 
 * Pulls the options out of the database and displays the 
 * search widget.
 */
function js_better_search_widget ( $argv )
{
	global $js_bsw_domain;

    extract($argv);
    $options = get_option('js_better_search_widget');
    
    $title   = $options['title']/* ? $options['title'] : __('default_title',$js_bsw_domain)*/;
    $button  = $options['button'] ? $options['button'] : __('default_button',$js_bsw_domain);
    $length  = ctype_digit($options['length']) ? $options['length'] : 15;
    
    $default = $options['default'] ? addslashes($options['default']) : '';
    $script  = $default ? $options['script'] : false;

	$focus   = $options['focus'] ? $options['focus'] : '#000';
	$blur    = $options['blur'] ? $options['blur'] : '#999';
?>
    <?php echo $before_widget; ?>
        <?php if($title) echo $before_title,$title,$after_title; ?>
	<form id="better-search-form" method="get" action="<?php bloginfo('home'); ?>">
	<div>
		<input type="text" name="s" id="s" size="<?php echo $length; ?>" <?php if($_GET['s']) echo "value='{$_GET['s']}'"; else if($default) echo "value='$default'"; ?> <?php if($script) echo "onfocus='this.style.color=\"$focus\";if(\"$default\"==this.value)this.value=\"\";' onblur='if(\"\"==this.value){this.style.color=\"$blur\";this.value=\"$default\";}' style='color:$blur'"; ?> />
		<input type="submit" value="<?php echo attribute_escape($button); ?>" />
	</div>
	</form>
    <?php echo $after_widget; ?>
<?php
}

/**
 * The widget options form.
 * 
 * Displays the form for changing the widget settings
 * on the widget admin page.
 */
function js_better_search_widget_control ()
{
	global $js_bsw_domain;
	
    $options = $newoptions = get_option('js_better_search_widget');
    if ( $_POST['better-search-submit'] ) {
		$newoptions['title']   = strip_tags(stripslashes($_POST['better-search-title']));
		$newoptions['button']  = strip_tags(stripslashes($_POST['better-search-button']));
		$newoptions['length']  = preg_replace('/\D/', '', $_POST['better-search-length']);
		$newoptions['default'] = strip_tags(stripslashes($_POST['better-search-default']));
		$newoptions['script']  = (1==$_POST['better-search-script'])?1:0;
		$newoptions['focus']   = htmlentities(strip_tags($_POST['better-search-focus']));
		$newoptions['blur']    = htmlentities(strip_tags($_POST['better-search-blur']));
    }

    if ( $options != $newoptions ) {
        $options = $newoptions;
        update_option('js_better_search_widget', $options);
    }
    $title   = $options['title'] ? $options['title'] : __('default_title', $js_bsw_domain);
    $button  = $options['button'] ? $options['button'] : __('default_button', $js_bsw_domain);
    $length  = $options['length'];
    $default = $options['default'];
	$script  = $options['script'];
	$focus   = $options['focus'];
	$blur    = $options['blur'];
?>
		<p><label for="better-search-title"><?php _e('form_title',$js_bsw_domain); ?> <input type="text" style="width: 90%;" id="better-search-title" name="better-search-title" value="<?php echo $title; ?>" /></label></p>
		
		<p><label for="better-search-button"><?php _e('form_button',$js_bsw_domain); ?> <input type="text" style="width: 90%;" id="better-search-button" name="better-search-button" value="<?php echo $button; ?>" /></label></p>
		
		<p><label for="better-search-length"><?php _e('form_size',$js_bsw_domain); ?> <input type="text" style="width: 30px;" id="better-search-length" name="better-search-length" value="<?php echo $length; ?>" /></label></p>
		
		<p><label for="better-search-default"><?php _e('form_default',$js_bsw_domain); ?> <input type="text" style="width: 90%;" id="better-search-default" name="better-search-default" value="<?php echo $default; ?>" /></label></p>
		
		<p><?php _e('form_script',$js_bsw_domain); ?>
			<label for="better-search-script-yes" style="display: block; padding-left: 15px"><input type="radio" name="better-search-script" id="better-search-script-yes" value="1" <?php if(1==$script) echo "checked='checked'"?> /> <?php _e('form_script_on',$js_bsw_domain); ?></label>
			<label for="better-search-script-no" style="display: block; padding-left: 15px"><input type="radio" name="better-search-script" id="better-search-script-no" value="0" <?php if(1!=$script) echo "checked='checked'"?> /> <?php _e('form_script_off',$js_bsw_domain); ?></label>
		</p>

		<p><label for="better-search-focus"><?php _e('form_focus_color',$js_bsw_domain); ?>
			<input type="text" size="7" name="better-search-focus" id="better-search-focus" value="<?php echo $focus; ?>" onchange="this.style.color=this.value" style="color:<?php echo $focus; ?>" />
		</label></p>

		<p><label for="better-search-blur"><?php _e('form_blur_color',$js_bsw_domain); ?>
			<input type="text" size="7" name="better-search-blur" id="better-search-blur" value="<?php echo $blur; ?>" onchange="this.style.color=this.value" style="color:<?php echo $blur; ?>" />
		</label></p>
		
		<input type="hidden" id="better-search-submit" name="better-search-submit" value="1" />
<?php
}

/**
 * Load the l10n files and register the widget and control.
 */
function js_better_search_widget_init ()
{
	global $js_bsw_domain;
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain($js_bsw_domain, 'wp-content/plugins/'.$plugin_dir.'/languages',$plugin_dir.'/languages');

	if ( function_exists('wp_register_sidebar_widget') ) {
		wp_register_sidebar_widget('better-search-widget', __('widget_name',$js_bsw_domain), 'js_better_search_widget', array('description'=>__('widget_description',$js_bsw_domain)), 'js_better_search_widget');
		wp_register_widget_control('better-search-widget', __('widget_name',$js_bsw_domain), 'js_better_search_widget_control', array('description'=>__('widget_description',$js_bsw_domain)));
    } else if ( function_exists('register_sidebar_widget') ) {
        register_sidebar_widget(__('widget_name',$js_bsw_domain), "js_better_search_widget");
        register_widget_control(__('widget_name',$js_bsw_domain), 'js_better_search_widget_control');
    }
}

add_action('widgets_init', 'js_better_search_widget_init');

