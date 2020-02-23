<div class="message-box">

    <p class="message-box__description margin-y">
        <?php if ( isset($data['error']) ) : ?>
            <?= $data['error'] ?>
        <?php else : ?>
            An error occured
        <?php endif; ?>
    </p>

    <a href="/" class="button button--large button--orange">Back to start</a>

</div>