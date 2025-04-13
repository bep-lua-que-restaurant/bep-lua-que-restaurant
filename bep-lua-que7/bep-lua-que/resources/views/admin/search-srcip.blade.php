<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        function fetchFilteredData() {
            let searchQuery = $('#search-name').val();
            let statusFilter = $('#statusFilter').val();

            $.ajax({
                url: "{{ $route }}",
                method: "GET",
                data: {
                    ten: searchQuery,
                    statusFilter: statusFilter,
                },
                success: function(response) {
                    $('#list-container').html(response.html);
                },
                error: function(xhr) {
                    console.error("Lỗi khi tải dữ liệu:", xhr);
                }
            });
        }

        // Gửi yêu cầu khi người dùng nhập vào ô tìm kiếm
        $('#search-name').on('input', function() {
            fetchFilteredData();
        });

        // Gửi yêu cầu khi chọn bộ lọc trạng thái
        $('#statusFilter').on('change', function() {
            fetchFilteredData();
        });
    });
</script>
