<?php

use App\Models\Cart;
use App\Models\Order;
use App\Models\Clinic;
use App\Models\Slider;
use App\Models\Service;
use App\Models\Activity;
use App\Models\MainUser;
use App\Models\Agreement;
use App\Models\OrderItem;
use App\Models\ManService;
use App\Models\InfoActivity;
use App\Models\PhoneCountry;
use App\Models\WomenService;
use App\Models\ClinicReserve;
use App\Models\manSalonReserve;
use App\Models\ProdcutCustomer;
use App\Models\MenAcademyService;
use App\Models\WomanSalonReserve;
use App\Models\PackageServiceItem;
use App\Models\WomenAcademyService;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseRegistrationMan;
use App\Models\CourseRegistrationWoman;

function countryCode()
{
    $countryCode = PhoneCountry::where('status', 1)
        ->orderBy('name_en', 'asc')
        ->get();
    return $countryCode;
}

function rand_Code($length)
{
    $chars = "0123456789";
    $size = strlen($chars);
    $final = "";
    for ($i = 0; $i < $length; $i++) {
        $str = $chars[rand(0, $size - 1)];
        $final = $final . $str;
    }
    return $final;
}

function rand_string($length)
{
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $size = strlen($chars);
    $final = "";
    for ($i = 0; $i < $length; $i++) {
        $str = $chars[rand(0, $size - 1)];
        $final = $final . $str;
    }

    return $final;
}

function UserInfoPublic()
{
    if (Auth::guard('mainUsers')->user()) {
        $userInfo = MainUser::where('id', Auth::guard('mainUsers')->user()->id)->with('InfoActivity')->first();

        // Check if userInfo exists before accessing properties
        if ($userInfo) {
            // get country name with null check
            if ($userInfo->country_id) {
                $phoneCountry = PhoneCountry::find($userInfo->country_id);
                $userInfo->country_name = $phoneCountry ? $phoneCountry->name_en : null;
            } else {
                $userInfo->country_name = null;
            }
        }

        return $userInfo;
    } else {
        return null;
    }
}

function CartInfo()
{
    if (Auth::guard('mainUsers')->user()) {

        $cartInfo = Cart::where('user_id', Auth::guard('mainUsers')->user()->id)
            ->where('status', 0)
            ->with([
                'cartServices' => function ($q) {
                    $q->where('pay', 0)->with([
                        'serviceInfo',
                        'packageServiceItems.customeDeleteItem',
                        'packageServiceItems.orderItem',
                        'customePackageItems.orderItem'
                    ]);
                }
            ])
            ->first();

        if ($cartInfo && $cartInfo->cartServices) {
            foreach ($cartInfo->cartServices as $cartService) {
                if ($cartService->packageServiceItems) {
                    foreach ($cartService->packageServiceItems as $packageServiceItem) {
                        if (!$packageServiceItem->orderItem) {
                            $packageServiceItem->orderItem = OrderItem::where('id', $packageServiceItem->orderitem_id)->first();
                        }
                    }
                }
            }
        } else {
            $cartInfo = null;
        }
    } else {
        return $cartInfo = [];
    }
    return $cartInfo;
}
function CartCount()
{
    if (Auth::guard('mainUsers')->user()) {


        $cartCount = Cart::where('user_id', Auth::guard('mainUsers')->user()->id)
            ->where('status', 0)
            ->withCount(['cartServices as unpaid_count' => function ($q) {
                $q->where('pay', 0);
            }])
            ->first();
        if ($cartCount) {
            return $cartCount->unpaid_count ?? 0;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function userTerms()
{
    $termsConditions = Agreement::where('agreement_category_id', 4)->first();
    return $termsConditions;
}

function userPrivacy()
{
    $privacy = Agreement::where('agreement_category_id', 5)->first();
    return $privacy;
}

function userServiceAgreement()
{
    $serviceAgreement = Agreement::where('agreement_category_id', 3)->first();
    return $serviceAgreement;
}

function slidersTechnology()
{
    $sliders = Slider::where('status', 1)->where('page_src', 1)->orderBy('page_src_sort', 'asc')->get();

    return $sliders;
}

function activeItemsCount()
{
    $activeItems = Order::where('order_status', '!=', 'Done')->where('user_id', Auth::guard('mainUsers')->user()->id)->count();
    return $activeItems;
}
function recentActivityStartingItemCount()
{
    $activeItems = Order::where('order_status', 'Starting')->where('user_id', Auth::guard('mainUsers')->user()->id)->count();
    return $activeItems;
}
function recentActivityPendingItemCount()
{
    $activeItems = Order::where('order_status', 'Pending')->where('user_id', Auth::guard('mainUsers')->user()->id)->count();
    return $activeItems;
}
function BeautyActiveItems()
{
    $user = Auth::guard('mainUsers')->user();

    if (!$user) {
        return collect([]);
    }

    $iDs = $user->active_menu_id;
    if ($iDs === null || $iDs === '') {
        return collect([]);
    }

    // Support comma-separated string "1,2,3" or JSON "[1,2,3]" or array
    if (is_string($iDs)) {
        $decoded = json_decode($iDs, true);
        $iDs = is_array($decoded)
            ? array_filter(array_map('intval', $decoded))
            : array_filter(array_map('intval', explode(',', $iDs)));
    } else {
        $iDs = is_array($iDs) ? array_filter(array_map('intval', $iDs)) : [];
    }
    if (empty($iDs)) {
        return collect([]);
    }

    $activity = Activity::whereIn('id', $iDs)->orderBy('id', 'desc')->get();

    foreach ($activity as $item) {
        $item->infoActivity = InfoActivity::where('activity_id', $item->id)
            ->where('user_id', $user->id)
            ->get();
    }

    // count reserve from table clinic_reserves where user_id = Auth::guard('mainUsers')->user()->id
    $reserveCount = ClinicReserve::where('salon_id', $user->id)->where('cancel', 0)->where('status', 1)->where('confirm', 1)->count();
    //    reserveCountMenAcademy  
    $reserveCountMenAcademy = CourseRegistrationMan::where('salon_id', $user->id)
        ->where('status', 1)->where('confirm', 1)->count();
    //    reserveCountWomanAcademy  
    $reserveCountWomanAcademy = CourseRegistrationWoman::where('salon_id', $user->id)
        ->where('status', 1)->where('confirm', 1)->count();
    //    reserveCountManSalon  
    $reserveCountManSalon = manSalonReserve::where('salon_id', $user->id)
        ->where('status', 1)->where('confirm', 1)->count();
    //    reserveCountManSalon  
    $reserveCountWomanSalon = WomanSalonReserve::where('salon_id', $user->id)
        ->where('status', 1)->where('confirm', 1)->count();
    // reserveCount add to Beauty Clinic if activity title==Beauty Clinic
    $beautyBaseUrl = rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/');
    foreach ($activity as $item) {
        $isClinicBeauty = ($item->title_en ?? '') === 'clinic_beauty'
            || strtolower($item->title_en ?? '') === 'clinic_beauty'
            || ($item->title ?? '') === 'Beauty Clinic';
        if ($isClinicBeauty) {
            $item->reserveCountBC = $reserveCount;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=bclinic';
        }
        if ($item->title_en == 'man_learn') {
            $item->reserveCountMenAcademy = $reserveCountMenAcademy;
        }
        if ($item->title_en == 'woman_learn') {
            $item->reserveCountWomanAcademy = $reserveCountWomanAcademy;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=womanAcademy';
        }
        if ($item->title_en == 'man_salon') {
            $item->reserveCountManSalon = $reserveCountManSalon;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=manSalon';
        }
        if ($item->title_en == 'women_salon') {
            $item->reserveCountWomanSalon = $reserveCountWomanSalon;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=womenSalon';
        }
    }

    // Only show activity if its reserve count is > 0
    $activity = $activity->filter(function ($item) {
        $count = 0;
        $isClinicBeauty = ($item->title_en ?? '') === 'clinic_beauty'
            || strtolower($item->title_en ?? '') === 'clinic_beauty'
            || ($item->title ?? '') === 'Beauty Clinic';
        if ($isClinicBeauty) {
            $count = $item->reserveCountBC ?? 0;
        } elseif ($item->title_en == 'man_learn') {
            $count = $item->reserveCountMenAcademy ?? 0;
        } elseif ($item->title_en == 'woman_learn') {
            $count = $item->reserveCountWomanAcademy ?? 0;
        } elseif ($item->title_en == 'man_salon') {
            $count = $item->reserveCountManSalon ?? 0;
        } elseif ($item->title_en == 'women_salon') {
            $count = $item->reserveCountWomanSalon ?? 0;
        }
        return $count > 0;
    })->values();

    return $activity;
}


function BeautyReservePersonalItems()
{
    $user = Auth::guard('mainUsers')->user();

    // count reserve woman
    $WomanReserveCount = WomanSalonReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();
    // count man_salon_reserves
    $reserveCount = manSalonReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();
    // count clinic_reserves
    $ClinicreserveCount = ClinicReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();

    $totalCountReserves = $WomanReserveCount + $reserveCount + $ClinicreserveCount;
    return $totalCountReserves;
}
function BeautyCoursesPersonalItems()
{
    $user = Auth::guard('mainUsers')->user();
    // count course_registration_man_learn
    $CourseRegistrationManCount = CourseRegistrationMan::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();
    // count course_registration_woman_learn
    $CourseRegistrationWomanCount = CourseRegistrationWoman::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();
    $totalCountCourses = $CourseRegistrationManCount + $CourseRegistrationWomanCount;
    return $totalCountCourses;
}
function BeautyShoppingPersonalItems()
{
    $user = Auth::guard('mainUsers')->user();
    // count product
    // GROUP BY prodcut_customers.customer_id
    $ProductCount = ProdcutCustomer::where('customer_id', $user->id)->groupBy('customer_id')->count();
    return $ProductCount;
}

function AcrossAllModulesServices()
{
    $user = Auth::guard('mainUsers')->user();
    if (!$user) {
        return [
            'clinicReserve' => null,
            'womanSalonReserve' => null,
            'manSalonReserve' => null,
            'womanAcademyReserve' => null,
            'manAcademyReserve' => null,
        ];
    }

    $today = now()->format('Y-m-d');
    $dateExpr = "COALESCE(STR_TO_DATE(day_date, '%Y-%m-%d'), STR_TO_DATE(day_date, '%m-%d-%Y'), STR_TO_DATE(day_date, '%m/%d/%Y'))";
    $baseWhere = ['status' => 1, 'cancel' => 0, 'confirm' => 1];

    // clinic reserve
    $clinicReserve = ClinicReserve::where('salon_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($clinicReserve) {
        $clinic = Clinic::find($clinicReserve->service_id);
        $clinicReserve->serviceName = $clinic?->title ?? null;
    }

    // woman salon reserve
    $womanSalonReserve = WomanSalonReserve::where('salon_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($womanSalonReserve) {
        $service = WomenService::find($womanSalonReserve->service_id);
        $womanSalonReserve->serviceName = $service?->title ?? null;
    }

    // man salon reserve
    $manSalonReserve = manSalonReserve::where('salon_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($manSalonReserve) {
        $service = ManService::find($manSalonReserve->service_id);
        $manSalonReserve->serviceName = $service?->title ?? null;
    }

    // woman academy reserve
    $womanAcademyReserve = CourseRegistrationWoman::where('salon_id', $user->id)
        ->where('status', 1)
        ->where('confirm', 1)
        ->where('done', 0)
        ->first();
    if ($womanAcademyReserve) {
        $service = WomenAcademyService::find($womanAcademyReserve->service_id);
        $womanAcademyReserve->serviceName = $service?->title ?? null;
    }

    // man academy reserve
    $manAcademyReserve = CourseRegistrationMan::where('salon_id', $user->id)
        ->where('status', 1)
        ->where('confirm', 1)
        ->where('done', 0)
        ->first();
    if ($manAcademyReserve) {
        $service = MenAcademyService::find($manAcademyReserve->service_id);
        $manAcademyReserve->serviceName = $service?->title ?? null;
    }

    return [
        'clinicReserve' => $clinicReserve,
        'womanSalonReserve' => $womanSalonReserve,
        'manSalonReserve' => $manSalonReserve,
        'womanAcademyReserve' => $womanAcademyReserve,
        'manAcademyReserve' => $manAcademyReserve,
    ];
}

function AcrossAllModulesServicesPersonnel()
{
    $user = Auth::guard('mainUsers')->user();
    if (!$user) {
        return [
            'clinicReserve' => null,
            'womanSalonReserve' => null,
            'manSalonReserve' => null,
            'womanAcademyReserve' => null,
            'manAcademyReserve' => null,
        ];
    }

    $today = now()->format('Y-m-d');
    $dateExpr = "COALESCE(STR_TO_DATE(day_date, '%Y-%m-%d'), STR_TO_DATE(day_date, '%m-%d-%Y'), STR_TO_DATE(day_date, '%m/%d/%Y'))";
    $baseWhere = ['status' => 1, 'cancel' => 0, 'confirm' => 1];

    // clinic reserve
    $clinicReserve = ClinicReserve::where('user_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($clinicReserve) {
        $clinic = Clinic::find($clinicReserve->service_id);
        $clinicReserve->serviceName = $clinic?->title ?? null;
    }

    // woman salon reserve
    $womanSalonReserve = WomanSalonReserve::where('user_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($womanSalonReserve) {
        $service = WomenService::find($womanSalonReserve->service_id);
        $womanSalonReserve->serviceName = $service?->title ?? null;
    }

    // man salon reserve
    $manSalonReserve = manSalonReserve::where('user_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($manSalonReserve) {
        $service = ManService::find($manSalonReserve->service_id);
        $manSalonReserve->serviceName = $service?->title ?? null;
    }

    // woman academy reserve
    $womanAcademyReserve = CourseRegistrationWoman::where('user_id', $user->id)
        ->where('status', 1)
        ->where('confirm', 1)
        ->where('done', 0)
        ->first();
    if ($womanAcademyReserve) {
        $service = WomenAcademyService::find($womanAcademyReserve->service_id);
        $womanAcademyReserve->serviceName = $service?->title ?? null;
    }

    // man academy reserve
    $manAcademyReserve = CourseRegistrationMan::where('user_id', $user->id)
        ->where('status', 1)
        ->where('confirm', 1)
        ->where('done', 0)
        ->first();
    if ($manAcademyReserve) {
        $service = MenAcademyService::find($manAcademyReserve->service_id);
        $manAcademyReserve->serviceName = $service?->title ?? null;
    }

    return [
        'clinicReserveBusiness' => $clinicReserve,
        'womanSalonReserveBusiness' => $womanSalonReserve,
        'manSalonReserveBusiness' => $manSalonReserve,
        'womanAcademyReserveBusiness' => $womanAcademyReserve,
        'manAcademyReserveBusiness' => $manAcademyReserve,
    ];
}

// pending items
function BeautyPendingItems()
{
    $user = Auth::guard('mainUsers')->user();

    if (!$user) {
        return collect([]);
    }

    $iDs = $user->active_menu_id;
    if ($iDs === null || $iDs === '') {
        return collect([]);
    }

    // Support comma-separated string "1,2,3" or JSON "[1,2,3]" or array
    if (is_string($iDs)) {
        $decoded = json_decode($iDs, true);
        $iDs = is_array($decoded)
            ? array_filter(array_map('intval', $decoded))
            : array_filter(array_map('intval', explode(',', $iDs)));
    } else {
        $iDs = is_array($iDs) ? array_filter(array_map('intval', $iDs)) : [];
    }
    if (empty($iDs)) {
        return collect([]);
    }

    $activity = Activity::whereIn('id', $iDs)->orderBy('id', 'desc')->get();

    foreach ($activity as $item) {
        $item->infoActivity = InfoActivity::where('activity_id', $item->id)
            ->where('user_id', $user->id)
            ->get();
    }

    // count reserve from table clinic_reserves where user_id = Auth::guard('mainUsers')->user()->id
    $reserveCount = ClinicReserve::where('salon_id', $user->id)->where('cancel', 0)->where('status', 1)->where('confirm', 0)->count();
    //    reserveCountMenAcademy  
    $reserveCountMenAcademy = CourseRegistrationMan::where('salon_id', $user->id)
        ->where('status', 1)->where('confirm', 0)->count();
    //    reserveCountWomanAcademy  
    $reserveCountWomanAcademy = CourseRegistrationWoman::where('salon_id', $user->id)
        ->where('status', 1)->where('confirm', 0)->count();
    //    reserveCountManSalon  
    $reserveCountManSalon = manSalonReserve::where('salon_id', $user->id)
        ->where('status', 1)->where('confirm', 0)->count();
    //    reserveCountManSalon  
    $reserveCountWomanSalon = WomanSalonReserve::where('salon_id', $user->id)
        ->where('status', 1)->where('confirm', 0)->count();
    // reserveCount add to Beauty Clinic if activity title==Beauty Clinic
    $beautyBaseUrl = rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/');
    foreach ($activity as $item) {
        $isClinicBeauty = ($item->title_en ?? '') === 'clinic_beauty'
            || strtolower($item->title_en ?? '') === 'clinic_beauty'
            || ($item->title ?? '') === 'Beauty Clinic';
        if ($isClinicBeauty) {
            $item->reserveCountBC = $reserveCount;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=bclinic';
        }
        if ($item->title_en == 'man_learn') {
            $item->reserveCountMenAcademy = $reserveCountMenAcademy;
        }
        if ($item->title_en == 'woman_learn') {
            $item->reserveCountWomanAcademy = $reserveCountWomanAcademy;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=womanAcademy';
        }
        if ($item->title_en == 'man_salon') {
            $item->reserveCountManSalon = $reserveCountManSalon;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=manSalon';
        }
        if ($item->title_en == 'women_salon') {
            $item->reserveCountWomanSalon = $reserveCountWomanSalon;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=womenSalon';
        }
    }

    // Only show activity if its reserve count is > 0
    $activity = $activity->filter(function ($item) {
        $count = 0;
        $isClinicBeauty = ($item->title_en ?? '') === 'clinic_beauty'
            || strtolower($item->title_en ?? '') === 'clinic_beauty'
            || ($item->title ?? '') === 'Beauty Clinic';
        if ($isClinicBeauty) {
            $count = $item->reserveCountBC ?? 0;
        } elseif ($item->title_en == 'man_learn') {
            $count = $item->reserveCountMenAcademy ?? 0;
        } elseif ($item->title_en == 'woman_learn') {
            $count = $item->reserveCountWomanAcademy ?? 0;
        } elseif ($item->title_en == 'man_salon') {
            $count = $item->reserveCountManSalon ?? 0;
        } elseif ($item->title_en == 'women_salon') {
            $count = $item->reserveCountWomanSalon ?? 0;
        }
        return $count > 0;
    })->values();

    return $activity;
}
function BeautyReservePendingItems()
{
    $user = Auth::guard('mainUsers')->user();

    // count reserve woman
    $WomanReserveCount = WomanSalonReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();
    // count man_salon_reserves
    $reserveCount = manSalonReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();
    // count clinic_reserves
    $ClinicreserveCount = ClinicReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();

    $totalCountReserves = $WomanReserveCount + $reserveCount + $ClinicreserveCount;
    return $totalCountReserves;
}

function BeautyReservePendingItemsPersonnel()
{
    $user = Auth::guard('mainUsers')->user();
    if (!$user) {
        return [
            'clinicReserve' => null,
            'womanSalonReserve' => null,
            'manSalonReserve' => null,
            'womanAcademyReserve' => null,
            'manAcademyReserve' => null,
        ];
    }

    $today = now()->format('Y-m-d');
    $dateExpr = "COALESCE(STR_TO_DATE(day_date, '%Y-%m-%d'), STR_TO_DATE(day_date, '%m-%d-%Y'), STR_TO_DATE(day_date, '%m/%d/%Y'))";
    $baseWhere = ['status' => 1, 'cancel' => 0, 'confirm' => 0];

    // clinic reserve
    $clinicReserve = ClinicReserve::where('salon_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($clinicReserve) {
        $clinic = Clinic::find($clinicReserve->service_id);
        $clinicReserve->serviceName = $clinic?->title ?? null;
    }

    // woman salon reserve
    $womanSalonReserve = WomanSalonReserve::where('salon_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($womanSalonReserve) {
        $service = WomenService::find($womanSalonReserve->service_id);
        $womanSalonReserve->serviceName = $service?->title ?? null;
    }

    // man salon reserve
    $manSalonReserve = manSalonReserve::where('salon_id', $user->id)
        ->where($baseWhere)
        ->whereNotNull('day_date')
        ->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} >= ?", [$today])
        ->orderByRaw("{$dateExpr} ASC")
        ->first();
    if ($manSalonReserve) {
        $service = ManService::find($manSalonReserve->service_id);
        $manSalonReserve->serviceName = $service?->title ?? null;
    }

    // woman academy reserve
    $womanAcademyReserve = CourseRegistrationWoman::where('salon_id', $user->id)
        ->where('status', 1)
        ->where('confirm', 0)
        ->where('done', 0)
        ->first();
    if ($womanAcademyReserve) {
        $service = WomenAcademyService::find($womanAcademyReserve->service_id);
        $womanAcademyReserve->serviceName = $service?->title ?? null;
    }

    // man academy reserve
    $manAcademyReserve = CourseRegistrationMan::where('salon_id', $user->id)
        ->where('status', 1)
        ->where('confirm', 0)
        ->where('done', 0)
        ->first();
    if ($manAcademyReserve) {
        $service = MenAcademyService::find($manAcademyReserve->service_id);
        $manAcademyReserve->serviceName = $service?->title ?? null;
    }

    return [
        'clinicReserveBusiness' => $clinicReserve,
        'womanSalonReserveBusiness' => $womanSalonReserve,
        'manSalonReserveBusiness' => $manSalonReserve,
        'womanAcademyReserveBusiness' => $womanAcademyReserve,
        'manAcademyReserveBusiness' => $manAcademyReserve,
    ];
}

/** Personal pending "next up" - user's reservations (user_id) with confirm=0 */
function BeautyReservePendingItemsPersonal()
{
    $user = Auth::guard('mainUsers')->user();
    if (!$user) {
        return ['clinicReserve' => null, 'womanSalonReserve' => null, 'manSalonReserve' => null, 'womanAcademyReserve' => null, 'manAcademyReserve' => null];
    }
    $today = now()->format('Y-m-d');
    $dateExpr = "COALESCE(STR_TO_DATE(day_date, '%Y-%m-%d'), STR_TO_DATE(day_date, '%m-%d-%Y'), STR_TO_DATE(day_date, '%m/%d/%Y'))";
    $baseWhere = ['status' => 1, 'cancel' => 0, 'confirm' => 0];

    $clinicReserve = ClinicReserve::where('user_id', $user->id)->where($baseWhere)->whereNotNull('day_date')->where('day_date', '!=', '0000-00-00')->whereRaw("{$dateExpr} >= ?", [$today])->orderByRaw("{$dateExpr} ASC")->first();
    if ($clinicReserve) {
        $clinicReserve->serviceName = Clinic::find($clinicReserve->service_id)?->title ?? null;
    }

    $womanSalonReserve = WomanSalonReserve::where('user_id', $user->id)->where($baseWhere)->whereNotNull('day_date')->where('day_date', '!=', '0000-00-00')->whereRaw("{$dateExpr} >= ?", [$today])->orderByRaw("{$dateExpr} ASC")->first();
    if ($womanSalonReserve) {
        $womanSalonReserve->serviceName = WomenService::find($womanSalonReserve->service_id)?->title ?? null;
    }

    $manSalonReserve = manSalonReserve::where('user_id', $user->id)->where($baseWhere)->whereNotNull('day_date')->where('day_date', '!=', '0000-00-00')->whereRaw("{$dateExpr} >= ?", [$today])->orderByRaw("{$dateExpr} ASC")->first();
    if ($manSalonReserve) {
        $manSalonReserve->serviceName = ManService::find($manSalonReserve->service_id)?->title ?? null;
    }

    $womanAcademyReserve = CourseRegistrationWoman::where('salon_id', $user->id)->where('status', 1)->where('confirm', 0)->where('done', 0)->first();
    if ($womanAcademyReserve) {
        $womanAcademyReserve->serviceName = WomenAcademyService::find($womanAcademyReserve->service_id)?->title ?? null;
    }

    $manAcademyReserve = CourseRegistrationMan::where('salon_id', $user->id)->where('status', 1)->where('confirm', 0)->where('done', 0)->first();
    if ($manAcademyReserve) {
        $manAcademyReserve->serviceName = MenAcademyService::find($manAcademyReserve->service_id)?->title ?? null;
    }

    return ['clinicReserve' => $clinicReserve, 'womanSalonReserve' => $womanSalonReserve, 'manSalonReserve' => $manSalonReserve, 'womanAcademyReserve' => $womanAcademyReserve, 'manAcademyReserve' => $manAcademyReserve];
}
function BeautyReservePendingPersonalItems()
{
    $user = Auth::guard('mainUsers')->user();

    // count reserve woman
    $WomanReserveCount = WomanSalonReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 1)->count();
    // count man_salon_reserves
    $reserveCount = manSalonReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 1)->count();
    // count clinic_reserves
    $ClinicreserveCount = ClinicReserve::where('user_id', $user->id)->where('status', 1)->where('confirm', 1)->count();

    $totalCountReserves = $WomanReserveCount + $reserveCount + $ClinicreserveCount;
    return $totalCountReserves;
}
function BeautyCoursesPendingPersonalItems()
{
    $user = Auth::guard('mainUsers')->user();
    // count course_registration_man_learn
    $CourseRegistrationManCount = CourseRegistrationMan::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();
    // count course_registration_woman_learn
    $CourseRegistrationWomanCount = CourseRegistrationWoman::where('user_id', $user->id)->where('status', 1)->where('confirm', 0)->count();
    $totalCountCourses = $CourseRegistrationManCount + $CourseRegistrationWomanCount;
    return $totalCountCourses;
}

// done items
function BeautyDoneItems()
{
    $user = Auth::guard('mainUsers')->user();
    $todayDate = now()->format('Y-m-d');

    if (!$user) {
        return collect([]);
    }

    $iDs = $user->active_menu_id;
    if ($iDs === null || $iDs === '') {
        return collect([]);
    }

    // Support comma-separated string "1,2,3" or JSON "[1,2,3]" or array
    if (is_string($iDs)) {
        $decoded = json_decode($iDs, true);
        $iDs = is_array($decoded)
            ? array_filter(array_map('intval', $decoded))
            : array_filter(array_map('intval', explode(',', $iDs)));
    } else {
        $iDs = is_array($iDs) ? array_filter(array_map('intval', $iDs)) : [];
    }
    if (empty($iDs)) {
        return collect([]);
    }

    $activity = Activity::whereIn('id', $iDs)->orderBy('id', 'desc')->get();

    foreach ($activity as $item) {
        $item->infoActivity = InfoActivity::where('activity_id', $item->id)
            ->where('user_id', $user->id)
            ->get();
    }

    // Done = past/completed: clinic & salons use day_date < today; academies use finish=1
    $dateExpr = "COALESCE(STR_TO_DATE(day_date, '%Y-%m-%d'), STR_TO_DATE(day_date, '%m-%d-%Y'), STR_TO_DATE(day_date, '%m/%d/%Y'))";
 
    $reserveCount = ClinicReserve::where('salon_id', $user->id)
        ->whereNotNull('day_date')->where('day_date', '!=', '0000-00-00')
        ->where('day_date','<=', $todayDate)->where('cancel', 0)->where('status', 1)->where('custome_rate', 0)->count();



    $reserveCountMenAcademy = CourseRegistrationMan::where('salon_id', $user->id)
        ->where('status', 1)->where('done', 1)->count();

    $reserveCountWomanAcademy = CourseRegistrationWoman::where('salon_id', $user->id)
        ->where('status', 1)->where('done', 1)->count();

    $reserveCountManSalon = manSalonReserve::where('salon_id', $user->id)
        ->where('status', 1)->whereNotNull('day_date')->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} < ?", [$todayDate])->count();
    $reserveCountWomanSalon = WomanSalonReserve::where('salon_id', $user->id)
        ->where('status', 1)->whereNotNull('day_date')->where('day_date', '!=', '0000-00-00')
        ->whereRaw("{$dateExpr} < ?", [$todayDate])->count();
    // reserveCount add to Beauty Clinic if activity title==Beauty Clinic
    $beautyBaseUrl = rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/');
    foreach ($activity as $item) {
        $isClinicBeauty = ($item->title_en ?? '') === 'clinic_beauty'
            || strtolower($item->title_en ?? '') === 'clinic_beauty'
            || ($item->title ?? '') === 'Beauty Clinic';
        if ($isClinicBeauty) {
            $item->reserveCountBC = $reserveCount;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=bclinic';
        }
        if ($item->title_en == 'man_learn') {
            $item->reserveCountMenAcademy = $reserveCountMenAcademy;
        }
        if ($item->title_en == 'woman_learn') {
            $item->reserveCountWomanAcademy = $reserveCountWomanAcademy;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=womanAcademy';
        }
        if ($item->title_en == 'man_salon') {
            $item->reserveCountManSalon = $reserveCountManSalon;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=manSalon';
        }
        if ($item->title_en == 'women_salon') {
            $item->reserveCountWomanSalon = $reserveCountWomanSalon;
            $item->linkToBeautyClinic = $beautyBaseUrl . '/login/recentActivityBesmani/' . $user->id . '/?pageTo=womenSalon';
        }
    } 

    // Only show activity if its reserve count is > 0
    $activity = $activity->filter(function ($item) {
        $count = 0;
        $isClinicBeauty = ($item->title_en ?? '') === 'clinic_beauty'
            || strtolower($item->title_en ?? '') === 'clinic_beauty'
            || ($item->title ?? '') === 'Beauty Clinic';
        if ($isClinicBeauty) {
            $count = $item->reserveCountBC ?? 0;
        } elseif ($item->title_en == 'man_learn') {
            $count = $item->reserveCountMenAcademy ?? 0;
        } elseif ($item->title_en == 'woman_learn') {
            $count = $item->reserveCountWomanAcademy ?? 0;
        } elseif ($item->title_en == 'man_salon') {
            $count = $item->reserveCountManSalon ?? 0;
        } elseif ($item->title_en == 'women_salon') {
            $count = $item->reserveCountWomanSalon ?? 0;
        }
        return $count > 0;
    })->values();

    return $activity;
}


function BusinessList()
{
    $user = Auth::guard('mainUsers')->user();

    if (!$user) {
        return collect([]);
    }

    $iDs = $user->active_menu_id;
    if ($iDs === null || $iDs === '') {
        return collect([]);
    }

    // Support comma-separated string "1,2,3" or JSON "[1,2,3]" or array
    if (is_string($iDs)) {
        $decoded = json_decode($iDs, true);
        $iDs = is_array($decoded)
            ? array_filter(array_map('intval', $decoded))
            : array_filter(array_map('intval', explode(',', $iDs)));
    } else {
        $iDs = is_array($iDs) ? array_filter(array_map('intval', $iDs)) : [];
    }
    if (empty($iDs)) {
        return collect([]);
    }

    $activity = Activity::whereIn('id', $iDs)->orderBy('id', 'desc')->get();

    foreach ($activity as $item) {
        $item->infoActivity = InfoActivity::where('activity_id', $item->id)
            ->where('user_id', $user->id)
            ->get();
    }
    return $activity;

}
