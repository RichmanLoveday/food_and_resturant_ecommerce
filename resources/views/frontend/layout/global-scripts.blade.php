<script>
    /** Load product modal **/
    function loadProductModal(e, productId) {
        // e.preventDefault();

        $.ajax({
            url: '{{ route('load-product-modal', ['productId' => '__PRODUCT_ID__']) }}'.replace(
                '__PRODUCT_ID__', productId),
            method: "GET",
            contentType: 'application/json',
            success: function(res) {
                $(".load_product_modal_body").html(res);
                $('#cartModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }
</script>
