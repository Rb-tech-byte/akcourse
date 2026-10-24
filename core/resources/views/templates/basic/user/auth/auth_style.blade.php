<style>
/* ==========================================================================
   MAARIFA23 USER AUTHENTICATION PAGES (LOGIN & REGISTER)
   Clean, Modern, Dark Luxury Studio Theme - Aligned with Asset 3.png
   Responsive Mobile-First Architecture
   ========================================================================== */

:root {
  --m23-gold: #FBAF43;
  --m23-gold-dark: #E29828;
  --m23-gold-light: #FFF7ED;
  --m23-charcoal: #3A3A3C;
}

.ak23-auth-section {
  position: relative;
  min-height: calc(100vh - 160px);
  padding: 50px 0 80px;
  background-color: var(--ak23-bg-main, #0C0D10);
  overflow: hidden;
  display: flex;
  align-items: center;
}

/* Ambient Audio Studio Glows */
.ak23-auth-glow {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
  z-index: 0;
  filter: blur(120px);
}

.ak23-auth-glow-green,
.ak23-auth-glow-gold {
  top: -120px;
  left: -100px;
  width: 550px;
  height: 550px;
  background: radial-gradient(circle, rgba(251, 175, 67, 0.12) 0%, rgba(251, 175, 67, 0) 70%);
}

.ak23-auth-container {
  position: relative;
  z-index: 1;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  width: 100%;
}

.ak23-auth-grid {
  display: grid;
  grid-template-columns: 1.05fr 0.95fr;
  gap: 56px;
  align-items: center;
}

/* --------------------------------------------------------------------------
   Left Showcase Column (Desktop)
   -------------------------------------------------------------------------- */
.ak23-auth-showcase {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.ak23-auth-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(251, 175, 67, 0.12);
  border: 1px solid rgba(251, 175, 67, 0.28);
  color: #FBAF43;
  padding: 6px 14px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  width: fit-content;
  white-space: nowrap;
}

.ak23-auth-badge-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #FBAF43;
  box-shadow: 0 0 8px #FBAF43;
  flex-shrink: 0;
}

.ak23-auth-hero-title {
  font-family: var(--ak23-font-heading, 'Plus Jakarta Sans', sans-serif);
  font-size: 40px;
  font-weight: 800;
  line-height: 1.15;
  color: #FFFFFF;
  letter-spacing: -0.025em;
  margin: 0;
}

.ak23-auth-hero-title span {
  background: linear-gradient(135deg, #FFBE5C 0%, #FBAF43 50%, #E29828 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.ak23-auth-hero-sub {
  font-size: 15px;
  line-height: 1.6;
  color: var(--ak23-text-muted, #9E9EB2);
  margin: 0;
  max-width: 500px;
}

/* Showcase Feature Cards */
.ak23-auth-features {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.ak23-auth-feature-item {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 16px 18px;
  background: rgba(20, 21, 26, 0.6);
  border: 1px solid rgba(255, 255, 255, 0.07);
  border-radius: 14px;
  backdrop-filter: blur(8px);
  transition: all 0.2s ease;
}

.ak23-auth-feature-item:hover {
  background: rgba(26, 27, 34, 0.8);
  border-color: rgba(251, 175, 67, 0.3);
  transform: translateY(-2px);
}

.ak23-auth-feature-icon {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  background: rgba(251, 175, 67, 0.12);
  color: #FBAF43;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
  border: 1px solid rgba(251, 175, 67, 0.25);
}

.ak23-auth-feature-icon.gold {
  background: rgba(251, 175, 67, 0.12);
  color: #FBAF43;
  border-color: rgba(251, 175, 67, 0.25);
}

.ak23-auth-feature-icon.blue {
  background: rgba(58, 58, 60, 0.3);
  color: #E2E8F0;
  border-color: rgba(255, 255, 255, 0.12);
}

.ak23-auth-feature-content h6 {
  font-size: 14.5px;
  font-weight: 700;
  color: #FFFFFF;
  margin: 0 0 3px 0;
}

.ak23-auth-feature-content p {
  font-size: 12.5px;
  line-height: 1.45;
  color: var(--ak23-text-muted, #9E9EB2);
  margin: 0;
}

/* Audio EQ Visualizer Bar */
.ak23-auth-eq-strip {
  display: flex;
  align-items: center;
  gap: 16px;
  padding-top: 14px;
  border-top: 1px solid rgba(255, 255, 255, 0.07);
}

.ak23-eq-bars {
  display: flex;
  align-items: flex-end;
  gap: 3px;
  height: 20px;
  flex-shrink: 0;
}

.ak23-eq-bar {
  width: 3px;
  background: #FBAF43;
  border-radius: 2px;
  animation: ak23EqAnimation 1.2s ease-in-out infinite alternate;
}

.ak23-eq-bar:nth-child(1) { height: 60%; animation-delay: 0.1s; }
.ak23-eq-bar:nth-child(2) { height: 90%; animation-delay: 0.3s; }
.ak23-eq-bar:nth-child(3) { height: 40%; animation-delay: 0.2s; }
.ak23-eq-bar:nth-child(4) { height: 100%; animation-delay: 0.5s; }
.ak23-eq-bar:nth-child(5) { height: 70%; animation-delay: 0.4s; }

@keyframes ak23EqAnimation {
  0% { height: 25%; }
  100% { height: 100%; }
}

.ak23-auth-proof-text {
  font-size: 12.5px;
  color: var(--ak23-text-muted, #9E9EB2);
}

.ak23-auth-proof-text strong {
  color: #FFFFFF;
}

/* --------------------------------------------------------------------------
   Auth Card Column & Container
   -------------------------------------------------------------------------- */
.ak23-auth-card-col {
  width: 100%;
}

.ak23-auth-card {
  background: linear-gradient(180deg, #15161C 0%, #0F1014 100%);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 22px;
  padding: 38px 34px;
  box-shadow: 0 24px 60px -10px rgba(0, 0, 0, 0.65), 0 0 40px rgba(251, 175, 67, 0.04);
  position: relative;
  overflow: hidden;
  width: 100%;
  box-sizing: border-box;
}

.ak23-auth-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, #FBAF43, #E29828, transparent);
  opacity: 0.85;
}

.ak23-auth-card-header {
  margin-bottom: 22px;
}

/* Modern Card Badge Chip */
.ak23-auth-card-badge {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: rgba(251, 175, 67, 0.1);
  border: 1px solid rgba(251, 175, 67, 0.25);
  border-radius: 999px;
  padding: 5px 12px;
  color: #FBAF43;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  margin-bottom: 12px;
  white-space: nowrap;
}

.ak23-auth-card-badge i {
  font-size: 14px;
}

.ak23-auth-card-title {
  font-family: var(--ak23-font-heading, 'Plus Jakarta Sans', sans-serif);
  font-size: 26px;
  font-weight: 800;
  color: #FFFFFF;
  margin: 0 0 6px 0;
  letter-spacing: -0.02em;
  line-height: 1.25;
}

.ak23-auth-card-sub {
  font-size: 13.5px;
  color: var(--ak23-text-muted, #9E9EB2);
  margin: 0;
  line-height: 1.5;
}

/* Social Buttons */
.ak23-social-grid {
  display: flex;
  gap: 10px;
  margin-bottom: 18px;
}

.ak23-social-btn {
  flex: 1;
  min-height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #181920;
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 11px;
  padding: 10px 14px;
  color: #FFFFFF !important;
  font-size: 13.5px;
  font-weight: 600;
  text-decoration: none !important;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.ak23-social-btn:hover {
  background: #20222B;
  border-color: rgba(251, 175, 67, 0.3);
  transform: translateY(-1px);
}

.ak23-social-btn i {
  font-size: 18px;
}

/* Divider */
.ak23-auth-divider {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 20px;
}

.ak23-auth-divider::before,
.ak23-auth-divider::after {
  content: "";
  flex: 1;
  height: 1px;
  background: rgba(255, 255, 255, 0.08);
}

.ak23-auth-divider span {
  font-size: 11.5px;
  font-weight: 700;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: #64748B;
  white-space: nowrap;
}

/* Form inputs & Groups */
.ak23-form-group {
  margin-bottom: 16px;
}

.ak23-form-label-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 7px;
  gap: 8px;
  width: 100%;
}

.ak23-form-label {
  display: inline-flex;
  align-items: center;
  gap: 3px;
  font-size: 13px;
  font-weight: 600;
  color: #CBD5E1;
  margin-bottom: 7px;
  letter-spacing: 0.01em;
}

.ak23-form-label-row .ak23-form-label {
  margin-bottom: 0;
}

.ak23-req-star {
  color: #FBAF43;
  margin-left: 2px;
  font-weight: 700;
}

.ak23-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.ak23-input-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 18px;
  color: #64748B;
  pointer-events: none;
  transition: color 0.2s ease;
  z-index: 2;
}

.ak23-input {
  width: 100% !important;
  height: 48px !important;
  background: #0E0F14 !important;
  border: 1px solid #23252E !important;
  border-radius: 11px !important;
  color: #FFFFFF !important;
  padding: 0 16px 0 44px !important;
  font-size: 16px !important; /* 16px prevents iOS Safari auto-zoom */
  font-family: inherit !important;
  box-sizing: border-box !important;
  transition: all 0.2s ease !important;
}

.ak23-input:focus {
  border-color: #FBAF43 !important;
  box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.18) !important;
  background: #13141A !important;
  outline: none !important;
}

.ak23-input::placeholder {
  color: #475569 !important;
  font-size: 13.5px !important;
}

.ak23-input:-webkit-autofill,
.ak23-input:-webkit-autofill:hover, 
.ak23-input:-webkit-autofill:focus,
.ak23-input:-webkit-autofill:active {
  -webkit-text-fill-color: #FFFFFF !important;
  -webkit-box-shadow: 0 0 0 1000px #0E0F14 inset !important;
  transition: background-color 5000s ease-in-out 0s !important;
}

.ak23-input-wrapper:focus-within .ak23-input-icon {
  color: #FBAF43;
}

.ak23-input-pw {
  padding-right: 48px !important;
}

.ak23-pw-toggle {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  width: 36px;
  height: 36px;
  background: transparent;
  border: none;
  color: #64748B;
  font-size: 19px;
  cursor: pointer;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  transition: all 0.2s ease;
  z-index: 2;
}

.ak23-pw-toggle:hover {
  color: #FBAF43;
  background: rgba(251, 175, 67, 0.08);
}

/* Row elements (Remember, forgot pass) */
.ak23-auth-meta-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 12px 0 18px;
  gap: 8px;
}

.ak23-checkbox-label {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  color: #94A3B8;
  font-size: 13px;
  cursor: pointer;
  user-select: none;
  margin: 0;
  line-height: 1.4;
}

.ak23-checkbox {
  width: 17px;
  height: 17px;
  min-width: 17px;
  accent-color: #FBAF43;
  cursor: pointer;
  border-radius: 4px;
  margin: 0;
}

.ak23-auth-forgot {
  color: #FBAF43 !important;
  font-size: 12.5px;
  font-weight: 600;
  text-decoration: none !important;
  transition: color 0.2s ease;
  white-space: nowrap;
}

.ak23-auth-forgot:hover {
  color: #FFBE5C !important;
  text-decoration: underline !important;
}

/* Submit Button */
.ak23-auth-submit-btn {
  width: 100%;
  min-height: 48px;
  background: linear-gradient(135deg, #FFBE5C 0%, #FBAF43 50%, #E29828 100%);
  color: #0E0F13 !important;
  font-family: var(--ak23-font-heading, 'Plus Jakarta Sans', sans-serif);
  font-weight: 800;
  font-size: 15px;
  border-radius: 12px;
  border: none;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  cursor: pointer;
  transition: all 0.25s ease;
  box-shadow: 0 6px 20px rgba(251, 175, 67, 0.28);
  letter-spacing: 0.01em;
  white-space: nowrap;
  box-sizing: border-box;
}

.ak23-auth-submit-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 26px rgba(251, 175, 67, 0.42);
  background: linear-gradient(135deg, #FFC46A 0%, #FBAF43 100%);
  color: #0E0F13 !important;
}

.ak23-auth-submit-btn:active {
  transform: translateY(0);
}

/* Footer Switcher */
.ak23-auth-footer {
  margin-top: 22px;
  padding-top: 18px;
  border-top: 1px solid rgba(255, 255, 255, 0.07);
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
}

.ak23-auth-switch-text {
  font-size: 13.5px;
  color: #94A3B8;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  flex-wrap: wrap;
}

.ak23-auth-switch-link {
  color: #FBAF43 !important;
  font-weight: 700;
  text-decoration: none !important;
  transition: color 0.2s ease;
  white-space: nowrap;
}

.ak23-auth-switch-link:hover {
  color: #FFBE5C !important;
  text-decoration: underline !important;
}

.ak23-auth-back-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  color: #64748B !important;
  font-size: 12.5px;
  font-weight: 600;
  text-decoration: none !important;
  transition: color 0.2s ease;
  margin-top: 2px;
}

.ak23-auth-back-link:hover {
  color: #94A3B8 !important;
}

/* Form Grid for Registration */
.ak23-form-grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

/* Policy Terms Checkbox */
.ak23-policy-text {
  font-size: 12.5px;
  color: #94A3B8;
  line-height: 1.5;
}

.ak23-policy-text a {
  color: #FBAF43 !important;
  text-decoration: none !important;
}

.ak23-policy-text a:hover {
  text-decoration: underline !important;
}

/* Referral Info Box in Register */
.ak23-referral-banner {
  display: flex;
  align-items: center;
  gap: 12px;
  background: rgba(251, 175, 67, 0.1);
  border: 1px solid rgba(251, 175, 67, 0.25);
  border-radius: 12px;
  padding: 12px 16px;
  margin-bottom: 16px;
}

.ak23-referral-icon {
  font-size: 22px;
  color: #FBAF43;
  flex-shrink: 0;
}

.ak23-referral-text {
  font-size: 13px;
  color: #E2E8F0;
}

.ak23-referral-text strong {
  color: #FBAF43;
}

/* Modal dark theme support */
#existModalCenter .modal-content {
  background: #141519;
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #FFFFFF;
  border-radius: 16px;
}

#existModalCenter .modal-header {
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

#existModalCenter .modal-footer {
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

#existModalCenter .btn-dark {
  background: #23252E;
  border: none;
  color: #FFFFFF;
}

/* ==========================================================================
   RESPONSIVE DESIGN RULES (MOBILE-FIRST ADAPTATIONS)
   ========================================================================== */

/* Tablet & Mobile Screens (< 992px) */
@media (max-width: 991.98px) {
  .ak23-auth-section {
    padding: 30px 0 60px;
    min-height: auto;
  }

  .ak23-auth-container {
    max-width: 520px;
    padding: 0 16px;
  }

  .ak23-auth-grid {
    display: flex;
    flex-direction: column;
    gap: 32px;
  }

  /* CRITICAL MOBILE UX: Card is at the top (order: 1), Showcase below (order: 2) */
  .ak23-auth-card-col {
    order: 1;
    width: 100%;
  }

  .ak23-auth-showcase {
    order: 2;
    text-align: center;
    align-items: center;
    gap: 18px;
    padding-top: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.07);
  }

  .ak23-auth-hero-title {
    font-size: 24px;
    text-align: center;
  }

  .ak23-auth-hero-sub {
    font-size: 13.5px;
    text-align: center;
    max-width: 100%;
  }

  .ak23-auth-features {
    width: 100%;
    gap: 10px;
  }

  .ak23-auth-feature-item {
    text-align: left;
    padding: 12px 14px;
    gap: 12px;
  }

  .ak23-auth-feature-icon {
    width: 38px;
    height: 38px;
    font-size: 18px;
  }

  .ak23-auth-feature-content h6 {
    font-size: 13.5px;
  }

  .ak23-auth-feature-content p {
    font-size: 12px;
  }

  .ak23-auth-eq-strip {
    justify-content: center;
    width: 100%;
  }

  .ak23-auth-card {
    padding: 28px 22px;
    border-radius: 18px;
  }

  .ak23-form-grid-2 {
    grid-template-columns: 1fr;
    gap: 0;
  }
}

/* Small Smartphone Devices (< 576px) */
@media (max-width: 575.98px) {
  .ak23-auth-section {
    padding: 20px 0 50px;
  }

  .ak23-auth-container {
    padding: 0 14px;
  }

  .ak23-auth-card {
    padding: 24px 18px;
    border-radius: 16px;
  }

  .ak23-auth-card-title {
    font-size: 22px;
  }

  .ak23-auth-card-sub {
    font-size: 13px;
  }

  .ak23-social-grid {
    flex-direction: column;
    gap: 8px;
  }

  .ak23-auth-submit-btn {
    font-size: 14.5px;
    padding: 12px 16px;
  }

  .ak23-auth-switch-text {
    font-size: 13px;
  }
}

/* Extra Small Devices (< 375px, e.g. iPhone SE 320px) */
@media (max-width: 374.98px) {
  .ak23-auth-container {
    padding: 0 10px;
  }

  .ak23-auth-card {
    padding: 20px 14px;
  }

  .ak23-input {
    padding: 0 12px 0 38px !important;
  }

  .ak23-input-icon {
    left: 12px;
    font-size: 16px;
  }

  .ak23-auth-submit-btn {
    font-size: 14px;
    gap: 8px;
  }

  .ak23-auth-forgot {
    font-size: 12px;
  }
}
</style>
