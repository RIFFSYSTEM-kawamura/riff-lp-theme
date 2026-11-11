<?php
/**
 * Section: 最終CTA
 */
$k         = 'cta_bottom';
$tagline   = riff_lp_get_section_setting( $k, 'tagline' );
$title     = riff_lp_get_section_setting( $k, 'title' );
$desc      = riff_lp_get_section_setting( $k, 'desc' );
$tel       = riff_lp_get_section_setting( $k, 'tel' );
$tel_label = riff_lp_get_section_setting( $k, 'tel_label' );
$btn_text  = riff_lp_get_section_setting( $k, 'btn_text' );
$btn_url   = riff_lp_get_section_setting( $k, 'btn_url' );
$tel_raw   = preg_replace( '/[^\d]/', '', $tel );
?>
<!-- ============================================================
     最終CTAセクション
     ============================================================ -->
<section class="cta-section" id="contact" aria-label="お問い合わせ"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <?php if ( $tagline ) : ?>
        <p class="cta-tagline"><?php echo esc_html( $tagline ); ?></p>
        <?php endif; ?>
        <?php if ( $title ) : ?>
        <h2 class="cta-title"><?php echo nl2br( esc_html( $title ) ); ?></h2>
        <?php endif; ?>
        <?php if ( $desc ) : ?>
        <p class="cta-desc"><?php echo nl2br( esc_html( $desc ) ); ?></p>
        <?php endif; ?>

        <?php if ( $tel ) : ?>
        <a href="tel:<?php echo esc_attr( $tel_raw ); ?>" class="cta-tel">
            📞 <?php echo esc_html( $tel ); ?>
        </a>
        <?php if ( $tel_label ) : ?>
        <p class="cta-tel-label"><?php echo esc_html( $tel_label ); ?></p>
        <?php endif; ?>
        <?php endif; ?>

        <?php if ( $btn_text && $btn_url ) : ?>
        <p class="cta-or">― または ―</p>
        <a href="<?php echo esc_url( $btn_url ); ?>"
           class="btn btn--primary btn--lg">
            <?php echo esc_html( $btn_text ); ?> →
        </a>
        <?php endif; ?>

        <p style="margin-top:20px;font-size:.8rem;color:rgba(255,255,255,.65);">
            ※ 完全無料・いつでも解約OK・押し売り一切なし
        </p>
    </div>
</section>
