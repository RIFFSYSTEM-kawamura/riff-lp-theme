<?php
/**
 * RIFF LP Theme - 管理画面メインページ
 *
 * @package RiffLP
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// 管理メニュー登録
// ============================================================
function riff_lp_admin_menu() {
    add_menu_page(
        'RIFF LP 設定',
        'RIFF LP',
        'manage_options',
        'riff-lp-settings',
        'riff_lp_settings_page',
        'dashicons-layout',
        20
    );
    add_submenu_page(
        'riff-lp-settings',
        'セクション管理',
        'セクション管理',
        'manage_options',
        'riff-lp-settings',
        'riff_lp_settings_page'
    );
    add_submenu_page(
        'riff-lp-settings',
        'ヘッダー・フッター設定',
        'ヘッダー・フッター',
        'manage_options',
        'riff-lp-header-footer',
        'riff_lp_header_footer_page'
    );
    add_submenu_page(
        'riff-lp-settings',
        '全体の設定',
        '全体の設定',
        'manage_options',
        'riff-lp-global-settings',
        'riff_lp_global_settings_page'
    );
}
add_action( 'admin_menu', 'riff_lp_admin_menu' );

// ============================================================
// セクション管理ページ
// ============================================================
function riff_lp_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    // POST 保存処理
    if ( isset( $_POST['riff_lp_save_section'] ) && check_admin_referer( 'riff_lp_save', 'riff_lp_nonce_field' ) ) {
        $section_key = sanitize_key( $_POST['section_key'] ?? '' );
        $defaults    = riff_lp_default_sections();
        if ( isset( $defaults[ $section_key ] ) ) {
            foreach ( $defaults[ $section_key ]['fields'] as $field_key => $field ) {
                $val = $_POST[ "field_{$field_key}" ] ?? '';
                if ( $field['type'] === 'code' ) {
                    update_option( "riff_lp_{$section_key}_{$field_key}", wp_unslash( $val ) );
                } elseif ( $field['type'] === 'color' ) {
                    $color_val = sanitize_hex_color( wp_unslash( $val ) );
                    update_option( "riff_lp_{$section_key}_{$field_key}", $color_val ?? '' );
                } else {
                    update_option( "riff_lp_{$section_key}_{$field_key}", sanitize_text_field( wp_unslash( $val ) ) );
                }
            }
        }
        echo '<div class="notice notice-success is-dismissible"><p>✅ 保存しました</p></div>';
    }

    $sections     = riff_lp_default_sections();
    $ordered_keys = riff_lp_get_section_order();
    $active_key   = sanitize_key( $_GET['edit'] ?? '' );
    ?>
    <div class="wrap riff-admin-wrap">
        <div class="riff-admin-header">
            <h1>RIFF LP セクション管理</h1>
            <p>ドラッグ&ドロップでセクションの順番を変更できます。各セクションをクリックして編集してください。</p>
        </div>

        <div class="riff-admin-layout">
            <!-- サイドバー：セクション一覧 -->
            <aside class="riff-admin-sidebar">
                <div class="riff-sidebar-header">
                    <h2>📋 セクション一覧</h2>
                    <small>ドラッグで並び替え</small>
                </div>
                <ul id="riff-section-list" class="riff-section-list">
                <?php foreach ( $ordered_keys as $key ) :
                    if ( ! isset( $sections[ $key ] ) ) continue;
                    $sec     = $sections[ $key ];
                    $enabled = riff_lp_is_section_enabled( $key );
                    $active  = ( $key === $active_key ) ? ' is-active' : '';
                    ?>
                    <li class="riff-section-item<?php echo $active; ?>"
                        data-key="<?php echo esc_attr( $key ); ?>">
                        <span class="drag-handle">⋮⋮</span>
                        <span class="sec-icon"><?php echo esc_html( $sec['icon'] ); ?></span>
                        <span class="sec-label"><?php echo esc_html( $sec['label'] ); ?></span>
                        <div class="sec-controls">
                            <label class="toggle-switch" title="表示/非表示">
                                <input type="checkbox"
                                    class="section-toggle"
                                    data-section="<?php echo esc_attr( $key ); ?>"
                                    <?php checked( $enabled ); ?>>
                                <span class="toggle-slider"></span>
                            </label>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=riff-lp-settings&edit=' . $key ) ); ?>"
                               class="btn-edit-sec">編集</a>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
                <div class="riff-sidebar-footer">
                    <button id="riff-save-order" class="button button-primary button-hero">
                        並び順を保存
                    </button>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank"
                       class="button button-secondary">
                        LPをプレビュー
                    </a>
                </div>
            </aside>

            <!-- メインエリア：セクション編集 -->
            <main class="riff-admin-main">
            <?php if ( $active_key && isset( $sections[ $active_key ] ) ) :
                $sec = $sections[ $active_key ];
                ?>
                <div class="riff-section-editor">
                    <h2><?php echo esc_html( $sec['icon'] . ' ' . $sec['label'] ); ?> を編集</h2>
                    <form method="post" action="">
                        <?php wp_nonce_field( 'riff_lp_save', 'riff_lp_nonce_field' ); ?>
                        <input type="hidden" name="section_key" value="<?php echo esc_attr( $active_key ); ?>">
                        <input type="hidden" name="riff_lp_save_section" value="1">
                        <div class="riff-fields-grid">
                        <?php foreach ( $sec['fields'] as $field_key => $field ) :
                            $current_val = riff_lp_get_section_setting( $active_key, $field_key );
                            ?>
                            <div class="riff-field-row riff-field-type-<?php echo esc_attr( $field['type'] ); ?>">
                                <label class="riff-field-label" for="field_<?php echo esc_attr( $field_key ); ?>">
                                    <?php echo esc_html( $field['label'] ); ?>
                                </label>
                                <?php if ( $field['type'] === 'image' ) : ?>
                                    <div class="riff-image-field">
                                        <div class="riff-image-preview" id="preview_<?php echo esc_attr( $field_key ); ?>">
                                            <?php if ( $current_val ) :
                                                $img_url = wp_get_attachment_image_url( $current_val, 'thumbnail' );
                                                if ( $img_url ) : ?>
                                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="">
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden"
                                            name="field_<?php echo esc_attr( $field_key ); ?>"
                                            id="field_<?php echo esc_attr( $field_key ); ?>"
                                            value="<?php echo esc_attr( $current_val ); ?>">
                                        <button type="button"
                                            class="button riff-upload-btn"
                                            data-target="field_<?php echo esc_attr( $field_key ); ?>"
                                            data-preview="preview_<?php echo esc_attr( $field_key ); ?>">
                                            📷 画像を選択
                                        </button>
                                        <?php if ( $current_val ) : ?>
                                        <button type="button"
                                            class="button riff-remove-img"
                                            data-target="field_<?php echo esc_attr( $field_key ); ?>"
                                            data-preview="preview_<?php echo esc_attr( $field_key ); ?>">
                                            🗑 削除
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif ( $field['type'] === 'textarea' ) : ?>
                                    <textarea
                                        id="field_<?php echo esc_attr( $field_key ); ?>"
                                        name="field_<?php echo esc_attr( $field_key ); ?>"
                                        class="riff-textarea"
                                        rows="4"><?php echo esc_textarea( $current_val ); ?></textarea>
                                <?php elseif ( $field['type'] === 'code' ) : ?>
                                    <textarea
                                        id="field_<?php echo esc_attr( $field_key ); ?>"
                                        name="field_<?php echo esc_attr( $field_key ); ?>"
                                        class="riff-code-editor"
                                        rows="16"
                                        spellcheck="false"><?php echo esc_textarea( $current_val ); ?></textarea>
                                <?php elseif ( $field['type'] === 'color' ) : ?>
                                    <div class="riff-color-wrap">
                                        <input type="text"
                                            id="field_<?php echo esc_attr( $field_key ); ?>"
                                            name="field_<?php echo esc_attr( $field_key ); ?>"
                                            class="riff-color-picker"
                                            value="<?php echo esc_attr( $current_val ); ?>"
                                            data-default-color="">
                                    </div>
                                <?php elseif ( $field['type'] === 'url' ) : ?>
                                    <input type="url"
                                        id="field_<?php echo esc_attr( $field_key ); ?>"
                                        name="field_<?php echo esc_attr( $field_key ); ?>"
                                        class="riff-input riff-input-url"
                                        value="<?php echo esc_attr( $current_val ); ?>">
                                <?php else : ?>
                                    <input type="text"
                                        id="field_<?php echo esc_attr( $field_key ); ?>"
                                        name="field_<?php echo esc_attr( $field_key ); ?>"
                                        class="riff-input"
                                        value="<?php echo esc_attr( $current_val ); ?>">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        </div>
                        <div class="riff-form-actions">
                            <button type="submit" class="button button-primary button-hero">
                                💾 保存する
                            </button>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=riff-lp-settings' ) ); ?>"
                               class="button button-secondary">← 一覧に戻る</a>
                        </div>
                    </form>
                </div>
            <?php else : ?>
                <div class="riff-empty-state">
                    <div class="riff-empty-icon"></div>
                    <h2>セクションを選択してください</h2>
                    <p>左のリストからセクションを選んで「編集」ボタンをクリックするとここで内容を変更できます。</p>
                    <div class="riff-tips">
                        <h3>💡 使い方のヒント</h3>
                        <ul>
                            <li>⋮⋮ アイコンをドラッグしてセクションの順番を変更できます</li>
                            <li>トグルスイッチでセクションの表示/非表示を切り替えられます</li>
                            <li>「並び順を保存」ボタンで順番を確定してください</li>
                            <li>「LPをプレビュー」で実際の見た目を確認できます</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            </main>
        </div>
    </div>
    <?php
}

// ============================================================
// ヘッダー・フッター設定ページ
// ============================================================
function riff_lp_header_footer_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    if ( isset( $_POST['riff_lp_save_hf'] ) && check_admin_referer( 'riff_lp_hf_save', 'riff_lp_hf_nonce' ) ) {
        $fields = [ 'header_brand_type', 'header_logo', 'header_tel', 'header_tel_label', 'header_show_tel', 'header_btn_text', 'header_btn_url',
                    'footer_brand_type', 'footer_logo', 'footer_company', 'footer_address', 'footer_tel', 'footer_email', 'footer_copy' ];
        
        // メニュー項目を処理
        $menu_data = isset( $_POST['riff_lp_menu_data'] ) ? json_decode( wp_unslash( $_POST['riff_lp_menu_data'] ), true ) : [];
        // 旧いメニューを削除
        for ( $i = 1; $i <= 20; $i++ ) {
            delete_option( "riff_lp_header_menu_item_{$i}_label" );
            delete_option( "riff_lp_header_menu_item_{$i}_url" );
        }
        // 新しいメニューを上上し
        if ( ! empty( $menu_data ) && is_array( $menu_data ) ) {
            $idx = 1;
            foreach ( $menu_data as $item ) {
                $label = sanitize_text_field( $item['label'] ?? '' );
                $url   = esc_url_raw( $item['url'] ?? '' );
                if ( $label ) {
                    update_option( "riff_lp_header_menu_item_{$idx}_label", $label );
                    update_option( "riff_lp_header_menu_item_{$idx}_url", $url );
                    $idx++;
                }
            }
        }
        
        // その他のフィールドを処理
        foreach ( $fields as $f ) {
            if ( $f === 'header_logo' || $f === 'footer_logo' ) {
                update_option( "riff_lp_{$f}", absint( $_POST[ $f ] ?? 0 ) );
                continue;
            }
            if ( $f === 'header_show_tel' ) {
                update_option( "riff_lp_{$f}", isset( $_POST[ $f ] ) ? '1' : '0' );
                continue;
            }
            $value = $_POST[ $f ] ?? '';
            if ( str_ends_with( $f, '_url' ) && $f !== 'header_btn_url' ) {
                update_option( "riff_lp_{$f}", esc_url_raw( wp_unslash( $value ) ) );
            } else {
                update_option( "riff_lp_{$f}", sanitize_text_field( wp_unslash( $value ) ) );
            }
        }

        // SNS設定を保存
        foreach ( array_keys( riff_lp_sns_platforms() ) as $platform ) {
            update_option( "riff_lp_sns_{$platform}_url",  esc_url_raw( wp_unslash( $_POST["sns_{$platform}_url"] ?? '' ) ) );
            update_option( "riff_lp_sns_{$platform}_show", isset( $_POST["sns_{$platform}_show"] ) ? '1' : '0' );
        }

        // SNS並び順を保存
        if ( isset( $_POST['riff_lp_sns_order'] ) && $_POST['riff_lp_sns_order'] !== '' ) {
            $valid_keys = array_keys( riff_lp_sns_platforms() );
            $raw_order  = explode( ',', sanitize_text_field( wp_unslash( $_POST['riff_lp_sns_order'] ) ) );
            $order      = array_values( array_intersect( $raw_order, $valid_keys ) );
            update_option( 'riff_lp_sns_order', $order );
        }

        echo '<div class="notice notice-success is-dismissible"><p>✅ 保存しました</p></div>';
    }

    $get = function( $key, $default = '' ) {
        return esc_attr( get_option( "riff_lp_{$key}", $default ) );
    };
    ?>
    <div class="wrap riff-admin-wrap">
        <div class="riff-admin-header">
            <h1>ヘッダー・フッター設定</h1>
        </div>
        <form method="post" action="" class="riff-hf-form" id="riff-hf-form">
            <?php wp_nonce_field( 'riff_lp_hf_save', 'riff_lp_hf_nonce' ); ?>
            <input type="hidden" name="riff_lp_save_hf" value="1">

            <div class="riff-card">
                <h2>ヘッダー設定</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="header_tel">電話番号</label></th>
                        <td><input type="text" id="header_tel" name="header_tel"
                                   value="<?php echo $get('header_tel','0120-000-000'); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="header_tel_label">電話ラベル</label></th>
                        <td><input type="text" id="header_tel_label" name="header_tel_label"
                                   value="<?php echo $get('header_tel_label','受付時間 平日 9:00〜18:00'); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="header_show_tel">電話番号表示</label></th>
                        <td>
                            <label>
                                <input type="checkbox" id="header_show_tel" name="header_show_tel" value="1"
                                       <?php checked( $get( 'header_show_tel', '1' ), '1' ); ?>>
                                表示する
                            </label>
                        </td>
                    </tr>
                    <tr>                        <th><label for="header_brand_type">ヘッダーブランド</label></th>
                        <td>
                            <select id="header_brand_type" name="header_brand_type" class="regular-select">
                                <option value="title" <?php selected( $get( 'header_brand_type', 'title' ), 'title' ); ?>>サイトタイトル</option>
                                <option value="logo"  <?php selected( $get( 'header_brand_type', 'title' ), 'logo' ); ?>>ロゴ画像</option>
                                <option value="both"  <?php selected( $get( 'header_brand_type', 'title' ), 'both' ); ?>>ロゴ＋タイトル</option>
                            </select>
                            <p class="description">「ロゴ＋タイトル」はロゴ画像とサイト名を横並びで表示します。</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="header_logo">ヘッダーロゴ画像</label></th>
                        <td>
                            <div class="riff-image-field">
                                <div class="riff-image-preview" id="preview_header_logo">
                                    <?php if ( $get( 'header_logo' ) ) :
                                        $logo_id = absint( $get( 'header_logo' ) );
                                        $img_url = wp_get_attachment_image_url( $logo_id, 'thumbnail' );
                                        if ( $img_url ) : ?>
                                            <img src="<?php echo esc_url( $img_url ); ?>" alt="">
                                        <?php endif;
                                    endif; ?>
                                </div>
                                <input type="hidden" id="header_logo" name="header_logo" value="<?php echo $get( 'header_logo', '' ); ?>">
                                <button type="button" class="button riff-upload-btn" data-target="header_logo" data-preview="preview_header_logo">
                                    📷 画像を選択
                                </button>
                                <?php if ( $get( 'header_logo' ) ) : ?>
                                <button type="button" class="button riff-remove-img" data-target="header_logo" data-preview="preview_header_logo">
                                    🗑 削除
                                </button>
                                <?php endif; ?>
                            </div>
                            <p class="description">ロゴ画像は自動的にヘッダー高さに合わせて調整されます。</p>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <strong style="display: flex; align-items: center; gap: 10px; cursor: pointer;" class="riff-menu-toggle" data-toggle="#riff-menu-settings">
                                <span style="display: inline-block; width: 16px; text-align: center;">▶</span>
                                📄 ヘッダーメニュー
                            </strong>
                        </th>
                    </tr>
                    <tr id="riff-menu-settings" class="riff-menu-settings" style="display: none;">
                        <td colspan="2">
                            <div class="riff-menu-builder" id="riff-menu-builder">
                                <?php
                                $existing_items = [];
                                for ( $i = 1; $i <= 20; $i++ ) {
                                    $label = $get( "header_menu_item_{$i}_label", '' );
                                    $url   = $get( "header_menu_item_{$i}_url", '' );
                                    if ( $label || $url ) {
                                        $existing_items[] = [ 'label' => $label, 'url' => $url ];
                                    }
                                }
                                $all_items = array_merge( $existing_items, [ [ 'label' => '', 'url' => '' ] ] );
                                ?>
                                <?php foreach ( $all_items as $idx => $item ) : ?>
                                <div class="riff-menu-item" data-index="<?php echo esc_attr( $idx ); ?>">
                                    <div class="riff-menu-item-inner">
                                        <input type="text" class="riff-menu-label" placeholder="メニュー名" value="<?php echo esc_attr( $item['label'] ); ?>">
                                        <input type="text" class="riff-menu-url" placeholder="https://... または #section-id" value="<?php echo esc_attr( $item['url'] ); ?>">
                                        <button type="button" class="button riff-menu-remove" style="display: none;">🗑</button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="button riff-menu-add" id="riff-menu-add" style="margin-top: 12px;">
                                ➕ メニュー項目を追加
                            </button>
                            <input type="hidden" id="riff_lp_menu_data" name="riff_lp_menu_data" value="[]">
                        </td>
                    </tr>

                    <tr>                        <th><label for="header_btn_text">ヘッダーボタンテキスト</label></th>
                        <td><input type="text" id="header_btn_text" name="header_btn_text"
                                   value="<?php echo $get('header_btn_text','無料診断はこちら'); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="header_btn_url">ヘッダーボタンURL</label></th>
                        <td><input type="url" id="header_btn_url" name="header_btn_url"
                                   value="<?php echo $get('header_btn_url','#contact'); ?>"
                                   class="regular-text"></td>
                    </tr>
                </table>
            </div>

            <div class="riff-card">
                <h2>フッター設定</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="footer_brand_type">フッターブランド</label></th>
                        <td>
                            <select id="footer_brand_type" name="footer_brand_type" class="regular-select">
                                <option value="title" <?php selected( $get( 'footer_brand_type', 'title' ), 'title' ); ?>>サイトタイトル</option>
                                <option value="logo"  <?php selected( $get( 'footer_brand_type', 'title' ), 'logo' ); ?>>ロゴ画像</option>
                                <option value="both"  <?php selected( $get( 'footer_brand_type', 'title' ), 'both' ); ?>>ロゴ＋タイトル</option>
                            </select>
                            <p class="description">「ロゴ＋タイトル」はロゴ画像とサイト名を横並びで表示します。</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="footer_logo">フッターロゴ画像</label></th>
                        <td>
                            <div class="riff-image-field">
                                <div class="riff-image-preview" id="preview_footer_logo">
                                    <?php if ( $get( 'footer_logo' ) ) :
                                        $footer_logo_id = absint( $get( 'footer_logo' ) );
                                        $footer_img_url = wp_get_attachment_image_url( $footer_logo_id, 'thumbnail' );
                                        if ( $footer_img_url ) : ?>
                                            <img src="<?php echo esc_url( $footer_img_url ); ?>" alt="">
                                        <?php endif;
                                    endif; ?>
                                </div>
                                <input type="hidden" id="footer_logo" name="footer_logo" value="<?php echo $get( 'footer_logo', '' ); ?>">
                                <button type="button" class="button riff-upload-btn" data-target="footer_logo" data-preview="preview_footer_logo">
                                    📷 画像を選択
                                </button>
                                <?php if ( $get( 'footer_logo' ) ) : ?>
                                <button type="button" class="button riff-remove-img" data-target="footer_logo" data-preview="preview_footer_logo">
                                    🗑 削除
                                </button>
                                <?php endif; ?>
                            </div>
                            <p class="description">フッターの背景色に合う画像（透過PNG推奨）をお使いください。高さは自動調整されます。</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="footer_company">会社名</label></th>
                        <td><input type="text" id="footer_company" name="footer_company"
                                   value="<?php echo $get('footer_company','株式会社〇〇'); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="footer_address">住所</label></th>
                        <td><input type="text" id="footer_address" name="footer_address"
                                   value="<?php echo $get('footer_address','〒000-0000 東京都〇〇区〇〇'); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="footer_tel">フッター電話番号</label></th>
                        <td><input type="text" id="footer_tel" name="footer_tel"
                                   value="<?php echo $get('footer_tel','0120-000-000'); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="footer_email">メールアドレス</label></th>
                        <td><input type="email" id="footer_email" name="footer_email"
                                   value="<?php echo $get('footer_email','info@example.com'); ?>"
                                   class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="footer_copy">コピーライト</label></th>
                        <td><input type="text" id="footer_copy" name="footer_copy"
                                   value="<?php echo $get('footer_copy','© 2024 株式会社〇〇 All Rights Reserved.'); ?>"
                                   class="regular-text"></td>
                    </tr>
                </table>
            </div>

            <div class="riff-card">
                <h2>SNS設定</h2>
                <p class="description" style="margin-bottom:16px;">表示チェックを入れるとSNSが表示されます。⋮⋮ をドラッグして並び順を変更できます。ヘッダーはスマホのハンバーガーメニュー内のみ表示されます。</p>
                <?php
                $sns_platforms = riff_lp_sns_platforms();
                $all_sns_keys  = array_keys( $sns_platforms );
                $saved_sns_order = get_option( 'riff_lp_sns_order', [] );
                $sns_order = array_values( array_unique( array_merge(
                    array_intersect( $saved_sns_order, $all_sns_keys ),
                    $all_sns_keys
                ) ) );
                ?>
                <ul id="riff-sns-list" class="riff-sns-list">
                    <?php foreach ( $sns_order as $key ) :
                        if ( ! isset( $sns_platforms[ $key ] ) ) continue;
                        $label = $sns_platforms[ $key ];
                    ?>
                    <li class="riff-sns-item" data-key="<?php echo esc_attr( $key ); ?>">
                        <span class="drag-handle">⋮⋮</span>
                        <span class="sns-icon sns-icon--<?php echo esc_attr( $key ); ?> riff-sns-preview" aria-hidden="true">
                            <?php echo riff_lp_sns_icon( $key ); ?>
                        </span>
                        <span class="riff-sns-label"><?php echo esc_html( $label ); ?></span>
                        <label class="riff-sns-show-label">
                            <input type="checkbox" name="sns_<?php echo esc_attr( $key ); ?>_show" value="1"
                                   <?php checked( get_option( "riff_lp_sns_{$key}_show", '0' ), '1' ); ?>>
                            表示する
                        </label>
                        <input type="url" name="sns_<?php echo esc_attr( $key ); ?>_url"
                               value="<?php echo esc_attr( get_option( "riff_lp_sns_{$key}_url", '' ) ); ?>"
                               placeholder="https://..."
                               class="regular-text riff-sns-url">
                    </li>
                    <?php endforeach; ?>
                </ul>
                <input type="hidden" id="riff_lp_sns_order" name="riff_lp_sns_order" value="">
            </div>

            <p>
                <button type="submit" class="button button-primary button-hero">保存する</button>
            </p>
        </form>
    </div>
    <?php
}

// ============================================================
// 全体の設定ページ
// ============================================================
function riff_lp_global_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    if ( isset( $_POST['riff_lp_save_global'] ) && check_admin_referer( 'riff_lp_global_save', 'riff_lp_global_nonce' ) ) {
        $font = sanitize_text_field( wp_unslash( $_POST['global_font_family'] ?? 'noto-sans-jp' ) );
        $fonts = array_keys( riff_lp_available_fonts() );
        if ( ! in_array( $font, $fonts, true ) ) {
            $font = 'noto-sans-jp';
        }
        update_option( 'riff_lp_global_font_family', $font );
        
        // カスタムフォント管理
        if ( isset( $_POST['riff_lp_custom_fonts_data'] ) ) {
            $custom_data = json_decode( wp_unslash( $_POST['riff_lp_custom_fonts_data'] ), true );
            $custom_fonts = [];
            if ( is_array( $custom_data ) ) {
                foreach ( $custom_data as $item ) {
                    $label = sanitize_text_field( $item['label'] ?? '' );
                    $url   = esc_url_raw( $item['url'] ?? '' );
                    $stack = sanitize_text_field( $item['stack'] ?? '' );
                    if ( $label && $url && $stack ) {
                        $custom_fonts[] = [
                            'label' => $label,
                            'url'   => $url,
                            'stack' => $stack,
                        ];
                    }
                }
            }
            update_option( 'riff_lp_custom_fonts', $custom_fonts );
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>✅ 保存しました</p></div>';
    }

    $font_key = get_option( 'riff_lp_global_font_family', 'noto-sans-jp' );
    $fonts    = riff_lp_available_fonts();
    $custom_fonts = get_option( 'riff_lp_custom_fonts', [] );
    ?>
    <div class="wrap riff-admin-wrap">
        <div class="riff-admin-header">
            <h1>全体の設定</h1>
        </div>
        <form method="post" action="" class="riff-hf-form" id="riff-global-form">
            <?php wp_nonce_field( 'riff_lp_global_save', 'riff_lp_global_nonce' ); ?>
            <input type="hidden" name="riff_lp_save_global" value="1">

            <div class="riff-card">
                <h2>フォント設定</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="global_font_family">サイト全体のフォント</label></th>
                        <td>
                            <select id="global_font_family" name="global_font_family" class="regular-select">
                                <?php foreach ( $fonts as $key => $font ) : ?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $font_key, $key ); ?>>
                                        <?php echo esc_html( $font['label'] ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">LP全体の本文フォントとして使用されます。</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="riff-card">
                <h2>➕ カスタムフォント追加</h2>
                <p style="margin-bottom: 20px;">Google Fonts や外部フォントを追加することができます。</p>
                <div class="riff-custom-fonts-builder" id="riff-custom-fonts-builder">
                    <?php if ( ! empty( $custom_fonts ) ) : ?>
                        <?php foreach ( $custom_fonts as $idx => $font ) : ?>
                        <div class="riff-custom-font-item" data-index="<?php echo esc_attr( $idx ); ?>">
                            <div class="riff-custom-font-inner">
                                <div class="riff-custom-font-row">
                                    <label>フォント名</label>
                                    <input type="text" class="riff-custom-font-label" placeholder="例：Noto Sans JP JP" value="<?php echo esc_attr( $font['label'] ); ?>">
                                </div>
                                <div class="riff-custom-font-row">
                                    <label>Google Fonts URL</label>
                                    <input type="url" class="riff-custom-font-url" placeholder="https://fonts.googleapis.com/css2?family=..." value="<?php echo esc_attr( $font['url'] ); ?>">
                                </div>
                                <div class="riff-custom-font-row">
                                    <label>フォントスタック</label>
                                    <input type="text" class="riff-custom-font-stack" placeholder="'Noto Sans JP', 'Meiryo', sans-serif" value="<?php echo esc_attr( $font['stack'] ); ?>">
                                </div>
                                <button type="button" class="button riff-custom-font-remove">削除</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="button" id="riff-custom-font-add" style="margin-top: 12px;">
                    ➕ フォントを追加
                </button>
                <input type="hidden" id="riff_lp_custom_fonts_data" name="riff_lp_custom_fonts_data" value="[]">
            </div>

            <div class="riff-form-actions">
                <button type="submit" class="button button-primary button-hero">保存する</button>
            </div>
        </form>
    </div>
    <?php
}
