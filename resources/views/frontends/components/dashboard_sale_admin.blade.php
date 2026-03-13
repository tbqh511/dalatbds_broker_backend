<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            @include('components.dashboard.header', ['title' => 'Sale Admin Dashboard'])

            <div class="dasboard-wrapper fl-wrap">

                {{-- Stats --}}
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-chart-bar"></i>Thống kê tổng quan</h5>
                </div>
                <div class="dasboard-widget-box fl-wrap" style="margin-bottom:20px">
                    <div class="row" style="text-align:center">
                        <div class="col-6 col-md-3" style="padding:10px">
                            <div class="stat-card">
                                <div class="stat-number">{{ $stats['total_leads'] }}</div>
                                <div class="stat-label">Tổng Leads</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3" style="padding:10px">
                            <div class="stat-card stat-warning">
                                <div class="stat-number">{{ $stats['unassigned_leads'] }}</div>
                                <div class="stat-label">Chưa phân công</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3" style="padding:10px">
                            <div class="stat-card stat-success">
                                <div class="stat-number">{{ $stats['converted_leads'] }}</div>
                                <div class="stat-label">Đã chuyển đổi</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3" style="padding:10px">
                            <div class="stat-card stat-info">
                                <div class="stat-number">{{ $stats['total_sales'] }}</div>
                                <div class="stat-label">Nhân viên Sale</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sales Team --}}
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-users"></i>Đội ngũ Sale</h5>
                </div>
                <div class="dasboard-widget-box fl-wrap" style="margin-bottom:20px">
                    @forelse($salesTeam as $sale)
                    <div class="sale-team-item">
                        <div class="sti-info">
                            <strong>{{ $sale->name }}</strong>
                            <span class="sti-role sale-role-{{ $sale->role }}">{{ $sale->role === 'sale_admin' ? 'Sale Admin' : 'Sale' }}</span>
                            @if($sale->mobile)
                            <span style="color:#888;font-size:13px;margin-left:6px">{{ $sale->mobile }}</span>
                            @endif
                        </div>
                        <a href="tel:{{ $sale->mobile }}" class="sti-call"><i class="far fa-phone"></i></a>
                    </div>
                    @empty
                    <p style="color:#aaa;font-size:14px">Chưa có nhân viên sale nào.</p>
                    @endforelse
                </div>

                {{-- Unassigned Leads --}}
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-exclamation-circle"></i>Leads chưa phân công</h5>
                </div>
                <div class="dasboard-widget-box fl-wrap">
                    @forelse($unassignedLeads as $lead)
                    <div class="unassigned-lead-item">
                        <div class="uli-info">
                            <strong>{{ $lead->customer?->full_name ?? 'Khách vãng lai' }}</strong>
                            <span class="new-bookmark status-{{ strtolower($lead->getRawOriginal('status')) }}" style="margin-left:6px">
                                {{ $lead->status }}
                            </span>
                            <div style="font-size:12px;color:#888;margin-top:2px">
                                {{ $lead->lead_type === 'Buy' ? 'Mua' : 'Thuê' }}
                                · {{ format_vnd($lead->demand_rate_min) }}–{{ format_vnd($lead->demand_rate_max) }}
                                · {{ $lead->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        <a href="{{ route('webapp.leads.show', $lead->id) }}" class="btn-sm color-bg" style="white-space:nowrap">
                            <i class="fal fa-eye"></i> Xem & Phân công
                        </a>
                    </div>
                    @empty
                    <p style="color:#aaa;font-size:14px">Tất cả leads đã được phân công.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>
