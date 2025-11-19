<div class="space-y-6">
    
    <!-- Summary Information -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-6 border border-blue-200 dark:border-blue-800">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-blue-900 dark:text-blue-100">Search Results Summary</h2>
            <div class="text-sm text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-800 px-3 py-1 rounded-full">
                Found {{ count($mainUsers) }} user(s) with mobile_moaref: {{ $userData['user_info']['Search Phone'] }}
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($mainUsers) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Users Found</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $userData['counts']['clinic_services'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Clinic Services</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ implode(', ', $mainUsers->pluck('id')->toArray()) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">User IDs</div>
            </div>
        </div>
    </div>
    <!-- Individual User Details -->
    @foreach($mainUsers as $index => $user)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        User #{{ $index + 1 }} - {{ trim($user->fl_name . ' ' . $user->last_name) }}
                    </h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                        ID: {{ $user->id }}
                    </div>
                </div>
            </div>
            
            <!-- User Details -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ trim($user->fl_name . ' ' . $user->last_name) }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email ?? 'N/A' }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobile</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->mobile ?? 'N/A' }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobile Moaref</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->mobile_moaref ?? 'N/A' }}</dd>
                    </div>
                </div>
            </div>

            <!-- Clinic Services for this User -->
            <div class="p-6">
                @php
                    $userClinicServices = \App\Models\ClinicService::where('user_id', $user->id)->orderBy('create_at', 'desc')->get();
                @endphp
                
                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">
                    Clinic Services ({{ $userClinicServices->count() }})
                </h4>
                
                @if($userClinicServices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Starting at</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Seats</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($userClinicServices as $service)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $service->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $service->service_name ?? $service->name ?? 'Service #' . $service->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $service->price ?? $service->starting_price ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $service->seats ?? $service->capacity ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $service->duration ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $service->create_at ? $service->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2">No clinic services found for this user</p>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
