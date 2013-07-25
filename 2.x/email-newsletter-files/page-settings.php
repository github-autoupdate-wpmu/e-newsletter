<?php

    $siteurl = get_option( 'siteurl' );

    $settings = $this->get_settings();

    $page_title =  __( 'eNewsletter Settings', 'email-newsletter' );

    if ( ! $settings ) {
        $page_title =  __( 'eNewsletter plugin Installation', 'email-newsletter' );

        $mode = "install";

    }
	global $email_newsletter;
	if (!class_exists('WpmuDev_HelpTooltips')) require_once $email_newsletter->plugin_dir . '/email-newsletter-files/class_wd_help_tooltips.php';
	$tips = new WpmuDev_HelpTooltips();
	$tips->set_icon_url($email_newsletter->plugin_url.'/email-newsletter-files/images/information.png');
	

    //Display status message
    if ( isset( $_GET['updated'] ) ) {
        ?><div id="message" class="updated fade"><p><?php echo urldecode( $_GET['message'] ); ?></p></div><?php
    }

?>

    <script type="text/javascript">
	
        jQuery( document ).ready( function($) {
			
			$('.newsletter-settings-tabs > div').not('.active').hide();
			$('#newsletter-tabs a').click(function(e) {
				var tab = $(this).attr('href');
				$(this).addClass('nav-tab-active').siblings('a').removeClass('nav-tab-active');
				$(tab).show().siblings('div').hide();
				$(tab).addClass('nav-tab-active');
				return false;
			});
			
            $( "input[type=button][name='save']" ).click( function() {
                if ( "" == $( "#smtp_host" ).val() && $( "#smtp_method" ).attr( 'checked' ) ) {
                    alert("<?php _e( 'Please write SMTP Outgoing Server, or select another Sending Method!', 'email-newsletter' ); ?>");
                    return false;
                }

                $( "#newsletter_action" ).val( "save_settings" );
                $( "#settings_form" ).submit();
            });

            //install plugin data
            $( "#install" ).click( function() {
                if ( "" == $( "#smtp_host" ).val() && $( "#smtp_method" ).attr( 'checked' ) ) {
                    alert("<?php _e( 'Please write SMTP Outgoing Server, or select another Sending Method!', 'email-newsletter' ); ?>");
                    return false;
                }

                $( "#newsletter_action" ).val( "install" );
                $( "#settings_form" ).submit();
                return false;

            });



            //uninstall plugin data
            $( "#uninstall_yes" ).click( function() {
                $( "#newsletter_action" ).val( "uninstall" );
                $( "#settings_form" ).submit();
                return false;

            });

            $( "#uninstall" ).click( function() {
                $( "#uninstall_confirm" ).show( );
                return false;
            });

            $( "#uninstall_no" ).click( function() {
                $( "#uninstall_confirm" ).hide( );
                return false;
            });


            //Test connection to bounces email
            $( "#test_bounce_conn" ).click( function() {
                var bounce_email    = encodeURIComponent($( "#bounce_email" ).val());
                var bounce_host     = encodeURIComponent($( "#bounce_host" ).val());
                var bounce_port     = encodeURIComponent($( "#bounce_port" ).val());
                var bounce_username = encodeURIComponent($( "#bounce_username" ).val());
                var bounce_password = encodeURIComponent($( "#bounce_password" ).val());
				var bounce_security = encodeURIComponent($( "#bounce_security" ).val());

                $( "#test_bounce_loading" ).show();
                $( "#test_bounce_conn" ).attr( 'disabled', true );

                $.ajax({
                    type: "POST",
                    url: "<?php echo $siteurl;?>/wp-admin/admin-ajax.php",
                    data: "action=test_bounces&bounce_email=" + bounce_email + "&bounce_host=" + bounce_host + "&bounce_port=" + bounce_port + "&bounce_username=" + bounce_username + "&bounce_password=" + bounce_password + "&bounce_security=" + bounce_security,
                    success: function( html ){
                        $( "#test_bounce_conn" ).attr( 'disabled', false );
                        $( "#test_bounce_loading" ).hide();
                        alert( html );
                    }
                 });
            });

            //Test connection to bounces email
            $( "#test_smtp_conn" ).click( function() {
                var smtp_host     = encodeURIComponent($( "#smtp_host" ).val());
                var smtp_port     = encodeURIComponent($( "#smtp_port" ).val());
                var smtp_from = encodeURIComponent($( "#smtp_from" ).val());
                var smtp_username = encodeURIComponent($( "#smtp_username" ).val());
                var smtp_password = encodeURIComponent($( "#smtp_password" ).val());
                var smtp_security = encodeURIComponent($( "#smtp_security" ).val());

                $( "#test_smtp_loading" ).show();
                $( "#test_smtp_conn" ).attr( 'disabled', true );

                $.ajax({
                    type: "POST",
                    url: "<?php echo $siteurl;?>/wp-admin/admin-ajax.php",
                    data: "action=test_smtp&smtp_from=" + smtp_from + "&smtp_host=" + smtp_host + "&smtp_port=" + smtp_port + "&smtp_username=" + smtp_username + "&smtp_password=" + smtp_password + "&smtp_security=" + smtp_security,
                    success: function( html ){
                        $( "#test_smtp_conn" ).attr( 'disabled', false );
                        $( "#test_smtp_loading" ).hide();
                        alert( html );
                    }
                 });
            });
            
            function set_out_option() {
	            $('.email_out_type' ).each( function() {
	                if( $( this )[0].checked ){
	                    $( '.email_out' ).hide();
	                    $( '.email_out_' + $( this ).val() ).show();
	                }
	            });
	        }
            
            set_out_option();
            $( '.email_out_type' ).change( function() {
                set_out_option();
                if( $( this )[0].checked ){
                    $( '.email_out' ).hide();
                    $( '.email_out_' + $( this ).val() ).show();
                }
            });
            
            $('table.permissionTable thead .check-column input:checkbox').change(function() {
            	if($(this).is(':checked')) {
            		$(this).parents('table').find('.check-column input:checkbox').not($(this)).attr('checked','checked');
            	} else {
            		$(this).parents('table').find('.check-column input:checkbox').not($(this)).prop("checked", false);
            	}
            });


        });
    </script>


    <div class="wrap">
        <h2><?php echo $page_title; ?></h2>

        <form method="post" name="settings_form" id="settings_form" >
            <input type="hidden" name="newsletter_action" id="newsletter_action" value="" />
            <?php if(isset($mode)) echo '<input type="hidden" name="mode"  value="'.$mode.'" />'; ?>
			
            <div class="newsletter-settings-tabs">
               
					<h3 id="newsletter-tabs" class="nav-tab-wrapper">
						<a href="#tabs-1" class="nav-tab nav-tab-active"><?php _e( 'General Settings', 'email-newsletter' ) ?></a>
						<a href="#tabs-2" class="nav-tab"><?php _e( 'Outgoing Email Settings', 'email-newsletter' ) ?></a>
						<a href="#tabs-3" class="nav-tab"><?php _e( 'Bounce Settings', 'email-newsletter' ) ?></a>
						<a href="#tabs-4" class="nav-tab"><?php _e( 'User Permissions', 'email-newsletter' ) ?></a>
						 <?php if ( ! isset( $mode ) || "install" != $mode ): ?>
						 	<a class="nav-tab" href="#tabs-5"><?php _e( 'Uninstall', 'email-newsletter' ) ?></a>
						 <?php endif; ?>
					</h3>
                    <div class="active" id="tabs-1">
                        <h3><?php _e( 'Double Opt In Settings', 'email-newsletter' ) ?></h3>
						
                        <table class="settings-form form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <?php _e( 'Double Opt In:', 'email-newsletter' ) ?>
                                </th>
                                <td>
                                    <input type="checkbox" name="settings[double_opt_in]" value="1" <?php checked('1',$settings['double_opt_in']); ?> />
                                    <span class="description"><?php _e( 'Yes, members will get confirmation email to subscribe to newsletters (only for not registered users)', 'email-newsletter' ) ?></span>
                                </td>
                            </tr>
							<tr valign="top">
                                <th scope="row">
                                    <?php _e( 'Double Opt In Subject:', 'email-newsletter' ) ?>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="settings[double_opt_in_subject]" value="<?php echo esc_attr($settings['double_opt_in_subject']); ?>" />
                                    <span class="description"><?php _e( 'Yes, members will get confirmation email to subscribe to newsletters (only for not registered users)', 'email-newsletter' ) ?></span>
                                </td>
                            </tr>
						</table>
						
						<h3><?php _e( 'Default Info Settings', 'email-newsletter' ) ?></h3>
						
						<table class="settings-form form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <?php _e( 'From name:', 'email-newsletter' ) ?>
                                </th>
                                <td>
                                    <input type="text" class="regular-text" name="settings[from_name]" value="<?php echo isset($settings['from_name']) ? esc_attr($settings['from_name']) : get_option( 'blogname' );?>" />
                                    <span class="description"><?php _e( 'Default "from" name when sending newsletters.', 'email-newsletter' ) ?></span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <?php _e( 'Contact information:', 'email-newsletter' ) ?>
                                </th>
                                <td>
                                    <textarea name="settings[contact_info]" class="contact-information" ><?php echo isset($settings['contact_info']) ? esc_textarea($settings['contact_info']) : "";?></textarea>
                                    <br />
                                    <span class="description"><?php _e( 'Default contact information will be added to the bottom of each email', 'email-newsletter' ) ?></span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <?php _e( 'View email in browser:', 'email-newsletter' ) ?>
                                </th>
                                <td>
                                    <textarea name="settings[view_browser]" class="view-browser" ><?php echo isset($settings['view_browser']) ? esc_textarea($settings['view_browser']) : __( '<a href="{VIEW_LINK}" title="View e-mail in browser">View e-mail in browser</a><br/>', 'email-newsletter' ); ?></textarea>
                                    <br />
                                    <span class="description"><?php _e( 'This HTML message will be visible before newsletter starts so user have ability to display email in browser. Use "{VIEW_LINK}" as link. Leave blank to disable.', 'email-newsletter' ) ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="tabs-2">
                        <h3><?php _e( 'Outgoing SMTP Email Settings', 'email-newsletter' ) ?></h3>
                        <table class="settings-form form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row">
                                        <?php echo _e( 'Email Sending Method:', 'email-newsletter' );?>
                                    </th>
                                    <td>
                                        <label id="tip_smtp">
                                            <input type="radio" name="settings[outbound_type]" id="smtp_method" value="smtp" class="email_out_type" <?php echo ( $settings['outbound_type'] == 'smtp' || ! $settings['outbound_type']) ? 'checked="checked"' : '';?> /><?php echo _e( 'SMTP (recommended)', 'email-newsletter' );?>
                                        </label>
											
										<?php $tips->bind_tip(__("The SMTP method allows you to use your SMTP server (or Gmail, Yahoo, Hotmail etc. ) for sending newsletters and emails. It's usually the best choice, especially if your host has restrictions on sending email and to help you to avoid being blacklisted as a SPAM sender",'email-newsletter'), '#tip_smtp'); ?>

                                        <label id="tip_php">
                                            <input type="radio" name="settings[outbound_type]" value="mail" class="email_out_type" <?php echo $settings['outbound_type'] == 'mail' ? 'checked="checked"' : '';?> /><?php echo _e( 'php mail', 'email-newsletter' );?>
                                        </label>
										<?php $tips->bind_tip(__( "This method uses php functions for sending newsletters and emails. Be careful because some hosts may set restrictions on using this method. If you can't edit settings of your server, we recommend to use the SMTP method for optimal results!", 'email-newsletter' ), '#tip_php'); ?>
                                    </td>
                                </tr>
                            </tbody>

                            <tbody class="email_out email_out_smtp">
                                <tr valign="top">
                                    <th scope="row">
                                        <?php _e( 'From email:', 'email-newsletter' ) ?>
                                    </th>
                                    <td>
                                        <input type="text" id="smtp_from" class="regular-text" name="settings[from_email]" value="<?php echo esc_attr( $settings['from_email'] ? $settings['from_email'] : get_option( 'admin_email' ) );?>" />
                                        <span class="description"><?php _e( 'Default "from" email address when sending newsletters.', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                    </th>
                                    <td>
                                        <span class="red description"><?php _e( 'Note: for SMTP method - in "From email" you should use only emails which related with your SMTP server!', 'email-newsletter' ) ?></span>                                        
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'SMTP Outgoing Server', 'email-newsletter' ) ?>:</th>
                                    <td>
                                        <input type="text" id="smtp_host" class="regular-text" name="settings[smtp_host]" value="<?php echo esc_attr($settings['smtp_host']);?>" />
                                        <span class="description"><?php _e( 'The hostname for the SMTP account, eg: mail.', 'email-newsletter' ) ?><?php echo $_SERVER['HTTP_HOST'];?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'SMTP Username:', 'email-newsletter' ) ?></th>
                                    <td>
                                        <input type="text" id="smtp_username" class="regular-text" name="settings[smtp_user]" value="<?php echo esc_attr($settings['smtp_user']);?>" />
                                        <span class="description"><?php _e( '(leave blank for none)', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'SMTP Password:', 'email-newsletter' ) ?></th>
                                    <td>
                                        <input type="password" id="smtp_password" class="regular-text" name="settings[smtp_pass]" value="<?php echo ( isset( $settings['smtp_pass'] ) && '' != $settings['smtp_pass'] ) ? '********' : ''; ?>" />
                                        <span class="description"><?php _e( '(leave blank for none)', 'email-newsletter' ); if(isset( $settings['smtp_pass'] ) && '' != $settings['smtp_pass']) _e( ' (For security, saved password lenght does not match preview)', 'email-newsletter' ); ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'SMTP Port', 'email-newsletter' ) ?>:</th>
                                    <td>
                                        <input type="text" id="smtp_port" name="settings[smtp_port]" value="<?php echo esc_attr($settings['smtp_port']);?>" />
                                        <span class="description"><?php _e( 'Defaults to 25.  Gmail uses 465 or 587', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'Secure SMTP?', 'email-newsletter' ) ?>:</th>
                                    <td>
                                        <select id="smtp_security" name="settings[smtp_secure_method]" >
                                            <option value="0" <?php selected('0',$settings['smtp_secure_method']); ?>><?php _e( 'None', 'email-newsletter' ) ?></option>
                                            <option value="ssl" <?php selected('ssl',$settings['smtp_secure_method']); ?>><?php _e( 'SSL', 'email-newsletter' ) ?></option>
                                            <option value="tls" <?php selected('tls',$settings['smtp_secure_method']); ?>><?php _e( 'TLS', 'email-newsletter' ) ?></option>
                                        </select>
                                        <span class="description"><?php _e( 'Choose and optional type of connection', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><div id="test_smtp_loading"></div></th>
                                    <td>
                                        <input class="button button-secondary" type="button" name="" id="test_smtp_conn" value="<?php _e( 'Test Connection', 'email-newsletter' ) ?>" />
                                        <span class="description"><?php _e( 'We will send test email on configured from email address.', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="settings-form form-table">
                            <h3><?php _e( 'CRON Email Sending Settings', 'email-newsletter' ) ?></h3>
                            <tbody>
                                <tr valign="top">
                                    <th scope="row">
                                        <?php _e( 'Enable', 'email-newsletter' ) ?>
                                        <span class="description"><?php _e( ' (CRON)', 'email-newsletter' ) ?></span>
                                    </th>
                                    <td>
                                        <select name="settings[cron_enable]" >
                                            <option value="1" <?php selected('1',esc_attr($settings['cron_enable'])); ?>><?php _e( 'Enable', 'email-newsletter' ) ?></option>
                                            <option value="2" <?php selected('2',esc_attr($settings['cron_enable'])); ?>><?php _e( 'Disable', 'email-newsletter' ) ?></option>
                                        </select>
                                        <span class="description"><?php _e( "('Disable' - not use CRON for sending emails)", 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <?php _e( 'Limit send:', 'email-newsletter' ) ?>
                                        <span class="description"><?php _e( ' (CRON)', 'email-newsletter' ) ?></span>
                                    </th>
                                    <td>
                                        <input type="text" name="settings[send_limit]" value="<?php echo esc_attr($settings['send_limit']);?>" />
                                        <span class="description"><?php _e( '(0 or blank for unlimited)', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <?php _e( 'Emails per', 'email-newsletter' ) ?>
                                        <span class="description"><?php _e( ' (CRON)', 'email-newsletter' ) ?></span>
                                    </th>
                                    <td>
                                        <select name="settings[cron_time]" >
                                            <option value="1" <?php echo ( 1 == $settings['cron_time'] ) ? 'selected="selected"' : ''; ?> ><?php _e( 'Hour', 'email-newsletter' ) ?></option>
                                            <option value="2" <?php echo ( 2 == $settings['cron_time'] ) ? 'selected="selected"' : ''; ?> ><?php _e( 'Day', 'email-newsletter' ) ?></option>
                                            <option value="3" <?php echo ( 3 == $settings['cron_time'] ) ? 'selected="selected"' : ''; ?> ><?php _e( 'Month', 'email-newsletter' ) ?></option>
                                        </select>
                                    </td>
                                </tr>
							</tbody>
                        </table>
                    </div>

                    <div id="tabs-3">
                        <h3><?php _e( 'Bounce Settings', 'email-newsletter' ) ?></h3>
						<?php
						if(!function_exists('imap_open')) {
						?>
						
	                    <p><?php _e( 'Please enable "IMAP" PHP extension for bounce to work.', 'email-newsletter' ) ?></p>
						
						<?php
						}
						else {
						?>
                        <p><?php _e( 'This controls how bounce emails are handled by the system. Please create a new separate POP3 email account to handle bounce emails. Enter these POP3 email details below.', 'email-newsletter' ) ?></p>
                        <table class="settings-form form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'Email Address:', 'email-newsletter' ) ?></td>
                                    <td>
                                        <input type="text" name="settings[bounce_email]" id="bounce_email" class="regular-text" value="<?php echo esc_attr($settings['bounce_email']);?>" />
                                        <span class="description"><?php _e( 'Email address where bounce emails will be sent by default (might be overwritten by server)', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'POP3 Host:', 'email-newsletter' ) ?></th>
                                    <td>
                                        <input type="text" name="settings[bounce_host]" id="bounce_host" class="regular-text" value="<?php echo esc_attr($settings['bounce_host']);?>" />
                                        <span class="description"><?php _e( 'The hostname for the POP3 account, eg: mail.', 'email-newsletter' ) ?><?php echo $_SERVER['HTTP_HOST'];?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'POP3 Port', 'email-newsletter' ) ?>:</th>
                                    <td>
                                        <input type="text" name="settings[bounce_port]" id="bounce_port" value="<?php echo esc_attr($settings['bounce_port']?$settings['bounce_port']:110);?>" size="2" />
                                        <span class="description"><?php _e( 'Defaults to 110 or 995 with SSL enabled', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'POP3 Username:', 'email-newsletter' ) ?></th>
                                    <td>
                                        <input type="text" name="settings[bounce_username]" id="bounce_username" class="regular-text" value="<?php echo esc_attr($settings['bounce_username']);?>" />
                                        <span class="description"><?php _e( 'Username for this bounce email account (usually the same as the above email address) ', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e( 'POP3 Password:', 'email-newsletter' ) ?></th>
                                    <td>
                                        <input type="password" name="settings[bounce_password]" id="bounce_password" class="regular-text" value="<?php echo ( isset( $settings['bounce_password'] ) && '' != $settings['bounce_password'] ) ? '********' : ''; ?>" />
                                        <span class="description"><?php _e( 'Password to access this bounce email account', 'email-newsletter' ); if(isset( $settings['bounce_password'] ) && '' != $settings['bounce_password']) _e( ' (For security, saved password lenght does not match preview)', 'email-newsletter' ); ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">
                                        <?php _e( 'Secure POP3?:', 'email-newsletter' );?>
                                    </th>
                                    <td>
                                        <select name="settings[bounce_security]" id="bounce_security" >
                                            <option value="" <?php echo ( '' == $settings['bounce_security'] ) ? 'selected="selected"' : ''; ?> ><?php _e( 'None', 'email-newsletter' ) ?></option>
                                            <option value="/ssl" <?php echo ( '/ssl' == $settings['bounce_security'] ) ? 'selected="selected"' : ''; ?> ><?php _e( 'SSL', 'email-newsletter' ) ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><div id="test_bounce_loading"></div></th>
                                    <td>
                                        <input class="button button-secondary" type="button" name="" id="test_bounce_conn" value="<?php _e( 'Test Connection', 'email-newsletter' ) ?>" />
                                        <span class="description"><?php _e( 'We will send test email on Bounce address and will try read this email and delete after(this part might not be possible)', 'email-newsletter' ) ?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
						<?php
						}
						?>
                    </div>
					<div id="tabs-4">
						<?php global $wp_roles; ?>
						<h3><?php _e('User Permissions','email-newsletter'); ?></h3>
						<p><?php _e('Here you can set your desired permissions for each user role on your site','email-newsletter'); ?></p>
						<div class="metabox-holder" id="newsletter_user_permissions">
							<?php foreach($wp_roles->get_names() as $name => $label) : ?>
								<?php if($name == 'administrator') continue; ?>
								<?php $role_obj = get_role($name); ?>
								<div class="postbox">
									<h3 class="hndle"><span><?php echo $label; ?></span></h3>
									<div class="inside">
										<table class="widefat permissionTable">
											<thead>
												<tr valign="top">
													<th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
													<th><?php _e('Capability','email-newsletter'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($this->capabilities as $key => $label) : ?>
													<tr valign="top">
														<th class="check-column" scope="row">
															<input id="<?php echo $name.'_'.$key; ?>" type="checkbox" value="1" name="settings[email_caps][<?php echo $key; ?>][<?php echo $name; ?>]" <?php checked(isset($wp_roles->roles[$name]['capabilities'][$key]) ? $wp_roles->roles[$name]['capabilities'][$key] : '',true); ?> />
														</th>
														<th style="" class="manage-column column-<?php echo $key; ?>" id="<?php echo $key; ?>" scope="col">
															<label for="<?php echo $name.'_'.$key; ?>"><?php echo $label; ?></label>
														</th>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
                    <?php if ( ! isset( $mode ) || "install" != $mode ): ?>
                    <div id="tabs-5">
                        <h3><?php _e( 'Uninstall', 'email-newsletter' ) ?></h3>
                        <p><?php _e( 'Here you can delete all data associated with the plugin from the database.', 'email-newsletter' ) ?></p>
                        <p>
                            <input class="button button-secondary" type="button" name="uninstall" id="uninstall" value="<?php _e( 'Delete data', 'email-newsletter' ) ?>" />
                            <span class="description" style="color: red;"><?php _e( "Delete all plugin's data from DB and remove enewsletter-custom-themes folder.", 'email-newsletter' ) ?></span>
                            <div id="uninstall_confirm" style="display: none;">
								<p>
									<span class="description"><?php _e( 'Are you sure?', 'email-newsletter' ) ?></span>
									<br />
									<input class="button button-secondary" type="button" name="uninstall" id="uninstall_no" value="<?php _e( 'No', 'email-newsletter' ) ?>" />
									<input class="button button-secondary" type="button" name="uninstall" id="uninstall_yes" value="<?php _e( 'Yes', 'email-newsletter' ) ?>" />
								</p>
                            </div>
                        </p>
                    </div>
                    <?php endif; ?>

            </div><!--/.newsletter-tabs-settings-->
		
            <p class="submit">
            <?php if ( isset( $mode ) && "install" == $mode ) { ?>
                <input class="button button-primary" type="button" name="install" id="install" value="<?php _e( 'Install', 'email-newsletter' ) ?>" />
            <?php } else { ?>
                <input class="button button-primary" type="button" name="save" value="<?php _e( 'Save all Settings', 'email-newsletter' ) ?>" />
            <?php } ?>
			</p>

        </form>

    </div><!--/wrap-->