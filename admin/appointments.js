document.addEventListener('DOMContentLoaded', () => {
    const appointmentsTableBody = document.getElementById('appointmentsTableBody');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const viewPreviousButton = document.querySelector('.view-button');

    const editAppointmentModal = document.getElementById('editAppointmentModal');
    const closeButton = editAppointmentModal.querySelector('.close-button');
    const cancelEditButton = editAppointmentModal.querySelector('.cancel-edit-btn');
    const editAppointmentForm = document.getElementById('editAppointmentForm');

    let currentFilterStatus = 'Pending';
    let showingPastAppointments = false;

    async function fetchAppointments(status = 'All', includePast = false) {
        appointmentsTableBody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 20px;">Loading appointments...</td></tr>';
        
        // This URL is for get_appointments.php, which also needs to be updated with the JOIN
        let url = `get_appointments.php?status=${status}`; 
        if (includePast) {
            url += `&include_past=true`;
        }
        
        try {
            const response = await fetch(url);
            const data = await response.json();

            if (data.success) {
                renderAppointments(data.appointments);
            } else {
                appointmentsTableBody.innerHTML = `<tr><td colspan="9" style="text-align: center; padding: 20px; color: red;">Error: ${data.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Error fetching appointments:', error);
            appointmentsTableBody.innerHTML = `<tr><td colspan="9" style="text-align: center; padding: 20px; color: red;">Failed to load appointments. Please try again.</td></tr>`;
        }
    }

    function renderAppointments(appointments) {
        appointmentsTableBody.innerHTML = '';
        if (appointments.length === 0) {
            appointmentsTableBody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 20px;">No appointments found for this status.</td></tr>';
            return;
        }

        appointments.forEach(app => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td data-label="App ID">${app.appID}</td>
                <td data-label="Client Name">${app.clientName}</td> 
                <td data-label="Email">${app.email}</td>
                <td data-label="Phone">${app.phone}</td>
                <td data-label="Pet Name">${app.petName}</td> 
                <td data-label="Service">${app.service}</td>
                <td data-label="Date">${app.appointment_date}</td>
                <td data-label="Status"><span class="status-badge status-${app.status.toLowerCase()}">${app.status}</span></td>
                <td data-label="Actions" class="options-cell">
                    <button class="action-button edit-btn" data-id="${app.appID}">Edit</button>
                    ${app.status === 'Pending' ? `<button class="action-button approve-btn" data-id="${app.appID}">Approve</button>` : ''}
                    ${app.status === 'Approved' ? `<button class="action-button complete-btn" data-id="${app.appID}">Complete</button>` : ''}
                    <button class="action-button cancel-btn" data-id="${app.appID}">Cancel</button>
                    <button class="action-button delete-btn" data-id="${app.appID}">Delete</button>
                </td>
            `;
            appointmentsTableBody.appendChild(row);
        });

        addEventListenersToButtons();
    }

    function addEventListenersToButtons() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const appID = event.target.dataset.id;
                openEditModal(appID);
            });
        });

        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const appID = event.target.dataset.id;
                updateAppointmentStatus(appID, 'Approved');
            });
        });

        document.querySelectorAll('.complete-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const appID = event.target.dataset.id;
                updateAppointmentStatus(appID, 'Completed');
            });
        });

        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const appID = event.target.dataset.id;
                if (confirm('Are you sure you want to cancel this appointment?')) {
                    updateAppointmentStatus(appID, 'Cancelled');
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const appID = event.target.dataset.id;
                if (confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
                    deleteAppointment(appID);
                }
            });
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            currentFilterStatus = button.id.replace('filter', '');
            if (currentFilterStatus === 'All Appointments') {
                currentFilterStatus = 'All';
            }
            if (button.id !== 'filterPrevious') {
                showingPastAppointments = false;
                viewPreviousButton.textContent = 'View previous appointments';
            }
            fetchAppointments(currentFilterStatus, showingPastAppointments);
        });
    });

    viewPreviousButton.addEventListener('click', () => {
        showingPastAppointments = !showingPastAppointments;
        if (showingPastAppointments) {
            viewPreviousButton.textContent = 'View current/upcoming appointments';
        } else {
            viewPreviousButton.textContent = 'View previous appointments';
        }
        fetchAppointments(currentFilterStatus, showingPastAppointments);
    });

    fetchAppointments(currentFilterStatus, showingPastAppointments);

    // THIS FUNCTION'S URL HAS BEEN CHANGED
    async function updateAppointmentStatus(appID, newStatus) {
        const formData = new FormData();
        formData.append('editAppID', appID); // Use 'editAppID' to match update_appointment.php's expectation
        formData.append('editStatus', newStatus); // Use 'editStatus' to match update_appointment.php's expectation

        try {
            // Sending to update_appointment.php
            const response = await fetch('update_appointment.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                alert(`Appointment ${appID} status updated to ${newStatus}.`);
                fetchAppointments(currentFilterStatus, showingPastAppointments);
            } else {
                alert('Error updating status: ' + data.message);
            }
        } catch (error) {
            console.error('Error updating appointment status:', error);
            alert('An error occurred while updating appointment status.');
        }
    }

    async function deleteAppointment(appID) {
        const formData = new FormData();
        formData.append('appID', appID);

        try {
            const response = await fetch('delete_appointment.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                alert(`Appointment ${appID} deleted successfully.`);
                fetchAppointments(currentFilterStatus, showingPastAppointments);
            } else {
                alert('Error deleting appointment: ' + data.message);
            }
        } catch (error) {
            console.error('Error deleting appointment:', error);
            alert('An error occurred while deleting appointment.');
        }
    }

    async function openEditModal(appID) {
        try {
            // This fetch URL is for get_appointment_details.php
            const response = await fetch(`get_appointment_details.php?appID=${appID}`);
            const data = await response.json();

            if (data.success && data.appointment) {
                const app = data.appointment;
                document.getElementById('editAppID').value = app.appID;
                document.getElementById('editClientName').value = app.clientName; 
                document.getElementById('editClientEmail').value = app.email;
                document.getElementById('editClientPhone').value = app.phone;
                document.getElementById('editPetName').value = app.petName; 
                document.getElementById('editService').value = app.service;
                document.getElementById('editAppointmentDate').value = app.appointment_date;
                document.getElementById('editStatus').value = app.status;
                editAppointmentModal.style.display = 'block';
            } else {
                alert('Error fetching appointment details: ' + data.message);
            }
        } catch (error) {
            console.error('Error fetching appointment details for edit:', error);
            alert('An error occurred while fetching appointment details.');
        }
    }

    closeButton.addEventListener('click', () => {
        editAppointmentModal.style.display = 'none';
    });

    cancelEditButton.addEventListener('click', () => {
        editAppointmentModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === editAppointmentModal) {
            editAppointmentModal.style.display = 'none';
        }
    });

    editAppointmentForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(editAppointmentForm);
        // The form itself provides all the needed fields
        // const appID = formData.get('editAppID'); // No longer needed here

        try {
            const response = await fetch('update_appointment.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                alert('Appointment updated successfully!');
                editAppointmentModal.style.display = 'none';
                fetchAppointments(currentFilterStatus, showingPastAppointments);
            } else {
                alert('Error updating appointment: ' + data.message);
            }
        } catch (error) {
            console.error('Error submitting appointment update:', error);
            alert('An error occurred while saving changes.');
        }
    });
});