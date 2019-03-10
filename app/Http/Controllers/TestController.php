<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alorel\Dropbox\Operation\AbstractOperation;
use Alorel\Dropbox\Operation\Files\ListFolder;
use Alorel\Dropbox\Operation\Files\CreateFolder;
use Alorel\Dropbox\Operation\Files\Upload;

class TestController extends Controller
{
    public function test() {

        $path = "/Vip Pet Care Microchips";
//        if(isset($_POST['content']) && !empty($_POST['content'])) {
//            $content = $_POST['content'];
//        } else {
//            return false;
//        }
//        $file = tmpfile();
//        fwrite($file, $content);


        try {
            $key = "few8hPamiXcAAAAAAACJZyZJGVOvRhSGEKna8TkwoF4HOFdCcegd_cYtLITxfZuz";
//            $key = "few8hPamiXcAAAAAAADi-SGJiaOHbatwMb5skz_TrenAIMcLCl9OqFkY0rfn5vfR";
            AbstractOperation::setDefaultAsync(false);
            AbstractOperation::setDefaultToken($key);
            $create = new CreateFolder();
            $result = $create->raw($path);

            if (!empty($result->getStatusCode())) {
                if ($result->getStatusCode() == '200') {
                    var_dump($result); die();
                }
            }

            die("Failed");
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function upload(Request $request) {
        try {

            //            $key = "few8hPamiXcAAAAAAADi-SGJiaOHbatwMb5skz_TrenAIMcLCl9OqFkY0rfn5vfR";
            $key = "few8hPamiXcAAAAAAACJZyZJGVOvRhSGEKna8TkwoF4HOFdCcegd_cYtLITxfZuz";
            $path = "/Vip Pet Care Microchips";
            $filePath = sprintf("%s/%s-data.json", $path,date("y-m-d"));
            if(isset($_POST['content']) && !empty($_POST['content'])) {
                $content = $_POST['content'];
            } else {
                die("Value 'content' is empty");
            }

            AbstractOperation::setDefaultAsync(false);
            AbstractOperation::setDefaultToken($key);

            $file = tmpfile();
            fwrite($file, $content);
            $upload = new Upload();
            $result = $upload->raw($filePath, $file);

            if (!empty($result->getStatusCode())) {
                if ($result->getStatusCode() == '200') {
                    die("Success");
                }
            }

            die("Failed");
        } catch (\Exception $e) {
            die($e->getMessage());
        }

    }
}


//curl https://api.dropbox.com/oauth2/token \
//    -d code=<AUTHORIZATION_CODE> \
//-d grant_type=authorization_code \
//-d redirect_uri=<REDIRECT_URI> \
//-u <APP_KEY>:<APP_SECRET>