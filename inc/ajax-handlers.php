<?php
/**
 * RIFF LP Theme - AJAX ハンドラー
 *
 * @package RiffLP
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// セクション並び順 保存
add_action( 'wp_ajax_riff_lp_save_order', function() {
    check_ajax_referer( 'riff_lp_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'forbidden' );

    $order = array_map( 'sanitize_key', $_POST['order'] ?? [] );
    update_option( 'riff_lp_section_order', $order );
    wp_send_json_success( [ 'message' => '並び順を保存しました' ] );
} );

// セクション表示/非表示 切り替え
add_action( 'wp_ajax_riff_lp_toggle_section', function() {
    check_ajax_referer( 'riff_lp_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'forbidden' );

    $section = sanitize_key( $_POST['section'] ?? '' );
    $enabled = (bool) ( $_POST['enabled'] ?? false );
    update_option( "riff_lp_section_enabled_{$section}", $enabled );
    wp_send_json_success( [ 'section' => $section, 'enabled' => $enabled ] );
} );
