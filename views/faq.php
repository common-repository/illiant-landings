<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wrap illiant-documentation wp-landings">
	<div class="headline-wrapper">
		<h1><img src="<?php echo plugins_url('/assets/images/wplandings-logo.png', dirname(__FILE__)); ?>" alt="WPLandings" width="140"> | FAQ</h1>
	</div>
	<div class="lp-box lp-docs">

		<div class="lp-docs-nav">
			<ul>
				<li>
					<a href="#d-4">Common error messages</a>
				</li>
			</ul>
		</div>
		<div class="lp-docs-content">
			
			<h1 id="d-4">Common error messages</h1>
			<div class="lp-acc">
				<div class="lp-acc-item">
					<div class="lp-acc-title">Error fetching data from Figma: Not found</div>
					<div class="lp-acc-content">
						<p>The Figma design URL might be incorrect, or the Figma account associated with the Access Token does not have permission to access the design. Verify the URL and permissions.</p>
					</div>
				</div>
				<div class="lp-acc-item">
					<div class="lp-acc-title">Error fetching data from Figma: Invalid token</div>
					<div class="lp-acc-content">
						<p>The Figma Access Token is invalid or has expired. Review your token in Figma settings and generate a new one if necessary.</p>
					</div>
				</div>
				<div class="lp-acc-item">
					<div class="lp-acc-title">Error uploading images to WordPress. Error message: Error sideloading image: The uploaded file could not be moved to…</div>
					<div class="lp-acc-content">
						<p>WordPress is unable to move the uploaded file due to insufficient permissions in the upload folder. Ensure the upload directory and its subdirectories have the correct write permissions. For changing permissions, access your server via FTP or your hosting control panel, navigate to the WordPress upload folder (typically wp-content/uploads), and adjust the permissions to ensure WordPress can write to it (755 for directories and 644 for files is a commonly recommended setup).</p>
					</div>
				</div>
				
			</div>
		</div>
		<div class="clear"></div>

	</div>
</div>
