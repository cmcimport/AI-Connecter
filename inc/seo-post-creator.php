<?php // archivo inc/seo-post-creator.php
function aiconn_seo_post_creator_page_content() {
    ?>
    <div class="wrap">
        <h1><?php _e('SEO Post Creator', 'aiconnecter'); ?></h1>
        <?php aiconn_seo_post_creator_form(); ?>
    </div>
    <?php
}

function aiconn_seo_post_creator_page() {
    // Registro de configuraciones
    register_setting('ai_connecter_seo_post', 'aiconn_post_rules');
    register_setting('ai_connecter_seo_post', 'aiconn_post_title');
    register_setting('ai_connecter_seo_post', 'aiconn_post_keywords');
    

    // Creación de sección
    add_settings_section(
        'aiconn_seo_post_settings_section',
        __('SEO Post Creator', 'aiconnecter'),
        'aiconn_seo_post_settings_section_callback',
        'ai_connecter_seo_post'
    );

    // Creación de campos de configuración
    add_settings_field(
        'aiconn_post_rules',
        __('Post Rules', 'aiconnecter'),
        'aiconn_post_rules_render',
        'ai_connecter_seo_post',
        'aiconn_seo_post_settings_section'
    );

    add_settings_field(
        'aiconn_post_title',
        __('Post Title', 'aiconnecter'),
        'aiconn_post_title_render',
        'ai_connecter_seo_post',
        'aiconn_seo_post_settings_section'
    );

    add_settings_field(
        'aiconn_post_keywords',
        __('Post Keywords', 'aiconnecter'),
        'aiconn_post_keywords_render',
        'ai_connecter_seo_post',
        'aiconn_seo_post_settings_section'
    );
}
add_action('admin_init', 'aiconn_seo_post_creator_page');

function aiconn_seo_post_settings_section_callback() {
    echo __('Generate SEO-optimized posts using the AI Connecter service.', 'aiconnecter');
}
function aiconn_post_rules_render() {
    $post_rules = get_option('aiconn_post_rules', '');
    ?>
    <input type='text' name='aiconn_post_rules' value='<?php echo esc_attr($post_rules); ?>' size='50'>
    <p class="description"><?php _e('Example: "Write a 550-word article about this product." or "Write a review about this services"', 'aiconnecter'); ?></p>
    <?php
}

function aiconn_post_title_render() {
    $post_title = get_option('aiconn_post_title', '');
    ?>
    <input type='text' name='aiconn_post_title' value='<?php echo esc_attr($post_title); ?>' size='50'>
    <?php
}

function aiconn_post_keywords_render() {
    $post_keywords = get_option('aiconn_post_keywords', '');
    ?>
    <input type='text' name='aiconn_post_keywords' value='<?php echo esc_attr($post_keywords); ?>' size='50'>
    <p class="description"><?php _e('Separate keywords with commas.', 'aiconnecter'); ?></p>
    <?php
}

function aiconn_seo_post_creator_form() {
    // Verificar si se envió el formulario
    if (isset($_POST['aiconn_create_post']) && check_admin_referer('aiconn_create_seo_post_draft')) {
        // Recuperar las opciones de configuración desde el formulario
        $post_title = sanitize_text_field($_POST['aiconn_post_title']);
        $post_keywords = sanitize_text_field($_POST['aiconn_post_keywords']);
        $post_rules = sanitize_text_field($_POST['aiconn_post_rules']);

        // Crear el post como borrador y recuperar el ID del post creado
        $post_id = aiconn_create_seo_post_draft($post_title, $post_keywords, $post_rules);

        // Mostrar un mensaje de éxito
        echo '<div class="notice notice-success is-dismissible"><p>';
        printf(__('Post created successfully as draft. <a href="%s">Edit Post</a>', 'aiconnecter'), admin_url('post.php?post=' . $post_id . '&action=edit'));
        echo '</p></div>';
    }

    ?>
    <div class="wrap">
        <div class="aiconn-settings-container">
            <div class="aiconn-settings-block">
                <form method="post" action="">
                   <?php
                    // Mostrar los campos de configuración
                    settings_fields('ai_connecter_seo_post');
                    do_settings_sections('ai_connecter_seo_post');

                    // Añadir un campo nonce
                    wp_nonce_field('aiconn_create_seo_post_draft');

                    // Añadir el botón para crear el post
                    ?>
                    <p>
                        <input type="submit" name="aiconn_create_post" class="button button-primary" value="<?php _e('Create Post as Draft', 'aiconnecter'); ?>">
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
}

function aiconn_create_seo_post_draft($post_title, $post_keywords, $post_rules) {

    $prompt = $post_rules . '. ' . $post_title . '. ' . $post_keywords;

    // error_log('Prompt (WordPress backend): ' . $prompt);

    // Obtén el contenido del post a través de la API usando $post_rules
    $api_key = aiconn_get_api_key();
    $post_content = aiconn_get_post_response($api_key, $prompt);

    // Si no se obtuvo contenido, retorna false
    if (!$post_content) {
        return false;
    }

    
    // Crear un nuevo post
    $post_data = array(
        'post_title' => $post_title,
        'post_content' => $post_content,
        'post_status' => 'draft',
        'post_type' => 'post',
        'post_author' => get_current_user_id()
    );

    // Insertar el post como borrador
    $post_id = wp_insert_post($post_data);

    // Añadir las palabras clave como taxonomía personalizada o meta data
    // Separar las palabras clave por comas antes de añadirlas como etiquetas (tags)
    wp_set_object_terms($post_id, $post_keywords, 'post_tag');

    return $post_id;
}
