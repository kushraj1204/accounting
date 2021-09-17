<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('category_rebuild')) {

    function category_rebuild($tableName, $parent_id = 0, $left = 0, $level = 0)
    {
        $CI = &get_instance();
        $CI->load->database();
        $db = $CI->db;
        $db->select('id')
            ->from($tableName)
            ->where('parent_id', (int)$parent_id)
            ->order_by('parent_id', 'title');
        $query = $db->get();
        $children = $query->result();

        // The right value of this node is the left value + 1
        $right = $left + 1;

        // Execute this function recursively over all children
        if (count($children) > 0) {
            foreach ($children as $item) {
                // $right is the current right value, which is incremented on recursion return
                $right = category_rebuild($tableName, $item->id, $right, $level + 1);

                // If there is an update failure, return false to break out of the recursion
                if ($right === false) {
                    return false;
                }
            }
        }

        // We've got the left value, and now that we've processed
        // the children of this node we also know the right value
        $data = array(
            'lft' => (int)$left,
            'rgt' => (int)$right,
            'level' => (int)$level,
        );
        $db->where('id', (int)$parent_id);
        $db->update($tableName, $data);

        // Return the right value of this node + 1
        return $right + 1;
    }
}

function create_unique_slug($string, $table, $field = 'slug', $key = NULL, $value = NULL)
{
    $CI =& get_instance();
    $slug = url_title($string);
    $slug = strtolower($slug);
    $i = 0;
    $params = array();
    $params[$field] = $slug;

    if ($key) $params["$key !="] = $value;

    while ($CI->db->where($params)->get($table)->num_rows()) {
        if (!preg_match('/-{1}[0-9]+$/', $slug))
            $slug .= '-' . ++$i;
        else
            $slug = preg_replace('/[0-9]+$/', ++$i, $slug);

        $params [$field] = $slug;
    }
    return $slug;
}

function echopreexit($param)
{
    echo "<pre>";
    print_r($param);
    exit;
}

function last_query()
{
    $CI =& get_instance();
    echo $CI->db->last_query();
    exit;
}

function show_message()
{
    $CI = &get_instance();
    //$CI->load->session();
    if ($CI->session->flashdata('msg')) {
        echo '<div class="alert alert-' . $CI->session->flashdata('msg')['type'] . ' alert-dismissible fade in">' .
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' .
            $CI->session->flashdata('msg')['message'] .
            '</div>';
    }
}

function buildAccordionCategory($elements, $parentId = 0, $selected_lft)
{
    $CI = &get_instance();
    $level = $CI->accountlib->getAccountSetting()->level;
    $style = $parentId > 0 ? 'style="margin-left: 20px;"' : '';
    $html = '<div id="accordion-' . $parentId . '" '. $style . ' role="tablist" aria-multiselectable="true">';

    foreach ($elements as $element) {
        $class = '';
        $checked = '';
        if($element->lft < $selected_lft && $element->rgt > $selected_lft){
            $class = 'in';
        }elseif($element->lft == $selected_lft){
            $checked = 'checked="checked"';
        }
        if ($element->parent_id == $parentId) {
            //$html .= '<div class="card">';
            if ($element->childrens > 0 && $element->level < ($level - 2)) {
                $html .= '<div class="panel" data-parent="' . $element->parent_id . '">';
                $html .= '
                    <label>
                        <input type="radio" name="parent_id" ' . $checked . ' id="parent-' . $element->id . '" value="' . $element->id . '" class="toggle-accordion">
                        <span role="tab" id="heading-' . $element->id . '" class="tab">
                            <span class="check-category" role="button" data-toggle="collapse" data-parent="#accordion-' . $element->parent_id . '" href="#collapse-' . $element->id . '" aria-controls="collapse-' . $element->id . '">
                                ' . $element->title . '
                            </span>
                        </span>
                    </label>
                    <div id="collapse-' . $element->id . '" class="panel-collapse collapse ' . $class . '" role="tabpanel" aria-labelledby="heading-' . $element->id . '">';
                $html .= buildAccordionCategory($elements, $element->id, $selected_lft);
                $html .= '</div>';
                $html .= '</div>';
            } else {
                $html .= '<div class="panel">';
                $html .= '<label for="parent-' . $element->id . '">
                    <input type="radio" name="parent_id" ' . $checked . ' id="parent-' . $element->id . '" value="' . $element->id . '"  ' . $element->lft . '  ' . $selected_lft . '>' .  $element->title . '
                </label>';
                $html .= '</div>';
            }
            //$html .= '</div>';
        }
        /*else{
            $html .= '</div></div></div>';
        }*/
    }
    $html .= '</div>';
    return $html;
}

?>