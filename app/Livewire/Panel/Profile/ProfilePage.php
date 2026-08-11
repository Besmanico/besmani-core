<?php

namespace App\Livewire\Panel\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Province;
use App\Models\City;
use App\Models\MainUser;

class ProfilePage extends Component
{
    public $fl_name;
    public $last_name;
    public $ssn;
    public $birthday;
    public $gender;
    public $country_id;
    public $id_province;
    public $id_city;
    public $apt_unit_suite;
    public $postal_code;
    public $address;
    public $website;
    public $mobile_moaref;
    public $moaref_user = null;
    public function mount() 
    {

        if (!Auth::guard('mainUsers')->check()) {
            $this->redirect('/', navigate: true);
            return;
        } 
        $this->country_id = 2;
        $user = Auth::guard('mainUsers')->user();
        $this->fl_name = $user->fl_name ?? '';
        $this->last_name = $user->last_name ?? '';
        $this->ssn = $user->ssn ?? '';
        $this->birthday = $user->birthday ?? ($user->date_of_birth ?? '');
        $this->gender = $user->gender ?? 2;
        // $this->country_id = $user->country_id ?? null;
        $this->id_province = $user->id_province ?? null;
        $this->id_city = $user->id_city ?? null;
        $this->apt_unit_suite = $user->neighbourhood ?? ($user->apt ?? '');
        $this->postal_code = $user->postal_code ?? ($user->zip ?? '');
        $this->website = $user->social_netword ?? ($user->url ?? '');

        $this->address = $user->address ?? '';
        $this->mobile_moaref = $user->mobile_moaref ?? '';

    }

    // new
    public function updatedMobileMoaref($value)
    {
        // وقتی دقیقا 10 رقم شد جستجو کن
        if (strlen($value) == 10) {
            $this->searchMoaref($value);
        } else {
            $this->mobile_moaref = null; // اگر کمتر از 10 رقم بود چیزی نشان نده
        }
    }

    public function searchMoaref($mobile)
    {
        $this->moaref_user = MainUser::where('mobile', $mobile)->first();
    }

    // new end
    public function save()
    {

 
       
       
        $this->validate([
            'fl_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ], [
            'fl_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
        ]);

        $user = Auth::guard('mainUsers')->user();

        $gender = $this->gender !== '' && $this->gender !== null
            ? (int) $this->gender
            : null;
        $countryId = $this->country_id !== '' && $this->country_id !== null
            ? (int) $this->country_id
            : null;
        $provinceId = $this->id_province !== '' && $this->id_province !== null
            ? (int) $this->id_province
            : null;
        $cityId = $this->id_city !== '' && $this->id_city !== null
            ? (int) $this->id_city
            : null;

        $table = (new MainUser)->getTable();
        $data = [
            'fl_name' => (string) ($this->fl_name ?? ''),
            'last_name' => (string) ($this->last_name ?? ''),
        ];
        if (Schema::hasColumn($table, 'ssn')) {
            $data['ssn'] = (string) ($this->ssn ?? '');
        }
        if (Schema::hasColumn($table, 'gender')) {
            $data['gender'] = $gender ?? 2;
        }
        if (Schema::hasColumn($table, 'country_id')) {
            $data['country_id'] = $countryId;
        }
        if (Schema::hasColumn($table, 'id_province')) {
            $data['id_province'] = $provinceId;
        }
        if (Schema::hasColumn($table, 'id_city')) {
            $data['id_city'] = $cityId;
        }
        if (Schema::hasColumn($table, 'postal_code')) {
            $data['postal_code'] = (string) ($this->postal_code ?? '');
        }
        if (Schema::hasColumn($table, 'birthday')) {
            $data['birthday'] = $this->birthday ?: null;
        } elseif (Schema::hasColumn($table, 'date_of_birth')) {
            $data['date_of_birth'] = $this->birthday ?: null;
        }
       
        
        if (Schema::hasColumn($table, 'neighbourhood')) {
            $data['neighbourhood'] = (string) ($this->apt_unit_suite ?? '');
        }

        if (Schema::hasColumn($table, 'social_netword')) {
            $data['social_netword'] = (string) ($this->website ?? '');
        }


        

        if (Schema::hasColumn($table, 'address')) {
            $data['address'] = (string) ($this->address ?? '');
        }

        if (Schema::hasColumn($table, 'mobile_moaref')) {
            $data['mobile_moaref'] = (string) ($this->mobile_moaref ?? '');
        } 

        

        try {
            MainUser::where('id', $user->id)->update($data);
            session()->flash('profile_message', 'Profile updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Profile update failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]); 
            session()->flash('profile_error', 'Failed to update profile. Please try again.');
        }
    }

    public function render()
    {
        $user = auth()->guard('mainUsers')->user();
        $countryCode = function_exists('countryCode') ? countryCode() : collect();
        $provinces = Province::orderBy('name_en')->orderBy('name_fa')->get();
        $cities = City::orderBy('name_en')->orderBy('name_fa')->get();
        $metaData = ['title' => 'Profile'];

        return view('livewire.panel.profile.profile-page', [
            'user' => $user,
            'countryCode' => $countryCode,
            'provinces' => $provinces,
            'cities' => $cities,
        ])->layout('components.layouts.panel', $metaData);
    }
}
