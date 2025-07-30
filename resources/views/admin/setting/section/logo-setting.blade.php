 <div class="tab-pane fade" id="logo-setting" role="tabpanel" aria-labelledby="home-tab4">
     <div class="card card-body border">
         <form action="{{ route('admin.logo-setting.update') }}" method="POST" enctype="multipart/form-data">
             @csrf
             @method('PUT')
             <div class="row">
                 <div class="col-md-3">
                     <div class="form-group">
                         <label>Logo</label>
                         <div id="logo-preview" class="image-preview logo-preview">
                             <label for="image-upload" id="logo-label">Choose File</label>
                             <input type="file" name="logo" id="logo-upload" />
                         </div>
                     </div>
                 </div>
                 <div class="col-md-3">
                     <div class="form-group">
                         <label>Footer Logo</label>
                         <div id="footer-logo-preview" class="image-preview footer-logo-preview">
                             <label for="footer-logo-upload" id="footer-logo-label">Choose File</label>
                             <input type="file" name="footer_logo" id="footer-logo-upload" />
                         </div>
                     </div>
                 </div>
                 <div class="col-md-3">
                     <div class="form-group">
                         <label>Favicon</label>
                         <div id="favicon-preview" class="image-preview favicon-preview">
                             <label for="favicon-upload" id="favicon-label">Choose File</label>
                             <input type="file" name="favicon" id="favicon-upload" />
                         </div>
                     </div>
                 </div>
                 <div class="col-md-3">
                     <div class="form-group">
                         <label>Breadcrumb</label>
                         <div id="breadcrumb-preview" class="image-preview breadcrumb-preview">
                             <label for="breadcrumb-upload" id="breadcrumb-label">Choose File</label>
                             <input type="file" name="breadcrumb" id="breadcrumb-upload" />
                         </div>
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
             $('.logo-preview').css({
                 'background-image': 'url({{ asset(config('settings.logo')) }})',
                 'background-size': 'cover',
                 'background-position': 'center center'
             });


             $('.footer-logo-preview').css({
                 'background-image': 'url({{ asset(config('settings.footer_logo')) }})',
                 'background-size': 'cover',
                 'background-position': 'center center'
             });

             $('.favicon-preview').css({
                 'background-image': 'url({{ asset(config('settings.favicon')) }})',
                 'background-size': 'cover',
                 'background-position': 'center center'
             });

             $('.breadcrumb-preview').css({
                 'background-image': 'url({{ asset(config('settings.breadcrumb')) }})',
                 'background-size': 'cover',
                 'background-position': 'center center'
             });

             $.uploadPreview({
                 input_field: "#logo-upload", // Default: .logo-upload
                 preview_box: "#logo-preview", // Default: .logo-preview
                 label_field: "#logo-label", // Default: .logo-label
                 label_default: "Choose File", // Default: Choose File
                 label_selected: "Change File", // Default: Change File
                 no_label: false, // Default: false
                 success_callback: null // Default: null
             });

             $.uploadPreview({
                 input_field: "#footer-logo-upload", // Default: .footer-logo-upload
                 preview_box: "#footer-logo-preview", // Default: .footer-logo-preview
                 label_field: "#footer-logo-label", // Default: .image-label
                 label_default: "Choose File", // Default: Choose File
                 label_selected: "Change File", // Default: Change File
                 no_label: false, // Default: false
                 success_callback: null // Default: null
             });

             $.uploadPreview({
                 input_field: "#favicon-upload", // Default: .favicon-upload
                 preview_box: "#favicon-preview", // Default: .favicon-preview
                 label_field: "#favicon-label", // Default: .image-label
                 label_default: "Choose File", // Default: Choose File
                 label_selected: "Change File", // Default: Change File
                 no_label: false, // Default: false
                 success_callback: null // Default: null
             });

             $.uploadPreview({
                 input_field: "#breadcrumb-upload", // Default: .breadcrumb-upload
                 preview_box: "#breadcrumb-preview", // Default: .breadcrumb-preview
                 label_field: "#breadcrumb-label", // Default: .image-label
                 label_default: "Choose File", // Default: Choose File
                 label_selected: "Change File", // Default: Change File
                 no_label: false, // Default: false
                 success_callback: null // Default: null
             });
         });
     </script>
 @endpush
