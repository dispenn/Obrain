<script type="text/javascript" charset="utf-8" src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=v2oCnni_v0Xh0iX6x6p7RkKCAAAKnON6&width=100%&height=400&lang=ru_RU&sourceType=constructor"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Контактная информация</h1>
        </div>
    </div>
</div>

<main class="main">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="page_body clearfix">
                    <div class="col-md-12">

                    </div>

                    <div class="col-md-6">
                        <address>
                            <p><?php echo $this->contacts['address']; ?></p>
                            <p>Email: <a href="mailto:<?php echo $this->contacts['email']; ?>"><?php echo $this->contacts['email']; ?></a></p>
                            <p>Телефоны: <?php echo $this->contacts['phone']; ?></p>
                        </address>
                    </div>
                    <div class="col-md-6">
                        <h2>Написать нам письмо</h2>
                        <form action="">
                            <div class="form-group">
                                <label>Имя:</label>
                                <input type="text" name="" class="form-control" placeholder="Ваше имя">
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="text" name="" class="form-control" placeholder="Электронный адрес">
                            </div>
                            <div class="form-group">
                                <label>Текст сообщения:</label>
                                <textarea name="" class="form-control"  rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success">Отправить</button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</main>