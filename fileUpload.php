<?PHP

set_time_limit(0);

$upload_dir = "upload/";

$action = $_GET['action'];
if ($action == 'init') {
    // 初始化上传
    $files = $_POST['files'];
    $return = array();
    foreach ($files as $v) {
        if (file_exists($upload_dir . $v['name'])) {
            if (filesize($upload_dir . $v['name']) == $v['size']) {
                $return[] = false;
            } else {
                @unlink($upload_dir . $v['name']);
                $return[] = true;
            }
        } else {
            $return[] = true;
        }
    }
    exit(json_encode($return));
} elseif ($action == 'upload') {
    // 上传文件片段
    try {
        $src = fopen('php://input', 'rb');
        if ($dest = fopen($upload_dir . $_GET['name'], 'cb')) {
            fseek($dest, $_GET['start']);
            stream_copy_to_stream($src, $dest, $_GET['length']);
            fclose($dest);
        }
        exit(json_encode(array('error' => 0)));
    } catch (Exception $e) {
        header("Status:500 Internal Server Error");
        exit;
    }
} elseif ($action == 'delete') {
    // 删除上传文件
    @unlink($upload_dir . $_GET['name']);
    print_r($_POST);
}

