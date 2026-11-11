<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$header_menu_items = riff_lp_get_header_menu_items();
$has_menu          = ! empty( $header_menu_items );
$btn_text          = get_option( 'riff_lp_header_btn_text', '無料診断はこちら' );
$btn_url           = get_option( 'riff_lp_header_btn_url', '#contact' );
?>

<!-- ============================================================
     サイトヘッダー
     ============================================================ -->
<header class="site-header<?php echo $has_menu ? ' site-header--has-menu' : ''; ?>" role="banner">
    <div class="container">
        <?php riff_lp_the_logo(); ?>

        <!-- デスクトップナビ -->
        <nav class="header-right" role="navigation" aria-label="ヘッダーナビゲーション">
            <?php if ( $has_menu ) : ?>
                <ul class="header-menu">
                    <?php foreach ( $header_menu_items as $item ) : ?>
                        <li>
                            <?php if ( ! empty( $item['url'] ) ) : ?>
                                <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                            <?php else : ?>
                                <span><?php echo esc_html( $item['label'] ); ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ( riff_lp_get_header_show_tel() ) : ?>
            <div class="header-tel">
                <div class="tel-label"><?php echo esc_html( get_option( 'riff_lp_header_tel_label', '受付時間 平日 9:00〜18:00' ) ); ?></div>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^\d]/', '', get_option( 'riff_lp_header_tel', '0120-000-000' ) ) ); ?>"
                   class="tel-number">
                    <?php echo esc_html( get_option( 'riff_lp_header_tel', '0120-000-000' ) ); ?>
                </a>
            </div>
            <?php endif; ?>

            <a href="<?php echo esc_url( $btn_url ); ?>" class="btn btn--primary">
                <?php echo esc_html( $btn_text ); ?>
            </a>
        </nav>

        <!-- ハンバーガーボタン（メニューあり・スマホのみ表示） -->
        <?php if ( $has_menu ) : ?>
        <button class="hamburger-btn" id="hamburger-btn"
                aria-label="メニューを開く" aria-expanded="false" aria-controls="mobile-menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <?php endif; ?>
    </div>

    <!-- モバイルメニュードロワー（メニューあり時のみ） -->
    <?php if ( $has_menu ) : ?>
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true">
        <ul class="mobile-menu-nav">
            <?php foreach ( $header_menu_items as $item ) : ?>
                <li>
                    <?php if ( ! empty( $item['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>" class="mobile-menu-link">
                            <?php echo esc_html( $item['label'] ); ?>
                        </a>
                    <?php else : ?>
                        <span class="mobile-menu-link mobile-menu-link--text">
                            <?php echo esc_html( $item['label'] ); ?>
                        </span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="mobile-menu-cta">
            <a href="<?php echo esc_url( $btn_url ); ?>" class="btn btn--primary btn--full">
                <?php echo esc_html( $btn_text ); ?>
            </a>
        </div>
        <?php
        $sns_items = riff_lp_get_sns_items();
        if ( ! empty( $sns_items ) ) :
            riff_lp_the_sns_icons( $sns_items, 'mobile-menu-sns' );
        endif;
        ?>
    </div>
    <?php endif; ?>
</header>
