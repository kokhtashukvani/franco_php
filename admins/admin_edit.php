<?php
require_once '../db.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: admin_list.php");
    exit();
}

$id = $_GET['id'];

// Fetch the admin from the database
$stmt = $conn->prepare("SELECT first_name, last_name, username, email, phone_number, is_active FROM admins WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Admin not found.";
    exit();
}

$admin = $result->fetch_assoc();
$stmt->close();
?>
<html lang="fa" dir="rtl"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد مدیریت</title>
    <link href="https://cdn.fontcdn.ir/Font/Persian/Vazir/Vazir.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.rtl.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <style>
        /* CSS styles remain the same */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Vazir',sans-serif;
        }

        body {
            background: #f5f5f5;
            font-family: "IRANSans", sans-serif;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: #263238;
            color: white;
            display: flex;
            flex-direction: column;
        }

            .sidebar h5 {
                padding: 1rem;
                font-weight: bold;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }

            .sidebar a {
                color: white;
                padding: 0.75rem 1rem;
                display: block;
                text-decoration: none;
            }

                .sidebar a:hover {
                    background: rgba(255,255,255,0.1);
                }

        .content {
            flex: 1;
            padding: 1rem;
        }

        .stat-card {
            color: white;
            padding: 1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

            .stat-card i {
                font-size: 2.5rem;
                opacity: 0.7;
            }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 1rem;
            margin-top: 1rem;
            overflow-x: auto;
        }

        .table thead {
            background: #eeeeee;
            position: sticky;
            top: 0;
        }

        .table-hover tbody tr:hover {
            background: #f1f8ff;
        }

        .btn-details {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            color: #1565c0;
            font-weight: bold;
            padding: 2px 10px;
            border-radius: 6px;
        }

        .info-box {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            height: 100%;
        }

        header {
            background-color: #fff;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            background-color: #fff;
        }

        dl.row dt,
        dl.row dd {
            margin-bottom: 10px; /* فاصله بین هر سطر */
        }

            dl.row dd img {
                display: block;
                margin-top: 5px; /* فاصله بین متن و تصویر */
            }

        /* کانتینر Toast: همه Toast ها در اینجا قرار می‌گیرند */
        #toast-container {
            position: fixed; /* همیشه روی صفحه نمایش داده می‌شود */
            top: 1rem;
            right: 1rem;
            z-index: 1055;
            display: flex;
            flex-direction: column;
            gap: 0.5rem; /* فاصله بین Toast ها */
        }

        /* استایل پایه Toast */
        .toast-custom {
            min-width: 250px;
            max-width: 350px;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.2);
            animation: fadein 0.5s, fadeout 0.5s 3.5s; /* انیمیشن ظاهر و محو شدن */
            font-family: Arial, sans-serif;
            font-size: 0.9rem;
        }

        /* رنگ‌های مختلف برای نوع پیام */
        .toast-success {
            background-color: #28a745;
        }
        /* سبز: موفقیت */
        .toast-error {
            background-color: #dc3545;
        }
        /* قرمز: خطا */
        .toast-warning {
            background-color: #ffc107;
            color: #000;
        }
        /* زرد: هشدار */

        /* دکمه بستن */
        .toast-custom button {
            background: transparent;
            border: none;
            color: inherit;
            font-size: 1rem;
            cursor: pointer;
        }

        /* انیمیشن‌های Fade In و Fade Out */
        @keyframes fadein {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeout {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-date/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Vazir', sans-serif;
        }
    </style>

       <!-- ✅ اینجا -->
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h5>مدیریت سایت</h5>
            <a href="/AdminPanel/Dashboard/Index"><i class="fa fa-home"></i> صفحه اصلی</a>
            <a href="../news/news_list.php"><i class="fa fa-bullhorn"></i> اخبار</a>
            <a href="../Announcement/announcement_list.php"><i class="fa fa-bullhorn"></i> اطلاعیه‌ها</a>
            <a href="../brand/brand_list.php"><i class="fa fa-bullhorn"></i> برند</a>
            <a href="/AdminPanel/Product/Groups"><i class="fa fa-box"></i> انبار کالا</a>
            <a href="../dealer/dealer_list.php"><i class="fa fa-users"></i> نمایندگی‌ها</a>
            <a href="../reports/reports.php"><i class="fa fa-shopping-cart"></i> گزارش سفارشات</a>
            <a href="admin_list.php"><i class="fa fa-shopping-cart"></i> کاربران سیستم</a>
            <hr style="border-color: rgba(255,255,255,0.1);">
            <a href="/Home/Index"><i class="fa fa-sign-in-alt"></i> ورود به سامانه اصلی</a>
        </div>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto content">
            <header><h5>ویرایش کاربر سیستم</h5></header>

            <div class="card p-4 mt-3">
                <div class="col-md-4">
                    <form action="admin_update.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="form-group">
                            <label class="form-label" for="first_name">نام</label>
                            <input class="form-control" id="first_name" name="first_name" type="text" value="<?php echo htmlspecialchars($admin['first_name']); ?>">
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                            <label class="form-label" for="last_name">نام خانوادگی</label>
                            <input class="form-control" id="last_name" name="last_name" type="text" value="<?php echo htmlspecialchars($admin['last_name']); ?>">
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                            <label class="form-label" for="username">نام کاربری</label>
                            <input class="form-control" id="username" name="username" type="text" value="<?php echo htmlspecialchars($admin['username']); ?>">
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                            <label class="form-label" for="email">ایمیل</label>
                            <input class="form-control" id="email" name="email" type="text" value="<?php echo htmlspecialchars($admin['email']); ?>">
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                            <label class="form-label" for="phone_number">تلفن</label>
                            <input class="form-control" id="phone_number" name="phone_number" type="text" value="<?php echo htmlspecialchars($admin['phone_number']); ?>">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" <?php echo $admin['is_active'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">کاربر فوق فعال میباشد</label>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ثبت</button>
                            &nbsp;&nbsp;
                            <a href="admin_list.php" class="btn btn-warning">
                                <i class="fa fa-times"></i> انصراف
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
