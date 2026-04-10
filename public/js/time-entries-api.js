document.addEventListener('DOMContentLoaded', () => {
  const panel = document.getElementById('timeEntryApiPanel');
  if (!panel) {
    return;
  }

  const form = document.getElementById('timeEntryApiForm');
  const message = document.getElementById('timeEntryApiMessage');
  const tableBody = document.getElementById('timeEntriesTableBody');
  const emptyRow = document.getElementById('timeEntriesEmptyRow');

  const spentBadge = document.getElementById('ticketSpentBadge');
  const totalMeta = document.getElementById('ticketTotalMeta');
  const remainingMeta = document.getElementById('ticketRemainingMeta');
  const billableMeta = document.getElementById('ticketBillableMeta');
  const projectRemainingMeta = document.getElementById('projectRemainingMeta');
  const projectBillableMeta = document.getElementById('projectBillableMeta');

  function showMessage(text, isError = false) {
    if (!message) return;
    message.textContent = text;
    message.className = isError
      ? 'form-message is-visible is-error'
      : 'form-message is-visible is-success';
  }

  function buildDeleteForm(deleteUrl) {
    return `
      <form action="${deleteUrl}" method="post" onsubmit="return confirm('Supprimer cette entrée de temps ?');">
        <input type="hidden" name="_token" value="${window.csrfToken}">
        <input type="hidden" name="_method" value="DELETE">
        <button class="btn_link" type="submit">Supprimer</button>
      </form>
    `;
  }

  function prependEntry(entry) {
    if (emptyRow) {
      emptyRow.remove();
    }

    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${entry.work_date_label ?? entry.work_date ?? '—'}</td>
      <td>${entry.hours_label}</td>
      <td>${entry.user_name}</td>
      <td class="muted">${entry.comment}</td>
      <td>${buildDeleteForm(entry.delete_url)}</td>
    `;

    tableBody.prepend(row);
  }

  function updateStats(payload) {
    if (spentBadge) spentBadge.textContent = payload.ticket.spent_hours_label + ' saisies';
    if (totalMeta) totalMeta.textContent = `${payload.ticket.spent_hours_label} / estimé ${window.ticketEstimatedHoursLabel}`;
    if (remainingMeta) remainingMeta.textContent = payload.ticket.remaining_hours_label;
    if (billableMeta) billableMeta.textContent = payload.ticket.billable_hours_label;

    if (payload.project) {
      if (projectRemainingMeta) projectRemainingMeta.textContent = payload.project.remaining_hours_label;
      if (projectBillableMeta) projectBillableMeta.textContent = payload.project.hours_to_bill_label;
    }
  }

  async function submitTimeEntry(event) {
    event.preventDefault();

    const data = Object.fromEntries(new FormData(form).entries());

    if (!data.hours || Number(data.hours) < 0.25) {
      showMessage('La durée doit être d’au moins 0,25h.', true);
      return;
    }

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify(data),
      });

      const payload = await response.json();

      if (!response.ok) {
        const firstError = payload.errors ? Object.values(payload.errors)[0][0] : null;
        throw new Error(firstError || payload.message || 'Impossible d’ajouter le temps passé.');
      }

      prependEntry(payload.data);
      updateStats(payload);
      form.reset();

      const dateInput = form.querySelector('#work_date');
      if (dateInput) {
        dateInput.value = new Date().toISOString().slice(0, 10);
      }

      showMessage(payload.message || 'Temps passé ajouté avec succès.');
    } catch (error) {
      showMessage(error.message, true);
    }
  }

  form?.addEventListener('submit', submitTimeEntry);
});
