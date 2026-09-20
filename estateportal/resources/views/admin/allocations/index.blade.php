@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Plot Allocations</h2>
        <p class="font-body-md text-on-surface-variant">Manage the assignment of plots to customers.</p>
    </div>
    <a href="{{ route('admin.allocations.create') }}">
        <x-button variant="primary" icon="add">New Allocation</x-button>
    </a>
</div>

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-left font-body-sm text-on-surface">
            <thead class="bg-surface border-b border-outline-variant/30 text-on-surface-variant font-label-caps">
                <tr>
                    <th class="px-6 py-4 font-medium">Customer</th>
                    <th class="px-6 py-4 font-medium">Plot Details</th>
                    <th class="px-6 py-4 font-medium">Price</th>
                    <th class="px-6 py-4 font-medium">Status / Amount Paid</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($allocations as $allocation)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-on-surface">{{ $allocation->customer->first_name }} {{ $allocation->customer->last_name }}</div>
                            <div class="text-on-surface-variant text-xs">{{ $allocation->customer->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium">Plot {{ $allocation->plot->plot_number }}</div>
                            <div class="text-on-surface-variant text-xs">{{ $allocation->plot->estate->name }}</div>
                        </td>
                        <td class="px-6 py-4">₦{{ number_format($allocation->plot->price) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded text-[10px] font-label-caps uppercase tracking-wider {{ $allocation->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-primary-container text-on-primary-container' }} mb-1 block w-max">
                                {{ $allocation->payment_status }}
                            </span>
                            <div class="text-xs font-medium">Paid: ₦{{ number_format($allocation->amount_paid) }}</div>
                        </td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($allocation->allocation_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="inline-block p-2 text-primary hover:bg-primary-fixed/30 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.allocations.destroy', $allocation->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-error hover:bg-error-container/30 rounded-lg transition-colors" onclick="return confirm('Are you sure you want to revoke this property allocation?');">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">description</span>
                            No plot allocations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $allocations->links() }}
    </div>
</x-card>
@endsection
