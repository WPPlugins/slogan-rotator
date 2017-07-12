<?php
/*
 * Plugin Name: Slogan Rotator
 * Plugin URI: https://wordpress.org/plugins/slogan-rotator/
 * Description: Show a different slogan every time the visitor refreshes the page.
 * Version: 1.0.1
 * Author: Mitch
 * Author URI: https://profiles.wordpress.org/lowest
 * License: GPL-2.0+
 * Text Domain: sr
 * Domain Path:
 * Network:
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! defined( 'SR_FILE' ) ) { define( 'SR_FILE', __FILE__ ); }

if ( ! defined( 'SR_VERSION' ) ) { define( 'SR_VERSION', '1.0.1' ); }

$sloganrotator_options = get_option('sloganrotator_settings');

function sloganrotator_settings_menu() {
	add_submenu_page('options-general.php', __('Slogan Rotator'), __('Slogan Rotator'),'manage_options', 'slogan-rotator', 'sloganrotator');
}
add_action( 'admin_menu', 'sloganrotator_settings_menu' );

function sloganrotator() {
	global $sloganrotator_options, $array;
	?>
	<div class="wrap">
		<h1>Slogan Rotator</h1>
		<p><?php printf( __('Use the %s shortcode to display the slogans.', 'sr'), '<code>[slogan-rotator]</code>' ); ?></p>
		<form method="post" action="options.php">
		<?php settings_fields( 'sloganrotator_settings_group' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th><?php printf( __('Example output %s', 'sr'), '<a href="' . $_SERVER['REQUEST_URI'] . '">[Reload]</a>' ); ?></th>
						<td><?php
						if(!empty(do_shortcode('[slogan-rotator]'))) {
							echo do_shortcode('[slogan-rotator]');
						} else {
							_e( 'You have not saved any slogans yet.' );
						}
						?></td>
					</tr>
					<tr>
						<th><label for="sloganrotator_settings[slogans]"><?php _e('Slogans'); ?></label></th>
						<td><textarea id="sloganrotator_settings[slogans]" name="sloganrotator_settings[slogans]" class="sloganrotator_textarea"><?php if(isset($sloganrotator_options['slogans'])) { echo $sloganrotator_options['slogans']; } ?></textarea>
						<p class="description"><?php _e('Separate each slogan by a new line. HTML is allowed.'); ?></p></td>
					</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

function sloganrotator_register_settings() {
	register_setting( 'sloganrotator_settings_group', 'sloganrotator_settings' );
}
add_action( 'admin_init', 'sloganrotator_register_settings' );

function sloganrotator_scripts() {
	if(isset($_GET['page']) && $_GET['page'] == 'slogan-rotator') {
		wp_register_script( 'sloganrotator-jquery', plugins_url( 'assets/jquery.js', SR_FILE ), false, '1.0.0' );
		wp_register_script( 'sloganrotator-autosize', plugins_url( 'assets/autosize.jquery.js', SR_FILE ), false, '1.0.0' );
		wp_register_style( 'sloganrotator-css', plugins_url( 'assets/sr.css', SR_FILE ), false, '1.0.0' );
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'sloganrotator-autosize' );
		wp_enqueue_script( 'sloganrotator-jquery' );
		wp_enqueue_style( 'sloganrotator-css' );
	}
}
add_action( 'admin_enqueue_scripts', 'sloganrotator_scripts' );

function sloganrotator_sc() {
	global $sloganrotator_options;
	
	$slogans = $sloganrotator_options['slogans'];
	$array = explode("\n", $slogans);
	
	if (count($array) > 1) {
		$randomize = array_rand($array);
		$result = $array[$randomize];
		
		return $result;
	} else {
		return $slogans;
	}
}
add_shortcode( 'slogan-rotator', 'sloganrotator_sc' );

function sloganrator_paypal( $link ) {
	return array_merge( $link, array('<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2VYPRGME8QELC" target="_blank" rel="noopener noreferrer">Donate</a>') );
}
add_filter( 'plugin_action_links_' . plugin_basename( SR_FILE ), 'sloganrator_paypal');