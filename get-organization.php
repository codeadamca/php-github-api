<?php

include('connect.php');

// Get User Information
if(!isset($_SESSION['organization']))
{
    
  $url = 'https://api.github.com/orgs/brickmmo';

  echo $url.'<br>';

  $headers[] = 'Content-type: application/json';
  $headers[] = 'Authorization: Bearer '.GITHUB_ACCESS_TOKEN;
  $headers[] = 'User-Agent: Awesome-Octocat-App';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);

  curl_close($ch);

  $_SESSION['organization'] = json_decode($result, true);

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personal Repos</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>  

<div class="w3-container w3-red w3-padding">
  
  <h1 class="w3-padding w3-green">Fetch Organization</h1>

  <h2 class="w3-padding w3-blue">
    Username: <?=$_SESSION['organization']['login']?>
    <br>
    Publie Repos: <?=$_SESSION['organization']['public_repos']?>
  </h2>

  <h2 class="w3-padding w3-purple">
    <a href="check-brickmmo-repos.php">Start brickmmo Collection</a>
  </h2>

</div>

</body>
</html>