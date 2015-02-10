<div class="subheader">
	<ul class="rslides">
		<?php if(get_field('top_rotating_images'))
			{
				while(has_sub_field('top_rotating_images'))
				{?>
				<li>
					<img src="<?php echo get_sub_field('the_image')?>" alt="" title="">
					<div class="container">
						<div class="page_title_container">
							 <div class="page_title">
								<h1><?php if(is_404()){echo "OOPS!";} else {the_title();} ?></h1>
							</div>
						</div>
					</div>
				</li>
 
				<?
				}
			}
			else 
			{ ?>
				<li>
						<?php if(get_field('top_image')){ ?>
							<img src="<?php the_field('top_image'); ?>" alt="" title="">	
						<?php } else { ?>				
							<img src="<?php bloginfo('template_url'); ?>/images/int.jpg" alt="" title="">
						<?php } ?>	

					<div class="container">
						<div class="page_title_container">
							 <div class="page_title">
								<h1><?php if(is_404()){echo "OOPS!";} else {the_title();} ?></h1>
							</div>
						</div>
					</div>
				</li>

			<? } ?>

		

	</ul>
</div>