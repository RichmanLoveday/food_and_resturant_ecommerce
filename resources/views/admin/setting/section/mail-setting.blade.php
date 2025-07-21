 <div class="tab-pane fade" id="mail-setting" role="tabpanel" aria-labelledby="home-tab4">
     <div class="card card-body border">
         <form action="{{ route('admin.mail-setting.update') }}" method="POST">
             @csrf
             @method('PUT')

             <div class="row">
                 <div class="col-md-4">
                     <div class="form-group">
                         <label for="stie-name">Mail Driver</label>
                         <input type="text" class="form-control" name="mail_driver" id="site-name"
                             value="{{ config('settings.mail_driver') }}">
                     </div>
                 </div>
                 <div class="col-md-4">
                     <div class="form-group">
                         <label for="stie-name">Mail Host</label>
                         <input type="text" class="form-control" name="mail_host" id="site-name"
                             value="{{ config('settings.mail_host') }}">
                     </div>
                 </div>
                 <div class="col-md-4">
                     <div class="form-group">
                         <label for="stie-name">Mail Port</label>
                         <input type="text" class="form-control" name="mail_port" id="site-name"
                             value="{{ config('settings.mail_port') }}">
                     </div>
                 </div>
             </div>

             <div class="row">
                 <div class="col-md-6">
                     <div class="form-group">
                         <label for="stie-name">Mail Username</label>
                         <input type="text" class="form-control" name="mail_username" id="site-name"
                             value="{{ config('settings.mail_username') }}">
                     </div>
                 </div>
                 <div class="col-md-6">
                     <div class="form-group">
                         <label for="stie-name">Mail Password</label>
                         <input type="text" class="form-control" name="mail_password" id="site-name"
                             value="{{ config('settings.mail_password') }}">
                     </div>
                 </div>
             </div>

             <div class="form-group">
                 <label for="stie-name">Mail Encryption</label>
                 <input type="text" class="form-control" name="mail_encryption" id="site-name"
                     value="{{ config('settings.mail_encryption') }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Mail Form Address</label>
                 <input type="text" class="form-control" name="mail_form_address" id="site-name"
                     value="{{ config('settings.mail_form_address') }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Mail Receive Address</label>
                 <input type="text" class="form-control" name="mail_receive_address" id="site-name"
                     value="{{ config('settings.mail_receive_address') }}">
             </div>

             <button type="submit" class="btn btn-primary">Save</button>
         </form>
     </div>
 </div>
