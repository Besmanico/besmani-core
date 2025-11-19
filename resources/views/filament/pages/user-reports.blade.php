<div class="space-y-6">
    <!-- اطلاعات کاربر -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">اطلاعات کاربر</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($reports['user_info'] as $label => $value)
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $value ?? 'ندارد' }}</dd>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- آمار کلی -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">آمار کلی فعالیت‌ها</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full w-12 h-12 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['activities_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">فعالیت‌ها</div>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full w-12 h-12 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['products_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">محصولات</div>
                </div>

                <div class="text-center">
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full w-12 h-12 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['portfolios_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">نمونه کارها</div>
                </div>

                <div class="text-center">
                    <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full w-12 h-12 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['clinic_services_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">خدمات کلینیک</div>
                </div>
            </div>
        </div>
    </div>

    <!-- خدمات سالن -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">خدمات سالن</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="text-center">
                    <div class="bg-pink-100 dark:bg-pink-900 p-3 rounded-full w-12 h-12 mx-auto mb-2 flex items-center justify-center">
                        <span class="text-pink-600 dark:text-pink-300 text-sm font-bold">W</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['women_salon_services_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">خدمات سالن زنانه</div>
                </div>

                <div class="text-center">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full w-12 h-12 mx-auto mb-2 flex items-center justify-center">
                        <span class="text-blue-600 dark:text-blue-300 text-sm font-bold">M</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['men_salon_services_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">خدمات سالن مردانه</div>
                </div>
            </div>
        </div>
    </div>

    <!-- دوره‌های آکادمی -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">دوره‌های آکادمی</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="text-center">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full w-12 h-12 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['women_academy_courses_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">دوره‌های آکادمی زنانه</div>
                </div>

                <div class="text-center">
                    <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full w-12 h-12 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['men_academy_courses_count'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">دوره‌های آکادمی مردانه</div>
                </div>
            </div>
        </div>
    </div>

    <!-- اطلاعات تکمیلی -->
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            شناسه کاربر در جدول اصلی: <strong>{{ $mainUser->id }}</strong>
        </div>
    </div>
</div>
