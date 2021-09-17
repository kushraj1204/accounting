<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personnel extends Account_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('account/personnel_model');
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
        $this->financial_year = $this->session->userdata('account')['financial_year'];
    }

    function unauthorized()
    {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/list');
        $this->data['listpersonnel'] = $this->personnel_model->getAllPersonnel();
        $this->load->view('layout/header');
        $this->load->view('account/personnel/list', $this->data);
        $this->load->view('layout/footer');
    }

    public function customers()
    {
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_view')) {
            access_denied();
        }

        $type = 'customer';
        $this->data['type'] = $type;
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/customers');
        $this->data['listpersonnel'] = [];
//        echopreexit($this->data);
        foreach ($this->data['listpersonnel'] as $eachdata) {
            $multiplier = strtolower($eachdata->balance_type) == 'credit' ? -1 : 1;
            $eachdata->total = isset($eachdata->logamount) ? (($multiplier * $eachdata->balance) + $eachdata->logamount) : $multiplier * $eachdata->balance;
            $eachdata->balance = $multiplier * $eachdata->balance;
//            $eachdata->total = isset($eachdata->logamount) ? ($eachdata->balance - $eachdata->logamount) : 0 + $eachdata->balance;
        }
        $this->load->view('layout/header');
        $this->load->view('account/personnel/list', $this->data);
        $this->load->view('layout/footer');
    }

    public function personnelList()
    {

        $postData = $this->input->post();
        $data = $this->personnel_model->getPersonnelList($postData, $this->financial_year);

        echo json_encode($data);
    }

    public function suppliers()
    {
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_view')) {
            access_denied();
        }
        $type = 'supplier';
        $this->data['type'] = $type;
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/suppliers');
        $this->data['listpersonnel'] = [];
        foreach ($this->data['listpersonnel'] as $eachdata) {
            $eachdata->balance = ($eachdata->balance_type == "debit") ? $eachdata->balance * (-1) : $eachdata->balance;
            $eachdata->total = (isset($eachdata->logamount) ? $eachdata->logamount : 0) + $eachdata->balance;
        }
        $this->load->view('layout/header');
        $this->load->view('account/personnel/list', $this->data);
        $this->load->view('layout/footer');
    }

    public function add_personnel()
    {
        $this->load->helper('form');
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/list');
        $this->load->view('layout/header');
        $this->load->view('account/personnel/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function add_customer()
    {
        $this->load->helper('form');
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_add')) {
            access_denied();
        }
        $this->data['type'] = 'customer';
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/customers');
        $this->load->view('layout/header');
        $this->load->view('account/personnel/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function add_supplier()
    {
        $this->load->helper('form');
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_add')) {
            access_denied();
        }
        $this->data['type'] = 'supplier';
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/suppliers');
        $this->load->view('layout/header');
        $this->load->view('account/personnel/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function edit($id)
    {
        $this->load->helper('form');
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_edit')) {
            access_denied();
        }
        $personnel = $this->personnel_model->getPersonnelDetail($id);
        $this->data['type'] = $personnel->type;
        $this->data['personnel'] = $personnel;
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/' . $personnel->type . 's');
        $this->load->view('layout/header');
        $this->load->view('account/personnel/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function save_personnel()
    {
        $this->load->helper('form');
        $input = $this->input;
        $id = $input->post('id', 0);
        $is_unique = '|is_unique[acc_personnel.code]';
        $type = $input->post('type', 'customer');
        $this->data['type'] = $type;
        if ($id == 0) {
            if (!$this->rbac->hasPrivilege('account_personnel', 'can_add')) {
                access_denied();
            }
        } else {
            if (!$this->rbac->hasPrivilege('account_personnel', 'can_edit')) {
                access_denied();
            }
            $query = $this->db->get_where('acc_personnel', array('id' => $id));
            $original_value = $query->row();
            if ($input->post('code') == $original_value->code) {
                $is_unique = '';
            }
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/' . $type . 's');

        $email = $input->post('email', '');
        $this->form_validation->set_rules('type', $this->lang->line('type'), 'required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        if ($email != '') {
            $this->form_validation->set_rules('email', $this->lang->line('email'), 'valid_email');
        }
        $this->form_validation->set_rules('contact', $this->lang->line('contact'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required' . $is_unique);
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'required');
        $this->form_validation->set_rules('balance', $this->lang->line('balance'), 'required|greater_than_equal_to[0]');
        //$this->form_validation->set_rules('pan', $this->lang->line('vat/pan'), 'required');
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'required');
        if ($this->form_validation->run() == TRUE) {
            $name = $input->post('name', '');
            $contact = $input->post('contact', '');
            $code = $input->post('code', '');
            $address = $input->post('address', '');
            $balance = $input->post('balance', '');
            $balance_type = $input->post('balance_type', '');
            $pan = $input->post('pan', '');
            $description = $input->post('description', '');
            $category = $input->post('category', '');

            $formValues = array(
                'type' => $type,
                'name' => $name,
                'email' => $email,
                'contact' => $contact,
                'code' => $code,
                'address' => $address,
                'balance' => $balance,
                'pan' => $pan,
                'balance_type' => $balance_type,
                'description' => $description,
                'category' => $category,
                'id' => $id,
            );
            if ($id == 0) {
                $formValues['published'] = 1;
                $formValues['created_by'] = $this->session->userdata['admin']['id'];
                $formValues['created_date'] = $this->customlib->getCurrentTime();
            }
            $this->personnel_model->savePersonnel($formValues);

            $msg = $this->lang->line('record_added');
            if ($id > 0) {
                $msg = $this->lang->line('record_updated');
            }
            $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'success'));
            redirect("account/personnel/" . $type . 's');
        } else {

            //$this->session->set_flashdata('error', validation_errors());
            $this->load->view('layout/header');
            $this->load->view('account/personnel/add', $this->data);
            $this->load->view('layout/footer');

        }
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_delete')) {
            access_denied();
        }
        $this->personnel_model->delete($id);
        $this->session->set_flashdata('msg', array('message' => $this->lang->line('record_deleted'), 'type' => 'success'));
        redirect("account/personnel");
    }

    public function add_personnel_form()
    {
        if (!$this->rbac->hasPrivilege('account_personnel', 'can_add')) {
            access_denied();
        }
        $this->load->view('account/personnel/form', $this->data);
    }

    function saveStudent($data)
    {
        //reshape what comes in to required fieldsets
        if ($data['id'] == 0) {
            $formValues['published'] = 1;
            $formValues['created_by'] = $this->session->userdata['admin']['id'];
            $formValues['created_date'] = $this->customlib->getCurrentTime();
        }
        $id = $this->personnel_model->savePersonnel($data);
        return true;
    }

    public function save_personnel_form()
    {
        $result = array('success' => false, 'message' => '', 'personnel' => array());
        $input = $this->input;
        $id = $input->post('id', 0);
        $is_unique = '|is_unique[acc_personnel.code]';
        $type = $input->post('type', 'customer');

        $this->form_validation->set_rules('type', $this->lang->line('type'), 'required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        $email = $input->post('email', '');
        if ($email != '') {
            $this->form_validation->set_rules('email', $this->lang->line('email'), 'valid_email');
        }
        $this->form_validation->set_rules('contact', $this->lang->line('contact'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('customer_code'), 'required' . $is_unique);
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'required');
        $this->form_validation->set_rules('balance', $this->lang->line('balance'), 'required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('pan', $this->lang->line('vat_/_pan'), 'required');
        if ($this->form_validation->run() == TRUE) {
            $name = $input->post('name', '');
            $contact = $input->post('contact', '');
            $code = $input->post('code', '');
            $address = $input->post('address', '');
            $balance = $input->post('balance', '');
            $balance_type = $input->post('balance_type', '');
            $pan = $input->post('pan', '');
            $description = $input->post('description', '');

            $formValues = array(
                'type' => $type,
                'name' => $name,
                'email' => $email,
                'contact' => $contact,
                'code' => $code,
                'address' => $address,
                'balance' => $balance,
                'pan' => $pan,
                'balance_type' => $balance_type,
                'description' => $description,
                'id' => $id,
            );
            if ($id == 0) {
                $formValues['published'] = 1;
                $formValues['created_by'] = $this->session->userdata['admin']['id'];
                $formValues['created_date'] = $this->customlib->getCurrentTime();
            }
            $id = $this->personnel_model->savePersonnel($formValues);
            $result['personnel']['name'] = $name;
            $result['personnel']['id'] = $id;
            $result['personnel']['code'] = $code;
            $result['message'] = 'Record Added Successfully';
            $result['success'] = true;
        } else {
            $data = array(
                'name' => form_error('name'),
                'email' => form_error('email'),
                'contact' => form_error('contact'),
                'code' => form_error('code'),
                'address' => form_error('address'),
                'balance' => form_error('balance'),
                'balance_type' => form_error('balance_type'),
            );
            $result['message'] = $data;
        }
        echo json_encode($result);
    }


    function personnelLedgerList()
    {
        $postData = $this->input->post();
        $draw = $postData['draw'];
        $start = $postData['start'];
        $id = $postData['id'];
        $financialYear = $postData['financial_year'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value

        ## Total number of records without filtering

        $personnel = $this->personnel_model->getPersonnelDetail($id, $financialYear);
        $type = $personnel->type;
        $records = $this->personnel_model->getPersonnelLedgerDetailList($id, $type, $financialYear, '', 0, 0);
        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->personnel_model->getPersonnelLedgerDetailList($id, $type, $financialYear, $searchValue, 0, 0);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->personnel_model->getPersonnelLedgerDetailList($id, $type, $financialYear, $searchValue, $rowperpage, $start);
        $tempamt = 0;
        if ($start != 0) {
            $openingBalancerecords = $this->personnel_model->getPersonnelLedgerDetailList($id, $type, $financialYear, $searchValue, $start, 0);
            foreach ($openingBalancerecords as $key => $eachledger) {
                $eachledger->amount = (float)abs($eachledger->amount);
                if (isset($eachledger->invid)) {
                    $eachledger->debit = ($type == 'customer') ? $eachledger->amount : 0;
                    $eachledger->credit = ($type == 'supplier') ? $eachledger->amount : 0;
                }
                if (isset($eachledger->jrnid)) {
                    $eachledger->debit = ($eachledger->amount_type == 'debit') ? $eachledger->amount : 0;
                    $eachledger->credit = ($eachledger->amount_type == 'credit') ? $eachledger->amount : 0;
                }
                if (isset($eachledger->payid)) {
                    $eachledger->debit = ($type == 'supplier') ? $eachledger->amount : 0;
                    $eachledger->credit = ($type == 'customer') ? $eachledger->amount : 0;
                }
                if (isset($eachledger->recid)) {
                    $eachledger->debit = ($type == 'customer') ? 0 : $eachledger->amount;
                    $eachledger->credit = ($type == 'supplier') ? 0 : $eachledger->amount;
                }
                $eachledger->amount = (strtolower($type) == 'customer') ? ($eachledger->debit - $eachledger->credit) : ($eachledger->credit - $eachledger->debit);
                $tempamt = $tempamt + $eachledger->amount;
            }
        }

        $data = array();

        $openingbalance = $personnel->balance;
        $openingbalancetype = $personnel->balance_type;
        $openingdata = new stdClass();
        $openingdata->id = $personnel->id;
        $openingdata->relatedid = $personnel->id;
        $openingdata->relatedaccounts = "Opening Balance";
        $openingdata->created_date = '-';
        $openingdata->parent_type = "";
        $openingdata->narration = "Balance b/d";
        $openingdata->amount = $start == 0 ? $openingbalance : $tempamt;
        $openingdata->debit = strtolower($openingbalancetype) == "debit" ? $openingbalance : 0;
        $openingdata->credit = strtolower($openingbalancetype) == "credit" ? $openingbalance : 0;
        array_unshift($records, $openingdata);
//        $tempamt = 0;

        foreach ($records as $key => $eachledger) {
            $eachledger->coanameconcatinv = str_replace("Fine,", " ", $eachledger->coanameconcatinv);
            $this->data['ledgerdetail'][$key]->coanameconcatinv = str_replace("Fine,", " ", $this->data['ledgerdetail'][$key]->coanameconcatinv);
            $eachledger->coanameconcatinv = str_replace(",Fine", " ", $eachledger->coanameconcatinv);
            $this->data['ledgerdetail'][$key]->coanameconcatinv = str_replace(",Fine", " ", $this->data['ledgerdetail'][$key]->coanameconcatinv);
            $eachledger->amount = (float)abs($eachledger->amount);

            if (isset($eachledger->invid)) {
                $eachledger->relatedaccounts = $eachledger->coanameconcatinv;
                $eachledger->relatedid = $eachledger->invid;
                $eachledger->narration = $eachledger->invnarration;
                $eachledger->debit = ($type == 'customer') ? $eachledger->amount : 0;
                $eachledger->credit = ($type == 'supplier') ? $eachledger->amount : 0;
                $eachledger->created_date = ($this->datechooser == "bs") ? $eachledger->invoice_date_bs : $this->customlib->formatDate($eachledger->invoice_date);
            }
            if (isset($eachledger->jrnid)) {
                $eachledger->relatedaccounts = $eachledger->coanameconcatjrn;
                echo isset($eachledger->personnameconcatjrn) ? ', ' . $eachledger->personnameconcatjrn : '';
                $eachledger->relatedid = $eachledger->jrnid;
                $eachledger->narration = $eachledger->jrnnarration;
                //here
                $eachledger->debit = ($eachledger->amount_type == 'debit') ? $eachledger->amount : 0;
                $eachledger->credit = ($eachledger->amount_type == 'credit') ? $eachledger->amount : 0;
                $eachledger->created_date = ($this->datechooser == "bs") ? $eachledger->entry_date_bs : $eachledger->entry_date;
            }
            if (isset($eachledger->payid)) {
                $eachledger->relatedaccounts = ($eachledger->payment_mode == 'cash') ? "Cash" : $eachledger->paybank;
                $eachledger->relatedid = $eachledger->payid;
                $eachledger->narration = $eachledger->paynarration;
                $eachledger->debit = ($type == 'supplier') ? $eachledger->amount : 0;
                $eachledger->credit = ($type == 'customer') ? $eachledger->amount : 0;
                $eachledger->created_date = ($this->datechooser == "bs") ? $eachledger->payment_date_bs : $eachledger->payment_date;
            }
            if (isset($eachledger->recid)) {
                $eachledger->relatedaccounts = ($eachledger->receipt_mode == 'cash') ? "Cash" : $eachledger->recbank;
                $eachledger->relatedid = $eachledger->recid;
                $eachledger->narration = $eachledger->recnarration;
                $eachledger->debit = ($type == 'customer') ? 0 : $eachledger->amount;
                $eachledger->credit = ($type == 'supplier') ? 0 : $eachledger->amount;
                $eachledger->created_date = ($this->datechooser == "bs") ? $eachledger->receipt_date_bs : $eachledger->receipt_date;
            }
            $eachledger->amount = (strtolower($type) == 'customer') ? ($eachledger->debit - $eachledger->credit) : ($eachledger->credit - $eachledger->debit);
            $tempamt = $tempamt + $eachledger->amount;
            $eachledger->amount = $tempamt;
            if ($eachledger->relatedaccounts == "Opening Balance") {
                $viewurl = 'account/settings/general';
            }
            if ($eachledger->parent_type == "journal") {
                $viewurl = 'account/journal/view/';
            }
            if ($eachledger->parent_type == "receipt") {
                $viewurl = 'account/receipt/viewReceipt/';
            }
            if ($eachledger->parent_type == "payment") {
                $viewurl = 'account/payment/viewPayment/';
            }
            if ($eachledger->parent_type == "invoice") {
                $viewurl = 'account/invoice/view/';
            }
            $actionbuttons = '';
            $additive = ($eachledger->relatedaccounts == "Opening Balance") ? '' : $eachledger->relatedid;
            $actionbuttons .= '<a target="_blank" href="' . base_url() . $viewurl . $additive . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';


            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "date" => ($eachledger->relatedaccounts == "Opening Balance") ? '' : $eachledger->created_date,
                "accounts" => $eachledger->relatedaccounts,
                "narration" => $eachledger->narration,
                "source" => ucfirst($eachledger->parent_type),
                "debit" => $eachledger->debit ? $this->accountlib->currencyFormat($eachledger->debit, true, 2, '.', ',', true) : '-',
                "credit" => $eachledger->credit ? $this->accountlib->currencyFormat($eachledger->credit, true, 2, '.', ',', true) : '-',
                "balance" => $this->accountlib->currencyFormat($eachledger->amount, true, 2, '.', ',', true),
                "action" => $actionbuttons

            );


        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }


    function ledger($id, $financialYear = 0)
    {
//        if (!$this->rbac->hasPrivilege('account_ledger', 'can_view')) {
//            access_denied();
//        }

        if ($financialYear == 0) {
            $financialYear = $this->financial_year > 0 ? $this->financial_year : 1;
        }
        $this->data['selectedYear'] = $financialYear;
        $years = $this->account_model->getFinancialYearList();
        $financial_year = array();
        foreach ($years as $year) {
            if ($this->datechooser == 'bs') {
                $year->display = $year->year_starts_bs . ' - ' . $year->year_ends_bs;
            } else {
                $year->display = $year->year_starts . ' - ' . $year->year_ends;
            }
            $financial_year[$year->id] = $year;
        }

        $this->data['financial_years'] = $financial_year;

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/customerLedger/detail');
        $personnel = $this->personnel_model->getPersonnelDetail($id, $financialYear);
        $type = $personnel->type;
        /*$this->data['ledgerdetail'] = $this->personnel_model->getPersonnelLedgerDetail($id, $type, $financialYear);
        $openingbalance = $personnel->balance;
        $openingbalancetype = $personnel->balance_type;
        $openingdata = new stdClass();
        $openingdata->id = $personnel->id;
        $openingdata->relatedid = $personnel->id;
        $openingdata->relatedaccounts = "Opening Balance";
        $openingdata->created_date = '-';
        $openingdata->parent_type = "";
        $openingdata->narration = "Balance b/d";
        $openingdata->amount = $openingbalance;
        $openingdata->debit = strtolower($openingbalancetype) == "debit" ? $openingbalance : 0;
        $openingdata->credit = strtolower($openingbalancetype) == "credit" ? $openingbalance : 0;
        array_unshift($this->data['ledgerdetail'], $openingdata);
        $tempamt = 0;

        foreach ($this->data['ledgerdetail'] as $key => $eachledger) {
            $eachledger->coanameconcatinv = str_replace("Fine,", " ", $eachledger->coanameconcatinv);
            $this->data['ledgerdetail'][$key]->coanameconcatinv = str_replace("Fine,", " ", $this->data['ledgerdetail'][$key]->coanameconcatinv);
            $eachledger->coanameconcatinv = str_replace(",Fine", " ", $eachledger->coanameconcatinv);
            $this->data['ledgerdetail'][$key]->coanameconcatinv = str_replace(",Fine", " ", $this->data['ledgerdetail'][$key]->coanameconcatinv);
            $eachledger->amount = (float)abs($eachledger->amount);

            if (isset($eachledger->invid)) {
                $eachledger->relatedaccounts = $eachledger->coanameconcatinv;
                $eachledger->relatedid = $eachledger->invid;
                $eachledger->narration = $eachledger->invnarration;
                $eachledger->debit = ($type == 'customer') ? $eachledger->amount : 0;
                $eachledger->credit = ($type == 'supplier') ? $eachledger->amount : 0;
                $eachledger->created_date = ($this->datechooser == "bs") ? $eachledger->invoice_date_bs : $this->customlib->formatDate($eachledger->invoice_date);
            }
            if (isset($eachledger->jrnid)) {
                $eachledger->relatedaccounts = $eachledger->coanameconcatjrn;
                echo isset($eachledger->personnameconcatjrn) ? ', ' . $eachledger->personnameconcatjrn : '';
                $eachledger->relatedid = $eachledger->jrnid;
                $eachledger->narration = $eachledger->jrnnarration;
                $eachledger->debit = ($eachledger->amount_type == 'debit') ? $eachledger->amount : 0;
                $eachledger->credit = ($eachledger->amount_type == 'credit') ? $eachledger->amount : 0;
                $eachledger->created_date = ($this->datechooser == "bs") ? $eachledger->entry_date_bs : $eachledger->entry_date;
            }
            if (isset($eachledger->payid)) {
                $eachledger->relatedaccounts = ($eachledger->payment_mode == 'cash') ? "Cash" : $eachledger->paybank;
                $eachledger->relatedid = $eachledger->payid;
                $eachledger->narration = $eachledger->paynarration;
                $eachledger->debit = ($type == 'supplier') ? $eachledger->amount : 0;
                $eachledger->credit = ($type == 'customer') ? $eachledger->amount : 0;
                $eachledger->created_date = ($this->datechooser == "bs") ? $eachledger->payment_date_bs : $eachledger->payment_date;
            }
            if (isset($eachledger->recid)) {
                $eachledger->relatedaccounts = ($eachledger->receipt_mode == 'cash') ? "Cash" : $eachledger->recbank;
                $eachledger->relatedid = $eachledger->recid;
                $eachledger->narration = $eachledger->recnarration;
                $eachledger->debit = ($type == 'customer') ? 0 : $eachledger->amount;
                $eachledger->credit = ($type == 'supplier') ? 0 : $eachledger->amount;
                $eachledger->created_date = ($this->datechooser == "bs") ? $eachledger->receipt_date_bs : $eachledger->receipt_date;
            }
            $eachledger->amount = (strtolower($type) == 'customer') ? ($eachledger->debit - $eachledger->credit) : ($eachledger->credit - $eachledger->debit);
            $tempamt = $tempamt + $eachledger->amount;
            $eachledger->amount = $tempamt;
        }*/

        $this->data['id'] = $id;
        $this->data['personnel'] = $personnel;
        $this->data['personneltype'] = $type;
        $this->load->view('layout/header');
        $this->load->view('account/personnel/ledger', $this->data);
        $this->load->view('layout/footer');
    }


    function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/customer.csv";
        $data = file_get_contents($filepath);
        $name = 'personnel.csv';
        force_download($name, $data);
    }

    public function import()
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/import');
        $fields = array('code', 'pan', 'name', 'type', 'email', 'contact', 'address', 'balance', 'description');
        $type = array('1' => 'Customer', '2' => 'Supplier');
        $data['fields'] = $fields;
        $data['types'] = $type;
        $this->load->view('layout/header', $data);
        $this->load->view('account/personnel/import', $data);
        $this->load->view('layout/footer', $data);
    }

    public function import_confirm()
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/import');
        $redirecturi = "account/personnel/import";
        if ($this->input->method(TRUE) === 'POST') {
            $data = $this->input->post('data');
            $type = $this->input->post('type');
            $result = json_decode($data, true);
            if ($type != 'customer' && $type != 'supplier') {
                $this->session->set_flashdata('msg', array('message' => $this->lang->line('invalid_type'), 'type' => 'danger'));
                redirect($redirecturi);
            }
            $codeArray = array();
            $rows = $this->checkInsertability($result);
            $totalCount = 0;
            $count = 0;

            foreach ($rows as $row) {
                $totalCount += 1;
                if ($row['error'] == 0) {
                    $count += 1;
                    array_push($codeArray, '"' . $row['code'] . '"');
                    if (strtolower($type) == 'customer') {
                        $balance_type = $row['balance'] >= 0 ? 'debit' : 'credit';
                    } else {
                        $balance_type = $row['balance'] >= 0 ? 'credit' : 'debit';
                    }
                    $balance = abs($row['balance']);
                    $data = array(
                        'type' => $type,
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'contact' => $row['contact'],
                        'code' => $row['code'],
                        'address' => $row['address'],
                        'balance' => $balance,
                        'pan' => $row['pan'],
                        'balance_type' => $balance_type,
                        'description' => $row['description'],
                        'category' => strtolower($row['type']),

                    );
                    $insertData[] = $data;
                }
            }

            if ($count > 0) {
                $status = $this->personnel_model->batchInsert($insertData, $rows, $codeArray, $type);
                if ($status) {
                    $this->session->set_flashdata('msg', array('message' => $count . $this->lang->line('of') . $totalCount . $this->lang->line('data_imported_successfully'), 'type' => 'success'));
                    redirect($redirecturi);
                } else {
                    $this->session->set_flashdata('msg', array('message' => $this->lang->line('error_please_try_again'), 'type' => 'danger'));
                    redirect($redirecturi);
                }
            } else {
                $this->session->set_flashdata('msg', array('message' => $count . $this->lang->line('of') . $totalCount . $this->lang->line('data_imported_successfully'), 'type' => 'success'));
                redirect($redirecturi);
            }

        }
        redirect($redirecturi);

    }


    public function importPersonnel()
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/personnel/import');
        $input = $this->input;
        $typearray = array('customer', 'supplier',);
        $type = strtolower($input->post('type'));
        $redirecturi = "account/personnel/import";
        if (!in_array($type, $typearray)) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('invalid_type'), 'type' => 'danger'));
            redirect($redirecturi);
        }

        if (isset($_FILES['fileupload']) && $_FILES['fileupload']['tmp_name']) {
            if (!$_FILES['fileupload']['error']) {
                $file = $_FILES['fileupload']['tmp_name'];
                $extension = strtoupper(pathinfo($_FILES['fileupload']['name'], PATHINFO_EXTENSION));
                if ($extension == 'CSV') {
                    $this->load->library('CSVReader');
                    $rows = $this->csvreader->parse_file($file);
                    $rows = $this->checkInsertability($rows);
                    $fields = array('code', 'pan', 'name', 'type', 'email', 'contact', 'address', 'balance', 'description', 'error', 'error_cause');
                    $data['fields'] = $fields;
                    $data['type'] = $type;
                    $data['data'] = $rows;
                    $this->load->view('layout/header', $data);
                    $this->load->view('account/personnel/import_preview', $data);
                    $this->load->view('layout/footer', $data);
                } else {
                    $this->session->set_flashdata('msg', array('message' => $this->lang->line('please_upload_a_csv_file'), 'type' => 'danger'));
                    redirect($redirecturi);
                }
            } else {
                $this->session->set_flashdata('msg', array('message' => $this->lang->line('error_in_importing'), 'type' => 'danger'));
                redirect($redirecturi);
            }
        } else {
            redirect($redirecturi);
        }

    }

    public function checkInsertability($rows)
    {
        $codeArray = array();
        $codeArrayUnique = array();
        $vatArray = array();
        $vatArrayUnique = array();
        $panArray = array();
        $emailArray = array();
        $emailArrayUnique = array();
        $invalidCategory = 0;
        $insertData = array();
        foreach ($rows as $key => $row) {
            $rows[$key]['error'] = 0;
            $rows[$key]['error_cause'] = '';
            array_push($codeArray, "'" . $row['code'] . "'");
            if (!is_null($row['pan'])) {
                array_push($panArray, "'" . $row['pan'] . "'");
            }
            if (!is_null($row['email'])) {
                array_push($emailArray, "'" . $row['email'] . "'");
            }
            if (!in_array(strtolower($row['type']), array('others'))) {

                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Type must be Others';
                $invalidCategory = 1;
            }

        }
        $emailCountArray = array_count_values($emailArray);
        $emailArrayUnique = array_unique($emailArray);
        $codeCountArray = array_count_values($codeArray);
        $codeArrayUnique = array_unique($codeArray);
        $panCountArray = array_count_values($panArray);
        $panArrayUnique = array_unique($panArray);
        foreach ($rows as $key => $row) {
            if ($row['code'] == '') {
                $rows[$key]['code'] = strtoupper(mb_substr(rows[$key]['name'], 0, 3)) . substr(md5(rand()), 0, 4) . time() . substr(md5(rand()), 0, 4);
                $row['code'] = $rows[$key]['code'];
            }
        }
        $duplicates = $this->personnel_model->checkDuplicate($codeArray, $emailArray, $panArray);

        foreach ($rows as $key => $row) {
            if ($codeCountArray['"' . $row['code'] . '"'] > 1) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Duplicate code in spreadsheet';
            }
            if ($emailCountArray['"' . $row['email'] . '"'] > 1) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Duplicate email in spreadsheet';
            }
            if ($panCountArray['"' . $row['pan'] . '"'] > 1) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Duplicate pan in spreadsheet';
            }
            if (array_key_exists('"' . $row['code'] . '"', $duplicates['data'])) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Code has already been used';
            }
            if (array_key_exists('"' . $row['email'] . '"', $duplicates['data']) && $row['pan']) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Email has already been used';
            }
            if (array_key_exists('"' . $row['pan'] . '"', $duplicates['data']) && $row['pan']) {
                $rows[$key]['error'] = 1;
                $rows[$key]['error_cause'] = 'Pan has already been used';
            }
        }


        return $rows;

    }

}

?>