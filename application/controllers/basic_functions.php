<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
	------------------------Важная информация------------------------
	* Тип таблиц Innodb, поэтому все связи реализованы в самом mysql.
	* Вся обработка входящих данных, происходит в form validation (trim, xss, дополнительное экранирование желательно)
*/
class Basic_functions extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('login', 'refresh');
		}
	}

	public function index() {
		if (isset($_FILES) && isset($_POST['save'])) {
			$banner = $this->input->post('banner');
			$directory = 'banner';

			if ($banner['position'] == 'top') {
				$banner_height = 100;
				$banner_weight = 300;
			} elseif ($banner['position'] == 'bottom') {
				$banner_height = 200;
				$banner_weight = 520;
			} elseif ($banner['position'] == 'right' || $banner['position'] == 'left') {
				$banner_height = 400;
				$banner_weight = 115;
			}

			$path = $this->general_functions->upload_files($_FILES['path'], $directory, 1, $banner_height, $banner_weight);
			$banner['path'] = $path[0]['path'];
			$this->banner($banner);
		} elseif (isset($_POST['email'])) {
			$email = $this->input->post('setting');
			$status = $this->admin_model->email_update($email);
			if ($status === TRUE || is_numeric($status)) {
				$this->general_functions->alert('success', 'admin/index', 'Запись успешно сохранена.');
			} else {
				$this->general_functions->alert('error', 'admin/index', 'Произошла ошибка, попробуйте позднее.');	
			}
		} else {
			$setting = $this->admin_model->setting();
			$banner = $this->admin_model->banner();
			$this->load->view('admin_panel/header');
			$this->load->view('admin_panel/index', array('setting' => $setting, 'banner' => $banner));
			$this->load->view('admin_panel/footer');
		}
	}

/*
	* $name_table - название таблицы, из которой будет выборка.
	* $page_offset - отступ от начала выборки. 
*/
	public function pagination_list($name_table = NULL, $baseurl = NULL, $uri_segment = 2, $page_offset = 0, $page_limit = 20) {
		if ($name_table != NULL && $baseurl != NULL) {
			$table_count = $this->general_functions->count_all($name_table);
			if($table_count > 0){

				$config = array(
					'full_tag_open'		=> '<div class="pagination">',
					'full_tag_close'		=> '</div>',
					'next_link'		=> '&raquo;',
					'prev_link' 	=> '&laquo;',
					'first_link' 	=> '&laquo; Первая',
					'last_link' 	=> 'Последняя &raquo;',
					'base_url' 		=> base_url($baseurl),
					'uri_segment' 	=> $uri_segment,
					'total_rows'	=> $table_count,
					'per_page'		=> $page_limit,
				);

				return $this->pagination->initialize($config);
			}
		}
	}
/*
	* Загрузка картинок для плагина ckeditor
*/
	public function ckupload() {
		$callback = $_GET['CKEditorFuncNum'];
		$file_name = $_FILES['upload']['name'];
		$file_name_tmp = $_FILES['upload']['tmp_name'];
		$file_new_name = FCPATH.'/uploads/img/'; // серверный адрес - папка для сохранения файлов. (нужны права на запись)
		$full_path = $file_new_name.$file_name;
		$http_path = base_url('uploads/img').'/'.$file_name; // адрес изображения для обращения через http
		$error = '';
		if( move_uploaded_file($file_name_tmp, $full_path) ) {
	// можно добавить код при успешном выполнение загрузки файла
		} else {
			$error = 'Ошибка, повторите попытку позже'; // эта ошибка появится в браузере если скрипт не смог загрузить файл
			$http_path = '';
		}
		echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(".$callback.", \"".$http_path."\", \"".$error."\");</script>";
	}

/**
     * Проверка наличия языка сайта в ссылке
     * и переедресация на язык по-умолчанию
     * в случае если мы его в url не нашли
*/
	public function _check_lang() {
		$uri_string = $this->uri->uri_string();
      	$lang = $this->uri->segment(1);
      	$lang_config = $this->config->item('language_site'); /* получаем языки сайта из конфига*/
      	//Если язык не выбран по умолчанию определяем язык сайта
      	if (!isset($lang_config[$this->uri->segment(1)])) { 
      		//print_r($lang_config['default'].'/'.$uri_string);
            redirect($lang_config['default'].'/'.$uri_string, 'location', 301);
        }
        
	    $this->language = $lang;
	    
	    switch($lang):
			case 'en':
				$this->lang->load('interface', 'english');
				$this->config->set_item('language', 'english');
				break;

			case 'ru':
				$this->lang->load('interface', 'russian');
				$this->config->set_item('language', 'russian');
				break;

			default:
				$this->lang->load('interface', 'russian');
				$this->config->set_item('language', 'russian');
				break;
      	endswitch;
		// если пользователь существует то работаем с его данными
        //if($this->session->userdata('userInfo')){
        //    $identity = $this->session->userdata('userInfo');
        //    $this->setDefaultRole($identity['user_role']);
        //}
        //$this->session->set_userdata('some_name', 'some_value');
		/*$uri_string = $this->uri->uri_string();
      	$lang = $this->uri->segment(1);
      	$lang_config = $this->config->item('language_site'); /* получаем языки сайта из конфига*/
      	//Если язык не выбран по умолчанию определяем язык сайта
      	/*if (!isset($lang_config[$this->uri->segment(1)])) { 
            redirect($lang_config['default'].'/'.$uri_string, 'location', 301);
        }
	    $this->language = $lang;
	    
	    switch($lang):
			case 'en':
				$this->lang->load('interface', 'english');
				$this->config->set_item('language', 'english');
				break;

			case 'ru':
				$this->lang->load('interface', 'russian');
				$this->config->set_item('language', 'russian');
				break;

			default:
				$this->lang->load('interface', 'russian');
				$this->config->set_item('language', 'russian');
				break;
      	endswitch;*/

      	

	}
    
    
    
}