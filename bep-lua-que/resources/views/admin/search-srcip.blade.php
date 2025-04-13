<script>
    function filterEmployees() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const statusFilter = document.getElementById("statusFilter").value;
        const rows = document.querySelectorAll(".nha-cung-cap-row");

        rows.forEach(row => {
            const name = row.querySelector(".ten-nha-cung-cap").textContent.toLowerCase();
            const id = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
            const status = row.querySelector(".trang-thai-nha-cung-cap").textContent.trim();

            const matchesSearch = name.includes(input) || id.includes(input);
            const matchesStatus = statusFilter === "Tất cả" || statusFilter === "" || status.includes(statusFilter);

            if (matchesSearch && matchesStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
</script>
