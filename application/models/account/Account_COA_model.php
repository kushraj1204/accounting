<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Account_COA_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = 'acc_chart_of_accounts_detail';
        $this->financial_year = $this->session->userdata('account')['financial_year'];
        $this->financial_year = $this->session->userdata('account')['financial_year'];
        $this->load->model('account/openingBalance_model');
        $this->load->library('accountlib');
        $this->level = $this->accountlib->getAccountSetting()->level;
    }

    public function saveCOAItem($data)
    {
        $data['subcategory1'] = isset($data['subcategory1']) ? $data['subcategory1'] : 0;
        $data['subcategory2'] = isset($data['subcategory2']) ? $data['subcategory2'] : 0;
        $data['financial_year'] = $this->financial_year;
        if ($data['is_cash'] == 1) {
            $this->db->update($this->tableName, array('is_cash' => 0));
        }
        if ($data['is_defaultBank'] == 1) {
            $this->db->update($this->tableName, array('is_defaultBank' => 0));
        }

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            unset($data['id']);
            $this->db->update($this->tableName, $data);
        } else {
            $this->db->insert($this->tableName, $data);
            return $this->db->insert_id();
        }

    }

    function getRecords($searchValue, $rowperpage, $start, $type)
    {
        $this->db->select('coa.*,category.title as categoryname');
        if ($this->level >= 4) {
            $this->db->select('subcategory.title as subcategory1name');
            $this->db->join('acc_coa_categories as subcategory', 'coa.subcategory1 = subcategory.id', 'inner');
        }
        if ($this->level >= 5) {
            $this->db->select('subcategory2.title as subcategory2name');
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'inner');
        }
        $this->db->from($this->tableName . ' as coa');
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'inner');
        $this->db->where('coa.type', $type);
        if ($searchValue != '') {
            $this->db->like('coa.name', $searchValue);
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }

        return $this->db->get()->result();
    }

    public function getCOAList($postData = null)
    {
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value
        $target = $postData['type'];
        switch ($target) {
            case '#tab_assets':
                $type = 1;
                break;
            case '#tab_liabilities':
                $type = 2;
                break;
            case '#tab_incomes':
                $type = 3;
                break;
            case '#tab_expenses':
                $type = 4;
                break;
            default:
                $type = 5;
                break;
        }
        ## Total number of records without filtering
        $records = $this->getRecords('', 0, 0, $type);
        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->getRecords($searchValue, 0, 0, $type);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->getRecords($searchValue, $rowperpage, $start, $type);
        $data = array();
        foreach ($records as $key => $record) {
            $actionbuttons = '';
            $record->subcategory1name = $record->subcategory1name ? $record->subcategory1name : '-';
            $record->subcategory2name = $record->subcategory2name ? $record->subcategory2name : '-';
            if ($this->rbac->hasPrivilege('account_chart_of_accounts', 'can_edit')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/settings/edit_coa/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("edit") . '">
                                                    <i class="fa fa-pencil"></i>
                                                </a>';
            }
            if ($this->rbac->hasPrivilege('account_chart_of_accounts', 'can_delete') && $record->is_deletable == 1) {
                $actionbuttons .= '<a href="' . base_url() . 'account/settings/delete_coa/' . $record->id . '"
                                                   class="btn btn-default btn-xs deletecoa" data-toggle="tooltip"
                                                   title="' . $this->lang->line("delete") . '">
                                                    <i class="fa fa-remove"></i>
                                                </a>';

            }

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "name" => $record->name,
                "categoryname" => $record->categoryname,
                "subcategory1name" => $record->subcategory1name,
                "subcategory2name" => $record->subcategory2name,
                "code" => $record->code,
                "action" => $actionbuttons

            );
        }
//        echopreexit($records);
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;

    }

    public function get($id = NULL)
    {
        $this->db->select('coa.*,category.title as categoryName');
        if ($this->level >= 5) {
            $this->db->select('subcategory2.title as subCategoryName');
        } elseif ($this->level >= 4) {
            $this->db->select('subcategory1.title as subCategoryName');
        } elseif ($this->level >= 3) {
            $this->db->select('category.title as subCategoryName');
        }
        $this->db->from($this->tableName . ' as coa');
        if ($this->level >= 4) {
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'left');
        }
        if ($this->level >= 5) {
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'left');
        }
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'left');
//        $this->db->where('coa.financial_year', $this->financial_year);
        if ($id) {
            $this->db->where('coa.id', $id);
        }
        $query = $this->db->get();
        if ($id) {
            return $query->row();
        }
        return $query->result();
    }

    public function getCOAListing($page, $type)
    {
        $limit = 2;
        $start = ($page - 1) * $limit;
        $this->db->select('coa.*,category.title as categoryname');
        if ($this->level >= 4) {
            $this->db->select('subcategory.title as subcategory1name');
            $this->db->join('acc_coa_categories as subcategory', 'coa.subcategory1 = subcategory.id', 'inner');
        }
        if ($this->level >= 5) {
            $this->db->select('subcategory2.title as subcategory2name');
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'inner');
        }
        $this->db->from($this->tableName . ' as coa');
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'inner');
        $this->db->where('coa.type', $type);
        $query = $this->db->get();
        return array('total' => 1, 'result' => $query->result());

    }

    function getTotalItemCount($type)
    {

        $this->db->select('coa.*,category.title as categoryname,subcategory.title as subcategoryname');
        $this->db->from($this->tableName . ' as coa');
        $this->db->join('acc_coa_categories as subcategory', 'coa.subcategory1 = subcategory.id', 'inner');
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'inner');
        $this->db->where('coa.type', $type);
        $this->db->where('coa.financial_year', $this->financial_year);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function deleteCOAItem($id)
    {
        $this->db->delete($this->tableName, array('id' => $id, 'is_deletable' => 1));
    }

    function getBanksList()
    {

        $this->db->select('coa.id,coa.name');
        $this->db->from($this->tableName . ' as coa');
        $this->db->where('coa.is_bank = "1"');
//        $this->db->where('coa.financial_year', $this->financial_year);
        $query = $this->db->get();
        return $query->result();
    }

    public function getCoaListForThisYear($financial_year = NULL, $type = 0)
    {
        if (!$financial_year) {
            $financial_year = $this->financial_year;
        }
        $this->db->select('SUM(CASE WHEN logs.amount_type = "debit" then logs.amount else 0 end) as coaDebitSum');
        $this->db->select('SUM(CASE WHEN logs.amount_type = "credit" then logs.amount else 0 end) as coaCreditSum');
        $this->db->select('logs.category_id,logs.category_type,coa1.id as coaid,coa1.type as type');
        $this->db->select('logs.financial_year');
        $this->db->from('acc_transaction_logs as logs');
        $this->db->join($this->tableName . ' as coa1', 'logs.category_id = coa1.id and logs.category_type = "coa"', 'inner');
        $this->db->group_by('coa1.id');
        if ($type > 0) {
            $this->db->where('coa1.type', $type);
        };
        $this->db->where('logs.financial_year', $financial_year);
        $this->db->where('logs.status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function coaListIncomeStatement($financial_year = NULL)
    {
        if (!$financial_year) {
            $financial_year = $this->financial_year;
        }

        $this->db->select('coa.*');
        $this->db->select('category.title as categoryName');
        if ($this->level >= 5) {
            $this->db->select('subcategory2.title as subCategory2Name');
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'inner');
        }
        if ($this->level >= 4) {
            $this->db->select('subcategory1.title as subCategory1Name');
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'inner');
        }
        $this->db->from($this->tableName . ' as coa');
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'inner');
        $this->db->where('coa.type IN (3,4)');
        $this->db->group_by('coa.id');
        $coa_clause = $this->db->get_compiled_select();

        $this->db->select('coa1.id as coa1id');
        $this->db->select('SUM(CASE WHEN logs.amount_type = "debit" then logs.amount else 0 end) as coaDebitSum');
        $this->db->select('SUM(CASE WHEN logs.amount_type = "credit" then logs.amount else 0 end) as coaCreditSum');
        $this->db->select('logs.category_id,logs.category_type,');
        $this->db->select('logs.financial_year as thefinancialyear');
        $this->db->from($this->tableName . ' as coa1');//
        $this->db->join('acc_transaction_logs as logs', 'logs.category_id = coa1.id and logs.category_type = "coa"', 'left');
        $this->db->group_by('coa1.id,logs.financial_year');
        $this->db->where('coa1.type IN (3,4)');
        $this->db->where('logs.financial_year=' . $financial_year);
        $this->db->where('logs.status', 1);
        $sum_clause = $this->db->get_compiled_select();

        $this->db->select('s.*,c.*,c.id as cid');
        $this->db->from('(' . $coa_clause . ') as c');
        $this->db->join('(' . $sum_clause . ') as s', 'c.id=s.coa1id', 'left');
        $sum1_clause = $this->db->get_compiled_select();

        $this->db->select('coa2.id as coa2id');
        $this->db->select('SUM(CASE WHEN logs.amount_type = "debit" then logs.amount else 0 end) as coaDebitSumPrev');
        $this->db->select('SUM(CASE WHEN logs.amount_type = "credit" then logs.amount else 0 end) as coaCreditSumPrev');
        $this->db->from('acc_transaction_logs as logs');
        $this->db->join($this->tableName . ' as coa2', 'logs.category_id = coa2.id and logs.category_type = "coa"', 'inner');
        $this->db->group_by('coa2.id,logs.financial_year');
        $this->db->where('coa2.type IN (3,4)');
        $this->db->where('logs.financial_year=' . ($financial_year - 1));
        $this->db->where('logs.status', 1);
        $sum2_clause = $this->db->get_compiled_select();

        $this->db->select('*');
        $this->db->from('(' . $sum1_clause . ') as s1');
        $this->db->join('(' . $sum2_clause . ') as s2', 's1.cid=s2.coa2id', 'left');
        $query = $this->db->get();
        return $query->result();

    }

    public function getCOAListForLedger($financial_year = NULL, $type = 0)
    {
        if (!$financial_year) {
            $financial_year = $this->financial_year;
        }
        $this->db->select('SUM(CASE WHEN logs.amount_type = "debit" then logs.amount else 0 end) as coaDebitSum');
        $this->db->select('SUM(CASE WHEN logs.amount_type = "credit" then logs.amount else 0 end) as coaCreditSum');
        $this->db->select('logs.category_id,logs.category_type,coa1.id as coaid');
        $this->db->select('logs.financial_year');
        $this->db->from('acc_transaction_logs as logs');
        $this->db->join($this->tableName . ' as coa1', 'logs.category_id = coa1.id and logs.category_type = "coa"', 'inner');
        $this->db->group_by('coa1.id');
        if ($type > 0) {
            $this->db->where('coa1.type', $type);
        };
        $this->db->where('logs.financial_year', $financial_year);
        $this->db->where('logs.status', 1);
        $sum_clause = $this->db->get_compiled_select();

        $this->db->select('sum(log.amount) as amount');
        $this->db->select('obal.balance as openingbalance,obal.balance_type as openingbalancetype');
        $this->db->select('obalprevyear.balance as openingbalanceprevyear,obalprevyear.balance_type as openingbalancetypeprevyear');
        $this->db->select('ABS(sum_clause.coaDebitSum) AS coaDebitSum, ABS(sum_clause.coaCreditSum) AS coaCreditSum');
        $this->db->join('acc_transaction_logs as log', 'log.category_id = coa.id and log.category_type = "coa" and log.status = 1 and log.financial_year = ' . $financial_year, 'left');
        $this->db->join('(' . $sum_clause . ') as sum_clause', 'sum_clause.category_id = coa.id and sum_clause.category_type = "coa" and sum_clause.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_invoice as invoice', 'log.parent_id = invoice.id and log.parent_type = "invoice" and invoice.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_journal as journal', 'log.parent_id = journal.id and log.parent_type = "journal" and journal.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_payment as payment', 'log.parent_id = payment.id and log.parent_type = "payment" and payment.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_receipt as receipt', 'log.parent_id = receipt.id and log.parent_type = "receipt" and receipt.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_opening_balances as obal', 'obal.coa_id = coa.id and obal.financial_year = "' . $financial_year . '"', 'left');
        $this->db->join('acc_opening_balances as obalprevyear', 'obalprevyear.coa_id = coa.id and obalprevyear.financial_year = "' . ($financial_year - 1) . '"', 'left');
        /*$this->db->where('(
            journal.financial_year = ' . $financial_year .  '
            OR invoice.financial_year = ' . $financial_year . '
            OR payment.financial_year = ' . $financial_year . '
            OR receipt.financial_year = ' . $financial_year . '
        )');*/

        /*$this->db->select('balance.balance, balance.balance_type');
        $this->db->join('acc_opening_balances as balance', 'balance.coa_id = log.parent_id and log.category_type = "coa" and log.status = 1', 'left');*/

        $this->db->select('coa.*');
        $this->db->select('category.title as categoryName');
        if ($this->level >= 5) {
            $this->db->select('subcategory2.title as subCategory2Name');
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'inner');
        }
        if ($this->level >= 4) {
            $this->db->select('subcategory1.title as subCategory1Name');
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'inner');
        }
        $this->db->from($this->tableName . ' as coa');
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'inner');
        if ($type > 0) {
            $this->db->where('coa.type', $type);
        }
        $this->db->group_by('coa.id');
        $query = $this->db->get();
        return $query->result();
    }

    function getCashItem()
    {
        $this->db->select('coa.id,coa.name');
        $this->db->from($this->tableName . ' as coa');
        $this->db->where('coa.is_cash = "1"');
//        $this->db->where('coa.financial_year', $this->financial_year);
        $query = $this->db->get();
        return $query->row();
    }

    function getDefaultBank()
    {
        $this->db->select('coa.id,coa.name');
        $this->db->from($this->tableName . ' as coa');
        $this->db->where('coa.is_bank = "1"');
        $this->db->where('coa.is_defaultBank = "1"');
//        $this->db->where('coa.financial_year', $this->financial_year);
        $query = $this->db->get();
        return $query->row()->id;
    }

    function closeCOABalances($currentFinancialYearID)
    {

        $coa = $this->getCOAListForLedger($this->financial_year);
        $openingBalances = $this->openingBalance_model->getOpeningBalances($this->financial_year);
        $balances = array();
        foreach ($openingBalances as $balance) {
            if ($balance->coa_id != 0) {
                $balances[$balance->coa_id] = $balance;
            }
        }
        $insertdata = array();
        $created_by = $this->session->userdata['admin']['id'];
        $created_at = $this->customlib->getCurrentTime();
        foreach ($coa as $eachdata) {
            if (in_array($eachdata->type, array(1, 4))) {
                $eachdata->openingbalance = (strtolower($balances[$eachdata->id]->balance_type) == "credit") ? $balances[$eachdata->id]->balance * (-1) : $balances[$eachdata->id]->balance;
                $eachdata->total = (isset($eachdata->amount) ? $eachdata->amount : 0) + $eachdata->openingbalance;
                if ($eachdata->total > 0) {
                    $balance_type = 'debit';
                } else {
                    $balance_type = 'credit';
                }
            } else if (in_array($eachdata->type, array(2, 3, 5))) {
                $eachdata->openingbalance = (strtolower($balances[$eachdata->id]->balance_type) == "debit") ? $balances[$eachdata->id]->balance * (-1) : $balances[$eachdata->id]->balance;
                $eachdata->total = (isset($eachdata->amount) ? $eachdata->amount : 0);
//                if ($eachdata->id == 500) { // (13)500 currently in test.neemacademy &means that it is yearly profit account thing needs to be decided
                if ($eachdata->id == 13) { // (13)500 currently in test.neemacademy &means that it is yearly profit account thing needs to be decided
                    $eachdata->total = $this->computeClosingIncome($this->financial_year);

                }
                $eachdata->total += $eachdata->openingbalance;
                if ($eachdata->total > 0) {
                    $balance_type = 'credit';
                } else {
                    $balance_type = 'debit';
                }


            }
            $insertdata[] = array(
                'balance' => abs($eachdata->total),
                'balance_type' => $balance_type,
                'personnel_id' => 0,
                'financial_year' => $currentFinancialYearID,
                'coa_id' => $eachdata->id,
                'created_at' => $created_at,
                'created_by' => $created_by
            );
        }
        if (!empty($insertdata)) {
            $this->openingBalance_model->batchaddOpeningBalance($insertdata);
        }
    }

    public function computeClosingIncome($financialYear)
    {
        //needs to be checked
        $coa = $this->getCoaListForThisYear($financialYear);
        $profit = 0;
        foreach ($coa as $eachCOA) {
            if ($eachCOA->type == '3') {
                $profit += $eachCOA->coaCreditSum;
                $profit += -($eachCOA->coaDebitSum);
            }
            if ($eachCOA->type == '4') {
                $profit -= $eachCOA->coaDebitSum;
                $profit -= -($eachCOA->coaCreditSum);
            }
        }
        return $profit;

    }

    public function updateSchoolCOA($values)
    {
        switch ($values['type']) {
            case 'discount':
                $type = 4;
                $category = 12;
                $subcategory1 = 13;
                $subcategory2 = 14;
                $is_bank = 0;
                $is_cash = 0;
                $is_default_bank = 0;
                $status = $values['is_active'] != 'no' ? 1 : 0;
                break;
            case 'fee_type':
                $type = 3;
                $category = 6;
                $subcategory1 = 7;
                $subcategory2 = 8;
                $is_bank = 0;
                $is_cash = 0;
                $is_default_bank = 0;
                $status = $values['is_active'] != 'no' ? 1 : 0;
                break;
            default:
                $type = 0;
                $category = 0;
                $subcategory1 = 0;
                $subcategory2 = 0;
                $is_bank = 0;
                $is_cash = 0;
                $is_default_bank = 0;
                $status = 0;
                break;
        }

        if ($type == 0) {
            return;
        }

        $description = isset($values['description']) && $values['description'] != '' ? $values['description'] : $this->lang->line('school') . ' ' . $this->lang->line($values['type']) . ' ' . $values['name'];

        $created_by = $this->session->userdata['admin']['id'];
        $created_at = $this->customlib->getCurrentTime();

        $data = array(
            'name' => $values['name'],
            'type' => $type,
            'category' => $category,
            'subcategory1' => $subcategory1,
            'subcategory2' => $subcategory2,
            'description' => $description,
            'rate' => 0,
            'code' => $values['code'],
            'status' => $status,
            'is_bank' => $is_bank,
            'is_cash' => $is_cash,
            'is_defaultBank' => $is_default_bank,
            'created_at' => $created_at,
            'created_by' => $created_by,
            'modified_at' => $created_at,
            'modified_by' => $created_by,
            'school_item_id' => $values['id'],
            'school_item_type' => $values['type'],
            'financial_year' => $this->financial_year,
            'is_deletable' => 0,
        );

        $checkSchoolCOA = $this->checkSchoolCOA($values);
        if ($checkSchoolCOA == 0 && $status != 0) {
            $slug = create_unique_slug($values['name'], $this->tableName, $field = 'code', $key = 'code', $value = '');
            $code = isset($values['code']) && $values['code'] != '' ? $values['code'] : $slug;
            $data['code'] = $code;
            $this->db->insert($this->tableName, $data);
        } else {
            if ($checkSchoolCOA > 0) {
                unset($data['created_at']);
                unset($data['created_by']);
                if ($status == 0) {
                    $data = array();
                    $data['status'] = 0;
                }
                $this->db->where(array('school_item_id' => $values['id'], 'school_item_type' => $values['type']))
                    ->update($this->tableName, $data);
            }
        }
    }

    public function checkSchoolCOA($values)
    {
        $this->db->select('*')->from($this->tableName)->where(array('school_item_id' => $values['id'], 'school_item_type' => $values['type']));
        $this->db->limit(1)->order_by('id ASC');
        $query = $this->db->get();
        return $query->row()->id;
    }

    function getCOAbyItemType($itemType)
    {
        $this->db->select('*');
        $this->db->from($this->tableName . ' as coa');
        $this->db->where('coa.school_item_type', $itemType);
//        $this->db->where('coa.financial_year', $this->financial_year);
        $query = $this->db->get();
        $results = $query->result();
        $return = array();
        foreach ($results as $result) {
            $return[$result->school_item_id] = $result;
        }
        return $return;
    }

    public function checkCodeExists($codeArray)
    {
        $this->db->select('*')->from($this->tableName)
            ->where('code IN  ( ' . implode(',', $codeArray) . ')');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function batchInsert($data, $rows, $codeArray, $typeKey)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        $this->db->insert_batch($this->tableName, $data);

        $codeIdArray = $this->getIdsFromCode($codeArray);
        $openingBalancesInsertData = array();
        $created_by = $this->session->userdata['admin']['id'];
        $created_at = $this->customlib->getCurrentTime();
        foreach ($rows as $row) {
            if ($row['error'] == 0) {
                if (!is_numeric($row['opening_balance'])) {
                    $row['opening_balance'] = 0;
                }
                if (in_array($typeKey, array(1, 4))) {
                    $balance_type = $row['opening_balance'] >= 0 ? 'debit' : 'credit';
                    $balance = abs($row['opening_balance']);
                } else {
                    $balance_type = $row['opening_balance'] >= 0 ? 'credit' : 'debit';
                    $balance = abs($row['opening_balance']);
                }
                $data = array(
                    'balance' => $balance,
                    'balance_type' => $balance_type,
                    'personnel_id' => 0,
                    'financial_year' => $this->financial_year,
                    'coa_id' => array_search($row['code'], $codeIdArray),
                    'created_at' => $created_at,
                    'created_by' => $created_by
                );
                $openingBalancesInsertData[] = $data;
            }
        }
        $this->openingBalance_model->batchInsert($openingBalancesInsertData);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return FALSE;
        } else {

            $this->db->trans_commit();
            return TRUE;
        }

    }

    public function getIdsFromCode($codeArray)
    {
        $this->db->select('id,code')->from($this->tableName)
            ->where('code IN  ( ' . implode(',', $codeArray) . ')');
        $query = $this->db->get();
        $results = $query->result();
        $codeIdArray = array();
        foreach ($results as $result) {
            $codeIdArray[$result->id] = $result->code;
        }
        return $codeIdArray;
    }

    public function checkDuplicate($codeArray)
    {
        $this->db->select('id,code')->from($this->tableName)
            ->where('code IN  ( ' . implode(',', $codeArray) . ')');
        $query = $this->db->get();

        $data = $query->result();
        $dataArray = array();
        foreach ($data as $datum) {
            $dataArray['"' . $datum->code . '"'] = $datum->id;
        }
        return array('count' => $query->num_rows(), 'data' => $dataArray);
    }


    function getCOALedgerList($postData = null)
    {

        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $financial_year = $postData['financial_year'];
        $typetemp = $postData['type'];
        if ($typetemp == 'assets') {
            $type = 1;
        }
        if ($typetemp == 'liabilities') {
            $type = 2;
        }
        if ($typetemp == 'incomes') {
            $type = 3;
        }
        if ($typetemp == 'expenses') {
            $type = 4;
        }
        $openingBalanceType = $type + 2;
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value
        if ($type < 3) {
            $personnel = $this->personnel_model->getPersonnelTrialBalance($financial_year);
            if ($type == 2) { //liabilities bhaye
                $personnelbalances = array('supplierpayables' => 0, 'supplierpayablesprev' => 0);
                foreach ($personnel as $key => $person) {
                    if ($key == 1) {
                        $personnelbalances['supplierpayablesprev'] = $person['openingCreditTotal'] - $person['openingDebitTotal'];
                        $personnelbalances['supplierpayables'] = $person['creditTotal'] - $person['debitTotal'] + $personnelbalances['supplierpayablesprev'];
                        if ($financial_year == 1) {
                            $personnelbalances['supplierpayablesprev'] = 0;
                        }
                    }
                }
                $payable = new stdClass();
                $payable->name = "Supplier Payables";
                $payable->code = "Payables";
                $payable->categoryName = "-";
                $payable->subCategory1Name = "-";
                $payable->subCategory2Name = "-";
                $payable->balance = $this->accountlib->currencyFormat($personnelbalances['supplierpayables']);
                $payable->action = '<a href="' . base_url() . 'account/personnel/suppliers' . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
                $this->data['personnelbalance'] = $payable;
            }
            if ($type == 1) { //assets bhaye
                $personnelbalances = array('customerreceivables' => 0, 'customerreceivablesprev' => 0);
                foreach ($personnel as $key => $person) {

                    if ($key == 2) {
                        $personnelbalances['customerreceivablesprev'] = $person['openingDebitTotal'] - $person['openingCreditTotal'];
                        $personnelbalances['customerreceivables'] = $person['debitTotal'] - $person['creditTotal'] + $personnelbalances['customerreceivablesprev'];
                        if ($financial_year == 1) {
                            $personnelbalances['customerreceivablesprev'] = 0;
                        }
                    }
                }

                $receivable = new stdClass();
                $receivable->name = "Customer Receivables";
                $receivable->code = "Receivables";
                $receivable->categoryName = "-";
                $receivable->subCategory1Name = "-";
                $receivable->subCategory2Name = "-";
                $receivable->balance = $this->accountlib->currencyFormat($personnelbalances['customerreceivables']);
                $receivable->action = '<a href="' . base_url() . 'account/personnel/customers' . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
            }
        }

        ## Total number of records without filtering
        $records = $this->getRecordsLedgerList($financial_year, $type, '', 0, 0);
        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->getRecordsLedgerList($financial_year, $type, $searchValue, 0, 0);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->getRecordsLedgerList($financial_year, $type, $searchValue, $rowperpage, $start);

        $openingBalances = $this->openingBalance_model->getOpeningBalances($this->financial_year, $openingBalanceType);
        $balances = array();
        foreach ($openingBalances as $balance) {
            $balances[$balance->coa_id] = $balance;
        }
        $data = array();
        foreach ($records as $key => $record) {
            $debitMultiplier = 1;
            $creditMultiplier = 1;
            $debitPlusArray = array(1, 4);//Asset, Expenses
            $creditPlusArray = array(2, 3, 5);//Liabilities, Incomes, Equity
            $zeroOpeningBalanceArray = array();//Incomes, Expenses
            $top_parent = $this->transaction_model->get_top_parent($record->category);
            if (in_array($top_parent, $debitPlusArray)) {
                $creditMultiplier = -1;
            } elseif (in_array($top_parent, $creditPlusArray)) {
                $debitMultiplier = -1;
            }
            $balanceMultiplier = strtolower($balances[$record->id]->balance_type) == 'debit' ? $debitMultiplier : $creditMultiplier;
            if (in_array($top_parent, $zeroOpeningBalanceArray)) {
                $balanceMultiplier = 0;
            }
            $balance = $record->amount + ($balanceMultiplier * $balances[$record->id]->balance);

            $actionbuttons = '';
            $actionbuttons .= '<a href="' . base_url() . 'account/ledger/detail/' . $record->id . '/' . $this->financial_year . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
            $record->subCategory1Name = $record->subCategory1Name ? $record->subCategory1Name : '-';
            $record->subCategory2Name = $record->subCategory2Name ? $record->subCategory2Name : '-';

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "name" => $record->name,
                "code" => $record->name,
                "categoryName" => $record->categoryName,
                "subCategory1Name" => $record->subCategory1Name,
                "subCategory2Name" => $record->subCategory2Name,
                "balance" => $this->accountlib->currencyFormat($balance),
                "action" => $actionbuttons

            );
        }
        if ($type == 1) {
            $data[] = (array)$receivable;
        }
        if ($type == 2) {
            $data[] = (array)$payable;
        }
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }

    public function getRecordsLedgerList($financial_year = NULL, $type = 0, $searchValue, $rowperpage, $start)
    {
        if (!$financial_year) {
            $financial_year = $this->financial_year;
        }

        $this->db->select('SUM(CASE WHEN logs.amount_type = "debit" then logs.amount else 0 end) as coaDebitSum');
        $this->db->select('SUM(CASE WHEN logs.amount_type = "credit" then logs.amount else 0 end) as coaCreditSum');
        $this->db->select('logs.category_id,logs.category_type,coa1.id as coaid');
        $this->db->select('logs.financial_year');
        $this->db->from('acc_transaction_logs as logs');
        $this->db->join($this->tableName . ' as coa1', 'logs.category_id = coa1.id and logs.category_type = "coa"', 'inner');
        $this->db->group_by('coa1.id');
        if ($type > 0) {
            $this->db->where('coa1.type', $type);
        };
        $this->db->where('logs.financial_year', $financial_year);
        $this->db->where('logs.status', 1);
        $sum_clause = $this->db->get_compiled_select();

        $this->db->select('sum(log.amount) as amount');
        $this->db->select('obal.balance as openingbalance,obal.balance_type as openingbalancetype');
        $this->db->select('obalprevyear.balance as openingbalanceprevyear,obalprevyear.balance_type as openingbalancetypeprevyear');
        $this->db->select('ABS(sum_clause.coaDebitSum) AS coaDebitSum, ABS(sum_clause.coaCreditSum) AS coaCreditSum');
        $this->db->join('acc_transaction_logs as log', 'log.category_id = coa.id and log.category_type = "coa" and log.status = 1 and log.financial_year = ' . $financial_year, 'left');
        $this->db->join('(' . $sum_clause . ') as sum_clause', 'sum_clause.category_id = coa.id and sum_clause.category_type = "coa" and sum_clause.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_invoice as invoice', 'log.parent_id = invoice.id and log.parent_type = "invoice" and invoice.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_journal as journal', 'log.parent_id = journal.id and log.parent_type = "journal" and journal.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_payment as payment', 'log.parent_id = payment.id and log.parent_type = "payment" and payment.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_receipt as receipt', 'log.parent_id = receipt.id and log.parent_type = "receipt" and receipt.financial_year = ' . $financial_year, 'left');
        $this->db->join('acc_opening_balances as obal', 'obal.coa_id = coa.id and obal.financial_year = "' . $financial_year . '"', 'left');
        $this->db->join('acc_opening_balances as obalprevyear', 'obalprevyear.coa_id = coa.id and obalprevyear.financial_year = "' . ($financial_year - 1) . '"', 'left');
        /*$this->db->where('(
            journal.financial_year = ' . $financial_year .  '
            OR invoice.financial_year = ' . $financial_year . '
            OR payment.financial_year = ' . $financial_year . '
            OR receipt.financial_year = ' . $financial_year . '
        )');*/

        /*$this->db->select('balance.balance, balance.balance_type');
        $this->db->join('acc_opening_balances as balance', 'balance.coa_id = log.parent_id and log.category_type = "coa" and log.status = 1', 'left');*/

        $this->db->select('coa.*');
        $this->db->select('category.title as categoryName');
        if ($this->level >= 5) {
            $this->db->select('subcategory2.title as subCategory2Name');
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'inner');
        }
        if ($this->level >= 4) {
            $this->db->select('subcategory1.title as subCategory1Name');
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'inner');
        }
        $this->db->from($this->tableName . ' as coa');
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'inner');
        if ($type > 0) {
            $this->db->where('coa.type', $type);
        }
        $this->db->group_by('coa.id');
        if ($searchValue != '') {
            $this->db->like('coa.name', $searchValue);
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }
        $query = $this->db->get();
        return $query->result();
    }


}