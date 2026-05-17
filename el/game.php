<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>لعبة أسئلة بسيطة</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            text-align: center;  
            background: radial-gradient(circle, rgb(90, 20, 20), rgb(50, 10, 10));
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .card { 
            background-color: rgb(250, 250, 240);
            padding: 30px; 
            width: 70%;
            max-width: 350px; 
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(255, 255, 255, 0.2); 
            transition: transform 0.3s ease;
        }

        #gameCard { display: none; }

        h2 { 
            color: #333; 
            margin-bottom: 25px;
            font-size: 1.5rem;
        }

        input {
            width: 90%;
            padding: 12px;
            margin-bottom: 12px;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            text-align: center;
            font-family: inherit;
        }

        button { 
            display: block; 
            width: 100%; 
            margin: 15px 0;
            padding: 15px; 
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1rem;
            background: linear-gradient(145deg, #c0c0c0, #808080);
            color: #ffffff; 
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        button:hover {
            filter: brightness(1.1);
            transform: translateY(-2px);
        }

        button:active {
            transform: scale(0.96);
        }
    </style>
</head>
<body>

<!-- كرت الحماية المطور -->
<div class="card" id="loginCard">
    
    <!-- قسم تسجيل الدخول -->
    <div id="loginSection">
        <h2>تسجيل الدخول للعب 🔐</h2>
        <input type="text" id="gameUsername" placeholder="اسم المستخدم">
        <input type="password" id="gamePassword" placeholder="كلمة المرور">
        <button onclick="verifyPassword()">دخول اللعبة</button>
        <p style="font-size: 0.9rem; color: #555;">ليس لديك حساب؟ <a href="#" onclick="toggleAuth('signup')" style="color: darkblue; font-weight: bold; text-decoration: none;">سجل الآن</a></p>
    </div>

    <!-- قسم إنشاء حساب جديد (Sign Up) -->
    <div id="signupSection" style="display: none;">
        <h2>إنشاء حساب جديد 👤</h2>
        <input type="text" id="newUsername" placeholder="اختر اسم مستخدم">
        <input type="password" id="newPassword" placeholder="اختر كلمة مرور قوية">
        <button onclick="registerUser()" style="background: linear-gradient(145deg, #4CAF50, #2E7D32);">إنشاء الحساب</button>
        <p style="font-size: 0.9rem; color: #555;">لديك حساب بالفعل؟ <a href="#" onclick="toggleAuth('login')" style="color: darkblue; font-weight: bold; text-decoration: none;">تسجيل الدخول</a></p>
    </div>

    <!-- حقل الملاحظات والرسائل -->
    <p id="loginError" style="color: red; font-weight: bold; margin-top: 10px; font-size: 0.95rem;"></p>
</div>

<!-- كرت اللعبة الأساسي -->
<div class="card" id="gameCard">
    <h2 id="ques">السؤال يظهر هنا</h2>
 
    <button onclick="check(0)" id="btn0">اختيار 1</button>
    <button onclick="check(1)" id="btn1">اختيار 2</button>
    <button onclick="check(2)" id="btn2">اختيار 3</button>
    
    <p id="res"></p>
    <p>النقاط: <span id="scr">0</span></p>
    <p>الوقت المتبقي: <span id="timer" style="color: rgb(100, 20, 20); font-weight: bold;">10</span> ثانية</p>
</div>

<script>
// التبديل بين واجهة الدخول والتسجيل
function toggleAuth(mode) {
    const loginSec = document.getElementById('loginSection');
    const signupSec = document.getElementById('signupSection');
    const errorField = document.getElementById('loginError');
    
    errorField.innerText = ""; 
    
    if (mode === 'signup') {
        loginSec.style.display = 'none';
        signupSec.style.display = 'block';
    } else {
        loginSec.style.display = 'block';
        signupSec.style.display = 'none';
    }
}

// دالة إنشاء الحساب (ترسل البيانات إلى ملف signup.php)
function registerUser() {
    const username = document.getElementById('newUsername').value;
    const password = document.getElementById('newPassword').value;
    const errorField = document.getElementById('loginError');

    if(!username || !password) {
        errorField.style.color = "red";
        errorField.innerText = "الرجاء ملء جميع الحقول!";
        return;
    }

    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    fetch('jj.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            errorField.style.color = "green";
            errorField.innerText = data.message;
            setTimeout(() => { toggleAuth('login'); }, 2000);
        } else {
            errorField.style.color = "red";
            errorField.innerText = data.message;
        }
    })
    .catch(error => {
        errorField.style.color = "red";
        errorField.innerText = "حدث خطأ في الاتصال بالسيرفر!";
    });
}

// دالة التحقق من الحساب (ترسل البيانات إلى ملف exe.php كما حددت في كودك)
function verifyPassword() {
    const username = document.getElementById('gameUsername').value;
    const password = document.getElementById('gamePassword').value;
    const errorField = document.getElementById('loginError');

    if(!username || !password) {
        errorField.style.color = "red";
        errorField.innerText = "الرجاء إدخال اسم المستخدم وكلمة المرور!";
        return;
    }

    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    fetch('exe.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('loginCard').style.display = 'none';
            document.getElementById('gameCard').style.display = 'block';
            show(); 
        } else {
            errorField.style.color = "red";
            errorField.innerText = data.message;
        }
    })
    .catch(error => {
        errorField.style.color = "red";
        errorField.innerText = "حدث خطأ في الاتصال بالسيرفر!";
    });
}

// إعدادات اللعبة
var questions = ["ما هو لون السماء؟", "ما هو عاصمة المغرب؟", "1 + 1 = ?"];
var choices = [
    ["أحمر", "أزرق", "أخضر"],
    ["الرباط", "باريس", "لندن"],
    ["5", "3", "2"]
];
var answers = ["أزرق", "الرباط", "2"];

var index = 0;
var score = 0;
var timeLeft = 10;
var timer; 

function startTimer() {
    timeLeft = 10;
    document.getElementById("timer").innerText = timeLeft;
    clearInterval(timer); 

    timer = setInterval(function() {
        timeLeft--;
        document.getElementById("timer").innerText = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(timer);
            check(-1);
        }
    }, 1000);
}

function show() {
    if (index < questions.length) {
        document.getElementById("ques").innerText = questions[index];
        document.getElementById("btn0").innerText = choices[index][0];
        document.getElementById("btn1").innerText = choices[index][1];
        document.getElementById("btn2").innerText = choices[index][2];
        document.getElementById("res").innerText = ""; 
        
        startTimer(); 
    } else {
        clearInterval(timer);
        showFinalResult();
    }
}

function check(btnNumber) {
    clearInterval(timer); 
    var correctChoice = answers[index];
    var userChoice = (btnNumber === -1) ? "انتهى الوقت" : choices[index][btnNumber];

    if (userChoice === correctChoice) {
        score++;
        document.getElementById("res").innerText = "صح! ✅";
    } else {
        document.getElementById("res").innerText = (btnNumber === -1) ? "انتهى الوقت! ⏰" : "خطأ! ❌";
    }

    document.getElementById("scr").innerText = score;
    
    index++;
    setTimeout(show, 1500); 
}

function showFinalResult() {
    var status = (score >= 2) ? "Smart 🧠" : "Stupid 🤡";
    var statusColor = (score >= 2) ? "#4CAF50" : "#ff4444";
    
    // نقوم بتغيير محتوى كرت اللعبة نفسه بدلاً من مسح الصفحة بالكامل document.body
    document.getElementById('gameCard').innerHTML = `
        <div style="text-align:center; padding:20px;">
            <h1 style="color:#333">انتهت اللعبة! 🏁</h1>
            <div style="color:${statusColor}; font-size: 40px; font-weight: bold; margin: 20px;">
                ${status}
            </div>
            <p style="color:#555; font-size: 1.2rem;">نقاطك النهائية هي: ${score} من ${questions.length}</p>
            
            <!-- عند الضغط هنا ستفتح اللعبة فوراً بدون طلب كلمة مرور -->
            <button onclick="restartGame()" style="background: linear-gradient(145deg, #1e3c72, #2a5298); color:white; padding: 15px; border-radius:10px; font-weight:bold;">إعادة اللعب مباشرة 🔄</button>
        </div>
    `;
}
function restartGame() {
    // 1. إعادة تصفير المتغيرات الحسابية للعبة
    index = 0;
    score = 0;
    
    // 2. إعادة بناء الواجهة الأصلية لكرت الأسئلة بداخل كرت اللعبة
    document.getElementById('gameCard').innerHTML = `
        <h2 id="ques">السؤال يظهر هنا</h2>
     
        <button onclick="check(0)" id="btn0">اختيار 1</button>
        <button onclick="check(1)" id="btn1">اختيار 2</button>
        <button onclick="check(2)" id="btn2">اختيار 3</button>
        
        <p id="res"></p>
        <p>النقاط: <span id="scr">0</span></p>
        <p>الوقت المتبقي: <span id="timer" style="color: rgb(100, 20, 20); font-weight: bold;">10</span> ثانية</p>
    `;
    
    // 3. تشغيل اللعبة فوراً دون المرور بكرت الحماية
    show();
}
</script>
</body>
</html>