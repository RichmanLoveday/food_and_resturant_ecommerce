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
        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                class="nav-link nav-link-lg fp_message_envelope message-toggle {{ count($messages) > 0 ? 'beep' : '' }}"><i
                    class="far fa-envelope"></i></a>


            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Messages
                    <form action="{{ route('admin.chat.mark-as-read') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">Mark All
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
                <a href="features-profile.html" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <a href="features-activities.html" class="dropdown-item has-icon">
                    <i class="fas fa-bolt"></i> Activities
                </a>
                <a href="features-settings.html" class="dropdown-item has-icon">
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
            <li class="active"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i>
                    <span>Dashboard</span></a></li>

            <li class="menu-header">Starter</li>

            <li><a href="{{ route('admin.slider.index') }}" class="nav-link"><i class="far fa-square"></i>
                    <span>Slider</span></a></li>

            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>Manage Resturant</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.category.index') }}">Product Categories</a></li>
                    <li><a class="nav-link" href="{{ route('admin.product.index') }}">Product</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>Manage Orders</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.orders.index') }}">All Orders</a></li>
                    <li><a class="nav-link" href="{{ route('admin.orders.pending') }}">Pending Orders</a></li>
                    <li><a class="nav-link" href="{{ route('admin.orders.in-process') }}">InProcess Orders</a></li>
                    <li><a class="nav-link" href="{{ route('admin.orders.delivered') }}">Delivered Orders</a></li>
                    <li><a class="nav-link" href="{{ route('admin.orders.declined') }}">Declined Orders</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>Manage Ecommerce</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.coupon.index') }}">Coupon</a></li>
                    <li><a class="nav-link" href="{{ route('admin.delivery-area.index') }}">Delivery Areas</a></li>
                    <li><a class="nav-link" href="{{ route('admin.payment-setting.index') }}">Payment Gateway</a>
                    </li>
                </ul>
            </li>


            <li><a href="{{ route('admin.chat.index') }}" class="nav-link"><i class="far fa-square"></i>
                    <span>Messages</span></a></li>

            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>Sections</span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('admin.why-choose-us.index') }}" class="nav-link">Why choose us</a></li>
                    <li><a href="{{ route('admin.daily-offer.index') }}" class="nav-link">Daily Offer</a></li>
                    <li><a href="{{ route('admin.banner-slider.index') }}" class="nav-link">Banner Slidder</a></li>
                    <li><a href="{{ route('admin.chef.index') }}" class="nav-link">Chefs</a></li>
                    <li><a href="{{ route('admin.app-download.index') }}" class="nav-link">App Download</a></li>
                    <li><a href="{{ route('admin.testimonial.index') }}" class="nav-link">Testimonial</a></li>
                    <li><a href="{{ route('admin.counter.index') }}" class="nav-link">Counter</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>Pages</span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('admin.about.index') }}" class="nav-link">About</a></li>
                    <li><a href="{{ route('admin.privacy-policy.index') }}" class="nav-link">Privacy Policy</a></li>
                    <li><a href="{{ route('admin.terms-and-condition.index') }}" class="nav-link">Terms and
                            conditions</a></li>
                    <li><a href="{{ route('admin.contact.index') }}" class="nav-link">Contact</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>Manage Reservation</span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('admin.reservation-time.index') }}" class="nav-link">Reservation Times</a>
                    </li>
                    <li><a href="{{ route('admin.reservation.index') }}" class="nav-link">Reservation</a>
                    </li>
                </ul>
            </li>

            <li><a href="{{ route('admin.news-letter.index') }}" class="nav-link"><i class="far fa-square"></i>News
                    Leter</a></li>

            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>Blogs</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('admin.blog-category.index') }}">Categories</a></li>
                    <li><a class="nav-link" href="{{ route('admin.blogs.index') }}">Blog</a></li>
                    <li><a class="nav-link" href="{{ route('admin.blogs.comments.index') }}">Comment</a>
                    </li>
                </ul>
            </li>

            <li><a href="{{ route('admin.settings.index') }}" class="nav-link"><i class="far fa-square"></i>
                    <span>Settings</span></a></li>

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
