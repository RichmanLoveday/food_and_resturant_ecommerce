 <div class="tab-pane fade show active" id="general-setting" role="tabpanel" aria-labelledby="home-tab4">
     <div class="card card-body border">
         <form action="{{ route('admin.general-setting.update') }}" method="POST">
             @csrf
             @method('PUT')

             <div class="form-group">
                 <label for="stie-name">Site Name</label>
                 <input type="text" class="form-control" name="site_name" id="site-name"
                     value="{{ config('settings.site_name') }}">
             </div>

             <div class="form-group">
                 <label for="default-currency">Default Currency</label>
                 <select name="site_default_currency" class=" select2 form-control" id="default-currency">
                     <option value="usd">USD</option>

                     @foreach (config('currencies.currency_list') as $currency)
                         <option @selected(config('settings.site_default_currency') === $currency) value="{{ $currency }}">
                             {{ $currency }}</option>
                     @endforeach
                 </select>
             </div>

             <div class="row">
                 <div class="col-md-6">
                     <div class="form-group">
                         <label for="currency-icon">Currency Icon</label>

                         <select name="site_currency_icon" class=" select2 form-control" id="default-currency">
                             <option value="$">$</option>

                             @foreach (config('currencies_icons.currency_icon') as $icon)
                                 <option @selected(config('settings.site_currency_icon') === $icon) value="{{ $icon }}">
                                     {{ $icon }}</option>
                             @endforeach
                         </select>
                     </div>
                 </div>

                 <div class="col-md-6">
                     <div class="form-group">
                         <label for="currency-icon-position">Currency Icon Position</label>
                         <select name="site_currency_icon_position" class=" select2 form-control"
                             id="default-currency-position">
                             <option @selected(config('settings.site_currency_icon_position') == 'right') value="right">Right</option>
                             <option @selected(config('settings.site_currency_icon_position') == 'left') value="left">Left</option>
                         </select>
                     </div>
                 </div>
             </div>

             <button type="submit" class="btn btn-primary">Save</button>
         </form>
     </div>
 </div>
