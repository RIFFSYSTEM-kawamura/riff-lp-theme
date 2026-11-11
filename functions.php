<?php
/**
 * RIFF LP Theme - functions.php
 *
 * @package RiffLP
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'RIFF_LP_VERSION', '1.0.0' );
define( 'RIFF_LP_DIR',     get_template_directory() );
define( 'RIFF_LP_URI',     get_template_directory_uri() );

// ============================================================
// テーマサポート設定
// ============================================================
function riff_lp_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', [
        'height'      => 96,
        'width'       => 320,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    load_theme_textdomain( 'riff-lp', RIFF_LP_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'riff_lp_setup' );

// ============================================================
// スクリプト & スタイル エンキュー
// ============================================================
function riff_lp_available_fonts() {
    $fonts = [
        'noto-sans-jp' => [
            'label' => 'Noto Sans JP',
            'url'   => 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700;900&display=swap',
            'stack' => "'Noto Sans JP', 'Hiragino Kaku Gothic ProN', 'Meiryo', sans-serif",
        ],
        'mplus-1p' => [
            'label' => 'M PLUS 1p',
            'url'   => 'https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@400;700;900&display=swap',
            'stack' => "'M PLUS 1p', 'Hiragino Kaku Gothic ProN', 'Meiryo', sans-serif",
        ],
        'roboto' => [
            'label' => 'Roboto',
            'url'   => 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap',
            'stack' => "'Roboto', 'Hiragino Kaku Gothic ProN', 'Meiryo', sans-serif",
        ],
        'open-sans' => [
            'label' => 'Open Sans',
            'url'   => 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700;900&display=swap',
            'stack' => "'Open Sans', 'Segoe UI', 'Hiragino Kaku Gothic ProN', 'Meiryo', sans-serif",
        ],
        'lato' => [
            'label' => 'Lato',
            'url'   => 'https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap',
            'stack' => "'Lato', 'Segoe UI', 'Hiragino Kaku Gothic ProN', 'Meiryo', sans-serif",
        ],
        'source-sans-pro' => [
            'label' => 'Source Sans Pro',
            'url'   => 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;700;900&display=swap',
            'stack' => "'Source Sans Pro', 'Segoe UI', 'Hiragino Kaku Gothic ProN', 'Meiryo', sans-serif",
        ],
        'system' => [
            'label' => 'システムフォント',
            'url'   => '',
            'stack' => "'Segoe UI', 'Hiragino Kaku Gothic ProN', 'Meiryo', sans-serif",
        ],
    ];
    
    // カスタムフォントを追加
    $custom = get_option( 'riff_lp_custom_fonts', [] );
    if ( is_array( $custom ) && ! empty( $custom ) ) {
        foreach ( $custom as $key => $font_data ) {
            $fonts[ 'custom_' . $key ] = $font_data;
        }
    }
    
    return $fonts;
}

function riff_lp_get_global_font_key() {
    $available = riff_lp_available_fonts();
    $font_key  = get_option( 'riff_lp_global_font_family', 'noto-sans-jp' );
    return isset( $available[ $font_key ] ) ? $font_key : 'noto-sans-jp';
}

function riff_lp_get_global_font_data() {
    $available = riff_lp_available_fonts();
    return $available[ riff_lp_get_global_font_key() ];
}

function riff_lp_enqueue_assets() {
    $font_data = riff_lp_get_global_font_data();
    $deps      = [];

    if ( ! empty( $font_data['url'] ) ) {
        wp_enqueue_style(
            'riff-lp-fonts',
            esc_url( $font_data['url'] ),
            [],
            null
        );
        $deps = [ 'riff-lp-fonts' ];
    }

    wp_enqueue_style(
        'riff-lp-style',
        RIFF_LP_URI . '/style.css',
        $deps,
        RIFF_LP_VERSION
    );

    wp_add_inline_style( 'riff-lp-style', ':root { --font-main: ' . $font_data['stack'] . '; }' );

    // ヘッダーボタン カスタムカラー
    $hbtn_bg    = sanitize_hex_color( get_option( 'riff_lp_header_btn_bg_color', '' ) );
    $hbtn_color = sanitize_hex_color( get_option( 'riff_lp_header_btn_text_color', '' ) );
    if ( $hbtn_bg || $hbtn_color ) {
        $hbtn_css = '.btn--header{';
        if ( $hbtn_bg )    $hbtn_css .= 'background:' . $hbtn_bg . ';box-shadow:0 6px 20px rgba(0,0,0,.25);';
        if ( $hbtn_color ) $hbtn_css .= 'color:' . $hbtn_color . ';';
        $hbtn_css .= '}';
        if ( $hbtn_bg )    $hbtn_css .= '.btn--header:hover{background:' . $hbtn_bg . ';box-shadow:0 10px 28px rgba(0,0,0,.3);}';
        wp_add_inline_style( 'riff-lp-style', $hbtn_css );
    }

    // メインJS
    wp_enqueue_script(
        'riff-lp-main',
        RIFF_LP_URI . '/assets/js/lp.js',
        [],
        RIFF_LP_VERSION,
        true
    );

    // カスタムJS (init-ver1.js) — ユーザーが自由に編集できる専用ファイル
    $init_v1 = RIFF_LP_DIR . '/assets/js/init-ver1.js';
    if ( file_exists( $init_v1 ) ) {
        wp_enqueue_script(
            'riff-lp-init-v1',
            RIFF_LP_URI . '/assets/js/init-ver1.js',
            [ 'riff-lp-main' ],
            filemtime( $init_v1 ),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'riff_lp_enqueue_assets' );

// 管理画面アセット
function riff_lp_admin_assets( $hook ) {
    if ( strpos( $hook, 'riff-lp' ) === false ) return;

    wp_enqueue_style(
        'riff-lp-admin',
        RIFF_LP_URI . '/assets/css/admin.css',
        [],
        RIFF_LP_VERSION
    );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_media();
    wp_enqueue_script(
        'riff-lp-admin',
        RIFF_LP_URI . '/assets/js/admin.js',
        [ 'jquery', 'jquery-ui-sortable', 'wp-util' ],
        RIFF_LP_VERSION,
        true
    );
    wp_localize_script( 'riff-lp-admin', 'riffLP', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'riff_lp_nonce' ),
    ] );
}
add_action( 'admin_enqueue_scripts', 'riff_lp_admin_assets' );

// ============================================================
// インクルード
// ============================================================
require_once RIFF_LP_DIR . '/inc/metaboxes.php';
require_once RIFF_LP_DIR . '/inc/admin-page.php';
require_once RIFF_LP_DIR . '/inc/ajax-handlers.php';

// ============================================================
// デフォルトセクション設定
// ============================================================
if ( ! function_exists( 'riff_lp_default_sections' ) ) :
function riff_lp_default_sections() {
    $sections = [
        'hero' => [
            'label'   => 'ヒーローセクション',
            'icon'    => '🏠',
            'enabled' => true,
            'order'   => 1,
            'fields'  => [
                'badge_text'    => ['type'=>'text',  'label'=>'バッジテキスト',   'default'=>'電気代削減の専門家'],
                'title_line1'   => ['type'=>'text',  'label'=>'タイトル1行目',   'default'=>'あなたの電気料金を'],
                'title_line2'   => ['type'=>'text',  'label'=>'タイトル2行目',   'default'=>'お安くしませんか？'],
                'num_value'     => ['type'=>'text',  'label'=>'大きな数字',       'default'=>'98'],
                'num_unit'      => ['type'=>'text',  'label'=>'数字の単位',       'default'=>'%'],
                'description'   => ['type'=>'textarea','label'=>'説明文',         'default'=>'電力会社の変更・節電提案で、多くのお客様が光熱費削減に成功しています。まずはお気軽にご相談ください。'],
                'btn_text'      => ['type'=>'text',  'label'=>'CTAボタンテキスト','default'=>'無料診断を申し込む'],
                'btn_url'       => ['type'=>'url',   'label'=>'CTAボタンURL',     'default'=>'#contact'],
                'image'         => ['type'=>'image', 'label'=>'ヒーロー画像',     'default'=>''],
                'stat1_num'     => ['type'=>'text',  'label'=>'実績数値①',       'default'=>'98'],
                'stat1_unit'    => ['type'=>'text',  'label'=>'実績単位①',       'default'=>'%'],
                'stat1_label'   => ['type'=>'text',  'label'=>'実績ラベル①',     'default'=>'削減達成率'],
                'stat2_num'     => ['type'=>'text',  'label'=>'実績数値②',       'default'=>'500'],
                'stat2_unit'    => ['type'=>'text',  'label'=>'実績単位②',       'default'=>'社'],
                'stat2_label'   => ['type'=>'text',  'label'=>'実績ラベル②',     'default'=>'導入企業数'],
                'stat3_num'     => ['type'=>'text',  'label'=>'実績数値③',       'default'=>'0'],
                'stat3_unit'    => ['type'=>'text',  'label'=>'実績単位③',       'default'=>'円'],
                'stat3_label'   => ['type'=>'text',  'label'=>'実績ラベル③',     'default'=>'初期費用'],
            ],
        ],
        'trust' => [
            'label'   => '安心バッジ帯',
            'icon'    => '🛡️',
            'enabled' => true,
            'order'   => 2,
            'fields'  => [
                'item1_icon'  => ['type'=>'text','label'=>'アイコン①','default'=>'✅'],
                'item1_text'  => ['type'=>'text','label'=>'テキスト①','default'=>'初期費用・解約費用0円'],
                'item2_icon'  => ['type'=>'text','label'=>'アイコン②','default'=>'✅'],
                'item2_text'  => ['type'=>'text','label'=>'テキスト②','default'=>'停電リスクなし'],
                'item3_icon'  => ['type'=>'text','label'=>'アイコン③','default'=>'✅'],
                'item3_text'  => ['type'=>'text','label'=>'テキスト③','default'=>'アフターフォロー万全'],
            ],
        ],
        'graph' => [
            'label'   => 'グラフ・実績セクション',
            'icon'    => '📊',
            'enabled' => true,
            'order'   => 3,
            'fields'  => [
                'tag'       => ['type'=>'text',    'label'=>'タグテキスト','default'=>'実績データ'],
                'headline'  => ['type'=>'text',    'label'=>'見出し',      'default'=>'昔の「常識」は変わります'],
                'body'      => ['type'=>'textarea','label'=>'本文',        'default'=>'以前は電気代は「固定費」と思われていましたが、現在は自由化により、より安い料金へ切り替えることが可能になりました。'],
                'image'     => ['type'=>'image',   'label'=>'グラフ画像',  'default'=>''],
            ],
        ],
        'shinjoshiki' => [
            'label'   => '新常識バナー',
            'icon'    => '💡',
            'enabled' => true,
            'order'   => 4,
            'fields'  => [
                'label'    => ['type'=>'text',    'label'=>'ラベル',    'default'=>'電気料金についての'],
                'title'    => ['type'=>'text',    'label'=>'タイトル',  'default'=>'新常識'],
                'subtitle' => ['type'=>'textarea','label'=>'サブタイトル','default'=>'電力会社を切り替えるだけで、電気代が下がる時代になりました。'],
            ],
        ],
        'percent' => [
            'label'   => '98%セクション',
            'icon'    => '📈',
            'enabled' => true,
            'order'   => 5,
            'fields'  => [
                'num'     => ['type'=>'text',    'label'=>'パーセント数値','default'=>'98'],
                'label'   => ['type'=>'text',    'label'=>'数値ラベル',    'default'=>'電力切り替えを提案した結果'],
                'headline'=> ['type'=>'text',    'label'=>'見出し',        'default'=>'電力が切り替えた方の98%が\n電力削減に成功しました！'],
                'body'    => ['type'=>'textarea','label'=>'本文',          'default'=>'弊社が電力切り替えを提案し、実際に切り替えを実施した企業様の98%が電気代削減に成功しております。'],
            ],
        ],
        'results' => [
            'label'   => '実績・写真グリッド',
            'icon'    => '🏆',
            'enabled' => true,
            'order'   => 6,
            'fields'  => [
                'headline' => ['type'=>'text',   'label'=>'見出し',    'default'=>'年間で4,000万円以上削減したケースも！'],
                'sub'      => ['type'=>'text',   'label'=>'サブ見出し','default'=>'電気代削減の実績をご紹介します'],
                'image1'   => ['type'=>'image',  'label'=>'写真①',    'default'=>''],
                'image2'   => ['type'=>'image',  'label'=>'写真②',    'default'=>''],
                'image3'   => ['type'=>'image',  'label'=>'写真③',    'default'=>''],
                'image4'   => ['type'=>'image',  'label'=>'写真④',    'default'=>''],
                'image5'   => ['type'=>'image',  'label'=>'写真⑤',    'default'=>''],
                'image6'   => ['type'=>'image',  'label'=>'写真⑥',    'default'=>''],
                'image7'   => ['type'=>'image',  'label'=>'写真⑦',    'default'=>''],
                'image8'   => ['type'=>'image',  'label'=>'写真⑧',    'default'=>''],
            ],
        ],
        'reasons' => [
            'label'   => '選ばれる3つの理由',
            'icon'    => '⭐',
            'enabled' => true,
            'order'   => 7,
            'fields'  => [
                'section_label' => ['type'=>'text','label'=>'セクションラベル','default'=>'選ばれる理由'],
                'section_title' => ['type'=>'text','label'=>'セクションタイトル','default'=>'エレクトが選ばれる3つの理由'],
                'r1_title' => ['type'=>'text',   'label'=>'理由①タイトル','default'=>'電力比較サイトの口コミ評価で\n全国98%達成！'],
                'r1_desc'  => ['type'=>'textarea','label'=>'理由①説明',   'default'=>'お客様からの高い評価が証明する信頼性。豊富な実績とノウハウで最適な電力会社をご提案します。'],
                'r1_image' => ['type'=>'image',   'label'=>'理由①画像',   'default'=>''],
                'r2_title' => ['type'=>'text',   'label'=>'理由②タイトル','default'=>'顧客中心のサービスで\n安心サポート！'],
                'r2_desc'  => ['type'=>'textarea','label'=>'理由②説明',   'default'=>'切り替え手続きから導入後のフォローまで、専任担当者が責任を持って対応します。'],
                'r2_image' => ['type'=>'image',   'label'=>'理由②画像',   'default'=>''],
                'r3_title' => ['type'=>'text',   'label'=>'理由③タイトル','default'=>'契約後も安心の\nアフターフォロー！'],
                'r3_desc'  => ['type'=>'textarea','label'=>'理由③説明',   'default'=>'電力切り替え後も料金の確認や節電アドバイスなど、継続的にサポートします。'],
                'r3_image' => ['type'=>'image',   'label'=>'理由③画像',   'default'=>''],
            ],
        ],
        'message' => [
            'label'   => 'メッセージセクション',
            'icon'    => '💬',
            'enabled' => true,
            'order'   => 8,
            'fields'  => [
                'label'  => ['type'=>'text',    'label'=>'ラベル',  'default'=>'Message'],
                'title'  => ['type'=>'text',    'label'=>'見出し',  'default'=>'ご好評いただいています\nエレクトにお任せください！'],
                'body'   => ['type'=>'textarea','label'=>'本文',    'default'=>'電力自由化以来、多くの企業様の電気代削減をお手伝いしてきました。お客様一社一社の状況を丁寧に分析し、最適なプランをご提案しています。'],
                'sig'    => ['type'=>'text',    'label'=>'署名',    'default'=>'代表取締役　○○　○○'],
                'photo'  => ['type'=>'image',   'label'=>'顔写真',  'default'=>''],
            ],
        ],
        'voices' => [
            'label'   => 'お客様の声',
            'icon'    => '🗣️',
            'enabled' => true,
            'order'   => 9,
            'fields'  => [
                'section_title' => ['type'=>'text','label'=>'セクションタイトル','default'=>'お客様の声'],
                'v1_text'     => ['type'=>'textarea','label'=>'声①本文',   'default'=>'切り替えてから毎月の電気代が大幅に下がりました。手続きも全て任せられて本当に楽でした。'],
                'v1_meta'     => ['type'=>'text',    'label'=>'声①名前・属性','default'=>'製造業 A社様'],
                'v1_industry' => ['type'=>'text',    'label'=>'声①業種',    'default'=>'従業員50名'],
                'v2_text'     => ['type'=>'textarea','label'=>'声②本文',   'default'=>'専任担当者が丁寧に説明してくれたので安心して切り替えができました。'],
                'v2_meta'     => ['type'=>'text',    'label'=>'声②名前・属性','default'=>'飲食業 B社様'],
                'v2_industry' => ['type'=>'text',    'label'=>'声②業種',    'default'=>'店舗数5店'],
                'v3_text'     => ['type'=>'textarea','label'=>'声③本文',   'default'=>'年間で予想以上の削減額になり驚きました。もっと早く相談すればよかったです。'],
                'v3_meta'     => ['type'=>'text',    'label'=>'声③名前・属性','default'=>'小売業 C社様'],
                'v3_industry' => ['type'=>'text',    'label'=>'声③業種',    'default'=>'従業員30名'],
            ],
        ],
        'steps' => [
            'label'   => '5STEPセクション',
            'icon'    => '👣',
            'enabled' => true,
            'order'   => 10,
            'fields'  => [
                'section_label' => ['type'=>'text','label'=>'セクションラベル','default'=>'電力会社切り替えへの道のり'],
                'section_title' => ['type'=>'text','label'=>'セクションタイトル','default'=>'電力会社切り替えへの5STEP'],
                'step1_name'  => ['type'=>'text',   'label'=>'STEP1 名称',  'default'=>'お見積りのご依頼'],
                'step1_desc'  => ['type'=>'textarea','label'=>'STEP1 説明', 'default'=>'お電話またはメールフォームよりお気軽にご連絡ください。'],
                'step1_image' => ['type'=>'image',   'label'=>'STEP1 画像', 'default'=>''],
                'step2_name'  => ['type'=>'text',   'label'=>'STEP2 名称',  'default'=>'10月分 電気代・プランのご提出'],
                'step2_desc'  => ['type'=>'textarea','label'=>'STEP2 説明', 'default'=>'現在の電気代明細をご用意ください。最新1ヶ月分で試算いたします。'],
                'step2_image' => ['type'=>'image',   'label'=>'STEP2 画像', 'default'=>''],
                'step3_name'  => ['type'=>'text',   'label'=>'STEP3 名称',  'default'=>'ご提案・ご成約内容のご判断'],
                'step3_desc'  => ['type'=>'textarea','label'=>'STEP3 説明', 'default'=>'削減シミュレーションをご提示します。納得いただいてからご契約となります。'],
                'step3_image' => ['type'=>'image',   'label'=>'STEP3 画像', 'default'=>''],
                'step4_name'  => ['type'=>'text',   'label'=>'STEP4 名称',  'default'=>'申込書を基に できるなら早く切り替え手続きを実施'],
                'step4_desc'  => ['type'=>'textarea','label'=>'STEP4 説明', 'default'=>'手続きはほぼ弊社で代行。お客様のご負担は最小限です。'],
                'step4_image' => ['type'=>'image',   'label'=>'STEP4 画像', 'default'=>''],
                'step5_name'  => ['type'=>'text',   'label'=>'STEP5 名称',  'default'=>'電力会社の切り替え完了！'],
                'step5_desc'  => ['type'=>'textarea','label'=>'STEP5 説明', 'default'=>'切り替え完了後も専任担当者が継続サポート。安心してお任せください。'],
                'step5_image' => ['type'=>'image',   'label'=>'STEP5 画像', 'default'=>''],
            ],
        ],
        'faq' => [
            'label'   => 'FAQセクション',
            'icon'    => '❓',
            'enabled' => true,
            'order'   => 11,
            'fields'  => [
                'section_title' => ['type'=>'text','label'=>'セクションタイトル','default'=>'よくある質問'],
                'faq1_q' => ['type'=>'text',    'label'=>'Q①',  'default'=>'切り替えによって停電のリスクはありませんか？'],
                'faq1_a' => ['type'=>'textarea','label'=>'A①',  'default'=>'ご安心ください。電線やガス管などのインフラは変わりません。今まで通りご利用いただけます。'],
                'faq2_q' => ['type'=>'text',    'label'=>'Q②',  'default'=>'切り替えの手続きは難しくないですか？'],
                'faq2_a' => ['type'=>'textarea','label'=>'A②',  'default'=>'手続きはほぼ弊社で代行いたします。お客様にご用意いただくものは電気代の明細のみです。'],
                'faq3_q' => ['type'=>'text',    'label'=>'Q③',  'default'=>'初期費用や解約費用はかかりますか？'],
                'faq3_a' => ['type'=>'textarea','label'=>'A③',  'default'=>'初期費用・解約費用は一切かかりません。安心してご相談ください。'],
                'faq4_q' => ['type'=>'text',    'label'=>'Q④',  'default'=>'どのくらい電気代が安くなりますか？'],
                'faq4_a' => ['type'=>'textarea','label'=>'A④',  'default'=>'現在の使用量やプランによって異なりますが、平均で月額10〜30%の削減が見込めます。'],
                'faq5_q' => ['type'=>'text',    'label'=>'Q⑤',  'default'=>'賃貸物件でも切り替えできますか？'],
                'faq5_a' => ['type'=>'textarea','label'=>'A⑤',  'default'=>'高圧一括受電の場合を除き、ほとんどの物件で切り替え可能です。まずはご相談ください。'],
            ],
        ],
        'cta_bottom' => [
            'label'   => '最終CTA',
            'icon'    => '📞',
            'enabled' => true,
            'order'   => 12,
            'fields'  => [
                'tagline'   => ['type'=>'text',    'label'=>'タグライン',  'default'=>'今すぐ無料診断を受ける'],
                'title'     => ['type'=>'text',    'label'=>'タイトル',    'default'=>'電気料金が\n安くなるか確認する'],
                'desc'      => ['type'=>'textarea','label'=>'説明文',      'default'=>'まずはお気軽にご相談ください。専任スタッフが丁寧にご対応いたします。'],
                'tel'       => ['type'=>'text',    'label'=>'電話番号',    'default'=>'0120-000-000'],
                'tel_label' => ['type'=>'text',    'label'=>'電話番号ラベル','default'=>'受付時間 平日 9:00〜18:00'],
                'btn_text'  => ['type'=>'text',    'label'=>'ボタンテキスト','default'=>'無料診断フォームはこちら'],
                'btn_url'   => ['type'=>'url',     'label'=>'ボタンURL',    'default'=>'#contact'],
            ],
        ],
        'custom_1' => [
            'label'   => 'カスタムセクション①',
            'icon'    => '✏️',
            'enabled' => false,
            'order'   => 13,
            'fields'  => [
                'section_id' => ['type'=>'text', 'label'=>'セクションID（CSS・JSのセレクタ用）', 'default'=>'custom-section-1'],
                'html'       => ['type'=>'code', 'label'=>'HTML', 'default'=>''],
                'css'        => ['type'=>'code', 'label'=>'CSS',  'default'=>''],
                'js'         => ['type'=>'code', 'label'=>'JavaScript', 'default'=>''],
            ],
        ],
        'custom_2' => [
            'label'   => 'カスタムセクション②',
            'icon'    => '✏️',
            'enabled' => false,
            'order'   => 14,
            'fields'  => [
                'section_id' => ['type'=>'text', 'label'=>'セクションID（CSS・JSのセレクタ用）', 'default'=>'custom-section-2'],
                'html'       => ['type'=>'code', 'label'=>'HTML', 'default'=>''],
                'css'        => ['type'=>'code', 'label'=>'CSS',  'default'=>''],
                'js'         => ['type'=>'code', 'label'=>'JavaScript', 'default'=>''],
            ],
        ],
        'custom_3' => [
            'label'   => 'カスタムセクション③',
            'icon'    => '✏️',
            'enabled' => false,
            'order'   => 15,
            'fields'  => [
                'section_id' => ['type'=>'text', 'label'=>'セクションID（CSS・JSのセレクタ用）', 'default'=>'custom-section-3'],
                'html'       => ['type'=>'code', 'label'=>'HTML', 'default'=>''],
                'css'        => ['type'=>'code', 'label'=>'CSS',  'default'=>''],
                'js'         => ['type'=>'code', 'label'=>'JavaScript', 'default'=>''],
            ],
        ],
        'custom_4' => [
            'label'   => 'カスタムセクション④',
            'icon'    => '✏️',
            'enabled' => false,
            'order'   => 16,
            'fields'  => [
                'section_id' => ['type'=>'text', 'label'=>'セクションID（CSS・JSのセレクタ用）', 'default'=>'custom-section-4'],
                'html'       => ['type'=>'code', 'label'=>'HTML', 'default'=>''],
                'css'        => ['type'=>'code', 'label'=>'CSS',  'default'=>''],
                'js'         => ['type'=>'code', 'label'=>'JavaScript', 'default'=>''],
            ],
        ],
        'custom_5' => [
            'label'   => 'カスタムセクション⑤',
            'icon'    => '✏️',
            'enabled' => false,
            'order'   => 17,
            'fields'  => [
                'section_id' => ['type'=>'text', 'label'=>'セクションID（CSS・JSのセレクタ用）', 'default'=>'custom-section-5'],
                'html'       => ['type'=>'code', 'label'=>'HTML', 'default'=>''],
                'css'        => ['type'=>'code', 'label'=>'CSS',  'default'=>''],
                'js'         => ['type'=>'code', 'label'=>'JavaScript', 'default'=>''],
            ],
        ],
    ];

    // 全セクションに背景設定フィールドを追加
    foreach ( $sections as &$sec ) {
        $sec['fields']['bg_color'] = ['type' => 'color', 'label' => '背景カラー',  'default' => ''];
        $sec['fields']['bg_image'] = ['type' => 'image', 'label' => '背景画像',    'default' => ''];
    }
    unset( $sec );

    return $sections;
}
endif; // function_exists riff_lp_default_sections

// ============================================================
// ヘルパー関数：セクション設定取得
// ============================================================
function riff_lp_get_section_setting( $section_key, $field_key ) {
    $option_key = "riff_lp_{$section_key}_{$field_key}";
    $defaults   = riff_lp_default_sections();
    $default    = $defaults[ $section_key ]['fields'][ $field_key ]['default'] ?? '';
    $value      = get_option( $option_key, $default );
    return $value;
}

function riff_lp_get_section_order() {
    $defaults = riff_lp_default_sections();
    $saved    = get_option( 'riff_lp_section_order', [] );
    if ( empty( $saved ) ) {
        $order = array_keys( $defaults );
        return $order;
    }
    return $saved;
}

function riff_lp_is_section_enabled( $section_key ) {
    return (bool) get_option( "riff_lp_section_enabled_{$section_key}", true );
}

function riff_lp_get_image_url( $section_key, $field_key ) {
    $attachment_id = riff_lp_get_section_setting( $section_key, $field_key );
    if ( ! $attachment_id ) return '';
    return wp_get_attachment_image_url( $attachment_id, 'large' ) ?: '';
}

// セクション背景スタイルを style 属性として echo する
function riff_lp_section_bg_style_attr( $section_key ) {
    $color    = riff_lp_get_section_setting( $section_key, 'bg_color' );
    $image_id = riff_lp_get_section_setting( $section_key, 'bg_image' );

    $styles = [];
    if ( $color ) {
        $styles[] = 'background-color:' . esc_attr( $color );
    }
    if ( $image_id ) {
        $img_url = wp_get_attachment_image_url( (int) $image_id, 'full' );
        if ( $img_url ) {
            $styles[] = 'background-image:url(' . esc_url( $img_url ) . ')';
            $styles[] = 'background-size:cover';
            $styles[] = 'background-position:center center';
            $styles[] = 'background-repeat:no-repeat';
        }
    }

    if ( $styles ) {
        echo ' style="' . esc_attr( implode( ';', $styles ) ) . '"';
    }
}

// ============================================================
// カスタムロゴ or テキスト出力
// ============================================================
function riff_lp_get_header_brand_type() {
    $type = get_option( 'riff_lp_header_brand_type', 'title' );
    return in_array( $type, [ 'logo', 'title', 'both' ], true ) ? $type : 'title';
}

function riff_lp_get_header_logo_id() {
    return absint( get_option( 'riff_lp_header_logo', 0 ) );
}

function riff_lp_get_header_logo_url() {
    $logo_id = riff_lp_get_header_logo_id();
    if ( ! $logo_id ) {
        return '';
    }
    return wp_get_attachment_image_url( $logo_id, 'full' );
}

function riff_lp_get_header_show_tel() {
    return (bool) get_option( 'riff_lp_header_show_tel', 1 );
}

function riff_lp_get_header_menu_items() {
    $items = [];
    for ( $i = 1; $i <= 20; $i++ ) {
        $label = get_option( "riff_lp_header_menu_item_{$i}_label", '' );
        $url   = get_option( "riff_lp_header_menu_item_{$i}_url", '' );
        if ( $label ) {
            $items[] = [
                'label' => $label,
                'url'   => $url,
            ];
        }
    }
    return $items;
}

function riff_lp_the_logo() {
    $brand_type = riff_lp_get_header_brand_type();
    $logo_url   = riff_lp_get_header_logo_url();
    $site_name  = get_bloginfo( 'name' );
    $href       = esc_url( home_url( '/' ) );

    if ( $brand_type === 'both' && $logo_url ) {
        echo '<a class="site-logo site-logo--both" href="' . $href . '">';
        echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_name ) . '">';
        echo '<span class="logo-text">' . esc_html( $site_name ) . '</span>';
        echo '</a>';
    } elseif ( $brand_type === 'logo' && $logo_url ) {
        echo '<a class="site-logo" href="' . $href . '">';
        echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_name ) . '">';
        echo '</a>';
    } else {
        echo '<a class="site-logo" href="' . $href . '">';
        echo '<span class="logo-text">' . esc_html( $site_name ) . '</span>';
        echo '</a>';
    }
}

// フッターブランド
function riff_lp_get_footer_brand_type() {
    $type = get_option( 'riff_lp_footer_brand_type', 'title' );
    return in_array( $type, [ 'logo', 'title', 'both' ], true ) ? $type : 'title';
}

function riff_lp_get_footer_logo_id() {
    return absint( get_option( 'riff_lp_footer_logo', 0 ) );
}

function riff_lp_get_footer_logo_url() {
    $logo_id = riff_lp_get_footer_logo_id();
    if ( ! $logo_id ) return '';
    return wp_get_attachment_image_url( $logo_id, 'full' ) ?: '';
}

function riff_lp_the_footer_logo() {
    $brand_type = riff_lp_get_footer_brand_type();
    $logo_url   = riff_lp_get_footer_logo_url();
    $site_name  = get_bloginfo( 'name' );
    $href       = esc_url( home_url( '/' ) );

    if ( $brand_type === 'both' && $logo_url ) {
        echo '<a href="' . $href . '" class="footer-logo-both">';
        echo '<img class="footer-logo-img" src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_name ) . '">';
        echo '<span class="footer-logo-title">' . esc_html( $site_name ) . '</span>';
        echo '</a>';
    } elseif ( $brand_type === 'logo' && $logo_url ) {
        echo '<a href="' . $href . '">';
        echo '<img class="footer-logo-img" src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_name ) . '">';
        echo '</a>';
    } else {
        echo esc_html( $site_name );
    }
}

// ============================================================
// [riff_lp_section] ショートコード
// ============================================================
if ( ! function_exists( 'riff_lp_section_shortcode' ) ) :
function riff_lp_section_shortcode( $atts ) {
    $atts = shortcode_atts( [ 'key' => '' ], $atts, 'riff_lp_section' );
    $key  = sanitize_key( $atts['key'] );
    if ( ! $key ) return '';

    $sections = riff_lp_default_sections();
    if ( ! isset( $sections[ $key ] ) ) return '';

    ob_start();
    $section_key = $key;

    // 子テーマを優先して検索（locate_template は child → parent の順）
    $tpl = locate_template( "template-parts/lp-{$key}.php" );
    if ( ! $tpl ) {
        $tpl = RIFF_LP_DIR . "/template-parts/lp-{$key}.php";
    }

    if ( file_exists( $tpl ) ) {
        include $tpl;
    } elseif ( strpos( $key, 'custom_' ) === 0 ) {
        $tpl_custom = locate_template( 'template-parts/lp-custom.php' );
        if ( ! $tpl_custom ) {
            $tpl_custom = RIFF_LP_DIR . '/template-parts/lp-custom.php';
        }
        if ( file_exists( $tpl_custom ) ) {
            include $tpl_custom;
        }
    }

    return ob_get_clean();
}
endif; // function_exists riff_lp_section_shortcode
add_shortcode( 'riff_lp_section', 'riff_lp_section_shortcode' );

// ============================================================
// SNS ヘルパー
// ============================================================
function riff_lp_sns_platforms() {
    return [
        'x'         => 'X (Twitter)',
        'instagram' => 'Instagram',
        'tiktok'    => 'TikTok',
        'facebook'  => 'Facebook',
        'threads'   => 'Threads',
        'line'      => 'LINE',
    ];
}

function riff_lp_sns_icon( $platform ) {
    $icons = [
        'x'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.742l7.733-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
        'tiktok'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05A6.34 6.34 0 003.15 15.3a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V8.69a8.27 8.27 0 004.84 1.55V6.79a4.85 4.85 0 01-1.08-.1z"/></svg>',
        'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'threads'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.472 12.01v-.017c.03-3.579.858-6.43 2.523-8.482C5.852 1.206 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.04.569c-1.104-3.96-3.898-5.984-8.304-6.015-2.91.022-5.11.936-6.54 2.717C4.307 6.504 3.616 8.914 3.589 12c.027 3.086.718 5.496 2.057 7.164 1.43 1.783 3.631 2.698 6.54 2.717 2.623-.02 4.358-.631 5.8-2.045 1.647-1.613 1.618-3.593 1.09-4.798-.31-.71-.873-1.3-1.634-1.75-.192 1.352-.622 2.446-1.284 3.272-.886 1.102-2.14 1.704-3.73 1.79-1.202.065-2.361-.218-3.259-.801-1.063-.689-1.685-1.74-1.752-2.964-.065-1.19.408-2.285 1.33-3.082.88-.76 2.119-1.207 3.583-1.291a13.853 13.853 0 013.02.142c-.126-.742-.375-1.332-.75-1.757-.513-.586-1.288-.883-2.299-.89h-.029c-.803 0-1.88.199-2.56.896l-1.699-1.327c.87-1.157 2.251-1.849 3.938-1.859h.033c3.164.023 5.028 1.988 5.188 5.396.41.092.811.214 1.198.368 1.968.771 3.303 2.133 3.949 4.037.987 2.913.217 6.111-2.02 8.282C18.304 23.252 15.775 24 12.186 24zm-.224-8.66c1.307-.065 2.11-.72 2.376-1.919a7.942 7.942 0 00.148-1.481c-.877-.218-1.771-.326-2.654-.319-1.89.11-2.831.941-2.766 2.086.04.72.48 1.576 2.124 1.636.257.009.515.006.772-.003z"/></svg>',
        'line'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></svg>',
    ];
    return $icons[ $platform ] ?? '';
}

function riff_lp_get_sns_items() {
    $platforms = riff_lp_sns_platforms();
    $all_keys  = array_keys( $platforms );
    $saved_order = get_option( 'riff_lp_sns_order', [] );
    // Merge saved order with any new platforms not yet in it
    $order = array_values( array_unique( array_merge(
        array_intersect( $saved_order, $all_keys ),
        $all_keys
    ) ) );

    $items = [];
    foreach ( $order as $key ) {
        if ( ! isset( $platforms[ $key ] ) ) continue;
        $show = (bool) get_option( "riff_lp_sns_{$key}_show", false );
        if ( $show ) {
            $items[ $key ] = [
                'label' => $platforms[ $key ],
                'url'   => get_option( "riff_lp_sns_{$key}_url", '' ),
            ];
        }
    }
    return $items;
}

function riff_lp_the_sns_icons( $items, $extra_class = '' ) {
    if ( empty( $items ) ) return;
    $cls = trim( 'sns-icons ' . $extra_class );
    echo '<div class="' . esc_attr( $cls ) . '">';
    foreach ( $items as $key => $item ) {
        if ( ! empty( $item['url'] ) ) {
            echo '<a href="' . esc_url( $item['url'] ) . '"'
                . ' class="sns-icon sns-icon--' . esc_attr( $key ) . '"'
                . ' target="_blank" rel="noopener noreferrer"'
                . ' aria-label="' . esc_attr( $item['label'] ) . '">';
            echo riff_lp_sns_icon( $key );
            echo '</a>';
        } else {
            echo '<span class="sns-icon sns-icon--' . esc_attr( $key ) . ' sns-icon--no-link"'
                . ' aria-label="' . esc_attr( $item['label'] ) . '">';
            echo riff_lp_sns_icon( $key );
            echo '</span>';
        }
    }
    echo '</div>';
}

// ============================================================
// ページタイトルタグ出力
// ============================================================
function riff_lp_wp_title( $title, $sep ) {
    global $paged, $page;
    if ( is_feed() ) return $title;
    $title .= get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
        $title .= " $sep $site_description";
    }
    if ( $paged >= 2 || $page >= 2 ) {
        $title .= " $sep " . sprintf( __( 'Page %s', 'riff-lp' ), max( $paged, $page ) );
    }
    return $title;
}
add_filter( 'wp_title', 'riff_lp_wp_title', 10, 2 );
