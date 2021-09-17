<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_lib
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('setting_model');
    }

    public function getWorkdays($date1, $date2, $holiday)
    {
        if (!defined('SATURDAY')) define('SATURDAY', 6);
        if (!defined('SUNDAY')) define('SUNDAY', 0);
        // Array of all public festivities
        $publicHolidays = $holiday;
        // The Patron day (if any) is added to public festivities


//        if ($patron) {
//            $publicHolidays[] = $patron;
//        }
//        print_r($publicHolidays);

        /*
         * Array of all Easter Mondays in the given interval
         */
//        $yearStart = date('Y', strtotime($date1));
//        $yearEnd = date('Y', strtotime($date2));
//        for ($i = $yearStart; $i <= $yearEnd; $i++) {
//            $easter = date('Y-m-d', easter_date($i));
//            list($y, $m, $g) = explode("-", $easter);
//            $monday = mktime(0, 0, 0, date($m), date($g) + 1, date($y));
//            $easterMondays[] = $monday;
//        }
        $start = strtotime($date1);
        $end = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if (!in_array($mmgg, $publicHolidays)) {
                $workdays++;
            }
        }
        return intval($workdays);
    }


    public function assc_array_count_values($array, $key)
    {
        foreach ($array as $row) {
            $new_array[] = $row[$key];
        }
        return array_count_values($new_array);
    }

    public function get_words($sentence, $count = 10)
    {
        preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
        return $matches[0];
    }


    public function time_elapsed_string($datetime, $full = false)
    {
        $settings = $this->CI->Setting_model->getSetting();
        $timezone = $settings->timezone;
        $now = new DateTime('now', new DateTimeZone($timezone));
        $ago = new DateTime($datetime, new DateTimeZone($timezone));
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function date_compare($a, $b)
    {
        $t1 = strtotime($a['datetime']);
        $t2 = strtotime($b['datetime']);
        return $t1 - $t2;
    }

    public function no_of_saturdays($session_start, $public_holiday)
    {
        $now = time(); // or your date as well
        $start_date = $session_start;
        $your_date = strtotime($start_date);
        $datediff = $now - $your_date;
        $no_of_days = round($datediff / (60 * 60 * 24));
        $count = 0;
        for ($i = 0; $i < $no_of_days; $i++) {
            $date = strtotime("+" . $i . " day", strtotime($start_date));
            $date = date('Y-m-d', $date);
            $timestamp = strtotime($date);
            $day = date('l', $timestamp);
            if ($day == $public_holiday) {
                $count++;
            }

        }
        return $count;
    }

    public function check_user_status($intitude_code, $instituteUserId, $instituteRoleId, $reg_code)
    {
        $url = Neema_Url . "CheckActiveStatus?instituteCode=$intitude_code&instituteUserId=$instituteUserId&instituteRoleId=$instituteRoleId&registrationCode=$reg_code";
        $json_data = file_get_contents($url);
        $data = json_decode($json_data);
        if ($data->Result == 1) {
            return 'Active';
        } else {
            return 'InActive';
        }
    }
}