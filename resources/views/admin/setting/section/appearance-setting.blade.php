 <div class="tab-pane fade" id="appearance-setting" role="tabpanel" aria-labelledby="home-tab4">
     <div class="card card-body border">
         <form action="{{ route('admin.appearance-setting.update') }}" method="POST" enctype="multipart/form-data">
             @csrf
             @method('PUT')
             <div class="row">
                 <div class="col-md-6">
                     <div class="form-group">
                         <label>Site Color</label>
                         <input type="text" name="site_color" value="{{ config('settings.site_color') }}"
                             class="form-control colorpickerinput">
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
             $(".colorpickerinput").colorpicker({
                 format: 'hex',
                 component: '.input-group-append',
             });
         });
     </script>
 @endpush
