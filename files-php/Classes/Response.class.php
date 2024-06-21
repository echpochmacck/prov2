<?php
class Response
{

    public object $user;
    public object $post;


    public  function __construct($user, $post)
    {
        $this->user = $user;
        $this->post = $post;
        if (!$this->user->id && $this->user->token) {
            $this->redirect('/pracrice/');
        }
    }


    public function getLink(string $url, array $params = []): string
    {
        if ($this->user->id && !array_key_exists('token', $params)) {
            $params['token'] = $this->user->token;
            // if ($this->post->id && !array_key_exists('post-id', $params)) {
            //     $params['post-id'] = $this->post->id;
            // }
            // array_merge();
        }    

        if (!str_contains($url, '?') && !empty($params)) {
            return  $url . self::prepareParams($params);
        }

        return $url;
    }

    public function redirect(string $url = '', array $params = []): void
    {
        header('Location: ' . self::getLink($url, $params));
        exit;
    }

    public static function prepareParams(mixed $params = false): string
    {
        $result = '';
        if ($params) {
            if (is_string($params)) {
                $result = $params;
            } else {

                $result = implode('&', array_map(
                    fn ($key, $el) => "$key=$el",
                    array_keys($params),
                    array_values($params)

                ));
            }
        }

        return   $result ? '?' . $result : '';
    }

    public static function location(string $script, string $param): void
    {
        header('Location: ' . $script . $param);
        exit;
    }
}
