<?php
declare(strict_types=1);

/**
 * Small hand-drawn line icons (24×24, currentColor) so the PHP pages need no
 * icon library — the original React app used lucide-react.
 */
function icon(string $name, string $class = ''): string
{
    $paths = [
        'box' => '<path d="M3.5 7.6 12 3.2l8.5 4.4v8.8L12 20.8l-8.5-4.4z"/><path d="M3.5 7.6 12 12l8.5-4.4M12 12v8.8"/>',
        'package-search' => '<path d="M3.5 7.6 12 3.2l8.5 4.4v8.8L12 20.8l-8.5-4.4z"/><path d="M3.5 7.6 12 12l8.5-4.4M12 12v8.8"/><circle cx="12" cy="12" r="3.2"/>',
        'clock' => '<circle cx="12" cy="12" r="8.2"/><path d="M12 7.6V12l3.2 2.1"/>',
        'pin' => '<path d="M12 21s6.2-5.4 6.2-10.2A6.2 6.2 0 0 0 5.8 10.8C5.8 15.6 12 21 12 21z"/><circle cx="12" cy="10.6" r="2.3"/>',
        'truck' => '<path d="M2.8 6.6h10.3v9.1H2.8z"/><path d="M13.1 9.4h4l2.9 3v3.3h-6.9z"/><circle cx="6.5" cy="18" r="1.7"/><circle cx="16.4" cy="18" r="1.7"/>',
        'building' => '<path d="M4 20.6V5.4L13 3.2v17.4"/><path d="M13 9.6h6.6v11"/><path d="M7 8.6h3M7 12.1h3M7 15.6h3M16 12.6h1.5M16 16h1.5"/>',
        'globe' => '<circle cx="12" cy="12" r="8.2"/><path d="M3.9 12h16.2"/><path d="M12 3.8c2.4 2.7 2.4 13.7 0 16.4-2.4-2.7-2.4-13.7 0-16.4z"/>',
        'shield' => '<path d="M12 3.6 19 6.1v5.5c0 4.3-2.9 7.6-7 8.8-4.1-1.2-7-4.5-7-8.8V6.1z"/><path d="m9.2 12.2 2.1 2.1 3.9-4"/>',
        'scan' => '<path d="M4 8V5.6A1.6 1.6 0 0 1 5.6 4H8M16 4h2.4A1.6 1.6 0 0 1 20 5.6V8M20 16v2.4a1.6 1.6 0 0 1-1.6 1.6H16M8 20H5.6A1.6 1.6 0 0 1 4 18.4V16M4.6 12h14.8"/>',
        'bell' => '<path d="M6.6 17h10.8l-1.3-2.2V10a4.1 4.1 0 1 0-8.2 0v4.8z"/><path d="M10.3 19.4a2 2 0 0 0 3.4 0"/>',
        'search' => '<circle cx="11" cy="11" r="6.2"/><path d="m15.6 15.6 4.2 4.2"/>',
        'swap' => '<path d="M4.5 8.6h13l-3-3M19.5 15.4h-13l3 3"/>',
        'mail' => '<rect x="3.2" y="5.6" width="17.6" height="12.8" rx="2"/><path d="m4.2 7.4 7.8 5.4 7.8-5.4"/>',
        'check' => '<circle cx="12" cy="12" r="8.2"/><path d="m8.6 12.2 2.4 2.4 4.4-4.6"/>',
    ];

    $inner = $paths[$name] ?? $paths['box'];
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" width="20" height="20" fill="none" '
        . 'stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" '
        . 'aria-hidden="true" focusable="false">' . $inner . '</svg>';
}
