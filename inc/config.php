<?php // archivo inc/config.php

function aiconn_create_menu() {
    add_menu_page(
        __('AI Connecter', 'aiconnecter'),
        __('AI Connecter', 'aiconnecter'),
        'manage_options',
        'ai_connecter',
        'aiconn_settings_page',
        'dashicons-format-chat',
        6
    );

    add_submenu_page(
        'ai_connecter',
        __('Settings', 'aiconnecter'),
        __('Settings', 'aiconnecter'),
        'manage_options',
        'ai_connecter'
    );

    add_submenu_page(
        'ai_connecter',
        __('Chatbot', 'aiconnecter'),
        __('Chatbot', 'aiconnecter'),
        'manage_options',
        'ai_connecter_chatbot',
        'aiconn_chatbot_options_page'
    );

    add_submenu_page(
        'ai_connecter',
        __('SEO Post Creator', 'aiconnecter'),
        __('SEO Post Creator', 'aiconnecter'),
        'manage_options',
        'ai_connecter_seo_post',
        'aiconn_seo_post_creator_page_content'
    );
}
add_action('admin_menu', 'aiconn_create_menu');

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
