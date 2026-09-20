@extends('layouts.customer')

@section('content')
<div class="mb-6">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">My Documents</h2>
    <p class="font-body-md text-on-surface-variant">View and download all official paperwork associated with your account.</p>
</div>

<x-card class="max-w-4xl">
    <table class="w-full text-left font-body-sm text-on-surface">
        <thead class="bg-surface font-label-caps text-on-surface-variant border-b border-outline-variant/30">
            <tr>
                <th class="px-6 py-4 font-medium">Document Name</th>
                <th class="px-6 py-4 font-medium">Type</th>
                <th class="px-6 py-4 font-medium">Date Uploaded</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20">
            <!-- Simulated Sample Response -->
            <tr class="hover:bg-surface-container-low/50 transition-colors">
                <td class="px-6 py-4 font-medium flex items-center gap-3">
                    <span class="material-symbols-outlined text-outline">description</span>
                    Welcome Letter (Potter's House)
                </td>
                <td class="px-6 py-4">Receipt</td>
                <td class="px-6 py-4 font-label-caps text-on-surface-variant">{{ now()->subDays(5)->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-right flex justify-end gap-2">
                    <button class="text-primary hover:bg-primary-fixed/30 px-3 py-2 rounded-lg transition-colors font-label-caps flex items-center gap-1">
                        Download <span class="material-symbols-outlined text-[16px]">download</span>
                    </button>
                </td>
            </tr>
            <tr class="hover:bg-surface-container-low/50 transition-colors">
                <td class="px-6 py-4 font-medium flex items-center gap-3">
                    <span class="material-symbols-outlined text-outline">picture_as_pdf</span>
                    Provisional Allocation Letter
                </td>
                <td class="px-6 py-4">Contract</td>
                <td class="px-6 py-4 font-label-caps text-on-surface-variant">{{ now()->subDays(30)->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-right flex justify-end gap-2">
                    <button class="text-primary hover:bg-primary-fixed/30 px-3 py-2 rounded-lg transition-colors font-label-caps flex items-center gap-1">
                        Download <span class="material-symbols-outlined text-[16px]">download</span>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</x-card>
@endsection
