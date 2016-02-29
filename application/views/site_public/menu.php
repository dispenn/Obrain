<header class="header">

    <div class="h_up_bar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <span>Помогите ребенку развить интеллект! Звоните и записывайтесь прямо сейчас: +7 778 6111621, +7 778 6111622</span>
                </div>
            </div>
        </div>
    </div>

    <div class="h_head">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="logo">
                        <a href="index.html" title="Obrain. Центр развития интеллекта."><img src="<?php echo '/themes/site_public/'; ?>img/logo.png" alt="Logo"></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h_wrapp_faq">
                        <p>Мы помогаем детям: стать способными, самостоятельно принимать решения, легко учиться и быстро запоминать!</p>
                        <button class="btn btn-lg btn-blue" id="form_faq_dp" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Задать вопрос</button>

                        <div class="h_faq_form dropdown-menu" aria-labelledby="form_faq_dp">
                            <form action="" class="form-horizontal">
                                <div class="form-group">
                                    <label>Имя:</label>
                                    <input type="text" name="" class="form-control" placeholder="Ваше имя">
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="text" name="" class="form-control" placeholder="Электронный адрес">
                                </div>
                                <div class="form-group">
                                    <label>Ваш вопрос:</label>
                                    <textarea name="" class="form-control"  rows="4"></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-success">Отправить</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="h_nav_bar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <ul>
                    <?php if (!empty($this->main_menu)): ?>
                    <?php foreach ($this->main_menu as $category): ?>
                       <li><a href="<?php if (preg_match("/http[s]*:\/\//", $category['link'])) { echo $category['link']; } else { echo base_url() . $category['link']; } ?>" <?php if (preg_match("/http[s]*:\/\//", $category['link'])) { echo 'target="_blank"'; } ; ?> class="<?php echo $category['class']; ?>"><?php echo $category['title']; ?></a></li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </ul>

                </div>
            </div>
        </div>
    </div>

</header>