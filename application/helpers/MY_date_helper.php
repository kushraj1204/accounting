<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('date_empty')) {
    function date_empty($date)
    {
        if (in_array($date, array('0000-00-00', '0000-00-00 00:00:00'))) {
            return true;
        }
        return empty($date);
    }
}

if (!function_exists('neema_date')) {
    function neema_date($date)
    {
        $CI = &get_instance();
        if (date_empty($date)) {
            return '';
        }
        return date($CI->customlib->getSchoolDateFormat(), $CI->customlib->dateyyyymmddTodateformat($date));
    }
}