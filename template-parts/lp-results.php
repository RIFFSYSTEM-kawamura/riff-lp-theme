<?php
/**
 * Section: 実績・写真グリッド
 */
$k        = 'results';
$headline = riff_lp_get_section_setting( $k, 'headline' );
$sub      = riff_lp_get_section_setting( $k, 'sub' );
$images   = [];
for ( $i = 1; $i <= 8; $i++ ) {
    $images[] = riff_lp_get_image_url( $k, "image{$i}" );
}
$images = array_filter( $images );
?>
<!-- ============================================================
     実績・写真グリッド
     ============================================================ -->
<section class="lp-section results-section" id="results"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <?php if ( $headline ) : ?>
        <h2 class="results-headline" data-animate="fade-up">
            <?php echo nl2br( esc_html( $headline ) ); ?>
        </h2>
        <?php endif; ?>
        <?php if ( $sub ) : ?>
        <p class="results-sub" data-animate="fade-up"><?php echo esc_html( $sub ); ?></p>
        <?php endif; ?>

        <?php if ( ! empty( $images ) ) : ?>
        <div class="results-photo-grid" data-animate="fade-up">
            <?php foreach ( $images as $img_url ) : ?>
            <div class="results-photo-item">
                <img src="<?php echo esc_url( $img_url ); ?>"
                     alt="実績写真"
                     loading="lazy">
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <!-- 写真未設定時のプレースホルダー -->
        <div class="results-photo-grid" data-animate="fade-up">
            <?php for ( $i = 0; $i < 8; $i++ ) : ?>
            <div class="results-photo-item" style="display:flex;align-items:center;justify-content:center;">
                <span style="color:#aaa;font-size:.8rem;">写真 <?php echo $i + 1; ?></span>
            </div>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
