@extends('layouts.app')
@section('title','Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Dashboard') }}</h2>
                    <div class="flex items-center gap-2">
                        <!-- Desktop Actions -->
                        <div class="hidden sm:flex items-center gap-2">
                            <button x-data @click="$dispatch('open-bill-calc-modal')" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold tex[...]">...</button>
                            @can('manage-tenants')
                            <a href="{{ route('admin.users') }}" class="inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded text-xs text-gray-800 dark:text-gray-200 hover:bg-gr[...]">...</a>
                            <button x-data @click="$dispatch('open-create-tenant')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-[...]">...</button>
                            @endcan
                        </div>

                        <!-- Mobile Actions Dropdown -->
                        <div class="sm:hidden flex items-center" x-data="{ open: false }">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-4[...]">...</button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute right-0 top-16 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md overflow-hidden shadow-xl z-50 ring-1 ring-black ring-opacity-5 py-1 origin-top-right"
                                style="display: none;">
                                <button @click="$dispatch('open-bill-calc-modal'); open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 [...]">...</button>
                                @can('manage-tenants')
                                <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Users</a>
                                <button @click="$dispatch('open-create-tenant'); open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 da[...]">...</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Success Toast Message  -->
                @if (session('status'))
                <div class="rounded-md bg-green-100 text-green-800 px-4 py-2 text-sm">
                    {{ session('status') }}
                </div>
                @endif
                <!-- Error Toast Message  -->
                @if (session('error'))
                <div class="rounded-md bg-red-100 text-red-800 px-4 py-2 text-sm">
                    {{ session('error') }}
                </div>
                @endif

                <livewire:dashboard.stats />

                <p>{{ __("You're logged in!") }}</p>
                @can('manage-tenants')
                <livewire:tenants-table />
                <livewire:tenant-bill-history />
                <livewire:bill-calculator />
                @else
                <p class="text-sm text-gray-600 dark:text-gray-400">You do not have permission to manage tenants.</p>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection