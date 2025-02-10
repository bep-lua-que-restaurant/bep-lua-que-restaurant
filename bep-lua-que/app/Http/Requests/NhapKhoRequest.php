<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NhapKhoRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép tất cả người dùng sử dụng request này
    }

    public function rules()
    {
        return [
           
        ];
    }

    
}
