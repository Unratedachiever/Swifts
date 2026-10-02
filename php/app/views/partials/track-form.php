<?php
/**
 * Tracking number form (hero or page variant).
 *
 * @var string $variant
 * @var string $initial
 */
$variant = $variant ?? 'hero';
$initial = $initial ?? '';
?>
<form class="form-row" method="get" action="/track" role="search">
  <div class="grow">
    <label class="small muted" for="track-<?= e($variant) ?>" style="display:block;margin-bottom:0.35rem;">Tracking number</label>
    <input id="track-<?= e($variant) ?>" type="search" name="number" value="<?= e($initial) ?>"
           placeholder="Tracking number (SWF…)" autocomplete="off" required>
  </div>
  <div class="fixed" style="align-self:flex-end;">
    <button class="btn btn-lg btn-block" type="submit">Track package</button>
  </div>
</form>
