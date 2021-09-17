<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Account_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
    }

    public function getGeneralSettings()
    {
        $this->db->select('gen.*');
        $this->db->from('acc_general_settings as gen');
        $this->db->limit(1);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        return $query->row();
    }

    public function saveGeneralSettings($data)
    {
        if (isset($data['id'])) {
            unset($data['created_at']);
            unset($data['modified_at']);
            $this->db->where('id', $data['id']);
            $this->db->update('acc_general_settings', $data);
            return true;
        }/* else {
            $this->db->insert('acc_general_settings', $data);
            return $this->db->insert_id();
        }*/
    }

    public function getCurrentFinancialYearID()
    {
        $this->db->select('fa.*');
        $this->db->from('acc_financial_year as fa');
        $this->db->where('is_current', '1');
        $query = $this->db->get();
        return $query->row()->id;
    }

    public function getCurrentFinancialYear()
    {
        $this->db->select('fa.*');
        $this->db->from('acc_financial_year as fa');
        $this->db->where('is_current', '1');
        $query = $this->db->get();
        return $query->row();
    }

    public function getFinancialYearList()
    {
        $this->db->select('fa.*');
        $this->db->from('acc_financial_year as fa');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function setUpFinancialYear($data)
    {
        $this->db->update('acc_financial_year', array('is_current' => 0));
        $this->db->insert('acc_financial_year', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getFinancialYear($id)
    {
        $this->db->select('fa.*');
        $this->db->from('acc_financial_year as fa');
        $this->db->where('fa.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function closeFinancialyear($data)
    {

        $settings = $this->getGeneralSettings();
        if ($settings->date_system == 1) {

            list($startengyear, $startengmonth, $startengday) = explode('-', $data['year_starts']);
            $newdata['year_starts'] = ($startengyear + 1) . '-' . $startengmonth . '-' . $startengday;
            $this->bikram_sambat->setEnglishDate($startengyear + 1, $startengmonth, $startengday);
            $newnepalistart = $this->bikram_sambat->toNepaliString();
            $newdata['year_starts_bs'] = $newnepalistart;


            $newdata['year_ends'] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($data['year_ends'])) . " + 1 year"));
            $newdata['year_ends'] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($newdata['year_ends'])) . " - 1 day"));

            list($endengyear, $endengmonth, $endengday) = explode('-', $newdata['year_ends']);
            $this->bikram_sambat->setEnglishDate($endengyear, $endengmonth, $endengday);
            $newnepaliend = $this->bikram_sambat->toNepaliString();
            $newdata['year_ends_bs'] = $newnepaliend;
            $newdata['is_current'] = 1;

        }

        if ($settings->date_system == 2) {

            list($startnepyear, $startnepmonth, $startnepday) = explode('-', $data['year_starts_bs']);
            $newdata['year_starts_bs'] = ($startnepyear + 1) . '-' . $startnepmonth . '-' . $startnepday;
            $this->bikram_sambat->setNepaliDate($startnepyear + 1, $startnepmonth, $startnepday);
            $newenglishstart = $this->bikram_sambat->toEnglishString();
            $newdata['year_starts'] = $newenglishstart;

            list($endnepyear, $endnepmonth, $endnepday) = explode('-', $data['year_ends_bs']);
            $endyear = $endnepyear + 1;
            $endmonth = $endnepmonth;
            $lastdayofmonth = $this->bikram_sambat->getLastDayOf($endyear, $endmonth);
            $newdata['year_ends_bs'] = ($endyear) . '-' . $endmonth . '-' . $lastdayofmonth;
            $this->bikram_sambat->setNepaliDate($endyear, $endmonth, $lastdayofmonth);
            $newenglishend = $this->bikram_sambat->toEnglishString();
            $newdata['year_ends'] = $newenglishend;
            $newdata['is_current'] = 1;


        }
        $this->setUpFinancialYear($newdata);
        return true;
    }

    public function getYearlyTransaction($startDate, $endDate)
    {
        $this->load->model('account/account_COA_model');
        $banks = $this->account_COA_model->getBanksList();
        $banksarray = array(10, 11);
        foreach ($banks as $bank) {
            $banksarray[] = $bank->id;
        }
        $this->db->select('logs.*');
        $this->db->from('acc_transaction_logs as logs');
        $this->db->where('logs.category_type', 'coa');
        $this->db->where('logs.category_id IN (' . implode(",", $banksarray) . ')');

        $this->db->join('acc_receipt as rec', '(rec.id = logs.parent_id AND logs.parent_type = "receipt")', 'left');
        $this->db->join('acc_payment as pay', '(pay.id = logs.parent_id AND logs.parent_type = "payment")', 'left');
        $this->db->join('acc_journal as jrn', '(jrn.id = logs.parent_id AND logs.parent_type = "journal")', 'left');

        $this->db->select('CASE
                WHEN logs.parent_type = "receipt" THEN rec.receipt_date
                WHEN logs.parent_type = "payment" THEN pay.payment_date
                WHEN logs.parent_type = "journal" THEN jrn.entry_date
            END as date', FALSE);
        $this->db->select('CASE
                WHEN logs.parent_type = "receipt" THEN rec.receipt_date_bs
                WHEN logs.parent_type = "payment" THEN pay.payment_date_bs
                WHEN logs.parent_type = "journal" THEN jrn.entry_date_bs
            END as date_bs', FALSE);

        $this->db->where('(
        (pay.payment_date >= "' . $startDate . '" AND pay.payment_date <= "' . $endDate . '")
        OR (rec.receipt_date >= "' . $startDate . '" AND rec.receipt_date <= "' . $endDate . '")
        OR (jrn.entry_date >= "' . $startDate . '" AND jrn.entry_date <= "' . $endDate . '")
        )');

        $query = $this->db->get();
        return $query->result_array();
    }


}