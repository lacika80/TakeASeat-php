<?
require_once("Db.php");
class MenuPoint extends Db
{
    public function AddToList()
    {


        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM menuPoints WHERE name = ?');
        if ($stmt->execute([$_POST["name"]])) {
            $menu = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($menu != false) {
                $stmt = $this->pdo_connect_mysql()->prepare('UPDATE menuPoints SET url = ?, sort = ? WHERE name = ?');
                $stmt->execute([$_POST["url"], $_POST["sort"], $_POST["name"]]);


                header("Location: /admin/menu");
                die;
            }
        }
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM menuPoints WHERE url = ?');
        if ($stmt->execute([$_POST["url"]])) {
            $menu = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($menu != false) {
                $stmt = $this->pdo_connect_mysql()->prepare('UPDATE menuPoints SET name = ?, sort = ? WHERE url = ?');
                $stmt->execute([$_POST["name"], $_POST["sort"], $_POST["url"]]);
                header("Location: /admin/menu");
                die;
            }
        }
        $stmt = $this->pdo_connect_mysql()->prepare('INSERT INTO menuPoints (name, url, sort) VALUES (?, ?, ?)');
        $stmt->execute([$_POST["name"],$_POST["url"],$_POST["sort"]]);
        header("Location: /admin/menu");
                die;
    }
    /**
     * @param int $id
     */
    public function Delete($id)
    {
        $stmt = $this->pdo_connect_mysql()->prepare('DELETE FROM menuPoints WHERE id = ?');
        $stmt->execute([$id]);
    }
    /**
     * @return array
     */
    public function GetAll()
    {

        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM menuPoints order by sort asc');


        if ($stmt->execute()) {
            $menus = $stmt->fetchall(PDO::FETCH_ASSOC);
            $menudtoArray = array();
            foreach ($menus as $key => $value) {
                $menu = new menupointdto;

                foreach ($value as $key2 => $value2) {
                    $menu->$key2 = $value2;
                }
                $menudtoArray[] = $menu;
            }
            return $menudtoArray;
        }
    }
}
