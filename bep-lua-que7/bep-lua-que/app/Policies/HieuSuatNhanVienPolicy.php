<?php

namespace App\Policies;

use App\Models\HieuSuatNhanVien;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HieuSuatNhanVienPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HieuSuatNhanVien $hieuSuatNhanVien): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HieuSuatNhanVien $hieuSuatNhanVien): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HieuSuatNhanVien $hieuSuatNhanVien): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HieuSuatNhanVien $hieuSuatNhanVien): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HieuSuatNhanVien $hieuSuatNhanVien): bool
    {
        //
    }
}
