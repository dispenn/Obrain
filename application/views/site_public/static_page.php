<section class="breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="wrapp">
                    <ul>
                        <li><a href="index.html"><i class="fa fa-home"></i>Главная</a>  <i class="fa fa-angle-double-right"></i></li>
                        <li><span><?php echo $page_info['title']; ?></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo $page_info['title']; ?></h1>
        </div>
    </div>
</div>

<main class="main">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="page_body clearfix" id="faq">

                    <?php echo $page_info['fulltext']; ?>

                </div>

            </div>
        </div>
    </div>
</main>