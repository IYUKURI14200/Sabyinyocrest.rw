<?php
require_once __DIR__ . '/includes/bootstrap.php';

$settings   = get_settings();
$rooms      = get_rooms();
$services   = get_services();
$gallery    = get_gallery();
$facilities = get_facilities();
$amenities  = get_amenities();
$heroSlides = get_hero_slides();
if (!$heroSlides) {
    // fall back to the single hero image if no slideshow photos are set
    $heroSlides = [['image' => $settings['hero_image']]];
}

$icons = [
  'bed'   => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 18v-7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v7"/><path d="M3 18h18"/><path d="M3 13v-2a2 2 0 0 1 2-2h4"/><circle cx="7" cy="9" r="1.4" fill="currentColor" stroke="none"/></svg>',
  'glass' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 21h8"/><path d="M12 17v4"/><path d="M6 3h12l-1.5 9a4.5 4.5 0 0 1-9 0L6 3z"/></svg>',
  'users' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3.2"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.5a3.2 3.2 0 0 1 0 6.4"/><path d="M18 14a6.5 6.5 0 0 1 4 6"/></svg>',
  'car'   => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 13l1.5-5A2 2 0 0 1 6.4 6.5h11.2a2 2 0 0 1 1.9 1.5L21 13"/><rect x="2" y="13" width="20" height="5" rx="1.5"/><circle cx="7" cy="18.5" r="1.6" fill="currentColor" stroke="none"/><circle cx="17" cy="18.5" r="1.6" fill="currentColor" stroke="none"/></svg>',
  'wifi'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12.5a11 11 0 0 1 14 0"/><path d="M8.2 16a6.5 6.5 0 0 1 7.6 0"/><circle cx="12" cy="19.5" r="1.2" fill="currentColor" stroke="none"/></svg>',
  'shield'=> '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/></svg>',
];

$wa = preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? '');
$mapQ = urlencode($settings['map_query'] ?: $settings['address']);
$mapEmbedSrc = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3152.344844614883!2d29.634451199999994!3d-1.4900095!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dc5b00640cd103%3A0xd980f13afe671da0!2sSabyinyocrest!5e1!3m2!1sen!2srw!4v1789463653359!5m2!1sen!2srw";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($settings['brand_name']) ?> — <?= e($settings['address']) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Work+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAV -->
<nav id="nav" class="on-hero">
  <div class="container nav-inner">
    <a href="#home" class="brand">
      <span class="brand-mark">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M3 21L9 9L13 15L16 10L21 21H3Z" fill="#F3ECDC"/><circle cx="12" cy="5" r="2" fill="#D6A13C"/></svg>
      </span>
      <span>
        <span class="brand-name" style="display:block;"><?= e($settings['brand_name']) ?></span>
        <span class="brand-sub"><?= e($settings['tagline']) ?></span>
      </span>
    </a>
    <ul class="nav-links" id="navLinks">
      <li><a href="#about">About</a></li>
      <li><a href="#rooms">Rooms</a></li>
      <li><a href="menu.php">Dining</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#gallery">Gallery</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-cta-wrap">
      <a href="booking.php" class="btn btn-clay btn-sm">Book Now</a>
      <button class="nav-toggle" id="navToggle" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>
  </div>
</nav>

<!-- HERO -->
<header class="hero" id="home">
  <?php foreach ($heroSlides as $i => $slide): ?>
    <div class="hero-bg <?= $i===0?'active':'' ?>" style="background-image:url('<?= img_src($slide['image']) ?>')"></div>
  <?php endforeach; ?>
  <div class="hero-scrim"></div>
  <div class="container hero-content">
    <div class="hero-eyebrow">At the foot of the Virunga volcanoes</div>
    <h1><?= $settings['hero_title'] /* trusted admin HTML e.g. <em> */ ?></h1>
    <p><?= e($settings['hero_subtitle']) ?></p>
    <div class="hero-actions">
      <a href="booking.php" class="btn btn-clay">Reserve Your Stay</a>
      <a href="#rooms" class="btn btn-ghost">View Rooms</a>
    </div>
  </div>
  <div class="scroll-cue"><span class="line"></span>SCROLL</div>
  <div class="hero-ridge">
    <svg viewBox="0 0 1440 90" width="100%" height="90" preserveAspectRatio="none"><path d="M0,90 L0,60 L160,20 L230,45 L340,5 L430,50 L560,15 L660,55 L760,25 L880,60 L980,30 L1100,58 L1220,18 L1320,48 L1440,25 L1440,90 Z" fill="#F3ECDC"/></svg>
  </div>
</header>

<!-- WELCOME -->
<section class="welcome-wrap" id="about">
  <div class="container">
    <div class="welcome-card reveal">
      <div class="eyebrow" style="justify-content:center;">Welcome</div>
      <h2>Welcome to <span class="accent"><?= e($settings['brand_name']) ?></span></h2>
      <div class="welcome-divider"></div>
      <p><?= e($settings['welcome_body1']) ?></p>
      <p><?= e($settings['welcome_body2']) ?></p>
      <div class="welcome-stats">
        <div class="wstat"><b>20 Minutes</b><span>From Volcanoes NP</span></div>
        <div class="wstat"><b>24/7</b><span>Security</span></div>
        
      </div>
      <div style="margin-top:30px;">
        <a href="#rooms" class="btn btn-outline-dark">Explore the Sabyinyo Crest</a>
      </div>
    </div>
  </div>
</section>

<?php if ($settings['video_enabled']): ?>
<!-- FEATURED VIDEO -->
<section class="video-section">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow" style="justify-content:center;">In motion</div>
      <h2><?= e($settings['video_title']) ?></h2>
      <p><?= e($settings['video_caption']) ?></p>
    </div>
    <div class="video-frame-wrap reveal">
      <div class="video-badge"><span class="dot"></span> Now Playing</div>
      <div class="vf-corner-tl"></div>
      <div class="vf-corner-tr"></div>
      <div class="vf-corner-bl"></div>
      <div class="vf-corner-br"></div>
      <div class="video-frame">
        <?php if ($settings['video_source'] === 'youtube' && $settings['video_youtube_id']): ?>
          <iframe
            src="https://www.youtube.com/embed/<?= e($settings['video_youtube_id']) ?>?autoplay=1&mute=1&loop=1&playlist=<?= e($settings['video_youtube_id']) ?>&controls=1&rel=0&playsinline=1"
            title="<?= e($settings['video_title']) ?>"
            allow="autoplay; encrypted-media; picture-in-picture"
            allowfullscreen loading="lazy"></iframe>
        <?php elseif ($settings['video_file']): ?>
          <video controls autoplay muted loop playsinline preload="auto" poster="<?= img_src($settings['hero_image']) ?>">
            <source src="<?= e($settings['video_file']) ?>" type="video/mp4">
            Your browser doesn't support embedded video.
          </video>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ROOMS -->
<section class="section" id="rooms" style="background:var(--sand);">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow" style="justify-content:center;">Stay</div>
      <h2>Our  Rooms</h2>
      <p>Every room comes with breakfast, daily housekeeping, a private bathroom and free Wi-Fi.</p>
    </div>
    <div class="rooms-grid">
      <?php foreach ($rooms as $r): ?>
      <div class="room-card reveal">
        <div class="room-media">
          <img src="<?= img_src($r['image']) ?>" alt="<?= e($r['name']) ?>">
          <div class="room-price-tag"><b>$<?= number_format((float)$r['price'], 0) ?></b>/night</div>
        </div>
        <div class="room-body">
          <h3><?= e($r['name']) ?></h3>
          <p><?= e($r['description']) ?></p>
          <div class="room-actions">
            <a href="booking.php?room=<?= (int)$r['id'] ?>" class="btn btn-outline-dark btn-sm">View Details</a>
            <a href="booking.php?room=<?= (int)$r['id'] ?>" class="btn btn-clay btn-sm">Book Now</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="section" id="dining">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow" style="justify-content:center;">On the property</div>
      <h2>Our Services</h2>
      <p>World-class facilities for your comfort and convenience.</p>
    </div>
    <div class="services-grid reveal" id="services">
      <?php foreach ($services as $s): ?>
      <div class="service-card">
        <div class="service-icon"><?= $icons[$s['icon']] ?? $icons['bed'] ?></div>
        <h3><?= e($s['title']) ?></h3>
        <p><?= e($s['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center; margin-top:44px;">
      <a href="menu.php" class="btn btn-clay">View Full Dining Menu</a>
    </div>
  </div>
</section>

<!-- QUOTE BANNER -->
<section class="quote-banner">
  <div class="quote-bg" style="background-image:url('<?= img_src($settings['testimonial_image']) ?>')"></div>
  <div class="quote-scrim"></div>
  <span class="quote-mark">&#8220;</span>
  <blockquote><?= e($settings['testimonial_quote']) ?></blockquote>
</section>

<!-- GALLERY -->
<section class="section" id="gallery" style="background:var(--sand);">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow" style="justify-content:center;">Take a look</div>
      <h2>Gallery</h2>
      <p>A closer look at our rooms, lounges, and grounds.</p>
    </div>
    <div class="gallery-grid">
      <?php foreach ($gallery as $g): ?>
      <div class="gallery-item reveal" data-lightbox="<?= img_src($g['image']) ?>">
        <img src="<?= img_src($g['image']) ?>" alt="<?= e($g['caption']) ?>" loading="lazy">
        <span class="gallery-cap"><?= e($g['caption']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section class="contact-section" id="contact">
  <div class="container">
    <div class="section-head left reveal" style="max-width:600px;">
      <div class="eyebrow">Booking options</div>
      <h2>Reserve Your Perfect Room</h2>
      <p>Reserve today and experience quiet, comfortable hospitality at the foot of the volcanoes.</p>
    </div>
    <div class="contact-grid reveal">
      <div class="map-wrap">
        <iframe loading="lazy" allowfullscreen src="<?= e($mapEmbedSrc) ?>" referrerpolicy="strict-origin-when-cross-origin"></iframe>
      </div>
      <div class="contact-card">
        <h3>Get in touch</h3>
        <p>Our team usually replies within minutes — reach out however suits you best.</p>
        <div class="contact-btns">
          <a href="booking.php" class="cbtn" style="background:var(--clay); color:#fff;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            Book Online
          </a>
          <a href="https://www.booking.com/hotel/rw/sabyinyo-crest.html" class="cbtn" target="_blank" rel="noopener" style="background:#003580; color:#fff;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.2l7.8 3.9L12 12l-7.8-3.9L12 4.2zM4 9.1l7 3.5v7.3l-7-3.5V9.1zm9 10.8v-7.3l7-3.5v7.3l-7 3.5z"/></svg>
            🗓️Book on Booking.com
          </a>
          <a href="https://wa.me/<?= e($wa) ?>" class="cbtn cbtn-whatsapp" target="_blank" rel="noopener">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.44 1.32 4.94L2 22l5.29-1.39a9.9 9.9 0 0 0 4.75 1.21h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2z"/></svg>
            WhatsApp Us
          </a>
          <a href="tel:<?= e(preg_replace('/\s/', '', $settings['reception_phone'])) ?>" class="cbtn cbtn-primary">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            Call Reception
          </a>
          <a href="mailto:<?= e($settings['email']) ?>" class="cbtn cbtn-outline">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
            Email Us
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-ridge">
    <svg viewBox="0 0 1440 60" width="100%" height="60" preserveAspectRatio="none"><path d="M0,60 L0,40 L160,10 L260,32 L360,4 L470,34 L580,8 L700,38 L820,14 L950,40 L1080,18 L1220,36 L1320,10 L1440,30 L1440,60 Z" fill="var(--ink)"/></svg>
  </div>
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-brand">
          <span class="brand-mark"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 21L9 9L13 15L16 10L21 21H3Z" fill="#F3ECDC"/><circle cx="12" cy="5" r="2" fill="#D6A13C"/></svg></span>
          <span class="brand-name"><?= e($settings['brand_name']) ?></span>
        </div>
        <p style="font-size:14px; max-width:280px; margin-bottom:8px;"><?= e($settings['footer_blurb']) ?></p>
        <div style="font-size:13.5px; margin-top:14px;"><?= (int)$settings['reviews_count'] ?> Reviews &nbsp;|&nbsp; <a href="<?= e($settings['reviews_url']) ?>" target="_blank" rel="noopener">View All Reviews</a></div>
        <div class="social-row">
          <a href="<?= e($settings['facebook']) ?>" target="_blank" rel="noopener"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7.5h2.5l.4-3H13.5V8.4c0-.87.24-1.46 1.5-1.46h1.6V4.3C16.3 4.2 15.3 4 14.2 4c-2.3 0-3.9 1.4-3.9 4v2.5H7.8v3h2.5V21h3.2z"/></svg></a>
          <a href="<?= e($settings['twitter']) ?>" target="_blank" rel="noopener"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.9c-.7.3-1.5.5-2.3.6.8-.5 1.4-1.3 1.7-2.3-.8.5-1.7.8-2.6 1a4.1 4.1 0 0 0-7 3.7A11.6 11.6 0 0 1 3.4 4.8a4.1 4.1 0 0 0 1.3 5.5c-.6 0-1.3-.2-1.8-.5v.1c0 2 1.4 3.6 3.3 4a4.2 4.2 0 0 1-1.8.1 4.1 4.1 0 0 0 3.9 2.9A8.3 8.3 0 0 1 2 18.6a11.6 11.6 0 0 0 6.3 1.8c7.5 0 11.7-6.3 11.7-11.7v-.5c.8-.6 1.5-1.3 2-2.1z"/></svg></a>
          <a href="<?= e($settings['instagram']) ?>" target="_blank" rel="noopener"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></a>
        </div>
      </div>
      <div>
        <h4>Our Facilities</h4>
        <ul><?php foreach ($facilities as $f): ?><li><?= e($f['label']) ?></li><?php endforeach; ?></ul>
      </div>
      <div>
        <h4>General Amenities</h4>
        <ul><?php foreach ($amenities as $a): ?><li><?= e($a['label']) ?></li><?php endforeach; ?></ul>
      </div>
      <div>
        <h4>Contact Us</h4>
        <ul class="footer-contact">
          <li> <span>Reception: <?= e($settings['reception_phone']) ?></span></li>
          <li> <span>Manager: <?= e($settings['manager_phone']) ?></span></li>
          <li> <span><?= e($settings['email']) ?></span></li>
          <li>📍 <span><?= e($settings['address']) ?></span></li>
        </ul>
        <a href="booking.php" class="btn btn-clay btn-sm" style="margin-top:20px;">Book Now</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= date('Y') ?> <?= e($settings['brand_name']) ?>. All rights reserved.</span>
      <a href="admin/login.php" class="admin-lock" title="Site admin" style="text-decoration:none;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </a>
    </div>
  </div>
</footer>

<button class="to-top" id="toTop" aria-label="Scroll to top">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
</button>

<div id="lightbox"><button class="lb-close" id="lbClose">✕</button><img id="lbImg" src="" alt=""></div>

<script src="assets/js/main.js"></script>
</body>
</html>
