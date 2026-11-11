<?php
/**
 * Section: 安心バッジ帯
 */
$k = 'trust';
$items = [
    [ riff_lp_get_section_setting( $k, 'item1_icon' ), riff_lp_get_section_setting( $k, 'item1_text' ) ],
    [ riff_lp_get_section_setting( $k, 'item2_icon' ), riff_lp_get_section_setting( $k, 'item2_text' ) ],
    [ riff_lp_get_section_setting( $k, 'item3_icon' ), riff_lp_get_section_setting( $k, 'item3_text' ) ],
];
?>
<!-- ============================================================
     安心バッジ帯
     ============================================================ -->
<div class="trust-band" role="complementary" aria-label="信頼性ポイント"<?php riff_lp_section_bg_style_attr( $section_key ); ?>>
    <div class="container">
        <?php foreach ( $items as $item ) : if ( ! $item[1] ) continue; ?>
        <div class="trust-item">
            <div class="trust-icon"><?php echo esc_html( $item[0] ); ?></div>
            <span><?php echo esc_html( $item[1] ); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>
