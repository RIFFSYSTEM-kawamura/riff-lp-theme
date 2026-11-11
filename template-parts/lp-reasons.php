<?php
/**
 * Section: 選ばれる3つの理由
 */
$k             = 'reasons';
$section_label = riff_lp_get_section_setting( $k, 'section_label' );
$section_title = riff_lp_get_section_setting( $k, 'section_title' );

$reasons = [];
for ( $i = 1; $i <= 3; $i++ ) {
    $reasons[] = [
        'title' => riff_lp_get_section_setting( $k, "r{$i}_title" ),
        'desc'  => riff_lp_get_section_setting( $k, "r{$i}_desc" ),
        'image' => riff_lp_get_image_url( $k, "r{$i}_image" ),
        'num'   => str_pad( $i, 2, '0', STR_PAD_LEFT ),
    ];
}
?>
<!-- ============================================================
     選ばれる3つの理由
     ============================================================ -->
<section class="lp-section reasons-section" id="reasons"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <?php if ( $section_label ) : ?>
        <span class="riff-label"><?php echo esc_html( $section_label ); ?></span>
        <?php endif; ?>
        <?php if ( $section_title ) : ?>
        <h2 class="section-title section-title--primary" data-animate="fade-up">
            <?php echo nl2br( esc_html( $section_title ) ); ?>
        </h2>
        <?php endif; ?>

        <div class="reasons-grid">
            <?php foreach ( $reasons as $reason ) : ?>
            <article class="reason-card" data-animate="fade-up">
                <div class="reason-card-img">
                    <?php if ( $reason['image'] ) : ?>
                    <img src="<?php echo esc_url( $reason['image'] ); ?>"
                         alt="理由<?php echo esc_attr( $reason['num'] ); ?>の画像"
                         loading="lazy">
                    <?php else : ?>
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#e3f0ff;">
                        <span style="font-size:3rem;">⭐</span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="reason-card-body">
                    <div class="reason-num"><?php echo esc_html( $reason['num'] ); ?></div>
                    <h3 class="reason-card-title"><?php echo nl2br( esc_html( $reason['title'] ) ); ?></h3>
                    <p class="reason-card-desc"><?php echo nl2br( esc_html( $reason['desc'] ) ); ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
