@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Estates</h2>
        <p class="font-body-md text-on-surface-variant">Manage property developments, phases and overall capacities.</p>
    </div>
    <a href="{{ route('admin.estates.create') }}">
        <x-button variant="primary" icon="add_location">Create Estate</x-button>
    </a>
</div>

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-left font-body-sm text-on-surface">
            <thead class="bg-surface border-b border-outline-variant/30 text-on-surface-variant font-label-caps">
                <tr>
                    <th class="px-6 py-4 font-medium">Estate Name</th>
                    <th class="px-6 py-4 font-medium">Location</th>
                    <th class="px-6 py-4 font-medium text-center">Total Area</th>
                    <th class="px-6 py-4 font-medium text-center">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($estates as $estate)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-outline">domain</span>
                                {{ $estate->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $estate->location }}</td>
                        <td class="px-6 py-4 text-center">{{ number_format($estate->total_area) }} Sqm</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-1 rounded text-[10px] font-label-caps uppercase tracking-wider {{ $estate->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-surface-container-high text-on-surface-variant' }}">
                                {{ $estate->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.layouts.edit', $estate->id) }}" class="inline-block p-2 text-primary hover:bg-primary-fixed/30 rounded-lg transition-colors border border-outline-variant/50 mr-1" title="Manage Map Layout">
                                <span class="material-symbols-outlined text-[20px]">map</span>
                            </a>
                            <a href="{{ route('admin.estates.edit', $estate->id) }}" class="inline-block p-2 text-primary hover:bg-primary-fixed/30 rounded-lg transition-colors border border-outline-variant/50 mr-1" title="Edit Estate">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.estates.destroy', $estate->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-error border border-error/50 hover:bg-error-container/30 rounded-lg transition-colors" title="Delete Estate">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">holiday_village</span>
                            No estates found in the system.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection
