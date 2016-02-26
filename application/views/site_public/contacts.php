        <div class="container">
           <div class="gap gap-small"></div>
            <div class="row row-wrap">
                <h2>Контактная информация</h2>
                
                <div class="col-md-12">
				<?php if ($error = $this->session->flashdata('error')):?>
				<?php echo alert_message($error, 'danger');?>
			<?php elseif($success = $this->session->flashdata('success')):?>
			<?php echo alert_message($success, 'success');?>
		<?php elseif($success = $this->session->flashdata('info')):?>
		<?php echo alert_message($info, 'info');?>
	<?php elseif(validation_errors()):?>
	<?php echo alert_message(validation_errors(), 'danger');?>
<?php endif;?>
</div>
                
                <div class="col-md-5">
                    <ul class="list contacts">
                        <li><h3><?php echo $this->contacts['fulltext']; ?></h3></li>
                        <?php if ($this->contacts['address'] != ""): ?><li><i class="fa fa-map-marker"></i> Адрес: <?php echo $this->contacts['address']; ?></li><?php endif; ?>
                        <?php if ($this->contacts['phone'] != ""): ?><li><i class="fa fa-phone"></i> Тел. <?php echo $this->contacts['phone']; ?></li><?php endif; ?>
                        <?php if ($this->contacts['email'] != ""): ?><li><i class="fa fa-envelope"></i> E-mail: <a href="mailto:<?php echo $this->contacts['email']; ?>"><?php echo $this->contacts['email']; ?></a><?php endif; ?>
                        </li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <h3>Написать вопрос</h3>
                    <form name="contactForm" id="contact-form" class="contact-form" method="post" action="<?php echo base_url() . 'contacts'; ?>">
                        <fieldset>
                            <div class="form-group">
                                <label>ФИО</label>
                                <input class="form-control" id="name" name="form[name]" type="text" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>Компания</label>
                                <input class="form-control" id="company" name="form[company]" type="text" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>Телефон</label>
                                <input class="form-control" id="phone" name="form[phone]" type="text" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>E-mail</label>
                                <input class="form-control" id="email" name="form[email]" type="text" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>Текст сообщения</label>
                                <textarea class="form-control" id="message" name="form[text]" placeholder=""></textarea>
                            </div>
                            <button id="send-message" type="submit" class="btn btn-primary">Отправить</button>
                        </fieldset>
                    </form>
                </div>
            </div>
            
            <?php if($this->contacts['script_map'] != ""): ?>
            <div class="row row-wrap">
                <div class="col-md-12">
                    <h3>Посмотреть на карте</h3>
                    <?php echo $this->contacts['script_map']; ?>
                </div>
            </div>    
            <?php endif; ?>
            <div class="gap gap-small"></div>
        </div>
