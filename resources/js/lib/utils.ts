import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function capitalize(str: string) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

export const getImageName = (str: string) => str?.split('/').pop() || '';

export function generateStatusStyles(status: string): string {
    switch (status.toLowerCase()) {
        case 'open':
            return 'text-green-700 bg-green-200';
        case 'in_progress':
            return 'text-yellow-700 bg-yellow-200';
        case 'closed':
            return 'text-red-700 bg-red-200';
        case 'resolved':
            return 'text-indigo-700 bg-indigo-200';
        default:
            return 'text-blue-700 bg-blue-300';
    }
}
