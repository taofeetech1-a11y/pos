<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StockSense — Inventory Management System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --red: #DC2626;
    --red-dark: #991B1B;
    --red-light: #FEF2F2;
    --red-mid: #FCA5A5;
    --dark: #0A0A0A;
    --dark2: #111827;
    --gray: #6B7280;
    --gray-light: #F3F4F6;
    --gray-mid: #E5E7EB;
    --white: #FFFFFF;
    --text: #111827;
    --muted: #6B7280;
    --success: #16A34A;
    --warning: #F59E0B;
    --info: #3B82F6;
    --font-head: 'Syne', sans-serif;
    --font-body: 'DM Sans', sans-serif;
    --radius: 12px;
    --radius-sm: 8px;
    --shadow: 0 2px 20px rgba(0,0,0,0.06);
    --shadow-card: 0 4px 32px rgba(0,0,0,0.08);
  }

  html { scroll-behavior: smooth; }

  body {
    font-family: var(--font-body);
    color: var(--text);
    background: var(--white);
    line-height: 1.6;
    overflow-x: hidden;
  }

  /* NAV */
  nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--gray-mid);
    padding: 0 5%;
    display: flex; align-items: center; justify-content: space-between;
    height: 64px;
  }

  .nav-logo {
    font-family: var(--font-head);
    font-weight: 800;
    font-size: 22px;
    color: var(--dark);
    text-decoration: none;
    display: flex; align-items: center; gap: 8px;
  }

  .nav-logo span {
    display: inline-block;
    width: 28px; height: 28px;
    background: var(--red);
    border-radius: 6px;
    position: relative;
  }

  .nav-logo span::after {
    content: '';
    position: absolute; inset: 5px;
    border: 2px solid white;
    border-radius: 3px;
  }

  .nav-links {
    display: flex; align-items: center; gap: 32px;
    list-style: none;
  }

  .nav-links a {
    font-size: 14px; font-weight: 400;
    color: var(--muted);
    text-decoration: none;
    transition: color .2s;
  }

  .nav-links a:hover { color: var(--text); }

  .nav-cta {
    display: flex; gap: 10px;
  }

  .btn {
    font-family: var(--font-body);
    font-size: 14px; font-weight: 500;
    padding: 9px 20px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    border: none;
    transition: all .2s;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 6px;
  }

  .btn-primary {
    background: var(--red);
    color: white;
  }
  .btn-primary:hover { background: var(--red-dark); transform: translateY(-1px); }

  .btn-secondary {
    background: transparent;
    color: var(--text);
    border: 1px solid var(--gray-mid);
  }
  .btn-secondary:hover { border-color: var(--gray); background: var(--gray-light); }

  /* HERO */
  .hero {
    padding: 90px 5% 80px;
    max-width: 1280px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }

  .hero-tag {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 500;
    color: var(--red);
    background: var(--red-light);
    border: 1px solid #FECACA;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 20px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }

  .hero-tag::before {
    content: '';
    width: 6px; height: 6px;
    background: var(--red);
    border-radius: 50%;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
  }

  .hero h1 {
    font-family: var(--font-head);
    font-size: clamp(36px, 5vw, 56px);
    font-weight: 800;
    line-height: 1.1;
    color: var(--dark);
    margin-bottom: 20px;
  }

  .hero h1 em {
    font-style: normal;
    color: var(--red);
  }

  .hero p {
    font-size: 17px;
    color: var(--muted);
    line-height: 1.7;
    margin-bottom: 32px;
    max-width: 480px;
  }

  .hero-btns {
    display: flex; gap: 12px; flex-wrap: wrap;
    margin-bottom: 40px;
  }

  .btn-large {
    font-size: 15px;
    padding: 13px 28px;
    border-radius: 10px;
  }

  .hero-badges {
    display: flex; flex-wrap: wrap; gap: 8px;
  }

  .badge {
    font-size: 12px; font-weight: 400;
    color: var(--muted);
    background: var(--gray-light);
    border: 1px solid var(--gray-mid);
    padding: 5px 12px;
    border-radius: 20px;
    display: flex; align-items: center; gap: 5px;
  }

  .badge::before {
    content: '✓';
    color: var(--success);
    font-weight: 700;
  }

  /* DASHBOARD MOCKUP */
  .hero-visual {
    position: relative;
  }

  .dashboard-frame {
    background: var(--dark2);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.1);
    position: relative;
  }

  .dash-topbar {
    background: #1F2937;
    padding: 10px 16px;
    display: flex; align-items: center; gap: 6px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }

  .dash-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
  }

  .dash-dot:nth-child(1) { background: #EF4444; }
  .dash-dot:nth-child(2) { background: #F59E0B; }
  .dash-dot:nth-child(3) { background: #22C55E; }

  .dash-title {
    font-size: 12px; color: #9CA3AF;
    margin-left: 8px; font-family: var(--font-body);
  }

  .dash-body {
    padding: 16px;
    display: flex; gap: 12px;
  }

  .dash-sidebar {
    width: 44px;
    background: #1F2937;
    border-radius: 8px;
    padding: 12px 0;
    display: flex; flex-direction: column; align-items: center; gap: 16px;
  }

  .dash-sidebar-icon {
    width: 28px; height: 28px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
  }

  .dash-sidebar-icon.active { background: var(--red); }

  .dash-sidebar-icon svg { width: 14px; height: 14px; }

  .dash-main { flex: 1; }

  .dash-kpis {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;
    margin-bottom: 10px;
  }

  .kpi-card {
    background: #1F2937;
    border-radius: 8px;
    padding: 10px;
  }

  .kpi-label { font-size: 9px; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; }
  .kpi-value { font-size: 16px; font-weight: 700; color: white; font-family: var(--font-head); }
  .kpi-trend { font-size: 9px; margin-top: 2px; }
  .kpi-up { color: #4ADE80; }
  .kpi-down { color: #F87171; }

  .dash-charts {
    display: grid; grid-template-columns: 2fr 1fr; gap: 8px;
    margin-bottom: 10px;
  }

  .chart-card {
    background: #1F2937;
    border-radius: 8px;
    padding: 10px;
  }

  .chart-label { font-size: 9px; color: #9CA3AF; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }

  .mini-bars {
    display: flex; align-items: flex-end; gap: 4px; height: 48px;
  }

  .mini-bar {
    flex: 1; border-radius: 3px 3px 0 0;
    background: var(--red); opacity: 0.7;
    transition: opacity .2s;
  }

  .mini-bar:nth-child(odd) { opacity: 0.4; }
  .mini-bar.highlight { opacity: 1; background: var(--red); }

  .mini-donut {
    width: 60px; height: 60px;
    margin: 0 auto;
  }

  .dash-table {
    background: #1F2937;
    border-radius: 8px;
    overflow: hidden;
  }

  .dt-header {
    display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
    padding: 6px 10px;
    font-size: 8px; color: #6B7280;
    text-transform: uppercase; letter-spacing: 0.06em;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }

  .dt-row {
    display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
    padding: 6px 10px;
    font-size: 9px; color: #D1D5DB;
    border-bottom: 1px solid rgba(255,255,255,0.03);
    align-items: center;
  }

  .dt-row:last-child { border-bottom: none; }

  .stock-ok { color: #4ADE80; font-size: 8px; }
  .stock-low { color: #FBBF24; font-size: 8px; }
  .stock-out { color: #F87171; font-size: 8px; }

  /* STATS BANNER */
  .stats-banner {
    background: var(--dark2);
    padding: 40px 5%;
  }

  .stats-inner {
    max-width: 1280px; margin: 0 auto;
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 1px;
    background: rgba(255,255,255,0.1);
    border-radius: var(--radius);
    overflow: hidden;
  }

  .stat-item {
    background: var(--dark2);
    padding: 32px 24px;
    text-align: center;
  }

  .stat-num {
    font-family: var(--font-head);
    font-size: 40px; font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 6px;
  }

  .stat-num span { color: var(--red); }

  .stat-label {
    font-size: 13px; color: #9CA3AF;
  }

  /* FEATURES */
  .section {
    padding: 80px 5%;
    max-width: 1280px;
    margin: 0 auto;
  }

  .section-tag {
    font-size: 12px; font-weight: 500;
    color: var(--red);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 12px;
  }

  .section-heading {
    font-family: var(--font-head);
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 700;
    color: var(--dark);
    line-height: 1.15;
    margin-bottom: 16px;
  }

  .section-sub {
    font-size: 16px;
    color: var(--muted);
    max-width: 560px;
    line-height: 1.7;
    margin-bottom: 52px;
  }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
  }

  .feature-card {
    background: var(--white);
    border: 1px solid var(--gray-mid);
    border-radius: var(--radius);
    padding: 28px;
    transition: all .25s;
    position: relative;
    overflow: hidden;
  }

  .feature-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
    background: var(--red);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .3s;
  }

  .feature-card:hover {
    border-color: #FCA5A5;
    box-shadow: var(--shadow-card);
    transform: translateY(-3px);
  }

  .feature-card:hover::before { transform: scaleX(1); }

  .feature-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    background: var(--red-light);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 16px;
  }

  .feature-icon svg { width: 22px; height: 22px; color: var(--red); }

  .feature-card h3 {
    font-family: var(--font-head);
    font-size: 17px; font-weight: 600;
    color: var(--dark);
    margin-bottom: 10px;
  }

  .feature-card p {
    font-size: 14px; color: var(--muted); line-height: 1.65;
  }

  /* PROBLEM / SOLUTION */
  .problem-section {
    background: var(--gray-light);
    padding: 80px 5%;
  }

  .problem-inner {
    max-width: 1280px; margin: 0 auto;
  }

  .ps-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
    margin-top: 48px;
  }

  .ps-col h4 {
    font-family: var(--font-head);
    font-size: 14px; font-weight: 600;
    letter-spacing: 0.06em; text-transform: uppercase;
    margin-bottom: 16px;
    padding: 8px 16px;
    border-radius: var(--radius-sm);
  }

  .ps-col.problems h4 { color: #991B1B; background: #FEE2E2; }
  .ps-col.solutions h4 { color: #166534; background: #DCFCE7; }

  .ps-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px;
    border-radius: var(--radius-sm);
    margin-bottom: 8px;
    background: white;
    border: 1px solid var(--gray-mid);
    font-size: 14px;
    color: var(--text);
  }

  .ps-icon {
    width: 20px; height: 20px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
    font-size: 11px; font-weight: 700;
  }

  .ps-icon.x { background: #FEE2E2; color: #EF4444; }
  .ps-icon.check { background: #DCFCE7; color: #16A34A; }

  /* HOW IT WORKS */
  .steps-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 0;
    position: relative;
  }

  .steps-grid::before {
    content: '';
    position: absolute; top: 28px; left: 10%; right: 10%;
    height: 2px;
    background: repeating-linear-gradient(90deg, var(--red) 0, var(--red) 8px, transparent 8px, transparent 16px);
    z-index: 0;
  }

  .step-item {
    text-align: center; padding: 0 20px;
    position: relative; z-index: 1;
  }

  .step-num {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: var(--red);
    color: white;
    font-family: var(--font-head);
    font-size: 20px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    border: 4px solid white;
    box-shadow: 0 0 0 2px var(--red);
  }

  .step-item h3 {
    font-family: var(--font-head);
    font-size: 16px; font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
  }

  .step-item p {
    font-size: 13px; color: var(--muted); line-height: 1.6;
  }

  /* TECH STACK */
  .tech-section {
    background: var(--dark2); padding: 80px 5%;
  }

  .tech-inner { max-width: 1280px; margin: 0 auto; }

  .tech-section .section-heading { color: white; }
  .tech-section .section-tag { color: #F87171; }
  .tech-section .section-sub { color: #9CA3AF; }

  .tech-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
  }

  .tech-group {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: var(--radius);
    padding: 24px;
  }

  .tech-group h4 {
    font-size: 11px; font-weight: 500;
    color: #6B7280; text-transform: uppercase; letter-spacing: 0.08em;
    margin-bottom: 16px;
  }

  .tech-pill {
    display: inline-block;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    color: #D1D5DB;
    font-size: 12px; font-weight: 400;
    padding: 5px 10px;
    border-radius: 6px;
    margin: 3px 3px 3px 0;
  }

  /* SECURITY */
  .security-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
  }

  .security-item {
    padding: 24px;
    border: 1px solid var(--gray-mid);
    border-radius: var(--radius);
    background: white;
  }

  .security-item svg {
    width: 28px; height: 28px;
    color: var(--red);
    margin-bottom: 12px;
  }

  .security-item h3 {
    font-family: var(--font-head);
    font-size: 14px; font-weight: 600;
    color: var(--dark); margin-bottom: 6px;
  }

  .security-item p { font-size: 13px; color: var(--muted); }

  /* TESTIMONIALS */
  .testi-section { background: var(--gray-light); padding: 80px 5%; }
  .testi-inner { max-width: 1280px; margin: 0 auto; }

  .testi-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
    margin-top: 48px;
  }

  .testi-card {
    background: white;
    border: 1px solid var(--gray-mid);
    border-radius: var(--radius);
    padding: 28px;
  }

  .testi-stars {
    color: var(--warning);
    font-size: 14px;
    margin-bottom: 14px;
    letter-spacing: 2px;
  }

  .testi-text {
    font-size: 15px;
    color: var(--text);
    line-height: 1.7;
    margin-bottom: 20px;
    font-style: italic;
  }

  .testi-author {
    display: flex; align-items: center; gap: 10px;
  }

  .testi-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-head);
    font-size: 14px; font-weight: 700;
    color: white;
  }

  .testi-info p:first-child { font-size: 14px; font-weight: 500; color: var(--dark); }
  .testi-info p:last-child { font-size: 12px; color: var(--muted); }

  /* PRICING */
  .pricing-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
    margin-top: 48px;
  }

  .pricing-card {
    border: 1px solid var(--gray-mid);
    border-radius: var(--radius);
    padding: 32px;
    position: relative;
    background: white;
  }

  .pricing-card.featured {
    border-color: var(--red);
    border-width: 2px;
  }

  .plan-badge {
    position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
    background: var(--red); color: white;
    font-size: 11px; font-weight: 500;
    padding: 4px 14px; border-radius: 20px;
    white-space: nowrap;
    letter-spacing: 0.04em;
  }

  .plan-name {
    font-family: var(--font-head);
    font-size: 20px; font-weight: 700;
    color: var(--dark); margin-bottom: 8px;
  }

  .plan-desc { font-size: 13px; color: var(--muted); margin-bottom: 24px; }

  .plan-price {
    font-family: var(--font-head);
    font-size: 40px; font-weight: 800;
    color: var(--dark);
    line-height: 1;
    margin-bottom: 4px;
  }

  .plan-price sup { font-size: 20px; font-weight: 500; }
  .plan-period { font-size: 13px; color: var(--muted); margin-bottom: 28px; }

  .plan-features { list-style: none; margin-bottom: 28px; }

  .plan-features li {
    font-size: 13px; color: var(--text);
    padding: 7px 0;
    border-bottom: 1px solid var(--gray-mid);
    display: flex; align-items: center; gap: 8px;
  }

  .plan-features li:last-child { border-bottom: none; }
  .plan-features li::before { content: '✓'; color: var(--success); font-weight: 700; font-size: 12px; }

  /* FINAL CTA */
  .cta-section {
    background: var(--red);
    padding: 80px 5%;
    text-align: center;
  }

  .cta-section h2 {
    font-family: var(--font-head);
    font-size: clamp(28px, 4vw, 48px);
    font-weight: 800;
    color: white;
    margin-bottom: 16px;
    line-height: 1.15;
  }

  .cta-section p {
    font-size: 17px; color: rgba(255,255,255,0.8);
    margin-bottom: 36px; max-width: 520px; margin-left: auto; margin-right: auto;
  }

  .cta-btns { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }

  .btn-white {
    background: white; color: var(--red);
    font-weight: 600;
  }
  .btn-white:hover { background: var(--red-light); }

  .btn-outline-white {
    background: transparent;
    color: white;
    border: 1px solid rgba(255,255,255,0.5);
  }
  .btn-outline-white:hover { background: rgba(255,255,255,0.1); border-color: white; }

  /* FOOTER */
  footer {
    background: var(--dark); padding: 60px 5% 30px;
  }

  .footer-grid {
    max-width: 1280px; margin: 0 auto;
    display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr; gap: 48px;
    margin-bottom: 48px;
  }

  .footer-brand h3 {
    font-family: var(--font-head);
    font-size: 20px; font-weight: 800;
    color: white; margin-bottom: 12px;
    display: flex; align-items: center; gap: 8px;
  }

  .footer-brand p { font-size: 13px; color: #6B7280; line-height: 1.7; }

  .footer-col h4 {
    font-size: 12px; font-weight: 500;
    color: #9CA3AF; text-transform: uppercase;
    letter-spacing: 0.08em; margin-bottom: 16px;
  }

  .footer-col ul { list-style: none; }
  .footer-col li { margin-bottom: 8px; }
  .footer-col a { font-size: 13px; color: #6B7280; text-decoration: none; transition: color .2s; }
  .footer-col a:hover { color: white; }

  .footer-bottom {
    max-width: 1280px; margin: 0 auto;
    border-top: 1px solid rgba(255,255,255,0.06);
    padding-top: 24px;
    display: flex; justify-content: space-between; align-items: center;
  }

  .footer-bottom p { font-size: 12px; color: #4B5563; }

  /* RESPONSIVE */
  @media (max-width: 1024px) {
    .hero { grid-template-columns: 1fr; }
    .hero-visual { display: none; }
    .features-grid { grid-template-columns: 1fr 1fr; }
    .steps-grid { grid-template-columns: 1fr 1fr; }
    .steps-grid::before { display: none; }
    .tech-grid { grid-template-columns: 1fr 1fr; }
    .security-grid { grid-template-columns: 1fr 1fr; }
    .footer-grid { grid-template-columns: 1fr 1fr; }
  }

  @media (max-width: 640px) {
    nav { padding: 0 4%; }
    .nav-links { display: none; }
    .features-grid { grid-template-columns: 1fr; }
    .ps-grid { grid-template-columns: 1fr; }
    .pricing-grid { grid-template-columns: 1fr; }
    .testi-grid { grid-template-columns: 1fr; }
    .stats-inner { grid-template-columns: 1fr 1fr; }
    .footer-grid { grid-template-columns: 1fr; }
    .security-grid { grid-template-columns: 1fr; }
    .tech-grid { grid-template-columns: 1fr; }
    .steps-grid { grid-template-columns: 1fr; }
    .dash-kpis { grid-template-columns: 1fr 1fr; }
    .dash-charts { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="#" class="nav-logo">
    <span></span>
    StockSense
  </a>
  <ul class="nav-links">
    <li><a href="#features">Features</a></li>
    <li><a href="#how-it-works">How It Works</a></li>
    <li><a href="#tech">Tech Stack</a></li>
    <li><a href="#pricing">Pricing</a></li>
  </ul>
  <div class="nav-cta">
    <a href="#" class="btn btn-secondary">Sign In</a>
    <a href="#" class="btn btn-primary">Get Started</a>
  </div>
</nav>

<!-- HERO -->
<section style="background: linear-gradient(135deg, #fff 0%, #FEF2F2 50%, #fff 100%); padding-bottom: 0;">
<div class="hero">
  <div class="hero-content">
    <div class="hero-tag">Laravel 12 Powered</div>
    <h1>Manage Inventory <em>Smarter,</em> Faster &amp; Better</h1>
    <p>A complete Inventory Management System for modern businesses — track products, manage suppliers, process sales, monitor stock, and generate powerful analytics from one unified dashboard.</p>
    <div class="hero-btns">
      <a href="#" class="btn btn-primary btn-large">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        Get Started Free
      </a>
      <a href="#" class="btn btn-secondary btn-large">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
        Watch Demo
      </a>
    </div>
    <div class="hero-badges">
      <span class="badge">Real-time tracking</span>
      <span class="badge">Role management</span>
      <span class="badge">Sales analytics</span>
      <span class="badge">Supplier management</span>
      <span class="badge">Low-stock alerts</span>
    </div>
  </div>

  <!-- DASHBOARD VISUAL -->
  <div class="hero-visual">
    <div class="dashboard-frame">
      <div class="dash-topbar">
        <div class="dash-dot"></div>
        <div class="dash-dot"></div>
        <div class="dash-dot"></div>
        <span class="dash-title">StockSense — Admin Dashboard</span>
      </div>
      <div class="dash-body">
        <div class="dash-sidebar">
          <div class="dash-sidebar-icon active">
            <svg fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
          </div>
          <div class="dash-sidebar-icon">
            <svg fill="none" stroke="#6B7280" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
          </div>
          <div class="dash-sidebar-icon">
            <svg fill="none" stroke="#6B7280" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          </div>
          <div class="dash-sidebar-icon">
            <svg fill="none" stroke="#6B7280" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
          </div>
          <div class="dash-sidebar-icon">
            <svg fill="none" stroke="#6B7280" stroke-width="2" viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
          </div>
        </div>
        <div class="dash-main">
          <div class="dash-kpis">
            <div class="kpi-card">
              <div class="kpi-label">Revenue</div>
              <div class="kpi-value">₦4.2M</div>
              <div class="kpi-trend kpi-up">↑ 12.4%</div>
            </div>
            <div class="kpi-card">
              <div class="kpi-label">Orders</div>
              <div class="kpi-value">1,284</div>
              <div class="kpi-trend kpi-up">↑ 8.1%</div>
            </div>
            <div class="kpi-card">
              <div class="kpi-label">Products</div>
              <div class="kpi-value">386</div>
              <div class="kpi-trend kpi-down">▼ 2 low</div>
            </div>
            <div class="kpi-card">
              <div class="kpi-label">Suppliers</div>
              <div class="kpi-value">47</div>
              <div class="kpi-trend kpi-up">Active</div>
            </div>
          </div>
          <div class="dash-charts">
            <div class="chart-card">
              <div class="chart-label">Monthly Sales</div>
              <div class="mini-bars">
                <div class="mini-bar" style="height:30%"></div>
                <div class="mini-bar" style="height:50%"></div>
                <div class="mini-bar" style="height:40%"></div>
                <div class="mini-bar" style="height:70%"></div>
                <div class="mini-bar" style="height:55%"></div>
                <div class="mini-bar highlight" style="height:90%"></div>
                <div class="mini-bar" style="height:60%"></div>
                <div class="mini-bar" style="height:75%"></div>
              </div>
            </div>
            <div class="chart-card">
              <div class="chart-label">By Brand</div>
              <svg class="mini-donut" viewBox="0 0 80 80">
                <circle cx="40" cy="40" r="28" fill="none" stroke="#374151" stroke-width="14"/>
                <circle cx="40" cy="40" r="28" fill="none" stroke="#DC2626" stroke-width="14" stroke-dasharray="70 106" stroke-dashoffset="0"/>
                <circle cx="40" cy="40" r="28" fill="none" stroke="#F59E0B" stroke-width="14" stroke-dasharray="30 146" stroke-dashoffset="-70"/>
                <circle cx="40" cy="40" r="28" fill="none" stroke="#3B82F6" stroke-width="14" stroke-dasharray="20 156" stroke-dashoffset="-100"/>
                <text x="40" y="44" text-anchor="middle" fill="white" font-size="11" font-weight="bold" font-family="sans-serif">3 Brands</text>
              </svg>
            </div>
          </div>
          <div class="dash-table">
            <div class="dt-header">
              <div>Product</div>
              <div>Stock</div>
              <div>Price</div>
              <div>Status</div>
            </div>
            <div class="dt-row">
              <div>Samsung TV 43"</div>
              <div>124</div>
              <div>₦280k</div>
              <div class="stock-ok">● Good</div>
            </div>
            <div class="dt-row">
              <div>iPhone 15 Pro</div>
              <div>8</div>
              <div>₦950k</div>
              <div class="stock-low">● Low</div>
            </div>
            <div class="dt-row">
              <div>Hisense Fridge</div>
              <div>0</div>
              <div>₦320k</div>
              <div class="stock-out">● Out</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

<!-- STATS BANNER -->
<div class="stats-banner">
  <div class="stats-inner">
    <div class="stat-item">
      <div class="stat-num">50<span>K+</span></div>
      <div class="stat-label">Products Tracked</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">99<span>%</span></div>
      <div class="stat-label">Uptime Reliability</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">4</div>
      <div class="stat-label">User Role Levels</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">∞</div>
      <div class="stat-label">Scalable Operations</div>
    </div>
  </div>
</div>

<!-- FEATURES -->
<section id="features">
<div class="section">
  <div class="section-tag">Core Features</div>
  <h2 class="section-heading">Everything You Need To Run<br>Inventory Operations Efficiently</h2>
  <p class="section-sub">One platform, zero compromises. Manage products, track sales, coordinate suppliers, and act on real-time analytics — all without switching tabs.</p>

  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
      </div>
      <h3>Product Management</h3>
      <p>Track products, quantities, prices, categories, brands, discounts, and stock alerts in real time. Full CRUD with image support.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      </div>
      <h3>Sales Processing</h3>
      <p>Create fast, accurate sales transactions with automatic calculations, receipts, and full payment tracking for every order.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      </div>
      <h3>Supplier Management</h3>
      <p>Manage suppliers, purchase orders, invoices, supply history, and procurement workflows from one centralised view.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      </div>
      <h3>Analytics Dashboard</h3>
      <p>Visualize sales trends, brand performance, profits, and inventory insights — all surfaced in one data-rich dashboard.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
      </div>
      <h3>Role-Based Access</h3>
      <p>Assign secure access for Super Admins, Store Managers, Admins, and Cashiers — each with tailored permissions via Spatie.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
      </div>
      <h3>Inventory Alerts</h3>
      <p>Receive automatic low-stock alerts before products run out — preventing operational downtime and lost sales.</p>
    </div>
  </div>
</div>
</section>

<!-- PROBLEM / SOLUTION -->
<div class="problem-section" id="why">
<div class="problem-inner">
  <div class="section-tag">Why It Matters</div>
  <h2 class="section-heading">Stop Losing Money From<br>Poor Inventory Management</h2>
  <div class="ps-grid">
    <div class="ps-col problems">
      <h4>Common Problems</h4>
      <div class="ps-item"><div class="ps-icon x">✕</div> Manual stock tracking leads to errors and costly discrepancies</div>
      <div class="ps-item"><div class="ps-icon x">✕</div> Missing or incomplete sales records reduce revenue visibility</div>
      <div class="ps-item"><div class="ps-icon x">✕</div> Poor supplier coordination causes delays and procurement issues</div>
      <div class="ps-item"><div class="ps-icon x">✕</div> No analytics means flying blind on business performance</div>
      <div class="ps-item"><div class="ps-icon x">✕</div> Unauthorized access creates security and compliance risks</div>
      <div class="ps-item"><div class="ps-icon x">✕</div> Stock shortages cause operational downtime and lost customers</div>
    </div>
    <div class="ps-col solutions">
      <h4>StockSense Solutions</h4>
      <div class="ps-item"><div class="ps-icon check">✓</div> Automated inventory updates with real-time sync across all users</div>
      <div class="ps-item"><div class="ps-icon check">✓</div> Real-time sales management with complete transaction history</div>
      <div class="ps-item"><div class="ps-icon check">✓</div> Centralised supplier management with procurement workflows</div>
      <div class="ps-item"><div class="ps-icon check">✓</div> Smart business insights dashboard with trend visualisation</div>
      <div class="ps-item"><div class="ps-icon check">✓</div> Role-based permissions per user level — built on Spatie</div>
      <div class="ps-item"><div class="ps-icon check">✓</div> Proactive low-stock alerts before shelves run empty</div>
    </div>
  </div>
</div>
</div>

<!-- HOW IT WORKS -->
<section id="how-it-works">
<div class="section">
  <div class="section-tag">Simple Process</div>
  <h2 class="section-heading">Simple Workflow,<br>Powerful Results</h2>
  <p class="section-sub">Four steps from setup to full operational control — no complex configuration required.</p>

  <div class="steps-grid">
    <div class="step-item">
      <div class="step-num">1</div>
      <h3>Add Products</h3>
      <p>Create products, assign categories and brands, set pricing and opening stock quantities.</p>
    </div>
    <div class="step-item">
      <div class="step-num">2</div>
      <h3>Manage Suppliers</h3>
      <p>Record supplier details and track product supply history and procurement operations.</p>
    </div>
    <div class="step-item">
      <div class="step-num">3</div>
      <h3>Process Sales</h3>
      <p>Cashiers create transactions with automatic calculations, receipt generation, and payment tracking.</p>
    </div>
    <div class="step-item">
      <div class="step-num">4</div>
      <h3>Monitor Analytics</h3>
      <p>Track sales, profits, stock levels, and business performance in real time from your dashboard.</p>
    </div>
  </div>
</div>
</section>

<!-- TECH STACK -->
<div class="tech-section" id="tech">
<div class="tech-inner">
  <div class="section-tag">Technology</div>
  <h2 class="section-heading">Built With Modern Technologies</h2>
  <p class="section-sub" style="margin-bottom: 48px;">A production-ready stack trusted by the Laravel community — performant, secure, and developer-friendly.</p>
  <div class="tech-grid">
    <div class="tech-group">
      <h4>Backend</h4>
      <span class="tech-pill">Laravel 12</span>
      <span class="tech-pill">PHP 8.2+</span>
      <span class="tech-pill">Eloquent ORM</span>
      <span class="tech-pill">Spatie Permissions</span>
      <span class="tech-pill">Laravel Breeze</span>
    </div>
    <div class="tech-group">
      <h4>Frontend</h4>
      <span class="tech-pill">Tailwind CSS</span>
      <span class="tech-pill">Alpine.js</span>
      <span class="tech-pill">Vite</span>
      <span class="tech-pill">Axios</span>
      <span class="tech-pill">Blade Templates</span>
    </div>
    <div class="tech-group">
      <h4>Dev Tools</h4>
      <span class="tech-pill">Laravel Pint</span>
      <span class="tech-pill">Pest PHP</span>
      <span class="tech-pill">Intervention Image</span>
      <span class="tech-pill">Artisan CLI</span>
    </div>
    <div class="tech-group">
      <h4>Database & Auth</h4>
      <span class="tech-pill">MySQL</span>
      <span class="tech-pill">Migrations</span>
      <span class="tech-pill">Seeders</span>
      <span class="tech-pill">CSRF Protection</span>
      <span class="tech-pill">Hashed Passwords</span>
    </div>
  </div>
</div>
</div>

<!-- SECURITY -->
<section>
<div class="section">
  <div class="section-tag">Security</div>
  <h2 class="section-heading">Secure, Reliable &amp; Scalable</h2>
  <p class="section-sub">Built with security at every layer — not bolted on as an afterthought.</p>
  <div class="security-grid">
    <div class="security-item">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <h3>Secure Authentication</h3>
      <p>Laravel Breeze-powered auth with hashed passwords and session management.</p>
    </div>
    <div class="security-item">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
      <h3>Role-Based Authorization</h3>
      <p>Granular permission control across Super Admin, Manager, Admin, and Cashier roles.</p>
    </div>
    <div class="security-item">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      <h3>SQL Injection Prevention</h3>
      <p>Eloquent ORM bindings and prepared statements protect all database operations.</p>
    </div>
    <div class="security-item">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <h3>CSRF Protection</h3>
      <p>All forms protected against cross-site request forgery attacks out of the box.</p>
    </div>
  </div>
</div>
</section>

<!-- TESTIMONIALS -->
<div class="testi-section">
<div class="testi-inner">
  <div class="section-tag">Testimonials</div>
  <h2 class="section-heading">What Businesses Say</h2>
  <div class="testi-grid">
    <div class="testi-card">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-text">"This system completely transformed how we track inventory and manage daily sales. The analytics dashboard alone paid for itself in the first month."</p>
      <div class="testi-author">
        <div class="testi-avatar" style="background: #DC2626;">AO</div>
        <div class="testi-info">
          <p>Adebayo Okonkwo</p>
          <p>Retail Store Owner, Lagos</p>
        </div>
      </div>
    </div>
    <div class="testi-card">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-text">"The supplier and stock management tools helped us reduce operational errors by over 60%. Role-based access means everyone only sees what they should."</p>
      <div class="testi-author">
        <div class="testi-avatar" style="background: #1D4ED8;">FM</div>
        <div class="testi-info">
          <p>Fatimah Musa</p>
          <p>Warehouse Manager, Abuja</p>
        </div>
      </div>
    </div>
    <div class="testi-card">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-text">"The analytics dashboard gives us better visibility into profits and product performance than anything we've used before. Low-stock alerts are a lifesaver."</p>
      <div class="testi-author">
        <div class="testi-avatar" style="background: #16A34A;">CE</div>
        <div class="testi-info">
          <p>Chidera Eze</p>
          <p>Business Administrator, Port Harcourt</p>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- PRICING -->
<section id="pricing">
<div class="section">
  <div class="section-tag">Pricing</div>
  <h2 class="section-heading">Flexible Pricing For Every Business</h2>
  <p class="section-sub">Start free. Scale when you're ready. No hidden fees, no long-term contracts.</p>

  <div class="pricing-grid">
    <div class="pricing-card">
      <div class="plan-name">Starter</div>
      <p class="plan-desc">Perfect for small businesses getting started with inventory tracking.</p>
      <div class="plan-price"><sup>₦</sup>0</div>
      <p class="plan-period">Free forever</p>
      <ul class="plan-features">
        <li>Up to 100 products</li>
        <li>Basic analytics</li>
        <li>Product management</li>
        <li>1 admin user</li>
        <li>Low-stock alerts</li>
      </ul>
      <a href="#" class="btn btn-secondary" style="width:100%; justify-content:center; font-size:14px; padding:11px;">Get Started Free</a>
    </div>

    <div class="pricing-card featured">
      <div class="plan-badge">Most Popular</div>
      <div class="plan-name">Professional</div>
      <p class="plan-desc">Everything a growing business needs to manage operations at scale.</p>
      <div class="plan-price"><sup>₦</sup>25<span style="font-size:20px">k</span></div>
      <p class="plan-period">per month</p>
      <ul class="plan-features">
        <li>Unlimited products</li>
        <li>Advanced reporting</li>
        <li>Supplier management</li>
        <li>Multi-role access (4 roles)</li>
        <li>Sales processing</li>
        <li>Priority support</li>
      </ul>
      <a href="#" class="btn btn-primary" style="width:100%; justify-content:center; font-size:14px; padding:11px;">Start Free Trial</a>
    </div>

    <div class="pricing-card">
      <div class="plan-name">Enterprise</div>
      <p class="plan-desc">Full customisation, dedicated support, and scalable deployment for large teams.</p>
      <div class="plan-price" style="font-size:28px; line-height:1.3;">Custom</div>
      <p class="plan-period">tailored to your needs</p>
      <ul class="plan-features">
        <li>Full system customisation</li>
        <li>Dedicated account manager</li>
        <li>SLA-backed uptime</li>
        <li>On-premise deployment</li>
        <li>API access</li>
        <li>White-label option</li>
      </ul>
      <a href="#" class="btn btn-secondary" style="width:100%; justify-content:center; font-size:14px; padding:11px;">Contact Sales</a>
    </div>
  </div>
</div>
</section>

<!-- FINAL CTA -->
<div class="cta-section">
  <h2>Ready To Simplify Your<br>Inventory Management?</h2>
  <p>Start managing inventory, suppliers, and sales from one powerful platform. Setup takes minutes.</p>
  <div class="cta-btns">
    <a href="#" class="btn btn-white btn-large">Start Free Trial</a>
    <a href="#" class="btn btn-outline-white btn-large">Book a Demo</a>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <h3>
        <span style="display:inline-block;width:22px;height:22px;background:var(--red);border-radius:4px;"></span>
        StockSense
      </h3>
      <p>A modern Inventory Management System built with Laravel 12 for product tracking, supplier management, sales processing, and business analytics.</p>
    </div>
    <div class="footer-col">
      <h4>Product</h4>
      <ul>
        <li><a href="#">Features</a></li>
        <li><a href="#">Pricing</a></li>
        <li><a href="#">Changelog</a></li>
        <li><a href="#">Roadmap</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Docs</h4>
      <ul>
        <li><a href="#">Documentation</a></li>
        <li><a href="#">API Reference</a></li>
        <li><a href="#">Guides</a></li>
        <li><a href="#">Support</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Company</h4>
      <ul>
        <li><a href="#">About</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="#">Careers</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Legal</h4>
      <ul>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Cookie Policy</a></li>
        <li><a href="#">Security</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 <a href="https://wealthtechservice.com" target="_blank">wealthtechservice</a>. Built with Laravel 12. All rights reserved.</p>
    <p style="color:#374151; font-size:12px;">Made with ♥ for modern businesses</p>
  </div>
</footer>

</body>
</html>