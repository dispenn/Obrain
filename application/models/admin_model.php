<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}


    public function select_main_images() {
        $query = $this->db->query("
            SELECT 
                `main_images`.*, 
                `all_files1`.`path` AS `logo_topm`, 
                `all_files2`.`path` AS `top`, 
                `all_files3`.`path` AS `top_bg`,
                `all_files4`.`path` AS `bg`,
                `all_files5`.`path` AS `logo_top` 
            FROM `main_images` 
            LEFT JOIN `all_files` AS `all_files1` ON `all_files1`.`id`=`main_images`.`id_logo_topm` 
            LEFT JOIN `all_files` AS `all_files2` ON `all_files2`.`id`=`main_images`.`id_top` 
            LEFT JOIN `all_files` AS `all_files3` ON `all_files3`.`id`=`main_images`.`id_top_bg` 
            LEFT JOIN `all_files` AS `all_files4` ON `all_files4`.`id`=`main_images`.`id_bg` 
            LEFT JOIN `all_files` AS `all_files5` ON `all_files5`.`id`=`main_images`.`id_logo_top` 
            WHERE `main_images`.`id` = 1
        ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    public function select_questions() {
        $query = $this->db->query("
            SELECT 
                `forms`.*
            FROM `forms` 
            WHERE 
                `forms`.`category` = 2 
            ORDER BY `forms`.`id` DESC
        ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    public function select_sub() {
        $query = $this->db->query("
            SELECT 
                `forms`.*
            FROM `forms` 
            WHERE 
                `forms`.`category` = 1 
            ORDER BY `forms`.`id` DESC
        ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
    public function select_employees() {
        $query = $this->db->query("
            SELECT 
                `employees`.*, 
                `departments`.`title` AS `department`, 
                `all_files`.`path`, 
                `all_files`.`thumb` 
            FROM `employees` 
            LEFT JOIN `departments` ON `departments`.`id`=`employees`.`id_department` 
            LEFT JOIN `all_files` ON `all_files`.`id`=`employees`.`id_image` 
        ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    public function select_category_catalog($main = FALSE) {
        $where = "";
        if ($main !== FALSE) {
            $where .= " WHERE `category_catalog`.`id_main_category` = 0 ";
        }
        
        $query = $this->db->query("
            SELECT 
                `category_catalog`.*, 
                `main_category_catalog`.`title` AS `main_category_title`
            FROM `category_catalog` 
            LEFT JOIN `category_catalog` AS `main_category_catalog` ON `main_category_catalog`.`id`=`category_catalog`.`id_main_category`  
            ".$where." 
        ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    public function select_catalog() {
        $query = $this->db->query("
            SELECT 
                `catalog`.*, 
                `category_catalog`.`title` AS `category`, 
                `all_files`.`path`, 
                `all_files`.`thumb` 
            FROM `catalog` 
            LEFT JOIN `category_catalog` ON `category_catalog`.`id`=`catalog`.`id_category` 
            LEFT JOIN `all_files` ON `all_files`.`id`=`catalog`.`id_image` 
        ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

	/*
		*   
	*/
	function count_all($table_count = NULL) {
		if($table_count != NULL){
			return $this->db->count_all($table_count);
		} else {
			return FALSE;
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
        *  Настройки для системы
    */
    public function email_update($email = NULL) {
        if ($email != NULL) {
            $result = $this->db->query("UPDATE `setting` SET `setting`.`email`  = '".$email['email']."' WHERE `setting`.`id` = '1'");
            if ($result === TRUE) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /*
        *   Список категорий галереи
    */
    public function select_gallery() {
        $query = $this->db->query("
            SELECT 
                `gallery`.`id`, 
                `gallery`.`alias`, 
                `gallery`.`title`, 
                `gallery`.`date`, 
                `gallery`.`fulltext`
            FROM `gallery` 
        ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
    /*
        *   Список расцветок
    */
    public function select_colors() {
        $query = $this->db->query("SELECT `colors`.`id`, `colors`.`id_category`, `colors`.`id_images`, `colors`.`title`, `colors`.`description`, `category_colors`.`title` AS `category_title`, `all_files`.`path` FROM `colors` LEFT JOIN `category_colors` ON `category_colors`.`id`=`colors`.`id_category` LEFT JOIN `all_files` ON `all_files`.`id`=`colors`.`id_images`");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
    public function select_brigadies_manufacture_members($config = NULL) {
            $where = '';
            if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `".$config['where_field']."` IN (".implode(",",$config['where']).")";
            }
            $query = $this->db->query("SELECT `brigadies_manufacture_members`.*, `users`.`username` FROM `brigadies_manufacture_members` LEFT JOIN `users` ON `users`.`id`=`brigadies_manufacture_members`.`id_user` ".$where." ");
            if ($query->row_array() > 0) {
                return $query->result_array();
            }
    }
    
    public function select_brigadies_head($config = NULL) {
            $where = '';
            if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `".$config['where_field']."` = '".$config['where']."'";
            }
            $query = $this->db->query("SELECT `brigadies_manufacture_members`.*, `brigadies_manufacture`.`id_head` FROM `brigadies_manufacture_members` LEFT JOIN `brigadies_manufacture` ON `brigadies_manufacture`.`id`=`brigadies_manufacture_members`.`id_brigade` ".$where." ");
            if ($query->row_array() > 0) {
                return $query->result_array();
            }
    }
    
    public function select_brigadies_group_members($config = NULL) {
            $where = '';
            if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `".$config['where_field']."` = '".$config['where']."'";
            }
            $query = $this->db->query("SELECT `brigadies_manufacture_members`.*, `users_groups`.`group_id` FROM `brigadies_manufacture_members` LEFT JOIN `users_groups` ON `users_groups`.`user_id`=`brigadies_manufacture_members`.`id_user` ".$where." ");
            if ($query->row_array() > 0) {
                return $query->result_array();
            }
    }
    
    public function select_works_manufacture($config = NULL) {
        if (!isset($config['compare']) || $config['compare'] === NULL) {
            $config['compare'] = '=';
        }
        if (!isset($config['and_compare']) || $config['and_compare'] === NULL) {
            $config['and_compare'] = '=';
        }
        
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
        $query = $this->db->query("SELECT `work`.*, `users`.`username` AS `manufacture` FROM `work` LEFT JOIN `users` ON `users`.`id`=`work`.`id_manufacture` ".$where." ".$sort." ");
        if ($query->row_array() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
    /*
        *   Список заказов
    */
    public function select_orders($config = NULL) {
        if (!isset($config['compare']) || $config['compare'] === NULL) {
            $config['compare'] = '=';
        }
        $where = '';
        if (isset($config['where_field']) && isset($config['where'])) {
            $where = "WHERE `".$config['where_field']."` ".$config['compare']." '".$config['where']."'";
        }
        $query = $this->db->query("SELECT `orders`.*, `users`.`username`, `users_manager`.`username` AS `manager`, `users_manufacture`.`username` AS `manufacture`, `profiles`.`name`, `profiles`.`discount` AS `discount_profiles` FROM `orders` LEFT JOIN `users` ON `users`.`id`=`orders`.`id_user` LEFT JOIN `users` AS `users_manager` ON `users_manager`.`id`=`orders`.`id_manager` LEFT JOIN `users` AS `users_manufacture` ON `users_manufacture`.`id`=`orders`.`id_manufacture` LEFT JOIN `profiles` ON `profiles`.`id`=`orders`.`id_profile` ".$where." ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
    /*
        *   Список товаров в заказе
    */
    public function select_orders_catalog($id_order = NULL) {
        $where = '';
        if ($id_order !== NULL) {
            $where .= "WHERE `ordered_goods`.`id_order` = '".$id_order."'";
        }
        $query = $this->db->query("SELECT `ordered_goods`.*, `catalog`.`title`, `catalog`.`alias`, `catalog`.`price`, `catalog`.`new_price`, `all_files`.`path`, `category_catalog`.`alias` AS `category_alias` FROM `ordered_goods` LEFT JOIN `catalog` ON `catalog`.`id`=`ordered_goods`.`id_catalog` LEFT JOIN `all_files` ON `all_files`.`id`=`catalog`.`id_main_photo` LEFT JOIN `category_catalog` ON `category_catalog`.`id`=`catalog`.`id_category` ".$where." ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
     /*
        *   Список категорий галереи
    */
    public function select_brigade_name($id_user, $id_work) {
        $where = '';
        if ($id_user !== NULL) {
            $where .= "WHERE `id_user` = '".$id_user."' AND `id_work` = '".$id_work."'";
        }
        $query = $this->db->query("SELECT `brigadies_work`.`id_user`, `brigadies_work`.`id_brigade`, `brigadies_work`.`id_work`, `brigadies`.`title` FROM `brigadies_work` LEFT JOIN `brigadies` ON `brigadies`.`id`=`brigadies_work`.`id_brigade` ".$where." ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }   
    
/*
    *   Список выданных расходников
*/
    public function select_consumables($id_user) {
        $where = '';
        if ($id_user !== NULL) {
            $where .= "WHERE `id_user` = '".$id_user."'";
        }
        $query = $this->db->query("SELECT `consumables`.`id`, `consumables`.`id_user`, `consumables`.`id_catalog`, `consumables`.`count`, `consumables`.`date`, `catalog`.`title`, `catalog`.`consumption_rate` FROM `consumables` LEFT JOIN `catalog` ON `catalog`.`id`=`consumables`.`id_catalog` ".$where." ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    } 
 
/*
    *   Общая площадь за месяц
*/
    public function select_work_area($config = NULL) {
        $where = '';
        if (isset($config['id_work'])) {
            $where .= "WHERE `room`.`id_work` = '".$config['id_work']."'";
        }
        
        // `room`.`id`, `room`.`id_work`, 
        // SUM(`room_plafond`.`area`) AS `area`
        // GROUP BY `room`.`id`  
        $query = $this->db->query("SELECT SUM(`room_plafond`.`area`) AS `area` FROM `room` LEFT JOIN `room_plafond` ON `room_plafond`.`id_room`=`room`.`id` ".$where."");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }    
 
    
/*
    *   Список поездок по поручениям
*/
    public function select_trip_errands($config = NULL) {
        $where = '';
        if (isset($config['id_user'])) {
            $where .= "WHERE `id_user` = '".$config['id_user']."'";
        }
        
        $query = $this->db->query("SELECT `trip_errands`.`id`, `trip_errands`.`date`, `trip_errands`.`status`, `trip_errands`.`id_user`, `trip_errands`.`address`, `trip_errands`.`km`, `trip_errands`.`goal`, `trip_errands`.`time`, `trip_errands`.`comment`, `users`.`username` FROM `trip_errands` LEFT JOIN `users` ON `users`.`id`=`trip_errands`.`id_user` ".$where." ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
/*
    *   Список корректировок к оплате
*/
    public function select_adjustment_payment($config = NULL) {
        $where = '';
        if (isset($config['id_user'])) {
            $where .= "WHERE `adjustment_payment`.`id_user` = '".$config['id_user']."'";
        }
        if (isset($config['type'])) {
            $where .= " AND `adjustment_payment`.`type` = '".$config['type']."'";
        }
        
        $query = $this->db->query("SELECT `adjustment_payment`.`id`, `adjustment_payment`.`date` AS `date_adjustment`, `adjustment_payment`.`type`, `adjustment_payment`.`comment`, `adjustment_payment`.`id_work`, `adjustment_payment`.`id_user`, `adjustment_payment`.`summa`, `work`.`date_zamer` AS `date_zamer`, `work`.`date` AS `date`, `work`.`address_installation` FROM `adjustment_payment` LEFT JOIN `work` ON `work`.`id`=`adjustment_payment`.`id_work` ".$where." ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }    
 
/*
    *   Список инструментов
*/
    public function select_tools($config = NULL) {
        $where = '';
        if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `tools`.`".$config['where_field']."` = '".$config['where']."'";
        }
        
        if (isset($config['category'])) {
            $where = "WHERE `tools`.`id_category` = '".$config['category']."'";
        }
        
        $query = $this->db->query("SELECT `tools`.*, SUBSTRING(`all_files`.`path`,2) AS `path`, `category_tools`.`title` AS `category`, `users`.`username`, `groups`.`name` AS `groupname` FROM `tools` LEFT JOIN `all_files` ON `all_files`.`id`=`tools`.`id_main_photo` LEFT JOIN `category_tools` ON `category_tools`.`id`=`tools`.`id_category` LEFT JOIN `users` ON `tools`.`id_user`=`users`.`id` LEFT JOIN `users_groups` ON `tools`.`id_user`=`users_groups`.`user_id` LEFT JOIN `groups` ON `users_groups`.`group_id`=`groups`.`id` ".$where." ORDER BY `title` ASC ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    } 
    
/*
    *   Список поездок по поручениям
*/
    public function select_users($config = NULL) {
        $where = '';
        if (isset($config['id_group'])) {
            $where .= "WHERE `users_groups`.`group_id` = '".$config['id_group']."'";
        }
        if (isset($config['id_user'])) {
            $where .= "WHERE `users`.`id` = '".$config['id_user']."'";
        }
            
        $query = $this->db->query("SELECT `users`.`id`, `users`.`username`, `users`.`email`, `users_groups`.`group_id` FROM `users` LEFT JOIN `users_groups` ON `users_groups`.`user_id`=`users`.`id` ".$where." ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
    /*
        *   Список категорий галереи
    */
    public function select_brigade_members($config = NULL) {
        if ($config['table'] != NULL) {
            $query = $this->db->query("SELECT `".$config['table']."`.`id_brigade`, `".$config['table']."`.`id_user`, `users`.`id`, `users`.`username`, `groups`.`name` AS `groupname` FROM `".$config['table']."` LEFT JOIN `users` ON `users`.`id`=`".$config['table']."`.`id_user` LEFT JOIN `users_groups` ON `users_groups`.`user_id`=`".$config['table']."`.`id_user` LEFT JOIN `groups` ON `groups`.`id`=`users_groups`.`group_id` WHERE `id_brigade` = '".$config['where']."'");
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
        }
        return FALSE;
    }

    /*
        *   Удаление сертификатов
    */
     public function delete_certificate ($id = NULL) {
        if($id != NULL){
            $result_certificate = $this->db->query("DELETE FROM `catalog_certificate` WHERE `catalog_certificate`.`id_catalog` IN (".implode(",",$id).")");
            $result_catalog = $this->db->query("DELETE FROM `catalog` WHERE `catalog`.`id` IN (".implode(",",$id).")");
            if($result_certificate === TRUE && $result_catalog === TRUE) {
                return TRUE;
            } elseif ($result_catalog === TRUE){
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /*
        *   Удаление сертификатов производителя
    */
     public function delete_certificate_man ($id = NULL) {
        if($id != NULL){
            $certs = $this->db->query("SELECT `manufacturer_certificate`.`id_all_files` FROM `manufacturer_certificate` WHERE `manufacturer_certificate`.`id_manufacturer` IN (".implode(",",$id).")");
            $certs = $certs->result_array();
            foreach ($certs as $cert) {
                $unlink_files = $this->db->query("SELECT `all_files`.`path` FROM `all_files` WHERE `all_files`.`id` IN (".implode(",",$cert).")");
                $unlink_files = $unlink_files->result_array();
                
                $result = $this->db->query("DELETE FROM `all_files` WHERE `all_files`.`id` IN (".implode(",",$cert).")");
                foreach ($unlink_files as $unlink_files_key => $unlink_files_value) {
                    $document_path = substr($unlink_files_value['path'], 1);
                    $document_path_delete = $_SERVER['DOCUMENT_ROOT'] . $document_path; 
                    if (file_exists($document_path_delete)){
                        unlink($document_path_delete);
                    } else {
                        return FALSE; 
                    }
                }
            }
            
            $result_certificate = $this->db->query("DELETE FROM `manufacturer_certificate` WHERE `manufacturer_certificate`.`id_manufacturer` IN (".implode(",",$id).")");
            
            $result_manufacturers = $this->db->query("DELETE FROM `manufacturers` WHERE `manufacturers`.`id` IN (".implode(",",$id).")");
            if($result_certificate === TRUE && $result_manufacturers === TRUE) {
                return TRUE;
            } elseif ($result_manufacturers === TRUE){
                return TRUE;
            }
        }
        return FALSE;
    }
    
/*
    *   Удаление инструментов
*/
     public function delete_tools_images ($id = NULL) {
        if($id != NULL){
            $result_certificate = $this->db->query("DELETE FROM `tools_images` WHERE `tools_images`.`id_tool` IN (".implode(",",$id).")");
            $result_tool = $this->db->query("DELETE FROM `tools` WHERE `tools`.`id` IN (".implode(",",$id).")");
            if($result_certificate === TRUE && $result_tool === TRUE) {
                return TRUE;
            } elseif ($result_tool === TRUE){
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /*
        *   Удаление изображений
    */
     public function delete_images ($id = NULL) {
        if($id != NULL){
            $result_images = $this->db->query("DELETE FROM `gallery_images` WHERE `gallery_images`.`id_gallery` IN (".implode(",",$id).")");
            $result_images = $this->db->query("DELETE FROM `gallery` WHERE `gallery`.`id` IN (".implode(",",$id).")");
            if($result_images === TRUE && $result_images === TRUE) {
                return TRUE;
            } elseif ($result_images === TRUE){
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /*
        *   Удаление изображений
    */
     public function delete_work_images ($id = NULL) {
        if($id != NULL){
            $result_images = $this->db->query("DELETE FROM `work_images` WHERE `work_images`.`id_work` IN (".implode(",",$id).")");
            $result_images = $this->db->query("DELETE FROM `work` WHERE `work`.`id` IN (".implode(",",$id).")");
            if($result_images === TRUE && $result_images === TRUE) {
                return TRUE;
            } elseif ($result_images === TRUE){
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /*
        *   Удаление сертификатов
    */
     public function delete_certificates ($id = NULL) {
        if($id != NULL){
            $result_images = $this->db->query("DELETE FROM `certificates_images` WHERE `certificates_images`.`id_certificate` IN (".implode(",",$id).")");
            $result_images = $this->db->query("DELETE FROM `certificates` WHERE `certificates`.`id` IN (".implode(",",$id).")");
            if($result_images === TRUE && $result_images === TRUE) {
                return TRUE;
            } elseif ($result_images === TRUE){
                return TRUE;
            }
        }
        return FALSE;
    }
    
    public function delete_adjustment ($id_work = NULL, $id_user = NULL, $type = NULL) {
        if($id_work !== NULL && $id_user !== NULL && $type !== NULL){
                $result_delete = $this->db->query("DELETE FROM `adjustment_payment` WHERE `adjustment_payment`.`id_work` = '".$id_work."' AND `adjustment_payment`.`id_user` = '".$id_user."' AND `adjustment_payment`.`type` = '".$type."'");
            if($result_delete === TRUE) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
}
