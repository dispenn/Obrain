        <footer class="main">
            <div class="footer-top-area">
                <div class="container">
                    <div class="row row-wrap">
                        <div class="col-md-3">
                            <a href="<?php echo base_url();?>">
                                <img src="<?php echo base_url('themes/site/img/logo.png');?>" alt="logo" title="logo" class="logo_footer">
                            </a>
                            <div class="gap gap-small"></div>
                            <script type="text/javascript">(function(w,doc) {
                            if (!w.__utlWdgt ) {
                                w.__utlWdgt = true;
                                var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
                                s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                                s.src = ('https:' == w.location.protocol ? 'https' : 'http')  + '://w.uptolike.com/widgets/v1/uptolike.js';
                                var h=d[g]('body')[0];
                                h.appendChild(s);
                            }})(window,document);
                            </script>
                            <div data-background-alpha="0.0" data-buttons-color="#FFFFFF" data-counter-background-color="#ffffff" data-share-counter-size="12" data-top-button="false" data-share-counter-type="disable" data-share-style="1" data-mode="share" data-like-text-enable="false" data-mobile-view="false" data-icon-color="#ffffff" data-orientation="horizontal" data-text-color="#000000" data-share-shape="round-rectangle" data-sn-ids="fb.vk.tw.ok.gp." data-share-size="30" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.vk.tw.wh.ok.gp." data-pid="1405130" data-counter-background-alpha="1.0" data-following-enable="false" data-exclude-show-more="false" data-selection-enable="false" class="uptolike-buttons" ></div>
                            <p>Поделитесь информацией со своими друзьями в социальных сетях.</p>
                        </div>
                        <div class="col-md-3">
                            <h4>Подписаться на новости</h4>
                            <div class="box">
                                <form method="post" action="<?php echo base_url(); ?>">
                                    <div class="form-group mb10">
                                        <label>E-mail</label>
                                        <input type="text" name="form[email]" required="required" class="form-control" />
                                    </div>
                                    <p class="mb10">Введите свой e-mail, чтобы быть в курсе новостей</p>
                                    <input type="submit" class="btn btn-primary" value="Подписаться" />
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4>Контакты</h4>
                            <ul class="list contacts">
                                <?php if ($this->contacts['fulltext'] != ""): ?><li><?php echo $this->contacts['fulltext']; ?></li><?php endif; ?>
                                <?php if ($this->contacts['address'] != ""): ?><li><i class="fa fa-map-marker"></i> Адрес: <?php echo $this->contacts['address']; ?></li><?php endif; ?>
                                <?php if ($this->contacts['phone'] != ""): ?><li><i class="fa fa-phone"></i> Тел. <?php echo $this->contacts['phone']; ?></li><?php endif; ?>
                                <?php if ($this->contacts['email'] != ""): ?><li><i class="fa fa-envelope"></i> E-mail: <a href="mailto:<?php echo $this->contacts['email']; ?>"><?php echo $this->contacts['email']; ?></a><?php endif; ?>
                                </li>
                            </ul>
                            <!-- HotLog -->
                                 <script type="text/javascript" language="javascript">
                                 hotlog_js="1.0"; hotlog_r=""+Math.random()+"&s=2164493&im=609&r="+
                                 escape(document.referrer)+"&pg="+escape(window.location.href);
                                 </script>
                                 <script type="text/javascript" language="javascript1.1">
                                 hotlog_js="1.1"; hotlog_r+="&j="+(navigator.javaEnabled()?"Y":"N");
                                 </script>
                                 <script type="text/javascript" language="javascript1.2">
                                 hotlog_js="1.2"; hotlog_r+="&wh="+screen.width+"x"+screen.height+"&px="+
                                 (((navigator.appName.substring(0,3)=="Mic"))?screen.colorDepth:screen.pixelDepth);
                                 </script>
                                 <script type="text/javascript" language="javascript1.3">
                                 hotlog_js="1.3";
                                 </script>
                                 <script type="text/javascript" language="javascript">
                                 hotlog_r+="&js="+hotlog_js;
                                 document.write('<a href="http://click.hotlog.ru/?2164493" target="_blank"><img '+
                                 'src="http://hit39.hotlog.ru/cgi-bin/hotlog/count?'+
                                 hotlog_r+'" border="0" width="88" height="31" alt="HotLog"><\/a>');
                                 </script>
                                 <noscript>
                                 <a href="http://click.hotlog.ru/?2164493" target="_blank"><img
                                 src="http://hit39.hotlog.ru/cgi-bin/hotlog/count?s=2164493&im=609" border="0"
                                 width="88" height="31" alt="HotLog"></a>
                                 </noscript>
                                 <!-- /HotLog -->
                        </div>
                        <div class="col-md-3">
                            <h4>Последние новости</h4>
                            <ul class="thumb-list">
                            <?php if(isset($this->last_news) && !empty($this->last_news)): ?>
                                <?php foreach($this->last_news as $value): ?>
                                    <li>
                                        <a href="<?php echo 'news/'.$value['alias']; ?>">
                                            <img src="<?php echo base_url() . substr($value['path'], 2); ?>" alt="Image Alternative text" title="<?php echo $value['title']; ?>" />
                                        </a>
                                        <div class="thumb-list-item-caption">
                                            <p class="thumb-list-item-meta"><?php echo $value['date']; ?></p>
                                            <p class="thumb-list-item-desciption"><?php echo $value['title']; ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <p>Copyright © 2015, Solfi. Создание сайта — <a href="http://at-develop.ru" target="_blank">Apptech</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>



        <!-- Scripts queries -->
        <script src="<?php echo base_url('themes/site/js/jquery.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/boostrap.min.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/flexnav.min.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/magnific.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/fitvids.min.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/ionrangeslider.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/icheck.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/fotorama.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/owl-carousel.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/masonry.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/nicescroll.js');?>"></script>

        <!-- Custom scripts -->
        <script src="<?php echo base_url('themes/site/js/custom.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/ammap/ammap.js');?>"></script>
	    <script src="<?php echo base_url('themes/site/js/ammap/maps/js/russiaHigh.js');?>"></script>
        <script src="<?php echo base_url('themes/site/js/ammap/themes/light.js');?>"></script>

        
        <script>

var latlong = {};

<?php if (!empty($regions)): ?>
    <?php foreach ($regions as $region): ?>
        latlong["<?php echo $region['alias']; ?>"] = {"latitude":<?php echo $region['latitude']; ?>, "longitude":<?php echo $region['longitude']; ?>};
    <?php endforeach; ?>
<?php endif; ?>



var mapData = [

<?php if (!empty($regions)): ?>
    <?php foreach ($regions as $region): ?>
        {"code":"<?php echo $region['alias']; ?>" , "name":"<?php echo $region['title']; ?>", "value":<?php echo $region['size']; ?>, "color":"<?php echo $region['color']; ?>"},
    <?php endforeach; ?>
<?php endif; ?>


];


var map;
var minBulletSize = 10;
var maxBulletSize = 20;
var min = Infinity;
var max = -Infinity;


// get min and max values
for (var i = 0; i < mapData.length; i++) {
	var value = mapData[i].value;
	if (value < min) {
		min = value;
	}
	if (value > max) {
		max = value;
	}
}

 // build map
AmCharts.ready(function() {
  	AmCharts.theme = AmCharts.themes.light;
	map = new AmCharts.AmMap();

	map.areasSettings = {
		unlistedAreasColor: "#5fa7e6",
		unlistedAreasAlpha: 0.8
	};
	map.imagesSettings.balloonText = "<span style='font-size:14px;'><b>[[title]]</b></span>";
    map.pathToImages = "<?php echo base_url('themes/site/js/ammap/images');?>/";
	var dataProvider = {
		mapVar: AmCharts.maps.russiaHigh,
		images: []
	}

	// create circle for each country


	// it's better to use circle square to show difference between values, not a radius
    var maxSquare = maxBulletSize * maxBulletSize * 2 * Math.PI;
    var minSquare = minBulletSize * minBulletSize * 2 * Math.PI;

    // create circle for each country
    for (var i = 0; i < mapData.length; i++) {
        var dataItem = mapData[i];
        var value = dataItem.value;
        // calculate size of a bubble
        var square = (value - min) / (max - min) * (maxSquare - minSquare) + minSquare;
        if (square < minSquare) {
            square = minSquare;
        }
        var size = Math.sqrt(square / (Math.PI * 2));
        var id = dataItem.code;

        dataProvider.images.push({
            type: "circle",
            width: size,
            height: size,
            color: dataItem.color,
            longitude: latlong[id].longitude,
            latitude: latlong[id].latitude,
            title: dataItem.name,
            value: value
        });
    }


		

	map.dataProvider = dataProvider;
    map.export = {
    	enabled: true
    }

	map.write("chartdiv");
});
        </script>

    </div>
</body>

<script>
    function clickLoginButton(form) {
        identity = $('#identity').prop('value');
        password = $('#password').prop('value');
        
        var validate = false;
        
        var url = '/site_public/ajax_test_login';      
        $.get(
            url,
            "identity=" + identity + '&password=' + password,
            function (result) {
                if (result.title == 'error') {
                    document.getElementById('validate_message').innerHTML = "Неправильный email или пароль.";
                    validate = false;
                }
                else {
                    form.submit();
                }    
            },
            "json"
        );
        return validate;
    }
    
    function clearValidateMessage() {
        document.getElementById('validate_message').innerHTML = "";
    }
</script>

</html>