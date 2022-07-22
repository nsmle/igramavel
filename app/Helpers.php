<?php

if (!function_exists('get_all_api_endpoint')) {
    function get_all_api_endpoint() {
        $igramHeadersCredentials = [
            'Authorization' => "Bearer <token>"
        ];
        
        $igramEndpoint = [
            'get_all_endpoint' => [
                'url'    => url('/api'),
                'method' => 'GET'
            ],
            'login_and_get_token' => [
                'url'      => url('/api/auth/login'),
                'method'   => 'POST',
                'body'     => [
                    'username' => 'YOUR_INSTAGRAM_USERNAME',
                    'password' => 'YOUR_INSTAGRAM_PASSWORD'
                ]
            ],
            'get_profile_self' => [
                'url'      => url('/api/profile'),
                'method'   => 'GET',
                'headers'  => $igramHeadersCredentials,
            ],
            'get_profile_by_id_or_username' => [
                'url'      => url('/api/profile/{:id}'),
                'method'   => 'GET',
                'headers'  => $igramHeadersCredentials,
            ],
            'get_profile_followers' => [
                'url'      => url('/api/profile/{:id|:username}/followers'),
                'method'   => 'GET',
                'headers'  => $igramHeadersCredentials,
            ],
            'get_profile_followings' => [
                'url'      => url('/api/profile/{:id|:username}/followings'),
                'method'   => 'GET',
                'headers'  => $igramHeadersCredentials,
            ],
            'get_profile_next_followers' => [
                'url'      => url('/api/profile/{:id|:username}/followers/{endCursor}'),
                'method'   => 'GET',
                'headers'  => $igramHeadersCredentials,
            ],
            'get_profile_next_followings' => [
                'url'      => url('/api/profile/{:id|:username}/followings/{endCursor}'),
                'method'   => 'GET',
                'headers'  => $igramHeadersCredentials,
            ],
            'follow_or_unfollow_user' => [
                'url'      => url('/api/profile/{:id|:username}/follow'),
                'method'   => 'POST',
                'headers'  => $igramHeadersCredentials,
            ],
            'follow_or_unfollow_user' => [
                'url'      => url('/api/profile/{:id|:username}/unfollow'),
                'method'   => 'POST',
                'headers'  => $igramHeadersCredentials,
            ],
            'get_post' => [
                'url'      => url('/api/post/{:postId}'),
                'method'   => 'GET',
                'headers'  => $igramHeadersCredentials,
            ],
            'like_post' => [
                'url'      => url('/api/post/{:postId}/like'),
                'method'   => 'POST',
                'headers'  => $igramHeadersCredentials,
            ],
            'unlike_post' => [
                'url'      => url('/api/post/{:postId}/unlike'),
                'method'   => 'POST',
                'headers'  => $igramHeadersCredentials,
            ],
            'like_post_by_url' => [
                'url'      => url('/api/post/like'),
                'method'   => 'POST',
                'headers'  => $igramHeadersCredentials,
                'body'     => [
                    'post_url' => 'https://www.instagram.com/p/abcd1234/'
                ]
            ],
            'unlike_post_by_url' => [
                'url'      => url('/api/post/unlike'),
                'method'   => 'POST',
                'headers'  => $igramHeadersCredentials,
                'body'     => [
                    'post_url' => 'https://www.instagram.com/p/abcd1234/'
                ]
            ],
            'comment_post' => [
                'url'      => url('/api/post/{:postId}/comment'),
                'method'   => 'POST',
                'headers'  => $igramHeadersCredentials,
                'body'     => [
                    'message' => 'the comments that you will write in the post.'
                ]
            ],
            'comment_post_by_url' => [
                'url'      => url('/api/post/comment'),
                'method'   => 'POST',
                'headers'  => $igramHeadersCredentials,
                'body'     => [
                    'post_url' => 'https://www.instagram.com/p/abcd1234/',
                    'message'  => 'the comments that you will write in the post.'
                ]
            ],
        ];
        
        return $igramEndpoint;
    }
}