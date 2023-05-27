<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Kegiatan_SKP extends BaseController
{
    public function index()
    {
        return view('admin/kegiatan_skp', ['title' => '[Admin] Kegiatan SKP']);
    }
}
