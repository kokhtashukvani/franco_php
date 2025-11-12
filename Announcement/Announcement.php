<?php
include 'db.php';

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: Announcement.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order = json_decode(file_get_contents("php://input"));
    $stmt = $conn->prepare("UPDATE announcements SET display_order = ? WHERE id = ?");
    foreach ($order as $item) {
        $id = $item->id;
        $display_order = $item->order;
        $stmt->bind_param("ii", $display_order, $id);
        $stmt->execute();
    }
    $stmt->close();
    exit;
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
    <h5>مدیریت اطلاعیه‌ها</h5>
<form action="/AdminPanel/Account/Logout" area="" method="post">            <button type="submit" class="btn btn-danger">
                <i class="fa fa-sign-out-alt"></i> خروج
            </button>
<input name="__RequestVerificationToken" type="hidden" value="CfDJ8KYTGIkQdfxCtALZgV80tFdCTOR8TaU8fJqSfDRqplsqBsiIRWPqCR_RtWqIaVPAnszIY8x4bqHtKvoq0FTv0mcoIr3JiA161vW19zddrse34hGPa1igORhCoPCdLKF97h0IxVD8ZU7H2R_uBQHf620kWnNeljOIOAxZdX5_I3ySA3d4JSpOupa0ZWAfmCl7xA"></form></header>


    <div class="card p-4 mt-3">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <div class="btn-group">
                <a class="btn btn-primary" href="/AdminPanel/Announcement/Register"><i class="fa fa-plus"></i> ایجاد جدید</a>
            </div>
            <button class="btn btn-secondary"><i class="fa fa-sync"></i> بارگذاری مجدد لیست</button>

            <button id="saveOrderBtn" class="btn btn-success btn-sm"><i class="fa fa-save"></i> ذخیره ترتیب</button>

            <a href="/AdminPanel/Adver/Edit" class="btn btn-info text-white"><i class="fa fa-list"></i> متن خاص بخش مطافق</a>
        </div>

        <div id="crudTableContainer" class="table-responsive">
            
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>


<!-- Modal (hidden inputs no initial value) -->
<div class="modal fade" id="uploadExcelModal" tabindex="-1" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadExcelModalLabel">
                    <i class="fa fa-file-excel"></i> آپلود محصولات با اکسل
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <form id="excelUploadForm" enctype="multipart/form-data">
                    <input type="hidden" name="categoryId">
                    <input type="hidden" name="brandId">

                    <div class="mb-3">
                        <label for="excelFile" class="form-label">انتخاب فایل اکسل</label>
                        <input type="file" class="form-control" id="excelFile" name="file" accept=".xlsx,.xls" required="">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fa fa-upload"></i> ارسال فایل
                        </button>
                        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- داخل foreach ردیف‌ها: دکمهٔ آپلود با data-category -->




<table id="crudTable" class="table table-bordered table-striped">
    <thead class="table-header" style="background:#5986b3 !important">
        <tr>

                    <th style="width:8%">ترتیب نمایش</th>
                    <th style="text-align:center; vertical-align:middle">نمایش</th>
                    <th style="text-align:center; vertical-align:middle">عنوان</th>
                    <th style="text-align:center; vertical-align:middle">تاریخ</th>
            <th style="text-align:center; vertical-align:middle">عملیات</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'db.php';
        $sql = "SELECT * FROM announcements ORDER BY display_order ASC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr data-id='" . $row["id"] . "'>";
                echo "<td><i class='fa fa-bars drag-handle'></i> &nbsp;&nbsp; " . $row["display_order"] . "</td>";
                echo "<td class='text-center'>";
                if ($row["is_show"]) {
                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='green' viewBox='0 0 16 16'><path d='M13.485 1.929a1 1 0 0 1 .086 1.414l-7.071 8a1 1 0 0 1-1.497 0l-3.536-4a1 1 0 1 1 1.497-1.328L6 8.586l6.536-7.414a1 1 0 0 1 .949-.243z'></path></svg>";
                }
                echo "</td>";
                echo "<td class='text-center'>" . $row["title"] . "</td>";
                echo "<td class='text-center'>" . $row["release_date"] . "</td>";
                echo "<td class='text-center'>";
                echo "<a class='btn btn-sm btn-primary' href='Announcement_edit.php?id=" . $row["id"] . "'><i class='fa fa-edit'></i></a>";
                echo "<a class='btn btn-sm btn-danger' href='Announcement.php?delete_id=" . $row["id"] . "' onclick='return confirm(\"آیا از حذف این رکورد مطمئن هستید؟\");'><i class='fa fa-trash'></i></a>";
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

<script>
    // گرفتن نام کنترلر از مدل Razor
    const controllerName = "Announcement";
    const areaName = "AdminPanel";

    // فعال‌سازی Drag & Drop
    let sortable = new Sortable(document.querySelector("#crudTable tbody"), {
        handle: ".drag-handle",
        animation: 150
    });

    // ذخیره ترتیب
    $("#saveOrderBtn").on("click", function () {
        let order = [];
        $("#crudTable tbody tr").each(function (index) {
            order.push({ id: $(this).data("id"), order: index + 1 });
        });

        $.ajax({
            url: 'Announcement.php',
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(order),
            success: function () {
                let reloadUrl = "/" + (areaName ? areaName + "/" : "") + controllerName + (controllerName != "Product" ? "/List" : "/Groups") ;
                window.location.href = reloadUrl;
            },
            error: function () {
                alert("خطا در ذخیره ترتیب");
            }
        });
    });

    // وقتی روی دکمه upload کلیک میشه، categoryId رو در hidden ست کن
    $(document).on("click", ".upload-excel-btn", function () {
        // دو روش خواندن: data() یا attr() — برای اطمینان هردو را امتحان می‌کنیم
        let categoryId = $(this).data("category");
        if (!categoryId) categoryId = $(this).attr("data-category");

        console.log("upload-excel-btn clicked, data-category:", categoryId);
        $("#excelUploadForm input[name='categoryId']").val(categoryId || "");
    });

    // پاک‌سازی هنگام بستن modal تا مقدار قدیمی نپاید
    $('#uploadExcelModal').on('hidden.bs.modal', function () {
        $("#excelUploadForm input[name='categoryId']").val('');
        $("#excelUploadForm input[name='file']").val(''); // پاک کردن فیلد فایل
    });


     $("#excelUploadForm").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "/AdminPanel/SubProduct/UploadProducts", // مسیر کنترلر
            type: "POST",
            data: formData,
            processData: false, // جلوگیری از پردازش پیش‌فرض
            contentType: false, // نوع محتوا رو خود مرورگر تعیین کنه
            success: function (response) {
                alert(response.message);
                $("#uploadExcelModal").modal("hide");
                let reloadUrl = "/AdminPanel/Product/Groups";
                $("#crudTableContainer").load(reloadUrl);
            },
            error: function (xhr) {
                let response = JSON.parse(xhr.responseText);
                alert("❌ خطا: " + response.message);
            }
        });
    });
</script>

        </div>
    </div>
</main>


    </div>
    
    <script>
        $(document).ready(function () {
        $("#reloadBtn").click(function () {
            $.ajax({
                url: '/AdminPanel/News/LoadNewsTable',
                type: 'GET',
                success: function (data) {
                    $("#newsTableContainer").html(data);
                },
                error: function () {
                    alert("خطا در بارگذاری مجدد جدول!");
                }
            });
        });

        });
    </script>


    <!-- کانتینر Toast -->
    <div id="toast-container"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

</body></html>