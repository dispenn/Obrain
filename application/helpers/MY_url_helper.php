<?php

function theme_url($uri) {
	$CI =& get_instance();
	return $CI->config->base_url('themes/'.$uri);
}

//to generate an image tag, set tag to true. you can also put a string in tag to generate the alt tag
function theme_img($uri, $tag=false) {
	if($tag) {
		return '<img src="'.theme_url('administrator/img/'.$uri).'" alt="'.$tag.'">';
	} else {
		return theme_url('administrator/img/'.$uri);
	}	
}

function theme_images($uri, $tag=false) {
	if($tag) {
		return '<img src="'.theme_url('administrator/images/'.$uri).'" alt="'.$tag.'">';
	} else {
		return theme_url('administrator/images/'.$uri);
	}
}

function theme_js($uri, $tag=false) {
	if($tag) {
		return '<script type="text/javascript" src="'.theme_url('administrator/js/'.$uri).'"></script>';
	} else {
		return theme_url(''.$uri);
	}
}

//you can fill the tag field in to spit out a link tag, setting tag to a string will fill in the media attribute
function theme_css($uri, $tag=false) {
	if($tag) {
		$media=false;
		if(is_string($tag)) {
			$media = 'media="'.$tag.'"';
		}
		return '<link href="'.theme_url('administrator/css/'.$uri).'" type="text/css" rel="stylesheet" '.$media.'>';
	}
	
	return theme_url(''.$uri);
}

function alert_message($error = NULL, $type = NULL) {
	if($type == NULL) {$type = 'info';}
	if($error != NULL ) {
		return '
		<aside class="right-side">
            <div class="row">
              <div class="col-xs-12">
                <section class="content">
	                <div class="alert alert-'.$type.' alert-dismissable">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                    '.$error.'
	                </div>
				</section>
              </div>
            </div>
          </aside>';
	}
}

function theme_images_public($uri, $tag=false) {
	if($tag) {
		return '<img src="'.theme_url(''.$uri).'" alt="'.$tag.'">';
	} else {
		return theme_url(''.$uri);
	}
}

function theme_js_public($uri, $tag=false) {
	if($tag) {
		return '<script type="text/javascript" src="'.theme_url(''.$uri).'"></script>';
	} else {
		return theme_url(''.$uri);
	}
}

//you can fill the tag field in to spit out a link tag, setting tag to a string will fill in the media attribute
function theme_css_public($uri, $tag=false) {
	if($tag) {
		$media=false;
		if(is_string($tag)) {
			$media = 'media="'.$tag.'"';
		}
		return '<link href="'.theme_url(''.$uri).'" type="text/css" rel="stylesheet" '.$media.'>';
	}
	
	return theme_url(''.$uri);
}