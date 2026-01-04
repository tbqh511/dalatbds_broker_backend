<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Đăng tin mới'])
            <!-- dashboard-title end -->
            
            <div class="dasboard-wrapper fl-wrap no-pag">
                <div class="dasboard-scrollnav-wrap scroll-to-fixed-fixed scroll-init2 fl-wrap">
                    <ul>
                        <li><a href="#sec1" class="act-scrlink">Thông tin</a></li>
                        <li><a href="#sec2">Vị trí</a></li>
                        <li><a href="#sec3">Hình ảnh</a></li>
                        <li><a href="#sec4">Chi tiết</a></li>
                        <li><a href="#sec5">Phòng</a></li>
                        <li><a href="#sec6">Bản vẽ</a></li>
                        <li><a href="#sec7">Tiện ích</a></li>
                    </ul>
                    <div class="progress-indicator">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            viewBox="-1 -1 34 34">
                            <circle cx="16" cy="16" r="15.9155"
                                class="progress-bar__background" />
                            <circle cx="16" cy="16" r="15.9155"
                                class="progress-bar__progress 
                                js-progress-bar" />
                        </svg>
                    </div>
                </div>
                <!-- dasboard-widget-title -->
                <div class="dasboard-widget-title fl-wrap" id="sec1">
                    <h5><i class="fas fa-info"></i>Thông tin cơ bản</h5>
                </div>
                <!-- dasboard-widget-title end -->
                <!-- dasboard-widget-box  -->
                <div class="dasboard-widget-box fl-wrap">
                    <div class="custom-form">
                        <div class="row">
                            <div class="col-sm-4">       
                                <label>Tiêu đề tin <span class="dec-icon"><i class="far fa-briefcase"></i></span></label>
                                <input type="text" placeholder="Tên dự án hoặc tài sản" value=""/>
                            </div>
                            <div class="col-sm-4">
                                <label>Loại</label>
                                <div class="listsearch-input-item">
                                    <select data-placeholder="Tất cả loại" class="chosen-select no-search-select" >
                                        <option>Tất cả loại</option>
                                        <option>Cho thuê</option>
                                        <option>Bán</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">       
                                <label>Giá <span class="dec-icon"><i class="far fa-money-bill-wave"></i></span></label>
                                <input type="text" placeholder="Giá niêm yết" value=""/>
                            </div>
                            <div class="col-sm-4">
                                <label>Danh mục</label>
                                <div class="listsearch-input-item">
                                    <select data-placeholder="Căn hộ" class="chosen-select no-search-select" >
                                        <option>Tất cả danh mục</option>
                                        <option>Nhà phố</option>
                                        <option>Căn hộ</option>
                                        <option>Khách sạn</option>
                                        <option>Biệt thự</option>
                                        <option>Văn phòng</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Từ khóa <span class="dec-icon"><i class="far fa-key"></i></span></label>
                                <input type="text" placeholder="Tối đa 15 từ, cách nhau bằng dấu phẩy" value=""/>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- dasboard-widget-box  end-->
                <!-- dasboard-widget-title -->
                <div class="dasboard-widget-title dwb-mar fl-wrap" id="sec2">
                    <h5><i class="fas fa-street-view"></i>Vị trí / Liên hệ</h5>
                </div>
                <!-- dasboard-widget-title end -->
                <!-- dasboard-widget-box  -->
                <div class="dasboard-widget-box fl-wrap">
                    <div class="custom-form">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Địa chỉ <span class="dec-icon"><i class="far fa-map-marker"></i></span></label>
                                <input type="text" placeholder="Địa chỉ tài sản" value=""/>
                            </div>
                            <div class="col-sm-4">       
                                <label>Kinh độ (Kéo thả trên bản đồ) <span class="dec-icon"><i class="far fa-long-arrow-alt-right"></i></span></label>
                                <input type="text" id="long" placeholder="Kinh độ bản đồ" value=""/>
                            </div>
                            <div class="col-sm-4">       
                                <label>Vĩ độ (Kéo thả trên bản đồ)<span class="dec-icon"><i class="far fa-long-arrow-alt-down"></i> </span></label>
                                <input type="text" id="lat" placeholder="Vĩ độ bản đồ" value=""/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="map-container">
                            <div id="singleMap" class="drag-map" data-latitude="11.940419" data-longitude="108.458313"></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Thành phố</label>
                                <div class="listsearch-input-item">
                                    <select data-placeholder="Đà Lạt" class="chosen-select no-search-select" >
                                        <option>Tất cả thành phố</option>
                                        <option>Đà Lạt</option>
                                        <option>Bảo Lộc</option>
                                        <option>Đức Trọng</option>
                                        <option>Lâm Hà</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Email liên hệ <span class="dec-icon"><i class="far fa-envelope"></i></span>  </label>
                                <input type="text" placeholder="email@example.com" value=""/>
                            </div>
                            <div class="col-sm-6">
                                <label>Điện thoại <span class="dec-icon"><i class="far fa-phone"></i> </span> </label>
                                <input type="text" placeholder="0912345678" value=""/>
                            </div>
                            <div class="col-sm-6">
                                <label> Website <span class="dec-icon"><i class="far fa-globe"></i> </span> </label>
                                <input type="text" placeholder="dalatbds.com" value=""/>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- dasboard-widget-box  end-->                            
                <!-- dasboard-widget-title -->
                <div class="dasboard-widget-title dwb-mar fl-wrap" id="sec3">
                    <h5><i class="fas fa-image"></i>Hình ảnh tiêu đề</h5>
                </div>
                <!-- dasboard-widget-title end -->
                <!-- dasboard-widget-box  -->
                <div class="dasboard-widget-box fl-wrap">
                    <div class="custom-form">
                        <div class="add-list-media-header"  >
                            <label class="radio inline">
                            <input type="radio" name="gender"  checked>
                            <span>Carousel</span>
                            </label>
                        </div>
                        <div class="add-list-media-header">
                            <label class="radio inline">
                            <input type="radio" name="gender">
                            <span>Slider</span>
                            </label>
                        </div>
                        <div class="add-list-media-header">
                            <label class="radio inline">
                            <input type="radio" name="gender"   >
                            <span>Ảnh nền</span>
                            </label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="listsearch-input-item fl-wrap">
                            <form class="fuzone">
                                <div class="fu-text">
                                    <span><i class="far fa-cloud-upload-alt"></i> Nhấn vào đây hoặc kéo thả file để tải lên</span>
                                    <div class="photoUpload-files fl-wrap"></div>
                                </div>
                                <input type="file" class="upload" multiple>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- dasboard-widget-box  end-->                        
                <!-- dasboard-widget-title -->
                <div class="dasboard-widget-title dwb-mar fl-wrap" id="sec4">
                    <h5><i class="fas fa-list"></i>Chi tiết tin đăng</h5>
                </div>
                <!-- dasboard-widget-title end -->
                <!-- dasboard-widget-box  -->
                <div class="dasboard-widget-box fl-wrap">
                    <div class="custom-form">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Diện tích: <span class="dec-icon"><i class="far fa-sort-size-down-alt"></i></span></label>
                                        <input type="text" placeholder="Diện tích nhà" value=""/>                                            
                                        <label>Sức chứa: <span class="dec-icon"><i class="far fa-users"></i></span></label>
                                        <input type="text" placeholder="Số người tối đa" value=""/>                                                    
                                        <label>Diện tích sân: <span class="dec-icon"><i class="far fa-trees"></i></span></label>
                                        <input type="text" placeholder="Diện tích sân" value=""/>                                                    
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Phòng ngủ: <span class="dec-icon"><i class="far fa-bed"></i></span></label>
                                        <input type="text" placeholder="Số phòng ngủ" value=""/>
                                        <label>Phòng tắm: <span class="dec-icon"><i class="far fa-bath"></i></span></label>
                                        <input type="text" placeholder="Số phòng tắm" value=""/>                                                
                                        <label>Nhà để xe: <span class="dec-icon"><i class="far fa-warehouse"></i></span></label>
                                        <input type="text" placeholder="Số lượng xe" value=""/>                                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Mô tả chi tiết</label>
                                <div class="listsearch-input-item">
                                    <textarea cols="40" rows="3" style="height: 235px" placeholder="Mô tả chi tiết về bất động sản" spellcheck="false"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <label>Tiện ích: </label>
                        <div class=" add-list-tags fl-wrap">
                            <!-- Checkboxes -->
                            <ul class="fl-wrap filter-tags no-list-style ds-tg">
                                <li>
                                    <input id="check-aaa5" type="checkbox" name="check" checked>
                                    <label for="check-aaa5"> Wi Fi</label>
                                </li>
                                <li>
                                    <input id="check-bb5" type="checkbox" name="check" checked>
                                    <label for="check-bb5">Hồ bơi</label>
                                </li>
                                <li>
                                    <input id="check-dd5" type="checkbox" name="check">
                                    <label for="check-dd5"> An ninh</label>
                                </li>
                                <li>
                                    <input id="check-cc5" type="checkbox" name="check">
                                    <label for="check-cc5"> Phòng giặt</label>
                                </li>
                                <li>
                                    <input id="check-ff5" type="checkbox" name="check" checked>
                                    <label for="check-ff5"> Nhà bếp</label>
                                </li>
                                <li>
                                    <input id="check-c4" type="checkbox" name="check">
                                    <label for="check-c4">Điều hòa</label>
                                </li>
                                <li>
                                    <input id="check-c18" type="checkbox" name="check">
                                    <label for="check-c18">Bãi đậu xe</label>
                                </li>
                            </ul>
                            <!-- Checkboxes end -->                                                
                        </div>
                    </div>
                </div>
                <!-- dasboard-widget-box  end-->                        
                <!-- dasboard-widget-title -->
                <div class="dasboard-widget-title dwb-mar fl-wrap" id="sec5">
                    <h5><i class="fas fa-home-lg-alt"></i>Phòng</h5>
                    <div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch5" checked>
                        <label class="onoffswitch-label" for="myonoffswitch5">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
                <!-- dasboard-widget-title end -->                                                    
                <!-- dasboard-widget-box  -->
                <div class="dasboard-widget-box fl-wrap">
                    <div class="custom-form add_room-item-wrap">
                        <div class="add_room-container fl-wrap">
                            <!-- add_room-item   -->
                            <div class="add_room-item fl-wrap" >
                                <span class="remove-rp tolt" data-microtip-position="left"  data-tooltip="Xóa phòng"><i class="fal fa-times-circle"></i></span>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Tên phòng: <span class="dec-icon"><i class="fal fa-layer-group"></i></span></label>
                                        <input type="text" placeholder="Phòng gia đình tiêu chuẩn" value=""/>                
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Phòng bổ sung: <span class="dec-icon"><i class="fal fa-layer-plus"></i></span></label>
                                        <input type="text" placeholder="Ví dụ: Phòng xông hơi" value=""/>                
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Chi tiết phòng</label>
                                        <div class="listsearch-input-item">
                                            <textarea cols="40" rows="3" style="height: 175px;margin-bottom: 10px" placeholder="Chi tiết" spellcheck="false"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Hình ảnh phòng</label>
                                        <div class="listsearch-input-item fl-wrap">
                                            <form class="fuzone">
                                                <div class="fu-text">
                                                    <span><i class="far fa-cloud-upload-alt"></i> Nhấn vào đây hoặc kéo thả file để tải lên</span>
                                                    <div class="photoUpload-files fl-wrap"></div>
                                                </div>
                                                <input type="file" class="upload" multiple>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <label>Tiện nghi: </label>
                                <div class=" add-list-tags fl-wrap">
                                    <!-- Checkboxes -->
                                    <ul class="fl-wrap filter-tags no-list-style ds-tg">
                                        <li>
                                            <input id="check-2aaa5" type="checkbox" name="check" checked>
                                            <label for="check-2aaa5">Điều hòa</label>
                                        </li>
                                        <li>
                                            <input id="check-2bb5" type="checkbox" name="check" checked>
                                            <label for="check-2bb5">TV</label>
                                        </li>
                                        <li>
                                            <input id="check-2dd5" type="checkbox" name="check">
                                            <label for="check-2dd5"> Bồn tắm gốm</label>
                                        </li>
                                        <li>
                                            <input id="check-2cc5" type="checkbox" name="check" checked>
                                            <label for="check-2cc5">Lò vi sóng</label>
                                        </li>
                                    </ul>
                                    <!-- Checkboxes end -->                                                
                                </div>
                            </div>
                            <!--add_room-item end  -->
                        </div>
                        <a href="#" class="add-room-item">Thêm mới <i class="fal fa-plus"></i> </a>
                    </div>
                </div>
                <!-- dasboard-widget-box  end-->
                <!-- dasboard-widget-title -->
                <div class="dasboard-widget-title dwb-mar fl-wrap" id="sec6">
                    <h5><i class="fas fa-ruler-combined"></i>Bản vẽ nhà </h5>
                    <div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch22">
                        <label class="onoffswitch-label" for="myonoffswitch22">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
                <!-- dasboard-widget-title end -->                                                    
                <!-- dasboard-widget-box  -->
                <div class="dasboard-widget-box fl-wrap">
                    <div class="custom-form add_room-item-wrap">
                        <div class="add_room-container fl-wrap">
                            <!-- add_room-item   -->
                            <div class="add_room-item fl-wrap" >
                                <span class="remove-rp tolt" data-microtip-position="left"  data-tooltip="Xóa"><i class="fal fa-times-circle"></i></span>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Tên bản vẽ: <span class="dec-icon"><i class="far fa-ruler-horizontal"></i></span></label>
                                                <input type="text" placeholder=" Bản vẽ tầng 1 " value=""/>    
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Thông tin bổ sung: <span class="dec-icon"><i class="far fa-ruler-horizontal"></i></span></label>
                                                <input type="text" placeholder="Ví dụ: 100 m2" value=""/>    
                                            </div>
                                        </div>
                                        <label>Chi tiết bản vẽ</label>
                                        <div class="listsearch-input-item">
                                            <textarea cols="40" rows="3" style="height: 85px;" placeholder="Chi tiết" spellcheck="false"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <label>Tải lên hình ảnh</label>
                                        <div class="listsearch-input-item fl-wrap">
                                            <form class="fuzone">
                                                <div class="fu-text">
                                                    <span><i class="far fa-cloud-upload-alt"></i> Nhấn vào đây hoặc kéo thả file để tải lên</span>
                                                    <div class="photoUpload-files fl-wrap"></div>
                                                </div>
                                                <input type="file" class="upload">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--add_room-item end  -->
                        </div>
                        <a href="#" class="add-room-item">Thêm mới <i class="fal fa-plus"></i> </a>
                    </div>
                </div>
                <!-- dasboard-widget-box  end-->
                <!-- dasboard-widget-title -->
                <div class="dasboard-widget-title dwb-mar fl-wrap" id="sec7">
                    <h5><i class="fas fa-sliders-h"></i>Widget nội dung</h5>
                </div>
                <!-- dasboard-widget-title end -->                                                    
                <!-- dasboard-widget-box  -->
                <div class="dasboard-widget-box fl-wrap">
                    <div class="custom-form">
                        <div class="row">
                            <!-- content-widget-switcher -->    
                            <div class="col-md-4">
                                <div class="content-widget-switcher fl-wrap">
                                    <span class="content-widget-switcher-title">Video giới thiệu</span>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitchmc" checked>
                                        <label class="onoffswitch-label" for="myonoffswitchmc">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                    <div class="content-widget-switcher-item fl-wrap">                      
                                        <label>Video Youtube: <span class="dec-icon"><i class="fab fa-youtube"></i></span></label>
                                        <input type="text" placeholder="Youtube hoặc Vimeo" value=""/>
                                        <label>Video Vimeo: <span class="dec-icon"><i class="fab fa-vimeo-v"></i></span></label>
                                        <input type="text" placeholder="Youtube hoặc Vimeo" value=""/>
                                    </div>
                                </div>
                            </div>
                            <!-- content-widget-switcher end-->                                      
                            <!-- content-widget-switcher -->    
                            <div class="col-md-4">
                                <div class="content-widget-switcher fl-wrap">
                                    <span class="content-widget-switcher-title">Tài liệu bất động sản</span>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitchmc523" checked>
                                        <label class="onoffswitch-label" for="myonoffswitchmc523">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                    <div class="content-widget-switcher-item fl-wrap">
                                        <form class="fuzone">
                                            <div class="fu-text">
                                                <span><i class="far fa-cloud-upload-alt"></i> Nhấn vào đây hoặc kéo thả file để tải lên</span>
                                                <div class="photoUpload-files fl-wrap"></div>
                                            </div>
                                            <input type="file" class="upload" multiple>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- content-widget-switcher end-->                                          
                            <!-- content-widget-switcher -->    
                            <div class="col-md-4">
                                <div class="content-widget-switcher fl-wrap">
                                    <span class="content-widget-switcher-title">Tính toán vay vốn</span>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitchmc423" checked>
                                        <label class="onoffswitch-label" for="myonoffswitchmc423">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="content-widget-switcher fl-wrap" style="margin-top: 20px">
                                    <span class="content-widget-switcher-title">Google Map</span>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitchmc923">
                                        <label class="onoffswitch-label" for="myonoffswitchmc923">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="content-widget-switcher fl-wrap" style="margin-top: 20px">
                                    <span class="content-widget-switcher-title">Form liên hệ</span>
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitchmc23">
                                        <label class="onoffswitch-label" for="myonoffswitchmc23">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- content-widget-switcher end-->
                        </div>
                    </div>
                </div>
                <!-- dasboard-widget-box  end-->
                <a href="#" class="btn  color-bg float-btn">Lưu thay đổi </a>
            </div>
        </div>
        <!-- dashboard-footer -->
        @include('components.dashboard.footer')
        <!-- dashboard-footer end -->
    </div>
    <!-- content end -->
    <div class="dashbard-bg gray-bg"></div>
</div>
