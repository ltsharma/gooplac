<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ltsharma.com
 * @since      1.0.0
 *
 * @package    Gooplac
 * @subpackage Gooplac/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="wrap">
	<h1>Google Place Autocomplete - GOOPLAC</h1>
	<hr>
	<form method="post" action="options.php">
		<?php
			settings_fields( $this->plugin_name . '-settings' );
			do_settings_sections( $this->plugin_name . '-settings' ); 
			?>
		<p class="legend">By default you can use #gooplac-input to any input</p>
		<?php	submit_button();
		?>
	</form>
</div>