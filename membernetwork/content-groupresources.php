<?php if(get_field('group_resources')): ?>
 
	<ul class="group">
 
	<?php while(has_sub_field('group_resources')): ?>
 
		<li>
			<span class="title"><a href="http://<?php the_sub_field('url'); ?>"><?php the_sub_field('title'); ?></a></span>
			<span class="description"><?php the_sub_field('description'); ?></span>
		</li>
 
	<?php endwhile; ?>
 
	</ul>
 
<?php endif; ?>