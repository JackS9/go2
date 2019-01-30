/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2013 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
(function($) {
	var ajaxInfo = {hash: '', encKey: 'eiorusfj23', history: false, intervalId: '', interval: 100, url: '',ajaxLoaderImage: 'ajaxLoader1', async: true};	
	$.extend({
		setAjaxInfo: function(info) {
			ajaxInfo = $.extend(ajaxInfo,info);
			if(ajaxInfo.history && ajaxInfo.intervalId.length == 0) {
				ajaxInfo.intervalId = setInterval('$.checkForHashChange()',ajaxInfo.interval);
			}
			else if(!ajaxInfo.history && ajaxInfo.intervalId > 0) {
				clearInterval(ajaxInfo.intervalId);
			}
		},
		
		sendAjaxRequest: function(action,info,options) {
			$('#'+ajaxInfo.ajaxLoaderImage).css('display','block');
			options = options == null ? new Object : options;
			options.url = options.url == null ? ajaxInfo.url : options.url;
			options.async = options.async == null ? ajaxInfo.async : options.async;
			options.updateHistory = options.updateHistory == null ? false : options.updateHistory;
			if(ajaxInfo.history && options.updateHistory) {
				ajaxInfo.hash = $.encrypt($.toJSON({info: info, action: action, url: options.url}),ajaxInfo.encKey);
				//ajaxInfo.hash = $.encrypt($.toJSON({info: info, action: action}),ajaxInfo.encKey);
				window.location.hash = ajaxInfo.hash;
			}
			$.ajax({
				url: options.url,
				type: "POST",
				data: {info: info, action: action},
				dataType: "json",
				async: options.async,
				error: function (xhr, desc, exceptionobj) {
					//alert(desc);
					//alert(exceptionobj);
					//alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
				    //alert("responseText: "+xhr.responseText);

					// Make sure the response text has a length. This suppresses empty alert boxes when a user leaves a page and there is an unfinished ajax request.
					if(xhr.responseText.length > 0) {
						alert(xhr.responseText);
					}
				},
				complete: function(xmlRequest,textStatus) {
					$('#'+ajaxInfo.ajaxLoaderImage).css('display','none');
				},
				success: function (json) {
					if (json.error) { alert(json.error); return; }
					$.each(json, function(i,retArr) {
						$.displayAjaxData(retArr);
					});
				}
			});
		},
		
		sendAjaxForm: function(action,form) {
			$.sendAjaxRequest(action, $(form).serialize());
		},
		
		displayAjaxData: function(retArr) {
			if(retArr[0] == 'html') {
				$(retArr[1]).html(retArr[2]);
			}
			else if(retArr[0] == 'val') {
				$(retArr[1]).val(retArr[2]);
			}
			else if(retArr[0] == 'append') {
				$(retArr[1]).append(retArr[2]);
			}
			else if(retArr[0] == 'prepend') {
				$(retArr[1]).prepend(retArr[2]);
			}
			else if(retArr[0] == 'after') {
				$(retArr[1]).after(retArr[2]);
			}
			else if(retArr[0] == 'before') {
				$(retArr[1]).before(retArr[2]);
			}
			else if(retArr[0] == 'remove') {
				$(retArr[1]).remove();
			}
			else if(retArr[0] == 'script') {
				eval(retArr[1]);
			}
			else {
				alert(retArr);
			}
		},
		
		checkForHashChange: function() {
			var newHash = window.location.hash;
			newHash = newHash.replace("#","");
			if(newHash.length == 0) {
				//alert('i am here');
			}
			if(newHash != ajaxInfo.hash && newHash.length > 0) {
				ajaxInfo.hash = newHash;
				var data = $.evalJSON($.decrypt(newHash,ajaxInfo.encKey));
				$.sendAjaxRequest(data.action,data.info,{url:data.url});
			}
		},
		
		encrypt: function(str, pwd) {
			if(pwd == null || pwd.length <= 0) {
				alert("Please enter a password with which to encrypt the message.");
				return null;
			}
			var prand = "";
			for(var i=0; i<pwd.length; i++) {
				prand += pwd.charCodeAt(i).toString();
			}
			var sPos = Math.floor(prand.length / 5);
			var mult = parseInt(prand.charAt(sPos) + prand.charAt(sPos*2) + prand.charAt(sPos*3) + prand.charAt(sPos*4) + prand.charAt(sPos*5));
			var incr = Math.ceil(pwd.length / 2);
			var modu = Math.pow(2, 31) - 1;
			if(mult < 2) {
				alert("Algorithm cannot find a suitable hash. Please choose a different password. \nPossible considerations are to choose a more complex or longer password.");
				return null;
			}
			var salt = Math.round(Math.random() * 1000000000) % 100000000;
			prand += salt;
			while(prand.length > 10) {
				prand = (parseInt(prand.substring(0, 10)) + parseInt(prand.substring(10, prand.length))).toString();
			}
			prand = (mult * prand + incr) % modu;
			var enc_chr = "";
			var enc_str = "";
			for(var i=0; i<str.length; i++) {
				enc_chr = parseInt(str.charCodeAt(i) ^ Math.floor((prand / modu) * 255));
				if(enc_chr < 16) {
					enc_str += "0" + enc_chr.toString(16);
				} else enc_str += enc_chr.toString(16);
				prand = (mult * prand + incr) % modu;
			}
			salt = salt.toString(16);
			while(salt.length < 8)salt = "0" + salt;
			enc_str += salt;
			return enc_str;
		},
		
		decrypt: function(str, pwd) {
			if(str == null || str.length < 8) {
				// Suppress warning message if string is too short.
				//alert("A salt value could not be extracted from the encrypted message because it's length is too short. The message cannot be decrypted.");
				return;
			}
			if(pwd == null || pwd.length <= 0) {
				alert("Please enter a password with which to decrypt the message.");
				return;
			}
			var prand = "";
			for(var i=0; i<pwd.length; i++) {
				prand += pwd.charCodeAt(i).toString();
			}
			var sPos = Math.floor(prand.length / 5);
			var mult = parseInt(prand.charAt(sPos) + prand.charAt(sPos*2) + prand.charAt(sPos*3) + prand.charAt(sPos*4) + prand.charAt(sPos*5));
			var incr = Math.round(pwd.length / 2);
			var modu = Math.pow(2, 31) - 1;
			var salt = parseInt(str.substring(str.length - 8, str.length), 16);
			str = str.substring(0, str.length - 8);
			prand += salt;
			while(prand.length > 10) {
				prand = (parseInt(prand.substring(0, 10)) + parseInt(prand.substring(10, prand.length))).toString();
			}
			prand = (mult * prand + incr) % modu;
			var enc_chr = "";
			var enc_str = "";
			for(var i=0; i<str.length; i+=2) {
				enc_chr = parseInt(parseInt(str.substring(i, i+2), 16) ^ Math.floor((prand / modu) * 255));
				enc_str += String.fromCharCode(enc_chr);
				prand = (mult * prand + incr) % modu;
			}
			return enc_str;
		}
	});
})(jQuery);

function enterPressed(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
	if(code == 13) {
		return true;
	}
	return false;
}
