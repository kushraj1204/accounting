<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Payment_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->financial_year = $this->session->userdata('account')['financial_year'];
    }

    function getPaymentList($postData = null)
    {
        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value
        $mode = $postData['mode'];
        $from = $postData['from'];
        $to = $postData['to'];
        ## Total number of records without filtering
        $records = $this->getRecords('', 0, 0);
        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->getRecords($searchValue, 0, 0, $mode, $from, $to);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->getRecords($searchValue, $rowperpage, $start, $mode, $from, $to);

        $data = array();

        foreach ($records as $key => $record) {
            $record->payment_mode_details = (array)json_decode($record->payment_mode_details);
            if ($record->payment_mode == 'cheque') {
                $record->payment_mode_detail = 'Cheque date : ' . $record->payment_mode_details['cheque_date'] . ', Cheque no: ' . $record->payment_mode_details['cheque_no'];
            }
            if ($record->payment_mode == 'Prabhupay') {
                $record->payment_mode_detail = 'Transaction Id: ' . $record->payment_mode_details['TransactionId'];
            }
            $payment_date = $this->datechooser == 'bs' ? $record->payment_date_bs : $this->customlib->formatDate($record->payment_date);
//            $detail = '<a data-toggle="tooltip" title=""' . $record->payment_mode_detail . '"">' . ucfirst($record->payment_mode) . '</a>';
            $actionbuttons = '';
            if ($this->rbac->hasPrivilege('account_payments', 'can_view')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/payment/viewPayment/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
            }
            if ($this->accountlib->checkEditPermission($record->created_date, $record->financial_year, 'allow_payment_edit')) {

                if ($this->rbac->hasPrivilege('account_payments', 'can_edit') && !$record->auto_created) {

                    $actionbuttons .= '<a href="' . base_url() . 'account/payment/editPayment/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("edit") . '">
                                                    <i class="fa fa-pencil"></i>
                                                </a>';
                }

                if ($this->rbac->hasPrivilege('account_payments', 'can_delete') && !$record->auto_created) {
                    $actionbuttons .= '<a href="' . base_url() . 'account/payment/deletePayment/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("delete") . '"
                                                   onclick="return confirm(\'' . $this->lang->line("delete_confirm") . '\');">
                                                    <i class="fa fa-remove"></i>
                                                </a>';

                }
            }

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "payment_date" => $payment_date,
                "paid_to_name" => $record->paid_to_name,
                "payment_no" => $record->payment_no,
                "description" => $record->description,
                "amount" => $this->accountlib->currencyFormat($record->paid_amount, true, 2, '.', ',', true),
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

    function getRecords($searchValue, $rowperpage, $start, $mode = 'any', $from = '', $to = '')
    {
        $this->db->select('payment.*,personnel.name as paid_to_name');
        $this->db->from('acc_payment as payment');
        $this->db->join('acc_personnel as personnel', 'personnel.id = payment.paid_to', 'inner');
        $this->db->join('acc_payment_details as paydetails', 'paydetails.payment_id = payment.id', 'inner');
        if ($searchValue != '') {
            $this->db->like('personnel.name', $searchValue);
        }
        if ($mode != 'any') {
            $this->db->where('payment.payment_mode', $mode);
        }
        if ($from != '') {
            $this->db->where('payment.payment_date >=', $from);
        }
        if ($to != '') {
            $this->db->where('payment.payment_date <=', $to);
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }
        $query = $this->db->get();
        return $query->result();
    }


    function getLastId()
    {
        $this->db->select('payment.payment_no');
        $this->db->from('acc_payment as payment');
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->last_row();
        return $row->payment_no;
    }

    function addPayment($data)
    {
        $data['financial_year'] = $this->financial_year;
        $this->db->insert('acc_payment', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function updatePayment($data)
    {
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('acc_payment', $data);
    }

    function getAllPayments()
    {
        $this->db->select('payment.*,personnel.name as paid_to_name');
        $this->db->from('acc_payment as payment');
        $this->db->join('acc_personnel as personnel', 'personnel.id = payment.paid_to', 'inner');
        $this->db->join('acc_payment_details as paydetails', 'paydetails.payment_id = payment.id', 'inner');
        $query = $this->db->get();
        return $query->result();
    }

    function getPayment($id)
    {

        $this->db->select('payment.*,GROUP_CONCAT(details.journal_id) as journalids,personnel.name,personnel.email,personnel.code,coa.name as bankname');
        $this->db->from('acc_payment as payment');
        $this->db->join('acc_payment_details as details', 'payment.id = details.payment_id', 'inner');
        //$this->db->join('acc_journal as journal', 'journal.id = details.journal_id', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = payment.paid_to', 'inner');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = payment.asset_id AND payment.payment_mode="cheque"', 'left');
        $this->db->where('payment.id', $id);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
//        echo "<pre>"; print_r($query->result());exit;
        return $query->row();
    }

    function deletePayment($id)
    {
        $this->db->delete('acc_payment', array('id' => $id));
        $this->db->delete('acc_payment_details', array('payment_id' => $id));
    }

    function getDuePayments($supplier_id)
    {
        $this->db->select('payment.*');
        $this->db->from('acc_payment as payment');
        $this->db->where('payment.paid_to', $supplier_id);
        $this->db->where('payment.due =1');
        $query = $this->db->get();
        return $query->result();
    }

    function markAsDueCleared($id)
    {
        $data = array('due' => 0);
        $this->db->where('id', $id);
        $this->db->update('acc_payment', $data);
        return;
    }

    function checkNextPayment($id, $paid_to, $concernedjournal_ids)
    {
        $this->db->select('payment.*');
        $this->db->from('acc_payment as payment');
        $this->db->join('acc_payment_details as details', '(payment.id = details.payment_id) AND ((details.journal_id IN (' . implode(',', $concernedjournal_ids) . ') ) )', 'inner');
        $this->db->join('acc_personnel as personnel', 'personnel.id = payment.paid_to', 'inner');
        $this->db->where('payment.id >', $id);
        $this->db->where('payment.paid_to', $paid_to);
        $query = $this->db->get();
        return $query->result();
    }


    function getDuePayment($id)
    {

        $this->db->select('payment.*,personnel.name,personnel.email,personnel.code,coa.name as bankname,adjustedfor.total as atotal,adjustedfor.paid_amount as apaid,adjustedfor.payment_no as apayno');
        $this->db->from('acc_payment as payment');
        $this->db->join('acc_personnel as personnel', 'personnel.id = payment.paid_to', 'inner');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = payment.asset_id AND payment.payment_mode="cheque"', 'left');
        $this->db->join('acc_payment as adjustedfor', 'adjustedfor.id = payment.payment_adjusted_for', 'left');
        $this->db->where('payment.id', $id);
        $query = $this->db->get();
        return $query->row();
    }


    function insertPaymentDetails($data)
    {
        $this->db->insert('acc_payment_details', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function updatePaymentDetails($data)
    {
        if ($data['payment_id']) {
            $this->db->where('payment_id', $data['payment_id']);
        }
        $this->db->where('journal_id', $data['journal_id']);
        unset($data['payment_id']);
        unset($data['journal_id']);
        $this->db->update('acc_payment_details', $data);
    }

    function deletePaymentDetails($id)
    {
        $this->db->where_in('id', $id);
        $this->db->delete('acc_payment_details');
    }

    function markPaymentDetailsOff($id)
    {
        $this->db->where('payment_id', $id);
        $this->db->update('acc_payment_details', array('status' => 0));
    }

    function getPaymentDetail($receipt_id, $reference_id)
    {
        $this->db->select('payment.*');
        $this->db->from('acc_payment_details as payment');
        $this->db->where('payment.payment_id', $receipt_id);
        $this->db->where('payment.journal_id', $reference_id);
        $query = $this->db->get();
        return $query->row();
    }

    function updatePaymentDetail($data)
    {

        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('acc_payment_details', $data);
    }

    function getPaymentDetails($payment_id)
    {
        $this->db->select('payment.*');
        $this->db->from('acc_payment_details as payment');
        $this->db->where('payment.payment_id', $payment_id);
        $query = $this->db->get();
        return $query->result();
    }

    function getLastAdvance($personnel)
    {
        $this->db->select('*');
        $this->db->from('acc_advances');
        $this->db->where('personnel_id', $personnel);
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    function addAdvanceReceipt($datum, $advance_amount, $insertid, $paid_amt)
    {
        $old_advance = $this->getLastAdvance($datum['paid_to']);
        $old_advance_amt = 0;
        if (count($old_advance) > 0) {
            $old_advance_amt = $old_advance[0]->advance_amount;
        }
        $data = array(
            'personnel_id' => $datum['paid_to'],
            'advance_amount' => $advance_amount + $old_advance_amt,
            'advance_date' => $datum['payment_date'],
            'remarks' => json_encode(array('total_amount_paid' => $paid_amt, 'old_advance_amount' => $old_advance_amt)),
            'reference_id' => $insertid
        );
        $this->db->insert('acc_advances', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function adjustAdvance($amount, $personnel_id, $date, $insertid)
    {
        $old_advance = $this->getLastAdvance($personnel_id);
        $old_advance_amt = 0;
        if (count($old_advance) > 0) {
            $old_advance_amt = $old_advance[0]->advance_amount;
        }
        $data = array(
            'personnel_id' => $personnel_id,
            'advance_amount' => $old_advance_amt - $amount,
            'advance_date' => $date,
            'remarks' => json_encode(array('total_amount_paid' => -$amount, 'old_advance_amount' => $old_advance_amt)),
            'reference_id' => $insertid
        );
        $this->db->insert('acc_advances', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }


    function saveAdvancePayment($datum, $advance_amount)
    {
        $payment_no = $this->getNextPaymentCode();
        $paid_amt = $datum['paid_amount'] + $advance_amount;
        $datum['payment_no'] = $payment_no;
        $datum['paid_amount'] = $advance_amount;
        $datum['total'] = $advance_amount;
        $datum['description'] = 'Advance payment made';
        $insertid = $this->addPayment($datum);
        if ($insertid) {
            $this->addAdvanceReceipt($datum, $advance_amount, $insertid, $paid_amt);
            $datatemp = array(
                'journal_id' => 0,
                'payment_id' => $insertid,
                'total' => $advance_amount,
                'remaining_amount' => 0,
                'paid_amount' => $advance_amount,
                'status' => 1,
            );
            $this->insertPaymentDetails($datatemp);
        }
        $currentDateTime = $this->customlib->getCurrentTime();
        $multiplier = -1;
        $insertData[0] = array(
            'parent_id' => $insertid,
            'parent_type' => 'payment',
            'category_id' => $datum['paid_to'],
            'category_type' => 'supplier',
            'status' => 1,
            'amount' => $multiplier * ($advance_amount),
            'amount_type' => 'debit',
            'created_date' => $currentDateTime,
            'created_by' => $this->session->userdata['admin']['id'],
            'financial_year' => $this->financial_year,
        );
        $insertData[1] = array(
            'parent_id' => $insertid,
            'parent_type' => 'receipt',
            'category_id' => $datum['asset_id'],
            'category_type' => 'coa',
            'status' => 1,
            'amount' => $multiplier * ($advance_amount),
            'amount_type' => 'credit',
            'created_date' => $currentDateTime,
            'created_by' => $this->session->userdata['admin']['id'],
            'financial_year' => $this->financial_year,
        );
        if (count($insertData)) {
            $this->transaction_model->updateLogs($insertid, 'payment', $currentDateTime, $insertData);
        }
    }

    public function getNextPaymentCode()
    {
        $settings = $this->accountlib->getAccountSetting();
        $payment_prefix = '';
        if ($settings->use_general_payment_prefix) {
            $payment_prefix = $settings->general_payment_prefix;
        }
        $lastId = $this->getLastId();
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

    public function adjustJournalWithAdvance($data, $totalAmt, $advance_amount, $ref_id)
    {
        $due = ($totalAmt > $advance_amount) ? 1 : 0;
        $payment_no = $this->getNextPaymentCode();
        $datum = array(
            'payment_no' => $payment_no,
            'payment_date' => $data['invoice_date'],
            'payment_date_bs' => $data['invoice_date_bs'],
            'ref_no' => $ref_id,
            'paid_to' => $data['customer_id'],
            'payment_mode' => 'cash',
            'asset_id' => 11,
            'payment_mode_details' => '',
            'due' => $due,
            'description' => 'Auto payment generated with by adjusting advance',
            'total' => $totalAmt,
            'paid_amount' => ($due) ? $advance_amount : $totalAmt,
            'created_by' => $this->session->userdata['admin']['id'],
            'created_date' => $this->customlib->getCurrentTime(),
            'send_email' => 1,
            'bs_year' => $data['bs_year'],
            'bs_month' => $data['bs_month'],
            'bs_day' => $data['bs_day'],

        );
        $insertid = $this->addPayment($datum);
        if ($insertid) {

            $datatemp = array(
                'journal_id' => $ref_id,
                'payment_id' => $insertid,
                'total' => $totalAmt,
                'remaining_amount' => ($due) ? $totalAmt - $advance_amount : 0,
                'paid_amount' => ($due) ? $advance_amount : $totalAmt,
                'status' => ($due) ? 0 : 1,
            );
            $this->insertPaymentDetails($datatemp);

            $amt = (($due) ? $advance_amount : $totalAmt);
            $this->adjustAdvance($amt, $data['customer_id'], $datum['payment_date'], $insertid);
        }
    }


}