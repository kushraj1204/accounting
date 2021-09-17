<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('student_session_fee_model');
        $this->datechooser = $this->setting_model->getDatechooser();
    }

    function searchtransaction() {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'transaction/searchtransaction');
        $data['title'] = 'Search Expense';
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $search = $this->input->post('search');
            if ($search == "search_filter") {
                $data['exp_title'] = 'Transaction From ' . $this->input->post('date_from') . " To " . $this->input->post('date_to');
                $date_from = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_from')));
                $date_to = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_to')));
                $expenseList = $this->expense_model->search("", $date_from, $date_to);
                $incomeList = $this->income_model->search("", $date_from, $date_to);
                $feeList = $this->studentfeemaster_model->getFeeBetweenDate($date_from, $date_to);
                $data['expenseList'] = $expenseList;
                $data['incomeList'] = $incomeList;
                $data['feeList'] = $feeList;
            }
        }
        $this->load->view('layout/header', $data);
        if($this->datechooser == 'bs') {
            $data['exp_title'] = 'Transaction From ' . $this->input->post('date_from_bs') . " To " . $this->input->post('date_to_bs');
            $this->load->view('admin/transaction/searchtransaction_bs', $data);
        } else {
            $this->load->view('admin/transaction/searchtransaction', $data);
        }
        $this->load->view('layout/footer', $data);
    }

    function studentacademicreport_OLD() {
        if (!$this->rbac->hasPrivilege('balance_fees_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'transaction/studentacademicreport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/studentAcademicReport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/studentAcademicReport', $data);
                $this->load->view('layout/footer', $data);
            } else {
                $class_id = $this->input->post('class_id');
                $section_id = $this->input->post('section_id');
                $feetype = $this->input->post('feetype');
                $feetype_arr = $this->input->post('feetype_arr');
                $student_Array = array();
                $studentlist = $this->student_model->searchByClassSection($class_id, $section_id);
                if (!empty($studentlist)) {
                    foreach ($studentlist as $key => $eachstudent) {
                        $obj = new stdClass();
                        $obj->name = $eachstudent['firstname'] . " " . $eachstudent['lastname'];
                        $obj->class = $eachstudent['class'];
                        $obj->section = $eachstudent['section'];
                        $obj->admission_no = $eachstudent['admission_no'];
                        $obj->roll_no = $eachstudent['roll_no'];
                        $obj->father_name = $eachstudent['father_name'];
                        $student_session_id = $eachstudent['student_session_id'];
                        //$student_total_fees = $this->studentfeemaster_model->getStudentFees($student_session_id);
                        $student_total_fees = $this->student_session_fee_model->getOverallFees($student_session_id);
                        if (!empty($student_total_fees)) {


                            $totalfee = $student_total_fees['payable_amount'];
                            $deposit = $student_total_fees['paid_amount'] - $student_total_fees['fine_amount'];
                            $discount = $student_total_fees['total_discount'];
                            $balance = 0;
                            $fine = $student_total_fees['fine_amount'];
                            /*foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {


                                if (!empty($student_total_fees_value->fees)) {
                                    foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {
                                        $totalfee = $totalfee + $each_fee_value->amount;

                                        $amount_detail = json_decode($each_fee_value->amount_detail);
                                        // echo "<pre/>";
                                        // var_dump($amount_detail);
// print_r($amount_detail);


                                        if (is_object($amount_detail)) {
                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                $deposit = $deposit + $amount_detail_value->amount;
                                                $fine = $fine + $amount_detail_value->amount_fine;
                                                $discount = $discount + $amount_detail_value->amount_discount;
                                            }
                                        }
                                    }
                                }
                            }*/
                            $obj->totalfee = $totalfee;
                            $obj->payment_mode = "N/A";
                            $obj->deposit = $deposit;
                            $obj->fine = $fine;
                            $obj->discount = $discount;
                            $obj->balance = ($totalfee) - ($deposit);
                        } else {

                            $obj->totalfee = "N/A";
                            $obj->payment_mode = "N/A";
                            $obj->deposit = "N/A";
                            $obj->fine = "N/A";
                            $obj->balance = "N/A";
                            $obj->discount = "N/A";
                        }
                        $student_Array[] = $obj;
                    }
                }


                $data['student_due_fee'] = $student_Array;

                // exit();
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                $data['feetype'] = $feetype;
                $data['feetype_arr'] = $feetype_arr;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/transaction/studentAcademicReport', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function studentacademicreport() {
        if (!$this->rbac->hasPrivilege('balance_fees_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'transaction/studentacademicreport');
        $data['title'] = 'student fee';
        $data['title'] = 'student fee';
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/studentAcademicReport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_due_fee = $this->student_session_fee_model->getStudentDueFeesByClassSection($class_id, $section_id);
            foreach ($student_due_fee as &$sf) {
                $sf['fine'] = $this->student_session_fee_model->calculateFine($sf);
                $sf['_total_fee_'] = $sf['total_fee'] + $sf['prev_due_amount'];
                $sf['_balance_'] = ($sf['_total_fee_'] + $sf['fine']) - (($sf['paid_amount'] - $sf['fine_amount']) + $sf['total_discount']);
            }
            $data['student_due_fee'] = $student_due_fee;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transaction/studentAcademicReport_new', $data);
            $this->load->view('layout/footer', $data);
        }
    }

}