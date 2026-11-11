<?php
/**
 * Section: 5STEP
 */
$k             = 'steps';
$section_label = riff_lp_get_section_setting( $k, 'section_label' );
$section_title = riff_lp_get_section_setting( $k, 'section_title' );

$steps = [];
for ( $i = 1; $i <= 5; $i++ ) {
    $steps[] = [
        'num'   => $i,
        'name'  => riff_lp_get_section_setting( $k, "step{$i}_name" ),
        'desc'  => riff_lp_get_section_setting( $k, "step{$i}_desc" ),
        'image' => riff_lp_get_image_url( $k, "step{$i}_image" ),
    ];
}
?>
<!-- ============================================================
     5STEPセクション
     ============================================================ -->
<section class="lp-section steps-section" id="steps"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <?php if ( $section_label ) : ?>
        <span class="riff-label"><?php echo esc_html( $section_label ); ?></span>
        <?php endif; ?>
        <?php if ( $section_title ) : ?>
        <h2 class="section-title section-title--primary" data-animate="fade-up">
            <?php echo nl2br( esc_html( $section_title ) ); ?>
        </h2>
        <?php endif; ?>

        <ol class="steps-list" aria-label="手続きステップ">
            <?php foreach ( $steps as $step ) : if ( ! $step['name'] ) continue; ?>
            <li class="step-item" data-animate="fade-up">
                <div class="step-num-wrap">
                    <div class="step-num" aria-label="ステップ<?php echo esc_attr( $step['num'] ); ?>">
                        <span class="step-label">STEP</span>
                        <span class="step-n"><?php echo esc_html( $step['num'] ); ?></span>
                    </div>
                </div>
                <div class="step-body">
                    <h3 class="step-title">
                        <span class="step-name"><?php echo nl2br( esc_html( $step['name'] ) ); ?></span>
                    </h3>
                    <?php if ( $step['desc'] ) : ?>
                    <p class="step-desc"><?php echo nl2br( esc_html( $step['desc'] ) ); ?></p>
                    <?php endif; ?>
                    <?php if ( $step['image'] ) : ?>
                    <div class="step-img">
                        <img src="<?php echo esc_url( $step['image'] ); ?>"
                             alt="STEP<?php echo esc_attr( $step['num'] ); ?>イメージ"
                             loading="lazy">
                    </div>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
