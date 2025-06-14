 <div class="tab-pane fade" id="stripe-setting" role="tabpanel" aria-labelledby="stripe-tab">
     <div class="card card-body border">
         <form action="{{ route('admin.stripe-setting.update') }}" method="POST" enctype="multipart/form-data">
             @csrf
             @method('PUT')

             <div class="form-group">
                 <label for="stie-name">Stripe Status</label> <br>
                 <select name="stripe_status" class="select3 form-control" id="default-currency">
                     <option @selected(@$paypalGateway['stripe_status'] == 1) value="1">Active</option>
                     <option @selected(@$paypalGateway['stripe_status'] == 0) value="0">Inactive</option>
                 </select>
             </div>

             {{-- <div class="form-group">
                 <label for="stie-name">Stripe Account Mode</label> <br>
                 <select name="stripe_account_mode" class=" select3 form-control" id="default-currency">
                     <option @selected(@$paypalGateway['stripe_account_mode'] === 'sandbox') value="sandbox">Sandbox</option>
                     <option @selected(@$paypalGateway['stripe_account_mode'] === 'live') value="live">Live</option>
                 </select>
             </div> --}}

             <div class="form-group">
                 <label for="stie-name">Stripe Country Name</label> <br>
                 <select name="stripe_country" class=" select3 form-control" id="default-currency">
                     <option value="">--select--</option>
                     @foreach (config('country_list') as $key => $country)
                         <option @selected(@$paypalGateway['stripe_country'] === $key) value="{{ $key }}">{{ $country }}</option>
                     @endforeach
                 </select>
             </div>

             <div class="form-group">
                 <label for="stie-name">Stripe Currency</label> <br>
                 <select name="stripe_currency" class=" select3 form-control" id="default-currency">
                     <option value="">--select--</option>
                     @foreach (config('currencies.currency_list') as $currency)
                         <option @selected(@$paypalGateway['stripe_currency'] === $currency) value="{{ $currency }}">{{ $currency }}</option>
                     @endforeach
                     <option value="live">Live</option>
                 </select>
             </div>

             <div class="form-group">
                 <label for="stripe_rate">Currency Rate (Per {{ config('settings.site_default_currency') }})</label>
                 <br>
                 <input type="text" class="form-control" name="stripe_rate" id="site-name"
                     value="{{ @$paypalGateway['stripe_rate'] }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Stripe Key</label> <br>
                 <input type="text" class="form-control" name="stripe_api_key" id="site-name"
                     value="{{ @$paypalGateway['stripe_api_key'] }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Stripe Secret Key</label> <br>
                 <input type="text" class="form-control" name="stripe_secret_key" id="site-name"
                     value="{{ @$paypalGateway['stripe_secret_key'] }}">
             </div>

             <div class="form-group">
                 <label>Stripe Logo</label>
                 <div class="col-sm-12 col-md-7">
                     <div id="image-preview-2" class="image-preview stripe-preview">
                         <label for="image-upload-2" id="image-label-2">Choose File</label>
                         <input type="file" name="stripe_logo" id="image-upload-2" />
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
             $('.stripe-preview').css({
                 'background-image': 'url({{ @$paypalGateway['stripe_logo'] }})',
                 'background-size': 'cover',
                 'background-position': 'center center'
             });

             if (jQuery().select2) {
                 $(".select3").select2();
             }


             $.uploadPreview({
                 input_field: "#image-upload-2", // Default: .image-upload-2
                 preview_box: "#image-preview-2", // Default: .image-preview
                 label_field: "#image-label-2", // Default: .image-label-2
                 label_default: "Choose File", // Default: Choose File
                 label_selected: "Change File", // Default: Change File
                 no_label: false, // Default: false
                 success_callback: null // Default: null
             });

         });
     </script>
 @endpush
