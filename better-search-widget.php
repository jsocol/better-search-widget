<?php
/**
 * Plugin Name: Better Search Widget
 * Plugin URI: http://jamessocol.com/projects/better-search-widget.php
 * Description: Creates a more flexible search widget, with a title and everything. Lets you customize the title, button value, and field length.
 * Version: 1.0.0
 * Author: James Socol
 * Author URI: http://jamessocol.com/
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
