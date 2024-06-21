<?php if (!$user->isGuest) : ?>
    <div>
        <?php if (!$user->isAdmin && $user->id === $post->user_id) : ?>
            <a href="<?= $response->getLink('/practice/post-action.php') ?>" class="text-warning" style="font-size: 1.8em;" title="Редактировать">🖍</a>
        <?php endif ?>
        <?php if (!$user->isAdmin && $user->id === $post->user_id) :  ?>
            <a href="<?= $response->getLink('/practice/delete.php') ?>" class="text-danger" style="font-size: 1.8em;" title="Удалить">🗑</a>
        <?php endif ?>
    <?php endif ?>