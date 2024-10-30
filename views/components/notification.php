<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="lp-not">
    <div class="lp-icon-success">
        <img src="<?php echo esc_url(plugins_url('../assets/images/icon-success.png', dirname(__FILE__))); ?>" alt="success">
    </div>
    <div class="lp-icon-error">
        <img src="<?php echo esc_url(plugins_url('../assets/images/icon-error.png', dirname(__FILE__))); ?>" alt="error">
    </div>
    <div class="lp-loader">
        <div class="lp-not-spinner">
        </div>
        <div class="lp-counter">
            <span class="curr"></span>/<span class="tot"></span>
        </div>
    </div>
    <div class="lp-not-message">
        
    </div>
</div>