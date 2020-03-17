<section id="blog-post" class="mainblock">
    <div class="container">
		<form method="post" name="bankintergrapay_form" id="bankintergrapay_form">
		    <div class="cu-row">
		        <label for="payerFirstName" class="cu-col-4 col-form-label"><?= __('First Name*','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerFirstName" id="payerFirstName" placeholder="<?= __('First Name','bankintergrapay') ?>">
		            <span class="error" id="payerFirstName"></span>
		        </div>    
		    </div>
		    <div class="cu-row">
		        <label for="payerLastName" class="cu-col-4 col-form-label"><?= __('Last Name*','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerLastName" id="payerLastName" placeholder="<?= __('Last Name','bankintergrapay') ?>">
		            <span class="error" id="payerLastName"></span>
		        </div>    
		    </div>
		    <div class="cu-row">
		        <label for="payerAddressStreet" class="cu-col-4"><?= __('Address','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerAddressStreet" id="payerAddressStreet" placeholder="<?= __('Address','bankintergrapay') ?>">
		            <span class="error" id="payerAddressStreet"></span>
		        </div>    
		    </div>
		    <div class="cu-row">
		        <label for="payerAddressSuburb" class="cu-col-4"><?= __('Suburb','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerAddressSuburb" id="payerAddressSuburb" placeholder="<?= __('Suburb','bankintergrapay') ?>">
		            <span class="error" id="payerAddressSuburb"></span>
		        </div>    
		    </div>
		    <div class="cu-row">
		        <label for="payerAddressState" class="cu-col-4"><?= __('State','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerAddressState" id="payerAddressState" placeholder="<?= __('State','bankintergrapay') ?>">
		            <span class="error" id="payerAddressState"></span>
		        </div>    
		    </div>
		    <div class="cu-row">
		        <label for="payerAddressPostCode" class="cu-col-4"><?= __('Post Code','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerAddressPostCode" id="payerAddressPostCode" placeholder="<?= __('Post Code','bankintergrapay') ?>">
		            <span class="error" id="payerAddressPostCode"></span>
		        </div>    
		    </div>
		    <div class="cu-row">
		        <label for="payerAddressCountry" class="cu-col-4"><?= __('Country','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerAddressCountry" id="payerAddressCountry" placeholder="<?= __('Country','bankintergrapay') ?>">
		            <span class="error" id="payerAddressCountry"></span>
		        </div>    
		    </div>
		    <div class="cu-row">
		        <label for="payerEmail" class="cu-col-4"><?= __('Email*','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="email" class="" name="payerEmail" id="payerEmail" placeholder="<?= __('Email','bankintergrapay') ?>">
		            <span class="error" id="payerEmail"></span>
		        </div>
		    </div>
		    <div class="cu-row">
		        <label for="payerPhone" class="cu-col-4"><?= __('Phone','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerPhone" id="payerPhone" placeholder="<?= __('Phone','bankintergrapay') ?>">
		            <span class="error" id="payerPhone"></span>
		        </div>
		    </div>
		    <div class="cu-row">
		        <label for="payerMobile" class="cu-col-4"><?= __('Mobile*','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="payerMobile" id="payerMobile" placeholder="<?= __('Mobile','bankintergrapay') ?>">
		            <span class="error" id="payerMobile"></span>
		        </div>
		    </div>
		    <div class="cu-row">
		        <label for="invoice" class="cu-col-4"><?= __('Invoice*','bankintergrapay') ?></label>
		        <div class="cu-col-8">
		            <input type="text" class="" name="invoice" id="" placeholder="<?= __('Invoice','bankintergrapay') ?>">
		            <span class="error" id="invoice"></span>
		        </div>
		    </div>            
		    <div class="cu-row">
		        <div class="cu-col-12">
		            <span class="error" id="ERROR"></span>
		        </div>    
		    </div>
		    <div class="cu-row">

    			<div class="save_btn_container">
		        	<button id="bankintergrapay_form_new" type="submit" class="btn btn-primary"><?= __('Pay With Ineintergrapay','bankintergrapay') ?></button>
				</div>
				<span id="saved"></span>
		    </div>    
		</form>	
	</div>
</section>