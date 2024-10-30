<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="lp-modal">
    <div class="modal-dlg">
        <div class="modal-cnt">
            <div class="modal-head">
                <h5 class="modal-ttl"></h5>
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <img src="<?php echo esc_url(plugins_url('../assets/images/validFrame.gif', dirname(__FILE__))); ?>" alt="gif" class="inst-gif">

            <div class="modal-footer">
                <button type="button" class="lp-btn large blue btn-close" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn-confirm" class="lp-btn large orange">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="lp-modal-images">
    <div class="modal-dlg">
        <div class="modal-cnt">
            <div class="modal-head">
                <h5 class="modal-ttl"></h5>
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="lp-btn large blue btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="lp-modal-instructions x-large">
    <div class="modal-dlg">
        <div class="modal-cnt">
            <div class="modal-head">
                <h5 class="modal-ttl">Follow this 4 steps to prepare your Figma designs for conversion</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="inst-steps">
                    <div class="lp-box">
                        <div class="row">
                            <div class="col-md-6">
                                
                                <h3>Configure your design with AUTO layout</h3>
                                <p>For the best results, we recommend configuring all sections, containers and columns of your design with AUTO layout in Figma. <br><br> All layers that are not configured with AUTO layout will still be converted, but you will need to manually set the layout in the WordPress Page editor.</p>
                            </div>
                        
                            <div class="col-md-6">
                                <img src="<?php echo plugins_url('../assets/images/instructions-1.png', dirname(__FILE__)); ?>" width="100%">

                            </div>
                        </div>
                        <div class="text-right counter">
                            <p>1/4</p>
                        </div>
                    </div>
                    <div class="lp-box">
                        <div class="row">
                            <div class="col-md-6">
                                
                                <h3>Rename group layers you want to treat as a single image</h3>
                                <p>For compositions of elements you wish to import as a single image, group them and label the group "isImage." This helps WPLandings to import them accurately.<br><br>In the example we have a cellphone composed of multiple layers that we want to use a single image. To achieve this, group these elements together and label the group as "isImage" to ensure the entire composition is imported as a single image.</p>
                            </div>
                        
                            <div class="col-md-6">
                                <img src="<?php echo plugins_url('../assets/images/instructions-2.png', dirname(__FILE__)); ?>" width="100%">

                            </div>
                        </div>
                        <div class="text-right counter">
                            <p>2/4</p>
                        </div>
                    </div>
                    <div class="lp-box">
                        <div class="row">
                            <div class="col-md-6">
                                
                                <h3>Exclude from your design header and footer nav menus</h3>
                                <p>Hide or delete headers or footers navs from your Figma design. While WPLandings does not import functional nav menus, you can integrate existing ones from your WordPress theme.</p>
                            </div>
                        
                            <div class="col-md-6">
                                <img src="<?php echo plugins_url('../assets/images/instructions-3.png', dirname(__FILE__)); ?>" width="100%">

                            </div>
                        </div>
                        <div class="text-right counter">
                            <p>3/4</p>
                        </div>
                    </div>
                    <div class="lp-box">
                        <div class="row">
                            <div class="col-md-6">
                                
                                <h3>Install custom fonts in your theme</h3>
                                <p>Please ensure that all fonts used in your design are installed in your theme.</p>
                            </div>
                            <div class="col-md-6">
                                
                                <h3>Interactive elements</h3>
                                <p>WPLandings does not create interactive elements like forms, carousels, tabs, or accordions. For these features, replace the generated code with shortcodes or blocks from specialized third-party plugins.</p>
                            </div>
                        </div>
                        <div class="text-right counter">
                            <p>4/4</p>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="lp-btn large white outline btn-close" data-dismiss="modal">Skip</button>
                <button type="button" class="lp-btn large blue btn-next" data-dismiss="modal">Next</button>
                <button type="button" class="lp-btn large orange btn-confirm" data-dismiss="modal">Finish</button>
            </div>
        </div>
    </div>
</div>