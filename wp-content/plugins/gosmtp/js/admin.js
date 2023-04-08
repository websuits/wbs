jQuery(document).ready(function(){

	// Tabs Handler
	var tabs = jQuery('.gosmtp-wrapper').find('.nav-tab');
	var tabsPanel = jQuery('.tabs-wrapper').find('.gosmtp-tab-panel');

	function gosmtp_load_tab(event){ 

		var hash  = window.location.hash;

		// No action needed when there is know hash value 
		if(!hash){
			return;
		}

		// Scroll top if tabs is not visible
		jQuery("html, body").animate({ scrollTop: 0 }, "fast");

		// Select elements
		jEle = jQuery(".nav-tab-wrapper").find("[href='" + hash + "']"); 

		if(jEle.length < 1){
			return;
		}
		
		// Remove active tab
		tabs.removeClass('nav-tab-active');
		tabsPanel.hide();
		
		// Make tab active
		jEle.addClass('nav-tab-active');
		jQuery('.tabs-wrapper').find(hash).show();
	}

	// Load function when hash value change
	jQuery( window ).on( 'hashchange', gosmtp_load_tab);

	// For First load
	gosmtp_load_tab();

	tabs.click(function(e){
		if(jQuery(this).hasClass("nav-tab-active")){
			e.preventDefault();
		}
	});

	// Auth show and hide Handler
	var smtpAuth = jQuery('input[name="smtp[smtp_auth]"]');

	smtpAuth.on('click', function(){
		var val = jQuery(this).attr('value');

		if(val == 'Yes'){
			jQuery('.smtp-authentication').show();
		}else{
			jQuery('.smtp-authentication').hide();
		}
	});

	// Mailer active effert
	jQuery('.gosmtp-mailer-input').not('.pro').click( function(){
		
		var jEle = jQuery(this);
		
		// Set active mailer
		jQuery('.gosmtp-mailer-input').find('.mailer_label').removeClass('mail_active');
		jEle.find('.mailer_label').addClass('mail_active');

		// Taggle mailer tabs
		jQuery('#smtpsetting tr').hide(); 
		jQuery('.always_active').closest('tr').show();

		//Show active tab
		attr_name = jQuery('.mail_active').attr('data-name');
		jQuery('.'+attr_name).closest("tr").show();
		
		jEle.find('[name="mailer"]').prop('checked', true);

		// For On load set
		if(attr_name =='smtp'){
			jQuery('input[name="smtp[smtp_auth]"][checked="checked"]').click();
		}

	});

	//Handle checkbox events
	// TODO: check
	jQuery('body').on('click','.gosmtp-multi-check, .gosmtp-checkbox', function(){

		$this = jQuery(this);
		var parent = $this.parent().parent().parent();
		var checkedCount = jQuery('td input[type="checkbox"]:checked').length;
		var total = jQuery('td input[type="checkbox"]').length;
		var prop = false;
		var clas = '';
    
		if($this.hasClass('gosmtp-multi-check')){
			clas = 'td input[type="checkbox"]';
			prop = $this.prop('checked') == true ? true : false;
		}else{
			prop = checkedCount == total ? true : false;
			clas = '.gosmtp-multi-check';
		}
    
		parent.find(clas).prop('checked',prop);
		
		checkedCount = jQuery('td input[type="checkbox"]:checked').length;
		if(checkedCount > 0){
			jQuery('.gosmtp-log-options').css('display','flex');
		}else{
			jQuery('.gosmtp-log-options').css('display','none');
		}
		
	});

	jQuery('body').on('click','#gosmtp-table-opt-btn',function(){
		var option = jQuery('#gosmtp-table-options').val();
		var ids = [];
    
		jQuery('#gosmtp-logs-table').find('td input[type=checkbox]:checked').each(function(){
			ids.push(jQuery(this).val());
		})

		if(ids.length == 0){
			alert('Invalid selection!');
			return;
		}

		var action = option == 'delete' ? 'gosmtp_delete_log' : '';
		
		if(action == ''){
			alert('Invalid option!');
			return;
		}

		jQuery.ajax({
			url:gosmtp_ajaxurl + 'action='+action,
			dataType : 'JSON',
			type : 'post',
			data: {
				id:ids,
				gosmtp_nonce: gosmtp_ajax_nonce
			},
			success:function(data){
				if( data.response !=undefined ){
					alert(data.response);
				}else{
					alert('Someting went wrong !');
				}
				
				window.location.reload();
			},
			error:function(){
				alert('Someting went wrong !');
			}
		});
	});
  
	// Send Test Mail
	jQuery('body').on('submit', '#smtp-test-mail', function(e){

		e.preventDefault();
		var $this = jQuery(this);
		var formData = new FormData( jQuery(this)[0] );
		formData.append('gosmtp_nonce', gosmtp_ajax_nonce);

		jQuery.ajax({
			url: gosmtp_ajaxurl + 'action=gosmtp_test_mail',
			data: formData,
			type: 'POST',
			processData: false,
			contentType: false,
			cache: false,
			beforeSend: function(){
				gosmtp_loader('show');
				jQuery('#send_mail').attr('type', 'button');
				var btnhtml = `<i class="dashicons dashicons-update-alt"></i>&nbsp;Sending&nbsp;`;
				$this.find('#send_mail').html(btnhtml);
			},
			success: function( res ){
				gosmtp_loader('hide');
				$this.find('#send_mail').html('Send Mail');
				jQuery('#send_mail').attr('type', 'submit');
				res = gosmtp_isJSON(res);
				
				if(!res){
					alert('Someting went wrong !');
					return false;
				}

				if( res.error != undefined){
					alert(res.error);
					return false;
				}
				
				alert('Mail sent successfully!');
			},
			error: function(){
				gosmtp_loader('hide');
				alert('Mail not sent!');
				jQuery('#send_mail').attr('type','submit');
				$this.find('#send_mail').html('Send Mail');
			}
		});
	});

	jQuery('.gosmtp-mailer-input').find('.mail_active').closest('.gosmtp-mailer-input').click();
	
	// Handle reload and retry events
	jQuery('body').on('click', '.gosmtp-resend, .gosmtp-retry, .gosmtp-pupup-retry, .gosmtp-pupup-resend', function(){
		
		var $this = jQuery(this);
		var isDialog = $this.hasClass('gosmtp-pupup-resend') || $this.hasClass('gosmtp-pupup-retry') ? true : false;
		var mail_id = jQuery(this).attr('data-id') != undefined ? jQuery(this).attr('data-id') : '';
		var operation = jQuery(this).hasClass('gosmtp-resend') == true ? 'resend' : 'retry';
		var className = '';
    
		jQuery.ajax({
			url:gosmtp_ajaxurl + 'action=gosmtp_resend_mail',
			dataType : 'JSON',
			type : 'post',
			data: {
				id:mail_id,
				gosmtp_nonce: gosmtp_ajax_nonce,
				operation: operation
			},
			beforeSend:function(){
				gosmtp_loader('show');
				$this.addClass('gosmtp-resend-process');
			},
			success:function( res ){
				gosmtp_loader('hide');

				if(isDialog){
					className = $this.hasClass('gosmtp-pupup-retry') ? 'gosmtp-pupup-retry' : 'gosmtp-pupup-resend';
				}else{
					className = $this.hasClass('gosmtp-pupup-retry') ? 'gosmtp-pupup-retry' : 'gosmtp-pupup-resend';
				}
				
				var dialog_icon = "";

				$this.removeClass(className);
				$this.removeClass('gosmtp-resend-process');
				
				if(res.error != undefined){
					$this.html('<i class="dashicons dashicons-update-alt"></i><span>Retry</span>');
					$this.addClass('gosmtp-retry');
					dialog_icon = '<i class="failed dashicons dashicons-warning"></i>';
					alert( res.error );
				}else{
					$this.html('<i class="dashicons dashicons-image-rotate"></i><span>Resend</span>');
					dialog_icon = '<i class="sent dashicons dashicons-yes-alt"></i>';
					$this.addClass('gosmtp-resend');
					alert( res.response );
				}
				
				if(isDialog){
					jQuery('.gosmtp-dialog-header').find('.gosmtp-status-icon').html(dialog_icon);
				}

				window.location.reload();

			},
			error:function(){
				gosmtp_loader('hide');
				alert('Someting went wrong !');
			}
		});
	});
	
	// Handle delete events
	jQuery('body').on('click','.gosmtp-mail-delete',function(){

		var mail_id = jQuery(this).attr('data-id') != undefined ? jQuery(this).attr('data-id') : '';
		var parent = jQuery(this).parent().parent();
		jQuery.ajax({
			url:gosmtp_ajaxurl + 'action=gosmtp_delete_log',
			dataType : 'JSON',
			type : 'post',
			data: {
				id:mail_id,
				gosmtp_nonce: gosmtp_ajax_nonce
			},
			success:function(data){
				if( data.response !=undefined ){
					alert(data.response);
					window.location.reload();
				}else{
					alert('Someting went wrong !');
				}
			},
			error:function(){
				alert('Someting went wrong !');
			}
		});
	});

	// GoSMTP mail info popup
	jQuery('body').on('click','.gosmtp-mail-details', function(){

		var dialog = jQuery('#gosmtp-logs-dialog');
		
		var dialog_icon = dialog.find('.gosmtp-dialog-header').find('.gosmtp-status-icon');
		var mail_id = jQuery(this).attr('data-id') != undefined ? jQuery(this).attr('data-id') : '';

		jQuery.ajax({
			url : gosmtp_ajaxurl + 'action=gosmtp_get_log',
			dataType : 'JSON',
			type : 'post',
			data: {
				'gosmtp_nonce' : gosmtp_ajax_nonce,
				id: mail_id
			},
			beforeSend : function(){
				gosmtp_loader('show');
			},
			success : function( res ){				
				if(res.response.data != undefined){
					var resp = res.response.data;
					var headers = resp.headers != undefined ? resp.headers : '';
		  
					if(typeof headers == 'object' && Object.keys(headers).length > 0){
						headers_ = JSON.stringify(headers, null, 3);
						dialog.find('.gosmtp-log-headers').html('<pre>'+headers_+'</pre>');
					}

					var attachments = resp.attachments != undefined ? resp.attachments : '';
					var attachments_count = 0;
					if(typeof attachments == 'object' && Object.keys(attachments).length > 0){
						var attachments_ = JSON.stringify(attachments, null, 3);
						dialog.find('.gosmtp-log-attachments').html('<pre>'+attachments_+'</pre>');
						attachments_count = attachments.length;
					}
					dialog.find('.gosmtp-attachment-count').text('('+attachments_count+')');

					var response = resp.response != undefined ? resp.response : '';
					if(typeof response == 'object' && Object.keys(response).length > 0){
						response = JSON.stringify(response, null, 3);
					}
					dialog.find('.gosmtp-log-response').html('<pre>'+response+'</pre>');

					var to = resp.to != undefined ? resp.to : 'NA';
					dialog.find('.gosmtp-message-tos').text(to);

					var from = resp.from != undefined ? resp.from : 'NA';
					dialog.find('.gosmtp-message-from').text(from);

					var subject = resp.subject != undefined ? resp.subject : 'NA';
					dialog.find('.gosmtp-message-subject').text(subject);

					var created = resp.created != undefined ? resp.created : 'NA';
					dialog.find('.gosmtp-message-created').text(created);

					var mailer = resp.mailer != undefined ? resp.mailer : 'NA';
					dialog.find('.gosmtp-message-mailer').text(mailer);

					var body = resp.body != undefined ? resp.body : 'NA';
					dialog.find('.gosmtp-message-body').text(body);

					var status_content = 'NA';
					if(resp.status != undefined){
						var status = resp.status;
						var icon = '<i class="'+(status.toLowerCase())+' dashicons '+(status == 'Sent' ? 'dashicons-yes-alt' : 'dashicons-warning')+'"></i>';
						dialog_icon.html(icon);
						var resend_retry = status == 'Sent' ? 'Resend' : 'Retry';
						var rr_html = `<button type="button" data-id="`+mail_id+`" class="gosmtp-pupup-`+resend_retry.toLowerCase()+`">
							<i class="dashicons `+( resend_retry == 'Retry' ? 'dashicons-update-alt' : 'dashicons-image-rotate' )+`"></i>
							<span>`+resend_retry+`</span>
						</button>`;
						jQuery('.gosmtp-dialog-actions').html(rr_html)
					}
				}
				jQuery('body').css('overflow','hidden');
				gosmtp_loader('hide');
				dialog.fadeIn();
			},
			error:function(){
				gosmtp_loader('hide');
				alert('Someting went wrong !');
			}
		});
	});
	
	// GoSMTP test mail popup
	jQuery('body').on('click','#gosmtp-testmail-btn', function(){
		jQuery('body').css('overflow','hidden');
		var dialog = jQuery('#gosmtp-testmail-dialog');
		dialog.fadeIn();
	});

	jQuery('.gosmtp-dialog,.gosmtp-dialog-close').on('click',function(e){
		// $this = jQuery(this);
		if(e.currentTarget.classList[0] == 'gosmtp-dialog-close' || e.target.classList[0] == 'gosmtp-dialog'){
			jQuery('.gosmtp-dialog').fadeOut();
			jQuery('body').css('overflow','auto');
		}
	});

	// GoSMTP accordion
	jQuery('.gosmtp-accordion-header').on('click',function(e){
		jQuery(this).parent().toggleClass("gosmtp-accordion-open")
		jQuery(this).parent().find('.gosmtp-accordion-content').slideToggle();
	});
	
	// Scrolling event on mailer click
	jQuery('body').on('click', '.mailer', function(e){
		var mailer_container = jQuery(this).closest('tr');
		jQuery(mailer_container).get(0).scrollIntoView({behavior: "smooth", inline: "nearest"});
	});

});

function gosmtp_isJSON(str) {
	try {
		var obj = JSON.parse(str);
		return obj;
	} catch (e) {
		return false;
	}
}

function gosmtp_copy_url(id){

	var copyText = jQuery("#" +id);
	var copyMessage = jQuery("." +id);

	// Select the text field
	copyText.select();
	
	// Show Message after Coppied
	copyMessage.slideDown(500);
	
	// Copy the text inside the text field
	navigator.clipboard.writeText(copyText.val());
	
	// Hide Message after 3 second
	setTimeout(function(){
		copyMessage.slideUp(500);
	}, 3000);

}

function gosmtp_loader(option = ''){
	var config = option == 'show' ? 'flex' : 'none';
	jQuery('.gosmtp-loader').css('display', config)
}
