<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$settings = get_settings();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $fields = [
        'brand_name'        => trim($_POST['brand_name'] ?? ''),
        'tagline'           => trim($_POST['tagline'] ?? ''),
        'hero_title'        => trim($_POST['hero_title'] ?? ''),
        'hero_subtitle'     => trim($_POST['hero_subtitle'] ?? ''),
        'welcome_body1'     => trim($_POST['welcome_body1'] ?? ''),
        'welcome_body2'     => trim($_POST['welcome_body2'] ?? ''),
        'testimonial_quote' => trim($_POST['testimonial_quote'] ?? ''),
        'footer_blurb'      => trim($_POST['footer_blurb'] ?? ''),
    ];
    if ($fields['brand_name'] === '') $errors[] = 'Hotel name is required.';

    $heroImage = $settings['hero_image'];
    $quoteImage = $settings['testimonial_image'];
    try {
        $up1 = handle_upload('hero_image', 'site');
        if ($up1) $heroImage = $up1;
        $up2 = handle_upload('testimonial_image', 'site');
        if ($up2) $quoteImage = $up2;
    } catch (RuntimeException $ex) {
        $errors[] = $ex->getMessage();
    }

    if (!$errors) {
        $stmt = db()->prepare('UPDATE settings SET brand_name=?, tagline=?, hero_title=?, hero_subtitle=?, hero_image=?, welcome_body1=?, welcome_body2=?, testimonial_quote=?, testimonial_image=?, footer_blurb=? WHERE id=1');
        $stmt->execute([
            $fields['brand_name'], $fields['tagline'], $fields['hero_title'], $fields['hero_subtitle'], $heroImage,
            $fields['welcome_body1'], $fields['welcome_body2'], $fields['testimonial_quote'], $quoteImage, $fields['footer_blurb'],
        ]);
        flash_set('Site content updated.');
        redirect('content.php');
    }
    $settings = array_merge($settings, $fields, ['hero_image'=>$heroImage, 'testimonial_image'=>$quoteImage]);
}

$activeTab = 'content';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar"><h1>Site Content</h1></div>

<?php if ($errors): ?>
  <div class="alert error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="admin-form">
  <?= csrf_field() ?>

  <div class="admin-card">
    <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Brand</h3>
    <div class="form-row">
      <div class="field"><label>Hotel Name</label><input type="text" name="brand_name" value="<?= e($settings['brand_name']) ?>" required></div>
      <div class="field"><label>Tagline (shown under name in the nav)</label><input type="text" name="tagline" value="<?= e($settings['tagline']) ?>"></div>
    </div>
  </div>

  <div class="admin-card">
    <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Hero Section</h3>
    <div class="field">
      <label>Background Photo</label>
      <img id="heroPreview" class="preview" src="../<?= img_src($settings['hero_image']) ?>">
      <input type="file" name="hero_image" accept="image/jpeg,image/png,image/webp" data-preview="heroPreview">
    </div>
    <div class="field">
      <label>Headline (you can use &lt;em&gt;text&lt;/em&gt; for italic emphasis)</label>
      <input type="text" name="hero_title" value="<?= e($settings['hero_title']) ?>">
    </div>
    <div class="field">
      <label>Subtitle</label>
      <textarea name="hero_subtitle" rows="3"><?= e($settings['hero_subtitle']) ?></textarea>
    </div>
  </div>

  <div class="admin-card">
    <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Welcome Section</h3>
    <div class="field"><label>Paragraph 1</label><textarea name="welcome_body1" rows="3"><?= e($settings['welcome_body1']) ?></textarea></div>
    <div class="field"><label>Paragraph 2</label><textarea name="welcome_body2" rows="3"><?= e($settings['welcome_body2']) ?></textarea></div>
  </div>

  <div class="admin-card">
    <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Testimonial Banner</h3>
    <div class="field">
      <label>Background Photo</label>
      <img id="quotePreview" class="preview" src="../<?= img_src($settings['testimonial_image']) ?>">
      <input type="file" name="testimonial_image" accept="image/jpeg,image/png,image/webp" data-preview="quotePreview">
    </div>
    <div class="field"><label>Quote</label><textarea name="testimonial_quote" rows="2"><?= e($settings['testimonial_quote']) ?></textarea></div>
  </div>

  <div class="admin-card">
    <h3 style="font-family:'Fraunces',serif; font-size:19px; margin-bottom:16px;">Footer</h3>
    <div class="field"><label>Footer Blurb</label><textarea name="footer_blurb" rows="3"><?= e($settings['footer_blurb']) ?></textarea></div>
  </div>

  <button type="submit" class="abtn primary" style="padding:13px 26px; font-size:14.5px;">Save All Content Changes</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>
