<?php
/*
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'testimonials_widgets' );

/*
 * Register widget.
 */
function testimonials_widgets() {
	register_widget( 'Testimonials_Widget' );
	load_plugin_textdomain( 'testimonials-widget', null, '/testimonials-widget/languages/' );
}

/*
 * Widget class.
 */
class testimonials_widget extends WP_Widget {
	/* ---------------------------- */
	/* -------- Widget setup -------- */
	/* ---------------------------- */
	function Testimonials_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'testimonials_widget', 'description' => __('Testimonial widget plugin allows you display testimonials in a sidebar on your WordPress blog.', 'testimonials-widget') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'testimonials_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'testimonials_widget', __('Testimonials Widget', 'testimonials-widget'), $widget_ops, $control_ops );
	}

	/* ---------------------------- */
	/* ------- Display Widget -------- */
	/* ---------------------------- */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('the_title', $instance['title'], null);

		$testimonials = testimonialswidget_display_testimonials( $instance, $this->number );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display Widget */
		echo $testimonials;

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/* ---------------------------- */
	/* ------- Update Widget -------- */
	/* ---------------------------- */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = wp_kses_data($new_instance['title']);
		$instance['min_height'] = intval($new_instance['min_height']);
		$instance['show_author'] = (isset($new_instance['show_author']) && $new_instance['show_author']) ? true : false;
		$instance['show_source'] = (isset($new_instance['show_source']) && $new_instance['show_source']) ? true : false;
		$instance['refresh_interval'] = intval($new_instance['refresh_interval']);
		$instance['random_order'] = (isset($new_instance['random_order']) && $new_instance['random_order']) ? true : false;
		$instance['tags_all'] = (isset($new_instance['tags_all']) && $new_instance['tags_all'])?1:0;
		$instance['tags'] = wp_filter_nohtml_kses($new_instance['tags']);
		$instance['char_limit'] = intval($new_instance['char_limit']);
		$instance['limit'] = intval($new_instance['limit']);

		return $instance;
	}

	/* ---------------------------- */
	/* ------- Widget Settings ------- */
	/* ---------------------------- */

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Testimonials', 'testimonials-widget'),
			'min_height' => 150,
			'show_author' => 1,
			'show_source' => 1,
			'random_order' => 1,
			'tags_all' => 0,
			'refresh_interval' => 10,
			'tags' => '',
			'char_limit' => 500,
			'limit' => 10
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Now we define the display of widget options menu
		$show_author_checked = $show_source_checked	= $random_order_checked	= $tags_all_checked = '';

        if($instance['show_author'])
        	$show_author_checked = ' checked="checked"';
        if($instance['show_source'])
        	$show_source_checked = ' checked="checked"';
        if($instance['random_order'])
        	$random_order_checked = ' checked="checked"';
        if($instance['tags_all'])
        	$tags_all_checked = ' checked="checked"';

		echo '<p><label for="'.$this->get_field_id( 'title' ).'">'.__('Title', 'testimonials-widget').' </label><input class="widefat" type="text" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.htmlspecialchars($instance['title'], ENT_QUOTES).'" /></p>';
		echo '<p><label for="'.$this->get_field_id( 'min_height' ).'">'.__('Minimum Height', 'testimonials-widget').' </label><input class="widefat" type="text" id="'.$this->get_field_id( 'min_height' ).'" name="'.$this->get_field_name( 'min_height' ).'" value="'.htmlspecialchars($instance['min_height'], ENT_QUOTES).'" /><br/><span class="setting-description"><small>'.__('Minimum height in px, this must be set to a value that suits your logest testimonial (increase this value if you find that your testimonials are getting cut off).', 'testimonials-widget').'</small></span></p>';
		echo '<p><input type="checkbox" id="'.$this->get_field_id( 'show_author' ).'" name="'.$this->get_field_name( 'show_author' ).'" value="1"'.$show_author_checked.' /> <label for="'.$this->get_field_id( 'show_author' ).'">'.__('Show author?', 'testimonials-widget').'</label></p>';
		echo '<p><input type="checkbox" id="'.$this->get_field_id( 'show_source' ).'" name="'.$this->get_field_name( 'show_source' ).'" value="1"'.$show_source_checked.' /> <label for="'.$this->get_field_id( 'show_source' ).'">'.__('Show source?', 'testimonials-widget').'</label></p>';
		echo "<p style=\"text-align:left;\"><small><a id=\"".$this->get_field_id( 'adv_key' )."\" style=\"cursor:pointer;\" onclick=\"jQuery('div#".$this->get_field_id( 'adv_opts' )."').slideToggle();\">".__('Advanced options', 'testimonials-widget')." &raquo;</a></small></p>";
		echo '<div id="'.$this->get_field_id( 'adv_opts' ).'" style="display:none">';
		echo '<p><label for="'.$this->get_field_id( 'refresh_interval' ).'">'.__('Refresh Interval', 'testimonials-widget').' </label><input class="widefat" type="text" id="'.$this->get_field_id( 'refresh_interval' ).'" name="'.$this->get_field_name( 'refresh_interval' ).'" value="'.htmlspecialchars($instance['refresh_interval'], ENT_QUOTES).'" /><br/><span class="setting-description"><small>'.__('In seconds or 0 for no refresh.', 'testimonials-widget').'</small></span></p>';
		echo '<p><input type="checkbox" id="'.$this->get_field_id( 'random_order' ).'" name="'.$this->get_field_name( 'random_order' ).'" value="1"'.$random_order_checked.' /> <label for="'.$this->get_field_id( 'random_order' ).'">'.__('Random order', 'testimonials-widget').'</label><br/><span class="setting-description"><small>'.__('Unchecking this will rotate testimonials in the order added, latest first.', 'testimonials-widget').'</small></span></p>';
		echo '<p><label for="'.$this->get_field_id( 'tags' ).'">'.__('Tags filter', 'testimonials-widget').' </label><input class="widefat" type="text" id="'.$this->get_field_id( 'tags' ).'" name="'.$this->get_field_name( 'tags' ).'" value="'.htmlspecialchars($instance['tags'], ENT_QUOTES).'" /><br/><span class="setting-description"><small>'.__('Comma separated', 'testimonials-widget').'</small></span></p>';
		echo '<p><input type="checkbox" id="'.$this->get_field_id( 'tags_all' ).'" name="'.$this->get_field_name( 'tags_all' ).'" value="1"'.$tags_all_checked.' /> <label for="'.$this->get_field_id( 'tags_all' ).'">'.__('Require all tags', 'testimonials-widget').'</label><br/><span class="setting-description"><small>'.__('Checking this will select only testimonials with all of the given tags.', 'testimonials-widget').'</small></span></p>';
		echo '<p><label for="'.$this->get_field_id( 'char_limit' ).'">'.__('Character limit', 'testimonials-widget').' </label><input class="widefat" type="text" id="'.$this->get_field_id( 'char_limit' ).'" name="'.$this->get_field_name( 'char_limit' ).'" value="'.htmlspecialchars($instance['char_limit'], ENT_QUOTES).'" /><br/><span class="setting-description"><small>'.__('Number of characters to limit testimonial views to. Zero means no limit', 'testimonials-widget').'</small></span></p>';
		echo '<p><label for="'.$this->get_field_id( 'limit' ).'">'.__('Limit', 'testimonials-widget').' </label><input class="widefat" type="text" id="'.$this->get_field_id( 'limit' ).'" name="'.$this->get_field_name( 'limit' ).'" value="'.htmlspecialchars($instance['limit'], ENT_QUOTES).'" /><br/><span class="setting-description"><small>'.__('Number of testimonials to pull at a time. Zero means no limit', 'testimonials-widget').'</small></span></p>';
		echo '</div>';
	}
}
?>
