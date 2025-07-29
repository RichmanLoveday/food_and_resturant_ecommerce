<script>
    /** Show confirm message  */
    $('body').on('click', '.delete-item', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    type: 'DELETE',
                    success: function(res) {
                        if (res.status == 'success') {
                            toastr.success(res.message);
                            //$('.table').DataTable().draw();
                            window.location.reload();
                        } else if (res.status == 'error') {
                            toastr.error(res.message);
                        }
                    },
                    error: function(error) {}
                });
            }
        });
    });

    /** show or hide loader **/
    function showLoader() {
        $('.overlay').addClass('active');
    }

    function hideLoader() {
        $('.overlay').removeClass('active');
    }

    /** Load product modal **/
    function loadProductModal(e, productId) {
        // e.preventDefault();

        $.ajax({
            url: '{{ route('load-product-modal', ['productId' => '__PRODUCT_ID__']) }}'.replace(
                '__PRODUCT_ID__', productId),
            method: "GET",
            contentType: 'application/json',
            beforeSend: function() {
                $('.overlay').toggleClass('active');
            },
            success: function(res) {
                $(".load_product_modal_body").html(res);
                $('#cartModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log(error);
            },
            complete: function() {
                $('.overlay').toggleClass('active');
            }
        });
    }


    /** Add product to wishlist **/
    function addToWishList(e, productId) {
        // e.preventDefault(); // Uncomment if needed

        $.ajax({
            url: "{{ route('wishlist.store') }}",
            method: "POST",
            data: {
                productId: productId
            },
            beforeSend: function() {
                $('.overlay').toggleClass('active');
            },
            success: function(res) {
                toastr.success(res.message);
            },
            error: function(xhr, status, error) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(index, value) {
                    toastr.error(value);
                });
            },
            complete: function() {
                $('.overlay').toggleClass('active');
            }
        });
    }

    /** Update side bar cart **/
    function updateSideBarCart(callBack = null) {
        $.ajax({
            url: '{{ route('get-cart-products') }}',
            method: "GET",
            contentType: 'application/json',
            success: function(response) {
                $('.cart_content').html(response);

                let cartTotal = $('#cart_total').val();
                let cartCount = $('#cart_product_count').val();
                $('.cart_subtotal').text(`{{ currencyPosition('${cartTotal}') }}`);
                $('.cart_count').text(cartCount);

                if (callBack && typeof callBack === 'function') {
                    callBack();
                }
            },
            error: function(xhr, status, error) {

            },
            complete: function() {

            }
        });
    }


    /** Remove cart product from sidebar */
    function removeProductFromSidebar(rowId) {
        $.ajax({
            url: '{{ route('cart-product-remove', ['rowId' => '__ROW_ID__']) }}'
                .replace(
                    '__ROW_ID__', rowId),

            method: 'GET',
            beforeSend: function() {
                showLoader();
            },
            success: function(response) {
                if (response.status === 'success') {
                    updateSideBarCart(function() {
                        toastr.success(response.message);
                        hideLoader();
                    });
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = xhr.responseJSON.message;
                toastr.error(errorMessage);
            },
        })
    }


    /** Get current cart total amount */
    function getCartTotal() {
        return parseFloat("{{ cartTotal() }}");
    }
</script>
