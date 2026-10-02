<?php
require_once __DIR__ . '/includes/bootstrap.php';

$settings = get_settings();
$breakfast = get_menu('breakfast');
$lunchDinner = get_menu('lunch_dinner');
$diningSlides = get_dining_slides();
if (!$diningSlides) {
    $diningSlides = [['image' => $settings['dining_hero_image']]];
}

function render_menu_categories(array $categories): void {
    foreach ($categories as $cat) {
        if (!$cat['items']) continue;
        ?>
        <div class="menu-cat">
          <div class="menu-cat-head">
            <span class="icon"><?= e($cat['icon']) ?></span>
            <h2><?= e($cat['name']) ?></h2>
          </div>
          <div class="menu-cat-rule"></div>
          <?php foreach ($cat['items'] as $item): ?>
            <div class="menu-item">
              <div class="menu-item-top">
                <span class="menu-item-name"><?= e($item['name']) ?></span>
                <?php if ($item['price'] !== null): ?><span class="menu-item-leader"></span><?php endif; ?>
                <?php if ($item['price'] !== null): ?>
                  <span class="menu-item-price">$<?= number_format((float)$item['price'], 2) ?></span>
                <?php endif; ?>
              </div>
              <?php if ($item['description']): ?><div class="menu-item-desc"><?= e($item['description']) ?></div><?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
        <?php
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Menu — <?= e($settings['brand_name']) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Work+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
  .menu-hero{
    background:var(--ink); color:var(--paper); padding:170px 24px 90px; text-align:center; position:relative; overflow:hidden; min-height:480px;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
  }
  .menu-hero-bg{
    position:absolute; inset:0; background-size:cover; background-position:center;
    opacity:0; transition:opacity 1.6s ease-in-out;
    animation:heroZoom 20s ease-in-out infinite alternate;
  }
  .menu-hero-bg.active{opacity:1; z-index:1;}
  .menu-hero-scrim{
    position:absolute; inset:0; z-index:2;
    background:linear-gradient(180deg, rgba(20,24,18,0.38) 0%, rgba(20,24,18,0.22) 45%, rgba(20,24,18,0.68) 100%);
  }
  .menu-hero::before{
    content:""; position:absolute; inset:0; z-index:3;
    background:radial-gradient(circle at 20% 25%, rgba(214,161,60,0.22), transparent 50%),
               radial-gradient(circle at 82% 75%, rgba(183,84,50,0.22), transparent 50%);
  }
  .menu-hero > *:not(.menu-hero-bg):not(.menu-hero-scrim){position:relative; z-index:4;}
  .menu-hero h1{
    font-size:clamp(38px,5.5vw,60px); color:var(--paper);
    text-shadow:0 4px 30px rgba(0,0,0,0.45);
  }
  .menu-hero-divider{
    width:64px; height:3px; margin:22px auto; border-radius:2px;
    background:linear-gradient(90deg, var(--marigold), var(--clay));
    box-shadow:0 2px 12px rgba(214,161,60,0.5);
  }
  .menu-hero p{color:rgba(251,248,241,0.85); max-width:540px; margin:0 auto; font-size:16px; text-shadow:0 2px 12px rgba(0,0,0,0.4);}
  .menu-hero .eyebrow{color:var(--marigold); text-shadow:0 2px 10px rgba(0,0,0,0.4);}
  .menu-hero .eyebrow::before{background:var(--marigold);}

  .menu-tabs{display:flex; justify-content:center; gap:10px; margin:-28px auto 0; position:relative; z-index:5; padding:0 24px;}
  .menu-tab{
    font-family:'JetBrains Mono', monospace; font-size:12.5px; letter-spacing:0.08em; text-transform:uppercase;
    padding:14px 26px; border-radius:999px; border:1.5px solid var(--line); background:var(--paper); color:var(--ink);
    cursor:pointer; box-shadow:var(--shadow); transition:.2s;
  }
  .menu-tab.active{background:var(--canopy); color:#fff; border-color:var(--canopy);}

  .menu-body{max-width:760px; margin:0 auto; padding:80px 24px 110px;}
  .menu-panel{display:none;}
  .menu-panel.active{display:block;}

  /* ---- Classic menu card styling ---- */
  .menu-cat{margin-bottom:64px;}
  .menu-cat-head{text-align:center; margin-bottom:8px;}
  .menu-cat-head .icon{font-size:28px; display:block; margin-bottom:6px;}
  .menu-cat-head h2{
    font-size:24px; letter-spacing:0.06em; text-transform:uppercase; font-weight:600;
    display:inline-flex; align-items:center; gap:16px;
  }
  .menu-cat-head h2::before, .menu-cat-head h2::after{
    content:""; width:36px; height:2px; background:linear-gradient(90deg, transparent, var(--clay));
  }
  .menu-cat-head h2::after{background:linear-gradient(90deg, var(--clay), transparent);}
  .menu-cat-rule{
    width:90px; height:1px; margin:16px auto 30px;
    background:linear-gradient(90deg, transparent, var(--marigold), transparent);
  }

  .menu-item{padding:16px 0; border-bottom:1px solid var(--line);}
  .menu-item:last-child{border-bottom:none;}
  .menu-item-top{display:flex; align-items:baseline; gap:10px;}
  .menu-item-name{
    font-family:'Fraunces', serif; font-size:19.5px; font-weight:700; color:var(--ink);
    letter-spacing:0.01em; white-space:nowrap;
  }
  .menu-item-leader{flex:1; border-bottom:2px dotted var(--line); transform:translateY(-4px); min-width:20px;}
  .menu-item-price{
    font-family:'Fraunces', serif; font-size:18px; font-weight:600; color:var(--clay);
    white-space:nowrap; flex-shrink:0;
  }
  .menu-item-desc{color:var(--ink-soft); font-size:14.5px; margin-top:5px; font-style:italic; max-width:560px; line-height:1.5;}

  .menu-cta{text-align:center; margin-top:20px;}
</style>
</head>
<body>

<nav id="nav" class="scrolled">
  <div class="container nav-inner">
    <a href="index.php" class="brand">
      <span class="brand-mark"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M3 21L9 9L13 15L16 10L21 21H3Z" fill="#F3ECDC"/><circle cx="12" cy="5" r="2" fill="#D6A13C"/></svg></span>
      <span><span class="brand-name" style="display:block;"><?= e($settings['brand_name']) ?></span><span class="brand-sub"><?= e($settings['tagline']) ?></span></span>
    </a>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.php#about">About</a></li>
      <li><a href="index.php#rooms">Rooms</a></li>
      <li><a href="menu.php">Dining</a></li>
      <li><a href="index.php#gallery">Gallery</a></li>
      <li><a href="index.php#contact">Contact</a></li>
    </ul>
    <div class="nav-cta-wrap">
      <a href="index.php" class="btn btn-outline-dark btn-sm">← Back to site</a>
      <button class="nav-toggle" id="navToggle" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>
  </div>
</nav>

<header class="menu-hero">
  <?php foreach ($diningSlides as $i => $slide): ?>
    <div class="menu-hero-bg <?= $i===0?'active':'' ?>" style="background-image:url('<?= img_src($slide['image']) ?>')"></div>
  <?php endforeach; ?>
  <div class="menu-hero-scrim"></div>
  <div class="eyebrow" style="justify-content:center;">Dining at <?= e($settings['brand_name']) ?></div>
  <h1>Our Menu</h1>
  <div class="menu-hero-divider"></div>
  <p>Fresh, local ingredients — from Rwandan classics to comfort-food favourites. All meals are prepared to order in our kitchen.</p>
</header>

<div class="menu-tabs">
  <button class="menu-tab active" data-panel="lunch">Lunch &amp; Dinner</button>
  <button class="menu-tab" data-panel="breakfast">Breakfast</button>
</div>

<div class="menu-body">
  <div class="menu-panel active" id="panel-lunch">
    <?php if (!$lunchDinner): ?>
      <p style="text-align:center; color:var(--ink-soft);">The lunch & dinner menu isn't published yet — check back soon.</p>
    <?php endif; ?>
    <?php render_menu_categories($lunchDinner); ?>
  </div>

  <div class="menu-panel" id="panel-breakfast">
    <?php if (!$breakfast): ?>
      <p style="text-align:center; color:var(--ink-soft);">The breakfast menu isn't published yet — check back soon.</p>
    <?php endif; ?>
    <?php render_menu_categories($breakfast); ?>
  </div>

  <div class="menu-cta">
    <a href="booking.php" class="btn btn-clay">Reserve a Table or Room</a>
  </div>
</div>

<script src="assets/js/main.js"></script>
<script>
  document.querySelectorAll('.menu-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.menu-tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.menu-panel').forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      document.getElementById('panel-' + tab.dataset.panel).classList.add('active');
    });
  });
</script>
</body>
</html>
