@php $activeRole = isset($customer) ? $customer->getEffectiveRole() : 'guest'; @endphp
  <div class="role-switcher">
    <button class="rbtn {{ $activeRole === 'guest' ? 'active' : '' }}" onclick="setRole('guest',this)">👤 Guest</button>
    <button class="rbtn {{ $activeRole === 'broker' ? 'active' : '' }}" onclick="setRole('broker',this)">🏠 Broker</button>
    <button class="rbtn {{ $activeRole === 'bds_admin' ? 'active' : '' }}" onclick="setRole('bds_admin',this)">🏘️ BĐS Admin</button>
    <button class="rbtn {{ $activeRole === 'sale' ? 'active' : '' }}" onclick="setRole('sale',this)">💼 Sale</button>
    <button class="rbtn {{ $activeRole === 'sale_admin' ? 'active' : '' }}" onclick="setRole('sale_admin',this)">📋 Sale Admin</button>
    <button class="rbtn {{ $activeRole === 'admin' ? 'active' : '' }}" onclick="setRole('admin',this)">👑 Admin</button>
  </div>
