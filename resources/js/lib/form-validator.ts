import type { InertiaForm } from '@inertiajs/vue3';
interface Validators {
    required: string;
    string: string;
    number: string;
}
type ValidatorRule = keyof Validators; // 'required' | 'string' | 'number'
type ValidatorSchema = Record<string, ValidatorRule[]>;
const errorMessages: Validators = {
    required: 'This field is required.',
    string: 'This field must be a string.',
    number: 'This field must be a number.',
};

export function FormValidator(
    form: InertiaForm<any>,
    validators: ValidatorSchema
): boolean {
    let isValid = true;
    form.clearErrors(); // Remove old errors

    for (const field in validators) {
        const value = form[field];
        const rules = validators[field];

        for (const rule of rules) {
            switch (rule) {
                case 'required':
                    if (value === null || value === undefined || value === '') {
                        form.setError(field, errorMessages.required);
                        isValid = false;
                    }
                    break;

                case 'string':
                    if (value !== undefined && value !== null && typeof value !== 'string') {
                        form.setError(field, errorMessages.string);
                        isValid = false;
                    }
                    break;

                case 'number':
                    if (value !== undefined && value !== null && typeof value !== 'number') {
                        form.setError(field, errorMessages.number);
                        isValid = false;
                    }
                    break;
            }

            // Break on first error for a field
            if (form.errors[field]) break;
        }
    }

    return isValid;
}
