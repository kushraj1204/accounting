<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feegenerator extends Admin_Controller
{
    private $bs_months;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('hostelroom_model');
        $this->load->library('bikram_sambat');
        $this->bs_months = array(
            'Baisakh' => 1,
            'Jestha' => 2,
            'Asar' => 3,
            'Shrawan' => 4,
            'Bhadra' => 5,
            'Ashwin' => 6,
            'Kartik' => 7,
            'Mangshir' => 8,
            'Poush' => 9,
            'Magh' => 10,
            'Falgun' => 11,
            'Chaitra' => 12
        );
    }

    public function index20191231()
    {
        if (!$this->rbac->hasPrivilege('fees_master', 'can_add')) {
            access_denied();
        }
        if ($this->input->method(true) === 'POST') {
            $gen_hostel = $this->input->post('gen_hostel');
            $gen_transport = $this->input->post('gen_transport');
            if (!empty($gen_hostel)) {
                $this->form_validation->set_rules("h_session_year", "Session year", "required|numeric|xss_clean");
                $this->form_validation->set_rules("h_amount", "Amount", "required|numeric|xss_clean");
                if (!($this->form_validation->run() === false)) {
                    $this->hostel();
                }
            } else if (!empty($gen_transport)) {
                $this->form_validation->set_rules("t_session_year", "Session year", "required|numeric|xss_clean");
                if (!($this->form_validation->run() === false)) {
                    try {
                        $this->transport();
                    } catch (Exception $e) {
                        $this->session->set_flashdata('transport_error', $e->getMessage());
                    }
                }
            }
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feegenerator');
        $data = array();
        $data['months'] = array_keys($this->bs_months);
        $data['hostel_rooms'] = $this->hostelroom_model->lists();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feegenerator/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function hostel20191231()
    {
        $months = $this->input->post('months[]');
        $rooms = $this->input->post('rooms[]');
        $amount = $this->input->post('h_amount');
        $due_day = $this->input->post('due_day');
        $session_year = $this->input->post('h_session_year');
        //generate fee group
        if (count($months) > 0 && count($rooms) > 0) {
            $fee_master = array();
            $current_session = (int)$this->setting_model->getCurrentSession();
            $data = array();
            foreach ($rooms as $room) {
                foreach ($months as $month) {
                    $data[] = array(
                        'name' => $room . ' ' . $month . ' fee',
                        'description' => 'Auto generated',
                        'bs_month' => $this->bs_months[$month],
                    );
                }
            }
            $total_fee_groups = count($data);
            if ($total_fee_groups > 0) {
                //fee groups
                $this->db->insert_batch('fee_groups', $data);
                $first_fee_groups_id = $this->db->insert_id();
                $last_fee_groups_id = $first_fee_groups_id + $total_fee_groups - 1;

                //fee_session_groups
                $fee_session_groups = array();
                for ($i = $first_fee_groups_id; $i <= $last_fee_groups_id; $i++) {
                    $fee_session_groups[] = array(
                        'fee_groups_id' => $i,
                        'session_id' => $current_session
                    );
                }
                $total_fee_session_groups = count($fee_session_groups);
                $this->db->insert_batch('fee_session_groups', $fee_session_groups);
                $first_fee_session_group_id = $this->db->insert_id();
                $last_fee_session_group_id = $first_fee_session_group_id + $total_fee_session_groups - 1;

                //get fee type
                $this->db->select('id')
                    ->from('feetype')
                    ->where('code', 'HOSTEL_FEE');
                $query = $this->db->get();
                $feetype_result = $query->row();
                if (empty($feetype_result)) {
                    //generate fee type
                    $this->db->insert('feetype', array(
                        'type' => 'Hostel fee',
                        'code' => 'HOSTEL_FEE',
                        'description' => 'Auto Generated'
                    ));
                    $feetype_id = $this->db->insert_id();
                } else {
                    $feetype_id = $feetype_result->id;
                }

                //get fee_groups for bs_month
                $this->db->select('id,bs_month')
                    ->from('fee_groups');
                $query = $this->db->get();
                $fee_groups_result = $query->result_array();
                $fee_groups_month_map = array();
                foreach ($fee_groups_result as $r) {
                    $fee_groups_month_map[$r['id']] = $r['bs_month'];
                }

                //get fee_session_groups
                $this->db->select('id fee_session_group_id,fee_groups_id')
                    ->from('fee_session_groups fsg')
                    ->where('fsg.session_id', $current_session)
                    ->where('fsg.id >=', $first_fee_session_group_id)
                    ->where('fsg.id <=', $last_fee_session_group_id);
                $query = $this->db->get();
                $fee_session_groups_result = $query->result_array();
                foreach ($fee_session_groups_result as $r) {
                    $due_date_bs = $session_year . '-' . $fee_groups_month_map[$r['fee_groups_id']] . '-' . $due_day;
                    try {
                        $this->bikram_sambat->setNepaliDate($session_year, $fee_groups_month_map[$r['fee_groups_id']], $due_day);
                        $due_date = $this->bikram_sambat->toEnglishString();
                    } catch (Exception $e) {
                        $due_date = '';
                    }
                    $fee_master[] = array(
                        'fee_session_group_id' => $r['fee_session_group_id'],
                        'fee_groups_id' => $r['fee_groups_id'],
                        'feetype_id' => $feetype_id,
                        'session_id' => $current_session,
                        'due_date' => $due_date,
                        'due_date_bs' => $due_date_bs,
                        'due_day_bs' => $due_day,
                        'due_month_bs' => $fee_groups_month_map[$r['fee_groups_id']],
                        'due_year_bs' => $session_year,
                        'amount' => $amount
                    );
                }

                //fee_groups_feetype
                if (count($fee_master) > 0) {
                    $this->db->insert_batch('fee_groups_feetype', $fee_master);
                }
            }
        }
        redirect('admin/feemaster');
    }

    public function transport20191231()
    {
        $months = $this->input->post('months[]');
        $stops = $this->input->post('stop[]');
        $amounts = $this->input->post('amount[]');
        $due_day = $this->input->post('due_day');
        $session_year = $this->input->post('t_session_year');
        $stops = array_filter($stops, function ($s) {
            return strlen($s) > 0;
        });
        if (count($months) == 0) {
            throw new Exception("Please choose some months");
        }
        if (count($stops) == 0) {
            throw new Exception("Please provide some stops");
        }
        if (count($months) > 0 && count($stops) > 0) {
            $fee_master = array();
            $current_session = (int)$this->setting_model->getCurrentSession();
            //generate fee group
            $data = array();
            $stop_fees = array();
            foreach ($stops as $ind => $stop) {
                if (empty($stop)) {
                    continue;
                }
                foreach ($months as $month) {
                    $data[] = array(
                        'name' => 'Transport ' . $stop . ' ' . $month . ' fee',
                        'description' => 'Auto generated',
                        'bs_month' => $this->bs_months[$month],
                    );
                    $stop_fees[] = $amounts[$ind];
                }
            }
            $total_fee_groups = count($data);
            if ($total_fee_groups > 0) {
                $this->db->trans_begin();
                //fee_groups
                $this->db->insert_batch('fee_groups', $data);
                $first_fee_groups_id = $this->db->insert_id();
                $last_fee_groups_id = $first_fee_groups_id + $total_fee_groups - 1;

                //fee_session_groups
                $fee_session_groups = array();
                for ($i = $first_fee_groups_id; $i <= $last_fee_groups_id; $i++) {
                    $fee_session_groups[] = array(
                        'fee_groups_id' => $i,
                        'session_id' => $current_session
                    );

                }
                $total_fee_session_groups = count($fee_session_groups);
                $this->db->insert_batch('fee_session_groups', $fee_session_groups);
                $first_fee_session_group_id = $this->db->insert_id();
                $last_fee_session_group_id = $first_fee_session_group_id + $total_fee_session_groups - 1;

                //get fee type
                $this->db->select('id')
                    ->from('feetype')
                    ->where('code', 'TRANSPORT_FEE');
                $query = $this->db->get();
                $feetype_result = $query->row();
                if (empty($feetype_result)) {
                    //generate fee type
                    $this->db->insert('feetype', array(
                        'type' => 'Transportation fee',
                        'code' => 'TRANSPORT_FEE',
                        'description' => 'Auto Generated'
                    ));
                    $feetype_id = $this->db->insert_id();
                } else {
                    $feetype_id = $feetype_result->id;
                }

                //get fee_groups for bs_month
                $this->db->select('id,bs_month')
                    ->from('fee_groups')
                    ->where('id >=', $first_fee_groups_id)
                    ->where('id <=', $last_fee_groups_id);
                $query = $this->db->get();
                $fee_groups_result = $query->result_array();
                $fee_groups_month_map = array();
                foreach ($fee_groups_result as $r) {
                    $fee_groups_month_map[$r['id']] = $r['bs_month'];
                }

                //get fee_session_groups
                $this->db->select('id fee_session_group_id,fee_groups_id')
                    ->from('fee_session_groups fsg')
                    ->where('fsg.session_id', $current_session)
                    ->where('fsg.id >=', $first_fee_session_group_id)
                    ->where('fsg.id <=', $last_fee_session_group_id)
                    ->order_by('fsg.id', 'ASC');
                $query = $this->db->get();
                $fee_session_groups_result = $query->result_array();
                $i = 0;
                if (count($fee_session_groups_result) != count($stop_fees)) {
                    $this->db->trans_rollback();
                    throw new Exception("Stop/Amount mismatch");
                }
                foreach ($fee_session_groups_result as $r) {
                    $due_date_bs = $session_year . '-' . $fee_groups_month_map[$r['fee_groups_id']] . '-' . $due_day;
                    try {
                        $this->bikram_sambat->setNepaliDate($session_year, $fee_groups_month_map[$r['fee_groups_id']], $due_day);
                        $due_date = $this->bikram_sambat->toEnglishString();
                    } catch (Exception $e) {
                        $due_date = '';
                    }
                    $fee_master[] = array(
                        'fee_session_group_id' => $r['fee_session_group_id'],
                        'fee_groups_id' => $r['fee_groups_id'],
                        'feetype_id' => $feetype_id,
                        'session_id' => $current_session,
                        'due_date' => $due_date,
                        'due_date_bs' => $due_date_bs,
						'amount' => $stop_fees[$i],
                        'due_day_bs' => $due_day,
                        'due_month_bs' => $fee_groups_month_map[$r['fee_groups_id']],
                        'due_year_bs' => $session_year,
                    );
                    $i++;
                }

                //fee_groups_feetype
                if (count($fee_master) > 0) {
                    $this->db->insert_batch('fee_groups_feetype', $fee_master);
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
            }
        }
        redirect('admin/feemaster');
    }
}