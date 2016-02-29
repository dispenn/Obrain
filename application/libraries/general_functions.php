<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}
/*******************************************************************************                                                            *
* Version:  1                                                                  *
* Date:     2013-09-19                                                         *
* Author:   Yakovenko Dmitry                                                   *                                            *
*                                                                              *
* Универсальные функции вынесены в библеотеку для удобства использования       *
*******************************************************************************/

class General_functions {
        public function __construct() {
                $this->CI =& get_instance();
                $this->CI->load->helper('url');
                $this->CI->load->library('session');
                //$this->CI->load->library('database');
        }

/*
    *   Общее количество записей в таблице, нужно для пагинации.
    *
    *   $table_count  =   array таблиц которые нужно посчитать;
    *
*/
public function count_all($table_count = NULL) {
    if ($table_count != NULL && is_array($table_count)) {
        foreach ($table_count as $table_count_value) {
            $count[$table_count_value] = $this->CI->db->count_all($table_count_value);
        }
        return $count;
    } elseif ($table_count != NULL && is_string($table_count)) {
       return $this->CI->db->count_all($table_count);
    } 
    return FALSE;
}

/* Информационные сообщения системы.
        *       $type = тип ошибки
        *       $redirect_to_page = адрес перенаправления
        *       $messages = текст ошибки для пользователя
*/
public function alert ($type = NULL, $redirect_to_page = NULL, $messages = 'Неизвестная ошибка. Попробуйте позднее.') {
        if ($redirect_to_page === NULL || empty($redirect_to_page)) {
                $redirect_to_page = $_SERVER['HTTP_REFERER'];
        } 
        if (($type == 'error' || $type == 'success' || $type == 'info') && $type != NULL) {
                $this->CI->session->set_flashdata($type, $messages);
        } else {
                $this->CI->session->set_flashdata('error', 'Системная ошибка. Обратитесь к администратору!');
        }
        redirect($redirect_to_page);
}

/* Транслитерация строки, переводит в нижний регистр.
        *       $type = входная строка
*/
public function transliterate($string) {

    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => "",  'ы' => 'y',   'ъ' => "",
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
 
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => "",   'Ы' => 'Y',   'Ъ' => "",
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        ',' => '',    '.' => '', ':' => '', '"' => '', '\'' => ''
    );
        
      return iconv("UTF-8", "UTF-8//TRANSLIT//IGNORE", mb_strtolower(strtr ($string, $converter)));
}

/* Загрузка файлов с заданным именем 
*/

public function upload_files_with_name($files = NULL, $name_directory = NULL, $file_name = NULL) {
        if ($files != NULL) {
            if ($name_directory != NULL) {
                    $upload_path = './uploads/'.$name_directory.'';
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'].substr($upload_path, 1))) {
                        mkdir($_SERVER['DOCUMENT_ROOT'].substr($upload_path, 1), 0777);
                    } 
            } else {
                     $upload_path = './uploads/';
                     
            }
            $allowed_types = NULL;
            if ($allowed_types == NULL || empty($allowed_types)) {
                $allowed_types = array('jpg', 'jpeg', 'png', 'JPG', 'PNG', 'JPEG');
            }
            // Массив данных о загруженных файлах для добавления в базу
            $files_information = array();
            // Перебираем все подгружаемые файлы.
            foreach ($files['error'] as $key => $error) {
                    // Если файл из списка успешно загружен
                    if ($error == UPLOAD_ERR_OK) {
                        $file_type = explode('.', $files['name'][$key]);
                        $file_type = $file_type[count($file_type)-1];
                        
                        if (in_array($file_type, $allowed_types)) {
                            // Временное место хранения загруженного файла
                            $file_tmp = $files['tmp_name'][$key];

                            // Формируем имя и путь к загруженному файлу
                            if ($file_name != NULL) {
                                $name_file = $file_name.'.'.$file_type;
                            } else {
                                $name_file = md5(uniqid(rand(),1)).'.'.$file_type;
                            }
                            
                            $uploaded_path = $upload_path.'/'.$name_file;
                            
                            // Перемещаем загруженный файл из временного хранения
                            // В папку загрузок.
                            if (move_uploaded_file($file_tmp, $uploaded_path)) {
                                $uploaded_thumb_path = '';

                                $files_uploaded['path'] = $uploaded_path;
                                
                                $files_information[] = array(
                                    'path' => $uploaded_path
                                );
                            }
                        }
                    }
            }
            if ($files_information) {
                return $files_information;
            }
            return FALSE;
        }
}

/* Загрузка файлов
    *       $name_directory = название директории в которую загружать 
    *       $compression = ужимать ли файл
*/

public function upload_files($files = NULL, $name_directory = NULL, $compression = '0', $images_height = 900, $images_width = 600, $allowed_types = NULL, $thumb_height = NULL, $thumb_width = NULL) {
        if ($files != NULL) {
            if ($name_directory != NULL) {
                    $upload_path = './uploads/'.$name_directory.'';
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'].substr($upload_path, 1))) {
                        mkdir($_SERVER['DOCUMENT_ROOT'].substr($upload_path, 1), 0777);
                    } 
            } else {
                     $upload_path = './uploads/';
                     
            }
            if ($allowed_types == NULL || empty($allowed_types)) {
                $allowed_types = array('jpg', 'jpeg', 'png', 'JPG', 'PNG', 'JPEG');
            }
            // Массив данных о загруженных файлах для добавления в базу
            $files_information = array();
            // Перебираем все подгружаемые файлы.
            foreach ($files['error'] as $key => $error) {
                    // Если файл из списка успешно загружен
                    if ($error == UPLOAD_ERR_OK) {
                        $file_type = explode('.', $files['name'][$key]);
                        $file_type = $file_type[count($file_type)-1];
                        
                        if (in_array($file_type, $allowed_types)) {
                            // Временное место хранения загруженного файла
                            $file_tmp = $files['tmp_name'][$key];

                            // Формируем имя и путь к загруженному файлу
                            $name_file = md5(uniqid(rand(),1)).'.'.$file_type;
                            $uploaded_path = $upload_path.'/'.$name_file;
                            
                            // Перемещаем загруженный файл из временного хранения
                            // В папку загрузок.
                            if (move_uploaded_file($file_tmp, $uploaded_path)) {
                                $uploaded_thumb_path = '';
                                //Если заданы параметры для превью
                                if ($thumb_height != NULL && $thumb_width != NULL) {
                                    //Путь до ужатой версии файла
                                    $uploaded_thumb_path = $upload_path.'/thumb_'.$name_file;
                                    $files_uploaded_thumb['path'] = $uploaded_thumb_path;
                                    //Создаём копию файла для превью
                                    if (copy($uploaded_path, $uploaded_thumb_path)) {
                                        $compression_status_thumb = $this->reduce_size_images($files_uploaded_thumb, $thumb_height, $thumb_width);
                                    } else {
                                        //Если не скопировалось перезаписываем путь в ошибку.
                                        $uploaded_thumb_path = 'error';
                                    }
                                }

                                $files_uploaded['path'] = $uploaded_path;
                                $compression_status = NULL;
                                if ($compression == '1') {
                                    $compression_status = $this->reduce_size_images($files_uploaded, $images_height, $images_width);
                                }
                                
                                $files_information[] = array(
                                    'path' => $uploaded_path,
                                    'thumb' => $uploaded_thumb_path,
                                    'compression' => $compression_status,
                                );
                            }
                        }
                    }
            }
            if ($files_information) {
                return $files_information;
            }
            return FALSE;
        }
}

/* Загрузка файлов
    *       $name_directory = название директории в которую загружать 
*/

public function upload_files_all_format($files = NULL, $name_directory = NULL) {
        if ($files != NULL) {
            if ($name_directory != NULL) {
                    $upload_path = './uploads/'.$name_directory.'';
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'].substr($upload_path, 1))) {
                        mkdir($_SERVER['DOCUMENT_ROOT'].substr($upload_path, 1), 0777);
                    } 
            } else {
                     $upload_path = './uploads/';
                     
            }
            // Массив данных о загруженных файлах для добавления в базу
            $files_information = array();
            // Перебираем все подгружаемые файлы.
            foreach ($files['error'] as $key => $error) {
                    // Если файл из списка успешно загружен
                    if ($error == UPLOAD_ERR_OK) {
                        $file_type = explode('.', $files['name'][$key]);
                        $file_type = $file_type[count($file_type)-1];
                        
                        // Временное место хранения загруженного файла
                        $file_tmp = $files['tmp_name'][$key];

                        // Формируем имя и путь к загруженному файлу
                        $name_file = md5(uniqid(rand(),1)).'.'.$file_type;
                        $uploaded_path = $upload_path.'/'.$name_file;
                            
                        // Перемещаем загруженный файл из временного хранения
                        // В папку загрузок.
                        if (move_uploaded_file($file_tmp, $uploaded_path)) {
                            $uploaded_thumb_path = '';
    
                            $files_uploaded['path'] = $uploaded_path;
                                                            
                            $files_information[] = array(
                                'path' => $uploaded_path
                            );
                        }
                    }
            }
            if ($files_information) {
                return $files_information;
            }
            return FALSE;
        }
}

/* Загрузка файлов в другую директорию (не uploads)
    *       $name_directory = название директории в которую загружать 
    *       $compression = ужимать ли файл
*/

public function upload_files_dir($files = NULL, $name_directory = NULL, $compression = '0', $images_height = 900, $images_width = 600, $allowed_types = NULL, $thumb_height = NULL, $thumb_width = NULL) {
        if ($files != NULL) {
            if ($name_directory != NULL) {
                    $upload_path = './'.$name_directory.'';
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'].substr($upload_path, 1))) {
                        mkdir($_SERVER['DOCUMENT_ROOT'].substr($upload_path, 1), 0777);
                    } 
            } else {
                     $upload_path = './uploads/';
                     
            }
            if ($allowed_types == NULL || empty($allowed_types)) {
                $allowed_types = array('jpg', 'jpeg', 'png', 'JPG', 'PNG', 'JPEG');
            }
            // Массив данных о загруженных файлах для добавления в базу
            $files_information = array();
            // Перебираем все подгружаемые файлы.
            foreach ($files['error'] as $key => $error) {
                    // Если файл из списка успешно загружен
                    if ($error == UPLOAD_ERR_OK) {
                        $file_type = explode('.', $files['name'][$key]);
                        $file_type = $file_type[count($file_type)-1];
                        
                        if (in_array($file_type, $allowed_types)) {
                            // Временное место хранения загруженного файла
                            $file_tmp = $files['tmp_name'][$key];

                            // Формируем имя и путь к загруженному файлу
                            $name_file = md5(uniqid(rand(),1)).'.'.$file_type;
                            $uploaded_path = $upload_path.'/'.$name_file;
                            
                            // Перемещаем загруженный файл из временного хранения
                            // В папку загрузок.
                            if (move_uploaded_file($file_tmp, $uploaded_path)) {
                                $uploaded_thumb_path = '';
                                //Если заданы параметры для превью
                                if ($thumb_height != NULL && $thumb_width != NULL) {
                                    //Путь до ужатой версии файла
                                    $uploaded_thumb_path = $upload_path.'/thumb_'.$name_file;
                                    $files_uploaded_thumb['path'] = $uploaded_thumb_path;
                                    //Создаём копию файла для превью
                                    if (copy($uploaded_path, $uploaded_thumb_path)) {
                                        $compression_status_thumb = $this->reduce_size_images($files_uploaded_thumb, $thumb_height, $thumb_width);
                                    } else {
                                        //Если не скопировалось перезаписываем путь в ошибку.
                                        $uploaded_thumb_path = 'error';
                                    }
                                }

                                $files_uploaded['path'] = $uploaded_path;
                                $compression_status = NULL;
                                if ($compression == '1') {
                                    $compression_status = $this->reduce_size_images($files_uploaded, $images_height, $images_width);
                                }
                                
                                $files_information[] = array(
                                    'path' => $uploaded_path,
                                    'thumb' => $uploaded_thumb_path,
                                    'compression' => $compression_status,
                                );
                            }
                        }
                    }
            }
            if ($files_information) {
                return $files_information;
            }
            return FALSE;
        }
}

/*
        *   Уменьшение размера фотографий.
        *   $uploaded_path массив путей к загруженным файлам.
        *   Если размер картинки меньше разрешение в config_images то не будет ужиматься.
*/
        public function reduce_size_images($uploaded_path = NULL, $config_images_height = 900, $config_images_width = 600) {
            //$uploaded_path = '/uploads/photos/'.$uploaded_path.'.jpg';
            if ($uploaded_path) {
                $this->CI->load->library('image_lib');          //Загружаем библиотеку
                $config_images['image_library'] = 'gd2';        //Тип библиотеки который поддерживает сжатие.
                $config_images['create_thumb'] = FALSE;         //Не создавать миниатюру.
                $config_images['maintain_ratio'] = TRUE;        //Сохранять пропорции.
                $config_images['width'] = $config_images_width; //Размеры фотографии
                $config_images['height'] = $config_images_height; 
                
                $uploaded_path['path'] = substr($uploaded_path['path'], 1);// Убираем точку в начале пути.
                $config_images['source_image'] = $_SERVER['DOCUMENT_ROOT'] . $uploaded_path['path'];
                $images_information = getimagesize($config_images['source_image']);
                if ($images_information[0] < $config_images['width'] && $images_information[1] < $config_images['height']) {
                     return TRUE;
                } else {
                    $this->CI->image_lib->initialize($config_images);
                    if ( ! $this->CI->image_lib->resize()) {
                            return $this->CI->image_lib->display_errors();
                    } else {
                            return TRUE;
                    }
                }     
            }
            return FALSE;                       
        }
        
        public function rdate($format, $timestamp = null, $case = 0) {
         if ( $timestamp === null )
          $timestamp = time();
        
         static $loc =
          'Январ,ь,я,е,ю,ём,е
          Феврал,ь,я,е,ю,ём,е
          Март, ,а,е,у,ом,е
          Апрел,ь,я,е,ю,ем,е
          Ма,й,я,е,ю,ем,е
          Июн,ь,я,е,ю,ем,е
          Июл,ь,я,е,ю,ем,е
          Август, ,а,е,у,ом,е
          Сентябр,ь,я,е,ю,ём,е
          Октябр,ь,я,е,ю,ём,е
          Ноябр,ь,я,е,ю,ём,е
          Декабр,ь,я,е,ю,ём,е';
        
         if ( is_string($loc) )
         {
          $months = array_map('trim', explode("\n", $loc));
          $loc = array();
          foreach($months as $monthLocale)
          {
           $cases = explode(',', $monthLocale);
           $base = array_shift($cases);
           
           $cases = array_map('trim', $cases);
           
           $loc[] = array(
            'base' => $base,
            'cases' => $cases,
           );
          }
         }
         
         $m = (int)date('n', $timestamp)-1;
         
         $F = $loc[$m]['base'].$loc[$m]['cases'][$case];
        
         $format = strtr($format, array(
          'F' => $F,
          'M' => substr($F, 0, 3),
         ));
         
         return date($format, $timestamp);
    }
    
        
/*--------------------Закрытие класса--------------------*/
/*public function reduce_size_images($uploaded_path = NULL, $config_images_height = 900, $config_images_width = 600) {
                //$uploaded_path = '/uploads/photos/'.$uploaded_path.'.jpg';
                //elec.formfaktor.ru/people/images_test/9c8cda456b3b715dfe1602bbdf41f8bf
                if ($uploaded_path) {
                        /*$this->CI->load->library('image_lib');                      //Загружаем библиотеку
                        $config_images['image_library'] = 'gd2';        //Тип библиотеки который поддерживает сжатие.
                        $config_images['create_thumb'] = FALSE;         //Не создавать миниатюру.
                        $config_images['maintain_ratio'] = TRUE;        //Сохранять пропорции.
                        $config_images['width'] = $config_images_width;                          //Размеры фотографии
                        $config_images['height'] = $config_images_height; 
                        
                        foreach ($uploaded_path as $uploaded_path_key => $uploaded_path_value) {
                        
                            $uploaded_path_value['path'] = substr($uploaded_path_value['path'], 1);// Убираем точку в начале пути.
                            $config_images['source_image'] = $_SERVER['DOCUMENT_ROOT'] . $uploaded_path_value['path'];
                            list($width, $height) = getimagesize($config_images['source_image']);
                            $ratio_orig = $width/$height;
                             
                            if ($config_images_width/$config_images_height > $ratio_orig) {
                               $config_images_width = $config_images_height*$ratio_orig;
                            } else {
                               $config_images_height = $config_images_width/$ratio_orig;
                            }
                            $image_p = imagecreatetruecolor($config_images_width, $config_images_height);
                            $image = imagecreatefromjpeg($config_images['source_image']);
                            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $config_images_width, $config_images_height, $width, $height);
                             
                            // вывод
                            
                            //header("Content-type: image/jpeg");
                            imagejpeg($image_p, $uploaded_path_value['path']);
                            return TRUE; 
                            //$images_information = getimagesize($config_images['source_image']);
                            /*if ($images_information[0] < $config_images['width'] && $images_information[1] < $config_images['height']) {
                                 return TRUE;
                             } else {
                                //$this->CI->image_lib->clear(); 
                                $this->CI->image_lib->initialize($config_images);
                                if ( ! $this->CI->image_lib->resize()) {
                                        return $this->CI->image_lib->display_errors();
                                } else {
                                        return TRUE;
                                }
                             }
                        }       
                }                       
        }*/
}
