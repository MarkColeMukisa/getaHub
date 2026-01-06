<div wire:poll.30s class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Tenants -->
    <div class="relative group overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-3xl group-hover:bg-indigo-100 dark:group-hover:bg-indigo-800/30 transition-colors duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Tenants</p>
                <h3 class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format($stats['total_tenants']) }}</h3>
            </div>
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl text-indigo-600 dark:text-indigo-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400">
            <span>Overall active records</span>
        </div>
    </div>

    <!-- Bills This Month -->
    <div class="relative group overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full blur-3xl group-hover:bg-emerald-100 dark:group-hover:bg-emerald-800/30 transition-colors duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Monthly Bills</p>
                <h3 class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format($stats['bills_this_month']) }}</h3>
            </div>
            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl text-emerald-600 dark:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm font-medium text-emerald-600 dark:text-emerald-400">
            <span>Generated this month</span>
        </div>
    </div>

    <!-- Notifications Sent -->
    <div class="relative group overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-50 dark:bg-sky-900/20 rounded-full blur-3xl group-hover:bg-sky-100 dark:group-hover:bg-sky-800/30 transition-colors duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notifications</p>
                <h3 class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format($stats['notifications_sent']) }}</h3>
            </div>
            <div class="p-3 bg-sky-100 dark:bg-sky-900/40 rounded-xl text-sky-600 dark:text-sky-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm font-medium text-sky-600 dark:text-sky-400">
            <span>Delivered succesfully</span>
        </div>
    </div>

    <!-- Failed Notifications -->
    <div class="relative group overflow-hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 dark:bg-rose-900/20 rounded-full blur-3xl group-hover:bg-rose-100 dark:group-hover:bg-rose-800/30 transition-colors duration-500"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alerts</p>
                <h3 class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format($stats['failed_notifications']) }}</h3>
            </div>
            <div class="p-3 bg-rose-100 dark:bg-rose-900/40 rounded-xl text-rose-600 dark:text-rose-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm font-medium text-rose-600 dark:text-rose-400">
            <span>Requires attention</span>
        </div>
    </div>
</div>