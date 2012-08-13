<?php
	if($_POST['sjs_hidden'] == 'Y') {
		//Form data sent
		$start = $_POST['sjs_start'];
		update_option('sjs_start', $start);

		$speed = $_POST['sjs_speed'];
		update_option('sjs_speed', $speed);

		$delay = $_POST['sjs_delay'];
		update_option('sjs_delay', $delay);

		$controls = $_POST['sjs_controls'];
		update_option('sjs_controls', $controls);

		$pagination = $_POST['sjs_pagination'];
		update_option('sjs_pagination', $pagination);
?>
<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php
	} else {
		//Normal page display
		$start = get_option('sjs_start');
		$speed = get_option('sjs_speed');
		$delay = get_option('sjs_delay');
		$controls = get_option('sjs_controls');
		$pagination = get_option('sjs_pagination');
	}
?>

<div class="wrap">
	<?php echo "<h2>" . __( 'SwipeJS Options', 'sjs_trdom' ) . "</h2>"; ?>

	<form name="sjs_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="sjs_hidden" value="Y">

		<?php echo "<h4>" . __( 'SwipeJS Settings', 'sjs_trdom' ) . "</h4>"; ?>

		<!-- <p><?php _e("Starting Slide: " ); ?>&nbsp;
			<input type="text" name="sjs_start" value="<?php echo $start; ?>" size="20"><?php _e(" ex: 0 = first. 1 = second etc." ); ?></p> -->

		<p><?php _e("Transition Speed: " ); ?>&nbsp;
			<input type="text" name="sjs_speed" value="<?php echo $speed; ?>" required size="20"><?php _e("ms" ); ?></p>

		<p><?php _e("Auto Transition Delay: " ); ?>&nbsp;
			<input type="text" name="sjs_delay" value="<?php echo $delay; ?>" required size="20"><?php _e("ms. Use 0 for off." ); ?></p>

		<p><?php _e("Controls: " ); ?>&nbsp;
			<input type="checkbox" name="sjs_controls" <?php checked( get_option('sjs_controls') == 'on',true); ?> size="20"></p>

		<p><?php _e("Pagination: " ); ?>&nbsp;
			<input type="checkbox" name="sjs_pagination" <?php checked( get_option('sjs_pagination') == 'on',true); ?> size="20"></p>

		<p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'sjs_trdom' ) ?>" /></p>
	</form>
</div>