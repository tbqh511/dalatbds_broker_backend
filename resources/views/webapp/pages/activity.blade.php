  <div class="page" id="page-activity" x-data="activityApp()" x-init="init()">

    <div class="notif-tabs-bar">
      <div class="notif-tabs">
        <template x-for="tab in tabs" :key="tab.key">
          <button class="ntab"
            :class="{ 'active': activeTab === tab.key }"
            @click="switchTab(tab.key)"
            x-text="tab.label"></button>
        </template>
      </div>
      <button x-cloak x-show="hasUnread"
        @click="markAllRead()"
        :disabled="markingAll"
        class="notif-mark-all-btn"
        style="flex-shrink:0;font-size:12px;color:var(--primary);background:none;border:none;padding:6px 4px 6px 8px;cursor:pointer;white-space:nowrap;"
        x-text="markingAll ? 'Đang xử lý...' : 'Đọc tất cả'"></button>
    </div>

    <!-- Loading -->
    <div x-cloak x-show="loading && notifications.length === 0" style="text-align:center;padding:40px;">
      <div style="color:var(--text-tertiary);font-size:13px;">Đang tải...</div>
    </div>

    <!-- Empty -->
    <div x-cloak x-show="!loading && notifications.length === 0" style="text-align:center;padding:40px;">
      <div style="color:var(--text-tertiary);font-size:13px;">Chưa có thông báo nào</div>
    </div>

    <!-- Notification items -->
    <template x-for="notif in notifications" :key="notif.id">
      <div class="notif-item"
        :class="{ 'unread': notif.is_unread }"
        @click="openDetail(notif)"
        style="cursor:pointer;">
        <div class="notif-icon"
          :style="'background:' + notif.type_config.icon_bg + ';display:flex;align-items:center;justify-content:center;'"
          x-html="getIconSvg(notif.type_config.icon)"></div>
        <div class="notif-body">
          <div class="notif-title" x-text="notif.title"></div>
          <div class="notif-desc" x-text="notif.body"></div>
          <div class="notif-actions" @click.stop>
            <template x-for="action in getActions(notif)" :key="action.label">
              <button class="notif-action-btn"
                :class="{ 'primary': action.primary }"
                :style="action.style || ''"
                @click="handleAction(action, notif)"
                x-html="action.html"></button>
            </template>
            <span x-show="notif.type === 'property_pending' && notif.data && notif.data.handled_by_id"
              style="font-size:11px;color:var(--text-tertiary);padding:2px 0;">
              Đã xử lý bởi admin khác
            </span>
          </div>
          <div class="notif-time" x-text="notif.time_ago"></div>
        </div>
      </div>
    </template>

    <!-- Load more -->
    <div x-cloak x-show="hasMore && !loading" style="padding:16px;text-align:center;">
      <button @click="loadMore()" style="padding:10px 20px;border:1px solid var(--border);border-radius:20px;font-size:13px;color:var(--text-secondary);background:var(--bg-card);">
        Xem thêm hoạt động
      </button>
    </div>

    <!-- Loading more indicator -->
    <div x-cloak x-show="loading && notifications.length > 0" style="text-align:center;padding:12px;">
      <div style="color:var(--text-tertiary);font-size:12px;">Đang tải thêm...</div>
    </div>

  </div><!-- end page-activity -->
