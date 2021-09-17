<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('marksheet_grade')) {
    function marksheet_grade($grade_list, $full_marks, $obtained_marks)
    {
        $c_mark = floor(($obtained_marks / $full_marks) * 100);
        $is_full_marks = $c_mark == 100;
        $f_grade = array_filter($grade_list, function ($g) use ($c_mark, $is_full_marks) {
            if ($is_full_marks) {
                return $c_mark == $g['mark_upto'];
            }
            return $c_mark >= $g['mark_from'] && $c_mark < ($g['mark_upto'] + 1);
        });
        if (!empty($f_grade)) {
            return current($f_grade);
        }
        return false;
    }
}

if (!function_exists('marksheet_percent')) {
    function marksheet_percent($full_marks, $obtained_marks)
    {
        return number_format(($obtained_marks / $full_marks) * 100, 2);
    }
}