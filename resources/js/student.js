let studentDataTable = null;

function openModal(button) {
    const modal = document.getElementById("studentModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    const studentId = button.getAttribute("data-id");
    const name = button.getAttribute("data-name");
    const belt = button.getAttribute("data-belt");
    const status = button.getAttribute("data-status");

    document.getElementById("profileTab").innerHTML = `
        <p><strong>Profile Information</strong></p>
        <p><strong>Name:</strong> ${name}</p>
        <p><strong>Belt:</strong> ${belt}</p>
        <p><strong>Status:</strong> ${status}</p>
    `;

    modal.dataset.studentId = studentId;
    loadAttendanceData(studentId);
}

function closeModal() {
    const modal = document.getElementById("studentModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function switchTab(button) {
    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active", "border-b-2", "border-blue-600"));
    button.classList.add("active", "border-b-2", "border-blue-600");

    document.querySelectorAll(".tab-content").forEach(c => c.classList.add("hidden"));
    const tabName = button.dataset.tab + "Tab";
    document.getElementById(tabName).classList.remove("hidden");

    if (button.dataset.tab === 'attendance') {
        const studentId = document.getElementById("studentModal").dataset.studentId;
        loadAttendanceData(studentId);
    }
}

async function loadAttendanceData(studentId) {
    const tbody = document.getElementById("attendanceTableBody");
    const noData = document.getElementById("noAttendanceData");
    tbody.innerHTML = '';
    noData.classList.add("hidden");

    try {
        const res = await fetch(`/api/students/${studentId}/attendance`);
        const data = await res.json();

        if (!data.length) {
            noData.classList.remove("hidden");
            document.getElementById("totalSessions").textContent = 0;
            document.getElementById("presentCount").textContent = 0;
            document.getElementById("absentCount").textContent = 0;
            return;
        }

        let present = 0, absent = 0;
        data.forEach(log => {
            if (log.status === "present") present++; else if (log.status === "absent") absent++;

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${new Date(log.checkin_time).toLocaleDateString()}</td>
                <td>${new Date(log.checkin_time).toLocaleTimeString()}</td>
                <td>${log.checkout_time ? new Date(log.checkout_time).toLocaleTimeString() : '-'}</td>
                <td>${log.status}</td>
                <td>${log.method || 'Manual'}</td>
            `;
            tbody.appendChild(row);
        });

        document.getElementById("totalSessions").textContent = data.length;
        document.getElementById("presentCount").textContent = present;
        document.getElementById("absentCount").textContent = absent;

    } catch (err) {
        console.error(err);
    }
}

// Initialize DataTable
function initializeStudentDataTable() {
    const studentTable = document.getElementById('studentTable');
    if (!studentTable) return;

    const tbody = studentTable.querySelector('tbody');
    const rows = tbody ? tbody.querySelectorAll('tr') : [];
    const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');

    if (hasData && typeof simpleDatatables !== 'undefined') {
        try {
            studentDataTable = new simpleDatatables.DataTable(studentTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search...",
                    perPage: "Entries per page",
                    noRows: "No students found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query"
                }
            });

            console.log('Student DataTable initialized successfully');
        } catch (error) {
            console.error('Student DataTable initialization failed:', error);
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeStudentDataTable();
});
