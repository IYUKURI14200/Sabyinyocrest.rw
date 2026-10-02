<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();

$settings = get_settings();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $enabled = !empty($_POST['video_enabled']) ? 1 : 0;
    $source  = ($_POST['video_source'] ?? 'upload') === 'youtube' ? 'youtube' : 'upload';
    $title   = trim($_POST['video_title'] ?? '');
    $caption = trim($_POST['video_caption'] ?? '');

    $videoFile = $settings['video_file'];
    $youtubeId = $settings['video_youtube_id'];

    if ($source === 'upload') {
        try {
            $uploaded = handle_video_upload('video_file', 'site');
            if ($uploaded) $videoFile = $uploaded;
        } catch (RuntimeException $ex) {
            $errors[] = $ex->getMessage();
        }
        if ($enabled && !$videoFile) {
            $errors[] = 'Please upload a video file, or switch to a YouTube link.';
        }
    } else {
        $rawUrl = trim($_POST['video_youtube_url'] ?? '');
        $parsedId = parse_youtube_id($rawUrl);
        if ($enabled && !$parsedId) {
            $errors[] = 'That doesn\'t look like a valid YouTube link. Paste the full video URL, e.g. https://www.youtube.com/watch?v=XXXXXXXXXXX';
        } elseif ($parsedId) {
            $youtubeId = $parsedId;
        }
    }

    if (!$errors) {
        $stmt = db()->prepare('UPDATE settings SET video_enabled=?, video_source=?, video_file=?, video_youtube_id=?, video_title=?, video_caption=? WHERE id=1');
        $stmt->execute([$enabled, $source, $videoFile, $youtubeId, $title, $caption]);
        flash_set('Video section updated.');
        redirect('video.php');
    }
    $settings = array_merge($settings, [
        'video_enabled' => $enabled, 'video_source' => $source, 'video_file' => $videoFile,
        'video_youtube_id' => $youtubeId, 'video_title' => $title, 'video_caption' => $caption,
    ]);
}

$maxVideoMb = round(VIDEO_MAX_UPLOAD_BYTES / 1024 / 1024);
$activeTab = 'video';
require __DIR__ . '/includes/header.php';
?>
<div class="admin-topbar"><h1>Featured Video</h1></div>

<?php if ($errors): ?>
  <div class="alert error"><?= e(implode(' ', $errors)) ?></div>
<?php endif; ?>

<div class="admin-card">
  <p style="color:var(--ink-soft); font-size:14.5px; margin-bottom:22px;">
    A short video shown right under the homepage photo slideshow — great for a welcome message,
    a quick property tour, or a clip featuring your guests. You can either upload a video file
    directly, or paste a YouTube link (recommended for longer or higher-quality videos, since it
    won't slow down your site).
  </p>

  <form method="post" enctype="multipart/form-data" class="admin-form" id="videoForm">
    <?= csrf_field() ?>

    <div class="field" style="display:flex; align-items:center; gap:10px; max-width:none;">
      <input type="checkbox" id="video_enabled" name="video_enabled" value="1" style="width:auto;" <?= $settings['video_enabled']?'checked':'' ?>>
      <label for="video_enabled" style="margin:0; text-transform:none; font-size:14.5px; color:var(--ink);">Show the video section on the homepage</label>
    </div>

    <div class="field">
      <label>Video Source</label>
      <select name="video_source" id="video_source">
        <option value="upload" <?= $settings['video_source']==='upload'?'selected':'' ?>>Upload a video file</option>
        <option value="youtube" <?= $settings['video_source']==='youtube'?'selected':'' ?>>YouTube link</option>
      </select>
    </div>

    <div id="uploadFields" style="display:<?= $settings['video_source']==='upload'?'block':'none' ?>;">
      <div class="field">
        <label>Video File <span style="text-transform:none; font-weight:400;">(MP4, WebM, or MOV — up to <?= $maxVideoMb ?>MB)</span></label>
        <?php if ($settings['video_file']): ?>
          <video class="preview" controls style="width:220px; height:150px; margin-bottom:10px;">
            <source src="../<?= e($settings['video_file']) ?>" type="video/mp4">
          </video><br>
        <?php endif; ?>
        <input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime,video/ogg">
      </div>
    </div>

    <div id="youtubeFields" style="display:<?= $settings['video_source']==='youtube'?'block':'none' ?>;">
      <div class="field">
        <label>YouTube URL</label>
        <input type="text" name="video_youtube_url" placeholder="https://www.youtube.com/watch?v=..."
          value="<?= $settings['video_youtube_id'] ? 'https://www.youtube.com/watch?v='.e($settings['video_youtube_id']) : '' ?>">
      </div>
    </div>

    <div class="field">
      <label>Title</label>
      <input type="text" name="video_title" value="<?= e($settings['video_title']) ?>">
    </div>
    <div class="field">
      <label>Caption</label>
      <textarea name="video_caption" rows="2"><?= e($settings['video_caption']) ?></textarea>
    </div>

    <button type="submit" class="abtn primary" style="padding:12px 22px;">Save Video Settings</button>
  </form>
</div>

<script>
  document.getElementById('video_source').addEventListener('change', function(){
    document.getElementById('uploadFields').style.display = this.value === 'upload' ? 'block' : 'none';
    document.getElementById('youtubeFields').style.display = this.value === 'youtube' ? 'block' : 'none';
  });
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
