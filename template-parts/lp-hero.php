<?php
/**
 * Section: ヒーロー
 */
$k        = 'hero';
$badge    = riff_lp_get_section_setting( $k, 'badge_text' );
$title1   = riff_lp_get_section_setting( $k, 'title_line1' );
$title2   = riff_lp_get_section_setting( $k, 'title_line2' );
$num      = riff_lp_get_section_setting( $k, 'num_value' );
$unit     = riff_lp_get_section_setting( $k, 'num_unit' );
$desc     = riff_lp_get_section_setting( $k, 'description' );
$btn_text = riff_lp_get_section_setting( $k, 'btn_text' );
$btn_url  = riff_lp_get_section_setting( $k, 'btn_url' );
$img_url  = riff_lp_get_image_url( $k, 'image' );
$s1n      = riff_lp_get_section_setting( $k, 'stat1_num' );
$s1u      = riff_lp_get_section_setting( $k, 'stat1_unit' );
$s1l      = riff_lp_get_section_setting( $k, 'stat1_label' );
$s2n      = riff_lp_get_section_setting( $k, 'stat2_num' );
$s2u      = riff_lp_get_section_setting( $k, 'stat2_unit' );
$s2l      = riff_lp_get_section_setting( $k, 'stat2_label' );
$s3n      = riff_lp_get_section_setting( $k, 'stat3_num' );
$s3u      = riff_lp_get_section_setting( $k, 'stat3_unit' );
$s3l      = riff_lp_get_section_setting( $k, 'stat3_label' );
?>
<!-- ============================================================
     ヒーローセクション
     ============================================================ -->
<section class="hero-section" id="hero"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <div class="hero-inner">
            <div class="hero-content">
                <?php if ( $badge ) : ?>
                <div class="hero-badge">
                    <span>⚡</span>
                    <span><?php echo esc_html( $badge ); ?></span>
                </div>
                <?php endif; ?>

                <h1 class="hero-title">
                    <?php if ( $title1 ) : ?>
                    <?php echo esc_html( $title1 ); ?><br>
                    <?php endif; ?>
                    <?php if ( $title2 ) : ?>
                    <span class="accent"><?php echo esc_html( $title2 ); ?></span>
                    <?php endif; ?>
                    <?php if ( $num ) : ?>
                    <br><span class="num"><?php echo esc_html( $num ); ?></span><span class="num-unit"><?php echo esc_html( $unit ); ?></span>
                    <?php endif; ?>
                </h1>

                <?php if ( $desc ) : ?>
                <p class="hero-desc"><?php echo nl2br( esc_html( $desc ) ); ?></p>
                <?php endif; ?>

                <?php if ( $btn_text && $btn_url ) : ?>
                <a href="<?php echo esc_url( $btn_url ); ?>" class="btn btn--primary btn--lg">
                    <?php echo esc_html( $btn_text ); ?> →
                </a>
                <p class="hero-cta-note">※ 完全無料・お断りOK</p>
                <?php endif; ?>
            </div>

            <?php if ( $img_url ) : ?>
            <div class="hero-img-wrap">
                <img src="<?php echo esc_url( $img_url ); ?>"
                     alt="ヒーローイメージ"
                     loading="eager">
            </div>
            <?php endif; ?>
        </div>

        <!-- 実績数値 -->
        <div class="hero-stats">
            <div class="hero-stat-item">
                <div class="stat-num"><?php echo esc_html( $s1n ); ?><span class="stat-unit"><?php echo esc_html( $s1u ); ?></span></div>
                <div class="stat-label"><?php echo esc_html( $s1l ); ?></div>
            </div>
            <div class="hero-stat-item">
                <div class="stat-num"><?php echo esc_html( $s2n ); ?><span class="stat-unit"><?php echo esc_html( $s2u ); ?></span></div>
                <div class="stat-label"><?php echo esc_html( $s2l ); ?></div>
            </div>
            <div class="hero-stat-item">
                <div class="stat-num"><?php echo esc_html( $s3n ); ?><span class="stat-unit"><?php echo esc_html( $s3u ); ?></span></div>
                <div class="stat-label"><?php echo esc_html( $s3l ); ?></div>
            </div>
        </div>
    </div>
</section>
