/* @ds-bundle: {"format":4,"namespace":"LTBSDesignSystem_ab8fed","components":[{"name":"Avatar","sourcePath":"components/core/Avatar.jsx"},{"name":"Badge","sourcePath":"components/core/Badge.jsx"},{"name":"Button","sourcePath":"components/core/Button.jsx"},{"name":"Chip","sourcePath":"components/core/Chip.jsx"},{"name":"ChipRow","sourcePath":"components/core/Chip.jsx"},{"name":"IconGlyphs","sourcePath":"components/core/Icon.jsx"},{"name":"Icon","sourcePath":"components/core/Icon.jsx"},{"name":"IconButton","sourcePath":"components/core/IconButton.jsx"},{"name":"Input","sourcePath":"components/core/Input.jsx"},{"name":"BottomSheet","sourcePath":"components/feedback/BottomSheet.jsx"},{"name":"HeroCard","sourcePath":"components/feedback/HeroCard.jsx"},{"name":"Toast","sourcePath":"components/feedback/Toast.jsx"},{"name":"ROLES","sourcePath":"components/identity/RoleBadge.jsx"},{"name":"RoleBadge","sourcePath":"components/identity/RoleBadge.jsx"},{"name":"BottomNav","sourcePath":"components/navigation/BottomNav.jsx"},{"name":"Tabs","sourcePath":"components/navigation/Tabs.jsx"},{"name":"MarketPriceItem","sourcePath":"components/realestate/MarketPriceItem.jsx"},{"name":"MarketPriceStrip","sourcePath":"components/realestate/MarketPriceItem.jsx"},{"name":"MenuItem","sourcePath":"components/realestate/MenuItem.jsx"},{"name":"PropCard","sourcePath":"components/realestate/PropCard.jsx"},{"name":"SectionHeader","sourcePath":"components/realestate/SectionHeader.jsx"},{"name":"StatCard","sourcePath":"components/realestate/StatCard.jsx"}],"sourceHashes":{"components/core/Avatar.jsx":"9fe957258fdf","components/core/Badge.jsx":"e1a6bf861f32","components/core/Button.jsx":"19c2b4db689f","components/core/Chip.jsx":"2212873166b2","components/core/Icon.jsx":"28362a733952","components/core/IconButton.jsx":"4a21943779cb","components/core/Input.jsx":"f9636e474c03","components/feedback/BottomSheet.jsx":"ba56cca330b7","components/feedback/HeroCard.jsx":"8b641c7e7e6b","components/feedback/Toast.jsx":"ee32a39b2af6","components/identity/RoleBadge.jsx":"6f025066ecf7","components/navigation/BottomNav.jsx":"7bb2be43869b","components/navigation/Tabs.jsx":"53f8f3b0a4c8","components/realestate/MarketPriceItem.jsx":"d2dd2b8aaca6","components/realestate/MenuItem.jsx":"c4b4bc39bf23","components/realestate/PropCard.jsx":"4407d302a772","components/realestate/SectionHeader.jsx":"af66f7e996ff","components/realestate/StatCard.jsx":"d4344bc7230e","ui_kits/webapp/CrmScreens.jsx":"f5556a44db54","ui_kits/webapp/DetailScreen.jsx":"cd73c5b5dc30","ui_kits/webapp/HomeScreen.jsx":"4e5b3738a3a7","ui_kits/webapp/ProfileScreen.jsx":"9454466eb73d","ui_kits/webapp/data.js":"c17817d9d25b"},"inlinedExternals":[],"unexposedExports":[]} */

(() => {

const __ds_ns = (window.LTBSDesignSystem_ab8fed = window.LTBSDesignSystem_ab8fed || {});

const __ds_scope = {};

(__ds_ns.__errors = __ds_ns.__errors || []);

// components/core/Avatar.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS Avatar — user/owner image with graceful initials fallback.
   Tone tints the initials chip (owner cards rotate teal/purple/blue). */
const TONES = {
  blue: {
    fg: "var(--primary)",
    bg: "var(--primary-light)"
  },
  teal: {
    fg: "var(--teal)",
    bg: "var(--teal-light)"
  },
  purple: {
    fg: "var(--purple)",
    bg: "var(--purple-light)"
  },
  amber: {
    fg: "#b45309",
    bg: "var(--warning-light)"
  },
  green: {
    fg: "var(--success)",
    bg: "var(--success-light)"
  }
};
function Avatar({
  src,
  name = "",
  size = 44,
  tone = "blue",
  ring = false,
  square = false,
  style = {},
  ...rest
}) {
  const t = TONES[tone] || TONES.blue;
  const initials = name.trim().split(/\s+/).filter(Boolean).map(w => w[0]).slice(0, 2).join("").toUpperCase() || "?";
  const box = {
    width: size,
    height: size,
    flexShrink: 0,
    borderRadius: square ? "var(--radius-md)" : "var(--radius-pill)",
    overflow: "hidden",
    display: "inline-flex",
    alignItems: "center",
    justifyContent: "center",
    background: t.bg,
    color: t.fg,
    fontFamily: "var(--font-sans)",
    fontWeight: "var(--fw-bold)",
    fontSize: Math.max(11, Math.round(size * 0.36)),
    boxShadow: ring ? "0 0 0 2.5px var(--bg-card), 0 0 0 4px var(--primary-light)" : "none",
    ...style
  };
  return /*#__PURE__*/React.createElement("span", _extends({
    style: box
  }, rest), src ? /*#__PURE__*/React.createElement("img", {
    src: src,
    alt: name,
    style: {
      width: "100%",
      height: "100%",
      objectFit: "cover"
    }
  }) : initials);
}
Object.assign(__ds_scope, { Avatar });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Avatar.jsx", error: String((e && e.message) || e) }); }

// components/core/Badge.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS Badge — small status pill on cards, list rows, headers.
   Tone controls fg/bg pair; `solid` fills for high-emphasis (price/label). */
const TONES = {
  blue: {
    fg: "var(--primary)",
    bg: "var(--primary-light)"
  },
  green: {
    fg: "var(--success)",
    bg: "var(--success-light)"
  },
  amber: {
    fg: "#b45309",
    bg: "var(--warning-light)"
  },
  red: {
    fg: "var(--danger)",
    bg: "var(--danger-light)"
  },
  purple: {
    fg: "var(--purple)",
    bg: "var(--purple-light)"
  },
  teal: {
    fg: "var(--teal)",
    bg: "var(--teal-light)"
  },
  gray: {
    fg: "var(--text-secondary)",
    bg: "var(--bg-secondary)"
  }
};
function Badge({
  children,
  tone = "blue",
  solid = false,
  dot = false,
  style = {},
  ...rest
}) {
  const t = TONES[tone] || TONES.blue;
  const base = {
    display: "inline-flex",
    alignItems: "center",
    gap: dot ? "5px" : 0,
    padding: "3px 9px",
    fontFamily: "var(--font-sans)",
    fontSize: "var(--fs-10)",
    fontWeight: "var(--fw-semibold)",
    lineHeight: 1.4,
    borderRadius: "var(--radius-pill)",
    whiteSpace: "nowrap",
    background: solid ? t.fg : t.bg,
    color: solid ? "#fff" : t.fg
  };
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      ...base,
      ...style
    }
  }, rest), dot && /*#__PURE__*/React.createElement("span", {
    style: {
      width: 5,
      height: 5,
      borderRadius: "50%",
      background: solid ? "#fff" : t.fg,
      flexShrink: 0
    }
  }), children);
}
Object.assign(__ds_scope, { Badge });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Badge.jsx", error: String((e && e.message) || e) }); }

// components/core/Chip.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS Chip — horizontally-scrolling filter pill (home feed filters:
   Tất cả / Đất ở / Nhà phố …). Active = filled primary. */
function Chip({
  children,
  active = false,
  onClick,
  style = {},
  ...rest
}) {
  const base = {
    display: "inline-flex",
    alignItems: "center",
    gap: "5px",
    padding: "7px 15px",
    fontFamily: "var(--font-sans)",
    fontSize: "var(--fs-13)",
    fontWeight: "var(--fw-medium)",
    lineHeight: 1,
    borderRadius: "var(--radius-pill)",
    whiteSpace: "nowrap",
    cursor: "pointer",
    userSelect: "none",
    transition: "background var(--dur-fast), color var(--dur-fast), border-color var(--dur-fast)",
    border: active ? "1px solid transparent" : "1px solid var(--border)",
    background: active ? "var(--primary)" : "var(--bg-card)",
    color: active ? "#fff" : "var(--text-secondary)",
    fontWeight: active ? "var(--fw-semibold)" : "var(--fw-medium)"
  };
  return /*#__PURE__*/React.createElement("button", _extends({
    type: "button",
    onClick: onClick,
    style: {
      ...base,
      ...style
    }
  }, rest), children);
}

/* Row wrapper: horizontal scroll strip that hides its scrollbar. */
function ChipRow({
  children,
  style = {},
  ...rest
}) {
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      display: "flex",
      gap: "8px",
      overflowX: "auto",
      padding: "2px 0",
      scrollbarWidth: "none",
      msOverflowStyle: "none",
      ...style
    }
  }, rest), children);
}
Object.assign(__ds_scope, { Chip, ChipRow });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Chip.jsx", error: String((e && e.message) || e) }); }

// components/core/Icon.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS icon set — Lucide/Feather geometry (24×24, stroke 1.7, round caps),
   the exact glyphs used across the WebApp. Stroke inherits currentColor. */
const PATHS = {
  home: '<path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V13h6v9"/>',
  search: '<circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/>',
  bell: '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>',
  plus: '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
  user: '<circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>',
  users: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
  heart: '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
  star: '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
  calendar: '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
  mapPin: '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
  phone: '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/>',
  share: '<path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/>',
  send: '<line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>',
  edit: '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
  building: '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
  briefcase: '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>',
  target: '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
  trendingUp: '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
  barChart: '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
  check: '<polyline points="20 6 9 17 4 12"/>',
  chevronLeft: '<polyline points="15 18 9 12 15 6"/>',
  chevronRight: '<polyline points="9 18 15 12 9 6"/>',
  chevronDown: '<polyline points="6 9 12 15 18 9"/>',
  shield: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
  fileText: '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
  eye: '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
  dollarSign: '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
  layers: '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
  logout: '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>',
  x: '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
  alertTriangle: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
  helpCircle: '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
  maximize: '<polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>',
  refresh: '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>',
  compass: '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
  bed: '<path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M2 15h20"/><path d="M6 10V6a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4"/>',
  grid: '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>',
  link: '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>'
};
const IconGlyphs = Object.keys(PATHS);
function Icon({
  name,
  size = 20,
  stroke = 1.7,
  fill = "none",
  color = "currentColor",
  className = "",
  style = {},
  ...rest
}) {
  const inner = PATHS[name];
  return /*#__PURE__*/React.createElement("svg", _extends({
    width: size,
    height: size,
    viewBox: "0 0 24 24",
    fill: fill,
    stroke: color,
    strokeWidth: stroke,
    strokeLinecap: "round",
    strokeLinejoin: "round",
    className: className,
    style: {
      display: "block",
      flexShrink: 0,
      ...style
    },
    "aria-hidden": "true",
    dangerouslySetInnerHTML: {
      __html: inner || ""
    }
  }, rest));
}
Object.assign(__ds_scope, { IconGlyphs, Icon });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Icon.jsx", error: String((e && e.message) || e) }); }

// components/core/Button.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS Button — the CRM action button. Solid primary by default;
   accent tones (green/amber/purple) map to CRM verbs (book / approve / assign). */
const TONES = {
  primary: {
    bg: "var(--primary)",
    fg: "#fff",
    shadow: "var(--shadow-primary)"
  },
  green: {
    bg: "var(--success)",
    fg: "#fff",
    shadow: "0 2px 8px rgba(5,150,105,0.25)"
  },
  amber: {
    bg: "var(--warning)",
    fg: "#fff",
    shadow: "0 2px 8px rgba(217,119,6,0.25)"
  },
  purple: {
    bg: "var(--purple)",
    fg: "#fff",
    shadow: "0 2px 8px rgba(124,58,237,0.25)"
  },
  danger: {
    bg: "var(--danger)",
    fg: "#fff",
    shadow: "0 2px 8px rgba(239,68,68,0.22)"
  }
};
const SIZES = {
  sm: {
    pad: "8px 14px",
    fs: "13px",
    gap: "5px",
    icon: 14,
    radius: "var(--radius-sm)"
  },
  md: {
    pad: "12px 18px",
    fs: "14px",
    gap: "6px",
    icon: 16,
    radius: "var(--radius-md)"
  },
  lg: {
    pad: "14px 22px",
    fs: "15px",
    gap: "7px",
    icon: 18,
    radius: "var(--radius-md)"
  }
};
function Button({
  children,
  variant = "solid",
  // solid | outline | soft | ghost
  tone = "primary",
  // primary | green | amber | purple | danger
  size = "md",
  icon,
  // Icon name (left)
  iconRight,
  // Icon name (right)
  block = false,
  disabled = false,
  style = {},
  ...rest
}) {
  const t = TONES[tone] || TONES.primary;
  const s = SIZES[size] || SIZES.md;
  let base = {
    display: block ? "flex" : "inline-flex",
    width: block ? "100%" : "auto",
    alignItems: "center",
    justifyContent: "center",
    gap: s.gap,
    padding: s.pad,
    fontFamily: "var(--font-sans)",
    fontSize: s.fs,
    fontWeight: "var(--fw-semibold)",
    lineHeight: 1,
    borderRadius: s.radius,
    border: "1.5px solid transparent",
    cursor: disabled ? "not-allowed" : "pointer",
    opacity: disabled ? 0.45 : 1,
    transition: "transform var(--dur-fast) var(--ease-standard), background var(--dur-fast), box-shadow var(--dur-fast)",
    WebkitTapHighlightColor: "transparent",
    whiteSpace: "nowrap"
  };
  if (variant === "solid") {
    base = {
      ...base,
      background: t.bg,
      color: t.fg,
      boxShadow: t.shadow
    };
  } else if (variant === "outline") {
    base = {
      ...base,
      background: "transparent",
      color: t.bg,
      borderColor: t.bg
    };
  } else if (variant === "soft") {
    base = {
      ...base,
      background: "var(--primary-light)",
      color: t.bg,
      borderColor: "transparent"
    };
  } else if (variant === "ghost") {
    base = {
      ...base,
      background: "transparent",
      color: "var(--text-secondary)",
      borderColor: "transparent"
    };
  }
  return /*#__PURE__*/React.createElement("button", _extends({
    type: "button",
    disabled: disabled,
    style: {
      ...base,
      ...style
    },
    onMouseDown: e => {
      if (!disabled) e.currentTarget.style.transform = "scale(0.97)";
    },
    onMouseUp: e => {
      e.currentTarget.style.transform = "scale(1)";
    },
    onMouseLeave: e => {
      e.currentTarget.style.transform = "scale(1)";
    }
  }, rest), icon && /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: s.icon,
    stroke: 1.9
  }), children, iconRight && /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: iconRight,
    size: s.icon,
    stroke: 1.9
  }));
}
Object.assign(__ds_scope, { Button });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Button.jsx", error: String((e && e.message) || e) }); }

// components/core/IconButton.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS IconButton — circular/rounded touch target for headers,
   card quick-actions, sheet backs. Min 36–44px for one-handed reach. */
const SIZES = {
  sm: 32,
  md: 38,
  lg: 44
};
function IconButton({
  icon,
  size = "md",
  variant = "surface",
  // surface | soft | ghost | primary
  color,
  label,
  disabled = false,
  style = {},
  ...rest
}) {
  const dim = SIZES[size] || SIZES.md;
  let box = {
    width: dim,
    height: dim,
    display: "inline-flex",
    alignItems: "center",
    justifyContent: "center",
    borderRadius: "var(--radius-pill)",
    border: "1px solid transparent",
    cursor: disabled ? "not-allowed" : "pointer",
    opacity: disabled ? 0.45 : 1,
    flexShrink: 0,
    transition: "background var(--dur-fast), transform var(--dur-fast)",
    WebkitTapHighlightColor: "transparent"
  };
  let iconColor = color || "var(--text-secondary)";
  if (variant === "surface") {
    box = {
      ...box,
      background: "var(--bg-card)",
      borderColor: "var(--border)"
    };
    iconColor = color || "var(--text-primary)";
  } else if (variant === "soft") {
    box = {
      ...box,
      background: "var(--primary-light)"
    };
    iconColor = color || "var(--primary)";
  } else if (variant === "primary") {
    box = {
      ...box,
      background: "var(--primary)",
      boxShadow: "var(--shadow-primary)"
    };
    iconColor = "#fff";
  } else if (variant === "ghost") {
    box = {
      ...box,
      background: "transparent"
    };
  }
  return /*#__PURE__*/React.createElement("button", _extends({
    type: "button",
    "aria-label": label || icon,
    disabled: disabled,
    style: {
      ...box,
      ...style
    },
    onMouseDown: e => {
      if (!disabled) e.currentTarget.style.transform = "scale(0.92)";
    },
    onMouseUp: e => {
      e.currentTarget.style.transform = "scale(1)";
    },
    onMouseLeave: e => {
      e.currentTarget.style.transform = "scale(1)";
    }
  }, rest), /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: Math.round(dim * 0.46),
    color: iconColor
  }));
}
Object.assign(__ds_scope, { IconButton });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/IconButton.jsx", error: String((e && e.message) || e) }); }

// components/core/Input.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS Input — form field used across CRM sheets & search.
   `search` variant is the rounded pill search bar; default is the labelled field. */
function Input({
  label,
  hint,
  icon,
  variant = "field",
  // field | search
  as = "input",
  // input | textarea | select
  children,
  style = {},
  wrapStyle = {},
  ...rest
}) {
  if (variant === "search") {
    return /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        alignItems: "center",
        gap: "8px",
        padding: "9px 14px",
        background: "var(--bg-secondary)",
        borderRadius: "var(--radius-pill)",
        ...wrapStyle
      }
    }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
      name: icon || "search",
      size: 16,
      color: "var(--text-tertiary)"
    }), /*#__PURE__*/React.createElement("input", _extends({
      style: {
        flex: 1,
        border: "none",
        outline: "none",
        background: "transparent",
        fontFamily: "var(--font-sans)",
        fontSize: "var(--fs-14)",
        color: "var(--text-primary)",
        minWidth: 0,
        ...style
      }
    }, rest)));
  }
  const fieldStyle = {
    width: "100%",
    boxSizing: "border-box",
    padding: as === "textarea" ? "10px 12px" : "11px 12px",
    fontFamily: "var(--font-sans)",
    fontSize: "var(--fs-14)",
    color: "var(--text-primary)",
    background: "var(--bg-primary)",
    border: "1px solid var(--border)",
    borderRadius: "var(--radius-sm)",
    outline: "none",
    resize: as === "textarea" ? "none" : undefined,
    transition: "border-color var(--dur-fast)",
    ...style
  };
  const El = as === "textarea" ? "textarea" : as === "select" ? "select" : "input";
  return /*#__PURE__*/React.createElement("label", {
    style: {
      display: "block",
      ...wrapStyle
    }
  }, label && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-12)",
      fontWeight: "var(--fw-medium)",
      color: "var(--text-secondary)",
      marginBottom: 6
    }
  }, label), /*#__PURE__*/React.createElement(El, _extends({
    style: fieldStyle,
    onFocus: e => e.currentTarget.style.borderColor = "var(--primary)",
    onBlur: e => e.currentTarget.style.borderColor = "var(--border)"
  }, rest), as === "select" ? children : undefined), hint && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-11)",
      color: "var(--text-tertiary)",
      marginTop: 5
    }
  }, hint));
}
Object.assign(__ds_scope, { Input });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Input.jsx", error: String((e && e.message) || e) }); }

// components/feedback/BottomSheet.jsx
try { (() => {
/* Đà Lạt BĐS BottomSheet — slide-up modal for CRM actions (đặt lịch, gửi
   BĐS, cập nhật trạng thái, add/edit forms). Grabber handle + title + body. */
function BottomSheet({
  open = false,
  title,
  onClose,
  children,
  footer,
  style = {}
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      inset: 0,
      zIndex: 60,
      display: open ? "flex" : "none",
      alignItems: "flex-end",
      justifyContent: "center",
      background: "rgba(15,28,50,0.5)"
    },
    onClick: onClose
  }, /*#__PURE__*/React.createElement("div", {
    onClick: e => e.stopPropagation(),
    style: {
      width: "100%",
      maxWidth: "var(--app-max-width)",
      background: "var(--bg-card)",
      borderRadius: "var(--radius-xl) var(--radius-xl) 0 0",
      boxShadow: "var(--shadow-sheet)",
      padding: "8px 0 24px",
      maxHeight: "88%",
      overflowY: "auto",
      animation: open ? "dlbdsSheetUp var(--dur-base) var(--ease-out)" : "none",
      ...style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 38,
      height: 4,
      background: "var(--border-strong)",
      borderRadius: 2,
      margin: "0 auto 14px"
    }
  }), title && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-16)",
      fontWeight: "var(--fw-bold)",
      color: "var(--text-primary)",
      padding: "0 20px 14px",
      borderBottom: "1px solid var(--border)"
    }
  }, title), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "16px 20px 0"
    }
  }, children), footer && /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "16px 20px 0",
      display: "flex",
      gap: 10
    }
  }, footer)), /*#__PURE__*/React.createElement("style", null, `@keyframes dlbdsSheetUp{from{transform:translateY(100%)}to{transform:translateY(0)}}`));
}
Object.assign(__ds_scope, { BottomSheet });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/BottomSheet.jsx", error: String((e && e.message) || e) }); }

// components/feedback/HeroCard.jsx
try { (() => {
/* Đà Lạt BĐS HeroCard — the coloured summary panel atop CRM/admin subpages
   (Hoa hồng, KPI, Giá thị trường, Profile). Big headline value + stat grid. */
const GRADS = {
  primary: "var(--grad-hero)",
  pine: "var(--grad-pine)",
  dusk: "var(--grad-dusk)"
};
function HeroCard({
  label,
  main,
  gradient = "primary",
  stats = [],
  flush = false,
  children,
  style = {}
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      background: GRADS[gradient] || GRADS.primary,
      color: "#fff",
      padding: "18px 18px 20px",
      borderRadius: flush ? 0 : "var(--radius-lg)",
      ...style
    }
  }, label && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-10)",
      fontWeight: "var(--fw-semibold)",
      letterSpacing: "var(--ls-wide)",
      textTransform: "uppercase",
      opacity: 0.72,
      marginBottom: 8
    }
  }, label), main && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-28)",
      fontWeight: "var(--fw-bold)",
      lineHeight: 1.1,
      letterSpacing: "var(--ls-tight)"
    }
  }, main), children, stats.length > 0 && /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: `repeat(${stats.length}, 1fr)`,
      gap: 8,
      marginTop: 16
    }
  }, stats.map((s, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      background: "rgba(255,255,255,0.14)",
      borderRadius: "var(--radius-sm)",
      padding: "9px 8px"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-16)",
      fontWeight: "var(--fw-bold)",
      lineHeight: 1.1
    }
  }, s.value), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-10)",
      opacity: 0.78,
      marginTop: 3
    }
  }, s.label)))));
}
Object.assign(__ds_scope, { HeroCard });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/HeroCard.jsx", error: String((e && e.message) || e) }); }

// components/feedback/Toast.jsx
try { (() => {
/* Đà Lạt BĐS Toast — brief confirmation pill (✓ Đã lưu bookmark).
   Renders at the bottom of the phone frame above the nav bar. */
const TONES = {
  success: {
    bg: "var(--ink-900)",
    icon: "check",
    fg: "var(--success-light)"
  },
  primary: {
    bg: "var(--primary)",
    icon: "check",
    fg: "#fff"
  },
  danger: {
    bg: "var(--danger)",
    icon: "x",
    fg: "#fff"
  }
};
function Toast({
  message,
  tone = "success",
  show = true,
  style = {}
}) {
  const t = TONES[tone] || TONES.success;
  return /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: "50%",
      bottom: 84,
      transform: `translateX(-50%) translateY(${show ? 0 : 12}px)`,
      opacity: show ? 1 : 0,
      transition: "opacity var(--dur-base), transform var(--dur-base)",
      display: "inline-flex",
      alignItems: "center",
      gap: 8,
      padding: "10px 16px",
      background: t.bg,
      color: "#fff",
      borderRadius: "var(--radius-pill)",
      boxShadow: "var(--shadow-lg)",
      fontFamily: "var(--font-sans)",
      fontSize: "var(--fs-13)",
      fontWeight: "var(--fw-medium)",
      whiteSpace: "nowrap",
      zIndex: 80,
      pointerEvents: "none",
      ...style
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: t.icon,
    size: 15,
    color: t.fg,
    stroke: 2.2
  }), message);
}
Object.assign(__ds_scope, { Toast });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/Toast.jsx", error: String((e && e.message) || e) }); }

// components/identity/RoleBadge.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS RoleBadge — the 7 permission roles. Each has a fixed label,
   tint and glyph. Drives which UI a user sees across the WebApp. */
const ROLES = {
  guest: {
    label: "Khách",
    icon: "user",
    fg: "var(--text-secondary)",
    bg: "var(--bg-secondary)"
  },
  broker: {
    label: "eBroker",
    icon: "building",
    fg: "var(--primary)",
    bg: "var(--primary-light)"
  },
  sale: {
    label: "Sale",
    icon: "briefcase",
    fg: "var(--success)",
    bg: "var(--success-light)"
  },
  sale_admin: {
    label: "Sale Admin",
    icon: "barChart",
    fg: "var(--purple)",
    bg: "var(--purple-light)"
  },
  bds_admin: {
    label: "BĐS Admin",
    icon: "shield",
    fg: "#b45309",
    bg: "var(--warning-light)"
  },
  admin: {
    label: "Admin",
    icon: "star",
    fg: "var(--danger)",
    bg: "var(--danger-light)"
  },
  operator: {
    label: "Operator",
    icon: "layers",
    fg: "var(--teal)",
    bg: "var(--teal-light)"
  }
};
function RoleBadge({
  role = "guest",
  size = "md",
  showIcon = true,
  onLight = false,
  style = {},
  ...rest
}) {
  const r = ROLES[role] || ROLES.guest;
  const sm = size === "sm";
  const base = {
    display: "inline-flex",
    alignItems: "center",
    gap: sm ? "4px" : "5px",
    padding: sm ? "3px 9px" : "5px 11px",
    fontFamily: "var(--font-sans)",
    fontSize: sm ? "var(--fs-10)" : "var(--fs-12)",
    fontWeight: "var(--fw-semibold)",
    lineHeight: 1.3,
    borderRadius: "var(--radius-pill)",
    whiteSpace: "nowrap",
    background: onLight ? "rgba(255,255,255,0.16)" : r.bg,
    color: onLight ? "#fff" : r.fg
  };
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      ...base,
      ...style
    }
  }, rest), showIcon && /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: r.icon,
    size: sm ? 11 : 13,
    stroke: 1.8
  }), r.label);
}
Object.assign(__ds_scope, { ROLES, RoleBadge });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/identity/RoleBadge.jsx", error: String((e && e.message) || e) }); }

// components/navigation/BottomNav.jsx
try { (() => {
/* Đà Lạt BĐS BottomNav — fixed 5-slot tab bar with a centre raised FAB
   ("Đăng tin"). Khám phá · Tìm kiếm · [+] · Hoạt động · Hồ sơ. */
const DEFAULT_ITEMS = [{
  key: "home",
  icon: "home",
  label: "Khám phá"
}, {
  key: "search",
  icon: "search",
  label: "Tìm kiếm"
}, {
  key: "post",
  icon: "plus",
  label: "Đăng tin",
  fab: true
}, {
  key: "activity",
  icon: "bell",
  label: "Hoạt động"
}, {
  key: "profile",
  icon: "user",
  label: "Hồ sơ"
}];
function BottomNav({
  items = DEFAULT_ITEMS,
  active = "home",
  badges = {},
  onSelect,
  style = {}
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "flex-end",
      justifyContent: "space-around",
      height: "var(--bottom-nav-h)",
      background: "var(--bg-card)",
      borderTop: "1px solid var(--border)",
      boxShadow: "0 -2px 12px rgba(15,28,50,0.05)",
      padding: "0 6px",
      ...style
    }
  }, items.map(it => {
    if (it.fab) {
      return /*#__PURE__*/React.createElement("button", {
        key: it.key,
        type: "button",
        onClick: () => onSelect && onSelect(it.key),
        style: {
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          gap: 2,
          background: "none",
          border: "none",
          cursor: "pointer",
          transform: "translateY(-10px)"
        }
      }, /*#__PURE__*/React.createElement("span", {
        style: {
          width: 48,
          height: 48,
          borderRadius: "var(--radius-pill)",
          background: "var(--grad-primary)",
          boxShadow: "var(--shadow-primary-lg)",
          display: "flex",
          alignItems: "center",
          justifyContent: "center"
        }
      }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
        name: it.icon,
        size: 22,
        color: "#fff",
        stroke: 2.2
      })), /*#__PURE__*/React.createElement("span", {
        style: {
          fontSize: "var(--fs-9)",
          color: "var(--text-tertiary)"
        }
      }, it.label));
    }
    const on = it.key === active;
    return /*#__PURE__*/React.createElement("button", {
      key: it.key,
      type: "button",
      onClick: () => onSelect && onSelect(it.key),
      style: {
        position: "relative",
        flex: 1,
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        gap: 3,
        padding: "10px 0 8px",
        background: "none",
        border: "none",
        cursor: "pointer",
        color: on ? "var(--primary)" : "var(--text-tertiary)"
      }
    }, /*#__PURE__*/React.createElement("span", {
      style: {
        position: "relative"
      }
    }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
      name: it.icon,
      size: 23,
      stroke: on ? 2 : 1.7
    }), badges[it.key] ? /*#__PURE__*/React.createElement("span", {
      style: {
        position: "absolute",
        top: -5,
        right: -8,
        minWidth: 15,
        height: 15,
        padding: "0 4px",
        borderRadius: "var(--radius-pill)",
        background: "var(--danger)",
        color: "#fff",
        fontSize: 9,
        fontWeight: 700,
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        lineHeight: 1
      }
    }, badges[it.key]) : null), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: "var(--fs-9)",
        fontWeight: on ? "var(--fw-semibold)" : "var(--fw-medium)"
      }
    }, it.label));
  }));
}
Object.assign(__ds_scope, { BottomNav });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/navigation/BottomNav.jsx", error: String((e && e.message) || e) }); }

// components/navigation/Tabs.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS Tabs — underline segmented control used on every subpage
   (Tất cả / Mới / Đã liên hệ …). Scrolls horizontally when overflowing. */
function Tabs({
  items = [],
  value,
  onChange,
  style = {},
  ...rest
}) {
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      display: "flex",
      gap: "4px",
      padding: "0 12px",
      borderBottom: "1px solid var(--border)",
      background: "var(--bg-card)",
      overflowX: "auto",
      scrollbarWidth: "none",
      ...style
    }
  }, rest), items.map(it => {
    const key = typeof it === "string" ? it : it.value;
    const label = typeof it === "string" ? it : it.label;
    const active = key === value;
    return /*#__PURE__*/React.createElement("button", {
      key: key,
      type: "button",
      onClick: () => onChange && onChange(key),
      style: {
        position: "relative",
        padding: "12px 10px 11px",
        fontFamily: "var(--font-sans)",
        fontSize: "var(--fs-13)",
        fontWeight: active ? "var(--fw-semibold)" : "var(--fw-medium)",
        color: active ? "var(--primary)" : "var(--text-secondary)",
        background: "none",
        border: "none",
        cursor: "pointer",
        whiteSpace: "nowrap",
        flexShrink: 0
      }
    }, label, /*#__PURE__*/React.createElement("span", {
      style: {
        position: "absolute",
        left: 6,
        right: 6,
        bottom: -1,
        height: 2.5,
        borderRadius: "2px 2px 0 0",
        background: active ? "var(--primary)" : "transparent"
      }
    }));
  }));
}
Object.assign(__ds_scope, { Tabs });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/navigation/Tabs.jsx", error: String((e && e.message) || e) }); }

// components/realestate/MarketPriceItem.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS MarketPriceItem — a ward/area price cell in the market strip
   ("Nhịp đập thị trường"). Area name, price/m², trend delta with direction. */
function MarketPriceItem({
  area,
  price,
  trendDir = "flat",
  trendPct,
  style = {}
}) {
  const up = trendDir === "up";
  const down = trendDir === "down";
  const color = up ? "var(--success)" : down ? "var(--danger)" : "var(--text-tertiary)";
  const arrow = up ? "↑" : down ? "↓" : "—";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      flexShrink: 0,
      minWidth: 108,
      background: "var(--bg-card)",
      border: "1px solid var(--border)",
      borderRadius: "var(--radius-md)",
      padding: "10px 12px",
      ...style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-11)",
      color: "var(--text-secondary)",
      marginBottom: 5,
      whiteSpace: "nowrap",
      overflow: "hidden",
      textOverflow: "ellipsis"
    }
  }, area), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-15)",
      fontWeight: "var(--fw-bold)",
      color: "var(--text-primary)",
      lineHeight: 1.1
    }
  }, price), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-11)",
      fontWeight: "var(--fw-semibold)",
      color,
      marginTop: 4
    }
  }, arrow, trendPct != null && trendDir !== "flat" ? ` ${trendPct}%` : ""));
}

/* Row wrapper — horizontal scroll strip of price cells. */
function MarketPriceStrip({
  children,
  style = {},
  ...rest
}) {
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      display: "flex",
      gap: 10,
      overflowX: "auto",
      padding: "2px 0 4px",
      scrollbarWidth: "none",
      ...style
    }
  }, rest), children);
}
Object.assign(__ds_scope, { MarketPriceItem, MarketPriceStrip });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/realestate/MarketPriceItem.jsx", error: String((e && e.message) || e) }); }

// components/realestate/MenuItem.jsx
try { (() => {
/* Đà Lạt BĐS MenuItem — profile / settings list row. Tinted icon chip,
   title + sub, optional trailing badge, chevron. `accent` adds a left rail. */
const TONES = {
  blue: "var(--primary-light)",
  teal: "var(--teal-light)",
  purple: "var(--purple-light)",
  amber: "var(--warning-light)",
  green: "var(--success-light)",
  red: "var(--danger-light)",
  gray: "var(--bg-secondary)"
};
const FG = {
  blue: "var(--primary)",
  teal: "var(--teal)",
  purple: "var(--purple)",
  amber: "#b45309",
  green: "var(--success)",
  red: "var(--danger)",
  gray: "var(--text-secondary)"
};
function MenuItem({
  icon,
  title,
  sub,
  tone = "blue",
  badge,
  accent,
  danger = false,
  onClick,
  style = {}
}) {
  return /*#__PURE__*/React.createElement("div", {
    onClick: onClick,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      padding: "12px 16px",
      background: "var(--bg-card)",
      borderBottom: "1px solid var(--border)",
      borderLeft: accent ? `3px solid ${FG[accent] || "var(--primary)"}` : "none",
      cursor: onClick ? "pointer" : "default",
      ...style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 38,
      height: 38,
      borderRadius: "var(--radius-sm)",
      background: TONES[tone],
      color: FG[tone],
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: 18,
    stroke: 1.7,
    color: danger ? "var(--danger)" : undefined
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-14)",
      fontWeight: "var(--fw-semibold)",
      color: danger ? "var(--danger)" : "var(--text-primary)",
      lineHeight: 1.3
    }
  }, title), sub && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-12)",
      color: "var(--text-tertiary)",
      marginTop: 2
    }
  }, sub)), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      flexShrink: 0,
      color: danger ? "var(--danger)" : "var(--text-tertiary)"
    }
  }, badge, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "chevronRight",
    size: 16,
    stroke: 2
  })));
}
Object.assign(__ds_scope, { MenuItem });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/realestate/MenuItem.jsx", error: String((e && e.message) || e) }); }

// components/realestate/PropCard.jsx
try { (() => {
/* Đà Lạt BĐS PropCard — the property listing card in the home/search feed.
   Photo with price + category overlay, title, location, meta row, footer. */
function PropCard({
  image,
  title,
  price,
  category,
  // e.g. "Đất ở"
  transaction,
  // "sale" | "rent"
  address,
  area,
  // m²
  legal,
  // e.g. "Sổ đỏ"
  rooms,
  // bedrooms
  views,
  liked = false,
  onToggleLike,
  actions = ["edit", "phone", "share"],
  // footer quick actions
  onAction,
  onClick,
  style = {}
}) {
  return /*#__PURE__*/React.createElement("div", {
    onClick: onClick,
    style: {
      background: "var(--bg-card)",
      borderRadius: "var(--radius-lg)",
      boxShadow: "var(--shadow-card)",
      overflow: "hidden",
      cursor: onClick ? "pointer" : "default",
      ...style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 168,
      background: "var(--ink-800)"
    }
  }, image ? /*#__PURE__*/React.createElement("img", {
    src: image,
    alt: "",
    style: {
      width: "100%",
      height: "100%",
      objectFit: "cover"
    }
  }) : /*#__PURE__*/React.createElement("div", {
    style: {
      width: "100%",
      height: "100%",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      background: "linear-gradient(135deg,#1a2a44,#0f1c32)"
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "building",
    size: 48,
    color: "rgba(255,255,255,0.35)",
    stroke: 1.2
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      inset: 0,
      background: "linear-gradient(180deg,transparent 45%,rgba(15,28,50,0.55) 100%)"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      top: 12,
      left: 12,
      display: "flex",
      gap: 6
    }
  }, category && /*#__PURE__*/React.createElement(__ds_scope.Badge, {
    tone: "blue",
    solid: true,
    style: {
      boxShadow: "var(--shadow-sm)"
    }
  }, category), transaction && /*#__PURE__*/React.createElement(__ds_scope.Badge, {
    tone: transaction === "rent" ? "purple" : "green",
    solid: true
  }, transaction === "rent" ? "Cho thuê" : "Đang bán")), /*#__PURE__*/React.createElement("button", {
    type: "button",
    onClick: e => {
      e.stopPropagation();
      onToggleLike && onToggleLike();
    },
    style: {
      position: "absolute",
      top: 10,
      right: 10,
      width: 34,
      height: 34,
      borderRadius: "var(--radius-pill)",
      border: "none",
      cursor: "pointer",
      background: "rgba(255,255,255,0.92)",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      boxShadow: "var(--shadow-sm)"
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "heart",
    size: 15,
    color: "var(--primary)",
    fill: liked ? "var(--primary)" : "none",
    stroke: 1.9
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 12,
      bottom: 11,
      color: "#fff",
      fontWeight: "var(--fw-bold)",
      fontSize: "var(--fs-18)",
      textShadow: "0 1px 8px rgba(0,0,0,0.4)"
    }
  }, price)), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "12px 14px 4px"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-15)",
      fontWeight: "var(--fw-semibold)",
      color: "var(--text-primary)",
      lineHeight: 1.3,
      marginBottom: 6,
      display: "-webkit-box",
      WebkitLineClamp: 1,
      WebkitBoxOrient: "vertical",
      overflow: "hidden"
    }
  }, title), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 5,
      fontSize: "var(--fs-12)",
      color: "var(--text-secondary)",
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "mapPin",
    size: 12,
    color: "var(--primary)"
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      overflow: "hidden",
      textOverflow: "ellipsis",
      whiteSpace: "nowrap"
    }
  }, address)), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexWrap: "wrap",
      gap: "6px 14px",
      paddingBottom: 10
    }
  }, area && /*#__PURE__*/React.createElement(Meta, {
    icon: "maximize",
    label: `${area} m²`
  }), legal && /*#__PURE__*/React.createElement(Meta, {
    icon: "fileText",
    label: legal
  }), rooms && /*#__PURE__*/React.createElement(Meta, {
    icon: "bed",
    label: `${rooms} PN`
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between",
      padding: "9px 14px",
      borderTop: "1px solid var(--border)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 4,
      fontSize: "var(--fs-12)",
      color: "var(--text-tertiary)"
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "eye",
    size: 12
  }), views, " l\u01B0\u1EE3t xem"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 7
    }
  }, actions.map(a => /*#__PURE__*/React.createElement("button", {
    key: a,
    type: "button",
    onClick: e => {
      e.stopPropagation();
      onAction && onAction(a);
    },
    style: {
      width: 30,
      height: 30,
      borderRadius: "var(--radius-sm)",
      cursor: "pointer",
      border: a === "phone" ? "none" : "1px solid var(--border)",
      background: a === "phone" ? "var(--primary-light)" : "var(--bg-card)",
      color: a === "phone" ? "var(--primary)" : "var(--text-secondary)",
      display: "flex",
      alignItems: "center",
      justifyContent: "center"
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: a,
    size: 13,
    stroke: 1.8
  }))))));
}
function Meta({
  icon,
  label
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 5,
      fontSize: "var(--fs-12)",
      color: "var(--text-secondary)"
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: 12,
    color: "var(--primary)",
    stroke: 1.8
  }), /*#__PURE__*/React.createElement("span", null, label));
}
Object.assign(__ds_scope, { PropCard });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/realestate/PropCard.jsx", error: String((e && e.message) || e) }); }

// components/realestate/SectionHeader.jsx
try { (() => {
/* Đà Lạt BĐS SectionHeader — the small icon+label heading that opens every
   feed block and detail section. Optional collapse chevron & trailing action. */
function SectionHeader({
  icon,
  title,
  action,
  collapsible = false,
  open = true,
  onToggle,
  style = {}
}) {
  return /*#__PURE__*/React.createElement("div", {
    onClick: collapsible ? onToggle : undefined,
    style: {
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between",
      padding: "14px 16px 8px",
      cursor: collapsible ? "pointer" : "default",
      ...style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 6,
      fontSize: "var(--fs-15)",
      fontWeight: "var(--fw-semibold)",
      color: "var(--text-primary)"
    }
  }, icon && /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: 15,
    color: "var(--text-primary)",
    stroke: 1.8
  }), title), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8
    }
  }, action, collapsible && /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "chevronDown",
    size: 16,
    color: "var(--text-tertiary)",
    style: {
      transform: open ? "none" : "rotate(-90deg)",
      transition: "transform var(--dur-fast)"
    }
  })));
}
Object.assign(__ds_scope, { SectionHeader });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/realestate/SectionHeader.jsx", error: String((e && e.message) || e) }); }

// components/realestate/StatCard.jsx
try { (() => {
/* Đà Lạt BĐS StatCard — dashboard quick-stat tile on the home page.
   Tinted icon chip + label + big value + delta caption. Tappable. */
const TONES = {
  blue: {
    fg: "var(--primary)",
    bg: "var(--primary-light)"
  },
  amber: {
    fg: "var(--warning)",
    bg: "var(--warning-light)"
  },
  green: {
    fg: "var(--success)",
    bg: "var(--success-light)"
  },
  purple: {
    fg: "var(--purple)",
    bg: "var(--purple-light)"
  },
  pink: {
    fg: "var(--pink)",
    bg: "var(--pink-light)"
  },
  teal: {
    fg: "var(--teal)",
    bg: "var(--teal-light)"
  }
};
function StatCard({
  icon,
  label,
  value,
  delta,
  tone = "blue",
  onClick,
  style = {}
}) {
  const t = TONES[tone] || TONES.blue;
  return /*#__PURE__*/React.createElement("div", {
    onClick: onClick,
    style: {
      background: "var(--bg-card)",
      border: "1px solid var(--border)",
      borderRadius: "var(--radius-md)",
      padding: "13px 13px 12px",
      cursor: onClick ? "pointer" : "default",
      transition: "box-shadow var(--dur-fast), transform var(--dur-fast)",
      ...style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 34,
      height: 34,
      borderRadius: "var(--radius-sm)",
      background: t.bg,
      color: t.fg,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: 18,
    stroke: 1.7
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-12)",
      color: "var(--text-secondary)",
      marginBottom: 3,
      lineHeight: 1.3
    }
  }, label), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-24)",
      fontWeight: "var(--fw-bold)",
      color: "var(--text-primary)",
      lineHeight: 1.1
    }
  }, value), delta && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-11)",
      color: "var(--text-tertiary)",
      marginTop: 3
    }
  }, delta));
}
Object.assign(__ds_scope, { StatCard });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/realestate/StatCard.jsx", error: String((e && e.message) || e) }); }

// ui_kits/webapp/CrmScreens.jsx
try { (() => {
/* Đà Lạt BĐS WebApp — CRM subpages: Commissions & Leads (slide-in overlays). */
const C = window.LTBSDesignSystem_ab8fed;
function SubHeader({
  icon,
  title,
  onClose,
  action
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      height: 52,
      display: "flex",
      alignItems: "center",
      gap: 8,
      padding: "0 12px",
      background: "var(--bg-card)",
      borderBottom: "1px solid var(--border)",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(C.IconButton, {
    icon: "chevronLeft",
    variant: "ghost",
    size: "sm",
    onClick: onClose
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      display: "flex",
      alignItems: "center",
      gap: 6,
      fontSize: "var(--fs-16)",
      fontWeight: 700,
      color: "var(--text-primary)"
    }
  }, icon && /*#__PURE__*/React.createElement(C.Icon, {
    name: icon,
    size: 16
  }), title), action);
}
function CommissionsScreen({
  data,
  onClose
}) {
  const [tab, setTab] = React.useState("Tất cả");
  const d = data.commissions;
  const max = Math.max(...d.chart);
  const filtered = d.rows.filter(r => tab === "Tất cả" || r.label === tab);
  return /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      inset: 0,
      background: "var(--bg-app)",
      zIndex: 45,
      display: "flex",
      flexDirection: "column"
    }
  }, /*#__PURE__*/React.createElement(SubHeader, {
    icon: "dollarSign",
    title: "Hoa h\u1ED3ng c\u1EE7a t\xF4i",
    onClose: onClose
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      overflowY: "auto"
    }
  }, /*#__PURE__*/React.createElement(C.HeroCard, {
    flush: true,
    label: "T\u1ED4NG HOA H\u1ED2NG D\u1EF0 KI\u1EBEN",
    main: d.total,
    gradient: "primary",
    stats: [{
      value: d.received,
      label: "Đã nhận"
    }, {
      value: d.pending,
      label: "Đang chờ"
    }, {
      value: d.upcoming,
      label: "Sắp về"
    }]
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      background: "var(--bg-card)",
      padding: "14px 16px 8px",
      borderBottom: "1px solid var(--border)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-11)",
      fontWeight: 600,
      color: "var(--text-secondary)",
      marginBottom: 10
    }
  }, "Hoa h\u1ED3ng theo th\xE1ng (tri\u1EC7u)"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "flex-end",
      gap: 10,
      height: 72
    }
  }, d.chart.map((v, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      flex: 1,
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      gap: 5
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: "100%",
      height: v / max * 58,
      background: i === d.chart.length - 1 ? "var(--primary)" : "var(--blue-200)",
      borderRadius: "4px 4px 0 0"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-9)",
      color: i === d.chart.length - 1 ? "var(--primary)" : "var(--text-tertiary)",
      fontWeight: i === d.chart.length - 1 ? 700 : 400
    }
  }, data.commissions.labels[i]))))), /*#__PURE__*/React.createElement(C.Tabs, {
    items: ["Tất cả", "Chờ cọc", "Đã cọc", "Hoàn tất"],
    value: tab,
    onChange: setTab
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "12px 16px",
      display: "flex",
      flexDirection: "column",
      gap: 10
    }
  }, filtered.map((r, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      background: "var(--bg-card)",
      border: "1px solid var(--border)",
      borderRadius: "var(--radius-md)",
      padding: "12px 14px"
    }
  }, /*#__PURE__*/React.createElement(C.Avatar, {
    name: r.name,
    tone: r.tone === "green" ? "green" : r.tone === "amber" ? "amber" : "blue",
    size: 40
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-14)",
      fontWeight: 600,
      color: "var(--text-primary)"
    }
  }, r.name), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-12)",
      color: "var(--text-tertiary)"
    }
  }, r.deal)), /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "right"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-15)",
      fontWeight: 700,
      color: "var(--text-primary)"
    }
  }, r.amount), /*#__PURE__*/React.createElement(C.Badge, {
    tone: r.tone
  }, r.label)))))));
}
window.CommissionsScreen = CommissionsScreen;
function LeadsScreen({
  data,
  role,
  onClose
}) {
  const [tab, setTab] = React.useState("Tất cả");
  const map = {
    "Tất cả": null,
    "Mới": "new",
    "Đã liên hệ": "contacted",
    "Đã chuyển": "converted"
  };
  const filtered = data.leads.filter(l => !map[tab] || l.status === map[tab]);
  const title = role === "sale_admin" ? "Phân công Lead" : "Khách cần chăm sóc";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      inset: 0,
      background: "var(--bg-app)",
      zIndex: 45,
      display: "flex",
      flexDirection: "column"
    }
  }, /*#__PURE__*/React.createElement(SubHeader, {
    icon: "users",
    title: title,
    onClose: onClose
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "12px 14px 0",
      background: "var(--bg-card)"
    }
  }, /*#__PURE__*/React.createElement(C.Input, {
    variant: "search",
    placeholder: "T\xEAn, S\u0110T..."
  })), /*#__PURE__*/React.createElement(C.Tabs, {
    items: ["Tất cả", "Mới", "Đã liên hệ", "Đã chuyển"],
    value: tab,
    onChange: setTab,
    style: {
      marginTop: 10
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      overflowY: "auto",
      padding: "12px 16px",
      display: "flex",
      flexDirection: "column",
      gap: 10
    }
  }, filtered.map((l, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      background: "var(--bg-card)",
      border: "1px solid var(--border)",
      borderRadius: "var(--radius-md)",
      padding: "12px 14px"
    }
  }, /*#__PURE__*/React.createElement(C.Avatar, {
    name: l.name,
    tone: l.tint,
    size: 42
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      marginBottom: 3
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--fs-14)",
      fontWeight: 600,
      color: "var(--text-primary)"
    }
  }, l.name), /*#__PURE__*/React.createElement(C.Badge, {
    tone: l.tone
  }, l.label)), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-12)",
      color: "var(--text-secondary)",
      overflow: "hidden",
      textOverflow: "ellipsis",
      whiteSpace: "nowrap"
    }
  }, l.need), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-11)",
      color: "var(--text-tertiary)",
      marginTop: 2
    }
  }, l.phone)), role === "sale_admin" ? /*#__PURE__*/React.createElement(C.Button, {
    size: "sm",
    variant: "soft",
    icon: "send"
  }, "Giao") : /*#__PURE__*/React.createElement(C.IconButton, {
    icon: "phone",
    variant: "soft"
  })))));
}
window.LeadsScreen = LeadsScreen;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/webapp/CrmScreens.jsx", error: String((e && e.message) || e) }); }

// ui_kits/webapp/DetailScreen.jsx
try { (() => {
/* Đà Lạt BĐS WebApp — Property detail overlay with role-based CTA bar. */
const D = window.LTBSDesignSystem_ab8fed;
function SpecRow({
  label,
  value,
  tone
}) {
  const c = tone === "green" ? "var(--success)" : tone === "blue" ? "var(--primary)" : "var(--text-primary)";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      padding: "10px 0",
      borderBottom: "1px solid var(--border)"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--fs-13)",
      color: "var(--text-secondary)"
    }
  }, label), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--fs-13)",
      fontWeight: 600,
      color: c
    }
  }, value));
}
function DetailStat({
  icon,
  val,
  lbl
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "center",
      marginBottom: 6,
      color: "var(--primary)"
    }
  }, /*#__PURE__*/React.createElement(D.Icon, {
    name: icon,
    size: 18
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-14)",
      fontWeight: 700,
      color: "var(--text-primary)"
    }
  }, val), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-10)",
      color: "var(--text-tertiary)",
      marginTop: 2
    }
  }, lbl));
}

/* Role → bottom CTA bar config */
function ctaFor(role, onClose, onBook, onSend) {
  const back = /*#__PURE__*/React.createElement(D.IconButton, {
    icon: "chevronLeft",
    variant: "soft",
    onClick: onClose
  });
  switch (role) {
    case "guest":
      return /*#__PURE__*/React.createElement(React.Fragment, null, back, /*#__PURE__*/React.createElement(D.Button, {
        block: true,
        icon: "user"
      }, "\u0110\u0103ng k\xFD eBroker"));
    case "broker":
      return /*#__PURE__*/React.createElement(React.Fragment, null, back, /*#__PURE__*/React.createElement(D.Button, {
        block: true,
        icon: "user"
      }, "Th\xEAm Lead / Kh\xE1ch"));
    case "bds_admin":
      return /*#__PURE__*/React.createElement(React.Fragment, null, back, /*#__PURE__*/React.createElement(D.Button, {
        block: true,
        tone: "amber",
        icon: "check"
      }, "Duy\u1EC7t B\u0110S"));
    case "sale_admin":
      return /*#__PURE__*/React.createElement(React.Fragment, null, back, /*#__PURE__*/React.createElement(D.Button, {
        block: true,
        tone: "purple",
        icon: "users",
        onClick: onSend
      }, "Giao cho Sale"), /*#__PURE__*/React.createElement(D.IconButton, {
        icon: "phone",
        variant: "primary"
      }));
    case "sale":
    case "admin":
    default:
      return /*#__PURE__*/React.createElement(React.Fragment, null, back, /*#__PURE__*/React.createElement(D.IconButton, {
        icon: "send",
        variant: "soft",
        onClick: onSend
      }), /*#__PURE__*/React.createElement(D.Button, {
        tone: "green",
        icon: "calendar",
        onClick: onBook
      }, "\u0110\u1EB7t l\u1ECBch"), /*#__PURE__*/React.createElement(D.IconButton, {
        icon: "phone",
        variant: "primary"
      }));
  }
}
function DetailScreen({
  prop,
  role,
  liked,
  onToggleLike,
  onClose,
  onBook,
  onSend
}) {
  if (!prop) return null;
  const staff = role !== "guest";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      inset: 0,
      background: "var(--bg-app)",
      zIndex: 50,
      display: "flex",
      flexDirection: "column"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      overflowY: "auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 240,
      background: prop.grad
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      top: 12,
      left: 12,
      display: "flex",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement(D.IconButton, {
    icon: "chevronLeft",
    onClick: onClose,
    style: {
      background: "rgba(0,0,0,0.35)"
    },
    color: "#fff"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      top: 12,
      right: 12,
      display: "flex",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement(D.IconButton, {
    icon: "heart",
    color: liked ? "var(--primary)" : "#fff",
    style: {
      background: "rgba(0,0,0,0.35)"
    },
    onClick: onToggleLike
  }), /*#__PURE__*/React.createElement(D.IconButton, {
    icon: "share",
    color: "#fff",
    style: {
      background: "rgba(0,0,0,0.35)"
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 16,
      bottom: 14,
      color: "#fff"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-28)",
      fontWeight: 700,
      textShadow: "0 1px 8px rgba(0,0,0,.4)"
    }
  }, prop.price), prop.priceM2 && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-12)",
      opacity: .9
    }
  }, prop.priceM2))), /*#__PURE__*/React.createElement("div", {
    style: {
      background: "var(--bg-card)",
      padding: "14px 16px",
      borderBottom: "1px solid var(--border)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 6,
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement(D.Badge, {
    tone: "blue"
  }, prop.category), /*#__PURE__*/React.createElement(D.Badge, {
    tone: prop.transaction === "rent" ? "purple" : "green",
    dot: true
  }, prop.transaction === "rent" ? "Cho thuê" : "Đang bán")), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-18)",
      fontWeight: 700,
      color: "var(--text-primary)",
      lineHeight: 1.3,
      marginBottom: 8
    }
  }, prop.title), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 5,
      fontSize: "var(--fs-13)",
      color: "var(--text-secondary)",
      marginBottom: 16
    }
  }, /*#__PURE__*/React.createElement(D.Icon, {
    name: "mapPin",
    size: 13,
    color: "var(--primary)"
  }), staff ? prop.address : "Đăng nhập để xem địa chỉ chính xác"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      background: "var(--bg-app)",
      borderRadius: "var(--radius-md)",
      padding: "12px 4px"
    }
  }, /*#__PURE__*/React.createElement(DetailStat, {
    icon: "maximize",
    val: prop.area + " m²",
    lbl: "Di\u1EC7n t\xEDch"
  }), /*#__PURE__*/React.createElement(DetailStat, {
    icon: "bed",
    val: prop.rooms || "—",
    lbl: "Ph\xF2ng ng\u1EE7"
  }), /*#__PURE__*/React.createElement(DetailStat, {
    icon: "compass",
    val: prop.direction,
    lbl: "H\u01B0\u1EDBng"
  }), /*#__PURE__*/React.createElement(DetailStat, {
    icon: "eye",
    val: prop.views,
    lbl: "L\u01B0\u1EE3t xem"
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      background: "var(--bg-card)",
      padding: "4px 16px 8px",
      marginTop: 10,
      borderTop: "1px solid var(--border)",
      borderBottom: "1px solid var(--border)"
    }
  }, /*#__PURE__*/React.createElement(D.SectionHeader, {
    icon: "fileText",
    title: "Th\xF4ng s\u1ED1 chi ti\u1EBFt",
    style: {
      padding: "12px 0 4px"
    }
  }), /*#__PURE__*/React.createElement(SpecRow, {
    label: "Di\u1EC7n t\xEDch",
    value: prop.area + " m²",
    tone: "blue"
  }), /*#__PURE__*/React.createElement(SpecRow, {
    label: "Ph\xE1p l\xFD",
    value: staff ? prop.legal : "Xác minh để xem",
    tone: "green"
  }), prop.priceM2 && /*#__PURE__*/React.createElement(SpecRow, {
    label: "Gi\xE1 / m\xB2",
    value: prop.priceM2,
    tone: "green"
  }), staff && /*#__PURE__*/React.createElement(SpecRow, {
    label: "Hoa h\u1ED3ng",
    value: prop.commission,
    tone: "blue"
  })), staff && /*#__PURE__*/React.createElement("div", {
    style: {
      background: "var(--bg-card)",
      padding: "4px 16px 12px",
      marginTop: 10,
      borderTop: "1px solid var(--border)",
      borderBottom: "1px solid var(--border)"
    }
  }, /*#__PURE__*/React.createElement(D.SectionHeader, {
    icon: "shield",
    title: "Ph\xE1p l\xFD & H\u1ED3 s\u01A1",
    style: {
      padding: "12px 0 8px"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      padding: "10px 12px",
      background: "var(--success-light)",
      borderRadius: "var(--radius-sm)"
    }
  }, /*#__PURE__*/React.createElement(D.Icon, {
    name: "check",
    size: 16,
    color: "var(--success)",
    stroke: 2.4
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--fs-13)",
      fontWeight: 600,
      color: "var(--text-primary)",
      flex: 1
    }
  }, prop.legal, " \xB7 \u0110\xE3 ki\u1EC3m tra quy ho\u1EA1ch"), /*#__PURE__*/React.createElement(D.Badge, {
    tone: "green"
  }, "H\u1EE3p l\u1EC7"))), /*#__PURE__*/React.createElement("div", {
    style: {
      background: "var(--bg-card)",
      padding: "12px 16px",
      marginTop: 10,
      borderTop: "1px solid var(--border)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement(D.Avatar, {
    name: "B\xF9i Kh\xE1nh",
    tone: "teal",
    size: 44
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-14)",
      fontWeight: 600,
      color: "var(--text-primary)"
    }
  }, "B\xF9i Kh\xE1nh"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-12)",
      color: "var(--text-tertiary)"
    }
  }, "eBroker \xB7 \u0110\xE0 L\u1EA1t B\u0110S")), staff && /*#__PURE__*/React.createElement(D.IconButton, {
    icon: "phone",
    variant: "soft"
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      height: 12
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      padding: "10px 14px",
      background: "var(--bg-card)",
      borderTop: "1px solid var(--border)"
    }
  }, ctaFor(role, onClose, onBook, onSend)));
}
window.DetailScreen = DetailScreen;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/webapp/DetailScreen.jsx", error: String((e && e.message) || e) }); }

// ui_kits/webapp/HomeScreen.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/* Đà Lạt BĐS WebApp — shared header + Home feed screen. */
const {
  Icon,
  IconButton,
  Badge,
  Chip,
  ChipRow,
  StatCard,
  PropCard,
  MarketPriceItem,
  MarketPriceStrip,
  SectionHeader,
  RoleBadge,
  Avatar
} = window.LTBSDesignSystem_ab8fed;
function AppHeader({
  role,
  onLogo
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      height: 56,
      display: "flex",
      alignItems: "center",
      justifyContent: "space-between",
      padding: "0 14px",
      background: "var(--bg-card)",
      borderBottom: "1px solid var(--border)",
      position: "sticky",
      top: 0,
      zIndex: 30
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "../../assets/logo.svg",
    alt: "\u0110\xE0 L\u1EA1t B\u0110S",
    style: {
      height: 30
    },
    onClick: onLogo
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 6
    }
  }, /*#__PURE__*/React.createElement(IconButton, {
    icon: "search",
    variant: "ghost",
    size: "sm"
  }), /*#__PURE__*/React.createElement(IconButton, {
    icon: "bell",
    variant: "ghost",
    size: "sm"
  }), /*#__PURE__*/React.createElement(Avatar, {
    name: "Nguy\u1EC5n Minh Khang",
    size: 32,
    tone: "blue"
  })));
}
window.AppHeader = AppHeader;
function BookingToday({
  items
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      margin: "14px 16px 0",
      background: "var(--bg-card)",
      border: "1px solid var(--border)",
      borderRadius: "var(--radius-md)",
      padding: "12px 14px"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 5,
      fontSize: "var(--fs-12)",
      fontWeight: 600,
      color: "var(--text-secondary)",
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "calendar",
    size: 13
  }), " K\u1EBF ho\u1EA1ch g\u1EB7p g\u1EE1 h\xF4m nay"), items.map((b, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 11,
      padding: "7px 0",
      borderTop: i ? "1px solid var(--border)" : "none"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-14)",
      fontWeight: 700,
      color: "var(--primary)",
      width: 44
    }
  }, b.time), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-13)",
      fontWeight: 600,
      color: "var(--text-primary)"
    }
  }, b.name), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-11)",
      color: "var(--text-tertiary)"
    }
  }, b.addr)), /*#__PURE__*/React.createElement(Badge, {
    tone: b.done ? "green" : "blue"
  }, b.status))));
}
function HomeScreen({
  data,
  role,
  liked,
  onToggleLike,
  onOpen
}) {
  const [chip, setChip] = React.useState("Tất cả");
  const chips = ["Tất cả", "Đất ở", "Nhà phố", "Biệt thự", "Căn hộ", "Khách sạn"];
  const isStaff = role !== "guest";
  return /*#__PURE__*/React.createElement("div", null, isStaff && /*#__PURE__*/React.createElement(BookingToday, {
    items: data.bookings
  }), isStaff && /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "1fr 1fr",
      gap: 10,
      padding: "14px 16px 0"
    }
  }, /*#__PURE__*/React.createElement(StatCard, {
    icon: "briefcase",
    tone: "green",
    label: "C\u01A1 h\u1ED9i \u0111ang m\u1EDF",
    value: 7,
    delta: "kh\xE1ch \u0111ang t\u01B0 v\u1EA5n",
    onClick: () => onOpen("leads")
  }), /*#__PURE__*/React.createElement(StatCard, {
    icon: "dollarSign",
    tone: "purple",
    label: "Giao d\u1ECBch \u0111ang theo",
    value: 3,
    delta: "\u0111ang th\u01B0\u01A1ng l\u01B0\u1EE3ng",
    onClick: () => onOpen("commissions")
  }), /*#__PURE__*/React.createElement(StatCard, {
    icon: "users",
    tone: "amber",
    label: "T\u1EC7p kh\xE1ch h\xE0ng",
    value: 38,
    delta: "kh\xE1ch \u0111ang k\u1EBFt n\u1ED1i",
    onClick: () => onOpen("leads")
  }), /*#__PURE__*/React.createElement(StatCard, {
    icon: "star",
    tone: "pink",
    label: "\u0110i\u1EC3m uy t\xEDn",
    value: 126,
    delta: "+8 ph\u1EA3n h\u1ED3i tu\u1EA7n n\xE0y"
  })), /*#__PURE__*/React.createElement(SectionHeader, {
    icon: "trendingUp",
    title: "Nh\u1ECBp \u0111\u1EADp th\u1ECB tr\u01B0\u1EDDng \u0110\xE0 L\u1EA1t"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "0 16px"
    }
  }, /*#__PURE__*/React.createElement(MarketPriceStrip, null, data.market.map((m, i) => /*#__PURE__*/React.createElement(MarketPriceItem, _extends({
    key: i
  }, m))))), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "12px 16px 4px"
    }
  }, /*#__PURE__*/React.createElement(ChipRow, null, chips.map(c => /*#__PURE__*/React.createElement(Chip, {
    key: c,
    active: chip === c,
    onClick: () => setChip(c)
  }, c)))), /*#__PURE__*/React.createElement(SectionHeader, {
    title: "Tin m\u1EDBi nh\u1EA5t"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 14,
      padding: "0 16px 16px"
    }
  }, data.properties.map(p => /*#__PURE__*/React.createElement(PropCard, {
    key: p.id,
    image: undefined,
    category: p.category,
    transaction: p.transaction,
    price: p.price,
    title: p.title,
    address: role === "guest" ? "Đăng nhập để xem địa chỉ" : p.address,
    area: p.area,
    legal: role === "guest" ? null : p.legal,
    rooms: p.rooms,
    views: p.views,
    liked: liked.has(p.id),
    onToggleLike: () => onToggleLike(p.id),
    actions: role === "guest" ? ["share"] : role === "sale" ? ["phone", "share"] : ["edit", "phone", "share"],
    onClick: () => onOpen("detail", p)
  }))));
}
window.HomeScreen = HomeScreen;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/webapp/HomeScreen.jsx", error: String((e && e.message) || e) }); }

// ui_kits/webapp/ProfileScreen.jsx
try { (() => {
/* Đà Lạt BĐS WebApp — Profile screen with role-gated menu sections. */
const P = window.LTBSDesignSystem_ab8fed;
function MenuGroup({
  title,
  children
}) {
  return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-12)",
      fontWeight: 600,
      color: "var(--text-secondary)",
      padding: "16px 16px 8px"
    }
  }, title), /*#__PURE__*/React.createElement("div", {
    style: {
      borderTop: "1px solid var(--border)"
    }
  }, children));
}
function ProfileScreen({
  role,
  onOpen
}) {
  const {
    MenuItem,
    Badge,
    RoleBadge,
    Avatar
  } = P;
  const staff = role !== "guest";
  const canSale = role === "sale" || role === "sale_admin" || role === "admin";
  const canBds = role === "broker" || role === "bds_admin" || role === "admin";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      paddingBottom: 20
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      background: "var(--grad-hero)",
      padding: "24px 16px 22px",
      color: "#fff",
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "center",
      marginBottom: 12
    }
  }, /*#__PURE__*/React.createElement(Avatar, {
    name: "Nguy\u1EC5n Minh Khang",
    size: 72,
    tone: "blue",
    ring: true
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-18)",
      fontWeight: 700
    }
  }, "Nguy\u1EC5n Minh Khang"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "center",
      gap: 8,
      marginTop: 8
    }
  }, /*#__PURE__*/React.createElement(RoleBadge, {
    role: role,
    onLight: true
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--fs-11)",
      color: "rgba(255,255,255,.65)",
      alignSelf: "center"
    }
  }, "\u0110\xE0 L\u1EA1t B\u0110S"))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      background: "var(--bg-card)",
      borderBottom: "1px solid var(--border)"
    }
  }, [["12", "Tin BĐS"], ["3", "Giao dịch"], ["126 ★", "Uy tín"]].map(([v, l], i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      flex: 1,
      textAlign: "center",
      padding: "14px 0",
      borderLeft: i ? "1px solid var(--border)" : "none"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-18)",
      fontWeight: 700,
      color: "var(--text-primary)"
    }
  }, v), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--fs-11)",
      color: "var(--text-tertiary)"
    }
  }, l)))), canBds && /*#__PURE__*/React.createElement(MenuGroup, {
    title: "Qu\u1EA3n l\xFD B\u0110S"
  }, /*#__PURE__*/React.createElement(MenuItem, {
    icon: "building",
    tone: "blue",
    title: "B\u0110S c\u1EE7a t\xF4i",
    sub: "12 tin \xB7 2 ch\u1EDD duy\u1EC7t",
    badge: /*#__PURE__*/React.createElement(Badge, {
      tone: "amber"
    }, "2"),
    onClick: () => onOpen("home")
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "users",
    tone: "teal",
    title: "Ngu\u1ED3n kh\xE1ch",
    sub: "38 kh\xE1ch"
  })), canSale && /*#__PURE__*/React.createElement(MenuGroup, {
    title: "CRM \u2014 Ch\u0103m s\xF3c kh\xE1ch h\xE0ng"
  }, /*#__PURE__*/React.createElement(MenuItem, {
    icon: "users",
    tone: "blue",
    accent: "blue",
    title: "Kh\xE1ch c\u1EA7n ch\u0103m s\xF3c",
    sub: "8 kh\xE1ch \u0111ang theo d\xF5i",
    badge: /*#__PURE__*/React.createElement(Badge, {
      tone: "blue"
    }, "T\u1ED5ng h\u1EE3p"),
    onClick: () => onOpen("leads")
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "calendar",
    tone: "blue",
    title: "L\u1ECBch h\u1EB9n",
    sub: "Xem l\u1ECBch xem B\u0110S"
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "dollarSign",
    tone: "green",
    title: "Hoa h\u1ED3ng c\u1EE7a t\xF4i",
    sub: "248,5 tr d\u1EF1 ki\u1EBFn \xB7 8 deal",
    onClick: () => onOpen("commissions")
  })), (role === "bds_admin" || role === "admin") && /*#__PURE__*/React.createElement(MenuGroup, {
    title: "Qu\u1EA3n l\xFD khu v\u1EF1c B\u0110S"
  }, /*#__PURE__*/React.createElement(MenuItem, {
    icon: "building",
    tone: "red",
    accent: "red",
    title: "Duy\u1EC7t B\u0110S",
    sub: "5 tin ch\u1EDD xem x\xE9t",
    badge: /*#__PURE__*/React.createElement(Badge, {
      tone: "red"
    }, "5")
  })), (role === "sale_admin" || role === "admin") && /*#__PURE__*/React.createElement(MenuGroup, {
    title: "Qu\u1EA3n l\xFD Team Sale"
  }, /*#__PURE__*/React.createElement(MenuItem, {
    icon: "barChart",
    tone: "amber",
    title: "KPI & Team",
    sub: "Theo d\xF5i hi\u1EC7u qu\u1EA3 team"
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "target",
    tone: "red",
    title: "Assign Lead",
    sub: "Ph\xE2n c\xF4ng lead cho sale",
    onClick: () => onOpen("leads")
  })), role === "admin" && /*#__PURE__*/React.createElement(MenuGroup, {
    title: "Qu\u1EA3n tr\u1ECB h\u1EC7 th\u1ED1ng"
  }, /*#__PURE__*/React.createElement(MenuItem, {
    icon: "users",
    tone: "blue",
    title: "Qu\u1EA3n l\xFD ng\u01B0\u1EDDi d\xF9ng",
    sub: "Broker & kh\xE1ch h\xE0ng"
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "trendingUp",
    tone: "green",
    title: "B\xE1o c\xE1o t\u1ED5ng h\u1EE3p",
    sub: "Doanh thu \xB7 Deals \xB7 Hoa h\u1ED3ng"
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "barChart",
    tone: "amber",
    title: "Gi\xE1 th\u1ECB tr\u01B0\u1EDDng",
    sub: "Qu\u1EA3n l\xFD gi\xE1/m\xB2 theo khu v\u1EF1c"
  })), /*#__PURE__*/React.createElement(MenuGroup, {
    title: "M\u1EA1ng l\u01B0\u1EDBi gi\u1EDBi thi\u1EC7u"
  }, /*#__PURE__*/React.createElement(MenuItem, {
    icon: "link",
    tone: "purple",
    accent: "purple",
    title: "M\u1EA1ng l\u01B0\u1EDBi th\u1ED5 \u0111\u1ECBa",
    sub: "Chia s\u1EBB link \xB7 Nh\u1EADn 5% thu nh\u1EADp",
    badge: /*#__PURE__*/React.createElement(Badge, {
      tone: "purple",
      solid: true
    }, "5% MLM")
  })), /*#__PURE__*/React.createElement(MenuGroup, {
    title: "T\xE0i kho\u1EA3n"
  }, /*#__PURE__*/React.createElement(MenuItem, {
    icon: "edit",
    tone: "gray",
    title: "Ch\u1EC9nh s\u1EEDa h\u1ED3 s\u01A1"
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "bell",
    tone: "gray",
    title: "C\xE0i \u0111\u1EB7t th\xF4ng b\xE1o"
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "helpCircle",
    tone: "gray",
    title: "H\u1ED7 tr\u1EE3 & FAQ"
  }), /*#__PURE__*/React.createElement(MenuItem, {
    icon: "logout",
    tone: "red",
    danger: true,
    title: "\u0110\u0103ng xu\u1EA5t"
  })));
}
window.ProfileScreen = ProfileScreen;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/webapp/ProfileScreen.jsx", error: String((e && e.message) || e) }); }

// ui_kits/webapp/data.js
try { (() => {
/* Đà Lạt BĐS WebApp — mock data for the UI kit (no real listings). */
window.DLBDS_DATA = {
  user: {
    name: "Nguyễn Minh Khang",
    role: "sale"
  },
  market: [{
    area: "P.Cam Ly",
    price: "28,5 tr",
    trendDir: "up",
    trendPct: 3.2
  }, {
    area: "P.Lâm Viên",
    price: "42,0 tr",
    trendDir: "up",
    trendPct: 1.4
  }, {
    area: "P.5",
    price: "19,8 tr",
    trendDir: "down",
    trendPct: 1.1
  }, {
    area: "P.8",
    price: "24,3 tr",
    trendDir: "flat"
  }, {
    area: "Xuân Thọ",
    price: "9,6 tr",
    trendDir: "up",
    trendPct: 5.0
  }],
  bookings: [{
    time: "09:00",
    name: "Anh Minh Tuấn",
    addr: "Đường 3/4, P.Lâm Viên",
    status: "Chuẩn bị gặp",
    done: false
  }, {
    time: "14:30",
    name: "Chị Thu Hà",
    addr: "Đường Yersin, P.Cam Ly",
    status: "Đã tư vấn xong",
    done: true
  }],
  properties: [{
    id: 1,
    category: "Đất ở",
    transaction: "sale",
    price: "3,2 tỷ",
    grad: "linear-gradient(135deg,#2f6d5b,#16382c)",
    title: "Đất ở view đồi thông, P.Lâm Viên",
    address: "Đường 3/4, P.Lâm Viên",
    area: 120,
    legal: "Sổ đỏ",
    rooms: null,
    views: 248,
    direction: "Đông Nam",
    priceM2: "26,7 tr/m²",
    commission: "1%"
  }, {
    id: 2,
    category: "Biệt thự",
    transaction: "sale",
    price: "12,5 tỷ",
    grad: "linear-gradient(135deg,#3a5a8c,#16233f)",
    title: "Biệt thự nghỉ dưỡng sườn đồi, P.10",
    address: "Trại Mát, P.10",
    area: 340,
    legal: "Sổ hồng",
    rooms: 5,
    views: 512,
    direction: "Nam",
    priceM2: "36,8 tr/m²",
    commission: "1,5%"
  }, {
    id: 3,
    category: "Nhà phố",
    transaction: "rent",
    price: "18 tr/tháng",
    grad: "linear-gradient(135deg,#6b4f8a,#2c1f45)",
    title: "Nhà phố mặt tiền kinh doanh, P.1",
    address: "Đường Phan Đình Phùng, P.1",
    area: 90,
    legal: "Sổ đỏ",
    rooms: 4,
    views: 176,
    direction: "Tây Bắc",
    priceM2: "",
    commission: "1 tháng"
  }, {
    id: 4,
    category: "Khách sạn",
    transaction: "sale",
    price: "45 tỷ",
    grad: "linear-gradient(135deg,#8a6d3b,#3f2f16)",
    title: "Khách sạn 40 phòng trung tâm, P.2",
    address: "Đường Bùi Thị Xuân, P.2",
    area: 620,
    legal: "Sổ hồng",
    rooms: 40,
    views: 894,
    direction: "Đông",
    priceM2: "72,5 tr/m²",
    commission: "1%"
  }],
  commissions: {
    total: "248,5 tr",
    received: "120 tr",
    pending: "88 tr",
    upcoming: "40,5 tr",
    chart: [42, 88, 61, 120, 96, 140],
    labels: ["T2", "T3", "T4", "T5", "T6", "T7"],
    rows: [{
      name: "Anh Minh Tuấn",
      deal: "Đất ở · P.Lâm Viên",
      amount: "32,0 tr",
      status: "completed",
      tone: "green",
      label: "Hoàn tất"
    }, {
      name: "Chị Thu Hà",
      deal: "Nhà phố · P.Cam Ly",
      amount: "18,0 tr",
      status: "deposited",
      tone: "blue",
      label: "Đã cọc"
    }, {
      name: "Anh Quốc Bảo",
      deal: "Biệt thự · P.10",
      amount: "62,5 tr",
      status: "pending_deposit",
      tone: "amber",
      label: "Chờ cọc"
    }, {
      name: "Chị Lan Anh",
      deal: "Căn hộ · P.3",
      amount: "12,0 tr",
      status: "completed",
      tone: "green",
      label: "Hoàn tất"
    }]
  },
  leads: [{
    name: "Anh Minh Tuấn",
    phone: "0903 xxx 218",
    need: "Tìm Đất ở · 1–3 tỷ · P.Cam Ly",
    status: "new",
    tone: "blue",
    label: "Mới",
    tint: "blue"
  }, {
    name: "Chị Thu Hà",
    phone: "0912 xxx 774",
    need: "Tìm Nhà phố · 1,5–3 tỷ · P.1",
    status: "contacted",
    tone: "amber",
    label: "Đã liên hệ",
    tint: "teal"
  }, {
    name: "Anh Quốc Bảo",
    phone: "0987 xxx 130",
    need: "Biệt thự nghỉ dưỡng · 10–15 tỷ",
    status: "converted",
    tone: "green",
    label: "Đã chuyển",
    tint: "purple"
  }, {
    name: "Chị Lan Anh",
    phone: "0938 xxx 902",
    need: "Căn hộ · dưới 2 tỷ · trung tâm",
    status: "new",
    tone: "blue",
    label: "Mới",
    tint: "amber"
  }]
};
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/webapp/data.js", error: String((e && e.message) || e) }); }

__ds_ns.Avatar = __ds_scope.Avatar;

__ds_ns.Badge = __ds_scope.Badge;

__ds_ns.Button = __ds_scope.Button;

__ds_ns.Chip = __ds_scope.Chip;

__ds_ns.ChipRow = __ds_scope.ChipRow;

__ds_ns.IconGlyphs = __ds_scope.IconGlyphs;

__ds_ns.Icon = __ds_scope.Icon;

__ds_ns.IconButton = __ds_scope.IconButton;

__ds_ns.Input = __ds_scope.Input;

__ds_ns.BottomSheet = __ds_scope.BottomSheet;

__ds_ns.HeroCard = __ds_scope.HeroCard;

__ds_ns.Toast = __ds_scope.Toast;

__ds_ns.ROLES = __ds_scope.ROLES;

__ds_ns.RoleBadge = __ds_scope.RoleBadge;

__ds_ns.BottomNav = __ds_scope.BottomNav;

__ds_ns.Tabs = __ds_scope.Tabs;

__ds_ns.MarketPriceItem = __ds_scope.MarketPriceItem;

__ds_ns.MarketPriceStrip = __ds_scope.MarketPriceStrip;

__ds_ns.MenuItem = __ds_scope.MenuItem;

__ds_ns.PropCard = __ds_scope.PropCard;

__ds_ns.SectionHeader = __ds_scope.SectionHeader;

__ds_ns.StatCard = __ds_scope.StatCard;

})();
