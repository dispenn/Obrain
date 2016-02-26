<div class="container">
<div class="gap gap-small"></div>
            <div class="row">
                <h3>Фотоархив</h3>
                <div class="col-md-12">
                    <div class="row row-wrap">
                    
                        <?php if (isset($list) && !empty($list)): ?>
                            <?php foreach ($list as $key => $list_value): ?>
                                <div class="col-md-4">
                                    <div class="product-thumb">
                                        <header class="product-header">
                                        <a class="hover-img" href="<?php echo base_url() . 'gallery/' . $list_value['alias'];?>">
                                            <img src="<?php echo base_url() . substr($list_value['path'], 2) ?>" alt="" title="" /><i class="fa fa-link hover-icon"></i>
                                        </a>    
                                        </header>
                                        <div class="product-inner">
                                            <h5 class="product-title"><?php echo $list_value['title']; ?></h5>
                                            <p class="product-desciption"><?php echo $list_value['fulltext']; ?></p>
                                            <p class="product-location"><a class="btn btn-small btn-primary" href="<?php echo base_url() . 'gallery/' . $list_value['alias'];?>">Посмотреть фотографии</a></p>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                    <div class="gap gap-small"></div>

                </div>
            </div>

        </div>