<div class="col-xl-8 col-md-8 py-5 px-md-2">
					<?php if (!$user->isGuest  && !$user->isAdmin) : ?>
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div>
									<a href="<?= $response->getLink('/practice/post-action.php') ?>" class="btn btn-outline-success">📝 Создать пост</a>
								</div>
							</div>
						</div> <?php endif; ?>
					<div class="row pt-md-4">
						<!-- один пост/превью -->
						<?php
						$html = '';
						// var_dump($offset);die;
						foreach ($arr = $post->list($limit, $offset) as $key => $value) {
							$value->formDate();
							$html .= "<div class='col-md-12 col-xl-12'>
							<div class='blog-entry ftco-animate d-md-flex'>";

							$html .= "<div class='text text-2 pl-md-4'>
									<h3 class='mb-2'><a href='" . $response->getLink('post-action.php', ['post-id' => $value->id]) . "'>$value->title</a></h3>
									<div class='meta-wrap'>
										<p class='meta'>
											<!-- <img src='avatar.jpg' /> -->
											<span class='text text-3'>{$value->user->login}</span>
											<span><i class='icon-calendar mr-2'></i>$value->date</span>
											<span><i class='icon-comment2 mr-2'></i>$value->numberOfComment Comment</span>
										</p>
									</div>
									<p class='mb-4'>$value->preview</p>
									<div class='d-flex pt-1  justify-content-between'>
										<div>
											<a href='" . $response->getlink('post.php', ['post-id' => $value->id]) . "' class='btn-custom'>
												Подробнее... <span class='ion-ios-arrow-forward'></span></a>
										</div>";
							if (!$user->isGuest && !$user->isAdmin && $value->user->id === $user->id) {
								// var_dump($value->user->id);die;
								$html .= "<div>
											<a href='" . $response->getLink('post-action.php', ['post-id' => $value->id]) . "' style='font-size: 1.8em;' title='Редактировать'>🖍</a>
											<a href='" . $response->getLink('delete.php', ['post-id' => $value->id]) . "' class = 'text-danger' style='font-size: 1.8em;' title='Удалить'>🗑</a>
											</div>";
							}
							if (!$user->isGuest && $user->isAdmin) {
								// var_dump($value->user->id);die;
								$html .= "<div>
												<a href='" . $response->getLink('delete.php', ['post-id' => $value->id]) . " 'style='font-size: 1.8em;' title='Удалить'>🗑</a>
												</div>";
							}
							$html .= "</div>
										</div>
									</div>
								</div>";
						}; ?>
						<?= $html ?>



					</div>


					<?php
					if (!$user->request->get('page')) {
						$page = 1;
					} else {
						$page = $user->request->get('page');
					}
					$html = '<div class="row">
								<div class="col">
									<div class="block-27">
										<ul>';
					if ($offset !== 0)
						$html .= '	<li><a href="' . $response->getLink('posts.php', ['pageOf' => $page - 2, 'offset' => $offset - 1, 'page' => $page - 1]) . '">&lt;</a></li>';
					foreach ($pages as $value) {
						// var_dump($value);
						if ($page == $value['page']) {
							$html .= '<li class="active"> <a href="' . $response->getLink('posts.php', ['page' => $value['page'], 'offset' => $value['page'] - 1, 'pageOf' => $value['page'] - 1]) . '">' . $value['page'] . '</a></li>';
						} 
						 else {
							$html .= '<li><a href="' . $response->getLink('posts.php', ['page' => $value['page'], 'offset' => $value['page'] - 1, 'pageOf' => $value['page'] - 1]) . '">' . $value['page'] . '</a></li>';
						}
						
					} ?>
					<?= $html ?>
					<?php if ($user->request->get('page') == $count) : ?>
						<li><a href="<?= $response->getLink('posts.php') ?>">&gt;</a></li>
					<?php else : ?>
						<li><a href="<?= $response->getLink('posts.php', ['page' => $page + 1, 'pageOf' => $page,  'offset' => $offset + 1]) ?>">&gt;</a></li>
					<?php endif ?>

					</ul>
				</div>