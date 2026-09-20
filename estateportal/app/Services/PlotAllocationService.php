<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Plot;
use App\Models\PlotAllocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PlotAllocationService
{
    /**
     * Allocate a plot to a user within a DB transaction.
     * Throws \RuntimeException if the plot already has an active allocation.
     */
    public function allocate(Plot $plot, User $customer, array $data, ?User $createdBy = null): PlotAllocation
    {
        return DB::transaction(function () use ($plot, $customer, $data, $createdBy) {
            // Lock the plot row to prevent concurrent allocations
            $freshPlot = Plot::lockForUpdate()->findOrFail($plot->id);

            // Check for existing active allocation
            $existing = PlotAllocation::where('plot_id', $freshPlot->id)
                ->where('status', 'active')
                ->first();

            if ($existing) {
                throw new \RuntimeException(
                    "{$freshPlot->plot_number} is already allocated and cannot be assigned to another customer."
                );
            }

            // Verify the plot is not in an allocated status without an active record (data integrity)
            if ($freshPlot->status === 'allocated') {
                throw new \RuntimeException(
                    "{$freshPlot->plot_number} is currently marked as allocated. Please contact the administrator."
                );
            }

            // Generate allocation reference
            $reference = $data['allocation_reference'] ?? $this->generateReference($freshPlot);

            // Create the allocation
            $allocation = PlotAllocation::create([
                'plot_id'              => $freshPlot->id,
                'user_id'              => $customer->id,
                'allocation_reference' => $reference,
                'status'               => 'active',
                'payment_status'       => $data['payment_status'] ?? 'pending',
                'amount_paid'          => $data['amount_paid'] ?? 0,
                'allocation_date'      => $data['allocation_date'] ?? now(),
                'allocated_at'         => now(),
                'notes'                => $data['notes'] ?? null,
                'created_by'           => $createdBy?->id,
            ]);

            // Update plot status
            $freshPlot->update(['status' => 'allocated']);

            // Create activity log
            ActivityLog::create([
                'user_id'     => $createdBy?->id ?? $customer->id,
                'action'      => 'plot_allocated',
                'description' => "Plot {$freshPlot->plot_number} (ID:{$freshPlot->id}) allocated to {$customer->full_name} — Ref: {$reference}",
                'resource'    => 'PlotAllocation',
                'resource_id' => $allocation->id,
            ]);

            // Create notification for the customer
            \DB::table('notifications')->insert([
                'id'              => Str::uuid(),
                'type'            => 'App\\Notifications\\PlotAllocated',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => $customer->id,
                'data'            => json_encode([
                    'title'      => 'Plot Allocated to You',
                    'message'    => "You have been allocated {$freshPlot->plot_number} in {$freshPlot->estate->name}. Reference: {$reference}",
                    'plot_id'    => $freshPlot->id,
                    'estate_id'  => $freshPlot->estate_id,
                    'reference'  => $reference,
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return $allocation;
        });
    }

    /**
     * Cancel/revoke an active allocation within a transaction.
     */
    public function revoke(PlotAllocation $allocation, ?User $revokedBy = null): void
    {
        DB::transaction(function () use ($allocation, $revokedBy) {
            $allocation->update(['status' => 'cancelled']);
            $allocation->plot->update(['status' => 'available']);

            ActivityLog::create([
                'user_id'     => $revokedBy?->id,
                'action'      => 'plot_allocation_revoked',
                'description' => "Allocation {$allocation->allocation_reference} revoked. Plot {$allocation->plot->plot_number} is now available.",
                'resource'    => 'PlotAllocation',
                'resource_id' => $allocation->id,
            ]);
        });
    }

    private function generateReference(Plot $plot): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $plot->estate->name ?? 'EP'), 0, 4));
        $lot    = strtoupper(preg_replace('/\s+/', '', $plot->plot_number));
        $year   = now()->year;
        $seq    = str_pad(PlotAllocation::count() + 1, 4, '0', STR_PAD_LEFT);
        return "{$prefix}-{$lot}-{$year}-{$seq}";
    }
}
