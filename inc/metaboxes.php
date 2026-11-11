<?php
/**
 * RIFF LP Theme - カスタムメタボックス
 *
 * @package RiffLP
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// 固定ページ カスタムCSS メタボックス
// ============================================================
function riff_lp_page_css_metabox_register() {
    add_meta_box(
        'riff_lp_page_css',
        'カスタムCSS（このページのみ）',
        'riff_lp_page_css_metabox_render',
        'page',
        'normal',
        'low'
    );
}
add_action( 'add_meta_boxes', 'riff_lp_page_css_metabox_register' );

function riff_lp_page_css_metabox_render( $post ) {
    wp_nonce_field( 'riff_lp_page_css_save', 'riff_lp_page_css_nonce' );
    $css = get_post_meta( $post->ID, '_riff_lp_page_css', true );
    ?>
    <p style="margin:0 0 8px;color:#666;font-size:12px;">
        このページにのみ適用されるCSSを記述してください。セレクタも含めて入力してください。
    </p>
    <textarea
        id="riff_lp_page_css"
        name="riff_lp_page_css"
        rows="12"
        style="width:100%;font-family:monospace;font-size:13px;line-height:1.6;resize:vertical;border:1px solid #ddd;border-radius:4px;padding:10px;box-sizing:border-box;"
        placeholder=".my-section { color: red; }&#10;&#10;.hero-title { font-size: 2rem; }"
    ><?php echo esc_textarea( $css ); ?></textarea>
    <?php
}

function riff_lp_page_css_metabox_save( $post_id ) {
    if ( ! isset( $_POST['riff_lp_page_css_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['riff_lp_page_css_nonce'], 'riff_lp_page_css_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $css = isset( $_POST['riff_lp_page_css'] ) ? wp_strip_all_tags( wp_unslash( $_POST['riff_lp_page_css'] ) ) : '';

    if ( $css !== '' ) {
        update_post_meta( $post_id, '_riff_lp_page_css', $css );
    } else {
        delete_post_meta( $post_id, '_riff_lp_page_css' );
    }
}
add_action( 'save_post_page', 'riff_lp_page_css_metabox_save' );

// ============================================================
// 固定ページ カスタムCSS をフロントエンドに出力
// ============================================================
function riff_lp_page_css_output() {
    if ( ! is_singular( 'page' ) ) return;
    $css = get_post_meta( get_the_ID(), '_riff_lp_page_css', true );
    if ( ! $css ) return;
    echo '<style id="riff-lp-page-css">' . "\n";
    echo $css . "\n";
    echo '</style>' . "\n";
}
add_action( 'wp_head', 'riff_lp_page_css_output', 99 );
