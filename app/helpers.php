<?php

function gravatar_url($email): string
{
    $email = md5($email);

    return "https://gravatar.com/avatar/$email" . http_build_query([
            's' => 60,
//            'd' => 'default pic'
        ]);
}
