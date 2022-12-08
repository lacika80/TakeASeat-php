<?
require_once("Db.php");
class Contact extends Db
{
    public function GetAll()
    {
        $stmt = $this->pdo_connect_mysql()->prepare('SELECT * from contacts');
        $stmt->execute();
        $contacts = $stmt->fetchall(PDO::FETCH_ASSOC);
        $contactdtoArray = array();
        foreach ($contacts as $key => $value) {
            $contact = new Contactdto;

            foreach ($value as $key2 => $value2) {
                $contact->$key2 = $value2;
            }
            $contactdtoArray[] = $contact;
        }
        return $contactdtoArray;
    }
    public function Send()
    {
        $datas = [$_POST["name"] ?? "", $_POST["email"] ?? "", $_POST["phone"] ?? "", $_POST["message"] ?? ""];
        $stmt = $this->pdo_connect_mysql()->prepare('INSERT INTO `contacts`(`name`, `email`, `phone`, `message`) VALUES (?, ?, ?, ? )');
        $stmt->execute($datas);
        $to      = json_decode(file_get_contents("App/Lib/App.setting.json"), true)["email"];
        $from    = $_POST['email'];
        $message = $_POST['message'];
        $headers = 'From: ' . $to . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        mail($to, "", $message, $headers);
    }
    public function update()
    {
        $strJsonFileContents = json_decode(file_get_contents("App/Lib/App.setting.json"), true);
        $strJsonFileContents["email"] = $_POST["email"];
        if ($_POST["link"][0] == "/") {
            $strJsonFileContents["contactThanksLink"] = substr($_POST["link"], 1);
        } else {
            $strJsonFileContents["contactThanksLink"] = $_POST["link"];
        }
        $strJsonFileContents["contactTextId"] = $_POST["textid"];
        file_put_contents("App/Lib/App.setting.json", json_encode($strJsonFileContents));
        header("Location: /admin/contact");
        die;
    }
}
