<?php
  include '..\postsincludes\dbh.inc.php';

  if(isset($_GET['img']))
  {
    $file=$_GET['img'];
    $fileType='img';
  }
  elseif(isset($_GET['vid']))
  {
    $file=$_GET['vid'];
    $fileType='vid';
  }
  elseif(isset($_GET['aud']))
  {
    $file=$_GET['aud'];
    $fileType='aud';
  }

  echo "<br><br><div class='comment'>";

  if(isset($_SESSION["useruid"]))
  {
    if($fileType=='vid')
    {
      echo "<div style='text-align: center;'>
              <form method='POST' action='..\postsincludes\commentvideo.inc.php'>
                <input type='hidden' name='file' value='$file'>
                <textarea name='message' class='textcomment'></textarea>
                <button type='submit' name='submit' class='buttoncomment'>Comment</button>
              </form>
            </div>
          <br><br>";
    }
    if($fileType=='img')
    {
      echo "<div style='text-align: center;'>
              <form method='POST' action='..\postsincludes\commentimage.inc.php'>
                <input type='hidden' name='file' value='$file'>
                <textarea name='message' class='textcomment'></textarea>
                <button type='submit' name='submit' class='buttoncomment'>Comment</button>
              </form>
            </div>
          <br><br>";
    }
    if($fileType=='aud')
    {
      echo "<div style='text-align: center;'>
              <form method='POST' action='..\postsincludes\commentaudio.inc.php'>
                <input type='hidden' name='file' value='$file'>
                <textarea name='message' class='textcomment'></textarea>
                <button type='submit' name='submit' class='buttoncomment'>Comment</button>
              </form>
            </div>
          <br><br>";
    }
  }

  $sql="SELECT * FROM filesComments WHERE filesCommentsFile='".$file."' ORDER BY filesCommentsLikes desc";
  $result=mysqli_query($conn, $sql);
  echo "<br><h1 style='margin-left: 9%; align: justify;'>Comments (".mysqli_num_rows($result)."):</h1>";

  $i=0;
  while ($row=$result->fetch_assoc())
  {
    $author=$row['filesCommentsAuthor'];
    echo "<div class='comments'>";
    echo "<div>";
    echo "<a href='profile.php?profile=".$author."' target='_blank' class='linknone'><h3 style='display: inline'>".$author."</h3></a>";
    echo "&nbsp|&nbsp";
    echo "<h4 style='display: inline'>".$row['filesCommentsDate']."</h4>";
    echo "<br><br>";
    echo nl2br($row['filesCommentsMessage']);
    echo "</div>";
    echo "<div>";
    if(isset($_SESSION["useruid"]))
    {
      $rk=$row['filesCommentsRandomKey'];
      $author=$_SESSION["useruid"];
      $sql1="SELECT * FROM filescommentslikes WHERE filescommentslikesUser='$author' AND filescommentslikesFile='$file' AND filescommentslikesRandomKey='$rk';";
      $result1=mysqli_query($conn, $sql1);
      $queryResults1=mysqli_num_rows($result1);

      if($queryResults1>0)
      {
        echo "<img class='upvotecomment' src='../images/Upvoted.png' alt='upvote'><br>";
        echo "<p align='center'>".$row['filesCommentsLikes']."</p>";
      }
      else
      {
        echo "<form action='..\postsincludes\commentslikes.inc.php?file=$file&filetype=$fileType&rk=$rk' method='post'>";
        echo "<button style='border: none; background-color: #e2e2e2;'>";
        echo "<img class='upvotecomment' src='../images/Upvote.png' alt='upvote'><br>";
        echo "</button>";
        echo "</form>";
      }
    }
    echo "</div>";
    echo "</div><br>";
    $i=$i+1;
  }

echo "</div><br><br><br>";