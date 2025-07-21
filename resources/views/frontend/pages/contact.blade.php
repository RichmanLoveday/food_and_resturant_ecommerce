@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__contact mt_100 xs_mt_70 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-6 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__contact_info">
                        <span><i class="fal fa-phone-alt"></i></span>
                        <div class="text">
                            <h3>call</h3>
                            <p>{{ $contact->phone_one }}</p>
                            <p>{{ $contact->phone_two }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__contact_info">
                        <span><i class="fal fa-envelope"></i></span>
                        <div class="text">
                            <h3>mail</h3>
                            <p>{{ $contact->mail_one }}</p>
                            <p>{{ $contact->mail_two }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__contact_info">
                        <span><i class="fas fa-street-view"></i></span>
                        <div class="text">
                            <h3>location</h3>
                            <p>{{ $contact->address }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fp__contact_form_area mt_100 xs_mt_70">
                <div class="row">
                    <div class="col-xl-12 wow fadeInUp" data-wow-duration="1s">
                        <form class="fp__contact_form">
                            <h3>contact</h3>
                            <div class="row">
                                <div class="col-xl-6 col-lg-6">
                                    <div class="fp__contact_form_input">
                                        <span><i class="fal fa-user-alt"></i></span>
                                        <input name="name" type="text" placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="fp__contact_form_input">
                                        <span><i class="fal fa-envelope"></i></span>
                                        <input name="email" type="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="fp__contact_form_input">
                                        <span><i class="fal fa-phone-alt"></i></span>
                                        <input name="phone" type="text" placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="fp__contact_form_input">
                                        <span><i class="fal fa-book"></i></span>
                                        <input name="subject" type="text" placeholder="Subject">
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="fp__contact_form_input textarea">
                                        <span><i class="fal fa-book"></i></span>
                                        <textarea name="message" rows="8" placeholder="Message"></textarea>
                                    </div>
                                    <button type="submit" class="submit_button">send message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt_100 xs_mt_70">
                    <div class="col-xl-12 wow fadeInUp" data-wow-duration="1s">
                        <div class="fp__contact_map">
                            <iframe src="{{ $contact->map_link }}" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(e) {
            $('.fp__contact_form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: "{{ route('contact.send-message') }}",
                    data: formData,
                    beforeSend: function() {
                        $('.submit_button').attr('disabled', true).html(
                            `<span class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"></span>
                            Sending...`
                        );
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('.fp__contact_form')[0].reset();
                        $('.submit_button').attr('disabled', false)
                            .html("Send Message");

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(index, value) {
                            toastr.error(value);
                        });

                        $('.submit_button').attr('disabled', false)
                            .html("Send Message");
                    }
                })
            })
        });
    </script>
@endpush
