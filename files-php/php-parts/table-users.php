<div class="container">
            <div class="row block-9">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <h3 class="h3">Пользователи</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Surname</th>
                                        <th scope="col">Login</th>

                                        <th scope="col">E-mail</th>
                                        <th scope="col">Temp block</th>
                                        <th scope="col">Permanent block</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									$html = '';
									// var_dump($superUser->listOfUsers());
									foreach ($superUser->listOfUsers() as $value) {
										$html .= "<tr>
										<th scope='row'>1</th>
										<td>{$value['name']}</td>
										<td>{$value['surname']}</td>
										<td>{$value['login']}</td>
										<td>{$value['email']}</td>"
										;
										if ($user->id === $value['id']) {
											$html .= "<td>
											себя нельзя блокировать 
										</td>
										<td> </td>";
										} else if (!$value['dateUnlock'] || ((strtotime($value['dateUnlock']) < strtotime(date('Y-m-d H:i:s')) && $value['dateUnlock'] !== '1970-01-01 00:00:00'))) {
											$html .= "<td>
											<a href = " . $response->getLink('temp-block.php', ['user_id' => $value['id'], 'loogin' => $value['login']]) . " class='btn btn-outline-warning px-4'>⏳ Block</a>
										</td>
										<td>
											<a href=" . $response->getLink('forever-block.php', ['user_id' => $value['id'] ,'loogin' => $value['login']]) . " class='btn btn-outline-danger px-4'>📌 Block</a>
										</td>";
										} else if( $value['dateUnlock'] === '1970-01-01 00:00:00') {
											$html .= "<td>
											заблокирован навсегда
										</td><td> </td>";
										}else {
											$html .= "<td>
											заблокирован до {$value['dateUnlock']}
										</td><td> </td>";
										}
									}
									echo $html;
									?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>