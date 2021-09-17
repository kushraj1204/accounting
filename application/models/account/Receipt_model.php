<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Receipt_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('accountlib');
        $this->financial_year = $this->session->userdata('account')['financial_year'];
        $this->load->model('account/invoice_model');
        $this->load->model('account/account_COA_model');
        $this->load->model('student_fee_advance_model');
    }

    function getLastId()
    {
        $this->db->select('receipt.receipt_no');
        $this->db->from('acc_receipt as receipt');
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->last_row();
        return $row->receipt_no;
    }

    function addReceipt($data)
    {
        $data['financial_year'] = $this->financial_year;
        $this->db->insert('acc_receipt', $data);
        return $this->db->insert_id();
    }

    function updateReceipt($data)
    {
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('acc_receipt', $data);
    }

    function getReceiptList($postData = null)
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
            $record->receipt_mode_details = (array)json_decode($record->receipt_mode_details);
            if ($record->receipt_mode == 'cheque') {
                $record->receipt_mode_detail = 'Cheque date : ' . $record->receipt_mode_details['cheque_date'] . ', Cheque no: ' . $record->receipt_mode_details['cheque_no'];
            }
            if ($record->receipt_mode == 'Prabhupay') {
                $record->receipt_mode_detail = 'Transaction Id: ' . $record->receipt_mode_details['TransactionId'];
            }
            $receipt_date = $this->datechooser == 'bs' ? $record->receipt_date_bs : $this->customlib->formatDate($record->receipt_date);
            $detail = '<a data-toggle="tooltip" title=""' . $record->receipt_mode_detail . '"">' . ucfirst($record->receipt_mode) . '</a>';
            $actionbuttons = '';
            if ($this->rbac->hasPrivilege('account_receipts', 'can_view')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/receipt/viewReceipt/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
            }
            if ($this->accountlib->checkEditPermission($record->created_date, $record->financial_year, 'allow_receipt_edit')) {

                if ($this->rbac->hasPrivilege('account_receipts', 'can_edit') && !$record->auto_created) {

                    $actionbuttons .= '<a href="' . base_url() . 'account/receipt/editReceipt/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("edit") . '">
                                                    <i class="fa fa-pencil"></i>
                                                </a>';
                }

                if ($this->rbac->hasPrivilege('account_receipts', 'can_delete') && !$record->auto_created) {
                    $actionbuttons .= '<a href="' . base_url() . 'account/receipt/deleteReceipt/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("delete") . '"
                                                   onclick="return confirm(\'' . $this->lang->line("delete_confirm") . '\');">
                                                    <i class="fa fa-remove"></i>
                                                </a>';

                }
            }

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "receipt_date" => $receipt_date,
                "received_from" => $record->received_from_name,
                "receipt_no" => $record->receipt_no,
                "receipt_mode" => $detail,
                "description" => $record->description,
                "amount" => $this->accountlib->currencyFormat($record->received_amount, true, 2, '.', ',', true),
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
        $this->db->select('receipt.*,personnel.name as received_from_name');
        $this->db->from('acc_receipt as receipt');
        $this->db->join('acc_personnel as personnel', 'personnel.id = receipt.received_from', 'inner');
        $this->db->join('acc_receipt_details as recdetails', 'recdetails.receipt_id = receipt.id', 'inner');
        $this->db->group_by('receipt.id');
        if ($searchValue != '') {
            $this->db->like('personnel.name', $searchValue);
        }
        if ($mode != 'any') {
            $this->db->where('receipt.receipt_mode', $mode);
        }
        if ($from != '') {
            $this->db->where('receipt.receipt_date >=', $from);
        }
        if ($to != '') {
            $this->db->where('receipt.receipt_date <=', $to);
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }
        $query = $this->db->get();


        return $query->result();
    }


    function getFeeCollectionsList($postData = null)
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
        $records = $this->getFeeRecords('', 0, 0);
        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->getFeeRecords($searchValue, 0, 0, $mode, $from, $to);
        $totalRecordwithFilter = count($records);

        $totalCollections = 0;
        foreach ($records as $key => $record) {
            $totalCollections += $record->received_amount;
        }

        ## Fetch records
        $records = $this->getFeeRecords($searchValue, $rowperpage, $start, $mode, $from, $to);

        $data = array();
        foreach ($records as $key => $record) {
            $record->receipt_mode_details = (array)json_decode($record->receipt_mode_details);
            if ($record->receipt_mode == 'cheque') {
                $record->receipt_mode_detail = 'Cheque date : ' . $record->receipt_mode_details['cheque_date'] . ', Cheque no: ' . $record->receipt_mode_details['cheque_no'];
            }
            if ($record->receipt_mode == 'Prabhupay') {
                $record->receipt_mode_detail = 'Transaction Id: ' . $record->receipt_mode_details['TransactionId'];
            }
            $receipt_date = $this->datechooser == 'bs' ? $record->receipt_date_bs : $this->customlib->formatDate($record->receipt_date);
            $detail = '<a data-toggle="tooltip" title=""' . $record->receipt_mode_detail . '"">' . ucfirst($record->receipt_mode) . '</a>';
            $actionbuttons = '';
            if ($this->rbac->hasPrivilege('account_receipts', 'can_view')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/receipt/viewReceipt/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
            }

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "receipt_date" => $receipt_date,
                "received_from" => $record->received_from_name,
                "receipt_no" => $record->receipt_no,
                "receipt_mode" => $detail,
                "description" => $record->description,
                "amount" => $this->accountlib->currencyFormat($record->received_amount, true, 2, '.', ',', true),
                "action" => $actionbuttons

            );
        }
//        echopreexit($records);
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
            'totalCollections' => $this->accountlib->currencyFormat($totalCollections, true, 2, '.', ',', true)
        );

        return $response;
    }

    function getFeeRecords($searchValue, $rowperpage, $start, $mode = 'any', $from = '', $to = '')
    {
        $this->db->select('receipt.*,personnel.name as received_from_name');
        $this->db->select('sessionfees.session_id');
        $this->db->from('acc_receipt as receipt');
        $this->db->join('acc_personnel as personnel', 'personnel.id = receipt.received_from', 'inner');
        $this->db->join('acc_receipt_details as recdetails', 'recdetails.receipt_id = receipt.id', 'inner');
        $this->db->join('student_fees_payment_history as paymenthistory', 'receipt.ref_no = paymenthistory.id', 'inner');
        $this->db->join('student_session_fees as sessionfees', 'sessionfees.id = paymenthistory.student_session_fees_id', 'inner');
        $this->db->where('(receipt.auto_created = 1 OR receipt.fee_id > 0)');
        $this->db->group_by('receipt.id');
        if ($searchValue != '') {
            $this->db->like('personnel.name', $searchValue);
        }
        if ($mode != 'any') {
            $this->db->where('receipt.receipt_mode', $mode);
        }
        if ($from != '') {
            $this->db->where('receipt.receipt_date >=', $from);
        }
        if ($to != '') {
            $this->db->where('receipt.receipt_date <=', $to);
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }
        $query = $this->db->get();


        return $query->result();
    }


    function getAllReceipts()
    {
        $this->db->select('receipt.*,personnel.name as received_from_name');
        $this->db->from('acc_receipt as receipt');
        $this->db->join('acc_personnel as personnel', 'personnel.id = receipt.received_from', 'inner');
        $this->db->join('acc_receipt_details as recdetails', 'recdetails.receipt_id = receipt.id', 'inner');
        $this->db->group_by('receipt.id');
        $query = $this->db->get();
        return $query->result();
    }

    function getStudentFeeReceipts($session_id)
    {

        $this->db->select('receipt.*,personnel.name as received_from_name');
        $this->db->select('sessionfees.session_id');
        $this->db->from('acc_receipt as receipt');
        $this->db->join('acc_personnel as personnel', 'personnel.id = receipt.received_from', 'inner');
        $this->db->join('acc_receipt_details as recdetails', 'recdetails.receipt_id = receipt.id', 'inner');
        $this->db->join('student_fees_payment_history as paymenthistory', 'receipt.ref_no = paymenthistory.id', 'inner');
        $this->db->join('student_session_fees as sessionfees', 'sessionfees.id = paymenthistory.student_session_fees_id', 'inner');
        $this->db->where('(receipt.auto_created = 1 OR receipt.fee_id > 0)');
        if (isset($session_id)) {
            $this->db->where('sessionfees.session_id', $session_id);
        }
        $this->db->group_by('receipt.id');
        $query = $this->db->get();
        return $query->result();
    }

    function getReceipt($id)
    {
        $this->db->select('receipt.*,GROUP_CONCAT(details.journal_id) as journalids,GROUP_CONCAT(details.invoice_id) as invoiceids,personnel.name,personnel.email,personnel.code,bank.name as bankname');
        $this->db->from('acc_receipt as receipt');
        $this->db->join('acc_receipt_details as details', '(receipt.id = details.receipt_id)', 'inner');
        //        $this->db->join('acc_invoice as invoice', '(invoice.id = receipt.invoice_id AND receipt.invoice_id != 0)', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = receipt.received_from', 'inner');
        $this->db->join('acc_chart_of_accounts_detail as bank', 'bank.id = receipt.asset_id AND receipt.receipt_mode="cheque"', 'left');
        //        $this->db->join('acc_receipt as adjustedfor', 'adjustedfor.id = receipt.receipt_adjusted_for', 'left');
        $this->db->where('receipt.id', $id);
        $this->db->group_by('receipt.id');
        $query = $this->db->get();
        return $query->row();
    }


    function deleteReceipt($id)
    {
        $this->db->delete('acc_receipt', array('id' => $id));
        $this->db->delete('acc_receipt_details', array('receipt_id' => $id));
    }

    function getDueReceipts($customer_id)
    {
        $this->db->select('receipt.*');
        $this->db->from('acc_receipt as receipt');
        $this->db->where('receipt.received_from', $customer_id);
        $this->db->where('receipt.due', 1);
        $query = $this->db->get();
        return $query->result();
    }

    function markAsDueCleared($id)
    {
        $data = array('due' => 0);
        $this->db->where('id', $id);
        $this->db->update('acc_receipt', $data);

        $data = array('due' => 0);
        $this->db->where('receipt_id', $id);
        $this->db->update('acc_receipt_details', $data);
        return;
    }

    function checkNextReceipt($id, $received_from, $concernedjournal_ids, $concernedinvoice_ids)
    {
        $this->db->select('receipt.*');
        $this->db->from('acc_receipt as receipt');
        $this->db->join('acc_receipt_details as details', '(receipt.id = details.receipt_id) AND ((details.journal_id IN (' . implode(',', $concernedjournal_ids) . ')) OR (details.invoice_id IN (' . implode(',', $concernedinvoice_ids) . ')) )', 'inner');
        $this->db->join('acc_personnel as personnel', 'personnel.id = receipt.received_from', 'inner');
        $this->db->where('receipt.id >', $id);
        $this->db->where('receipt.received_from', $received_from);
        $query = $this->db->get();
        return $query->result();
    }

    function insertReceiptDetails($data)
    {
        $this->db->insert('acc_receipt_details', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function updateReceiptDetails($data)
    {
        if ($data['payment_id']) {
            $this->db->where('receipt_id', $data['receipt_id']);
        }
        $this->db->where('journal_id', $data['journal_id']);
        unset($data['receipt_id']);
        unset($data['journal_id']);
        $this->db->update('acc_receipt_details', $data);
    }

    function updateReceiptDetail($data)
    {

        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('acc_receipt_details', $data);
    }

    function deleteReceiptDetails($id)
    {
        $this->db->where_in('id', $id);
        $this->db->delete('acc_receipt_details');
    }

    function getJournalDetailsForReceipt($receipt_id)
    {
        $this->db->select('journal.*, entry.amount,entry.quantity,person.name,person.type,invoice.*');
        $this->db->from('acc_receipt' . ' as receipt');
        $this->db->join('acc_receipt_details as details', 'receipt.id = details.receipt_id', 'inner');
        $this->db->join('acc_journal as journal', 'journal.id = details.journal_id', 'left');
        $this->db->join('acc_journal_entry as entry', 'journal.id = entry.journal_id', 'left');
        $this->db->join('acc_personnel as person', 'person.id = receipt.received_from', 'inner');
        $this->db->group_by('journal.id');
        $this->db->where('receipt.id', $receipt_id);
        $query = $this->db->get();
        return $query->result();
    }


    function getInvoicesDetailsForReceipt($receipt_id)
    {
        $this->db->select('journal.*, entry.amount,entry.quantity,person.name,person.type,invoice.*');
        $this->db->from('acc_receipt' . ' as receipt');
        $this->db->join('acc_receipt_details as details', 'receipt.id = details.receipt_id', 'inner');
        $this->db->join('acc_invoice as invoice', 'invoice.id = details.invoice_id', 'left');
        $this->db->join('acc_personnel as person', 'person.id = receipt.received_from', 'inner');
        $this->db->group_by('journal.id');
        $this->db->where('receipt.id', $receipt_id);
        $query = $this->db->get();
        return $query->result();
    }

    function getInvoiceEntriesForReceipt($receipt_id)
    {
        $this->db->select('entry.*,invoice.code as invoicecode');
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
        $this->db->from('acc_invoice_entry as entry');
        $this->db->join('acc_invoice as invoice', 'invoice.id = entry.invoice_id', 'inner');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        $this->db->join('acc_receipt_details as receiptdetails', 'receiptdetails.invoice_id = invoice.id', 'inner');
        $this->db->join('acc_receipt as receipt', 'receipt.id = receiptdetails.receipt_id', 'inner');

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

        $this->db->where('receipt.id', $receipt_id);
        $this->db->order_by('invoice.id', 'ASC');

        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    function getInvoiceDetailsForReceipt($receipt_id)
    {
        $this->db->select('"Invoice" as dueType, invoice.description as narration, invoice.*,ABS(tlogs.amount) as amount , SUM(rdt.received_amount) as partialpaidamount, GROUP_CONCAT(dt.id) as pastpaymentids, GROUP_CONCAT(dt.receipt_no) as pastpaymentcodes');
        $this->db->from('acc_receipt' . ' as receipt');
        $this->db->join('acc_receipt_details as details', 'receipt.id = details.receipt_id', 'inner');
        $this->db->join('acc_invoice_entry as entry', 'details.invoice_id = entry.invoice_id', 'inner');
        $this->db->join('acc_invoice as invoice', 'invoice.id = entry.invoice_id', 'inner');
        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = entry.coa_id', 'left');
        //$this->db->join('acc_coa_categories as coa_categories', 'coa_categories.id = coa.subcategory', 'left');
        //        $this->db->join('acc_personnel as personnel', 'personnel.id = entry.personnel_id', 'left');
        $this->db->join('acc_transaction_logs as tlogs', 'tlogs.parent_id = invoice.id AND tlogs.parent_type="Invoice" AND tlogs.category_type IN ("customer","supplier") ', 'inner');
        $this->db->join('acc_receipt_details as rdt', '(invoice.id = rdt.invoice_id AND rdt.receipt_id<' . $receipt_id . ')', 'left');
        $this->db->join('acc_receipt as dt', '(dt.id = rdt.receipt_id)', 'left');
        $this->db->where('receipt.id', $receipt_id);
        $this->db->group_by('id');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    function getReceiptDetails($receipt_id)
    {
        $this->db->select('receipt.*');
        $this->db->from('acc_receipt_details as receipt');
        $this->db->where('receipt.receipt_id', $receipt_id);
        $query = $this->db->get();
        return $query->result();
    }

    function getReceiptDetail($receipt_id, $reference_id, $reference_type)
    {
        $this->db->select('receipt.*');
        $this->db->from('acc_receipt_details as receipt');
        $this->db->where('receipt.receipt_id', $receipt_id);
        if ($reference_type == 'Journal') {
            $this->db->where('receipt.journal_id', $reference_id);
        }
        if ($reference_type == 'Invoice') {
            $this->db->where('receipt.invoice_id', $reference_id);
        }

        $query = $this->db->get();
        return $query->row();
    }

    function markReceiptDetailsOff($id)
    {
        $this->db->where('receipt_id', $id);
        $this->db->update('acc_receipt_details', array('status' => 0));
    }

    public function adjustOutStandingFees($data, $remainingfee)
    {
        $advance_amount = $data['advance_amount'];
        $remainingfee = ($data['advance_amount'] < $remainingfee) ? $data['advance_amount'] : $remainingfee;
        $data['paid_amount'] = $remainingfee;
        $customer_id = $this->invoice_model->getConcernedCustomerId($data['invoice_id']);
        $dueinvoices = $this->invoice_model->getDueInvoiceFor($customer_id, true, false, 14);
        $sendEmail = 1;
        $dueinvoiceids = array(0);
        $totalpayable = 0;
        $maxvaluedinvoice = 0;
        foreach ($dueinvoices as $dueinvoice) {
            $temppastpaymentids = explode(',', $dueinvoice->pastpaymentids);
            $temppastpaymentcodes = explode(',', $dueinvoice->pastpaymentcodes);
            $tempuniquepastpaymentcodes = array_unique($temppastpaymentcodes);
            $tempuniquepastpaymentids = array_unique($temppastpaymentids);
            $tempfullsize = sizeof($temppastpaymentids);
            $tempactualsize = sizeof($tempuniquepastpaymentids);
            if (sizeof($temppastpaymentids) > 1) {
                $dueinvoice->partialpaidamount = $dueinvoice->partialpaidamount / ($tempfullsize / $tempactualsize);
            }
            $totalpayable = $totalpayable + ($dueinvoice->amount - $dueinvoice->partialpaidamount);
            $dueinvoice->payableamount = (float)$dueinvoice->amount - (float)$dueinvoice->partialpaidamount;
            array_push($dueinvoiceids, $dueinvoice->id);
            $maxvaluedinvoice = $dueinvoice->id;
            if ($totalpayable >= $data['paid_amount']) {
                break;
            }
        }

        $payment_details = json_decode($data['payment_detail']);
        $payment_details->paid_amount = $data['advance_amount'];

        $cashid = 11;
        $receipt_mode = $payment_details->payment_mode;
        $receipt_mode_details = '';
        if (strtolower($receipt_mode) == 'cash') {
            $asset_id = $cashid;
            $receipt_mode_details = json_encode(array('receipt_mode' => 'cash'));
        }
        if (strtolower($receipt_mode) == 'cheque') {
            $cheque_no = $payment_details->cheque_number;
            $cheque_date = $payment_details->cheque_date;
            $cheque_date_bs = $payment_details->cheque_date_bs;
            $receipt_mode_details = json_encode(array('cheque_date' => $cheque_date, 'cheque_no' => $cheque_no, 'cheque_date_bs' => $cheque_date_bs));
            $bank = $this->account_COA_model->getDefaultBank(); //temporary assign of bank
            $asset_id = $bank;
        }

        if (strtolower($receipt_mode) == 'Prabhupay') {
            $prabhupayaccount = 10;
            $asset_id = $prabhupayaccount;
            $receipt_mode_details = json_encode($payment_details->detail);
        }

        $dueamount = $totalpayable - $remainingfee;
        $due = ($dueamount > 0) ? 1 : 0;
        $receipt_no = $this->getNextReceiptCode();
        $datum = array(
            'receipt_no' => $receipt_no,
            'receipt_date' => $data['payment_date'],
            'receipt_date_bs' => $data['payment_year_bs'] . '-' . $data['payment_month_bs'] . '-' . $data['payment_day_bs'],
            'ref_no' => $data['invoice_id'],
            'received_from' => $customer_id,
            'receipt_mode' => $receipt_mode,
            'asset_id' => $asset_id,
            'receipt_mode_details' => $receipt_mode_details,
            'due' => $due,
            'description' => 'Outstanding fees paid',
            'total' => $totalpayable,
            'received_amount' => $data['paid_amount'],
            'created_by' => $this->session->userdata['admin']['id'],
            'created_date' => $this->customlib->getCurrentTime(),
            'send_email' => $sendEmail,
            'bs_year' => $data['payment_year_bs'],
            'bs_month' => $data['payment_month_bs'],
            'bs_day' => $data['payment_day_bs'],
            'auto_created' => 1

        );
        $insertid = $this->addReceipt($datum);
        if ($insertid) {
            foreach ($dueinvoices as $dueinvoice) {
                if (in_array($dueinvoice->id, $dueinvoiceids)) {
                    $partial = 0;
                    if ($due == 1 && $maxvaluedinvoice == $dueinvoice->id) {
                        $partial = 1;
                    }
                    $datatemp = array(
                        'journal_id' => 0,
                        'invoice_id' => $dueinvoice->id,
                        'receipt_id' => $insertid,
                        'total' => $dueinvoice->amount,
                        'remaining_amount' => $dueinvoice->payableamount,
                        'received_amount' => ($partial) ? ($dueinvoice->payableamount - $dueamount) : $dueinvoice->payableamount,
                        'status' => ($partial) ? 0 : 1,
                    );
                    $this->insertReceiptDetails($datatemp);
                }
            }

            $currentDateTime = $this->customlib->getCurrentTime();
            $multiplier = -1;

            $insertData[0] = array(
                'parent_id' => $insertid,
                'parent_type' => 'receipt',
                'category_id' => $customer_id,
                'category_type' => 'customer',
                'status' => 1,
                'amount' => $multiplier * ($data['paid_amount']),
                'amount_type' => 'credit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            $insertData[1] = array(
                'parent_id' => $insertid,
                'parent_type' => 'receipt',
                'category_id' => $asset_id,
                'category_type' => 'coa',
                'status' => 1,
                'amount' => ($data['paid_amount']),
                'amount_type' => 'debit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            if (count($insertData)) {
                $this->transaction_model->updateLogs($insertid, 'payment', $currentDateTime, $insertData);
            }

            if (isset($dueinvoiceids)) {
                $this->invoice_model->markAsCleared($dueinvoiceids);
                if ($due == 1) {
                    $this->invoice_model->markAsPartiallyPaid($maxvaluedinvoice);
                }
            }
            $remaining_advance = $advance_amount - $remainingfee;
            $prev_advance = $this->student_fee_advance_model->getAdvance($data['student_id']);
            $this->student_fee_advance_model->saveAdvance(array(
                'student_id' => $data['student_id'],
                'advance_amount' => $remaining_advance,
                'student_session_fees_id' => $prev_advance->student_session_fees_id,
                'remarks' => 'Remaining advance after clearing outstanding fees',
                'extra_data' => json_encode(array('paid_amount' => $remainingfee, 'previous_advance_amount' => $prev_advance->advance_amount))
            ), true);
            Neema_events::trigger('on_fee_advance_deduction', array(
                'student_id' => $data['student_id'],
                'student_session_fees_id' => $prev_advance->student_session_fees_id,
                'previous_advance_amount' => $prev_advance->advance_amount,
                'remaining_advance_amount' => $remaining_advance,
                'deducted_amount' => $remainingfee,
            ));
            if ($sendEmail) {
                //send email
            }
        }
    }


    //auto adjusting invoices/journals if receipt already there
    public function adjustInvoiceWithAdvance($data, $totalAmt, $advance_amount, $ref_id)
    {

        $due = ($totalAmt > $advance_amount) ? 1 : 0;
        $receipt_no = $this->getNextReceiptCode();
        $datum = array(
            'receipt_no' => $receipt_no,
            'receipt_date' => $data['invoice_date'],
            'receipt_date_bs' => $data['invoice_date_bs'],
            'ref_no' => $ref_id,
            'received_from' => $data['customer_id'],
            'receipt_mode' => 'cash',
            'asset_id' => 11,
            'receipt_mode_details' => '',
            'due' => $due,
            'description' => 'Auto receipt generated with by adjusting advance',
            'total' => $totalAmt,
            'received_amount' => ($due) ? $advance_amount : $totalAmt,
            'created_by' => $this->session->userdata['admin']['id'],
            'created_date' => $this->customlib->getCurrentTime(),
            'send_email' => 1,
            'bs_year' => $data['bs_year'],
            'bs_month' => $data['bs_month'],
            'bs_day' => $data['bs_day'],
            'auto_created' => 1

        );
        $insertid = $this->addReceipt($datum);
        if ($insertid) {

            $datatemp = array(
                'journal_id' => ($data['type'] == 'journal') ? $ref_id : 0,
                'invoice_id' => ($data['type'] == 'invoice') ? $ref_id : 0,
                'receipt_id' => $insertid,
                'total' => $totalAmt,
                'remaining_amount' => ($due) ? $totalAmt - $advance_amount : 0,
                'received_amount' => ($due) ? $advance_amount : $totalAmt,
                'status' => ($due) ? 0 : 1,
            );
            $this->insertReceiptDetails($datatemp);

            $currentDateTime = $this->customlib->getCurrentTime();
            $multiplier = -1;

            $insertData[0] = array(
                'parent_id' => $insertid,
                'parent_type' => 'receipt',
                'category_id' => $data['customer_id'],
                'category_type' => 'customer',
                'status' => 1,
                'amount' => $multiplier * (($due) ? $advance_amount : $totalAmt),
                'amount_type' => 'credit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            $insertData[1] = array(
                'parent_id' => $insertid,
                'parent_type' => 'receipt',
                'category_id' => 11,
                'category_type' => 'coa',
                'status' => 1,
                'amount' => (($due) ? $advance_amount : $totalAmt),
                'amount_type' => 'debit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            if (count($insertData)) {
//                $this->transaction_model->updateLogs($insertid, 'payment', $currentDateTime, $insertData); as it is advance deduction we actually dont receive the amount
                $amt = (($due) ? $advance_amount : $totalAmt);
                $this->adjustAdvance($amt, $data['customer_id'], $datum['receipt_date'], $insertid);
            }
        }
    }


    function saveAdvancePayment($data)
    {
        $customer_id = $this->invoice_model->getConcernedCustomerId($data['invoice_id']);
        $accruedFeeCarriedOver = $this->invoice_model->checkOutstandingInvoiceForStudent($data['student_id']);
        $remainingFee = 0;
        if ($accruedFeeCarriedOver) {
            $remainingFee = $accruedFeeCarriedOver->remaining_amount - $accruedFeeCarriedOver->received_amount;
            $this->adjustOutStandingFees($data, $remainingFee);
        }
        if ($data['advance_amount'] <= $remainingFee) {
            return;
        } else {

            $payment_details = json_decode($data['payment_detail']);
            $payment_details->paid_amount = $data['advance_amount'];

            $cashid = 11;
            $receipt_mode = $payment_details->payment_mode;
            $receipt_mode_details = '';
            if (strtolower($receipt_mode) == 'cash') {
                $asset_id = $cashid;
                $receipt_mode_details = json_encode(array('receipt_mode' => 'cash'));
            }
            if (strtolower($receipt_mode) == 'cheque') {
                $cheque_no = $payment_details->cheque_number;
                $cheque_date = $payment_details->cheque_date;
                $cheque_date_bs = $payment_details->cheque_date_bs;
                $receipt_mode_details = json_encode(array('cheque_date' => $cheque_date, 'cheque_no' => $cheque_no, 'cheque_date_bs' => $cheque_date_bs));
                $bank = $this->account_COA_model->getDefaultBank(); //temporary assign of bank
                $asset_id = $bank;
            }

            if (strtolower($receipt_mode) == 'Prabhupay') {
                $prabhupayaccount = 10;
                $asset_id = $prabhupayaccount;
                $receipt_mode_details = json_encode($payment_details->detail);
            }
            $advance_amount = $data['advance_amount'] - $remainingFee;
            $due = 0;
            $receipt_no = $this->getNextReceiptCode();
            $datum = array(
                'receipt_no' => $receipt_no,
                'receipt_date' => $data['payment_date'],
                'receipt_date_bs' => $data['payment_year_bs'] . '-' . $data['payment_month_bs'] . '-' . $data['payment_day_bs'],
                'ref_no' => $data['invoice_id'],
                'received_from' => $customer_id,
                'receipt_mode' => $receipt_mode,
                'asset_id' => $asset_id,
                'receipt_mode_details' => $receipt_mode_details,
                'due' => $due,
                'description' => 'Advance amount paid',
                'total' => $advance_amount,
                'received_amount' => $advance_amount,
                'created_by' => $this->session->userdata['admin']['id'],
                'created_date' => $this->customlib->getCurrentTime(),
                'send_email' => 1,
                'bs_year' => $data['payment_year_bs'],
                'bs_month' => $data['payment_month_bs'],
                'bs_day' => $data['payment_day_bs'],
                'auto_created' => 1

            );
            $insertid = $this->addReceipt($datum);
            if ($insertid) {
                $datatemp = array(
                    'journal_id' => 0,
                    'invoice_id' => 0,
                    'receipt_id' => $insertid,
                    'total' => $advance_amount,
                    'remaining_amount' => 0,
                    'received_amount' => $advance_amount,
                    'status' => 1,
                );
                $this->insertReceiptDetails($datatemp);
            }


            $currentDateTime = $this->customlib->getCurrentTime();
            $multiplier = -1;

            $insertData[0] = array(
                'parent_id' => $insertid,
                'parent_type' => 'receipt',
                'category_id' => $customer_id,
                'category_type' => 'customer',
                'status' => 1,
                'amount' => $multiplier * ($advance_amount),
                'amount_type' => 'credit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            $insertData[1] = array(
                'parent_id' => $insertid,
                'parent_type' => 'receipt',
                'category_id' => $asset_id,
                'category_type' => 'coa',
                'status' => 1,
                'amount' => ($advance_amount),
                'amount_type' => 'debit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            if (count($insertData)) {
                $this->transaction_model->updateLogs($insertid, 'receipt', $currentDateTime, $insertData);
            }
        }
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

    function addAdvanceReceipt($datum, $advance_amount, $insertid, $received_amt)
    {
        $old_advance = $this->getLastAdvance($datum['received_from']);
        $old_advance_amt = 0;
        if (count($old_advance) > 0) {
            $old_advance_amt = $old_advance[0]->advance_amount;
        }
        $data = array(
            'personnel_id' => $datum['received_from'],
            'advance_amount' => $advance_amount + $old_advance_amt,
            'advance_date' => $datum['receipt_date'],
            'remarks' => json_encode(array('total_amount_received' => $received_amt, 'old_advance_amount' => $old_advance_amt)),
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
            'remarks' => json_encode(array('total_amount_received' => -$amount, 'old_advance_amount' => $old_advance_amt)),
            'reference_id' => $insertid
        );
        $this->db->insert('acc_advances', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function saveAdvanceReceipt($datum, $advance_amount)
    {
        $receipt_no = $this->getNextReceiptCode();
        $received_amt = $datum['received_amount'] + $advance_amount;
        $datum['receipt_no'] = $receipt_no;
        $datum['received_amount'] = $advance_amount;
        $datum['total'] = $advance_amount;
        $datum['description'] = 'Advance payment received';
        $insertid = $this->addReceipt($datum);
        if ($insertid) {
            $this->addAdvanceReceipt($datum, $advance_amount, $insertid, $received_amt);
            $datatemp = array(
                'journal_id' => 0,
                'invoice_id' => 0,
                'receipt_id' => $insertid,
                'total' => $advance_amount,
                'remaining_amount' => 0,
                'received_amount' => $advance_amount,
                'status' => 1,
            );
            $this->insertReceiptDetails($datatemp);
        }
        $currentDateTime = $this->customlib->getCurrentTime();
        $multiplier = -1;
        $insertData[0] = array(
            'parent_id' => $insertid,
            'parent_type' => 'receipt',
            'category_id' => $datum['received_from'],
            'category_type' => 'customer',
            'status' => 1,
            'amount' => $multiplier * ($advance_amount),
            'amount_type' => 'credit',
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
            'amount' => ($advance_amount),
            'amount_type' => 'debit',
            'created_date' => $currentDateTime,
            'created_by' => $this->session->userdata['admin']['id'],
            'financial_year' => $this->financial_year,
        );
        if (count($insertData)) {
            $this->transaction_model->updateLogs($insertid, 'receipt', $currentDateTime, $insertData);
        }
    }


    public function saveFeePaymentAsReceipt($data)
    {
        $payment_details = json_decode($data['payment_detail']);
        $customer_id = $this->invoice_model->getConcernedCustomerId($data['invoice_id']);
        $dueinvoices = $this->invoice_model->getDueInvoiceFor($customer_id, true, true);
        $sendEmail = 1;
        $dueinvoiceids = array(0);
        $totalpayable = 0;
        $maxvaluedinvoice = 0;
        foreach ($dueinvoices as $dueinvoice) {
            $temppastpaymentids = explode(',', $dueinvoice->pastpaymentids);
            $temppastpaymentcodes = explode(',', $dueinvoice->pastpaymentcodes);
            $tempuniquepastpaymentcodes = array_unique($temppastpaymentcodes);
            $tempuniquepastpaymentids = array_unique($temppastpaymentids);
            $tempfullsize = sizeof($temppastpaymentids);
            $tempactualsize = sizeof($tempuniquepastpaymentids);
            if (sizeof($temppastpaymentids) > 1) {
                $dueinvoice->partialpaidamount = $dueinvoice->partialpaidamount / ($tempfullsize / $tempactualsize);
            }
            $totalpayable = $totalpayable + ($dueinvoice->amount - $dueinvoice->partialpaidamount);
            $dueinvoice->payableamount = (float)$dueinvoice->amount - (float)$dueinvoice->partialpaidamount;
            array_push($dueinvoiceids, $dueinvoice->id);
            $maxvaluedinvoice = $dueinvoice->id;
            if ($totalpayable >= $data['paid_amount']) {
                break;
            }
        }
        $cashid = 11;
        $receipt_mode = $payment_details->payment_mode;
        $receipt_mode_details = '';
        if (strtolower($receipt_mode) == 'cash') {
            $asset_id = $cashid;
            $receipt_mode_details = json_encode(array('receipt_mode' => 'cash'));
        }
        if (strtolower($receipt_mode) == 'cheque') {
            $cheque_no = $payment_details->cheque_number;
            $cheque_date = $payment_details->cheque_date;
            $cheque_date_bs = $payment_details->cheque_date_bs;
            $receipt_mode_details = json_encode(array('cheque_date' => $cheque_date, 'cheque_no' => $cheque_no, 'cheque_date_bs' => $cheque_date_bs));
            $bank = $this->account_COA_model->getDefaultBank(); //temporary assign of bank
            $asset_id = $bank;
        }

        if (strtolower($receipt_mode) == 'prabhupay') {
            $prabhupayaccount = 10;
            $asset_id = $prabhupayaccount;
            $receipt_mode_details = json_encode($payment_details->detail);
        }

        $dueamount = $totalpayable - $data['paid_amount'];
        $due = ($dueamount > 0) ? 1 : 0;
        $receipt_no = $this->getNextReceiptCode();
        $datum = array(
            'receipt_no' => $receipt_no,
            'receipt_date' => $data['payment_date'],
            'receipt_date_bs' => $data['payment_year_bs'] . '-' . $data['payment_month_bs'] . '-' . $data['payment_day_bs'],
            'ref_no' => $data['id'],
            'received_from' => $customer_id,
            'receipt_mode' => $receipt_mode,
            'asset_id' => $asset_id,
            'receipt_mode_details' => $receipt_mode_details,
            'due' => $due,
            'description' => 'Fees paid' . ($data['advance_used'] ? ' using advance amount' : ''),
            'total' => $totalpayable,
            'received_amount' => $data['paid_amount'],
            'created_by' => $this->session->userdata['admin']['id'],
            'created_date' => $this->customlib->getCurrentTime(),
            'send_email' => $sendEmail,
            'bs_year' => $data['payment_year_bs'],
            'bs_month' => $data['payment_month_bs'],
            'bs_day' => $data['payment_day_bs'],
            'auto_created' => 1

        );

        $insertid = $this->addReceipt($datum);
        if ($insertid) {
            foreach ($dueinvoices as $dueinvoice) {
                if (in_array($dueinvoice->id, $dueinvoiceids)) {
                    $partial = 0;
                    if ($due == 1 && $maxvaluedinvoice == $dueinvoice->id) {
                        $partial = 1;
                    }
                    $datatemp = array(
                        'journal_id' => 0,
                        'invoice_id' => $dueinvoice->id,
                        'receipt_id' => $insertid,
                        'total' => $dueinvoice->amount,
                        'remaining_amount' => $dueinvoice->payableamount,
                        'received_amount' => ($partial) ? ($dueinvoice->payableamount - $dueamount) : $dueinvoice->payableamount,
                        'status' => ($partial) ? 0 : 1,
                    );
                    $this->insertReceiptDetails($datatemp);
                }
            }

            $currentDateTime = $this->customlib->getCurrentTime();
            $multiplier = -1;

            $insertData[0] = array(
                'parent_id' => $insertid,
                'parent_type' => 'receipt',
                'category_id' => $customer_id,
                'category_type' => 'customer',
                'status' => 1,
                'amount' => $multiplier * ($data['paid_amount']),
                'amount_type' => 'credit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            $insertData[1] = array(
                'parent_id' => $insertid,
                'parent_type' => 'receipt',
                'category_id' => $asset_id,
                'category_type' => 'coa',
                'status' => 1,
                'amount' => ($data['paid_amount']),
                'amount_type' => 'debit',
                'created_date' => $currentDateTime,
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year,
            );
            if (count($insertData) && !$data['advance_used']) {
                $this->transaction_model->updateLogs($insertid, 'payment', $currentDateTime, $insertData);
            }

            if (isset($dueinvoiceids)) {
                $this->invoice_model->markAsCleared($dueinvoiceids);
                if ($due == 1) {
                    $this->invoice_model->markAsPartiallyPaid($maxvaluedinvoice);
                }
            }
            $sessionfee['receipt_id'] = $data['id'];
            $this->db->where('id', $data['id']);
            $this->db->update('student_fees_payment_history', $sessionfee);
            if ($sendEmail) {
                //send email
            }
        }
    }


    public function getNextReceiptCode()
    {
        $settings = $this->accountlib->getAccountSetting();
        $receipt_prefix = '';
        if ($settings->use_general_receipt_prefix) {
            $receipt_prefix = $settings->general_receipt_prefix;
        }
        $lastId = $this->getLastId();
        if (!$lastId) {
            $newId = $settings->general_receipt_start;
        } else {
            if ($settings->use_general_receipt_prefix) {
                $lastId = trim($lastId, $receipt_prefix);
            }
            $lastId = (int)$lastId;
            $newId = $lastId + 1;
        }

        return $receipt_prefix . $newId;
    }
}
