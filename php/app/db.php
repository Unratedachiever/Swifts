<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Shared PDO (SQLite) connection. Creates the schema and seeds the reference
 * data (facilities + demo shipments) on first use, so a fresh volume boots
 * straight into a working app.
 */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $path = db_path();
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException("Cannot create database directory: {$dir}");
    }

    $pdo = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('PRAGMA foreign_keys = ON');

    db_migrate($pdo);
    return $pdo;
}

function db_migrate(PDO $pdo): void
{
    $pdo->exec(<<<'SQL'
        create table if not exists users (
            id integer primary key autoincrement,
            name text not null,
            email text not null unique,
            password_hash text not null,
            is_staff integer not null default 0,
            created_at text not null default (datetime('now'))
        );

        create table if not exists sessions (
            id text primary key,
            user_id integer not null references users(id) on delete cascade,
            created_at text not null default (datetime('now')),
            expires_at text not null
        );

        create table if not exists facilities (
            id text primary key,
            name text not null,
            type text not null,
            street text not null,
            city text not null,
            state text not null,
            postal_code text not null,
            country text not null,
            lat real not null,
            lng real not null,
            phone text not null,
            hours text not null,
            services text not null
        );

        create table if not exists shipments (
            id integer primary key autoincrement,
            tracking_number text not null unique,
            user_id integer references users(id) on delete set null,
            service_code text not null,
            status text not null,
            origin text not null,
            destination text not null,
            weight_lb real not null default 0,
            estimated_delivery text,
            is_demo integer not null default 0,
            created_at text not null default (datetime('now'))
        );

        create table if not exists shipment_events (
            id integer primary key autoincrement,
            shipment_id integer not null references shipments(id) on delete cascade,
            status text not null,
            location text not null,
            note text not null,
            occurred_at text not null
        );

        create table if not exists contact_messages (
            id integer primary key autoincrement,
            name text not null,
            email text not null,
            topic text not null,
            message text not null,
            created_at text not null default (datetime('now'))
        );
    SQL);

    db_seed_facilities($pdo);
    db_seed_demo_shipments($pdo);
}

/** Facilities ported from migrations/0003_seed.sql. */
function db_seed_facilities(PDO $pdo): void
{
    $count = (int) $pdo->query('select count(*) from facilities')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $rows = [
        ['loc_la_hub', 'Los Angeles Gateway Hub', 'distribution', '4100 E Bandini Blvd', 'Los Angeles', 'CA', '90040', 'United States', 34.005, -118.16, '1-800-794-3844', 'Open 24 hours', 'Drop-off, pickup, freight, holds'],
        ['loc_la_sc', 'Downtown LA Service Center', 'service', '888 S Figueroa St', 'Los Angeles', 'CA', '90017', 'United States', 34.047, -118.261, '1-800-794-3844', 'Mon–Fri 8:00–19:00, Sat 9:00–14:00', 'Packaging, drop-off, prints'],
        ['loc_ny_hub', 'New York Metro Hub', 'distribution', '1200 Randolph Ave', 'New York', 'NY', '07114', 'United States', 40.73, -74.15, '1-800-794-3844', 'Open 24 hours', 'Drop-off, pickup, freight'],
        ['loc_ny_pk', 'Manhattan Pickup Point', 'pickup', '350 5th Ave, Suite 210', 'New York', 'NY', '10118', 'United States', 40.7484, -73.9857, '1-800-794-3844', 'Mon–Fri 7:30–20:00, Sat 9:00–16:00', 'Drop-off, holds, packing'],
        ['loc_chi_hub', 'Chicago Sortation Center', 'warehouse', '7100 S Cicero Ave', 'Chicago', 'IL', '60638', 'United States', 41.764, -87.743, '1-800-794-3844', 'Open 24 hours', 'Freight, drop-off'],
        ['loc_dal_hub', 'Dallas Inland Hub', 'distribution', '2400 Valley View Ln', 'Dallas', 'TX', '75234', 'United States', 32.93, -96.89, '1-800-794-3844', 'Open 24 hours', 'Drop-off, freight, customs'],
        ['loc_phx', 'Phoenix Desert Gateway', 'warehouse', '3800 E Washington St', 'Phoenix', 'AZ', '85034', 'United States', 33.448, -112.0, '1-800-794-3844', 'Mon–Sun 6:00–22:00', 'Drop-off, pickup'],
        ['loc_mia', 'Miami International Gateway', 'distribution', '1800 NW 89th Ave', 'Miami', 'FL', '33172', 'United States', 25.79, -80.33, '1-800-794-3844', 'Open 24 hours', 'International, customs, freight'],
        ['loc_sea', 'Seattle Cascade Hub', 'warehouse', '640 S 96th St', 'Seattle', 'WA', '98108', 'United States', 47.52, -122.33, '1-800-794-3844', 'Mon–Sat 6:00–21:00', 'Drop-off, pickup'],
        ['loc_atl', 'Atlanta Peachtree Hub', 'distribution', '2200 Sullivan Rd', 'Atlanta', 'GA', '30337', 'United States', 33.66, -84.43, '1-800-794-3844', 'Open 24 hours', 'Drop-off, freight'],
        ['loc_den', 'Denver High Plains Center', 'warehouse', '4900 Smith Rd', 'Denver', 'CO', '80216', 'United States', 39.78, -104.96, '1-800-794-3844', 'Mon–Sat 7:00–20:00', 'Drop-off, pickup'],
        ['loc_bos', 'Boston Harbor Service Center', 'service', '1 Seaport Blvd', 'Boston', 'MA', '02210', 'United States', 42.35, -71.04, '1-800-794-3844', 'Mon–Fri 8:00–18:30', 'Packaging, drop-off'],
        ['loc_sf', 'San Francisco Bay Drop-off', 'pickup', '301 Mission St', 'San Francisco', 'CA', '94105', 'United States', 37.790, -122.396, '1-800-794-3844', 'Mon–Fri 8:00–19:00, Sat 10:00–15:00', 'Drop-off, holds'],
        ['loc_mem', 'Memphis Super Hub', 'distribution', '3875 Airways Blvd', 'Memphis', 'TN', '38116', 'United States', 35.05, -89.98, '1-800-794-3844', 'Open 24 hours', 'National sort, freight'],
        ['loc_lon', 'London Heathrow Gateway', 'distribution', 'Unit 4, Polar Park', 'London', 'England', 'TW6', 'United Kingdom', 51.47, -0.45, '+44 20 7946 0100', 'Open 24 hours', 'International, customs'],
        ['loc_tor', 'Toronto Pearson Center', 'warehouse', '6300 Silver Dart Dr', 'Toronto', 'ON', 'L5P', 'Canada', 43.68, -79.63, '1-800-794-3844', 'Mon–Sun 6:00–23:00', 'Cross-border, drop-off'],
    ];

    $stmt = $pdo->prepare(
        'insert into facilities (id, name, type, street, city, state, postal_code, country, lat, lng, phone, hours, services)
         values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($rows as $row) {
        $stmt->execute($row);
    }
}

/** Two demo shipments so tracking is useful before the booking flow is ported. */
function db_seed_demo_shipments(PDO $pdo): void
{
    $count = (int) $pdo->query('select count(*) from shipments')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $demo = [
        [
            'tracking' => 'SWF100450231',
            'service' => 'standard',
            'status' => 'in_transit',
            'origin' => 'Los Angeles, CA',
            'destination' => 'New York, NY',
            'weight' => 12.4,
            'eta' => date('Y-m-d', strtotime('+2 days')),
            'events' => [
                ['created', 'Los Angeles, CA', 'Shipping label created', '-3 days'],
                ['picked_up', 'Los Angeles, CA', 'Parcel accepted at Gateway Hub', '-2 days'],
                ['in_transit', 'Memphis, TN', 'Departed super hub', '-1 day'],
            ],
        ],
        [
            'tracking' => 'SWF100450875',
            'service' => 'express',
            'status' => 'delivered',
            'origin' => 'Chicago, IL',
            'destination' => 'Austin, TX',
            'weight' => 4.2,
            'eta' => date('Y-m-d', strtotime('-1 day')),
            'events' => [
                ['created', 'Chicago, IL', 'Shipping label created', '-4 days'],
                ['picked_up', 'Chicago, IL', 'Picked up from sender', '-4 days'],
                ['in_transit', 'Dallas, TX', 'Arrived at Dallas Inland Hub', '-3 days'],
                ['out_for_delivery', 'Austin, TX', 'On vehicle for delivery', '-2 days'],
                ['delivered', 'Austin, TX', 'Delivered — signed by R. Alvarez', '-1 day'],
            ],
        ],
    ];

    $insertShipment = $pdo->prepare(
        'insert into shipments (tracking_number, service_code, status, origin, destination, weight_lb, estimated_delivery, is_demo, created_at)
         values (?, ?, ?, ?, ?, ?, ?, 1, ?)'
    );
    $insertEvent = $pdo->prepare(
        'insert into shipment_events (shipment_id, status, location, note, occurred_at)
         values (?, ?, ?, ?, ?)'
    );

    // Timestamps are computed in PHP: mixing "?" and ":named" placeholders in
    // one statement leaves the named ones unbound on the SQLite driver.
    $stamp = static fn (string $when): string => date('Y-m-d H:i:s', strtotime($when));

    foreach ($demo as $shipment) {
        $insertShipment->execute([
            $shipment['tracking'],
            $shipment['service'],
            $shipment['status'],
            $shipment['origin'],
            $shipment['destination'],
            $shipment['weight'],
            $shipment['eta'],
            $stamp($shipment['events'][0][3]),
        ]);
        $id = (int) $pdo->lastInsertId();
        foreach ($shipment['events'] as [$status, $location, $note, $when]) {
            $insertEvent->execute([$id, $status, $location, $note, $stamp($when)]);
        }
    }
}

/** Find a shipment (with its events) by tracking number. */
function find_shipment(string $trackingNumber): ?array
{
    $pdo = db();
    $stmt = $pdo->prepare('select * from shipments where upper(tracking_number) = upper(?)');
    $stmt->execute([trim($trackingNumber)]);
    $shipment = $stmt->fetch();
    if (!$shipment) {
        return null;
    }

    $events = $pdo->prepare(
        'select * from shipment_events where shipment_id = ? order by occurred_at asc, id asc'
    );
    $events->execute([$shipment['id']]);
    $shipment['events'] = $events->fetchAll();

    return $shipment;
}
