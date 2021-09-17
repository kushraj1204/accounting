<?php

class Student_fee_advance_model extends CI_model
{
    private $tblname = "student_fee_advance";

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAdvance($data, $replace = false)
    {
        if (!is_array($data) || !isset($data['student_id'])) {
            return false;
        }
        if (!$replace) {
            //previous advance
            $prev_advance = $this->getAdvance($data['student_id']);
            if (!empty($prev_advance)) {
                $added_advance_amount = $data['advance_amount'];
                $prev_advance_amount = $prev_advance->advance_amount;
                $data['advance_amount'] = $prev_advance_amount + $added_advance_amount;
                if (isset($data['extra_data'])) {
                    $extra_data = json_decode($data['extra_data'], true);
                } else {
                    $extra_data = array();
                }
                $extra_data['previous_advance_amount'] = $prev_advance_amount;
                $extra_data['added_advance_amount'] = $added_advance_amount;
                $data['extra_data'] = json_encode($extra_data);
            }
        }
        $data['added_date'] = $this->customlib->getCurrentTime('Y-m-d H:i:s');
        if (!isset($data['advance_date'])) {
            $data['advance_date'] = $data['added_date'];
        }
        $r = $this->db->insert($this->tblname, $data);
        if ($r) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function getAdvance($student_id)
    {
        $this->db->select('*');
        $this->db->from($this->tblname);
        $this->db->where('student_id', $student_id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->row();
    }
}