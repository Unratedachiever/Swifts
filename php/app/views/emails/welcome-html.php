<?php
/**
 * Branded welcome email (table-based, inline styles).
 *
 * @var string $first_name
 * @var string $name
 * @var string $logo_url
 * @var string $account_url
 * @var string $track_url
 * @var string $home_url
 */
$font = "'Outfit', -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
$display = "'Syne', 'Outfit', -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Welcome to SwiftShip</title>
<!--[if mso]><style>body,table,td{font-family:Arial,Helvetica,sans-serif !important;}</style><![endif]-->
</head>
<body style="margin:0;padding:0;background:#f3f0e8;">
<div style="display:none;font-size:1px;color:#f3f0e8;max-height:0;overflow:hidden;">Your SwiftShip account is ready — track shipments, save addresses, and get delivery notices.</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f3f0e8;padding:28px 12px;">
  <tr>
    <td align="center">
      <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px;max-width:600px;background:#fbf8f1;border:1px solid #d8d2c4;border-radius:14px;overflow:hidden;">

        <!-- Header -->
        <tr>
          <td style="background:#0c1424;padding:26px 32px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="52" valign="middle" style="padding-right:14px;">
                  <img src="<?= e($logo_url) ?>" width="44" height="44" alt="SwiftShip Logistics" style="display:block;border:0;border-radius:10px;">
                </td>
                <td valign="middle">
                  <div style="font-family:<?= $display ?>;font-size:19px;font-weight:700;color:#f3f0e8;letter-spacing:-0.3px;line-height:1.1;">SwiftShip</div>
                  <div style="font-family:<?= $font ?>;font-size:10px;font-weight:600;color:#9fb3bd;letter-spacing:2.4px;text-transform:uppercase;padding-top:3px;">Logistics</div>
                </td>
                <td align="right" valign="middle" style="font-family:<?= $font ?>;font-size:12px;color:#9fb3bd;">Welcome aboard</td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Headline -->
        <tr>
          <td style="padding:34px 32px 8px 32px;">
            <p style="margin:0 0 10px 0;font-family:<?= $font ?>;font-size:11px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;color:#1f6f7c;">Account confirmed</p>
            <h1 style="margin:0;font-family:<?= $display ?>;font-size:28px;line-height:1.2;font-weight:700;color:#0c1424;letter-spacing:-0.6px;">You're in, <?= e($first_name) ?>.</h1>
            <p style="margin:14px 0 0 0;font-family:<?= $font ?>;font-size:15px;line-height:1.65;color:#3b464f;">
              Your SwiftShip account is ready. Book a shipment in seconds, follow every scan in real time, and keep addresses, billing, and delivery notices in one place.
            </p>
          </td>
        </tr>

        <!-- Highlights -->
        <tr>
          <td style="padding:22px 32px 4px 32px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2dccd;border-radius:12px;background:#f7f3ea;">
              <tr>
                <td style="padding:18px 20px;">
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td width="26" valign="top" style="font-family:<?= $font ?>;font-size:15px;color:#1f6f7c;line-height:1.5;">&#9679;</td>
                      <td style="font-family:<?= $font ?>;font-size:14px;line-height:1.6;color:#141c28;padding-bottom:10px;"><strong>Track anything</strong> &nbsp;·&nbsp; live status, scans, and proof of delivery for every <span style="color:#1f6f7c;">SWF</span> number.</td>
                    </tr>
                    <tr>
                      <td width="26" valign="top" style="font-family:<?= $font ?>;font-size:15px;color:#1f6f7c;line-height:1.5;">&#9679;</td>
                      <td style="font-family:<?= $font ?>;font-size:14px;line-height:1.6;color:#141c28;padding-bottom:10px;"><strong>Save addresses</strong> &nbsp;·&nbsp; ship faster with saved senders, recipients, and pickup preferences.</td>
                    </tr>
                    <tr>
                      <td width="26" valign="top" style="font-family:<?= $font ?>;font-size:15px;color:#1f6f7c;line-height:1.5;">&#9679;</td>
                      <td style="font-family:<?= $font ?>;font-size:14px;line-height:1.6;color:#141c28;"><strong>Get notified</strong> &nbsp;·&nbsp; delay and delivery notices land on your dashboard, not in a phone queue.</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Button -->
        <tr>
          <td style="padding:26px 32px 6px 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td align="center" bgcolor="#1f6f7c" style="border-radius:8px;">
                  <a href="<?= e($account_url) ?>" style="display:inline-block;padding:14px 26px;font-family:<?= $font ?>;font-size:15px;font-weight:600;color:#f3f0e8;text-decoration:none;">Open your account</a>
                </td>
              </tr>
            </table>
            <p style="margin:12px 0 0 0;font-family:<?= $font ?>;font-size:13px;color:#5c6978;">
              Or <a href="<?= e($track_url) ?>" style="color:#1f6f7c;text-decoration:underline;">track a package</a> with the number on your label.
            </p>
          </td>
        </tr>

        <!-- Sign-off -->
        <tr>
          <td style="padding:24px 32px 30px 32px;">
            <p style="margin:0;font-family:<?= $font ?>;font-size:15px;line-height:1.65;color:#3b464f;">
              Need a hand? Call <strong style="color:#0c1424;"><?= e(BRAND_PHONE) ?></strong> — <?= e(BRAND_HOURS) ?>.<br>
              — The SwiftShip team
            </p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#0c1424;padding:24px 32px;">
            <p style="margin:0 0 6px 0;font-family:<?= $font ?>;font-size:12px;font-weight:600;color:#f3f0e8;">SwiftShip Logistics</p>
            <p style="margin:0;font-family:<?= $font ?>;font-size:12px;line-height:1.7;color:#8d9aa8;">
              <?= e(BRAND_ADDRESS) ?><br>
              You're receiving this because an account was created with this email at
              <a href="<?= e($home_url) ?>" style="color:#7fb6c2;text-decoration:none;">SwiftShip</a>.
            </p>
          </td>
        </tr>

      </table>
      <p style="margin:14px 0 0 0;font-family:<?= $font ?>;font-size:11px;color:#8f8878;">SwiftShip Logistics · demo carrier platform · <?= e(date('Y')) ?></p>
    </td>
  </tr>
</table>
</body>
</html>
