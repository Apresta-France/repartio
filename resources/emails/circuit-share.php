<p>Bonjour,</p>
<p><?= e($owner_name) ?> vous partage un circuit de revenus<?= $title ? ' : <strong>' . e($title) . '</strong>' : '' ?>.</p>
<?php if (!empty($note)): ?>
<p style="padding:12px 14px;background:#f4f5f8;border-radius:10px;"><?= nl2br(e($note)) ?></p>
<?php endif; ?>
<p>L’aperçu est en lecture seule — aucun compte n’est nécessaire.</p>
<p><a href="<?= e($preview_url) ?>" style="display:inline-block;padding:12px 18px;background:#e07a32;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;">Voir le circuit</a></p>
<p style="font-size:12px;color:#6b7084;">Si le bouton ne fonctionne pas : <?= e($preview_url) ?></p>
