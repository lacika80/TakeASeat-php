<?
require_once("Db.php");
class Image extends Db
{
    public function Upload()
    {
        $target_dir = 'public/images/';
        $image_path = $_POST["name"] != "" ? $target_dir . $_POST["name"] :  $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $image_path = "/".$image_path;
        $sort = $_POST['sort'] ?? NULL;
        if($sort != null){
          $stmt = $this->pdo_connect_mysql()->prepare('INSERT INTO images (filepath, sort) VALUES (?, ?)');
          $stmt->execute([$image_path, $sort,]);
        }else{
          $stmt = $this->pdo_connect_mysql()->prepare('INSERT INTO images (filepath) VALUES (?)');
          $stmt->execute([$image_path]);
        }
        header("Location: /admin/slider?uploaded=true");
        die;
    }
    /**
     * @param int $id
     */
    public function Delete($id)
    {   
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM images WHERE id = ?');
        $stmt->execute([$id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        unlink(substr($image["filepath"],1));
        $stmt = $this->pdo_connect_mysql()->prepare('DELETE FROM images WHERE id = ?');
        $stmt->execute([$id]);
    }
    /**
     * gives back the active pictures in order
     * @return array
     */
    public function GetActives()
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM images WHERE sort > 0 ORDER BY sort ASC');


        if ($stmt->execute()) {
            $images = $stmt->fetchall(PDO::FETCH_ASSOC);
            $imagetoArray = array();
            foreach ($images as $key => $value) {
                $image = new Imagedto;

                foreach ($value as $key2 => $value2) {
                    $image->$key2 = $value2;
                }
                $imagetoArray[] = $image;
            }
            return $imagetoArray;
        }
    }
    /**
     * gives back all the pictures from new to old
     * @return array
     */
    public function GetAll()
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM images  ORDER BY id desc');


        if ($stmt->execute()) {
            $images = $stmt->fetchall(PDO::FETCH_ASSOC);
            $imagetoArray = array();
            foreach ($images as $key => $value) {
                $image = new Imagedto;

                foreach ($value as $key2 => $value2) {
                    $image->$key2 = $value2;
                }
                $imagetoArray[] = $image;
            }
            return $imagetoArray;
        }
    }
    public function SetOrder()
    {
        foreach ($_POST as $key => $value) {
            $stmt = $this->pdo_connect_mysql()->prepare('UPDATE images SET sort = ? WHERE id = ?');
            if ($stmt->execute([$value, $key])) {
            } else {
                header("Location: /admin/slider?updated=false");
                die;
            }
        }
        header("Location: /admin/slider?updated=true");
        die;
    }
}
