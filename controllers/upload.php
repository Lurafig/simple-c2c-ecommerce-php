<?php

$input_name = "files";
$updir_name = "files";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $funcs = [
        "a" => fn($a) => handleFileUpload($a),
        "r" => fn($a) => removeUploadedFiles($a)
    ];
    
    $r = json_decode(
        file_get_contents("php://input"),
        flags: JSON_OBJECT_AS_ARRAY
    );

    $y = isset($r) && is_array($r) ? $r : $_POST;

    print_r (
        json_encode(
            $funcs[$y["func"]]($y["param"])
            )
    );
};

function handleFileUpload($fileIds){
    global $updir_name, $input_name, $uploaded_images;

    $files = $_POST[$input_name];
    
    if (count($files) > 5) {
        return [
            "err_code" => 1, 
            "err_message" => "Limite de imagens excedido (5)",
            "f" => $files
        ];
    }
    
    $paths = [];
    $ids = json_decode($fileIds);

    $uploadDir = "..\\$updir_name\\";
    
    if (!is_dir($uploadDir)){
        mkdir($uploadDir);
    }

    for($o = 0; $o < count($files); $o++){
        $file = tmpfile();
        
        $path =  stream_get_meta_data($file)["uri"];

        $data = explode(",", $files[$o])[1];
        $chunkSize = 8192;

        // fwrite($file, base64_decode($data));
        for($i = 0; $i < strlen($data); $i += $chunkSize){
            $chunk = substr($data, $i, $chunkSize);
            fwrite($file, base64_decode($chunk));
        }
        
        $dest =  $uploadDir . $ids[$o] . ".png";
        $paths[] = $dest;

        rename($path, $dest);

        fclose($file);
    }

    return [
        "err_code" => 0,
        "err_message" => "Sucesso",
        "files_path" => json_encode($paths)
    ];
};

function removeUploadedFiles($ids) {
    $ids = json_decode($ids);

    set_error_handler(function(){});

    for($i = 0; $i < count($ids); $i++){
        unlink($ids[$i]);
    };

    restore_error_handler();

    return [
        "err_code" => 0,
        "err_message" => "Funcionando"
    ];
};
    // if(!is_dir($uploadDir)){
    //     mkdir($uploadDir);
    // }
    // echo "djas";
    // $target_dir = "files/";
    // $target_file = $target_dir . basename($_FILES["file"]["name"]);
    // $uploadOk = 1;
    // $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // if(isset($_POST["submit"])) {
    //     $check = getimagesize($_FILES["file"]["tmp_name"]);
    //     if($check !== false) {
    //         echo "File is an image - " . $check["mime"] . ".";
    //         $uploadOk = 1;
    //     } else {
    //         echo "File is not an image.";
    //         $uploadOk = 0;
    //     }
    // } else {
    //     echo "isjicdjijijijixndi";
    // }
    // echo "nuihuiwhidji";
// }

?>
<?php ob_start() ?>
<script>
    if("<?php echo $success ?>" == 1) {
        console.log("Sucesso no upload do arquivo");
    } else {
        console.log("Falha no upload do arquivo");
    }
</script>
<?php ob_end_clean()?>