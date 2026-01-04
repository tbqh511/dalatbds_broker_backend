<div class="content">
    <div class="dashbard-menu-overlay"></div>
    <div class="dashbard-menu-wrap">
        <div class="dashbard-menu-close"><i class="fal fa-times"></i></div>
        <div class="dashbard-menu-container">
            <!-- user-profile-menu-->
            <div class="user-profile-menu">
                <h3>Main</h3>
                <ul class="no-list-style">
                    <li><a href="#" class="user-profile-act"><i class="fal fa-chart-line"></i>Dashboard</a></li>
                    <li><a href="#"><i class="fal fa-user-edit"></i> Edit profile</a></li>
                    <li><a href="#"><i class="fal fa-envelope"></i> Messages <span>3</span></a></li>
                    <li><a href="#"><i class="fal fa-users"></i> Agents List</a></li>
                </ul>
            </div>
            <!-- user-profile-menu end-->
            <!-- user-profile-menu-->
            <div class="user-profile-menu">
                <h3>Listings</h3>
                <ul class="no-list-style">
                    <li><a href="#"><i class="fal fa-th-list"></i> My listings</a></li>
                    <li><a href="#"> <i class="fal fa-calendar-check"></i> Bookings <span>2</span></a></li>
                    <li><a href="#"><i class="fal fa-comments-alt"></i> Reviews </a></li>
                    <li><a href="#"><i class="fal fa-file-plus"></i> Add New</a></li>
                </ul>
            </div>
            <!-- user-profile-menu end-->
        </div>
        <div class="dashbard-menu-footer">© Homeradar 2022 .  All rights reserved.</div>
    </div>

    <!-- dashboard content -->
    <div class="dashboard-content">
        <div class="dashboard-menu-btn color-bg"><span><i class="fas fa-bars"></i></span>Dasboard Menu</div>
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            <div class="dashboard-title fl-wrap">
                <div class="dashboard-title-item"><span>Dashboard</span></div>
                <div class="dashbard-menu-header">
                    <div class="dashbard-menu-avatar fl-wrap">
                        <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                        <h4>Welcome, <span>{{ auth()->check() ? auth()->user()->name : 'User' }}</span></h4>
                    </div>
                    <a href="{{ url('/') }}" class="log-out-btn tolt" data-microtip-position="bottom" data-tooltip="Log Out"><i class="far fa-power-off"></i></a>
                </div>
                <!--Tariff Plan menu-->
                <div class="tfp-det-container">
                    <div class="tfp-btn"><span>Your Tariff Plan : </span> <strong>Extended</strong></div>
                    <div class="tfp-det">
                        <p>You Are on <a href="#">Extended</a> . Use link bellow to view details or upgrade. </p>
                        <a href="#" class="tfp-det-btn color-bg">Details</a>
                    </div>
                </div>
                <!--Tariff Plan menu end-->
            </div>
            <!-- dashboard-title end -->

            <div class="dasboard-wrapper fl-wrap no-pag">
                <div class="dashboard-stats-container fl-wrap">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="dashboard-stats fl-wrap">
                                <i class="fal fa-map-marked"></i>
                                <h4>Active Listings</h4>
                                <div class="dashboard-stats-count">124</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stats fl-wrap">
                                <i class="fal fa-chart-bar"></i>
                                <h4>Listing Views</h4>
                                <div class="dashboard-stats-count">1056<span>(<strong>+356</strong> this week)</span></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stats fl-wrap">
                                <i class="fal fa-comments-alt"></i>
                                <h4>Your Reviews</h4>
                                <div class="dashboard-stats-count">357<span>(<strong>+12</strong> this week)</span></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stats fl-wrap">
                                <i class="fal fa-heart"></i>
                                <h4>Times Bookmarked</h4>
                                <div class="dashboard-stats-count">2329<span>(<strong>+234</strong> this week)</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="notification success-notif  fl-wrap">
                            <p>Your listing <a href="#">Family house in Brooklyn</a> has been approved!</p>
                            <a class="notification-close" href="#"><i class="fal fa-times"></i></a>
                        </div>
                        <div class="dashboard-widget-title fl-wrap">Your  Statistic</div>
                        <div class="dasboard-content fl-wrap">
                            <div class="chart-wrap fl-wrap">
                                <div class="chart-header fl-wrap">
                                    <div class="listsearch-input-item">
                                        <select data-placeholder="Week" class="chosen-select no-search-select">
                                            <option>Week</option>
                                            <option>Month</option>
                                            <option>Year</option>
                                        </select>
                                    </div>
                                    <div id="myChartLegend"></div>
                                </div>
                                <canvas id="canvas-chart"></canvas>
                            </div>
                        </div>
                        <div class="dashboard-widget-title fl-wrap">Last Activites</div>
                        <div class="dashboard-list-box fl-wrap">
                            <div class="dashboard-list fl-wrap">
                                <div class="dashboard-message">
                                    <span class="close-dashboard-item color-bg"><i class="fal fa-times"></i></span>
                                    <div class="main-dashboard-message-icon color-bg"><i class="far fa-check"></i></div>
                                    <div class="main-dashboard-message-text">
                                        <p>Your listing <a href="#">Urban Appartmes</a> has been approved! </p>
                                    </div>
                                    <div class="main-dashboard-message-time"><i class="fal fa-calendar-week"></i> 28 may 2020</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dasboard-widget fl-wrap">
                            <div class="dasboard-widget-title fl-wrap">
                                <h5><i class="fas fa-comment-alt"></i>Last Messages</h5>
                            </div>
                            <div class="chat-contacts fl-wrap">
                                <a class="chat-contacts-item" href="#">
                                    <div class="dashboard-message-avatar">
                                        <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                        <div class="message-counter">2</div>
                                    </div>
                                    <div class="chat-contacts-item-text">
                                        <h4>Mark Rose</h4>
                                        <span>27 Dec 2018 </span>
                                        <p>Vivamus lobortis vel nibh nec maximus. Donec dolor erat, rutrum ut feugiat sed, ornare vitae nunc.</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="box-widget fl-wrap">
                            <div class="banner-widget fl-wrap">
                                <div class="bg-wrap bg-parallax-wrap-gradien">
                                    <div class="bg" data-bg="{{ asset('images/bg/1.jpg') }}"></div>
                                </div>
                                <div class="banner-widget_content">
                                    <h5>Participate in our loyalty program. Refer a friend and get a discount.</h5>
                                    <a href="#" class="btn float-btn color-bg small-btn">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- dashboard-footer -->
            <div class="dashboard-footer">
                <div class="dashboard-footer-links fl-wrap">
                    <span>Helpfull Links:</span>
                    <ul>
                        <li><a href="#">About  </a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Pricing Plans</a></li>
                        <li><a href="#">Contacts</a></li>
                        <li><a href="#">Help Center</a></li>
                    </ul>
                </div>
                <a href="#main" class="dashbord-totop  custom-scroll-link"><i class="fas fa-caret-up"></i></a>
            </div>
            <!-- dashboard-footer end -->
        </div>
    </div>
</div>
