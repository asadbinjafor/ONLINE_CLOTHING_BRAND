
function validateProductForm()
{
    var name       = document.getElementById("name").value.trim();
    var categoryId = document.getElementById("category_id").value;
    var price      = document.getElementById("price").value.trim();
    var stock      = document.getElementById("stock").value.trim();
    var desc       = document.getElementById("description").value.trim();
    var sizeChart  = document.getElementById("size_chart").value.trim();

    if(name == ""){ alert("Product name is required"); return false; }
    if(desc == ""){ alert("Description is required"); return false; }
    if(sizeChart == ""){ alert("Size chart is required"); return false; }
    if(categoryId == "" || categoryId == "0"){ alert("Please select a category"); return false; }
    if(price == "" || isNaN(price) || parseFloat(price) <= 0){ alert("Price must be greater than 0"); return false; }
    if(stock == "" || isNaN(stock) || parseInt(stock) < 0 || stock.indexOf(".") !== -1){
        alert("Stock must be a non-negative whole number"); return false;
    }
    var img = document.getElementById("image");
    if(img && img.files.length > 0){
        var f = img.files[0];
        if(f.size > 2*1024*1024){ alert("Image must be 2MB or less"); return false; }
    }
    return true;
}

function confirmDelete(itemName)
{
    return confirm("Are you sure you want to delete this " + itemName + "?");
}

function updateOrderStatus(orderId, status)
{
    var label = status === "confirmed" ? "confirm" : "reject";
    if(!confirm("Are you sure you want to " + label + " order #" + orderId + "?")){ return; }

    var msgDiv = document.getElementById("orderMsg");
    if(msgDiv){ msgDiv.innerHTML = ""; }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            var data = JSON.parse(this.responseText);
            if(data.success){
                var badge = document.getElementById("status-badge-" + orderId);
                var actionCell = document.getElementById("action-cell-" + orderId);
                if(badge){
                    badge.className = "order-status-badge status-" + data.status;
                    badge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                }
                if(actionCell){ actionCell.innerHTML = "<span class='text-muted'>—</span>"; }
                if(msgDiv){ msgDiv.innerHTML = "<div class='msg-success'>Order #" + orderId + " " + data.status + ".</div>"; }
            } else {
                if(msgDiv){ msgDiv.innerHTML = "<div class='msg-error'>" + (data.message || "Failed") + "</div>"; }
                else { alert(data.message || "Failed"); }
            }
        }
    };
    xhttp.open("POST", "../control/admin_order_status_api.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send("order_id=" + orderId + "&status=" + status);
}
