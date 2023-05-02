<?php // archivo inc/chatbot.php

function aiconn_chatbot_page() {
    register_setting('ai_connecter_chatbot', 'aiconn_enable_chatbot');
    register_setting('ai_connecter_chatbot', 'aiconn_default_message');
    register_setting('ai_connecter_chatbot', 'aiconn_visual');
    register_setting('ai_connecter_chatbot', 'aiconn_position');
    register_setting('ai_connecter_chatbot', 'aiconn_colors');
	register_setting('ai_connecter_chatbot', 'aiconn_support_word');
	register_setting('ai_connecter_chatbot', 'aiconn_send_word');
	register_setting('ai_connecter_chatbot', 'aiconn_write_msg');
    

    add_settings_section(
        'aiconn_chatbot_settings_section',
        __('AI Connecter Chatbot', 'aiconnecter'),
        'aiconn_chatbot_settings_section_callback',
        'ai_connecter_chatbot'
    );
    
	add_settings_field(
		'aiconn_enable_chatbot',
		__('Enable Chatbot', 'aiconnecter'),
		'aiconn_enable_chatbot_render',
		'ai_connecter_chatbot',
		'aiconn_chatbot_settings_section'
	);

	add_settings_field(
		'aiconn_default_message',
		__('Default message', 'aiconnecter'),
		'aiconn_default_message_render',
		'ai_connecter_chatbot',
		'aiconn_chatbot_settings_section'
	);

	add_settings_field(
		'aiconn_visual',
		__('Style', 'aiconnecter'),
		'aiconn_visual_render',
		'ai_connecter_chatbot',
		'aiconn_chatbot_settings_section'
	);

	add_settings_field(
		'aiconn_position',
		__('Position', 'aiconnecter'),
		'aiconn_position_render',
		'ai_connecter_chatbot',
		'aiconn_chatbot_settings_section'
	);

	add_settings_field(
		'aiconn_colors',
		__('Colors', 'aiconnecter'),
		'aiconn_colors_render',
		'ai_connecter_chatbot',
		'aiconn_chatbot_settings_section'
	);
	
	add_settings_field(
		'aiconn_support_word',
		__('"Support" word', 'aiconnecter'),
		'aiconn_support_word_render',
		'ai_connecter_chatbot',
		'aiconn_chatbot_settings_section'
	);

	add_settings_field(
		'aiconn_send_word',
		__('"Send" word', 'aiconnecter'),
		'aiconn_send_word_render',
		'ai_connecter_chatbot',
		'aiconn_chatbot_settings_section'
	);
	
	add_settings_field(
		'aiconn_write_msg',
		__('Text "Write your message..."', 'aiconnecter'),
		'aiconn_write_msg_render',
		'ai_connecter_chatbot',
		'aiconn_chatbot_settings_section'
	);
}
add_action('admin_init', 'aiconn_chatbot_page');
function aiconn_chatbot_settings_section_callback() {
    echo __('Configure your AI Connecter Chatbot settings here.', 'aiconnecter');
}
// Render functions for new setting fields
function aiconn_enable_chatbot_render() {
    $enable_chatbot = get_option('aiconn_enable_chatbot', 1);
    ?>
    <input type='checkbox' name='aiconn_enable_chatbot' value='1' <?php checked(1, $enable_chatbot); ?>>
    <?php
}

function aiconn_support_word_render() {
    $support_word = get_option('aiconn_support_word', __('Support', 'aiconnecter'));
    ?>
    <input type='text' name='aiconn_support_word' value='<?php echo esc_attr($support_word); ?>' size='30'>
    <?php
}
function aiconn_send_word_render() {
    $send_word = get_option('aiconn_send_word', __('Send', 'aiconnecter'));
    ?>
    <input type='text' name='aiconn_send_word' value='<?php echo esc_attr($send_word); ?>' size='30'>
    <?php
}

function aiconn_default_message_render() {
    $default_message = get_option('aiconn_default_message', __('Welcome to our AI support.', 'aiconnecter'));
    ?>
    <input type='text' name='aiconn_default_message' value='<?php echo esc_attr($default_message); ?>' size='80'>
    <?php
}

function aiconn_visual_render() {
    $visual = get_option('aiconn_visual', 'default');
    $visual_options = array(
        'default' => __('Default', 'aiconnecter')
    );
    ?>
    <select name='aiconn_visual'>
        <?php foreach ($visual_options as $value => $label): ?>
            <option value='<?php echo esc_attr($value); ?>' <?php selected($value, $visual); ?>><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    </select>
    <?php
}

function aiconn_position_render() {
    $position = get_option('aiconn_position', 'bottom_right');
    $position_options = array(
        'bottom_right' => __('Bottom Right', 'aiconnecter'),
        'bottom_left' => __('Bottom Left', 'aiconnecter'),
    );
    ?>
    <select name='aiconn_position'>
        <?php foreach ($position_options as $value => $label): ?>
            <option value='<?php echo esc_attr($value); ?>' <?php selected($value, $position); ?>><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    </select>
    <?php
}

function aiconn_write_msg_render() {
    $write_word = get_option('aiconn_write_msg', __('Write your message...', 'aiconnecter'));
    ?>
    <input type="text" name='aiconn_write_msg' value="<?php echo esc_attr($write_word); ?>">
    <?php
}

function aiconn_colors_render() {
    $colors = get_option('aiconn_colors', array(
        'header' => '#0073aa',
        'header_text' => '#ffffff',
        'user_message' => '#0073aa',
        'bot_message' => '#444444',
        'send_button' => '#0073aa',
        'send_button_text' => '#ffffff',
    ));
    ?>
    <label>
        <?php _e('Header:', 'aiconnecter'); ?>
        <input type='color' name='aiconn_colors[header]' value='<?php echo esc_attr($colors['header']); ?>'>
    </label>
    <br>
    <label>
        <?php _e('Header Text:', 'aiconnecter'); ?>
        <input type='color' name='aiconn_colors[header_text]' value='<?php echo esc_attr($colors['header_text']); ?>'>
    </label>
    <br>
    <label>
        <?php _e('User Message:', 'aiconnecter'); ?>
        <input type='color' name='aiconn_colors[user_message]' value='<?php echo esc_attr($colors['user_message']); ?>'>
    </label>
    <br>
    <label>
        <?php _e('Bot Message:', 'aiconnecter'); ?>
        <input type='color' name='aiconn_colors[bot_message]' value='<?php echo esc_attr($colors['bot_message']); ?>'>
    </label>
    <br>
        <?php _e('Send Button:', 'aiconnecter'); ?>
    <input type='color' name='aiconn_colors[send_button]' value='<?php echo esc_attr($colors['send_button']); ?>'>
    <br>
    <?php _e('Send Button Text:', 'aiconnecter'); ?>
    <input type='color' name='aiconn_colors[send_button_text]' value='<?php echo esc_attr($colors['send_button_text']); ?>'>
    <?php
}


function aiconn_chatbot_options_page() {
    $api_info = aiconn_check_license();
    ?>

    <div class="wrap">
    <div class="aiconn-settings-container">
        <div class="aiconn-settings-block">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <?php if ($api_info !== false): ?>
                <form action='options.php' method='post'>
                <?php
                    settings_fields('ai_connecter_chatbot');
                    do_settings_sections('ai_connecter_chatbot');
                    submit_button();
                ?>
                </form>
            <?php else: ?>
                <div>
                    <p><strong><?php _e('API Status:', 'aiconnecter'); ?></strong> <?php _e('Not connected', 'aiconnecter'); ?></p>
                    <?php if ($api_info): ?>
                        <pre><?php print_r($api_info); ?></pre>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php  }