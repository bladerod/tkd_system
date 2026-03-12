<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Martial Arts School Reports</title>
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  @vite(['resources/css/app.css'])
  @vite(['resources/css/reports.css'])
  @vite(['resources/css/dashboard.css'])
</head>
<body>

@include("includes.navbar")
@include('includes.sidebar')

<div class="main-content">
  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
      <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
      <span>/</span>
      <span class="text-[#1C1C1D] font-medium">Attendance</span>
  </div>
  <h1 class="text-4xl font-bold text-[#1C1C1D] mb-3">Attendance</h1>

  <!-- Attendance Reports -->
  <div class="report-card">
    <div class="report-header">Attendance Reports</div>
    <table>
      <thead>
        <tr>
          <th>Student</th><th>Date</th><th>Time In</th><th>Time Out</th>
          <th>Status</th><th>Instructor</th><th>Class</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Juan Dela Cruz</td><td>2026-02-04</td><td>08:05 AM</td><td>10:00 AM</td>
          <td class="status late">Late</td><td>Sir Mark</td><td>Kids TKD</td>
        </tr>
        <tr>
          <td>Maria Cruz</td><td>2026-02-04</td><td>08:00 AM</td><td>10:00 AM</td>
          <td class="status present">Present</td><td>Sir Mark</td><td>Kids TKD</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Revenue Reports -->
  <div class="report-card">
    <div class="report-header">Revenue Reports</div>
    <table>
      <thead>
        <tr>
          <th>Date</th><th>Student</th><th>Payment Type</th>
          <th>Method</th><th>Amount Paid</th><th>Receipt No.</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>2026-02-04</td><td>Juan Dela Cruz</td><td>Tuition</td>
          <td>Cash, Gcash</td><td>₱1,500</td><td>OR-1023</td>
        </tr>
        <tr>
          <td>2026-02-04</td><td>Maria Dela Cruz</td><td>Uniform</td>
          <td>Cash</td><td>₱850</td><td>OR-1023</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Parent Billing Summary -->
  <div class="report-card">
    <div class="report-header">Parent Billing Summary</div>
    <table>
      <thead>
        <tr>
          <th>Student</th><th>Parent</th><th>Total Bill</th>
          <th>Total Paid</th><th>Remaining Balance</th><th>Due Date</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Juan Dela Cruz</td><td>Ana Dela Cruz</td><td>₱9,000</td>
          <td>₱6,000</td><td class="balance due">₱3,000</td><td>2026-02-15</td>
        </tr>
        <tr>
          <td>Maria Dela Cruz</td><td>Ana Dela Cruz</td><td>₱15,000</td>
          <td>₱3,000</td><td class="balance due">₱12,000</td><td>2026-02-15</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Instructor Load -->
  <div class="report-card">
    <div class="report-header">Instructor Load</div>
    <table>
      <thead>
        <tr>
          <th>Instructor</th><th>Date</th><th>Class</th>
          <th>No. of Students</th><th>Time</th><th>Hours</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Sir Mark</td><td>2026-02-04</td><td>Kids TKD</td>
          <td>15</td><td>08:00–10:00 AM</td><td>2 hrs</td>
        </tr>
        <tr>
          <td>Sir John</td><td>2026-02-04</td><td>Adult TKD</td>
          <td>15</td><td>08:00–10:00 AM</td><td>2 hrs</td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

</body>
  @vite(['resources/js/dashboard.js'])
  <script src="//unpkg.com/alpinejs" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</html>
