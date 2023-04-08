<?php

namespace GOSMTP\Mailer;

class Loader{
	
	var $options;
	var $mailer = '';
	var $url = '';
	var $headers = array();
	
	public function __construct(){
		
		// Load options
		$this->loadOptions();
		
	}
	
	public function loadOptions(){
		$options = get_option('gosmtp_options', array());
		
		$this->options = $options;
	}
	
	public function getMailerOption(){
		
		$mailer = $this->mailer;
		
		if(empty($mailer) || !isset($this->options['mailer'][0])){
			return array();
		}
		
		return $this->options['mailer'][0];
	}
	
	public function getActiveMailer(){
		
		if(!isset($this->options['mailer'][0]) || !isset($this->options['mailer'][0]['mail_type'])){
			return 'mail';
		}
		
		return $this->options['mailer'][0]['mail_type'];
	}
	
	public function getOption($key, $mailer = '', $default = ''){
		
		$options = $this->options;
		
		if(!empty($mailer) && $mailer == $this->getActiveMailer()){
			$options = $this->options['mailer'][0];
		}
		
		if(isset($options[$key])){
			return $options[$key];
		}
		
		return $default;	
	}
	
	public function save_options($options){
		
		if(!method_exists($this, 'load_field')){
			return $options;
		}
		
		$fields = $this->load_field();
		
		foreach($fields as $key => $field){
			
			$val = '';
			
			if(!empty($_REQUEST[$this->mailer]) && isset($_REQUEST[$this->mailer][$key])){
				$val = sanitize_text_field($_REQUEST[$this->mailer][$key]);
			}
			
			$options[$key] = $val;
		}
		
		return $options;	
	}
	
	public function delete_option($key, $mailer = ''){

		if(!empty($mailer) && isset($this->options['mailer'][0][$key])){
			unset($this->options['mailer'][0][$key]);
		}elseif(isset($this->options[$key])){
			unset($this->options[$key]);
		}

		update_option( 'gosmtp_options', $this->options );
	}
	
	public function update_option($key, $val, $mailer=''){
		
		if(!empty($mailer)){
			
			if(!is_array($this->options['mailer'][0])){
				$this->options['mailer'][0] = array();
			}
			
			$this->options['mailer'][0][$key] = $val;
			
		}else{
			$this->options[$key] = $val;
		}
		
		update_option( 'gosmtp_options', $this->options);
	}
	
	protected function filterRecipientsArray($args){
		$recipients = [];

		foreach($args as $key => $recip){
			
			$recip = array_filter($recip);

			if(empty($recip) || ! filter_var( $recip[0], FILTER_VALIDATE_EMAIL ) ){
				continue;
			}

			$recipients[$key] = array(
				'address' => $recip[0]
			);

			if(!empty($recip[1])){
				$recipients[$key]['name'] = $recip[1];
			}
		}

		return $recipients;
	}

	public function setHeaders($headers){

		foreach($headers as $header){
			$name = isset($header[0]) ? $header[0] : false;
			$value = isset($header[1]) ? $header[1] : false;

			if(empty($name) || empty($value)){
				continue;
			}

			$this->setHeader($name, $val);
		}
		
	}

	public function setHeader($name, $val){
		
		$name = sanitize_text_field($name);
		
		$this->headers[$name] = WP::sanitize_value($val);
		
	}

	protected function getDefaultParams(){
		$timeout = (int)ini_get('max_execution_time');

		return [
			'timeout'     => $timeout ?: 30,
			'httpversion' => '1.1',
			'blocking'    => true,
		];
	}

	public function set_from($from = ''){
		global $phpmailer;
		
		// Check for force set
		if(!empty($this->options['force_from_email'])){
		    $phpmailer->From = !empty($this->options['from_email']) ? $this->options['from_email'] : $phpmailer->From;
			$from = $phpmailer->From;
		}
		
		if(!empty($this->options['force_from_name'])){
		    $phpmailer->FromName = !empty($this->options['from_name']) ? $this->options['from_name'] : $phpmailer->FromName;
		}
		
		return $from;
	}
	
	public function handle_response($response){
		
		$status = false;
		$message = array();

		if(is_wp_error($response)){

			$code = $response->get_error_code();

			if(!is_numeric($code)) {
				$code = 400;
			}

			$msg = $response->get_error_message();

			$message = array(
				'code'    => $code,
				'message' => $msg
			);
			
			$this->process_response($message, $status);
			
			throw new \PHPMailer\PHPMailer\Exception($msg, $code);
			
			return;
			
		}elseif($response['status'] == true){
			
			unset($response['status']);
			
			$message = $response;
			$status = true;
		
		}else{
			$message = array(
				'code'    => $code,
				'message' => __('Unable to send mail, Please check your SMTP details')
			);
		}
		
		return $this->process_response($message, $status);
		
	}

	public function process_response($message, $status){
		global $phpmailer;
		
		$logger = new \GOSMTP\Logger();

		$headers = array(
			'Reply-To' => $phpmailer->getReplyToAddresses(),
			'Cc' => $phpmailer->getCcAddresses(),
			'Bcc' => $phpmailer->getBccAddresses(),
			'Content-Type' => $phpmailer->ContentType,
		);

		$data = array(
			'to' => maybe_serialize($phpmailer->getToAddresses()),
			'from' => $phpmailer->From,
			'subject' => $phpmailer->Subject,
			'body' => $phpmailer->Body,
			'attachments' => maybe_serialize($phpmailer->getAttachments()),
			'status' => $status ? 'sent' : 'failed',
			'response' => maybe_serialize($message),
			'headers' => maybe_serialize($headers),
			'source' => $this->mailer
		);

		if(isset($_POST['gostmp_id'])){
			$id = (int)gosmtp_optpost('gostmp_id');
			$result = $logger->get_logs('records', $id);
			$operation = isset($_POST['operation']) ? gosmtp_optpost('operation') : false;
			
			if(!empty($operation) && !empty($result)){
				
				if($operation == 'resend'){
					$data['resent_count'] = $result[0]->resent_count + 1;
				}else{
					$data['retries'] = $result[0]->retries + 1;
				}
				
				$logger->update_logs($data, $id);
			}
		}else{
			$logger->add_logs($data);    
		}

		return $status;
	}
	
}
