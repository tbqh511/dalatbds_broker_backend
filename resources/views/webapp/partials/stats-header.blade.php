<!-- stats-bar removed -->
{{-- <div class="sp-summary">
  @if(isset($stats) && is_array($stats))
    @foreach($stats as $stat)
    <div class="sp-sum-item" @if(!empty($stat['onclick'])) onclick="{{ $stat['onclick'] }}" style="cursor:pointer;" @endif>
      <div class="sp-sum-val" id="{{ $stat['id'] ?? '' }}" style="color:{{ $stat['color'] ?? 'var(--text-primary)' }};">
        {{ $stat['value'] ?? '—' }}
      </div>
      <div class="sp-sum-lbl">{{ $stat['label'] ?? '' }}</div>
    </div>
    @endforeach
  @endif
</div> --}}

<!-- Search bar -->
<div class="sp-searchbar">
  <div class="sp-search-input">
    <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
    </span>
    <input type="text" id="{{ $searchInputId ?? 'searchInput' }}" placeholder="{{ $searchPlaceholder ?? 'Tìm kiếm...' }}" oninput="{{ $onSearchInput ?? '' }}(this.value)">
  </div>
  <button class="sp-filter-btn" onclick="openFilterSheet('{{ $filterSheetId ?? 'filterSheet' }}')">
    <span style="display:inline-flex;align-items:center;gap:4px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M16.24 7.76a6 6 0 0 1 0 8.49M4.93 4.93a10 10 0 0 0 0 14.14M7.76 7.76a6 6 0 0 0 0 8.49"/></svg>
      Lọc
    </span>
  </button>
</div>
