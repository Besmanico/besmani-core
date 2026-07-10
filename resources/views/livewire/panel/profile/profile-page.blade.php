<div class="profile-page-form section-contact-us">
    <style>
        /* استایل اینپوت‌ها شبیه صفحه تماس با ما */
        .profile-page-form input{
            background-color: #deeaf7 !important;
            font-weight: bold;
            
        }
        .profile-page-form select{
            background-color: #deeaf7 !important;
            font-weight: bold;
            
        }
        .profile-page-form textarea{
            background-color: #deeaf7 !important;
            font-weight: bold;
            
        }
        .profile-page-form input.form-control,
        .profile-page-form select.form-control,
        .profile-page-form textarea.form-control {
            border-radius: 10px;
            height: 50px;
            padding: 15px 20px;
            border: 1px solid #ccc;
            background-color: #f5f5f8;

        }

        .profile-page-form textarea.form-control {
            height: 50px;
            min-height: 50px;
        }

        .profile-page-form .form-group label,
        .profile-page-form .row>[class*="col-"]>label {
            margin: 0 0 10px 20px;
            color: #26292c;
            font-size: 16px;
            font-weight: bold;
        }

        .profile-page-form .row>[class*="col-"]:nth-child(n+3) {
            margin-top: 25px;
        }

        .profile-page-form input.form-control:hover,
        .profile-page-form input.form-control:focus,
        .profile-page-form select.form-control:hover,
        .profile-page-form select.form-control:focus,
        .profile-page-form textarea.form-control:hover,
        .profile-page-form textarea.form-control:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #444 !important;
            background-color: #f5f5f8 !important;
        }
        .profile-page-form .profile-gender-radios .radio-inline {
            display: inline-block;
            margin-right: 1rem;
            /* color: #16a34a; */
            font-weight: 500;
        }
        .profile-page-form .profile-gender-radios input[type="radio"] {
            position: static;
            visibility: visible;
            accent-color: #16a34a;
            /* margin-right: 6px; */
        }  
    </style>
    @if ($user)
        <main class="panel-main"> 
            @livewire('panel.header')
            <div class="container"> 
                {{-- <h2 class="mb-4">Profile</h2> --}}
                <form wire:submit.prevent="save" class="position-relative">
                    <div wire:loading wire:target="save" class="d-flex position-absolute align-items-center justify-content-center rounded" style="top:0;left:0;right:0;bottom:0;background:rgba(255,255,255,0.85);z-index:20;">
                        <div class="text-center">
                            <i class="fa fa-spinner fa-spin fa-3x text-success mb-2"></i>
                            <p class="mb-0 font-weight-bold">Saving profile...</p>
                        </div>
                    </div>
                    @if (session()->has('profile_message'))
                        <div class="alert alert-success alert-dismissible mb-3" role="alert">
                            {{ session('profile_message') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif
                    @if (session()->has('profile_error'))
                        <div class="alert alert-danger alert-dismissible mb-3" role="alert">
                            {{ session('profile_error') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0 list-unstyled">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul> 
                        </div>
                    @endif 
                    <div class="row">
                        <div class=" col-sm-4 mt-2">
                            <div class="form-group">
                                <label><i class="fa fa-user"></i> First Name : <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.defer="fl_name">
                            </div>
                        </div>
                        <div class=" col-sm-4 mt-2">
                            <div class="form-group">
                                <label><i class="fa fa-user"></i> Last Name : <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.defer="last_name">
                            </div> 
                        </div>
                        <div class=" col-sm-4  " style="margin-top: 4px;">
                            <label style="margin-bottom: 0;"><i class="fa fa-phone"></i> Phone Number : <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control " style="max-width: 140px;margin-top: 10px;">
                                    @foreach ($countryCode as $country)
                                        <option value="{{ $country->id }}"
                                            {{ optional($user)->country_id == $country->id ? 'selected' : '' }}>
                                            {{ $country->code }} {{ $country->name_en }}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control" value="{{ $user->mobile ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class=" col-sm-4 mt-2">
                            <div class="form-group">
                                <label><i class="fa fa-envelope"></i> Email : <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" value="{{ $user->email ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class=" col-sm-2 mt-2">
                            <div class="form-group">
                                <label><i class="fa fa-id-card"></i> SSN :</label>
                                <input type="text" class="form-control" placeholder="SSN" wire:model.defer="ssn">
                            </div>
                        </div>
                        <div class=" col-sm-2 mt-2">
                            <div class="form-group">
                                <label><i class="fa fa-calendar"></i> Date of Birth :</label>
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy" wire:model.defer="birthday">
                            </div>
                        </div>
                        <div class=" col-sm-4 mt-2">
                            <label><i class="fa fa-venus-mars"></i> Gender : <span class="text-danger">*</span></label>
                            <div class="form-control profile-gender-radios" style="padding: 8px 12px;">
                                <label class="radio-inline mr-3">
                                    <input type="radio" wire:model.defer="gender" value="1"> Woman</label>
                                <label class="radio-inline mr-3">
                                    <input type="radio" wire:model.defer="gender" value="2"> Man</label>
                                <label class="radio-inline">
                                    <input type="radio" wire:model.defer="gender" value="3"> Other</label>
                            </div>
                        </div>
                        <div class=" col-sm-3 mt-2">
                            <label><i class="fa fa-globe"></i> Country : <span class="text-danger">*</span></label>
                            <select class="form-control" id="profile-country" name="country_id" wire:model.defer="country_id">
                                <option value="">—</option>
                                @foreach ($countryCode as $country)
                                    <option value="{{ $country->id }}"
                                        {{ optional($user)->country_id == $country->id ? 'selected' : '' }}>
                                        {{ $country->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class=" col-sm-3 mt-2">
                            <label><i class="fa fa-map-marker"></i> State/Province : <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="profile-state-province" name="id_province"
                                data-selected="{{ optional($user)->id_province ?? '' }}" wire:model.defer="id_province">
                                <option value="">—</option> 
                                @php
                                    $provincesByCountry = isset($provinces) ? $provinces->groupBy('phone_country_id') : collect();
                                    $selectedCountryId = optional($user)->country_id;
                                    $selectedProvinceId = optional($user)->id_province ?? null;
                                @endphp
                                @if ($selectedCountryId && $provincesByCountry->has($selectedCountryId))
                                    @foreach ($provincesByCountry->get($selectedCountryId) as $prov)
                                        <option value="{{ $prov->id }}" {{ $selectedProvinceId == $prov->id ? 'selected' : '' }}>{{ $prov->name_en ?? $prov->name_fa }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class=" col-sm-3 mt-2">
                            <label><i class="fa fa-building"></i> City : <span class="text-danger">*</span></label>
                            <select class="form-control" id="profile-city" name="id_city"
                                data-selected="{{ optional($user)->id_city ?? '' }}" wire:model.defer="id_city">
                                <option value="">—</option>
                                @php
                                    $citiesByProvince = isset($cities) ? $cities->groupBy('province_id') : collect();
                                    $selectedCityId = optional($user)->id_city ?? null;
                                @endphp
                                @if ($selectedProvinceId && $citiesByProvince->has($selectedProvinceId))
                                    @foreach ($citiesByProvince->get($selectedProvinceId) as $city)
                                        <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>{{ $city->name_en ?? $city->name_fa }}</option>
                                    @endforeach
                                @endif  
                            </select> 
                        </div>
                       
                        <div class=" col-sm-3 mt-2">
                            <label><i class="fa fa-envelope-o"></i> Postal Code : <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="postal_code">
                        </div>
                        
                        <div class=" col-sm-6 mt-2">
                            <label><i class="fa fa-map-marker"></i> Street Address : <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" rows="1" cols="1" wire:model.defer="address" style="height: 50px;" >{{ $user->street_address ?? ($user->address ?? '') }}</textarea>
                        </div> 
                        
                        <div class=" col-sm-3 mt-2">
                            <label><i class="fa fa-home"></i> Apt/Unit/Suite :</label>
                            <input type="text" class="form-control" wire:model.defer="apt_unit_suite">
                        </div>
                        <div class=" col-sm-3 mt-2">
                            <label><i class="fa fa-link"></i> Website / URL :</label>
                            <input type="url" class="form-control" wire:model.defer="website">
                        </div>
                       
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3"><strong>Reference</strong></h5>
                    <div class="row">
                        <div class=" col-md-6">
                            <label><i class="fa fa-phone-square"></i> Phone Number : <span
                                    class="text-danger">*</span> <small>(Without Country Code)</small></label>
                            {{-- <input type="text" class="form-control" wire:model.defer="mobile_moaref"
                             value="{{ $user->mobile_moaref ?? '' }}"     --}}
                             <input type="text" class="form-control"  wire:model="mobile_moaref"
                             maxlength="10"
                             inputmode="numeric"
                             value="{{ $user->mobile_moaref ?? '' }}" >
                        </div>
                        <div class=" col-md-6">
                            <label><i class="fa fa-user-plus"></i> Name :</label>
                            <input type="text" class="form-control" value="{{ $user->fl_moaref ?? '' }}"
                                readonly>
                        </div>


                        {{-- new --}}
                        {{-- نمایش اطلاعات کاربر پیدا شده --}}
@if($moaref_user)
<div class="alert alert-info mt-2">
    کاربر معرف: {{ $moaref_user->name }} ({{ $moaref_user->mobile }})
</div>
@endif
                        {{-- new end--}}



                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn-action-btn" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Save </span>
                            <span wire:loading wire:target="save">
                                <i class="fa fa-spinner fa-spin"></i> Saving...
                            </span>
                        </button>
                    </div>
                </form> 
            </div>
        </main>
        @if (isset($provinces) && isset($cities))
        <script>
        (function() {
            var provinces = @json($provinces->map(function($p) { return ['id' => $p->id, 'phone_country_id' => $p->phone_country_id, 'name' => $p->name_en ?? $p->name_fa]; })->values());
            var cities = @json($cities->map(function($c) { return ['id' => $c->id, 'province_id' => $c->province_id, 'name' => $c->name_en ?? $c->name_fa]; })->values());
            var provincesByCountry = {};
            provinces.forEach(function(p) {
                var key = p.phone_country_id;
                if (!provincesByCountry[key]) provincesByCountry[key] = [];
                provincesByCountry[key].push(p);
                if (key !== String(key)) {
                    provincesByCountry[String(key)] = provincesByCountry[key];
                }
            });
            var citiesByProvince = {};
            cities.forEach(function(c) {
                var key = c.province_id;
                if (!citiesByProvince[key]) citiesByProvince[key] = [];
                citiesByProvince[key].push(c);
                if (key !== String(key)) {
                    citiesByProvince[String(key)] = citiesByProvince[key];
                }
            });
            function runCascade() {
                var countryEl = document.getElementById('profile-country');
                var stateEl = document.getElementById('profile-state-province');
                var cityEl = document.getElementById('profile-city');
                if (!countryEl || !stateEl || !cityEl) return;
                function setOptions(select, options, keepFirst) {
                    var first = keepFirst && select.options.length ? select.options[0] : null;
                    select.innerHTML = '';
                    if (first) select.appendChild(first);
                    (options || []).forEach(function(opt) {
                        var o = document.createElement('option');
                        o.value = opt.id;
                        o.textContent = opt.name || '';
                        select.appendChild(o);
                    });
                }
                function fillStateByCountry() {
                    var raw = countryEl.value;
                    var cid = raw ? (parseInt(raw, 10) || raw) : null;
                    var list = cid ? (provincesByCountry[cid] || provincesByCountry[String(cid)] || []) : null;
                    setOptions(stateEl, list, true);
                }
                function fillCityByState() {
                    var raw = stateEl.value;
                    var pid = raw ? (parseInt(raw, 10) || raw) : null;
                    var list = pid ? (citiesByProvince[pid] || citiesByProvince[String(pid)] || []) : null;
                    setOptions(cityEl, list, true);
                }
                if (!countryEl._cascadeBound) {
                    countryEl._cascadeBound = true;
                    countryEl.addEventListener('change', function() {
                        fillStateByCountry();
                        setOptions(cityEl, null, true);
                    });
                    stateEl.addEventListener('change', function() { 
                        fillCityByState();
                    });
                }
                fillStateByCountry();
                var savedProvince = (stateEl.getAttribute('data-selected') || '').toString().trim();
                if (savedProvince) stateEl.value = savedProvince;
                fillCityByState();
                var savedCity = (cityEl.getAttribute('data-selected') || '').toString().trim();
                if (savedCity) cityEl.value = savedCity;
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(runCascade, 0);
                });
            } else {
                setTimeout(runCascade, 0);
            }
            document.addEventListener('livewire:navigated', runCascade);
        })();
        </script>
        @endif
    @else
        <main class="panel-main">
            @livewire('panel.header')
            <div class="container">
                <p>Please sign in to view your profile.</p>
            </div>
        </main>
    @endif
</div>
