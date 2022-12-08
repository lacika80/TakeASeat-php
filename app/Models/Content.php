<?
require_once("Db.php");
require_once("ImageAdapter.php");
class Content extends Db
{
    public function CreateDtoFromPost()
    {
        $contentdto = new Contentdto;
        $contentdto->id = $_GET["id"] ?? null;
        $contentdto->title = $_POST["editorTitle"] ? substr($_POST["editorTitle"], 3, strlen($_POST["editorTitle"]) - 7) :  null;
        $contentdto->prologue = $_POST["editorPrologue"] ? substr($_POST["editorPrologue"], 3, strlen($_POST["editorPrologue"]) - 7) :  null;;
        $contentdto->content = $_POST["editorContent"] ?? null;
        $contentdto->metaKeywords = $_POST["metaKeywords"] ?? null;
        $contentdto->metaDescription = $_POST["metaDescription"] ?? null;
        $contentdto->metaTitle = $_POST["metaTitle"] ?? null;
        $date = date("Y-m-d H:i:s");
        $contentdto->dateCreated = $_POST["createDate"] != "" ? $_POST["createDate"] : $date;
        $contentdto->dateModified = $_POST["setDate"] != "" ? $_POST["setDate"] :  null;
        $contentdto->URL = $_POST["URL"] ?? null;
        $contentdto->blog = $_POST["contentSelector"] == "blog" ? true : false;
        if ($_FILES['image']['size'] > 10) {
            $target_dir = 'public/images/';
            $image_path = $target_dir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            $contentdto->image =  "/" . $image_path;
        } else {
            $contentdto->image = null;
        }
        return $contentdto;
    }



    public function upload()
    {
        //általánosítás
        $contentdto = $this->CreateDtoFromPost();
        //beillesztés content nélkül
        $stmt = $this->pdo_connect_mysql()->prepare("INSERT INTO contents (title, prologue, content, metaKeywords, metaDescription, metaTitle, image, dateCreated, dateModified, URL, blog) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$contentdto->title,  $contentdto->prologue,  "...",  $contentdto->metaKeywords,  $contentdto->metaDescription,  $contentdto->metaTitle, $contentdto->image,  $contentdto->dateCreated, $contentdto->dateModified, $contentdto->URL,  $contentdto->blog]);
        //id kinyerése
        $stmt = $this->pdo_connect_mysql()->prepare("SELECT id FROM contents ORDER BY id desc LIMIT 1");
        $stmt->execute();
        $id = $stmt->fetch(PDO::FETCH_ASSOC);
        //content formázása
        $iAdapt = new ImageAdapter;
        $content = $iAdapt->imagescannerToBin($_POST["editorContent"], $id["id"]);
        //content beillesztése
        $stmt = $this->pdo_connect_mysql()->prepare("UPDATE contents set content=? WHERE id = ?");
        $stmt->execute([$content, $id["id"]]);

        header("Location: /" . $contentdto->URL);
    }
    /**
     * @param int $limit
     * @param string $order
     * @return array
     */
    public function GetBlogList($limit = "", $order = "dateCreated DESC")
    {
        $limithelper = $limit != "" ? " LIMIT " : "";
        $orderhelper = $order != "" ? " ORDER BY " : "";
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM contents WHERE blog = 1' . $orderhelper . $order . $limithelper . $limit);
       

        if ($stmt->execute()) {
            $blogs = $stmt->fetchall(PDO::FETCH_ASSOC);
            $blogdtoArray = array();
            if (is_array($blogs)) {
                foreach ($blogs as $key => $value) {
                    $blog = new Contentdto;

                    foreach ($value as $key2 => $value2) {
                        $blog->$key2 = $value2;
                    }
                    $blogdtoArray[] = $blog;
                }
            }
            return $blogdtoArray;
        }
    }
    /**
     * @param string $order
     * @return array
     */
    public function Get3Blog($order = "dateCreated DESC")
    {
        return ($this->GetBlogList(3, $order));
    }
    public function getAllText()
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM contents WHERE blog = 0');
        $stmt->execute();
        $content = $stmt->fetchall(PDO::FETCH_ASSOC);
        $textdtotoArray = array();
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $text = new Contentdto;

                foreach ($value as $key2 => $value2) {
                    $text->$key2 = $value2;
                }
                $textdtotoArray[] = $text;
            }
        }
        return $textdtotoArray;
    }
    public function getAll()
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM contents');
        $stmt->execute();
        $content = $stmt->fetchall(PDO::FETCH_ASSOC);
        $textdtotoArray = array();
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $text = new Contentdto;
                if (is_array($value)) {
                    foreach ($value as $key2 => $value2) {
                        $text->$key2 = $value2;
                    }
                }
                $textdtotoArray[] = $text;
            }
        }
        return $textdtotoArray;
    }
    public function Delete($id)
    {
        $stmt = $this->pdo_connect_mysql()->prepare('DELETE FROM contents WHERE id = ?');
        $stmt->execute([$id]);
        $iAdapter = new ImageAdapter;
        $iAdapter->imagescannerToBin("",$id);
        header("Location: /admin/textualContent/search");
        die;
    }

    public function Edit()
    {
        $iAdapter = new ImageAdapter;
        $contentdto = $this->CreateDtoFromPost();
        $contentdto->content = $iAdapter->imagescannerToBin($contentdto->content,  $contentdto->id);
        $stmt = $this->pdo_connect_mysql()->prepare("UPDATE contents set title=?, prologue=?, content=?, metaKeywords=?, metaDescription=?, metaTitle=?, dateCreated=?, dateModified=?, URL=?, blog=? WHERE id = ?");
        $stmt->execute([$contentdto->title,  $contentdto->prologue,  $contentdto->content,  $contentdto->metaKeywords,  $contentdto->metaDescription,  $contentdto->metaTitle,  $contentdto->dateCreated, $contentdto->dateModified, $contentdto->URL,  $contentdto->blog,  $contentdto->id]);
        header("Location: /" . $contentdto->URL);
    }
    /**
     * @param int $id
     * @return DTOs/contentdto
     */
    public function GetOneByUrl($URL)
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM contents WHERE URL = ? LIMIt 1');
        if ($stmt->execute([$URL])) {
            $content = $stmt->fetch(PDO::FETCH_ASSOC);
            $contentdto = new Contentdto;
            if (is_array($content)) {
                foreach ($content as $key => $value) {
                    $contentdto->$key = $value;
                }
            }
            return $contentdto;
        }
    }
    public function GetOneById($id)
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * FROM contents WHERE id = ? LIMIt 1');
        if ($stmt->execute([$id])) {
            $content = $stmt->fetch(PDO::FETCH_ASSOC);
            $contentdto = new Contentdto;

            if (is_array($content)) {
                foreach ($content as $key => $value) {
                    $contentdto->$key = $value;
                }
            }
            //amennyiben ckeditorban átírás alá kerülne a content, javítjuk a benne
            //lévő image hivatkozásokat, hogy a ckeditor image adaptere tudja kezelni
            if ($_GET["Action"] == "textualContent" && $_GET["setid"]) {
                $iAdapt = new ImageAdapter;
                $contentdto->content = $iAdapt->imagescannerToBase64($contentdto->content);
            }
            return $contentdto;
        }
    }
    /**
     * @param int $id a bevezető id-ja
     * @return contentdto
     */
    public function GetIntroduction($id)
    {
        return $this->GetOneById($id);
    }
}
