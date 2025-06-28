<?php

namespace App\Http\Requests\Tickets;

use App\Data\TicketData;
use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Ticket::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'min:5', 'max:255'],
            'content' => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'file_data' => ['nullable'],
            'file_data.path' => ['required_with:file_data', 'string'],
            'file_data.file_name' => ['required_with:file_data', 'string'],
        ];
    }

    /**
     * @return TicketData
     */
    public function toDto(): TicketData
    {
        return TicketData::fromRequest($this);
    }
}
