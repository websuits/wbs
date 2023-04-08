<?php
/*
* GoSMTP
* https://gosmtp.net
* (c) Softaculous Team
*/

if(!defined('GOSMTP_VERSION')){
	die('Hacking Attempt!');
}
	
// The Gosmtp Header
function gosmtp_page_header($title = 'GoSMTP'){
	
    $promos = apply_filters('gosmtp_review_link', true);
	
	wp_enqueue_style( 'gosmtp-admin' );
	wp_enqueue_script( 'gosmtp-admin' );
	
	echo '
<div class="gosmtp-box-container" style="margin:0">
	<h2>
		<table cellpadding="2" cellspacing="1" width="100%" class="fixed" border="0">
			<tr>
				<td valign="top">
					<h1>'.esc_html($title).'</h1>
				</td>
				'.($promos ? '
				<td align="right"><a target="_blank" class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/gosmtp">Review GoSMTP</a></td>' : '').'
				<td align="right" width="40"><a target="_blank" href="https://twitter.com/gosmtp"><img src="'.GOSMTP_URL.'/images/twitter.png" /></a></td>
				<td align="right" width="40"><a target="_blank" href="https://www.facebook.com/gosmtp/"><img src="'.GOSMTP_URL.'/images/facebook.png" /></a></td>
			</tr>
		</table>
	</h2>
	<hr/>
	<!--Main Table-->
	<table cellpadding="8" cellspacing="1" width="100%" class="fixed">
	<tr>
		<td valign="top">';

}

// The Gosmtp Settings footer
function gosmtp_page_footer($no_twitter = 0){
	
	echo '</td>';
	
	$promos = apply_filters('pagelayer_right_bar_promos', true);

	if($promos){

		echo '
	<td width="200" valign="top" id="pagelayer-right-bar">
		<div class="gosmtp-promotion" style="width:100%;" >
			<div class="gosmtp-promotion-content" style="background:white; border:1px solid #c3c4c7; padding:10px 10px 20px 10px; border-radius:6px;">
				<h2 class="gosmtp-promotion-logo">
					<span><a target="_blank" href="https://pagelayer.com/?from=gosmtp-plugin"><img src="'. GOSMTP_URL.'/images/pagelayer_product.png" width="100%"></a></span>
				</h2>
				<div>
					<em>The Best WordPress <b>Site Builder</b> </em>:<br>
					<ul style="font-size:13px;">
						<li>Drag &amp; Drop Editor</li>
						<li>Widgets</li>
						<li>In-line Editing</li>
						<li>Styling Options</li>
						<li>Animations</li>
						<li>Easily customizable</li>
						<li>Real Time Design</li>
						<li>And many more ...</li>
					</ul>
					<center><a class="button button-primary" target="_blank" href="https://pagelayer.com/?from=gosmtp-plugin">Visit Pagelayer</a></center>
				</div>
			</div>

			<div class="gosmtp-promotion-content" style="margin-top: 20px; background:white; border:1px solid #c3c4c7; padding:10px 10px 20px 10px; border-radius:6px;">
				<h2 class="gosmtp-promotion-logo">
					<span><a target="_blank" href="https://loginizer.com/?from=gosmtp-plugin"><img src="'.GOSMTP_URL.'/images/loginizer_product.png" width="100%"></a></span>
				</h2>
				<div>
					<em>Protect your WordPress website from <b>unauthorized access and malware</b> </em>:<br>
					<ul style="font-size:13px;">
						<li>BruteForce Protection</li>
						<li>reCaptcha</li>
						<li>Two Factor Authentication</li>
						<li>Black/Whitelist IP</li>
						<li>Detailed Logs</li>
						<li>Extended Lockouts</li>
						<li>2FA via Email</li>
						<li>And many more ...</li>
					</ul>
					<center><a class="button button-primary" target="_blank" href="https://loginizer.com/?from=gosmtp-plugin">Visit Loginizer</a></center>
				</div>
			</div>
		</div>';
		
	}
	
	echo '</tr>
	</table>
	<br />';
        
	if(empty($no_twitter)){
	
		echo '
	<div style="width:45%;background:#FFF;padding:15px; margin:20px auto; border:1px solid #c3c4c7;">
		<b>Let your followers know that you use GOSMTP to Sent mail on your website :</b>
		<form method="get" action="https://twitter.com/intent/tweet" id="tweet" onsubmit="return dotweet(this);">
			<textarea name="text" cols="45" row="3" style="resize:none;">I increased email deliverability of my #WordPress #site using @gosmtp</textarea>&nbsp; &nbsp; 
			<input type="submit" value="Tweet!" class="button button-primary" onsubmit="return false;" id="twitter-btn" style="margin-top:20px;"/>
		</form>	
	</div>
	<br/>

	<script>
		 function dotweet(ele){
			window.open(jQuery("#"+ele.id).attr("action")+"?"+jQuery("#"+ele.id).serialize(), "_blank", "scrollbars=no, menubar=no, height=400, width=500, resizable=yes, toolbar=no, status=no");
			return false;
		}
	</script>
	
	<a href="'.GOSMTP_WWW_URL.'" target="_blank">GOSMTP</a><span> v'.GOSMTP_VERSION.' You can report any bugs </span><a href="http://wordpress.org/support/plugin/gosmtp" target="_blank">here</a>.
</div>';
	
	}
}

// GoSMTP Setting page
function gosmtp_settings_page(){

	$all_mailers = gosmtp_load_mailer_list();

	// Save SMTP details
	if(isset($_REQUEST['save'])){
		
		// Check nonce
		check_admin_referer('gosmtp-options');
		
		$force_from_email = '';
		if(!empty($_REQUEST['force_from_email'])){
			$force_from_email = 1;
		}
		
		$force_from_name = '';
		if(!empty($_REQUEST['force_from_name'])){
			$force_from_name = 1;
		}
		
		$return_path = '';
		if(!empty($_REQUEST['return_path'])){
			$return_path = 1;
		}
		
		$save_mailer = gosmtp_optreq('mailer');
		
		$options = get_option('gosmtp_options', array());
		$options['mailer'] = [];		
		$options['mailer'][0]['mail_type'] = $save_mailer;
		$options['from_email'] = gosmtp_optreq('from_email');
		$options['force_from_email'] = $force_from_email;
		$options['from_name'] = gosmtp_optreq('from_name');
		$options['force_from_name'] = $force_from_name;
		$options['return_path'] = $return_path;
		
		// Update fields of mailer
		if(!empty($save_mailer) && !method_exists($all_mailers[$save_mailer], 'save_option')){
			$options['mailer'][0] = $all_mailers[$save_mailer]->save_options($options['mailer'][0]);
		}	
		
		$options = apply_filters( 'gosmtp_save_options', $options );
		
		if(update_option( 'gosmtp_options', $options )){
			$msg['success'] = 1;
		}
		
	}

	$smtp_options = get_option('gosmtp_options', array());

	// Default mailer set mail
	if(!is_array($smtp_options['mailer']) || empty($smtp_options['mailer'][0])){
		$smtp_options['mailer'] = [];
		$smtp_options['mailer'][0]['mail_type'] = 'mail';
	} 

	echo '<div class="wrap">';
	
	gosmtp_page_header();
	
	if(!empty($msg['success'])){
		echo '<div id="message" class="updated notice is-dismissible"><p>'.__('SMTP settings have been saved successfully!').'</p></div>';
	}

	if(!empty($send_mail['success'])){
		echo '<div id="message" class="updated notice is-dismissible"><p>'.__('Mail sent successfully!').'</p></div>';
	}else if(!empty($send_mail['error'])){
		echo '<div id="message" class="error notice is-dismissible">
		<p>'.( !empty($send_mail['error_msg']) ? wp_kses_post($send_mail['error_msg']) : __('Unable to send mail, Please check your SMTP details') ).'</p></div>';
	}

	?>
	<div class="gosmtp-setting-content">
		<div class="tabs-wrapper">
			<h2 class="nav-tab-wrapper gosmtp-wrapper">
				<a href="#smtpsetting" class="nav-tab nav-tab-active"><?php _e('SMTP Settings'); ?></a>
				<a href="#test-mail" class="nav-tab "><?php _e('Test Mail'); ?></a>
				<a href="#support" class="nav-tab "><?php _e('Support'); ?></a>
			</h2>
			<div class="gosmtp-tab-panel" id="smtpsetting">
				<form class="gosmtp-smtp-mail" name="smtp-details" method="post" action="">
					<?php wp_nonce_field('gosmtp-options'); ?>
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('From Email'); ?></th>
							<td>
								<input name="from_email" type="text" class="regular-text always_active" placeholder="notifications@example.com"  value="<?php if(!empty($smtp_options['from_email'])){
									echo esc_attr($smtp_options['from_email']);
								}?>"> 
								<p class="description" id="tagline-description"><?php _e( "The email address that emails are sent from. If you're using an email provider (Yahoo, Outlook.com, etc) this should be your email address for that account. Please note that other plugins can change this, to prevent this use the setting below." ); ?></p>
								<br>
								<input name="force_from_email" type="checkbox" <?php if(!empty($smtp_options['force_from_email'])){
									echo "checked";
								}?>>
								<label><?php _e('Force From Email');?><label>
								<p class="description" id="tagline-description"><?php _e( 'If checked, the From Email setting above will be used for all emails, ignoring values set by other plugins.' ); ?></p>
							
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('From Name'); ?></th>
							<td>
								<input name="from_name" type="text" class="regular-text always_active" placeholder="My Website"  value="<?php if(!empty($smtp_options['from_name'])){
									echo esc_attr($smtp_options['from_name']);
								}?>"> 
								<p class="description" id="tagline-description"><?php _e( "The name that emails are sent from." ); ?></p>
								<br>
								<input name="force_from_name" type="checkbox" <?php if(!empty($smtp_options['force_from_name'])){
									echo "checked";
								}?>>
								<label><?php _e('Force From Name');?><label>
								<p class="description" id="tagline-description"><?php _e( 'If checked, the From Name setting above will be used for all emails, ignoring values set by other plugins.' ); ?></p>
							
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Return Path'); ?></th>
							<td>
								<input class="mail sendlayer mailgun smtp" name="return_path" type="checkbox" <?php if(!empty($smtp_options['return_path'])){
									echo "checked";
								}?>>
								<label><?php _e('Set the return-path to match the From Email');?><label>
								<p class="description" id="tagline-description"><?php _e( 'Return Path indicates where non-delivery receipts - or bounce messages - are to be sent. If unchecked, bounce messages may be lost.' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Mailer'); ?></th>
							<td class="mailer_container">

							<?php
							$mailer_list = gosmtp_get_mailer_list();
							
							foreach($mailer_list as $key => $mailer){
								
								$is_pro = $disabled = $after_icon = '';
								$active_mailer = ($smtp_options['mailer'][0]['mail_type'] == $key) ? 'mail_active' : '';
								$icon = isset($mailer['icon']) ? $mailer['icon'] : GOSMTP_URL .'/images/'.$key.'.svg';;
									
								if(!class_exists($mailer['class'])){
									$is_pro = 'pro';
									$disabled = 'disabled';
									$after_icon='<div class="lock_icon">
										<span class="dashicons dashicons-lock"></span>
									</div>';
								}
								
								echo '<div class="gosmtp-mailer-input always_active '.esc_attr($is_pro).'">
									<label class="label">'. esc_html($mailer['title']) .'</label>
									<div for="'.esc_attr($key).'" class="mailer_label '.esc_attr($active_mailer).'" data-name="'.esc_attr($key).'">
										<img src="'. esc_attr($icon) .'" class="mailer" >
										'.wp_kses_post($after_icon).'
									</div>
									<input id="'.esc_attr($key).'" class="mailer_check" data-name="'.esc_attr($key).'" name="mailer" type="radio" '.esc_attr($disabled).' value="'. esc_attr($key) .'" '. checked( $key, $smtp_options['mailer'][0]['mail_type'], false) .'>
								</div>';
							}
							?>
							</td>
						</tr>
						<?php
							foreach($all_mailers as $key => $mailer){
								
								if(!method_exists($mailer, 'load_field')){
									continue;
								}
								
								echo '<tr>
									<td><h1 class="'.esc_attr($key).' smtp_heading">'. esc_html($mailer->title) .'</h1><td>
								</tr>';
								
								// Load current options
								$mailer->loadOptions();
								
								echo gosmtp_create_field($mailer->load_field(), $mailer);
							}
						?>
					</table>
					
					<p>
						<input type="submit" name="save" class="button button-primary" value="Save Changes">
					</p>
				</form>	
			</div>
			<div class="gosmtp-tab-panel" id="test-mail" style="display:none">
				<form class="gosmtp-smtp-mail" id="smtp-test-mail" name="test-mail" method="post" action="">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('To'); ?>:</th>
							<td>
								<input type="email" name="reciever_test_email" class="regular-text" placeholder="example@example.com" required />
								<p class="description" id="tagline-description"><?php _e( 'Enter the recipient\'s email address.' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Subject'); ?>:</th>
							<td>
								<input type="text" name="smtp_test_subject" class="regular-text" placeholder="Enter Subject" value="Test Mail" required />
								<p class="description" id="tagline-description"><?php _e( 'Enter a subject for your message.' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Message'); ?></th>
							<td>
								<textarea name="smtp_test_message" placeholder="Enter Message" class="regular-text" rows="10"required ><?php _e('This is a test mail!'); ?></textarea>
								<p class="description" id="tagline-description"><?php _e( 'Write your email message' ); ?> </p>
							</td>
						</tr>
					</table>
					<p>
						<input type="submit" name="send_mail" id="send_mail" class="button button-primary" value="Send Mail">
					</p>
				</form>
			</div>
			<div class="gosmtp-tab-panel" id="support" style="display:none">
				<div style="width:70%; margin:20px auto; display:flex; justify-content:center; flex-direction:column; align-items:center; line-height:1.5;">
					<img src="<?php echo esc_url(GOSMTP_URL) .'/images/gosmtp-text.png'?>" width="200"/>
					<h2><?php esc_html_e('You can contact the GoSMTP Team via email. Our email address is', 'gosmtp'); ?> <a href="mailto:support@gosmtp.net">support@gosmtp.net</a> <?php esc_html_e('or through Our Premium Support Ticket System at', 'gosmtp'); ?> <a href="https://softaculous.deskuss.com" target="_blank"><?php _e('here'); ?></h2>
				</div>
			</div>
		</div>
	</div>
	<?php gosmtp_page_footer(); ?>
</div>

<script>
	// TODO: move this in init.php
	var gosmtp_ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ) ?>?";
	var gosmtp_ajax_nonce = "<?php echo wp_create_nonce('gosmtp_ajax') ?>";
</script>

<?php

}

function gosmtp_create_field($fields, $mailer){
	$html = '';
	
	if(!is_array($fields)){
		return $html;
	}
	
	foreach($fields as $key => $field){
		
		$attrs = '';
		
		if(!empty($field['attr'])){
			$attrs .= esc_attr($field['attr']);
		}
		
		// Added attributes
		if(!in_array($field['type'], array('button', 'notice'))){
			
			$_type = ($field['type'] == 'copy') ? 'text' : $field['type'];
			
			$attrs .= ' type="'.esc_attr($_type).'"';
			$attrs .= ' name="'.esc_attr($mailer->mailer.'['.$key.']').'"';
		}
		
		// Added Classes
		$classes = esc_attr($mailer->mailer);
		
		if(!empty($field['class'])){
			$classes .= ' '.esc_attr($field['class']);
		}
		
		// Get value
		$val = $mailer->getOption($key, $mailer->mailer);
		
		if(empty($val)){
			$val = isset($field['default']) ? $field['default'] : '';
		}
		
		$placeholder = empty($field['place_holder']) ? '' : esc_attr($field['place_holder']);
		$list = empty($field['list']) ? array() : $field['list'];
		
		$input_html = '';
		
		switch($field['type']){
			case 'radio':
			
				foreach($list as $list_key => $list_value){
					$input_html .= '<input class="'.$classes.'" value="'.esc_attr($list_key).'" '.$attrs.''.checked($list_key, $val, false).'>
					<label>'.esc_html($list_value).'</label>';
				}
				
				break;

			case 'checkbox':
				
				$input_html .= '<input value="1" class="regular-text '.$classes.'" '.$attrs.' '.checked('1', $val, false).'>';
				
				break;

			case 'button':
			
				$href = empty($field['href']) ? '#' : esc_url($field['href']);
				
				$input_html .= '<a href="'.$href.'"class="button '.$classes.'" '.$attrs.'>'.esc_html($val).'</a>';
				
				break;
				
			case 'notice':

				$input_html .= '<div id="message" class="notice_container '.$classes.'">'.esc_html($val).'</div>';
				
				break;

			case 'select':

				$input_html .='<select class="regular-text '.$classes.'" '.$attrs.'>';
				
				foreach($list as $list_key => $list_value){
					$input_html .= '<option value="'.esc_attr($list_key).'" '.selected($list_key, $val, false).'>'.esc_html($list_value).'</option>';
				}
				
				$input_html .='</select>';
				
				break;
			
			 case 'copy':
			 
				$id = empty($field['id']) ? '' : esc_attr($field['id']);
				
				$input_html .= '<input class="regular-text gosmtp_copy '.$classes.'" placeholder="'.$placeholder.'" id="'.$id.'" value="'.esc_attr($val).'" '.$attrs.'>
				<span class="dashicons dashicons-admin-page " onclick="gosmtp_copy_url(\''.$id.'\')"></span>
				<p class="gosmtp_copy_message '.$id.'">'. __('Message coppied Successfully') .'.</p>';
				
				break;


			default:
			
				$input_html .= '<input class="regular-text '.$classes.'" placeholder="'.$placeholder.'" value="'.esc_attr($val).'" '.$attrs.'>';
				break;
		}
		
		$description = empty($field['desc']) ? '' : wp_kses_post($field['desc']);
		
		$html .= '<tr class='.(empty($field['tr_class']) ? '' : esc_attr($field['tr_class'])).'>
			<th scope="row">'.esc_html($field['title']).'</th>
			<td>
				'.$input_html.'
				<p class="description" id="tagline-description">'.wp_kses_post($description).'</p>
			</td>
		</tr>';
	}
	
	return $html;
}