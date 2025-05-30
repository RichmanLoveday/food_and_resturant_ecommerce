  <div class="tab-pane fade" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab">
      <div class="fp_dashboard_body address_body">
          <h3>address <a class="dash_add_new_address"><i class="far fa-plus"></i> add new
              </a>
          </h3>
          <div class="fp_dashboard_address">
              <div class="fp_dashboard_existing_address">
                  <div class="row">
                      @foreach ($userAddresses as $address)
                          <div class="col-md-6">
                              <div class="fp__checkout_single_address">
                                  <div class="form-check">
                                      <label class="form-check-label">
                                          @php
                                              $icon_type = 'fa-home';
                                              if ($address->type === 'office') {
                                                  $icon_type = 'fa-car-building';
                                              }
                                          @endphp
                                          <span class="icon"><i class="fas {{ $icon_type }}"></i>
                                              {{ $address->type }}</span>
                                          <span class="address">{{ $address->address }},
                                              {{ $address->deliveryArea?->area_name }}</span>
                                      </label>
                                  </div>
                                  <ul>
                                      <li><a class="dash_edit_btn show_edit_section"
                                              data-class="edit_section_{{ $address->id }}"><i
                                                  class="far fa-edit"></i></a></li>
                                      <li><a href="{{ route('address.destroy', $address->id) }}"
                                              class="dash_del_icon delete-item"><i class="fas fa-trash-alt"></i></a>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      @endforeach
                  </div>
              </div>
              <div class="fp_dashboard_new_address ">
                  <form action="{{ route('address.store') }}" method="POST">
                      @csrf
                      <div class="row">
                          <div class="col-12">
                              <h4>add new address</h4>
                          </div>
                          <div class="col-md-12 col-lg-12 col-xl-12">
                              <div class="fp__check_single_form">
                                  <select id="select_js3" name="area">
                                      <option value="">Select Area</option>
                                      @foreach ($deliveryAreas as $area)
                                          <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-6 col-lg-12 col-xl-6">
                              <div class="fp__check_single_form">
                                  <input type="text" name="first_name" placeholder="First Name">
                              </div>
                          </div>
                          <div class="col-md-6 col-lg-12 col-xl-6">
                              <div class="fp__check_single_form">
                                  <input type="text" name="last_name" placeholder="Last Name">
                              </div>
                          </div>

                          <div class="col-md-6 col-lg-12 col-xl-6">
                              <div class="fp__check_single_form">
                                  <input type="text" name="phone" placeholder="Phone">
                              </div>
                          </div>

                          <div class="col-md-6 col-lg-12 col-xl-6">
                              <div class="fp__check_single_form">
                                  <input type="email" name="email" placeholder="Email">
                              </div>
                          </div>

                          <div class="col-md-12 col-lg-12 col-xl-12">
                              <div class="fp__check_single_form">
                                  <textarea cols="3" rows="4" name="address" placeholder="Address"></textarea>
                              </div>
                          </div>
                          <div class="col-12">
                              <div class="fp__check_single_form check_area">
                                  <div class="form-check">
                                      <input class="form-check-input" name="type" value="home" type="radio"
                                          name="flexRadioDefault" id="flexRadioDefault1">
                                      <label class="form-check-label" for="flexRadioDefault1">
                                          home
                                      </label>
                                  </div>
                                  <div class="form-check">
                                      <input class="form-check-input" name="type" value="office" type="radio"
                                          name="flexRadioDefault" id="flexRadioDefault2">
                                      <label class="form-check-label" for="flexRadioDefault2">
                                          office
                                      </label>
                                  </div>
                              </div>
                          </div>
                          <div class="col-12">
                              <button type="button" class="common_btn cancel_new_address">cancel</button>
                              <button type="submit" class="common_btn">save
                                  address</button>
                          </div>
                      </div>
                  </form>
              </div>


              @foreach ($userAddresses as $address)
                  <div class="fp_dashboard_edit_address edit_section_{{ $address->id }}">
                      <form action="{{ route('address.update', $address->id) }}" method="POST">
                          @csrf
                          @method('PUT')

                          <div class="row">
                              <div class="col-12">
                                  <h4>edit address</h4>
                              </div>
                              <div class="col-md-12 col-lg-12 col-xl-12">
                                  <div class="fp__check_single_form">
                                      <select class="nice-select" name="area">
                                          <option value="">Select Area</option>
                                          @foreach ($deliveryAreas as $area)
                                              <option @selected($address->delivery_area_id === $area->id) value="{{ $area->id }}">
                                                  {{ $area->area_name }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-6 col-lg-12 col-xl-6">
                                  <div class="fp__check_single_form">
                                      <input type="text" value="{{ $address->first_name }}" name="first_name"
                                          placeholder="First Name">
                                  </div>
                              </div>
                              <div class="col-md-6 col-lg-12 col-xl-6">
                                  <div class="fp__check_single_form">
                                      <input type="text" name="last_name" value="{{ $address->last_name }}"
                                          placeholder="Last Name">
                                  </div>
                              </div>

                              <div class="col-md-6 col-lg-12 col-xl-6">
                                  <div class="fp__check_single_form">
                                      <input type="text" name="phone" value="{{ $address->phone }}"
                                          placeholder="Phone">
                                  </div>
                              </div>

                              <div class="col-md-6 col-lg-12 col-xl-6">
                                  <div class="fp__check_single_form">
                                      <input type="email" value="{{ $address->email }}" name="email"
                                          placeholder="Email">
                                  </div>
                              </div>

                              <div class="col-md-12 col-lg-12 col-xl-12">
                                  <div class="fp__check_single_form">
                                      <textarea cols="3" rows="4" name="address" placeholder="Address">{!! $address->address !!}</textarea>
                                  </div>
                              </div>
                              <div class="col-12">
                                  <div class="fp__check_single_form check_area">
                                      <div class="form-check">
                                          <input @checked($address->type === 'home') class="form-check-input" name="type"
                                              value="home" type="radio" name="flexRadioDefault"
                                              id="flexRadioDefault1">
                                          <label class="form-check-label" for="flexRadioDefault1">
                                              home
                                          </label>
                                      </div>
                                      <div class="form-check">
                                          <input @checked($address->type === 'office') class="form-check-input" name="type"
                                              value="office" type="radio" name="flexRadioDefault"
                                              id="flexRadioDefault2">
                                          <label class="form-check-label" for="flexRadioDefault2">
                                              office
                                          </label>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-12">
                                  <button type="button" class="common_btn cancel_edit_address">cancel</button>
                                  <button type="submit" class="common_btn">update
                                      address</button>
                              </div>
                          </div>
                      </form>
                  </div>
              @endforeach
          </div>
      </div>
  </div>

  @push('scripts')
      <script>
          $(document).ready(function() {
              $('.show_edit_section').on('click', function() {
                  let className = $(this).data('class');
                  console.log(className);
                  $('.fp_dashboard_edit_address').removeClass('d-block');
                  $('.fp_dashboard_edit_address').removeClass('d-none');
                  $('.fp_dashboard_existing_address').addClass('d-none');
                  $('.' + className).addClass('d-block');
              });

              $('.cancel_edit_address').on('click', function() {
                  $('.fp_dashboard_edit_address').addClass('d-none');
                  $('.fp_dashboard_existing_address').removeClass('d-none');
              });
          });
      </script>
  @endpush
