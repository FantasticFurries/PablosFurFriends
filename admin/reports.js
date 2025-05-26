document.addEventListener('DOMContentLoaded', () => {
  // Window controls for Monthly Bookings window
  const monthlyWindow = document.getElementById('monthlyBookingsWindow');
  const petsWindow = document.getElementById('petsServicedWindow');
  
  // Add event listeners to window controls
  setupWindowControls(monthlyWindow);
  setupWindowControls(petsWindow);
  
  // Time range selector for Pets Assisted
  const petsTimeRange = document.getElementById('petsTimeRange');
  petsTimeRange.addEventListener('change', updatePetsAssistedCount);
  
  // Year selector for Monthly Bookings
  const yearSelect = document.getElementById('yearSelect');
  yearSelect.addEventListener('change', updateMonthlyBookings);
  
  // View Details buttons
  const viewDetailsBtns = document.querySelectorAll('.view-details-btn');
  viewDetailsBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const card = this.closest('.report-card');
      const cardTitle = card.querySelector('h3').textContent;
      
      if (cardTitle.includes('Pets Assisted')) {
        // Logic for Pets Assisted details
        alert('Pets Assisted details would be shown here');
      } else if (cardTitle.includes('Staff Members')) {
        // Logic for Staff Members details
        alert('Staff Members details would be shown here');
      } else if (cardTitle.includes('Monthly Bookings')) {
        // Show Monthly Bookings window
        monthlyWindow.style.display = 'block';
      }
    });
  });

  
  function setupWindowControls(windowElement) {
    const minimizeBtn = windowElement.querySelector('.minimize-btn');
    const content = windowElement.querySelector('.window-content');
    
    let isMinimized = false;
    let originalSize = {
      width: windowElement.style.width,
      height: windowElement.style.height,
      top: windowElement.style.top,
      left: windowElement.style.left
    };
    
    minimizeBtn.addEventListener('click', () => {
      if (isMinimized) {
        content.style.display = 'block';
        minimizeBtn.textContent = '−';
        isMinimized = false;
      } else {
        content.style.display = 'none';
        minimizeBtn.textContent = '+';
        isMinimized = true;
      }
    });
  }
  
  function updatePetsAssistedCount() {
    const timeRange = petsTimeRange.value;
    const totalPetsValue = document.getElementById('totalPetsValue');
    
    // Show loading
    totalPetsValue.textContent = '...';
    
    // Create form data for the AJAX request
    const formData = new FormData();
    formData.append('timeRange', timeRange);
    
    // Make AJAX call to get filtered data
    fetch('get_pets_count.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            totalPetsValue.textContent = data.count;
        } else {
            console.error('Error fetching pets count:', data.message);
            totalPetsValue.textContent = 'Error';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        totalPetsValue.textContent = 'Error';
    });
}
  
 function updateMonthlyBookings() {
    const year = yearSelect.value;
    
    // Show loading
    const tableBody = document.querySelector('.monthly-table tbody');
    tableBody.innerHTML = '<tr><td colspan="2" style="text-align: center;">Loading...</td></tr>';
    
    // Create form data for the AJAX request
    const formData = new FormData();
    formData.append('year', year);
    
    // Make AJAX call to get filtered data
    fetch('get_monthly_bookings.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            tableBody.innerHTML = '';
            data.monthlyData.forEach(month => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${month.month}</td>
                    <td>${month.count}</td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="2" style="text-align: center;">Error loading data</td></tr>';
            console.error('Error fetching monthly bookings:', data.message);
        }
    })
    .catch(error => {
        tableBody.innerHTML = '<tr><td colspan="2" style="text-align: center;">Error loading data</td></tr>';
        console.error('Error:', error);
    });
}
});

