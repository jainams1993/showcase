<body style="padding:0; margin:0">
	<style type="text/css"> 
        @media screen and (max-width: 630px) {
        	.email-container {
                width: 100% !important;
                margin: auto !important;
            }
        }
    </style> 
	<center style="width: 100%; background: #fff;">
		<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" class="email-container" style="margin: auto; max-width: 630px; width: 100%;">
            <tr>
				<td>
					<table cellpadding="0" cellspacing="0" style="margin: 0; padding: 0;border:1px solid #ccc; border-bottom: 0;" width="100%">
					    	<td bgcolor="#fff" style="width:1%;font-size:1px"><div><br></div></td>
						    <td align="left" valign="top" style="padding-bottom:20px; padding-top:20px; color:#222;">
					        	<span style=" color:#23b6f0;"><b><?= __('3/17 Verrinder Road,') ?></b></span>
					        	<br />
					        	<br />
					        	<span style="font-size: 16px;"><?= __('Berrimah NT 0828') ?></span>
					        </td>
					        <td align="right" valign="top" style="padding-bottom:20px; padding-top:20px; color:#23b6f0;">
					        	<a href="<?= site_url(); ?>"><img width="200px" src="<?= plugin_dir_url('') ?>online-integrapay-payment/assets/img/logo.png" /></a>
					        </td>
					        <td bgcolor="#fff" style="width:1%;font-size:1px"><div><br></div></td>
				        </tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" style="margin: 0; padding: 0; border:1px solid #ccc; border-top: 0; border-bottom: 0;" width="100%">
						<tr>
				        	<td bgcolor="#fff" style="width:1%;font-size:1px;"><div><br></div></td>
				        	<td width="98%" style="border: 1px solid #23b6f0"></td>
				        	<td bgcolor="#fff" style="width:1%;font-size:1px;"><div><br></div></td>
				        </tr>
					</table>
				</td>	
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" style="width:100%; border:1px solid #ccc; border-top: 0; border-bottom: 0;">
						<tr>
							<td bgcolor="#fff" style="width:1%;font-size:1px"><div><br></div></td>
							<td align="left" valign="top" width="59%" style="padding:20px 15px 20px 0;">
								<span style="color:#23b6f0;"><?= __('Bill To:') ?></span>
								<br />
								<span style="font-size: 16px;"><?= $data['payerFirstName'] ?></span>
								<br />
								<span style="font-size: 16px;"><?= $data['payerAddressStreet'] ?></span>
								<br />
								<span style="font-size: 16px;"><?= __('Email Address') ?> : <?= $data['payerEmail'] ?> </span>
								<br />
								<span style="font-size: 16px;"><?= __('Phone No') ?> : <?= $data['phone'] ?></span>							
							</td>
							<td align="left" valign="top" width="39%" style="padding:20px 0 20px 15px;">
								<span style="color:#23b6f0;"><?= __('Invoice No') ?>:</span>
								<span style="font-size: 16px;"><?= $data['invoice']; ?></span>
								<br />
								<span style="color:#23b6f0;"><?= __('Invoice Date:') ?></span>
								<span style="font-size: 16px;"><?= $data['transactionDateTime'] ?></span>
								<br />
							</td>
							<td bgcolor="#fff" style="width:1%;font-size:1px"><div><br></div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" style="width:100%; border:1px solid #ccc; border-top: 0; border-bottom: 0; padding: 20px 7px;">
						<tr>
							<td>
								<table border="1" cellpadding="10" cellspacing="0" style="width:100%; overflow-x: auto;">
									<tr bgcolor="#23b6f0" style="color: #fff; text-align: center;">
										<td width="10%" style="padding: 10px;"><?= __('Qty') ?></td>
										<td width="50%" style="padding: 10px;"><?= __('Description') ?></td>
										<td width="20%" style="padding: 10px;"><?= __('Unit Price') ?></td>
										<td width="20%" style="padding: 10px;"><?= __('Amount') ?></td>
									</tr>
									<tr>
										<td width="10%" align="center" style="font-size: 16px;">1</td>
										<td width="50%" style="font-size: 16px;"><?= __('Online Payment on NC Electrical') ?></td>
										<td width="20%" align="right" style="font-size: 16px;">$ <?= $data['transactionAmount'] ?></td>
										<td width="20%" align="right" style="font-size: 16px;">$ <?= $data['transactionAmount'] ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>			
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" style="width:100%; padding-top: 50px; border:1px solid #ccc; border-top: 0; border-bottom: 0; ">
						<!-- <tr align="right">
							<td align="right" width="80%" style="padding-bottom: 15px;"></td>
							<td align="right" width="20%" style="padding-bottom: 15px; font-size: 16px;">$ 1,250.00</td>
						</tr> -->
						<tr align="right">
							<td bgcolor="#fff" style="width:1%;font-size:1px"><div><br></div></td>
							<td align="right" width="79%" style="padding-bottom: 15px;"><b><?= __('Total') ?></b></td>
							<td align="right" width="19%" style="padding-bottom: 15px; font-size: 16px;"><b>$ <?= $data['transactionAmount'] ?></b></td>
							<td bgcolor="#fff" style="width:1%;font-size:1px"><div><br></div></td>
						</tr>
					</table>
				</td>
			</tr>	
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" style="margin: 0; padding: 0; border:1px solid #ccc; border-top: 0;" width="100%">
						<tr>
							<td bgcolor="#fff" style="width:1%;font-size:1px;"><div><br></div></td>
				        	<td align="left" valign="top" width="59%" style="padding:20px 0; font-size: 16px;">
				        		This email is sent from 
				        	</td>
				        	<td bgcolor="#fff" style="width:1%;font-size:1px;"><div><br></div></td>
				        </tr>
					</table>
				</td>	
			</tr>
		</table>	
	</center>
</body>