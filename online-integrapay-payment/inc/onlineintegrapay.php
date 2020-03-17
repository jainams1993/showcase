<?php 
// Template File is use for displaying the payment form
get_header();
?>
<section id="blog-post" class="mainblock">
    <div class="container">
         <?php
        // TO SHOW THE PAGE CONTENTS
        while ( have_posts() ) : the_post(); ?> 
            <div class="entry-content-page">
                <?php the_content(); ?> <!-- Page Content -->
            </div>

        <?php
        endwhile; 
        wp_reset_query();
         //resetting the page query
        ?>
        <form method="post" name="onlineintergrapay_form" id="onlineintergrapay_form">
            
            <div class="cu-row">
                <label for="name" class="cu-col-4 col-form-label"><?= __('Payment method*','onlineintergrapay') ?></label>
                <div class="cu-col-8">
                    <select id="payment_mode" name="payment_method">
                        <option value="0">Choose Payment Method</option>
                        <option value="1">Credit/Debit Card</option>
                        <option value="2">Bank Payment</option>
                    </select>
                    <span class="error" id="payment_method"></span>
                </div>    
            </div>
            <?php /*
            <input type="hidden" class="" name="payment_method" id="payment_method" value="1">
            */ ?>
            <div class="cu-row">
                <label for="name" class="cu-col-4 col-form-label"><?= __('Name*','onlineintergrapay') ?></label>
                <div class="cu-col-8">
                    <input type="text" class="" name="name" id="" placeholder="<?= __('Name','onlineintergrapay') ?>">
                    <span class="error" id="name"></span>
                </div>    
            </div>
            <div class="cu-row">
                <label for="email" class="cu-col-4"><?= __('Email*','onlineintergrapay') ?></label>
                <div class="cu-col-8">
                    <input type="email" class="" name="email" id="" placeholder="<?= __('Email','onlineintergrapay') ?>">
                    <span class="error" id="email"></span>
                </div>
            </div>
            <div class="cu-row">
                <label for="address" class="cu-col-4"><?= __('Address*','onlineintergrapay') ?></label>
                <div class="cu-col-8">
                    <input type="text" class="" name="address" id="" placeholder="<?= __('1234 Main St','onlineintergrapay') ?>">
                    <span class="error" id="address"></span>
                </div>    
            </div>
            <div class="cu-row">
                <label for="phone" class="cu-col-4"><?= __('Phone*','onlineintergrapay') ?></label>
                <div class="cu-col-8">
                    <input type="text" class="" name="phone" id="" placeholder="<?= __('Phone','onlineintergrapay') ?>">
                    <span class="error" id="phone"></span>
                </div>
            </div>
            <div class="cu-row">
                <label for="invoice" class="cu-col-4"><?= __('Invoice*','onlineintergrapay') ?></label>
                <div class="cu-col-8">
                    <input type="text" class="" name="invoice" id="" placeholder="<?= __('Invoice','onlineintergrapay') ?>">
                    <span class="error" id="invoice"></span>
                </div>
            </div>            
            <div class="cu-row">
                <label for="amount" class="cu-col-4"><?= __('Amount','onlineintergrapay') ?><span>($)*</span></label>
                <div class="cu-col-8">
                    <input type="text" class="" name="amount" id="" placeholder="<?= __('Amount','onlineintergrapay') ?>">
                    <span class="error" id="amount"></span>
                </div>    
            </div>
            <div class="cu-row">
                <div class="cu-col-12">
                    <span class="error" id="ERROR"></span>
                </div>    
            </div>
            <div class="cu-row">
                <button id="onlineintergrapay_form_new" class="btn btn-primary"><?= __('Pay With Ineintergrapay','onlineintergrapay') ?>
                </button>
            </div>    
        </form>
    </div>
    <div class="loader">
    </div>
</section>
<?php
get_footer();
?>