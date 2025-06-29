<?php

namespace App\Http\Requests\Tickets;

use App\Data\ReplyData;
use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'file_data' => ['nullable'],
            'file_data.path' => ['required_with:file_data', 'string'],
            'file_data.file_name' => ['required_with:file_data', 'string'],
        ];
    }

    /**
     * @param Ticket $ticket
     * @return ReplyData
     */
    public function toDto(Ticket $ticket): ReplyData
    {
        return ReplyData::fromRequest($this, $ticket);
    }
}
