@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('.flatpickr').flatpickr();
        });

        $(document).on('click', '.infoBtn', function () {
            $('.info-reason').text($(this).data('reason'))
        });

        $(document).on('click', '.orderBtn', function () {
            let title = $(this).data('service_title');
            let id = $(this).data('service_id');

            let orderRoute = "{{ route('user.order.create') }}" + '?serviceId=' + id;
            $('.order-now').attr('href', orderRoute);

            $('.service-title').text(title);
            let description = $(this).data('service_description');

            if (description) {
                $('.service-description').html(description);
            } else {
                $('.service-description').text('The service description is not available.');
            }
        });

        $(document).on('click', '.orderDetailsBtn', function () {
            let title = $(this).data('service_title');
            let link = $(this).data('service_link');
            let quantity = $(this).data('service_quantity');

            $('#orderDetailsModal .title').text(title);
            $('#orderDetailsModal .link').text(link);
            $('#orderDetailsModal .quantity').text(quantity);
        });

        $(document).on('click', '.refillOrderBtn', function () {
            let route = $(this).data('route');
            $('#refillConfirm').attr('action', route);
        });
    </script>
@endpush
