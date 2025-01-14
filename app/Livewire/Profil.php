<?php

namespace App\Livewire;

use Livewire\Component;

class Profil extends Component
{

    public function render()
    {
        return view('livewire.profil',[
            'user' => auth()->user(),
        ])->layout('components.layouts.app', ['title' => 'Profil']);
    }

    public $name;
    public $email;
    public $password;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        $user = auth()->user();
        $user->name = $this->name;

        $emailChanged = $user->email !== $this->email;
        $passwordChanged = !empty($this->password);

        if ($emailChanged || $passwordChanged) {
            $user->email = $this->email;

            if ($passwordChanged) {
            $user->password = bcrypt($this->password);
            $this->dispatch('tambahAlert', [
                'title' => 'Update Password Berhasil',
                'text' => 'Password Berhasil Diupdate',
                'type' => 'success',
                'timeout' => 1000
            ]);
            }

            $user->save();
            auth()->logout();
            return redirect()->route('login');
        }

        $user->save();

        $this->dispatch('tambahAlert', [
            'title' => 'Update Data Berhasil',
            'text' => 'Data Profil Berhasil Diupdate',
            'type' => 'success',
            'timeout' => 1000
        ]);
    }
}