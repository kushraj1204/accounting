<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ledger extends Account_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('account/account_COA_model');
        $this->load->model('account/transaction_model');
        $this->load->model('account/personnel_model');
        $this->load->model('account/openingBalance_model');
        $this->date_system = $this->accountlib->getAccountSetting()->date_system;
        $this->datechooser = $this->setting_model->getDatechooser();
        $opening_balance_date = $this->datechooser == 'bs' ? $this->accountlib->getAccountSetting()->opening_balance_date_bs : $this->accountlib->getAccountSetting()->opening_balance_date;
        if (!$opening_balance_date) {
            $financialYearStart = $this->accountlib->financialYearStart();
            $opening_balance_date = ($this->datechooser == 'bs' ? $financialYearStart->year_starts_bs : $financialYearStart->year_starts);
        }
        $this->opening_balance_date = $opening_balance_date;
        $this->level = $this->accountlib->getAccountSetting()->level;
        $this->financial_year = $this->session->userdata('account')['financial_year'];
        $this->load->library('bikram_sambat');
    }


    public function coaLedgerList()
    {

        $postData = $this->input->post();

        $data = $this->account_COA_model->getcoaLedgerList($postData);

        echo json_encode($data);
    }

    public function assets($financialYear = 0)
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/ledger/assets');
        $this->data['type'] = 'assets';
        if ($financialYear == 0) {
            $financialYear = $this->financial_year > 0 ? $this->financial_year : 1;
        }
        $this->data['selectedYear'] = $financialYear;
//        $personnel = $this->personnel_model->getPersonnelTrialBalance($financialYear);
//
//        $personnelbalances = array('customerreceivables' => 0, 'customerreceivablesprev' => 0);
//        foreach ($personnel as $key => $person) {
//
//            if ($key == 2) {
//                $personnelbalances['customerreceivablesprev'] = $person['openingDebitTotal'] - $person['openingCreditTotal'];
//                $personnelbalances['customerreceivables'] = $person['debitTotal'] - $person['creditTotal'] + $personnelbalances['customerreceivablesprev'];
//                if ($financialYear == 1) {
//                    $personnelbalances['customerreceivablesprev'] = 0;
//                }
//            }
//        }
        $years = $this->account_model->getFinancialYearList();

        $financial_year = array();
        foreach ($years as $year) {
            $starts = $year->year_starts_bs;
            if ($year->id == 1) {
                $starts = $this->opening_balance_date;
            }
            if ($this->datechooser == 'bs') {
                $year->display = $starts . ' - ' . $year->year_ends_bs;
            } else {
                $year->display = $starts . ' - ' . $year->year_ends;
            }
            $financial_year[$year->id] = $year;
        }

        $this->data['financial_years'] = $financial_year;

//        $openingBalances = $this->openingBalance_model->getOpeningBalances($financialYear, 3);
//        $balances = array();
//        foreach ($openingBalances as $balance) {
//            $balances[$balance->coa_id] = $balance;
//        }
//        $receivable = new stdClass();
//        $receivable->name = "Customer Receivables";
//        $receivable->code = "Receivables";
//        $receivable->openingbalance = $personnelbalances['customerreceivables'];
//        $this->data['opening_balance'] = $balances;
//        $this->data['assets'] = $this->account_COA_model->getCOAListForLedger($financialYear, 1);//1=>assets
//        $this->data['personnelbalance'] = $receivable;
        $this->data['opening_balance'] = [];
        $this->data['assets'] = [];//1=>assets
        $this->data['personnelbalance'] = [];
        $this->load->view('layout/header');
        $this->load->view('account/ledger/index', $this->data);
        $this->load->view('layout/footer');
    }

    public function liabilities($financialYear = 0)
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/ledger/liabilities');
        $this->data['type'] = 'liabilities';
        if ($financialYear == 0) {
            $financialYear = $this->financial_year > 0 ? $this->financial_year : 1;
        }
        $this->data['selectedYear'] = $financialYear;

//        $personnel = $this->personnel_model->getPersonnelTrialBalance($financialYear);
//
//        $personnelbalances = array('supplierpayables' => 0, 'supplierpayablesprev' => 0);
//        foreach ($personnel as $key => $person) {
//            if ($key == 1) {
//                $personnelbalances['supplierpayablesprev'] = $person['openingCreditTotal'] - $person['openingDebitTotal'];
//                $personnelbalances['supplierpayables'] = $person['creditTotal'] - $person['debitTotal'] + $personnelbalances['supplierpayablesprev'];
//                if ($financialYear == 1) {
//                    $personnelbalances['supplierpayablesprev'] = 0;
//                }
//
//            }
//
//        }
//        $payable = new stdClass();
//        $payable->name = "Supplier Payables";
//        $payable->code = "Payables";
//        $payable->openingbalance = $personnelbalances['supplierpayables'];
//        $this->data['personnelbalance'] = $payable;
        $this->data['personnelbalance'] = [];


        $years = $this->account_model->getFinancialYearList();
        $financial_year = array();
        foreach ($years as $year) {
            $starts = $year->year_starts_bs;
            if ($year->id == 1) {
                $starts = $this->opening_balance_date;
            }
            if ($this->datechooser == 'bs') {
                $year->display = $starts . ' - ' . $year->year_ends_bs;
            } else {
                $year->display = $starts . ' - ' . $year->year_ends;
            }
            $financial_year[$year->id] = $year;
        }

        $this->data['financial_years'] = $financial_year;
//        $openingBalances = $this->openingBalance_model->getOpeningBalances($financialYear, 4);
//        $balances = array();
//        foreach ($openingBalances as $balance) {
//            $balances[$balance->coa_id] = $balance;
//        }
//        $this->data['opening_balance'] = $balances;
//        $this->data['assets'] = $this->account_COA_model->getCOAListForLedger($financialYear, 2);//2=>liabilities

        $this->data['assets'] = [];//2=>liabilities
        $this->data['opening_balance'] = [];
        $this->load->view('layout/header');
        $this->load->view('account/ledger/index', $this->data);
        $this->load->view('layout/footer');
    }

    public function incomes($financialYear = 0)
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/ledger/incomes');
        $this->data['type'] = 'incomes';
        if ($financialYear == 0) {
            $financialYear = $this->financial_year > 0 ? $this->financial_year : 1;
        }
        $this->data['selectedYear'] = $financialYear;
        $years = $this->account_model->getFinancialYearList();
        $financial_year = array();
        foreach ($years as $year) {
            $starts = $year->year_starts_bs;
            if ($year->id == 1) {
                $starts = $this->opening_balance_date;
            }
            if ($this->datechooser == 'bs') {
                $year->display = $starts . ' - ' . $year->year_ends_bs;
            } else {
                $year->display = $starts . ' - ' . $year->year_ends;
            }
            $financial_year[$year->id] = $year;
        }

        $this->data['financial_years'] = $financial_year;
//        $openingBalances = $this->openingBalance_model->getOpeningBalances($this->financial_year, 5);
//        $balances = array();
//        foreach ($openingBalances as $balance) {
//            $balances[$balance->coa_id] = $balance;
//        }
//        $this->data['opening_balance'] = $balances;
//        $this->data['assets'] = $this->account_COA_model->getCOAListForLedger($financialYear, 3);//3=>incomes

        $this->data['opening_balance'] = [];
        $this->data['assets'] = [];//3=>incomes

        $this->load->view('layout/header');
        $this->load->view('account/ledger/index', $this->data);
        $this->load->view('layout/footer');
    }

    public function expenses($financialYear = 0)
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'account/ledger/expenses');
        $this->data['type'] = 'expenses';
        if ($financialYear == 0) {
            $financialYear = $this->financial_year > 0 ? $this->financial_year : 1;
        }
        $this->data['selectedYear'] = $financialYear;
        $years = $this->account_model->getFinancialYearList();
        $financial_year = array();
        foreach ($years as $year) {
            $starts = $year->year_starts_bs;
            if ($year->id == 1) {
                $starts = $this->opening_balance_date;
            }
            if ($this->datechooser == 'bs') {
                $year->display = $starts . ' - ' . $year->year_ends_bs;
            } else {
                $year->display = $starts . ' - ' . $year->year_ends;
            }
            $financial_year[$year->id] = $year;
        }

        $this->data['financial_years'] = $financial_year;
//        $openingBalances = $this->openingBalance_model->getOpeningBalances($this->financial_year, 6);
//        $balances = array();
//        foreach ($openingBalances as $balance) {
//            $balances[$balance->coa_id] = $balance;
//        }
//        $this->data['opening_balance'] = $balances;
//        $this->data['assets'] = $this->account_COA_model->getCOAListForLedger($financialYear, 4);//4=>expenses
        $this->data['opening_balance'] = [];
        $this->data['assets'] = [];//4=>expenses

        $this->load->view('layout/header');
        $this->load->view('account/ledger/index', $this->data);
        $this->load->view('layout/footer');
    }

    public function ledgerDetailList()
    {

        $postData = $this->input->post();

        $draw = $postData['draw'];
        $start = $postData['start'];
        $id = $postData['id'];
        $financialYear = $this->financial_year > 0 ? $this->financial_year : 1;
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value

        ## Total number of records without filtering

        $records = $this->transaction_model->getTransactionLedgerList($id, $financialYear, '', 0, 0);
        $totalRecords = count($records);
        ## Total number of record with filtering
        $records = $this->transaction_model->getTransactionLedgerList($id, $financialYear, $searchValue, 0, 0);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->transaction_model->getTransactionLedgerList($id, $financialYear, $searchValue, $rowperpage, $start);

        $data = array();


        $asset = $this->account_COA_model->get($id);
        $this->data['asset'] = $asset;
        $opening_balance = $this->openingBalance_model->getOpeningBalance('coa', $id, $financialYear);
        $top_parent = $this->transaction_model->get_top_parent($asset->category);
        $debitMultiplier = 1;
        $creditMultiplier = 1;
        $debitPlusArray = array(1, 4);//Asset, Expenses
        $creditPlusArray = array(2, 3, 5);//Liabilities, Incomes, Equity
        $zeroOpeningBalanceArray = array();//Incomes, Expenses
        if (in_array($top_parent, $debitPlusArray)) {
            $creditMultiplier = -1;
        } elseif (in_array($top_parent, $creditPlusArray)) {
            $debitMultiplier = -1;
        }
        $balanceMultiplier = strtolower($opening_balance->balance_type) == 'debit' ? $debitMultiplier : $creditMultiplier;
        $balance = $opening_balance->balance;

        if (in_array($top_parent, $zeroOpeningBalanceArray)) {
            $balanceMultiplier = 0;
            $balance = 0;
        }


        $sum = 0;

        $sum = $balanceMultiplier * $balance;

        if ($start != 0) {
            $openingBalancerecords = $this->transaction_model->getTransactionLedgerList($id, $financialYear, $searchValue, $start, 0);
            foreach ($openingBalancerecords as $key => $eachledger) {

                if (in_array($top_parent, $debitPlusArray)) {
                    $debit = $eachledger->transaction_amount >= 0 ? $eachledger->transaction_amount : 0;
                    $credit = $eachledger->transaction_amount < 0 ? -1 * $eachledger->transaction_amount : 0;
                } elseif (in_array($top_parent, $creditPlusArray)) {
                    $debit = $eachledger->transaction_amount < 0 ? -1 * $eachledger->transaction_amount : 0;
                    $credit = $eachledger->transaction_amount >= 0 ? $eachledger->transaction_amount : 0;
                } else {
                    $debit = $eachledger->transaction_amount >= 0 ? $eachledger->transaction_amount : 0;
                    $credit = $eachledger->transaction_amount < 0 ? -1 * $eachledger->transaction_amount : 0;
                }
                $sum = $sum + $debitMultiplier * $debit + $creditMultiplier * $credit;
            }
        }
        $debit = strtolower($opening_balance->balance_type) == 'debit' ? $balance : 0;
        $credit = strtolower($opening_balance->balance_type) == 'credit' ? $balance : 0;
        $openingdata = new stdClass();
        $openingdata->name = 'Opening Balance';
        $openingdata->categoryName = '-';
        $openingdata->subCategory1Name = '-';
        $openingdata->subCategory2Name = '-';
        $openingdata->date = '-';
        $openingdata->debit = $debit > 0 ? $this->accountlib->currencyFormat($debitMultiplier * $debit) : '-';
        $openingdata->credit = $credit > 0 ? $this->accountlib->currencyFormat($creditMultiplier * $credit) : '-';
        $openingdata->sum = $this->accountlib->currencyFormat($sum);
        $openingdata->action = '-';
        foreach ($records as $key => $eachledger) {

            if (in_array($top_parent, $debitPlusArray)) {
                $debit = $eachledger->transaction_amount >= 0 ? $eachledger->transaction_amount : 0;
                $credit = $eachledger->transaction_amount < 0 ? -1 * $eachledger->transaction_amount : 0;
            } elseif (in_array($top_parent, $creditPlusArray)) {
                $debit = $eachledger->transaction_amount < 0 ? -1 * $eachledger->transaction_amount : 0;
                $credit = $eachledger->transaction_amount >= 0 ? $eachledger->transaction_amount : 0;
            } else {
                $debit = $eachledger->transaction_amount >= 0 ? $eachledger->transaction_amount : 0;
                $credit = $eachledger->transaction_amount < 0 ? -1 * $eachledger->transaction_amount : 0;
            }
            $sum = $sum + $debitMultiplier * $debit + $creditMultiplier * $credit;
            $date = $this->datechooser == 'bs' ? $eachledger->entry_date_bs : $eachledger->entry_date;
            switch ($eachledger->parent_type) {
                case "receipt":
                    $link = 'account/receipt/editReceipt/';
                    break;
                case "payment":
                    $link = 'account/payment/editPayment/';
                    break;
                case "invoice":
                    $link = 'account/invoice/view/';
                    break;
                default:
                    $link = 'account/journal/view/';
                    break;
            }

            $actionbuttons = '<a target="_blank" href="' . base_url() . $link . $eachledger->parent_id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "name" => ($eachledger->name) . '-' . ($eachledger->code),
                "categoryName" => $eachledger->categoryName,
                "subCategory1Name" => $asset->subCategory1Name != '' ? $asset->subCategory1Name : '-',
                "subCategory2Name" => $asset->subCategory2Name != '' ? $asset->subCategory2Name : '-',
                "date" => $date,
                "debit" => $debit ? $this->accountlib->currencyFormat($debit, true, 2, '.', ',', true) : '-',
                "credit" => $credit ? $this->accountlib->currencyFormat($credit, true, 2, '.', ',', true) : '-',
                "sum" => $this->accountlib->currencyFormat($sum, true, 2, '.', ',', true),
                "action" => $actionbuttons

            );
        }
        array_unshift($data, (array)$openingdata);


        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }

    public function detail($id, $financialYear)
    {
        $this->session->set_userdata('top_menu', 'Accounts');
        $asset = $this->account_COA_model->get($id);
        $this->data['asset'] = $asset;
//        $this->data['opening_balance'] = $this->openingBalance_model->getOpeningBalance('coa', $id, $financialYear);
//        $this->data['logs'] = $this->transaction_model->getTransactionList($id, $financialYear);
        $this->data['opening_balance'] =[];
        $this->data['logs'] = [];
        $top_parent = $this->transaction_model->get_top_parent($asset->category);
        $this->data['top_parent'] = $top_parent;
        if ($top_parent == 1) {
            $this->data['type'] = $this->lang->line('asset');
            $this->session->set_userdata('sub_menu', 'account/ledger/assets');
        } elseif ($top_parent == 2) {
            $this->data['type'] = $this->lang->line('liability');
            $this->session->set_userdata('sub_menu', 'account/ledger/liabilities');
        } elseif ($top_parent == 3) {
            $this->data['type'] = $this->lang->line('income');
            $this->session->set_userdata('sub_menu', 'account/ledger/incomes');
        } elseif ($top_parent == 4) {
            $this->data['type'] = $this->lang->line('expense');
            $this->session->set_userdata('sub_menu', 'account/ledger/expenses');
        }
        $this->load->view('layout/header');
        $this->load->view('account/ledger/ledger', $this->data);
        $this->load->view('layout/footer');
    }


    function detailAjax()
    {
        $input = $this->input;
        $id = $input->post('id');
        $financialYear = $input->post('financial_year');

        $asset = $this->account_COA_model->get($id);


        $this->data['asset'] = $asset;
        $this->data['opening_balance'] = $this->openingBalance_model->getOpeningBalance('coa', $id, $financialYear);

        $this->data['logs'] = $this->transaction_model->getTransactionList($id, $financialYear);
        $top_parent = $this->transaction_model->get_top_parent($asset->category);
        $this->data['top_parent'] = $top_parent;

        echo json_encode($this->data);
        exit;
    }
}

?>