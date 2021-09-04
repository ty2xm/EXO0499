<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
  <?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  
     $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);

    	
        //編集    
        if(!empty($_POST["edit"]) && !empty($_POST["epass"])){
            $edit =$_POST["edit"];
            $epass = $_POST["epass"];

            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();                             
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    $editid = $row['id'];
                    $editname = $row['name'];
                    $editcomment = $row['comment'];
                    }
        }
        //投稿機能
        if (!empty($_POST['name']) && !empty($_POST['comment'])&& !empty($_POST['pass'])) {
            $post_name = $_POST['name'];
            $post_com = $_POST['comment'];
            $post_pass = $_POST['pass'];
            $post_date = date("Y年m月d日 H:i:s");
            
            if (empty($_POST['edit_id'])) {
            
            $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	        $name = $post_name;
	        $comment = $post_com; 
	        $date = $post_date;
	        $pass = $post_pass;
	        $sql -> execute();
        } else {
            $edit_id = $_POST['edit_id'];
            $edit_com=$_POST["comment"];
            $edit_name=$_POST["name"];
            $edit_pass=$_POST["pass"];
            
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //編集番号とパスワードが一致した場合
                if($edit_id == $row['id'] && $edit_pass == $row['pass']){
                    //データの編集
                    $id = $edit_id;
                    $name = $edit_name;
                    $comment = $edit_com; 
                    $pass = $edit_pass;
                    $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment, pass=:pass WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }        
        }
      }
     
     //削除機能
        if(!empty($_POST["dnum"]) && !empty($_POST["dpass"])) {
            $dnum=$_POST["dnum"];
            $dpass=$_POST["dpass"];
            
            //入力データの抽出
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                
                //削除番号とパスワードが一致した場合
                if($dnum == $row['id'] && $dpass == $row['pass']){
                    //入力したデータの削除
                    $id = $dnum;
                    $sql = 'delete from mission5_1 where id=:id' ; 
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
        
        //表示機能
        $sql = 'SELECT * FROM mission5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            
        echo "<hr>";
        }     
            

    ?>
    
    <form action="mission_5-1.php" method="post">
      <input type="text" name="name" placeholder="名前" value="<?php if(isset($edit_name)) {echo $edit_name;} ?>"><br>
      <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($edit_com)) {echo $edit_com;} ?>"><br>
      <input type="hidden" name="edit_id" value="<?php if(isset($edit_id)) {echo $edit_id;} ?>">
      <input type="text" name="pass" placeholder="パスワード">
      <input type="submit" name="submit" value="送信"><br><br>
      
    </form>
    <form action="mission_5-1.php" method="post">
      <input type="text" name="dnum" placeholder="削除対象番号"><br>
      <input type="text" name="dpass" placeholder="パスワード">
      <input type="submit" name="delete" value="削除"><br><br>
    </form>
    <form action="mission_5-1.php" method="post">
      <input type="text" name="edit" placeholder="編集対象番号"><br>
      <input type="text" name="epass" placeholder="パスワード">
      <input type="submit" value="編集">
    </form>

</body>
</html>