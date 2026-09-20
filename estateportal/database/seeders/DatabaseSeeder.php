<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Estate;
use App\Models\Plot;
use App\Models\PlotAllocation;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin ──────────────────────────────────────────────────
        $admin = User::create([
            'first_name' => 'Estate Portal',
            'last_name'  => 'Administrator',
            'username'   => 'admin',
            'email'      => 'admin@estateportal.test',
            'password'   => Hash::make('Admin@12345'),
            'role'       => 'admin',
        ]);

        // ── 2. Customers ──────────────────────────────────────────────
        $michael = User::create([
            'first_name' => 'Michael',
            'last_name'  => 'Njoku',
            'username'   => 'michael',
            'email'      => 'michael@estateportal.test',
            'password'   => Hash::make('Michael@12345'),
            'role'       => 'customer',
        ]);

        $david = User::create([
            'first_name' => 'David',
            'last_name'  => 'Okafor',
            'username'   => 'david',
            'email'      => 'david@estateportal.test',
            'password'   => Hash::make('David@12345'),
            'role'       => 'customer',
        ]);

        // ── 3. Estate: Potter's House Phase 2 ────────────────────────
        $estate = Estate::create([
            'name'        => "Potter's House Phase 2",
            'slug'        => 'potters-house-phase-2',
            'location'    => 'Off Ibusa/Warri Expressway, Ibusa Town, Oshimili North LGA, Delta State, Nigeria',
            'city'        => 'Ibusa',
            'lga'         => 'Oshimili North',
            'state'       => 'Delta State',
            'country'     => 'Nigeria',
            'description' => 'A premium residential estate in the heart of Ibusa, Delta State. Designed for modern living with full amenities, secure environment, and excellent road network.',
            'total_area'  => 50000,
            'total_plots' => 21,
            'status'      => 'active',
            'created_by'  => $admin->id,
        ]);

        // ── 4. Create 21 Plots (LOT 1-21) with normalized coordinates ─
        // Coordinates are normalized 0.0-1.0 demo grid layout
        // The admin will refine these using the Layout Manager
        $statusMap = [
            0  => 'allocated', // LOT 1 (David)
            2  => 'allocated', // LOT 3 (David)
            6  => 'allocated', // LOT 7 (Michael)
            14 => 'allocated', // LOT 15 (Michael)
        ];

        $sizesMap = [
            1 => 464.5, 2 => 382.0, 3 => 345.0, 4 => 356.0, 5 => 410.0,
            6 => 428.0, 7 => 464.5, 8 => 395.0, 9 => 370.0, 10 => 450.0,
            11 => 418.0, 12 => 382.0, 13 => 395.0, 14 => 360.0, 15 => 464.5,
            16 => 390.0, 17 => 375.0, 18 => 415.0, 19 => 430.0, 20 => 350.0, 21 => 420.0,
        ];

        for ($i = 0; $i < 21; $i++) {
            $lotNum = $i + 1;
            $plots[] = Plot::create([
                'estate_id'      => $estate->id,
                'plot_number'    => 'LOT ' . $lotNum,
                'plot_reference' => 'PH2-L' . str_pad($lotNum, 2, '0', STR_PAD_LEFT),
                'size'           => $sizesMap[$lotNum],
                'price'          => 1500000,
                'status'         => $statusMap[$i] ?? 'available',
                // coordinates intentionally NULL — admin must trace actual boundaries
                // in the Layout Manager over the uploaded estate image
                'coordinates'    => null,
                'notes'          => null,
            ]);
        }

        // ── 5. Allocate Plots to Customers ────────────────────────────

        // Michael → LOT 7
        $this->allocate($plots[6], $michael, $admin, 'POTE-LOT7-2026-0001', '2026-08-12');
        // Michael → LOT 15
        $this->allocate($plots[14], $michael, $admin, 'POTE-LOT15-2026-0002', '2026-08-15');
        // David → LOT 3
        $this->allocate($plots[2], $david, $admin, 'POTE-LOT3-2026-0003', '2026-07-20');
        // David → LOT 1
        $this->allocate($plots[0], $david, $admin, 'POTE-LOT1-2026-0004', '2026-06-10');

        // ── 6. Activity Log ───────────────────────────────────────────
        ActivityLog::create([
            'user_id'     => $admin->id,
            'action'      => 'estate_created',
            'description' => "Estate \"Potter's House Phase 2\" created by administrator.",
        ]);
    }

    private function allocate(Plot $plot, User $customer, User $admin, string $ref, string $date): void
    {
        PlotAllocation::create([
            'plot_id'              => $plot->id,
            'user_id'              => $customer->id,
            'allocation_reference' => $ref,
            'status'               => 'active',
            'payment_status'       => 'completed',
            'amount_paid'          => 1500000,
            'allocation_date'      => Carbon::parse($date),
            'allocated_at'         => Carbon::parse($date),
            'created_by'           => $admin->id,
        ]);

        // Notification
        \DB::table('notifications')->insert([
            'id'              => Str::uuid(),
            'type'            => 'App\\Notifications\\PlotAllocated',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id'   => $customer->id,
            'data'            => json_encode([
                'title'     => 'Plot Allocated to You',
                'message'   => "You have been allocated {$plot->plot_number} in Potter's House Phase 2. Reference: {$ref}",
                'plot_id'   => $plot->id,
                'estate_id' => $plot->estate_id,
                'reference' => $ref,
            ]),
            'created_at'      => Carbon::parse($date),
            'updated_at'      => Carbon::parse($date),
        ]);

        ActivityLog::create([
            'user_id'     => $admin->id,
            'action'      => 'plot_allocated',
            'description' => "Plot {$plot->plot_number} allocated to {$customer->full_name} — Ref: {$ref}",
        ]);
    }
}
