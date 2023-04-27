jQuery(document).ready(function ($) {
    var chatBox = $('#aiconn-chat-box');
    var chatHeader = $('#aiconn-chat-header');
    var chatMessages = $('#aiconn-chat-messages');
    var chatInput = $('#aiconn-chat-text');
    var chatSendButton = $('#aiconn-chat-send');

    // Apply visual settings
    const visual = aiconn_frontend_data.visual;
    const position = aiconn_frontend_data.position;
    chatBox.addClass(`aiconn-visual-${visual}`).addClass(`aiconn-position-${position}`);

	// Set custom words
    $("#aiconn-chat-header").text(aiconn_frontend_data.support_word);
    $("#aiconn-chat-send").text(aiconn_frontend_data.send_word);
    $("#aiconn-chat-text").attr("placeholder", aiconn_frontend_data.write_msg_word);
	
    chatSendButton.on('click', function (e) {
        e.preventDefault();
        sendMessage();
    });

    chatInput.on('keydown', function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            sendMessage();
        }
    });

    chatHeader.on('click', function () {
        chatBox.toggleClass('aiconn-minimized');
    });

    function sendMessage() {
        var messageText = chatInput.val().trim();

        if (!messageText) {
            return;
        }

        chatInput.val('');
        appendMessage('user', messageText);
        getResponse(messageText);
    }

	function appendMessage(type, text) {
		var messageClass = type === 'user' ? 'aiconn-chat-message-user' : 'aiconn-chat-message-bot';
		var message = $('<div class="aiconn-chat-message ' + messageClass + '">' + text + '</div>');

		// Apply color settings
		const colors = aiconn_frontend_data.colors;
		if (type === 'user') {
			message.css('color', colors.user_message);
		} else {
			message.css('color', colors.bot_message);
		}

		chatMessages.append(message);
		chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
	}


    function getResponse(userText) {
        $.ajax({
            url: aiconn_frontend_data.ajax_url,
            method: 'POST',
            data: {
                action: 'aiconn_get_response',
                message: userText,
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    appendMessage('bot', response.data.response);
                } else {
                    console.error('Error:', response.data.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            },
        });
    }
	function showDefaultMessage() {
		const defaultMessage = aiconn_frontend_data.default_message;
		if (defaultMessage) {
			appendMessage('bot', defaultMessage);
		}
	}
	showDefaultMessage();
	
	function isMobileDevice() {
		return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
	}

	if (isMobileDevice()) {
		let chatBox = document.getElementById('aiconn-chat-box');
		let chatInput = document.getElementById('aiconn-chat-text');

		chatInput.addEventListener('focus', () => {
			chatBox.classList.add('aiconn-chat-box-raised');
		});

		chatInput.addEventListener('blur', () => {
			chatBox.classList.remove('aiconn-chat-box-raised');
		});
	}

});
