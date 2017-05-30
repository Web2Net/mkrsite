<?php
    date_default_timezone_set('Europe/Kiev');
    
    define('WEB2NET', '1');
    
    define('KURS_USD','8.80');
    
//    define('DB_HOST', 'localhost');
//    define('DB_USER', 'mkr-man');
//    define('DB_PASS', 'bmnF9YWXz');
//    define('DB_NAME', 'mkr_new');    
    
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'mkr_new');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    
    
    define('USER_IP', getenv ("REMOTE_ADDR"));
    define('PAGE_REF', getenv("HTTP_REFERER"));    
    define('SITE_PATH', getenv("DOCUMENT_ROOT"));
    define('SITE_URL', "http://".getenv("HTTP_HOST"));
    define('ADM_SITE_URL',SITE_URL.'/cms');
    define('PAGE_URL', SITE_URL.getenv("REQUEST_URI"));    
    
    define('PATH_EMAIL_BASE', SITE_PATH.'/cms/sender/e-mail_base'); // Рассылка  
     
    define('PATH_DUMP',       SITE_PATH.'/archives');  // Где лежат дампы БД
    define('EXT_DUMP',  'sql');                           // Разширение для дампов БД
    
    define('PATH_DESIGN',       '/site/design');
    //define('PATH_DESIGN',       '/site/design/t_cross');
    //define('PATH_DESIGN',       '/site/design/t_1');
    
    define('PATH_LIB',          '/lib');
    define('PATH_TPL',          'site/tpl');
    define('PATH_IMG',          PATH_DESIGN.'/img');                             
    define('PATH_INC',          PATH_TPL.'/inc');
    define('PATH_IMG_ITEMS',    PATH_IMG.'/items');
    define('PATH_IMG_ART',    PATH_IMG.'/art');
    
    define('PATH_IMG_ADMIN',          '/cms/design');
    
    define('EMAIL_BOSS',      'admin@mkr.com.ua');
    define('EMAIL_ADMIN',     'admin@mkr.com.ua');
    define('EMAIL_FEEDBACK',  'feedback@mkr.com.ua');
    define('EMAIL_WEBMASTER', 'admin@mkr.com.ua');
    define('EMAIL_OFFICE',    'office@mkr.com.ua');
    define('EMAIL_UNSUBCRIBE','unsubscribe@mkr.com.ua');
    
    //define('CHARSET', 'windows-1251');
    define('CHARSET', 'utf-8');
    
    define('SITE_NAME', 'Мастерская комплексных решений');
    
    define('USERS_TABLE','users');
    define('SID',session_id());
    
    
    define('PATH_INC_MOBI',          PATH_TPL.'/mobi/inc');
    define('PATH_DESIGN_MOBI',       '/site/design/mobi');
    define('PATH_IMG_MOBI',          PATH_DESIGN_MOBI.'/img');
    
    
?>
