<?php // inc/frontend.php

function aiconn_enqueue_frontend_assets() {
    wp_enqueue_script('aiconn-frontend', plugin_dir_url(__FILE__) . '../assets/js/frontend.js', array('jquery'), false, true);

    $ajax_url = admin_url('admin-ajax.php');
    wp_localize_script('aiconn-frontend', 'aiconn_frontend_data', array(
        'ajax_url' => $ajax_url,
        'default_message' => get_option('aiconn_default_message'),
        'visual' => get_option('aiconn_visual'),
        'position' => get_option('aiconn_position'),
        'colors' => get_option('aiconn_colors'),
		'support_word' => get_option('aiconn_support_word', __('Soporte', 'aiconn-support-plugin')),
        'send_word' => get_option('aiconn_send_word', __('Enviar', 'aiconn-support-plugin')),
        'write_msg_word' => get_option('aiconn_write_msg', __('Escriba su mensaje...', 'aiconn-support-plugin'))
    ));
}

add_action('wp_enqueue_scripts', 'aiconn_enqueue_frontend_assets');

function aiconn_enqueue_styles() {
    $visual = get_option('aiconn_visual', 'default');

    if ($visual === 'default') {
        wp_enqueue_style('aiconn-default', plugin_dir_url(__FILE__) . '../assets/css/default.css');
    } elseif ($visual === 'minimal') {
        wp_enqueue_style('aiconn-minimal', plugin_dir_url(__FILE__) . '../assets/css/minimal.css');
    } elseif ($visual === 'classic') {
        wp_enqueue_style('aiconn-classic', plugin_dir_url(__FILE__) . '../assets/css/classic.css');
    }

	// Custom colors
	$colors = get_option('aiconn_colors', array(
        'header' => '#0073aa',
        'header_text' => '#ffffff',
        'user_message' => '#0073aa',
        'bot_message' => '#444444',
        'send_button' => '#0073aa',
        'send_button_text' => '#ffffff',
    ));
	$custom_css = "
        #aiconn-chat-header { background-color: {$colors['header']}; color: {$colors['header_text']}; }
        .aiconn-chat-message-user { color: {$colors['user_message']} }
        .aiconn-chat-message-bot { color: {$colors['bot_message']} }
        #aiconn-chat-send { background-color: {$colors['send_button']}; color: {$colors['send_button_text']} }
    ";
    wp_add_inline_style('aiconn-default', $custom_css);
	wp_add_inline_style('aiconn-' . $visual, $custom_css);
}
add_action('wp_enqueue_scripts', 'aiconn_enqueue_styles');

function aiconn_add_chat_box() {
    if (get_option('aiconn_enable_chatbot', 1)) {
        $position = get_option('aiconn_position', 'bottom_right');
        $position_class = 'aiconn-' . esc_attr($position);
    ?>
    <div id="aiconn-chat-box" class="<?php echo $position_class; ?>">
        <div id="aiconn-chat-header"></div>
        <div id="aiconn-chat-messages"></div>
        <div id="aiconn-chat-input">
            <input type="text" id="aiconn-chat-text" placeholder="">
            <button id="aiconn-chat-send"></button>
        </div>
    </div>
    <?php }
}
add_action('wp_footer', 'aiconn_add_chat_box', 10);

function aiconn_handle_ajax_request() {
    $api_key = get_option('aiconn_api_key');
    $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';

    $response = aiconn_get_chat_response($api_key, $message);

    if ($response) {
        wp_send_json_success(['response' => $response]);
    } /* else {
        $log_message = 'Error. API Key: ' . $api_key . ', Message: ' . $message;
        error_log($log_message);
        wp_send_json_error(['message' => __('Error chat', 'aiconn-support-plugin')]);
    } */
}

add_action('wp_ajax_aiconn_get_response', 'aiconn_handle_ajax_request');
add_action('wp_ajax_nopriv_aiconn_get_response', 'aiconn_handle_ajax_request');