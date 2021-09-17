<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Zoom extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setting = $this->setting_model->getSetting();
        $this->zoomSetting = json_decode($this->setting->zoom, true);
        if (!$this->zoomSetting) {
            $this->zoomSetting = array(
                'api_key' => '',
                'api_secret' => '',
            );
        }
        $this->currentUser = $this->customlib->getLoggedInUserData();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'eLearning');
        $this->session->set_userdata('sub_menu', 'admin/zoom');
        $data = array(
            'setup' => 1,
        );
        if (empty($this->zoomSetting['api_key']) || empty($this->zoomSetting['api_secret'])) {
            $data['setup'] = 0;
        }
        $this->load->view("layout/header", $data);
        $this->load->view('admin/zoom/index');
        $this->load->view("layout/footer", $data);
    }

    public function setting()
    {
        if (!$this->rbac->hasPrivilege('general_setting', 'can_edit')) {
            access_denied();
        }
        if ($this->input->method(true) === 'POST') {
            $this->form_validation->set_rules('api_key', $this->lang->line("API Key"), 'trim|required|xss_clean');
            $this->form_validation->set_rules('api_secret', $this->lang->line("API Secret"), 'trim|required|xss_clean');
            if ($this->form_validation->run() !== FALSE) {
                $data = array(
                    'id' => $this->setting->id,
                    'zoom' => json_encode(
                        array(
                            'api_key' => $this->input->post('api_key'),
                            'api_secret' => $this->input->post('api_secret'),
                        )
                    )
                );
                $this->setting_model->add($data);
                redirect('admin/zoom/setting');
            }
        }
        $data = array(
            'zoomSetting' => $this->zoomSetting
        );
        $this->session->set_userdata('top_menu', 'eLearning');
        $this->session->set_userdata('sub_menu', 'admin/zoom/setting');
        $this->load->view("layout/header", $data);
        $this->load->view('admin/zoom/setting');
        $this->load->view("layout/footer", $data);
    }

    public function meeting()
    {
        if ($this->input->method(true) !== 'POST') {
            die("Error");
        }
        $api_key = $this->zoomSetting['api_key'];
        $api_secret = $this->zoomSetting['api_secret'];
        $meeting_number = $this->input->post('meeting_number');
        $meeting_number = str_replace(' ', '', $meeting_number);
        $meeting_password = $this->input->post('meeting_password');
        $username = $this->input->post('username');
        $role = 1;
        $sig = $this->_generate_signature($api_key, $api_secret, $meeting_number, $role);
        $data = array(
            'zoom_version' => '1.7.10',
            'api_key' => $api_key,
            'meeting_number' => $meeting_number,
            'username' => $username,
            'email' => '',
            'role' => $role,
            'password' => $meeting_password,
            'signature' => $sig,
        );
        $this->load->view('admin/zoom/meeting', $data);
    }

    private function _generate_signature($api_key, $api_secret, $meeting_number, $role)
    {

        $time = time() * 1000 - 30000;//time in milliseconds (or close enough)
        $data = base64_encode($api_key . $meeting_number . $time . $role);
        $hash = hash_hmac('sha256', $data, $api_secret, true);
        $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);
        //return signature, url safe base64 encoded
        return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
    }

    public function meetingend()
    {
        $this->load->view('admin/zoom/end');
    }

    public function host($id = "")
    {
        $this->load->model('zoom_meeting_model');
        $data = array();
        $data['is_super_admin'] = $this->rbac->isSuperAdmin();
        if ($data['is_super_admin']) {
            $meetings = $this->zoom_meeting_model->getAll();
        } else {
            $meetings = $this->zoom_meeting_model->getAll(array('user_id' => $this->currentUser['id']));
        }
        $data['meetings'] = $meetings;
        if (!empty($id)) {
            if ($data['is_super_admin']) {
                $data['meeting'] = $this->zoom_meeting_model->findBy(array('id' => $id));
            } else {
                $data['meeting'] = $this->zoom_meeting_model->findBy(array('id' => $id, 'user_id' => $this->currentUser['id']));
            }
            $data['form_title'] = 'Edit Meeting';
        } else {
            $data['meeting'] = array();
            $data['form_title'] = 'Add Meeting';
        }
        $data['classes'] = $this->class_model->get();
        $data['teachers'] = $this->staff_model->getStaffbyrole(2);
        $this->session->set_userdata('top_menu', 'eLearning');
        $this->session->set_userdata('sub_menu', 'admin/zoom/host');
        $this->_handleMeetingSubmit($data);
        $this->load->view("layout/header", $data);
        $this->load->view('admin/zoom/host');
        $this->load->view("layout/footer", $data);
    }

    private function _handleMeetingSubmit($data)
    {
        if ($this->input->method(true) === 'POST') {
            $this->form_validation->set_rules('class_id', 'lang:class', 'trim|required|xss_clean');
            $this->form_validation->set_rules('topic', 'lang:Topic', 'trim|required|xss_clean');
            $this->form_validation->set_rules('meeting_number', $this->lang->line("Meeting Number"), 'trim|required|xss_clean');
            $this->form_validation->set_rules('starts_on', 'lang:start_time', 'trim|required|valid_datetime');
            $this->form_validation->set_rules('meeting_password', $this->lang->line('Meeting Password'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('meeting_url', $this->lang->line('Meeting Url'), 'trim|required|xss_clean');
            if ($data['is_super_admin']) {
                $this->form_validation->set_rules('user_id', 'lang:teacher', 'trim|required|xss_clean');
            }
            if ($this->form_validation->run() !== FALSE) {
                if ($data['is_super_admin']) {
                    $user_id = $this->input->post('user_id');
                } else {
                    $user_id = $this->currentUser['id'];
                }
                $form_data = array(
                    'topic' => $this->input->post('topic'),
                    'meeting_number' => $this->input->post('meeting_number'),
                    'meeting_password' => $this->input->post('meeting_password'),
                    'meeting_url' => $this->input->post('meeting_url'),
                    'class_id' => $this->input->post('class_id'),
                    'section_id' => $this->input->post('section_id'),
                    'created_date' => $this->customlib->getCurrentTime(),
                    'user_id' => $user_id,
                    'starts_on' => $this->input->post('starts_on'),
                );
                if (!empty($data['meeting']) && isset($data['meeting']->id)) {
                    $form_data['id'] = $data['meeting']->id;
                }
                $id = $this->zoom_meeting_model->save($form_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('saved_successfully') . '</div>');
                redirect('admin/zoom/host');
            }
        }
    }

    public function delete($id)
    {
        $this->load->model('zoom_meeting_model');
        $condition = array('id' => $id);
        if (!$this->rbac->isSuperAdmin()) {
            $condition['user_id'] = $this->currentUser['id'];
        }
        $this->zoom_meeting_model->deleteBy($condition);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('deleted_successfully') . '</div>');
        redirect('admin/zoom/host');
    }
}