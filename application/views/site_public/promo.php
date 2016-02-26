<div class="container">
<div class="gap gap-small"></div>
            <div class="row">
                <h2>Акции и предложения</h2>
                    <!-- BLOG POST -->
                    <?php if(!empty($list)): ?>
                        <?php foreach ($list as $list_value): ?>
                            <article class="post">
                            <div class="col-md-4">
                                <div class="post-image">
                                    <a class="hover-img" href="<?php echo base_url() . 'promo/' . $list_value['alias']; ?>">
                                        <img src="<?php echo base_url() . substr($list_value['path'], 2); ?>" alt="" title="" /><i class="fa fa-link hover-icon"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="post-inner">
                                    <h4 class="post-title"><a href="<?php echo base_url() . 'promo/' . $list_value['alias']; ?>"><?php echo $list_value['title'];?></a></h4>
                                    <ul class="post-meta">
                                        <li><i class="fa fa-calendar"></i><a href="<?php echo base_url() . 'promo/' . $list_value['alias']; ?>"> <?php echo $list_value['date']; ?></a>
                                        </li>
                                    </ul>
                                    <p class="post-desciption"><?php echo $list_value['short_text'];?></p><a class="btn btn-small btn-primary" href="<?php echo base_url() . 'promo/' . $list_value['alias']; ?>">Посмотреть условия</a>
                                </div>
                            </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php echo $this->pagination->create_links(); ?>
                    <div class="gap"></div>
            </div>
</div>