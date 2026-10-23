<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ==========================================================================
   MAARIFA23 ADMIN AUTHENTICATION (LOGIN & PASSWORD RECOVERY)
   High-End Luxury Dark Studio Theme - Aligned with Asset 3.png
   ========================================================================== */

:root {
  --m23-admin-bg: #0C0D10;
  --m23-admin-card-bg: #141519;
  --m23-admin-input-bg: #0E0F12;
  --m23-admin-border: rgba(255, 255, 255, 0.08);
  --m23-admin-border-focus: #FBAF43;
  --m23-admin-gold: #FBAF43;
  --m23-admin-gold-dark: #E29828;
  --m23-admin-gold-glow: rgba(251, 175, 67, 0.25);
  --m23-admin-charcoal: #3A3A3C;
  --m23-admin-text: #F3F4F6;
  --m23-admin-text-muted: #8E95A5;
}

body {
  background-color: var(--m23-admin-bg) !important;
  color: var(--m23-admin-text) !important;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
  margin: 0;
  padding: 0;
  min-height: 100vh;
  overflow-x: hidden;
}

.ak-admin-auth-wrapper {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
  background-color: var(--m23-admin-bg);
  background-image: 
    radial-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
    linear-gradient(180deg, #0C0D10 0%, #121318 100%);
  background-size: 24px 24px, 100% 100%;
  overflow: hidden;
  box-sizing: border-box;
}

/* Ambient Studio Glows */
.ak-admin-glow {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
  filter: blur(140px);
  z-index: 0;
}

.ak-admin-glow-green,
.ak-admin-glow-gold {
  top: -100px;
  left: -80px;
  width: 580px;
  height: 580px;
  background: radial-gradient(circle, rgba(251, 175, 67, 0.14) 0%, rgba(251, 175, 67, 0) 70%);
}

.ak-admin-glow-secondary {
  bottom: -120px;
  right: -80px;
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(226, 152, 40, 0.10) 0%, rgba(226, 152, 40, 0) 70%);
}

.ak-admin-container {
  position: relative;
  z-index: 1;
  max-width: 1140px;
  width: 100%;
  margin: 0 auto;
}

.ak-admin-grid {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 50px;
  align-items: center;
}

.ak-admin-single-center {
  max-width: 520px;
  margin: 0 auto;
}

/* --------------------------------------------------------------------------
   Left Column: Studio Command Showcase
   -------------------------------------------------------------------------- */
.ak-admin-showcase {
  display: flex;
  flex-direction: column;
  gap: 26px;
  padding-right: 20px;
}

.ak-admin-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(251, 175, 67, 0.12);
  border: 1px solid rgba(251, 175, 67, 0.28);
  color: #FBAF43;
  padding: 6px 14px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  width: fit-content;
}

.ak-admin-badge-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #FBAF43;
  box-shadow: 0 0 8px #FBAF43;
  animation: pulseDot 2s infinite ease-in-out;
}

@keyframes pulseDot {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(0.85); }
}

.ak-admin-hero-title {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 38px;
  font-weight: 800;
  line-height: 1.18;
  color: #FFFFFF;
  letter-spacing: -0.025em;
  margin: 0;
}

.ak-admin-hero-title span {
  background: linear-gradient(135deg, #FFBE5C 0%, #FBAF43 50%, #E29828 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.ak-admin-hero-sub {
  font-size: 15px;
  line-height: 1.6;
  color: var(--m23-admin-text-muted);
  margin: 0;
  max-width: 520px;
}

.ak-admin-features {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.ak-admin-feature-card {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 16px;
  background: rgba(20, 21, 26, 0.65);
  border: 1px solid var(--m23-admin-border);
  border-radius: 12px;
  backdrop-filter: blur(8px);
  transition: all 0.25s ease;
}

.ak-admin-feature-card:hover {
  background: rgba(26, 27, 34, 0.85);
  border-color: rgba(251, 175, 67, 0.3);
  transform: translateY(-2px);
}

.ak-admin-feature-icon {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  background: rgba(251, 175, 67, 0.12);
  border: 1px solid rgba(251, 175, 67, 0.28);
  color: #FBAF43;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
}

.ak-admin-feature-icon.gold {
  background: rgba(251, 175, 67, 0.12);
  border-color: rgba(251, 175, 67, 0.28);
  color: #FBAF43;
}

.ak-admin-feature-icon.blue {
  background: rgba(58, 58, 60, 0.3);
  border-color: rgba(255, 255, 255, 0.12);
  color: #E2E8F0;
}

.ak-admin-feature-content h6 {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 14px;
  font-weight: 700;
  color: #FFFFFF;
  margin: 0 0 4px 0;
}

.ak-admin-feature-content p {
  font-size: 13px;
  line-height: 1.5;
  color: var(--m23-admin-text-muted);
  margin: 0;
}

/* EQ Status Strip */
.ak-admin-status-strip {
  display: flex;
  align-items: center;
  gap: 14px;
  padding-top: 10px;
}

.ak-admin-eq-bars {
  display: inline-flex;
  align-items: flex-end;
  gap: 3px;
  height: 18px;
}

.ak-admin-eq-bar {
  width: 3px;
  background: #FBAF43;
  border-radius: 2px;
  animation: adminEqBounce 1.2s ease-in-out infinite alternate;
}

.ak-admin-eq-bar:nth-child(1) { height: 6px; animation-delay: 0.1s; }
.ak-admin-eq-bar:nth-child(2) { height: 16px; animation-delay: 0.35s; }
.ak-admin-eq-bar:nth-child(3) { height: 10px; animation-delay: 0.2s; }
.ak-admin-eq-bar:nth-child(4) { height: 18px; animation-delay: 0.45s; }
.ak-admin-eq-bar:nth-child(5) { height: 8px; animation-delay: 0.15s; }

@keyframes adminEqBounce {
  0% { transform: scaleY(0.4); opacity: 0.7; }
  100% { transform: scaleY(1); opacity: 1; }
}

.ak-admin-status-text {
  font-size: 12px;
  color: var(--m23-admin-text-muted);
  font-weight: 500;
}

.ak-admin-status-text strong {
  color: #FBAF43;
}

/* --------------------------------------------------------------------------
   Right Column: Admin Authentication Card
   -------------------------------------------------------------------------- */
.ak-admin-card {
  position: relative;
  background: var(--m23-admin-card-bg);
  border: 1px solid var(--m23-admin-border);
  border-radius: 20px;
  padding: 40px 36px;
  box-shadow: 
    0 24px 60px rgba(0, 0, 0, 0.6),
    0 1px 1px rgba(255, 255, 255, 0.05) inset;
  backdrop-filter: blur(16px);
  width: 100%;
  box-sizing: border-box;
}

.ak-admin-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 20%;
  right: 20%;
  height: 1.5px;
  background: linear-gradient(90deg, transparent, rgba(251, 175, 67, 0.6), transparent);
}

.ak-admin-card-header {
  margin-bottom: 28px;
}

.ak-admin-topmark {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 18px;
}

.ak-admin-logo-mark {
  height: 30px;
  display: flex;
  align-items: center;
}

.ak-admin-topmark-label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: #FBAF43;
}

.ak-admin-card-title {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 26px;
  font-weight: 800;
  color: #FFFFFF;
  margin: 0 0 6px 0;
  letter-spacing: -0.02em;
}

.ak-admin-card-sub {
  font-size: 13px;
  color: var(--m23-admin-text-muted);
  margin: 0;
  line-height: 1.5;
}

/* Security Notice Pill */
.ak-admin-security-pill {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  background: rgba(251, 175, 67, 0.08);
  border: 1px solid rgba(251, 175, 67, 0.22);
  border-radius: 8px;
  font-size: 11px;
  color: #FBAF43;
  margin-bottom: 24px;
}

.ak-admin-security-pill i {
  font-size: 14px;
}

/* Form Styles */
.ak-admin-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.ak-admin-form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.ak-admin-label-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.ak-admin-label {
  font-size: 12px;
  font-weight: 600;
  color: #D1D5DB;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  margin: 0;
}

.ak-admin-req-star {
  color: #FBAF43;
  margin-left: 2px;
}

.ak-admin-forgot-link {
  font-size: 12px;
  font-weight: 600;
  color: var(--m23-admin-gold);
  text-decoration: none;
  transition: color 0.2s ease;
}

.ak-admin-forgot-link:hover {
  color: #FFBE5C;
  text-decoration: underline;
}

.ak-admin-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.ak-admin-input-icon {
  position: absolute;
  left: 16px;
  font-size: 18px;
  color: #6B7280;
  pointer-events: none;
  transition: color 0.2s ease;
  z-index: 2;
}

.ak-admin-input {
  width: 100%;
  height: 48px;
  background-color: var(--m23-admin-input-bg) !important;
  border: 1px solid var(--m23-admin-border) !important;
  border-radius: 10px !important;
  color: #FFFFFF !important;
  font-size: 14px !important;
  font-weight: 500 !important;
  padding: 0 44px 0 46px !important;
  outline: none !important;
  transition: all 0.2s ease !important;
  box-sizing: border-box !important;
}

.ak-admin-input:hover {
  border-color: rgba(255, 255, 255, 0.16) !important;
}

.ak-admin-input:focus {
  border-color: #FBAF43 !important;
  box-shadow: 0 0 0 3px rgba(251, 175, 67, 0.22) !important;
  background-color: #121318 !important;
}

.ak-admin-input-wrapper:focus-within .ak-admin-input-icon {
  color: #FBAF43;
}

.ak-admin-input:-webkit-autofill,
.ak-admin-input:-webkit-autofill:hover, 
.ak-admin-input:-webkit-autofill:focus {
  -webkit-text-fill-color: #FFFFFF !important;
  -webkit-box-shadow: 0 0 0 1000px #0E0F12 inset !important;
  transition: background-color 5000s ease-in-out 0s !important;
}

/* Password Toggle */
.ak-admin-pw-toggle {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  color: #6B7280;
  padding: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 6px;
  transition: all 0.2s ease;
  z-index: 3;
}

.ak-admin-pw-toggle:hover {
  color: #FFFFFF;
}

/* Submit Button */
.ak-admin-submit-btn {
  width: 100%;
  height: 50px;
  background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%) !important;
  border: none !important;
  border-radius: 10px !important;
  color: #121316 !important;
  font-family: 'Plus Jakarta Sans', sans-serif !important;
  font-size: 15px !important;
  font-weight: 800 !important;
  letter-spacing: 0.01em !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: 10px !important;
  cursor: pointer !important;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
  box-shadow: 0 8px 24px rgba(251, 175, 67, 0.32) !important;
  text-decoration: none !important;
}

.ak-admin-submit-btn:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 12px 30px rgba(251, 175, 67, 0.45) !important;
  background: linear-gradient(135deg, #FFBE5C 0%, #FBAF43 100%) !important;
  color: #121316 !important;
}

.ak-admin-submit-btn:active {
  transform: translateY(0) !important;
}

.ak-admin-submit-btn i {
  font-size: 18px;
  transition: transform 0.2s ease;
}

.ak-admin-submit-btn:hover i {
  transform: translateX(4px);
}

/* Footer / Back Link */
.ak-admin-card-footer {
  margin-top: 24px;
  padding-top: 20px;
  border-top: 1px solid var(--m23-admin-border);
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-items: center;
}

.ak-admin-back-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #9CA3AF;
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  transition: color 0.2s ease;
}

.ak-admin-back-link:hover {
  color: #FBAF43;
}

/* Responsive queries */
@media (max-width: 991px) {
  .ak-admin-grid {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  
  .ak-admin-showcase {
    padding-right: 0;
    text-align: center;
    align-items: center;
  }

  .ak-admin-hero-sub {
    max-width: 100%;
  }

  .ak-admin-status-strip {
    justify-content: center;
  }
}

@media (max-width: 575px) {
  .ak-admin-card {
    padding: 30px 20px;
    border-radius: 16px;
  }
  
  .ak-admin-hero-title {
    font-size: 28px;
  }
}
</style>
