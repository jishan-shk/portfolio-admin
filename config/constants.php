<?php
if (!defined('SCRIPT_VERSION')) {
    define("SCRIPT_VERSION", (env('APP_ENV') != 'prod') ? rand() : '0.0.1');
    define("STATUS_INACTIVE", 0);
    define("STATUS_ACTIVE", 1);
    define("STATUS_DELETED", 2);


    define('SKILL_LOGO_PATH', 'uploads/skills');
    define('COMPANY_LOGO_PATH', 'uploads/company/logo');
    define('EDUCATION_LOGO_PATH', 'uploads/education/');
    define('COMPANY_DOCUMENT_PATH', 'uploads/company/document');
    define('PROJECTS_PATH', 'uploads/projects/');
    define('UPLOAD_PATH', 'uploads/');

    defined('SHOW_DATE_FORMAT') or define('SHOW_DATE_FORMAT', 'd-M-Y');
    defined('UI_SHORT_DATE_FORMAT') or define('UI_SHORT_DATE_FORMAT', 'd-m-Y');
    defined('DB_FULL_DATE_TIME') or define('DB_FULL_DATE_TIME', 'Y-m-d H:i:s');
    defined('DB_DATE_FORMATE_ONLY') or define('DB_DATE_FORMATE_ONLY', 'Y-m-d');
    defined('SMS_LEN') or define('SMS_LEN', 6);
    defined('OTP_EXPIRY_TIME') or define('OTP_EXPIRY_TIME', 10);
    defined('IS_EXPIRY_CHECK') or define('IS_EXPIRY_CHECK', true);
}
