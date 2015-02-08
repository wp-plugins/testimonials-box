<div id="testimonialBox" style="background-color:<?php echo $background_color; ?>">
	<div id="title"><?php echo $title; ?></div>
	<?php
		if($testimonial_image){
	?>
	<div id="testimonialImage"><img src="<?php echo $testimonial_image ?>"/></div>
	<?php
		} //end if($testimonial_image)
	?>
	<div id="name"><?php echo $name; ?></div>
	<div id="testimonial"><?php echo wpautop($testimonial); ?></div>
</div>
