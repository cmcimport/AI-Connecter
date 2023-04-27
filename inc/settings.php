<?php // inc/settings.php

function aiconn_settings_init() {
    register_setting('aiconn', 'aiconn_api_key');
	register_setting('aiconn', 'aiconn_api_key');
    register_setting('aiconn', 'aiconn_enable_chatbot');
    register_setting('aiconn', 'aiconn_default_message');
    register_setting('aiconn', 'aiconn_visual');
    register_setting('aiconn', 'aiconn_position');
    register_setting('aiconn', 'aiconn_colors');
	register_setting('aiconn', 'aiconn_support_word');
	register_setting('aiconn', 'aiconn_send_word');
	register_setting('aiconn', 'aiconn_write_msg');

    add_settings_section(
        'aiconn_settings_section',
        __('AI Connecter', 'aiconnecter'),
        'aiconn_settings_section_callback',
        'aiconn'
    );

    add_settings_field(
        'aiconn_api_key',
        __('API Key', 'aiconnecter'),
        'aiconn_api_key_render',
        'aiconn',
        'aiconn_settings_section'
    );
	
	// New setting fields
	add_settings_field(
		'aiconn_enable_chatbot',
		__('Enable Chatbot', 'aiconnecter'),
		'aiconn_enable_chatbot_render',
		'aiconn',
		'aiconn_settings_section'
	);

	add_settings_field(
		'aiconn_default_message',
		__('Default message', 'aiconnecter'),
		'aiconn_default_message_render',
		'aiconn',
		'aiconn_settings_section'
	);

	add_settings_field(
		'aiconn_visual',
		__('Style', 'aiconnecter'),
		'aiconn_visual_render',
		'aiconn',
		'aiconn_settings_section'
	);

	add_settings_field(
		'aiconn_position',
		__('Position', 'aiconnecter'),
		'aiconn_position_render',
		'aiconn',
		'aiconn_settings_section'
	);

	add_settings_field(
		'aiconn_colors',
		__('Colors', 'aiconnecter'),
		'aiconn_colors_render',
		'aiconn',
		'aiconn_settings_section'
	);
	
	add_settings_field(
		'aiconn_support_word',
		__('"Support" word', 'aiconnecter'),
		'aiconn_support_word_render',
		'aiconn',
		'aiconn_settings_section'
	);

	add_settings_field(
		'aiconn_send_word',
		__('"Send" word', 'aiconnecter'),
		'aiconn_send_word_render',
		'aiconn',
		'aiconn_settings_section'
	);
	
	add_settings_field(
		'aiconn_write_msg',
		__('Text "Write your message..."', 'aiconnecter'),
		'aiconn_write_msg_render',
		'aiconn',
		'aiconn_settings_section'
	);

}

// Add CSS to the admin page
function aiconn_admin_styles() {
    echo '<style>
        .aiconn-settings-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .aiconn-settings-block {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            width: 48%;
        }
    </style>';
}

add_action('admin_head', 'aiconn_admin_styles');
add_action('admin_init', 'aiconn_settings_init');

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

function aiconn_write_msg_render() {
    $write_word = get_option('aiconn_write_msg', __('Write your message...', 'aiconnecter'));
    ?>
    <input type="text" name='aiconn_write_msg' value="<?php echo esc_attr($write_word); ?>">
    <?php
}


function aiconn_settings_section_callback() {
    echo __('Add the API Key provided in the developer website.', 'aiconnecter');
}

function aiconn_api_key_render() {
    $api_key = aiconn_get_api_key();
    ?>
    <input type='text' name='aiconn_api_key' value='<?php echo esc_attr($api_key); ?>' size='50'>
    <?php
}

function aiconn_options_page() {
    $api_info = aiconn_check_license();

	// Render functions for new setting fields
	function aiconn_enable_chatbot_render() {
		$enable_chatbot = get_option('aiconn_enable_chatbot', 1);
		?>
		<input type='checkbox' name='aiconn_enable_chatbot' value='1' <?php checked(1, $enable_chatbot); ?>>
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
			/* 'minimal' => __('Minimal', 'aiconnecter'),
			'classic' => __('Classic', 'aiconnecter'), */
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

	
    ?>
    <div class="wrap">
		<div class="aiconn-settings-container">
			<div class="aiconn-settings-block">
				<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

				<?php if ($api_info !== false): ?>
					<div>
						<p><strong><?php _e('API Status:', 'aiconnecter'); ?></strong> <?php _e('Connected', 'aiconnecter'); ?></p>
						<p><strong><?php _e('Available Tokens:', 'aiconnecter'); ?></strong> <?php echo esc_html($api_info['tokens']); ?></p>
					</div>
				<?php else: ?>
					<div>
						<p><strong><?php _e('API Status:', 'aiconnecter'); ?></strong> <?php _e('Not connected', 'aiconnecter'); ?></p>
						<?php if ($api_info): ?>
							<pre><?php print_r($api_info); ?></pre>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<p>
					<a href="https://www.aiconnecter.com" target="_blank" class="button button-primary"><?php _e('Go to the developer website', 'aiconnecter'); ?></a>
				</p>
			</div>
		</div>
        
        <form action='options.php' method='post'>
            <?php
            settings_fields('aiconn');
	
            echo '<div class="aiconn-settings-container">';

				echo '<div class="aiconn-settings-block">';
				do_settings_sections('aiconn');
				submit_button();
				echo '</div>';
				
            echo '</div>';
            
            ?>
        </form>
    </div>
    <?php
}

function aiconn_add_admin_menu() {
    add_options_page(
        __('AI Connecter', 'aiconnecter'),
        __('AI Connecter', 'aiconnecter'),
        'manage_options',
        'aiconnecter',
        'aiconn_options_page'
    );
}
add_action('admin_menu', 'aiconn_add_admin_menu');

?>