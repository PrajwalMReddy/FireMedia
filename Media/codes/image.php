<?php
  include '../postsincludes/dbh.inc.php';
  $randomKey=mysqli_real_escape_string($conn, $_GET['img']);
  $sql="SELECT * FROM files WHERE filesRandomKey='$randomKey'";
  $result=mysqli_query($conn, $sql);
  $queryResult=mysqli_num_rows($result);
	
  while ($row=mysqli_fetch_assoc($result))
  {
    $title=$row['filesTitle']." (Image)";
    if($row['filesVisibility']=="Reported")
    {
      header("location: ../codes/photos.php");
    }
  }

  include 'header.php';
  $imgId=$_GET['img'];
  if($imgId=="colors")
  {
    header("location: ../../colors.html");
    exit();
  }
  if (!isset($_SESSION[$imgId]) and isset($_SESSION["useruid"]))
  {
    $sql="UPDATE files SET filesViews = filesViews + 1 WHERE filesRandomKey='".$imgId."';";
    $result=mysqli_query($conn, $sql);

    $sql="INSERT INTO filesViewscounter (filesViewscounterUser, filesViewscounterFile) VALUES (?, ?);";
    $stmt=mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql))
    {
      header("location: ../codes/photos.php");
      exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $imgId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION[$imgId]=1;
  }
?>
<script>
  function report()
  {
    var imgId="<?php echo $imgId; ?>";
    var report=confirm("Are You Sure You Want To Report This Image?");
    if (report==true)
    {
      window.location.href=("report.php?report="+imgId);
    }
  }
</script>
<div>
  <?php
    if (isset($_GET['img']))
    {
      $randomKey=mysqli_real_escape_string($conn, $_GET['img']);
      $sql="SELECT * FROM files WHERE filesRandomKey='$randomKey'";
      $result=mysqli_query($conn, $sql);
      $queryResult=mysqli_num_rows($result);

      while ($row=mysqli_fetch_assoc($result))
      {
        $rk=$row['filesRandomKey'];
        $src=$row['filesName'];
        $srcActual=$rk.".".$src;
        echo "<br><div class='blogdiv'>
                <div>
                  <img class='showimg' src='../uploads/images/".$srcActual."' alt='".$row['filesTitle']."&nbsp¯\_(ツ)_/¯'>
                </div>
                <div>
                  <h2>".$row['filesTitle']."</h2>
                  <h3>By:&nbsp<a href='profile.php?profile=".$row['filesAuthor']."' target='_blank' class='linknone'>".$row['filesAuthor']."<a></h3>
                  <p>".$row['filesDate']."&nbsp|&nbsp".$row['filesViews']."&nbspViews</p>
                  <p>".$row['filesDescription']."</p>";
              if(isset($_SESSION["useruid"]))
              {
                $sql="SELECT * FROM fileslikes WHERE fileslikesUser='$username' AND fileslikesFile='$randomKey' AND fileslikesType='like'";
                $result1=mysqli_query($conn, $sql);
                $queryResults1=mysqli_num_rows($result1);

                $sql2="SELECT * FROM fileslikes WHERE fileslikesFile='$randomKey' AND fileslikesType='like';";
                $result2=mysqli_query($conn, $sql2);
                $queryResults2=mysqli_num_rows($result2);

                echo "<hr>";
                if ($queryResults1>0)
                {
                  if($queryResults2==1)
                  {
                    echo"<form action='..\postsincludes\likes.inc.php?vid=$imgId&type=unlike&filetype=img' method='post'>
                           <button id='likebutton' type='submit' name='submit'>
                             <img id='likeimg' src='..\images\ChargeOn.png'>
                           </button>
                         </form>
                         <p>1&nbspLike</p>";
                  }
                  else
                  {
                    echo "<form action='..\postsincludes\likes.inc.php?vid=$imgId&type=unlike&filetype=img' method='post'>
                           <button id='likebutton' type='submit' name='submit'>
                             <img id='likeimg' src='..\images\ChargeOn.png'>
                           </button>
                         </form>
                         <p>".$queryResults2."&nbspLikes</p>";
                  }
                }
                else
                {
                  echo "<form action='..\postsincludes\likes.inc.php?vid=$imgId&type=like&filetype=img' method='post'>
                         <button id='likebutton' type='submit' name='submit'>
                           <img id='likeimg' src='..\images\Charge.png'>
                         </button>
                       </form>
                       <p>Like This Image</p>";
                }
                echo"<hr>
                     <a href='javascript:report()' style='color:red; font-size:15px;'>REPORT IMAGE</a>";
              }
              echo "</div></div></div>";

      }
    }
    else
    {
      header("location: photos.php");
    }

echo "</div>";

include 'comment.php';

echo "<br><br><br><br><br><br>";

  include 'footer.php';
?>