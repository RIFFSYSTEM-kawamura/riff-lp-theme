<?php
/**
 * Section: お客様の声
 */
$k             = 'voices';
$section_title = riff_lp_get_section_setting( $k, 'section_title' );

$voices = [];
for ( $i = 1; $i <= 3; $i++ ) {
    $voices[] = [
        'text'     => riff_lp_get_section_setting( $k, "v{$i}_text" ),
        'meta'     => riff_lp_get_section_setting( $k, "v{$i}_meta" ),
        'industry' => riff_lp_get_section_setting( $k, "v{$i}_industry" ),
    ];
}
?>
<!-- ============================================================
     お客様の声
     ============================================================ -->
<section class="lp-section voices-section" id="voices"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <?php if ( $section_title ) : ?>
        <h2 class="section-title section-title--primary" data-animate="fade-up">
            <?php echo esc_html( $section_title ); ?>
        </h2>
        <?php endif; ?>

        <div class="voices-grid">
            <?php foreach ( $voices as $voice ) : if ( ! $voice['text'] ) continue; ?>
            <article class="voice-card" data-animate="fade-up">
                <div class="voice-stars">★★★★★</div>
                <p class="voice-text"><?php echo nl2br( esc_html( $voice['text'] ) ); ?></p>
                <div class="voice-meta"><?php echo esc_html( $voice['meta'] ); ?></div>
                <div class="voice-industry"><?php echo esc_html( $voice['industry'] ); ?></div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
