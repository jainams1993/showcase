<?php 
 function settingIntegrapay(){ 
 	if(isset($_POST['integrapay']))
 	{
 	 	update_option( 'integrapay_payment_username',$_POST['integrapay_payment_username'], '', '' ); 
 	 	update_option( 'integrapay_payment_password',$_POST['integrapay_payment_password'], '', 'yes' ); 
 	 	update_option( 'integrapay_payment_api',$_POST['integrapay_payment_api'], '', 'yes' ); 
 	 	update_option( 'integrapay_payment_api_method',$_POST['integrapay_payment_api_method'], '' ); 
 	 	update_option('integrapay_integrapay_payment_response',$_POST['integrapay_integrapay_payment_response'],'');
 	 	update_option('eddr_url',$_POST['eddr_url'],'');
 	 	update_option('bank_template_id',$_POST['bank_template_id'],'');
 	 	update_option('schedule_payment_url',$_POST['schedule_payment_url'],'');
 	}
 	?>
 	<form method="post" action="">
	 	<table class="form-table">
			 <tbody>
			 	<tr>
					<th scope="row" valign="top"><?= __('User Name' ,'integrapay_payment') ?></th>
					<td><input type="text" name="integrapay_payment_username" value="<?= get_option('integrapay_payment_username' ,''); ?>" style="width: 50%"></td>
				</tr>
				<tr>
					<th scope="row" valign="top"><?= __('User Password' ,'integrapay_payment') ?></th>
					<td><input type="text" name="integrapay_payment_password" value="<?= get_option('integrapay_payment_password','') ?>" style="width: 50%"></td>
				</tr>
					
				<tr>
					<th scope="row" valign="top"><?= __('Integrapay payment Api' ,'integrapay_payment') ?></th>
					</th>
					<td>
					<input type="text" name="integrapay_payment_api" value="<?= get_option('integrapay_payment_api' ,''); ?>" style="width: 50%"></td>
				</tr>
				<tr>
					<th scope="row" valign="top"><?= __('Integrapay payment Response Url' ,'integrapay_payment') ?></th>
					</th>
					<td>
					<input type="text" name="integrapay_integrapay_payment_response" value="<?= get_option('integrapay_integrapay_payment_response' ,''); ?>" style="width: 50%"></td>
				</tr>
				
			</tbody>
		</table>

		<table class="form-table">
			 <tbody>
			 	<tr>
					<th scope="row" valign="top"><?= __('eDDR URL' ,'integrapay_payment') ?></th>
					<td><input type="text" name="eddr_url" value="<?= get_option('eddr_url' ,''); ?>" style="width: 50%"></td>
				</tr>
				<tr>
					<th scope="row" valign="top"><?= __('Template Id' ,'integrapay_payment') ?></th>
					<td><input type="text" name="bank_template_id" value="<?= get_option('bank_template_id' ,''); ?>" style="width: 50%"></td>
				</tr>
				<tr>
					<th scope="row" valign="top"><?= __('Schedule Payment Url' ,'integrapay_payment') ?></th>
					<td><input type="text" name="schedule_payment_url" value="<?= get_option('schedule_payment_url' ,''); ?>" style="width: 50%"></td>
				</tr>
			</tbody>
		</table>

		<table>
			<tbody>
				<tr>
					<th>
						<button type="submit" name="integrapay"><?= __('Save' ,'integrapay_payment') ?></button>
					</th>
				</tr>
			</tbody>
		</table>
	</form>
 <?php 
 return;
	}
 ?>