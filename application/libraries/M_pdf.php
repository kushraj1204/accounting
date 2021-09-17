<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . '/third_party/mpdf/mpdf.php';

class M_pdf
{
    public $pdf;

    public function __construct($c = array())
    {
        $d = array(
            'mode' => 'c',
            'format' => 'A4',
            'default_font_size' => 0,
            'default_font' => '',
            'mgl' => 15,
            'mgr' => 15,
            'mgt' => 16,
            'mgb' => 16,
            'mgh' => 9,
            'mgf' => 9,
            'orientation' => 'P'
        );
        $p = array_merge($d, $c);
        $this->pdf = new mPDF($p['mode'], $p['format'], $p['default_font_size'], $p['default_font'], $p['mgl'], $p['mgr'], $p['mgt'], $p['mgb'], $p['mgh'], $p['mgf'], $p['orientation']);
    }

}
