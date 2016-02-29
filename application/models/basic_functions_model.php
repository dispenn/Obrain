<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Basic_functions_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}

	/*
		*  Подсчёт строк в таблице
	*/
	function count_all($table_count = NULL) {
		if($table_count != NULL){
			return $this->db->count_all($table_count);
		} else {
			return FALSE;
		}
	}

    /*
        *  Проверка alias на уникальность
            $type = $db_config[2];
            $id = $db_config[3];
            $id_field = $db_config[4];
    */
    function alias_exist($db_config = NULL, $field_one = NULL) {
        if ($db_config != NULL) {
            $db_config = explode('.', $db_config);
        } else {
           return FALSE; 
        }
        if($field_one != NULL && !isset($db_config[2])){
            $query = $this->db->where($db_config[1], $field_one)->limit(1)->get($db_config[0]);
            if($query->num_rows() > 0){
                return FALSE;
            } else {
                return TRUE;
            }
        } elseif ($field_one != NULL && $db_config[2] == 'edit' && $db_config[3] != NULL){
            if (!isset($db_config[4])) {
                $db_config[4] = 'id';
            }
            $query = $this->db->query("SELECT * FROM `".$db_config[0]."` WHERE `".$db_config[0]."`.`".$db_config[1]."` = '".$field_one."' AND `".$db_config[0]."`.`".$db_config[4]."` != '".$db_config[3]."'");
            //$query = $this->db->where($db_config[1], $field_one)->get();
            if($query->num_rows() > 0){
                return FALSE;
            } else {
                return TRUE;
            }
        }
        
    } 

    /*
        *  Настройки для системы
    */
    public function setting() {
        $query = $this->db->query("SELECT * FROM `setting`");
            
        if ($query->num_rows() == 1) {
            return $query->row_array();
        }
        return FALSE;
    }

    /*
        *   Запрос на выборку
        *
        *   page_limit, page_offset  =   для пагинации. Количество записей на страницу, сдвиг на выборку записей.
    */
    public function select($config = NULL, $page_limit = NULL, $page_offset = NULL) {
        if (!isset($config['compare']) || $config['compare'] === NULL) {
            $config['compare'] = '=';
        }
        if (!isset($config['and_compare']) || $config['and_compare'] === NULL) {
            $config['and_compare'] = '=';
        }
        
        if ($config['table'] != NULL && $config['type'] == 'list') {
            $condition = '';
            $where = '';
            $sort = '';
            if (isset($page_limit) && isset($page_offset)) {
                $condition = 'LIMIT '.$page_offset.', '.$page_limit;
            }
            if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `".$config['where_field']."` ".$config['compare']." '".$config['where']."'";
            }
            if (isset($config['and_where_field']) && isset($config['and_where'])) {
                $where .= " AND `".$config['and_where_field']."` ".$config['and_compare']." '".$config['and_where']."'";
            }
            if (isset($config['sort'])) {
                if (isset($config['sort_with_null']) && $config['sort_with_null'] == TRUE) {
                    $sort = "ORDER BY CASE WHEN ".$config['sort']." = '0' THEN '65535' END, ".$config['sort']." ".$config['type_sort'];
                } else {
                    $sort = "ORDER BY ".$config['sort']." ".$config['type_sort'];
                }
            }
            $query = $this->db->query("SELECT * FROM `".$config['table']."` ".$condition." ".$where." ".$sort." ");
            if ($query->row_array() > 0) {
                return $query->result_array();
            }
        }
        return FALSE;
    }

/*
    *   Запрос на выборку строк, связанных с таблицей all_files
    *   $config['table'] - название таблицы
    *   $config['image_field'] - поле, связывающее таблицу с таблицей all_files
    *   $type - FALSE для изображения, TRUE для превью
*/
    public function select_with_images($config = NULL, $page_limit) {
        /*if ($type === TRUE) {
            $type = 'thumb';
        }
        else {
            $type = 'path';
        }*/
        if (!isset($config['compare']) || $config['compare'] === NULL) {
            $config['compare'] = '=';
        }
        if (!isset($config['and_compare']) || $config['and_compare'] === NULL) {
            $config['and_compare'] = '=';
        }
        if (!isset($config['type_sort']) || $config['type_sort'] === NULL) {
            $config['type_sort'] = 'asc';
        }
        
        if ($config['table'] != NULL && $config['image_field'] != NULL) {
            $where = '';
            $condition = '';
            $sort = '';
            if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `".$config['table']."`.`".$config['where_field']."` ".$config['compare']." '".$config['where']."'";
            }
            if (isset($config['and_where_field']) && isset($config['and_where'])) {
                $where .= " AND `".$config['and_where_field']."` ".$config['and_compare']." '".$config['and_where']."'";
            }
            if (isset($config['sort'])) {
                if (isset($config['sort_with_null']) && $config['sort_with_null'] == TRUE) {
                    $sort = "ORDER BY CASE WHEN ".$config['sort']." = '0' THEN '65535' END, ".$config['sort']." ".$config['type_sort'];
                } else {
                    $sort = "ORDER BY ".$config['sort']." ".$config['type_sort'];
                }
            }
            if (isset($page_limit) && isset($page_offset)) {
                if ($page_offset == NULL) {
                    $page_offset = 0;
                }
                $condition = 'LIMIT '.$page_offset.', '.$page_limit;
            }
            // SUBSTRING обрезает точку в начале пути
            $query = $this->db->query("SELECT `".$config['table']."`.*, SUBSTRING(`all_files`.`path`,2) AS `path`,  SUBSTRING(`all_files`.`thumb`,2) AS `thumb` FROM `".$config['table']."` LEFT JOIN `all_files` ON `all_files`.`id`=`".$config['table']."`.`".$config['image_field']."` ".$where." ".$sort." ".$condition);
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
        }
        return FALSE;
    }

    /*
        *   Запрос на выборку одного поля таблицы
        *
        *   $field - название поля, которое нужно получить
    */    
    public function select_id($config = NULL, $field = NULL) {
        if (!isset($config['compare']) || $config['compare'] === NULL) {
            $config['compare'] = '=';
        }
        if (!isset($config['and_compare']) || $config['and_compare'] === NULL) {
            $config['and_compare'] = '=';
        }
        
        if ($config['table'] != NULL && $config['type'] == 'list') {
            $condition = '';
            $where = '';
            $sort = '';
            if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `".$config['where_field']."` ".$config['compare']." '".$config['where']."'";
            }
            if (isset($config['and_where_field']) && isset($config['and_where'])) {
                $where .= " AND `".$config['and_where_field']."` ".$config['and_compare']." '".$config['and_where']."'";
            }
            if (isset($config['sort'])) {
                $sort = "ORDER BY ".$config['sort']." ".$config['type_sort'];
            }
            $query = $this->db->query("SELECT `".$config['table']."`.`".$field."` FROM `".$config['table']."` ".$condition." ".$where." ".$sort." ");
            if ($query->row_array() > 0) {
                return $query->result_array();
            }
        }
        return FALSE;
    }

    /*
        *   Запрос на выборку массива id
        *
        *   page_limit, page_offset  =   для пагинации. Количество записей на страницу, сдвиг на выборку записей.
    */
    public function select_in($config = NULL) {
        if ($config['table'] != NULL && $config['type'] == 'list') {
            $where = '';
            if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `".$config['where_field']."` IN (".implode(",",$config['where']).")";
            }
            $query = $this->db->query("SELECT * FROM `".$config['table']."` ".$where." ");
            if ($query->row_array() > 0) {
                return $query->result_array();
            }
        }
        return FALSE;
    }
    
	public function insert_db ($config_db = NULL, $data = NULL) {
		if ( ! empty ($config_db['name_table']) && $config_db['insert_batch'] === FALSE && $config_db['insert_id'] === FALSE) {

			$insert = $this->db->insert($config_db['name_table'], $data);
		
		} elseif ( ! empty ($config_db['name_table']) && $config_db['insert_batch'] === TRUE && $config_db['insert_id'] === FALSE) {
		
			$insert = $this->db->insert_batch($config_db['name_table'], $data);
		
		} elseif ( ! empty ($config_db['name_table']) && $config_db['insert_batch'] === FALSE && $config_db['insert_id'] === TRUE) {
		
			$this->db->trans_start();
				$this->db->insert($config_db['name_table'], $data);
				$id = $this->db->insert_id($config_db['name_table']);
			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				return $id;
			}
		}
		if($insert === TRUE) {
			return TRUE;
		} 
		return FALSE;
	}

/*  Редактирование информации
    *       $config_db['name_table'] = название таблицы.
    *       $config_db['where_field'] = название поля WHERE.
    *       $data = данные для uodate.
    *       $id = редактируемая запись.
*/
    public function update_db ($config_db = NULL, $data = NULL, $id = NULL) {
        if ($config_db != NULL && $data != NULL && $id != NULL) {
            $this->db->where($config_db['where_field'], $id);
            $result = $this->db->update($config_db['name_table'], $data);
            if ($result === TRUE) {
                return TRUE;
            }
        }
        return FALSE;
    }

/*  Удаление из базы любой информации.
    *       $delete['id'] = id записей которые удаляются.
    *       $delete['table'] = название таблицы.
    *       $delete['field'] = поле в таблице.
*/
    public function delete ($delete = NULL) {
        if($delete != NULL){
            if(is_array($delete['id'])) {
                $result_delete = $this->db->query("DELETE FROM `".$delete['table']."` WHERE `".$delete['table']."`.`".$delete['field']."` IN (".implode(",",$delete['id']).")");
            } else {
                $result_delete = $this->db->query("DELETE FROM `".$delete['table']."` WHERE `".$delete['table']."`.`".$delete['field']."` = '".$delete['id']."'");
            }
            if($result_delete === TRUE) {
                return TRUE;
            }
        }
        return FALSE;
    }

/*
    *
    *   Загрузка фотографии в БД
    *
    *   $action[0] = просмотр списка файлов привязанных к объекту
    *          [1] = запись в БД all_files
    *          [3] = удаление записи
    *   $files[path] = путь к файлу
*/
    public function upload_files_db($files = NULL, $action = 0) {
        if ($action === 0) {

        } elseif ($action === 1 && $files != NULL){
            /* Оптимизировать позже
            if ($this->db->insert_batch('all_files', $masseurs_photos)) {
                return TRUE;
            }*/
            foreach ($files as $files_value) {
                unset($files_value['compression']);
                $this->db->insert('all_files', $files_value);
                $all_files_id[] = array(
                    'id' => $this->db->insert_id()
                );
            }
            return $all_files_id;

        } elseif ($action === 2 && $files != NULL) {
            $unlink_files = $this->db->query("SELECT `all_files`.`path`, `all_files`.`thumb` FROM `all_files` WHERE `all_files`.`id` IN (".implode(",",$files).")");
            $unlink_files = $unlink_files->result_array();
            $result = $this->db->query("DELETE FROM `all_files` WHERE `all_files`.`id` IN (".implode(",",$files).")");
            foreach ($unlink_files as $unlink_files_key => $unlink_files_value) {
                $document_path = substr($unlink_files_value['path'], 1);
                $document_path_delete = $_SERVER['DOCUMENT_ROOT'] . $document_path; 
                if (!empty($unlink_files_value['thumb'])) {
                    $document_path_thumb = substr($unlink_files_value['thumb'], 1);
                    $document_path_delete_thumb = $_SERVER['DOCUMENT_ROOT'] . $document_path_thumb; 
                    if (file_exists($document_path_delete_thumb)){
                        unlink($document_path_delete_thumb);
                    }
                }
                if (file_exists($document_path_delete)){
                    unlink($document_path_delete);
                } else {
                    return FALSE; 
                }
            }
            return TRUE; 
        }
        return FALSE;
    }
    /* $query = $this->db->query("SELECT `masseurs_photos`.`all_files_id` FROM `masseurs_photos` LEFT JOIN `all_files` ON `all_files`.`id`=`masseurs_photos`.`all_files_id` WHERE `masseurs_photos`.`masseurs_id` = '".$id_obj."' AND `masseurs_photos`.`main` = '".$main."'");
    $delete = $query->result_array();
    foreach ($delete as $delete_key => $delete_value) {
        $delete_all[] = $delete_value['all_files_id'];
    }*/
}
