<?php
header('Content-Type: application/json');

$h = 'localhost';
$d = 'dar';
$u = 'root';
$p = '';

// التأكد من إرسال الحقلين معاً
if (isset($_POST['username']) && isset($_POST['password'])) {
    $user_input = $_POST['username'];
    $pass_input = $_POST['password'];

    try {
        $pdo = new PDO("mysql:host=$h;dbname=$d;charset=utf8", $u, $p);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // البحث في قاعدة البيانات عن المستخدم المدخل
        $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->execute([$user_input]); 
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // إذا وُجد المستخدم، نقوم بفحص كلمة المرور المشفرة الخاصة به
        if ($user && password_verify($pass_input, $user['password'])) {
            echo json_encode(['status' => 'success']);
        } else {
            // رسالة موحدة ومبهمة لأسباب أمنية (حتى لا يعرف المخترق هل الخطأ في الاسم أم كلمة المرور)
            echo json_encode(['status' => 'error', 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة!']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'فشل الاتصال بقاعدة البيانات.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'طلب غير مكتمل.']);
}
?>