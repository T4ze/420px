<?php

define("THREESHOLD", 40);

class Image {
    public $id;
    public $created_at;
    public $user_id;
    public $user_name;
    public $path;
    public $palette;

    private function __construct() {}

    static function getRecent($c) {
        try {
            $p = SPDO::getInstance()->prepare("SELECT `images`.*, `users`.`username`
                                                from `images`
                                                JOIN `users` ON `users`.`id` = `images`.`owner`
                                                ORDER by `created_at`
                                                DESC limit :count");
            $p->bindValue(':count', (int) $c, PDO::PARAM_INT);
            $q = $p->execute();
            $r = $p->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($elt) {
                $image = new Image();
                $image->id = $elt['id'];
                $image->created_at = $elt['created_at'];
                $image->user_id = $elt['owner'];
                $image->user_name = $elt['username'];
                $image->path = "./public/" . $elt['owner'] . "/" . $elt['path'];
                $image->palette = array_filter($elt, function($v) {
                    return (strpos($v, '#') !== false);
                });

                return $image;
            }, $r);
        } catch (PDOException $e) {
            echo 'Fail to get recent : ' . $e->getMessage();
            return [];
        }
    }

    static function getImagesByUser($userid) {
        try {
            $r = SPDO::getInstance()->all("SELECT `images`.*, `users`.`username`
                                                from `images`
                                                JOIN `users` ON `users`.`id` = `images`.`owner`
                                                WHERE `owner`=:owner
                                                ORDER by `created_at` DESC", [
                "owner" => htmlspecialchars($userid),
            ]);

            return array_map(function ($elt) {
                $image = new Image();
                $image->id = $elt['id'];
                $image->created_at = $elt['created_at'];
                $image->user_id = $elt['owner'];
                $image->user_name = $elt['username'];
                $image->path = "./public/" . $elt['owner'] . "/" . $elt['path'];
                $image->palette = array_filter($elt, function($v) {
                    return (strpos($v, '#') !== false);
                });

                return $image;
            }, $r);
        } catch (PDOException $e) {
            echo 'Fail images from user : ' . $e->getMessage();
            return [];
        }
    }

    private function savePrimaryColor() {
        $palette = Image::getPaletteFromImage($this->path);
        $p = SPDO::getInstance()->prepare('UPDATE `images` SET `color_1`=?, `color_2`=?, `color_3`=?, `color_4`=?, `color_5`=?
                                                WHERE `id`=' . $this->id);
        $r = $p->execute($palette);
    }

    static function uploadByUser($userid, $fileName, $tmpFile) {
        try {
            $p = SPDO::getInstance()->prepare('INSERT INTO `images` (`owner`, `path`) VALUES (:owner, :filename)');
            $r = $p->execute([
                "owner" => htmlspecialchars($userid),
                "filename" => htmlspecialchars($fileName),
            ]);

            $img = new Image();
            $img->id = SPDO::getInstance()->lastInsertId();
            $img->path = $tmpFile;
            $img->savePrimaryColor();

            return;
        } catch (PDOException $e) {
            echo 'Fail to upload : ' . $e->getMessage();
            header('Location: /profile.php');
            exit();
        }
    }

    static function deleteById($userid, $picid) {
        try {
            $r1 = SPDO::getInstance()->first("SELECT `path` from `images` WHERE `owner`=:owner and `id`=:id", [
                "owner" => $userid,
                "id" => $picid,
            ]);
            if (!$r1) return 'Impossible to delete image';

            $p = SPDO::getInstance()->prepare('DELETE FROM `images` WHERE owner=:owner AND id=:id');
            $r = $p->execute([
                "owner" => htmlspecialchars($userid),
                "id" => htmlspecialchars($picid),
            ]);
            unlink("./public/" . $userid . DIRECTORY_SEPARATOR . $r1['path']);

            return null;
        } catch (PDOException $e) {
            echo 'Fail to delete : ' . $e->getMessage();
            return 'Impossible to delete image';
        }
    }

    static function getImageByUserAndId($picid, $userid, $username) {
        try {
            $r = SPDO::getInstance()->first("SELECT `path`, `created_at` from `images` WHERE `owner`=:owner and `id`=:id", [
                "owner" => $userid,
                "id" => $picid,
            ]);

            if (!$r)
                return null;

            $image = new Image();
            $image->id = $picid;
            $image->created_at = $r['created_at'];
            $image->user_id = $userid;
            $image->user_name = $username;
            $image->path = "./public/" . $userid . "/" . $r['path'];

            return $image;
        } catch (PDOException $e) {
            echo 'Fail to delete : ' . $e->getMessage();
            return null;
        }
    }

    
    static function getImagesByColor($color) {
        try {
            $r = SPDO::getInstance()->all("SELECT `images`.*, `users`.`username`
                                                from `images`
                                                JOIN `users` ON `users`.`id` = `images`.`owner`
                                                WHERE color_1=:color
                                                        OR color_2=:color
                                                        OR color_3=:color
                                                        OR color_4=:color
                                                        OR color_5=:color
                                                ORDER by `created_at` DESC", [
                "color" => '#'.htmlspecialchars($color),
            ]);

            return array_map(function ($elt) {
                $image = new Image();
                $image->id = $elt['id'];
                $image->created_at = $elt['created_at'];
                $image->user_id = $elt['owner'];
                $image->user_name = $elt['username'];
                $image->path = "./public/" . $elt['owner'] . "/" . $elt['path'];
                $image->palette = array_filter($elt, function($v) {
                    return (strpos($v, '#') !== false);
                });

                return $image;
            }, $r);
        } catch (PDOException $e) {
            echo 'Fail images from user : ' . $e->getMessage();
            return [];
        }
    }

    private function applyFilter($img, $filterName) {
        switch ($filterName) {
            case 'contrast_minus':
                $img->brightnessContrastImage('0', '-10');
                break;
            case 'contrast_plus':
                $img->brightnessContrastImage('0', '10');
                break;
            case 'light_minus':
                $img->brightnessContrastImage('-10', '0');
                break;
            case 'light_plus':
                $img->brightnessContrastImage('10', '0');
                break;
            case 'sepia':
                $img->sepiaToneImage(80);
                break;
            case 'grayscale':
                $img->setImageColorspace(3);
                break;
            case 'gauss':
                $img->blurImage(5, 3);
                break;
            case 'edge':
                $img->edgeImage(0);
                break;
        }
        return $img;
    }

    public function withFilters($filters) {
        $image = new Imagick($this->path);
        foreach ($filters as $f) {
            $image = $this->applyFilter($image, $f);
        }

        return $image;
    }

    public function saveFilters($filters) {
        $tmpImage = $this->withFilters($filters);
        $tmpImage->writeImage($this->path);
        $this->savePrimaryColor();
    }

    public static function getPaletteFromImage($path) {
        $image = new Imagick($path);
        $image->scaleImage(50, 50, 0);

        $pixels = $image->getImageHistogram();
        $h = [];

        foreach($pixels as $p){
            $colors = $p->getColor();

            foreach (['r', 'g', 'b'] as $k)
                $$k = floor($colors[$k] / THREESHOLD) * THREESHOLD;

            $h[sprintf("#%02x%02x%02x", $r, $g, $b)] = ($h[sprintf("#%02x%02x%02x", $r, $g, $b)] ?? 0 ) + $p->getColorCount();
        }
        arsort($h);
        $h = array_keys(array_slice($h, 0, 5));
        return array_pad($h, 5, "#FFFFFF");
    }
}

?>