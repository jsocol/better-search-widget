<?php
/**
 * Plugin Name: Better Search Widget
 * Plugin URI: http://jamessocol.com/projects/better-search-widget.php
 * Description: Creates a more flexible search widget, with a title and everything. Lets you customize the title, button value, and field length.
 * Version: 1.0.0
 * Author: James Socol
 * Author URI: http://jamessocol.com/
 */

/*  Copyright 2008  James Socol  (email : me@jamessocol.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
function js_better_search_widget ( $argv )
{
    extract($argv);
    $options = get_option('js_better_search_widget');
    $title = $options['title'] ? $options['title'] : 'Search';
    $button = $options['button'] ? $options['button'] : 'Search';
    $length = ctype_digit($options['length']) ? $options['length'] : 15;
?>
    <?php echo $before_widget; ?>
        <?php echo $before_title.$title.$after_title; ?>
	<form id="searchform" method="get" action="<?php bloginfo('home'); ?>">
	<div>
	<input type="text" name="s" id="s" size="<?php echo $length; ?>" />
		<input type="submit" value="<?php echo attribute_escape($button); ?>" />
	</div>
	</form>
    <?php echo $after_widget; ?>
<?php
}

function js_better_search_widget_control ()
{
    $options = $newoptions = get_option('js_better_search_widget');
    if ( $_POST['better-search-submit'] ) {
        $newoptions['title'] = strip_tags(stripslashes($_POST['better-search-title']));
	$newoptions['button'] = strip_tags(stripslashes($_POST['better-search-button']));
	$newoptions['length'] = preg_replace('/\D/', '', $_POST['better-search-length']);
    }

    if ( $options != $newoptions ) {
        $options = $newoptions;
        update_option('js_better_search_widget', $options);
    }
    $title = $options['title'];
    $button = $options['button'];
    $length = $options['length'];
?>
        <p><label for="better-search-title"><?php _e('Title:'); ?> <input type="text" style="width: 250px;" id="better-search-title" name="better-search-title" value="<?php echo $title; ?>" /></label></p>
	<p><label for="better-search-button"><?php _e('Search Button:'); ?> <input type="text" style="width: 250px;" id="better-search-button" name="better-search-button" value="<?php echo $button; ?>" /></label></p>
	<p><label for="better-search-length"><?php _e('Search Box Size:'); ?> <input type="text" style="width: 30px;" id="better-search-length" name="better-search-length" value="<?php echo $length; ?>" /></label></p>
        <input type="hidden" id="better-search-submit" name="better-search-submit" value="1" />
<?php
}

function js_better_search_widget_init ()
{
    if ( function_exists('register_sidebar_widget') ) {
        register_sidebar_widget("Better Search", "js_better_search_widget");
        register_widget_control("Better Search", 'js_better_search_widget_control');
    }
}

add_action('widgets_init', 'js_better_search_widget_init');

?>
