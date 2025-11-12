<?php
require_once '../db.php';
include '../templates/header.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch brands for the dropdown
$sql_brands = "SELECT id, title FROM brands ORDER BY title ASC";
$brands_result = $conn->query($sql_brands);
?>

<header class="d-flex justify-content-between align-items-center mb-3">
    <h5>مدیریت محصولات &gt; ثبت محصول جدید</h5>
    <a href="#" class="btn btn-danger">
        <i class="fa fa-sign-out-alt"></i> خروج
    </a>
</header>

<div class="card p-4 mt-3">
    <form action="product_create.php" class="card-body" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="g-3">
            <div class=" row col-md-3">
                <label class="form-label" for="Code">کد محصول</label>
                <input class="form-control" id="Code" name="Code" type="text" value="">
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row col-md-6">
                <label class="form-label" for="Title">عنوان محصول</label>
                <input class="form-control" id="Title" name="Title" type="text" value="">
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row col-md-3">
                <label class="form-label" for="brandId">برند محصول</label>
                <select class="form-control" id="brandId" name="brandId">
                    <option value="">-- یک برند انتخاب کنید --</option>
                    <?php
                    if ($brands_result->num_rows > 0) {
                        while ($row = $brands_result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row col-md-3">
                <label class="form-label" for="StockStatus">وضعیت موجودی</label>
                <select class="form-select" id="StockStatus" name="StockStatus">
                    <option selected="selected" value="1">موجود</option>
                    <option value="0">ناموجود</option>
                </select>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row col-md-3">
                <label class="form-label" for="CountInBag">تعداد در کارتن</label>
                <input class="form-control numeric-input" id="CountInBag" name="CountInBag" type="text" value="0">
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row col-md-3">
                <label class="form-label" for="CachPrice">قیمت نقدی</label>
                <input class="form-control numeric-input" id="CachPrice" name="CachPrice" type="text" value="0">
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row col-md-3">
                <label class="form-label" for="NoCachPrice">قیمت غیر نقدی</label>
                <input class="form-control numeric-input" id="NoCachPrice" name="NoCachPrice" type="text" value="0">
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row">
                <div class="col-md-3">
                    <input class="form-check-input" id="is-show" name="IsShow" type="checkbox" value="1" checked>
                    <label class="form-check-label" for="is-show">نمایش/عدم نمایش</label>
                </div>
            </div>
        </div>
        <div class="mt-4 d-flex justify-content-start gap-2">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ثبت</button>
            <a href="product_list.php" class="btn btn-warning"><i class="fa fa-times"></i> انصراف</a>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll(".numeric-input").forEach(input => {
        // موقع تایپ
        input.addEventListener("input", function() {
            let value = this.value.replace(/[^0-9]/g, "");
            if (value) {
                value = parseInt(value, 10).toLocaleString("en-US");
            }
            this.value = value;
        });

        // قبل از ارسال فرم جداکننده‌ها حذف بشن
        input.form.addEventListener("submit", function() {
            input.value = input.value.replace(/,/g, "");
        });
    });
</script>
<?php
$conn->close();
include '../templates/footer.php';
?>