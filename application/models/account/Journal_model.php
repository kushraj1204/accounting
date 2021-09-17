<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Journal_model extends CI_Model
{

    function __construct()
    {

        parent::__construct();
        $this->load->library('accountlib');
        $this->tableName = 'acc_journal';
        $this->financial_year = $this->session->userdata('account')['financial_year'];
        $this->level = $this->accountlib->getAccountSetting()->level;
        //foreign models
        $this->load->model('account/transaction_model');
        $this->load->model('account/invoice_model');
        $this->load->model('account/receipt_model');
    }

    function getJournalList($postData = null)
    {

        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value

        ## Total number of records without filtering
        $records = $this->getRecords('', 0, 0);
        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->getRecords($searchValue, 0, 0);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->getRecords($searchValue, $rowperpage, $start);

        $data = array();

        foreach ($records as $key => $record) {
            $journal_date = $this->datechooser == 'bs' ? $record->entry_date_bs : $this->customlib->formatDate($record->entry_date);
            $actionbuttons = '';
            if ($this->rbac->hasPrivilege('account_journal', 'can_view')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/journal/view/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
            }
            if ($this->rbac->hasPrivilege('allow_journal_edit', 'can_edit')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/journal/edit/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("edit") . '">
                                                    <i class="fa fa-pencil"></i>
                                                </a>';
            }
            if ($this->rbac->hasPrivilege('allow_journal_edit', 'can_delete')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/journal/delete/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("delete") . '"
                                                   onclick="return confirm(\'' . $this->lang->line("delete_confirm") . '\');">
                                                    <i class="fa fa-remove"></i>
                                                </a>';
            }

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "count" => ($key + 1) + ($rowperpage * ($pagenum - 1)),
                "created_date" => $journal_date,
                "journal_no" => $record->code,
                "narration" => $record->narration,
                "amount" => $this->accountlib->currencyFormat($record->amount, true, 2, '.', ',', true),
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

    function getRecords($searchValue, $rowperpage, $start)
    {
        $this->db->select('journal.*, SUM(IF(entry.amount_type = "debit", amount, 0)) AS amount');
        $this->db->from($this->tableName . ' as journal');
        $this->db->join('acc_journal_entry as entry', 'journal.id = entry.journal_id', 'left');
        $this->db->group_by('journal.id');
        $this->db->where('journal.financial_year', $this->financial_year);
        if ($searchValue != '') {
            $this->db->like('journal.narration', $searchValue);
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }

        return $this->db->get()->result();
    }


    function getLastId()
    {
        $this->db->select('journal.id');
        $this->db->from($this->tableName . ' as journal');
        $query = $this->db->get();
        $row = $query->last_row();
        return (int)$row->id;
    }

    function getJournalDetail($id)
    {
        $this->db->select('journal.*');
        $this->db->from($this->tableName . ' as journal');
        $this->db->where('journal.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function getJournals()
    {
        $this->db->select('journal.*, SUM(IF(entry.amount_type = "debit", amount, 0)) AS amount');
        $this->db->from($this->tableName . ' as journal');
        $this->db->join('acc_journal_entry as entry', 'journal.id = entry.journal_id', 'left');
        $this->db->group_by('journal.id');
        $this->db->where('journal.financial_year', $this->financial_year);
        $query = $this->db->get();
        return $query->result();
    }

    function getJournalEntries($id, $for = NULL)
    {
        $this->db->select('entry.*,journal.narration,journal.code as journalcode,journal.due_date as journalduedate');
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.name
                WHEN entry.personnel_id !=0 THEN personnel.name
                ELSE "----"
            END as coa_title', FALSE);
        /*$this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa_categories.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);*/
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.category
                ELSE 0
            END as parent_category', FALSE);
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.code
                WHEN entry.personnel_id !=0 THEN personnel.code
            END as code', FALSE);
        $this->db->from('acc_journal_entry as entry');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory', 'left');

        if ($this->level >= 5) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory2.title
                WHEN entry.personnel_id != 0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory2', 'subcategory2.id = coa.subcategory2', 'left');
        } elseif ($this->level >= 4) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory1.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory1', 'subcategory1.id = coa.subcategory1', 'left');
        } elseif ($this->level >= 3) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN category.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as category', 'category.id = coa.category', 'left');
        }

        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        $this->db->join('acc_journal as journal', 'journal.id = entry.journal_id', 'inner');
        if (!is_array($id)) {
            $this->db->where('journal_id', $id);
        } else {
            $this->db->where_in('journal_id', $id);
        }
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function saveJournal($data)
    {
        $data['financial_year'] = $this->financial_year;

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $id = $data['id'];
            unset($data['id']);
            unset($data['code']);
            $this->db->update($this->tableName, $data);
        } else {
            $this->db->insert($this->tableName, $data);
            $id = $this->db->insert_id();
        }
        $this->updateJournalEntry($id);
        return $id;
    }

    function updateJournalEntry($id)
    {
        $input = $this->input;
        $coa_id = $input->post('coa_id[]', []);
        $coa_type = $input->post('coa_type[]', []);
        $amount = $input->post('amount[]', []);
        $amount_type = $input->post('amount_type[]', []);
        $entry_id = $input->post('entry_id[]', []);
        $is_new = $input->post('is_new[]', []);
        if (count($coa_id) == 0) {
            return false;
        }

        $insertData = [];
        $updateData = [];
        $updateIds = [];
        for ($i = 0; $i < count($coa_id); $i++) {
            $coaId = 0;
            $personnelId = 0;
            if ($coa_type[$i] == 'coa') {
                $coaId = $coa_id[$i];
            } elseif ($coa_type[$i] == 'personnel') {
                $personnelId = $coa_id[$i];
            }
            if ($is_new[$i] == 1) {
                $insertData[] = array(
                    'coa_id' => $coaId,
                    'personnel_id' => $personnelId,
                    'amount' => $amount[$i],
                    'amount_type' => $amount_type[$i],
                    'journal_id' => $id,
                );
            } else {
                $updateIds[] = $entry_id[$i];
                $updateData[] = array(
                    'coa_id' => $coaId,
                    'personnel_id' => $personnelId,
                    'amount' => $amount[$i],
                    'amount_type' => $amount_type[$i],
                    'id' => $entry_id[$i],
                );
            }
        }

        if (count($updateData) > 0) {
            $this->db->update_batch('acc_journal_entry', array_filter($updateData), 'id');
        }
        if (count($updateData) >= 0) {
            if (count($updateIds) > 0) {
                $this->db->where_not_in('id', $updateIds);
            }
            $this->db->where('journal_id', $id);
            $this->db->delete('acc_journal_entry');
        }

        if (count($insertData) > 0) {
            $this->db->insert_batch('acc_journal_entry', array_filter($insertData));
        }

        //update logs for entries
        $entries = $this->getJournalEntries($id);
        $currentDateTime = $this->customlib->getCurrentTime();
        $data = array();
        foreach ($entries as $entry) {
            $category_id = ($entry->coa_id > 0) ? $entry->coa_id : $entry->personnel_id;
            $category_type = ($entry->coa_id > 0) ? 'coa' : $entry->coa_category;
            $multiplier = $this->transaction_model->getMultiplier($category_type, $entry->parent_category, $entry->amount_type);
            $entryAmount = $multiplier * $entry->amount;
            $data[] = array(
                'parent_id' => $id,
                'parent_type' => 'journal',
                'category_id' => $category_id,
                'category_type' => $category_type,
                'status' => 1,
                'amount' => $entryAmount,
                'amount_type' => $entry->amount_type,
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
        }
        if (count($data)) {
            $this->transaction_model->updateLogs($id, 'journal', $currentDateTime, $data);
        }
    }

    function delete($id)
    {
        $this->db->delete('acc_transaction_logs', array('parent_id' => $id, 'parent_type' => 'journal'));
        $this->db->delete('acc_journal_entry', array('journal_id' => $id));
        $this->db->delete($this->tableName, array('id' => $id));
        return true;
    }

    function getDueJournalFor($personnel_id, $for = NULL)
    {
        $this->db->select('journal.*, entry.amount,person.name,person.type,"Journal" as dueType');
        $this->db->from($this->tableName . ' as journal');
        $this->db->join('acc_journal_entry as entry', 'journal.id = entry.journal_id', 'inner');
        $this->db->join('acc_personnel as person', 'person.id = entry.personnel_id', 'inner');
        $this->db->group_by('journal.id');
        $this->db->where('entry.personnel_id', $personnel_id);
        if ($for == 'payment') {
            $this->db->select('SUM(rdt.paid_amount) as partialpaidamount,GROUP_CONCAT(dt.id) as pastpaymentids, GROUP_CONCAT(dt.payment_no) as pastpaymentcodes');
            $this->db->join('acc_payment_details as rdt', '(rdt.journal_id = journal.id AND rdt.status=0)', 'left');
            $this->db->join('acc_payment as dt', '(dt.id = rdt.payment_id)', 'left');
            $this->db->where('entry.amount_type', 'credit');
        }
        if ($for == 'receipt') {
            $this->db->select('SUM(rdt.received_amount) as partialpaidamount, GROUP_CONCAT(dt.id) as pastpaymentids, GROUP_CONCAT(dt.receipt_no) as pastpaymentcodes');
            $this->db->join('acc_receipt_details as rdt', '(rdt.journal_id = journal.id AND rdt.status=0)', 'left');
            $this->db->join('acc_receipt as dt', '(dt.id = rdt.receipt_id)', 'left');
            $this->db->where('entry.amount_type', 'debit');
        }
        $this->db->where_in('journal.is_cleared', array(0, -1));
        $this->db->group_by('journal.id');
        $query = $this->db->get();
        return $query->result();
    }

    function getDueJournalEntries($ids, $personnel_id)
    {
        $this->db->select('entry.*,journal.code');
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.name
                WHEN entry.personnel_id !=0 THEN personnel.name
                ELSE "----"
            END as coa_title', FALSE);
        /*$this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa_categories.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);*/
        $this->db->from('acc_journal_entry as entry');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        $this->db->join('acc_journal as journal', 'journal.id = entry.journal_id', 'inner');
        if ($this->level >= 5) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory2.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'left');
        } elseif ($this->level >= 4) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory1.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'left');
        }

        $this->db->where_in('entry.journal_id', $ids);
        $this->db->where_in('journal.is_cleared', array(0, -1));
        $this->db->where('entry.personnel_id != "' . $personnel_id . '"');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function changeStatus($data)
    {
        $this->db->update_batch('acc_journal', $data, 'id');

        return;
    }

    function markAsUncleared($id)
    {
        $data = array('is_cleared' => 0);
        if (is_array($id)) {
            $this->db->where_in('id', $id);
        } else {
            $this->db->where('id', $id);
        }

        $this->db->update('acc_journal', $data);
        return;
    }

    function markAsCleared($id)
    {

        $data = array('is_cleared' => 1);
        if (is_array($id)) {
            $this->db->where_in('id', $id);
        } else {
            $this->db->where('id', $id);
        }
        $this->db->update('acc_journal', $data);
        return;
    }

    function markAsPartiallyPaid($id)
    {
        $data = array('is_cleared' => -1);
        if (is_array($id)) {
            $this->db->where_in('id', $id);
        } else {
            $this->db->where('id', $id);
        }
        $this->db->update('acc_journal', $data);
        return;
    }

    function updateJournalStatus($data)
    {

        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('acc_journal', $data);
        return;
    }

    function markAsCompletelyPaid($id)
    {
        $data = array('is_cleared' => 1);
        if (is_array($id)) {
            $this->db->where_in('id', $id);
        } else {
            $this->db->where('id', $id);
        }
        $this->db->update('acc_journal', $data);
        return;
    }

    function getJournalDetailsForPayment($payment_id)
    {
        $this->db->select('journal.*, entry.amount,entry.quantity,person.name,person.type,  SUM(rdt.paid_amount) as partialpaidamount, GROUP_CONCAT(dt.id) as pastpaymentids, GROUP_CONCAT(dt.payment_no) as pastpaymentcodes');
        $this->db->from('acc_payment' . ' as payment');
        $this->db->join('acc_payment_details as details', 'payment.id = details.payment_id', 'inner');
        $this->db->join('acc_journal as journal', 'journal.id = details.journal_id', 'inner');
        $this->db->join('acc_journal_entry as entry', 'journal.id = entry.journal_id', 'inner');
        $this->db->join('acc_personnel as person', 'person.id = entry.personnel_id', 'inner');
        $this->db->join('acc_payment_details as rdt', '(rdt.journal_id = journal.id AND rdt.payment_id <' . $payment_id . ')', 'left');
        $this->db->join('acc_payment as dt', '(dt.id = rdt.payment_id)', 'left');
        $this->db->group_by('journal.id');
        $this->db->where('payment.id', $payment_id);
        $this->db->where('entry.amount_type', 'credit');
        $query = $this->db->get();
        return $query->result();
    }


    function getJournalEntriesForPayment($id)
    {
        $this->db->select('entry.*,journal.narration,journal.code as journalcode,journal.due_date as journalduedate');
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.name
                WHEN entry.personnel_id !=0 THEN personnel.name
                ELSE "----"
            END as coa_title', FALSE);
        /*$this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa_categories.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);*/
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.category
                ELSE 0
            END as parent_category', FALSE);
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.code
                WHEN entry.personnel_id !=0 THEN personnel.code
            END as code', FALSE);
        $this->db->from('acc_journal_entry as entry');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        $this->db->join('acc_journal as journal', 'journal.id = entry.journal_id', 'inner');
        $this->db->join('acc_payment_details as details', 'journal.id = details.journal_id', 'inner');
        $this->db->join('acc_payment as payment', '(details.payment_id= payment.id AND payment.paid_to!=personnel_id)', 'inner');
        $this->db->where('payment.id', $id);
        $this->db->order_by('id', 'ASC');
        if ($this->level >= 5) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory2.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'left');
        } elseif ($this->level >= 4) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory1.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'left');
        }
        $query = $this->db->get();
        return $query->result();
    }

    function getJournalEntriesForReceipt($id)
    {
        $this->db->select('entry.*,journal.narration,journal.code as journalcode,journal.due_date,journal.due_date_bs');
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.name
                WHEN entry.personnel_id !=0 THEN personnel.name
                ELSE "----"
            END as coa_title', FALSE);
        /*$this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa_categories.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);*/
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.category
                ELSE 0
            END as parent_category', FALSE);
        $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN coa.code
                WHEN entry.personnel_id !=0 THEN personnel.code
            END as code', FALSE);
        $this->db->from('acc_journal_entry as entry');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        $this->db->join('acc_journal as journal', 'journal.id = entry.journal_id', 'inner');
        $this->db->join('acc_receipt_details as details', 'journal.id = details.journal_id', 'inner');
        $this->db->join('acc_receipt as receipt', '(details.receipt_id= receipt.id AND receipt.received_from!=personnel_id)', 'inner');
        $this->db->where('receipt.id', $id);
        $this->db->order_by('entry.id', 'ASC');
        $this->db->order_by('journal.id', 'ASC');
        if ($this->level >= 5) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory2.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'left');
        } elseif ($this->level >= 4) {
            $this->db->select('CASE
                WHEN entry.coa_id !=0 THEN subcategory1.title
                WHEN entry.personnel_id !=0 THEN personnel.type
                ELSE "----"
            END as coa_category', FALSE);
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'left');
        }
        $query = $this->db->get();
        return $query->result();
    }


    function getJournalDetailsForReceipt($receipt_id)
    {
        $this->db->select('journal.*, entry.amount,entry.quantity,person.name,person.type,  SUM(rdt.received_amount) as partialreceivedamount, GROUP_CONCAT(dt.id) as pastreceiptids, GROUP_CONCAT(dt.receipt_no) as pastreceiptcodes');
        $this->db->from('acc_receipt' . ' as receipt');
        $this->db->join('acc_receipt_details as details', 'receipt.id = details.receipt_id', 'inner');
        $this->db->join('acc_journal as journal', 'journal.id = details.journal_id', 'inner');
        $this->db->join('acc_journal_entry as entry', 'journal.id = entry.journal_id', 'inner');
        $this->db->join('acc_personnel as person', 'person.id = entry.personnel_id', 'inner');
        $this->db->join('acc_receipt_details as rdt', '(rdt.journal_id = journal.id AND rdt.receipt_id <' . $receipt_id . ')', 'left');
        $this->db->join('acc_receipt as dt', '(dt.id = rdt.receipt_id)', 'left');
        $this->db->group_by('journal.id');
        $this->db->order_by('journal.id', 'ASC');
        $this->db->where('receipt.id', $receipt_id);
        $this->db->where('entry.amount_type', 'debit');
        $query = $this->db->get();
        return $query->result();
    }


}