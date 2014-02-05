<?php
/**
 * @var $form_name
 * @var $attributes
 */
?>
<div class="mm-wp-lib-attribute-control"
	 data-form-name="<?php echo $form_name?>">

	<ul class="attribute-list">
		<li class="attribute template">
			<table style="width:100%"><tr>
					<td style="width:45%">
						<label>
							<span>Name</span>
							<input
								type="text"
								class="attribute_name widefat"
								placeholder="<?php _e('Attribute Name')?>"
								>
						</label>
					</td>
					<td style="width:45%">
						<label>
							<span>Value</span>
							<input
								type="text"
								class="attribute_value widefat"
								placeholder="<?php _e('Attribute Value')?>"
								>
						</label>
					</td>
					<td style="width:10%;text-align: right;">
						<a href="#" class="remove"><i class="fa fa-minus-circle"></i><span>Remove</span>
					</td>
				</tr></table>

		</li>
		<?php
		foreach($attributes as $n => $attr){
			?>
			<li class="attribute">
				<table style="width:100%"><tr>
						<td style="width:45%">
							<label>
								<span><?php _e('Name')?></span>
								<input
									type="text"
									class="attribute_name widefat"
									placeholder="<?php _e('Attribute Name')?>"
									name="<?php echo $form_name?>[<?php echo $n?>][attribute_name]"
									value="<?php echo esc_attr($attr['attribute_name'])?>"
									>
							</label>
						</td>
						<td style="width:45%">
							<label>
								<span>Value</span>
								<input
									type="text"
									class="attribute_value widefat"
									placeholder="<?php _e('Attribute Value')?>"
									name="<?php echo $form_name?>[<?php echo $n?>][attribute_value]"
									value="<?php echo esc_attr($attr['attribute_value'])?>"

									>
							</label>
						</td>
						<td style="width:10%;text-align: right;">
							<a href="#" class="remove"><i class="fa fa-minus-circle"></i><span>Remove</span></a>
						</td>
					</tr></table>
			</li>
		<?php
		}
		?>
	</ul>
	<p style="text-align: right"><a href="#" class="add button"><?php _e('Add New')?></a></p>

</div>
 