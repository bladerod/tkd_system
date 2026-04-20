// Wait for DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
    // Initialize all functionality
    initializeDiscountsPage();
});

function initializeDiscountsPage() {
    // Initialize form handling
    initializeFormHandling();

    // Attach button listeners using event delegation
    attachTableEventListeners();

    // Initialize DataTable if table exists and has data
    initializeDataTableIfNeeded();
}

// Make sure functions are globally available
window.openAddDiscountModal = function () {
    console.log("Opening add modal");
    const modal = document.getElementById("addDiscountModal");
    if (modal) {
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    }
};

window.closeAddDiscountModal = function () {
    console.log("Closing add modal");
    const modal = document.getElementById("addDiscountModal");
    if (modal) {
        modal.classList.add("hidden");
        document.body.style.overflow = "auto";
        const form = document.getElementById("addDiscountForm");
        if (form) form.reset();
    }
};

window.closeEditDiscountModal = function () {
    console.log("Closing edit modal");
    const modal = document.getElementById("editDiscountModal");
    if (modal) {
        modal.classList.add("hidden");
        document.body.style.overflow = "auto";
    }
};

// Modal functions
window.openAddDiscountModal = function () {
    const modal = document.getElementById("addDiscountModal");
    if (modal) {
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    }
};

window.closeAddDiscountModal = function () {
    const modal = document.getElementById("addDiscountModal");
    if (modal) {
        modal.classList.add("hidden");
        document.body.style.overflow = "auto";

        // Reset form
        const form = document.getElementById("addDiscountForm");
        if (form) {
            form.reset();
        }

        // Reset error messages
        resetErrorMessages();

        // Reset field borders
        resetFieldBorders();
    }
};

window.openEditDiscountModal = function (discountId) {
    fetch(`/settings/discounts/${discountId}`, {
        // ⚠️ Wait, note the URL change below!
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((discount) => {
            // Populate form fields
            document.getElementById("edit_name").value = discount.name || "";
            document.getElementById("edit_type").value = discount.type || "";
            document.getElementById("edit_value").value = discount.value || "";
            document.getElementById("edit_applicable_to").value =
                discount.applicable_to || "";
            document.getElementById("edit_valid_from").value =
                discount.valid_from || "";
            document.getElementById("edit_valid_to").value =
                discount.valid_to || "";
            document.getElementById("edit_status").value =
                discount.status || "1";

            // Set form action URL
            const editForm = document.getElementById("editDiscountForm");
            editForm.action = `/settings/discounts/${discount.id}`;

            // Show modal
            const modal = document.getElementById("editDiscountModal");
            if (modal) {
                modal.classList.remove("hidden");
                document.body.style.overflow = "hidden";
            }
        })
        .catch((error) => {
            console.error("Error loading discount:", error);
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Failed to load discount data. Please try again.",
                confirmButtonColor: "#3085d6",
            });
        });
};

window.closeEditDiscountModal = function () {
    const modal = document.getElementById("editDiscountModal");
    if (modal) {
        modal.classList.add("hidden");
        document.body.style.overflow = "auto";

        // Reset form
        const form = document.getElementById("editDiscountForm");
        if (form) {
            form.reset();
        }

        // Reset error messages
        resetErrorMessages();

        // Reset field borders
        resetFieldBorders();
    }
};

// Helper functions
function resetErrorMessages() {
    document.querySelectorAll(".error-message").forEach((el) => {
        el.classList.add("hidden");
        el.textContent = "";
    });
}

function resetFieldBorders() {
    document.querySelectorAll("input, select, textarea").forEach((el) => {
        el.classList.remove("border-red-500", "border-green-500");
    });
}

// Table event delegation
function attachTableEventListeners() {
    // We attach to the document body so it survives Simple-Datatables rebuilding the table!
    document.body.removeEventListener("click", handleTableClick);
    document.body.addEventListener("click", handleTableClick);
}

// In your handleTableClick function, make sure the delete part is correct
function handleTableClick(e) {
    // Handle edit button clicks
    const editButton = e.target.closest(".edit-discount-btn");
    if (editButton) {
        e.preventDefault();
        const discountId = editButton.getAttribute("data-discount-id");
        if (discountId) {
            window.openEditDiscountModal(discountId);
        }
        return;
    }

    // Handle delete button clicks - FIXED to match user.js pattern
    const deleteButton = e.target.closest(".delete-discount-btn");
    if (deleteButton) {
        e.preventDefault();
        const discountId = deleteButton.getAttribute("data-discount-id");
        const discountName =
            deleteButton.getAttribute("data-discount-name") || "this discount";

        Swal.fire({
            title: "Are you sure?",
            text: `You are about to delete "${discountName}". This action cannot be undone.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (!result.isConfirmed) return;

            // Create form and submit - same as user.js
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/settings/discounts/${discountId}`;
            form.style.display = "none";

            const csrfToken = document.createElement("input");
            csrfToken.type = "hidden";
            csrfToken.name = "_token";
            csrfToken.value = getCsrfToken();

            const methodField = document.createElement("input");
            methodField.type = "hidden";
            methodField.name = "_method";
            methodField.value = "DELETE";

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        });
    }
}

// Get CSRF token from meta tag
function getCsrfToken() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute("content") : "";
}

// Initialize Simple DataTable
function initializeDataTableIfNeeded() {
    const discountTable = document.getElementById("discountTable");
    if (!discountTable) return;

    // Check if table has data
    const tbody = discountTable.querySelector("tbody");
    const rows = tbody ? tbody.querySelectorAll("tr") : [];
    const hasData = rows.length > 0 && !rows[0].querySelector("td[colspan]");

    if (hasData && typeof simpleDatatables !== "undefined") {
        try {
            // Check if DataTable is already initialized
            if (discountTable.datatable) {
                return;
            }

            const dataTable = new simpleDatatables.DataTable(discountTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search discounts...",
                    perPage: "Entries per page",
                    noRows: "No discounts found",
                    info: "Showing {start} to {end} of {rows} discounts",
                    noResults: "No results match your search query",
                },
            });

            // Store DataTable instance for potential later use
            discountTable.datatable = dataTable;

            // Re-attach listeners after DataTable redraws
            if (typeof dataTable.on === "function") {
                dataTable.on("datatable.draw", function () {});
            }
        } catch (error) {
            console.error("DataTable initialization failed:", error);
        }
    }
}

// Form handling with validation
function initializeFormHandling() {
    // Add form validation
    const addForm = document.getElementById("addDiscountForm");
    if (addForm) {
        // Clone to remove existing event listeners
        const newAddForm = addForm.cloneNode(true);
        addForm.parentNode.replaceChild(newAddForm, addForm);

        // Add date validation
        setupDateValidation(newAddForm);

        // Add submit handler
        newAddForm.addEventListener("submit", function (e) {
            // Validate date range
            if (!validateDateRange("add_valid_from", "add_valid_to")) {
                e.preventDefault();
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById("submitAddBtn");
            if (submitBtn) {
                submitBtn.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Adding...';
                submitBtn.disabled = true;
            }

            return true;
        });
    }

    // Edit form validation
    const editForm = document.getElementById("editDiscountForm");
    if (editForm) {
        // Clone to remove existing event listeners
        const newEditForm = editForm.cloneNode(true);
        editForm.parentNode.replaceChild(newEditForm, editForm);

        // Add date validation
        setupDateValidation(newEditForm, "edit");

        // Add submit handler
        newEditForm.addEventListener("submit", function (e) {
            // Validate date range
            if (!validateDateRange("edit_valid_from", "edit_valid_to")) {
                e.preventDefault();
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById("submitEditBtn");
            if (submitBtn) {
                submitBtn.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Updating...';
                submitBtn.disabled = true;
            }

            return true;
        });
    }

    // Add real-time validation for numeric fields
    setupNumericValidation();
}

function setupDateValidation(form, prefix = "add") {
    const validFrom = form.querySelector(`#${prefix}_valid_from`);
    const validTo = form.querySelector(`#${prefix}_valid_to`);

    if (validFrom && validTo) {
        validFrom.addEventListener("change", function () {
            if (validTo.value && this.value > validTo.value) {
                Swal.fire({
                    icon: "warning",
                    title: "Invalid Date Range",
                    text: "Valid From date cannot be later than Valid To date",
                    confirmButtonColor: "#3085d6",
                    timer: 3000,
                    showConfirmButton: true,
                });
                this.value = "";
            }
        });

        validTo.addEventListener("change", function () {
            if (validFrom.value && this.value < validFrom.value) {
                Swal.fire({
                    icon: "warning",
                    title: "Invalid Date Range",
                    text: "Valid To date cannot be earlier than Valid From date",
                    confirmButtonColor: "#3085d6",
                    timer: 3000,
                    showConfirmButton: true,
                });
                this.value = "";
            }
        });
    }
}

function validateDateRange(fromId, toId) {
    const fromDate = document.getElementById(fromId);
    const toDate = document.getElementById(toId);

    if (fromDate && toDate && fromDate.value && toDate.value) {
        if (fromDate.value > toDate.value) {
            Swal.fire({
                icon: "error",
                title: "Invalid Date Range",
                text: "Valid From date must be before or equal to Valid To date",
                confirmButtonColor: "#3085d6",
            });
            return false;
        }
    }

    return true;
}

function setupNumericValidation() {
    // Add validation for numeric fields to prevent negative values
    const numericFields = document.querySelectorAll(
        'input[type="number"][name="value"]',
    );
    numericFields.forEach((field) => {
        field.addEventListener("input", function () {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    });
}

// Close modal when clicking outside
window.onclick = function (event) {
    const addModal = document.getElementById("addDiscountModal");
    const editModal = document.getElementById("editDiscountModal");

    if (event.target === addModal && addModal) {
        window.closeAddDiscountModal();
    }
    if (event.target === editModal && editModal) {
        window.closeEditDiscountModal();
    }
};

// Close modal with Escape key
document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape") return;

    const addModal = document.getElementById("addDiscountModal");
    const editModal = document.getElementById("editDiscountModal");

    if (addModal && !addModal.classList.contains("hidden")) {
        window.closeAddDiscountModal();
    }
    if (editModal && !editModal.classList.contains("hidden")) {
        window.closeEditDiscountModal();
    }
});

// Re-initialize after any dynamic content updates (e.g., after form submission)
function refreshDiscountsTable() {
    const table = document.getElementById("discountTable");
    if (table && table.datatable) {
        // Destroy existing DataTable
        table.datatable.destroy();
        delete table.datatable;

        // Reinitialize
        initializeDataTableIfNeeded();
    }
}

// Export for global access
window.refreshDiscountsTable = refreshDiscountsTable;
