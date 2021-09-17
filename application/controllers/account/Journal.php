<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Journal extends Account_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('account/journal_model');
        $this->load->model('account/receipt_model');
        $this->load->model('account/payment_model');
        $this->load->model('account/account_COA_model');
        $this->load->model('account/personnel_model');
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
        $this->accountSetting = $this->accountlib->getAccountSetting();
        $this->load->library('mailer');
        $this->mailer;
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
        if (!$this->rbac->hasPrivilege('account_journal', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/journal/list');

//        $this->data['journals'] = $this->journal_model->getJournals();
        $this->data['journals'] = [];
        $this->load->view('layout/header');
        $this->load->view('account/journal/list', $this->data);
        $this->load->view('layout/footer');
    }

    public function journalList()
    {

        $postData = $this->input->post();

        $data = $this->journal_model->getJournalList($postData);

        echo json_encode($data);
    }

    public function add_journal()
    {
        if (!$this->rbac->hasPrivilege('account_journal', 'can_add')) {
            access_denied();
        }
        $settings = $this->accountlib->getAccountSetting();
        $journal_prefix = '';
        if ($settings->use_journal_prefix) {
            $journal_prefix = $settings->journal_prefix;
        }
        $lastId = $this->journal_model->getLastId();
        $lastId = $settings->journal_start + $lastId;
        $journal_id = $journal_prefix . $lastId;
        $this->data['journal_id'] = $journal_id;//provide automatic journal code
        $this->data['allow'] = 1;

        $coas = $this->account_COA_model->get();
        $personnels = $this->personnel_model->getAllPersonnel();
        $coa_accounts = array();
        foreach ($coas as $coa) {
            $account = new stdClass();
            $account->type = 'coa';
            $account->category = ucfirst($coa->subCategoryName);
            $account->id = $coa->id;
            $account->name = $coa->name;
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
            $account->code = $coa->code;
            $account->rate = 0;
            $coa_accounts[] = $account;
        }

        usort($coa_accounts, function ($a, $b) {
            return strcmp(strtolower($a->name), strtolower($b->name));
        });

        $this->data['coa_accounts'] = $coa_accounts;

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/journal/list');
        $this->load->view('layout/header');
        $this->load->view('account/journal/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('account_journal', 'can_add')) {
            access_denied();
        }
        $journal = $this->journal_model->getJournalDetail($id);
        $this->data['journal'] = $journal;
        $this->data['journal_id'] = $journal->code;
        $this->data['journal_entries'] = $this->journal_model->getJournalEntries($id);
        $this->data['allow'] = $this->accountlib->checkEditPermission($journal->created_date, $journal->financial_year, 'allow_journal_edit');

        $coas = $this->account_COA_model->get();
        $personnels = $this->personnel_model->getAllPersonnel();
        $coa_accounts = array();
        foreach ($coas as $coa) {
            $account = new stdClass();
            $account->type = 'coa';
            $account->category = ucfirst($coa->subCategoryName);
            $account->id = $coa->id;
            $account->name = $coa->name;
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
            $account->code = $coa->code;
            $account->rate = 0;
            $coa_accounts[] = $account;
        }

        usort($coa_accounts, function ($a, $b) {
            return strcmp(strtolower($a->name), strtolower($b->name));
        });

        $this->data['coa_accounts'] = $coa_accounts;

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/journal/list');
        $this->load->view('layout/header');
        $this->load->view('account/journal/add', $this->data);
        $this->load->view('layout/footer');
    }

    public function save_journal()
    {
        $input = $this->input;
        $id = $input->post('id', 0);
        $is_unique = '|is_unique[acc_journal.code]';
        $coa_id = $input->post('coa_id[]', []);


        if ($id == 0) {
            if (!$this->rbac->hasPrivilege('account_journal', 'can_add')) {
                access_denied();
            }
        } else {
            if (!$this->rbac->hasPrivilege('account_journal', 'can_edit')) {
                access_denied();
            }
            $query = $this->db->get_where('acc_journal', array('id' => $id));
            $original_value = $query->row();
            if ($input->post('code') == $original_value->code) {
                $is_unique = '';
            }
        }
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/journal/list');

        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required' . $is_unique);
        $this->form_validation->set_rules('narration', $this->lang->line('narration'), 'required');

        if ($this->datechooser == 'bs') {
            $this->form_validation->set_rules('entry_date_bs', $this->lang->line('entry_date'), 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('entry_date', $this->lang->line('entry_date'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == TRUE && count($coa_id) > 0) {
            $code = $input->post('code', '');
            $entry_date = $input->post('entry_date', '');
            $entry_date_bs = $input->post('entry_date_bs', '');
            $due_date = $input->post('due_date', '');
            $due_date_bs = $input->post('due_date_bs', '');
            $reference_no = $input->post('reference_no', '');
            $narration = $input->post('narration', '');
            $bs_date = explode('-', $entry_date_bs);
            $bs_year = $bs_date[0];
            $bs_month = $bs_date[1];
            $bs_day = $bs_date[2];

            $formValues = array(
                'code' => $code,
                'entry_date' => date('Y-m-d', $this->customlib->datetostrtotime($entry_date)),
                'entry_date_bs' => $entry_date_bs,
                'due_date' => date('Y-m-d', $this->customlib->datetostrtotime($due_date)),
                'due_date_bs' => $due_date_bs,
                'reference_no' => $reference_no,
                'narration' => $narration,
                'id' => $id,
                'bs_year' => $bs_year,
                'bs_month' => $bs_month,
                'bs_day' => $bs_day,
            );
            if ($id == 0) {
                $formValues['created_by'] = $this->session->userdata['admin']['id'];
                $formValues['created_date'] = $this->customlib->getCurrentTime();
            }
            $coa_id = $input->post('coa_id[]', []);
            $coa_type = $input->post('coa_type[]', []);
            $amount = $input->post('amount[]', []);
            $amount_type = $input->post('amount_type[]', []);

            $amt = array();
            $idarray = array();
            foreach ($coa_type as $key => $eachcoatype) {
                if ($eachcoatype == 'personnel') {
                    array_push($idarray, $coa_id[$key]);
                }
            }
            $uniqueIdArray = array_unique($idarray);
            $status = 0;
            $advance_usage = 0;
            if (count($uniqueIdArray) == 1) {
                $amt[$uniqueIdArray[0]] = 0;
                $personnel = $this->personnel_model->getPersonnelDetail($uniqueIdArray[0]);
                foreach ($coa_id as $key => $eachcoa) {
                    if ($eachcoa == $uniqueIdArray[0]) {
                        $multiplier = $personnel->type == 'customer' ? ($amount_type[$key] == 'debit' ? 1 : -1) : ($amount_type[$key] == 'credit' ? 1 : -1);
                        $amt[$uniqueIdArray[0]] += $multiplier * $amount[$key];
                    }
                }
                if ($amt[$uniqueIdArray[0]] > 0) {
                    $last_advance = $this->receipt_model->getLastAdvance($uniqueIdArray[0]);
                    if (count($last_advance) > 0) {
                        $last_advance = $last_advance[0];
                    }
                    $advance_amount = $last_advance->advance_amount;
                    if ($advance_amount > 0) {
                        $advance_usage = 1;
                        $totalAmt = $amt[$uniqueIdArray[0]];
                        if ($advance_amount >= $totalAmt) {
                            $status = 1;
                        }
                        if ($advance_amount < $totalAmt) {
                            $status = -1;
                        }
                        $formValues['is_cleared'] = $status;
                    }
                }
            }
            $formValues['is_cleared'] = $status;

            $journalid = $this->journal_model->saveJournal($formValues);
            if ($advance_usage == 1 && $journalid > 0) {
                $formValues['type'] = 'journal';
                $formValues['invoice_date'] = $formValues['entry_date'];
                $formValues['invoice_date_bs'] = $formValues['entry_date_bs'];
                $formValues['customer_id'] = $uniqueIdArray[0];
                if ($personnel->type == 'customer') {
                    $this->receipt_model->adjustInvoiceWithAdvance($formValues, $totalAmt, $advance_amount, $journalid);
                } else {
                    $this->payment_model->adjustJournalWithAdvance($formValues, $totalAmt, $advance_amount, $journalid);
                }
            }
            $msg = $this->lang->line('record_added');

            if ($id > 0) {
                $msg = $this->lang->line('record_updated');
            }

            $this->session->set_flashdata('msg', array('message' => $msg, 'type' => 'success'));

            redirect("account/journal");
        } else {
            if (count($coa_id) == 0) {
                $this->session->set_flashdata('msg', array('message' => $this->lang->line('journal_entries_not_submitted'), 'type' => 'danger'));
                if ($id > 0) {
                    redirect('account/journal/edit/' . $id);
                } else {
                    redirect('account/journal/add_journal');
                }
            }
            $settings = $this->accountlib->getAccountSetting();
            $journal_prefix = '';
            if ($settings->use_journal_prefix) {
                $journal_prefix = $settings->journal_prefix;
            }
            $lastId = $this->journal_model->getLastId();
            if ($lastId == 0) {
                $lastId = $settings->journal_start;
            } else {
                $lastId++;
            }
            $journal_id = $journal_prefix . $lastId;
            $this->data['journal_id'] = $journal_id;//provide automatic journal code

            $coas = $this->account_COA_model->get();
            $personnels = $this->personnel_model->getAllPersonnel();
            $coa_accounts = array();
            foreach ($coas as $coa) {
                $account = new stdClass();
                $account->type = 'coa';
                $account->category = ucfirst($coa->subCategoryName);
                $account->id = $coa->id;
                $account->name = $coa->name;
                $coa_accounts[] = $account;
            }
            foreach ($personnels as $coa) {
                $account = new stdClass();
                $account->type = 'personnel';
                $account->category = ucfirst($coa->type);
                $account->id = $coa->id;
                $account->name = $coa->name;
                $coa_accounts[] = $account;
            }

            usort($coa_accounts, function ($a, $b) {
                return strcmp(strtolower($a->name), strtolower($b->name));
            });

            $this->data['coa_accounts'] = $coa_accounts;
            if ($id > 0) {
                $journal = $this->journal_model->getJournalDetail($id);
                $this->data['journal'] = $journal;
                $this->data['journal_id'] = $journal->code;
                $this->data['journal_entries'] = $this->journal_model->getJournalEntries($id);
                $this->data['allow'] = $this->accountlib->checkEditPermission($journal->created_date, $journal->financial_year, 'allow_journal_edit');
            }

            $this->load->view('layout/header');
            $this->load->view('account/journal/add', $this->data);
            $this->load->view('layout/footer');

        }
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('account_journal', 'can_delete')) {
            access_denied();
        }
        $this->journal_model->delete($id);
        $this->session->set_flashdata('msg', array('message' => $this->lang->line('record_updated'), 'type' => 'success'));
        redirect("account/journal");
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('account_journal', 'can_view')) {
            access_denied();
        }
        $journal = $this->journal_model->getJournalDetail($id);
        $this->data['journal'] = $journal;
        $this->data['journal_entries'] = $this->journal_model->getJournalEntries($id);

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/journal/list');
        $this->load->view('layout/header');
        $this->load->view('account/journal/view', $this->data);
        $this->load->view('layout/footer');
    }
}

?>