<?php // archivo inc/seo-post-creator.php
function aiconn_seo_post_creator_page_content()
{
    ?>
    <div class="wrap">
        <h1>
            <?php _e('SEO Post Creator', 'aiconnecter'); ?>
        </h1>
        <?php aiconn_seo_post_creator_form(); ?>
    </div>
    <?php
}

function aiconn_seo_post_creator_page()
{
    // Registro de configuraciones
    register_setting('ai_connecter_seo_post', 'aiconn_post_description');

    // Creación de sección
    add_settings_section(
        'aiconn_seo_post_settings_section',
        __('SEO Post Creator', 'aiconnecter'),
        'aiconn_seo_post_settings_section_callback',
        'ai_connecter_seo_post'
    );

    // Creación de campos de configuración
    add_settings_field(
        'aiconn_post_description',
        __('Post Description', 'aiconnecter'),
        'aiconn_post_description_render',
        'ai_connecter_seo_post',
        'aiconn_seo_post_settings_section'
    );
}
add_action('admin_init', 'aiconn_seo_post_creator_page');

function aiconn_seo_post_settings_section_callback()
{
    echo __('Generate SEO-optimized posts using the AI Connecter service.', 'aiconnecter');
}
function aiconn_post_description_render()
{
    $post_description = get_option('aiconn_post_description', '');
    ?>
    <textarea name='aiconn_post_description' rows='5' cols='50'><?php echo esc_textarea($post_description); ?></textarea>
    <p class="description">
        <?php _e('Provide a detailed description for the post, including title, keywords, and any specific instructions.', 'aiconnecter'); ?>
    </p>
    <?php
}

function aiconn_seo_post_creator_form()
{
    // Verificar si se envió el formulario
    if (isset($_POST['aiconn_create_post']) && check_admin_referer('aiconn_create_seo_post_draft')) {
        // Recuperar las opciones de configuración desde el formulario
        $post_description = sanitize_textarea_field($_POST['aiconn_post_description']);

        // Crear el post como borrador y recuperar el ID del post creado
        $post_id = aiconn_create_seo_post_draft($post_description);

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
                        <input type="submit" name="aiconn_create_post" class="button button-primary"
                            value="<?php _e('Create Post as Draft', 'aiconnecter'); ?>">
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
}

function aiconn_create_seo_post_draft($post_description)
{
    $prompt = $post_description;

    // error_log('Prompt (WordPress backend): ' . $prompt);

    // Obtén el contenido del post a través de la API usando $post_description
    $api_key = aiconn_get_api_key();
    $post_content = aiconn_get_post_response($api_key, $prompt);

    // Si no se obtuvo contenido, retorna false
    if (!$post_content) {
        return false;
    }

    // Extraer el título del post desde $post_description
    preg_match('/(?<=\. ).*(?=\.)|(?<=\. ).*(?=\,)|(?<=\, ).*(?=\,)|(?<=\, ).*(?=\.)/', $post_description, $matches);
    $post_title = !empty($matches) ? $matches[0] : 'Untitled';

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

    // Extraer las palabras clave desde $post_description
    preg_match_all('/(?<=\,).*(?=\,)|(?<=\,).*(?=\.)|(?<=\,).*(?=\.)/', $post_description, $keyword_matches);
    $keywords = !empty($keyword_matches[0]) ? array_map('trim', $keyword_matches[0]) : array();

    // Añadir las palabras clave como taxonomía personalizada o meta data
    wp_set_object_terms($post_id, $keywords, 'post_tag');

    return $post_id;

}