<?php
/**
 * Section: 98%セクション
 */
$k        = 'percent';
$num      = riff_lp_get_section_setting( $k, 'num' );
$label    = riff_lp_get_section_setting( $k, 'label' );
$headline = riff_lp_get_section_setting( $k, 'headline' );
$body     = riff_lp_get_section_setting( $k, 'body' );
?>
<!-- ============================================================
     98%セクション
     ============================================================ -->
<section class="lp-section percent-section" id="percent"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <div class="percent-inner">
            <div class="percent-num-wrap" data-animate="fade-right">
                <span class="percent-num js-count" data-target="<?php echo esc_attr( $num ); ?>">0</span>
                <?php if ( $label ) : ?>
                <p class="percent-label"><?php echo esc_html( $label ); ?></p>
                <?php endif; ?>
            </div>
            <div class="percent-text" data-animate="fade-left">
                <?php if ( $headline ) : ?>
                <h2 class="headline">
                    <?php echo nl2br( esc_html( $headline ) ); ?>
                </h2>
                <?php endif; ?>
                <?php if ( $body ) : ?>
                <p class="body"><?php echo nl2br( esc_html( $body ) ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
