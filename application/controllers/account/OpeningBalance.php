<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class OpeningBalance extends Account_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->financial_year = $this->session->userdata('account')['financial_year'];
        $this->load->model('account/openingBalance_model');
        $this->response = array('status' => 'failure', 'data' => '');
    }

    public function ajax_getHeadings()
    {
        if (!$this->rbac->hasPrivilege('account_opening_balances', 'can_add')) {
            echo json_encode($this->response);
            exit;

        }
        $input = $this->input;
        $for = $input->post('balance_for');
        $heading = $this->openingBalance_model->getHeadings($for);
        $this->response['data'] = $heading;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;
    }

    public function openingBalancesList(){

        $postData = $this->input->post();

        $data = $this->openingBalance_model->getOpeningBalancesList($postData);

        echo json_encode($data);
    }

    public function ajax_addOpeningBalance()
    {
        if (!$this->rbac->hasPrivilege('account_opening_balances', 'can_add')) {
            echo json_encode($this->response);
            exit;
        }
        if ($this->financial_year > 1) {
            $this->response['data'] = 0.1;
            $this->response['status'] = 'Opening balance cant be added/modified after the first year closing';
            echo json_encode($this->response);
            exit;
        }
        $input = $this->input;
        $created_by = $this->session->userdata['admin']['id'];
        $created_at = $this->customlib->getCurrentTime();
        $balance_for = $input->post('balance_for');
        $heading = $input->post('heading');
        $balance = $input->post('balance');
        $balance_type = $input->post('balance_type');
        if ($balance_for == 'customer' || $balance_for == 'supplier') {
            $coa = 0;
            $personnel = $heading;
        } else {
            $personnel = 0;
            $coa = $heading;
        }
        $data = array(
            'balance' => $balance,
            'balance_type' => $balance_type,
            'personnel_id' => $personnel,
            'financial_year' => $this->financial_year,
            'coa_id' => $coa,
            'created_at' => $created_at,
            'created_by' => $created_by
        );
        $exists = $this->openingBalance_model->checkOpeningBalanceExists($personnel, $coa);
        if ($personnel != 0) {
            $existsOutstanding = $this->checkOutstandingInvoiceExists($personnel);
            if ($existsOutstanding) {
                $this->response['data'] = 0.1;
                $this->response['status'] = 'Opening balance already exists for student as outstanding income invoice entry';
                echo json_encode($this->response);
                exit;
            }
        }
        if ($exists->balance > 0) {
            $this->response['data'] = 0.1;
            $this->response['status'] = 'Operation Failed';
            echo json_encode($this->response);
            exit;
        }
        if (count($exists) > 0) {
            $insertid = (int)$exists->id;
            $modified_by = $this->session->userdata['admin']['id'];
            $modified_at = $this->customlib->getCurrentTime();
            $tempdata = array(
                'balance' => $balance,
                'balance_type' => $balance_type,
                'personnel_id' => $personnel,
                'coa_id' => $coa,
                'modified_at' => $modified_at,
                'modified_by' => $modified_by
            );
            $this->openingBalance_model->updateOpeningBalance($tempdata, $insertid);
        } else {
            $insertid = $this->openingBalance_model->addOpeningBalance($data);
        }
        $this->response['data'] = $insertid;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;
    }

    public function checkOutstandingInvoiceExists($id)
    {
        $this->db->select('inv.*,invent.rate as balance')->from('acc_invoice as inv')
            ->join('acc_invoice_entry as invent', '(inv.id = invent.invoice_id AND invent.coa_id = 14)', 'inner')
            ->where(array('inv.customer_id' => $id));
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    function ajax_editOpeningBalance()
    {
        if (!$this->rbac->hasPrivilege('account_opening_balances', 'can_view') || $this->financial_year != 1) {
            echo json_encode($this->response);
            exit;
        }
        if ($this->financial_year > 1) {
            $this->response['data'] = 0.1;
            $this->response['status'] = 'Opening balance cant be added/modified after the first year closing';
            echo json_encode($this->response);
            exit;
        }
        $input = $this->input;
        $modified_by = $this->session->userdata['admin']['id'];
        $modified_at = $this->customlib->getCurrentTime();
        $id = $input->post('id_edit');
        $personnel_id = $input->post('personnel_id');
        $coa_id = $input->post('coa_id');
        $balance = $input->post('balance_edit');
        $balance_type = $input->post('balance_type_edit');
        $data = array(
            'balance' => $balance,
            'balance_type' => $balance_type,
            'personnel_id' => $personnel_id,
            'coa_id' => $coa_id,
            'modified_at' => $modified_at,
            'modified_by' => $modified_by
        );
        $this->openingBalance_model->updateOpeningBalance($data, $id);
        $this->response['data'] = $data;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;
    }

    function ajax_getOpeningBalances()
    {
        if (!$this->rbac->hasPrivilege('account_opening_balances', 'can_view')) {
            echo json_encode($this->response);
            exit;
        }
        $input = $this->input;
        $target = $input->post('target');
        if ($target == '#tab_customers') {
            $type = 1;
        }
        if ($target == '#tab_suppliers') {
            $type = 2;
        }
        if ($target == '#tab_assets') {
            $type = 3;
        }
        if ($target == '#tab_liabilities') {
            $type = 4;
        }
        if ($target == '#tab_equity') {
            $type = 7;
        }
        $result = $this->openingBalance_model->getOpeningBalances($this->financial_year, $type);
        foreach ($result as $eachresult) {
            if (isset($eachresult->coa_id) && ($eachresult->coa_id > 0)) {
                $eachresult->name = $eachresult->coaname;
                $eachresult->code = $eachresult->coacode;
            } else if (isset($eachresult->personnel_id) && ($eachresult->personnel_id > 0)) {
                $eachresult->name = $eachresult->personnelname;
                $eachresult->code = $eachresult->personnelcode;
            }
            if ($eachresult->balance_type == 'debit') {
                $eachresult->debit = $eachresult->balance;
                $eachresult->credit = 0;
            }
            if ($eachresult->balance_type == 'credit') {
                $eachresult->credit = $eachresult->balance;
                $eachresult->debit = 0;
            }
        }
        $this->response['data'] = $result;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;
    }

    public function ajax_getEditItem()
    {
        if (!$this->rbac->hasPrivilege('account_opening_balances', 'can_edit') || $this->financial_year != 1) {
            echo json_encode($this->response);
            exit;
        }
        $input = $this->input;
        $id = $input->post('id');
        $result = $this->openingBalance_model->getItem($id);
        if (isset($result->coa_id) && ($result->coa_id > 0)) {
            $result->name = $result->coaname;
            $result->code = $result->coacode;
            $result->headingid = $result->coa_id;
            if ($result->coatype == 1) {
                $result->type = 'Asset';
            }
            if ($result->coatype == 2) {
                $result->type = 'Liability';
            }
            if ($result->coatype == 3) {
                $result->type = 'Income';
            }
            if ($result->coatype == 4) {
                $result->type = 'Expense';
            }
            if ($result->coatype == 5) {
                $result->type = 'Equity';
            }
        } else if (isset($result->personnel_id) && ($result->personnel_id > 0)) {
            $result->name = $result->personnelname;
            $result->code = $result->personnelcode;
            $result->headingid = $result->personnel_id;
            if ($result->personneltype == 'customer') {
                $result->type = 'Customer';
            }
            if ($result->personneltype == 'supplier') {
                $result->type = 'Supplier';
            }
        }
        $result->balance = $result->balance;
        $result->balance_type = $result->balance_type;
        $this->response['data'] = $result;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;
    }


    function deleteOpeningBalance($id)
    {
        if (!$this->rbac->hasPrivilege('account_opening_balances', 'can_delete') || $this->financial_year != 1) {
            echo json_encode($this->response);
            exit;
        }
        if ($this->financial_year > 1) {
            $this->response['data'] = 0.1;
            $this->response['status'] = 'Opening balance cant be added/modified after the first year closing';
            echo json_encode($this->response);
            exit;
        }
        $this->openingBalance_model->deleteOpeningBalance($id);
        $this->response['data'] = $id;
        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;
    }

}