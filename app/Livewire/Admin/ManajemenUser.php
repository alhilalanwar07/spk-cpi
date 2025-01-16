<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenUser extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perpage = 10;
    public $selectedPerPage = 10;

    public $name, $email, $password, $role;
    public $modal = true;


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerpage()
    {
        $this->resetPage();
    }

    public function setPerPage($value)
    {
        $this->perpage = $value;
        $this->resetPage();
    }
    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')->paginate($this->perpage);

        return view('livewire.admin.manajemen-user',[
            'users' => $users
        ])->layout('components.layouts.app', ['title' => 'Manajemen User']);
    }

    public function resetInput(){
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->role = null;

        $this->modal = false;
    }

    public function edit($id){
        $user = User::find($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;

        $this->modal = true;
    }

    public function simpan(){
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required'
        ],[
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role tidak boleh kosong'
        ]);

        try {
            User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => $this->role
            ]);

            $this->dispatch('tambahAlert', [
            'title'     => 'Simpan data berhasil',
            'text'      => 'Data User Berhasil Ditambahkan',
            'type'      => 'success',
            'timeout'   => 1000
            ]);

            $this->reset('name', 'email', 'password', 'role');
        } catch (\Exception $e) {
            $this->dispatch('updateAlertToast', [
            'title'     => 'Simpan data gagal',
            'text'      => 'Terjadi kesalahan saat menyimpan data',
            'type'      => 'error',
            'timeout'   => 1000
            ]);
        }
    }

    public function update(){
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ],[
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'role.required' => 'Role tidak boleh kosong'
        ]);

        try {
            $user = User::where('email', $this->email)->first();
            $updateData = [
                'name' => $this->name,
                'role' => $this->role
            ];

            if (!empty($this->password)) {
                $updateData['password'] = bcrypt($this->password);
            }

            $user->update($updateData);

            $this->dispatch('tambahAlert', [
                'title'     => 'Update data berhasil',
                'text'      => 'Data User Berhasil Diupdate',
                'type'      => 'success',
                'timeout'   => 1000
            ]);

            $this->reset('name', 'email', 'password', 'role');
        } catch (\Exception $e) {
            $this->dispatch('updateAlertToast', [
            'title'     => 'Update data gagal',
            'text'      => 'Terjadi kesalahan saat mengupdate data',
            'type'      => 'error',
            'timeout'   => 1000
            ]);
        }
    }

    public function delete($id){
        try {
            $user = User::find($id);
            $user->delete();

            $this->dispatch('tambahAlert', [
                'title'     => 'Hapus data berhasil',
                'text'      => 'Data User Berhasil Dihapus',
                'type'      => 'success',
                'timeout'   => 1000
            ]);
        } catch (\Exception $e) {
            $this->dispatch('updateAlertToast', [
                'title'     => 'Hapus data gagal',
                'text'      => 'Terjadi kesalahan saat menghapus data',
                'type'      => 'error',
                'timeout'   => 1000
            ]);
        }
    }

}
