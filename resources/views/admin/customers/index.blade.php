@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Registered Customers</h2>
        <p class="font-body-md text-on-surface-variant">View and manage all customer accounts on the platform.</p>
    </div>
    <a href="{{ route('admin.customers.create') }}">
        <x-button variant="primary" icon="add">Add New Customer</x-button>
    </a>
</div>

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-left font-body-sm text-on-surface">
            <thead class="bg-surface border-b border-outline-variant/30 text-on-surface-variant font-label-caps">
                <tr>
                    <th class="px-6 py-4 font-medium">Customer Name</th>
                    <th class="px-6 py-4 font-medium">Contact Details</th>
                    <th class="px-6 py-4 font-medium">Role</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($customers as $customer)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-outline">account_circle</span>
                                {{ $customer->first_name }} {{ $customer->last_name }}
                            </div>
                            <div class="text-on-surface-variant text-xs mt-1 ml-8">@ {{ $customer->username }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="mb-1">{{ $customer->email }}</div>
                            <div class="text-on-surface-variant">{{ $customer->phone ?? 'No phone added' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded text-[10px] font-label-caps uppercase tracking-wider {{ $customer->role === 'admin' ? 'bg-error-container text-on-error-container' : 'bg-primary-container text-on-primary-container' }}">
                                {{ $customer->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="inline-block p-2 text-primary hover:bg-primary-fixed/30 rounded-lg transition-colors" title="Edit Customer">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to completely delete this customer? All their associated properties will be affected.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-error hover:bg-error-container/30 rounded-lg transition-colors" title="Delete Customer">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">group_off</span>
                            No customers registered yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($customers, 'links'))
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    @endif
</x-card>
@endsection
