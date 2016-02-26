<div class="container">
<div class="gap gap-small"></div>
            <div class="row">
                <h2>Продукция</h2>
                <div class="col-md-3">
                    <aside class="sidebar-left">
                        <ul class="nav nav-tabs nav-stacked nav-coupon-category">
                        <?php if(!empty($categoryes)): ?>
                            <?php foreach ($categoryes as $category) : ?>
                                <li <?php if ($category['alias'] == $alias) { echo 'class="active"'; } ?> ><a href="<?php echo base_url() . 'catalog/' . $category['alias']; ?>"><i class="<?php echo $category['icon']; ?>"></i><?php echo $category['title']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </ul>
                    </aside>
                </div>
                <div class="col-md-9" id="popup-gallery">
                <?php echo $form['text']; ?>
                <?php if (!empty($podcategory_list)): ?>
                    <?php foreach ($podcategory_list as $podcategory_value): ?>
                        <h3><?php echo $podcategory_value['title']; ?></h3>
                        <?php if(!empty($podcategory_value['catalog'])): ?>
                            <?php foreach ($podcategory_value['catalog'] as $catalog_value): ?>
                                <div class="product-thumb product-thumb-horizontal">
                                    <header class="product-header">
                                        <a class="hover-img popup-gallery-image" href="<?php echo base_url() . substr($catalog_value['path'], 2); ?>" data-effect="mfp-zoom-out">
                                            <img src="<?php echo base_url() . substr($catalog_value['path'], 2); ?>" alt="<?php echo $catalog_value['catalog_title']; ?>" title="<?php echo $catalog_value['catalog_title']; ?>" />
                                        </a>
                                    </header>
                                        <div class="product-inner">
                                        <h5 class="product-title"><?php echo $catalog_value['catalog_title']; ?></h5>
                                        <div class="product-desciption"><?php echo $catalog_value['description']; ?></div>
                                        
                                        <?php if(isset($catalog_value['images']) && $catalog_value['images'] != NULL): ?>
                                            <a class="btn btn-small btn-primary" href="<?php echo base_url() . 'catalog/' . $alias . '/' . $catalog_value['catalog_alias']; ?>">Фотогалерея</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php echo $this->pagination->create_links(); ?>
                <?php else: ?>
                      
                <?php endif; ?>
                </div>
            </div>

        </div>
        