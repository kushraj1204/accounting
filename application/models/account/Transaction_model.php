<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Transaction_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->tableName = 'acc_transaction_logs';
        $this->load->library('accountlib');
        $this->financial_year = $this->session->userdata('account')['financial_year'];
        $this->load->model('account/account_category_model');
        $this->load->model('account/personnel_model');
        $this->level = $this->accountlib->getAccountSetting()->level;
    }

    function get_top_parent($id, $root_id = 0)
    { //category id whose parent id has to be found, top most category id
        $categories = $this->account_category_model->getAllCategories();
        $item_list = array();
        foreach ($categories as $item) {
            $item_list[$item->id] = $item->parent_id;
        }

        $current_category = $id;

        while (TRUE) {
            if ($item_list[$current_category] == $root_id) {
                // Check to see if we have found the parent category.
                return $current_category;
            } else {
                // update our current category
                $current_category = $item_list[$current_category];
            }
        }

        return false;

    }

    function checkTransactions($parent_id, $parent_type, $category_id, $category_type, $result_type = 'single')
    {
        $this->db->select('*')->from($this->tableName)
            ->where(
                array(
                    'parent_id' => $parent_id,
                    'parent_type' => $parent_type,
                    'category_id' => $category_id,
                    'category_type' => $category_type,
                    'status' => 1,
                )
            )
            ->order_by('id', 'DESC');
        if ($result_type == 'single') {
            $this->db->limit(1);
        }
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    /*function updateBalance($parent_id, $parent_type, $category_id, $category_type, $amount){
        $transaction = $this->transaction_model->checkTransactions($parent_id, $parent_type, $category_id, $category_type);
        $updateAmount = $amount;
        if($transaction->id > 0){
            $updateAmount = $amount - $transaction->amount;
        }

        $balance = 0;
        $table = '';
        if($category_type == 'customer' || $category_type == 'supplier'){
            $customer = $this->personnel_model->getPersonnelDetail($category_id);
            if($customer->balance_type == 'credit'){
                $balance = ($customer->type == 'customer') ? (-1 * $customer->balance) : $customer->balance;//-ve the +ve value
            }else{
                $balance = ($customer->type == 'supplier') ? (-1 * $customer->balance) : $customer->balance;//-ve the +ve value
            }
            $table = 'acc_personnel';
        }
        if($table == ''){
            return;
        }
        $balance = $balance + (float)$updateAmount;
        $balance_type = 'debit';
        if($balance < 0){
            $balance_type = ($customer->type == 'customer') ? 'credit' : 'debit';
            $balance = (-1 * $balance);//+ve the -ve value
        }
        $data['balance'] = $balance;
        $data['balance_type'] = $balance_type;
        $this->db->where('id', $category_id);
        $this->db->update($table, $data);
        $this->updateLogs($parent_id, $parent_type, $category_id, $category_type, $amount);//update logs
        //parent_id, parent_type, category_id, category_type, amount
    }*/

    function updateLogs($parent_id, $parent_type, $currentDateTime, $newData)
    {
        //update
        $data['status'] = 0;
        $this->db->where(array(
            'parent_id' => $parent_id,
            'parent_type' => $parent_type,
            'created_date <' => $currentDateTime,
        ));
        $this->db->update($this->tableName, $data);

        //insert
        $this->db->insert_batch($this->tableName, $newData);

    }

    function getMultiplier($category_type, $parent_category, $amount_type)
    {
        $multiplier = 1;
        switch ($category_type) {
            case 'coa':
                $root_parent = $this->transaction_model->get_top_parent($parent_category, 0);
                switch ($root_parent) {
                    case 1://assets
                    case 4://expenses
                        $multiplier = $amount_type == 'debit' ? $multiplier : -1;
                        break;
                    case 2://liabilities
                    case 3://income
                    case 5://equity
                        $multiplier = $amount_type == 'debit' ? -1 : $multiplier;
                        break;
                }
                break;
            case 'supplier':
                $multiplier = $amount_type == 'debit' ? -1 : $multiplier;
                break;
            case 'customer':
                $multiplier = $amount_type == 'debit' ? $multiplier : -1;
                break;
        }
        return $multiplier;
    }

    function insert($data)
    {
        $this->db->insert_batch($this->tableName, $data);
    }

    function unsetEntry($parent_id, $parent_type, $category_id, $category_type)
    {
        $data['status'] = 0;
        $this->db->where(array(
            'parent_id' => $parent_id,
            'parent_type' => $parent_type,
            'category_id' => $category_id,
            'category_type' => $category_type,
            'status' => 1
        ));
        $this->db->update('acc_transaction_logs', $data);
    }

    function getTransactionList($coa_id, $financial_year)
    {
        $this->db->select('CASE
                WHEN log.category_type = "coa" THEN coa.name
                WHEN log.category_type != "coa" THEN personnel.name
            END as name', FALSE);
        $this->db->select('CASE
                WHEN log.category_type = "coa" THEN coa.code
                WHEN log.category_type != "coa" THEN personnel.code
            END as code', FALSE);
        //date
        $this->db->select('CASE
                WHEN log.parent_type = "journal" THEN journal.entry_date
                WHEN log.parent_type = "invoice" THEN invoice.invoice_date
                WHEN log.parent_type = "receipt" THEN receipt.receipt_date
                WHEN log.parent_type = "payment" THEN payment.payment_date
            END as entry_date', FALSE);
        $this->db->select('CASE
                WHEN log.parent_type = "journal" THEN journal.entry_date_bs
                WHEN log.parent_type = "invoice" THEN invoice.invoice_date_bs
                WHEN log.parent_type = "receipt" THEN receipt.receipt_date_bs
                WHEN log.parent_type = "payment" THEN payment.payment_date_bs
            END as entry_date_bs', FALSE);
        $this->db->select('CASE
                WHEN log.parent_type = "journal" THEN journal.due_date_bs
                WHEN log.parent_type = "invoice" THEN invoice.due_date_bs
                WHEN log.parent_type = "receipt" THEN "-"
                WHEN log.parent_type = "payment" THEN "-"
            END as due_date_bs', FALSE);
        //date

        $this->db->select('log.id, log.parent_id, log.parent_type, log.category_id, log.category_type, log.amount');
        //main block
        $this->db->select('transaction.amount as transaction_amount');
        $this->db->from('acc_transaction_logs as log')
            ->join('acc_transaction_logs transaction',
                '`transaction`.`parent_id` = `log`.`parent_id` 
                AND `transaction`.`parent_type` = `log`.`parent_type`
                AND `transaction`.`status` = 1
                AND `transaction`.`category_type` = "coa"
                AND `log`.`amount_type` != `transaction`.`amount_type`
                AND `transaction`.`category_id` = ' . $coa_id .
                ' AND `transaction`.`financial_year` = ' . $financial_year,
                'inner')
            ->where(
                array(
                    'log.financial_year = ' => $financial_year,
                    'log.status' => 1,
                )
            )
            /*->where('(
                invoice.financial_year = ' . $financial_year . '
                OR journal.financial_year = ' . $financial_year . '
                OR receipt.financial_year = ' . $financial_year . '
                OR payment.financial_year = ' . $financial_year . '
            )')*/
            /*
            $this->db->select('COALESCE(coa.is_cash, 0) as is_cash, COALESCE(coa.is_bank, 0) as is_bank');
            ->having('is_cash', 0)
            ->having('is_bank', 0)
            */


            /*->select('SIGN(transaction.amount) as transaction_sign,
                    CASE
                        WHEN (log.parent_type = "invoice" AND log.category_type != "coa") OR log.category_type = "supplier"
                            THEN SIGN(-1 * log.amount)
                        ELSE SIGN(log.amount)
                    END as amount_sign')
            ->having('amount_sign != transaction_sign')*/

            ->group_by('transaction.id')
            ->order_by('log.id ASC');
        //main block

        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = log.category_id and log.category_type = "coa"', 'left');
        $this->db->join('acc_chart_of_accounts_detail as transactionCoa', 'transactionCoa.id = transaction.category_id and transaction.category_type = "coa"', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = log.category_id and log.category_type != "coa"', 'left');
        $this->db->join('acc_invoice as invoice', 'invoice.id = log.parent_id and log.parent_type = "invoice"', 'left');
        $this->db->join('acc_journal as journal', 'journal.id = log.parent_id and log.parent_type = "journal"', 'left');
        $this->db->join('acc_receipt as receipt', 'receipt.id = log.parent_id and log.parent_type = "receipt"', 'left');
        $this->db->join('acc_payment as payment', 'payment.id = log.parent_id and log.parent_type = "payment"', 'left');

        //categories
        //$this->db->select('category.title as categoryName');
        $this->db->select('CASE
                WHEN log.category_type = "coa" THEN coa.name
                WHEN log.category_type != "coa" THEN personnel.type
            END as categoryName', FALSE);
        if ($this->level >= 5) {
            $this->db->select('subcategory2.title as subCategory2Name');
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'left');
        }
        if ($this->level >= 4) {
            $this->db->select('subcategory1.title as subCategory1Name');
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'left');
        }
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'left');
        //categories

        $query = $this->db->get();
//        last_query();
        $result = $query->result();
        return $result;
    }


    function getTransactionLedgerList($coa_id, $financial_year, $searchValue, $rowperpage, $start)
    {
        $this->db->select('CASE
                WHEN log.category_type = "coa" THEN coa.name
                WHEN log.category_type != "coa" THEN personnel.name
            END as name', FALSE);
        $this->db->select('CASE
                WHEN log.category_type = "coa" THEN coa.code
                WHEN log.category_type != "coa" THEN personnel.code
            END as code', FALSE);
        //date
        $this->db->select('CASE
                WHEN log.parent_type = "journal" THEN journal.entry_date
                WHEN log.parent_type = "invoice" THEN invoice.invoice_date
                WHEN log.parent_type = "receipt" THEN receipt.receipt_date
                WHEN log.parent_type = "payment" THEN payment.payment_date
            END as entry_date', FALSE);
        $this->db->select('CASE
                WHEN log.parent_type = "journal" THEN journal.entry_date_bs
                WHEN log.parent_type = "invoice" THEN invoice.invoice_date_bs
                WHEN log.parent_type = "receipt" THEN receipt.receipt_date_bs
                WHEN log.parent_type = "payment" THEN payment.payment_date_bs
            END as entry_date_bs', FALSE);
        $this->db->select('CASE
                WHEN log.parent_type = "journal" THEN journal.due_date_bs
                WHEN log.parent_type = "invoice" THEN invoice.due_date_bs
                WHEN log.parent_type = "receipt" THEN "-"
                WHEN log.parent_type = "payment" THEN "-"
            END as due_date_bs', FALSE);
        //date

        $this->db->select('log.id, log.parent_id, log.parent_type, log.category_id, log.category_type, log.amount');
        //main block
        $this->db->select('transaction.amount as transaction_amount');
        $this->db->from('acc_transaction_logs as log')
            ->join('acc_transaction_logs transaction',
                '`transaction`.`parent_id` = `log`.`parent_id` 
                AND `transaction`.`parent_type` = `log`.`parent_type`
                AND `transaction`.`status` = 1
                AND `transaction`.`category_type` = "coa"
                AND `log`.`amount_type` != `transaction`.`amount_type`
                AND `transaction`.`category_id` = ' . $coa_id .
                ' AND `transaction`.`financial_year` = ' . $financial_year,
                'inner')
            ->where(
                array(
                    'log.financial_year = ' => $financial_year,
                    'log.status' => 1,
                )
            )
            /*->where('(
                invoice.financial_year = ' . $financial_year . '
                OR journal.financial_year = ' . $financial_year . '
                OR receipt.financial_year = ' . $financial_year . '
                OR payment.financial_year = ' . $financial_year . '
            )')*/
            /*
            $this->db->select('COALESCE(coa.is_cash, 0) as is_cash, COALESCE(coa.is_bank, 0) as is_bank');
            ->having('is_cash', 0)
            ->having('is_bank', 0)
            */


            /*->select('SIGN(transaction.amount) as transaction_sign,
                    CASE
                        WHEN (log.parent_type = "invoice" AND log.category_type != "coa") OR log.category_type = "supplier"
                            THEN SIGN(-1 * log.amount)
                        ELSE SIGN(log.amount)
                    END as amount_sign')
            ->having('amount_sign != transaction_sign')*/

            ->group_by('transaction.id')
            ->order_by('log.id ASC');
        //main block

        $this->db->join('acc_chart_of_accounts_detail as coa', 'coa.id = log.category_id and log.category_type = "coa"', 'left');
        $this->db->join('acc_chart_of_accounts_detail as transactionCoa', 'transactionCoa.id = transaction.category_id and transaction.category_type = "coa"', 'left');
        $this->db->join('acc_personnel as personnel', 'personnel.id = log.category_id and log.category_type != "coa"', 'left');
        $this->db->join('acc_invoice as invoice', 'invoice.id = log.parent_id and log.parent_type = "invoice"', 'left');
        $this->db->join('acc_journal as journal', 'journal.id = log.parent_id and log.parent_type = "journal"', 'left');
        $this->db->join('acc_receipt as receipt', 'receipt.id = log.parent_id and log.parent_type = "receipt"', 'left');
        $this->db->join('acc_payment as payment', 'payment.id = log.parent_id and log.parent_type = "payment"', 'left');

        //categories
        //$this->db->select('category.title as categoryName');
        $this->db->select('CASE
                WHEN log.category_type = "coa" THEN coa.name
                WHEN log.category_type != "coa" THEN personnel.type
            END as categoryName', FALSE);
        if ($this->level >= 5) {
            $this->db->select('subcategory2.title as subCategory2Name');
            $this->db->join('acc_coa_categories as subcategory2', 'coa.subcategory2 = subcategory2.id', 'left');
        }
        if ($this->level >= 4) {
            $this->db->select('subcategory1.title as subCategory1Name');
            $this->db->join('acc_coa_categories as subcategory1', 'coa.subcategory1 = subcategory1.id', 'left');
        }
        $this->db->join('acc_coa_categories as category', 'coa.category = category.id', 'left');
        //categories
        if ($rowperpage != 0) {
            $this->db->limit($rowperpage, $start);
        }
//        last_query();
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


}