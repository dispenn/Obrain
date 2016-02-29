<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	------------------------Правила для форм------------------------
*/
class Rules_model extends CI_Model {
	
     public $banner_rules = array(
		array(
			'field'   => 'form[libk]',
			'label'   => 'Ссылка',
			'rules'   => 'trim|max_length[255]|xss_clean'
			),
		array(
			'field'   => 'form[position]',
			'label'   => 'Позиция',
			'rules'   => 'trim|max_length[255]|xss_clean'
			)
	);

	public $pages_rules = array(
		array(
			'field'   => 'form[title]',
			'label'   => 'Заголовок',
			'rules'   => 'trim|required|max_length[255]|xss_clean'
		),
		array(
			'field'   => 'form[access]',
			'label'   => 'Статус',
			'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'form[metakey]',
			'label'   => 'Ключевые слова',
			'rules'   => 'trim|max_length[200]|xss_clean'
		),
		array(
			'field'   => 'form[metadesc]',
			'label'   => 'Описание',
			'rules'   => 'trim|max_length[200]|xss_clean'
		),
		array(
			'field'   => 'form[fulltext]',
			'label'   => 'Текст страницы',
			'rules'   => 'trim|required'
		)
	);

    public $department_rules = array(
		array(
			'field'   => 'form[title]',
			'label'   => 'Название',
			'rules'   => 'trim|required|xss_clean'
			)
	);
    
    public $about_rules = array(
        array(
			'field'   => 'form[company]',
			'label'   => 'company',
			'rules'   => 'trim|xss_clean'
			),
        array(
			'field'   => 'form[client]',
			'label'   => 'client',
			'rules'   => 'trim|xss_clean'
			),
        array(
			'field'   => 'form[awards]',
			'label'   => 'awards',
			'rules'   => 'trim|xss_clean'
			)
    );
    
    public $anketa_rules = array(
        array(
			'field'   => 'form[link]',
			'label'   => 'Ссылка',
			'rules'   => 'required'
			)
    );
    
    
    public $category_catalog_rules = array(
		array(
			'field'   => 'form[title]',
			'label'   => 'Название',
			'rules'   => 'trim|required|xss_clean'
			),
        array(
			'field'   => 'form[position]',
			'label'   => 'Позиция',
			'rules'   => 'trim|xss_clean'
			),
        array(
			'field'   => 'form[id_main_category]',
			'label'   => 'Главная категория',
			'rules'   => 'trim|xss_clean'
			),
        array(
			'field'   => 'form[id_icon]',
			'label'   => 'Иконка',
			'rules'   => 'trim|xss_clean'
			)
	);
    
    public $contact_rules = array(
		array(
			'field'   => 'form[address]',
			'label'   => 'Адрес',
			'rules'   => 'trim|required|xss_clean'
			),
		array(
			'field'   => 'form[phone]',
			'label'   => 'Телефон',
			'rules'   => 'trim|required|xss_clean'
			),
		array(
			'field'   => 'form[fulltext]',
			'label'   => 'Текст',
			'rules'   => 'trim|xss_clean'
			),
		array(
			'field'   => 'form[email]',
			'label'   => 'E-mail',
			'rules'   => 'trim|required|valid_email|xss_clean'
			)
	);
    
    public $question_rules = array(
		array(
			'field'   => 'form[name]',
			'label'   => 'Имя',
			'rules'   => 'trim|required|max_length[255]|xss_clean'
			),
		array(
			'field'   => 'form[phone]',
			'label'   => 'Телефон',
			'rules'   => 'trim|required|xss_clean'
			),
		array(
			'field'   => 'form[text]',
			'label'   => 'Текст',
			'rules'   => 'trim|required|xss_clean'
			),
		array(
			'field'   => 'form[email]',
			'label'   => 'E-mail',
			'rules'   => 'trim|valid_email|xss_clean'
			),
		array(
			'field'   => 'form[company]',
			'label'   => 'Компания',
			'rules'   => 'trim|max_length[255]|xss_clean'
			)
	);
    
    public $vacancy_rules = array(
		array(
			'field'   => 'form[name]',
			'label'   => 'Название',
			'rules'   => 'trim|required|max_length[255]|xss_clean'
			),
		array(
			'field'   => 'form[salary]',
			'label'   => 'Зарплата',
			'rules'   => 'trim|required||max_length[255]xss_clean'
			),
		array(
			'field'   => 'form[desc]',
			'label'   => 'Описание',
			'rules'   => 'trim|required|xss_clean'
			)
	);
    
    public $promo_rules = array(
		array(
			'field'   => 'form[title]',
			'label'   => 'Заголовок',
			'rules'   => 'trim|required|max_length[255]|xss_clean'
			),
		array(
			'field'   => 'form[date]',
			'label'   => 'Дата',
			'rules'   => 'trim|max_length[255]|xss_clean'
			),
		array(
			'field'   => 'form[short_text]',
			'label'   => 'Текст',
			'rules'   => 'trim|max_length[255]|xss_clean'
			),
		array(
			'field'   => 'form[full_text]',
			'label'   => 'Текст',
			'rules'   => 'trim|xss_clean'
			)
	);
    
    public $gallery_rules = array(
		array(
			'field'   => 'form[title]',
			'label'   => 'Название',
			'rules'   => 'trim|required|xss_clean'
		),
		array(
			'field'   => 'form[fulltext]',
			'label'   => 'Описание',
			'rules'   => 'trim|xss_clean'
		),
        array(
			'field'   => 'form[date]',
			'label'   => 'Дата',
			'rules'   => 'trim|xss_clean'
        )
	);
    
    public $form_rules = array(
		array(
			'field'   => 'form[name]',
			'label'   => 'Имя',
			'rules'   => 'trim|required|max_length[255]|xss_clean'
			),
		array(
			'field'   => 'form[phone]',
			'label'   => 'Телефон',
			'rules'   => 'trim|required|max_length[255]|xss_clean'
			),
        array(
			'field'   => 'form[id_course]',
			'label'   => 'Курс',
			'rules'   => 'trim|xss_clean'
			)
	);

    public $employee_rules = array(
		array(
			'field'   => 'form[name]',
			'label'   => 'ФИО',
			'rules'   => 'trim|required|max_length[255]|xss_clean'
			),
        array(
			'field'   => 'form[post]',
			'label'   => 'Должность',
			'rules'   => 'trim|max_length[255]|xss_clean'
			),
        array(
			'field'   => 'form[phone]',
			'label'   => 'Телефон',
			'rules'   => 'trim|max_length[255]|xss_clean'
			),
        array(
			'field'   => 'form[email]',
			'label'   => 'Email',
			'rules'   => 'trim|max_length[255]|xss_clean'
			),
        array(
			'field'   => 'form[id_department]',
			'label'   => 'Отдел',
			'rules'   => 'trim|max_length[255]|xss_clean'
			)
	);
 
    public $catalog_rules = array(
		array(
			'field'   => 'form[title]',
			'label'   => 'Название',
			'rules'   => 'trim|required|max_length[255]|xss_clean'
			),
        array(
			'field'   => 'form[id_category]',
			'label'   => 'Категория',
			'rules'   => 'trim|required|xss_clean'
			),
        array(
			'field'   => 'form[description]',
			'label'   => 'Описание',
			'rules'   => 'trim|xss_clean'
			),
        array(
			'field'   => 'form[position]',
			'label'   => 'Позиция',
			'rules'   => 'trim|xss_clean'
			)
	);
 
    public $timetable_rules = array(
		array(
			'field'   => 'form[text]',
			'label'   => 'text',
			'rules'   => 'trim|required|xss_clean'
			)
	);
    
    public $view_options_rules = array(
		array(
			'field'   => 'form[lessons]',
			'label'   => 'lessons',
			'rules'   => 'trim|xss_clean'
			),
		array(
			'field'   => 'form[course]',
			'label'   => 'course',
			'rules'   => 'trim|xss_clean'
			),
		array(
			'field'   => 'form[click]',
			'label'   => 'click',
			'rules'   => 'trim|xss_clean'
			),
		array(
			'field'   => 'form[pay]',
			'label'   => 'pay',
			'rules'   => 'trim|xss_clean'
			)
	);
	
	public function __construct() {
		parent::__construct();
	}
}
