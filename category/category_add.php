<?php
include '../templates/header.php';
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<header>
    <h5>ثبت گروه محصول</h5>
</header>

<div class="card p-4 mt-3">
    <form action="category_create.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="row mb-3">
            <div class="col-md-2">
                <label class="form-label" for="Code">کد گروه</label>
                <input class="form-control" id="Code" name="Code" type="text" value="">
            </div>

            <div class="col-md-10">
                <label class="form-label" for="Title">عنوان</label>
                <input class="form-control" id="Title" name="Title" type="text" value="">
            </div>
        </div>
        <div class="mb-3 form-check">
            <input class="form-check-input" id="is-show" name="IsShow" type="checkbox" value="1" checked>
            <label class="form-check-label" for="is-show">نمایش/عدم نمایش</label>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ثبت</button>
        <a href="category_list.php" class="btn btn-warning"><i class="fa fa-times"></i> انصراف</a>
    </form>
</div>

<?php include '../templates/footer.php'; ?>