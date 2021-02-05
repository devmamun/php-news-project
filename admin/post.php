<?php
  include "header.php";
  include "config.php";

  $limit = 3;
  if(isset($_REQUEST['page'])){
    $page_no = $_REQUEST['page'];
  }else{
    $page_no = 1;
  }
    $offset = ($page_no-1)*$limit;
    if($_SESSION['role']==1){
      $sql = "SELECT * FROM news_post
        LEFT JOIN news_category ON news_post.category = news_category.category_id
        LEFT JOIN news_user ON news_post.author = news_user.user_id
        order by news_post.post_id DESC LIMIT {$offset},{$limit} ";
    }elseif($_SESSION['role']==0){
        $sql = "SELECT * FROM news_post
          LEFT JOIN news_category ON news_post.category = news_category.category_id
          LEFT JOIN news_user ON news_post.author = news_user.user_id
          WHERE news_post.author = {$_SESSION['user_id']}
          order by news_post.post_id DESC LIMIT {$offset},{$limit} ";
    }

    $result = mysqli_query($conn,$sql);

?>
  <div id="admin-content">
      <div class="container">
          <div class="row">
              <div class="col-md-10">
                  <h1 class="admin-heading">All Posts</h1>
              </div>
              <div class="col-md-2">
                  <a class="add-new" href="add-post.php">add post</a>
              </div>
              <div class="col-md-12">
                <?php if(mysqli_num_rows($result)<=0){
                  echo "</h1>No data found.</h1>";
                }else{  ?>
                  <table class="content-table">
                      <thead>
                          <th>S.No.</th>
                          <th>Title</th>
                          <th>Category</th>
                          <th>Date</th>
                          <th>Author</th>
                          <th>Edit</th>
                          <th>Delete</th>
                      </thead>
                      <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)){  ?>
                          <tr>
                              <td class='id'><?php echo ++$offset; ?></td>
                              <td><?php echo $row['title']; ?></td>
                              <td><?php echo $row['category_name']; ?></td>
                              <td><?php echo $row['post_date']; ?></td>
                              <td><?php echo $row['username']; ?></td>
                              <td class='edit'><a href='update-post.php?id=<?php echo $row["post_id"] ?>'><i class='fa fa-edit'></i></a></td>
                              <td class='delete'><a href='delete-post.php?id=<?php echo $row["post_id"]; ?>&catid=<?php echo $row['category_id']; ?>&post_img=<?php echo $row['post_img']; ?>'><i class='fa fa-trash-o'></i></a></td>
                            </tr>
                          <?php  } ?>
                        </tbody>
                    </table>
                <?php  }

                if($_SESSION['role']==1){
                  $sql1 = "SELECT * FROM news_post";
                }elseif($_SESSION['role']==0){
                    $sql1 = "SELECT * FROM news_post WHERE news_post.author = {$_SESSION['user_id']}";
                }
                  $result1 = mysqli_query($conn,$sql1) or die("Query Failed");
                  if(mysqli_num_rows($result1)>0){
                    $total_record = mysqli_num_rows($result1);
                    $total_pages = ceil($total_record/$limit);
                    echo "<ul class='pagination admin-pagination'>";
                    if($page_no>1){
                      $prev = $page_no-1;
                      echo '<li><a href="post.php?page='.$prev.'">Prev</a></li>';
                    }

                    for ($i=1; $i <= $total_pages; $i++) {
                      if($page_no==$i){
                        $active = "active";
                      }else{
                        $active = "";
                      }
                      echo "<li class='{$active}'><a href='post.php?page=$i'>$i</a></li>";
                    }
                    if($page_no<$total_pages){
                      $next = $page_no+1;
                      echo '<li><a href="post.php?page='.$next.'">Next</a></li>';
                    }
                    echo "</ul>";
                  }

                ?>
              </div>
          </div>
      </div>
  </div>
<?php include "footer.php"; ?>
