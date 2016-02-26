<div class="container">
<div class="gap gap-small"></div>
            <div class="row row-wrap">
                <h2><?php echo $department['title']; ?></h2>
                <?php if(!empty($employees)): ?>
                    <?php foreach($employees as $employee): ?>
                        <div class="col-md-3">
                            <div class="team-member">
                                <img src="<?php echo base_url() . substr($employee['path'], 1); ?>" alt="" />
                                <h4><?php echo $employee['name']; ?></h4>
                                <p class="team-member-position"><?php echo $employee['post']; ?></p>
                                <?php if($employee['phone'] != ""): ?>
                                <p class="team-member-desciption">Тел. <?php echo $employee['phone']; ?></p>
                                <?php endif; ?>
                                <?php if($employee['email'] != ""): ?>
                                <p class="team-member-desciption"><a href="mailto:<?php echo $employee['email']; ?>"><?php echo $employee['email']; ?></a></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
            </div>
            <div class="gap gap-small"></div>
        </div>