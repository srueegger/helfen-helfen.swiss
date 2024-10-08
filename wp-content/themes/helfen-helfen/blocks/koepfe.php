<?php
// Create id attribute allowing for custom "anchor" value.
$id = 'koepfe-' . $block['id'];
if( !empty($block['anchor']) ) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'koepfe';
if( !empty($block['className']) ) {
	$className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
	$className .= ' align' . $block['align'];
}
?>
<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="row no-gutters">
		<?php
		$args = array(
			'numberposts' => -1,
			'post_type' => 'koepfe',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_status' => 'publish'
		);
		$koepfe = get_posts($args);
		if(!empty($koepfe)) {
			global $post;
			foreach($koepfe as $post) {
				setup_postdata( $post );
				$typ = get_field('kopf_typ', get_the_ID());
				if(!$typ) {
					/* Normaler Kopf ausgeben */
					$image = get_field('kopf_image', get_the_ID());
					?>
					<div class="col-12 col-sm-6 col-xl-4 kopfItem">
						<picture>
							<source srcset="<?php echo $image['sizes']['kopf']; ?> 1x, <?php echo $image['sizes']['kopf2x']; ?> 2x">
							<img data-object-fit="cover" src="<?php echo $image['sizes']['kopf']; ?>" loading="lazy" alt="<?php echo $image['alt']; ?>" />
						</picture>
						<div class="overlay"></div>
						<div class="titleTxt">
							<?php the_title('<h2>', '</h2>'); ?>
							<p class="mb-0"><?php the_field('kopf_job', get_the_ID()); ?></p>
						</div>
						<div class="quoteInfos">
							<div class="quote w-75 mx-auto mb-5">
								<blockquote class="blockquote">
									<p class="mb-0 text-center text-white">"<?php the_field('kopf_quote', get_the_ID()); ?>"</p>
								</blockquote>
							</div>
							<div class="infoContent ml-4">
								<a href="mailto:<?php the_field('kopf_mail', get_the_ID()); ?>">
									<i class="fas fa-envelope fa-inverse fa-2x fa-fw"></i>
								</a>
								<?php if(get_field('kopf_sm', get_the_ID()) != -1) { ?>
								<a href="<?php the_field('kopf_sm_link', get_the_ID()); ?>" target="_blank">
									<i class="fab fa-<?php the_field('kopf_sm', get_the_ID()); ?> fa-2x fa-fw fa-inverse"></i>
								</a>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php
				} else {
					/* Textbox ausgeben */
					?>
					<div class="col-12 col-sm-6 col-xl-4 kopfItem txtItem <?php the_field('kopf_txt_color', get_the_ID()); ?>">
						<h3><?php the_field('kopf_txt_title1', get_the_ID()); ?></h3>
						<h4><?php the_field('kopf_txt_title2', get_the_ID()); ?></h4>
						<?php
						$link = get_field('kopf_txt_link', get_the_ID());
						$link_target = $link['target'] ? $link['target'] : '_self';
						?>
						<a href="<?php echo $link['url']; ?>" target="<?php echo $link_target; ?>" class="hh-btn">
							<div class="fullpageArrowBottom">
								<i class="fas fa-angle-right fa-3x"></i>
							</div>
						</a>
					</div>
					<?php
				}
			}
			wp_reset_postdata();
		}
		?>
	</div>
</div>