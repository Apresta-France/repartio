(() => {
  const root = document.querySelector('[data-showcase]');
  if (!root) return;

  const builder = root.querySelector('[data-builder]');
  const noteKicker = root.querySelector('[data-showcase-kicker]');
  const noteTitle = root.querySelector('[data-showcase-title]');
  const noteText = root.querySelector('[data-showcase-text]');
  const playBtns = [...document.querySelectorAll('[data-showcase-play]')];
  const stopBtn = root.querySelector('[data-showcase-stop]');
  let steps = [];
  try {
    steps = JSON.parse(root.querySelector('[data-showcase-steps]')?.textContent || '[]');
  } catch {
    steps = [];
  }
  if (!steps.length) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const STEP_MS = 5600;
  let index = 0;
  let playing = false;
  let timer = 0;
  let readyTries = 0;

  const tour = () => builder?.repartioTour || null;

  const setNote = (step) => {
    if (noteKicker) noteKicker.textContent = step.kicker || '';
    if (noteTitle) noteTitle.textContent = step.title || '';
    if (noteText) noteText.textContent = step.text || '';
  };

  const markSteps = () => {
    root.querySelectorAll('[data-showcase-step]').forEach((btn) => {
      btn.classList.toggle('is-on', Number(btn.dataset.showcaseStep) === index);
    });
  };

  const apply = (i, { animate = true } = {}) => {
    const step = steps[i];
    if (!step) return;
    index = i;
    setNote(step);
    markSteps();
    const api = tour();
    if (!api) return;
    const ids = step.nodes || [];
    api.highlight(ids);
    if (step.month != null) api.setMonth(step.month);
    else api.setMonth(60);
    if (!animate || reduced) {
      if (ids.length) api.focus(ids);
      else api.fit();
      return;
    }
    if (ids.length) api.focus(ids);
    else api.fit();
  };

  const stop = () => {
    playing = false;
    root.classList.remove('is-playing');
    window.clearInterval(timer);
    timer = 0;
    playBtns.forEach((btn) => {
      btn.hidden = false;
      btn.textContent = 'Lancer la démo';
    });
    if (stopBtn) stopBtn.hidden = true;
  };

  const play = () => {
    const api = tour();
    if (!api) return;
    if (playing) {
      stop();
      return;
    }
    playing = true;
    root.classList.add('is-playing');
    root.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'start' });
    playBtns.forEach((btn) => { btn.hidden = true; });
    if (stopBtn) stopBtn.hidden = false;
    if (index >= steps.length - 1) index = 0;
    apply(index);
    if (reduced) {
      stop();
      return;
    }
    window.clearInterval(timer);
    timer = window.setInterval(() => {
      if (index >= steps.length - 1) {
        stop();
        return;
      }
      apply(index + 1);
    }, STEP_MS);
  };

  const go = (i) => {
    stop();
    apply(i);
  };

  root.querySelectorAll('[data-showcase-step]').forEach((btn) => {
    btn.addEventListener('click', () => go(Number(btn.dataset.showcaseStep)));
  });
  playBtns.forEach((btn) => btn.addEventListener('click', play));
  stopBtn?.addEventListener('click', () => {
    stop();
    apply(index, { animate: false });
  });

  const boot = () => {
    const api = tour();
    if (!api) {
      if (readyTries++ < 80) requestAnimationFrame(boot);
      return;
    }
    requestAnimationFrame(() => {
      api.fit();
      apply(0, { animate: false });
    });
  };

  boot();
  window.addEventListener('resize', () => {
    if (playing) return;
    const api = tour();
    if (!api) return;
    const ids = steps[index]?.nodes || [];
    if (ids.length) api.focus(ids);
    else api.fit();
  });
})();
