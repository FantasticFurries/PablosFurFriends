const table = document.querySelector('.staffs-table table');
const headers = table.querySelectorAll('th[data-sort]');
let sortDirection = {};

headers.forEach((header, index) => {
  sortDirection[index] = 'asc';

  header.style.cursor = 'pointer';
  header.title = 'Click to sort';

  header.addEventListener('click', () => {
    const type = header.getAttribute('data-sort');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    headers.forEach(h => h.querySelector('.sort-arrow').textContent = '');

    rows.sort((a, b) => {
      let aText = a.children[index].textContent.trim();
      let bText = b.children[index].textContent.trim();

      if (type === 'number') {
        aText = Number(aText);
        bText = Number(bText);
      } else if (type === 'date') {
        aText = new Date(aText);
        bText = new Date(bText);
      } else {
        aText = aText.toLowerCase();
        bText = bText.toLowerCase();
      }

      if (aText < bText) return sortDirection[index] === 'asc' ? -1 : 1;
      if (aText > bText) return sortDirection[index] === 'asc' ? 1 : -1;
      return 0;
    });

    while (tbody.firstChild) {
      tbody.removeChild(tbody.firstChild);
    }

    rows.forEach(row => tbody.appendChild(row));

    header.querySelector('.sort-arrow').textContent = sortDirection[index] === 'asc' ? '▲' : '▼';

    sortDirection[index] = sortDirection[index] === 'asc' ? 'desc' : 'asc';
  });
});

function deleteStaff(id) {
  if (confirm("Are you sure you want to delete this staff member?")) {
    fetch('delete_staff.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'staffID=' + encodeURIComponent(id)
    })
    .then(response => response.text())
    .then(data => {
      if (data === 'success') {
        const row = document.querySelector(`tr[data-staff-id='${id}']`);
        if (row) row.remove();

        const rowsLeft = document.querySelectorAll('tbody tr').length;
        if (rowsLeft === 0) {
          document.getElementById('noStaffsMessage').style.display = 'block';
        }
      } else {
        alert('Failed to delete staff. Please try again.');
      }
    });
  }
}

//popup for confirmation
function showPopup(message, isSuccess = true) {
  const popup = document.getElementById('popupMessage');
  popup.textContent = message;
  popup.style.backgroundColor = isSuccess ? '#28a745' : '#dc3545'; 
  popup.style.display = 'block';

  setTimeout(() => {
    popup.style.display = 'none';
  }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
  // Back button
  document.getElementById("btnBack")?.addEventListener("click", () => {
    const url = new URL(window.location.href);
    url.searchParams.delete("viewStaff");
    window.location.href = url.toString();
  });

  // Cancel Edit button
  document.getElementById('btnCancelEdit')?.addEventListener('click', () => {
    document.querySelector('.edit-staff-window').style.display = 'none';
    document.querySelector('.view-staff-window').style.display = 'block';
  });

  // Cancel Add button
  document.getElementById('btnCancelAdd')?.addEventListener('click', () => {
    document.getElementById('addStaffWindow').style.display = 'none';
  });

  // Show Add Staff form
  document.getElementById('add-btn')?.addEventListener('click', () => {
    document.getElementById('addStaffWindow').style.display = 'block';
  });

  // Show Edit Staff form
  document.getElementById('updateDetailsBtn')?.addEventListener('click', () => {
    document.querySelector('.view-staff-window').style.display = 'none';
    document.querySelector('.edit-staff-window').style.display = 'block';
  });
});