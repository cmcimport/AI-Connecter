<?php

function aiconn_api_url() {
    return 'https://www.aiconnecter.com/check_license.php';
}

function aiconn_get_api_key() {
    return get_option('aiconn_api_key', '');
}

function aiconn_check_license() {
    $api_key = aiconn_get_api_key();
    $url = aiconn_api_url();
    $response = wp_remote_post($url, [
        'body' => ['api_key' => $api_key]
    ]);

    $body = json_decode(wp_remote_retrieve_body($response), true);

    return isset($body['success']) && $body['success'] === 'Información de la API obtenida correctamente.' ? $body : false;
}
function aiconn_get_chat_response($api_key, $message) {
    $api_url = 'https://www.aiconnecter.com/handle_chat_request.php';
    $response = wp_remote_post($api_url, [
        'body' => ['api_key' => $api_key, 'message' => $message]
    ]);

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['error']) && !empty($body['error'])) {
        return false;
    }

    if (isset($body['response']) && !empty($body['response'])) {
        return $body['response'];
    }

    return false;
}

function aiconn_get_post_response($api_key, $message) {
    $api_url = 'https://www.aiconnecter.com/handle_post_request.php';
    $response = wp_remote_post($api_url, [
        'body' => ['api_key' => $api_key, 'message' => $message]
    ]);

    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    error_log('Response Body: ' . print_r($body, true));  // Agregue esta línea

    if (isset($body['error']) && !empty($body['error'])) {
        error_log('API Error: ' . $body['error']);  // Agregue esta línea
        return false;
    }

    if (isset($body['response']) && !empty($body['response'])) {
        return $body['response'];
    }

    return false;
}

