<script>
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

    /** Update side bar cart **/
    function updateSideBarCart() {
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
                $('.overlay').toggleClass('active');
            },
            success: function(response) {
                if (response.status === 'success') {
                    updateSideBarCart();
                    toastr.success(response.message);
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = xhr.responseJSON.message;
                toastr.error(errorMessage);
            },
            complete: function() {
                $('.overlay').toggleClass('active');
            }
        })
    }
</script>
