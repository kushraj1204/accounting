<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    protected $CI;

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }

    public function edit_unique($value, $params)
    {
        $this->CI->load->database();
        list($table, $field, $current_id) = explode(".", $params);

        $query = $this->CI->db->select('id')->from($table)->where($field, $value)->limit(1)->get();

        if ($query->row() && $query->row()->id != $current_id) {
            $this->CI->form_validation->set_message('edit_unique', "{field} field must contain a unique value");
            return false;
        }
        return true;
    }

    public function valid_datetime($datetime)
    {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        return $d && $d->format('Y-m-d H:i:s') === $datetime;
    }
}