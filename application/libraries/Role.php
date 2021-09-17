<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role {

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->student_login_prefix = "std";
        $this->CI->parent_login_prefix = "parent";
        $this->CI->teacher_login_prefix = "teacher";
        $this->CI->librarian_login_prefix = "librarian";
        $this->CI->accountant_login_prefix = "accountant";
    }

    public function get_random_password($chars_min = 6, $chars_max = 8, $use_upper_case = false, $include_numbers = false, $include_special_chars = false) {
        $length = rand($chars_min, $chars_max);
        $selection = 'aeuoyibcdfghjklmnpqrstvwxz';
        if ($include_numbers) {
            $selection .= "1234567890";
        }
        if ($include_special_chars) {
            $selection .= "!@\"#$%&[]{}?|";
        }

        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $current_letter = $use_upper_case ? (rand(0, 1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];
            $password .= $current_letter;
        }
        return $password;
    }

    public function generateLoginFromString($str, $id = null)
    {
        $str = strtolower($str);
        $pattern = $this->CI->db->escape('^' . $str . '[0-9]{0,}$');
        $this->CI->db->where('username RLIKE ' . $pattern);
        $this->CI->db->from('users');
        $count = $this->CI->db->count_all_results();
        if ($count > 0) {
            $login = false;
            $append = $count;
            while ($login === false) {
                $this->CI->db->from('users');
                $chk = $str . $append;
                $this->CI->db->where('username', $chk);
                if ($id) {
                    $this->CI->db->where('id !=', $id);
                }
                $c = $this->CI->db->count_all_results();
                if ($c > 0) {
                    $append++;
                } else {
                    $str = $chk;
                    $login = true;
                }
            }
        }
        return $str;
    }

}
