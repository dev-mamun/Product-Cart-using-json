<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: dev.mamun@gmail.com
 * Date: 4/13/2021
 * Time: 11:17 AM
 * Year: 2021
 */
date_default_timezone_set('Asia/Dhaka');
$database = "items.json";

if (!file_exists($database)) {
    $data = array(
        "project" => "JSON file base sample cart",
        "name" => "JSON database file",
        "author" => "MD.Abdullah al mamun",
        "Created" => "4/13/2021",
        "items" => []
    );
    $items = json_encode($data);
    $db = file_put_contents($database, $items);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JSON Base Product List</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Enter Your Product</h1>
            <form id="save">
                <input type="hidden" name="action" value="add-item">
                <div class="form-row">
                    <div class="col-7">
                        <input type="text" name="title" class="form-control" placeholder="Product Name" required>
                    </div>
                    <div class="col">
                        <input type="number" name="qty" class="form-control" placeholder="Quantity" required>
                    </div>
                    <div class="col">
                        <input type="number" name="price" step="0.01" class="form-control" placeholder="Price" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success mb-2">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table id="item-list" class="table table-bordered">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Submitted At</th>
                    <th>Total Price</th>
                </tr>
                </thead>
                <tbody>
                <!--<tr>
                    <td>Xbox</td>
                    <td>2</td>
                    <td>100</td>
                    <td>13/04/2021</td>
                    <td>200</td>
                </tr>
                <tr>
                    <td>KN95 Mask</td>
                    <td>3</td>
                    <td>50</td>
                    <td>12/04/2021</td>
                    <td>150</td>
                </tr>
                <tr class="bg-light">
                    <td colspan="4" class="text-right">Sub-Total</td>
                    <td>350</td>
                </tr>-->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="jquery-3.6.0.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#save").on("submit", function (e) {
            e.preventDefault();
            var $request = $.ajax({
                url: "ajax.php",
                method: "POST",
                data: $(this).serialize(),
            });
            $request.done(function (response) {
                if (response.status) {
                    alert(response.msg);
                    itemList(response.data.items);
                }
            });
            $request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR)
                //alert("Request failed: " + textStatus);
            });
        });
        loadItems();
    });

    loadItems = function(){
        var $request = $.ajax({
            url: "ajax.php",
            method: "GET",
            data: {action:"get-item"},
        });
        $request.done(function (response) {
            if (response.status) {
                itemList(response.data.items);
            }
        });
        $request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
    };
    itemList = function ($items) {
        var $subTotal = 0;
        const $table = $("#item-list");
        $table.find("tbody").empty();
        $items.sort(function(a, b){
            return a.created_at-b.created_at;
        });
        $items.sort().reverse();

        $.each($items, function (key, val) {
            const $row = $("<tr></tr>");
            $("<td></td>").text(val.title).appendTo($row);
            $("<td></td>").text(val.qty).appendTo($row);
            $("<td></td>").text(val.price).appendTo($row);
            $("<td></td>").text(val.created_at).appendTo($row);
            $("<td></td>").text(val.total).appendTo($row);
            $subTotal += val.total;
            $table.find("tbody").append($row);
        });
        const $row1 = $("<tr></tr>").addClass("bg-light");
        $("<td></td>").attr("colspan", "4").addClass("text-right").text("Sub-Total: ").appendTo($row1);
        $("<td></td>").text($subTotal.toFixed(2)).appendTo($row1);
        $table.find("tbody").append($row1);
    };
</script>
</body>
</html>
