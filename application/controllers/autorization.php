<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Autorization extends CI_Controller {

	function __construct() {
		parent::__construct();
		//Подключение своих тегов для вывода ошибок, которые подключаются в config/ion_auth/
		//Здесь не трогать встовлять нужные теги в config, ищя по соответствующим ключам.
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->menu = new stdClass();
        //$this->menu->content = $this->basic_functions_model->select(array('table' => 'content_types', 'type' => 'list'));
	}

	function index($id_group = NULL) {
		//Если не авторизован, редирект на autorization/login
		//Иначе отображение списка пользователей.
		if (!$this->ion_auth->logged_in()) {
			redirect('autorization/login', 'refresh');
		}
        elseif ($this->ion_auth->in_group('Менеджер')) {
            $group = $this->basic_functions_model->select(array('table' => 'groups', 'type' => 'list', 'where_field' => 'id', 'where' => $id_group));
            if ($id_group != NULL && !empty($group)) {
    			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    			//Список пользователей
    			//$this->data['users'] = $this->ion_auth->users()->result();
                $this->data['users'] = $this->admin_model->select_users(array('id_group' => $id_group));
    			/*foreach ($this->data['users'] as $k => $user)
    			{
    				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
    			}*/
    
    			$this->config->load('ion_auth');
    			$this->data['ion_auth_config'] = $this->config->item('ion_auth');
    		    $this->data['group'] = $group[0];
                
    			$this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('autorization/index', $this->data);
    			$this->load->view('admin_panel/footer');
            }
            else {
                show_404();
            }
        }
		//Проверяем входит ли в группу администраторов
		elseif (!$this->ion_auth->is_admin()) {
			
			redirect('administrator', 'refresh');
		} 
        else {
            $group = $this->basic_functions_model->select(array('table' => 'groups', 'type' => 'list', 'where_field' => 'id', 'where' => $id_group));
            if ($id_group != NULL && !empty($group)) {
    			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    			//Список пользователей
    			//$this->data['users'] = $this->ion_auth->users()->result();
                $this->data['users'] = $this->admin_model->select_users(array('id_group' => $id_group));
    			/*foreach ($this->data['users'] as $k => $user)
    			{
    				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
    			}*/
    
    			$this->config->load('ion_auth');
    			$this->data['ion_auth_config'] = $this->config->item('ion_auth');
    		    $this->data['group'] = $group[0];
                
    			$this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('autorization/index', $this->data);
    			$this->load->view('admin_panel/footer');
            }
            elseif ($id_group === NULL) {
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    			//Список пользователей
    			//$this->data['users'] = $this->ion_auth->users()->result();
                $this->data['users'] = $this->admin_model->select_users();
    			/*foreach ($this->data['users'] as $k => $user)
    			{
    				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
    			}*/
    
    			$this->config->load('ion_auth');
    			$this->data['ion_auth_config'] = $this->config->item('ion_auth');
    		    $this->data['group'] = $group[0];
                
    			$this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('autorization/index', $this->data);
    			$this->load->view('admin_panel/footer');
            }
            else {
                show_404();
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
		$this->form_validation->set_rules('identity', 'Логин', 'required');
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
				redirect('login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			//Показать ошибки почему не прошёл авторизацию.
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('autorization/login', $this->data);
			$this->load->view('admin_panel/footer');
		}
	}

	//Разлогинить пользователя, точнее убить сессию
	function logout()
	{
		$this->data['title'] = "Logout";

		$logout = $this->ion_auth->logout();

		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('', 'refresh');
	}

	//Изменить пароль
	function change_password()
	{
		$this->form_validation->set_rules('old', 'Старый пароль', 'required');
		$this->form_validation->set_rules('new', 'Новый пароль', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', 'Повторите пароль', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			//Выводим форму смены пароля
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			//render
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('autorization/change_password', $this->data);
			$this->load->view('admin_panel/footer');
		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('autorization/change_password', 'refresh');
			}
		}
	}

	//forgot password/забыли пароль
	function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Электронная почта', 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('autorization/forgot_password', $this->data);
			$this->load->view('admin_panel/footer');
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
				redirect("autorization/forgot_password", 'refresh');
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
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
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
				$this->load->view('site_public/footer', array('contacts' => $this->contacts));
			}
			else
			{
				if (/*$this->_valid_csrf_nonce() === FALSE || */$user->id != $this->input->post('user_id'))
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
						redirect('autotization/reset_password/' . $code, 'refresh');
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


	//Код активации нового юзера с почты, а так же активация отключённого пользователя 
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("shop_history", 'refresh');
		}
		else
		{
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("autorization/forgot_password", 'refresh');
		}
	}

	//Деактивация юзера
	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'подтверждение', 'required');
		$this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('autorization/deactivate_user', $this->data);
			$this->load->view('admin_panel/footer');
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('user', 'refresh');
		}
	}

	//Создание нового пользователя в системе
	function create_user()
	{
		$this->data['title'] = "Create User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			if (!$this->ion_auth->in_group('Менеджер')) {
                redirect('login', 'refresh');
            }
		}

        $this->form_validation->set_rules('first_name', 'Логин', 'trim|required|xss_clean|callback__login_exist_check[users.username]');
		//$this->form_validation->set_rules('first_name', 'Имя', 'required|xss_clean');
		//$this->form_validation->set_rules('email', 'Электронная почта', 'valid_email');
		//$this->form_validation->set_rules('company', 'Компания', 'xss_clean');
		//$this->form_validation->set_rules('last_name', 'Фамилия', 'xss_clean');
		//$this->form_validation->set_rules('phone1', 'Телефон', 'xss_clean');
		//$this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
		//$this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|xss_clean|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('people_group', 'Группа', 'required|xss_clean');
        $this->form_validation->set_rules('brigadies', 'Бригада', 'xss_clean');
		$this->form_validation->set_rules('password', 'Пароль', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Повторите пароль', 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('first_name'));
			$password = $this->input->post('password');
			$people_group = (array) $this->input->post('people_group');
            $brigadies = 0;
            $brigadies2 = 0;
            if ($people_group[0] == 6) {
                $brigadies = $this->input->post('brigadies');
            }
            elseif ($people_group[0] == 8 || $people_group[0] == 9) {
                $brigadies2 = $this->input->post('brigadies_manufacture');
            }

			$additional_data = array(
				'first_name' => $this->input->post('first_name')
			);
            
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, '', $additional_data, $people_group, $brigadies, $brigadies2))
		{
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("autorization/index/".$people_group[0], 'refresh');
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
				'class' => 'form-control',
			);
			
            if ($this->ion_auth->in_group('Менеджер')) {
                $group = $this->ion_auth->groups()->result_array();
    			if ($group == TRUE) {
    				foreach ($group as $key => $value) {
    				    if ($value['name'] == 'Бригадир' || $value['name'] == 'Замерщик') {
    				        $this->data['gpoups']["$value[id]"] = $value['name'];
    				    }		
    				}
    			} else {
    				$this->data['gpoups']['error'] = 'Ошибка при получении информации';
    			}
            }
            else {
                $group = $this->ion_auth->groups()->result_array();
    			if ($group == TRUE) {
    				
                    $this->data['gpoups']['1'] = 'Не определена';
    				foreach ($group as $key => $value) {
    					$this->data['gpoups']["$value[id]"] = $value['name'];
    				}
    			} else {
    				$this->data['gpoups']['error'] = 'Ошибка при получении информации';
    			} 
            }
			
            $this->data['select_brigadies'] = $this->basic_functions_model->select(array('table' => 'brigadies', 'type' => 'list'));
            $this->data['select_brigadies_manufacture'] = $this->basic_functions_model->select(array('table' => 'brigadies_manufacture', 'type' => 'list'));
            
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
				'class' => 'form-control',
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
				'class' => 'form-control',
			);
            
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('autorization/create_user', $this->data);
			$this->load->view('admin_panel/footer');
		}
	}

	//Редактирование пользователя
	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
            if (!$this->ion_auth->in_group('Менеджер')) {
                redirect('login', 'refresh');
            }
		}

		$user = $this->ion_auth->user($id)->row();

		if (isset($user->phone) && !empty($user->phone))
		{
			$user->phone = explode('-', $user->phone);
		}

		//validate form input
		//$this->form_validation->set_rules('first_name', 'Имя', 'required|xss_clean');
        $this->form_validation->set_rules('first_name', 'Логин', 'trim|required|xss_clean|callback__login_exist_check[users.username.edit.'.$id.']');
		$this->form_validation->set_rules('people_group', 'Группа', 'required|xss_clean');
		//$this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
		//$this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|xss_clean|min_length[4]|max_length[4]');
		//$this->form_validation->set_rules('company', 'Компания', 'xss_clean');
		//$this->form_validation->set_rules('last_name', 'Фамилия', 'xss_clean');
		//$this->form_validation->set_rules('phone1', 'Телефон', 'xss_clean');
		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error('This form post did not pass our security checks.');
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
                'username' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
			);

			$groups = array(
				'user_id' => $user->id,
				'group_id'  => $this->input->post('people_group')
			);

			$data['group'] = (array) $this->input->post('people_group');
			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', 'Пароль', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'Повторите пароль', 'required');

				$data['password'] = $this->input->post('password');
			}
            
            $brigadies = 0;
            $brigadies2 = 0;
            if ($data['group'][0] == 6) {
                $brigadies = $this->input->post('brigadies');
            }
            elseif ($data['group'][0] == 8 || $data['group'][0] == 9) {
                $brigadies2 = $this->input->post('brigadies_manufacture');
            }

			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);
				$this->ion_auth->update_groups_users($groups);
                
                if ($brigadies == 0) {
                    $this->delete_from_brigade($user->id);
                }
                else {
                    $brigade = $this->basic_functions_model->select(array('table' => 'brigadies_members', 'type' => 'list', 'where_field' => 'id_user', 'where' => $user->id));
                    if (!empty($brigade)) {
                        $config_update_db = array(
    						'name_table' => 'brigadies_members',
    						'where_field' => 'id_user'
    				 	);
                        $form['id_brigade'] = $brigadies;
    					$this->basic_functions_model->update_db($config_update_db, $form, $id);
                    }
                    else {
                        $config_insert_db = array(
        					'name_table' => 'brigadies_members',
        					'insert_batch' => FALSE,
        					'insert_id' => TRUE
        				 );
                        $form['id_user'] = $user->id;
                        $form['id_brigade'] = $brigadies;
        				$this->basic_functions_model->insert_db($config_insert_db, $form);
                    }
                }
                if ($brigadies2 == 0) {
                    $this->delete_from_brigade_manufacture($user->id);
                }
                else {
                    $brigade = $this->basic_functions_model->select(array('table' => 'brigadies_manufacture_members', 'type' => 'list', 'where_field' => 'id_user', 'where' => $user->id));
                    if (!empty($brigade)) {
                        $config_update_db = array(
    						'name_table' => 'brigadies_manufacture_members',
    						'where_field' => 'id_user'
    				 	);
                        $form['id_brigade'] = $brigadies2;
    					$this->basic_functions_model->update_db($config_update_db, $form, $id);
                    }
                    else {
                        $config_insert_db = array(
        					'name_table' => 'brigadies_manufacture_members',
        					'insert_batch' => FALSE,
        					'insert_id' => TRUE
        				 );
                        $form['id_user'] = $user->id;
                        $form['id_brigade'] = $brigadies2;
        				$this->basic_functions_model->insert_db($config_insert_db, $form);
                    }
                }

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "Успешно сохранено");
				redirect("autorization/index/".$data['group'][0], 'refresh');
			}
		}

		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
			'class' => 'form-control',
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
			'class' => 'form-control',
		);
        if ($this->ion_auth->in_group('Менеджер')) {
            $group = $this->ion_auth->groups()->result_array();
			if ($group == TRUE) {
				foreach ($group as $key => $value) {
				    if ($value['name'] == 'Бригадир' || $value['name'] == 'Замерщик') {
				        $this->data['gpoups']["$value[id]"] = $value['name'];
				    }		
				}
			} else {
				$this->data['gpoups']['error'] = 'Ошибка при получении информации';
			}
        }
        else {
            $group = $this->ion_auth->groups()->result_array();
            if ($group == TRUE) {
                $this->data['gpoups']['1'] = 'Не выбрано';
                foreach ($group as $key => $value) {
                    $this->data['gpoups']["$value[id]"] = $value['name'];
                }
            } else {
                $this->data['gpoups']['error'] = 'Ошибка при получении информации';
            }
        }
		/*$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('company', $user->company),
		);*/
		/*$this->data['phone1'] = array(
			'name'  => 'phone1',
			'id'    => 'phone1',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone1', $user->phone[0]),
			'class' => 'form-control',
		);*/
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'class' => 'form-control',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'class' => 'form-control',
			'type' => 'password'
		);
        
        $this->data['select_brigadies'] = $this->basic_functions_model->select(array('table' => 'brigadies', 'type' => 'list'));
        $this->data['select_brigadies_manufacture'] = $this->basic_functions_model->select(array('table' => 'brigadies_manufacture', 'type' => 'list'));
        
        $brigada = $this->basic_functions_model->select(array('table' => 'brigadies_members', 'type' => 'list', 'where_field' => 'id_user', 'where' => $id));
        if (!empty($brigada)) {
            $this->data['brigade_number'] = $brigada[0]['id_brigade'];
        }
        else {
            $this->data['brigade_number'] = 0;
        }
        $brigada_manufacture = $this->basic_functions_model->select(array('table' => 'brigadies_manufacture_members', 'type' => 'list', 'where_field' => 'id_user', 'where' => $id));
        if (!empty($brigada_manufacture)) {
            $this->data['brigade_manufacture_number'] = $brigada_manufacture[0]['id_brigade'];
        }
        else {
            $this->data['brigade_manufacture_number'] = 0;
        }
        
        $gruppa = $this->basic_functions_model->select(array('table' => 'users_groups', 'type' => 'list', 'where_field' => 'user_id', 'where' => $id));
        $this->data['group_number'] = $gruppa[0]['group_id'];
        
		$this->load->view('admin_panel/header', array('menu' => $this->menu));
		$this->load->view('autorization/edit_user', $this->data);
		$this->load->view('admin_panel/footer');
	}

    function _login_exist_check ($field_one = NULL, $db_config = NULL) {
		$status = '';
		if ($field_one != NULL && $db_config != NULL) {
			$status = $this->basic_functions_model->alias_exist($db_config, $field_one);
		}
	    if ($status === FALSE) {
			$this->form_validation->set_message('_alias_exist_check', 'Пользователь с таким логином уже зарегистрирован');
		 	return FALSE;
		} elseif ($status === TRUE) {
			return TRUE;
		}
		
		$this->form_validation->set_message('_alias_exist_check', 'Ошибка запроса');
	    return FALSE;
    } 

	//Список групп доступа
	public function group() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('login', 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		//Список групп доступа
		$this->data['group'] = $this->ion_auth->groups()->result();

		foreach ($this->data['group'] as $k => $group) {
			$this->data['users'][$k]->group = $this->ion_auth->get_users_groups($group->id)->result();
		}
		$this->config->load('ion_auth');
		$this->data['ion_auth_config'] = $this->config->item('ion_auth');
		$this->load->view('admin_panel/header', array('menu' => $this->menu));
		$this->load->view('autorization/groups', $this->data);
		$this->load->view('admin_panel/footer');
	}

	//Создать новую группу пользователей
	function create_group()
	{
		$this->data['title'] = "Create Group";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('login', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', 'Имя группы', 'required|xss_clean');
		$this->form_validation->set_rules('description', 'Описание', 'xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("user", 'refresh');
			}
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('autorization/create_group', $this->data);
			$this->load->view('admin_panel/footer');
		}
	}

	//Редактирование группы
	function edit_group($id)
	{
		if(!$id || empty($id))
		{
			redirect('login', 'refresh');
		}

		$this->data['title'] = "Edit Group";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('login', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		$this->form_validation->set_rules('group_name', 'Имя группы', 'required|xss_clean');
		$this->form_validation->set_rules('group_description', 'Описание', 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', "Успешно сохранено");
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("user", 'refresh');
			}
		}

		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['group'] = $group;

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);
		$this->load->view('admin_panel/header', array('menu' => $this->menu));
		$this->load->view('autorization/edit_group', $this->data);
		$this->load->view('admin_panel/footer');
	}

/*
	* Удаление записей из таблиц 
	* $id - идентификатор записи
	* table_name - название таблицы из которой удаляется
	------------- Требование ко всем таблицам -------------
	* Название поля идентицикатора, _id постфикс к table_name.
*/
	public function delete() {
		if (isset($_POST['id']) && isset($_POST['delete']) && isset($_POST['table_name'])){

			$delete_id = $this->input->post('id');
			$table_name = $this->input->post('table_name');
			if (isset($_POST['field_name'])) {
				$field_name = $this->input->post('field_name');
			} else {
				$field_name = NULL;
			}
			
			$delete = $this->ion_auth_model->delete($delete_id, $table_name, $field_name);
			if ($delete == TRUE) {
				$this->session->set_flashdata('success', 'Запись успешно удалена.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Произошла ошибка при удалении записи, попробуйте позже.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
		} else {
			$this->session->set_flashdata('error', 'Для удаления выберите минимум одну запись.');
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
	}

/*
	* Удаление записей из таблиц 
	* $id - идентификатор записи
	* table_name - название таблицы из которой удаляется
	------------- Требование ко всем таблицам -------------
	* Название поля идентицикатора, _id постфикс к table_name.
*/
	public function privileges_delete() {
		if (isset($_POST['id']) && isset($_POST['delete']) && isset($_POST['table_name'])){

			$delete_id = $this->input->post('id');
			$table_name = 'privileges_user';
			$field_name = 'privileges_user_id';
			
			$delete = $this->ion_auth_model->delete($delete_id, $table_name, $field_name);
			if ($delete == TRUE) {
				$this->session->set_flashdata('success', 'Запись успешно удалена.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Произошла ошибка при удалении записи, попробуйте позже.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
		} else {
			$this->session->set_flashdata('error', 'Для удаления выберите минимум одну запись.');
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
	}
//Доступные организации для пользователя.
public function privileges_user($user_id = NULL) {
		if ($user_id != NULL && is_numeric($user_id)) {
					$organization_list = $this->ion_auth_model->privileges_user($user_id, NULL);
					$branch['user_id'] = $user_id;
					if ($organization_list != FALSE) {
						$this->load->view('admin_panel/header', array('menu' => $this->menu));
						$this->load->view('autorization/privileges_user', array('branch' => $organization_list, 'user_id' => $user_id));
						$this->load->view('admin_panel/footer');
					} else {
						$this->load->view('admin_panel/header', array('menu' => $this->menu));
						$this->load->view('autorization/privileges_user', array('user_id' => $user_id));
						$this->load->view('admin_panel/footer');
					}	
		} else {
			$this->session->set_flashdata('error', 'Произошла ошибка при получении данных, попробуйте позднее.');
			redirect('autorization', 'refresh');
		}
	}

	public function privileges_user_create($user_id = NULL) {
		if (isset($_POST['save']) && !empty($_POST['save'])) {
			$user_id = $this->input->post('user_id');
			$branch_id = $this->input->post('branch_id');
			$object_list = $this->ion_auth_model->privileges_user($user_id, 2, $branch_id);
			if ($object_list == TRUE) {
				$this->session->set_flashdata('success', 'Объект добавлен в список доступных.');
				redirect('autorization/privileges_user/' . $user_id, 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Произошла ошибка при записи данных, попробуйте позднее.');
				redirect('autorization', 'refresh');
			}
		} elseif (!isset($_POST['save']) || empty($_POST['save'])) {
			if ($user_id != NULL && is_numeric($user_id)) {
				$object_list = $this->ion_auth_model->privileges_user($user_id, 1);
				if ($object_list != FALSE) {
					$this->load->view('admin_panel/header', array('menu' => $this->menu));
					$this->load->view('autorization/privileges_user_create', array('branch' => $object_list, 'user_id' => $user_id));
					$this->load->view('admin_panel/footer');
				} else {
					$this->session->set_flashdata('error', 'Нет доступных филиалов для выбора.');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', 'Произошла ошибка при обработке данных, получены неверные данные.');
				redirect('autorization', 'refresh');
			}
		}
	}

public function privileges_branch($user_id = NULL, $branch_id = NULL) {
		if (is_numeric($user_id) && is_numeric($branch_id)) {
			if (isset($_POST['save'])) {
				$rules = $this->input->post('rules');
				//Переписать кусок кода
				if (!isset($rules['privileges_review'])) {
					$rules['privileges_review'] = 0;
				} 
				if (!isset($rules['privileges_create'])) {
					$rules['privileges_create'] = 0;
				} 
				if (!isset($rules['privileges_edit'])) {
					$rules['privileges_edit'] = 0;
				} 
				if (!isset($rules['privileges_delete'])) {$rules['privileges_delete'] = 0; } 
				//-----------------------------
				$rules = $this->ion_auth_model->privileges_rules(NULL, NULL, $rules);
				if ($rules == TRUE) {
					$this->session->set_flashdata('success', 'Изменения успешно сохранены.');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Произошла ошибка при записи данных, попробуйте позднее.');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} else {
					$rules = $this->ion_auth_model->privileges_rules($user_id, $branch_id, FALSE);
					$rules['user_id'] = $user_id;
					if ($rules != FALSE) {
						$this->load->view('admin_panel/header', array('menu' => $this->menu));
						$this->load->view('autorization/privileges_branch', array('rules' => $rules));
						$this->load->view('admin_panel/footer');
					} else {
						$this->session->set_flashdata('error', 'Произошла ошибка при получении данных, попробуйте позднее.');
						redirect('autorization/privileges_user/'.$user_id, 'refresh');
					}
			}
		} else {
			$this->session->set_flashdata('error', 'Произошла ошибка при получении данных, попробуйте позднее.');
			redirect('autorization', 'refresh');
		}
	}

/*
	* Удаление записей из таблиц 
	* $id - идентификатор записи
	* table_name - название таблицы из которой удаляется
	------------- Требование ко всем таблицам -------------
	* Название поля идентицикатора, _id постфикс к table_name.
*/
	public function user_delete() {
		if (isset($_POST['id']) && isset($_POST['delete']) && isset($_POST['table_name'])){

			$delete_id = $this->input->post('id');
			$table_name = $this->input->post('table_name');
			 
			$delete = $this->ion_auth_model->delete_user($delete_id, $table_name);
            $delete_brigade = $this->delete_from_brigade($delete_id);
            
			if ($delete == TRUE) {
				$this->session->set_flashdata('success', 'Запись успешно удалена.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Произошла ошибка при удалении записи, попробуйте позже.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
		} else {
			$this->session->set_flashdata('error', 'Для удаления выберите минимум одну запись.');
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
	}
    
	public function delete_from_brigade($id = NULL) {
		$status = FALSE;
		if ($id != NULL) {
    		$delete = array('table' => 'brigadies_members', 'field' => 'id_user', 'id' => $id);
    		$status = $this->basic_functions_model->delete($delete);
		}

		return $status;
	}
    
   	public function delete_from_brigade_manufacture($id = NULL) {
		$status = FALSE;
		if ($id != NULL) {
    		$delete = array('table' => 'brigadies_manufacture_members', 'field' => 'id_user', 'id' => $id);
    		$status = $this->basic_functions_model->delete($delete);
		}

		return $status;
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $render=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}
}
