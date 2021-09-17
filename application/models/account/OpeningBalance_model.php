<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class OpeningBalance_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('account/personnel_model');
    }

    function getHeadings($for)
    {
        $coa = array('1' => 'asset', '2' => 'liability', '3' => 'income', '4' => 'expense', '5' => 'equity');
        if ($for == 'customer' || $for == 'supplier') {
            $this->db->select('per.id, per.name,per.contact,per.code');
            $this->db->from('acc_personnel as per');
            $this->db->join('acc_opening_balances as obal', 'per.id = obal.personnel_id', 'left');
            $this->db->where('per.type', $for);
            $where = '(obal.id IS NULL OR obal.balance=0)';
            $this->db->where($where);

        } else if ($for == 'asset' || $for == 'liability' || $for == 'income' || $for == 'expense' || $for == 'equity') {
            $this->db->select('per.id, per.name, per.code');
            $this->db->from('acc_chart_of_accounts_detail as per');
            $this->db->join('acc_opening_balances as obal', 'per.id = obal.coa_id', 'left');
            $where = '(obal.id IS NULL OR obal.balance=0)';
            $this->db->where($where);
            $this->db->where('per.type', array_search($for, $coa));
            $this->db->where('per.status', '1');
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function addOpeningBalance($data)
    {

        $this->db->insert('acc_opening_balances', $data);
        return $this->db->insert_id();

    }

    function getOpeningBalancesList($postData = null)
    {
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value
        $target = $postData['type'];
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
        ## Total number of records without filtering
        $records = $this->getRecords($this->financial_year, $type, '', 0, 0);
        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->getRecords($this->financial_year, $type, $searchValue, 0, 0);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->getRecords($this->financial_year, $type, $searchValue, $rowperpage, $start);
        $data = array();
        foreach ($records as $key => $record) {
            if (isset($record->coa_id) && ($record->coa_id > 0)) {
                $record->name = $record->coaname;
                $record->code = $record->coacode;
            } else if (isset($record->personnel_id) && ($record->personnel_id > 0)) {
                $record->name = $record->personnelname;
                $record->code = $record->personnelcode;
            }
            if ($record->balance_type == 'debit') {
                $record->debit = $record->balance;
                $record->credit = 0;
            }
            if ($record->balance_type == 'credit') {
                $record->credit = $record->balance;
                $record->debit = 0;
            }
            $actionbuttons = '';

            if ($this->rbac->hasPrivilege('account_opening_balances', 'can_edit') && $this->financial_year == 1) {
                $actionbuttons .= '<a data-id="' . $record->id . '" data-toggle="modal" 
                                                   class="btn btn-default btn-xs editbutton" data-toggle="tooltip"
                                                   title="' . $this->lang->line("edit") . '">
                                                    <i class="fa fa-pencil"></i>
                                                </a>';
            }
            if ($this->rbac->hasPrivilege('account_opening_balances', 'can_delete') && $this->financial_year == 1) {
                $actionbuttons .= '<a href="' . base_url() . 'account/openingBalance/deleteOpeningBalance/' . $record->id . '"
                                                   class="btn btn-default btn-xs deleteopeningbalance" data-toggle="tooltip"
                                                   title="' . $this->lang->line("delete") . '">
                                                    <i class="fa fa-remove"></i>
                                                </a>';

            }

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "name" => $record->name,
                "code" => $record->code,
                "debit" => $record->debit,
                "credit" => $record->credit,
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

    function getRecords($financial_year, $type, $searchValue, $rowperpage, $start)
    {
        $this->db->select('bal.*,person.name as personnelname,person.type as personneltype,person.code as personnelcode,coa.name as coaname,coa.type as coatype, coa.code as coacode');
        $this->db->from('acc_opening_balances as bal');
        $this->db->join('acc_personnel as person', 'bal.personnel_id = person.id', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'bal.coa_id = coa.id', 'left');
        if ($financial_year > 0) {
            $this->db->where('bal.financial_year', $financial_year);
        }
        if ($type == 1) {
            $this->db->where('person.type', 'customer');
        }
        if ($type == 2) {
            $this->db->where('person.type', 'supplier');
        }
        if ($type == 3) {
            $this->db->where('coa.type', '1');
        }
        if ($type == 4) {
            $this->db->where('coa.type', '2');
        }
        if ($type == 5) {
            $this->db->where('coa.type', '3');
        }
        if ($type == 6) {
            $this->db->where('coa.type', '4');
        }
        if ($type == 7) {
            $this->db->where('coa.type', '5');
        }
        if ($searchValue != '') {
            if ($type < 3) {
                $this->db->like('person.name', $searchValue);
            } else {
                $this->db->like('coa.name', $searchValue);
            }
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function batchaddOpeningBalance($data)
    {
        $this->db->insert_batch('acc_opening_balances', $data);
    }

    function updateOpeningBalance($data, $id, $updatePersonnel = true)
    {
        $this->db->where('id', $id);
        $this->db->update('acc_opening_balances', $data);
        if ($data['personnel_id'] && $updatePersonnel) {
            $personneldata['balance'] = $data['balance'];
            $personneldata['balance_type'] = $data['balance_type'];
            $personneldata['id'] = $data['personnel_id'];
            $this->personnel_model->updatePersonnelBalance($personneldata);
        }
        return;
    }

    function updateOpeningPersonnelBalance($data)
    {
        $this->db->where('personnel_id', $data['personnel_id']);
        unset($data['personnel_id']);
        $this->db->update('acc_opening_balances', $data);

    }

    public function checkOpeningBalanceExists($personnel_id, $coa_id)
    {
        $this->db->select('bal.*');
        $this->db->from('acc_opening_balances as bal');
        if ($personnel_id != 0) {
            $this->db->where('bal.personnel_id', $personnel_id);
        } else {
            $this->db->where('bal.coa_id', $coa_id);
        }
        $query = $this->db->get();
        return $query->row();

    }

    public function getOpeningBalances($financial_year = 1, $type = NULL)
    {
        $this->db->select('bal.*,person.name as personnelname,person.type as personneltype,person.code as personnelcode,coa.name as coaname,coa.type as coatype, coa.code as coacode');
        $this->db->from('acc_opening_balances as bal');
        $this->db->join('acc_personnel as person', 'bal.personnel_id = person.id', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'bal.coa_id = coa.id', 'left');
        if ($financial_year > 0) {
            $this->db->where('bal.financial_year', $financial_year);
        }
        if ($type == 1) {
            $this->db->where('person.type', 'customer');
        }
        if ($type == 2) {
            $this->db->where('person.type', 'supplier');
        }
        if ($type == 3) {
            $this->db->where('coa.type', '1');
        }
        if ($type == 4) {
            $this->db->where('coa.type', '2');
        }
        if ($type == 5) {
            $this->db->where('coa.type', '3');
        }
        if ($type == 6) {
            $this->db->where('coa.type', '4');
        }
        if ($type == 7) {
            $this->db->where('coa.type', '5');
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getItem($id)
    {


        $this->db->select('bal.*,person.name as personnelname,person.type as personneltype,person.code as personnelcode,coa.name as coaname,coa.type as coatype, coa.code as coacode');
        $this->db->from('acc_opening_balances as bal');
        $this->db->join('acc_personnel as person', 'bal.personnel_id = person.id', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'bal.coa_id = coa.id', 'left');

        $this->db->where('bal.id', $id);

        $query = $this->db->get();
        $result = $query->row();

        return $result;
    }

    function deleteOpeningBalance($id)
    {
        $this->db->delete('acc_opening_balances', array('id' => $id));
    }

    function getOpeningBalance($type, $id, $financial_year = 0)
    {
        $condition = array('coa_id' => $id);
        if ($type == 'personnel') {
            $condition = array('personnel_id' => $id);
        }
        if ($financial_year != 0) {
            $condition['financial_year'] = $financial_year;
        }
        $this->db->select('*')->from('acc_opening_balances')->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function batchInsert($data)
    {
        $this->db->insert_batch('acc_opening_balances', $data);

    }


}
