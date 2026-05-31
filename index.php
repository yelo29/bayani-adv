<!DOCTYPE html>
<html lang="tl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gawang Pinas — Proud na Filipino na Produkto</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  /* ─── TOKENS ───────────────────────────────────────────── */
  :root {
    --cream:    #F5F0E8;
    --parchment:#EDE4D3;
    --tan:      #C9A97A;
    --sienna:   #A0623A;
    --bark:     #5C3D2E;
    --charcoal: #2B1F17;
    --white:    #FDFAF5;
    --sage:     #7A8C6E;
    --rust:     #C4522A;
    --gold:     #C8963E;

    --font-display: 'Playfair Display', Georgia, serif;
    --font-body:    'DM Sans', sans-serif;
    --ease-out: cubic-bezier(0.22, 1, 0.36, 1);
    --radius:   12px;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  html { scroll-behavior: smooth; }

  body {
    background: var(--cream);
    color: var(--charcoal);
    font-family: var(--font-body);
    font-size: 16px;
    line-height: 1.7;
    overflow-x: hidden;
  }

  /* ─── NOISE TEXTURE OVERLAY ────────────────────────────── */
  body::before {
    content: '';
    position: fixed; inset: 0; z-index: 0;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.035'/%3E%3C/svg%3E");
    opacity: 0.4;
  }

  * { position: relative; z-index: 1; }

  /* ─── UTILITY ───────────────────────────────────────────── */
  .container { max-width: 1120px; margin: 0 auto; padding: 0 32px; }
  .tag {
    display: inline-block;
    font-family: var(--font-body);
    font-size: 11px; font-weight: 500;
    letter-spacing: 1.5px; text-transform: uppercase;
    padding: 4px 12px; border-radius: 20px;
    background: var(--parchment); color: var(--sienna);
    border: 1px solid var(--tan);
  }
  .tag.home    { background: #EEF1EB; color: var(--sage); border-color: var(--sage); }
  .tag.fashion { background: #F4EBE4; color: var(--sienna); border-color: var(--sienna); }
  .tag.food    { background: #FBF3E4; color: var(--gold); border-color: var(--gold); }
  .tag.crafts  { background: #F0EAE3; color: var(--bark); border-color: var(--bark); }

  /* ─── NAVIGATION ────────────────────────────────────────── */
  nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(245, 240, 232, 0.9);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--parchment);
    padding: 0 32px;
  }
  .nav-inner {
    max-width: 1120px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between;
    height: 68px;
  }
  .nav-logo {
    font-family: var(--font-display);
    font-size: 22px; font-weight: 600;
    color: var(--bark); text-decoration: none;
    letter-spacing: -0.3px;
  }
  .nav-logo span { color: var(--rust); font-style: italic; }
  .nav-links { display: flex; gap: 8px; list-style: none; }
  .nav-links a {
    font-size: 14px; font-weight: 400;
    color: var(--charcoal); text-decoration: none;
    padding: 6px 14px; border-radius: 8px;
    transition: background 0.18s, color 0.18s;
  }
  .nav-links a:hover,
  .nav-links a.active { background: var(--parchment); color: var(--bark); font-weight: 500; }
  .nav-cta {
    font-size: 13px; font-weight: 500;
    padding: 8px 20px; border-radius: 8px;
    background: var(--bark); color: var(--cream);
    border: none; cursor: pointer;
    transition: background 0.18s, transform 0.15s;
    text-decoration: none;
  }
  .nav-cta:hover { background: var(--sienna); transform: translateY(-1px); }

  /* hamburger */
  .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 4px; }
  .hamburger span { width: 22px; height: 2px; background: var(--charcoal); border-radius: 2px; transition: all 0.25s; }

  /* ─── HERO ───────────────────────────────────────────────── */
  .hero {
    min-height: 88vh;
    display: flex; align-items: center;
    padding: 80px 32px 60px;
    overflow: hidden;
  }
  .hero-inner {
    max-width: 1120px; margin: 0 auto;
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 64px; align-items: center;
  }
  .hero-eyebrow {
    font-size: 11px; font-weight: 500; letter-spacing: 2px; text-transform: uppercase;
    color: var(--sienna); margin-bottom: 16px;
    display: flex; align-items: center; gap: 10px;
  }
  .hero-eyebrow::before {
    content: ''; display: inline-block;
    width: 32px; height: 1px; background: var(--sienna);
  }
  .hero h1 {
    font-family: var(--font-display);
    font-size: clamp(38px, 5vw, 58px);
    font-weight: 600; line-height: 1.12;
    letter-spacing: -1px;
    color: var(--bark);
    margin-bottom: 20px;
  }
  .hero h1 em { color: var(--rust); font-style: italic; }
  .hero-sub {
    font-size: 17px; color: #6B5744; line-height: 1.75;
    max-width: 440px; margin-bottom: 36px;
  }
  .hero-btns { display: flex; gap: 12px; flex-wrap: wrap; }
  .btn-primary {
    padding: 13px 28px; font-size: 14px; font-weight: 500;
    background: var(--bark); color: var(--cream);
    border: none; border-radius: var(--radius);
    cursor: pointer; text-decoration: none;
    transition: background 0.18s, transform 0.15s var(--ease-out);
  }
  .btn-primary:hover { background: var(--sienna); transform: translateY(-2px); }
  .btn-outline {
    padding: 13px 28px; font-size: 14px; font-weight: 500;
    background: transparent; color: var(--bark);
    border: 1.5px solid var(--tan);
    border-radius: var(--radius); cursor: pointer; text-decoration: none;
    transition: border-color 0.18s, background 0.18s, transform 0.15s var(--ease-out);
  }
  .btn-outline:hover { border-color: var(--bark); background: var(--parchment); transform: translateY(-2px); }

  /* hero visual */
  .hero-visual {
    display: grid; grid-template-columns: 1fr 1fr;
    grid-template-rows: auto auto;
    gap: 14px;
  }
  .hero-card {
    background: var(--parchment);
    border: 1px solid rgba(162,111,70,0.2);
    border-radius: 16px;
    padding: 28px 20px 20px;
    display: flex; flex-direction: column;
    transition: transform 0.3s var(--ease-out), box-shadow 0.3s;
    cursor: default;
  }
  .hero-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(92,61,46,0.12); }
  .hero-card.tall { grid-row: span 2; }
  .hero-card-icon {
    font-size: 32px; margin-bottom: 12px;
    width: 52px; height: 52px;
    background: var(--cream); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
  }
  .hero-card h3 { font-family: var(--font-display); font-size: 16px; font-weight: 600; color: var(--bark); margin-bottom: 6px; }
  .hero-card p  { font-size: 12px; color: #7A6355; line-height: 1.5; }
  .hero-card .hero-card-meta {
    margin-top: auto; padding-top: 14px;
    font-size: 11px; font-weight: 500; text-transform: uppercase;
    letter-spacing: 1px; color: var(--sienna);
  }

  /* hero stats */
  .hero-stats {
    display: flex; gap: 32px;
    margin-top: 40px; padding-top: 32px;
    border-top: 1px solid var(--parchment);
  }
  .hero-stat-num {
    font-family: var(--font-display); font-size: 26px; font-weight: 600;
    color: var(--bark);
  }
  .hero-stat-label { font-size: 12px; color: #7A6355; }

  /* ─── SECTION COMMON ────────────────────────────────────── */
  section { padding: 80px 0; }
  .section-header { text-align: center; margin-bottom: 52px; }
  .section-header .tag { margin-bottom: 16px; }
  .section-header h2 {
    font-family: var(--font-display);
    font-size: clamp(28px, 3.5vw, 40px);
    font-weight: 600; color: var(--bark);
    letter-spacing: -0.5px; line-height: 1.2;
  }
  .section-header p { font-size: 16px; color: #6B5744; margin-top: 12px; max-width: 520px; margin-left: auto; margin-right: auto; }

  /* ─── MARQUEE BAND ───────────────────────────────────────── */
  .marquee-band {
    background: var(--bark); padding: 14px 0;
    overflow: hidden; white-space: nowrap;
  }
  .marquee-track {
    display: inline-block;
    animation: marquee 30s linear infinite;
  }
  .marquee-item {
    display: inline-block; margin: 0 28px;
    font-size: 12px; font-weight: 500; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--tan);
  }
  .marquee-item::after {
    content: '◆'; margin-left: 28px; color: var(--sienna); font-size: 8px;
  }
  @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

  /* ─── CATEGORY STRIP ────────────────────────────────────── */
  .categories { padding: 60px 0; }
  .category-strip { display: flex; gap: 16px; overflow-x: auto; padding: 4px 0 16px; scrollbar-width: none; }
  .category-strip::-webkit-scrollbar { display: none; }
  .cat-pill {
    flex-shrink: 0; padding: 12px 24px;
    border-radius: 100px; border: 1.5px solid var(--parchment);
    background: var(--white); cursor: pointer;
    font-size: 14px; font-weight: 500; color: #6B5744;
    display: flex; align-items: center; gap: 8px;
    transition: all 0.2s var(--ease-out);
  }
  .cat-pill:hover, .cat-pill.active {
    background: var(--bark); color: var(--cream);
    border-color: var(--bark); transform: translateY(-2px);
  }
  .cat-pill span { font-size: 18px; }

  /* ─── PRODUCT GRID ───────────────────────────────────────── */
  .products { padding: 40px 0 80px; }
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 24px;
  }
  .product-card {
    background: var(--white);
    border: 1px solid rgba(162,111,70,0.15);
    border-radius: 18px; overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s var(--ease-out), box-shadow 0.3s, border-color 0.2s;
  }
  .product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(92,61,46,0.13);
    border-color: var(--tan);
  }
  .product-img {
    height: 200px; background: var(--parchment);
    display: flex; align-items: center; justify-content: center;
    font-size: 56px; position: relative; overflow: hidden;
  }
  .product-img-bg {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, var(--parchment) 0%, #E8DCCB 100%);
  }
  .product-img-icon { position: relative; z-index: 2; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.08)); }
  .product-badge {
    position: absolute; top: 14px; right: 14px; z-index: 3;
    font-size: 10px; font-weight: 500; letter-spacing: 1px; text-transform: uppercase;
    padding: 4px 10px; border-radius: 20px;
    background: var(--bark); color: var(--cream);
  }
  .product-body { padding: 20px; }
  .product-origin {
    font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;
    color: var(--sienna); margin-bottom: 6px;
    display: flex; align-items: center; gap: 6px;
  }
  .product-origin::before { content: '[PIN]'; font-size: 10px; }
  .product-name {
    font-family: var(--font-display); font-size: 19px; font-weight: 600;
    color: var(--bark); line-height: 1.3; margin-bottom: 8px;
  }
  .product-desc { font-size: 13px; color: #7A6355; line-height: 1.6; margin-bottom: 14px; }
  .product-footer { display: flex; align-items: center; justify-content: space-between; }
  .product-discover {
    font-size: 13px; font-weight: 500; color: var(--sienna);
    text-decoration: none; display: flex; align-items: center; gap: 4px;
    transition: gap 0.2s;
  }
  .product-discover:hover { gap: 8px; }

  /* ─── PRODUCT MODAL / DETAIL ────────────────────────────── */
  .overlay {
    position: fixed; inset: 0; z-index: 200;
    background: rgba(43,31,23,0.6);
    backdrop-filter: blur(4px);
    display: none; align-items: center; justify-content: center;
    padding: 20px;
    animation: fadeOverlay 0.25s ease;
  }
  .overlay.open { display: flex; }
  @keyframes fadeOverlay { from { opacity: 0; } to { opacity: 1; } }

  .modal {
    background: var(--white);
    border-radius: 20px;
    max-width: 820px; width: 100%;
    max-height: 90vh; overflow-y: auto;
    animation: slideModal 0.3s var(--ease-out);
  }
  @keyframes slideModal { from { opacity: 0; transform: translateY(24px) scale(0.97); } to { opacity: 1; transform: none; } }

  .modal-header {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 0; border-radius: 20px 20px 0 0; overflow: hidden;
  }
  .modal-img {
    height: 260px; background: var(--parchment);
    display: flex; align-items: center; justify-content: center;
    font-size: 80px;
  }
  .modal-intro {
    padding: 32px 28px;
    background: var(--cream);
    display: flex; flex-direction: column; justify-content: center;
  }
  .modal-intro .tag { margin-bottom: 12px; }
  .modal-intro h2 {
    font-family: var(--font-display); font-size: 26px; font-weight: 600;
    color: var(--bark); margin-bottom: 8px; line-height: 1.2;
  }
  .modal-intro .origin {
    font-size: 12px; color: var(--sienna); margin-bottom: 12px;
    display: flex; align-items: center; gap: 4px;
  }
  .modal-intro p { font-size: 14px; color: #6B5744; line-height: 1.7; }

  .modal-close {
    position: absolute; top: 16px; right: 16px;
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--parchment); border: none; cursor: pointer;
    font-size: 18px; display: flex; align-items: center; justify-content: center;
    color: var(--bark); z-index: 10;
    transition: background 0.15s;
  }
  .modal-close:hover { background: var(--tan); color: var(--white); }

  /* modal tabs */
  .modal-tabs {
    display: flex; border-bottom: 1px solid var(--parchment);
    padding: 0 28px; background: var(--white);
  }
  .modal-tab {
    padding: 14px 18px; font-size: 13px; font-weight: 400;
    cursor: pointer; color: #7A6355;
    border-bottom: 2px solid transparent;
    transition: color 0.15s, border-color 0.15s;
  }
  .modal-tab.active { color: var(--bark); font-weight: 500; border-bottom-color: var(--sienna); }

  .modal-panel { display: none; padding: 28px; animation: fadeIn 0.2s ease; }
  .modal-panel.active { display: block; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

  /* timeline */
  .timeline { padding-left: 20px; border-left: 2px solid var(--parchment); }
  .tl-item { padding-bottom: 28px; position: relative; }
  .tl-dot {
    position: absolute; left: -29px; top: 4px;
    width: 12px; height: 12px; border-radius: 50%;
    background: var(--sienna); border: 2px solid var(--white);
    box-shadow: 0 0 0 2px var(--sienna);
  }
  .tl-year { font-size: 11px; font-weight: 500; letter-spacing: 1px; text-transform: uppercase; color: var(--sienna); margin-bottom: 4px; }
  .tl-title { font-family: var(--font-display); font-size: 16px; font-weight: 600; color: var(--bark); margin-bottom: 4px; }
  .tl-text { font-size: 13px; color: #6B5744; line-height: 1.6; }

  /* locations */
  .loc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; }
  .loc-card {
    padding: 14px; border: 1px solid var(--parchment);
    border-radius: var(--radius); background: var(--cream);
    text-align: center;
  }
  .loc-card .loc-icon { font-size: 22px; margin-bottom: 8px; }
  .loc-card .loc-name { font-size: 13px; font-weight: 500; color: var(--bark); margin-bottom: 2px; }
  .loc-card .loc-type { font-size: 11px; color: #7A6355; }

  /* funfacts */
  .facts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .fact-card {
    padding: 18px; border-radius: var(--radius);
    background: var(--parchment); border: 1px solid rgba(162,111,70,0.2);
  }
  .fact-num { font-family: var(--font-display); font-size: 30px; font-weight: 600; color: var(--sienna); margin-bottom: 6px; }
  .fact-text { font-size: 13px; color: #6B5744; line-height: 1.55; }

  /* specs */
  .specs-tbl { width: 100%; border-collapse: collapse; font-size: 14px; }
  .specs-tbl tr { border-bottom: 1px solid var(--parchment); }
  .specs-tbl tr:last-child { border-bottom: none; }
  .specs-tbl td { padding: 10px 6px; }
  .specs-tbl td:first-child { color: #7A6355; width: 42%; }
  .specs-tbl td:last-child { font-weight: 500; color: var(--bark); }

  /* ─── BRAND STORY BAND ───────────────────────────────────── */
  .story-band {
    background: var(--bark); color: var(--cream);
    padding: 80px 32px;
  }
  .story-inner {
    max-width: 1120px; margin: 0 auto;
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 64px; align-items: center;
  }
  .story-eyebrow {
    font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--tan); margin-bottom: 16px;
  }
  .story-inner h2 {
    font-family: var(--font-display); font-size: 36px; font-weight: 600;
    line-height: 1.2; letter-spacing: -0.5px; color: var(--cream);
    margin-bottom: 20px;
  }
  .story-inner h2 em { color: var(--gold); font-style: italic; }
  .story-inner p { font-size: 15px; color: #C4B5A5; line-height: 1.8; margin-bottom: 16px; }
  .story-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 32px; }
  .story-stat { padding: 20px; border: 1px solid rgba(201,169,122,0.25); border-radius: var(--radius); }
  .story-stat-num { font-family: var(--font-display); font-size: 30px; font-weight: 600; color: var(--gold); }
  .story-stat-label { font-size: 12px; color: #C4B5A5; margin-top: 4px; }
  .story-visual {
    background: rgba(255,255,255,0.05); border: 1px solid rgba(201,169,122,0.2);
    border-radius: 20px; padding: 36px;
  }
  .story-pillars { display: flex; flex-direction: column; gap: 20px; }
  .pillar { display: flex; gap: 16px; align-items: flex-start; }
  .pillar-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: rgba(201,169,122,0.15); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 20px;
  }
  .pillar-title { font-size: 14px; font-weight: 500; color: var(--cream); margin-bottom: 4px; }
  .pillar-text  { font-size: 13px; color: #C4B5A5; line-height: 1.55; }

  /* ─── FEATURED BANNER ────────────────────────────────────── */
  .featured-banner {
    background: var(--parchment);
    border-top: 1px solid rgba(162,111,70,0.2);
    border-bottom: 1px solid rgba(162,111,70,0.2);
    padding: 52px 32px;
  }
  .banner-inner {
    max-width: 1120px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between; gap: 24px;
    flex-wrap: wrap;
  }
  .banner-inner h3 {
    font-family: var(--font-display); font-size: 26px; font-weight: 600;
    color: var(--bark);
  }
  .banner-inner p { font-size: 14px; color: #6B5744; margin-top: 6px; max-width: 420px; }

  /* ─── FOOTER ─────────────────────────────────────────────── */
  footer {
    background: var(--charcoal); color: #C4B5A5;
    padding: 60px 32px 32px;
  }
  .footer-inner {
    max-width: 1120px; margin: 0 auto;
    display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 48px; margin-bottom: 48px;
  }
  .footer-brand .logo {
    font-family: var(--font-display); font-size: 20px; font-weight: 600;
    color: var(--cream); margin-bottom: 12px;
  }
  .footer-brand .logo span { color: var(--gold); font-style: italic; }
  .footer-brand p { font-size: 13px; line-height: 1.7; color: #9A8778; max-width: 240px; }
  .footer-col h4 { font-size: 12px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: var(--cream); margin-bottom: 16px; }
  .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
  .footer-col ul a { font-size: 13px; color: #9A8778; text-decoration: none; transition: color 0.15s; }
  .footer-col ul a:hover { color: var(--tan); }
  .footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.07);
    padding-top: 24px;
    display: flex; justify-content: space-between; align-items: center;
    font-size: 12px; color: #5A4E47; flex-wrap: wrap; gap: 8px;
  }

  /* ─── ANIMATIONS ─────────────────────────────────────────── */
  .reveal {
    opacity: 0; transform: translateY(20px);
    transition: opacity 0.6s var(--ease-out), transform 0.6s var(--ease-out);
  }
  .reveal.visible { opacity: 1; transform: none; }
  .reveal-delay-1 { transition-delay: 0.1s; }
  .reveal-delay-2 { transition-delay: 0.2s; }
  .reveal-delay-3 { transition-delay: 0.3s; }

  /* ─── RESPONSIVE ─────────────────────────────────────────── */
  @media (max-width: 768px) {
    .hero-inner, .modal-header, .story-inner, .footer-inner { grid-template-columns: 1fr; gap: 32px; }
    .hero-visual { display: none; }
    .hamburger { display: flex; }
    .nav-links, .nav-cta { display: none; }
    .nav-links.open { display: flex; flex-direction: column; position: absolute; top: 68px; left: 0; right: 0; background: var(--cream); padding: 16px; border-bottom: 1px solid var(--parchment); z-index: 50; }
    .facts-grid { grid-template-columns: 1fr; }
    .story-stats { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<!-- ─── NAV ──────────────────────────────────────────────────── -->
<nav>
  <div class="nav-inner">
    <a href="#" class="nav-logo">Gawang <span>Pinas</span></a>
    <ul class="nav-links" id="navLinks">
      <li><a href="#products" class="active">Mga Produkto</a></li>
      <li><a href="#story">Ang Ating Kwento</a></li>
      <li><a href="#about">Tungkol</a></li>
      <li><a href="#contact">Kontak</a></li>
    </ul>
    <a href="#products" class="nav-cta">Galugarin ang mga Produkto</a>
    <div class="hamburger" onclick="toggleMenu()">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- ─── HERO ──────────────────────────────────────────────────── -->
<section class="hero">
  <div class="hero-inner">
    <div>
      <div class="hero-eyebrow">Proud na Filipino</div>
      <h1>Dito Ginawa.<br><em>Mahal Saan Man.</em></h1>
      <p class="hero-sub">Tuklasin ang pinakamahusay na lokal na gawang Pilipino na produkto — mula sa pang-araw-araw na gamit sa bahay at gawa-kamay na sining hanggang sa lokal na moda at tunay na pagkain.</p>
      <div class="hero-btns">
        <a href="#products" class="btn-primary">Mag-browse ng Produkto</a>
        <a href="#story" class="btn-outline">Ang Ating Kwento</a>
      </div>
      <div class="hero-stats">
        <div><div class="hero-stat-num">120+</div><div class="hero-stat-label">Lokal na produkto</div></div>
        <div><div class="hero-stat-num">14</div><div class="hero-stat-label">Mga rehiyon na sakop</div></div>
        <div><div class="hero-stat-num">60+</div><div class="hero-stat-label">Mga Filipino na tagagawa</div></div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="hero-card tall">
        <div class="hero-card-icon">[BASKET]</div>
        <h3>Gawa-kamay na Sining</h3>
        <p>Ipinasa sa mga henerasyon, ang bawat piraso ay may kwento ng sining ng Pilipino.</p>
        <div class="hero-card-meta">Sining at Pamana</div>
      </div>
      <div class="hero-card">
        <div class="hero-card-icon">[BOTTLE]</div>
        <h3>Lokal na Lasa</h3>
        <p>Tunay na lasa mula sa buong kapuluan.</p>
        <div class="hero-card-meta">Pagkain at Inumin</div>
      </div>
      <div class="hero-card">
        <div class="hero-card-icon">[SHIRT]</div>
        <h3>Lokal na Tela</h3>
        <p>Suotin ang iyong ugat nang proud.</p>
        <div class="hero-card-meta">Moda</div>
      </div>
    </div>
  </div>
</section>

<!-- ─── MARQUEE ────────────────────────────────────────────────── -->
<div class="marquee-band">
  <div class="marquee-track">
    <span class="marquee-item">Gawa sa Pilipinas</span>
    <span class="marquee-item">Bahay at Kusina</span>
    <span class="marquee-item">Moda at Kasuotan</span>
    <span class="marquee-item">Pagkain at Inumin</span>
    <span class="marquee-item">Sining at Gawa-kamay</span>
    <span class="marquee-item">Suportahan ang Lokal</span>
    <span class="marquee-item">Proud na Filipino</span>
    <span class="marquee-item">Gawa sa Pilipinas</span>
    <span class="marquee-item">Bahay at Kusina</span>
    <span class="marquee-item">Moda at Kasuotan</span>
    <span class="marquee-item">Pagkain at Inumin</span>
    <span class="marquee-item">Sining at Gawa-kamay</span>
    <span class="marquee-item">Suportahan ang Lokal</span>
    <span class="marquee-item">Proud na Filipino</span>
  </div>
</div>

<!-- ─── CATEGORIES ─────────────────────────────────────────────── -->
<section class="categories" id="products">
  <div class="container">
    <div class="section-header reveal">
      <span class="tag">Mag-browse ayon sa Kategorya</span>
      <h2>Ano ang hinahanap mo?</h2>
      <p>Mula sa pang-araw-araw na gamit sa bahay hanggang sa mga piraso na sining na isahan — lahat ay gawa dito sa Pilipinas.</p>
    </div>
    <div class="category-strip" id="catStrip">
      <div class="cat-pill active" onclick="filterCat('all', this)"><span>[FLAG]</span> Lahat ng Produkto</div>
      <div class="cat-pill" onclick="filterCat('home', this)"><span>[HOUSE]</span> Bahay at Kusina</div>
      <div class="cat-pill" onclick="filterCat('fashion', this)"><span>[SHIRT]</span> Moda at Kasuotan</div>
      <div class="cat-pill" onclick="filterCat('food', this)"><span>[BOTTLE]</span> Pagkain at Inumin</div>
      <div class="cat-pill" onclick="filterCat('crafts', this)"><span>[BASKET]</span> Sining at Gawa-kamay</div>
    </div>
  </div>
</section>

<!-- ─── PRODUCTS ───────────────────────────────────────────────── -->
<section class="products">
  <div class="container">
    <div class="product-grid" id="productGrid"></div>
  </div>
</section>

<!-- ─── FEATURED BANNER ────────────────────────────────────────── -->
<div class="featured-banner">
  <div class="banner-inner">
    <div>
      <h3>Kilala mo ba ang lokal na produkto na dapat ipakita?</h3>
      <p>Laging naghahanap kami ng higit pang mga kamangha-manghang gawang Pilipino na produkto na ipakita. Nominate ang isang tagagawa o brand ngayon.</p>
    </div>
    <a href="#contact" class="btn-primary">Nominate ang isang Produkto</a>
  </div>
</div>

<!-- ─── BRAND STORY ────────────────────────────────────────────── -->
<section class="story-band" id="story">
  <div class="story-inner">
    <div>
      <div class="story-eyebrow">Bakit Gawang Pinas</div>
      <h2>Ang bawat produkto ay may <em>kwento</em> na kwento.</h2>
      <p>Ang Gawang Pinas ay isang platform na nakatuon sa pagpapakita ng mga kamangha-manghang produkto na ginagawa ng mga kamay ng Filipino — sa maliliit na workshop, pamilyang kusina, at komunidad na kooperatiba sa buong 7,641 na isla.</p>
      <p>Hindi kami nagbebenta. Nagdiriwang kami. Gusto namin na ang bawat Filipino — at ang mundo — na tuklasin ang kalidad at pagkamalikha sa kanilang sariling bakuran.</p>
      <div class="story-stats">
        <div class="story-stat"><div class="story-stat-num">14</div><div class="story-stat-label">Mga rehiyon na ipinakita</div></div>
        <div class="story-stat"><div class="story-stat-num">120+</div><div class="story-stat-label">Mga produkto na ipinakita</div></div>
        <div class="story-stat"><div class="story-stat-num">60+</div><div class="story-stat-label">Lokal na tagagawa</div></div>
        <div class="story-stat"><div class="story-stat-num">4</div><div class="story-stat-label">Mga kategorya</div></div>
      </div>
    </div>
    <div class="story-visual">
      <div class="story-pillars">
        <div class="pillar">
          <div class="pillar-icon">[GRAIN]</div>
          <div>
            <div class="pillar-title">Lokal na Pinanggalingan</div>
            <div class="pillar-text">Ang bawat itinatampok na produkto ay gumagamit ng sangkap o materyales mula sa loob ng Pilipinas.</div>
          </div>
        </div>
        <div class="pillar">
          <div class="pillar-icon">[HANDSHAKE]</div>
          <div>
            <div class="pillar-title">Nakaugat sa Komunidad</div>
            <div class="pillar-text">Ang bawat produkto ay sumusuporta sa kabuhayan ng mga pamilyang Filipino at komunidad.</div>
          </div>
        </div>
        <div class="pillar">
          <div class="pillar-icon">[MEDAL]</div>
          <div>
            <div class="pillar-title">Tinustong Kalidad</div>
            <div class="pillar-text">Ipinapakita namin ang mga produkto na tunay na sining at pamantayan ng kalidad.</div>
          </div>
        </div>
        <div class="pillar">
          <div class="pillar-icon">[BOOK]</div>
          <div>
            <div class="pillar-title">Mga Kwento Muna</div>
            <div class="pillar-text">Sa likod ng bawat produkto ay isang kasaysayan, isang pamana, at isang kwento ng tao.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── PRODUCT MODAL ──────────────────────────────────────────── -->
<div class="overlay" id="overlay" onclick="closeModal(event)">
  <div class="modal" id="modal">
    <button class="modal-close" onclick="closeModalDirect()">[X]</button>
    <div class="modal-header">
      <div class="modal-img" id="modalImg">[JAR]</div>
      <div class="modal-intro">
        <span class="tag" id="modalTag">Kategorya</span>
        <h2 id="modalName">Pangalan ng Produkto</h2>
        <div class="origin" id="modalOrigin">[PIN] Rehiyon</div>
        <p id="modalDesc">Deskripsyon dito.</p>
      </div>
    </div>
    <div class="modal-tabs">
      <div class="modal-tab active" onclick="switchTab('overview', this)">Pangkalahatan</div>
      <div class="modal-tab" onclick="switchTab('history', this)">Kasaysayan</div>
      <div class="modal-tab" onclick="switchTab('locations', this)">Saan Makahanap</div>
      <div class="modal-tab" onclick="switchTab('funfacts', this)">Alam Mo Ba?</div>
      <div class="modal-tab" onclick="switchTab('specs', this)">Detalye</div>
    </div>
    <div class="modal-panel active" id="panel-overview"></div>
    <div class="modal-panel" id="panel-history"></div>
    <div class="modal-panel" id="panel-locations"></div>
    <div class="modal-panel" id="panel-funfacts"></div>
    <div class="modal-panel" id="panel-specs"></div>
  </div>
</div>

<!-- ─── FOOTER ─────────────────────────────────────────────────── -->
<footer id="contact">
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="logo">Gawang <span>Pinas</span></div>
      <p>Isang pagtatanghal ng pinakamahusay na gawang Pilipino na produkto, nagdiriwang ng lokal na sining at pamana.</p>
    </div>
    <div class="footer-col">
      <h4>Tuklasin</h4>
      <ul>
        <li><a href="#products">Lahat ng Produkto</a></li>
        <li><a href="#">Bahay at Kusina</a></li>
        <li><a href="#">Moda</a></li>
        <li><a href="#">Pagkain at Inumin</a></li>
        <li><a href="#">Sining</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Tungkol</h4>
      <ul>
        <li><a href="#story">Ang Ating Kwento</a></li>
        <li><a href="#">Para sa mga Tagagawa</a></li>
        <li><a href="#">Nominate ang isang Produkto</a></li>
        <li><a href="#">Press</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Kontak</h4>
      <ul>
        <li><a href="mailto:hello@gawangpinas.ph">hello@gawangpinas.ph</a></li>
        <li><a href="#">Facebook</a></li>
        <li><a href="#">Instagram</a></li>
        <li><a href="#">TikTok</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2026 Gawang Pinas. Lahat ng karapatan ay nakalaan.</span>
    <span>Ginawa na may [HEART] para sa Pilipinas</span>
  </div>
</footer>

<script>
/* ─── DATA ─────────────────────────────────────────────────────── */
const products = [
  {
    id: 1, cat: 'home', emoji: '[GLASS]', badge: 'Itinatampok',
    name: 'Burnay na Salamin sa Inom', origin: 'Vigan, Ilocos Sur',
    tagLabel: 'Bahay at Kusina', tagClass: 'home',
    desc: 'Handblown mula sa recycled glass ng mga Ilocano na artisan, ang bawat piraso ay may unique na karakter. Dishwasher safe at magandang-imperfect.',
    overview: 'Ang Burnay na glassware ay isa sa mga pinakamahalagang sining ng Vigan. Ginawa mula sa molten recycled glass, ang bawat salamin ay mouthblown sa isang process na hindi nagbago sa loob ng siglo. Ang resulta ay isang medyo uneven, wonderfully organic na piraso na gumagana bilang pang-araw-araw na tableware o isang decorative accent.',
    overviewStats: [{ num: '300+', label: 'Taon ng tradisyon' }, { num: '100%', label: 'Recycled glass' }, { num: 'Unique', label: 'Ang bawat piraso ay iba' }],
    history: [
      { year: '1700s', title: 'Spanish colonial roots', text: 'Ang glass-blowing ay dumating sa Ilocos region kasama ang Spanish missionaries, na nagturo sa lokal na craftsmen ng sining.' },
      { year: '1800s', title: 'Ang craft ay yumabong sa Vigan', text: 'Ang Vigan ay naging center ng Philippine glassblowing, na may family workshop na nagpapasa ng skill sa mga henerasyon.' },
      { year: '2000s', title: 'Eco-movement shift', text: 'Nagsimulang gumamit ang mga artisan ng 100% recycled glass, na nagbabawas ng waste habang pinapanatili ang handcrafted na tradisyon.' },
      { year: '2024', title: 'Global recognition', text: 'Ang mga produkto ng Vigan Burnay ay nakarating sa international markets sa pamamagitan ng export cooperatives at online platforms.' }
    ],
    locations: [
      { icon: '[BUILDING]', name: 'Vigan Heritage Town', type: 'Souvenir shops' },
      { icon: '[CART]', name: 'SM Homeworld', type: 'Select branches' },
      { icon: '[STORE]', name: 'Kultura Filipino', type: 'SM Malls nationwide' },
      { icon: '[BOX]', name: 'Shopee / Lazada', type: 'Official stores online' },
      { icon: '[TENT]', name: 'Artisano Markets', type: 'Weekend pop-up events' },
    ],
    facts: [
      { num: '1,200°C', text: 'Temperatura kung saan natunaw ang glass bago ito hugutin ng kamay.' },
      { num: '3 mins', text: 'Ang window na mayroon ang isang glassblower na hubugin ang glass bago ito mag-cool at mag-hard.' },
      { num: '0 waste', text: 'Ang broken o imperfect na piraso ay remelt at gagamitin muli — walang napupunta sa waste.' },
      { num: '1 maker', text: 'Ang bawat salamin ay hinawakan lamang ng isang artisan mula sa simula hanggang sa katapusan.' }
    ],
    specs: [
      ['Materyal', 'Recycled silica glass'],
      ['Capacity', '250–300 ml'],
      ['Taas', 'Approx. 10–12 cm'],
      ['Safe para sa', 'Dishwasher, cold beverages'],
      ['Pinanggalingan', 'Vigan, Ilocos Sur'],
      ['Mga Certification', 'DTI Certified'],
    ]
  },
  {
    id: 2, cat: 'fashion', emoji: '[SHIRT]', badge: 'Lokal na Brand',
    name: 'Piña Weave na T-Shirt', origin: 'Kalibo, Aklan',
    tagLabel: 'Moda', tagClass: 'fashion',
    desc: 'Isang modernong tee na halo sa piña fiber — ang world-renowned textile na woven mula sa pineapple leaves sa Aklan. Lightweight, breathable, at unmistakably Filipino.',
    overview: 'Ang Piña cloth ay isa sa mga pinakacelebrated na textile ng Pilipinas, na tradisyonal na ginamit para sa Barong Tagalog. Ang modernong adaptasyong ito ay naghalo ng piña fiber sa cotton para sa isang casual na t-shirt na breathable, durable, at nagdadala ng siglo ng Aklanon weaving heritage.',
    overviewStats: [{ num: '100%', label: 'Gawa sa Aklan' }, { num: '40%', label: 'Piña fiber blend' }, { num: 'Lahat ng size', label: 'XS to 3XL available' }],
    history: [
      { year: '1600s', title: 'Pineapple leaves, muling naisip', text: 'Ang indigenous Aklanon weavers ay natuklasan na ang pineapple leaf fibers, kapag processed, ay nagproproduce ng incredibly fine at lustrous textile.' },
      { year: '1800s', title: 'Pambansang formal wear', text: 'Ang Piña cloth ay naging material na choice para sa Barong Tagalog — ang pambansang formal garment para sa mga Filipino lalaki.' },
      { year: '2010s', title: 'Fashion revival', text: 'Nagsimulang muling naisip ng mga young Filipino designer ang piña sa everyday wear — shirts, bags, at accessories para sa modernong Filipino.' },
      { year: '2025', title: 'Sustainable fashion movement', text: 'Ang mga Piña t-shirt ay nakakuha ng international attention bilang isang eco-friendly, sustainable fashion alternative.' }
    ],
    locations: [
      { icon: '[TENT]', name: 'Ati-Atihan Festival', type: 'Kalibo, Aklan (Ene)' },
      { icon: '[BAG]', name: 'Kultura Filipino', type: 'SM Malls' },
      { icon: '[STORE]', name: 'Pasalubong Center', type: 'Major airports' },
      { icon: '[BOX]', name: 'Shopee', type: 'Official brand store' },
      { icon: '[MALL]', name: 'Rustan\'s', type: 'Select branches' },
    ],
    facts: [
      { num: '3,000', text: 'Pineapple leaves na kailangan para makabuo ng isang metro ng piña cloth.' },
      { num: '6 months', text: 'Oras na kinakailangan ng isang skilled weaver para makabuo ng isang formal na piña garment.' },
      { num: '4x', text: 'Ang Piña fiber ay apat na beses na mas malakas kaysa cotton ng parehong bigat.' },
      { num: '1600s', text: 'Ang Piña weaving ay ginagawa sa Aklan sa loob ng higit sa 400 taon.' }
    ],
    specs: [
      ['Fiber', '60% Cotton, 40% Piña'],
      ['Weave', 'Plain weave blend'],
      ['Bigat', '160 gsm'],
      ['Pag-aalaga', 'Hand wash, cold water'],
      ['Mga Size', 'XS, S, M, L, XL, 2XL, 3XL'],
      ['Pinanggalingan', 'Kalibo, Aklan'],
    ]
  },
  {
    id: 3, cat: 'food', emoji: '[BOTTLE]', badge: 'Pamana',
    name: 'Sukang Iloko (Ilocos Vinegar)', origin: 'Ilocos Norte',
    tagLabel: 'Pagkain at Inumin', tagClass: 'food',
    desc: 'Fermented mula sa sugarcane juice sa earthen jars, ang Sukang Iloko ay ang tangy, complex na vinegar sa puso ng Ilocano cuisine. Rich, acidic, at deeply aromatic.',
    overview: 'Ang Sukang Iloko ay hindi ordinaryong vinegar. Fermented naturally sa burnay jars sa loob ng mga buwan, ito ay nagde-develop ng complex, fruity acidity na distinct mula sa commercial vinegars. Isang staple sa Ilocano cooking, ginagamit para sa pinakbet, dinuguan, at bilang dipping sauce kasama ang bagnet.',
    overviewStats: [{ num: '6 buwan', label: 'Natural fermentation' }, { num: '0', label: 'Additives o preservatives' }, { num: '4–5%', label: 'Natural acidity' }],
    history: [
      { year: '1500s', title: 'Pre-colonial fermentation', text: 'Matagal bago ang Spanish contact, ang mga Ilocanos ay nagpe-ferment ng sugarcane juice sa clay pots, na lumikha ng magiging Sukang Iloko.' },
      { year: '1800s', title: 'Trade commodity', text: 'Ang Ilocos vinegar ay naging prized trade item, na exported sa Manila at neighboring regions para sa unique flavor nito.' },
      { year: '1990s', title: 'Commercial adaptation', text: 'Nagsimulang magbottle ang small-scale producers ng Sukang Iloko para sa wider distribution habang pinapanatili ang traditional methods.' },
      { year: '2020s', title: 'Craft condiment boom', text: 'Ang mga food enthusiasts at chefs ay muling natuklasan ang Sukang Iloko bilang isang premium artisan condiment sa Filipino cuisine.' }
    ],
    locations: [
      { icon: '[STORE]', name: 'Ilocos Public Market', type: 'Laoag & Vigan' },
      { icon: '[CART]', name: 'S&R / Landers', type: 'Select branches' },
      { icon: '[MALL]', name: 'Landmark Supermarket', type: 'Nationwide' },
      { icon: '[BOX]', name: 'Lazada / Shopee', type: 'Online delivery' },
      { icon: '[PLANE]', name: 'Airport Pasalubong', type: 'NAIA & Clark' },
    ],
    facts: [
      { num: '6 buwan', text: 'Minimum fermentation time sa burnay jars para sa authentic na Sukang Iloko.' },
      { num: '2x', text: 'Higit na antioxidants kaysa commercially produced vinegar, dahil sa natural fermentation.' },
      { num: '500+', text: 'Taon ng unbroken fermentation tradition sa Ilocos region.' },
      { num: '1 jar', text: 'Ang isang burnay jar ay makape-ferment ng hanggang 20 liters ng sugarcane vinegar sa isang pagkakataon.' }
    ],
    specs: [
      ['Uri', 'Sugarcane vinegar'],
      ['Acidity', '4–5% acetic acid'],
      ['Fermentation', '6 months minimum'],
      ['Container', 'Glass bottle, 500ml'],
      ['Shelf Life', '2 years (unopened)'],
      ['Pinanggalingan', 'Ilocos Norte'],
    ]
  },
  {
    id: 4, cat: 'crafts', emoji: '[BASKET]', badge: 'Artisan',
    name: 'Banig Woven Mat', origin: 'Basey, Samar',
    tagLabel: 'Sining at Gawa-kamay', tagClass: 'crafts',
    desc: 'Woven mula sa tikog grass ng mga skilled hands ng mga Samareños, ang Banig mats ay UNESCO-recognized na piraso ng Philippine cultural heritage. Ang bawat pattern ay may kwento ng lokal.',
    overview: 'Ang Banig ng Basey, Samar ay kabilang sa pinakamahusay na woven mats sa Southeast Asia. Ginawa mula sa locally harvested tikog grass, ang bawat mat ay tumatagal ng mga araw upang makumpleto. Ang intricate geometric patterns — na tinatawag na "bunga" designs — ay unique sa bawat weaving family at nagdadala ng cultural meaning.',
    overviewStats: [{ num: '3–7', label: 'Araw para i-weave ang isang mat' }, { num: 'UNESCO', label: 'Heritage recognition' }, { num: '100%', label: 'Natural tikog grass' }],
    history: [
      { year: '1000s', title: 'Ancient weaving traditions', text: 'Ang archaeological evidence ay nagpapakita ng mat weaving sa Visayas na nagmula sa higit sa isang millennium, na ginamit para sa pagtulog, trade, at ritual.' },
      { year: '1600s', title: 'Spanish documentation', text: 'Ang mga Spanish colonizer ay tinalaga ang exceptional quality ng Samar mats at nagsimulang i-export ang mga ito bilang luxury goods sa Manila at Spain.' },
      { year: '1980s', title: 'Government recognition', text: 'Ang Philippine government ay itinalaga ang Basey bilang Banig capital ng Pilipinas, na nagtatatag ng cooperatives para sa mga weaver.' },
      { year: '2010s', title: 'Global crafts market', text: 'Ang Banig mats ay nakakuha ng traction sa international home décor markets, na itinampok sa design exhibitions sa Europe at Asia.' }
    ],
    locations: [
      { icon: '[VILLAGE]', name: 'Basey, Samar', type: 'Directly from weavers' },
      { icon: '[BUILDING]', name: 'Kultura Filipino', type: 'SM Malls nationwide' },
      { icon: '[BAG]', name: 'Tesoros / Silahis', type: 'Manila craft stores' },
      { icon: '[BOX]', name: 'Shopee', type: 'Cooperative stores online' },
      { icon: '[TENT]', name: 'Philippine Art & Craft Fair', type: 'Annual events' },
    ],
    facts: [
      { num: '72 hrs', text: 'Minimum time na kailangan para i-weave ang isang standard sleeping mat mula sa scratch.' },
      { num: '500+', text: 'Active weavers sa Basey lang, karamihan ay babae na sumusuporta sa kanilang mga pamilya.' },
      { num: '40+', text: 'Distinct pattern designs na documented sa Basey\'s weaving tradition.' },
      { num: '15 yrs', text: 'Ang isang quality Banig mat ay pwedeng tumagal ng hanggang 15 taon nang tamang pag-aalaga.' }
    ],
    specs: [
      ['Materyal', 'Tikog grass (Fimbristylis sp.)'],
      ['Size', 'Single (90×180cm) to King (180×210cm)'],
      ['Teknike', 'Plaiting / twill weave'],
      ['Dye', 'Natural plant-based dyes'],
      ['Pag-aalaga', 'Air dry, iwasan ang prolonged moisture'],
      ['Pinanggalingan', 'Basey, Samar'],
    ]
  },
  {
    id: 5, cat: 'home', emoji: '[JAR]', badge: 'Pamana',
    name: 'Burnay Clay Jar', origin: 'Vigan, Ilocos Sur',
    tagLabel: 'Bahay at Kusina', tagClass: 'home',
    desc: 'Fired sa wood-burning kilns para sa 3 araw, ang Burnay jars ay ang ancient earthenware ng Vigan — na ginagamit para sa pag-ferment ng vinegar, pag-store ng water, at bilang stunning home décor.',
    overview: 'Ang Burnay (o Burnay pottery) ay kabilang sa mga oldest craft tradition sa Pilipinas. Ang mga itim, unglazed na jars ay fired gamit ang wood-burning kiln sa isang process na barely nagbago sa loob ng 400 taon. Tradisyonal na ginagamit para sa fermentation, ngayon ay itinelebrate bilang home décor objects.',
    overviewStats: [{ num: '400+', label: 'Taon ng tradisyon' }, { num: '3 araw', label: 'Kiln firing time' }, { num: 'Non-toxic', label: 'Natural clay & wood' }],
    history: [
      { year: '1600s', title: 'Chinese influence', text: 'Ang pottery traditions sa Vigan ay naimpluwensyahan ng Chinese traders na nag-settle sa region, na nag-merge sa indigenous techniques.' },
      { year: '1800s', title: 'Industrial center', text: 'Ang pottery district ng Vigan ay nagproproduce ng libu-libong jars annually, na nag-supply sa buong Ilocos region ng storage vessels.' },
      { year: '2000s', title: 'UNESCO heritage listing', text: 'Ang Vigan ay idineklara bilang UNESCO World Heritage Site, na nagdadala ng attention sa Burnay pottery bilang isang living cultural tradition.' },
      { year: '2020s', title: 'Interior design icon', text: 'Ang mga interior designer at architect globally ay nagsimulang mag-incorporate ng Burnay jars bilang décor centerpieces sa high-end homes.' }
    ],
    locations: [
      { icon: '[VILLAGE]', name: 'Pagburnayan, Vigan', type: 'Pottery workshops' },
      { icon: '[BUILDING]', name: 'Vigan Heritage Town', type: 'Calle Crisologo stores' },
      { icon: '[CART]', name: 'Kultura Filipino', type: 'SM Malls' },
      { icon: '[MALL]', name: 'Rustan\'s Home', type: 'Select branches' },
      { icon: '[BOX]', name: 'Shopee', type: 'Artisan sellers' },
    ],
    facts: [
      { num: '72 hrs', text: 'Ang kiln ay nagbuo nang tuloy-tuloy para sa 3 araw upang makabuo ng characteristic dark burnay finish.' },
      { num: '1,000°C', text: 'Firing temperature na naabot sa loob ng wood-burning kiln.' },
      { num: '50 yrs+', text: 'Ang isang well-made Burnay jar ay pwedeng tumagal ng higit sa 50 taon nang minimal na pag-aalaga.' },
      { num: '0', text: 'Chemical glazes na ginamit — ang finish ay galing entirely sa clay at firing process.' }
    ],
    specs: [
      ['Materyal', 'Local Ilocos clay'],
      ['Firing', 'Wood kiln, 3-day process'],
      ['Finish', 'Unglazed, natural dark patina'],
      ['Mga Size', 'Small (15cm) to Large (60cm)'],
      ['Gagamitin', 'Décor, fermentation, storage'],
      ['Pinanggalingan', 'Vigan, Ilocos Sur'],
    ]
  },
  {
    id: 6, cat: 'food', emoji: '[CHOCOLATE]', badge: 'Award-winning',
    name: 'Tablea de Puro Cacao', origin: 'Davao del Sur',
    tagLabel: 'Pagkain at Inumin', tagClass: 'food',
    desc: 'Pure, stone-ground cacao tablets mula sa cacao capital ng Pilipinas. Ginagamit para sa traditional tsokolate — thick, rich, at unapologetically real chocolate.',
    overview: 'Ang Davao ay nagproproduce ng ilan sa mga world\'s finest cacao beans, at ang Tablea ay ang purest expression nito. Stone-ground mula sa fermented at roasted cacao nibs na walang additives, ang mga tablet na ito ay nadissolve sa isang thick, bittersweet hot chocolate na unlike kahit anong mass-produced.',
    overviewStats: [{ num: '100%', label: 'Pure cacao, walang additives' }, { num: '3×', label: 'Awarded internationally' }, { num: 'Fermented', label: 'Traditional process' }],
    history: [
      { year: '1500s', title: 'Pre-colonial cacao', text: 'Ang cacao ay itinanim sa Mindanao matagal bago ang Spanish arrival. Ang indigenous groups ay gumamit nito ceremonially at medicinally.' },
      { year: '1700s', title: 'Tsokolate culture', text: 'Ang Spanish missionaries ay popularized ang drinking chocolate (tsokolate) sa buong Pilipinas, na nagtatag nito bilang morning staple.' },
      { year: '1990s', title: 'Davao cacao boom', text: 'Ang Davao ay naging cacao capital ng Pilipinas, na recognized internationally para sa bean quality.' },
      { year: '2020s', title: 'Craft chocolate movement', text: 'Ang mga Filipino artisan chocolatiers ay nanalo ng international awards, na naglalagay ng Philippine cacao sa global fine chocolate map.' }
    ],
    locations: [
      { icon: '[STORE]', name: 'Davao City Markets', type: 'Agdao & Bankerohan' },
      { icon: '[CART]', name: 'S&R / Landers', type: 'Nationwide' },
      { icon: '[MALL]', name: 'Healthy Options', type: 'Select branches' },
      { icon: '[BOX]', name: 'Shopee / Lazada', type: 'Online stores' },
      { icon: '[PLANE]', name: 'Francisco Bangoy Airport', type: 'Pasalubong shops' },
    ],
    facts: [
      { num: '72 hrs', text: 'Fermentation time para sa cacao pods pagkatapos ng harvest upang mag-develop ng flavor complexity.' },
      { num: '1st', text: 'Ang Pilipinas ay nasa top 10 cacao producers globally, na ang Davao ang nangunguna.' },
      { num: '0', text: 'Additives, sugar, o milk sa pure Tablea — ito\'s 100% cacao.' },
      { num: '500 yrs', text: 'Ang Tsokolate ay bahagi ng Filipino breakfast culture sa loob ng kalahating siglo.' }
    ],
    specs: [
      ['Mga Sangkap', '100% pure cacao'],
      ['Process', 'Fermented, roasted, stone-ground'],
      ['Bigat', '250g per pack (approx. 10 tablets)'],
      ['Cacao %', '100% (unsweetened)'],
      ['Shelf Life', '12 months'],
      ['Pinanggalingan', 'Davao del Sur'],
    ]
  },
  {
    id: 7, cat: 'crafts', emoji: '[BAG]', badge: 'Artisan',
    name: 'Abaca Bayong Bag', origin: 'Leyte & Samar',
    tagLabel: 'Sining at Gawa-kamay', tagClass: 'crafts',
    desc: 'Handwoven mula sa abaca — ang "Manila hemp" na tumubo sa Eastern Visayas — ang sturdy pero elegant na tote bag na ito ay moda at heritage sa isang sustainable package.',
    overview: 'Ang Abaca Bayong ay ang everyday bag ng Filipino, na muling naisip para sa modernong use. Woven mula sa abaca fiber — isa sa mga world\'s strongest natural fibers — ang bawat bag ay handmade ng skilled weavers sa Eastern Visayas. Durable, water-resistant, at naturally beautiful.',
    overviewStats: [{ num: 'World\'s', label: 'Pinakamalakas na natural fiber' }, { num: '100%', label: 'Biodegradable' }, { num: 'Handwoven', label: 'Ng local artisans' }],
    history: [
      { year: '1800s', title: 'Abaca fiber trade', text: 'Ang Pilipinas ay naging world\'s primary source ng abaca fiber, na exported globally para sa rope, textiles, at paper.' },
      { year: '1900s', title: 'Bayong as daily staple', text: 'Ang woven abaca bag ay naging fixture sa Philippine daily life — para sa markets, church, at travel.' },
      { year: '2010s', title: 'Eco-fashion crossover', text: 'Ang mga fashion designer ay nagsimulang mag-incorporate ng Bayong sa high fashion shows, na cementing ang cultural status nito.' },
      { year: '2024', title: 'Global sustainable fashion', text: 'Ang international brands ay kumokomisyon ng Filipino abaca weavers para sa eco-friendly product lines.' }
    ],
    locations: [
      { icon: '[VILLAGE]', name: 'Leyte Artisan Markets', type: 'Tacloban City' },
      { icon: '[BUILDING]', name: 'Kultura Filipino', type: 'SM Malls' },
      { icon: '[BAG]', name: 'Tesoros', type: 'Intramuros, Manila' },
      { icon: '[BOX]', name: 'Shopee', type: 'Weaver cooperatives' },
      { icon: '[TENT]', name: 'Manila FAME', type: 'Trade fair, Okt annually' },
    ],
    facts: [
      { num: '3×', text: 'Ang Abaca fiber ay tatlong beses na mas malakas kaysa cotton at resist sa saltwater damage.' },
      { num: '80%', text: 'Ng world\'s abaca supply ay galing sa Pilipinas.' },
      { num: '1 week', text: 'Oras para sa isang skilled weaver na makumpleto ng isang medium-sized Bayong bag.' },
      { num: '100%', text: 'Biodegradable — ang abaca ay bumabagay nang natural na may zero environmental harm.' }
    ],
    specs: [
      ['Materyal', 'Natural abaca fiber'],
      ['Dimensions', 'Approx. 35×40×15 cm'],
      ['Handle', 'Braided abaca rope'],
      ['Closure', 'Open top o drawstring'],
      ['Bigat', 'Approx. 400g'],
      ['Pinanggalingan', 'Eastern Visayas'],
    ]
  },
  {
    id: 8, cat: 'fashion', emoji: '[HAT]', badge: 'Sustainable',
    name: 'Salakot Sun Hat', origin: 'Bohol & Pampanga',
    tagLabel: 'Moda', tagClass: 'fashion',
    desc: 'Ang traditional Filipino sun hat, na muling dinisenyo para sa everyday wear. Woven mula sa rattan o bamboo, ang bawat Salakot ay isang sculptural na piraso ng wearable Filipino heritage.',
    overview: 'Ang Salakot ay isa sa mga most recognizable symbols ng Filipino identity. Orihinal na suot ng farmers at warriors, ang artisan-crafted na Salakot ngayon ay nag-bridge ng tradition at fashion — suot ng celebrities, sa festivals, at itinampok sa global fashion editorials.',
    overviewStats: [{ num: '500+', label: 'Taon ng kasaysayan' }, { num: '100%', label: 'Natural materials' }, { num: 'Isang size', label: 'Adjustable fit' }],
    history: [
      { year: '1300s', title: 'Pre-colonial origins', text: 'Ang Salakot hats ay suot ng pre-colonial Filipinos para sa sun protection sa fields at bilang status symbols sa principalia class.' },
      { year: '1800s', title: 'Philippine Revolution symbol', text: 'Suot ng Katipunero fighters, ang Salakot ay naging powerful symbol ng Filipino resistance at national identity.' },
      { year: '1990s', title: 'Fashion reinvention', text: 'Ang mga Filipino fashion designer ay nag-revive ng Salakot bilang isang high-fashion accessory, na suot sa international runways.' },
      { year: '2020s', title: 'Sustainable fashion icon', text: 'Ang Salakot ay nakakuha ng global attention bilang isang zero-waste, sustainable fashion piece na gawa sa renewable materials.' }
    ],
    locations: [
      { icon: '[VILLAGE]', name: 'Pampanga Craft Markets', type: 'San Fernando' },
      { icon: '[BUILDING]', name: 'Kultura Filipino', type: 'SM Malls' },
      { icon: '[TENT]', name: 'Sinulog & Pahiyas Festivals', type: 'Seasonal' },
      { icon: '[BOX]', name: 'Shopee', type: 'Artisan shops online' },
      { icon: '[BAG]', name: 'Tesoros de Manila', type: 'Intramuros' },
    ],
    facts: [
      { num: '3 araw', text: 'Minimum time na i-hand-weave ang isang Salakot mula sa rattan strips.' },
      { num: '1 kg', text: 'Average na bigat — remarkably light para sa structural strength nito.' },
      { num: '2 uri', text: 'Main styles: ang conical farm Salakot at ang ornamental dome-shaped ceremonial version.' },
      { num: '400+', text: 'Taon ng hat-weaving tradition sa mga lalawigan ng Pampanga at Bohol.' }
    ],
    specs: [
      ['Materyal', 'Rattan o bamboo strips'],
      ['Finish', 'Natural lacquer o dyed'],
      ['Brim', '15–20 cm wide'],
      ['Inner Band', 'Adjustable cloth lining'],
      ['Bigat', 'Approx. 800g–1kg'],
      ['Pinanggalingan', 'Bohol & Pampanga'],
    ]
  }
];

/* ─── RENDER PRODUCTS ──────────────────────────────────────────── */
function renderProducts(cat) {
  const grid = document.getElementById('productGrid');
  const filtered = cat === 'all' ? products : products.filter(p => p.cat === cat);
  grid.innerHTML = filtered.map(p => `
    <div class="product-card reveal" onclick="openModal(${p.id})">
      <div class="product-img">
        <div class="product-img-bg"></div>
        <div class="product-img-icon">${p.emoji}</div>
        <div class="product-badge">${p.badge}</div>
      </div>
      <div class="product-body">
        <div class="product-origin">${p.origin}</div>
        <div class="product-name">${p.name}</div>
        <div class="product-desc">${p.desc}</div>
        <div class="product-footer">
          <span class="tag ${p.tagClass}">${p.tagLabel}</span>
          <a class="product-discover" href="#">Tuklasin →</a>
        </div>
      </div>
    </div>
  `).join('');
  observeReveal();
}

function filterCat(cat, el) {
  document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  renderProducts(cat);
}

/* ─── MODAL ─────────────────────────────────────────────────────── */
function openModal(id) {
  const p = products.find(x => x.id === id);
  if (!p) return;

  document.getElementById('modalImg').textContent = p.emoji;
  document.getElementById('modalTag').textContent = p.tagLabel;
  document.getElementById('modalTag').className = `tag ${p.tagClass}`;
  document.getElementById('modalName').textContent = p.name;
  document.getElementById('modalOrigin').textContent = '[PIN] ' + p.origin;
  document.getElementById('modalDesc').textContent = p.desc;

  // Overview
  document.getElementById('panel-overview').innerHTML = `
    <p style="font-size:14px;color:#6B5744;line-height:1.8;margin-bottom:20px;">${p.overview}</p>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
      ${p.overviewStats.map(s => `
        <div style="text-align:center;padding:16px;background:#F5F0E8;border-radius:10px;">
          <div style="font-family:'Playfair Display',serif;font-size:22px;font-weight:600;color:#5C3D2E;">${s.num}</div>
          <div style="font-size:12px;color:#7A6355;margin-top:4px;">${s.label}</div>
        </div>`).join('')}
    </div>`;

  // History
  document.getElementById('panel-history').innerHTML = `
    <div class="timeline">
      ${p.history.map(h => `
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">${h.year}</div>
          <div class="tl-title">${h.title}</div>
          <div class="tl-text">${h.text}</div>
        </div>`).join('')}
    </div>`;

  // Locations
  document.getElementById('panel-locations').innerHTML = `
    <p style="font-size:13px;color:#7A6355;margin-bottom:16px;">Makahanap ng produkto na malapit sa iyo — sa stores at online.</p>
    <div class="loc-grid">
      ${p.locations.map(l => `
        <div class="loc-card">
          <div class="loc-icon">${l.icon}</div>
          <div class="loc-name">${l.name}</div>
          <div class="loc-type">${l.type}</div>
        </div>`).join('')}
    </div>`;

  // Fun Facts
  document.getElementById('panel-funfacts').innerHTML = `
    <div class="facts-grid">
      ${p.facts.map(f => `
        <div class="fact-card">
          <div class="fact-num">${f.num}</div>
          <div class="fact-text">${f.text}</div>
        </div>`).join('')}
    </div>`;

  // Specs
  document.getElementById('panel-specs').innerHTML = `
    <table class="specs-tbl">
      ${p.specs.map(s => `<tr><td>${s[0]}</td><td>${s[1]}</td></tr>`).join('')}
    </table>`;

  // Reset tabs
  document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.modal-panel').forEach(t => t.classList.remove('active'));
  document.querySelector('.modal-tab').classList.add('active');
  document.getElementById('panel-overview').classList.add('active');

  document.getElementById('overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function switchTab(name, el) {
  document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.modal-panel').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('panel-' + name).classList.add('active');
}

function closeModal(e) {
  if (e.target === document.getElementById('overlay')) closeModalDirect();
}
function closeModalDirect() {
  document.getElementById('overlay').classList.remove('open');
  document.body.style.overflow = '';
}

/* ─── SCROLL REVEAL ─────────────────────────────────────────────── */
function observeReveal() {
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) {
        setTimeout(() => e.target.classList.add('visible'), i * 80);
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));
}

/* ─── HAMBURGER ─────────────────────────────────────────────────── */
function toggleMenu() {
  document.getElementById('navLinks').classList.toggle('open');
}

/* ─── INIT ───────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  renderProducts('all');
  document.querySelectorAll('.reveal').forEach(el => observeReveal());
});
</script>
</body>
</html>
