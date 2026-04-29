$(document).ready(function() {
    // Listen for the custom Search Button click
    $("#jqSearchBtn").on("click", function(e) {
        e.preventDefault(); // Prevent form submission
        var value = $("#jqSearchInput").val().toLowerCase();

        // 1. Try to trigger the Simple-Datatables built-in search (preserves pagination seamlessly)
        var $dtSearch = $(".dataTable-input");
        if ($dtSearch.length > 0) {
            $dtSearch.val(value);
            $dtSearch[0].dispatchEvent(new Event('keyup')); // Dispatch native event for the library to catch
        } else {
            // 2. Fallback to basic jQuery row filtering if Datatables hasn't loaded
            $("table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }
    });

    // Also trigger search instantly when typing/pressing Enter
    $("#jqSearchInput").on("keyup", function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Stop standard form GET request
        }
        $("#jqSearchBtn").click(); // Trigger the search logic above
    });
});