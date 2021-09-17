<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice extends Account_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model('account/invoice_model');
        $this->load->model('account/personnel_model');
        $this->load->model('account/receipt_model');
        $this->load->model('account/account_COA_model');
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
        $this->load->model('setting_model');
        $this->accountSetting = $this->accountlib->getAccountSetting();
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
        if (!$this->rbac->hasPrivilege('account_invoice', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/invoice/list');

        $this->data['invoices'] = [];
        $this->load->view('layout/header');
        $this->load->view('account/invoice/list', $this->data);
        $this->load->view('layout/footer');
    }

    public function invoiceList()
    {

        $postData = $this->input->post();

        $data = $this->invoice_model->getInvoiceList($postData);

        echo json_encode($data);
    }

    public function generatePDF($id)
    {
        $invoice = $this->invoice_model->getInvoiceDetail($id);
        $this->data['invoice'] = $invoice;
        $this->data['invoice_entries'] = $this->invoice_model->getInvoiceEntries($id);
        foreach ($this->data['invoice_entries'] as $entry) {
            $entry->balance_type = $this->invoice_model->checkDebitCredit($entry->coa_type);
        }
        $this->data['settings'] = (array)$this->setting_model->getSetting();

        $this->data['mgl'] = 5;
        $this->data['mgr'] = 5;
        $this->data['signature'] = 1;
        $layout = 'A4-L';
        $view = 'account/invoice/invoicePDF';

        $html = $this->load->view($view, $this->data, true);
        $file_name = $invoice->name . "_" . time();
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

    public function add_invoice()
    {
        if (!$this->rbac->hasPrivilege('account_invoice', 'can_add')) {
            access_denied();
        }
        $settings = $this->accountlib->getAccountSetting();
        $invoice_prefix = '';
        if ($settings->use_invoice_prefix) {
            $invoice_prefix = $settings->invoice_prefix;
        }
        $lastId = $this->invoice_model->getLastId();
        $lastId = $settings->invoice_start + $lastId;
        $invoice_id = $invoice_prefix . $lastId;
        $this->data['invoice_id'] = $invoice_id;//provide automatic invoice code
        $this->data['allow'] = 1;

        $customers = [];

        $coas = $this->account_COA_model->get();
        $personnels = $this->personnel_model->getAllPersonnel();
        $coa_accounts = array();
        foreach ($coas as $coa) {
            $account = new stdClass();
            $account->type = 'coa';
            $account->category = ucfirst($coa->subCategoryName);
            $account->id = $coa->id;
            $account->name = $coa->name;
            $account->parent_type = $coa->type;
            $account->code = $coa->code;
            $account->rate = $coa->rate;
            $coa_accounts[] = $account;
        }
        foreach ($personnels as $coa) {
            $account = new stdClass();
            $account->type = 'personnel';
            $account->category = ucfirst($coa->type);
            $account->id = $coa->id;
            $account->name = $coa->name;
            $account->parent_type = 0;
            $account->code = $coa->code;
            $account->rate = 0;
            $coa_accounts[] = $account;
            if ($coa->type == 'customer') {
                $customers[] = $account;
            }
        }

        usort($coa_accounts, function ($a, $b) {
            return strcmp(strtolower($a->name), strtolower($b->name));
        });
        $this->data['coa_accounts'] = $coa_accounts;
        $this->data['customers'] = $customers;

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/invoice/list');
        $this->load->view('layout/header');
        $this->load->view('account/invoice/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('account_invoice', 'can_add')) {
            access_denied();
        }
        $invoice = $this->invoice_model->getInvoiceDetail($id);
        $this->data['invoice'] = $invoice;
        $this->data['invoice_id'] = $invoice->code;
        $this->data['allow'] = $this->accountlib->checkEditPermission($invoice->created_date, $invoice->financial_year, 'allow_invoice_edit') && $invoice->fee_id == 0;

        $coas = $this->account_COA_model->get();
        $personnels = $this->personnel_model->getAllPersonnel();
        $coa_accounts = array();
        foreach ($coas as $coa) {
            $account = new stdClass();
            $account->type = 'coa';
            $account->category = ucfirst($coa->subCategoryName);
            $account->id = $coa->id;
            $account->name = $coa->name;
            $account->parent_type = $coa->type;
            $account->code = $coa->code;
            $account->rate = $coa->rate;
            $coa_accounts[] = $account;
        }
        foreach ($personnels as $coa) {
            $account = new stdClass();
            $account->type = 'personnel';
            $account->category = ucfirst($coa->type);
            $account->parent_type = 0;
            $account->id = $coa->id;
            $account->name = $coa->name;
            $account->parent_type = 0;
            $account->code = $coa->code;
            $account->rate = 0;
            $coa_accounts[] = $account;
            if ($coa->type == 'customer') {
                $customers[] = $account;
            }
        }

        usort($coa_accounts, function ($a, $b) {
            return strcmp(strtolower($a->name), strtolower($b->name));
        });

        $this->data['coa_accounts'] = $coa_accounts;
        $this->data['customers'] = $customers;
        $entries = $this->invoice_model->getInvoiceEntries($id);
        foreach ($entries as $entry) {
            $entry->balance_type = $this->invoice_model->checkDebitCredit($entry->coa_type);
        }
        $this->data['invoice_entries'] = $entries;

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/invoice/list');
        $this->load->view('layout/header');
        $this->load->view('account/invoice/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function save_invoice()
    {
        $input = $this->input;
        $id = (int)$input->post('id', 0);
        $is_unique = '|is_unique[acc_invoice.code]';
        $coa_id = $input->post('coa_id[]', []);

        $this->form_validation->set_error_delimiters('<span class="text-danger pull-right">', '</span>');
        if ($id == 0) {
            if (!$this->rbac->hasPrivilege('account_invoice', 'can_add')) {
                access_denied();
            }
        } else {
            if (!$this->rbac->hasPrivilege('account_invoice', 'can_edit')) {
                access_denied();
            }
            $query = $this->db->get_where('acc_invoice', array('id' => $id));
            $original_value = $query->row();
            if ($input->post('code') == $original_value->code) {
                $is_unique = '';
            }
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/invoice/list');

        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required' . $is_unique);
        $this->form_validation->set_rules('customer_id', $this->lang->line('customer_id'), 'required');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'required');
        if ($this->datechooser == 'bs') {
            $this->form_validation->set_rules('invoice_date_bs', $this->lang->line('invoice_date'), 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('invoice_date', $this->lang->line('invoice_date'), 'trim|required|xss_clean');
        }
        if ($this->form_validation->run() == TRUE && count($coa_id) > 0) {
            $code = $input->post('code', '');
            $invoice_date = $input->post('invoice_date', '');
            $invoice_date_bs = $input->post('invoice_date_bs', '');
            $due_date = $input->post('due_date', '');
            $due_date_bs = $input->post('due_date_bs', '');
            $reference_no = $input->post('reference_no', '');
            $registered_no = $input->post('registered_no', '');
            $customer_id = $input->post('customer_id', '');
            $description = $input->post('description', '');
            $bs_date = explode('-', $invoice_date_bs);
            $bs_year = $bs_date[0];
            $bs_month = $bs_date[1];
            $bs_day = $bs_date[2];

            $formValues = array(
                'code' => $code,
                'invoice_date' => date('Y-m-d', $this->customlib->datetostrtotime($invoice_date)),
                'due_date' => date('Y-m-d', $this->customlib->datetostrtotime($due_date)),
                'invoice_date_bs' => $invoice_date_bs,
                'due_date_bs' => $due_date_bs,
                'reference_no' => $reference_no,
                'registered_no' => $registered_no,
                'customer_id' => $customer_id,
                'description' => $description,
                'id' => $id,
                'bs_year' => $bs_year,
                'bs_month' => $bs_month,
                'bs_day' => $bs_day,
            );
            if ($id == 0) {
                $formValues['created_by'] = $this->session->userdata['admin']['id'];
                $formValues['created_date'] = $this->customlib->getCurrentTime();
            }
            $last_advance = $this->receipt_model->getLastAdvance($customer_id);
            if (count($last_advance) > 0) {
                $last_advance = $last_advance[0];
            }
            $advance_amount = $last_advance->advance_amount;
            if ($advance_amount > 0) {
                $rates = $input->post('rate', []);
                $totalAmt = 0;
                foreach ($rates as $rate) {
                    $totalAmt += $rate;
                }
                if ($advance_amount >= $totalAmt) {
                    $status = 1;
                }
                if ($advance_amount < $totalAmt) {
                    $status = -1;
                }
                $formValues['status'] = $status;
            }
            $invoiceId = $this->invoice_model->saveInvoice($formValues);
            if ($advance_amount > 0 && $invoiceId > 0) {
                $formValues['type'] = 'invoice';
                $this->receipt_model->adjustInvoiceWithAdvance($formValues, $totalAmt, $advance_amount, $invoiceId);
            }
            $msg = $this->lang->line('record_added');
            if ($id > 0) {
                $msg = $this->lang->line('record_updated');
            }
            $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'success'));
            redirect("account/invoice");
        } else {
            if (count($coa_id) == 0) {
                $this->session->set_flashdata('msg', array('message' => $this->lang->line('invoice_entries_not_submitted'), 'type' => 'danger'));
                if ($id > 0) {
                    redirect('account/invoice/edit/' . $id);
                } else {
                    redirect('account/invoice/add_invoice');
                }
            }
            $settings = $this->accountlib->getAccountSetting();
            $invoice_prefix = '';
            if ($settings->use_invoice_prefix) {
                $invoice_prefix = $settings->invoice_prefix;
            }
            $lastId = $this->invoice_model->getLastId();
            if ($lastId == 0) {
                $lastId = $settings->invoice_start;
            } else {
                $lastId++;
            }

            if ($id > 0) {
                $invoice = $this->invoice_model->getInvoiceDetail($id);
                $this->data['invoice'] = $invoice;
                $this->data['invoice_id'] = $invoice->code;
                $this->data['allow'] = $this->accountlib->checkEditPermission($invoice->created_date, $invoice->financial_year, 'allow_invoice_edit');
                $this->data['invoice_entries'] = $this->invoice_model->getInvoiceEntries($id);
            } else {
                $invoice_id = $invoice_prefix . $lastId;
                $this->data['invoice_id'] = $invoice_id;//provide automatic invoice code
            }

            $coas = $this->account_COA_model->get();
            $personnels = $this->personnel_model->getAllPersonnel();
            $coa_accounts = array();
            foreach ($coas as $coa) {
                $account = new stdClass();
                $account->type = 'coa';
                $account->category = ucfirst($coa->subcategoryname);
                $account->id = $coa->id;
                $account->name = $coa->name;
                $account->parent_type = $coa->type;
                $account->code = $coa->code;
                $account->rate = $coa->rate;
                $coa_accounts[] = $account;
            }
            foreach ($personnels as $coa) {
                $account = new stdClass();
                $account->type = 'personnel';
                $account->category = ucfirst($coa->type);
                $account->id = $coa->id;
                $account->name = $coa->name;
                $account->parent_type = 0;
                $account->code = $coa->code;
                $account->rate = 0;
                $coa_accounts[] = $account;
                if ($coa->type == 'customer') {
                    $customers[] = $account;
                }
            }

            usort($coa_accounts, function ($a, $b) {
                return strcmp(strtolower($a->name), strtolower($b->name));
            });

            $this->data['coa_accounts'] = $coa_accounts;
            $this->data['coa_accounts'] = $coa_accounts;
            $this->data['customers'] = $customers;

            $this->load->view('layout/header');
            $this->load->view('account/invoice/add', $this->data);
            $this->load->view('layout/footer');
        }
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('account_invoice', 'can_delete')) {
            access_denied();
        }
        $this->invoice_model->delete($id);
        $this->session->set_flashdata('msg', array('message' => $this->lang->line('record_deleted'), 'type' => 'success'));
        redirect("account/invoice");
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('account_invoice', 'can_view')) {
            access_denied();
        }
        $invoice = $this->invoice_model->getInvoiceDetail($id);
        $this->data['invoice'] = $invoice;
        $this->data['invoice_entries'] = $this->invoice_model->getInvoiceEntries($id);
        foreach ($this->data['invoice_entries'] as $entry) {
            $entry->balance_type = $this->invoice_model->checkDebitCredit($entry->coa_type);
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/invoice/list');
        $this->load->view('layout/header');
        $this->load->view('account/invoice/view', $this->data);
        $this->load->view('layout/footer');
    }

    public function sendMail($id)
    {
        $invoice = $this->invoice_model->getInvoiceDetail($id);
        if ($invoice->email != '') {
            $this->data['invoice'] = $invoice;
            $this->data['invoice_entries'] = $this->invoice_model->getInvoiceEntries($id);
            foreach ($this->data['invoice_entries'] as $entry) {
                $entry->balance_type = $this->invoice_model->checkDebitCredit($entry->coa_type);
            }
            $this->data['settings'] = (array)$this->setting_model->getSetting();
            $view = $this->load->view('account/invoice/invoicePDF', $this->data, TRUE);
            //send mail start
            $mailDetail = array('email' => $invoice->email, 'subject' => 'Invoice from school', 'msg' => $view);
            $this->mailsmsconf->mailsms('send_account_email', $mailDetail);

            $msg = $this->lang->line('mail_sent_successfully');
            $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'success'));
            redirect("account/invoice/view/" . $id);
            //send mail end
        }
        $msg = $this->lang->line('mail_not_found');
        $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'danger'));
        redirect("account/invoice/view/" . $id);
    }
}

?>