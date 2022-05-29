<?php


namespace App\Http\Controllers\Admin;


use App\Helper\CustomController;
use App\Models\Penerimaan;

class PenerimaanController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = Penerimaan::with('user.member')->get();
        return view('admin.transaksi.penerimaan.index')->with(['data' => $data]);
    }

    public function get_data_penerimaan()
    {
        try {
            $data = Penerimaan::with('user.member')->get();
            return $this->basicDataTables($data);
        } catch (\Exception $e) {
            return $this->basicDataTables([]);
        }
    }

    public function add_page()
    {
        return view('admin.transaksi.penerimaan.add');
    }

}
