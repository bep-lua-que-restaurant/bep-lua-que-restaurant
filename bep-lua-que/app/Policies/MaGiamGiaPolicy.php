<?php

namespace App\Policies;

use App\Models\MaGiamGia;
use App\Models\NhanVien;
use Illuminate\Auth\Access\Response;

class MaGiamGiaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(NhanVien $nhanVien): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(NhanVien $nhanVien, MaGiamGia $maGiamGia): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(NhanVien $nhanVien): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(NhanVien $nhanVien, MaGiamGia $maGiamGia): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(NhanVien $nhanVien, MaGiamGia $maGiamGia): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(NhanVien $nhanVien, MaGiamGia $maGiamGia): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(NhanVien $nhanVien, MaGiamGia $maGiamGia): bool
    {
        //
    }
}
