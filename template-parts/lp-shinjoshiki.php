<?php
/**
 * Section: 新常識バナー
 */
$k        = 'shinjoshiki';
$label    = riff_lp_get_section_setting( $k, 'label' );
$title    = riff_lp_get_section_setting( $k, 'title' );
$subtitle = riff_lp_get_section_setting( $k, 'subtitle' );
?>
<!-- ============================================================
     新常識バナー
     ============================================================ -->
<section class="shinjoshiki-banner" id="shinjoshiki" aria-label="新常識メッセージ"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <div class="shinjoshiki-inner" data-animate="fade-up">
            <?php if ( $label ) : ?>
            <p class="shinjoshiki-label"><?php echo esc_html( $label ); ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
            <h2 class="shinjoshiki-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            <?php if ( $subtitle ) : ?>
            <p class="shinjoshiki-sub"><?php echo nl2br( esc_html( $subtitle ) ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
