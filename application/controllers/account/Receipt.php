<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receipt extends Account_Controller
{

    function __construct()
    {

        parent::__construct();
        $this->response = array('status' => 'failure', 'data' => '');
        $this->load->library('mailsmsconf');
        $this->load->model('account/invoice_model');
        $this->load->model('account/personnel_model');
        $this->load->model('account/journal_model');
        $this->load->model('account/receipt_model');
        $this->load->model('account/account_COA_model');
        $this->load->model('setting_model');
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->currency = $this->customlib->getSchoolCurrencyFormat();
    }

    public function receiptList()
    {

        $postData = $this->input->post();

        $data = $this->receipt_model->getReceiptList($postData);

        echo json_encode($data);
    }


    public function feeCollectionsList()
    {

        $postData = $this->input->post();

        $data = $this->receipt_model->getFeeCollectionsList($postData);

        echo json_encode($data);
    }

    function index()
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_view')) {
            echo json_encode($this->response);
            exit;
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/receipt');
        $this->data['receipts'] = [];

        foreach ($this->data['receipts'] as $eachReceipt) {
            $eachReceipt->receipt_mode_details = (array)json_decode($eachReceipt->receipt_mode_details);
            if ($eachReceipt->receipt_mode == 'cheque') {
                $eachReceipt->receipt_mode_detail = 'Cheque date : ' . $eachReceipt->receipt_mode_details['cheque_date'] . ', Cheque no: ' . $eachReceipt->receipt_mode_details['cheque_no'];
            }
            if ($eachReceipt->receipt_mode == 'Prabhupay') {
                $eachReceipt->receipt_mode_detail = 'Transaction Id: ' . $eachReceipt->receipt_mode_details['TransactionId'];
            }
        }


        $this->load->view('layout/header');
        $this->load->view('account/receipt/list', $this->data);
        $this->load->view('layout/footer');
    }


    function add_receipt()
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_add')) {
            echo json_encode($this->response);
            exit;
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/receipt');
        $receipt_code = $this->getNextReceiptCode();
        $this->data['allow'] = 1;
        $this->data['receipt_no'] = $receipt_code;
        $this->data['customers'] = $this->personnel_model->getAllPersonnelByType('customer');

        $this->data['banks'] = $this->account_COA_model->getBanksList();
        if (sizeof($this->data['banks']) == 0) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('cash_bank_not_created'), 'type' => 'error'));

            redirect("account/receipt");
        }

        $this->load->view('layout/header');
        $this->load->view('account/receipt/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function getNextReceiptCode()
    {
        $settings = $this->accountlib->getAccountSetting();
        $receipt_prefix = '';
        if ($settings->use_general_receipt_prefix) {
            $receipt_prefix = $settings->general_receipt_prefix;
        }
        $lastId = $this->receipt_model->getLastId();
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

    function ajax_dueList()
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_add')) {
            echo json_encode($this->response);
            exit;
        }
        $input = $this->input;
        $customer_id = $input->post('id');

        $dueJournalList = $this->journal_model->getDueJournalFor($customer_id, 'receipt');
        $dueInvoiceList = $this->invoice_model->getDueInvoiceFor($customer_id, false);

        foreach ($dueJournalList as $eachdueJournal) {
            if ($this->datechooser == 'bs') {
                $eachdueJournal->due_date = $eachdueJournal->due_date_bs;
            }
            $eachdueJournal->payableamount = (float)$eachdueJournal->amount - (float)$eachdueJournal->partialpaidamount;
            $pastpaymentids = explode(',', $eachdueJournal->pastpaymentids);
            $pastpaymentcodes = explode(',', $eachdueJournal->pastpaymentcodes);
            $tempstring = '';
            foreach ($pastpaymentcodes as $key => $pastpaymentcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/editReceipt/' . $pastpaymentids[$key] . '">' . $pastpaymentcode . '</a>';
                $tempstring .= '</span>';
            }
            $eachdueJournal->pastpayments = $tempstring;
        }

        foreach ($dueInvoiceList as $eachdueInvoice) {
            if ($this->datechooser == 'bs') {
                $eachdueInvoice->due_date = $eachdueInvoice->due_date_bs;
            }

            // because done on invoice entry so will produce multiple instance of same paymentids and codes
            $temppastpaymentids = explode(',', $eachdueInvoice->pastpaymentids);
            $temppastpaymentcodes = explode(',', $eachdueInvoice->pastpaymentcodes);
            $tempuniquepastpaymentcodes = array_unique($temppastpaymentcodes);
            $tempuniquepastpaymentids = array_unique($temppastpaymentids);
            $tempfullsize = sizeof($temppastpaymentids);
            $tempactualsize = sizeof($tempuniquepastpaymentids);
            if (sizeof($temppastpaymentids) > 1) {
                $eachdueInvoice->partialpaidamount = $eachdueInvoice->partialpaidamount / ($tempfullsize / $tempactualsize);
            }


            $eachdueInvoice->payableamount = (float)$eachdueInvoice->amount - (float)$eachdueInvoice->partialpaidamount;
            $pastpaymentids = $tempuniquepastpaymentids;
            $pastpaymentcodes = $tempuniquepastpaymentcodes;
            $tempstring = '';
            foreach ($pastpaymentcodes as $key => $pastpaymentcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/editReceipt/' . $pastpaymentids[$key] . '">' . $pastpaymentcode . '</a>';
                $tempstring .= '</span>';
            }
            $eachdueInvoice->pastpayments = $tempstring;
        }
        $returndata = array_merge($dueJournalList, $dueInvoiceList);
        $this->response['data'] = $returndata;

        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;
    }

    public function generatePDF($id)
    {

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/receipt');
        $this->data['allow'] = true;
        $receipt = $this->receipt_model->getReceipt($id);
        if (!isset($receipt)) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_doesnt exist'), 'type' => 'error'));

            redirect("account/receipt");
        }
        $receipt->nettotal = $receipt->total;

        $this->data['receipt'] = $receipt;
        $this->data['banks'] = $this->account_COA_model->getBanksList();
        if (!isset($this->data['allow'])) {
            $this->data['allow'] = $this->accountlib->checkEditPermission($receipt->created_date, $receipt->financial_year, 'allow_receipt_edit');
        }
        $this->data['signature'] = 1;

        $journallist = $this->journal_model->getJournalDetailsForReceipt($id, false);

        $invoicelist = $this->receipt_model->getInvoiceDetailsForReceipt($id, false);

        $temp = array('max_val' => 0, 'minpayable' => 0, 'total_sum' => 0, 'maxvalue_id' => 0, 'maxvalue_type' => '');
        $journalids = array();
        $invoiceids = array();
        foreach ($journallist as $journal) {
            if ($this->datechooser == 'bs') {
                $journal->due_date = $journal->due_date_bs;
            }
            array_push($journalids, $journal->id);
            $journal->receivableamount = (float)$journal->amount - (float)(float)$journal->partialreceivedamount;
            $temp['sum'] = $temp['sum'] + (float)$journal->amount;
            if ((float)$journal->amount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$journal->amount;
                $temp['maxvalue_id'] = $journal->id;
                $temp['maxvalue_type'] = 'Journal';
            }
            $pastreceiptids = explode(',', $journal->pastreceiptids);
            $pastreceiptcodes = explode(',', $journal->pastreceiptcodes);
            $tempstring = '';
            foreach ($pastreceiptcodes as $key => $pastreceiptcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/viewReceipt/' . $pastreceiptids[$key] . '">' . $pastreceiptcode . '</a>';
                $tempstring .= '</span>';
            }
            $journal->pastreceipts = $tempstring;
        }
        foreach ($invoicelist as $invoice) {
            if ($this->datechooser == 'bs') {
                $invoice->due_date = $invoice->due_date_bs;
            }
            $pastreceiptids = explode(',', $invoice->pastpaymentids);
            $pastreceiptcodes = explode(',', $invoice->pastpaymentcodes);
            if (sizeof($pastreceiptcodes > 1)) {
                $invoice->partialpaidamount = $invoice->partialpaidamount / (sizeof($pastreceiptcodes) / sizeof(array_unique($pastreceiptcodes)));
                $pastreceiptcodes = array_unique($pastreceiptcodes);
            }
            array_push($invoiceids, $invoice->id);
            $temp['sum'] = $temp['sum'] + (float)$invoice->amount;
            if ((float)$invoice->amount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$invoice->amount;
                $temp['maxvalue_id'] = $invoice->id;
                $temp['maxvalue_type'] = 'Invoice';
            }
            $invoice->receivableamount = (float)$invoice->amount - (float)(float)$invoice->partialpaidamount;

            //
            $tempstring = '';
            foreach ($pastreceiptcodes as $key => $pastreceiptcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/viewReceipt/' . $pastreceiptids[$key] . '">' . $pastreceiptcode . '</a>';
                $tempstring .= '</span>';
            }
            $invoice->pastreceipts = $tempstring;
        }


        $journalentries = $this->journal_model->getJournalEntriesForReceipt($id, false);

        $invoiceentries = $this->receipt_model->getInvoiceEntriesForReceipt($id, false);
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
        foreach ($invoiceentries as $invoiceentry) {
            $invoiceentry->total = ($invoiceentry->rate * $invoiceentry->quantity) + $invoiceentry->tax_amount;
            $invoiceentry->code = $invoiceentry->invoicecode;
        }
        $this->data['settings'] = (array)$this->setting_model->getSetting();
        $temp['minpayable'] = $temp['sum'] - $temp['max_val'];
        $this->data['minpayable'] = $temp['minpayable'];
        $this->data['relatedjournal'] = $journallist;
        $this->data['relatedjournaldetails'] = $journalentries;
        $this->data['relatedinvoice'] = $invoicelist;
        $this->data['relatedinvoicedetails'] = $invoiceentries;
        //////////////
        $this->data['mgl'] = 5;
        $this->data['mgr'] = 5;

        $layout = 'A4-L';
        $view = 'account/receipt/receiptPDF';

        $html = $this->load->view($view, $this->data, true);

        $file_name = $receipt->name . "_" . time();
        $file_name = preg_replace('/\s+/', '_', $file_name);
        $pdfFilePath = $file_name . ".pdf";
        $this->load->library('m_pdf', array(
            'mode' => 'utf-8',
            'format' => $layout,
            'mgl' => $this->data['mgl'],
            'mgr' => $this->data['mgr'],
            'mgt' => 0,
            'mgb' => 0,
            'mgh' => 0,
            'mgf' => 0,
        ));
        $this->m_pdf->pdf->SetWatermarkImage(base_url() . '/uploads/school_content/logo/' . $this->data['settings']['image'], 0.1);
        $this->m_pdf->pdf->showWatermarkImage = true;
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

    public function sendMail($id)
    {
        $receipt = $this->receipt_model->getReceipt($id);
        if ($receipt->email != '') {
            $receipt = $this->receipt_model->getReceipt($id);
            if (!isset($receipt)) {
                $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_doesnt exist'), 'type' => 'error'));

                redirect("account/receipt");
            }
            $receipt->nettotal = $receipt->total;

            $this->data['receipt'] = $receipt;
            $this->data['banks'] = $this->account_COA_model->getBanksList();
            if (!isset($this->data['allow'])) {
                $this->data['allow'] = $this->accountlib->checkEditPermission($receipt->created_date, $receipt->financial_year, 'allow_receipt_edit');
            }

            $journallist = $this->journal_model->getJournalDetailsForReceipt($id, false);

            $invoicelist = $this->receipt_model->getInvoiceDetailsForReceipt($id, false);

            $temp = array('max_val' => 0, 'minpayable' => 0, 'total_sum' => 0, 'maxvalue_id' => 0, 'maxvalue_type' => '');
            $journalids = array();
            $invoiceids = array();
            foreach ($journallist as $journal) {
                if ($this->datechooser == 'bs') {
                    $journal->due_date = $journal->due_date_bs;
                }
                array_push($journalids, $journal->id);
                $journal->receivableamount = (float)$journal->amount - (float)(float)$journal->partialreceivedamount;
                $temp['sum'] = $temp['sum'] + (float)$journal->amount;
                if ((float)$journal->amount > $temp['max_val'] || $temp['max_val'] == 0) {
                    $temp['max_val'] = (float)$journal->amount;
                    $temp['maxvalue_id'] = $journal->id;
                    $temp['maxvalue_type'] = 'Journal';
                }
                $pastreceiptids = explode(',', $journal->pastreceiptids);
                $pastreceiptcodes = explode(',', $journal->pastreceiptcodes);
                $tempstring = '';
                foreach ($pastreceiptcodes as $key => $pastreceiptcode) {
                    $tempstring .= '<span>';
                    $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/viewReceipt/' . $pastreceiptids[$key] . '">' . $pastreceiptcode . '</a>';
                    $tempstring .= '</span>';
                }
                $journal->pastreceipts = $tempstring;
            }
            foreach ($invoicelist as $invoice) {
                if ($this->datechooser == 'bs') {
                    $invoice->due_date = $invoice->due_date_bs;
                }
                $pastreceiptids = explode(',', $invoice->pastpaymentids);
                $pastreceiptcodes = explode(',', $invoice->pastpaymentcodes);
                if (sizeof($pastreceiptcodes > 1)) {
                    $invoice->partialpaidamount = $invoice->partialpaidamount / (sizeof($pastreceiptcodes) / sizeof(array_unique($pastreceiptcodes)));
                    $pastreceiptcodes = array_unique($pastreceiptcodes);
                }
                array_push($invoiceids, $invoice->id);
                $temp['sum'] = $temp['sum'] + (float)$invoice->amount;
                if ((float)$invoice->amount > $temp['max_val'] || $temp['max_val'] == 0) {
                    $temp['max_val'] = (float)$invoice->amount;
                    $temp['maxvalue_id'] = $invoice->id;
                    $temp['maxvalue_type'] = 'Invoice';
                }
                $invoice->receivableamount = (float)$invoice->amount - (float)(float)$invoice->partialpaidamount;

                //
                $tempstring = '';
                foreach ($pastreceiptcodes as $key => $pastreceiptcode) {
                    $tempstring .= '<span>';
                    $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/viewReceipt/' . $pastreceiptids[$key] . '">' . $pastreceiptcode . '</a>';
                    $tempstring .= '</span>';
                }
                $invoice->pastreceipts = $tempstring;
            }


            $journalentries = $this->journal_model->getJournalEntriesForReceipt($id, false);

            $invoiceentries = $this->receipt_model->getInvoiceEntriesForReceipt($id, false);
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
            foreach ($invoiceentries as $invoiceentry) {
                $invoiceentry->total = ($invoiceentry->rate * $invoiceentry->quantity) + $invoiceentry->tax_amount;
                $invoiceentry->code = $invoiceentry->invoicecode;
            }
            $this->data['settings'] = (array)$this->setting_model->getSetting();
            $temp['minpayable'] = $temp['sum'] - $temp['max_val'];
            $this->data['minpayable'] = $temp['minpayable'];
            $this->data['relatedjournal'] = $journallist;
            $this->data['relatedjournaldetails'] = $journalentries;
            $this->data['relatedinvoice'] = $invoicelist;
            $this->data['relatedinvoicedetails'] = $invoiceentries;
            $this->data['invoice'] = $invoice;
            $this->data['invoice_entries'] = $this->invoice_model->getInvoiceEntries($id);
            $this->data['settings'] = (array)$this->setting_model->getSetting();


            $view = $this->load->view('account/receipt/receiptPDF', $this->data, TRUE);

            //send mail start
            $mailDetail = array('email' => $receipt->email, 'subject' => 'Receipt Voucher', 'msg' => $view);

            $this->mailsmsconf->mailsms('send_account_email', $mailDetail);

            $msg = $this->lang->line('mail_sent_successfully');

            $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'success'));
            redirect("account/receipt/viewReceipt/" . $id);
            //send mail end
        }
        $msg = $this->lang->line('mail_not_found');
        $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'danger'));
        redirect("account/receipt/viewReceipt/" . $id);
    }

    public function ajax_EntryListDetails()
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_add')) {
            echo json_encode($this->response);
        }
        $input = $this->input;

        $customerid = $input->post('customerid');
        $journalids = $input->post('journalids');
        $invoiceids = $input->post('invoiceids');

        if (!empty($journalids)) {
            $dueJournalEntryList = $this->journal_model->getDueJournalEntries($journalids, $customerid);
            $total = 0;
            foreach ($dueJournalEntryList as $eachjournalentry) {
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
                $eachjournalentry->dueType = 'Journal';
            }
            $this->response['journaldata'] = $dueJournalEntryList;
        }

        if (!empty($invoiceids)) {

            $dueInvoiceEntryList = $this->invoice_model->getDueInvoiceEntries($invoiceids);

            $total = 0;
            foreach ($dueInvoiceEntryList as $dueInvoiceEntry) {
                $dueInvoiceEntry->amount = ($dueInvoiceEntry->rate * $dueInvoiceEntry->quantity) + $dueInvoiceEntry->tax_amount;
                $total = $total + $dueInvoiceEntry->amount;
                $dueInvoiceEntry->debit = $dueInvoiceEntry->amount;
                $dueInvoiceEntry->credit = '';
                $eachjournalentry->dueType = 'Invoice';
            }
            $this->response['invoicedata'] = $dueInvoiceEntryList;
        }


        $this->response['status'] = 'success';
        echo json_encode($this->response);
        exit;
    }


    public function save_receipt()
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/receipt');
        $input = $this->input;
        $id = $input->post('id');

        if ($id) {
            $id = $input->post('id');
            $receipt = $this->receipt_model->getReceipt($id);
            if ($receipt->journalids == 0 && $receipt->invoiceids == 0) {
                $this->session->set_flashdata('msg', array('message' => 'Cant edit advance receipt', 'type' => 'error'));
                return redirect('account/receipt');
            }
            $this->updateReceipt();
        } else {
            $sendEmail = $input->post('send_mail');
            $sendEmail = isset($sendEmail) ? 1 : 0;
            $this->form_validation->set_rules('receipt_no', $this->lang->line('receipt_no'), 'required');
            if ($this->datechooser == 'bs') {
                $this->form_validation->set_rules('receipt_date_bs', $this->lang->line('receipt_date'), 'trim|required|xss_clean');
            } else {
                $this->form_validation->set_rules('receipt_date', $this->lang->line('receipt_date'), 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('receipt_date', $this->lang->line('receipt_date'), 'required');
            $this->form_validation->set_rules('ref_no', $this->lang->line('ref_no'), 'required');
            $this->form_validation->set_rules('receive_from', $this->lang->line('receive_from'), 'required');
            $this->form_validation->set_rules('receipt_mode', $this->lang->line('receipt_mode'), 'required|callback_possiblePaymentMode');
            $receipt_mode = $input->post('receipt_mode');
            $advance_paid = $input->post('advancePay');
            if ($receipt_mode == 'cheque') {
                $this->form_validation->set_rules('cheque_no', $this->lang->line('cheque_no'), 'required');
                if ($this->datechooser == 'bs') {
                    $this->form_validation->set_rules('cheque_date_bs', $this->lang->line('cheque_date'), 'trim|required|xss_clean');
                } else {
                    $this->form_validation->set_rules('cheque_date', $this->lang->line('cheque_date'), 'trim|required|xss_clean');
                }
                $this->form_validation->set_rules('bank', $this->lang->line('bank'), 'required');
            }
            $this->form_validation->set_rules('narration', $this->lang->line('narration'), 'required|xss_clean');
            $this->form_validation->set_rules('paid_amount', $this->lang->line('payment_amount'), 'required|numeric');
            $redirect = 0;
            $receive_from = $input->post('receive_from');
            $journal_ids = $input->post('journal_id');
            $invoice_ids = $input->post('invoice_id');

            $duejournals = $this->journal_model->getDueJournalFor($receive_from, 'receipt');
            $dueinvoices = $this->invoice_model->getDueInvoiceFor($receive_from);
            $duejournalids = array();
            $dueinvoiceids = array();
            $totalPayable = 0;
            $temp = array(
                'journal_sum' => 0, 'minpayment' => 0, 'journal_maxvalue' => 0, 'maxvalued_journal' => 0,
                'invoice_sum' => 0, 'maxvalued_invoice' => 0, 'invoice_maxvalue' => 0, 'sum' => 0, 'max_value' => 0,
                'maxvalue_type' => ''
            );
            $totalpayablesum = 0;
            foreach ($duejournals as $duejournal) {
                $totalpayablesum += (float)$duejournal->amount - (float)$duejournal->partialpaidamount;
                if (in_array($duejournal->id, $journal_ids)) {
                    $duejournal->payableamount = (float)$duejournal->amount - (float)$duejournal->partialpaidamount;
                    $temp['journal_sum'] = $temp['journal_sum'] + $duejournal->payableamount;
                    if ($duejournal->payableamount > $temp['journal_maxvalue'] || $temp['journal_maxvalue'] == 0) {
                        $temp['journal_maxvalue'] = $duejournal->payableamount;
                        $temp['maxvalued_journal'] = $duejournal->id;
                    }
                }
                array_push($duejournalids, $duejournal->id);
            }
            foreach ($dueinvoices as $dueinvoice) {
                $temppastpaymentids = explode(',', $dueinvoice->pastpaymentids);
                $temppastpaymentcodes = explode(',', $dueinvoice->pastpaymentcodes);
                $tempuniquepastpaymentcodes = array_unique($temppastpaymentcodes);
                $tempuniquepastpaymentids = array_unique($temppastpaymentids);
                $tempfullsize = sizeof($temppastpaymentids);
                $tempactualsize = sizeof($tempuniquepastpaymentids);
                if (sizeof($temppastpaymentids)) {
                    $dueinvoice->partialpaidamount = $dueinvoice->partialpaidamount / ($tempfullsize / $tempactualsize);
                }
                $totalpayablesum += (float)$dueinvoice->amount - (float)$dueinvoice->partialpaidamount;
                if (in_array($dueinvoice->id, $invoice_ids)) {

                    $temppastpaymentids = explode(',', $dueinvoice->pastpaymentids);
                    $temppastpaymentcodes = explode(',', $dueinvoice->pastpaymentcodes);
                    $tempuniquepastpaymentcodes = array_unique($temppastpaymentcodes);
                    $tempuniquepastpaymentids = array_unique($temppastpaymentids);
                    $tempfullsize = sizeof($temppastpaymentids);
                    $tempactualsize = sizeof($tempuniquepastpaymentids);
                    if (sizeof($temppastpaymentids)) {
                        $dueinvoice->partialpaidamount = $dueinvoice->partialpaidamount / ($tempfullsize / $tempactualsize);
                    }


                    $dueinvoice->payableamount = (float)$dueinvoice->amount - (float)$dueinvoice->partialpaidamount;

                    $temp['invoice_sum'] = $temp['invoice_sum'] + $dueinvoice->payableamount;
                    if ($dueinvoice->payableamount > $temp['invoice_maxvalue'] || $temp['invoice_maxvalue'] == 0) {
                        $temp['invoice_maxvalue'] = $dueinvoice->payableamount;
                        $temp['maxvalued_invoice'] = $dueinvoice->id;
                    }
                }
                array_push($dueinvoiceids, $dueinvoice->id);
            }

            if ($temp['journal_maxvalue'] > $temp['invoice_maxvalue']) {
                $temp['max_value'] = $temp['journal_maxvalue'];
                $temp['maxvalue_type'] = 'Journal';
            }
            if ($temp['journal_maxvalue'] <= $temp['invoice_maxvalue']) {
                $temp['max_value'] = $temp['invoice_maxvalue'];
                $temp['maxvalue_type'] = 'Invoice';
            }
            $temp['sum'] = $temp['invoice_maxvalue'] + $temp['journal_maxvalue'];
            $temp['minpayment'] = $temp['sum'] - $temp['max_value'];
            if (array_diff($journal_ids, $duejournalids) || array_diff($invoice_ids, $dueinvoiceids)) {
                //denotes the incoming ids are not all valid due ids
                echo "2";
                exit;
                $redirect = 1;
                $flasherrormessage = $this->lang->line('date_integrity_compromised');
            }

            $receipt_no = $this->getNextReceiptCode();
            $receipt_date = $input->post('receipt_date');
            $receipt_date = date('Y-m-d', $this->customlib->datetostrtotime($receipt_date));
            $receipt_date_bs = $input->post('receipt_date_bs');
            $ref_no = $input->post('ref_no');
            $receipt_mode = $input->post('receipt_mode');
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
            $bs_date = explode('-', $receipt_date_bs);
            $bs_year = $bs_date[0];
            $bs_month = $bs_date[1];
            $bs_day = $bs_date[2];
            $advance_amount = 0;

//            echopreexit($totalpayablesum.$paid_amount);
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
            if ($paid_amount <= $temp['minpayment'] && $temp['minpayment'] != 0) {
                //denotes minimum amount is paid
                echo "3";
                exit;
                $redirect = 1;
                $flasherrormessage = $this->lang->line('date_integrity_compromised');
            }
            $due = 0;
            if ($paid_amount < $nettotal) {
                $due = 1;
                $dueamount = $nettotal - $paid_amount;
            }
            if (isset($journal_ids) || isset($invoice_ids)) {
                $tempamountjrn = 0;
                if (isset($journal_ids)) {
                    $tempamountjrn = $temp['journal_sum'];
                }

                $tempamountinv = 0;
                if (isset($invoice_ids)) {
                    $tempamountinv = $temp['invoice_sum'];
                }
                $tempamount = $temp['journal_sum'] + $temp['invoice_sum'];
                if ($tempamount != $nettotal) {
                    echo $tempamount;
                    echo $nettotal;
                    echo "4";
                    exit;
                    $redirect = 1;
                    $flasherrormessage = $this->lang->line('date_integrity_compromised');
                }
            }
            if ($receipt_mode == 'cash') {
                $asset_id = 11;
            }
            if ($receipt_mode == 'cheque') {
                $asset_id = $bank;
                $receipt_mode_details = json_encode(array('cheque_date' => $cheque_date, 'cheque_no' => $cheque_no, 'cheque_date_bs' => $cheque_date_bs));
            }

            if ($this->form_validation->run() == TRUE && $redirect == 0) {
                $paid_amount = $advance_amount > 0 ? $nettotal : $paid_amount;
                $data = array(
                    'receipt_no' => $receipt_no,
                    'receipt_date' => $receipt_date,
                    'receipt_date_bs' => $receipt_date_bs,
                    'ref_no' => $ref_no,
                    'received_from' => $receive_from,
                    'receipt_mode' => $receipt_mode,
                    'asset_id' => $asset_id,
                    'receipt_mode_details' => $receipt_mode_details,
                    'due' => $due,
                    'description' => $narration . ($advance_amount > 0 ? 'along with advance of Rs.' . $advance_amount : ''),
                    'total' => $nettotal,
                    'received_amount' => $paid_amount,
                    'created_by' => $this->session->userdata['admin']['id'],
                    'created_date' => $this->customlib->getCurrentTime(),
                    'send_email' => $sendEmail,
                    'bs_year' => $bs_year,
                    'bs_month' => $bs_month,
                    'bs_day' => $bs_day,

                );

                $insertid = $this->receipt_model->addReceipt($data);
                if ($insertid) {
                    if ($advance_amount > 0) {
                        $this->receipt_model->saveAdvanceReceipt($data, $advance_amount);
                    }
                    if ($paid_amount > 0) {
                        foreach ($duejournals as $duejournal) {
                            if (in_array($duejournal->id, $journal_ids)) {
                                $partial = 0;
                                if ($due == 1 && $temp['maxvalued_journal'] == $duejournal->id && $temp['maxvalue_type'] == 'Journal') {
                                    $partial = 1;
                                }
                                $data = array(
                                    'journal_id' => $duejournal->id,
                                    'invoice_id' => 0,
                                    'receipt_id' => $insertid,
                                    'total' => $duejournal->amount,
                                    'remaining_amount' => $duejournal->payableamount,
                                    'received_amount' => ($partial) ? ($duejournal->payableamount - $dueamount) : $duejournal->payableamount,
                                    'status' => ($partial) ? 0 : 1,
                                );
                                $this->receipt_model->insertReceiptDetails($data);
                            }
                        }
                        foreach ($dueinvoices as $dueinvoice) {

                            if (in_array($dueinvoice->id, $invoice_ids)) {

                                $partial = 0;
                                if ($due == 1 && $temp['maxvalued_invoice'] == $dueinvoice->id && $temp['maxvalue_type'] == 'Invoice') {
                                    $partial = 1;
                                }
                                $data = array(
                                    'journal_id' => 0,
                                    'invoice_id' => $dueinvoice->id,
                                    'receipt_id' => $insertid,
                                    'total' => $dueinvoice->amount,
                                    'remaining_amount' => $dueinvoice->payableamount,
                                    'received_amount' => ($partial) ? ($dueinvoice->payableamount - $dueamount) : $dueinvoice->payableamount,
                                    'status' => ($partial) ? 0 : 1,
                                );
                                $this->receipt_model->insertReceiptDetails($data);
                            }
                        }
                        $currentDateTime = $this->customlib->getCurrentTime();
                        $multiplier = -1; //temporarily assigned
                        $cashid = 11;

                        $insertData[0] = array(
                            'parent_id' => $insertid,
                            'parent_type' => 'receipt',
                            'category_id' => $receive_from,
                            'category_type' => 'customer',
                            'status' => 1,
                            'amount' => $multiplier * ($paid_amount),
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
                            'amount' => ($paid_amount),
                            'amount_type' => 'debit',
                            'created_date' => $currentDateTime,
                            'created_by' => $this->session->userdata['admin']['id'],
                            'financial_year' => $this->financial_year,
                        );
                        if (count($insertData)) {
                            //$this->transaction_model->updateLogs($id, 'invoice', $currentDateTime, $data);
                            $this->transaction_model->updateLogs($insertid, 'payment', $currentDateTime, $insertData);
                        }

                        if (isset($journal_ids)) {
                            $this->journal_model->markAsCleared($journal_ids);
                            if ($due == 1 && $temp['maxvalue_type'] == 'Journal') {
                                $this->journal_model->markAsPartiallyPaid($temp['maxvalued_journal']);
                            }
                        }
                        if (isset($invoice_ids)) {
                            $this->invoice_model->markAsCleared($invoice_ids);
                            if ($due == 1 && $temp['maxvalue_type'] == 'Invoice') {
                                $this->invoice_model->markAsPartiallyPaid($temp['maxvalued_invoice']);
                            }
                        }
                    }
                    if ($sendEmail) {
                        //send email
                    }
                }

                $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_saved'), 'type' => 'success'));

                redirect("account/receipt");
            } else {

                //redirect
                $this->session->set_flashdata('msg', array('message' => $flasherrormessage, 'type' => 'error'));
                $receipt_code = $this->getNextReceiptCode();
                $this->data['receipt_no'] = $receipt_code;
                $this->data['customers'] = $this->personnel_model->getAllPersonnelByType('customer');
                $this->data['banks'] = $this->account_COA_model->getBanksList();
                $this->load->view('layout/header');
                $this->load->view('account/receipt/add', $this->data);
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

    function editReceipt($id)
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_edit')) {
            echo json_encode($this->response);
            exit;
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/receipt');
        $this->data['allow'] = true;
        $receipt = $this->receipt_model->getReceipt($id);

        if (!isset($receipt)) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_doesnt_exist'), 'type' => 'error'));

            redirect("account/receipt");
        }
        $concernedjournal_ids = array_unique(explode(',', $receipt->journalids));
        $concernedinvoice_ids = array_unique(explode(',', $receipt->invoiceids));
        $nextReceipts = $this->receipt_model->checkNextReceipt($id, $receipt->received_from, $concernedjournal_ids, $concernedinvoice_ids);
        if (isset($nextReceipts[0]) || $receipt->auto_created) {
            //cant be edited
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_cant_be_edited'), 'type' => 'error'));

            $this->data['allow'] = false;
        }

        $receipt->nettotal = $receipt->total;


        $this->data['receipt'] = $receipt;
        $this->data['banks'] = $this->account_COA_model->getBanksList();
        if (!isset($this->data['allow'])) {
            $this->data['allow'] = $this->accountlib->checkEditPermission($receipt->created_date, $receipt->financial_year, 'allow_receipt_edit');
        }

        $journallist = $this->journal_model->getJournalDetailsForReceipt($id);

        $invoicelist = $this->receipt_model->getInvoiceDetailsForReceipt($id);

        $temp = array('max_val' => 0, 'minpayable' => 0, 'total_sum' => 0, 'maxvalue_id' => 0, 'maxvalue_type' => '');
        $journalids = array();
        $invoiceids = array();
        foreach ($journallist as $journal) {
            if ($this->datechooser == 'bs') {
                $journal->due_date = $journal->due_date_bs;
            }
            array_push($journalids, $journal->id);
            $journal->receivableamount = (float)$journal->amount - (float)(float)$journal->partialreceivedamount;
            $temp['sum'] = $temp['sum'] + (float)$journal->amount;
            if ((float)$journal->amount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$journal->amount;
                $temp['maxvalue_id'] = $journal->id;
                $temp['maxvalue_type'] = 'Journal';
            }
            $pastreceiptids = explode(',', $journal->pastreceiptids);
            $pastreceiptcodes = explode(',', $journal->pastreceiptcodes);
            $tempstring = '';
            foreach ($pastreceiptcodes as $key => $pastreceiptcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/editReceipt/' . $pastreceiptids[$key] . '">' . $pastreceiptcode . '</a>';
                $tempstring .= '</span>';
            }
            $journal->pastreceipts = $tempstring;
        }
        foreach ($invoicelist as $invoice) {
            if ($this->datechooser == 'bs') {
                $invoice->due_date = $invoice->due_date_bs;
            }
            $pastreceiptids = explode(',', $invoice->pastpaymentids);
            $pastreceiptcodes = explode(',', $invoice->pastpaymentcodes);
            if (sizeof($pastreceiptcodes > 1)) {
                $invoice->partialpaidamount = $invoice->partialpaidamount / (sizeof($pastreceiptcodes) / sizeof(array_unique($pastreceiptcodes)));
                $pastreceiptcodes = array_unique($pastreceiptcodes);
            }
            array_push($invoiceids, $invoice->id);
            $temp['sum'] = $temp['sum'] + (float)$invoice->amount;
            if ((float)$invoice->amount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$invoice->amount;
                $temp['maxvalue_id'] = $invoice->id;
                $temp['maxvalue_type'] = 'Invoice';
            }
            $invoice->receivableamount = (float)$invoice->amount - (float)(float)$invoice->partialpaidamount;


            //
            $tempstring = '';
            foreach ($pastreceiptcodes as $key => $pastreceiptcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/editReceipt/' . $pastreceiptids[$key] . '">' . $pastreceiptcode . '</a>';
                $tempstring .= '</span>';
            }
            $invoice->pastreceipts = $tempstring;
        }


        $journalentries = $this->journal_model->getJournalEntriesForReceipt($id);

        $invoiceentries = $this->receipt_model->getInvoiceEntriesForReceipt($id);
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
        foreach ($invoiceentries as $invoiceentry) {
            $invoiceentry->total = ($invoiceentry->rate * $invoiceentry->quantity) + $invoiceentry->tax_amount;
            $invoiceentry->code = $invoiceentry->invoicecode;
        }
        $temp['minpayable'] = $temp['sum'] - $temp['max_val'];
        $this->data['minpayable'] = $temp['minpayable'];
        $this->data['relatedjournal'] = $journallist;
        $this->data['relatedjournaldetails'] = $journalentries;
        $this->data['relatedinvoice'] = $invoicelist;
        $this->data['relatedinvoicedetails'] = $invoiceentries;
        unset($temp);
        $this->load->view('layout/header');
        $this->load->view('account/receipt/add', $this->data);
        $this->load->view('layout/footer');
    }


    function viewReceipt($id)
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_edit')) {
            echo json_encode($this->response);
            exit;
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/receipt');
        $this->data['allow'] = true;
        $receipt = $this->receipt_model->getReceipt($id);
        if (!isset($receipt)) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_doesnt_exist'), 'type' => 'error'));

            redirect("account/receipt");
        }
        //        $concernedjournal_ids=array_unique(explode(',', $receipt->journalids));
        //        $concernedinvoice_ids=array_unique(explode(',', $receipt->invoiceids));
        //        $nextReceipts = $this->receipt_model->checkNextReceipt($id, $receipt->received_from,$concernedjournal_ids,$concernedinvoice_ids);
        $receipt->nettotal = $receipt->total;

        $this->data['receipt'] = $receipt;
        $this->data['banks'] = $this->account_COA_model->getBanksList();
        if (!isset($this->data['allow'])) {
            $this->data['allow'] = $this->accountlib->checkEditPermission($receipt->created_date, $receipt->financial_year, 'allow_receipt_edit');
        }

        $journallist = $this->journal_model->getJournalDetailsForReceipt($id, false);

        $invoicelist = $this->receipt_model->getInvoiceDetailsForReceipt($id, false);

        $temp = array('max_val' => 0, 'minpayable' => 0, 'total_sum' => 0, 'maxvalue_id' => 0, 'maxvalue_type' => '');
        $journalids = array();
        $invoiceids = array();
        foreach ($journallist as $journal) {
            if ($this->datechooser == 'bs') {
                $journal->due_date = $journal->due_date_bs;
            }
            array_push($journalids, $journal->id);
            $journal->receivableamount = (float)$journal->amount - (float)(float)$journal->partialreceivedamount;
            $temp['sum'] = $temp['sum'] + (float)$journal->amount;
            if ((float)$journal->amount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$journal->amount;
                $temp['maxvalue_id'] = $journal->id;
                $temp['maxvalue_type'] = 'Journal';
            }
            $pastreceiptids = explode(',', $journal->pastreceiptids);
            $pastreceiptcodes = explode(',', $journal->pastreceiptcodes);
            $tempstring = '';
            foreach ($pastreceiptcodes as $key => $pastreceiptcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/viewReceipt/' . $pastreceiptids[$key] . '">' . $pastreceiptcode . '</a>';
                $tempstring .= '</span>';
            }
            $journal->pastreceipts = $tempstring;
        }
        foreach ($invoicelist as $invoice) {
            if ($this->datechooser == 'bs') {
                $invoice->due_date = $invoice->due_date_bs;
            }
            $pastreceiptids = explode(',', $invoice->pastpaymentids);
            $pastreceiptcodes = explode(',', $invoice->pastpaymentcodes);
            if (sizeof($pastreceiptcodes > 1)) {
                $invoice->partialpaidamount = $invoice->partialpaidamount / (sizeof($pastreceiptcodes) / sizeof(array_unique($pastreceiptcodes)));
                $pastreceiptcodes = array_unique($pastreceiptcodes);
            }
            array_push($invoiceids, $invoice->id);
            $temp['sum'] = $temp['sum'] + (float)$invoice->amount;
            if ((float)$invoice->amount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$invoice->amount;
                $temp['maxvalue_id'] = $invoice->id;
                $temp['maxvalue_type'] = 'Invoice';
            }
            $invoice->receivableamount = (float)$invoice->amount - (float)(float)$invoice->partialpaidamount;

            //
            $tempstring = '';
            foreach ($pastreceiptcodes as $key => $pastreceiptcode) {
                $tempstring .= '<span>';
                $tempstring .= '<a target="_blank" href="' . base_url() . 'account/receipt/viewReceipt/' . $pastreceiptids[$key] . '">' . $pastreceiptcode . '</a>';
                $tempstring .= '</span>';
            }
            $invoice->pastreceipts = $tempstring;
        }


        $journalentries = $this->journal_model->getJournalEntriesForReceipt($id, false);

        $invoiceentries = $this->receipt_model->getInvoiceEntriesForReceipt($id, false);
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
        foreach ($invoiceentries as $invoiceentry) {
            $invoiceentry->total = ($invoiceentry->rate * $invoiceentry->quantity) + $invoiceentry->tax_amount;
            $invoiceentry->code = $invoiceentry->invoicecode;
        }
        $temp['minpayable'] = $temp['sum'] - $temp['max_val'];
        $this->data['minpayable'] = $temp['minpayable'];
        $this->data['relatedjournal'] = $journallist;
        $this->data['relatedjournaldetails'] = $journalentries;
        $this->data['relatedinvoice'] = $invoicelist;
        $this->data['relatedinvoicedetails'] = $invoiceentries;
        unset($temp);
        $this->load->view('layout/header');
        $this->load->view('account/receipt/view', $this->data);
        $this->load->view('layout/footer');
    }


    function updateReceipt()
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_edit')) {
            access_denied();
        }

        $input = $this->input;
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/receipt');
        $id = $input->post('id');
        $receipt = $this->receipt_model->getReceipt($id);
        if ($receipt->auto_created) {

            $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_cant_be_edited'), 'type' => 'error'));

            redirect("account/receipt");
        }
        $redirect = 0;
        $concernedjournal_ids = array_unique(explode(',', $receipt->journalids));
        $concernedinvoice_ids = array_unique(explode(',', $receipt->invoiceids));
        $nextReceipts = $this->receipt_model->checkNextReceipt($id, $receipt->received_from, $concernedjournal_ids, $concernedinvoice_ids);
        if (isset($nextReceipts[0])) {
            //cant be edited
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_cant_be_edited'), 'type' => 'error'));

            $redirect = 1;
        }
        $sendEmail = $input->post('send_mail');
        $sendEmail = isset($sendEmail) ? 1 : 0;
        $receipt_mode = $input->post('receipt_mode');
        if ($this->datechooser == 'bs') {
            $this->form_validation->set_rules('receipt_date_bs', $this->lang->line('receipt_date'), 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('receipt_date', $this->lang->line('receipt_date'), 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('ref_no', $this->lang->line('ref_no'), 'required');
        $this->form_validation->set_rules('receive_from', $this->lang->line('receive_from'), 'required');
        $this->form_validation->set_rules('receipt_mode', $this->lang->line('receipt_mode'), 'required|callback_possiblePaymentMode');
        if ($receipt_mode == 'cheque') {
            $this->form_validation->set_rules('cheque_no', $this->lang->line('cheque_no'), 'required');
            if ($this->datechooser == 'bs') {
                $this->form_validation->set_rules('cheque_date_bs', $this->lang->line('cheque_date'), 'trim|required|xss_clean');
            } else {
                $this->form_validation->set_rules('cheque_date', $this->lang->line('cheque_date'), 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('bank', $this->lang->line('bank'), 'required');
        }
        //        $cash = $this->account_COA_model->getCashItem();
        //        if ($receipt_mode == 'cash' && !isset($cash)) {
        //            $this->session->set_flashdata('msg', array('message' => "Cash asset is not created yet. ", 'type' => 'error'));
        //            redirect("account/receipt");
        //            exit;
        //        }
        $this->form_validation->set_rules('narration', $this->lang->line('narration'), 'required|xss_clean');
        $this->form_validation->set_rules('paid_amount', $this->lang->line('payment_amount'), 'required|numeric');

        $nettotal = $input->post('nettotal');

        $paid_amount = (float)$input->post('paid_amount');
        $tempreceipttotal = $receipt->total;
        if ($tempreceipttotal != $nettotal) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('date_integrity_compromised'), 'type' => 'error'));
            $redirect = 1;
        }
        //needs a revisit
        $journalentries = $this->journal_model->getJournalDetailsForReceipt($id);
        $invoiceentries = $this->receipt_model->getInvoiceDetailsForReceipt($id);
        $temp = array('max_val' => 0, 'minpayable' => 0, 'total_sum' => 0, 'maxvalue_id' => 0, 'maxvalue_type' => '');
        foreach ($journalentries as $journal) {
            $journal->payableamount = $journal->amount - $journal->partialpaidamount;
            $temp['sum'] = $temp['sum'] + (float)$journal->payableamount;
            if ((float)$journal->payableamount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$journal->payableamount;
                $temp['maxvalue_id'] = $journal->id;
                $temp['maxvalue_type'] = 'Journal';
            }
        }
        foreach ($invoiceentries as $invoice) {
            $invoice->payableamount = $invoice->amount - $invoice->partialpaidamount;
            $temp['sum'] = $temp['sum'] + (float)$invoice->payableamount;
            if ((float)$invoice->payableamount > $temp['max_val'] || $temp['max_val'] == 0) {
                $temp['max_val'] = (float)$invoice->payableamount;
                $temp['maxvalue_id'] = $invoice->id;
                $temp['maxvalue_type'] = 'Invoice';
            }
        }
        $temp['minpayable'] = $temp['sum'] - $temp['max_val'];
        if ($paid_amount <= $temp['minpayable']) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('cant_pay_less_than_minimum'), 'type' => 'error'));
            $redirect = 1;
        }


        if ($this->form_validation->run() == TRUE && $redirect == 0) {
            $id = $input->post('id');
            $receipt_date = $input->post('receipt_date');
            $receipt_date_bs = $input->post('receipt_date_bs');
            $receipt_date = date('Y-m-d', $this->customlib->datetostrtotime($receipt_date));
            $ref_no = $input->post('ref_no');
            $cheque_no = $input->post('cheque_no');
            $cheque_date = $input->post('cheque_date');
            $cheque_date_bs = $input->post('cheque_date_bs');
            $cheque_date = date('Y-m-d', $this->customlib->datetostrtotime($cheque_date));
            $bank = $input->post('bank');
            $narration = $input->post('narration');
            $due = 0;
            $paid_amount = (float)$input->post('paid_amount');
            $bs_date = explode('-', $receipt_date_bs);
            $bs_year = $bs_date[0];
            $bs_month = $bs_date[1];
            $bs_day = $bs_date[2];
            if ($paid_amount < $nettotal) {
                $due = 1;
                $dueamount = $nettotal - $paid_amount;
            }
            if ($receipt_mode == 'cash') {
                $asset_id = 11;
            }
            if ($receipt_mode == 'cheque') {
                $asset_id = $bank;
                $receipt_mode_details = json_encode(array('cheque_date' => $cheque_date, 'cheque_no' => $cheque_no, 'cheque_date_bs' => $cheque_date_bs));
            }
            $data = array(
                'id' => $id,
                'receipt_date' => $receipt_date,
                'receipt_date_bs' => $receipt_date_bs,
                'ref_no' => $ref_no,
                'receipt_mode' => $receipt_mode,
                'asset_id' => $asset_id,
                'receipt_mode_details' => $receipt_mode_details,
                'due' => $due,
                'description' => $narration,
                'received_amount' => $paid_amount,
                'modified_by' => $this->session->userdata['admin']['id'],
                'modified_date' => $this->customlib->getCurrentTime(),
                'send_email' => $sendEmail,
                'bs_year' => $bs_year,
                'bs_month' => $bs_month,
                'bs_day' => $bs_day,
            );
            $this->receipt_model->updateReceipt($data);

            if ((int)$receipt->due != $due) {
                if ($temp['maxvalue_type'] == 'Invoice') {

                    $receiptdetail = $this->receipt_model->getReceiptDetail($id, $temp['maxvalue_id'], 'Invoice');
                    //update the entry to be done
                    $partial = 0;
                    if ($due == 1) {
                        $partial = 1;
                    }
                    $received_amount = ($partial) ? ($receiptdetail->remaining_amount - $dueamount) : $receiptdetail->remaining_amount;
                    $status = $partial ? 0 : 1;
                    $data = array('id' => $receiptdetail->id, 'received_amount' => $received_amount, 'status' => $status);
                    $this->receipt_model->updateReceiptDetail($data);


                    $datum = array('status' => ($due == 0) ? 1 : -1, 'id' => $temp['maxvalue_id']);
                    $this->invoice_model->updateInvoiceStatus($datum);
                }
                if ($temp['maxvalue_type'] == 'Journal') {
                    $receiptdetail = $this->receipt_model->getReceiptDetail($id, $temp['maxvalue_id'], 'Journal');
                    //update the entry to be done
                    $partial = 0;
                    if ($due == 1) {
                        $partial = 1;
                    }
                    $received_amount = ($partial) ? ($receiptdetail->remaining_amount - $dueamount) : $receiptdetail->remaining_amount;
                    $status = $partial ? 0 : 1;
                    $data = array('id' => $receiptdetail->id, 'received_amount' => $received_amount, 'status' => $status);
                    $this->receipt_model->updateReceiptDetail($data);

                    $datum = array('is_cleared' => ($due == 0) ? 1 : -1, 'id' => $temp['maxvalue_id']);
                    $this->journal_model->updateJournalStatus($datum);
                }
            }
            unset($datum);
            unset($data);

            //updating logs
            $currentDateTime = $this->customlib->getCurrentTime();
            $multiplier = -1;
            $cashid = 11;
            if ($receipt->received_amount != $paid_amount || $receipt->receipt_mode != $receipt_mode) {
                $insertData[0] = array(
                    'parent_id' => $id,
                    'parent_type' => 'receipt',
                    'category_id' => $receipt->received_from,
                    'category_type' => 'customer',
                    'status' => 1,
                    'amount' => $multiplier * ($paid_amount),
                    'amount_type' => 'credit',
                    'created_date' => $currentDateTime,
                    'created_by' => $this->session->userdata['admin']['id'],
                    'financial_year' => $this->financial_year,
                );
                $insertData[1] = array(
                    'parent_id' => $id,
                    'parent_type' => 'receipt',
                    'category_id' => $asset_id,
                    'category_type' => 'coa',
                    'status' => 1,
                    'amount' => ($paid_amount),
                    'amount_type' => 'debit',
                    'created_date' => $currentDateTime,
                    'created_by' => $this->session->userdata['admin']['id'],
                    'financial_year' => $this->financial_year,
                );
                if (count($insertData)) {
                    $this->transaction_model->updateLogs($id, 'receipt', $currentDateTime, $insertData);
                }
            }

            if ($sendEmail) {
                //
            }
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('receipt_updated'), 'type' => 'success'));

            redirect("account/receipt");
        } else {

            //redirect
            $this->editReceipt($id);
        }
    }


    function deleteReceipt($id)
    {
        if (!$this->rbac->hasPrivilege('account_receipts', 'can_delete')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/receipt');
        $receipt = $this->receipt_model->getReceipt($id);
        if ($receipt->auto_created) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('cant_be_deleted'), 'type' => 'error'));

            redirect("account/receipt");
        }
        $concernedjournal_ids = array_unique(explode(',', $receipt->journalids));
        $concernedinvoice_ids = array_unique(explode(',', $receipt->invoiceids));
        $nextReceipts = $this->receipt_model->checkNextReceipt($id, $receipt->received_from, $concernedjournal_ids, $concernedinvoice_ids);
        if (isset($nextReceipts[0])) {
            $this->session->set_flashdata('msg', array('message' => $this->lang->line('cant_be_deleted'), 'type' => 'error'));

            redirect("account/receipt");
        } else {
            $receiptdetails = $this->receipt_model->getReceiptDetails($id);
            $relatedjournalids = array();
            $relatedjournalidsstatus = array();
            $relatedinvoiceids = array();
            $relatedinvoiceidsstatus = array();

            foreach ($receiptdetails as $receiptdetail) {
                if ($receiptdetail->journal_id) {
                    array_push($relatedjournalids, $receiptdetail->journal_id);
                    if ((float)$receiptdetail->total == (float)$receiptdetail->remaining_amount) {
                        array_push($relatedjournalidsstatus, 0);
                    } else {
                        array_push($relatedjournalidsstatus, -1);
                    }
                }
                if ($receiptdetail->invoice_id) {
                    array_push($relatedinvoiceids, $receiptdetail->invoice_id);
                    if ((float)$receiptdetail->total == (float)$receiptdetail->remaining_amount) {
                        array_push($relatedinvoiceidsstatus, 0);
                    } else {
                        array_push($relatedinvoiceidsstatus, -1);
                    }
                }
            }
            $this->receipt_model->deleteReceipt($id);

            if (!empty($relatedjournalids)) {
                //whether to mark 0 or -1
                foreach ($relatedjournalids as $key => $relatedjournalid) {
                    $data[] = array('id' => $relatedjournalid, 'is_cleared' => $relatedjournalidsstatus[$key]);
                }
                $this->journal_model->changeStatus($data);
            }
            if (!empty($relatedinvoiceids)) {
                //whether to mark 0 or -1
                foreach ($relatedinvoiceids as $key => $relatedinvoiceid) {
                    $data[] = array('id' => $relatedinvoiceid, 'status' => $relatedinvoiceidsstatus[$key]);
                }
                $this->invoice_model->changeStatus($data);
            }

            $cashid = 11;
            //delete logs

            $this->transaction_model->unsetEntry($id, 'receipt', $receipt->received_from, 'customer');
            $this->transaction_model->unsetEntry($id, 'receipt', ($receipt->receipt_mode == "cheque") ? $receipt->bank : $cashid, 'coa');

            redirect("account/receipt");
        }
    }
}
