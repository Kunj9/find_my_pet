<?php
?>
<div class="container" id="flash">
    <?php $messages = showMessages();
    ?>
    <?php if ($messages) : ?>
        <?php foreach ($messages as $msg) : ?>
            <div class="alert alert-<?php echo isset($msg['color']) ? $msg['color'] : 'info'; ?>" role="alert">
  <?php 
  $imgSrc = '';
  switch ($msg['color']) {
    case 'success':
      $imgSrc = '../media/flashMessages_img/success-icon.png';
      break;
    case 'info':
      $imgSrc = '../media/flashMessages_img/info-icon.png';
      break;
    case 'error':
      $imgSrc = '../media/flashMessages_img/error-icon.png';
      break;
}
  ?>
  <img src="<?php echo $imgSrc; ?>" alt="Alert Icon" class="alert-icon">
  <span><?php echo isset($msg['text']) ? $msg['text'] : ''; ?></span>
</div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<script>
    function display(ele) {
        let element = document.getElementsByTagName("nav")[0];
        if (element) {
            element.after(ele);
        }
    }

    display(document.getElementById("flash"));
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../partials-css/flash.css">
</head>
</html>