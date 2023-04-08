<?php
/*
* GoSMTP
* https://gosmtp.net
* (c) Softaculous Team
*/

if(!defined('GOSMTP_VERSION')){
	die('Hacking Attempt!');
}

add_action('wp_ajax_gosmtp_test_mail', 'gosmtp_test_mail');
function gosmtp_test_mail(){
	
	global $phpmailer;

	// Check nonce
	check_admin_referer( 'gosmtp_ajax' , 'gosmtp_nonce' );

	$to = gosmtp_optpost('reciever_test_email');
	$subject = gosmtp_optpost('smtp_test_subject');
	$body = gosmtp_optpost('smtp_test_message');
	
	// TODO: send debug param
	if(isset($_GET['debug'])){
		// show wp_mail() errors
		add_action( 'wp_mail_failed', function( $wp_error ){
			echo "<pre>";
			print_r($wp_error);
			echo "</pre>";
		}, 10, 1 );
	}
	
	$msg = array();
	
	// TODO check for mailer
	if(!get_option('gosmtp_options')){
		$msg['error'] = _('You have not configured SMTP settings yet !');
	}else{
		$result = wp_mail($to, $subject, $body);

		if(!$result){
			$msg['error'] = __('Unable to send mail !').(empty($phpmailer->ErrorInfo) ? '' : ' '.__('Error : ').$phpmailer->ErrorInfo);
		}else{
			$msg['response'] = __('Message sent successfully !');
		}
	}
	
	gosmtp_json_output($msg);
}

add_action('wp_ajax_gosmtp_get_log', 'gosmtp_get_log');
function gosmtp_get_log(){
	check_admin_referer( 'gosmtp_ajax' , 'gosmtp_nonce' );
	
	$logger = new GOSMTP\Logger();
	$id = gosmtp_optpost('id');
	
	if(empty($id)){
		$resp['error'] = __('Log ID Invalid!');
		gosmtp_json_output($resp);
	}
	
	$mail_data = $logger->get_logs('records', $id);
	
	if(empty($mail_data)){
		$resp['error'] = __('Records not found!');
		gosmtp_json_output($resp);
	}
	
	$mail = $mail_data[0];
	$tos = maybe_unserialize($mail->to);
	$attachments = maybe_unserialize($mail->attachments);
	$_attachments = array();
	$to_list = array();
	
	foreach($attachments as $key => $attachment){
		$_attachments[] = array(
			'Filename' => $attachment[1],
			'Content-Transfer-Encoding' => $attachment[3],
			'Content-Disposition' => $attachment[6],
			'Content-Type' => $attachment[4]
		);
	}

	$headers = maybe_unserialize($mail->headers);
	
	if(is_array($tos)){
		foreach($tos as $key => $to){
			$to_list[] = $to[0];
		}
	}else{
		$to_list[] = $tos;
	}
	
	$created_time = strtotime($mail->created_at);
	$created_at = date("M d, Y", $created_time).' at '. date('h:i A', $created_time);
	
	$tmp = array(
		'id' => $mail->id,
		'to' => implode(',', $to_list),
		'from' => $mail->from,
		'subject' => $mail->subject,
		'status' => $mail->status == 'sent' ? __('Sent') : __('Failed'),
		'created' => $created_at,
		'headers' => gosmtp_header_format($headers, 'array', true),
		'attachments' => $_attachments,
		'body' => $mail->body,
		'mailer' => $mail->source,
		'response' => maybe_unserialize($mail->response)
	);
	
	$resp['response']['data'] = $tmp;

	gosmtp_json_output($resp);
}

add_action('wp_ajax_gosmtp_resend_mail','gosmtp_resend_mail');
function gosmtp_resend_mail(){
	check_admin_referer( 'gosmtp_ajax' , 'gosmtp_nonce' );
	
	$resp = array();
	$id = gosmtp_optpost('id');
	
	if(empty($id)){
		$resp['error'] = __('Log ID Invalid!');
		gosmtp_json_output($resp);
	}

	$mail_headers = array();
	$id = (int)gosmtp_optpost('id');
    
	$logger = new GOSMTP\Logger();
	$response = $logger->get_logs('records', $id);
 
	if(!isset($response[0])){
		$resp['error'] = __('Something Wents To Wrong!');
		gosmtp_json_output($resp);
	}
	
	$response = $response[0];
	$tos = maybe_unserialize($response->to);
	$subject = $response->subject;
	$attachments = maybe_unserialize($response->attachments);
	$_attachments = array();
	$tos_list = array();
	$body = $response->body;
	
	if(count($tos) > 0){
		foreach($tos as $key => $to){
			$tos_list[] = $to[0];
		}
	}

	if(count($attachments) > 0){
		foreach($attachments as $key => $attachment){
			$_attachments[] = $attachment[0];
		}
	}

	$headers = maybe_unserialize($response->headers);
	$headers = gosmtp_header_format($headers, 'text');

	$result = wp_mail($tos_list, $subject, $body, $headers, $_attachments);

	if(!$result){
		$resp['error'] = 'Unable to send mail!';
	}else{
		$resp['response'] = 'Message sent successfully!';
	}

	gosmtp_json_output($resp);
}

add_action('wp_ajax_gosmtp_delete_log', 'gosmtp_delete_log');
function gosmtp_delete_log(){

	check_admin_referer( 'gosmtp_ajax' , 'gosmtp_nonce' );
	
	$resp = array();
	$ids = gosmtp_optpost('id');
	
	if(empty($ids)){
		$resp['error'] = __('Log ID Invalid!');
		gosmtp_json_output($resp);
	}
	
	$logger = new GOSMTP\Logger();
	
	if(is_array($ids)){
		foreach($ids as $k => $id){
			$response = (int)$logger->delete_log($id);
			
			if(!empty($response)){
				continue;
			}
			
			$resp['error'] = __('Some logs have not been removed for some reason!');
		}

	}else{
		$response = $logger->delete_log((int)$ids);
	}
	
	if(!empty($resp['error'])){
		$resp['error'] = $resp['error'];
		gosmtp_json_output($resp);
	}
	
	if($response){
		$resp['response'] = __('Log Removed Successfully!');
	}else{
		$resp['error'] = __('Unable to Remove logs for some reason!');
	}

	gosmtp_json_output($resp);
}

// Miscellaneous
function gosmtp_header_format($headers, $output = 'text', $replace_chars = false){
	
	$heads = array();

	if(empty($headers) || count($headers) < 1){
		return $heads;
	}

	foreach($headers as $type => $header){
		
		switch($output){
			case 'text':
				$tmp_qry = $type.': ';
				
				if(is_array($header)){
					foreach($header as $k => $vals){
						$format = ($type != 'Reply-To' ? $vals[1].' <'.$vals[0].'>' : '<'.$vals[0].'>');

						if($replace_chars){
							$format = htmlspecialchars($format);
						}

						$tmp_qry .= $format;
					}
					
					$heads[] = $tmp_qry;
				}else{
					$heads[] = $tmp_qry.' '.$header;
				}
				
				break;
				
			default:
				$tmp_qry = [];
				
				if(is_array($header)){
					foreach($header as $k => $vals){
						$format = ($type != 'Reply-To' ? $vals[1].' <'.$vals[0].'>' : '<'.$vals[0].'>');

						if($replace_chars){
							$format = htmlspecialchars($format);
						}

						$tmp_qry[] = $format;
					}
					
					$heads[$type] = $tmp_qry;
				}else{
					$heads[$type] = $header;
				}
		}
	}

	return $heads;
}
