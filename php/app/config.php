<?php
declare(strict_types=1);

/**
 * Configuration for the PHP port of SwiftShip Logistics.
 *
 * Everything environment-specific comes from env vars (see
 * docker-compose.base44.yml / /run/base44/app.env) so no resolved host or
 * credential is ever hardcoded here.
 */

function env(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    if ($value === false || trim($value) === '') {
        return $default;
    }
    return trim($value);
}

const BRAND_NAME = 'SwiftShip Logistics';
const BRAND_PHONE = '1-800-SWIFT-SHIP';
const BRAND_HOURS = 'Mon–Fri 6:00–21:00 PT · Sat 8:00–16:00 PT';
const BRAND_ADDRESS = '4100 E Bandini Blvd, Los Angeles, CA 90040';

/** Public base URL of the app (used in emails so links/logo work from an inbox). */
function app_url(): string
{
    return rtrim(env('APP_URL', 'http://localhost:3000') ?? '', '/');
}

/** SQLite file — a mounted volume in Docker, a local file otherwise. */
function db_path(): string
{
    return env('DB_PATH', dirname(__DIR__) . '/data/app.sqlite') ?? '';
}

/**
 * The four service products, ported from src/lib/cities.ts.
 */
const SERVICES = [
    'standard' => [
        'code' => 'standard',
        'name' => 'SwiftShip Ground',
        'tagline' => 'Reliable day-definite delivery',
        'description' => 'Economy ground service across the continental U.S. with full tracking.',
        'days_min' => 4,
        'days_max' => 7,
        'base' => 9.85,
        'per_lb' => 0.72,
        'transit' => '4–7 business days',
    ],
    'express' => [
        'code' => 'express',
        'name' => 'SwiftShip Express',
        'tagline' => '2–3 business days',
        'description' => 'Priority air and ground for time-sensitive parcels.',
        'days_min' => 2,
        'days_max' => 3,
        'base' => 18.40,
        'per_lb' => 1.15,
        'transit' => '2–3 business days',
    ],
    'overnight' => [
        'code' => 'overnight',
        'name' => 'SwiftShip Overnight',
        'tagline' => 'Next-business-day by 10:30 a.m.',
        'description' => 'Overnight air with morning delivery windows in most metros.',
        'days_min' => 1,
        'days_max' => 1,
        'base' => 32.50,
        'per_lb' => 1.85,
        'transit' => '1 business day',
    ],
    'international' => [
        'code' => 'international',
        'name' => 'SwiftShip Worldwide',
        'tagline' => 'Cross-border with customs support',
        'description' => 'International express to 220+ countries, including brokerage.',
        'days_min' => 5,
        'days_max' => 10,
        'base' => 28.90,
        'per_lb' => 2.10,
        'transit' => '5–10 business days',
    ],
];

/** Human label for a shipment status code. */
const STATUS_LABELS = [
    'created' => 'Label created',
    'picked_up' => 'Picked up',
    'in_transit' => 'In transit',
    'out_for_delivery' => 'Out for delivery',
    'delivered' => 'Delivered',
    'exception' => 'Exception',
];
