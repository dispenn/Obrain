<!-- //////////////////////////////////
	//////////////MAIN HEADER///////////// 
	////////////////////////////////////-->
        
        <header class="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-10">
                        <!-- MAIN NAVIGATION -->
                        <div class="flexnav-menu-button" id="flexnav-menu-button">Меню</div>
                        <nav>
                            <ul class="nav nav-pills flexnav" id="flexnav" data-breakpoint="800">
                                <li class="<?php if ($this->uri->segment(1) == 'about') {echo 'active';}?>"><a href="<?php echo base_url('about');?>">О компании</a></li>
                                <li class="<?php if ($this->uri->segment(1) == 'catalog' || $this->uri->segment(1) == 'catalog_inner') {echo 'active';}?>"><a href="<?php echo base_url('catalog');?>">Продукция</a></li>
                                <li class="<?php if ($this->uri->segment(1) == 'promo') {echo 'active';}?>"><a href="<?php echo base_url('promo');?>">Акции и предложения</a></li>
                                <li class="<?php if ($this->uri->segment(1) == 'news' || $this->uri->segment(1) == 'articles' || $this->uri->segment(1) == 'anketa') {echo 'active';}?>"><a href="#">Пресс-центр <i class="fa fa-angle-down"></i></a>
                                    <ul>
                                        <li><a href="<?php echo base_url('news');?>">Новости</a>
                                        </li>
                                        <li><a href="<?php echo base_url('articles');?>">Публикации</a>
                                        </li>
                                        <li><a href="<?php echo base_url('anketa');?>">Анкета</a>
                                        </li>
                                        <li><a href="<?php echo base_url('vacancy');?>">Вакансии</a>
                                        </li>
                                        <li><a href="<?php echo base_url('gallery');?>">Фотоархив</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="<?php if ($this->uri->segment(1) == 'contacts' || $this->uri->segment(1) == 'department') {echo 'active';}?>"><a href="<?php echo base_url('contacts');?>">Контакты</a>
                                <ul>
                                   <li><a href="<?php echo base_url('contacts');?>">Контактная информация</a>
                                   </li>
                                   <?php if (!empty($this->departments)): ?>
                                    <?php foreach ($this->departments as $value): ?>
                                       <li><a href="<?php echo base_url() . 'department/' . $value['alias'];?>"><?php echo $value['title']; ?></a>
                                       </li>
                                    <?php endforeach; ?>
                                   <?php endif; ?>
                                </ul>
                                </li>
                            </ul>
                        </nav>
                        <!-- END MAIN NAVIGATION -->
                    </div>
                    <div class="col-md-2">
                        <!-- LOGIN REGISTER LINKS -->
                        <ul class="login-register">
                            <?php if ($this->ion_auth->logged_in()): ?>
                                <?php $user = $this->ion_auth->user()->row(); ?>
                                <li><a href="<?php echo base_url('login');?>"><i class="fa fa-sign-in"></i>Вход</a>
                                </li>
    						<?php else: ?>
                                <li><a class="popup-text" href="#login-dialog" data-effect="mfp-move-from-top"><i class="fa fa-sign-in"></i>Вход</a>
                                </li>
    						<?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- LOGIN REGISTER LINKS CONTENT -->
        <div id="login-dialog" class="mfp-with-anim mfp-hide mfp-dialog clearfix">
            <i class="fa fa-sign-in dialog-icon"></i>
            <h3>Вход</h3>
            <h5>Добрый день, введите e-mail и пароль</h5>
            
            <?php if ( ! empty($message)):?>
          <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Предупреждение!</strong> <div id="infoMessage"><?php echo $message;?></div>
          </div>
          <?php endif;?>
            
            <form onsubmit="return clickLoginButton(this);" action="<?php echo base_url() . 'login'; ?>" method="POST" class="dialog-form">
                <div class="form-group">
                    <label>E-mail</label>
                    <input onchange="clearValidateMessage()" oninput="clearValidateMessage()" id="identity" name="identity" type="text" placeholder="e-mail" class="form-control" required autofocus >
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input onchange="clearValidateMessage()" oninput="clearValidateMessage()" id="password" name="password" type="password" placeholder="******" class="form-control" required>
               </div>
               <div class="form-group">
                    <span id="validate_message" style="color: red;"></span>
               </div>
                <input type="submit" id="login_button" value="Войти" class="btn btn-primary">
            </form>
            <ul class="dialog-alt-links">
                
                <li><a class="popup-text" href="#password-recover-dialog" data-effect="mfp-zoom-out">Восстановить пароль</a>
                </li>
            </ul>
            
        </div>




        <div id="password-recover-dialog" class="mfp-with-anim mfp-hide mfp-dialog clearfix">
            <i class="icon-retweet dialog-icon"></i>
            <h3>Восстановление пароля</h3>
            <h5>Для восстановления пароля введите e-mail</h5>
            <form class="dialog-form">
                <label>E-mail</label>
                <input type="text" placeholder="email@domain.com" class="span12">
                <input type="submit" value="Восстановить" class="btn btn-primary">
            </form>
        </div>
        <!-- END LOGIN REGISTER LINKS CONTENT -->