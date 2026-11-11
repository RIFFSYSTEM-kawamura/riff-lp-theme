<!-- ============================================================
     サイトフッター
     ============================================================ -->
<?php $footer_sns = riff_lp_get_sns_items(); ?>
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-inner<?php echo ! empty( $footer_sns ) ? ' has-sns' : ''; ?>">
            <div class="footer-info">
                <div class="footer-logo">
                    <?php riff_lp_the_footer_logo(); ?>
                </div>
                <address class="footer-address">
                    <?php echo nl2br( esc_html( get_option( 'riff_lp_footer_address', '〒000-0000 東京都〇〇区〇〇' ) ) ); ?><br>
                    TEL: <?php echo esc_html( get_option( 'riff_lp_footer_tel', '0120-000-000' ) ); ?><br>
                    MAIL: <?php echo esc_html( get_option( 'riff_lp_footer_email', 'info@example.com' ) ); ?>
                </address>
            </div>

            <?php if ( ! empty( $footer_sns ) ) : ?>
            <?php riff_lp_the_sns_icons( $footer_sns, 'footer-sns' ); ?>
            <?php endif; ?>

            <nav class="footer-nav" aria-label="フッターナビゲーション">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'footer',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'fallback_cb'    => function() {
                        echo '<a href="' . esc_url( home_url('/') ) . '">ホーム</a>';
                        echo '<a href="' . esc_url( home_url('/privacy-policy') ) . '">プライバシーポリシー</a>';
                        echo '<a href="' . esc_url( home_url('/contact') ) . '">お問い合わせ</a>';
                    },
                ] );
                ?>
            </nav>
        </div>
        <p class="footer-copy">
            <?php echo esc_html( get_option( 'riff_lp_footer_copy', '© ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . ' All Rights Reserved.' ) ); ?>
        </p>
    </div>
</footer>

<!-- スクロールトップボタン -->
<button class="scroll-top" id="scrollTop" aria-label="ページトップへ戻る">▲</button>

<?php wp_footer(); ?>
</body>
</html>
