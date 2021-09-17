<?php

class Staffidcard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->model('staffidcard_model');
        $this->data = array(
            'upload_path' => 'uploads/staff_id_card/',
        );
        $this->data['cards'] = $this->staffidcard_model->getAll();
        $this->data['datechooser'] = $this->setting_model->getDatechooser();
    }

    public function index($id = "")
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->data['card'] = $this->staffidcard_model->find($id);
            $this->data['form_title'] = 'Edit Staff ID Card';
        } else {
            $this->data['card'] = array();
            $this->data['form_title'] = 'Add Staff ID Card';
        }
        $this->_handleSubmit();
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffidcard');
        $this->load->view('layout/header');
        $this->load->view('admin/staff_id_card/index', array('data' => $this->data));
        $this->load->view('layout/footer');
    }

    private function _handleSubmit()
    {
        if ($this->input->method(true) === 'POST') {
            if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
                access_denied();
            }
            $this->form_validation->set_rules('school_name', 'lang:school_name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('address', 'lang:address_phone_email', 'trim|required|xss_clean');
            $this->form_validation->set_rules('title', 'lang:title', 'trim|required|xss_clean');
            if ($this->form_validation->run() !== FALSE) {
                $employee_id = 0;
                $department = 0;
                $designation = 0;
                $email = 0;
                $date_of_joining = 0;
                $permanent_address = 0;
                $contact_no = 0;
                $dob = 0;

                if ($this->input->post('is_active_department') == 1) {
                    $department = $this->input->post('is_active_department');
                }
                if ($this->input->post('is_active_employee_id') == 1) {
                    $employee_id = $this->input->post('is_active_employee_id');
                }
                if ($this->input->post('is_active_designation') == 1) {
                    $designation = $this->input->post('is_active_designation');
                }
                if ($this->input->post('is_active_email') == 1) {
                    $email = $this->input->post('is_active_email');
                }
                if ($this->input->post('is_active_date_of_joining') == 1) {
                    $date_of_joining = $this->input->post('is_active_date_of_joining');
                }
                if ($this->input->post('is_active_permanent_address') == 1) {
                    $permanent_address = $this->input->post('is_active_permanent_address');
                }
                if ($this->input->post('is_active_contact_no') == 1) {
                    $contact_no = $this->input->post('is_active_contact_no');
                }
                if ($this->input->post('is_active_dob') == 1) {
                    $dob = $this->input->post('is_active_dob');
                }
                $data = array(
                    'title' => $this->input->post('title'),
                    'school_name' => $this->input->post('school_name'),
                    'school_address' => $this->input->post('address'),
                    'header_color' => $this->input->post('header_color'),
                    'enable_department' => $department,
                    'enable_employee_id' => $employee_id,
                    'enable_designation' => $designation,
                    'enable_email' => $email,
                    'enable_date_of_joining' => $date_of_joining,
                    'enable_permanent_address' => $permanent_address,
                    'enable_contact_no' => $contact_no,
                    'enable_dob' => $dob,
                    'status' => 1,
                );
                if (!empty($this->data['card']) && isset($this->data['card']->id)) {
                    $data['id'] = $this->data['card']->id;
                }
                $id = $this->staffidcard_model->save($data);
                if ($id) {
                    if (!empty($_FILES['logo']['name'])) {
                        $config['overwrite'] = true;
                        $config['upload_path'] = $this->data['upload_path'];
                        $config['allowed_types'] = 'jpg|jpeg|png|gif';
                        $config['file_name'] = "logo" . $id;
                        //Load upload library and initialize configuration
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('logo')) {
                            $uploadData = $this->upload->data();
                            $logo = $uploadData['file_name'];
                        } else {
                            $logo = $this->input->post('old_logo');
                        }
                    } else {
                        $logo = $this->input->post('old_logo');
                    }

                    if (!empty($_FILES['principal_sign']['name'])) {
                        $config['overwrite'] = true;
                        $config['upload_path'] = $this->data['upload_path'];
                        $config['allowed_types'] = 'jpg|jpeg|png|gif';
                        $config['file_name'] = "principal_sign" . $id;
                        //Load upload library and initialize configuration
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('principal_sign')) {
                            $uploadData = $this->upload->data();
                            $principal_sign = $uploadData['file_name'];
                        } else {
                            $principal_sign = $this->input->post('old_principal_sign');
                        }
                    } else {
                        $principal_sign = $this->input->post('old_principal_sign');
                    }

                    $upload_data = array('id' => $id, 'logo' => $logo, 'principal_sign' => $principal_sign);
                    $this->staffidcard_model->save($upload_data);
                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('saved_successfully').'</div>');
                    redirect('admin/staffidcard');
                } else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-error text-left">Error occurred</div>');
                }
            }
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }
        $this->staffidcard_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">'.$this->lang->line('deleted_successfully').'</div>');
        redirect('admin/staffidcard/index');
    }

    public function generate()
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffidcard/generate');
        $this->data['roles'] = $this->staff_model->getStaffRole();
        if ($this->input->method(true) === 'POST') {
            $this->form_validation->set_rules('card_id', 'lang:card', 'trim|required|xss_clean');
            if ($this->form_validation->run() !== FALSE) {
                $role_id = $this->input->post('role_id');
                $card_id = $this->input->post('card_id');
                $this->data['card'] = $this->staffidcard_model->find($card_id);
                $this->data['role_id'] = $role_id;
                if ($role_id) {
                    $this->data['staffs'] = $this->staff_model->getEmployee($role_id, 1);
                } else {
                    $this->data['staffs'] = $this->staff_model->searchFullText("", 1);
                }
            }
        }
        $this->load->view('layout/header');
        $this->load->view('admin/staff_id_card/generate', $this->data);
        $this->load->view('layout/footer');
    }

    public function generatepdf()
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_view') || $this->input->method(true) !== 'POST') {
            access_denied();
        }
        $std_arr = $this->input->post('data[]');
        $card_id = $this->input->post('card_id');
        $role_id = $this->input->post('role_id');
        $this->data['sch_setting'] = $this->setting_model->get();
        $this->data['card'] = $this->staffidcard_model->find($card_id);
        $this->data['staffs'] = $this->staff_model->getByIdArray($std_arr, $role_id);
        $view = 'admin/staff_id_card/pdf';
        //$this->load->view($view, $this->data);
        $id_cards = $this->load->view($view, $this->data, true);
        $file_name = 'staff_id_cards-' . $this->data['card']->title . '-' . date('Y-m-d');
        $file_name = preg_replace('/\s+/', '_', $file_name);
        $pdfFilePath = $file_name . ".pdf";
        $this->load->library('m_pdf', array(
            'mode' => 'utf-8',
            'format' => 'A4',
            'mgl' => 0,
            'mgr' => 0,
            'mgt' => 0,
            'mgb' => 0,
            'mgh' => 0,
            'mgf' => 0,
        ));
        //$this->m_pdf->pdf->showImageErrors = true;
        $this->m_pdf->pdf->shrink_tables_to_fit = 1;
        $this->m_pdf->pdf->WriteHTML($id_cards);
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }
}