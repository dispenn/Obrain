<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
	------------------------Важная информация------------------------
	* Тип таблиц Innodb, поэтому все связи реализованы в самом mysql.
	* Вся обработка входящих данных, происходит в form validation (trim, xss, дополнительное экранирование желательно)
*/
class Administrator extends CI_Controller {

	public function __construct() {
		parent::__construct();
        if (!$this->ion_auth->logged_in()) {
			redirect('login', 'refresh');
		}
	}

/*
	* Стартовая страница администраторской части
*/
	public function index() {
		$this->load->view('admin_panel/header', array('menu' => $this->menu));
		$this->load->view('admin_panel/index', array('menu' => $this->menu));
		$this->load->view('admin_panel/footer');
	}

/*
	* Список страниц
*/
	public function pages () {
		if (!$this->ion_auth->is_admin()) {
			redirect('administrator', 'refresh');
		}

		$list = $this->basic_functions_model->select(array('table' => 'page_content', 'type' => 'list', 'sort' => 'id', 'type_sort' => 'desc'));
		if ($list !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/list_pages', array('lists' => $list));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}

/*
	* Создание статических страниц
*/
	public function create_pages() {
		if (!$this->ion_auth->is_admin()) {
			redirect('administrator', 'refresh');
		}

		$this->form_validation->set_rules($this->rules_model->pages_rules);
		if ($this->form_validation->run() === FALSE) {
			$list = $this->basic_functions_model->select(array('table' => 'page_content', 'type' => 'list'));
			$form = $this->input->post('form');
			if (!empty($form['alias'])) {
				$form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
				$form['alias'] = $this->alias_exist_check_translate_rand('page_content', $form['alias']);
			} else {
				$form['alias'] = $this->alias_exist_check_translate_rand('page_content', $form['title']);
			}
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/create_pages', array('select_list' => $list, 'form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
			if (!empty($form['alias'])) {
				$form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
				$form['alias'] = $this->alias_exist_check_translate_rand('page_content', $form['alias']);
			} else {
				$form['alias'] = $this->alias_exist_check_translate_rand('page_content', $form['title']);
			}
			if (is_array($form) && !empty($form)) {
				$config_insert_db = array(
					'name_table' => 'page_content',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				);

				$id_page = $this->basic_functions_model->insert_db($config_insert_db, $form);

				if ($id_page === TRUE || is_numeric($id_page)) {
					$this->general_functions->alert('success', 'administrator/pages', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/pages', 'Произошла ошибка, попробуйте позднее.');
				}
			}
		}
	}

	public function edit_pages($id = NULL) {
		if (!$this->ion_auth->is_admin()) {
			redirect('administrator', 'refresh');
		}

		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'page_content', 'type' => 'list', 'where_field' => 'id', 'where' => $id));

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/create_pages', array('form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->pages_rules);
			$this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[page_content.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');

				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/create_pages', array('form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'page_content',
						'where_field' => 'id'
					);

					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/pages', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/pages', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		}
	}

/*
    Баннеры
*/
    public function banners () {
        $list = $this->basic_functions_model->select_with_images(array('table' => 'banners', 'type' => 'list', 'image_field' => 'id_image', 'sort' => 'position'), 1000);
		
        if ($list !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/banners/list_banners', array('lists' => $list));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}

/*
    Создать баннер
*/
	public function create_banner() {
		$this->form_validation->set_rules($this->rules_model->banner_rules);
        if (empty($_FILES['file']['name'][0])) {
			$this->form_validation->set_rules('file[]', 'Изображение', 'required');
		}
		if ($this->form_validation->run() === FALSE) {
			$form = $this->input->post('form');
           
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/banners/create_banner', array('form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            
			if (is_array($form) && !empty($form)) {
			     $config_insert_db = array(
					'name_table' => 'banners',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
                 
				if ($_FILES) {
	            	$directory = 'images';
	            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1800, 1800);
	            	if ($file !== FALSE){
	            		$file_status = $this->basic_functions_model->upload_files_db($file, 1);
	            	}
	            }
	            $status = FALSE;
	            if (isset($file_status) && is_array($file_status)) {
	            	$form['id_image'] = $file_status[0]['id'];
	    	        $status = $this->basic_functions_model->insert_db($config_insert_db, $form); 
	            }
                else {
                    $this->general_functions->alert('error', 'administrator/banners', 'Призошла ошибка. Не удалось загрузить изображение. Размер файла не должен превышать 3 МБ.');	
                }

				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/banners', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/banners', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

/*
	* Редактирование 
*/
	public function edit_banner($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'banners', 'type' => 'list', 'where_field' => 'id', 'where' => $id));

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/banners/create_banner', array('form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->banner_rules);
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/banners/create_banner', array('form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'banners',
						'where_field' => 'id'
				 	);
                    if ($_FILES) {
		            	$directory = 'images';
		            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1800, 1800);

		            	if ($file !== FALSE){
		            		$list_file = $this->basic_functions_model->select(array('table' => 'banners', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
		            		if (isset($list_file[0]['id_image'])) {
                                $delete_all[] = $list_file[0]['id_image'];
    		            		$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            }
		            		
	            				$file_status = $this->basic_functions_model->upload_files_db($file, 1);
			            		if (isset($file_status) && is_array($file_status)) {
			            			$form['id_image'] = $file_status[0]['id'];
				            	}	
      		            }
		            }
                    
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/banners', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/banners', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}


/*
    Акции
*/
    public function promos () {
        $list = $this->basic_functions_model->select_with_images(array('table' => 'promos', 'type' => 'list', 'image_field' => 'id_image'), 1000);
		
        if ($list !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/promos/list_promos', array('lists' => $list));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}



/*
    Новая акция
*/
	public function create_promo() {
		$this->form_validation->set_rules($this->rules_model->promo_rules);
        if (empty($_FILES['file']['name'][0])) {
			$this->form_validation->set_rules('file[]', 'Изображение', 'required');
		}
		if ($this->form_validation->run() === FALSE) {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('promos', $form['title']);
            }
           
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/promos/create_promo', array('form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('promos', $form['title']);
            }
            if ($form['date'] != "") {
                $form['date'] = strtotime($form['date']);
            } else {
                $form['date'] = time(); // Текущая дата
            }
			if (is_array($form) && !empty($form)) {
			     $config_insert_db = array(
					'name_table' => 'promos',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
                 
				if ($_FILES) {
	            	$directory = 'images';
	            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);
	            	if ($file !== FALSE){
	            		$file_status = $this->basic_functions_model->upload_files_db($file, 1);
	            	}
	            }
	            $status = FALSE;
	            if (isset($file_status) && is_array($file_status)) {
	            	$form['id_image'] = $file_status[0]['id'];
	    	        $status = $this->basic_functions_model->insert_db($config_insert_db, $form); 
	            }
                else {
                    $this->general_functions->alert('error', 'administrator/promos', 'Призошла ошибка. Не удалось загрузить изображение. Размер файла не должен превышать 3 МБ.');	
                }

				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/promos', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/promos', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

/*
	* Редактирование 
*/
	public function edit_promo($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'promos', 'type' => 'list', 'where_field' => 'id', 'where' => $id));

            $form[0]['date'] = date("d.m.Y", $form[0]['date']);

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/promos/create_promo', array('form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->promo_rules);
            $this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[promos.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/promos/create_promo', array('form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
                if ($form['date'] != "") {
                    $form['date'] = strtotime($form['date']);
                } else {
                    $form['date'] = time(); // Текущая дата
                }
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'promos',
						'where_field' => 'id'
				 	);
                    if ($_FILES) {
		            	$directory = 'images';
		            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);

		            	if ($file !== FALSE){
		            		$list_file = $this->basic_functions_model->select(array('table' => 'promos', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
		            		if (isset($list_file[0]['id_image'])) {
                                $delete_all[] = $list_file[0]['id_image'];
    		            		$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            }
		            		
	            				$file_status = $this->basic_functions_model->upload_files_db($file, 1);
			            		if (isset($file_status) && is_array($file_status)) {
			            			$form['id_image'] = $file_status[0]['id'];
				            	}	
      		            }
		            }
                    
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/promos', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/promos', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}

/*
    Картинки
*/
    public function main_images() {
        $id = 1;	
        if (!isset($_POST['save'])) {
            //$form = $this->basic_functions_model->select(array('table' => 'main_images', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            
            $form = $this->admin_model->select_main_images();
            
            if (!empty($form))
            {
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('admin_panel/main_images', array('form' => $form[0], 'id' => $id));
    			$this->load->view('admin_panel/footer');
            }
            else
            {
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('admin_panel/main_images', array('id' => $id));
    			$this->load->view('admin_panel/footer');
            }
        }
        elseif (isset($_POST)) {
            if ($_FILES) {
                $form = array();
                
                $list_file = $this->basic_functions_model->select(array('table' => 'main_images', 'type' => 'list', 'where_field' => 'id', 'where' => 1));

                if ($_FILES['logo_topm']) {
           	        $directory = 'files';

                    if ($_FILES['logo_topm']['error'] == 0) {
                        if (isset($list_file[0]['id_logo_topm'])) {
                            $delete_all[] = $list_file[0]['id_logo_topm'];
                            $status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            unset($delete_all);
                        }
                    }

           	        $file = $this->general_functions->upload_files_with_name($_FILES['logo_topm'], $directory, 'logo_topm');

                    if ($file !== FALSE){
                        $file_status = $this->basic_functions_model->upload_files_db($file, 1);
                        if (isset($file_status) && is_array($file_status)) {
                            $form['id_logo_topm'] = $file_status[0]['id'];
                        }
   		            }

                }
                
                if ($_FILES['top']) {
           	        $directory = 'files';

                    if ($_FILES['top']['error'] == 0) {
                        if (isset($list_file[0]['id_top'])) {
                            $delete_all[] = $list_file[0]['id_top'];
                            $status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            unset($delete_all);
                        }
                    }

           	        $file = $this->general_functions->upload_files_with_name($_FILES['top'], $directory, 'top');

                    if ($file !== FALSE){
                        $file_status = $this->basic_functions_model->upload_files_db($file, 1);
                        if (isset($file_status) && is_array($file_status)) {
                            $form['id_top'] = $file_status[0]['id'];
                        }
   		            }
                }
                
                if ($_FILES['top_bg']) {
           	        $directory = 'files';

                    if ($_FILES['top_bg']['error'] == 0) {
                        if (isset($list_file[0]['id_top_bg'])) {
                            $delete_all[] = $list_file[0]['id_top_bg'];
                            $status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            unset($delete_all);
                        }
                    }

           	        $file = $this->general_functions->upload_files_with_name($_FILES['top_bg'], $directory, 'top_bg');

                    if ($file !== FALSE){
                        $file_status = $this->basic_functions_model->upload_files_db($file, 1);
                        if (isset($file_status) && is_array($file_status)) {
                            $form['id_top_bg'] = $file_status[0]['id'];
                        }	
   		            }
                }
                
                if ($_FILES['bg']) {
           	        $directory = 'files';

                    if ($_FILES['bg']['error'] == 0) {
                        if (isset($list_file[0]['id_bg'])) {
                            $delete_all[] = $list_file[0]['id_bg'];
                            $status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            unset($delete_all);
                        }
                    }

           	        $file = $this->general_functions->upload_files_with_name($_FILES['bg'], $directory, 'bg');

                    if ($file !== FALSE){
                        $file_status = $this->basic_functions_model->upload_files_db($file, 1);
                        if (isset($file_status) && is_array($file_status)) {
                            $form['id_bg'] = $file_status[0]['id'];
                        }	
   		            }
                }
                
                if ($_FILES['logo_top']) {
           	        $directory = 'files';

                    if ($_FILES['logo_top']['error'] == 0) {
                        if (isset($list_file[0]['id_logo_top'])) {
                            $delete_all[] = $list_file[0]['id_logo_top'];
                            $status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            unset($delete_all);
                        }
                    }

           	        $file = $this->general_functions->upload_files_with_name($_FILES['logo_top'], $directory, 'logo_top');

                    if ($file !== FALSE){
                        $file_status = $this->basic_functions_model->upload_files_db($file, 1);
                        if (isset($file_status) && is_array($file_status)) {
                            $form['id_logo_top'] = $file_status[0]['id'];
                        }
   		            }
                }

                $config_update_db = array(
				    'name_table' => 'main_images',
				    'where_field' => 'id'
		 	    );
          
                $list = $this->basic_functions_model->select(array('table' => 'main_images', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
                if (!empty($list)) {
                    $status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
                } else {
                    $status = $this->basic_functions_model->insert_db(array('name_table' => 'main_images', 'insert_batch' => FALSE, 'insert_id' => TRUE), $form);
                }
                 
   				if ($status === TRUE || is_numeric($status)) {
  					$this->general_functions->alert('success', 'administrator/main_images', 'Запись успешно сохранена.');
   				} else {
  					$this->general_functions->alert('error', 'administrator/main_images', 'Произошла ошибка, попробуйте позднее.');	
   				}

    		}
        }
	}

/*
    Новая акция
*/
	public function create_region() {
		$this->form_validation->set_rules($this->rules_model->region_rules);
		if ($this->form_validation->run() === FALSE) {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('regions', $form['title']);
            }
           
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/create_region', array('form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('regions', $form['title']);
            }

			if (is_array($form) && !empty($form)) {
			     $config_insert_db = array(
					'name_table' => 'regions',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
                 
                $status = $this->basic_functions_model->insert_db($config_insert_db, $form); 

				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/about', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/about', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

/*
	* Редактирование 
*/
	public function edit_region($id = NULL) {
	    if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'regions', 'type' => 'list', 'where_field' => 'id', 'where' => $id));

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/create_region', array('form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->region_rules);
            $this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[regions.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/create_region', array('form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');

				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'regions',
						'where_field' => 'id'
				 	);
                    
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/about', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/about', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}

/*
    Новости
*/
    public function news () {
        $list = $this->basic_functions_model->select_with_images(array('table' => 'news', 'type' => 'list', 'image_field' => 'id_image'), 1000);
		
        if ($list !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/news/list_news', array('lists' => $list));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}

/*
    Новая новость
*/
	public function create_news() {
		$this->form_validation->set_rules($this->rules_model->promo_rules);
        if (empty($_FILES['file']['name'][0])) {
			$this->form_validation->set_rules('file[]', 'Изображение', 'required');
		}
		if ($this->form_validation->run() === FALSE) {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('news', $form['title']);
            }
           
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/news/create_news', array('form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('news', $form['title']);
            }
            if ($form['date'] != "") {
                $form['date'] = strtotime($form['date']);
            } else {
                $form['date'] = time(); // Текущая дата
            }
			if (is_array($form) && !empty($form)) {
			     $config_insert_db = array(
					'name_table' => 'news',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
                 
				if ($_FILES) {
	            	$directory = 'images';
	            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);
	            	if ($file !== FALSE){
	            		$file_status = $this->basic_functions_model->upload_files_db($file, 1);
	            	}
	            }
	            $status = FALSE;
	            if (isset($file_status) && is_array($file_status)) {
	            	$form['id_image'] = $file_status[0]['id'];
	    	        $status = $this->basic_functions_model->insert_db($config_insert_db, $form); 
	            }
                else {
                    $this->general_functions->alert('error', 'administrator/news', 'Призошла ошибка. Не удалось загрузить изображение. Размер файла не должен превышать 3 МБ.');	
                }

				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/news', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/news', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

/*
	* Редактирование 
*/
	public function edit_news($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'news', 'type' => 'list', 'where_field' => 'id', 'where' => $id));

            $form[0]['date'] = date("d.m.Y", $form[0]['date']);

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/news/create_news', array('form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->promo_rules);
            $this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[news.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/news/create_news', array('form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
                if ($form['date'] != "") {
                    $form['date'] = strtotime($form['date']);
                } else {
                    $form['date'] = time(); // Текущая дата
                }
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'news',
						'where_field' => 'id'
				 	);
                    if ($_FILES) {
		            	$directory = 'images';
		            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);

		            	if ($file !== FALSE){
		            		$list_file = $this->basic_functions_model->select(array('table' => 'news', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
		            		if (isset($list_file[0]['id_image'])) {
                                $delete_all[] = $list_file[0]['id_image'];
    		            		$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            }
		            		
	            				$file_status = $this->basic_functions_model->upload_files_db($file, 1);
			            		if (isset($file_status) && is_array($file_status)) {
			            			$form['id_image'] = $file_status[0]['id'];
				            	}	
      		            }
		            }
                    
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/news', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/news', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}


/*
    Публикации
*/
    public function articles () {
        $list = $this->basic_functions_model->select_with_images(array('table' => 'articles', 'type' => 'list', 'image_field' => 'id_image'), 1000);
		
        if ($list !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/articles/list_articles', array('lists' => $list));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}

/*
    Новая новость
*/
	public function create_article() {
		$this->form_validation->set_rules($this->rules_model->promo_rules);
        if (empty($_FILES['file']['name'][0])) {
			$this->form_validation->set_rules('file[]', 'Изображение', 'required');
		}
		if ($this->form_validation->run() === FALSE) {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('articles', $form['title']);
            }
           
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/articles/create_article', array('form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('articles', $form['title']);
            }
            if ($form['date'] != "") {
                $form['date'] = strtotime($form['date']);
            } else {
                $form['date'] = time(); // Текущая дата
            }
			if (is_array($form) && !empty($form)) {
			     $config_insert_db = array(
					'name_table' => 'articles',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
                 
				if ($_FILES) {
	            	$directory = 'images';
	            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);
	            	if ($file !== FALSE){
	            		$file_status = $this->basic_functions_model->upload_files_db($file, 1);
	            	}
	            }
	            $status = FALSE;
	            if (isset($file_status) && is_array($file_status)) {
	            	$form['id_image'] = $file_status[0]['id'];
	    	        $status = $this->basic_functions_model->insert_db($config_insert_db, $form); 
	            }
                else {
                    $this->general_functions->alert('error', 'administrator/articles', 'Призошла ошибка. Не удалось загрузить изображение. Размер файла не должен превышать 3 МБ.');	
                }

				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/articles', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/articles', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

/*
	* Редактирование 
*/
	public function edit_article($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'articles', 'type' => 'list', 'where_field' => 'id', 'where' => $id));

            $form[0]['date'] = date("d.m.Y", $form[0]['date']);

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/articles/create_article', array('form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->promo_rules);
            $this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[articles.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
                
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/articles/create_article', array('form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
                if ($form['date'] != "") {
                    $form['date'] = strtotime($form['date']);
                } else {
                    $form['date'] = time(); // Текущая дата
                }
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'articles',
						'where_field' => 'id'
				 	);
                    if ($_FILES) {
		            	$directory = 'images';
		            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);

		            	if ($file !== FALSE){
		            		$list_file = $this->basic_functions_model->select(array('table' => 'articles', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
		            		if (isset($list_file[0]['id_image'])) {
                                $delete_all[] = $list_file[0]['id_image'];
    		            		$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            }
		            		
	            				$file_status = $this->basic_functions_model->upload_files_db($file, 1);
			            		if (isset($file_status) && is_array($file_status)) {
			            			$form['id_image'] = $file_status[0]['id'];
				            	}	
      		            }
		            }
                    
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/articles', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/articles', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}


/*
    Сотрудники
*/
    public function employees () {
        //$list = $this->basic_functions_model->select_with_images(array('table' => 'employees', 'type' => 'list', 'image_field' => 'id_image'), 1000);
		$list = $this->admin_model->select_employees();
        
        if ($list !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/employees/list_employees', array('lists' => $list));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}

/*
    Новый сотрудник
*/
	public function create_employee() {
		$this->form_validation->set_rules($this->rules_model->employee_rules);
        if (empty($_FILES['file']['name'][0])) {
			$this->form_validation->set_rules('file[]', 'Изображение', 'required');
		}
		if ($this->form_validation->run() === FALSE) {
			$form = $this->input->post('form');
            $select_list = $this->basic_functions_model->select(array('table' => 'departments', 'type' => 'list'));

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/employees/create_employee', array('form' => $form, 'select_list' => $select_list));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
			if (is_array($form) && !empty($form)) {
			     $config_insert_db = array(
					'name_table' => 'employees',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
                 
				if ($_FILES) {
	            	$directory = 'images';
	            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);
	            	if ($file !== FALSE){
	            		$file_status = $this->basic_functions_model->upload_files_db($file, 1);
	            	}
	            }
	            $status = FALSE;
	            if (isset($file_status) && is_array($file_status)) {
	            	$form['id_image'] = $file_status[0]['id'];
	    	        $status = $this->basic_functions_model->insert_db($config_insert_db, $form); 
	            }
                else {
                    $this->general_functions->alert('error', 'administrator/employees', 'Призошла ошибка. Не удалось загрузить изображение. Размер файла не должен превышать 3 МБ.');	
                }

				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/employees', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/employees', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

/*
	* Редактирование 
*/
	public function edit_employee($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'employees', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            $select_list = $this->basic_functions_model->select(array('table' => 'departments', 'type' => 'list'));

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/employees/create_employee', array('form' => $form[0], 'id' => $id, 'select_list' => $select_list));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->employee_rules);
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
                $select_list = $this->basic_functions_model->select(array('table' => 'departments', 'type' => 'list'));
                
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/employees/create_employee', array('form' => $form, 'id' => $id, 'select_list' => $select_list));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'employees',
						'where_field' => 'id'
				 	);
                    if ($_FILES) {
		            	$directory = 'images';
		            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);

		            	if ($file !== FALSE){
		            		$list_file = $this->basic_functions_model->select(array('table' => 'employees', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
		            		if (isset($list_file[0]['id_image'])) {
                                $delete_all[] = $list_file[0]['id_image'];
    		            		$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            }
		            		
	            				$file_status = $this->basic_functions_model->upload_files_db($file, 1);
			            		if (isset($file_status) && is_array($file_status)) {
			            			$form['id_image'] = $file_status[0]['id'];
				            	}	
      		            }
		            }
                    
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/employees', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/employees', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}

/*
    Вакансии
*/
    public function vacancy () {
        $vacancy = $this->basic_functions_model->select(array('table' => 'vacancy', 'type' => 'list'));
        
        if ($vacancy !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/vacancy/list_vacancy', array('lists' => $vacancy));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}

/*
    Новые вакансии
*/
	public function create_vacancy() {
		
        $this->form_validation->set_rules($this->rules_model->vacancy_rules);
        if ($this->form_validation->run() === FALSE) {  
            $this->load->view('admin_panel/header', array('menu' => $this->menu));
            $this->load->view('admin_panel/vacancy/create_vacancy');
            $this->load->view('admin_panel/footer');
        } else {
        
		$form = $this->input->post('form');
			if (is_array($form) && !empty($form)) {         
				$config_insert_db = array(
					'name_table' => 'vacancy',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
        
        
                 
		$status = $this->basic_functions_model->insert_db($config_insert_db, $form);
           }
        if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/vacancy', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/vacancy', 'Произошла ошибка, попробуйте позднее.');	
				}
        }
	}

/*
	* Редактирование вакансий
*/
	public function edit_vacancy($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'vacancy', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/vacancy/create_vacancy', array('form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->vacancy_rules);
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
                
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/vacancy/create_vacancy', array('form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'vacancy',
						'where_field' => 'id'
				 	);
                                        
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/vacancy', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/vacancy', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}



/*
    каталог
*/
    public function catalog () {
		$list = $this->admin_model->select_catalog();
        
        if ($list !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/catalog/list_catalog', array('lists' => $list));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}

/*
    Новый каталог
*/
	public function create_catalog() {
		$this->form_validation->set_rules($this->rules_model->catalog_rules);
        if (empty($_FILES['file']['name'][0])) {
			$this->form_validation->set_rules('file[]', 'Изображение', 'required');
		}
		if ($this->form_validation->run() === FALSE) {
			$form = $this->input->post('form');
            $select_list = $this->basic_functions_model->select(array('table' => 'category_catalog', 'type' => 'list', 'where_field' => 'id_main_category', 'where' => 0, 'compare' => '!='));

            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('catalog', $form['title']);
            }

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/catalog/create_catalog', array('form' => $form, 'select_list' => $select_list));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('catalog', $form['title']);
            }
            
			if (is_array($form) && !empty($form)) {
			     $config_insert_db = array(
					'name_table' => 'catalog',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
                 
				if ($_FILES['file']) {
	            	$directory = 'catalog';
	            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);
	            	if ($file !== FALSE){
	            		$file_status = $this->basic_functions_model->upload_files_db($file, 1);
	            	}
	            }
                
                $id_catalog = FALSE;
	            if (isset($file_status) && is_array($file_status)) {
	            	$form['id_image'] = $file_status[0]['id'];
	    	        $id_catalog = $this->basic_functions_model->insert_db($config_insert_db, $form); 
                    
                    if ($id_catalog === TRUE || is_numeric($id_catalog)) {
                        if ($_FILES['image']) {
        	            	$directory = 'catalog';
        	            	$file_image = $this->general_functions->upload_files($_FILES['image'], $directory, 1, 1000, 1000);
        	            	if ($file_image !== FALSE){
        	            		$file_id_images = $this->basic_functions_model->upload_files_db($file_image, 1);
        	            	}
        	            }
                        
                        if (isset($file_id_images) && is_array($file_id_images)) {
                            $data = array();
                            foreach ($file_id_images as $id_image) {
        	            		$data[] = array('id_all_files' => $id_image['id'], 'id_catalog' => $id_catalog);
        	            	}
                            $status = $this->basic_functions_model->insert_db(array('name_table' => 'catalog_images', 'insert_batch' => TRUE, 'insert_id' => FALSE), $data);
                            unset($data);
                        }
                        
    					$this->general_functions->alert('success', 'administrator/catalog', 'Запись успешно сохранена.');
    				} else {
    					$this->general_functions->alert('error', 'administrator/catalog', 'Произошла ошибка, попробуйте позднее.');	
    				}
	            }
                else {
                    $this->general_functions->alert('error', 'administrator/catalog', 'Призошла ошибка. Не удалось загрузить изображение. Размер файла не должен превышать 3 МБ.');	
                }
			}
		}
	}

/*
	* Редактирование 
*/
	public function edit_catalog($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'catalog', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            $select_list = $this->basic_functions_model->select(array('table' => 'category_catalog', 'type' => 'list', 'where_field' => 'id_main_category', 'where' => 0, 'compare' => '!='));

            $images = $this->basic_functions_model->select_with_images(array('table' => 'catalog_images', 'type' => 'list', 'where_field' => 'id_catalog', 'where' => $id, 'image_field' => 'id_all_files'), 1000);

			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/catalog/create_catalog', array('images' => $images, 'form' => $form[0], 'id' => $id, 'select_list' => $select_list));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->catalog_rules);
            $this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[catalog.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
                $select_list = $this->basic_functions_model->select(array('table' => 'category_catalog', 'type' => 'list', 'where_field' => 'id_main_category', 'where' => 0, 'compare' => '!='));
                
                $images = $this->basic_functions_model->select_with_images(array('table' => 'catalog_images', 'type' => 'list', 'where_field' => 'id_catalog', 'where' => $id, 'image_field' => 'id_all_files'), 1000);
                
                if (!empty($form['alias'])) {
                    $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
                } else {
                    $form['alias'] = $this->alias_exist_check_translate_rand('catalog', $form['title']);
                }
                
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/catalog/create_catalog', array('images' => $images, 'form' => $form, 'id' => $id, 'select_list' => $select_list));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'catalog',
						'where_field' => 'id'
				 	);
                    if ($_FILES['file']) {
		            	$directory = 'catalog';
		            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, 1000, 1000);

		            	if ($file !== FALSE){
		            		$list_file = $this->basic_functions_model->select(array('table' => 'catalog', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
		            		if (isset($list_file[0]['id_image'])) {
                                $delete_all[] = $list_file[0]['id_image'];
    		            		$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
                            }
		            		
	            				$file_status = $this->basic_functions_model->upload_files_db($file, 1);
			            		if (isset($file_status) && is_array($file_status)) {
			            			$form['id_image'] = $file_status[0]['id'];
				            	}	
      		            }
		            }
                    
                    if ($_FILES['image']) {
    	            	$directory = 'catalog';
    	            	$file_image = $this->general_functions->upload_files($_FILES['image'], $directory, 1, 1000, 1000);
    	            	if ($file_image !== FALSE){
    	            		$file_id_images = $this->basic_functions_model->upload_files_db($file_image, 1);
    	            	}
    	            }
    	            if (isset($file_id_images) && is_array($file_id_images)) {
                        $data = array();
                        foreach ($file_id_images as $id_image) {
    	            		$data[] = array('id_all_files' => $id_image['id'], 'id_catalog' => $id);
    	            	}
                        $status = $this->basic_functions_model->insert_db(array('name_table' => 'catalog_images', 'insert_batch' => TRUE, 'insert_id' => FALSE), $data);
    	                unset($data);
                    }
                    
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/catalog', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/catalog', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}


/*
	* Создание отдела
*/
	public function create_department() {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		$this->form_validation->set_rules($this->rules_model->department_rules);
        //$this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[departments.alias]');
		if ($this->form_validation->run() === FALSE) {
			$list = $this->basic_functions_model->select(array('table' => 'departments', 'type' => 'list'));
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('departments', $form['title']);
            }
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/employees/departments', array('list' => $list, 'form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('departments', $form['title']);
            }
			if (is_array($form) && !empty($form)) {
				$config_insert_db = array(
					'name_table' => 'departments',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
				$status = $this->basic_functions_model->insert_db($config_insert_db, $form);
				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/create_department', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/create_department', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

 /*
	* Редактирование 
*/
	public function edit_department($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'departments', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
			
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/employees/departments', array('form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->department_rules);
            $this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[departments.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/employees/departments', array('form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'departments',
						'where_field' => 'id'
				 	);
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/create_department', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/create_department', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}
    
/*
	* Создание категории продукции
*/
	public function create_category_catalog() {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		$this->form_validation->set_rules($this->rules_model->category_catalog_rules);
		if ($this->form_validation->run() === FALSE) {
			$list = $this->admin_model->select_category_catalog();
			$form = $this->input->post('form');
            
            $select_list = $this->basic_functions_model->select(array('table' => 'icons', 'type' => 'list'));
            
            $main_category_list = $this->admin_model->select_category_catalog(TRUE);
            
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('category_catalog', $form['title']);
            }
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/catalog/category_catalog', array('main_category_list' => $main_category_list, 'select_list' => $select_list, 'list' => $list, 'form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('category_catalog', $form['title']);
            }
			if (is_array($form) && !empty($form)) {
				$config_insert_db = array(
					'name_table' => 'category_catalog',
					'insert_batch' => FALSE,
					'insert_id' => TRUE
				 );
				$status = $this->basic_functions_model->insert_db($config_insert_db, $form);
				if ($status === TRUE || is_numeric($status)) {
					$this->general_functions->alert('success', 'administrator/create_category_catalog', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/create_category_catalog', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

 /*
	* Редактирование 
*/
	public function edit_category_catalog($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'category_catalog', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
			
            $select_list = $this->basic_functions_model->select(array('table' => 'icons', 'type' => 'list'));
            
            $main_category_list = $this->admin_model->select_category_catalog(TRUE);
            
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/catalog/category_catalog', array('main_category_list' => $main_category_list, 'select_list' => $select_list, 'form' => $form[0], 'id' => $id));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
			$this->form_validation->set_rules($this->rules_model->category_catalog_rules);
            $this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[category_catalog.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
				$select_list = $this->basic_functions_model->select(array('table' => 'icons', 'type' => 'list'));
                $main_category_list = $this->admin_model->select_category_catalog(TRUE);
                
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/catalog/category_catalog', array('main_category_list' => $main_category_list, 'select_list' => $select_list, 'form' => $form, 'id' => $id));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
				if (is_array($form) && !empty($form)) {
					$config_update_db = array(
						'name_table' => 'category_catalog',
						'where_field' => 'id'
				 	);
					$status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
					if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/create_category_catalog', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/create_category_catalog', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}

/*
	* Категории галереи 
*/	
	public function gallery () {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
		$list = $this->admin_model->select_gallery();
		if ($list !== NULL) {
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/gallery/gallery', array('list' => $list));
			$this->load->view('admin_panel/footer');
		} else {
			$this->general_functions->alert('error', 'administrator', 'Ошибка при формировании страницы, попробуйте позднее.');
		}
	}

/*
	* Создание галереи
*/	
	public function create_gallery() {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
        $height = 1024;
        $weight = 1280;
       	$thumb_height = 200;
        $thumb_weight = 250;
		$this->form_validation->set_rules($this->rules_model->gallery_rules);
		//$this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[gallery.alias]');
		
		if (empty($_FILES['file']['name'][0])) {
			$this->form_validation->set_rules('file[]', 'Изображение', 'required');
		}
		if ($this->form_validation->run() === FALSE) {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('gallery', $form['title']);
            }
			$this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/gallery/create_gallery', array('form' => $form));
			$this->load->view('admin_panel/footer');
		} else {
			$form = $this->input->post('form');
            if (!empty($form['alias'])) {
                $form['alias'] = str_replace(array('\'','"',' ','.',',','*','/','!','?', '—'), '-', $form['alias']);
            } else {
                $form['alias'] = $this->alias_exist_check_translate_rand('gallery', $form['title']);
            }
            if ($form['date'] != "") {
                $form['date'] = strtotime($form['date']);
            } else {
                $form['date'] = time(); // Текущая дата
            }
			if (is_array($form) && !empty($form)) {
				if ($_FILES) {
	            	$directory = 'gallery';
	            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, $height, $weight, NULL, $thumb_height, $thumb_weight);
	            	if ($file !== FALSE){
	            		$file_id_images = $this->basic_functions_model->upload_files_db($file, 1);
	            	}
	            }
	            $status = FALSE;
	            if (isset($file_id_images) && is_array($file_id_images)) {
	            	unset($height, $weight);
                    
                    $id_gallery = $this->basic_functions_model->insert_db(array('name_table' => 'gallery', 'insert_batch' => FALSE, 'insert_id' => TRUE), $form); 
                    foreach ($file_id_images as $key => $id_image) 
                    {
                        $file_id_images[$key]['main_photo'] = FALSE;
                    }
                    $file_id_images[0]['main_photo'] = TRUE;
                    
                    foreach ($file_id_images as $id_image) {
	            		$data[] = array('id_all_files' => $id_image['id'], 'id_gallery' => $id_gallery, 'main_photo' => $id_image['main_photo']);
	            	}
                    $status = $this->basic_functions_model->insert_db(array('name_table' => 'gallery_images', 'insert_batch' => TRUE, 'insert_id' => FALSE), $data);
	            }
                else {
                    $this->general_functions->alert('error', 'administrator/gallery', 'Произошла ошибка. Не удалось загрузить изображение. Размер файлов не должен превышать 3 МБ.');	
                }
	            
				if ($id_gallery === TRUE || is_numeric($id_gallery)) {
					$this->general_functions->alert('success', 'administrator/gallery', 'Запись успешно сохранена.');
				} else {
					$this->general_functions->alert('error', 'administrator/gallery', 'Произошла ошибка, попробуйте позднее.');	
				}
			}
		}
	}

/*
	* Редактирование 
*/
	public function edit_gallery($id = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
	   	$height = 1024;
        $weight = 1280;
        $thumb_height = 180;
        $thumb_weight = 250;
		if (is_numeric($id) && !isset($_POST['form'])) {
			$form = $this->basic_functions_model->select(array('table' => 'gallery', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            $images = $this->basic_functions_model->select(array('table' => 'gallery_images', 'type' => 'list', 'where_field' => 'id_gallery', 'where' => $id));
			
            foreach ($images as $image) {
                $ids[] = $image['id_all_files'];
            }
            if (!empty($ids)) {
                $img = $this->basic_functions_model->select_in(array('table' => 'all_files', 'type' => 'list', 'where_field' => 'id', 'where' => $ids));
                foreach ($images as $key => $image) {
                    $images[$key]['path'] = $img[$key]['path'];
                }
            }
            
            $this->load->view('admin_panel/header', array('menu' => $this->menu));
			$this->load->view('admin_panel/gallery/create_gallery', array('form' => $form[0], 'id' => $id, 'images' => $images));
			$this->load->view('admin_panel/footer');
		} elseif (is_numeric($id) && isset($_POST['form'])) {
            $images = $this->basic_functions_model->select(array('table' => 'gallery_images', 'type' => 'list', 'where_field' => 'id_gallery', 'where' => $id));
			
            foreach ($images as $key => $image)
            {
                $path = $this->basic_functions_model->select(array('table' => 'all_files', 'type' => 'list', 'where_field' => 'id', 'where' => $image['id_all_files']));
                $images[$key]['path'] = $path[0]['path'];
            }
            
			$this->form_validation->set_rules($this->rules_model->gallery_rules);
			$this->form_validation->set_rules('form[alias]', 'alias', 'trim|required|xss_clean|callback__alias_exist_check[gallery.alias.edit.'.$id.']');
			if ($this->form_validation->run() === FALSE) {
				$form = $this->input->post('form');
				$this->load->view('admin_panel/header', array('menu' => $this->menu));
				$this->load->view('admin_panel/gallery/create_gallery', array('form' => $form, 'id' => $id, 'images' => $images));
				$this->load->view('admin_panel/footer');
			} else {
				$form = $this->input->post('form');
                
                if ($form['date'] != "") {
                    $form['date'] = strtotime($form['date']);
                } else {
                    $form['date'] = time(); // Текущая дата
                }
                
				if (is_array($form) && !empty($form)) {
				 	
                    if ($_FILES) {
    	            	$directory = 'gallery';
    	            	$file = $this->general_functions->upload_files($_FILES['file'], $directory, 1, $height, $weight, NULL, $thumb_height, $thumb_weight);
    	            	if ($file !== FALSE){
    	            		$file_id_images = $this->basic_functions_model->upload_files_db($file, 1);
    	            	}
    	            }
    	            $status = FALSE;
    	            if (isset($file_id_images) && is_array($file_id_images)) {
    	            	unset($height, $weight);
                        
                        foreach ($file_id_images as $id_image) {
    	            		$data[] = array('id_all_files' => $id_image['id'], 'id_gallery' => $id, 'main_photo' => FALSE);
    	            	}
                        $status = $this->basic_functions_model->insert_db(array('name_table' => 'gallery_images', 'insert_batch' => TRUE, 'insert_id' => FALSE), $data);
    	            }

					$status = $this->basic_functions_model->update_db(array('name_table' => 'gallery', 'where_field' => 'id'), $form, $id);
					
                    if ($status === TRUE) {
						$this->general_functions->alert('success', 'administrator/gallery', 'Запись успешно сохранена.');
					} else {
						$this->general_functions->alert('error', 'administrator/gallery', 'Произошла ошибка, попробуйте позднее.');
					}
				}
			}
		} 
	}

/*
	* 
*/
	public function delete_gallery() {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        } 
       
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$list_file = $this->basic_functions_model->select_in(array('table' => 'gallery', 'type' => 'list', 'where_field' => 'id', 'where' => $_POST['id']));
	    	
            $images = $this->basic_functions_model->select_in(array('table' => 'gallery_images', 'type' => 'list', 'where_field' => 'id_gallery', 'where' => $_POST['id']));
    		$delete_all = array();
            foreach ($images as $image) {
                    $delete_all[] = $image['id_all_files'];
            }
	    	$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
	    	$status_images = $this->admin_model->delete_images($_POST['id']);
		}
		if (isset($status_delete) && $status_delete === TRUE && $status_images === TRUE) {
			$this->general_functions->alert('success', $_SERVER['HTTP_REFERER'], 'Запись успешно удалена.');	
		} elseif (isset($status_delete) && $status_delete === TRUE) {
			$this->general_functions->alert('info', $_SERVER['HTTP_REFERER'], 'Произошла ошибка при удалении файлов, сообщите администратору.');
		} else {
			$this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Произошла ошибка, попробуйте позднее.');
		}
	
}

/*
	* Удаление записей из таблицы, связанной с all_files
    * $table - название таблицы
    * $image_field - поле, связанное с all_files
*/
	public function delete_with_image($table = NULL, $image_field = NULL) {
	   if (!$this->ion_auth->is_admin()) {
            redirect('administrator', 'refresh');
        }
       
       if ($image_field === NULL) {
        $image_field = 'id_image';
       }
       
       if ($table !== NULL) {
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$list_file = $this->basic_functions_model->select_in(array('table' => $table, 'type' => 'list', 'where_field' => 'id', 'where' => $_POST['id']));
	    	
	    	foreach ($list_file as $list_value) {
	    		$delete_all[] = $list_value[$image_field];
	    	}
	    	$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
    		$delete = array('table' => $table, 'field' =>'id', 'id' => $_POST['id']);
			$status = $this->basic_functions_model->delete($delete);
		}
		if (isset($status) && $status === TRUE && $status_delete === TRUE) {
			$this->general_functions->alert('success', $_SERVER['HTTP_REFERER'], 'Запись успешно удалена.');	
		} elseif (isset($status) && $status === TRUE) {
			$this->general_functions->alert('info', $_SERVER['HTTP_REFERER'], 'Произошла ошибка при удалении файлов, сообщите администратору.');
		} else {
			$this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Произошла ошибка, попробуйте позднее.');
		}
	   } else {
	       $this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Произошла ошибка, попробуйте позднее.');
	   }
    }


/*
    Контакты
*/
    public function contact_info() {
        $id = 1;	
        if (!isset($_POST['form'])) {
            $form = $this->basic_functions_model->select(array('table' => 'contact_info', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            
            if (!empty($form))
            {
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('admin_panel/contact_info', array('form' => $form[0], 'id' => $id));
    			$this->load->view('admin_panel/footer');
            }
            else
            {
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('admin_panel/contact_info', array('id' => $id));
    			$this->load->view('admin_panel/footer');
            }
        }
        elseif (isset($_POST['form'])) {
            $this->form_validation->set_rules($this->rules_model->contact_rules);
    		if ($this->form_validation->run() === FALSE) {
    			$form = $this->input->post('form');
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
                $this->load->view('admin_panel/contact_info', array('form' => $form, 'id' => $id));
                $this->load->view('admin_panel/footer');
    		} else {
    			$form = $this->input->post('form');
    			if (is_array($form) && !empty($form)) {
                    $config_update_db = array(
    						'name_table' => 'contact_info',
    						'where_field' => 'id'
    				 	);
          
                    $list = $this->basic_functions_model->select(array('table' => 'contact_info', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
                    if (!empty($list))
                    {
                        $status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
                    }
                    else
                    {
                        $status = $this->basic_functions_model->insert_db(array('name_table' => 'contact_info', 'insert_batch' => FALSE, 'insert_id' => TRUE), $form);
                    }
                 
    				if ($status === TRUE || is_numeric($status)) {
    					$this->general_functions->alert('success', 'administrator/contact_info', 'Запись успешно сохранена.');
    				} else {
    					$this->general_functions->alert('error', 'administrator/contact_info', 'Произошла ошибка, попробуйте позднее.');	
    				}
    			}
    		}
        }
        
        /*$this->load->view('admin_panel/header', array('menu' => $this->menu));
		$this->load->view('admin_panel/contact_info');
		$this->load->view('admin_panel/footer');*/
	}


/*
    О компании
*/
    public function about() {
        $id = 1;	
        if (!isset($_POST['form'])) {
            $form = $this->basic_functions_model->select(array('table' => 'about', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            
            $list = $this->basic_functions_model->select(array('table' => 'regions', 'type' => 'list'));
            
            if (!empty($form))
            {
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('admin_panel/about', array('form' => $form[0], 'id' => $id, 'list' => $list));
    			$this->load->view('admin_panel/footer');
            }
            else
            {
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('admin_panel/about', array('id' => $id, 'list' => $list));
    			$this->load->view('admin_panel/footer');
            }
        }
        elseif (isset($_POST['form'])) {
            $this->form_validation->set_rules($this->rules_model->about_rules);
    		if ($this->form_validation->run() === FALSE) {
    			$form = $this->input->post('form');
                
                $list = $this->basic_functions_model->select(array('table' => 'regions', 'type' => 'list'));
                
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
                $this->load->view('admin_panel/about', array('form' => $form, 'id' => $id, 'list' => $list));
                $this->load->view('admin_panel/footer');
    		} else {
    			$form = $this->input->post('form');
    			if (is_array($form) && !empty($form)) {
                    $config_update_db = array(
    						'name_table' => 'about',
    						'where_field' => 'id'
    				 	);
          
                    $list = $this->basic_functions_model->select(array('table' => 'about', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
                    if (!empty($list))
                    {
                        $status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
                    }
                    else
                    {
                        $status = $this->basic_functions_model->insert_db(array('name_table' => 'about', 'insert_batch' => FALSE, 'insert_id' => TRUE), $form);
                    }
                 
    				if ($status === TRUE || is_numeric($status)) {
    					$this->general_functions->alert('success', 'administrator/about', 'Запись успешно сохранена.');
    				} else {
    					$this->general_functions->alert('error', 'administrator/about', 'Произошла ошибка, попробуйте позднее.');	
    				}
    			}
    		}
        }
	}

/*
    * Анкета
*/
    public function anketa() {
        $id = 1;
        if (!isset($_POST['form'])) {
            $form = $this->basic_functions_model->select(array('table' => 'anketa', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            
            
            if (!empty($form))
            {
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('admin_panel/anketa', array('form' => $form[0], 'id' => $id));
    			$this->load->view('admin_panel/footer');
            }
            else
            {
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
    			$this->load->view('admin_panel/anketa', array('id' => $id));
    			$this->load->view('admin_panel/footer');
            }
        }
        elseif (isset($_POST['form'])) {
            $this->form_validation->set_rules($this->rules_model->anketa_rules);
    		if ($this->form_validation->run() === FALSE) {
    			$form = $this->input->post('form');
                
                
                $this->load->view('admin_panel/header', array('menu' => $this->menu));
                $this->load->view('admin_panel/anketa', array('form' => $form, 'id' => $id));
                $this->load->view('admin_panel/footer');
    		} else {
    			$form = $this->input->post('form');
    			if (is_array($form) && !empty($form)) {
                    $config_update_db = array(
    						'name_table' => 'anketa',
    						'where_field' => 'id'
    				 	);
          
                    $list = $this->basic_functions_model->select(array('table' => 'anketa', 'type' => 'list', 'where_field' => 'id', 'where' => $id));
                    if (!empty($list))
                    {
                        $status = $this->basic_functions_model->update_db($config_update_db, $form, $id);
                    }
                    else
                    {
                        $status = $this->basic_functions_model->insert_db(array('name_table' => 'anketa', 'insert_batch' => FALSE, 'insert_id' => TRUE), $form);
                    }
                 
    				if ($status === TRUE || is_numeric($status)) {
    					$this->general_functions->alert('success', 'administrator/anketa', 'Запись успешно сохранена.');
    				} else {
    					$this->general_functions->alert('error', 'administrator/anketa', 'Произошла ошибка, попробуйте позднее.');	
    				}
    			}
    		}
        }
	}
/*
	* Записи
*/	
	public function form_questions () {
		$list = $this->admin_model->select_questions();
        
        $this->load->view('admin_panel/header', array('menu' => $this->menu));
        $this->load->view('admin_panel/forms/questions', array('lists' => $list));
        $this->load->view('admin_panel/footer');
	}
    
/*
	* Записи
*/	
	public function form_sub () {
		$list = $this->admin_model->select_sub();
        
        $this->load->view('admin_panel/header', array('menu' => $this->menu));
        $this->load->view('admin_panel/forms/sub', array('lists' => $list));
        $this->load->view('admin_panel/footer');
	}

/*
	* $id - удаляемой записи целочисленное значение. 
	* $field - название таблицы из которой удаляется. 
*/
	public function delete($table = NULL, $field = NULL, $id = NULL) {
		$status = FALSE;
		if ($field != NULL && $table != NULL) {
			if(isset($_POST['id'])){
				$id = $_POST['id'];
			}
			if ($id != NULL) {
				$delete = array('table' =>$table, 'field' =>$field, 'id' => $id);
				$status = $this->basic_functions_model->delete($delete);
			}
		}

		if (isset($status) && $status === TRUE) {
			$this->general_functions->alert('success', $_SERVER['HTTP_REFERER'], 'Запись успешно удалена.');	
		} else {
			$this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Произошла ошибка, попробуйте позднее.');
		}
	}
    
    public function delete_image($table = NULL, $id = NULL) {
        $status = FALSE;
        if ($id !== NULL && $table !== NULL)
        {
       	    $list = $this->basic_functions_model->select(array('table' => $table, 'type' => 'list', 'where_field' => 'id', 'where' => $id));
            if ($list[0]['main_photo'] == 1) {
                $this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Нельзя удалить главное фото.');
            }
            foreach ($list as $list_value) {
                    $delete_all[] = $list_value['id_all_files'];
            }
	    	$status_delete = $this->basic_functions_model->upload_files_db($delete_all, 2);
            //$status = $this->basic_functions_model->delete(array('id' => $list[0]['id_all_files'], 'table' => 'all_files', 'field' => 'id'));
            $status = $this->basic_functions_model->delete(array('id' => $id, 'table' => $table, 'field' => 'id'));
        }
        if (isset($status) && $status === TRUE) {
			$this->general_functions->alert('success', $_SERVER['HTTP_REFERER'], 'Запись успешно удалена.');	
		} else {
			$this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Произошла ошибка.');
		}
    }
    
    public function main_photo($table = NULL, $where_field = NULL, $id_gallery = NULL, $id = NULL) {
        $images = $this->basic_functions_model->select(array('table' => $table, 'type' => 'list', 'where_field' => $where_field, 'where' => $id_gallery));
        if (!empty($images) && $images !== NULL && $id !== NULL)
        {
            foreach ($images as $key => $image)
            {
                if ($images[$key]['id'] == $id) {
                    $images[$key]['main_photo'] = TRUE;
                }
                else {
                    $images[$key]['main_photo'] = FALSE;
                }
                $config_db = array('name_table' => $table, 'where_field' => 'id');
                $this->basic_functions_model->update_db($config_db, $images[$key], $images[$key]['id']);	
            }
            $this->general_functions->alert('success', $_SERVER['HTTP_REFERER'], 'Запись успешно изменена.');
            
        }
        else
        {
            $this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Произошла ошибка.');
        }
    }
    
/*
	* Публикация материалов
*/
	public function publish($table = NULL, $status = NULL, $id = NULL) {
	   
		if ($table != NULL && $status != NULL && $id != NULL) {
			$form['access'] = $status;
			$status = $this->basic_functions_model->update_db(array('name_table' => $table, 'where_field' => 'id'), $form, $id);
		}	

		if (isset($status) && $status === TRUE) {
			$this->general_functions->alert('success', $_SERVER['HTTP_REFERER'], 'Запись успешно изменена.');	
		} else {
			$this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Произошла ошибка, попробуйте позднее.');
		}
	}
    
	public function description_image($form = NULL, $table = NULL, $field = NULL, $id = NULL) {
		$status = FALSE;
        
		if ($field != NULL && $table != NULL) {
			if(isset($_POST['id'])){
				$id = $_POST['id'];
                
			}
			if ($id != NULL) {
                $forma = $this->input->post($form);
				$status = $this->basic_functions_model->update_db(array('name_table' => $table, 'where_field' => 'id'), $forma, $id);
			}
		}
        
		if (isset($status) && $status === TRUE) {
			$this->general_functions->alert('success', $_SERVER['HTTP_REFERER'], 'Запись успешно сохранена.');	
		} else {
			$this->general_functions->alert('error', $_SERVER['HTTP_REFERER'], 'Произошла ошибка, попробуйте позднее.');
		}
	}
	
/*
	* Проверка на уникальность alias

	$field_one = значение из формы, 
	$table_column = таблица и поле которые проверяются тип (string)table.field, 
	$type = создание или редактирование. 
*/
	function _alias_exist_check ($field_one = NULL, $db_config = NULL) {
		$status = '';
		if ($field_one != NULL && $db_config != NULL) {
			$status = $this->basic_functions_model->alias_exist($db_config, $field_one);
		}
	    if ($status === FALSE) {
			$this->form_validation->set_message('_alias_exist_check', 'Alias должен быть уникальным');
		 	return FALSE;
		} elseif ($status === TRUE) {
			return TRUE;
		}
		
		$this->form_validation->set_message('_alias_exist_check', 'Ошибка запроса');
	    return FALSE;
    } 
    
/*
    * Проверка и создание уникального alias
*/
    public function alias_exist_check_translate_rand($type= NULL, $title = NULL) {
        if ($type != NULL && $title != NULL && $title != 'all') {
            $alias = $this->general_functions->transliterate($title);
            $duplicate = $this->basic_functions_model->select(array('table' => $type, 'type' => 'list', 'where_field' => 'alias', 'where' => $alias));
            if ($duplicate == FALSE) {
                return str_replace(array("'", "\"", " ", ".", ",", "*", "/", "!", "?", "—", "»", "«"), "-", $alias);
            } else {
                $alias_rand = rand(1, 9999);
                $alias = $alias.$alias_rand;
            }
            return str_replace(array("'", "\"", " ", ".", ",", "*", "/", "!", "?", "—", "»", "«"), "-", $alias);
        }
        return FALSE;
    }      

    /* модерация конкурса */
    function moderation($sort = 0) {      
        $this->load->model('konkurs_model');
        //--- настройка пагинации
        $config = array(
					'full_tag_open'		=> '<div class="pagination">',
					'full_tag_close'		=> '</div>',
					'next_link'		=> '&raquo;',
					'prev_link' 	=> '&laquo;',
					'first_link' 	=> '&laquo; Первая',
					'last_link' 	=> 'Последняя &raquo;',
					'base_url' 		=> base_url('administrator/moderation/'.$sort.'/'),
					'uri_segment' 	=> 4,
					'total_rows'	=> $this->general_functions->count_all('reg_konkurs_users'),
					'per_page'		=> 5,
				);
        
        $this->pagination->initialize($config); 
        
        if ($sort == 0) {
            $this->data['kusers'] = $this->konkurs_model->count_votes(0,array('offset'   => intval($this->uri->segment(4)),
                                                                       'rows'     => $config['per_page'])
                                                                       );
        } elseif ($sort == 1) {
            $this->data['kusers'] = $this->konkurs_model->count_votes(1,array('offset'   => intval($this->uri->segment(4)),
                                                                       'rows'     => $config['per_page'])
                                                                       );
        } else {
            $this->data['kusers'] = $this->konkurs_model->count_votes(0,array('offset'   => intval($this->uri->segment(4)),
                                                                       'rows'     => $config['per_page'])
                                                                       );
        }
        
        //var_dump($this->data['kusers']);
        /*
        //--- выборка всех участников конкурса для модерации
        $this->data['kusers'] = $this->konkurs_model->select_all_konkursant(false,array(
                                                                                    'offset'   => intval($this->uri->segment(3)),
                                                                                    'rows'     => $config['per_page'])
                                                                                    );
        
        //--- если есть участиники, то посчитать количество голосов
        if ($this->data['kusers'] != FALSE) {
        foreach ($this->data['kusers'] as $key => $value) {
                $id[] = $this->data['kusers'][$key]['id'];           
            }     
            $votes = $this->konkurs_model->count_all_votes($id);     
            if (isset($votes) && !empty($votes)) { 
                foreach ($votes as $key => $value) {
                    if ($value['votes'] > 0 || !empty($value['votes'])) {
                        $this->data['kusers'][$key]['counts'] = $value['votes'];
                    } else {
                        $this->data['kusers'][$key]['counts'] = "Нет";
                    }
                }
            }
        }*/
        
        //asort($this->data['kusers']);
        
        if ($this->data['kusers'] != FALSE) {
            $this->load->view('admin_panel/header', array('menu' => $this->menu));
            $this->load->view('konkurs/moderation_view',$this->data);   
            $this->load->view('admin_panel/footer');         
        } else {
            $this->load->view('admin_panel/header', array('menu' => $this->menu));
            $this->load->view('konkurs/moderation_view',array('error' => 'Не удалось загрузить страницу.'));
            $this->load->view('admin_panel/footer');
        }
    }
    
    function sort_option() {
        if (isset($_POST['sort_option'])) {
            if ($_POST['sort_option'] == 0) {
                redirect('administrator/moderation/0','refresh');
            } elseif ($_POST['sort_option'] == 1) {
                redirect('administrator/moderation/1','refresh');
            } else {
                redirect('administrator/moderation/0','refresh');
            }
        }
    }
    
}