 <div class="tab-pane fade" id="razorpay-setting" role="tabpanel" aria-labelledby="razorpay-tap">
     <div class="card card-body border">
         <form action="{{ route('admin.razorpay-setting.update') }}" method="POST" enctype="multipart/form-data">
             @csrf
             @method('PUT')

             <div class="form-group">
                 <label for="stie-name">Razorpay Status</label> <br>
                 <select name="razorpay_status" class="select4 form-control" id="default-currency">
                     <option @selected(@$paypalGateway['razorpay_status'] === 1) value="1">Active</option>
                     <option @selected(@$paypalGateway['razorpay_status'] === 0) value="0">Inactive</option>
                 </select>
             </div>

             {{-- <div class="form-group">
                 <label for="stie-name">razorpay Account Mode</label> <br>
                 <select name="razorpay_account_mode" class=" select4 form-control" id="default-currency">
                     <option @selected(@$paypalGateway['razorpay_account_mode'] === 'sandbox') value="sandbox">Sandbox</option>
                     <option @selected(@$paypalGateway['razorpay_account_mode'] === 'live') value="live">Live</option>
                 </select>
             </div> --}}

             <div class="form-group">
                 <label for="stie-name">Razorpay Country Name</label> <br>
                 <select name="razorpay_country" class=" select4 form-control" id="default-currency">
                     <option value="">--select--</option>
                     @foreach (config('country_list') as $key => $country)
                         <option @selected(@$paypalGateway['razorpay_country'] === $key) value="{{ $key }}">{{ $country }}</option>
                     @endforeach
                 </select>
             </div>

             <div class="form-group">
                 <label for="stie-name">Razorpay Currency</label> <br>
                 <select name="razorpay_currency" class=" select4 form-control" id="default-currency">
                     <option value="">--select--</option>
                     @foreach (config('currencies.currency_list') as $currency)
                         <option @selected(@$paypalGateway['razorpay_currency'] === $currency) value="{{ $currency }}">{{ $currency }}</option>
                     @endforeach
                     <option value="live">Live</option>
                 </select>
             </div>

             <div class="form-group">
                 <label for="razorpay_rate">Currency Rate (Per {{ config('settings.site_default_currency') }})</label>
                 <br>
                 <input type="text" class="form-control" name="razorpay_rate" id="site-name"
                     value="{{ @$paypalGateway['razorpay_rate'] }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Razorpay Key</label> <br>
                 <input type="text" class="form-control" name="razorpay_api_key" id="site-name"
                     value="{{ @$paypalGateway['razorpay_api_key'] }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Razorpay Secret Key</label> <br>
                 <input type="text" class="form-control" name="razorpay_secret_key" id="site-name"
                     value="{{ @$paypalGateway['razorpay_secret_key'] }}">
             </div>

             <div class="form-group">
                 <label>Razorpay Logo</label>
                 <div class="col-sm-12 col-md-7">
                     <div id="image-preview-3" class="image-preview razorpay-preview">
                         <label for="image-upload-3" id="image-label-3">Choose File</label>
                         <input type="file" name="razorpay_logo" id="image-upload-3" />
                     </div>
                 </div>
             </div>

             <button type="submit" class="btn btn-primary">Save</button>
         </form>
     </div>
 </div>


 @push('scripts')
     <script>
         $(document).ready(function() {
             $('.razorpay-preview').css({
                 'background-image': 'url({{ @$paypalGateway['razorpay_logo'] }})',
                 'background-size': 'cover',
                 'background-position': 'center center'
             });

             if (jQuery().select2) {
                 $(".select4").select2();
             }


             $.uploadPreview({
                 input_field: "#image-upload-3", // Default: .image-upload-3
                 preview_box: "#image-preview-3", // Default: .image-preview
                 label_field: "#image-label-3", // Default: .image-label-3
                 label_default: "Choose File", // Default: Choose File
                 label_selected: "Change File", // Default: Change File
                 no_label: false, // Default: false
                 success_callback: null // Default: null
             });

         });
     </script>
 @endpush
