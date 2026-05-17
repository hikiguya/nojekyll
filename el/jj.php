<?php
header('Content-Type: application/json');

$h = 'localhost';
$d = 'dar';
$u = 'root';
$p = '';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user_input = trim($_POST['username']);
    $pass_input = $_POST['password'];

    if (empty($user_input) || empty($pass_input)) {
        echo json_encode(['status' => 'error', 'message' => 'الحقول لا يمكن أن تكون فارغة!']);
        exit;
    }
   

    try {
        $pdo = new PDO("mysql:host=$h;dbname=$d;charset=utf8", $u, $p);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 1. التحقق أولاً مما إذا كان اسم المستخدم محجوزاً مسبقاً
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $checkStmt->execute([$user_input]);
        if ($checkStmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'اسم المستخدم هذا مسجل بالفعل! اختر اسماً آخر.']);
            exit;
        }

        // 2. تشفير كلمة المرور بشكل آمن للغاية (Hashing)
        $hashed_password = password_hash($pass_input, PASSWORD_DEFAULT);

        // 3. إدخال المستخدم الجديد في قاعدة البيانات
        $insertStmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insertStmt->execute([$user_input, $hashed_password]);

        echo json_encode(['status' => 'success', 'message' => 'تم إنشاء الحساب بنجاح! جاري التحويل...']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'فشل الاتصال أو خطأ في قاعدة البيانات.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'طلب غير صالح.']);
}
?>