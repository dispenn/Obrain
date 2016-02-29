<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
    
    public function count_catalog($category = NULL) {
        $where = "";
        
        if ($category != NULL) {
            $where .= " WHERE `category_catalog`.`alias` = '".$category."' ";
        } 

        $query = $this->db->query("
            SELECT 
                `catalog`.`id`
            FROM `catalog` 
            LEFT JOIN `category_catalog` ON `category_catalog`.`id` = `catalog`.`id_category` " 
            .$where.
            " ORDER BY `catalog`.`id` DESC");
        if ($query->row_array() > 0) {
            return $query->result_array();
        }
        
        return FALSE;
    }

    public function select_category_list($main = FALSE) {
        $where = "";
        if ($main !== FALSE) {
            $where .= " WHERE `category_catalog`.`id_main_category` = 0 ";
        }
        
        $query = $this->db->query("
            SELECT 
                `category_catalog`.*, 
                `icons`.`title` AS `icon`, 
                `main_category_catalog`.`title` AS `main_category_title` 
            FROM `category_catalog` 
            LEFT JOIN `icons` ON `icons`.`id` = `category_catalog`.`id_icon` 
            LEFT JOIN `category_catalog` AS `main_category_catalog` ON `main_category_catalog`.`id`=`category_catalog`.`id_main_category` " 
            .$where.
            " ORDER BY CASE WHEN `category_catalog`.`position` = '0' THEN '65535' END, `position` ASC");
        if ($query->row_array() > 0) {
            return $query->result_array();
        }
        
        return FALSE;
    }

    public function select_pagination_filter($table, $num = 0, $offset = 0, $category = NULL) {
        $where = " WHERE `catalog`.`id` != 0 ";
        if ($category != NULL) {
            $where .= " AND `main_category_catalog`.`alias` = '".$category."' ";
        } 
    
        $condition = "";
        if ($num !== 0 && $offset == 0) {
            $condition = ' LIMIT '.$num;
        } elseif ($num !== 0 && $offset !== 0) {
            $condition = ' LIMIT '.$offset.', '.$num;
        }
    
        $query = $this->db->query("
            SELECT 
                `category_catalog`.`id`, 
                `category_catalog`.`title`, 
                `category_catalog`.`alias`,
                `catalog`.`id` AS `catalog_id`, 
                `catalog`.`title` AS `catalog_title`, 
                `catalog`.`alias` AS `catalog_alias`, 
                `catalog`.`id_image`, 
                `catalog`.`description`, 
                `all_files`.`path`, 
                `catalog_images`.`id` AS `images`
            FROM `category_catalog` 
            LEFT JOIN `catalog` ON `catalog`.`id_category` = `category_catalog`.`id` 
            LEFT JOIN `all_files` ON `all_files`.`id` = `catalog`.`id_image` 
            LEFT JOIN `catalog_images` ON `catalog_images`.`id_catalog` = `catalog`.`id` 
            LEFT JOIN `category_catalog` AS `main_category_catalog` ON `main_category_catalog`.`id` = `category_catalog`.`id_main_category`             
            " .$where." 
            GROUP BY `catalog`.`id` 
            ORDER BY CASE WHEN `catalog`.`position` = '0' THEN '65535' END, `catalog`.`position` ASC
            " .$condition
        );
            
        //$query = $this->db->get_where($table, array('publish' => 1), $num, $offset);
        return $query->result_array();
    }

    /*
        *   Список расцветок
    */
    public function select_galleryes() {
        $query = $this->db->query("
            SELECT 
                `gallery`.`id`, 
                `gallery`.`alias`, 
                `gallery`.`title`, 
                `gallery`.`fulltext`, 
                `all_files`.`path` 
            FROM 
                `gallery` 
            LEFT JOIN `gallery_images` ON `gallery_images`.`id_gallery`=`gallery`.`id` AND `gallery_images`.`main_photo` = 1 
            LEFT JOIN `all_files` ON `all_files`.`id`=`gallery_images`.`id_all_files`
        ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    public function select_last_news($number = 3) {
            $query = $this->db->query("
                SELECT 
                    `news`.*, 
                    `all_files`.`path`,
                    `all_files`.`thumb`
                FROM `news` 
                LEFT JOIN `all_files` ON `all_files`.`id`=`news`.`id_image` 
                ORDER BY `date` DESC
                LIMIT ".$number."
            ");
            if ($query->row_array() > 0) {
                return $query->result_array();
            }
            return FALSE;
    }

    /*
        *   Список товаров в корзине
    */
    public function select_shop_cart($id_user = NULL, $id_catalog = NULL, $id = NULL) {
        $where = '';
        if ($id_user !== NULL) {
            $where .= "WHERE `shop_cart`.`id_user` = '".$id_user."'";
        }
        if ($id_user !== NULL && $id_catalog !== NULL) {
            $where .= " AND `shop_cart`.`id_catalog` = '".$id_catalog."'";
        }
        if ($id !== NULL) {
            $where = "WHERE `shop_cart`.`id` = '".$id."'";
        }
        
        $sort = '';
        
        $query = $this->db->query("SELECT `shop_cart`.`id`, `shop_cart`.`id_user`, `shop_cart`.`count`, `shop_cart`.`id_catalog`, `catalog`.`id_main_photo`, `catalog`.`title`, `catalog`.`alias`, `catalog`.`price`, `catalog`.`new_price`, `all_files`.`path`, `category_catalog`.`alias` AS `category_alias` FROM `shop_cart` LEFT JOIN `catalog` ON `catalog`.`id`=`shop_cart`.`id_catalog` LEFT JOIN `all_files` ON `all_files`.`id`=`catalog`.`id_main_photo` LEFT JOIN `category_catalog` ON `category_catalog`.`id`=`catalog`.`id_category` ".$where." ORDER BY `id` DESC ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
    /*
        *   Список товаров в истории
    */
    /*public function select_shop_history($id_user = NULL, $id = NULL) {
        $where = '';
        if ($id_user !== NULL) {
            $where .= "WHERE `orders`.`id_user` = '".$id_user."'";
        }
        if ($id !== NULL) {
            $where = "WHERE `orders`.`id` = '".$id."'";
        }
        
        $sort = '';
        
        $query = $this->db->query("SELECT `orders`.*, `shop_cart`.`id_user`, `shop_cart`.`count`, `shop_cart`.`id_catalog`, `catalog`.`id_main_photo`, `catalog`.`title`, `catalog`.`alias`, `catalog`.`price`, `catalog`.`new_price`, `all_files`.`path`, `category_catalog`.`alias` AS `category_alias` FROM `shop_cart` LEFT JOIN `catalog` ON `catalog`.`id`=`shop_cart`.`id_catalog` LEFT JOIN `all_files` ON `all_files`.`id`=`catalog`.`id_main_photo` LEFT JOIN `category_catalog` ON `category_catalog`.`id`=`catalog`.`id_category` ".$where." ORDER BY `id` DESC ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }*/

    public function select_in_shop_cart($config = NULL) {
        if (!empty($config['where'])) {
            $where = '';
            if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `catalog`.`id` IN (".implode(",",$config['where']).")";
            }
            $query = $this->db->query("SELECT `catalog`.`id`, `catalog`.`id_main_photo`, `catalog`.`title`, `catalog`.`alias`, `catalog`.`price`, `catalog`.`new_price`, `all_files`.`path`, `category_catalog`.`alias` AS `category_alias` FROM `catalog` LEFT JOIN `all_files` ON `all_files`.`id`=`catalog`.`id_main_photo` LEFT JOIN `category_catalog` ON `category_catalog`.`id`=`catalog`.`id_category` ".$where." ");
            if ($query->row_array() > 0) {
                return $query->result_array();
            }
        }
        else {
            return FALSE;
        }
    }

/*
    *   Прайс-лист
    *   Для объединения записей по цене для уменьшения списка потолков в отличие от администраторской части
*/
    public function select_price($config = NULL) {
        $where = '';
        if (isset($config['where_field']) && isset($config['where'])) {
                $where = "WHERE `pricelist`.`".$config['where_field']."` = '".$config['where']."'";
        }
        
        /*
            * Установка максимальной длины:
                SET group_concat_max_len = 2048;
            * DISTINCT удаляет дублирующиеся строки
            * SEPARATOR разделяет данные через запятую
        */
        $query = $this->db->query("SELECT `pricelist`.`id`, `pricelist`.`id_manufacturer`, GROUP_CONCAT(DISTINCT `pricelist`.`facture` ORDER BY `pricelist`.`facture` ASC SEPARATOR ', ') AS `facture`, GROUP_CONCAT(DISTINCT `pricelist`.`width` ORDER BY `pricelist`.`width` ASC SEPARATOR ', ') AS `width`, GROUP_CONCAT(DISTINCT `pricelist`.`color` ORDER BY `pricelist`.`color` ASC SEPARATOR ', ') AS `color`, `pricelist`.`price1`, `pricelist`.`price2`, `pricelist`.`price3`, `manufacturers`.`title` AS `manufacturer` FROM `pricelist` LEFT JOIN `manufacturers` ON `pricelist`.`id_manufacturer`=`manufacturers`.`id` ".$where." GROUP BY `pricelist`.`price1`, `pricelist`.`price2`, `pricelist`.`price3` ORDER BY `title` ASC");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }
    
    /*
        *   Список картинок
    */
    public function select_images($config = NULL) {
        $query = $this->db->query("SELECT `".$config['table']."`.`id`, `".$config['table']."`.`id_all_files`, `".$config['table']."`.`".$config['where_field']."`, `all_files`.`id` AS `all_files_id`, `all_files`.`path`, `all_files`.`thumb` FROM `".$config['table']."` LEFT JOIN `all_files` ON `all_files`.`id` = `".$config['table']."`.`id_all_files` WHERE `".$config['table']."`.`".$config['where_field']."` = '".$config['where']."'");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    public function select_promos($num = NULL, $offset = NULL, $alias = FALSE) {
        if ($alias === FALSE)
        {
            //$query = $this->db->get('blog');
            //$this->db->order_by('id', 'desc');
            //$query = $this->db->get('promos', $num, $offset);
            $query = $this->db->query("
                SELECT 
                    `promos`.`id`, 
                    `promos`.`title`, 
                    `promos`.`alias`, 
                    `promos`.`id_image`, 
                    `promos`.`date`, 
                    `promos`.`short_text`, 
                    `all_files`.`path` 
                FROM 
                    `promos` 
                LEFT JOIN 
                    `all_files` ON `all_files`.`id` = `promos`.`id_image` 
                ORDER BY `promos`.`id` DESC 
                LIMIT ".$offset.", ".$num." 
            ");
            return $query->result_array();
        }
        
        //$query = $this->db->get_where('promos', array('alias' => $alias));
        $query = $this->db->query("
            SELECT 
                `promos`.*, 
                `all_files`.`path` 
            FROM 
                `promos` 
            LEFT JOIN 
                `all_files` ON `all_files`.`id` = `promos`.`id_image` 
            WHERE 
                `promos`.`alias` = '".$alias."'
        ");
        return $query->row_array();
    }
    
    public function select_articles($num = NULL, $offset = NULL, $alias = FALSE) {
        if ($alias === FALSE)
        {
            //$query = $this->db->get('blog');
            //$this->db->order_by('id', 'desc');
            //$query = $this->db->get('promos', $num, $offset);
            $query = $this->db->query("
                SELECT 
                    `articles`.*, 
                    `all_files`.`path` 
                FROM 
                    `articles` 
                LEFT JOIN 
                    `all_files` ON `all_files`.`id` = `articles`.`id_image` 
                ORDER BY `articles`.`date` DESC 
                LIMIT ".$offset.", ".$num." 
            ");
            return $query->result_array();
        }
        
        //$query = $this->db->get_where('promos', array('alias' => $alias));
        $query = $this->db->query("
            SELECT 
                `articles`.*, 
                `all_files`.`path` 
            FROM 
                `articles` 
            LEFT JOIN 
                `all_files` ON `all_files`.`id` = `articles`.`id_image` 
            WHERE 
                `articles`.`alias` = '".$alias."'
        ");
        return $query->row_array();
    }
    
    public function select_news($num = NULL, $offset = NULL, $alias = FALSE) {
        if ($alias === FALSE)
        {
            //$query = $this->db->get('blog');
            //$this->db->order_by('id', 'desc');
            //$query = $this->db->get('promos', $num, $offset);
            $query = $this->db->query("
                SELECT 
                    `news`.`id`, 
                    `news`.`title`, 
                    `news`.`alias`, 
                    `news`.`id_image`, 
                    `news`.`date`, 
                    `news`.`short_text`, 
                    `all_files`.`path` 
                FROM 
                    `news` 
                LEFT JOIN 
                    `all_files` ON `all_files`.`id` = `news`.`id_image` 
                ORDER BY `news`.`date` DESC 
                LIMIT ".$offset.", ".$num." 
            ");
            return $query->result_array();
        }
        
        //$query = $this->db->get_where('promos', array('alias' => $alias));
        $query = $this->db->query("
            SELECT 
                `news`.*, 
                `all_files`.`path` 
            FROM 
                `news` 
            LEFT JOIN 
                `all_files` ON `all_files`.`id` = `news`.`id_image` 
            WHERE 
                `news`.`alias` = '".$alias."'
        ");
        return $query->row_array();
    }
    /*
    public function select_news($num = NULL, $offset = NULL, $alias = FALSE) {
        if ($alias === FALSE)
        {
            //$query = $this->db->get('blog');
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('news', $num, $offset);
            return $query->result_array();
        }
        
        $query = $this->db->get_where('news', array('alias' => $alias));
        return $query->row_array();
    }*/
}
