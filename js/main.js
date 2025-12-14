// ElectroFix labs: main JS
// Lab 3: vanilla JS interactivity + form validation + page generation
// Lab 4: jQuery interactivity (AJAX in devices + admin delete)

/* ===== Helpers ===== */
function toast(msg) {
  const area = document.getElementById('toastArea');
  if (!area) return alert(msg);

  const el = document.createElement('div');
  el.className = 'alert alert-dark border mb-2';
  el.textContent = msg;
  area.appendChild(el);
  setTimeout(() => el.remove(), 2600);
}

/* ===== Lab 3: interactive issues table filter (vanilla) ===== */
function filterIssues() {
  const input = document.getElementById('issueSearch');
  const table = document.getElementById('issuesTable');
  if (!input || !table) return;

  const q = input.value.trim().toLowerCase();
  const rows = table.querySelectorAll('tbody tr');

  rows.forEach(row => {
    const cell = row.cells[0];
    const text = (cell ? cell.textContent : '').toLowerCase();
    row.style.display = (!q || text.includes(q)) ? '' : 'none';
  });
}

/* ===== Lab 3/6: open properties in new window (javascript: URL use-case) ===== */
async function openDeviceInfo(id) {
  try {
    const r = await fetch('api/devices.php?id=' + encodeURIComponent(id));
    const data = await r.json();
    if (!r.ok) throw new Error(data.error || ('HTTP ' + r.status));

    const win = window.open('', '_blank', 'width=760,height=560');
    if (!win) return alert('Pop-up заблокирован браузером.');

    const esc = (s) => String(s)
      .replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;').replaceAll("'", '&#039;');

    win.document.write(`<!doctype html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>${esc(data.name || 'Устройство')}</title>
<style>
  body{font-family:Segoe UI,Arial,sans-serif;background:#0b0c10;color:#f8f9fa;padding:24px}
  .card{background:#111319;border:1px solid #495057;border-radius:12px;padding:18px;max-width:820px}
  .k{color:#adb5bd;width:140px;display:inline-block}
  img{max-width:100%;border-radius:12px;border:1px solid #2b3038;margin:10px 0}
  a{color:#ffc107}
</style></head>
<body>
  <div class="card">
    <h1 style="margin:0 0 10px">${esc(data.name || '')}</h1>
    <p><span class="k">Категория:</span> ${esc(data.category || '')}</p>
    ${data.thumb_path ? `<img src="${esc(data.thumb_path)}" alt="">` : ``}
    <p>${esc(data.full_desc || data.short_desc || '')}</p>
    <p><a href="javascript:window.close()">Закрыть</a></p>
  </div>
</body></html>`);
    win.document.close();
  } catch (e) {
    alert('Ошибка: ' + e.message);
  }
}

/* ===== Devices page: jQuery AJAX to load details (Lab 4 + Lab 6) ===== */
function bindDeviceCards() {
  const list = document.getElementById('devicesList');
  const modalEl = document.getElementById('deviceModal');
  if (!list || !modalEl) return;

  const modal = new bootstrap.Modal(modalEl);
  const mTitle = document.getElementById('deviceModalTitle');
  const mBody  = document.getElementById('deviceModalBody');

  $(list).on('click', '[data-device-id]', function () {
    const id = $(this).data('device-id');

    $.getJSON('api/devices.php', { id })
      .done((data) => {
        mTitle.textContent = data.name || 'Устройство';
        const img = data.thumb_path ? `<img class="img-fluid rounded border mb-3" src="${data.thumb_path}" alt="">` : '';
        mBody.innerHTML = `
          ${img}
          <p class="text-secondary mb-1">${data.category || ''}</p>
          <p>${(data.full_desc || data.short_desc || '').replaceAll('\n', '<br>')}</p>
          <p class="small text-secondary mb-0">ID: ${data.id}</p>
        `;
        modal.show();
      })
      .fail((xhr) => {
        const msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Не удалось загрузить данные';
        toast(msg);
      });
  });
}

/* ===== Guides page: vanilla fetch preview (Lab 6) ===== */
function bindGuidePreview() {
  const list = document.getElementById('guidesList');
  const box  = document.getElementById('guidePreview');
  if (!list || !box) return;

  list.addEventListener('click', async (e) => {
    const a = e.target.closest('[data-guide-id]');
    if (!a) return;
    e.preventDefault();

    const id = a.getAttribute('data-guide-id');
    box.innerHTML = '<div class="text-secondary">Загрузка…</div>';

    try {
      const r = await fetch('api/guides.php?id=' + encodeURIComponent(id));
      const data = await r.json();
      if (!r.ok) throw new Error(data.error || ('HTTP ' + r.status));
      box.innerHTML = `
        <h3 class="h5">${data.title || ''}</h3>
        <p class="text-secondary">${data.summary || ''}</p>
        <div class="mb-2">${(data.content || '').slice(0, 500).replaceAll('\n','<br>')}${(data.content||'').length>500 ? '…' : ''}</div>
        <a class="btn btn-warning btn-sm" href="guide.php?id=${data.id}">Открыть полностью</a>
      `;
    } catch (err) {
      box.innerHTML = '<div class="alert alert-danger mb-0">Ошибка: ' + err.message + '</div>';
    }
  });
}

/* ===== Lab 3: support form validation + DB insert via AJAX + new page ===== */
async function handleSupportForm(e) {
  e.preventDefault();

  const name   = document.getElementById('name')?.value.trim();
  const email  = document.getElementById('email')?.value.trim();
  const device = document.getElementById('device')?.value;
  const problem = document.getElementById('problem')?.value.trim();
  const agree  = document.getElementById('agree')?.checked;

  if (!agree) return toast('Нужно согласиться с обработкой данных (галочка).');
  if (!name || name.length < 2) return toast('Имя слишком короткое.');
  if (!email || !email.includes('@')) return toast('Проверь e-mail.');
  if (!device) return toast('Выбери тип устройства.');
  if (!problem || problem.length < 10) return toast('Опиши проблему чуть подробнее (хотя бы 10 символов).');

  let ticket = null;
  try {
    const r = await fetch('api/questions.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, email, device, problem })
    });
    const data = await r.json().catch(() => ({}));
    if (!r.ok) throw new Error(data.error || ('HTTP ' + r.status));
    ticket = data.ticket || null;
  } catch (err) {
    // даже если сервер не поднят, мы покажем страницу результата — для лабы
    toast('Сервер не ответил: ' + err.message);
  }

  const win = window.open('', '_blank', 'width=760,height=560');
  if (!win) return alert('Pop-up заблокирован браузером.');

  const esc = (s) => String(s)
    .replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;').replaceAll("'", '&#039;');

  win.document.write(`<!doctype html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Заявка отправлена — ElectroFix</title>
<style>
  body{font-family:Segoe UI,Arial,sans-serif;background:#0b0c10;color:#f8f9fa;padding:24px}
  .card{background:#111319;border:1px solid #495057;border-radius:12px;padding:18px;max-width:820px}
  .k{color:#adb5bd;width:170px;display:inline-block}
  .warn{color:#ffc107;margin-top:12px}
  a{color:#ffc107}
  pre{white-space:pre-wrap;background:#0f1116;padding:12px;border-radius:10px;border:1px solid #2b3038}
</style></head>
<body>
  <div class="card">
    <h1 style="margin:0 0 12px">Заявка отправлена ✅</h1>
    ${ticket ? `<p class="warn"><b>Номер заявки:</b> ${esc(ticket)}</p>` : ``}
    <p><span class="k">Имя:</span> ${esc(name)}</p>
    <p><span class="k">E-mail:</span> ${esc(email)}</p>
    <p><span class="k">Устройство:</span> ${esc(device)}</p>
    <p class="k" style="margin-bottom:6px;">Описание проблемы:</p>
    <pre>${esc(problem)}</pre>
    <p class="warn"><strong>Важно:</strong> это учебная форма, а не реальный сервис 🙂</p>
    <p><a href="javascript:window.close()">Закрыть</a></p>
  </div>
</body></html>`);
  win.document.close();

  document.getElementById('support-form')?.reset();
}


/* ===== Issues page: AJAX load full info (Lab 6) ===== */
function bindIssueDetails() {
  const table = document.getElementById('issuesTable');
  const box = document.getElementById('issueDetailsBody');
  const title = document.getElementById('issueDetailsTitle');
  const offcanvasEl = document.getElementById('issueDetails');
  if (!table || !box || !title || !offcanvasEl) return;

  const oc = new bootstrap.Offcanvas(offcanvasEl);

  table.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-issue-id]');
    if (!btn) return;
    e.preventDefault();

    const id = btn.getAttribute('data-issue-id');
    box.innerHTML = '<div class="text-secondary">Загрузка…</div>';

    try {
      const r = await fetch('api/issues.php?id=' + encodeURIComponent(id));
      const data = await r.json();
      if (!r.ok) throw new Error(data.error || ('HTTP ' + r.status));

      title.textContent = data.symptom || 'Неисправность';
      box.innerHTML = `
        <p class="text-secondary mb-2"><b>Устройство:</b> ${data.device_name || '—'}</p>
        <p><b>Причины:</b><br>${(data.causes || '—').replaceAll('\n','<br>')}</p>
        <p><b>Решение:</b><br>${(data.solution || '—').replaceAll('\n','<br>')}</p>
        <p class="small text-secondary mb-0">Серьёзность: ${data.severity || '—'} • ID: ${data.id}</p>
      `;
      oc.show();
    } catch (err) {
      box.innerHTML = '<div class="alert alert-danger mb-0">Ошибка: ' + err.message + '</div>';
    }
  });
}


// DOM ready
document.addEventListener('DOMContentLoaded', () => {
  const issueSearch = document.getElementById('issueSearch');
  if (issueSearch) issueSearch.addEventListener('input', filterIssues);

  bindDeviceCards();
  bindGuidePreview();
  bindIssueDetails();

  // admin deletes: note - the PHP BASE_URL inside JS won't expand in static file,
  // so admin pages set window.EF_BASE_URL and we use it there (see below).
  if (document.getElementById('adminTable')) {
    // hook with EF_BASE_URL if present
    const base = window.EF_BASE_URL || '';
    $('#adminTable').on('click', '[data-delete]', function () {
      const btn = $(this);
      const type = btn.data('type');
      const id = btn.data('id');
      if (!confirm('Удалить запись #' + id + '?')) return;

      $.ajax({
        url: base + '/api/' + type + '.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ action: 'delete', id })
      })
      .done(() => {
        btn.closest('tr').remove();
        toast('Удалено: ' + type + ' #' + id);
      })
      .fail((xhr) => {
        const msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Ошибка удаления';
        toast(msg);
      });
    });
  }
});
