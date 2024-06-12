<?php

include('connect.php');

if(!isset($_SESSION['user'])) header('Location: /get-user.php');

if(isset($_GET['readme']))
{

  $url = 'https://api.github.com/repos/codeadamca/'.$_GET['readme'].'/readme';
  
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
  
  $result = json_decode($result, true);
  
  $query = 'UPDATE repo SET
    readme = "'.addslashes(base64_decode($result['content'])).'"
    WHERE name = "'.$_GET['readme'].'"
    AND user = "codeadamca"';
  mysqli_query($connect, $query);

  $redirect = '/check-codeadamca-repos.php?page='.($_GET['page'] + 1);

}
elseif(isset($_GET['page']))
{

  if($_GET['page'] > $_SESSION['user']['public_repos'])
  {
    header('Location: /get-user.php');
  }

  // Get User Repo
  $url = 'https://api.github.com/users/codeadamca/repos?per_page=1&page='.$_GET['page'];

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

  $repo = json_decode($result, true); 

  $query = 'INSERT INTO repo (
      json,
      name,
      user
    ) VALUES (
      "'.addslashes($result).'",
      "'.$repo[0]['name'].'",
      "codeadamca"
    )';
  mysqli_query($connect, $query);

  $redirect = '/check-codeadamca-repos.php?page='.$_GET['page'].'&readme='.$repo[0]['name'];

}
else
{
  $query = 'DELETE FROM repo WHERE user = "codeadamca"';
  mysqli_query($connect, $query);
  $redirect = '/check-codeadamca-repos.php?page=1';
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
  
    <h1 class="w3-padding w3-green">Fetch Repo</h1>

    <h2 class="w3-padding w3-blue">
      <?php if(isset($_GET['page'])): ?>
        Repo: <?=isset($_GET['readme']) ? $_GET['readme'] : $repo[0]['name']?>
      <?php else: ?>
        Starting...
      <?php endif; ?>
    </h2>

    <h2 class="w3-padding w3-purple">
      <a href="<?=$redirect?>"><?=$redirect?></a>
    </h2>

  </div>

  <script>
  setTimeout(() => {
    document.location = "<?=$redirect?>";
  }, "2000");
  </script>

</body>
</html>