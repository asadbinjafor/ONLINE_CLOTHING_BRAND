
/* Task 4 — Checkout & payment validation */

function showInlineError(elementId, message){
    var el = document.getElementById(elementId);
    if(el){
        el.textContent = message;
        el.style.display = message ? "block" : "none";
    }
}

function validateCheckout(){
    var address = document.getElementById("delivery_address");
    if(!address){
        return true;
    }
    if(address.value.trim() === ""){
        showInlineError("addressError", "Please enter your full delivery address");
        address.focus();
        return false;
    }
    if(address.value.trim().length < 10){
        showInlineError("addressError", "Address must be at least 10 characters");
        address.focus();
        return false;
    }
    showInlineError("addressError", "");
    return true;
}

function validatePayment(){
    var methods = document.getElementsByName("payment_method");
    var selected = false;

    for(var i = 0; i < methods.length; i++){
        if(methods[i].checked){
            selected = true;
            break;
        }
    }

    if(!selected){
        showInlineError("paymentMethodError", "Please select a payment method");
        return false;
    }
    showInlineError("paymentMethodError", "");
    return true;
}

function placeOrderAjax(){
    if(!validatePayment()){
        return;
    }

    var paymentMethod = "";
    var methods = document.getElementsByName("payment_method");
    for(var i = 0; i < methods.length; i++){
        if(methods[i].checked){
            paymentMethod = methods[i].value;
            break;
        }
    }

    var csrfEl = document.getElementById("csrf_token_ajax");
    var csrf = csrfEl ? csrfEl.value : "";

    var msgDiv = document.getElementById("paymentMsg");
    if(msgDiv){
        msgDiv.innerHTML = "<div class='msg-success'>Placing order...</div>";
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState !== 4){
            return;
        }
        if(this.status === 200){
            try{
                var data = JSON.parse(this.responseText);
                if(data.success){
                    window.location.href = "../view/order_success.php?order_id=" + data.order_id;
                } else if(msgDiv){
                    msgDiv.innerHTML = "<div class='msg-error'>" + escapeHtml(data.message || "Order failed") + "</div>";
                }
            } catch(e){
                if(msgDiv){
                    msgDiv.innerHTML = "<div class='msg-error'>Invalid server response.</div>";
                }
            }
        } else if(msgDiv){
            msgDiv.innerHTML = "<div class='msg-error'>Server error. Please try again.</div>";
        }
    };

    xhttp.open("POST", "../control/place_order_api.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send(
        "payment_method=" + encodeURIComponent(paymentMethod) +
        "&csrf_token=" + encodeURIComponent(csrf)
    );
}

function escapeHtml(text){
    var div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener("DOMContentLoaded", function(){
    var checkoutForm = document.getElementById("checkoutForm");
    if(checkoutForm){
        checkoutForm.addEventListener("submit", function(e){
            if(!validateCheckout()){
                e.preventDefault();
            }
        });
    }

    var paymentForm = document.getElementById("paymentForm");
    if(paymentForm){
        paymentForm.addEventListener("submit", function(e){
            if(!validatePayment()){
                e.preventDefault();
            }
        });
    }

    var addressField = document.getElementById("delivery_address");
    if(addressField){
        addressField.addEventListener("input", function(){
            if(this.value.trim().length >= 10){
                showInlineError("addressError", "");
            }
        });
    }
});
