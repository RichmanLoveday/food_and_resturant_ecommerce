<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                        class="fas fa-search"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        @php
            $messages = \App\Models\Chat::where('receiver_id', auth()->user()->id)
                ->where('seen', false)
                ->whereIn('id', function ($query) {
                    $query
                        ->selectRaw('MAX(id)')
                        ->from('chats')
                        ->where('receiver_id', auth()->user()->id)
                        ->groupBy('sender_id');
                })
                ->with([
                    'sender' => function ($query) {
                        $query->select('id', 'name', 'avatar');
                    },
                ])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        @endphp

        @if (\Auth::user()->id == 1)
            <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                    class="nav-link nav-link-lg fp_message_envelope message-toggle {{ count($messages) > 0 ? 'beep' : '' }}"><i
                        class="far fa-envelope"></i></a>


                <div class="dropdown-menu dropdown-list dropdown-menu-right">
                    <div class="dropdown-header">Messages
                        <form action="{{ route('admin.chat.mark-as-read') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="d-flex justify-content-end align-items-center">
                                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">Mark
                                    All
                                    As Read</a>
                            </div>
                        </form>
                    </div>
                    <div class="dropdown-list-content dropdown-list-message fp_messages_notification_list">
                        @foreach ($messages as $message)
                            <a data-user="{{ $message->sender_id }}"
                                href="{{ route('admin.chat.conversation', $message->sender_id) }}"
                                class="dropdown-item dropdown-item-unread got_new_message fp_user_message_notification">
                                <div class="dropdown-item-avatar">
                                    <img style="width: 50px; height:50px; object-fit:cover;" alt="image"
                                        src="{{ asset($message->sender->avatar) }}" class="rounded-circle">
                                    {{-- <div class="is-online"></div> --}}
                                </div>
                                <div class="dropdown-item-desc">
                                    <b>{{ Str::ucfirst($message->sender->name) }}</b>
                                    <p>{{ $message->message }}</p>
                                    <div class="time">{{ $message->created_at->diffForHumans() }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="dropdown-footer text-center">
                        <a href="{{ route('admin.chat.index') }}">View All <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </li>
        @endif

        @php
            $notifications = \App\Models\OrderPlacedNotification::where('seen', 0)->latest()->take(10)->get();
        @endphp
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown"
                class="nav-link notification-toggle nav-link-lg notification_beep {{ count($notifications) > 0 ? 'beep' : '' }}"><i
                    class="far fa-bell"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Notifications
                    <div class="float-right">
                        <a href="{{ route('admin.clear-notification') }}">Mark All As Read</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons rt_notification">
                    @foreach ($notifications as $notification)
                        <a href="{{ route('admin.order.show', $notification->order_id) }}" class="dropdown-item">
                            <div class="dropdown-item-icon bg-info text-white">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                {{ $notification->message }}
                                <div class="time">{{ date('h:i A | d-F-Y', strtotime($notification->created_at)) }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="dropdown-footer text-center">
                    <a href="{{ route('admin.orders.index') }}">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img style="width: 30px; height:30px; object-fit:cover;" alt="image"
                    src="{{ asset(auth()->user()->avatar) }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Logged in 5 min ago</div>
                <a href="{{ route('admin.profile.index') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <a href="{{ route('admin.settings.index') }}" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#"
                        onclick="event.preventDefault();
                                                this.closest('form').submit();"
                        class="dropdown-item
                        has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </form>
            </div>
        </li>
    </ul>
</nav>


<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Stisla</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ setSidebarActive(['admin.dashboard']) }}"><a href="{{ route('admin.dashboard') }}"
                    class="nav-link"><i class="fas fa-fire"></i>
                    <span>Dashboard</span></a></li>

            <li class="menu-header">Starter</li>

            <li class="{{ setSidebarActive(['admin.slider.*']) }}"><a href="{{ route('admin.slider.index') }}"
                    class="nav-link"><i class="far fa-images"></i>
                    <span>Slider</span></a></li>


            <li class="{{ setSidebarActive(['admin.daily-offer.*']) }}"><a
                    href="{{ route('admin.daily-offer.index') }}" class="nav-link"><i class="far fa-clock"></i>
                    <span>Daily Offer</span></a></li>

            <li class="dropdown {{ setSidebarActive(['admin.orders.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-box"></i>
                    <span>Manage Orders</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setSidebarActive(['admin.orders.index']) }}"><a class="nav-link"
                            href="{{ route('admin.orders.index') }}">All Orders</a>
                    </li>
                    <li class="{{ setSidebarActive(['admin.orders.pending']) }}"><a class="nav-link"
                            href="{{ route('admin.orders.pending') }}">Pending Orders</a></li>
                    <li class="{{ setSidebarActive(['admin.orders.in-process']) }}"><a class="nav-link"
                            href="{{ route('admin.orders.in-process') }}">InProcess Orders</a></li>
                    <li class="{{ setSidebarActive(['admin.orders.delivered']) }}"><a class="nav-link"
                            href="{{ route('admin.orders.delivered') }}">Delivered Orders</a></li>
                    <li class="{{ setSidebarActive(['admin.orders.declined']) }}"><a class="nav-link"
                            href="{{ route('admin.orders.declined') }}">Declined Orders</a></li>
                </ul>
            </li>

            <li
                class="dropdown {{ setSidebarActive(['admin.product.*', 'admin.category.*', 'admin.product-review.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-shopping-cart"></i>
                    <span>Manage Products</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setSidebarActive(['admin.category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.category.index') }}">Product Categories</a></li>
                    <li class="{{ setSidebarActive(['admin.product.index']) }}"><a class="nav-link"
                            href="{{ route('admin.product.index') }}">Product</a></li>
                    <li class="{{ setSidebarActive(['admin.product-review.*']) }}"><a class="nav-link"
                            href="{{ route('admin.product-review.index') }}">Product Reviews</a></li>
                </ul>
            </li>


            <li
                class="dropdown {{ setSidebarActive(['admin.coupon.*', 'admin.delivery-area.*', 'admin.payment-setting.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-store"></i>
                    <span>Manage Ecommerce</span></a>
                <ul class="dropdown-menu">
                    <li class=" {{ setSidebarActive(['admin.coupon.*']) }}"><a class="nav-link"
                            href="{{ route('admin.coupon.index') }}">Coupon</a></li>
                    <li class="{{ setSidebarActive(['admin.delivery-area.*']) }}"><a class="nav-link"
                            href="{{ route('admin.delivery-area.index') }}">Delivery Areas</a></li>
                    <li class="{{ setSidebarActive(['admin.payment-setting.*']) }}"><a class="nav-link"
                            href="{{ route('admin.payment-setting.index') }}">Payment Gateway</a>
                    </li>
                </ul>
            </li>

            <li class="dropdown {{ setSidebarActive(['admin.reservation-time.*', 'admin.reservation.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-chair"></i>
                    <span>Manage Reservation</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setSidebarActive(['admin.reservation-time.*']) }}"><a
                            href="{{ route('admin.reservation-time.index') }}" class="nav-link">Reservation Times</a>
                    </li>
                    <li class="{{ setSidebarActive(['admin.reservation.index']) }}"><a
                            href="{{ route('admin.reservation.index') }}" class="nav-link">Reservation</a>
                    </li>
                </ul>
            </li>

            @if (\Auth::user()->id === 1)
                <li class="{{ setSidebarActive(['admin.chat.index']) }}"><a href="{{ route('admin.chat.index') }}"
                        class="nav-link"><i class="fas fa-comment-dots"></i>
                        <span>Messages</span></a></li>
            @endif

            <li
                class="dropdown {{ setSidebarActive(['admin.blog-category.*', 'admin.blogs.*', 'admin.blogs.comments.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-rss"></i>
                    <span>Blogs</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setSidebarActive(['admin.blog-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.blog-category.index') }}">Categories</a></li>
                    <li class="{{ setSidebarActive(['admin.blogs.*']) }}"><a class="nav-link"
                            href="{{ route('admin.blogs.index') }}">Blog</a></li>
                    <li class="{{ setSidebarActive(['admin.blogs.comments.index']) }}"><a class="nav-link"
                            href="{{ route('admin.blogs.comments.index') }}">Comment</a>
                    </li>
                </ul>
            </li>

            <li
                class="dropdown {{ setSidebarActive(['admin.why-choose-us.*', 'admin.banner-slider.*', 'admin.chef.*', 'admin.app-download.index', 'admin.testimonial.*', 'admin.counter.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-stream"></i>
                    <span>Sections</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setSidebarActive(['admin.why-choose-us.*']) }}"><a
                            href="{{ route('admin.why-choose-us.index') }}" class="nav-link">Why choose us</a></li>
                    <li class="{{ setSidebarActive(['admin.banner-slider.*']) }}"><a
                            href="{{ route('admin.banner-slider.index') }}" class="nav-link">Banner Slidder</a></li>
                    <li class="{{ setSidebarActive(['admin.chef.*']) }}"><a href="{{ route('admin.chef.index') }}"
                            class="nav-link">Chefs</a></li>
                    <li class="{{ setSidebarActive(['admin.app-download.index']) }}"><a
                            href="{{ route('admin.app-download.index') }}" class="nav-link">App Download</a></li>
                    <li class="{{ setSidebarActive(['admin.testimonial.*']) }}"><a
                            href="{{ route('admin.testimonial.index') }}" class="nav-link">Testimonial</a></li>
                    <li class="{{ setSidebarActive(['admin.counter.index']) }}"><a
                            href="{{ route('admin.counter.index') }}" class="nav-link">Counter</a></li>
                </ul>
            </li>

            <li
                class="dropdown {{ setSidebarActive(['admin.custom-page-builder.*', 'admin.about.index', 'admin.terms-and-condition.index', 'admin.contact.index', 'admin.privacy-policy.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-file-alt"></i>
                    <span>Pages</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setSidebarActive(['admin.custom-page-builder.index']) }}"><a
                            href="{{ route('admin.custom-page-builder.index') }}" class="nav-link">Custom Page</a>
                    </li>
                    <li class="{{ setSidebarActive(['admin.about.index']) }}"><a
                            href="{{ route('admin.about.index') }}" class="nav-link">About</a></li>
                    <li class="{{ setSidebarActive(['admin.privacy-policy.index']) }}"><a
                            href="{{ route('admin.privacy-policy.index') }}" class="nav-link">Privacy Policy</a></li>
                    <li class="{{ setSidebarActive(['admin.terms-and-condition.index']) }}"><a
                            href="{{ route('admin.terms-and-condition.index') }}" class="nav-link">Terms and
                            conditions</a></li>
                    <li class="{{ setSidebarActive(['admin.contact.index']) }}"><a
                            href="{{ route('admin.contact.index') }}" class="nav-link">Contact</a></li>
                </ul>
            </li>

            <li class="{{ setSidebarActive(['admin.news-letter.index']) }}"><a
                    href="{{ route('admin.news-letter.index') }}" class="nav-link"><i
                        class="fas fa-newspaper"></i>News
                    Leter</a></li>

            <li class="{{ setSidebarActive(['admin.social-link.*']) }}"><a
                    href="{{ route('admin.social-link.index') }}" class="nav-link"><i class="fas fa-link"></i>Social
                    Links</a></li>


            <li class="{{ setSidebarActive(['admin.footer-info.index']) }}"><a
                    href="{{ route('admin.footer-info.index') }}" class="nav-link"><i class="fas fa-info"></i>Footer
                    Info</a></li>

            <li class="{{ setSidebarActive(['admin.menu-builder.index']) }}"><a
                    href="{{ route('admin.menu-builder.index') }}" class="nav-link"><i class="fas fa-list-alt"></i>
                    <span>Menu Builder</span></a></li>

            <li class="{{ setSidebarActive(['admin.admin-management.index']) }}"><a
                    href="{{ route('admin.admin-management.index') }}" class="nav-link"><i
                        class="fas fa-user-shield"></i>
                    <span>Admin Management</span></a></li>

            <li class="{{ setSidebarActive(['admin.settings.index']) }}"><a
                    href="{{ route('admin.settings.index') }}" class="nav-link"><i class="fas fa-cogs"></i>
                    <span>Settings</span></a></li>

            <li class="{{ setSidebarActive(['admin.clear-database.index']) }}"><a
                    href="{{ route('admin.clear-database.index') }}" class="nav-link"><i
                        class="fas fa-exclamation-triangle"></i>
                    <span>Clear Database</span></a></li>

            {{-- <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Layout</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="layout-default.html">Default Layout</a></li>
                    <li><a class="nav-link" href="layout-transparent.html">Transparent Sidebar</a></li>
                    <li><a class="nav-link" href="layout-top-navigation.html">Top Navigation</a></li>
                </ul>
            </li> --}}
        </ul>
    </aside>
</div>
