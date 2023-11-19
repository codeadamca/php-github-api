<?php

$env = file(__DIR__.'/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach($env as $value)
{
  $value = explode('=', $value);  
  define($value[0], $value[1]);
}

$url = 'https://api.github.com/repos/BrickMMO/bmos';

$headers[] = 'Content-type: application/json';
$headers[] = 'Authorization: Bearer '.GITHUB_ACCESS_TOKEN;
$headers[] = 'User-Agent: Awesome-Octocat-App   ';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

curl_close($ch);

$result = json_decode($result, true);

// echo '<pre>';
// print_r($result);
// echo '</pre>';

$repo = $result;

echo '<h1>'.$repo['name'].'</h1>';
echo '<ul>
    <li>ID: '.$repo['id'].'</li>
    <li>Full Name: '.$repo['full_name'].'</li>
    <li>Owner: '.$repo['owner']['login'].'</li>
    <li>URL: <a href="'.$repo['html_url'].'">'.$repo['html_url'].'</a></li>
    <li>Created At: '.date_format(date_create($repo['created_at']), "F jS, Y g:i a").'</li>
    <li>Updated At: '.date_format(date_create($repo['updated_at']), "F jS, Y g:i a").'</li>
    <li>Pushed At: '.date_format(date_create($repo['pushed_at']), "F jS, Y g:i a").'</li>
</ul>';