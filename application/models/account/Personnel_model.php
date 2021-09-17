<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Personnel_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = 'acc_personnel';
        $this->financial_year = $this->session->userdata('account')['financial_year'];
        $this->load->model('account/openingBalance_model');
        $this->load->model('account/invoice_model');
    }

    function getPersonnelDetail($id, $financialyear = 0)
    {
        $this->db->select('personnel.*');
        $this->db->from($this->tableName . ' as personnel');
        if ($financialyear != 0) {
            $this->db->select('obal.balance, obal.balance_type');
            $this->db->join('acc_opening_balances as obal', '(personnel.id = obal.personnel_id AND obal.financial_year = "' . ($financialyear) . '")', 'left');
        }
        $this->db->where('personnel.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function getAllPersonnel()
    {
        $this->db->select('personnel.*');
        $this->db->from($this->tableName . ' as personnel');
//        $this->db->where('personnel.financial_year', $this->financial_year);
        $query = $this->db->get();
        return $query->result();
    }

    function getAllPersonnelByType($type)
    {
        $this->db->select('personnel.*');
        $this->db->from($this->tableName . ' as personnel');
        $this->db->where(
            array(
//                'personnel.financial_year' => $this->financial_year,
                'personnel.type' => $type
            )
        );
        $query = $this->db->get();
        return $query->result();
    }

    function savePersonnel($data)
    {
        $slug = create_unique_slug($data['name'], $this->tableName, $field = 'slug', $key = 'id', $value = $data['id']);
        $data['slug'] = $slug;
        $data['financial_year'] = $this->financial_year;

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $openingdata['balance'] = $data['balance'];
            $openingdata['balance_type'] = $data['balance_type'];
            $openingdata['personnel_id'] = $data['id'];
            unset($data['id']);
            $this->db->update($this->tableName, $data);
            if ($data['balance'] || $data['balance_type'] && $this->financial_year <= 1) {
                $this->openingBalance_model->updateOpeningPersonnelBalance($openingdata);
            }
        } else {
            $this->db->insert($this->tableName, $data);
            $id = $this->db->insert_id();
            $data = array(
                'balance' => $this->financial_year <= 1 ? $data['balance'] : 0,
                'balance_type' => $data['balance_type'],
                'personnel_id' => $id,
                'coa_id' => 0,
                'created_at' => $this->customlib->getCurrentTime(),
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year
            );
            if ($this->financial_year <= 1) {
                $this->openingBalance_model->addOpeningBalance($data);
            }
            return $id;
        }
    }

    function updatePersonnelBalance($data)
    {
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update($this->tableName, $data);
        return true;
    }

    function delete($id)
    {
        $this->db->delete($this->tableName, array('id' => $id));
        return true;
    }

    function getPersonnelList($postData = null, $financial_year = 1)
    {

        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $type = $postData['type'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value

        ## Total number of records without filtering
        $records = $this->getRecords('', 0, 0, $type, $financial_year);

        $totalRecords = count($records);

        ## Total number of record with filtering
        $records = $this->getRecords($searchValue, 0, 0, $type, $financial_year);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $records = $this->getRecords($searchValue, $rowperpage, $start, $type, $financial_year);

        foreach ($records as $eachdata) {
            if (strtolower($type) == 'customer') {
                $multiplier = strtolower($eachdata->balance_type) == 'credit' ? -1 : 1;
                $eachdata->total = isset($eachdata->logamount) ? (($multiplier * $eachdata->balance) + $eachdata->logamount) : $multiplier * $eachdata->balance;
                $eachdata->balance = $multiplier * $eachdata->balance;
            }
            if (strtolower($type == 'supplier')) {
                $eachdata->balance = ($eachdata->balance_type == "debit") ? $eachdata->balance * (-1) : $eachdata->balance;
                $eachdata->total = (isset($eachdata->logamount) ? $eachdata->logamount : 0) + $eachdata->balance;

            }
        }
        $data = array();

        foreach ($records as $key => $record) {
            $actionbuttons = '';

            $actionbuttons .= '<a href="' . base_url() . 'account/personnel/ledger/' . $record->id . '/' . $financial_year . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("view") . '">
                                                    <i class="fa fa-eye"></i>
                                                </a>';
            if ($this->rbac->hasPrivilege('account_personnel', 'can_edit')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/personnel/edit/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("edit") . '">
                                                    <i class="fa fa-pencil"></i>
                                                </a>';
            }
            if ($this->rbac->hasPrivilege('acoount_personnel', 'can_delete')) {
                $actionbuttons .= '<a href="' . base_url() . 'account/personnel/delete/' . $record->id . '"
                                                   class="btn btn-default btn-xs" data-toggle="tooltip"
                                                   title="' . $this->lang->line("delete") . '"
                                                   onclick="return confirm(\'' . $this->lang->line("delete_confirm") . '\');">
                                                    <i class="fa fa-remove"></i>
                                                </a>';

            }

            $pagenum = $start / $rowperpage + 1;
            $data[] = array(
                "count" => ($key + 1) + ($rowperpage * ($pagenum - 1)),
                "name" => $record->name,
                "code" => $record->code,
                "email" => $record->email,
                "contact" => $record->contact,
                "closing_balance" => $this->accountlib->currencyFormat($record->total, true, 2, '.', ',', true),
                "action" => $actionbuttons

            );
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

    function getRecords($searchValue, $rowperpage, $start, $type, $financialyear = 1)
    {
        $this->db->select('personnel.*,SUM(logs.amount) as logamount');
        $this->db->select('ABS(SUM(CASE WHEN logs.amount_type = "debit" then logs.amount else 0 end)) as coaDebitSum');
        $this->db->select('ABS(SUM(CASE WHEN logs.amount_type = "credit" then logs.amount else 0 end)) as coaCreditSum');
        $this->db->from($this->tableName . ' as personnel');
        if ($type) {
            $this->db->where(
                array(
                    'personnel.type' => $type
                )
            );
        }

        $this->db->select('obal.balance as obal,obal.balance_type as obaltype');
        $this->db->join('acc_opening_balances as obal', '(personnel.id = obal.personnel_id AND obal.financial_year = "' . ($financialyear) . '")', 'left');

        $typearray = array();
        if ($type) {
            array_push($typearray, '"' . $type . '"');
        } else {
            array_push($typearray, "'supplier'", "'customer'");
        }
        $this->db->join('acc_transaction_logs as logs', '(personnel.id = logs.category_id AND logs.category_type IN (' . implode(",", $typearray) . ')) AND logs.status=1 AND logs.parent_id!=0 AND logs.financial_year = ' . $financialyear, 'left');
        $this->db->group_by('personnel.id');
        if ($searchValue != '') {
            $this->db->like('personnel.name', $searchValue);
        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }

        $query = $this->db->get();
        $result = $query->result();

        foreach ($result as $eachresult) {
            if (isset($eachresult->obal)) {
                $eachresult->balance = $eachresult->obal;
                $eachresult->balance_type = strtolower($eachresult->obaltype);
            } else {
                $eachresult->balance = 0;
                $eachresult->balance_type = 'debit';
            }
        }
        return $result;
    }


    function getPersonnelLedger($financialyear = 1, $type = NULL)
    {
        $this->db->select('personnel.*,SUM(logs.amount) as logamount');
        $this->db->select('ABS(SUM(CASE WHEN logs.amount_type = "debit" then logs.amount else 0 end)) as coaDebitSum');
        $this->db->select('ABS(SUM(CASE WHEN logs.amount_type = "credit" then logs.amount else 0 end)) as coaCreditSum');
        $this->db->from($this->tableName . ' as personnel');
        if ($type) {
            $this->db->where(
                array(
                    'personnel.type' => $type
                )
            );
        }

        $this->db->select('obal.balance as obal,obal.balance_type as obaltype');
        $this->db->join('acc_opening_balances as obal', '(personnel.id = obal.personnel_id AND obal.financial_year = "' . ($financialyear) . '")', 'left');

        $typearray = array();
        if ($type) {
            array_push($typearray, '"' . $type . '"');
        } else {
            array_push($typearray, "'supplier'", "'customer'");
        }
        $this->db->join('acc_transaction_logs as logs', '(personnel.id = logs.category_id AND logs.category_type IN (' . implode(",", $typearray) . ')) AND logs.status=1 AND logs.parent_id!=0 AND logs.financial_year = ' . $financialyear, 'left');
        $this->db->group_by('personnel.id');
        $query = $this->db->get();
        $result = $query->result();

        foreach ($result as $eachresult) {
            if (isset($eachresult->obal)) {
                $eachresult->balance = $eachresult->obal;
                $eachresult->balance_type = strtolower($eachresult->obaltype);
            } else {
                $eachresult->balance = 0;
                $eachresult->balance_type = 'debit';
            }
        }
        return $result;
    }

    function getPersonnelTrialBalance($financial_year = NULL)
    {

        $heading = array(1 => 'supplier', 2 => 'customer');
        foreach ($heading as $key => $eachheading) {
            $masterarray[$key] = array();
            $masterarray[$key]['debitTotal'] = 0;
            $masterarray[$key]['creditTotal'] = 0;
            $masterarray[$key]['openingCreditTotal'] = 0;
            $masterarray[$key]['openingDebitTotal'] = 0;
            $masterarray[$key]['transactionDebitTotal'] = 0;
            $masterarray[$key]['transactionCreditTotal'] = 0;
        }
        if ($financial_year == NULL) {
            $financial_year = $this->financial_year;
        }
        $list = $this->getPersonnelLedger($financial_year);
        //switching roles for personnel as per credit/debit value
        foreach ($list as $index => $eachdata) {
            $key = array_search($eachdata->type, $heading);
            if ($eachdata->type == 'supplier') {
                $amount = ((($eachdata->obaltype == 'debit') ? (-1) : 1) * $eachdata->obal) + $eachdata->logamount;

                if ($amount < 0) {
                    $list[$index]->type = 'customer';
                    $list[$index]->statuschanged = 1;
                }
                continue;
            }
            if ($eachdata->type == 'customer') {
                $amount = ((($eachdata->obaltype == 'credit') ? (-1) : 1) * $eachdata->obal) + $eachdata->logamount;
                if ($amount < 0) {
                    $list[$index]->type = 'supplier';
                    $list[$index]->statuschanged = 1;
                }
            }
        }
        //switching roles end

        foreach ($list as $index => $eachdata) {
            $key = array_search($eachdata->type, $heading);
            if ($eachdata->type == 'supplier') {

                $masterarray[$key]['openingCreditTotal'] += (strtolower($eachdata->obaltype) == "credit") ? $eachdata->obal : 0;
                $list[$index]->openingCreditTotal = (strtolower($eachdata->obaltype) == "credit") ? $eachdata->obal : 0;
                $masterarray[$key]['openingDebitTotal'] += (strtolower($eachdata->obaltype) == "debit") ? $eachdata->obal : 0;
                $list[$index]->openingDebitTotal = (strtolower($eachdata->obaltype) == "debit") ? $eachdata->obal : 0;

                $eachdata->total = (isset($eachdata->logamount) ? $eachdata->logamount : 0);
                //$eachdata->total = (isset($eachdata->logamount) ? $eachdata->logamount : 0) + $eachdata->balance;
                //incluce opening balance in total
                if ($eachdata->statuschanged) {
                    //as it should mimic customer behaviour which it is, although showed as supplier because of -ve balue
                    $eachdata->balance = (strtolower($eachdata->obaltype) == "credit") ? $eachdata->obal * (-1) : $eachdata->obal;
                    if ($eachdata->total > 0) {
                        $eachdata->debitTotal = abs($eachdata->total);
                        $eachdata->creditTotal = 0;
                    } else {
                        $eachdata->creditTotal = abs($eachdata->total);
                        $eachdata->debitTotal = 0;
                    }
                } else {
                    $eachdata->balance = (strtolower($eachdata->obaltype) == "debit") ? $eachdata->obal * (-1) : $eachdata->obal;
                    if ($eachdata->total > 0) {
                        $eachdata->creditTotal = abs($eachdata->total);
                        $eachdata->debitTotal = 0;
                    } else {
                        $eachdata->debitTotal = abs($eachdata->total);
                        $eachdata->creditTotal = 0;
                    }
                }
            }
            if ($eachdata->type == 'customer') {

                $masterarray[$key]['openingCreditTotal'] += (strtolower($eachdata->obaltype) == "credit") ? $eachdata->obal : 0;
                $list[$index]->openingCreditTotal = (strtolower($eachdata->obaltype) == "credit") ? $eachdata->obal : 0;

                $masterarray[$key]['openingDebitTotal'] += (strtolower($eachdata->obaltype) == "debit") ? $eachdata->obal : 0;
                $list[$index]->openingDebitTotal = (strtolower($eachdata->obaltype) == "debit") ? $eachdata->obal : 0;


                $eachdata->total = (isset($eachdata->logamount) ? $eachdata->logamount : 0);
                //$eachdata->total = (isset($eachdata->logamount) ? $eachdata->logamount : 0) + $eachdata->balance;
                //incluce opening balance in total

                if ($eachdata->statuschanged) {
                    //as it should mimic supplier behaviour which it is, although showed as customer because of -ve balue
                    $eachdata->balance = (strtolower($eachdata->obaltype) == "debit") ? $eachdata->obal * (-1) : $eachdata->obal;
                    if ($eachdata->total > 0) {
                        $eachdata->creditTotal = abs($eachdata->total);
                        $eachdata->debitTotal = 0;
                    } else {
                        $eachdata->debitTotal = abs($eachdata->total);
                        $eachdata->creditTotal = 0;
                    }
                } else {
                    $eachdata->balance = (strtolower($eachdata->obaltype) == "credit") ? $eachdata->obal * (-1) : $eachdata->obal;
                    if ($eachdata->total > 0) {
                        $eachdata->debitTotal = abs($eachdata->total);
                        $eachdata->creditTotal = 0;
                    } else {
                        $eachdata->creditTotal = abs($eachdata->total);
                        $eachdata->debitTotal = 0;
                    }
                }
            }

            $masterarray[$key]['debitTotal'] += $eachdata->debitTotal;
            $list[$index]->debitTotal = $eachdata->coaDebitSum + $eachdata->openingDebitTotal - $eachdata->coaCreditSum - $eachdata->openingCreditTotal;

            $masterarray[$key]['creditTotal'] += $eachdata->creditTotal;
            $list[$index]->creditTotal = $eachdata->coaCreditSum + $eachdata->openingCreditTotal - $eachdata->coaDebitSum - $eachdata->openingDebitTotal;

            $masterarray[$key]['transactionDebitTotal'] += $eachdata->coaDebitSum;
            $list[$index]->transactionDebitTotal = $eachdata->coaDebitSum;

            $masterarray[$key]['transactionCreditTotal'] += $eachdata->coaCreditSum;
            $list[$index]->transactionCreditTotal = $eachdata->coaCreditSum;
            array_push($masterarray[$key], $eachdata);
        }

        foreach ($heading as $key => $heading) {
            $tempdebittotal = $masterarray[$key]['debitTotal'];
            $tempcredittotal = $masterarray[$key]['creditTotal'];
            $tempopeningdebittotal = $masterarray[$key]['openingDebitTotal'];
            $tempopeningcredittotal = $masterarray[$key]['openingCreditTotal'];
            $masterarray[$key]['debitTotal'] = ($tempdebittotal > $tempcredittotal) ? $tempdebittotal - $tempcredittotal : 0;
            $masterarray[$key]['creditTotal'] = ($tempcredittotal > $tempdebittotal) ? $tempcredittotal - $tempdebittotal : 0;
            $masterarray[$key]['openingDebitTotal'] = ($tempopeningdebittotal > $tempopeningcredittotal) ? $tempopeningdebittotal - $tempopeningcredittotal : 0;
            $masterarray[$key]['openingCreditTotal'] = ($tempopeningcredittotal > $tempopeningdebittotal) ? $tempopeningcredittotal - $tempopeningdebittotal : 0;


        }
        return $masterarray;
    }

    function getPersonnelLedgerDetail($id, $type, $financial_year)
    {
        //this query subset is for bringing the concerned account details from invoice
        $this->db->select('inv.*, GROUP_CONCAT(coa.name) as coanameconcatinv, GROUP_CONCAT(coa.id) as coaidconcatinv,GROUP_CONCAT(per2.name) as personnameconcatinv,GROUP_CONCAT(per2.id) as personidconcatinv');
        $this->db->from('acc_invoice as inv');
        $this->db->join('acc_personnel as per', '(per.id = inv.customer_id)', 'inner');
        $this->db->join('acc_invoice_entry as inv2', '(inv2.invoice_id = inv.id)', 'inner');
        $this->db->join('acc_personnel as per2', '(per2.id = inv2.personnel_id)', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coa', '(coa.id = inv2.coa_id)', 'left');
        $this->db->where('per.id', $id);
        $this->db->group_by('inv.id');
        $invsubquery = $this->db->get_compiled_select();

        //this query subset is for bringin the concerned account details from journal
        $this->db->select('jrn.*, GROUP_CONCAT(coa.name) as coanameconcatjrn, GROUP_CONCAT(coa.id) as coaidconcatjrn,GROUP_CONCAT(per2.name) as personnameconcatjrn,GROUP_CONCAT(per2.id) as personidconcatjrn');
        $this->db->from('acc_journal as jrn');
        $this->db->join('acc_journal_entry as jrn21', '(jrn21.journal_id = jrn.id)', 'inner');
        $this->db->join('acc_personnel as per', '(per.id = jrn21.personnel_id)', 'inner');
        $this->db->join('acc_journal_entry as jrn22', '(jrn22.journal_id = jrn.id)', 'inner');
        $this->db->join('acc_personnel as per2', '(per2.id = jrn22.personnel_id AND per2.id != jrn21.personnel_id)', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coa', '(coa.id = jrn22.coa_id)', 'left');
        $this->db->where('per.id', $id);
        $this->db->group_by('jrn.id');
        $jrnsubquery = $this->db->get_compiled_select();
        //this is the main query.
        $this->db->select('logs.*,logs.amount as logamount');
        $this->db->select('jrnmain.coanameconcatjrn, jrnmain.coaidconcatjrn, jrnmain.personnameconcatjrn, jrnmain.personidconcatjrn, jrnmain.entry_date_bs, jrnmain.entry_date, jrn.amount_type as jrntype, jrnmain.id as jrnid, jrnmain.narration as jrnnarration');
        $this->db->select('inv.coanameconcatinv, inv.coaidconcatinv, inv.personnameconcatinv, inv.personidconcatinv, inv.invoice_date_bs, inv.invoice_date, inv.id as invid, inv.description as invnarration');
        $this->db->select('pay.payment_date_bs, pay.payment_mode, pay.asset_id, pay.payment_date, pay.id as payid, pay.description as paynarration');
        $this->db->select('rec.receipt_mode, rec.asset_id, rec.receipt_date_bs, rec.receipt_date, rec.id as recid, rec.description as recnarration');
        $this->db->select('coapay.name as paybank, coarec.name as recbank');
        $this->db->from('acc_transaction_logs' . ' as logs');
        $this->db->join('(' . $invsubquery . ') as inv', '(logs.parent_type = "invoice" AND logs.parent_id=inv.id)', 'left');
        $this->db->join('acc_journal_entry as jrn', '(logs.parent_type = "journal" AND logs.parent_id=jrn.journal_id AND AND jrn.personnel_id="' . $id . '")', 'left');
        $this->db->join('(' . $jrnsubquery . ') as jrnmain', '(jrnmain.id=jrn.journal_id)', 'left');
        $this->db->join('acc_payment as pay', '(logs.parent_type="payment" AND logs.parent_id=`pay`.id)', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coapay', '(coapay.id=pay.asset_id)', 'left');
        $this->db->join('acc_receipt as rec', '(logs.parent_type="receipt" AND logs.parent_id=`rec`.id)', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coarec', '(coarec.id=rec.asset_id)', 'left');
        $this->db->select('CASE
                WHEN jrnmain.entry_date IS NOT NULL THEN jrnmain.entry_date
                WHEN inv.invoice_date IS NOT NULL THEN inv.invoice_date
                WHEN pay.payment_date IS NOT NULL THEN pay.payment_date
                WHEN rec.receipt_date IS NOT NULL THEN rec.receipt_date
            END as sortingdate', FALSE);
        $this->db->where(
            array(
                'logs.category_type' => $type,
                'logs.category_id' => $id,
                'logs.status' => 1,
                'logs.financial_year' => $financial_year,
            )
        );
        $this->db->where('logs.parent_id != 0');
        $this->db->order_by('sortingdate');
        $this->db->order_by('logs.id');
        $query = $this->db->get();

        return $query->result();

        // if concerned accounts listing is not necessary to show this query is enough to replace the above

//        $this->db->select('logs.*, inv.invoice_date_bs, jrnmain.entry_date_bs, pay.payment_date_bs,pay.payment_mode,pay.bank,rec.receipt_mode, rec.bank, rec.receipt_date_bs,inv.invoice_date, jrnmain.entry_date, pay.payment_date, rec.receipt_date, inv.id as invid,jrn.amount_type as jrntype, jrnmain.id as jrnid,pay.id as payid,rec.id as recid, inv.description as invnarration, jrnmain.narration as jrnnarration,pay.description as paynarration,rec.description as recnarration,coapay.name,coarec.name');
//        $this->db->select('CASE
//        WHEN jrnmain.entry_date !=NULL THEN jrnmain.entry_date
//        WHEN inv.invoice_date !=NULL THEN inv.invoice_date
//        WHEN pay.payment_date !=NULL THEN pay.payment_date
//        WHEN rec.receipt_date !=NULL THEN rec.receipt_date
//        END as sortingdate', FALSE);
//        $this->db->from('acc_transaction_logs' . ' as logs');
//        $this->db->join('acc_invoice as inv', '(logs.parent_type = "invoice" AND logs.parent_id=inv.id)', 'left');
//        $this->db->join('acc_journal_entry as jrn', '(logs.parent_type = "journal" AND logs.parent_id=jrn.journal_id AND AND jrn.personnel_id="'.$id.'")', 'left');
//        $this->db->join('acc_journal as jrnmain', '(jrnmain.id=jrn.journal_id)', 'left');
//        $this->db->join('acc_payment as pay', '(logs.parent_type="payment" AND logs.parent_id=`pay`.id)', 'left');
//        $this->db->join('acc_chart_of_accounts_detail as coapay', '(coapay.id=pay.bank)', 'left');
//        $this->db->join('acc_receipt as rec', '(logs.parent_type="receipt" AND logs.parent_id=`rec`.id)', 'left');
//        $this->db->join('acc_chart_of_accounts_detail as coarec', '(coarec.id=rec.bank)', 'left');
//        $this->db->where(
//            array(
//                'logs.category_type' => $type,
//                'logs.category_id' => $id,
//                'logs.status' =>1,
//            )
//        );
//        $this->db->order_by('sortingdate');
//        $query = $this->db->get();
//        return $query->result();
    }


    function getPersonnelLedgerDetailList($id, $type, $financial_year, $searchValue, $rowperpage, $start)
    {
        //this query subset is for bringing the concerned account details from invoice
        $this->db->select('inv.*, GROUP_CONCAT(coa.name) as coanameconcatinv, GROUP_CONCAT(coa.id) as coaidconcatinv,GROUP_CONCAT(per2.name) as personnameconcatinv,GROUP_CONCAT(per2.id) as personidconcatinv');
        $this->db->from('acc_invoice as inv');
        $this->db->join('acc_personnel as per', '(per.id = inv.customer_id)', 'inner');
        $this->db->join('acc_invoice_entry as inv2', '(inv2.invoice_id = inv.id)', 'inner');
        $this->db->join('acc_personnel as per2', '(per2.id = inv2.personnel_id)', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coa', '(coa.id = inv2.coa_id)', 'left');
        $this->db->where('per.id', $id);
        $this->db->group_by('inv.id');
        $invsubquery = $this->db->get_compiled_select();

        //this query subset is for bringin the concerned account details from journal
        $this->db->select('jrn.*, GROUP_CONCAT(coa.name) as coanameconcatjrn, GROUP_CONCAT(coa.id) as coaidconcatjrn,GROUP_CONCAT(per2.name) as personnameconcatjrn,GROUP_CONCAT(per2.id) as personidconcatjrn');
        $this->db->from('acc_journal as jrn');
        $this->db->join('acc_journal_entry as jrn21', '(jrn21.journal_id = jrn.id)', 'inner');
        $this->db->join('acc_personnel as per', '(per.id = jrn21.personnel_id)', 'inner');
        $this->db->join('acc_journal_entry as jrn22', '(jrn22.journal_id = jrn.id)', 'inner');
        $this->db->join('acc_personnel as per2', '(per2.id = jrn22.personnel_id AND per2.id != jrn21.personnel_id)', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coa', '(coa.id = jrn22.coa_id)', 'left');
        $this->db->where('per.id', $id);
        $this->db->group_by('jrn.id');
        $jrnsubquery = $this->db->get_compiled_select();
        //this is the main query.
        $this->db->select('logs.*,logs.amount as logamount');
        $this->db->select('jrnmain.coanameconcatjrn, jrnmain.coaidconcatjrn, jrnmain.personnameconcatjrn, jrnmain.personidconcatjrn, jrnmain.entry_date_bs, jrnmain.entry_date, jrn.amount_type as jrntype, jrnmain.id as jrnid, jrnmain.narration as jrnnarration');
        $this->db->select('inv.coanameconcatinv, inv.coaidconcatinv, inv.personnameconcatinv, inv.personidconcatinv, inv.invoice_date_bs, inv.invoice_date, inv.id as invid, inv.description as invnarration');
        $this->db->select('pay.payment_date_bs, pay.payment_mode, pay.asset_id, pay.payment_date, pay.id as payid, pay.description as paynarration');
        $this->db->select('rec.receipt_mode, rec.asset_id, rec.receipt_date_bs, rec.receipt_date, rec.id as recid, rec.description as recnarration');
        $this->db->select('coapay.name as paybank, coarec.name as recbank');
        $this->db->from('acc_transaction_logs' . ' as logs');
        $this->db->join('(' . $invsubquery . ') as inv', '(logs.parent_type = "invoice" AND logs.parent_id=inv.id)', 'left');
        $this->db->join('acc_journal_entry as jrn', '(logs.parent_type = "journal" AND logs.parent_id=jrn.journal_id AND AND jrn.personnel_id="' . $id . '")', 'left');
        $this->db->join('(' . $jrnsubquery . ') as jrnmain', '(jrnmain.id=jrn.journal_id)', 'left');
        $this->db->join('acc_payment as pay', '(logs.parent_type="payment" AND logs.parent_id=`pay`.id)', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coapay', '(coapay.id=pay.asset_id)', 'left');
        $this->db->join('acc_receipt as rec', '(logs.parent_type="receipt" AND logs.parent_id=`rec`.id)', 'left');
        $this->db->join('acc_chart_of_accounts_detail as coarec', '(coarec.id=rec.asset_id)', 'left');
        $this->db->select('CASE
                WHEN jrnmain.entry_date IS NOT NULL THEN jrnmain.entry_date
                WHEN inv.invoice_date IS NOT NULL THEN inv.invoice_date
                WHEN pay.payment_date IS NOT NULL THEN pay.payment_date
                WHEN rec.receipt_date IS NOT NULL THEN rec.receipt_date
            END as sortingdate', FALSE);
        $this->db->where(
            array(
                'logs.category_type' => $type,
                'logs.category_id' => $id,
                'logs.status' => 1,
                'logs.financial_year' => $financial_year,
            )
        );
        $this->db->where('logs.parent_id != 0');
        $this->db->order_by('sortingdate');
        $this->db->order_by('logs.id');
//        if ($searchValue != '') {
//            $this->db->like('logs.parent_type', $searchValue);
//        }
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }

        $query = $this->db->get();

        return $query->result();

        // if concerned accounts listing is not necessary to show this query is enough to replace the above

//        $this->db->select('logs.*, inv.invoice_date_bs, jrnmain.entry_date_bs, pay.payment_date_bs,pay.payment_mode,pay.bank,rec.receipt_mode, rec.bank, rec.receipt_date_bs,inv.invoice_date, jrnmain.entry_date, pay.payment_date, rec.receipt_date, inv.id as invid,jrn.amount_type as jrntype, jrnmain.id as jrnid,pay.id as payid,rec.id as recid, inv.description as invnarration, jrnmain.narration as jrnnarration,pay.description as paynarration,rec.description as recnarration,coapay.name,coarec.name');
//        $this->db->select('CASE
//        WHEN jrnmain.entry_date !=NULL THEN jrnmain.entry_date
//        WHEN inv.invoice_date !=NULL THEN inv.invoice_date
//        WHEN pay.payment_date !=NULL THEN pay.payment_date
//        WHEN rec.receipt_date !=NULL THEN rec.receipt_date
//        END as sortingdate', FALSE);
//        $this->db->from('acc_transaction_logs' . ' as logs');
//        $this->db->join('acc_invoice as inv', '(logs.parent_type = "invoice" AND logs.parent_id=inv.id)', 'left');
//        $this->db->join('acc_journal_entry as jrn', '(logs.parent_type = "journal" AND logs.parent_id=jrn.journal_id AND AND jrn.personnel_id="'.$id.'")', 'left');
//        $this->db->join('acc_journal as jrnmain', '(jrnmain.id=jrn.journal_id)', 'left');
//        $this->db->join('acc_payment as pay', '(logs.parent_type="payment" AND logs.parent_id=`pay`.id)', 'left');
//        $this->db->join('acc_chart_of_accounts_detail as coapay', '(coapay.id=pay.bank)', 'left');
//        $this->db->join('acc_receipt as rec', '(logs.parent_type="receipt" AND logs.parent_id=`rec`.id)', 'left');
//        $this->db->join('acc_chart_of_accounts_detail as coarec', '(coarec.id=rec.bank)', 'left');
//        $this->db->where(
//            array(
//                'logs.category_type' => $type,
//                'logs.category_id' => $id,
//                'logs.status' =>1,
//            )
//        );
//        $this->db->order_by('sortingdate');
//        $query = $this->db->get();
//        return $query->result();
    }


    function closePersonnelBalances($currentFinancialYearID)
    {
        $personnel = $this->getPersonnelLedger($this->financial_year);
        $insertdata = array();
        $created_by = $this->session->userdata['admin']['id'];
        $created_at = $this->customlib->getCurrentTime();
        foreach ($personnel as $eachdata) {
            if ($eachdata->type == 'customer') {
                $eachdata->balance = ($eachdata->balance_type == "credit") ? $eachdata->balance * (-1) : $eachdata->balance;
                $eachdata->total = (isset($eachdata->logamount) ? $eachdata->logamount : 0) + $eachdata->balance;
                if ($eachdata->total > 0) {
                    $balance_type = 'debit';
                } else {
                    $balance_type = 'credit';
                }
            } else if ($eachdata->type == 'supplier') {
                $eachdata->balance = ($eachdata->balance_type == "debit") ? $eachdata->balance * (-1) : $eachdata->balance;
                $eachdata->total = (isset($eachdata->logamount) ? $eachdata->logamount : 0) + $eachdata->balance;
                if ($eachdata->total > 0) {
                    $balance_type = 'credit';
                } else {
                    $balance_type = 'debit';
                }
            }
            $insertdata[] = array(
                'balance' => abs($eachdata->total),
                'balance_type' => $balance_type,
                'personnel_id' => $eachdata->id,
                'financial_year' => $currentFinancialYearID,
                'coa_id' => 0,
                'created_at' => $created_at,
                'created_by' => $created_by
            );
        }
        $this->openingBalance_model->batchaddOpeningBalance($insertdata);
    }

    public function saveSchoolPersonnel($data)
    {
        $formData['financial_year'] = $this->financial_year > 1 ? $this->financial_year : 1;
        $formData['parent_id'] = $data['id'];
        $formData['type'] = strtolower($data['personnel_type']) == 'student' ? 'customer' : 'supplier';
        $formData['balance'] = $data['account_balance'];
        $formData['balance_type'] = strtolower($data['account_balance_type']);
        $formData['category'] = $data['personnel_type'];
        $formData['pan'] = '';
        $formData['published'] = 1;

        if ($data['personnel_type'] == 'student') {
            $descriptionStart = $this->lang->line('student_info');
            $formData['name'] = $data['firstname'] . ' ' . $data['lastname'];

            $email = ($data['guardian_email'] != '') ? $data['guardian_email'] : $data['email'];
            $formData['email'] = $email;

            $formData['address'] = $data['guardian_address'] != '' ? $data['guardian_address'] : '';

            $contact = $data['guardian_phone'] != '' ? $data['guardian_phone'] : $data['mobileno'];
            $formData['contact'] = $contact;
            $formData['balance'] = $formData['balance'];
            $formData['balance_type'] = $formData['balance_type'];

            $formData['code'] = $data['admission_no'];//create_unique_slug($data['admission_no'], $this->tableName, $field = 'slug', $key = 'code', $value = $formData['code']);
        } else {
            $descriptionStart = $this->lang->line('staff_info');
            $formData['name'] = $data['name'] . ' ' . (isset($data['surname']) ? $data['surname'] : '');
            $formData['email'] = $data['email'];
            $formData['code'] = $data['employee_id'];
            $formData['contact'] = isset($data['contact_no']) ? $data['contact_no'] : '';
            $formData['pan'] = isset($data['pan_no']) ? $data['pan_no'] : '';
            $formData['address'] = isset($data['permanent_address']) ? $data['permanent_address'] : '';
        }


        $slug = create_unique_slug($formData['name'], $this->tableName, $field = 'slug', $key = 'code', $value = $formData['code']);
        $formData['slug'] = $slug;

        $formData['description'] = $descriptionStart . ' ' . $formData['name'] . ' (' . $formData['code'] . ')';
//        $dataform = $formData;

        $checkStudent = $this->checkStudent($formData['parent_id'], $formData['category']);
//        $balance = 0;
//        $balance_type = 'debit';
//        if ($formData['type'] == 'customer') {
//            $balance = $formData['balance'];
//            $balance_type = $formData['balance_type'];
//            $formData['balance'] = 0;
//            $formData['balance_type'] = 'debit';
//        }
        if ($checkStudent->id > 0) {
            $this->db->where(array('parent_id' => $formData['parent_id'], 'category' => $formData['category']));
//            unset($formData['parent_id']);
            $this->db->update($this->tableName, $formData);
            $this->db->where(array('parent_id' => $formData['parent_id'], 'category' => $formData['category']));
            $this->db->select('*');
            $this->db->from('acc_personnel');
            $query = $this->db->get();
            $personnel_id = $query->row()->id;
            $opening_bal = $this->openingBalance_model->checkOpeningBalanceExists($personnel_id, 0);
            $updateid = (int)$opening_bal->id;
            $modified_by = $this->session->userdata['admin']['id'];
            $modified_at = $this->customlib->getCurrentTime();
            $tempdata = array(
                'balance' => $formData['balance'],
                'balance_type' => $formData['balance_type'],
                'personnel_id' => $personnel_id,
                'coa_id' => 0,
                'modified_at' => $modified_at,
                'modified_by' => $modified_by
            );
            $this->openingBalance_model->updateOpeningBalance($tempdata, $updateid, false);
        } else {
            $this->db->insert($this->tableName, $formData);
            $id = $this->db->insert_id();
            $data = array(
                'balance' => $formData['balance'],
                'balance_type' => $formData['balance_type'],
                'personnel_id' => $id,
                'coa_id' => 0,
                'created_at' => $this->customlib->getCurrentTime(),
                'created_by' => $this->session->userdata['admin']['id'],
                'financial_year' => $this->financial_year
            );
            $this->openingBalance_model->addOpeningBalance($data);

        }
        $dataform = $formData;
        $balance = $dataform['balance'];
        $balance_type = $dataform['balance_type'];
        if ($dataform['type'] == 'customer' && $dataform['balance_type'] == 'debit') {
            $data = array_merge($dataform, array(
                'balance' => $balance,
                'balance_type' => $balance_type,
            ));
            if ($checkStudent->id > 0) {
                $invoice = $this->checkOutstandingInvoiceExists($checkStudent->id);
                if (!$invoice) {
                    $this->invoice_model->addOpeningBalanceAsOutStandingFees($data);
                } else {
                    if ($invoice->balance != $data['balance']) {
                        $this->updateOutStandingFees(array_merge($data, array('id' => $checkStudent->id, 'invoice_id' => $invoice->id)));
                    }
                }
            } else {
                $this->invoice_model->addOpeningBalanceAsOutStandingFees($data);
            }
        }

        /*        if ($checkStudent->id > 0) {
                    $this->db->where(array('parent_id' => $formData['parent_id'], 'category' => $formData['category']));
                    unset($formData['parent_id']);
                    $this->db->update($this->tableName, $formData);
                    if (!($formData['type'] == 'customer' && $balance_type == 'debit')) {
                        $data = array(
                            'balance' => $formData['balance'],
                            'balance_type' => $formData['balance_type'],
                            'personnel_id' => $checkStudent->id,
                            'coa_id' => 0,
                        );
                        $this->openingBalance_model->updateOpeningPersonnelBalance($data);
                    }

                } else {
                    $this->db->insert($this->tableName, $formData);
                    $id = $this->db->insert_id();
                    if (!($formData['type'] == 'customer' && $balance_type == 'debit')) {
                        $data = array(
                            'balance' => $formData['balance'],
                            'balance_type' => $formData['balance_type'],
                            'personnel_id' => $id,
                            'coa_id' => 0,
                            'created_at' => $this->customlib->getCurrentTime(),
                            'created_by' => $this->session->userdata['admin']['id'],
                            'financial_year' => $this->financial_year
                        );
                        $this->openingBalance_model->addOpeningBalance($data);
                    }
                }
                if ($dataform['type'] == 'customer' && $balance_type == 'debit') {
                    $data = array_merge($dataform, array(
                        'balance' => $balance,
                        'balance_type' => $balance_type,
                    ));
                    if ($checkStudent->id > 0) {
                        $invoice = $this->checkOutstandingInvoiceExists($checkStudent->id);
                        if(!$invoice){
                            $this->invoice_model->addOpeningBalanceAsOutStandingFees($data);
                        }
                        else{
                            if($invoice->balance!=$data['balance']){
                                $this->updateOutStandingFees(array_merge($data,array('id'=>$checkStudent->id,'invoice_id'=>$invoice->id)));
                            }
                        }

                    } else {
                        $this->invoice_model->addOpeningBalanceAsOutStandingFees($data);

                    }
                }*/

    }


    public function updateOutStandingFees($data)
    {
        $invoice_entry_data['rate'] = $data['balance'];
        $this->db->where('coa_id', 14);
        $this->db->where('invoice_id', $data['invoice_id']);
        $this->db->update('acc_invoice_entry', $invoice_entry_data);

//        $log_data['amount'] = -1 * $data['balance'];
//        $this->db->where('parent_type', 'invoice');
//        $this->db->where('parent_id', $data['invoice_id']);
//        $this->db->where('category_id', 14);
//        $this->db->where('category_type', 'coa');
//        $this->db->update('acc_transaction_logs', $log_data);
//
//        $log_data['amount'] = $data['balance'];
//        $this->db->where('parent_type', 'invoice');
//        $this->db->where('parent_id', $data['invoice_id']);
//        $this->db->where('category_id', $data['id']);
//        $this->db->where('category_type', 'customer');
//        $this->db->update('acc_transaction_logs', $log_data);
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

    public function checkStudent($parent_id, $category)
    {
        $this->db->select('*')->from($this->tableName)->where(array('parent_id' => $parent_id, 'category' => $category));
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }


    public function checkUniqueness($codeArray, $vatArray, $emailArray)
    {
        $vatquery = '';
        if (count($vatArray) > 0) {
            $vatquery = ' OR pan IN  ( ' . implode(',', $vatArray) . ') ';
        }
        $emailquery = '';
        if (count($emailArray) > 0) {
            $emailquery = ' OR email IN  ( ' . implode(',', $emailArray) . ')';
        }
        $this->db->select('*')->from($this->tableName)
            ->where('code IN  ( ' . implode(',', $codeArray) . ')' . $vatquery . $emailquery);
        $query = $this->db->get();
        return $query->num_rows();
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

    public function checkDuplicate($codeArray, $emailArray, $panArray)
    {
        $panquery = ' OR pan IN  ( ' . implode(',', $panArray) . ') ';
        $emailquery = ' OR email IN  ( ' . implode(',', $emailArray) . ') ';
        $this->db->select('email,pan,id,code')->from($this->tableName)
            ->where('code IN  ( ' . implode(',', $codeArray) . ')' . $emailquery . $panquery);
        $query = $this->db->get();
        $data = $query->result();
        $dataArray = array();
        foreach ($data as $datum) {
            $dataArray['"' . $datum->email . '"'] = $datum->id;
            $dataArray['"' . $datum->pan . '"'] = $datum->id;
            $dataArray['"' . $datum->code . '"'] = $datum->id;
        }
        return array('count' => $query->num_rows(), 'data' => $dataArray);
    }

    public function batchInsert($data, $rows, $codeArray, $type)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        $this->db->insert_batch($this->tableName, $data);
        $created_by = $this->session->userdata['admin']['id'];
        $created_at = $this->customlib->getCurrentTime();

        $codeIdArray = $this->getIdsFromCode($codeArray);
//        $codeIdArray
        $openingBalancesInsertData = array();
        foreach ($rows as $row) {
            if (!is_numeric($row['balance'])) {
                $row['balance'] = 0;
            } else {
                $row['balance'] += 0;
            }
            if (strtolower($type) == 'customer') {
                $balance_type = $row['balance'] >= 0 ? 'debit' : 'credit';
            } else {
                $balance_type = $row['balance'] >= 0 ? 'credit' : 'debit';
            }
            $balance = abs($row['balance']);
            $data = array(
                'balance' => $balance,
                'balance_type' => $balance_type,
                'personnel_id' => array_search($row['code'], $codeIdArray),
                'financial_year' => $this->financial_year,
                'coa_id' => 0,
                'created_at' => $created_at,
                'created_by' => $created_by
            );
            $openingBalancesInsertData[] = $data;
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


}