 <div class="tab-pane fade show active" id="paypal-setting" role="tabpanel" aria-labelledby="paypal-tab">
     <div class="card card-body border">
         <form action="{{ route('admin.paypal-setting.update') }}" method="POST" enctype="multipart/form-data">
             @csrf
             @method('PUT')

             <div class="form-group">
                 <label for="stie-name">Paypal Status</label> <br>
                 <select name="paypal_status" class=" select2 form-control" id="default-currency">
                     <option @selected(@$paypalGateway['paypal_status'] == 1) value="1">Active</option>
                     <option @selected(@$paypalGateway['paypal_status'] == 0) value="0">Inactive</option>
                 </select>
             </div>

             <div class="form-group">
                 <label for="stie-name">Paypal Account Mode</label> <br>
                 <select name="paypal_account_mode" class=" select2 form-control" id="default-currency">
                     <option @selected(@$paypalGateway['paypal_account_mode'] === 'sandbox') value="sandbox">Sandbox</option>
                     <option @selected(@$paypalGateway['paypal_account_mode'] === 'live') value="live">Live</option>
                 </select>
             </div>

             <div class="form-group">
                 <label for="stie-name">Paypal Country Name</label> <br>
                 <select name="paypal_country" class=" select2 form-control" id="default-currency">
                     <option value="">--select--</option>
                     @foreach (config('country_list') as $key => $country)
                         <option @selected(@$paypalGateway['paypal_country'] === $key) value="{{ $key }}">{{ $country }}</option>
                     @endforeach
                 </select>
             </div>

             <div class="form-group">
                 <label for="stie-name">Paypal Currency Name</label> <br>
                 <select name="paypal_currency" class=" select2 form-control" id="default-currency">
                     <option value="">--select--</option>
                     @foreach (config('currencies.currency_list') as $currency)
                         <option @selected(@$paypalGateway['paypal_currency'] === $currency) value="{{ $currency }}">{{ $currency }}</option>
                     @endforeach
                     <option value="live">Live</option>
                 </select>
             </div>

             <div class="form-group">
                 <label for="paypal_rate">Currency Rate ( Per {{ config('settings.site_default_currency') }})</label>
                 <br>
                 <input type="text" class="form-control" name="paypal_rate" id="site-name"
                     value="{{ @$paypalGateway['paypal_rate'] }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Paypal Client Id</label> <br>
                 <input type="text" class="form-control" name="paypal_api_key" id="site-name"
                     value="{{ @$paypalGateway['paypal_api_key'] }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Paypal Secret Key</label> <br>
                 <input type="text" class="form-control" name="paypal_secret_key" id="site-name"
                     value="{{ @$paypalGateway['paypal_secret_key'] }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Paypal App ID</label> <br>
                 <input type="text" class="form-control" name="paypal_app_id" id="site-name"
                     value="{{ @$paypalGateway['paypal_app_id'] }}">
             </div>


             <div class="form-group">
                 <label>Image</label>
                 <div class="col-sm-12 col-md-7">
                     <div id="image-preview" class="image-preview">
                         <label for="image-upload" id="image-label">Choose File</label>
                         <input type="file" name="paypal_logo" id="image-upload" />
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
             $('.image-preview').css({
                 'background-image': 'url({{ @$paypalGateway['paypal_logo'] }})',
                 'background-size': 'cover',
                 'background-position': 'center center'
             });
         });
     </script>
 @endpush
