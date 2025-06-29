<?php

namespace App\Http\Controllers;

use App\Models\PersonalTrainer;
use Illuminate\Http\Request;
use App\Models\GymClass;
use App\Models\PersonalTrainerPackage;
use App\Models\MembershipPackage;
use Inertia\Inertia;
use function Termwind\render;

class CatalogController extends Controller
{
    public function membershipPackages()
    {
        $user = auth()->user();

        if($user->membership_registered=='unregistered')
        {
            $packages = MembershipPackage::active()
                ->where('code', 'MP-001')
                ->get()
                ->map(function ($package) {
                    $package->duration_in_months = round($package->duration / 30, 1);
                    return $package;
                });
        } else {
            $packages = MembershipPackage::active()
                ->where('code', '!=', 'MP-001')
                ->get()
                ->map(function ($package) {
                    $package->duration_in_months = round($package->duration / 30, 1);
                    return $package;
                });
        }

        return Inertia::render('membershipPackages/index', compact('packages'));
    }

    public function membershipPackageDetail(MembershipPackage $membershipPackage)
    {
        $durationInMonths = round($membershipPackage->duration / 30, 1);

        return Inertia::render('membershipPackages/detail', [
            'mPackage' => [
                'id' => $membershipPackage->id,
                'code' => $membershipPackage->code,
                'name' => $membershipPackage->name,
                'slug' => $membershipPackage->slug,
                'description' => $membershipPackage->description,
                'duration' => $membershipPackage->duration,
                'duration_in_months' => $durationInMonths,
                'price' => $membershipPackage->price,
                'status' => $membershipPackage->status,
                'images' => $membershipPackage->images,
                'created_at' => $membershipPackage->created_at->toDateTimeString(),
                'updated_at' => $membershipPackage->updated_at->toDateTimeString(),
            ]
        ]);
    }

    public function personalTrainers() {
        $trainers = PersonalTrainer::all();

        return Inertia::render('personalTrainer/index', compact('trainers'));
    }

    public function trainerDetail(PersonalTrainer $personalTrainer)
    {
        $personalTrainer->load(['personalTrainerPackage' => function ($query) {
            $query->active();
        }]);

        return Inertia::render('personalTrainer/detail', [
            'ptDetail' => [
                'id' => $personalTrainer->id,
                'code' => $personalTrainer->code,
                'nickname' => $personalTrainer->nickname,
                'slug' => $personalTrainer->slug,
                'metadata' => $personalTrainer->metadata,
                'description' => $personalTrainer->description,
                'images' => $personalTrainer->images,
                'created_at' => $personalTrainer->created_at->toDateTimeString(),
                'updated_at' => $personalTrainer->updated_at->toDateTimeString(),
                'personalTrainerPackages' => $personalTrainer->personalTrainerPackage->map(function ($package) {
                    return [
                        'id' => $package->id,
                        'name' => $package->name,
                        'code' => $package->code,
                        'slug' => $package->slug,
                        'description' => $package->description,
                        'day_duration' => $package->day_duration,
                        'price' => $package->price,
                        'images' => $package->images
                    ];
                }),
            ]
        ]);
    }

    public function gymClasses()
    {
        $gymClasses = GymClass::active()->get();

        return Inertia::render('gymClasses/index', compact('gymClasses'));
    }

    public function gymClassDetail(GymClass $gymClass)
    {
        $gymClass->load([
            'gymClassSchedules' => function ($query) {
                $query->where('date', '>=', now()->toDateString())
                ->orderBy('date')
                    ->orderBy('start_time');
            }
        ]);

        return Inertia::render('gymClasses/detail', [
            'gymClass' => [
                'id' => $gymClass->id,
                'code' => $gymClass->code,
                'name' => $gymClass->name,
                'slug' => $gymClass->slug,
                'description' => $gymClass->description,
                'price' => $gymClass->price,
                'images' => $gymClass->images,
                'status' => $gymClass->status,
                'created_at' => $gymClass->created_at->toDateTimeString(),
                'updated_at' => $gymClass->updated_at->toDateTimeString(),

                // map schedules explicitly
                'gymClassSchedules' => $gymClass->gymClassSchedules->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'date' => $schedule->date->toDateString(),
                        'start_time' => $schedule->start_time->format('H:i:s'),
                        'end_time' => $schedule->end_time->format('H:i:s'),
                        'slot' => $schedule->slot,
                        'available_slot' => $schedule->available_slot,
                        'created_at' => $schedule->created_at->toDateTimeString(),
                        'updated_at' => $schedule->updated_at->toDateTimeString(),
                    ];
                }),
            ],
        ]);
    }
}
