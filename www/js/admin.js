document.addEventListener('DOMContentLoaded', () => {
  const loginModal = document.getElementById('loginModal');
  const loginBtn = document.getElementById('loginBtn');
  const adminPanel = document.getElementById('adminPanel');
  const toggleViewBtn = document.getElementById('toggleViewBtn');
  const exportExcelBtn = document.getElementById('exportExcelBtn');
  const exportPdfBtn = document.getElementById('exportPdfBtn');
  const userDataTable = document.getElementById('userDataTable');

  // Login logic
  loginBtn.addEventListener('click', () => {
    const user = document.getElementById('adminUser').value;
    const pass = document.getElementById('adminPass').value;
    if (user === 'SAMMELAN' && pass === 'Secure@2005') {
      loginModal.style.display = 'none';
      adminPanel.style.display = 'block';
      loadUserData();
    } else {
      alert('Invalid credentials!');
    }
  });

  // Load user data from PHP
  function loadUserData() {
    fetch('admin_api.php?action=read')
      .then(res => res.json())
      .then(data => {
        let html = '<table><tr><th>Unique ID</th><th>Name</th><th>Photo</th><th>Actions</th></tr>';
        data.forEach(user => {
          html += `<tr>
            <td>${user.unique_id}</td>
            <td>${user.name}</td>
            <td><img src="uploads/${user.photo}" height="50"/></td>
            <td>
              <button onclick="editUser('${user.unique_id}')">Edit</button>
              <button onclick="deleteUser('${user.unique_id}')">Delete</button>
            </td>
          </tr>`;
        });
        html += '</table>';
        userDataTable.innerHTML = html;
      });
  }

  // Toggle view logic
  toggleViewBtn.addEventListener('click', () => {
    fetch('admin_api.php?action=toggle_view')
      .then(() => alert('View data toggled!'));
  });

  // Export logic
  exportExcelBtn.addEventListener('click', () => {
    window.location.href = 'export_excel.php';
  });

  exportPdfBtn.addEventListener('click', () => {
    window.location.href = 'export_pdf.php';
  });
});

// Edit & delete functions
function editUser(id) {
  const newName = prompt('Enter new name:');
  if (newName) {
    fetch('admin_api.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({action: 'update', unique_id: id, name: newName})
    }).then(() => location.reload());
  }
}

function deleteUser(id) {
  if (confirm('Delete this user?')) {
    fetch('admin_api.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({action: 'delete', unique_id: id})
    }).then(() => location.reload());
  }
}
