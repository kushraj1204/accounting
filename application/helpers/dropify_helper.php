<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('dropify_image')) {
    function dropify_image($default_value = false, $max_size = '500K')
    {
        $options = array(
            'data-max-file-size="' . $max_size . '"',
            'data-max-file-size-preview="' . $max_size . '"',
            'data-allowed-file-extensions="png jpg jpeg bmp"',
        );
        if ($default_value && is_file(FCPATH . '/' . $default_value)) {
            if (!filter_var($default_value, FILTER_VALIDATE_URL)) {
                $default_value = base_url($default_value);
            }
            $options[] = 'data-default-file="' . $default_value . '"';
        }
        return ' ' . implode(' ', $options) . ' ';
    }
}

if (!function_exists('dropify_file')) {
    function dropify_file($default_value = false, $max_size = '3M')
    {
        $options = array(
            'data-max-file-size="' . $max_size . '"',
            'data-max-file-size-preview="' . $max_size . '"',
            'data-allowed-file-extensions="pdf doc docx txt xls xlsx jpg jpeg png bmp csv"',
        );
        if ($default_value && is_file(FCPATH . '/' . $default_value)) {
            if (!filter_var($default_value, FILTER_VALIDATE_URL)) {
                $default_value = base_url($default_value);
            }
            $options[] = 'data-default-file="' . $default_value . '"';
        }
        return ' ' . implode(' ', $options) . ' ';
    }
}