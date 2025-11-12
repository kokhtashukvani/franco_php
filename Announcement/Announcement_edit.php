<?php
include 'db.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['Id'];
    $release_date = $_POST['ReleaseDateShamsi'];
    $title = $_POST['Title'];
    $description = $_POST['Description'];
    $is_show = isset($_POST['IsShow']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE announcements SET release_date = ?, title = ?, description = ?, is_show = ? WHERE id = ?");
    $stmt->bind_param("sssii", $release_date, $title, $description, $is_show, $id);

    if ($stmt->execute()) {
        header("Location: Announcement.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}
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
    <a href="/AdminPanel/News/List"><i class="fa fa-bullhorn"></i> اخبار</a>
    <a href="/AdminPanel/Announcement/List"><i class="fa fa-bullhorn"></i> اطلاعیه‌ها</a>
    <a href="/AdminPanel/Brand/List"><i class="fa fa-bullhorn"></i> برند</a>
    <a href="/AdminPanel/Product/Groups"><i class="fa fa-box"></i> انبار کالا</a>
    <a href="/AdminPanel/Agent/List"><i class="fa fa-users"></i> نمایندگی‌ها</a>
    <a href="/AdminPanel/FrancoShopReports/SalesReport"><i class="fa fa-shopping-cart"></i> گزارش سفارشات</a>
    <a href="/AdminPanel/Account/UserList"><i class="fa fa-shopping-cart"></i> کاربران سیستم</a>
    <hr style="border-color: rgba(255,255,255,0.1);">
    <a href="/Home/Index"><i class="fa fa-sign-in-alt"></i> ورود به سامانه اصلی</a>
</div>


        <!-- Main Content -->
        


<main class="col-md-10 ms-sm-auto content">

    
<header class="d-flex justify-content-between align-items-center mb-3">
    <h5>مدیریت اطلاعیه‌ها &gt; ویرایش اطلاعیه</h5>
<form action="/AdminPanel/Account/Logout" area="" method="post">            <button type="submit" class="btn btn-danger">
                <i class="fa fa-sign-out-alt"></i> خروج
            </button>
<input name="__RequestVerificationToken" type="hidden" value="CfDJ8KYTGIkQdfxCtALZgV80tFcKQHRU5oEqASoF5nhMJXnb-8yZPR__MgnzHG4za_EK9QvAx7nj0i-yqw69FmFEUknmQpHrfqA9YDp1KEOgjQ_aYgBQUdixYm41ApX6ip5RwczueXcBwUkH75nSRWNllSSXIl5G14k-x-CXjfzs8FUsXxkKgc1lFmmlVzTNOq6P4Q"></form></header>


    <div class="card p-4 mt-3">
<form action="Announcement_edit.php?id=<?php echo $id; ?>" class="card-body" enctype="multipart/form-data" method="post">
    <input type="hidden" name="Id" value="<?php echo $id; ?>">
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label" for="ReleaseDateShamsi">تاریخ</label>
            <div class="input-group">
                <input class="form-control pwt-datepicker-input-element" id="announcementDate" name="ReleaseDateShamsi" type="text" value="<?php echo $row['release_date']; ?>">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <label class="form-label" for="Title">عنوان</label>
            <input class="form-control" id="title" name="Title" type="text" value="<?php echo $row['title']; ?>">
        </div>
    </div>
    <div class="row mb-3">
        <label class="form-label" for="Description">توضیحات</label>
        <textarea class="form-control" id="decsription" name="Description" rows="6" style="width:100% !important;"><?php echo $row['description']; ?></textarea>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <input class="form-check-input" id="is-show" name="IsShow" type="checkbox" value="true" <?php if($row['is_show']) echo 'checked'; ?>>
            <label class="form-label" for="is-show">نمایش/عدم نمایش</label>
        </div>
    </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ثبت</button>
                &nbsp;&nbsp;
                <a href="/AdminPanel/Announcement/List" class="btn btn-warning">
                    <i class="fa fa-times"></i> انصراف
                </a>
            </div>
<input name="__RequestVerificationToken" type="hidden" value="CfDJ8KYTGIkQdfxCtALZgV80tFcKQHRU5oEqASoF5nhMJXnb-8yZPR__MgnzHG4za_EK9QvAx7nj0i-yqw69FmFEUknmQpHrfqA9YDp1KEOgjQ_aYgBQUdixYm41ApX6ip5RwczueXcBwUkH75nSRWNllSSXIl5G14k-x-CXjfzs8FUsXxkKgc1lFmmlVzTNOq6P4Q"><input name="IsShow" type="hidden" value="false"></form>

    </div>
</main>


    </div>
    
            <script>
                $(function () {
                    $("#announcementDate").persianDatepicker({
                        format: "YYYY/MM/DD",
                        autoClose: true,
                        calendarType: "persian",
                        initialValue: false
                    });
                });
            </script>
        

    <!-- کانتینر Toast -->
    <div id="toast-container"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<div id="persianDateInstance-4" class="datepicker-container pwt-hide" style="left: 1304px; top: 214px;"><div id="plotId" class="datepicker-plot-area  datepicker-state-no-meridian   datepicker-persian">
        <div data-navigator="" class="datepicker-navigator">
            <div class="pwt-btn pwt-btn-next">&lt;</div>
            <div class="pwt-btn pwt-btn-switch">۷۸۳ تیر</div>
            <div class="pwt-btn pwt-btn-prev">&gt;</div>
        </div>
    <div class="datepicker-grid-view">
        <div class="datepicker-day-view">    
            <div class="month-grid-box">
                <div class="header">
                    <div class="title"></div>
                    <div class="header-row">
                            <div class="header-row-cell">ش</div>
                            <div class="header-row-cell">ی</div>
                            <div class="header-row-cell">د</div>
                            <div class="header-row-cell">س</div>
                            <div class="header-row-cell">چ</div>
                            <div class="header-row-cell">پ</div>
                            <div class="header-row-cell">ج</div>
                    </div>
                </div>    
                <table cellspacing="0" class="table-days">
                    <tbody>
                           
                            <tr>
                                        <td data-date="783,3,26" data-unix="-17846837951000">
                                            <span class="other-month">۲۶</span>
                                        </td>
                                    
                                        <td data-date="783,3,27" data-unix="-17846751551000">
                                            <span class="other-month">۲۷</span>
                                        </td>
                                    
                                        <td data-date="783,3,28" data-unix="-17846665151000">
                                            <span class="other-month">۲۸</span>
                                        </td>
                                    
                                        <td data-date="783,3,29" data-unix="-17846578751000">
                                            <span class="other-month">۲۹</span>
                                        </td>
                                    
                                        <td data-date="783,3,30" data-unix="-17846492351000">
                                            <span class="other-month">۳۰</span>
                                        </td>
                                    
                                        <td data-date="783,3,31" data-unix="-17846405951000">
                                            <span class="other-month">۳۱</span>
                                        </td>
                                    
                                        <td data-date="783,4,1" data-unix="-17846319551000">
                                            <span class="">۱</span>
                                        </td>
                                    
                            </tr>
                           
                            <tr>
                                        <td data-date="783,4,2" data-unix="-17846233151000">
                                            <span class="">۲</span>
                                        </td>
                                    
                                        <td data-date="783,4,3" data-unix="-17846146751000">
                                            <span class="">۳</span>
                                        </td>
                                    
                                        <td data-date="783,4,4" data-unix="-17846060351000">
                                            <span class="">۴</span>
                                        </td>
                                    
                                        <td data-date="783,4,5" data-unix="-17845973951000">
                                            <span class="">۵</span>
                                        </td>
                                    
                                        <td data-date="783,4,6" data-unix="-17845887551000">
                                            <span class="">۶</span>
                                        </td>
                                    
                                        <td data-date="783,4,7" data-unix="-17845801151000">
                                            <span class="">۷</span>
                                        </td>
                                    
                                        <td data-date="783,4,8" data-unix="-17845714751000">
                                            <span class="">۸</span>
                                        </td>
                                    
                            </tr>
                           
                            <tr>
                                        <td data-date="783,4,9" data-unix="-17845628351000">
                                            <span class="">۹</span>
                                        </td>
                                    
                                        <td data-date="783,4,10" data-unix="-17845541951000">
                                            <span class="">۱۰</span>
                                        </td>
                                    
                                        <td data-date="783,4,11" data-unix="-17845455551000">
                                            <span class="">۱۱</span>
                                        </td>
                                    
                                        <td data-date="783,4,12" data-unix="-17845369151000">
                                            <span class="">۱۲</span>
                                        </td>
                                    
                                        <td data-date="783,4,13" data-unix="-17845282751000">
                                            <span class="">۱۳</span>
                                        </td>
                                    
                                        <td data-date="783,4,14" data-unix="-17845196351000">
                                            <span class="">۱۴</span>
                                        </td>
                                    
                                        <td data-date="783,4,15" data-unix="-17845109951000">
                                            <span class="">۱۵</span>
                                        </td>
                                    
                            </tr>
                           
                            <tr>
                                        <td data-date="783,4,16" data-unix="-17845023551000">
                                            <span class="">۱۶</span>
                                        </td>
                                    
                                        <td data-date="783,4,17" data-unix="-17844937151000">
                                            <span class="">۱۷</span>
                                        </td>
                                    
                                        <td data-date="783,4,18" data-unix="-17844850751000">
                                            <span class="">۱۸</span>
                                        </td>
                                    
                                        <td data-date="783,4,19" data-unix="-17844764351000">
                                            <span class="">۱۹</span>
                                        </td>
                                    
                                        <td data-date="783,4,20" data-unix="-17844677951000">
                                            <span class="">۲۰</span>
                                        </td>
                                    
                                        <td data-date="783,4,21" data-unix="-17844591551000">
                                            <span class="">۲۱</span>
                                        </td>
                                    
                                        <td data-date="783,4,22" data-unix="-17844505151000">
                                            <span class="">۲۲</span>
                                        </td>
                                    
                            </tr>
                           
                            <tr>
                                        <td data-date="783,4,23" data-unix="-17844418751000">
                                            <span class="">۲۳</span>
                                        </td>
                                    
                                        <td data-date="783,4,24" data-unix="-17844332351000">
                                            <span class="">۲۴</span>
                                        </td>
                                    
                                        <td data-date="783,4,25" data-unix="-17844245951000" class="selected">
                                            <span class="">۲۵</span>
                                        </td>
                                    
                                        <td data-date="783,4,26" data-unix="-17844159551000">
                                            <span class="">۲۶</span>
                                        </td>
                                    
                                        <td data-date="783,4,27" data-unix="-17844073151000">
                                            <span class="">۲۷</span>
                                        </td>
                                    
                                        <td data-date="783,4,28" data-unix="-17843986751000">
                                            <span class="">۲۸</span>
                                        </td>
                                    
                                        <td data-date="783,4,29" data-unix="-17843900351000">
                                            <span class="">۲۹</span>
                                        </td>
                                    
                            </tr>
                           
                            <tr>
                                        <td data-date="783,4,30" data-unix="-17843813951000">
                                            <span class="">۳۰</span>
                                        </td>
                                    
                                        <td data-date="783,4,31" data-unix="-17843727551000">
                                            <span class="">۳۱</span>
                                        </td>
                                    
                                        <td data-date="783,5,1" data-unix="-17843637552000">
                                            <span class="other-month">۱</span>
                                        </td>
                                    
                                        <td data-date="783,5,2" data-unix="-17843551152000">
                                            <span class="other-month">۲</span>
                                        </td>
                                    
                                        <td data-date="783,5,3" data-unix="-17843464752000">
                                            <span class="other-month">۳</span>
                                        </td>
                                    
                                        <td data-date="783,5,4" data-unix="-17843378352000">
                                            <span class="other-month">۴</span>
                                        </td>
                                    
                                        <td data-date="783,5,5" data-unix="-17843291952000">
                                            <span class="other-month">۵</span>
                                        </td>
                                    
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
    
    
    </div>
    
    <div class="toolbox">
            <div class="pwt-btn-today">امروز</div>
            <div class="pwt-btn-calendar">July</div>
    </div>
</div></div></body></html>