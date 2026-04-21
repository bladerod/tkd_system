let dataTable = null;

// Initialize DataTable
window.initializeInvoiceDataTable = function () {
    const invoiceTable = document.getElementById("invoiceTable");
    if (!invoiceTable) return;

    const tbody = invoiceTable.querySelector("tbody");
    const rows = tbody ? tbody.querySelectorAll("tr") : [];

    let hasValidData = false;
    if (rows.length > 0) {
        const firstRowCells = rows[0].querySelectorAll("td");
        const hasColspan =
            firstRowCells.length === 1 &&
            firstRowCells[0].hasAttribute("colspan");
        if (!hasColspan && firstRowCells.length === 7) {
            hasValidData = true;
        }
    }

    if (hasValidData && typeof simpleDatatables !== "undefined") {
        try {
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }

            dataTable = new simpleDatatables.DataTable(invoiceTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search...",
                    perPage: "Entries per page",
                    noRows: "No invoices found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query",
                },
            });

            console.log("Invoice DataTable initialized successfully");
        } catch (error) {
            console.error("Invoice DataTable initialization failed:", error);
            if (dataTable) {
                try {
                    dataTable.destroy();
                } catch (e) {}
                dataTable = null;
            }
        }
    }
};

// Open Add Invoice Modal
window.openAddInvoiceModal = function () {
    // Reset form
    const form = document.getElementById("addInvoiceForm");
    if (form) form.reset();

    const studentSelect = document.getElementById("studentSelect");
    if (studentSelect) {
        studentSelect.value = "";
        studentSelect.dispatchEvent(new Event("change")); 
    }

    // Reset displays
    const studentCodeDisplay = document.getElementById("studentCodeDisplay");
    const parentDisplay = document.getElementById("parentDisplay");
    const planDetails = document.getElementById("planDetails");
    const invoiceAmount = document.getElementById("invoiceAmount");
    const invoiceDiscount = document.getElementById("invoiceDiscount");
    const invoicePenalty = document.getElementById("invoicePenalty");
    const totalDuePreview = document.getElementById("totalDuePreview");
    const customDiscountContainer = document.getElementById(
        "customDiscountContainer",
    );
    const discountType = document.getElementById("discountType");

    if (studentCodeDisplay) studentCodeDisplay.textContent = "";
    if (parentDisplay) parentDisplay.textContent = "";
    if (planDetails) planDetails.classList.add("hidden");
    if (invoiceAmount) invoiceAmount.value = "";
    if (invoiceDiscount) invoiceDiscount.value = "0";
    if (invoicePenalty) invoicePenalty.value = "0";
    if (totalDuePreview) totalDuePreview.textContent = "₱0.00";
    if (customDiscountContainer) customDiscountContainer.style.display = "none";
    if (discountType) discountType.value = "none";

    // Set default dates
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    const dueDate = new Date(today.getFullYear(), today.getMonth(), 5);

    const billingPeriodStart = document.getElementById("billingPeriodStart");
    const billingPeriodEnd = document.getElementById("billingPeriodEnd");
    const dueDateInput = document.getElementById("dueDate");

    if (billingPeriodStart) billingPeriodStart.value = formatDate(firstDay);
    if (billingPeriodEnd) billingPeriodEnd.value = formatDate(lastDay);
    if (dueDateInput) dueDateInput.value = formatDate(dueDate);

    const modal = document.getElementById("addInvoiceModal");
    if (modal) modal.classList.remove("hidden");
};

// Format date for input
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}

// Close Add Invoice Modal
window.closeAddInvoiceModal = function () {
    const modal = document.getElementById("addInvoiceModal");
    if (modal) modal.classList.add("hidden");
    const form = document.getElementById("addInvoiceForm");
    if (form) form.reset();
};

// Calculate total due
function calculateTotalDue() {
    const amount =
        parseFloat(document.getElementById("invoiceAmount")?.value) || 0;
    const discount =
        parseFloat(document.getElementById("invoiceDiscount")?.value) || 0;
    const penalty =
        parseFloat(document.getElementById("invoicePenalty")?.value) || 0;
    const totalDue = amount - discount + penalty;
    const totalDuePreview = document.getElementById("totalDuePreview");
    if (totalDuePreview) {
        totalDuePreview.textContent =
            "₱" +
            totalDue.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
    }
    return totalDue;
}

// Calculate penalty based on student
async function calculatePenalty(studentId) {
    if (!studentId) {
        const invoicePenalty = document.getElementById("invoicePenalty");
        if (invoicePenalty) invoicePenalty.value = "0";
        calculateTotalDue();
        return;
    }

    try {
        const response = await fetch("/billing/get-penalty", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
                Accept: "application/json",
            },
            body: JSON.stringify({
                student_id: studentId,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            const invoicePenalty = document.getElementById("invoicePenalty");
            if (invoicePenalty) invoicePenalty.value = data.penalty_amount;
            calculateTotalDue();
        } else {
            console.error("Penalty calculation failed:", data.message);
        }
    } catch (error) {
        console.error("Error calculating penalty:", error);
        // Set penalty to 0 on error
        const invoicePenalty = document.getElementById("invoicePenalty");
        if (invoicePenalty) invoicePenalty.value = "0";
        calculateTotalDue();
    }
}

// Generate monthly invoices
window.generateMonthlyInvoices = async function () {
    Swal.fire({
        title: "Generate Monthly Invoices?",
        text: "This will generate invoices for all active students for the current month.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#63ad35",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, generate!",
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Processing...",
                text: "Please wait while invoices are being generated.",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            try {
                const response = await fetch("/billing/generate-monthly", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            document.querySelector('meta[name="csrf-token"]')
                                ?.content || "",
                        Accept: "application/json",
                    },
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire("Error!", error.message, "error");
            }
        }
    });
};

// Mark overdue invoices
window.markOverdueInvoices = async function () {
    Swal.fire({
        title: "Mark Overdue Invoices?",
        text: "This will mark all past due invoices as overdue.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#63ad35",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, mark!",
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Processing...",
                text: "Please wait.",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            try {
                const response = await fetch("/billing/mark-overdue", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            document.querySelector('meta[name="csrf-token"]')
                                ?.content || "",
                        Accept: "application/json",
                    },
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire("Error!", error.message, "error");
            }
        }
    });
};

// Process payment
window.processPayment = function (
    invoiceId,
    invoiceNo,
    studentName,
    balanceDue,
) {
    const numericBalance = parseFloat(balanceDue);

    Swal.fire({
        title: "Process Payment",
        html: `
            <div class="text-left">
                <p><strong>Invoice:</strong> ${invoiceNo}</p>
                <p><strong>Student:</strong> ${studentName}</p>
                <p><strong>Balance Due:</strong> ₱${numericBalance.toLocaleString()}</p>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount to Pay</label>
                    <input type="number" id="paymentAmount" class="w-full px-3 py-2 border border-gray-300 rounded-md" step="0.01" max="${numericBalance}">
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="cash">Cash</option>
                        <option value="gcash">GCash</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference No. (Optional)</label>
                    <input type="text" id="transactionReference" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Process Payment",
        cancelButtonText: "Cancel",
        preConfirm: () => {
            const amount = document.getElementById("paymentAmount").value;
            const paymentMethod =
                document.getElementById("paymentMethod").value;
            const transactionReference = document.getElementById(
                "transactionReference",
            ).value;

            if (!amount || parseFloat(amount) <= 0) {
                Swal.showValidationMessage("Please enter a valid amount");
                return false;
            }

            if (parseFloat(amount) > numericBalance) {
                Swal.showValidationMessage(
                    `Amount cannot exceed ₱${numericBalance.toLocaleString()}`,
                );
                return false;
            }

            return {
                amount,
                payment_method: paymentMethod,
                transaction_reference: transactionReference,
            };
        },
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Processing Payment...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            try {
                const response = await fetch(`/billing/${invoiceId}/payment`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            document.querySelector('meta[name="csrf-token"]')
                                ?.content || "",
                        Accept: "application/json",
                    },
                    body: JSON.stringify(result.value),
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire("Error!", error.message, "error");
            }
        }
    });
};

// Generate receipt
window.generateReceipt = function (invoiceId) {
    window.open(`/billing/${invoiceId}/receipt`, "_blank");
};

// Send reminder
window.sendReminder = async function (invoiceId) {
    Swal.fire({
        title: "Send Reminder?",
        text: "This will send a payment reminder to the parent.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#0096FF",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, send!",
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Sending...",
                text: "Please wait.",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            try {
                const response = await fetch(`/billing/${invoiceId}/reminder`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            document.querySelector('meta[name="csrf-token"]')
                                ?.content || "",
                        Accept: "application/json",
                    },
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire("Error!", error.message, "error");
            }
        }
    });
};

// ==========================================
// MASTER INITIALIZATION FUNCTION
// ==========================================
function initBillingUI() {
    // 0. Init Datatable
    setTimeout(() => {
        if (typeof initializeInvoiceDataTable === 'function') {
            initializeInvoiceDataTable();
        }
    }, 100);

    // ==========================================
    // 1. FORM SUBMIT HANDLER (DO THIS FIRST!)
    // ==========================================
    // We clone the form FIRST to prevent destroying our dropdown listeners!
    const oldForm = document.getElementById("addInvoiceForm");
    if (oldForm) {
        const newForm = oldForm.cloneNode(true);
        oldForm.parentNode.replaceChild(newForm, oldForm);

        newForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const studentId = document.getElementById("studentSelect")?.value;
            const planId = document.getElementById("planSelect")?.value;
            const billingPeriodStart = document.getElementById("billingPeriodStart")?.value;
            const billingPeriodEnd = document.getElementById("billingPeriodEnd")?.value;
            const amountInput = document.getElementById("invoiceAmount");
            const amount = amountInput?.value;
            const discount = document.getElementById("invoiceDiscount")?.value || 0;
            const penalty = document.getElementById("invoicePenalty")?.value || 0;
            const dueDate = document.getElementById("dueDate")?.value;

            if (!studentId) return Swal.fire("Error!", "Please select a student.", "error");
            if (!planId) return Swal.fire("Error!", "Please select a plan.", "error");
            if (!amount || parseFloat(amount) <= 0) return Swal.fire("Error!", "Please select a valid plan.", "error");

            Swal.fire({
                title: "Creating Invoice...",
                text: "Please wait.",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            try {
                const response = await fetch("/billing/create-invoice", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                        Accept: "application/json",
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        plan_id: parseInt(planId),
                        billing_period_start: billingPeriodStart,
                        billing_period_end: billingPeriodEnd,
                        amount: parseFloat(amount),
                        discount: parseFloat(discount),
                        penalty: parseFloat(penalty),
                        due_date: dueDate,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                    }).then(() => {
                        closeAddInvoiceModal();
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error("Error creating invoice:", error);
                Swal.fire("Error!", error.message, "error");
            }
        });
    }

    // ==========================================
    // 2. NOW IT IS SAFE TO ATTACH LISTENERS
    // ==========================================
    
    // STUDENT SELECTION (Parent & Penalty)
    const studentSelect = document.getElementById("studentSelect");
    const classSelect = document.getElementById("classSelect");
    const planSelect = document.getElementById("planSelect");
    
    if (studentSelect) {
        studentSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const studentCode = selectedOption.getAttribute("data-student-code") || "N/A";
                const parent = selectedOption.getAttribute("data-parent") || "N/A";

                document.getElementById("studentCodeDisplay").textContent = studentCode;
                document.getElementById("parentDisplay").textContent = parent;

                if (typeof calculatePenalty === 'function') calculatePenalty(selectedOption.value);
            } else {
                document.getElementById("studentCodeDisplay").textContent = "";
                document.getElementById("parentDisplay").textContent = "";
            }
        });
    }

    let allClassOptions = [];
    if (classSelect) {
        allClassOptions = Array.from(classSelect.options).map((opt) => opt.cloneNode(true));
    }

    if (studentSelect) {
        studentSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const studentCode = selectedOption.getAttribute("data-student-code") || "N/A";
                const parent = selectedOption.getAttribute("data-parent") || "N/A";
                
                // Parse the JSON array of class IDs this student is enrolled in
                let studentClassIds = [];
                try {
                    studentClassIds = JSON.parse(selectedOption.getAttribute("data-class-ids") || "[]");
                } catch(e) {
                    studentClassIds = [];
                }

                document.getElementById("studentCodeDisplay").textContent = studentCode;
                document.getElementById("parentDisplay").textContent = parent;

                if (typeof calculatePenalty === 'function') calculatePenalty(selectedOption.value);
                
                // Filter the Class Dropdown
                if (classSelect) {
                    classSelect.innerHTML = ""; 
                    const defaultOption = document.createElement("option");
                    defaultOption.value = "";
                    defaultOption.text = "-- Select Class --";
                    classSelect.appendChild(defaultOption);
                    
                    let matchCount = 0;
                    allClassOptions.forEach((opt) => {
                        // Keep the option if its value exists in the student's active class IDs array
                        if (opt.value && studentClassIds.includes(parseInt(opt.value))) {
                            classSelect.appendChild(opt.cloneNode(true));
                            matchCount++;
                        }
                    });
                    
                    if (matchCount === 0) {
                        defaultOption.text = "-- Student not enrolled in any classes --";
                        classSelect.disabled = true;
                        classSelect.classList.add("cursor-not-allowed", "bg-gray-100");
                    } else {
                        classSelect.disabled = false;
                        classSelect.classList.remove("cursor-not-allowed", "bg-gray-100");
                    }
                    
                    // Force change to cascade down to the Plan dropdown
                    classSelect.dispatchEvent(new Event("change"));
                }
                
            } else {
                // If NO student is selected, reset everything and lock the Class dropdown
                document.getElementById("studentCodeDisplay").textContent = "";
                document.getElementById("parentDisplay").textContent = "";
                
                if (classSelect) {
                    classSelect.innerHTML = "";
                    const defaultOption = document.createElement("option");
                    defaultOption.value = "";
                    defaultOption.text = "-- Select a Student First --";
                    classSelect.appendChild(defaultOption);
                    classSelect.disabled = true;
                    classSelect.classList.add("cursor-not-allowed", "bg-gray-100");
                    classSelect.dispatchEvent(new Event("change"));
                }
            }
        });
    }

    if (classSelect && planSelect) {
        const allPlanOptions = Array.from(planSelect.options).map((opt) => opt.cloneNode(true));

        classSelect.addEventListener("change", function () {
            const selectedClassId = this.value;

            planSelect.innerHTML = "";
            const defaultOption = document.createElement("option");
            defaultOption.value = "";

            if (!selectedClassId) {
                defaultOption.text = "-- Select a Class First --";
                planSelect.appendChild(defaultOption);
                planSelect.disabled = true;
                planSelect.classList.add("cursor-not-allowed", "bg-gray-100");
            } else {
                defaultOption.text = "-- Select Plan --";
                planSelect.appendChild(defaultOption);
                planSelect.disabled = false;
                planSelect.classList.remove("cursor-not-allowed", "bg-gray-100");

                let matchCount = 0;
                allPlanOptions.forEach((opt) => {
                    if (opt.value && opt.getAttribute("data-class-id") === selectedClassId) {
                        planSelect.appendChild(opt.cloneNode(true));
                        matchCount++;
                    }
                });

                if (matchCount === 0) {
                    defaultOption.text = "-- No Plans Available for this Class --";
                    planSelect.disabled = true;
                    planSelect.classList.add("cursor-not-allowed", "bg-gray-100");
                }
            }

            planSelect.dispatchEvent(new Event("change"));
        });
    }

    // PLAN -> BASE AMOUNT
    if (planSelect) {
        planSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            const invoiceAmount = document.getElementById("invoiceAmount");
            const planDetails = document.getElementById("planDetails");

            if (selectedOption && selectedOption.value) {
                const monthlyPrice = selectedOption.getAttribute("data-monthly-price") || 0;
                const planName = selectedOption.getAttribute("data-plan-name");
                const billingCycle = selectedOption.getAttribute("data-billing-cycle");

                document.getElementById("planNameDisplay").textContent = planName;
                document.getElementById("planBillingCycleDisplay").textContent = billingCycle;
                document.getElementById("planAmountDisplay").textContent = parseFloat(monthlyPrice).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                });

                if (invoiceAmount) invoiceAmount.value = monthlyPrice;
                if (planDetails) planDetails.classList.remove("hidden");
            } else {
                if (invoiceAmount) invoiceAmount.value = "";
                if (planDetails) planDetails.classList.add("hidden");
            }

            document.getElementById("discountType").dispatchEvent(new Event("change"));
        });
    }

    // DISCOUNT -> DYNAMIC CALCULATION
    const discountTypeSelect = document.getElementById("discountType");
    if (discountTypeSelect) {
        discountTypeSelect.addEventListener("change", function () {
            const customContainer = document.getElementById("customDiscountContainer");
            const invoiceDiscount = document.getElementById("invoiceDiscount");
            const baseAmount = parseFloat(document.getElementById("invoiceAmount").value) || 0;

            const selectedOption = this.options[this.selectedIndex];
            const type = selectedOption.getAttribute("data-type");
            let value = parseFloat(selectedOption.getAttribute("data-value")) || 0;

            if (this.value === "custom") {
                if (customContainer) customContainer.style.display = "block";
                value = parseFloat(document.getElementById("customDiscountAmount").value) || 0;
                invoiceDiscount.value = value.toFixed(2);
            } else {
                if (customContainer) customContainer.style.display = "none";

                if (type === "percent") {
                    const calculatedDiscount = baseAmount * (value / 100);
                    invoiceDiscount.value = calculatedDiscount.toFixed(2);
                } else if (type === "fixed") {
                    invoiceDiscount.value = value.toFixed(2);
                } else {
                    invoiceDiscount.value = "0";
                }
            }
            if (typeof calculateTotalDue === 'function') calculateTotalDue();
        });
    }

    // Listen to custom discount input
    const customDiscountAmount = document.getElementById("customDiscountAmount");
    if (customDiscountAmount) {
        customDiscountAmount.addEventListener("input", function () {
            document.getElementById("invoiceDiscount").value = this.value || 0;
            if (typeof calculateTotalDue === 'function') calculateTotalDue();
        });
    }

    // Listen to manual base amount overrides
    const invoiceAmount = document.getElementById("invoiceAmount");
    if (invoiceAmount) {
        invoiceAmount.addEventListener("input", function () {
            document.getElementById("discountType").dispatchEvent(new Event("change"));
        });
    }
}

// ==========================================
// VITE SAFARI BOOTSTRAPPER
// ==========================================
// This guarantees the code runs whether Vite loads it early or late!
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initBillingUI);
} else {
    initBillingUI();
}

// Close modal on click outside
window.onclick = function (event) {
    const modal = document.getElementById("addInvoiceModal");
    if (event.target === modal) {
        closeAddInvoiceModal();
    }
};

