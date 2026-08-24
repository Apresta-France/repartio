<section class="section" style="padding-bottom:36px;">
  <span class="eyebrow eyebrow-live">Questions fréquentes</span>
  <h1 class="page-title" style="font-size:46px;max-width:24ch;margin:12px 0;">Tout ce qu’on nous demande, rangé</h1>
  <p class="lede">Si la vôtre n’y est pas, écrivez-nous : la question finit souvent par rejoindre cette page.</p>
  <label class="field" style="max-width:420px;margin-top:12px;">
    <input type="search" data-faq-search placeholder="Chercher une question…">
  </label>
</section>
<section class="section" style="padding-top:0;">
  <?php
  $groups = [];
  foreach ($faq as $row) {
      $groups[$row[0]][] = $row;
  }
  foreach ($groups as $label => $items): ?>
    <div style="margin-bottom:30px;">
      <h2 style="font-size:20px;"><?= e($label) ?></h2>
      <div class="card">
        <?php foreach ($items as $i): ?>
          <div class="faq-item" data-faq-q="<?= e(mb_strtolower($i[1] . ' ' . $i[2])) ?>">
            <button type="button" data-faq style="padding:15px 18px;"><span style="flex:1;"><?= e($i[1]) ?></span><span class="sign">+</span></button>
            <p style="padding:0 18px 18px;"><?= e($i[2]) ?><?php if ($i[4]): ?> <a href="<?= e(url($i[4])) ?>"><?= e($i[3]) ?> →</a><?php endif; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</section>
