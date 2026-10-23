<style>
/* ==========================================================================
   MAARIFA23 ADMIN GLOBAL THEME - LOGO & DESIGN SYSTEM ALIGNED
   Colors Extracted from Asset 3.png:
   - Primary Accent / Warm Gold: #FBAF43
   - Primary Dark / Honey Bronze: #E29828
   - Deep Accent: #C8811C
   - Soft Cream Gold: #FFF7ED
   - Gold Border: rgba(251, 175, 67, 0.3)
   - Dark Charcoal: #3A3A3C
   - Dark Canvas: #121316
   - Light Slate: #F8FAFC
   ========================================================================== */

:root {
  --m23-gold: #FBAF43;
  --m23-gold-dark: #E29828;
  --m23-gold-deep: #C8811C;
  --m23-gold-light: #FFF7ED;
  --m23-gold-border: rgba(251, 175, 67, 0.3);
  --m23-charcoal: #3A3A3C;
  --m23-slate-bg: #F8FAFC;
  --m23-border: #E2E8F0;
  --m23-text-main: #0F172A;
  --m23-text-muted: #64748B;
  --m23-sidebar-bg: #121316;

  /* Backward compatibility aliases */
  --ak-green: #FBAF43;
  --ak-green-dark: #E29828;
  --ak-green-light: #FFF7ED;
  --ak-green-border: rgba(251, 175, 67, 0.3);
  --ak-slate-bg: #F8FAFC;
  --ak-border: #E2E8F0;
  --ak-text-main: #0F172A;
  --ak-text-muted: #64748B;
  --ak-sidebar-bg: #121316;
}

/* 1. Global Page Background & Typography */
body,
.page-wrapper,
.body-wrapper,
.bodywrapper__inner {
  background-color: var(--m23-slate-bg) !important;
}

.page-title,
.page-title h6,
h6.page-title {
  color: var(--m23-text-main) !important;
  font-weight: 800 !important;
  font-size: 22px !important;
  letter-spacing: -0.02em !important;
}

/* 2. Top Navigation Bar (Clean White Background) */
.navbar-wrapper,
.navbar-wrapper.ak23-navbar {
  background: #FFFFFF !important;
  background-color: #FFFFFF !important;
  border-bottom: 1px solid var(--m23-border) !important;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
  padding: 12px 28px !important;
}

.navbar-wrapper .res-sidebar-open-btn {
  color: var(--m23-text-muted) !important;
  background: transparent !important;
  border: none !important;
  font-size: 20px !important;
}

.navbar-search {
  position: relative;
  width: 320px;
}

.navbar-search .navbar-search-field {
  background-color: var(--m23-slate-bg) !important;
  color: var(--m23-text-main) !important;
  border: 1px solid var(--m23-border) !important;
  border-radius: 8px !important;
  padding: 8px 14px 8px 36px !important;
  font-size: 13px !important;
  font-weight: 500 !important;
  transition: all 0.2s ease !important;
}

.navbar-search .navbar-search-field::placeholder {
  color: #94A3B8 !important;
}

.navbar-search .navbar-search-field:focus {
  background-color: #FFFFFF !important;
  border-color: var(--m23-gold) !important;
  box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.18) !important;
}

.navbar-search i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #94A3B8 !important;
  font-size: 16px !important;
}

.navbar__right .navbar__action-list {
  display: flex;
  align-items: center;
  gap: 10px;
}

.navbar__right button.primary--layer,
.navbar__right .primary--layer {
  background: transparent !important;
  border: 1px solid transparent !important;
  width: 36px !important;
  height: 36px !important;
  border-radius: 8px !important;
  color: var(--m23-text-muted) !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: all 0.15s ease !important;
}

.navbar__right button.primary--layer i,
.navbar__right .primary--layer i {
  color: var(--m23-text-muted) !important;
  font-size: 18px !important;
  transition: color 0.15s ease !important;
}

.navbar__right button.primary--layer:hover,
.navbar__right .primary--layer:hover {
  background: #F1F5F9 !important;
  color: var(--m23-text-main) !important;
}
.navbar__right button.primary--layer:hover i,
.navbar__right .primary--layer:hover i {
  color: var(--m23-text-main) !important;
}

.navbar-user__info,
.navbar-user__name {
  color: var(--m23-text-main) !important;
  font-weight: 700 !important;
  font-size: 13.5px !important;
}

.navbar-user__thumb {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  overflow: hidden;
  border: 1.5px solid var(--m23-gold);
  display: flex;
  align-items: center;
  justify-content: center;
}

.navbar-user__thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.navbar-user button {
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  color: var(--m23-text-main) !important;
  background: transparent !important;
}

.navbar-user button i {
  color: var(--m23-text-muted) !important;
}

/* 3. Tables Across All Navigation Pages */
table.table--light thead th,
table.dataTable thead th,
.table thead th,
.table-responsive thead th,
.table-responsive--lg thead th,
.table-responsive--md thead th,
.table-responsive--sm thead th {
  background-color: var(--m23-slate-bg) !important;
  background: var(--m23-slate-bg) !important;
  color: #475569 !important;
  font-size: 11px !important;
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.05em !important;
  border-top: none !important;
  border-bottom: 1px solid var(--m23-border) !important;
  padding: 12px 18px !important;
}

table.table--light tbody td,
table.dataTable tbody td,
.table tbody td {
  padding: 12px 18px !important;
  color: #1E293B !important;
  font-size: 13px !important;
  border-bottom: 1px solid #F1F5F9 !important;
  vertical-align: middle !important;
}

table.table--light tbody tr:hover,
.table tbody tr:hover {
  background-color: var(--m23-slate-bg) !important;
}

/* 4. Cards & Panels */
.card {
  background-color: #FFFFFF !important;
  border: 1px solid var(--m23-border) !important;
  border-radius: 12px !important;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
  overflow: hidden !important;
}

.card-header {
  background-color: #FFFFFF !important;
  border-bottom: 1px solid #F1F5F9 !important;
  padding: 16px 20px !important;
}

.card-header .card-title {
  color: var(--m23-text-main) !important;
  font-weight: 700 !important;
  font-size: 15px !important;
}

.card-footer {
  background-color: #FFFFFF !important;
  border-top: 1px solid #F1F5F9 !important;
  padding: 14px 20px !important;
}

/* 5. Buttons (Maarifa23 Warm Gold & Charcoal Theme) */
.btn--primary,
button.btn--primary,
a.btn--primary,
input.btn--primary {
  --color: #FBAF43;
  background-color: #FBAF43 !important;
  border-color: #FBAF43 !important;
  color: #121316 !important;
  font-weight: 700 !important;
  box-shadow: 0 2px 10px rgba(251, 175, 67, 0.28) !important;
  transition: all 0.2s ease !important;
}

.btn--primary:hover,
.btn--primary:focus,
.btn--primary:active {
  background-color: #E29828 !important;
  border-color: #E29828 !important;
  color: #FFFFFF !important;
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(251, 175, 67, 0.4) !important;
}

.btn-outline--primary {
  --color: #FBAF43;
  color: #D9871A !important;
  border-color: rgba(251, 175, 67, 0.45) !important;
  background-color: transparent !important;
  font-weight: 700 !important;
  transition: all 0.2s ease !important;
}

.btn-outline--primary:hover,
.btn-outline--primary:focus,
.btn-outline--primary:active {
  background-color: rgba(251, 175, 67, 0.12) !important;
  border-color: #FBAF43 !important;
  color: #B45309 !important;
}

/* Success button unified with Maarifa23 Theme */
.btn--success,
button.btn--success,
a.btn--success {
  background-color: #FBAF43 !important;
  border-color: #FBAF43 !important;
  color: #121316 !important;
  font-weight: 700 !important;
}

.btn--success:hover {
  background-color: #E29828 !important;
  border-color: #E29828 !important;
  color: #FFFFFF !important;
}

/* Input addons & Filters */
.input-group-text.bg--primary,
.input-group button.btn--primary {
  background-color: #FBAF43 !important;
  border-color: #FBAF43 !important;
  color: #121316 !important;
  font-weight: 700 !important;
}

.input-group .form-control:focus {
  border-color: #FBAF43 !important;
  box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.18) !important;
}

.form-control:focus,
.form-select:focus {
  border-color: #FBAF43 !important;
  box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.18) !important;
}

/* 6. Utility Colors & Links */
.bg--primary {
  background-color: #FBAF43 !important;
  color: #121316 !important;
}

.text--primary,
a.text--primary {
  color: #D9871A !important;
}

.text--primary:hover,
a.text--primary:hover {
  color: #B45309 !important;
}

table a:not(.btn) {
  color: #D9871A;
  font-weight: 600;
}

table a:not(.btn):hover {
  color: #B45309;
  text-decoration: underline;
}

.badge--primary {
  background-color: rgba(251, 175, 67, 0.14) !important;
  border: 1px solid rgba(251, 175, 67, 0.32) !important;
  color: #B45309 !important;
  font-weight: 700 !important;
}

/* 7. Pagination */
.page-item.active .page-link {
  background-color: #FBAF43 !important;
  border-color: #FBAF43 !important;
  color: #121316 !important;
  font-weight: 800 !important;
}

.page-link {
  color: #475569 !important;
  border-color: var(--m23-border) !important;
}

.page-link:hover {
  color: #D9871A !important;
  border-color: #FBAF43 !important;
  background-color: rgba(251, 175, 67, 0.08) !important;
}

/* 8. Floating Setup Banner (.configure-card) */
.configure-card {
  border-radius: 12px !important;
  background: #121316 !important;
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45) !important;
}

.configure-card-header {
  background: #121316 !important;
  background-image: none !important;
  border-radius: 12px !important;
  border: none !important;
}

.configure-card-header .progress {
  background-color: rgba(255, 255, 255, 0.1) !important;
  height: 6px !important;
  border-radius: 999px !important;
}

.configure-card-header .progress-bar {
  background-color: #FBAF43 !important;
  border-radius: 999px !important;
}

.configure-card-link {
  background-color: #FBAF43 !important;
  color: #121316 !important;
  border-radius: 6px !important;
  padding: 6px 14px !important;
  font-weight: 800 !important;
  border: none !important;
  transition: all 0.2s ease !important;
}

.configure-card-link:hover {
  background-color: #E29828 !important;
  color: #FFFFFF !important;
}

.configure-card-title {
  color: #FFFFFF !important;
}

.configure-card-bottom a {
  color: #94A3B8 !important;
}

.configure-card-bottom a:hover {
  color: #FFFFFF !important;
}

.configure-card-bottom .count {
  color: #CBD5E1 !important;
}

.skip-configure {
  background-color: #1E1F24 !important;
  color: #CBD5E1 !important;
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
}

/* 9. Nice Select & Form Selects */
.nice-select .option.selected,
.nice-select .option:hover {
  background-color: rgba(251, 175, 67, 0.12) !important;
  color: #B45309 !important;
}

/* 10. Strict Elimination of Old Blue Utilities (Bootstrap 5 & Custom Admin) */
.text-primary, a.text-primary, .text--primary, a.text--primary {
  color: #D9871A !important;
}
.text-primary:hover, a.text-primary:hover, .text--primary:hover, a.text--primary:hover {
  color: #B45309 !important;
}
.bg-primary, .bg--primary {
  background-color: #FBAF43 !important;
  color: #121316 !important;
}
.bg-primary-subtle, .badge--primary {
  background-color: rgba(251, 175, 67, 0.14) !important;
  color: #B45309 !important;
  border-color: rgba(251, 175, 67, 0.3) !important;
}
.btn-primary, .btn--primary {
  background-color: #FBAF43 !important;
  border-color: #FBAF43 !important;
  color: #121316 !important;
  font-weight: 700 !important;
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active,
.btn--primary:hover, .btn--primary:focus, .btn--primary:active {
  background-color: #E29828 !important;
  border-color: #E29828 !important;
  color: #FFFFFF !important;
}
.btn-outline-primary, .btn-outline--primary {
  color: #D9871A !important;
  border-color: rgba(251, 175, 67, 0.45) !important;
  background-color: transparent !important;
  font-weight: 700 !important;
}
.btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active,
.btn-outline--primary:hover, .btn-outline--primary:focus, .btn-outline--primary:active {
  background-color: rgba(251, 175, 67, 0.12) !important;
  border-color: #FBAF43 !important;
  color: #B45309 !important;
}
.border-primary, .border-primary-subtle {
  border-color: rgba(251, 175, 67, 0.35) !important;
}
.badge.bg-primary, .badge.bg--primary {
  background-color: #FBAF43 !important;
  color: #121316 !important;
}
.badge.bg-primary-subtle {
  background-color: rgba(251, 175, 67, 0.14) !important;
  color: #B45309 !important;
  border: 1px solid rgba(251, 175, 67, 0.32) !important;
}
.badge.bg-info-subtle {
  background-color: rgba(251, 175, 67, 0.10) !important;
  color: #92400E !important;
  border: 1px solid rgba(251, 175, 67, 0.25) !important;
}
.badge.bg-secondary-subtle {
  background-color: #F1F5F9 !important;
  color: #475569 !important;
  border: 1px solid #E2E8F0 !important;
}
.hover-primary:hover {
  color: #FBAF43 !important;
}

/* 11. SYSTEM Component Styles (REST & RPC API, Webhooks, System Setting) */
.toggle.btn-success,
.toggle-on.btn-success {
  background-color: #FBAF43 !important;
  border-color: #E29828 !important;
  color: #0A0D12 !important;
  font-weight: 700 !important;
}

.toggle.btn-danger,
.toggle-off.btn-danger {
  background-color: #1E293B !important;
  border-color: #0F172A !important;
  color: #F8FAFC !important;
  font-weight: 600 !important;
}

.toggle-handle {
  background-color: #FFFFFF !important;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) !important;
}

/* Webhooks / Notification Tabs */
.notification-via {
  border: 1.5px solid var(--m23-border) !important;
  background: #FFFFFF !important;
  border-radius: 10px !important;
  transition: all 0.2s ease !important;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
}

.notification-via:hover {
  border-color: rgba(251, 175, 67, 0.4) !important;
  background: #FFFDF9 !important;
  transform: translateY(-2px);
}

.notification-via.active {
  border-color: var(--m23-gold) !important;
  background: rgba(251, 175, 67, 0.06) !important;
  box-shadow: 0 4px 14px rgba(251, 175, 67, 0.15) !important;
}

.notification-via.active .send-via-method i {
  color: var(--m23-gold) !important;
}

.notification-via.active .send-via-method h5 {
  color: var(--m23-text-main) !important;
  font-weight: 700 !important;
}

.notification-via.active .active-badge {
  background: var(--m23-gold) !important;
  color: #0A0D12 !important;
}

/* System Setting Search & Widgets */
.system-search-icon i {
  color: var(--m23-text-muted) !important;
}

.form-control.searchInput:focus {
  border-color: var(--m23-gold) !important;
  box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.2) !important;
}

/* Pagination Links (System & Reports) */
.pagination .page-item.active .page-link {
  background-color: var(--m23-gold) !important;
  border-color: var(--m23-gold) !important;
  color: #0A0D12 !important;
  font-weight: 700 !important;
}

.pagination .page-link {
  color: var(--m23-text-main) !important;
  border-color: var(--m23-border) !important;
}

.pagination .page-link:hover {
  background-color: var(--m23-gold-light) !important;
  color: var(--m23-gold-dark) !important;
}
</style>
