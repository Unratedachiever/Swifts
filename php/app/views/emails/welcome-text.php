<?php
/**
 * Plain-text alternative for the welcome email.
 *
 * @var string $first_name
 * @var string $account_url
 * @var string $track_url
 */
?>
SwiftShip Logistics — welcome aboard, <?= $first_name ?>.

Your SwiftShip account is ready. Book a shipment in seconds, follow every scan
in real time, and keep addresses, billing, and delivery notices in one place.

What you can do now:
  * Track anything — live status, scans, and proof of delivery for every SWF number.
  * Save addresses — saved senders, recipients, and pickup preferences.
  * Get notified — delay and delivery notices land on your dashboard.

Open your account: <?= $account_url ?>

Track a package: <?= $track_url ?>

Need a hand? Call <?= BRAND_PHONE ?> — <?= BRAND_HOURS ?>.

— The SwiftShip team

SwiftShip Logistics
<?= BRAND_ADDRESS ?>

You're receiving this because an account was created with this email at
<?= $account_url ?>.
