<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin panel</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <!-- bootstrap 3.0.2 -->
        <?php echo theme_css('bootstrap.min.css', true);?>
        <!-- font Awesome -->
        <?php echo theme_css('font-awesome.min.css', true);?>
        <!-- Ionicons -->
        <link href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" type="text/css" rel="stylesheet" />
        <!-- jvectormap -->
        <?php echo theme_css('jvectormap/jquery-jvectormap-1.2.2.css', true);?>
        <!-- datatables -->
        <?php echo theme_css('datatables/dataTables.bootstrap.css', true);?>
        <!-- fullCalendar -->
        <?php echo theme_css('fullcalendar/fullcalendar.css', true);?>
        <!-- Daterange picker -->
        <?php echo theme_css('daterangepicker/daterangepicker-bs3.css', true);?>
        <!-- Theme style -->
        <?php echo theme_css('AdminLTE.css', true);?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="<?=base_url('themes/site/css/lightbox.css')?>">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
        <!-- Lightbox -->
        <script src="<?=base_url('themes/site/js/lightbox.js')?>"></script>
        <script src="<?=base_url('themes/site/js/jquery.js')?>"></script>
        <!-- jQuery UI 1.10.3 
        <?php// echo theme_js('jquery-ui-1.10.3.min.js', true);?>-->
        
        <!-- Bootstrap -->
        <?php echo theme_js('bootstrap.min.js', true);?>
        <!-- Sparkline -->
        <?php echo theme_js('plugins/sparkline/jquery.sparkline.min.js', true);?>
        <!-- jvectormap -->
        <?php echo theme_js('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js', true);?>
        <?php echo theme_js('plugins/jvectormap/jquery-jvectormap-world-mill-en.js', true);?>
        <!-- fullCalendar -->
        <?php echo theme_js('plugins/fullcalendar/moment.min.js', true);?>
        <?php echo theme_js('plugins/fullcalendar/fullcalendar.js', true);?>
        <?php echo theme_js('plugins/fullcalendar/ru.js', true);?>
        
        <!-- File Upload -->
        <script type="text/javascript" src="<?=base_url()?>themes/js/jquery.damnUploader.js"></script>
        <script type="text/javascript" src="<?=base_url()?>themes/js/interafce.js"></script>
        <script type="text/javascript" src="<?=base_url()?>themes/js/uploader-setup.js"></script>
        
        <!-- DataTable Editable -->
        <script type="text/javascript" src="<?=base_url()?>themes/js/jquery.dataTables.editable.js"></script>
        
        <!-- daterangepicker -->
        <?php echo theme_js('plugins/daterangepicker/daterangepicker.js', true);?>

        <!-- AdminLTE App -->
        <?php echo theme_js('AdminLTE/app.js', true);?>
        
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <?php echo theme_js('AdminLTE/dashboard.js', true);?>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
        <script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
        <?php echo theme_css('magnific-popup/magnific-popup.css', true);?>
        <?php echo theme_js('plugins/magnific-popup/magnific-popup.js', true);?>
        <?php echo theme_js('plugins/jflickrfeed/jflickrfeed.js', true);?>
        <?php echo theme_js('theme.js', true);?>
        <?php echo theme_js('theme.plugins.js', true);?>
        <link rel="stylesheet" href="<?=base_url()?>themes/administrator/js/plugins/isotope/jquery.isotope.css">
        <?php echo theme_js('plugins/mediaelement/mediaelement-and-player.js', true);?>
        
    </head>

<body class="skin-blue">
      
        <?php if($this->ion_auth->logged_in()):?>
        <header class="header">
			<a class="navbar-brand text-center" style="height: 50px" href="<?php echo base_url('administrator');?>"><img src="" height="30"  alt="cms apptech" /></a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <?php if($this->ion_auth->is_admin()):?>
					<!-- Sidebar toggle button-->
					<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
				<?php endif;?>
                <?php 
                    $user = $this->ion_auth->user()->row();
                    $image = "";
                    if ($user->id_image != 0) {
                        $files = $this->basic_functions_model->select(array('table' => 'all_files', 'type' => 'list', 'where_field' => 'id', 'where' => $user->id_image));
                        if (!empty($files)) {
                            $image = $files[0]['path'];
                        }
                    }
                ?>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
						<?php // if($this->ion_auth->is_admin()):?>
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="glyphicon glyphicon-user"></i>
									<span><?php echo $user->username; ?><i class="caret"></i></span>
								</a>
								<ul class="dropdown-menu">
									<!-- User image -->
									<li class="user-header bg-light-blue">
                                        <?php if ($image != ""): ?>
                                        <img src="<?php echo base_url() . substr($image, 1);?>" class="img-circle" alt="User Image" />
                                        <?php else: ?>
										<img src="<?php echo theme_img('avatar04.png');?>" class="img-circle" alt="User Image" />
										<?php endif; ?>
                                        <p>
											<?php echo $user->username; ?>
										</p>
									</li>
									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-right">
											<a href="<?=base_url('logout')?>" class="btn btn-default btn-flat">Выйти</a>
										</div>
									</li>
								</ul>
							</li>
						<?php // else:?>
                        <?php /*
							<li class="dropdown user user-menu">
								<a href="<?=base_url('logout')?>">
									<i class="glyphicon glyphicon-user"></i>
									<span>Выйти</span>
								</a>
							</li>
						*/ ?>
                        <?php // endif;?>
                    </ul>
                </div>
            </nav>
        </header>
      <?php endif?>
        <div class="wrapper row-offcanvas row-offcanvas-left">
          <?php if($this->ion_auth->is_admin()):?>
            <aside class="left-side sidebar-offcanvas">
                    <!-- sidebar: style can be found in sidebar.less -->
                    <section class="sidebar">
                        <!-- sidebar menu: : style can be found in sidebar.less -->
                        <ul class="sidebar-menu">
                            <?php if ($this->ion_auth->is_admin()):?>
                                <li class="treeview active">
                                    <a href="#">
                                        <i class="fa fa-suitcase"></i> <span>Блоки</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                      <li><a href="<?=base_url('administrator/about')?>"><i class="fa fa-angle-double-right"></i> О компании</a></li>        
                                      <li><a href="<?=base_url('administrator/main_images')?>"><i class="fa fa-angle-double-right"></i> Изображения для сайта</a></li>    
                                      <li><a href="<?=base_url('administrator/promos')?>"><i class="fa fa-angle-double-right"></i> Акции</a></li>      
                                      <li><a href="<?=base_url('administrator/banners')?>"><i class="fa fa-angle-double-right"></i> Баннеры</a></li>      
                                      <li><a href="<?=base_url('administrator/contact_info')?>"><i class="fa fa-angle-double-right"></i> Контактная информация</a></li>
                                      <li><a href="<?=base_url('administrator/news')?>"><i class="fa fa-angle-double-right"></i> Новости</a></li> 
                                      <li><a href="<?=base_url('administrator/catalog')?>"><i class="fa fa-angle-double-right"></i> Продукция</a></li> 
                                      <li><a href="<?=base_url('administrator/create_category_catalog')?>"><i class="fa fa-angle-double-right"></i> Категории продукции</a></li> 
                                      <li><a href="<?=base_url('administrator/articles')?>"><i class="fa fa-angle-double-right"></i> Публикации</a></li> 
                                      <li><a href="<?=base_url('administrator/employees')?>"><i class="fa fa-angle-double-right"></i> Сотрудники</a></li> 
                                      <li><a href="<?=base_url('administrator/vacancy')?>"><i class="fa fa-angle-double-right"></i> Вакансии</a></li> 
                                      <li><a href="<?=base_url('administrator/gallery')?>"><i class="fa fa-angle-double-right"></i> Фотоархив</a></li> 
                                      <li><a href="<?=base_url('administrator/anketa')?>"><i class="fa fa-angle-double-right"></i> Анкета</a></li>                
                                    </ul>   
                                </li>
                                <li class="treeview">
                                    <a href="#">
                                        <i class="fa fa-edit"></i> <span>Формы</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="<?=base_url('administrator/form_questions')?>"><i class="fa fa-angle-double-right"></i> Вопросы</a></li>      
                                        <li><a href="<?=base_url('administrator/form_sub')?>"><i class="fa fa-angle-double-right"></i> Подписки</a></li>  
                                    </ul>   
                                </li>
                                <li><a href="<?=base_url('administrator/moderation') ?>"><i class="fa fa-camera-retro"></i> <span>Конкурс</span></a></li>
                                <li><a href="<?=base_url('autorization/index')?>"><i class="fa fa-user"></i> <span>Пользователи</span></a></li>
                            <?php endif?>
                            
                        </ul>
                    </section>
                    <!-- /.sidebar -->
                </aside>
          <?php endif?>
          
         
          <?php if ($error = $this->session->flashdata('error')):?>
            <?php echo alert_message($error, 'danger');?>
          <?php elseif($success = $this->session->flashdata('success')):?>
            <?php echo alert_message($success, 'success');?>
          <?php elseif($success = $this->session->flashdata('info')):?>
            <?php echo alert_message($info, 'info');?>
            <?php elseif(validation_errors()):?>
            <?php echo alert_message(validation_errors(), 'danger');?>
          <?php endif;?>
                
