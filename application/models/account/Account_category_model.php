<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Account_category_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('accountlib');
        $this->tableName = 'acc_coa_categories';
        //$this->financial_year = $this->session->userdata('account')['financial_year'];
        $level = $this->accountlib->getAccountSetting()->level;
        $this->level = $level >=3 && $level <= 5 ? $level : 4;
    }

    function getCategoryDetail($id) {
        $this->db->select('category.*, parent.lft as parent_lft');
        $this->db->from($this->tableName . ' as category');
        $this->db->join($this->tableName . ' as parent', 'parent.id = category.parent_id', 'left');
        $this->db->where('category.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function getAllCategories() {
        $this->db->select('category.*');
        $this->db->select('parent.title as parent_title');
        $this->db->from($this->tableName . ' as category');
        $this->db->join($this->tableName . ' as parent', 'parent.id = category.parent_id', 'left');
        //$this->db->where('category.title != ', 'Root');
        //$this->db->where('category.financial_year', $this->financial_year);
        $this->db->order_by('lft', 'parent_id', 'level');
        $query = $this->db->get();
        return $query->result();
    }

    function getAllCategoriesBySystemLevel($minus = 2) {
        $this->db->select('category.*');
        $this->db->select('count(children.id) as childrens');
        $this->db->select('parent.title as parent_title');
        $this->db->from($this->tableName . ' as category');
        $this->db->join($this->tableName . ' as parent', 'parent.id = category.parent_id', 'left');
        $this->db->join($this->tableName . ' as children', 'category.id = children.parent_id', 'left');
        $this->db->where('category.level <= ', $this->level - $minus);
        //$this->db->where('category.title != ', 'Root');
        //$this->db->where('category.financial_year', $this->financial_year);
        $this->db->group_by('category.id');
        $this->db->order_by('lft', 'parent_id', 'level');
        $query = $this->db->get();
        return $query->result();
    }

    function getNthTierAndChildCategories($level){
        $this->db->select('category.*');
        $this->db->from($this->tableName . ' as category');
        $this->db->where('category.level >= "'.$level.'"');
        $this->db->order_by('id ASC');
        //$this->db->where('category.financial_year', $this->financial_year);
        $query = $this->db->get();
        return $query->result();
    }

    function getAllCategoriesForEdit($lft, $rgt) {
        $this->db->select('category.*');
        $this->db->select('parent.title as parent_title');
        $this->db->from($this->tableName . ' as category');
        $this->db->join($this->tableName . ' as parent', 'parent.id = category.parent_id', 'left');
        //$this->db->where('category.title != ', 'Root');
        //$this->db->where('category.financial_year', $this->financial_year);
        $this->db->where("(category.lft < $lft or category.lft > $rgt)");
        $this->db->order_by('lft', 'parent_id', 'level');
        $query = $this->db->get();
        return $query->result();
    }

    function getAllCategoriesForEditBySystemLevel($lft, $rgt) {
        $this->db->select('category.*');
        $this->db->select('parent.title as parent_title');
        $this->db->select('count(children.id) as childrens');
        $this->db->from($this->tableName . ' as category');
        $this->db->join($this->tableName . ' as parent', 'parent.id = category.parent_id', 'left');
        $this->db->join($this->tableName . ' as children', 'category.id = children.parent_id', 'left');
        //$this->db->where('category.title != ', 'Root');
        //$this->db->where('category.financial_year', $this->financial_year);
        $this->db->group_by('category.id');
        $this->db->where('category.level <= ', $this->level - 2);
        $this->db->where("(category.lft < $lft or category.lft > $rgt)");
        $this->db->order_by('lft', 'parent_id', 'level');
        $query = $this->db->get();
        return $query->result();
    }

    function saveCategory($data){
        $slug = create_unique_slug($data['title'], $this->tableName, $field = 'slug', $key='id', $value=$data['id']);
        $data['slug'] = $slug;
        //$data['financial_year'] = $this->financial_year;

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            unset($data['id']);
            $this->db->update($this->tableName, $data);
            category_rebuild($this->tableName);
        } else {
            $this->db->insert($this->tableName, $data);
            category_rebuild($this->tableName);
            return $this->db->insert_id();
        }
    }

    function delete($id){
        $category = $this->getCategoryDetail($id);
        $this->db->where("lft BETWEEN $category->lft AND $category->rgt")->delete($this->tableName);
        $this->db->where(array('id' => $id, 'deletable' => 1))->delete($this->tableName);
        category_rebuild($this->tableName);
        return true;
    }

}