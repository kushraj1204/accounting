<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends Account_Controller
{

    function __construct()
    {

        parent::__construct();
        $this->response = array('status' => 'failure', 'data' => '');
        $this->load->model('account/personnel_model');
        $this->load->model('account/journal_model');
        $this->load->model('account/payment_model');
        $this->load->model('account/account_COA_model');
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->currency = $this->customlib->getSchoolCurrencyFormat();
    }

    public function paymentList()
    {

        $postData = $this->input->post();

        $data = $this->payment_model->getPaymentList($postData);

        echo json_encode($data);
    }

    function editPayment($id)
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_edit')) {
            echo json_encode($this->response);
            exit;
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/payment');
        $this->data['allow'] = true;

        $payment = $this->payment_model->getPayment($id);

        if (!isset($payment)) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('payment_doesnt_exist'), 'type' => 'error'));
            redirect("account/payment");
        }
        $concernedjournal_ids = array_unique(explode(',', $payment->journalids));
        $nextPayments = $this->payment_model->checkNextPayment($id, $payment->paid_to, $concernedjournal_ids);

        if (isset($nextPayments[0])) {
            //cant be edited
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('payment_cant_be_edited'), 'type' => 'error'));
            $this->data['allow'] = false;
        }

        $payment->nettotal = $payment->total;

        $this->data['payment'] = $payment;
        $this->data['banks'] = $this->account_COA_model->getBanksList();
        if (!isset($this->data['allow'])) {
            $this->data['allow'] = $this->accountlib->checkEditPermission($payment->created_date, $payment->financial_year, 'allow_payment_edit');
        }
        $paidJournalList = $this->journal_model->getJournalDetailsForPayment($id);

        $temp = array('max_val' => 0, 'minpayable' => 0, 'total_sum' => 0);
        foreach ($paidJournalList as $journal) {
            $journal->payableamount = $journal->amount - $journal->partialpaidamount;
            if ($this->datechooser == 'bs') {
                $journal->due_date = $journal->due_date_bs;
            }
            $temp['sum'] = $temp['sum'] + (float)$journal->payableamount;
            if ((float)$journal->payableamount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$journal->amount;
            }

            $pastpaymentids = explode(',', $journal->pastpaymentids);
            $pastpaymentcodes = explode(',', $journal->pastpaymentcodes);
            $tempstring = '';
            foreach ($pastpaymentcodes as $key => $pastpaymentcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/payment/editPayment/' . $pastpaymentids[$key] . '">' . $pastpaymentcode . '</a>';
                $tempstring .= '</span>';
            }
            $journal->pastpayments = $tempstring;

        }
        $temp['minpayable'] = $temp['sum'] - $temp['max_val'];
        $journalentries = $this->journal_model->getJournalEntriesForPayment($id);
        foreach ($journalentries as $eachjournalentry) {
            if ($eachjournalentry->quantity) {
                $eachjournalentry->rate = $eachjournalentry->amount / $eachjournalentry->quantity;
            }
            if ($eachjournalentry->amount_type == 'debit') {
                $eachjournalentry->debit = $eachjournalentry->amount;
                $eachjournalentry->credit = '0';
            } else {
                $eachjournalentry->credit = $eachjournalentry->amount;
                $eachjournalentry->debit = '0';
            }
        }
        $this->data['minpayable'] = $temp['minpayable'];
        unset($temp);
        $this->data['relatedjournal'] = $paidJournalList;
        $this->data['relatedjournaldetails'] = $journalentries;
        $this->load->view('layout/header');
        $this->load->view('account/payment/add', $this->data);
        $this->load->view('layout/footer');
    }


    function viewPayment($id)
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_edit')) {
            echo json_encode($this->response);
            exit;
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/payment');
        $this->data['allow'] = true;

        $payment = $this->payment_model->getPayment($id);

        if (!isset($payment)) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('payment_doesnt_exist'), 'type' => 'error'));
            redirect("account/payment");
        }
//        $concernedjournal_ids=array_unique(explode(',', $payment->journalids));
//        $nextPayments = $this->payment_model->checkNextPayment($id, $payment->paid_to,$concernedjournal_ids);

        $payment->nettotal = $payment->total;

        $this->data['payment'] = $payment;
        $this->data['banks'] = $this->account_COA_model->getBanksList();
        if (!isset($this->data['allow'])) {
            $this->data['allow'] = $this->accountlib->checkEditPermission($payment->created_date, $payment->financial_year, 'allow_payment_edit');
        }
        $paidJournalList = $this->journal_model->getJournalDetailsForPayment($id);

        $temp = array('max_val' => 0, 'minpayable' => 0, 'total_sum' => 0);
        foreach ($paidJournalList as $journal) {
            $journal->payableamount = $journal->amount - $journal->partialpaidamount;
            if ($this->datechooser == 'bs') {
                $journal->due_date = $journal->due_date_bs;
            }
            $temp['sum'] = $temp['sum'] + (float)$journal->payableamount;
            if ((float)$journal->payableamount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$journal->amount;
            }

            $pastpaymentids = explode(',', $journal->pastpaymentids);
            $pastpaymentcodes = explode(',', $journal->pastpaymentcodes);
            $tempstring = '';
            foreach ($pastpaymentcodes as $key => $pastpaymentcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/payment/viewPayment/' . $pastpaymentids[$key] . '">' . $pastpaymentcode . '</a>';
                $tempstring .= '</span>';
            }
            $journal->pastpayments = $tempstring;

        }
        $temp['minpayable'] = $temp['sum'] - $temp['max_val'];
        $journalentries = $this->journal_model->getJournalEntriesForPayment($id);
        foreach ($journalentries as $eachjournalentry) {
            if ($eachjournalentry->quantity) {
                $eachjournalentry->rate = $eachjournalentry->amount / $eachjournalentry->quantity;
            }
            if ($eachjournalentry->amount_type == 'debit') {
                $eachjournalentry->debit = $eachjournalentry->amount;
                $eachjournalentry->credit = '0';
            } else {
                $eachjournalentry->credit = $eachjournalentry->amount;
                $eachjournalentry->debit = '0';
            }
        }
        $this->data['minpayable'] = $temp['minpayable'];
        unset($temp);
        $this->data['relatedjournal'] = $paidJournalList;
        $this->data['relatedjournaldetails'] = $journalentries;
        $this->load->view('layout/header');
        $this->load->view('account/payment/view', $this->data);
        $this->load->view('layout/footer');
    }

    function index()
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_view')) {
            echo json_encode($this->response);
            exit;
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/payment');
//        $this->data['payments'] = $this->payment_model->getAllPayments();
        $this->data['payments'] = [];

        $this->load->view('layout/header');
        $this->load->view('account/payment/list', $this->data);
        $this->load->view('layout/footer');
    }

    function add_payment()
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_add')) {
            echo json_encode($this->response);
            exit;
        }
//        $cash = $this->account_COA_model->getCashItem();
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/payment');
        $payment_code = $this->getNextPaymentCode();
        $this->data['allow'] = 1;
        $this->data['payment_no'] = $payment_code;
        $this->data['suppliers'] = $this->personnel_model->getAllPersonnelByType('supplier');
        $this->data['banks'] = $this->account_COA_model->getBanksList();
        if (sizeof($this->data['banks']) == 0) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('cash_bank_not_created'), 'type' => 'error'));
            redirect("account/payment");
        }
        $this->load->view('layout/header');
        $this->load->view('account/payment/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function getNextPaymentCode()
    {
        $settings = $this->accountlib->getAccountSetting();
        $payment_prefix = '';
        if ($settings->use_general_payment_prefix) {
            $payment_prefix = $settings->general_payment_prefix;
        }
        $lastId = $this->payment_model->getLastId();
        if (!$lastId) {
            $newId = $settings->general_payment_start;
        } else {
            if ($settings->use_general_payment_prefix) {
                $lastId = trim($lastId, $payment_prefix);
            }
            $lastId = (int)$lastId;

            $newId = $lastId + 1;
        }

        return $payment_prefix . $newId;
    }

    public function ajax_dueJournalList()
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_add')) {
            echo json_encode($this->response);
            exit;
        }
        $input = $this->input;
        $supplier_id = $input->post('id');
        $dueJournalList = $this->journal_model->getDueJournalFor($supplier_id, 'payment');
        foreach ($dueJournalList as $duejournal) {
            if ($this->datechooser == 'bs') {
                $duejournal->due_date = $duejournal->due_date_bs;
            }
            $duejournal->payableamount = (float)$duejournal->amount - (float)$duejournal->partialpaidamount;
            $pastpaymentids = explode(',', $duejournal->pastpaymentids);
            $pastpaymentcodes = explode(',', $duejournal->pastpaymentcodes);
            $tempstring = '';
            foreach ($pastpaymentcodes as $key => $pastpaymentcode) {
                if ($tempstring) {
                    $tempstring .= '';
                }
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/payment/editPayment/' . $pastpaymentids[$key] . '">' . $pastpaymentcode . '</a>';
            }
            $duejournal->pastpayments = $tempstring;
        }

        $this->response['data'] = $dueJournalList;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;


    }


    public function ajax_JournalEntryList()
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_add')) {
            echo json_encode($this->response);
        }
        $input = $this->input;
        $ids = $input->post('ids');
        $supplierid = $input->post('supplierid');
        if (!isset($ids)) {
            $this->response['status'] = 'success';
            echo json_encode($this->response);
            exit;
        }
        $dueJournalEntryList = $this->journal_model->getDueJournalEntries($ids, $supplierid);
        $total = 0;

        foreach ($dueJournalEntryList as $eachjournalentry) {
            if ($eachjournalentry->quantity) {
                $eachjournalentry->rate = $eachjournalentry->amount / $eachjournalentry->quantity;
            }
            if ($eachjournalentry->personnel_id == $supplierid) {
                if ($eachjournalentry->amount_type == 'credit') {
                    $total = $total + $eachjournalentry->amount;
                }
            }
            if ($eachjournalentry->amount_type == 'debit') {
                $eachjournalentry->debit = $eachjournalentry->amount;
                $eachjournalentry->credit = '0';
            } else {
                $eachjournalentry->credit = $eachjournalentry->amount;
                $eachjournalentry->debit = '0';
            }
        }
        $this->response['total'] = $total;
        $this->response['data'] = $dueJournalEntryList;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;

    }


    public function save_payment()
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/payment');
        $input = $this->input;
        $id = $input->post('id');
        //make advance non editable okay
        if ($id) {
            $this->updatePayment();
        } else {

            $sendEmail = $input->post('send_mail');
            $sendEmail = isset($sendEmail) ? 1 : 0;
            $this->form_validation->set_rules('payment_no', 'Payment No', 'required');
            if ($this->datechooser == 'bs') {
                $this->form_validation->set_rules('payment_date_bs', $this->lang->line('payment_date'), 'trim|required|xss_clean');
            } else {
                $this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'required');
            $this->form_validation->set_rules('ref_no', $this->lang->line('ref_no'), 'required');
            $this->form_validation->set_rules('pay_to', $this->lang->line('pay_to'), 'required');
            $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'required|callback_possiblePaymentMode');
            $payment_mode = $input->post('payment_mode');
            $advance_paid = $input->post('advancePay');
            if ($payment_mode == 'cheque') {
                $this->form_validation->set_rules('cheque_no', $this->lang->line('cheque_no'), 'required');
                if ($this->datechooser == 'bs') {
                    $this->form_validation->set_rules('cheque_date_bs', $this->lang->line('cheque_date'), 'trim|required|xss_clean');
                } else {
                    $this->form_validation->set_rules('cheque_date', $this->lang->line('cheque_date'), 'trim|required|xss_clean');
                }
                $this->form_validation->set_rules('bank', $this->lang->line('bank'), 'required');
            }
//            $cash = $this->account_COA_model->getCashItem();
//            if ($payment_mode == 'cash' && !isset($cash)) {
//                $this->session->set_flashdata('msg', array('message' => "Cash asset is not created yet. ", 'type' => 'error'));
//                redirect("account/payment");
//                exit;
//            }
            $this->form_validation->set_rules('narration', $this->lang->line('narration'), 'required|xss_clean');
            $this->form_validation->set_rules('paid_amount', $this->lang->line('payment_amount'), 'required|numeric');
            $redirect = 0;
            $pay_to = $input->post('pay_to');
            $journal_ids = $input->post('journal_id');


            $duejournals = $this->journal_model->getDueJournalFor($pay_to, 'payment');
            $totalpayablesum = 0;
            $temp = array('journal_sum' => 0, 'minpayment' => 0, 'maxvalue' => 0, 'maxvalued_journal' => 0);
            foreach ($duejournals as $duejournal) {
                $totalpayablesum += (float)$duejournal->amount - (float)$duejournal->partialpaidamount;
                if (in_array($duejournal->id, $journal_ids)) {
                    $duejournal->payableamount = (float)$duejournal->amount - (float)$duejournal->partialpaidamount;
                    $temp['journal_sum'] = $temp['journal_sum'] + $duejournal->payableamount;
                    if ($duejournal->payableamount > $temp['max_value'] || $temp['max_value'] == 0) {
                        $temp['max_value'] = $duejournal->payableamount;
                        $temp['maxvalued_journal'] = $duejournal->id;
                    }
                }
            }
            $temp['minpayment'] = $temp['journal_sum'] - $temp['max_value'];


            $payment_no = $this->getNextPaymentCode();
            $payment_date = $input->post('payment_date');
            $payment_date = date('Y-m-d', $this->customlib->datetostrtotime($payment_date));
            $payment_date_bs = $input->post('payment_date_bs');
            $ref_no = $input->post('ref_no');
            $payment_mode = $input->post('payment_mode');
            $cheque_no = $input->post('cheque_no');
            $cheque_date = $input->post('cheque_date');
            $cheque_date_bs = $input->post('cheque_date_bs');
            $cheque_date = date('Y-m-d', $this->customlib->datetostrtotime($cheque_date));
            $bank = $input->post('bank');
            $bank = (int)$bank;
            $narration = $input->post('narration');
            $nettotal = $input->post('nettotal');
            $total = $nettotal;
            $paid_amount = $input->post('paid_amount');
            $bs_date = explode('-', $payment_date_bs);
            $bs_year = $bs_date[0];
            $bs_month = $bs_date[1];
            $bs_day = $bs_date[2];
            $advance_amount = 0;
            if ($totalpayablesum > $paid_amount && $advance_paid) {
                $redirect = 1;
                $flasherrormessage = 'Pending dues must be cleared before paying advance';
            }
            if (($totalpayablesum < $paid_amount) && !$advance_paid) {
                $redirect = 1;
                $flasherrormessage = $this->lang->line('date_integrity_compromised');
            }

            if ($paid_amount > $totalpayablesum) {
                $advance_amount = $paid_amount - $totalpayablesum;
            }
            if ($paid_amount <= $temp['minpayment']) {

                $redirect = 1;
                $flasherrormessage = $this->lang->line('date_integrity_compromised');
            }

            $due = 0;
            if ($paid_amount < $nettotal) {
                $due = 1;
                $dueamount = $nettotal - $paid_amount;
            }
            if (isset($journal_ids)) {
                $tempamount = $temp['journal_sum'];
                if ($tempamount != $total) {
                    $redirect = 1;
                    $flasherrormessage = $this->lang->line('date_integrity_compromised');
                }
            }
            if ($payment_mode == 'cash') {
                $asset_id = 11;
            }
            if ($payment_mode == 'cheque') {
                $asset_id = $bank;
                $payment_mode_details = json_encode(array('cheque_date' => $cheque_date, 'cheque_no' => $cheque_no, 'cheque_date_bs' => $cheque_date_bs));

            }

            if ($this->form_validation->run() == TRUE && $redirect == 0) {
                $paid_amount = $advance_amount > 0 ? $nettotal : $paid_amount;
                $data = array(
                    'payment_no' => $payment_no,
                    'payment_date' => $payment_date,
                    'payment_date_bs' => $payment_date_bs,
                    'ref_no' => $ref_no,
                    'paid_to' => $pay_to,
                    'payment_mode' => $payment_mode,
                    'asset_id' => $asset_id,
                    'payment_mode_details' => $payment_mode_details,
                    'description' => $narration . ($advance_amount > 0 ? 'along with advance of Rs.' . $advance_amount : ''),
                    'total' => $total,
                    'paid_amount' => $paid_amount,
                    'due' => $due,
                    'created_by' => $this->session->userdata['admin']['id'],
                    'created_date' => $this->customlib->getCurrentTime(),
                    'send_email' => $sendEmail,
                    'bs_year' => $bs_year,
                    'bs_month' => $bs_month,
                    'bs_day' => $bs_day,
                );

                $insertid = $this->payment_model->addPayment($data);
                if ($insertid) {
                    if ($advance_amount > 0) {
                        $this->payment_model->saveAdvancePayment($data, $advance_amount);
                    }
                    foreach ($duejournals as $duejournal) {
                        if (in_array($duejournal->id, $journal_ids)) {
                            if ($due == 1 && $temp['maxvalued_journal'] == $duejournal->id) {
                                $partial = 1;
                            }
                            $data = array('journal_id' => $duejournal->id,
                                'payment_id' => $insertid,
                                'total' => $duejournal->amount,
                                'remaining_amount' => $duejournal->payableamount,
                                'paid_amount' => ($partial) ? ($duejournal->payableamount - $dueamount) : $duejournal->payableamount,
                                'status' => ($partial) ? 0 : 1,
                            );
                            $this->payment_model->insertPaymentDetails($data);
                        }
                    }
                    $currentDateTime = $this->customlib->getCurrentTime();
                    $multiplier = -1;//cause payment
                    $cashid = 11;
//                    $parent_id, $parent_type, $currentDateTime, $newData
                    $insertData[0] = array(
                        'parent_id' => $insertid,
                        'parent_type' => 'payment',
                        'category_id' => $pay_to,
                        'category_type' => 'supplier',
                        'status' => 1,
                        'amount' => $multiplier * ($paid_amount),
                        'amount_type' => 'debit',
                        'created_date' => $currentDateTime,
                        'created_by' => $this->session->userdata['admin']['id'],
                        'financial_year' => $this->financial_year,
                    );
                    $insertData[1] = array(
                        'parent_id' => $insertid,
                        'parent_type' => 'payment',
                        'category_id' => $asset_id,
                        'category_type' => 'coa',
                        'status' => 1,
                        'amount' => $multiplier * ($paid_amount),
                        'amount_type' => 'credit',
                        'created_date' => $currentDateTime,
                        'created_by' => $this->session->userdata['admin']['id'],
                        'financial_year' => $this->financial_year,
                    );
                    if (count($insertData)) {
                        $this->transaction_model->updateLogs($insertid, 'payment', $currentDateTime, $insertData);
                    }

                }


                if (isset($journal_ids)) {
                    $this->journal_model->markAsCleared($journal_ids);
                    if ($due == 1) {
                        $this->journal_model->markAsPartiallyPaid($temp['maxvalued_journal']);
                    }
                }
                if ($sendEmail) {
                    //send email
                }
                $this->session->set_flashdata('msg', array('message' => $this->lang->line('payment_saved'), 'type' => 'success'));
                redirect("account/payment");
            } else {

                //redirect
                $this->session->set_flashdata('msg', array('message' => $flasherrormessage, 'type' => 'error'));

                $payment_code = $this->getNextPaymentCode();
                $this->data['payment_no'] = $payment_code;
                $this->data['suppliers'] = $this->personnel_model->getAllPersonnelByType('supplier');
                $this->data['banks'] = $this->account_COA_model->getBanksList();
                $this->load->view('layout/header');
                $this->load->view('account/payment/add', $this->data);
                $this->load->view('layout/footer');
            }
        }

    }

    public function possiblePaymentMode($str)
    {
        $possible_values = array('cash', 'cheque');
        if (in_array($str, $possible_values)) {
            return true;
        }
        return false;
    }

    function updatePayment()
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_edit')) {
            access_denied();
        }
        $input = $this->input;
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/payment');
        $id = $input->post('id');
        $payment = $this->payment_model->getPayment($id);
        $redirect = 0;
        $concernedjournal_ids = array_unique(explode(',', $payment->journalids));
        $nextPayments = $this->payment_model->checkNextPayment($id, $payment->paid_to, $concernedjournal_ids);
        if (isset($nextPayments[0])) {
            //cant be edited
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('payment_cant_be_edited'), 'type' => 'error'));

            $redirect = 1;
        }
        $sendEmail = $input->post('send_mail');
        $sendEmail = isset($sendEmail) ? 1 : 0;
        $payment_mode = $input->post('payment_mode');
        if ($this->datechooser == 'bs') {
            $this->form_validation->set_rules('payment_date_bs', $this->lang->line('payment_date'), 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('ref_no', $this->lang->line('ref_no'), 'required');
        $this->form_validation->set_rules('pay_to', $this->lang->line('pay_to'), 'required');
        $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'required|callback_possiblePaymentMode');
        if ($payment_mode == 'cheque') {
            $this->form_validation->set_rules('cheque_no', $this->lang->line('cheque_no'), 'required');
            if ($this->datechooser == 'bs') {
                $this->form_validation->set_rules('cheque_date_bs', $this->lang->line('cheque_date'), 'trim|required|xss_clean');
            } else {
                $this->form_validation->set_rules('cheque_date', $this->lang->line('cheque_date'), 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('bank', $this->lang->line('bank'), 'required');
        }
//        $cash = $this->account_COA_model->getCashItem();
//        if ($payment_mode == 'cash' && !isset($cash)) {
//            $this->session->set_flashdata('msg', array('message' => "Cash asset is not created yet. ", 'type' => 'error'));
//            redirect("account/payment");
//            exit;
//        }
        $this->form_validation->set_rules('narration', $this->lang->line('narration'), 'required|xss_clean');
        $this->form_validation->set_rules('paid_amount', $this->lang->line('payment_amount'), 'required|numeric');
        $nettotal = $input->post('nettotal');
        $temppaymenttotal = $payment->total;
        if ($temppaymenttotal != $nettotal) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('date_integrity_compromised'), 'type' => 'error'));
            $redirect = 1;
        }
        $paid_amount = (float)$input->post('paid_amount');

        $paidJournalList = $this->journal_model->getJournalDetailsForPayment($id);
        $temp = array('max_val' => 0, 'minpayable' => 0, 'total_sum' => 0);
        foreach ($paidJournalList as $journal) {
            $journal->payableamount = $journal->amount - $journal->partialpaidamount;
            $temp['sum'] = $temp['sum'] + (float)$journal->payableamount;
            if ((float)$journal->payableamount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$journal->payableamount;
                $temp['maxvalued_journal'] = $journal->id;
            }
        }
        $temp['minpayable'] = $temp['sum'] - $temp['max_val'];
        if ($paid_amount < $temp['minpayable']) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('date_integrity_compromised'), 'type' => 'error'));
            $redirect = 1;
        }
        if ($this->form_validation->run() == TRUE && $redirect == 0) {
            $id = $input->post('id');
            $payment_date = $input->post('payment_date');
            $payment_date_bs = $input->post('payment_date_bs');
            $payment_date = date('Y-m-d', $this->customlib->datetostrtotime($payment_date));
            $ref_no = $input->post('ref_no');
            $payment_mode = $input->post('payment_mode');
            $cheque_no = $input->post('cheque_no');
            $cheque_date = $input->post('cheque_date');
            $cheque_date_bs = $input->post('cheque_date_bs');
            $cheque_date = date('Y-m-d', $this->customlib->datetostrtotime($cheque_date));
            $bank = $input->post('bank');
            $narration = $input->post('narration');
            $due = 0;
            $bs_date = explode('-', $payment_date_bs);
            $bs_year = $bs_date[0];
            $bs_month = $bs_date[1];
            $bs_day = $bs_date[2];

            if ($paid_amount < $nettotal) {
                $due = 1;
                $dueamount = $nettotal - $paid_amount;
            }
            if ($payment_mode == 'cash') {
                $asset_id = 11;
            }
            if ($payment_mode == 'cheque') {
                $asset_id = $bank;
                $payment_mode_details = json_encode(array('cheque_date' => $cheque_date, 'cheque_no' => $cheque_no, 'cheque_date_bs' => $cheque_date_bs));
            }
            $data = array(
                'id' => $id,
                'payment_date' => $payment_date,
                'payment_date_bs' => $payment_date_bs,
                'ref_no' => $ref_no,
                'payment_mode' => $payment_mode,
                'asset_id' => $asset_id,
                'payment_mode_details' => $payment_mode_details,
                'description' => $narration,
                'paid_amount' => $paid_amount,
                'due' => $due,
                'modified_by' => $this->session->userdata['admin']['id'],
                'modified_date' => $this->customlib->getCurrentTime(),
                'send_email' => $sendEmail,
                'bs_year' => $bs_year,
                'bs_month' => $bs_month,
                'bs_day' => $bs_day,
            );
            $this->payment_model->updatePayment($data);

            if ((int)$payment->due != $due) {

                $paymentdetail = $this->payment_model->getPaymentDetail($id, $temp['maxvalued_journal']);
                //update the entry to be done
                $partial = 0;
                if ($due == 1) {
                    $partial = 1;
                }
                $paid_amount = ($partial) ? ($paymentdetail->remaining_amount - $dueamount) : $paymentdetail->remaining_amount;
                $status = $partial ? 0 : 1;
                $data = array('id' => $paymentdetail->id, 'paid_amount' => $paid_amount, 'status' => $status);
                $this->payment_model->updatePaymentDetail($data);

                $datum = array('is_cleared' => ($due == 0) ? 1 : -1, 'id' => $temp['maxvalued_journal']);
                $this->journal_model->updateJournalStatus($datum);;
            }
            unset($datum);
            //updating logs
            $currentDateTime = $this->customlib->getCurrentTime();
            $multiplier = -1;
            $cashid = 11;
            if ($payment->paid_amount != $paid_amount || $payment->payment_mode != $payment_mode) {
                $insertData[0] = array(
                    'parent_id' => $id,
                    'parent_type' => 'payment',
                    'category_id' => $payment->paid_to,
                    'category_type' => 'supplier',
                    'status' => 1,
                    'amount' => $multiplier * ($paid_amount),
                    'amount_type' => 'debit',
                    'created_date' => $currentDateTime,
                    'created_by' => $this->session->userdata['admin']['id'],
                    'financial_year' => $this->financial_year,
                );
                $insertData[1] = array(
                    'parent_id' => $id,
                    'parent_type' => 'payment',
                    'category_id' => $asset_id,
                    'category_type' => 'coa',
                    'status' => 1,
                    'amount' => $multiplier * ($paid_amount),
                    'amount_type' => 'credit',
                    'created_date' => $currentDateTime,
                    'created_by' => $this->session->userdata['admin']['id'],
                    'financial_year' => $this->financial_year,
                );
                if (count($insertData)) {
                    $this->transaction_model->updateLogs($id, 'payment', $currentDateTime, $insertData);
                }
            }


            if ($sendEmail) {
                //
            }
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('payment_updated'), 'type' => 'success'));

            redirect("account/payment");
        } else {

            //redirect
            $this->editPayment($id);
        }


    }

    function deletePayment($id)
    {
        if (!$this->rbac->hasPrivilege('account_payments', 'can_delete')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/payment');
        $payment = $this->payment_model->getPayment($id);
        $concernedjournal_ids = array_unique(explode(',', $payment->journalids));
        $nextPayments = $this->payment_model->checkNextPayment($id, $payment->paid_to, $concernedjournal_ids);
        if (isset($nextPayments[0])) {

            $this->session->set_flashdata('msg', array('message' => $this->lang->line('payment_cant_be_deleted'), 'type' => 'error'));

            redirect("account/payment");
        } else {

            $paymentdetails = $this->payment_model->getPaymentDetails($id);
            $relatedjournalids = array();
            $relatedjournalidsstatus = array();
            foreach ($paymentdetails as $paymentdetail) {
                array_push($relatedjournalids, $paymentdetail->journal_id);
                if ((float)$paymentdetail->total == (float)$paymentdetail->remaining_amount) {
                    array_push($relatedjournalidsstatus, 0);
                } else {
                    array_push($relatedjournalidsstatus, -1);
                }
            }
            $this->payment_model->deletePayment($id);

            foreach ($relatedjournalids as $key => $relatedjournalid) {
                $data[] = array('id' => $relatedjournalid, 'is_cleared' => $relatedjournalidsstatus[$key]);
            }
            $this->journal_model->changeStatus($data);

            $cashid = 11;
            //delete logs

            $this->transaction_model->unsetEntry($id, 'payment', $payment->paid_to, 'supplier');
            $this->transaction_model->unsetEntry($id, 'payment', ($payment->payment_mode == "cheque") ? $payment->bank : $cashid, 'coa');

            redirect("account/payment");
        }
    }

}