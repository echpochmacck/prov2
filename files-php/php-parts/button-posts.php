<?php if (!$user->isGuest  && !$user->isAdmin) : ?>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div>
                <a href="<?= $response->getLink('/practice/post-action.php') ?>" class="btn btn-outline-success">📝 Создать пост</a>
            </div>
        </div>
    </div> <?php endif; ?>