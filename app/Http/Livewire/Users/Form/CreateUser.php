<?php

namespace App\Http\Livewire\Users\Form;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Component
{
    public ?User $user;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $location;
    public $about;
    public $pool_id;
    public $mitra_id;

    protected $rules = [
        'pool_id' => 'required',
        'mitra_id' => 'required',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->user = User::findOrFail($id);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->phone = $this->user->phone;
            $this->location = $this->user->location;
            $this->about = $this->user->about;
            $this->pool_id = $this->user->pool_id;
            $this->mitra_id = $this->user->mitra_id;
        }
    }

    public function saveOrUpdate()
    {
        DB::beginTransaction();
        if (isset($this->user)) {
            if ($this->user->email == $this->email) {
                // do nothing
            } else {
                Validator::make([
                    'email' => $this->email,
                ], [
                    'email' => 'required|string|email|unique:users|max:255',
                ])->validate();
            }

            if ($this->password) {

                Validator::make([
                    'password' => $this->password,
                ], [
                    'password' => 'required|string|min:6',
                ])->validate();

                $this->user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'location' => $this->location,
                'about' => $this->about,
                'pool_id' => $this->pool_id,
                'mitra_id' => $this->mitra_id,
            ]);

            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Updated' , 'message' => 'User Berhasil Di Ubah!']);
        } else {
            $validatedData = Validator::make([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'pool_id' => $this->pool_id,
                'mitra_id' => $this->mitra_id,
            ], [
                'pool_id' => 'required',
                'mitra_id' => 'required',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ])->validate();

            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone' => $this->phone,
                'location' => $this->location,
                'about' => $this->about,
                'pool_id' => $this->pool_id,
                'mitra_id' => $this->mitra_id,
            ]);

            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Created' , 'message' => 'User Berhasil Dibuat']);
        }

        DB::commit();


        $this->reset(['name', 'email', 'password', 'phone', 'location', 'about', 'pool_id', 'mitra_id']);

        return redirect()->route('user.management');
    }

    public function render()
    {
        return view('livewire.users.form.create-user');
    }
}
