<?php
$wires = $wires ?? [];
$dots = $dots ?? [];
?>
<div class="circuit-thumb">
  <div class="dots"></div>
  <svg viewBox="0 0 300 136" width="100%" height="136" aria-hidden="true">
    <?php foreach ($wires as $w): ?>
      <path d="<?= e($w) ?>" fill="none" stroke="oklch(0.75 0.09 195)" stroke-width="1.5"></path>
    <?php endforeach; ?>
    <?php foreach ($dots as $d): ?>
      <rect x="<?= e((string) $d[0]) ?>" y="<?= e((string) $d[1]) ?>" width="<?= e((string) $d[2]) ?>" height="16" rx="5" fill="#fff" stroke="<?= e($d[3]) ?>" stroke-width="1.6"></rect>
    <?php endforeach; ?>
  </svg>
</div>
