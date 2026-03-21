<!-- ========== SUBPAGE: LỊCH HẸN ========== -->
<div class="subpage" id="subpage-bookings"
  x-data="bookingsApp()"
  x-init="init()">

  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('bookings')">←</button>
    <div class="sp-title">
      <span style="display:inline-flex;align-items:center;gap:5px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Lịch hẹn xem nhà
      </span>
    </div>
    <div class="sp-actions">
      <button class="sp-action-btn" @click="fetchBookings()" title="Tải lại">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
      </button>
    </div>
  </div>

  <!-- KPI Strip -->
  <div class="kpi-strip">
    <div class="kpi-item">
      <div class="kpi-val" style="color:var(--success)" x-text="stats.today">0</div>
      <div class="kpi-lbl">Hôm nay</div>
    </div>
    <div class="kpi-item">
      <div class="kpi-val" style="color:var(--primary)" x-text="stats.this_week">0</div>
      <div class="kpi-lbl">Tuần này</div>
    </div>
    <div class="kpi-item">
      <div class="kpi-val" style="color:var(--warning)" x-text="stats.needs_update">0</div>
      <div class="kpi-lbl">Cần update</div>
    </div>
    <div class="kpi-item">
      <div class="kpi-val" x-text="stats.this_month">0</div>
      <div class="kpi-lbl">Tháng này</div>
    </div>
  </div>

  <!-- Calendar week strip -->
  <div class="cal-strip">
    <div class="cal-month-nav">
      <div class="cal-month-label" x-text="calMonthLabel">...</div>
      <div style="display:flex;gap:6px;">
        <button class="cal-nav-btn" @click="changeWeek(-1)">‹</button>
        <button class="cal-nav-btn" @click="changeWeek(1)">›</button>
      </div>
    </div>
    <div class="cal-week">
      <template x-for="day in calWeekDays" :key="day.date">
        <div class="cal-day"
          :class="{ 'active': selectedDate === day.date, 'has-event': hasBookingOnDate(day.date) }"
          @click="toggleDateFilter(day.date)">
          <div class="cal-day-name" x-text="day.name"></div>
          <div class="cal-day-num" x-text="day.num"></div>
        </div>
      </template>
    </div>
  </div>

  <!-- Tabs -->
  <div class="sp-tabs">
    <button class="sp-tab" :class="{ active: activeTab === 'upcoming' }" @click="setTab('upcoming')">Sắp tới</button>
    <button class="sp-tab" :class="{ active: activeTab === 'needs_update' }" @click="setTab('needs_update')">
      Cần update <span x-show="stats.needs_update > 0" x-text="'('+stats.needs_update+')'" style="color:var(--warning)"></span>
    </button>
    <button class="sp-tab" :class="{ active: activeTab === 'done' }" @click="setTab('done')">Đã qua</button>
  </div>

  <div class="sp-scroll">

    <!-- Loading state -->
    <div x-show="loading" style="padding:40px;text-align:center;color:var(--text-tertiary);">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
      <div style="margin-top:8px;font-size:13px;">Đang tải lịch hẹn...</div>
    </div>

    <!-- Empty state -->
    <div x-show="!loading && filteredBookings.length === 0" style="padding:40px 20px;text-align:center;color:var(--text-tertiary);">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:10px;opacity:.4"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      <div style="font-size:14px;font-weight:600;margin-bottom:4px;" x-text="emptyMessage"></div>
      <div style="font-size:12px;" x-show="selectedDate">Nhấn vào ngày trên lịch để bỏ lọc</div>
    </div>

    <!-- Section labels & booking cards -->
    <template x-if="!loading && filteredBookings.length > 0">
      <div>
        <!-- Group by date sections -->
        <template x-for="group in groupedBookings" :key="group.date">
          <div>
            <!-- Date group label -->
            <div :style="'padding:10px 14px 4px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:' + group.labelColor">
              <span x-show="group.isNeedsUpdate" style="display:inline-flex;align-items:center;gap:4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Cần cập nhật kết quả
              </span>
              <span x-show="!group.isNeedsUpdate" x-text="group.label"></span>
            </div>

            <!-- Booking cards in this group -->
            <template x-for="booking in group.bookings" :key="booking.id">
              <div class="bk-card" :class="booking.cardClass">
                <div class="bk-head">
                  <div class="bk-datetime" :style="'background:' + booking.dateColor">
                    <div class="bk-date-day" x-text="booking.dayNum"></div>
                    <div class="bk-date-mon" x-text="booking.monthLabel"></div>
                    <div class="bk-time" x-text="booking.booking_time"></div>
                  </div>
                  <div class="bk-info">
                    <div class="bk-prop" x-text="booking.property_title"></div>
                    <div class="bk-customer">
                      <span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                      </span>
                      <span x-text="booking.customer_name"></span>
                    </div>
                    <div class="bk-note" x-show="booking.internal_note" x-text="booking.internal_note"></div>
                    <!-- Done status note -->
                    <div class="bk-note" x-show="booking.customer_feedback" x-text="'Phản hồi: ' + booking.customer_feedback" style="color:var(--text-secondary)"></div>
                  </div>
                  <span class="badge" :class="booking.badgeClass" x-text="booking.badgeText"></span>
                </div>

                <!-- Action buttons -->
                <div class="bk-actions" x-show="booking.status === 'scheduled' || booking.status === 'rescheduled'">
                  <!-- Gọi khách -->
                  <a class="bk-btn" :href="booking.customer_phone ? 'tel:' + booking.customer_phone : '#'"
                    @click.prevent="booking.customer_phone ? window.location.href='tel:'+booking.customer_phone : showToast('Không có SĐT khách hàng')">
                    <span style="display:inline-flex;align-items:center;gap:4px;">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>
                      Gọi khách
                    </span>
                  </a>
                  <!-- Dời lịch -->
                  <button class="bk-btn warning" @click="openReschedule(booking)">
                    <span style="display:inline-flex;align-items:center;gap:4px;">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                      Dời lịch
                    </span>
                  </button>
                  <!-- Cập nhật kết quả -->
                  <button class="bk-btn primary" @click="openResult(booking)">✓ Cập nhật</button>
                </div>

                <!-- Needs update: only update button -->
                <div class="bk-actions" x-show="booking.status !== 'scheduled' && booking.status !== 'rescheduled' && activeTab === 'needs_update'">
                  <button class="bk-btn primary" style="flex:2" @click="openResult(booking)">✓ Cập nhật kết quả ngay</button>
                </div>

                <!-- Reschedule panel -->
                <div class="bk-result-panel" x-show="rescheduleBookingId === booking.id" x-transition>
                  <div style="font-size:11px;font-weight:600;color:var(--text-tertiary);margin-bottom:10px;text-transform:uppercase;letter-spacing:.04em;">Dời lịch hẹn</div>
                  <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;">
                    <div>
                      <label style="font-size:11px;color:var(--text-tertiary);display:block;margin-bottom:4px;">Ngày mới</label>
                      <input type="date" x-model="rescheduleDate" :min="today"
                        style="width:100%;padding:8px;border:1.5px solid var(--border);border-radius:8px;font-size:14px;background:var(--surface);color:var(--text-primary);">
                    </div>
                    <div>
                      <label style="font-size:11px;color:var(--text-tertiary);display:block;margin-bottom:4px;">Giờ mới</label>
                      <input type="time" x-model="rescheduleTime"
                        style="width:100%;padding:8px;border:1.5px solid var(--border);border-radius:8px;font-size:14px;background:var(--surface);color:var(--text-primary);">
                    </div>
                  </div>
                  <div style="display:flex;gap:8px;">
                    <button class="bk-btn" style="flex:1" @click="rescheduleBookingId = null">Huỷ</button>
                    <button class="bk-btn primary" style="flex:2"
                      :disabled="!rescheduleDate || !rescheduleTime || submitting"
                      @click="submitReschedule(booking.id)">
                      <span x-show="!submitting">✓ Xác nhận dời lịch</span>
                      <span x-show="submitting">Đang lưu...</span>
                    </button>
                  </div>
                </div>

                <!-- Result panel -->
                <div class="bk-result-panel" x-show="resultBookingId === booking.id" x-transition>
                  <div style="font-size:11px;font-weight:600;color:var(--text-tertiary);margin-bottom:8px;text-transform:uppercase;letter-spacing:.04em;">Kết quả buổi xem nhà</div>
                  <div class="bk-result-opts">
                    <div class="bk-result-opt" :class="{ selected: resultStatus === 'completed_success' }"
                      @click="resultStatus = 'completed_success'">
                      <div class="bk-result-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                      </div>Ưng ý
                    </div>
                    <div class="bk-result-opt" :class="{ selected: resultStatus === 'completed_negotiating' }"
                      @click="resultStatus = 'completed_negotiating'">
                      <div class="bk-result-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="15" x2="16" y2="15"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                      </div>Cân nhắc
                    </div>
                    <div class="bk-result-opt" :class="{ selected: resultStatus === 'completed_failed' }"
                      @click="resultStatus = 'completed_failed'">
                      <div class="bk-result-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                      </div>Không ưng
                    </div>
                  </div>
                  <textarea class="lc-note-area" rows="2"
                    placeholder="Phản hồi chi tiết của khách (VD: Thích view nhưng đường hẻm nhỏ...)"
                    x-model="resultFeedback"></textarea>
                  <div style="display:flex;gap:8px;">
                    <button class="bk-btn" style="flex:1" @click="resultBookingId = null; resultStatus = null; resultFeedback = ''">Huỷ</button>
                    <button class="bk-result-confirm" style="flex:2"
                      :disabled="!resultStatus || submitting"
                      @click="submitResult(booking.id)">
                      <span x-show="!submitting">✓ Xác nhận kết quả</span>
                      <span x-show="submitting">Đang lưu...</span>
                    </button>
                  </div>
                </div>

                <!-- Cancel button (only for upcoming) -->
                <div x-show="(booking.status === 'scheduled' || booking.status === 'rescheduled') && booking.booking_date >= today"
                  style="padding:0 12px 10px;display:flex;justify-content:flex-end;">
                  <button style="font-size:11px;color:var(--danger);background:none;border:none;padding:4px 0;cursor:pointer;"
                    @click="cancelBooking(booking)">
                    ✕ Huỷ lịch này
                  </button>
                </div>
              </div>
            </template>
          </div>
        </template>
      </div>
    </template>

    <div style="height:16px"></div>
  </div>
</div>

<script>
function bookingsApp() {
  return {
    loading: false,
    submitting: false,
    bookings: [],
    stats: { today: 0, this_week: 0, needs_update: 0, this_month: 0 },
    activeTab: 'upcoming',
    selectedDate: null,
    weekOffset: 0,
    rescheduleBookingId: null,
    rescheduleDate: '',
    rescheduleTime: '',
    resultBookingId: null,
    resultStatus: null,
    resultFeedback: '',
    today: '{{ now()->toDateString() }}',

    init() {
      // Set CSRF token for all axios requests
      const token = document.querySelector('meta[name="csrf-token"]');
      if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

      // Expose global refresh function so openSubpage() can call it
      window.loadBookings = () => this.fetchBookings();

      this.fetchBookings();
    },

    async fetchBookings() {
      this.loading = true;
      try {
        const res = await axios.get('/webapp/api/bookings');
        this.bookings = res.data.bookings;
        this.stats = res.data.stats;
      } catch (e) {
        if (typeof showToast === 'function') showToast('Không thể tải lịch hẹn');
      } finally {
        this.loading = false;
      }
    },

    setTab(tab) {
      this.activeTab = tab;
      this.selectedDate = null;
      this.rescheduleBookingId = null;
      this.resultBookingId = null;
    },

    get filteredBookings() {
      const activeStatuses = ['scheduled', 'rescheduled'];
      const doneStatuses = ['completed_success', 'completed_negotiating', 'completed_failed', 'cancelled'];

      return this.bookings.filter(b => {
        if (this.selectedDate && b.booking_date !== this.selectedDate) return false;

        if (this.activeTab === 'upcoming') {
          return b.booking_date >= this.today && activeStatuses.includes(b.status);
        }
        if (this.activeTab === 'needs_update') {
          return b.booking_date < this.today && activeStatuses.includes(b.status);
        }
        if (this.activeTab === 'done') {
          return doneStatuses.includes(b.status);
        }
        return false;
      });
    },

    get groupedBookings() {
      if (this.activeTab === 'needs_update') {
        return [{ date: 'needs_update', label: 'Cần cập nhật kết quả', labelColor: 'var(--warning)', isNeedsUpdate: true, bookings: this.filteredBookings.map(b => this.enrichBooking(b)) }];
      }

      const groups = {};
      this.filteredBookings.forEach(b => {
        const key = b.booking_date || 'unknown';
        if (!groups[key]) groups[key] = [];
        groups[key].push(this.enrichBooking(b));
      });

      return Object.keys(groups).sort().map(date => {
        const label = this.dateGroupLabel(date);
        return {
          date,
          label: label.text,
          labelColor: label.color,
          isNeedsUpdate: false,
          bookings: groups[date],
        };
      });
    },

    enrichBooking(b) {
      const dateObj = b.booking_date ? new Date(b.booking_date + 'T00:00:00') : null;
      const dayNum = dateObj ? dateObj.getDate() : '--';
      const monthNames = ['Thg 1','Thg 2','Thg 3','Thg 4','Thg 5','Thg 6','Thg 7','Thg 8','Thg 9','Thg 10','Thg 11','Thg 12'];
      const monthLabel = dateObj ? monthNames[dateObj.getMonth()] : '';

      const proximity = this.getProximity(b.booking_date, b.status, b.booking_time);

      return {
        ...b,
        dayNum,
        monthLabel,
        dateColor: proximity.color,
        badgeText: proximity.badge,
        badgeClass: proximity.badgeClass,
        cardClass: proximity.cardClass,
      };
    },

    getProximity(dateStr, status, bookingTime) {
      if (!dateStr) return { color: 'var(--text-tertiary)', badge: '', badgeClass: '', cardClass: '' };

      const doneStatuses = ['completed_success', 'completed_negotiating', 'completed_failed', 'cancelled'];
      if (doneStatuses.includes(status)) {
        const labels = {
          completed_success: { badge: 'Ưng ý', badgeClass: 'badge-green' },
          completed_negotiating: { badge: 'Cân nhắc', badgeClass: 'badge-blue' },
          completed_failed: { badge: 'Không ưng', badgeClass: 'badge-red' },
          cancelled: { badge: 'Đã huỷ', badgeClass: 'badge-gray' },
        };
        const l = labels[status] || { badge: status, badgeClass: '' };
        return { color: 'var(--text-tertiary)', badge: l.badge, badgeClass: l.badgeClass, cardClass: '' };
      }

      if (dateStr < this.today) {
        return { color: 'var(--warning)', badge: '⚠ Chưa update', badgeClass: 'badge-amber', cardClass: 'rescheduled' };
      }

      const todayDate = new Date(this.today + 'T00:00:00');
      const bookDate = new Date(dateStr + 'T00:00:00');
      const diffDays = Math.round((bookDate - todayDate) / 86400000);

      if (diffDays === 0) {
        // Check morning/afternoon
        const hour = bookingTime ? parseInt(bookingTime.split(':')[0]) : 9;
        const timeLabel = hour < 12 ? 'Sáng nay' : (hour < 17 ? 'Chiều nay' : 'Tối nay');
        return { color: 'var(--success)', badge: timeLabel, badgeClass: 'badge-green', cardClass: 'today' };
      }
      if (diffDays === 1) {
        return { color: 'var(--primary)', badge: 'Ngày mai', badgeClass: 'badge-blue', cardClass: 'upcoming' };
      }
      if (diffDays <= 6) {
        return { color: 'var(--primary)', badge: diffDays + ' ngày tới', badgeClass: 'badge-blue', cardClass: 'upcoming' };
      }
      return { color: 'var(--text-tertiary)', badge: this.formatDateShort(dateStr), badgeClass: '', cardClass: 'upcoming' };
    },

    dateGroupLabel(dateStr) {
      if (!dateStr || dateStr === 'unknown') return { text: 'Không có ngày', color: 'var(--text-tertiary)' };

      const diffDays = Math.round((new Date(dateStr + 'T00:00:00') - new Date(this.today + 'T00:00:00')) / 86400000);
      const formatted = this.formatDateVN(dateStr);

      if (diffDays === 0) return { text: 'Hôm nay — ' + formatted, color: 'var(--success)' };
      if (diffDays === 1) return { text: 'Ngày mai — ' + formatted, color: 'var(--primary)' };
      if (diffDays < 0) return { text: formatted, color: 'var(--text-tertiary)' };
      return { text: 'Sắp tới — ' + formatted, color: 'var(--primary)' };
    },

    formatDateVN(dateStr) {
      if (!dateStr) return '';
      const d = new Date(dateStr + 'T00:00:00');
      return d.getDate() + '/' + String(d.getMonth() + 1).padStart(2, '0');
    },

    formatDateShort(dateStr) {
      if (!dateStr) return '';
      const d = new Date(dateStr + 'T00:00:00');
      return d.getDate() + '/' + String(d.getMonth() + 1).padStart(2, '0');
    },

    get emptyMessage() {
      if (this.activeTab === 'upcoming') return 'Không có lịch hẹn sắp tới';
      if (this.activeTab === 'needs_update') return 'Không có lịch cần cập nhật';
      return 'Chưa có lịch hẹn đã qua';
    },

    // Calendar
    get calWeekDays() {
      const dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
      const today = new Date(this.today + 'T00:00:00');
      // Monday of current week
      const monday = new Date(today);
      const dow = today.getDay(); // 0=Sun
      const daysToMon = dow === 0 ? -6 : 1 - dow;
      monday.setDate(monday.getDate() + daysToMon + this.weekOffset * 7);

      const days = [];
      for (let i = 0; i < 7; i++) {
        const d = new Date(monday);
        d.setDate(monday.getDate() + i);
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        days.push({
          date: `${yyyy}-${mm}-${dd}`,
          name: dayNames[d.getDay()],
          num: d.getDate(),
        });
      }
      return days;
    },

    get calMonthLabel() {
      const days = this.calWeekDays;
      if (!days.length) return '';
      const first = new Date(days[0].date + 'T00:00:00');
      return 'Tháng ' + (first.getMonth() + 1) + ', ' + first.getFullYear();
    },

    changeWeek(delta) {
      this.weekOffset += delta;
      this.selectedDate = null;
    },

    toggleDateFilter(date) {
      this.selectedDate = this.selectedDate === date ? null : date;
    },

    hasBookingOnDate(date) {
      return this.bookings.some(b => b.booking_date === date && ['scheduled', 'rescheduled'].includes(b.status));
    },

    // Actions
    openReschedule(booking) {
      this.resultBookingId = null;
      this.rescheduleBookingId = this.rescheduleBookingId === booking.id ? null : booking.id;
      this.rescheduleDate = booking.booking_date || '';
      this.rescheduleTime = booking.booking_time || '';
    },

    openResult(booking) {
      this.rescheduleBookingId = null;
      this.resultBookingId = this.resultBookingId === booking.id ? null : booking.id;
      this.resultStatus = null;
      this.resultFeedback = booking.customer_feedback || '';
    },

    async submitResult(bookingId) {
      if (!this.resultStatus || this.submitting) return;
      this.submitting = true;
      try {
        await axios.patch(`/webapp/api/bookings/${bookingId}/result`, {
          status: this.resultStatus,
          customer_feedback: this.resultFeedback,
        });
        this.resultBookingId = null;
        this.resultStatus = null;
        this.resultFeedback = '';
        await this.fetchBookings();
        if (typeof showToast === 'function') showToast('✓ Đã cập nhật kết quả!');
      } catch (e) {
        if (typeof showToast === 'function') showToast('Lỗi: Không thể cập nhật');
      } finally {
        this.submitting = false;
      }
    },

    async submitReschedule(bookingId) {
      if (!this.rescheduleDate || !this.rescheduleTime || this.submitting) return;
      this.submitting = true;
      try {
        await axios.patch(`/webapp/api/bookings/${bookingId}/reschedule`, {
          booking_date: this.rescheduleDate,
          booking_time: this.rescheduleTime,
        });
        this.rescheduleBookingId = null;
        await this.fetchBookings();
        if (typeof showToast === 'function') showToast('✓ Đã dời lịch thành công!');
      } catch (e) {
        const msg = e.response?.data?.errors ? Object.values(e.response.data.errors).flat().join(', ') : 'Không thể dời lịch';
        if (typeof showToast === 'function') showToast('Lỗi: ' + msg);
      } finally {
        this.submitting = false;
      }
    },

    async cancelBooking(booking) {
      if (!confirm(`Huỷ lịch hẹn "${booking.property_title}" với ${booking.customer_name}?`)) return;
      try {
        await axios.patch(`/webapp/api/bookings/${booking.id}/cancel`);
        await this.fetchBookings();
        if (typeof showToast === 'function') showToast('Đã huỷ lịch hẹn');
      } catch (e) {
        if (typeof showToast === 'function') showToast('Lỗi: Không thể huỷ lịch');
      }
    },
  };
}
</script>

<style>
.bk-result-opt.selected {
  border-color: var(--primary) !important;
  background: var(--primary-light) !important;
  color: var(--primary) !important;
}
.bk-result-opt.selected .bk-result-icon {
  color: var(--primary);
}
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>
