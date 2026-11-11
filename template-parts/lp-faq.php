<?php
/**
 * Section: FAQ
 */
$k             = 'faq';
$section_title = riff_lp_get_section_setting( $k, 'section_title' );

$faqs = [];
for ( $i = 1; $i <= 5; $i++ ) {
    $q = riff_lp_get_section_setting( $k, "faq{$i}_q" );
    $a = riff_lp_get_section_setting( $k, "faq{$i}_a" );
    if ( $q && $a ) {
        $faqs[] = [ 'q' => $q, 'a' => $a ];
    }
}
?>
<!-- ============================================================
     FAQセクション
     ============================================================ -->
<section class="lp-section faq-section" id="faq"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <?php if ( $section_title ) : ?>
        <h2 class="section-title section-title--primary" data-animate="fade-up">
            <?php echo esc_html( $section_title ); ?>
        </h2>
        <?php endif; ?>

        <dl class="faq-list" aria-label="よくある質問">
            <?php foreach ( $faqs as $i => $faq ) : ?>
            <div class="faq-item" data-animate="fade-up">
                <dt class="faq-q" role="button" tabindex="0"
                    aria-expanded="false"
                    aria-controls="faq-answer-<?php echo esc_attr( $i ); ?>">
                    <span class="faq-q-icon" aria-hidden="true">Q</span>
                    <span class="faq-q-text"><?php echo esc_html( $faq['q'] ); ?></span>
                    <span class="faq-arrow" aria-hidden="true">▼</span>
                </dt>
                <dd class="faq-a" id="faq-answer-<?php echo esc_attr( $i ); ?>" hidden>
                    <div class="faq-a-wrap">
                        <span class="faq-a-icon" aria-label="回答">A</span>
                        <p class="faq-a-text"><?php echo nl2br( esc_html( $faq['a'] ) ); ?></p>
                    </div>
                </dd>
            </div>
            <?php endforeach; ?>
        </dl>
    </div>
</section>
