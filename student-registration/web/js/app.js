const API_BASE_URL = '/api';
const form = document.querySelector('#student-form');
const list = document.querySelector('#student-list');
const formMessage = document.querySelector('#form-message');
const listMessage = document.querySelector('#list-message');
const submitButton = document.querySelector('#submit-button');
const resetButton = document.querySelector('#reset-button');

function showMessage(element, message, type = '') {
  element.textContent = message;
  element.className = `message ${type}`.trim();
}

async function request(endpoint, options = {}) {
  const response = await fetch(`${API_BASE_URL}/${endpoint}`, {
    headers: { 'Content-Type': 'application/json', ...(options.headers || {}) },
    ...options
  });
  const data = await response.json().catch(() => ({ success: false, message: 'The server returned an invalid response.' }));
  if (!response.ok || !data.success) throw new Error(data.message || 'The request could not be completed.');
  return data;
}

function renderStudents(students) {
  list.innerHTML = '';
  if (students.length === 0) {
    list.innerHTML = '<tr class="empty-row"><td colspan="6">No students have been registered yet.</td></tr>';
    return;
  }
  students.forEach((student) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${escapeHtml(student.name)}</td>
      <td>${escapeHtml(student.email)}</td>
      <td>${escapeHtml(student.phone || '-')}</td>
      <td>${escapeHtml(student.course || '-')}</td>
      <td>${escapeHtml(new Date(student.created_at).toLocaleDateString())}</td>
      <td class="actions">
        <button class="action-button" type="button" data-action="edit" data-id="${student.id}">Edit</button>
        <button class="action-button delete" type="button" data-action="delete" data-id="${student.id}">Delete</button>
      </td>`;
    list.appendChild(row);
  });
}

function escapeHtml(value) {
  const element = document.createElement('span');
  element.textContent = value;
  return element.innerHTML;
}

async function loadStudents() {
  showMessage(listMessage, 'Loading students...');
  try {
    const data = await request('students.php');
    renderStudents(data.students);
    showMessage(listMessage, `${data.students.length} student${data.students.length === 1 ? '' : 's'} found.`, 'success');
  } catch (error) {
    showMessage(listMessage, error.message, 'error');
  }
}

function resetForm() {
  form.reset();
  document.querySelector('#student-id').value = '';
  submitButton.textContent = 'Register student';
  showMessage(formMessage, '');
}

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  if (!form.reportValidity()) return;
  const formData = new FormData(form);
  const id = formData.get('id');
  const payload = Object.fromEntries(formData.entries());
  delete payload.id;
  submitButton.disabled = true;
  showMessage(formMessage, id ? 'Updating student...' : 'Registering student...');
  try {
    const data = await request(id ? 'update.php' : 'register.php', {
      method: id ? 'PUT' : 'POST',
      body: JSON.stringify(id ? { id, ...payload } : payload)
    });
    resetForm();
    showMessage(formMessage, data.message, 'success');
    await loadStudents();
  } catch (error) {
    showMessage(formMessage, error.message, 'error');
  } finally {
    submitButton.disabled = false;
  }
});

resetButton.addEventListener('click', resetForm);
document.querySelector('#refresh-button').addEventListener('click', loadStudents);

list.addEventListener('click', async (event) => {
  const button = event.target.closest('button[data-action]');
  if (!button) return;
  const id = button.dataset.id;
  if (button.dataset.action === 'edit') {
    try {
      const data = await request('students.php');
      const student = data.students.find((item) => String(item.id) === id);
      if (!student) throw new Error('Student could not be found.');
      Object.entries(student).forEach(([key, value]) => {
        const field = document.querySelector(`#${key}`) || document.querySelector(`[name="${key}"]`);
        if (field) field.value = value;
      });
      document.querySelector('#student-id').value = student.id;
      submitButton.textContent = 'Update student';
      form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } catch (error) {
      showMessage(listMessage, error.message, 'error');
    }
    return;
  }
  if (button.dataset.action === 'delete' && window.confirm('Delete this student?')) {
    try {
      const data = await request('delete.php', { method: 'DELETE', body: JSON.stringify({ id }) });
      showMessage(listMessage, data.message, 'success');
      await loadStudents();
    } catch (error) {
      showMessage(listMessage, error.message, 'error');
    }
  }
});

loadStudents();
