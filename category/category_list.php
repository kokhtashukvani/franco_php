<?php
require_once '../db.php';
include '../templates/header.php';

// Fetch categories from the database
$sql = "SELECT id, code, title, is_show, display_order FROM product_groups ORDER BY display_order ASC";
$result = $conn->query($sql);
?>

<header class="d-flex justify-content-between align-items-center mb-3">
    <h5>مدیریت گروه محصولات</h5>
    <a href="#" class="btn btn-danger">
        <i class="fa fa-sign-out-alt"></i> خروج
    </a>
</header>

<div class="card p-4 mt-3">
    <div class="d-flex flex-wrap gap-2 mb-3">
        <div class="btn-group">
            <a class="btn btn-primary" href="category_add.php"><i class="fa fa-plus"></i> ایجاد جدید</a>
        </div>
        <button class="btn btn-secondary" onclick="location.reload();"><i class="fa fa-sync"></i> بارگذاری مجدد لیست</button>
        <button id="saveOrderBtn" class="btn btn-success btn-sm"><i class="fa fa-save"></i> ذخیره ترتیب</button>
    </div>

    <div class="table-responsive">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>

        <table id="crudTable" class="table table-bordered table-striped">
            <thead class="table-header" style="background:#5986b3 !important">
                <tr>
                    <th style="width:8%">ترتیب نمایش</th>
                    <th style="text-align:center; vertical-align:middle">کد گروه</th>
                    <th style="text-align:center; vertical-align:middle">عنوان</th>
                    <th style="text-align:center; vertical-align:middle">نمایش/عدم نمایش</th>
                    <th style="text-align:center; vertical-align:middle">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr data-id="<?php echo $row['id']; ?>">
                            <td><i class="fa fa-bars drag-handle"></i> &nbsp;&nbsp; <?php echo $row['display_order']; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['code']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td class="text-center">
                                <?php if ($row['is_show']) : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" viewBox="0 0 16 16">
                                        <path d="M13.485 1.929a1 1 0 0 1 .086 1.414l-7.071 8a1 1 0 0 1-1.497 0l-3.536-4a1 1 0 1 1 1.497-1.328L6 8.586l6.536-7.414a1 1 0 0 1 .949-.243z"></path>
                                    </svg>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-primary" href="category_edit.php?id=<?php echo $row['id']; ?>">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-danger" href="category_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('آیا از حذف این رکورد مطمئن هستید؟');">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">هیچ گروه محصولی یافت نشد.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <script>
            // فعال‌سازی Drag & Drop
            let sortable = new Sortable(document.querySelector("#crudTable tbody"), {
                handle: ".drag-handle",
                animation: 150
            });

            // ذخیره ترتیب
            $("#saveOrderBtn").on("click", function() {
                let order = [];
                $("#crudTable tbody tr").each(function(index) {
                    order.push({
                        id: $(this).data("id"),
                        order: index + 1
                    });
                });

                $.ajax({
                    url: 'category_update_order.php',
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(order),
                    success: function(response) {
                        console.log(response);
                        window.location.href = 'category_list.php';
                    },
                    error: function() {
                        alert("خطا در ذخیره ترتیب");
                    }
                });
            });
        </script>
    </div>
</div>
<?php
$conn->close();
include '../templates/footer.php';
?>