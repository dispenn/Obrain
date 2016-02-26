<div class="container">
	<div class="gap gap-small"></div>
	<div class="row">
		<h2>О компании</h2>
		<div class="col-md-12">
			<?php echo $about['company']; ?>
		</div>
		<div class="col-md-12">
			<iframe width="420" height="315" src="https://www.youtube.com/embed/vW5Vs5Ymc40?showinfo=0" frameborder="0" allowfullscreen></iframe>
		</div>
		<div class="gap"></div>
	</div>
	
	<div class="row">
		<h2>Регионы присутствия</h2>
		<div class="col-md-12">
			<div id="chartdiv" style="width: 100%; height: 600px;"></div>
		</div> 
		<div class="gap"></div>
	</div>
	<div class="row">
		<h2>Как стать нашим клиентом</h2>
		<div class="col-md-12">
			<?php echo $about['client']; ?>
		</div> 
		<div class="gap"></div>
	</div>
	<div class="row">
		<h2>Наши награды и достижения</h2>
		<div class="col-md-12" id="popup-gallery">
			<?php echo $about['awards']; ?>
			
		</div> 
		<div class="gap"></div>
	</div>
</div>