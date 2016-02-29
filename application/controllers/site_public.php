<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_public extends CI_Controller {

    public function __construct() {
        parent::__construct();
         
        $this->contacts = new stdClass();
		$this->contacts = $this->basic_functions_model->select(array('table' => 'contact_info', 'type' => 'list', 'where_field' => 'id', 'where' => 1));
        $this->contacts = $this->contacts[0];  
        
        $this->departments = new stdClass();
		$this->departments = $this->basic_functions_model->select(array('table' => 'departments', 'type' => 'list'));
    
        $this->last_news = new stdClass();
        $this->last_news = $this->public_model->select_last_news(3);
        if (!empty($this->last_news)) {
            foreach ($this->last_news as $key => $value) {
                $this->last_news[$key]['date'] = $this->rdate("d M, Y", $this->last_news[$key]['date']);
            }
        }
    }

//Вход пользователя
	function login() {
		if ($this->ion_auth->logged_in()) {
			redirect('administrator', 'refresh');
		}

		$this->data['title'] = "Login";

		//Валидация форм
		//required - проверка на заполненность формы.
		$this->form_validation->set_rules('identity', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Пароль', 'required');

		if ($this->form_validation->run() == true)
		{
			//запомнить меня? чекбокс
			$remember = (bool) $this->input->post('remember');
			//авторизовать пользователя 
			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
                //redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('administrator', 'refresh');
			}
			else
			{
				//Если вход не получился, отправить на авторизацию.
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			//Показать ошибки почему не прошёл авторизацию.
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = $this->form_validation->set_value('identity');
                                  
            $this->load->view('site_public/header');
            $this->load->view('site_public/menu', $this->data);
            $this->load->view('site_public/index');
            $this->load->view('site_public/footer');
		}
	}

/*
*/
    public function ajax_test_login() {
        if (!isset($_GET['identity']) || !isset($_GET['password'])) {
            echo json_encode(array('title' => 'error'));
            exit;
        }

        $status = $this->ion_auth_model->validate_login($_GET['identity'], $_GET['password']);
        
        if ($status) {          
            echo json_encode(array('title' => 'success'));
            exit;
        }
        else {
            echo json_encode(array('title' => 'error'));
            exit;
        }
    }
/*
    * Восстановление пароля
*/
	public function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Электронная почта', 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
                'class' => 'form-control',
			);

			//set any errors and display the form
			$this->load->view('site_public/header', array('title' => 'Восстановление пароля'));
			$this->load->view('autorization/forgot_password', $this->data);
			$this->load->view('site_public/footer', array());
		}
		else
		{
			$config_tables = $this->config->item('tables', 'ion_auth');
			$identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();

			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("login", 'refresh'); 
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("forgot_password", 'refresh');
			}
		}
	}

//Сброс пароля
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{

			$this->form_validation->set_rules('new', 'Новый пароль', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', 'Повторите новый пароль', 'required');

			if ($this->form_validation->run() == false)
			{

				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				    'type' => 'password',
                    'class' => 'form-control',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
                    'class' => 'form-control',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;
                
				$this->load->view('site_public/header', array('title' => 'Сброс пароля'));
				$this->load->view('autorization/reset_password', $this->data);
				$this->load->view('site_public/footer');
			}
			else
			{
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error('This form post did not pass our security checks.');

				}
				else
				{
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						$this->logout();
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("forgot_password", 'refresh');
		}
	}

    public function index() {
        $this->load->view('site_public/header');
        $this->load->view('site_public/menu');
        $this->load->view('site_public/index', array());
        $this->load->view('site_public/footer');
	}

    public function contacts() {
        $this->form_validation->set_rules($this->rules_model->question_rules);
        if ($this->form_validation->run() === FALSE) {  
            $this->load->view('site_public/header');
            $this->load->view('site_public/menu');
            $this->load->view('site_public/contacts');
            $this->load->view('site_public/footer');
        } else {
            $form = $this->input->post('form');
			if (is_array($form) && !empty($form)) {         
				$config_insert_db = array(
					'name_table' => 'forms',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
                 
                $form['category'] = 2;
				$status = $this->basic_functions_model->insert_db($config_insert_db, $form);
                
                $this->email->from('no-reply@solfi.ru', 'Solfi');
                $this->email->to($this->contacts['email']); 
                
                $this->email->subject('Отправлен новый вопрос');
                $text_message = "\n\nФИО: " . $form['name'];
                if ($form['company']) {
                    $text_message .= "\nКомпания: " . $form['company'];
                }
                if ($form['phone']) {
                    $text_message .= "\nТелефон: " . $form['phone'];
                }
                if ($form['email']) {
                    $text_message .= "\nE-mail: " . $form['email'];
                }
                if ($form['text']) {
                    $text_message .= "\n\n" . $form['text'];
                }
                
                $this->email->message($text_message);	
                $this->email->send();
                
                if ($form['email']) {
                    $this->email->from('no-reply@solfi.ru', 'Solfi');
                    $this->email->to($form['email']); 
                    $this->email->subject('Вы задали вопрос');
                    $this->email->message($text_message);	
                    $this->email->send();
                }
                
				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'contacts', 'Вопрос успешно отправлен.');
				} else {
					$this->general_functions->alert('error', 'contacts', 'Произошла ошибка.');	
				}
			}
        }
	}

    public function department($alias = NULL) {
        $department =  $this->basic_functions_model->select(array('table' => 'departments', 'type' => 'list', 'where_field' => 'alias', 'where' => $alias));
        
        if ($alias != NULL && !empty($department)) {
            $employees = $this->basic_functions_model->select_with_images(array('table' => 'employees', 'type' => 'list', 'where_field' => 'id_department', 'where' => $department[0]['id'], 'image_field' => 'id_image'), 1000);
            
            $this->load->view('site_public/header');
            $this->load->view('site_public/menu');
            $this->load->view('site_public/department', array('department' => $department[0], 'employees' => $employees));
            $this->load->view('site_public/footer');
        }
	}

    public function news($alias = NULL) {
        if ($alias === NULL || is_numeric($alias)) {
            $config['base_url'] = base_url('news/');
            $config['total_rows'] = $this->db->count_all('news');
            $config['cur_page'] = $this->uri->segment(2);
            if ($config['cur_page'] == "") {
                $config['cur_page'] = 0;
            }
            $config['per_page'] = 10;
            $config['full_tag_open'] = '<ul class="pagination pagination-lg pull-right">';
            $config['full_tag_close'] = '</ul>';
            $config['prev_tag_open'] = '<li title="Предыдущая">';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li title="Следующая">';
            $config['next_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            $config['first_link'] = '<<';
            $config['first_tag_open'] = '<li title="Первая">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '>>';
            $config['last_tag_open'] = '<li title="Последняя">';
            $config['last_tag_close'] = '</li>';
            
            $this->pagination->initialize($config); 
            
            $form = $this->public_model->select_news($config['per_page'], $config['cur_page']);
            
            if (!empty($form)) {
                foreach ($form as $key => $form_value) {
                    if ($form[$key]['date'] != 0) {
                        $form[$key]['date'] = $this->rdate("d M Y", $form[$key]['date']) . ' г.';
                    }
                }
            }
            
            $this->load->view('site_public/header');
            $this->load->view('site_public/menu');
            $this->load->view('site_public/news', array('list' => $form));
            $this->load->view('site_public/footer');
        } else {
            $form = $this->public_model->select_news(0, 0, $alias);
            
            if (!empty($form)) {
                if ($form['date'] != 0) {
                    $form['date'] = $this->rdate("d M Y", $form['date']) . ' г.';
                }
                
                $this->load->view('site_public/header');
                $this->load->view('site_public/menu');
                $this->load->view('site_public/news_item', array('news' => $form));
                $this->load->view('site_public/footer');
            } else {
                show_404();
            }
        }
	}    

    public function articles($alias = NULL) {
        if ($alias === NULL || is_numeric($alias)) {
            $config['base_url'] = base_url('articles/');
            $config['total_rows'] = $this->db->count_all('articles');
            $config['cur_page'] = $this->uri->segment(2);
            if ($config['cur_page'] == "") {
                $config['cur_page'] = 0;
            }
            $config['per_page'] = 10;
            $config['full_tag_open'] = '<ul class="pagination pagination-lg pull-right">';
            $config['full_tag_close'] = '</ul>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            
            $this->pagination->initialize($config); 
            
            $form = $this->public_model->select_articles($config['per_page'], $config['cur_page']);
            
            if (!empty($form)) {
                foreach ($form as $key => $form_value) {
                    if ($form[$key]['date'] != 0) {
                        $form[$key]['date'] = $this->rdate("d M Y", $form[$key]['date']) . ' г.';
                    }
                }
            }
                        
            $this->load->view('site_public/header');
            $this->load->view('site_public/menu');
            $this->load->view('site_public/articles', array('list' => $form));
            $this->load->view('site_public/footer');
        } else {
            $form = $this->public_model->select_articles(0, 0, $alias);
            
            if (!empty($form)) {
                if ($form['date'] != 0) {
                    $form['date'] = $this->rdate("d M Y", $form['date']) . ' г.';
                }
                
                $this->load->view('site_public/header');
                $this->load->view('site_public/menu');
                $this->load->view('site_public/articles_item', array('article' => $form));
                $this->load->view('site_public/footer');
            } else {
                show_404();
            }
        }
	}    
    
    
    public function vacancy() {
        $vacancy = $this->basic_functions_model->select(array('table' => 'vacancy', 'type' => 'list'));
           
        $this->load->view('site_public/header');
        $this->load->view('site_public/menu');
        $this->load->view('site_public/vacancy', array('vacancy' => $vacancy));
        $this->load->view('site_public/footer');
   }   

    public function gallery($alias = NULL) {
        $this->load->view('site_public/header');
        $this->load->view('site_public/menu');
        if ($alias !== NULL) {
            $form = $this->basic_functions_model->select(array('table' => 'gallery', 'type' => 'list', 'where_field' => 'alias', 'where' => $alias));
            
            if (!empty($form) && $form != FALSE) {
                $images = $this->basic_functions_model->select_with_images(array('table' => 'gallery_images', 'type' => 'list', 'where_field' => 'id_gallery', 'where' => $form[0]['id'], 'image_field' => 'id_all_files', 'sort' => 'main_photo', 'type_sort' => 'desc'), 1000);
                
                $this->load->view('site_public/gallery_inner', array('form' => $form[0], 'images' => $images));
            } else {
                show_404();
            }
        } else {
            $galleryes = $this->public_model->select_galleryes();
            
            if(!empty($galleryes)) {
                foreach ($galleryes as $key => $gallery) {
                    $galleryes[$key]['fulltext'] = strip_tags($galleryes[$key]['fulltext']);
                    $galleryes[$key]['fulltext'] = substr($galleryes[$key]['fulltext'], 0, 160);
                    $galleryes[$key]['fulltext'] = substr($galleryes[$key]['fulltext'], 0, strrpos($galleryes[$key]['fulltext'], ' '));
                    $galleryes[$key]['fulltext'] .= "...";
                }
            }
            
            $this->load->view('site_public/gallery', array('list' => $galleryes));
        }
        $this->load->view('site_public/footer');
	}    

    public function catalog($alias = NULL, $alias_catalog = NULL) {
        $category_list = $this->public_model->select_category_list(TRUE);
        
        if ($alias === NULL) {
            if (!empty($category_list)) {
                $category = $category_list[0];
                $alias = $category['alias'];
                redirect("catalog/".$alias, 'refresh');
            } else {
                show_404();
            }
        } else {
            $category_find = $this->basic_functions_model->select(array('table' => 'category_catalog', 'type' => 'list', 'where_field' => 'alias', 'where' => $alias));
            if (!empty($category_find)) {
                $category = $category_find[0];
            } else {
                show_404();
            }
        }
        
        if (($alias != NULL && $alias_catalog == NULL) || is_numeric($alias_catalog)) {
            
                $config['base_url'] = base_url('catalog/'.$alias);
                $all_list = $this->public_model->count_catalog($alias);
                $config['total_rows'] = count($all_list);
                $config['cur_page'] = $this->uri->segment(3);
                $config['per_page'] = 99;
                $config['full_tag_open'] = '<right><ul class="pagination pagination-lg">';
                $config['full_tag_close'] = '</ul></right>';
                $config['prev_tag_open'] = '<li>';
                $config['prev_tag_close'] = '</li>';
                $config['next_tag_open'] = '<li>';
                $config['next_tag_close'] = '</li>';
                $config['num_tag_open'] = '<li>';
                $config['num_tag_close'] = '</li>';
                $config['cur_tag_open'] = '<li class="active"><a href="">';
                $config['cur_tag_close'] = '</a></li>';
                $config['first_link'] = 'Первая';
                $config['first_tag_open'] = '<li>';
                $config['first_tag_close'] = '</li>';
                $config['last_link'] = 'Последняя';
                $config['last_tag_open'] = '<li>';
                $config['last_tag_close'] = '</li>';
                
                $this->pagination->initialize($config); 
                
                $catalog_list = $this->public_model->select_pagination_filter('catalog', $config['per_page'], $this->uri->segment(3), $alias);
            
                $podcategory_list = $this->basic_functions_model->select(array('table' => 'category_catalog', 'type' => 'list', 'where_field' => 'id_main_category', 'where' => $category['id']));
            
                if(!empty($podcategory_list)) {
                    foreach($podcategory_list as $key => $podcategory_value) {
                        $podcategory_list[$key]['catalog'] = array();
                        foreach ($catalog_list as $key_catalog => $catalog_value) {
                            if ($catalog_list[$key_catalog]['id'] == $podcategory_list[$key]['id']) {
                                $podcategory_list[$key]['catalog'][] = $catalog_list[$key_catalog];
                                unset($catalog_list[$key_catalog]);
                            }
                        }
                    }
                }
            
                $this->load->view('site_public/header');
                $this->load->view('site_public/menu');
                $this->load->view('site_public/catalog', array('form' => $category, 'alias' => $alias, 'categoryes' => $category_list, 'podcategory_list' => $podcategory_list));
                $this->load->view('site_public/footer');
            
        } elseif ($alias != NULL && $alias_catalog != NULL) {
            
            $catalog = $this->basic_functions_model->select_with_images(array('table' => 'catalog', 'type' => 'list', 'where_field' => 'alias', 'where' => $alias_catalog, 'image_field' => 'id_image'), 1100);
                
            if (!empty($catalog)) {
                $images = $this->basic_functions_model->select_with_images(array('table' => 'catalog_images', 'type' => 'list', 'where_field' => 'id_catalog', 'where' => $catalog[0]['id'], 'image_field' => 'id_all_files'), 1100);
                    
                $this->load->view('site_public/header');
                $this->load->view('site_public/menu');
                $this->load->view('site_public/catalog_inner', array('images' => $images, 'alias' => $alias, 'categoryes' => $category_list, 'catalog' => $catalog[0]));
                $this->load->view('site_public/footer');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
	}

    public function anketa() {
        $anketa = $this->basic_functions_model->select(array('table' => 'anketa', 'type' => 'list', 'where_field' => 'id', 'where' => 1));
        
        $this->load->view('site_public/header');
        $this->load->view('site_public/menu');
        $this->load->view('site_public/anketa', array('anketa' => $anketa[0]));
        $this->load->view('site_public/footer');
	}     

    public function promo($alias = NULL) {
        if ($alias === NULL || is_numeric($alias)) {
            $config['base_url'] = base_url('promo/');
            $config['total_rows'] = $this->db->count_all('promos');
            $config['cur_page'] = $this->uri->segment(2);
            if ($config['cur_page'] == "") {
                $config['cur_page'] = 0;
            }
            $config['per_page'] = 10;
            $config['full_tag_open'] = '<ul class="pagination pagination-lg pull-right">';
            $config['full_tag_close'] = '</ul>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            
            $this->pagination->initialize($config); 
            
            $form = $this->public_model->select_promos($config['per_page'], $config['cur_page']);
            
            if (!empty($form)) {
                foreach ($form as $key => $form_value) {
                    if ($form[$key]['date'] != 0) {
                        $form[$key]['date'] = $this->rdate("d M Y", $form[$key]['date']) . ' г.';
                    }
                }
            }
            
            $this->load->view('site_public/header');
            $this->load->view('site_public/menu');
            $this->load->view('site_public/promo', array('list' => $form));
            $this->load->view('site_public/footer');
        } else {
            $form = $this->public_model->select_promos(0, 0, $alias);
            
            if (!empty($form)) {
                if ($form['date'] != 0) {
                    $form['date'] = $this->rdate("d M Y", $form['date']) . ' г.';
                }
                
                $this->load->view('site_public/header');
                $this->load->view('site_public/menu');
                $this->load->view('site_public/promo_item', array('promo' => $form));
                $this->load->view('site_public/footer');
            } else {
                show_404();
            }
        }
	}     

    public function about() {
        $about = $this->basic_functions_model->select(array('table' => 'about', 'type' => 'list', 'where_field' => 'id', 'where' => 1));
        
        $regions = $this->basic_functions_model->select(array('table' => 'regions', 'type' => 'list'));
        
        $this->load->view('site_public/header');
        $this->load->view('site_public/menu');
        $this->load->view('site_public/about', array('about' => $about[0]));
        $this->load->view('site_public/footer', array('regions' => $regions));
	}     

/*
    * Перевод даты в строковый вид
*/    
    public function rdate($param, $time=0) {
    	if(intval($time)==0)$time=time();
    	$MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
    	if(strpos($param,'M')===false) return date($param, $time);
    		else return date(str_replace('M',$MonthNames[date('n',$time)-1],$param), $time);
    }

}
