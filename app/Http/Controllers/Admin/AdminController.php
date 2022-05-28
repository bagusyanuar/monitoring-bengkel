<?php


namespace App\Http\Controllers\Admin;


use App\Helper\CustomController;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = Admin::with('user')->whereHas('user', function ($query) {
            return $query->where('role', '=', 'admin');
        })->get();
        return view('admin.pengguna.admin.index')->with(['data' => $data]);
    }

    public function add_page()
    {
        return view('admin.pengguna.admin.add');
    }

    public function create()
    {
        try {
            DB::beginTransaction();
            $data = [
                'username' => $this->postField('username'),
                'password' => Hash::make($this->postField('password')),
                'role' => $this->postField('role'),
            ];
            $user = User::create($data);
            Admin::create([
                'nama' => $this->postField('nama'),
                'user_id' => $user->id,
            ]);
            DB::commit();
            return redirect()->back()->with(['success' => 'Berhasil Menambahkan Data...']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['failed' => 'Terjadi Kesalahan ' . $e->getMessage()]);
        }
    }

    public function edit_page($id)
    {
        $data = User::with('admin')->findOrFail($id);
        return view('admin.pengguna.admin.edit')->with(['data' => $data]);
    }

    public function patch()
    {
        try {
            $id = $this->postField('id');
            DB::beginTransaction();
            $user = User::with('admin')->find($id);

            $data = [
                'username' => $this->postField('username'),
                'role' => $this->postField('role'),
            ];

            if ($this->postField('password') !== '') {
                $data['password'] = Hash::make($this->postField('password'));
            }
            $user->update($data);

            $admin = $user->admin;
            $admin->update([
                'nama' => $this->postField('nama')
            ]);
            DB::commit();
            return redirect('/admin')->with(['success' => 'Berhasil Merubah Data...']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['failed' => 'Terjadi Kesalahan' . $e->getMessage()]);
        }
    }

    public function destroy()
    {
        try {
            $id = $this->postField('id');
            DB::beginTransaction();
            Admin::with('user')->where('user_id', '=', $id)->delete();
            User::destroy($id);
            DB::commit();
            return $this->jsonResponse('success', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse('failed', 500);
        }
    }
}
