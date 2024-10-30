<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wrap illiant-documentation wp-landings">



	<div class="headline-wrapper">
		<h1><img src="<?php echo plugins_url('/assets/images/wplandings-logo.png', dirname(__FILE__)); ?>" alt="WPLandings" width="140"> | Documentation</h1>
	</div>
	<div class="lp-box lp-docs">
		<div class="lp-docs-nav">
			<ul>
				<li>
					<a href="#d-1">Getting Started</a>
					<ul>
						<li><a href="#d-1-1">Obtaining a Figma Access Token</a></li>
					</ul>
				</li>
				<li>
					<a href="#d-2">How to Prepare a Figma design</a>
					<ul>
						<li><a href="#d-2-2">Design Preparation Guide</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="lp-docs-content">
			<h1 id="d-1">Getting started</h1>
			
			<h2 id="d-1-1">Obtaining a Figma Access Token</h2>
			<p>A Figma Access Token acts as a secure "password," enabling software to access your Figma designs and data.<br><br>

To get your token:</p>
			<p>1. Open the Figma desktop app or website and access your account settings. Look for the <strong>Personal Access Tokens</strong> section.</p>
			<img src="<?php echo plugins_url('/assets/images/figma-settings.png', dirname(__FILE__)); ?>" width="200">
			<p>2. Select <strong>Create New Token</strong>, name it, and copy the token. This enables WPLandings to import your Figma designs.</p>
			<img src="<?php echo plugins_url('/assets/images/figma-token.png', dirname(__FILE__)); ?>" width="400">
			<p>3. Ensure the Figma account holding the Access Token has viewing or editing permissions for the file you want to convert, especially if it's private.</p>
			
			<hr></hr>


			<h1 id="d-2">How to prepare a Figma design</h1>
			<h2 id="d-2-2">Design Preparation Guide</h2>
			<p>1. <strong>Configure your design with AUTO layout (Opional)</strong>: For the best results, we recommend configuring all sections, containers and columns of your design with AUTO layout in Figma.</p>
			<h3 class="mb-3">Learn Figma Auto layout in 10 minutes</h3>
			<iframe width="560" height="315"  src="https://www.youtube.com/embed/To_ADCVSg5g?si=AYVCPafxCO27bpcg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
			<img src="<?php echo plugins_url('/assets/images/instructions-1.png', dirname(__FILE__)); ?>" width="100%">

			
			<p>2. <strong>Rename group layers you want to treat as a single image</strong>: For compositions of elements you wish to import as a single image, group them and label the group "isImage." This helps WPLandings to import them accurately.<br><br>In the example we have a cellphone composed of multiple layers that we want to use a single image. To achieve this, group these elements together and label the group as "isImage" to ensure the entire composition is imported as a single image.</p>

			<img src="<?php echo plugins_url('/assets/images/instructions-2.png', dirname(__FILE__)); ?>" width="100%">
		
			<p>3. <strong>Exclude from your design header and footer nav menus</strong>: Hide or delete headers or footers navs from your Figma design. While WPLandings does not import functional nav menus, you can integrate existing ones from your WordPress theme.</p>

			<img src="<?php echo plugins_url('/assets/images/instructions-3.png', dirname(__FILE__)); ?>" width="100%">

			<p>4. <strong>Install custom fonts in your theme</strong>: Please ensure that all fonts used in your design are installed in your theme.</p>

			<p>5. <strong>Interactive elements</strong>: WPLandings does not create interactive elements like forms, carousels, tabs, or accordions. For these features, replace the generated code with shortcodes or blocks from specialized third-party plugins.</p>

			<h2>Need Help?</h2>
			<p>If you encounter any issues during the conversion process or have questions about preparing your design, we're here to support you. For assistance, simply fill out the <a href="https://illiant.ai/contact-us/" target="_blank">support form</a>.</p>

		</div>
		<div class="clear"></div>

	</div>
</div>