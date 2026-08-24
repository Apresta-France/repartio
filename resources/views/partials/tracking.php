<?php
$rvEvents = flash('rv_events');
if (!is_array($rvEvents)) {
    $rvEvents = [];
}
?>
<script>
(function (w) {
  var q = [];
  function stub() { q.push(arguments); }
  if (typeof w.rv !== 'function') w.rv = stub;
  function flush() {
    if (typeof w.rv !== 'function' || w.rv === stub) return false;
    while (q.length) w.rv.apply(null, Array.prototype.slice.call(q.shift()));
    return true;
  }
  var n = 0;
  var t = setInterval(function () {
    if (flush() || ++n > 100) clearInterval(t);
  }, 40);
  var queued = <?= json_encode($rvEvents, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
  queued.forEach(function (ev) {
    var args = [ev.command].concat(ev.args || []);
    w.rv.apply(null, args);
  });
})(window);
</script>
<script async src="https://stat.reinvent.fr/t.js" data-site="RV-161B23"></script>
