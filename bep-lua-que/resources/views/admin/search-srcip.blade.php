<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    //code tim kiem
    $(document).ready(function() {
        const route = "{{ $route }}"; // Lấy route từ dữ liệu truyền vào view
        const tableId = "#{{ $tableId }}"; // ID của bảng
        const searchInputId = "#{{ $searchInputId }}"; // ID của ô tìm kiếm

        // Khi người dùng gõ vào ô tìm kiếm
        $(searchInputId).on('keyup', function() {
            const searchQuery = $(this).val(); // Lấy giá trị từ ô tìm kiếm
            fetchCategories(searchQuery); // Gọi hàm AJAX để tìm kiếm
        });

        // Hàm AJAX để gọi dữ liệu từ server
        function fetchCategories(query = '') {
            $.ajax({
                url: route, // Sử dụng URL đã được truyền vào
                method: "GET",
                data: {
                    ten: query // Gửi dữ liệu tìm kiếm theo query
                },
                success: function(response) {
                    // Cập nhật lại bảng dữ liệu sau khi tìm kiếm
                    $(tableId).html(response.html); // Cập nhật bảng theo ID
                },
                error: function() {
                    alert('Không thể tải dữ liệu, vui lòng thử lại.');
                }
            });
        }
    });
    //code tim kiem
    
</script>
