<?php
/*
	Plugin Name: Ultimate Toggle Footer
	Description: This plugin allows you to insert toggle-able content at the bottom of your homepage, available for your visitors to view if they’d like. It’s great for inserting SEO content in an unobtrusive way.
	Plugin URI: https://github.com/joethomas/ultimate-toggle-footer
	Version: 1.0.6
	Author: Joe Thomas
	Author URI: https://github.com/joethomas
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	Text Domain: ultimate-toggle-footer
	Domain Path: /languages/

	GitHub Plugin URI: https://github.com/joethomas/ultimate-toggle-footer
	GitHub Branch: master
*/

// Prevent direct file access
defined( 'ABSPATH' ) or exit;


/* Setup Plugin
==============================================================================*/

/**
 * Define the constants for use within the plugin
 */

// Plugin
function joe_utf_get_plugin_data() {
	$plugin = get_plugin_data( __FILE__, false, false );

	define( 'JOE_UTF_VER', $plugin['Version'] );
	define( 'JOE_UTF_TEXTDOMAIN', $plugin['TextDomain'] );
	define( 'JOE_UTF_NAME', $plugin['Name'] );
}
add_action( 'init', 'joe_utf_get_plugin_data' );

define( 'JOE_UTF_PREFIX', 'ultimate-toggle-footer' );


/* Bootstrap
==============================================================================*/

require_once( 'includes/updates.php' ); // controls plugin updates


/* Widget and Widget Area Setup
==============================================================================*/

/**
 * Register Home Subfooter Widget Area
 *
 */
function joe_utf_more_info_footer_register_widget_area() {
    register_sidebar( array(
		'id'			=> 'home-subfooter',
		'name'			=> __( 'Home Subfooter', 'ultimate-toggle-footer' ),
		'description'	=> __( 'Home Subfooter widget area.', 'ultimate-toggle-footer' ),
		'before_widget'	=> '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',
		'after_widget'	=> '</div></section>',
		'before_title'	=> '<h1 class="widget-title widgettitle">',
		'after_title'	=> '</h1>'
    ) );
}
add_action( 'widgets_init', 'joe_utf_more_info_footer_register_widget_area', 50 );

/**
 * Display Home Subfooter Widget Area
 *
 */
function joe_utf_more_info_footer_do_subfooter() {
	if ( is_active_sidebar( 'home-subfooter' ) && is_front_page() && ! is_home() ) {
	?>
		<div class="subfooter">
			<div class="home-subfooter widget-area">
			<?php
				dynamic_sidebar( 'home-subfooter' );
			?>
			</div>
		</div>
	<?php
	}
}
if( ! defined( 'PROPHOTO_SITE_URL' ) ) { // For themes other than ProPhoto
	add_action( 'wp_footer', 'joe_utf_more_info_footer_do_subfooter' );
} else { // For ProPhoto themes
	add_action( 'pp_end_body', 'joe_utf_more_info_footer_do_subfooter' );
}

/**
 * Widget Class
 *
 */

class Joe_Ultimate_Toggle_Footer extends WP_Widget {

	const VERSION = JOE_UTF_VER;

	/**
	 * Constructor Method
	 *
	 * Set some global values and create widget
	 */
	function __construct(){
		$options = array(
			'description' => 'A widget that displays togglable content and links in the footer of the site.',
			'name' => 'Ultimate Toggle Footer'
		);
		parent::__construct('Joe_Ultimate_Toggle_Footer','',$options);

		//Enqueue javascript in admin
		add_action( 'sidebar_admin_setup', array( $this, 'admin_setup' ) );

		//Enqueue default style in frontend if widget is displayed
		if ( is_active_widget(false, false, $this->id_base) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		}

	}

	// Enqueue admin javascript
	function admin_setup() {
		wp_enqueue_script( JOE_UTF_PREFIX . '-admin-scripts', plugins_url( 'includes/js/' . JOE_UTF_PREFIX . '-admin.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	// Enqueue frontend styles and scripts
	function frontend_scripts() {

		wp_enqueue_style( JOE_UTF_PREFIX . '-styles', plugins_url( 'includes/css/' . JOE_UTF_PREFIX . '.css', __FILE__ ), array(), self::VERSION );

		wp_enqueue_script( JOE_UTF_PREFIX . '-scripts', plugins_url( 'includes/js/' . JOE_UTF_PREFIX . '.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );

	}

	/**
	 * Widget Form
	 *
	 * Outputs the widget form that allows users to control the output of the widget
	 *
	 */
	public function form($instance) {
		extract($instance);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('more_info_text');?>">More Info Text: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('more_info_text');?>" name="<?php echo $this->get_field_name('more_info_text');?>" type="text" value="<?php if(isset($more_info_text)) echo esc_attr($more_info_text);?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('trigger_wrap');?>">Background Color Behind Button: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('trigger_wrap');?>" name="<?php echo $this->get_field_name('trigger_wrap');?>" type="text" value="<?php if(isset($trigger_wrap)) echo esc_attr($trigger_wrap);?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('trigger_bg');?>">Button Background Color: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('trigger_bg');?>" name="<?php echo $this->get_field_name('trigger_bg');?>" type="text" value="<?php if(isset($trigger_bg)) echo esc_attr($trigger_bg);?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('trigger_color');?>">Button Text Color: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('trigger_color');?>" name="<?php echo $this->get_field_name('trigger_color');?>" type="text" value="<?php if(isset($trigger_color)) echo esc_attr($trigger_color);?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('title');?>">Main Title: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php if(isset($title)) echo esc_attr($title);?>"/>
		</p>

		<textarea class="widefat" rows="10" cols="20" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo $textarea; ?></textarea>

		<p>
			<label for="<?php echo $this->get_field_id('numlinks');?>">No. of Links: </label>
			<input type="number" min="0" max="20" class="widefat" style="width:40px; text-align:center;" id="<?php echo $this->get_field_id('numlinksnum');?>" name="<?php echo $this->get_field_name('numlinksnum');?>" value="<?php echo !empty($numlinksnum) ? $numlinksnum:0;?>"/>
			<em style="font-size: 11px;"> (Save to see link fields)</em>
			<div style="padding: 5px 8px; background-color: #def2d8; font-size: 11px;"><strong>Warning:</strong> Reducing the number of links will delete the extraneous link information (will require it to be entered again if needed).</div>
		</p>
		<?php

		for($i=0;$i<$numlinksnum;$i++) {
			$count=$i+1;
			$target = 'jT' . $count;
			$link = 'jLink' . $count;
			$name = 'jName' . $count;
			$nofollow = 'jFollow' . $count;
			?>

			<hr />
			<h4>Link #<?php echo $count;?> Details</h4>

			<!-- Link Text Option -->
			<p>
				<label for="<?php echo $this->get_field_id($name);?>">Link Text:</label>
				<input class="widefat" style="background:#fff;" id="<?php echo $this->get_field_id($name);?>" name="<?php echo $this->get_field_name($name);?>" value="<?php if(isset($$name)) echo esc_attr($$name);?>"/>
			</p>

			<!-- Link URL Option -->
			<p>
				<label for="<?php echo $this->get_field_id($link);?>">URL:</label>
				<input class="widefat validate validate_url" id="<?php echo $this->get_field_id($link);?>" name="<?php echo $this->get_field_name($link);?>" value="<?php if(isset($$link)) echo esc_attr($$link);?>"/>
			</p>

			<!-- New Window Opening Option -->
			<p>

				<input type="checkbox" class="checkbox" <?php checked($instance[$target], true) ?> id="<?php echo $this->get_field_id($target);?>" name="<?php echo $this->get_field_name($target);?>" value="1"/>
				<label for="<?php echo $this->get_field_id($target);?>">Open In New Window</label>
			</p>

			<!-- No Follow Option -->
			<p>

				<input type="checkbox" class="checkbox" <?php checked($instance[$nofollow], true) ?> id="<?php echo $this->get_field_id($nofollow);?>" name="<?php echo $this->get_field_name($nofollow);?>" value="1"/>
				<label for="<?php echo $this->get_field_id($nofollow);?>">No Follow</label>
			</p>

		<?php
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('footerinfo');?>">More Info Footer: </label>
			<textarea class="widefat" rows="10" cols="20" id="<?php echo $this->get_field_id('footerinfo'); ?>" name="<?php echo $this->get_field_name('footerinfo'); ?>"><?php echo $footerinfo; ?></textarea>
		</p>
		<?php
	}

	/**
	 * Widget Output
	 *
	 * Outputs the widget on the frontend based on user's selected widget options
	 *
	 */

	public function widget( $args, $instance ){
		extract( $args );
		extract( $instance );
		$more_info_text = $instance['more_info_text'];
		$title = apply_filters( 'widget_title', $title );
		$textarea = $instance['textarea'];
		$footerinfo = $instance['footerinfo'];

		echo $before_widget;

		if ( ! empty( $more_info_text ) ) {
			echo '<div class="joe_utf-trigger-wrap" style="background-color: ' . $trigger_wrap . '; border-color: ' . $trigger_bg . ';">';
			echo '<span id="trigger" style="background-color: ' . $trigger_bg . '; color: ' . $trigger_color . ';">' . $more_info_text . '</span>';
			echo '</div>';
		} else {
			echo '<div class="joe_utf-trigger-wrap" style="background-color: ' . $trigger_wrap . '; border-color: ' . $trigger_bg . ';">';
			echo '<span id="trigger" style="padding: 10px; padding: 1rem; background-color: ' . $trigger_bg . '; color: ' . $trigger_color . ';"></span>';
			echo '</div>';
		}

		echo '<!-- More Info --><div id="joe_utf" class="joe_utf">';

		echo '<!-- More Info Wrap --><div class="joe_utf-wrap">';

		if( ! empty( $title ) || ! empty ( $textarea ) ) {

			echo '<!-- More Info Content --><div class="joe_utf-content">';

			if( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}

			if( ! empty( $textarea ) ) {
				echo $textarea;
			}

			echo '</div><!-- END More Info Content -->';

		}

		if ( (int) $numlinksnum == $numlinksnum && $numlinksnum > 0 ) // If is integer and greater than 0
		{
			echo '<ul class="joe_utf-links">';

			for($i=0;$i<$numlinksnum;$i++) {
				$count=$i+1;
				$target = 'jT' . $count;
				$link = 'jLink' . $count;
				$name = 'jName' . $count;
				$nofollow = 'jFollow' . $count;

				if(empty($$name)) return false;

				// Determining whether or not link is to open in new window
				if($$target == 1)
				{
					$tar = 'target="_blank"';
				}
				else
				{
					$tar = '';
				}

				// Determining whether or not link is set to nofollow
				if($$nofollow == 1)
				{
					$fol = 'rel="nofollow"';
				}
				else
				{
					$fol = '';
				}

				echo '<li class="joe_utf-link"><a href="'.esc_attr($$link).'" ' . $tar . ' ' . $fol . '>' . esc_attr($$name) . '</a></li>';
			}

			echo '</ul>';
		}

		if( ! empty( $footerinfo ) ) {
			echo '<div class="joe_utf-footerlinks">';
			echo $footerinfo;
			echo '</div>';
		}

		echo '</div><!-- END More Info Wrap -->';

		echo '</div><!-- END More Info -->';

		echo $after_widget;

	}
}

function register_Joe_Ultimate_Toggle_Footer(){
	register_widget( 'Joe_Ultimate_Toggle_Footer' );
}
add_action( 'widgets_init', 'register_Joe_Ultimate_Toggle_Footer' );