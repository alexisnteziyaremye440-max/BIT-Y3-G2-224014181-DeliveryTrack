document.addEventListener('DOMContentLoaded', () => {
  // Mobile nav toggle
  document.getElementById('menu')?.addEventListener('click', () => document.getElementById('links').classList.toggle('open'));

  // Confirm destructive actions
  document.querySelectorAll('form[data-confirm]').forEach(f =>
    f.addEventListener('submit', ev => { if (!confirm(f.dataset.confirm)) ev.preventDefault(); }));

  // Client-side validation (HTML5 constraints + friendly messages). The server re-validates everything.
  const show = (el, msg) => {
    const s = el.parentElement.querySelector('.err');
    if (s) s.textContent = msg;
    el.classList.toggle('bad', !!msg);
  };
  const check = el => {
    el.setCustomValidity('');
    if (el.name === 'password_confirm' && el.form.elements.password && el.value !== el.form.elements.password.value)
      el.setCustomValidity('Passwords do not match.');
    show(el, el.validity.valid ? '' : (el.dataset.msg && el.validity.patternMismatch ? el.dataset.msg : el.validationMessage));
    return el.validity.valid;
  };
  document.querySelectorAll('form[data-validate]').forEach(f => {
    const fields = () => [...f.querySelectorAll('input:not([type=hidden]),select,textarea')];
    f.addEventListener('submit', ev => { let ok = true; fields().forEach(el => { if (!check(el)) ok = false; }); if (!ok) ev.preventDefault(); });
    f.addEventListener('input', ev => { if (ev.target.matches('input,select,textarea')) check(ev.target); });
  });

  // Live search via Fetch + JSON API (rows built with textContent => XSS-safe)
  const q = document.getElementById('q'), table = document.getElementById('orders');
  if (q && table) {
    const tb = table.tBodies[0], role = table.dataset.role, msg = document.getElementById('status');
    let timer, ctl;
    const td = (txt, colSpan) => { const c = document.createElement('td'); c.textContent = txt; if (colSpan) c.colSpan = colSpan; return c; };
    const link = (href, txt) => { const a = document.createElement('a'); a.href = href; a.textContent = txt; return a; };
    const render = rows => {
      tb.replaceChildren();
      if (!rows.length) tb.insertRow().append(td('No orders found.', 7));
      rows.forEach(o => {
        const tr = tb.insertRow();
        const st = document.createElement('td'), b = document.createElement('span');
        b.className = 'badge s-' + o.status; b.textContent = o.status.replace('_', ' '); st.append(b);
        const act = document.createElement('td');
        act.append(link('index.php?r=orders/show&id=' + encodeURIComponent(o.order_id), 'Track'));
        if (role === 'admin') act.append(' ', link('index.php?r=orders/edit&id=' + encodeURIComponent(o.order_id), 'Edit'));
        tr.append(td('#' + o.order_id), td(o.full_name), td(o.details), td(Number(o.total).toFixed(2)), st, td(o.rider || '—'), act);
      });
    };
    q.addEventListener('input', () => {
      clearTimeout(timer);
      timer = setTimeout(async () => {
        ctl?.abort(); ctl = new AbortController();
        try {
          const r = await fetch('index.php?r=api/orders&q=' + encodeURIComponent(q.value.trim()), { signal: ctl.signal, headers: { Accept: 'application/json' } });
          if (!r.ok) throw new Error('HTTP ' + r.status);
          const j = await r.json(); render(j.data); msg.textContent = j.count + ' result(s)';
        } catch (e) { if (e.name !== 'AbortError') msg.textContent = 'Search failed — please retry.'; }
      }, 300);
    });
  }
});
