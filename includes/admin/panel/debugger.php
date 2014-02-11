<?php
use ModernMedia\WPLib\Debugger;
use ModernMedia\WPLib\Admin\DebuggerPanel;
/**
 * @var DebuggerPanel $this
 */
$data = Debugger::inst()->get_data();

?>
<div class="mm-wp-lib-debugger">
	<h3><?php printf(__('Stored Requests (%s)'), count($data))?></h3>

	<?php
	foreach($data as $r => $request){
		?>


		<table class="widefat">
			<thead>
			<tr>
				<th colspan="2">
					<strong><?php printf(__('Request #%s'),$r + 1)?></strong>

						<small><?php _e('Started:')?> <span class="timestamp"><?php echo $request->request_started?></span>
						|
						<?php _e('Ended:')?> <span class="timestamp"><?php echo $request->request_ended?></span></small>
					
				</th>
			</tr>
			<tr>
				<th><?php _e('Label/Time')?></th>
				<th><?php _e('Data')?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach($request->data as $datum){
				?>
				<tr>
					<td style="vertical-align: top;width:25%;">
						<p style="font-weight: bold"><?php echo $datum->label?></p>
						<p><span class="timestamp"><?php echo $datum->timestamp?></span></p>
					</td>
					<td  style="vertical-align: top;width:75%;">
					<pre><?php
						ob_start();
						var_dump(unserialize($datum->data));
						echo htmlentities(ob_get_clean());
						?></pre>
					</td>
				</tr>

			<?php
			}
			?>
			</tbody>
		</table>
	<?php
	}
	?>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		var ctl_selector_str = '.mm-wp-lib-debugger';
		$(ctl_selector_str + ' .timestamp').each(function(){
			var d = new Date(parseInt($(this).text()) * 1000);
			$(this).after('<span class="fmt-date">' + d.toLocaleString() + '</span>');
			$(this).hide();
		});
	});
</script>
