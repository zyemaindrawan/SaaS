<div class="fi-modal-content w-full sm:max-w-md md:max-w-lg lg:max-w-xl mx-auto px-3 sm:px-4 md:px-6">
    
    <!-- Basic Information -->
    <div class="space-y-4">
        <div class="fi-section">
            <div class="fi-section-content">
                <div class="fi-section-content-ctn rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5">
                    <div class="fi-section-header-wrapper px-4 py-3 border-b border-gray-100">
                        <div class="fi-section-header">
                            <h4 class="fi-section-header-heading text-sm font-medium text-gray-800">
                                Personal Information
                            </h4>
                        </div>
                    </div>
                    <div class="p-4 space-y-4">
                        <!-- Name -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50">
                                    <svg class="h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-500">Full Name</p>
                                <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $user->name }}</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-50">
                                    <svg class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-500">Email Address</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-sm text-gray-900">{{ $user->email }}</p>
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center rounded bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700">
                                            <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded bg-amber-50 px-1.5 py-0.5 text-xs font-medium text-amber-700">
                                            <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                            Unverified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-50">
                                    <svg class="h-4 w-4 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-500">Phone Number</p>
                                <p class="text-sm text-gray-900 mt-0.5">{{ $user->phone ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fi-section">
            <div class="fi-section-content">
                <div class="fi-section-content-ctn rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5">
                    <div class="fi-section-header-wrapper px-4 py-3 border-b border-gray-100">
                        <div class="fi-section-header">
                            <h4 class="fi-section-header-heading text-sm font-medium text-gray-800">
                                Account Status
                            </h4>
                        </div>
                    </div>
                    <div class="p-4 space-y-4">
                        <!-- Status -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $user->is_active ? 'bg-emerald-50' : 'bg-red-50' }}">
                                    <svg class="h-4 w-4 {{ $user->is_active ? 'text-emerald-600' : 'text-red-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        @if($user->is_active)
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-500">Status</p>
                                <p class="text-sm font-medium {{ $user->is_active ? 'text-emerald-600' : 'text-red-600' }} mt-0.5">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                        </div>

                        <!-- Member Since -->
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-50">
                                    <svg class="h-4 w-4 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5M9 12.75h6m-6 3h6" />
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-gray-500">Member Since</p>
                                <p class="text-sm text-gray-900 mt-0.5">{{ $user->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <!-- Account Statistics -->
    <div class="fi-section">
        <div class="fi-section-content">
            <div class="fi-section-content-ctn rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5">
                <div class="fi-section-header-wrapper px-4 py-3 border-b border-gray-100">
                    <div class="fi-section-header">
                        <div class="flex items-center gap-3">
                            <div class="fi-section-header-icon text-gray-700">
                                <svg class="fi-icon fi-icon-size-md h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                            </div>
                            <h4 class="fi-section-header-heading text-sm font-medium text-gray-800">
                                Account Statistics
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50">
                                <svg class="h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-500">Total Websites</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $user->websiteContents()->count() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-50">
                                <svg class="h-4 w-4 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-500">Total Payments</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $user->payments()->count() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-50">
                                <svg class="h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-500">Total Spent</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">Rp {{ number_format($user->payments()->where('status', 'paid')->sum('final_amount'), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>