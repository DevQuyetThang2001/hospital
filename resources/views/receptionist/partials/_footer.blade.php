<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-center text-sm-left d-block d-sm-inline-block">Copyright © <a
                href="https://www.bootstrapdash.com/" target="_blank">bootstrapdash.com</a> 2020</span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Free <a
                href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard </a>templates from
            Bootstrapdash.com</span>
    </div>
</footer>
<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- base:js -->
<script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="{{ asset('admin/js/off-canvas.js') }}"></script>
<script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('admin/js/template.js') }}"></script>
<script src="{{ asset('admin/js/settings.js') }}"></script>
<script src="{{ asset('admin/js/todolist.js') }}"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="{{ asset('admin/vendors/progressbar.js/progressbar.min.js') }}"></script>
<script src="{{ asset('admin/vendors/chart.js/Chart.min.js') }}"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="{{ asset('admin/js/dashboard.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- End custom js for this page-->
@yield('scripts')



<script>
    $(document).ready(function() {

        // Khai báo key riêng biệt cho Receptionist
        const STORAGE_KEY = "receptionist_shown_notifs";

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "timeOut": "15000",
            "extendedTimeOut": "5000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
        };

        // Lấy danh sách thông báo đã show từ localStorage
        let shownNotifications = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

        function saveShownNotifications() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(shownNotifications));
        }

        // Helper: thêm nhiều ID vào shownNotifications (unique)
        function markIdsAsShown(ids = []) {
            ids.forEach(id => {
                if (!shownNotifications.includes(id)) shownNotifications.push(id);
            });
            saveShownNotifications();
        }

        // Hàm load thông báo
        function loadNotifications() {
            const notifItems = $('#notifItems');

            $.get("/receptionist/notifications", function(notifications) {
                notifItems.empty();

                if (!notifications || notifications.length === 0) {
                    notifItems.html(
                        '<p class="text-center text-muted small p-3 mb-0">Không có thông báo nào.</p>'
                        );
                } else {
                    const loadedIds = [];

                    notifications.forEach(item => {
                        // Gắn data-id cho từng item
                        notifItems.append(`
                        <a class="dropdown-item preview-item" data-id="${item.id}">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-success">
                                    <i class="typcn typcn-info-large mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">${item.message}</h6>
                                <p class="font-weight-light small-text mb-0">${item.time}</p>
                            </div>
                        </a>
                    `);

                        loadedIds.push(item.id);

                        // Chỉ show toast nếu chưa show trước đó
                        if (!shownNotifications.includes(item.id)) {
                            toastr.info(item.message, 'Thông báo mới', {
                                onclick: function() {
                                    // Click toast dẫn tới trang chi tiết lịch khám
                                    window.location.href =
                                        "/receptionist/appointments/list";
                                }
                            });

                            // Đánh dấu đã show
                            shownNotifications.push(item.id);
                        }
                    });

                    // Lưu những ID vừa load
                    saveShownNotifications();
                }

                // Cập nhật số lượng chưa đọc
                $.get("/receptionist/notifications/count", function(countData) {
                    $('#notifCount').text(countData.count);
                });
            });
        }

        // Khi click chuông: đánh dấu đã đọc
        $('#notificationDropdown').on('click', function() {
            const currentIds = [];
            $('#notifItems').find('.preview-item').each(function() {
                const id = $(this).data('id');
                if (id !== undefined) currentIds.push(id);
            });

            // Đánh dấu đã "thấy" trên client
            markIdsAsShown(currentIds);

            // Gọi API mark-as-read
            $.post("{{ route('receptionist.notifications.markAsRead') }}", {
                _token: "{{ csrf_token() }}"
            }, function(response) {
                $('#notifCount').text('0');

                // Giữ nguyên dropdown để xem chi tiết
                $('#notifItems .preview-item').addClass('read');

                // Nếu backend trả về danh sách id đã mark, cập nhật thêm lần nữa
                if (response && response.marked_ids && Array.isArray(response.marked_ids)) {
                    markIdsAsShown(response.marked_ids);
                }
            }).fail(function() {
                console.error('Không thể đánh dấu thông báo là đã đọc.');
            });
        });

        // Load lần đầu
        loadNotifications();

        // Polling định kỳ mỗi 10 giây
        setInterval(loadNotifications, 10000);

    });
</script>

<style>
    /* Tùy chọn: làm mờ thông báo đã đọc */
    .preview-item.read {
        opacity: 0.6;
    }
</style>


</body>

</html>
