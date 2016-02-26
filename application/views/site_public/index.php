       <div class="container">
       <div class="col-md-12">
				<?php if ($error = $this->session->flashdata('error')):?>
				<?php echo alert_message($error, 'danger');?>
			<?php elseif($success = $this->session->flashdata('success')):?>
			<?php echo alert_message($success, 'success');?>
		<?php elseif($success = $this->session->flashdata('info')):?>
		<?php echo alert_message($info, 'info');?>
	<?php elseif(validation_errors()):?>
	<?php echo alert_message(validation_errors(), 'danger');?>
<?php endif;?>
</div></div>
       
        <div class="top-area">
            <?php if(isset($banners) && !empty($banners)): ?>
            <?php foreach ($banners as $key => $banners_list): ?>
            <div class="owl-carousel owl-slider" id="owl-carousel-slider<?php if ($key != 1) echo $key; ?>" data-inner-pagination="true" data-white-pagination="true" data-nav="true">
                <?php foreach ($banners_list as $banner): ?>
                    <div>
                        <div class="bg-holder">
                            <?php if ($banner['link'] != ""): ?>
                                <a href="<?php echo $banner['link']; ?>"><img src="<?php echo base_url() . substr($banner['path'], 1);?>"  style="width: 100%;" /></a>
                            <?php else: ?>
                                <img src="<?php echo base_url() . substr($banner['path'], 1);?>" style="width: 100%;" />
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

        </div>



