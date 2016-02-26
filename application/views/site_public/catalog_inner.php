<div class="container">
<div class="gap gap-small"></div>
            <div class="row">
            <h3><?php echo $catalog['title'];?></h3>
                <div class="col-md-3">
                    <div class="product-page-meta box">
                        <?php echo $catalog['description'];?>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row row-no-gutter" id="popup-gallery">
                        <?php if(!empty($images)): ?>
                            <?php foreach ($images as $image): ?>
                                <div class="col-md-4">
                                    <!-- HOVER IMAGE -->
                                    <a class="hover-img popup-gallery-image" href="<?php echo base_url() . substr($image['path'], 1); ?>" data-effect="mfp-zoom-out">
                                        <img src="<?php echo base_url() . substr($image['path'], 1); ?>" alt="<?php echo $catalog['title'];?>" title="<?php echo $catalog['title'];?>" /><i class="fa fa-resize-full hover-icon"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                    <div class="gap gap-small"></div>
                </div>
            </div>

        </div>