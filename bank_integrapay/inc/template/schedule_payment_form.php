<section id="blog-post" class="mainblock">
    <div class="container">
		<form method="post" name="schedule_payment" id="schedule_payment">
		    <div class="cu-row">
		        <label for="payerEmail" class="cu-col-4"><?= __('Email*','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="email" class="" name="payerEmail" id="payerEmail" placeholder="<?= __('Email','bankintergrapay') ?>">
		            <span class="error" id="payerEmail"></span>
		        </div>
		    </div>
		    <div class="cu-row">
		        <label for="debitDate" class="cu-col-4"><?= __('Date of Debit*','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="date" class="" name="debitDate" id="debitDate" placeholder="<?= __('Date of Debit','bankintergrapay') ?>">
		            <span class="error" id="debitDate"></span>
		        </div>
		    </div>
		    <div class="cu-row">
		        <label for="amount" class="cu-col-4"><?= __('Amount*','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="amount" id="" placeholder="<?= __('Amount','bankintergrapay') ?>">
		            <span class="error" id="amount"></span>
		        </div>
		    </div>            
		    <div class="cu-row">
		        <div class="cu-col-12">
		            <span class="error" id="ERROR"></span>
		        </div>    
		    </div>
		    <div class="cu-row">
    			<div class="save_btn_container">
		        	<button id="schedule_payment_button" type="submit" class="btn btn-primary"><?= __('Schedule Payment','bankintergrapay') ?></button>
				</div>
				<span id="saved"></span>
		    </div>    
		</form>	
	</div>
</section>