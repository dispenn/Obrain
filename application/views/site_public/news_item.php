<div class="container">
<div class="gap gap-small"></div>
            <div class="row">
                <h2><?php echo $news['title'];?></h2>
                    <!-- BLOG POST -->
                            <article class="post">
                            <div class="col-md-4">
                                <div class="row row-no-gutter" id="popup-gallery">
                                    <a class="hover-img popup-gallery-image" href="<?php echo base_url() . substr($news['path'], 2); ?>" data-effect="mfp-zoom-out">
                                        <img src="<?php echo base_url() . substr($news['path'], 2); ?>" alt="" title="" /><i class="fa fa-resize-full hover-icon"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="post-inner">
                                    <ul class="post-meta">
                                        <li><i class="fa fa-calendar"></i><a href=""> <?php echo $news['date']; ?></a>
                                        </li>
                                    </ul>
                                    <p class="post-desciption"><?php echo $news['full_text'];?></p>
                                </div>
                            </div>
                            </article>
                    
                    <div class="gap"></div>
            </div>
</div>