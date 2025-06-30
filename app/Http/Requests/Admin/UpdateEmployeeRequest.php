<?php

namespace App\Http\Requests\Admin;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:admin,staff',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return UserData
     */
    public function toDto(User $user): UserData
    {
        return UserData::from([
            ...$this->validated(),
            'email' => $user->email,
        ]);
    }
}
