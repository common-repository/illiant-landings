<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$plan = illiantlandings_fs()->get_plan();
$license = illiantlandings_fs()->_get_license();

// Initialize variables
$licenseId = null;
$created = null;
$updated = null;
$expiration = null;
$planName = "free"; 

// Check if plan exists and assign plan name
if ($plan !== null && is_object($plan) && isset($plan->name)) {
    $planName = $plan->name;
}

// Only proceed if $license is an object
if (is_object($license)) {
    if (isset($license->id)) {
        $licenseId = $license->id;
    }
    if (isset($license->created)) {
        $created = $license->created;
    }
    if (isset($license->expiration)) {
        $expiration = $license->expiration;
    }
}

$illiant_design_data = get_option('illiant_design_data');

$selected_node_name = '';

$selectedNode;

$active_global_styles_id = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();


// Check if the design data and selectedNode exist
if ($illiant_design_data && isset($illiant_design_data['selectedNode'])) {
    $selectedNode = isset($illiant_design_data['selectedNode']) ? str_replace('-', ':', $illiant_design_data['selectedNode']) : '';

    // Check for pages and frames to find the matching node ID
    if (isset($illiant_design_data['pages'])) {
        foreach ($illiant_design_data['pages'] as $page) {
            // If the selected node matches a page ID, use the name of the first frame within the page
            if ($page['id'] === $selectedNode && isset($page['frames'][0])) {
                $selected_node_name = $page['frames'][0]['name'];
                $selectedNode = $page['frames'][0]['id']; 
                
                break;
            }

            // Otherwise, check if the selected node matches a frame ID within the page
            if (isset($page['frames'])) {
                foreach ($page['frames'] as $frame) {
                    if ($frame['id'] === $selectedNode) {
                        $selected_node_name = $frame['name'];
                        break 2; // Exit both loops once found
                    }
                }
            }
        }
    }
}

?>

<div class="wrap illiant-page wp-landings">

    <?php
        $options = get_option('illiant_landings');
        $figmaId = isset($options['figmaId']) ? sanitize_text_field(wp_unslash($options['figmaId'])) : '';
        $figmaKey = isset($options['figmaV2']) ? sanitize_text_field(wp_unslash($options['figmaV2'])) : '';
    ?>
    <?php
        $optionsLp = get_option('illiant_landings_lp');
        $requests = isset($optionsLp['requests']) ? sanitize_text_field(wp_unslash($optionsLp['requests'])) : 0;
    ?>
   <div class="headline-wrapper">
        
        <span class="requests-counter">Current plan: <strong class="current-plan"><?php echo esc_attr($planName); ?></strong> 
        | Remaining credits: <strong class="cnt-requests-be"></strong> | Renews on: <strong class="cnt-renew-be"></strong></span>

       
        <h1>
            <img src="<?php echo plugins_url('/assets/images/wplandings-logo.png', dirname(__FILE__)); ?>" alt="WPLandings" width="140">| 
            <?php echo esc_html__('Convert Figma design', 'illiant-landings'); ?></h1>    
        
    </div> 
    <?php settings_errors(); ?>

    <?php if ( !illiantlandings_fs()->can_use_premium_code()) : ?>
        <div id="notice-free-trial" class="notice notice-info is-dismissible">
            <p>
                <?php echo esc_html__('Upgrade your account today and get additional credits.', 'illiant-landings'); ?>
                <a href="<?php echo esc_url( illiantlandings_fs()->get_upgrade_url() ); ?>" class="button button-primary mx-2">
                    <?php echo esc_html__('Upgrade your account!', 'illiant-landings'); ?>
                </a>
            </p>
        </div>
    <?php endif; ?>

    <?php include 'components/notification.php'; ?>

    <?php include 'components/modal.php'; ?>

    <?php include 'components/loader.php'; ?>
    
    <?php include 'components/alert.php'; ?>


    <div class="lp-box lp-import-form">
        <h2><?php echo esc_html__('Figma design URL', 'illiant-landings'); ?> <span> - If you modify your Figma design structure reconnect</span></h2>

        <div class="hidden">
            <input type="text" id="figma_access_token" name="figma_access_token" class="regular-text" value="<?php echo esc_attr($figmaKey); ?>" placeholder="figma_access_token">
        
            <input type="text" id="figma_id" name="figma_id" class="regular-text" value="<?php echo esc_attr($figmaId); ?>" placeholder="figma_id">
        
            <input type="text" id="planName" name="planName" class="regular-text" value="<?php echo esc_attr($planName);?>" placeholder="planName">
        
            <input type="text" id="licenseId" name="licenseId" class="regular-text" value="<?php echo esc_attr($licenseId);?>" placeholder="licenseId">
        
            <input type="text" id="created" name="created" class="regular-text" value="<?php echo esc_attr($created);?>" placeholder="created">
        
            <input type="text" id="expiration" name="expiration" class="regular-text" value="<?php echo esc_attr($expiration);?>" placeholder="expiration">
        
            <input type="text" id="sectionsJSON" name="sectionsJSON" class="regular-text" value="" placeholder="sectionsJSON">
        
            <input type="text" id="landing_blocks" name="landing_blocks" class="regular-text" value="" placeholder="landing_blocks">
        
            <input type="text" id="landing_bg" name="landing_bg" class="regular-text" value="" placeholder="landing_bg">
        
            <input type="text" id="global_styles_id" name="global_styles_id" class="regular-text" value="<?php echo esc_attr($active_global_styles_id);?>" placeholder="global_styles_id">
        
            <input type="text" id="update_id" name="update_id" class="regular-text" value="" placeholder="update_id">
        
            <input type="text" id="update_page_id" name="update_page_id" class="regular-text" value="" placeholder="update_page_id">
        
            <input type="text" id="update_frame_id" name="update_frame_id" class="regular-text" value="" placeholder="update_frame_id">
        </div>
        
       
        <form id="figma-desktop-form">
            <div class="row">
                <div class="col-md-9">
                    <input type="text" id="figma_desktop_url" name="figma_desktop_url" class="regular-text large-input" 
                    placeholder="<?php echo esc_html__('Paste your Figma URL here', 'illiant-landings'); ?>" 
                    value="<?php echo (isset($illiant_design_data) && isset($illiant_design_data['figmaURL'])) ? esc_url($illiant_design_data['figmaURL']) : ''; ?>">
                    <span class="figma-error"><?php echo esc_html__('Set up your key to import Figma design', 'illiant-landings'); ?></span>
                </div>
                <div class="col-md-3">
                    <a class="lp-btn block blue" id="connectFigma">Connect Figma Design</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="lp-box no-padding convert-box-1 convert-ui <?php echo ( $illiant_design_data ) ? 'active ' : ' '; ?> ">
                <?php include 'components/loaderModule.php'; ?>
                <div class="frame-tree">
                    <div class="lp-box-header">
                        <img src="<?php echo plugins_url('/assets/images/frame-icon.svg', dirname(__FILE__)); ?>" >
                        <h3>Select a frame</h3>
                    </div>
                    <div class="lp-box-body">
                        <div class="doc-stucture">
                            <?php 
                               

                                // Check if framePreviews exist in the design data
                                $framePreviews = isset($illiant_design_data['framePreviews']) ? $illiant_design_data['framePreviews'] : [];
                                if ($illiant_design_data && isset($illiant_design_data['pages'])) {
                                    echo '<ul class="figma-pages-list">';

                                    // Iterate through the pages
                                    foreach ($illiant_design_data['pages'] as $page) {
                                        $isSelectedPage = $page['id'] === $selectedNode; // Check if the selected node is a page ID
                                        $firstFrameId = isset($page['frames'][0]['id']) ? $page['frames'][0]['id'] : null;

                                        echo '<li class="figma-page-item">';
                                        echo '<span class="figma-page-name">' . esc_html( $page['name'] ) . '</span>';

                                        // Check if there are frames in the page
                                        if (isset($page['frames']) && !empty($page['frames'])) {
                                            echo '<ul class="figma-frames-list">';

                                            // Iterate through the frames of the page
                                            foreach ($page['frames'] as $index => $frame) {
                                                // Determine if the frame is active based on the selected node or if it's the first frame of a selected page
                                                $isActiveFrame = ($frame['id'] === $selectedNode) || ($isSelectedPage && $index === 0);

                                                // Add the 'active' class if this frame is the selected node or the first frame of a selected page
                                                $activeClass = $isActiveFrame ? 'active' : '';

                                                // Check if this frame has a preview image in the saved data
                                                $frameId = esc_attr($frame['id']);
                                                $previewUrl = isset($framePreviews[$frameId]) ? esc_url($framePreviews[$frameId]) : 'null';

                                                // Add data-preview attribute with the preview URL or 'null' if no preview exists
                                                echo '<li class="figma-frame-item ' . esc_attr($activeClass) . '" data-node="' . $frameId . '" data-page="' . esc_attr($page['id']) . '" data-preview="' . $previewUrl . '">';
                                                echo '<a class="figma-frame-name" >' . esc_html($frame['name']) . '</a>';
                                                echo '</li>';
                                            }

                                            echo '</ul>'; // Close frames list
                                        }

                                        echo '</li>'; // Close page item
                                    }

                                    echo '</ul>'; // Close pages list
                                }
                                
                            
                            ?>
                        </div>
                    </div>
                </div>
               
                <div class="no-figma-message">
                    <h3>Connect a Figma design to start converting</h3>
                    <img src="<?php echo plugins_url('/assets/images/figma-logo.png', dirname(__FILE__)); ?>" alt="Figma" width="120">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="lp-box no-padding convert-box-2 convert-ui 
                <?php 
                    echo ( $illiant_design_data ) ? 'active  ' : ' '; 

                    echo ($illiant_design_data && isset($illiant_design_data['framePreviews']) && !array_key_exists($selectedNode, $illiant_design_data['framePreviews'])) ? 'no-frame ' : ' ';

                    echo ($illiant_design_data && !isset($illiant_design_data['framePreviews'])) ? 'no-frame ' : ' ';
                ?> ">
                
                <?php include 'components/loaderModule.php'; ?>
                <div class="frame-tree">
                    <div class="lp-box-header">
                        <img src="<?php echo plugins_url('/assets/images/page-icon.svg', dirname(__FILE__)); ?>" >
                        <h3>Convert design to page</h3> 
                    </div>
                    <div class="lp-box-body no-padding">
                        <div class="p-10">
                            <div class="switch-block">
                                <p>Convert as:</p>
                                <div class="switch-container">
                                    <span>New page</span>
                                    <div class="switch">
                                        <input type="checkbox" id="toggle" class="switch-input" />
                                        <label for="toggle" class="switch-label"></label>
                                    </div>
                                    <span>Existing page</span>
                                </div>
                            </div>
                            <div class="conversion-type-block">
                                <div class="type-new type-on">
                                    <input type="text" id="page_title" name="page_title" class="regular-text" 
                                    placeholder="<?php echo esc_html__('Page title'); ?>" 
                                    value="<?php echo esc_attr($selected_node_name); ?>" >

                                    <a class="lp-btn block lightblue" id="convertFigma">Convert Page</a>
                                </div>
                                <div class="type-update">
                                    <select id="illiant-page-selector" style="width: 100%;"></select>
                                    <a class="lp-btn block lightblue disabled" id="updateFigma">Update Page</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="frame-bottom">
                            <div class="frame-direct-link hidden">
                                <h4>Page converted successfully!</h4>
                                <a class="page-link lp-btn orange" href="" target="_blank">Page Preview</a>
                            </div>
                            <div class="frame-preview-img">

                                <?php
                                // Check if there is a frame preview for the selected node and display it
                                if (isset($illiant_design_data['framePreviews']) && array_key_exists($selectedNode, $illiant_design_data['framePreviews'])) {
                                    $previewUrl = esc_url($illiant_design_data['framePreviews'][$selectedNode]);
                                    echo '<p> Frame preview: <span>' . $selected_node_name . '</span></p>';
                                    echo '<img src="' . $previewUrl . '" alt="Frame Preview" style="max-width: 100%;">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="no-frame-message">
                    <h3>Select a frame to convert</h3>
                    <img src="<?php echo plugins_url('/assets/images/noframe-icon.png', dirname(__FILE__)); ?>" alt="Figma" width="120">
                </div>

                <div class="novalid-frame-message">
                    <h3>The selected frame no longer exists.<br>Reconnect your Figma design to update the frame list.</h3>
                    <img src="<?php echo plugins_url('/assets/images/noframe-icon.png', dirname(__FILE__)); ?>" alt="Figma" width="120">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="lp-box">
                <h3 class="mb-3  pt-2 text-center">Contact us to help you prepare your first Figma design and receive an<br>additional 20 FREE CREDITS.</h3>
                <a class="lp-btn orange block" href="?page=illiant_wplandings-contact">CONTACT TECHNICAL SUPPORT</a>
            </div>
        </div>
    </div>
</div>