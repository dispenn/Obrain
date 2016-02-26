<div class="container">
<div class="gap gap-small"></div>
            <div class="row">
                <h2>Вакансии</h2>
                    <!-- BLOG POST -->
                    <?php if(!empty($vacancy)): ?>
                        <?php foreach ($vacancy as $vacancy_value): ?>
                            <article class="post">
                            <div class="col-md-8">
                                <div class="post-inner">
                                    <h4 class="post-title"><?php echo $vacancy_value['name'];?></h4>
                                    <ul class="post-meta">
                                        <li><?php echo $vacancy_value['salary']; ?> <i class="fa fa-rouble"></i> 
                                        </li>
                                    </ul>
                                    <?php echo $vacancy_value['desc'];?>
                                </div>
                            </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
            </div>
</div>