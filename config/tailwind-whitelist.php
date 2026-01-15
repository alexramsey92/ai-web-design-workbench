<?php

/**
 * Tailwind CSS Curated Whitelist
 * 
 * This file contains the curated list of Tailwind classes that can be used
 * in AI-generated HTML. This ensures consistent, production-ready output.
 * 
 * Classes are organized by category and styling level (full/mid/low).
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Layout & Spacing
    |--------------------------------------------------------------------------
    */
    'layout' => [
        'full' => [
            // Container & Width
            'container', 'mx-auto', 'max-w-7xl', 'max-w-6xl', 'max-w-5xl', 'max-w-4xl', 
            'max-w-3xl', 'max-w-2xl', 'max-w-xl', 'max-w-lg', 'max-w-md', 'max-w-sm',
            'w-full', 'w-screen', 'w-auto', 'w-1/2', 'w-1/3', 'w-2/3', 'w-1/4', 'w-3/4',
            
            // Padding
            'p-0', 'p-1', 'p-2', 'p-3', 'p-4', 'p-5', 'p-6', 'p-8', 'p-10', 'p-12', 'p-16', 'p-20', 'p-24',
            'px-0', 'px-1', 'px-2', 'px-3', 'px-4', 'px-5', 'px-6', 'px-8', 'px-10', 'px-12', 'px-16', 'px-20', 'px-24',
            'py-0', 'py-1', 'py-2', 'py-3', 'py-4', 'py-5', 'py-6', 'py-8', 'py-10', 'py-12', 'py-16', 'py-20', 'py-24',
            'pt-0', 'pt-1', 'pt-2', 'pt-4', 'pt-6', 'pt-8', 'pt-10', 'pt-12', 'pt-16', 'pt-20', 'pt-24',
            'pb-0', 'pb-1', 'pb-2', 'pb-4', 'pb-6', 'pb-8', 'pb-10', 'pb-12', 'pb-16', 'pb-20', 'pb-24',
            'pl-0', 'pl-1', 'pl-2', 'pl-4', 'pl-6', 'pl-8', 'pl-10', 'pl-12',
            'pr-0', 'pr-1', 'pr-2', 'pr-4', 'pr-6', 'pr-8', 'pr-10', 'pr-12',
            
            // Margin
            'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5', 'm-6', 'm-8', 'm-10', 'm-12', 'm-16', 'm-20', 'm-24', 'm-auto',
            'mx-0', 'mx-1', 'mx-2', 'mx-3', 'mx-4', 'mx-5', 'mx-6', 'mx-8', 'mx-10', 'mx-12', 'mx-16', 'mx-20', 'mx-24', 'mx-auto',
            'my-0', 'my-1', 'my-2', 'my-3', 'my-4', 'my-5', 'my-6', 'my-8', 'my-10', 'my-12', 'my-16', 'my-20', 'my-24', 'my-auto',
            'mt-0', 'mt-1', 'mt-2', 'mt-4', 'mt-6', 'mt-8', 'mt-10', 'mt-12', 'mt-16', 'mt-20', 'mt-24',
            'mb-0', 'mb-1', 'mb-2', 'mb-4', 'mb-6', 'mb-8', 'mb-10', 'mb-12', 'mb-16', 'mb-20', 'mb-24',
            'ml-0', 'ml-1', 'ml-2', 'ml-4', 'ml-6', 'ml-8', 'ml-10', 'ml-12', 'ml-auto',
            'mr-0', 'mr-1', 'mr-2', 'mr-4', 'mr-6', 'mr-8', 'mr-10', 'mr-12', 'mr-auto',
            
            // Gap
            'gap-0', 'gap-1', 'gap-2', 'gap-3', 'gap-4', 'gap-5', 'gap-6', 'gap-8', 'gap-10', 'gap-12', 'gap-16',
            'gap-x-0', 'gap-x-1', 'gap-x-2', 'gap-x-3', 'gap-x-4', 'gap-x-6', 'gap-x-8', 'gap-x-10', 'gap-x-12',
            'gap-y-0', 'gap-y-1', 'gap-y-2', 'gap-y-3', 'gap-y-4', 'gap-y-6', 'gap-y-8', 'gap-y-10', 'gap-y-12',
            
            // Space Between
            'space-x-0', 'space-x-1', 'space-x-2', 'space-x-3', 'space-x-4', 'space-x-6', 'space-x-8',
            'space-y-0', 'space-y-1', 'space-y-2', 'space-y-3', 'space-y-4', 'space-y-6', 'space-y-8',
        ],
        'mid' => [
            'container', 'mx-auto', 'max-w-7xl', 'max-w-4xl', 'max-w-2xl',
            'w-full', 'w-1/2', 'w-1/3', 'w-2/3',
            'p-4', 'p-6', 'p-8', 'p-12',
            'px-4', 'px-6', 'px-8', 'py-4', 'py-6', 'py-8', 'py-12', 'py-16',
            'm-0', 'm-auto', 'mx-auto', 'my-8', 'my-12',
            'gap-4', 'gap-6', 'gap-8', 'space-x-4', 'space-y-4', 'space-y-6',
        ],
        'low' => [
            'container', 'mx-auto', 'max-w-7xl',
            'w-full', 'p-4', 'p-8', 'px-4', 'py-8', 'py-12',
            'my-8', 'gap-4', 'space-y-4',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Flexbox & Grid
    |--------------------------------------------------------------------------
    */
    'flexbox' => [
        'full' => [
            'flex', 'inline-flex', 'flex-row', 'flex-row-reverse', 'flex-col', 'flex-col-reverse',
            'flex-wrap', 'flex-wrap-reverse', 'flex-nowrap',
            'flex-1', 'flex-auto', 'flex-initial', 'flex-none',
            'grow', 'grow-0', 'shrink', 'shrink-0',
            'justify-start', 'justify-end', 'justify-center', 'justify-between', 'justify-around', 'justify-evenly',
            'items-start', 'items-end', 'items-center', 'items-baseline', 'items-stretch',
            'content-start', 'content-end', 'content-center', 'content-between', 'content-around', 'content-evenly',
            'self-auto', 'self-start', 'self-end', 'self-center', 'self-stretch',
        ],
        'mid' => [
            'flex', 'flex-row', 'flex-col', 'flex-wrap',
            'flex-1', 'justify-center', 'justify-between',
            'items-center', 'items-start',
        ],
        'low' => [
            'flex', 'flex-col', 'justify-center', 'items-center',
        ],
    ],

    'grid' => [
        'full' => [
            'grid', 'inline-grid',
            'grid-cols-1', 'grid-cols-2', 'grid-cols-3', 'grid-cols-4', 'grid-cols-5', 'grid-cols-6', 
            'grid-cols-12',
            'col-span-1', 'col-span-2', 'col-span-3', 'col-span-4', 'col-span-5', 'col-span-6',
            'col-span-7', 'col-span-8', 'col-span-9', 'col-span-10', 'col-span-11', 'col-span-12',
            'col-span-full', 'col-start-1', 'col-start-2', 'col-end-3', 'col-end-4',
            'grid-rows-1', 'grid-rows-2', 'grid-rows-3', 'grid-rows-4', 'grid-rows-5', 'grid-rows-6',
            'row-span-1', 'row-span-2', 'row-span-3', 'row-span-4', 'row-span-5', 'row-span-6',
            'auto-cols-auto', 'auto-cols-min', 'auto-cols-max', 'auto-cols-fr',
            'auto-rows-auto', 'auto-rows-min', 'auto-rows-max', 'auto-rows-fr',
        ],
        'mid' => [
            'grid', 'grid-cols-1', 'grid-cols-2', 'grid-cols-3', 'grid-cols-4',
            'col-span-1', 'col-span-2', 'col-span-full',
        ],
        'low' => [
            'grid', 'grid-cols-1', 'grid-cols-2', 'grid-cols-3',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Typography
    |--------------------------------------------------------------------------
    */
    'typography' => [
        'full' => [
            // Font Family
            'font-sans', 'font-serif', 'font-mono', 'font-display',
            
            // Font Size
            'text-xs', 'text-sm', 'text-base', 'text-lg', 'text-xl', 
            'text-2xl', 'text-3xl', 'text-4xl', 'text-5xl', 'text-6xl', 'text-7xl', 'text-8xl', 'text-9xl',
            
            // Font Weight
            'font-thin', 'font-extralight', 'font-light', 'font-normal', 'font-medium', 
            'font-semibold', 'font-bold', 'font-extrabold', 'font-black',
            
            // Line Height
            'leading-3', 'leading-4', 'leading-5', 'leading-6', 'leading-7', 'leading-8', 'leading-9', 'leading-10',
            'leading-none', 'leading-tight', 'leading-snug', 'leading-normal', 'leading-relaxed', 'leading-loose',
            
            // Text Alignment
            'text-left', 'text-center', 'text-right', 'text-justify',
            
            // Text Color
            'text-white', 'text-black', 'text-gray-50', 'text-gray-100', 'text-gray-200', 'text-gray-300',
            'text-gray-400', 'text-gray-500', 'text-gray-600', 'text-gray-700', 'text-gray-800', 'text-gray-900',
            'text-primary-50', 'text-primary-100', 'text-primary-200', 'text-primary-300', 'text-primary-400',
            'text-primary-500', 'text-primary-600', 'text-primary-700', 'text-primary-800', 'text-primary-900',
            
            // Text Decoration
            'underline', 'overline', 'line-through', 'no-underline',
            'decoration-solid', 'decoration-double', 'decoration-dotted', 'decoration-dashed', 'decoration-wavy',
            
            // Text Transform
            'uppercase', 'lowercase', 'capitalize', 'normal-case',
            
            // Text Overflow
            'truncate', 'text-ellipsis', 'text-clip', 'overflow-hidden',
            
            // Letter Spacing
            'tracking-tighter', 'tracking-tight', 'tracking-normal', 'tracking-wide', 'tracking-wider', 'tracking-widest',
        ],
        'mid' => [
            'font-sans', 'font-display',
            'text-sm', 'text-base', 'text-lg', 'text-xl', 'text-2xl', 'text-3xl', 'text-4xl', 'text-5xl',
            'font-normal', 'font-medium', 'font-semibold', 'font-bold',
            'leading-tight', 'leading-normal', 'leading-relaxed',
            'text-left', 'text-center',
            'text-white', 'text-gray-600', 'text-gray-700', 'text-gray-800', 'text-gray-900',
            'text-primary-600', 'text-primary-700',
            'underline', 'no-underline', 'uppercase', 'capitalize',
        ],
        'low' => [
            'font-sans', 'text-base', 'text-xl', 'text-2xl', 'text-3xl',
            'font-normal', 'font-bold', 'text-center',
            'text-gray-700', 'text-gray-900',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Backgrounds & Borders
    |--------------------------------------------------------------------------
    */
    'backgrounds' => [
        'full' => [
            // Background Color
            'bg-transparent', 'bg-white', 'bg-black',
            'bg-gray-50', 'bg-gray-100', 'bg-gray-200', 'bg-gray-300', 'bg-gray-400', 'bg-gray-500',
            'bg-gray-600', 'bg-gray-700', 'bg-gray-800', 'bg-gray-900',
            'bg-primary-50', 'bg-primary-100', 'bg-primary-200', 'bg-primary-300', 'bg-primary-400',
            'bg-primary-500', 'bg-primary-600', 'bg-primary-700', 'bg-primary-800', 'bg-primary-900',
            
            // Gradient
            'bg-gradient-to-r', 'bg-gradient-to-l', 'bg-gradient-to-t', 'bg-gradient-to-b',
            'bg-gradient-to-tr', 'bg-gradient-to-tl', 'bg-gradient-to-br', 'bg-gradient-to-bl',
            'from-transparent', 'from-primary-500', 'from-primary-600', 'from-gray-900',
            'via-transparent', 'via-primary-500', 'via-primary-600',
            'to-transparent', 'to-primary-500', 'to-primary-600', 'to-gray-900',
        ],
        'mid' => [
            'bg-white', 'bg-gray-50', 'bg-gray-100', 'bg-gray-900',
            'bg-primary-600', 'bg-primary-700',
            'bg-gradient-to-r', 'bg-gradient-to-b',
            'from-primary-600', 'to-primary-700',
        ],
        'low' => [
            'bg-white', 'bg-gray-100', 'bg-primary-600',
        ],
    ],

    'borders' => [
        'full' => [
            // Border Width
            'border', 'border-0', 'border-2', 'border-4', 'border-8',
            'border-t', 'border-r', 'border-b', 'border-l',
            'border-t-0', 'border-r-0', 'border-b-0', 'border-l-0',
            
            // Border Color
            'border-transparent', 'border-white', 'border-black',
            'border-gray-100', 'border-gray-200', 'border-gray-300', 'border-gray-400', 'border-gray-500',
            'border-gray-600', 'border-gray-700', 'border-gray-800', 'border-gray-900',
            'border-primary-500', 'border-primary-600', 'border-primary-700',
            
            // Border Radius
            'rounded-none', 'rounded-sm', 'rounded', 'rounded-md', 'rounded-lg', 'rounded-xl', 
            'rounded-2xl', 'rounded-3xl', 'rounded-full',
            'rounded-t-none', 'rounded-t-sm', 'rounded-t', 'rounded-t-md', 'rounded-t-lg', 'rounded-t-xl',
            'rounded-r-none', 'rounded-r-sm', 'rounded-r', 'rounded-r-md', 'rounded-r-lg', 'rounded-r-xl',
            'rounded-b-none', 'rounded-b-sm', 'rounded-b', 'rounded-b-md', 'rounded-b-lg', 'rounded-b-xl',
            'rounded-l-none', 'rounded-l-sm', 'rounded-l', 'rounded-l-md', 'rounded-l-lg', 'rounded-l-xl',
        ],
        'mid' => [
            'border', 'border-0', 'border-2',
            'border-gray-200', 'border-gray-300', 'border-primary-600',
            'rounded', 'rounded-md', 'rounded-lg', 'rounded-xl', 'rounded-full',
        ],
        'low' => [
            'border', 'border-gray-200', 'rounded', 'rounded-lg',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Effects & Interactions
    |--------------------------------------------------------------------------
    */
    'effects' => [
        'full' => [
            // Shadow
            'shadow-none', 'shadow-sm', 'shadow', 'shadow-md', 'shadow-lg', 'shadow-xl', 'shadow-2xl',
            'shadow-inner',
            
            // Opacity
            'opacity-0', 'opacity-5', 'opacity-10', 'opacity-20', 'opacity-25', 'opacity-30', 
            'opacity-40', 'opacity-50', 'opacity-60', 'opacity-70', 'opacity-75', 'opacity-80', 
            'opacity-90', 'opacity-95', 'opacity-100',
            
            // Blur
            'blur-none', 'blur-sm', 'blur', 'blur-md', 'blur-lg', 'blur-xl', 'blur-2xl', 'blur-3xl',
        ],
        'mid' => [
            'shadow-sm', 'shadow', 'shadow-md', 'shadow-lg', 'shadow-xl',
            'opacity-50', 'opacity-75', 'opacity-100',
        ],
        'low' => [
            'shadow', 'shadow-lg', 'opacity-100',
        ],
    ],

    'transitions' => [
        'full' => [
            'transition', 'transition-none', 'transition-all', 'transition-colors', 
            'transition-opacity', 'transition-shadow', 'transition-transform',
            'duration-75', 'duration-100', 'duration-150', 'duration-200', 'duration-300', 
            'duration-500', 'duration-700', 'duration-1000',
            'ease-linear', 'ease-in', 'ease-out', 'ease-in-out',
            'delay-75', 'delay-100', 'delay-150', 'delay-200', 'delay-300', 'delay-500', 'delay-700', 'delay-1000',
        ],
        'mid' => [
            'transition', 'transition-all', 'transition-colors',
            'duration-150', 'duration-200', 'duration-300',
            'ease-in-out',
        ],
        'low' => [
            'transition', 'duration-200',
        ],
    ],

    'hover' => [
        'full' => [
            'hover:bg-primary-700', 'hover:bg-primary-800', 'hover:bg-gray-50', 'hover:bg-gray-100',
            'hover:text-primary-600', 'hover:text-primary-700', 'hover:text-white',
            'hover:border-primary-600', 'hover:border-primary-700',
            'hover:shadow-lg', 'hover:shadow-xl', 'hover:shadow-2xl',
            'hover:opacity-80', 'hover:opacity-90', 'hover:opacity-100',
            'hover:scale-105', 'hover:scale-110', 'hover:-translate-y-1',
            'hover:underline', 'hover:no-underline',
        ],
        'mid' => [
            'hover:bg-primary-700', 'hover:bg-gray-100',
            'hover:text-primary-700',
            'hover:shadow-lg',
            'hover:scale-105',
        ],
        'low' => [
            'hover:bg-primary-700', 'hover:shadow-lg',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Positioning & Display
    |--------------------------------------------------------------------------
    */
    'positioning' => [
        'full' => [
            'static', 'fixed', 'absolute', 'relative', 'sticky',
            'inset-0', 'inset-x-0', 'inset-y-0',
            'top-0', 'right-0', 'bottom-0', 'left-0',
            'top-1', 'top-2', 'top-4', 'top-8', 'top-16',
            'z-0', 'z-10', 'z-20', 'z-30', 'z-40', 'z-50',
        ],
        'mid' => [
            'relative', 'absolute', 'inset-0',
            'top-0', 'right-0', 'bottom-0', 'left-0',
            'z-10', 'z-20', 'z-50',
        ],
        'low' => [
            'relative', 'absolute', 'z-10',
        ],
    ],

    'display' => [
        'full' => [
            'block', 'inline-block', 'inline', 'hidden',
            'overflow-hidden', 'overflow-visible', 'overflow-auto', 'overflow-scroll',
            'overflow-x-auto', 'overflow-y-auto',
        ],
        'mid' => [
            'block', 'inline-block', 'hidden',
            'overflow-hidden', 'overflow-auto',
        ],
        'low' => [
            'block', 'hidden', 'overflow-hidden',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Responsive Breakpoints
    |--------------------------------------------------------------------------
    */
    'responsive' => [
        'full' => [
            // Commonly used responsive variants
            'sm:block', 'md:block', 'lg:block', 'xl:block',
            'sm:hidden', 'md:hidden', 'lg:hidden', 'xl:hidden',
            'sm:flex', 'md:flex', 'lg:flex', 'xl:flex',
            'sm:grid', 'md:grid', 'lg:grid', 'xl:grid',
            'sm:grid-cols-1', 'sm:grid-cols-2', 'sm:grid-cols-3',
            'md:grid-cols-2', 'md:grid-cols-3', 'md:grid-cols-4',
            'lg:grid-cols-3', 'lg:grid-cols-4', 'lg:grid-cols-6',
            'xl:grid-cols-4', 'xl:grid-cols-6',
            'sm:text-base', 'md:text-lg', 'lg:text-xl', 'xl:text-2xl',
            'sm:text-xl', 'md:text-2xl', 'lg:text-3xl', 'xl:text-4xl',
            'md:text-4xl', 'lg:text-5xl', 'xl:text-6xl',
            'sm:px-4', 'md:px-6', 'lg:px-8', 'xl:px-12',
            'sm:py-8', 'md:py-12', 'lg:py-16', 'xl:py-20',
        ],
        'mid' => [
            'md:block', 'lg:block',
            'md:hidden', 'lg:hidden',
            'md:flex', 'lg:flex',
            'md:grid-cols-2', 'lg:grid-cols-3',
            'md:text-lg', 'lg:text-xl',
            'md:px-6', 'lg:px-8',
        ],
        'low' => [
            'md:flex', 'lg:grid-cols-2',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Component-Specific Classes
    |--------------------------------------------------------------------------
    */
    'buttons' => [
        'full' => [
            'inline-flex', 'items-center', 'justify-center',
            'px-4', 'px-5', 'px-6', 'px-8', 'py-2', 'py-3', 'py-4',
            'text-sm', 'text-base', 'text-lg', 'font-medium', 'font-semibold',
            'rounded', 'rounded-md', 'rounded-lg', 'rounded-full',
            'bg-primary-600', 'bg-primary-700', 'bg-white', 'bg-gray-900',
            'text-white', 'text-gray-900', 'text-primary-600',
            'border', 'border-2', 'border-transparent', 'border-gray-300', 'border-primary-600',
            'shadow-sm', 'shadow', 'shadow-md',
            'hover:bg-primary-700', 'hover:bg-primary-800', 'hover:bg-gray-50',
            'focus:outline-none', 'focus:ring-2', 'focus:ring-primary-500', 'focus:ring-offset-2',
            'disabled:opacity-50', 'disabled:cursor-not-allowed',
        ],
        'mid' => [
            'inline-flex', 'items-center', 'justify-center',
            'px-6', 'py-3', 'text-base', 'font-medium',
            'rounded-lg', 'bg-primary-600', 'text-white',
            'hover:bg-primary-700', 'shadow',
        ],
        'low' => [
            'px-6', 'py-3', 'rounded-lg', 'bg-primary-600', 'text-white',
        ],
    ],

    'forms' => [
        'full' => [
            'block', 'w-full',
            'px-3', 'px-4', 'py-2', 'py-3',
            'text-base', 'text-gray-900',
            'border', 'border-gray-300', 'rounded-md', 'rounded-lg',
            'placeholder-gray-400', 'placeholder-gray-500',
            'focus:outline-none', 'focus:ring-2', 'focus:ring-primary-500', 'focus:border-primary-500',
            'disabled:bg-gray-100', 'disabled:cursor-not-allowed',
        ],
        'mid' => [
            'block', 'w-full', 'px-4', 'py-2',
            'border', 'border-gray-300', 'rounded-lg',
            'focus:ring-2', 'focus:ring-primary-500',
        ],
        'low' => [
            'w-full', 'px-4', 'py-2', 'border', 'rounded-lg',
        ],
    ],
];
