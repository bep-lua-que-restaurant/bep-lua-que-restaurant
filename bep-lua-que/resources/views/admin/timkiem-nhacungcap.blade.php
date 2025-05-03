<script>
    function locNhaCungCap(page = 1) {
        var searchInput = $('#searchInput').val();
        var statusFilter = $('#statusFilter').val();

        $.ajax({
            url: '{{ route("nha-cung-cap.index") }}',
            type: 'GET',
            data: {
                searchInput: searchInput,
                statusFilter: statusFilter,
                page: page
            },
            success: function (response) {
                $('#nhaCungCapTable').html($(response.html).find('#nhaCungCapTable').html());
                $('#pagination').html($(response.pagination).html());
            },
            error: function () {
                alert('Lỗi khi tải dữ liệu!');
            }
        });
    }
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        locNhaCungCap(page);
    });
</script>
