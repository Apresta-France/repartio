<p>Bonjour,</p>
<p><?= e($owner_name) ?> vous invite à agir sur son compte <strong>repartio</strong><?php if (!empty($circuits)): ?>, sur <?= count($circuits) === 1 ? 'ce circuit' : 'ces circuits' ?><?php endif ?>.</p>
<?php if (!empty($circuits)): ?>
<ul>
  <?php foreach ($circuits as $circuit): ?>
    <li><?= e($circuit['project_name']) ?> — <?= e(\App\Models\Access::LABELS[$circuit['permission']] ?? $circuit['permission']) ?></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
<p>Vous aurez besoin d’un compte avec cette adresse e-mail pour accepter.</p>
<p><a href="<?= e($invite_url) ?>" style="display:inline-block;padding:12px 18px;background:#e07a32;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;">Voir l’invitation</a></p>
<p style="font-size:12px;color:#6b7084;">Si le bouton ne fonctionne pas : <?= e($invite_url) ?></p>
<p style="font-size:12px;color:#6b7084;">Ce lien expire dans 14 jours.</p>
