<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->datechooser = $this->setting_model->getDatechooser();
        $this->load->library('bikram_sambat');
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title'] = 'Member';
        $data['title_list'] = 'Members';
        $memberList = $this->librarymember_model->get();
        $data['memberList'] = $memberList;
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/index', $data);
        $this->load->view('layout/footer');
    }

    public function issue($id) {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title'] = 'Member';
        $data['title_list'] = 'Members';
        $memberList = $this->librarymember_model->getByMemberID($id);
        $data['memberList'] = $memberList;
        $issued_books = $this->bookissue_model->getMemberBooks($id);
        $data['issued_books'] = $issued_books;
        $bookList = $this->book_model->get();
        $data['bookList'] = $bookList;
        $this->form_validation->set_rules('book_id', 'lang:book', 'trim|required|xss_clean');
        if($this->datechooser == 'bs') {
            $this->form_validation->set_rules('return_date_bs', 'lang:return_date', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('return_date', 'lang:return_date', 'trim|required|xss_clean');
        }
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $member_id = $this->input->post('member_id');
            $today = $this->customlib->getCurrentTime('Y-m-d');
            $data = array(
                'book_id' => $this->input->post('book_id'),
                'return_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('return_date'))),
                'return_date_bs' => $this->input->post('return_date_bs'),
                'issue_date_bs' => $this->customlib->getBSDate($today),
                'issue_date' => $today,
                'member_id' => $this->input->post('member_id'),
            );
            $this->bookissue_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
            redirect('admin/member/issue/' . $member_id);
        }

        $this->load->view('layout/header');
        if($this->datechooser == 'bs') {
            $this->load->view('admin/librarian/issue_bs', $data);
        } else {
            $this->load->view('admin/librarian/issue', $data);
        }
        $this->load->view('layout/footer');
    }

    public function bookreturn()
    {
        if ($this->input->method(true) == 'POST') {
            $this->form_validation->set_rules('returned_date', 'lang:returned_date', 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'returned_date' => form_error('returned_date'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $data = array(
                    'id' => $this->input->post('book_issue_id'),
                    'returned_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('returned_date'))),
                    'returned_date_bs' => $this->input->post('returned_date_bs'),
                    'is_returned' => 1
                );
                $this->bookissue_model->update($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'));
                echo json_encode($array);
            }
        } else {
            $array = array('status' => 'fail', 'error' => 'Invalid request');
            echo json_encode($array);
        }
    }

    function student() {

        if (!$this->rbac->hasPrivilege('add_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/student');
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchLibraryStudent($class, $section);
                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text);
                    $data['resultlist'] = $resultlist;
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function add() {
        if ($this->input->post('library_card_no') != "") {

            $this->form_validation->set_rules('library_card_no', 'lang:library_card_no', 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student = $this->input->post('member_id');
                $data = array(
                    'member_type' => 'student',
                    'member_id' => $student,
                    'library_card_no' => $library_card_no
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student = $this->input->post('member_id');
            $data = array(
                'member_type' => 'student',
                'member_id' => $student,
                'library_card_no' => $library_card_no
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    function check_cardno_exists() {
        $data['library_card_no'] = $this->security->xss_clean($this->input->post('library_card_no'));

        if ($this->librarymanagement_model->check_data_exists($data)) {
            $this->form_validation->set_message('check_cardno_exists', $this->lang->line('Record already exists'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function teacher() {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/teacher');
        $data['title'] = 'Add Teacher';
        $teacher_result = $this->teacher_model->getLibraryTeacher();
        $data['teacherlist'] = $teacher_result;

        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/member/teacher', $data);
        $this->load->view('layout/footer', $data);
    }

    function addteacher() {
        if ($this->input->post('library_card_no') != "") {

            $this->form_validation->set_rules('library_card_no', 'lang:library_card_no', 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student = $this->input->post('member_id');
                $data = array(
                    'member_type' => 'teacher',
                    'member_id' => $student,
                    'library_card_no' => $library_card_no
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student = $this->input->post('member_id');
            $data = array(
                'member_type' => 'teacher',
                'member_id' => $student,
                'library_card_no' => $library_card_no
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    public function surrender() {

        $this->form_validation->set_rules('member_id', 'lang:book', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $member_id = $this->input->post('member_id');
            $this->librarymember_model->surrender($member_id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('saved_successfully'));
            echo json_encode($array);
        }
    }

}

?>