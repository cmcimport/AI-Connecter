<?php // archivo inc/settings.php
function aiconn_settings_init() {
    register_setting('aiconn', 'aiconn_api_key');

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

}
add_action('admin_init', 'aiconn_settings_init');

function aiconn_settings_section_callback() {
    echo __('Add the API Key provided in the developer website.', 'aiconnecter');
}

function aiconn_api_key_render() {
    $api_key = aiconn_get_api_key();
    ?>
    <input type='text' name='aiconn_api_key' value='<?php echo esc_attr($api_key); ?>' size='50'>
    <?php
}

function aiconn_settings_page() {
    $api_info = aiconn_check_license();

	// Render functions for new setting fields

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
?>