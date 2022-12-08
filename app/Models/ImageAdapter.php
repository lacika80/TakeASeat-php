<?
class ImageAdapter
{
    /**
     * 1 html alapú szövegben megkeresi a base64 tipusu képeket. lementi és kicseréli a rávaló hivatkozást.
     * @param int $id a szöveg id-ja. ezalapján dönt a program arról, hogy képet töröljön-e netán a duplikációt átírányítsa
     * @param string $data a szöveg amit át kell nézni. (ha üres szöveget adsz paraméternek, akkor az összes kép törlődni fog ami az id-hoz tartozik)
     * @return string a javított szöveg
     */
    public function imagescannerToBin($data, $id)
    {
        $counter = 0;
        $index = 0;
        $start = "";
        $usedBinaryFiles = []; //tartalmazni fogja azoknak a fájloknak az elérési útját melyek használva vannak
        $dir = "public/images/textualContentImages";
        do {
            $start = strpos($data, 'base64,', $index);
            if ($start !== false) {
                $start += 7; // a tényleges adat kezdési pont 10-el magasabb. ha elöbb állítom be, akkor nem tud false lenni az érték.
                $end = strpos($data, '"', $start);
                $image = substr($data, $start, $end - $start);

                //valamit kell ezzel az adattal csinálni
                $binaryData = base64_decode($image);
                $contains = false; //a következő ellenőrzés segéd változója
               


                foreach (scandir($dir) as $file) {
                    if ($file !== "." && $file !== "..") {
                        if ($id < 10 && $file[0] == $id) {
                            $md5image2 = md5(file_get_contents("{$dir}/{$file}"));

                            if ($md5image2 == md5($binaryData)) {
                                $usedBinaryFiles[] = "{$dir}/{$file}";
                                $contains = "{$dir}/{$file}";
                            }
                        } elseif ($file[0] == $id[0] && $file[1] == $id[1]) {
                            $md5image2 = md5(file_get_contents("{$dir}/{$file}"));

                            if ($md5image2 == md5($binaryData)) {
                                $usedBinaryFiles[] = "{$dir}/{$file}";
                                $contains = "{$dir}/{$file}";
                            }
                        }
                    }
                }
                if ($contains === false) {
                    $size = getImageSizeFromString($binaryData);
                    $extension = substr($size['mime'], 6);
                    $img_file = "public/images/textualContentImages/" . $id . date('Y-m-d-H-i-s') . $counter . ".{$extension}";
                    $counter++; //ha 1 másodpercen belül is több kép kerül be, akkor ne írják egymást felül
                    file_put_contents($img_file, $binaryData);
                    $usedBinaryFiles[] = "{$img_file}";
                    //adat mentve. => jön az átírása a szövegben
                    $srcStart = strpos($data, '<img src="data:', $index) + 10;
                    $srcEnd =  strpos($data, '"', $srcStart);
                    $src = substr($data, $srcStart, $srcEnd - $srcStart);
                    $data = str_replace($src, $img_file, $data);
                    //index átírása a következő elemzéshez
                    $index = $end - $start - (strlen($image) - strlen($img_file));
                } else {
                    //átírás a szövegben
                    $srcStart = strpos($data, '<img src="data:', $index) + 10;
                    $srcEnd =  strpos($data, '"', $srcStart);
                    $src = substr($data, $srcStart, $srcEnd - $srcStart);
                    $data = str_replace($src, $contains, $data);
                    //index átírása a következő elemzéshez
                    $index = $end - $start - (strlen($image) - strlen($contains));
                }
            }
        } while ($start !== false);

        $binaryFiles = []; //A cikk összes meglévő képét fogja tartalmazni
        foreach (scandir($dir) as $file) {
            if ($file !== "." && $file !== "..") {
                if ($id < 10 && $file[0] == $id) {
                    $binaryFiles[] = "{$dir}/{$file}";
                } elseif ($file[0] == $id[0] && $file[1] == $id[1]) {
                    $binaryFiles[] = "{$dir}/{$file}";
                }
            }
        }

        $notUsed = array_diff($binaryFiles, $usedBinaryFiles);
        if (!empty($notUsed)) {
            foreach ($notUsed as $key => $value) {
                unlink($value);
            }
        }
        return $data;
    }
    /**
     * 1 html alapú szövegben megkeresi a kép hivatkozásokat és kicseréli magára a képre base64-ben
     * @param string $data a szöveg amit át kell nézni
     * @return string a javított szöveg
     */
    public function imagescannerToBase64($data)
    {
        $index = 0;
        $start = "";
        do {           
            $start = strpos($data, '<img src="', $index);
            if ($start !== false) {
                $start += 10; // a tényleges adat kezdési pont 10-el magasabb. ha elöbb állítom be, akkor nem tud false lenni az érték.               
                $end = strpos($data, '"', $start);              
                $image = substr($data, $start, $end - $start);               
                //valamit kell ezzel az adattal csinálni
                $path = $image;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $imagedata = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imagedata);
                //adat átalakítva. => jön az átírása a szövegben               
                $data = str_replace($image, $base64, $data);               
                //index átírása a következő elemzéshez            
                $index = $start + strlen($base64);
            }
        } while ($start !== false);       
        return $data;
    }
}
