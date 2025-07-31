 <div class="tab-pane fade" id="seo-setting" role="tabpanel" aria-labelledby="home-tab4">
     <div class="card card-body border">
         <form action="{{ route('admin.seo-setting.update') }}" method="POST">
             @csrf
             @method('PUT')

             <div class="form-group">
                 <label for="seo-title">Seo Title</label>
                 <input type="text" value="{{ config('settings.seo_title') }}" class="form-control" name="seo_title"
                     id="site-title">
             </div>
             <div class="form-group">
                 <label for="stie-name">Seo Description</label>
                 <textarea name="seo_description" class=" form-control" style="resize: none" cols="30" rows="10">{{ config('settings.seo_description') }}</textarea>
             </div>
             <div class="form-group">
                 <label>Seo Keywords</label>
                 <br>
                 <input type="text" value="{{ config('settings.seo_keywords') }}" class="form-control inputtags"
                     name="seo_keywords">
             </div>

             <button type="submit" class="btn btn-primary">Save</button>
         </form>
     </div>
 </div>

 @push('scripts')
     <script>
         $(".inputtags").tagsinput('items');
     </script>
 @endpush
