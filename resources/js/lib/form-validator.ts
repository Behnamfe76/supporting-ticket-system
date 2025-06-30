import type { InertiaForm } from '@inertiajs/vue3';

interface Validators {
    required: string;
    string: string;
    number: string;
    min: string;
    max: string;
    email: string;
    password: string;
    nullable: string;
}

type ValidatorRule = keyof Validators | { min?: number; max?: number };
type ValidatorSchema = Record<string, ValidatorRule[]>;
const errorMessages: Validators = {
    required: 'This field is required.',
    string: 'This field must be a string.',
    number: 'This field must be a number.',
    min: 'This field is too short.',
    max: 'This field is too long.',
    email: 'This field must be a valid email address.',
    password: 'Passwords do not match or are missing.',
    nullable: '', // No error message needed for nullable
};

export function FormValidator(form: InertiaForm<any>, validators: ValidatorSchema): boolean {
    let isValid = true;
    form.clearErrors();

    for (const field in validators) {
        const value = form[field];
        const rules = validators[field];

        // If nullable and value is empty, skip all other validations for this field
        if (rules.includes('nullable') && (value === null || value === undefined || value === '')) {
            continue;
        }

        for (const rule of rules) {
            if (typeof rule === 'object') {
                if (rule.min !== undefined) {
                    if (typeof value === 'string' && value.length < rule.min) {
                        form.setError(field, `This field must be at least ${rule.min} characters.`);
                        isValid = false;
                    }
                }
                if (rule.max !== undefined) {
                    if (typeof value === 'string' && value.length > rule.max) {
                        form.setError(field, `This field must be at most ${rule.max} characters.`);
                        isValid = false;
                    }
                }
            } else {
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

                    case 'email':
                        if (value !== undefined && value !== null && value !== '') {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (typeof value !== 'string' || !emailRegex.test(value)) {
                                form.setError(field, errorMessages.email);
                                isValid = false;
                            }
                        }
                        break;

                    case 'password':
                        const password = form['password'];
                        const passwordConfirmation = form['password_confirmation'];
                        if (!password || !passwordConfirmation || password !== passwordConfirmation) {
                            form.setError('password', errorMessages.password);
                            form.setError('password_confirmation', errorMessages.password);
                            isValid = false;
                        }
                        break;
                }
            }

            if (form.errors[field]) break;
        }
    }

    return isValid;
}
