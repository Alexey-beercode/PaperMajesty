<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waffles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-0evSX huddled7lwJEUaCUpQvcSNjLOTv7Spp69AiV1fqzfwvWFXGkROmn3OuCrJK3o1B" crossorigin="anonymous">
    <style>
        .form-check-input:checked {
            background-color: gray;
            border-color: gray;
        }

        .custom-link {
            color: black;
            text-decoration: none;
            transition: color 0.3s;
        }

        .custom-link:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <h5 class="card-header">Сортировка</h5>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <input type="hidden" name="waffleName" value="<?php echo isset($_POST['waffleName']) ? $_POST['waffleName'] : ''; ?>">
                        <input type="hidden" name="waffleTypeId" value="<?php echo isset($_POST['waffleTypeId']) ? $_POST['waffleTypeId'] : ''; ?>">
                        <input type="hidden" name="fillingTypeId" value="<?php echo isset($_POST['fillingTypeId']) ? $_POST['fillingTypeId'] : ''; ?>">
                        <input type="hidden" name="minPrice" value="<?php echo isset($_POST['minPrice']) ? $_POST['minPrice'] : ''; ?>">
                        <input type="hidden" name="maxPrice" value="<?php echo isset($_POST['maxPrice']) ? $_POST['maxPrice'] : ''; ?>">

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sortingParameters" id="None" value="None" <?php echo (isset($_POST['sortingParameters']) && $_POST['sortingParameters'] == 'None') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="None">
                                Без сортировки
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sortingParameters" id="PriceDecrease" value="PriceDecrease" <?php echo (isset($_POST['sortingParameters']) && $_POST['sortingParameters'] == 'PriceDecrease') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="PriceDecrease">
                                По уменьшению цены
                            </label>
                        </div>
                        <button type="submit" class="btn btn-secondary w-100">Применить</button>
                    </form>
                </div>
            </div>
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <h5 class="card-header">Фильтрация</h5>
                <div class="card-body">
                    <a class="btn btn-secondary w-100" href="index.php">Сбросить фильтрацию</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-3 g-3">
<?php
// Assuming your waffle data is retrieved from database or another source
if (isset($_POST['pageNow'])) {
    $currentPage = $_POST['pageNow'];
} else {
    $currentPage = 1;
}
$waffles = getWaffles($currentPage); // Replace get
