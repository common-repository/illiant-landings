<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$plan = illiantlandings_fs()->get_plan();

$planName = "free"; 

// Check if plan exists and assign plan name
if ($plan !== null && is_object($plan) && isset($plan->name)) {
    $planName = $plan->name;
}

?>

<div class="wrap illiant-settings wp-landings">

    <div class="headline-wrapper">

		<h1><img src="<?php echo plugins_url('/assets/images/wplandings-logo.png', dirname(__FILE__)); ?>" alt="WPLandings" width="140"></h1>
        
    </div> 
	<?php settings_errors(); ?>


    <?php include 'components/alert.php'; ?>

	<div class="lp-box blue lp-settings-form">
		<form id="lp-settings" class="horizontal-table" method="post" action="options.php">
			<?php 
				settings_fields( 'illiant_landings_settings' );
				do_settings_sections( 'illiant_wplandings' );
			?>
		</form>
		<div class="mt-4">
			<button id="save-lp-settings" class="lp-btn orange large">Save</button>
		</div>
        <?php include 'components/loaderModule.php'; ?>

	</div>
    <div class="lp-box lp-docs">
        <div class="row">
            <div class="col-md-12">
                <h2>Obtaining a Figma Access Token</h2>
                <p>A Figma Access Token acts as a secure "password," enabling software to access your Figma designs and data.<br><br>

To get your token:</p>
                <p>1. Open the Figma desktop app or website and access your account settings. Look for the <strong>Personal Access Tokens</strong> section.</p>
                <img src="<?php echo plugins_url('/assets/images/figma-settings.png', dirname(__FILE__)); ?>" width="200">
                <p>2. Select <strong>Create New Token</strong>, name it, and copy the token. This enables WPLandings to import your Figma designs.</p>
                <img src="<?php echo plugins_url('/assets/images/figma-token.png', dirname(__FILE__)); ?>" width="400">
                <p>3. Ensure the Figma account holding the Access Token has viewing or editing permissions for the file, especially if it's private.</p>
                <h2>Need Help?</h2>
                <p>Whether you're setting up your OpenAI API key or Figma Access Token, we're here to support you. For assistance, simply fill out the<a href="https://illiant.ai/contact-us/" target="_blank"> support form</a>.</p>
                <h2>FAQ</h2>
                <p>Have queries about OpenAI and Figma integration or how to maximize your use of WPLandings? Our <a href="admin.php?page=illiant_wplandings_faq">FAQ section</a> is ready to provide the insights you need.</p>
            </div>
        </div>
        
    </div>
</div>