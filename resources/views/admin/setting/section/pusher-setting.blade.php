 <div class="tab-pane fade" id="pusher-setting" role="tabpanel" aria-labelledby="home-tab4">
     <div class="card card-body border">
         <form action="{{ route('admin.pusher-setting.update') }}" method="POST">
             @csrf
             @method('PUT')

             <div class="form-group">
                 <label for="stie-name">Pusher App ID</label>
                 <input type="text" class="form-control" name="pusher_app_id" id="site-name"
                     value="{{ config('settings.pusher_app_id') }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Pusher Key</label>
                 <input type="text" class="form-control" name="pusher_key" id="site-name"
                     value="{{ config('settings.pusher_key') }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Pusher Secret Key</label>
                 <input type="text" class="form-control" name="pusher_secret" id="site-name"
                     value="{{ config('settings.pusher_secret') }}">
             </div>

             <div class="form-group">
                 <label for="stie-name">Pusher cluster</label>
                 <input type="text" class="form-control" name="pusher_cluster" id="site-name"
                     value="{{ config('settings.pusher_cluster') }}">
             </div>

             <button type="submit" class="btn btn-primary">Save</button>
         </form>
     </div>
 </div>
