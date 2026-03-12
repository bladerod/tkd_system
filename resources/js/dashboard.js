// script for navbar dropdown toggle
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('profileDropdownButton');
    const dropdownMenu = document.getElementById('profileDropdownMenu');
    
    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('hidden');
            const expanded = dropdownButton.getAttribute('aria-expanded') === 'true' ? 'false' : 'true';
            dropdownButton.setAttribute('aria-expanded', expanded);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
                dropdownButton.setAttribute('aria-expanded', 'false');
            }
        });
    }
});

// Register the datalabels plugin first
document.addEventListener('DOMContentLoaded', function() {
    // Make sure Chart and ChartDataLabels are available
    if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
        console.log('Chart.js DataLabels plugin registered successfully');
    } else {
        console.warn('Chart.js or ChartDataLabels not loaded yet');
    }
});

// for sale report chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chart-monthly-revenue').getContext('2d');
    
    // Monthly data
    const monthlyData = {
        labels: ['Feb 17', 'Feb 18', 'Feb 19', 'Feb 20', 'Feb 21', 'Feb 22', 'Feb 23'],
        values: [70.1, 80.1, 90.1, 100.1, 110.1, 120.1, 130.1]
    };
    
    // Annual data
    const annualData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        values: [450, 650, 800, 720, 950, 1100, 1250, 1400, 1350, 1200, 980, 1500]
    };
    
    // Create chart
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.labels,
            datasets: [{
                label: 'Sales (P)',
                data: monthlyData.values,
                borderColor: '#1C1C1D',
                backgroundColor: 'rgba(28, 28, 29, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#1C1C1D',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2,
                datalabels: {
                    align: 'top',
                    offset: 4
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 5,
            layout: {
                padding: {
                    top: 24  // Add padding to make room for data labels
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.raw + 'K';
                        }
                    }
                },
                datalabels: {
                    display: true,
                    color: '#1C1C1D',
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    borderRadius: 4,
                    padding: {
                        top: 2,
                        right: 4,
                        bottom: 2,
                        left: 4
                    },
                    font: {
                        size: 10,
                        weight: 'bold'
                    },
                    align: 'top',
                    offset: 8,
                    formatter: function(value) {
                        return '₱' + value + 'K';
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: {
                        callback: function(value) {
                            return '₱' + value + 'K';
                        },
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
    
    // Make function globally available for Alpine.js
    window.updateChartPeriod = function(period) {
        if (period === 'monthly') {
            chart.data.labels = monthlyData.labels;
            chart.data.datasets[0].data = monthlyData.values;
        } else {
            chart.data.labels = annualData.labels;
            chart.data.datasets[0].data = annualData.values;
        }
        chart.update();
    };
});

// for Enrollees graph
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chart-monthly-enrollees').getContext('2d');
    
    // Monthly data - Daily new enrollees
    const monthlyData = {
        labels: ['Feb 17', 'Feb 18', 'Feb 19', 'Feb 20', 'Feb 21', 'Feb 22', 'Feb 23'],
        values: [8, 12, 15, 10, 18, 22, 25]  // Number of new enrollees per day
    };
    
    // Annual data - Monthly enrollment totals
    const annualData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        values: [45, 52, 68, 58, 72, 85, 92, 105, 98, 82, 70, 120]  // Total enrollees per month
    };
    
    // Create chart
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.labels,
            datasets: [{
                label: 'New Enrollees',
                data: monthlyData.values,
                borderColor: '#1C1C1D',
                backgroundColor: 'rgba(28, 28, 29, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#1C1C1D',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2,
                datalabels: {
                    align: 'top',
                    offset: 4
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 5,
            layout: {
                padding: {
                    top: 24  // Add padding to make room for data labels
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' enrollees';
                        }
                    }
                },
                datalabels: {
                    display: true,
                    color: '#1C1C1D',
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    borderRadius: 4,
                    padding: {
                        top: 2,
                        right: 4,
                        bottom: 2,
                        left: 4
                    },
                    font: {
                        size: 10,
                        weight: 'bold'
                    },
                    align: 'top',
                    offset: 8,
                    formatter: function(value) {
                        return value;
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    title: {
                        display: true,
                        text: 'Number of Enrollees',
                        font: { size: 10, weight: '500' },
                        color: '#6B7280'
                    },
                    ticks: {
                        callback: function(value) {
                            return value;
                        },
                        font: { size: 10 },
                        stepSize: 5
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
    
    // Make function globally available for Alpine.js
    window.updateEnrolleesChartPeriod = function(period) {
        if (period === 'monthly') {
            chart.data.labels = monthlyData.labels;
            chart.data.datasets[0].data = monthlyData.values;
            chart.options.scales.y.title.text = 'Daily Enrollees';
        } else {
            chart.data.labels = annualData.labels;
            chart.data.datasets[0].data = annualData.values;
            chart.options.scales.y.title.text = 'Monthly Enrollees';
        }
        chart.update();
    };
});