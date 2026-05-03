<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| public/member-registration.php
|--------------------------------------------------------------------------
| Multi-step frontend registration form
| Plain PHP + HTML + JS
| Ready for your alumni system
|--------------------------------------------------------------------------
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Member Registration</title>

<style>
body{
    margin:0;
    font-family:Arial,Helvetica,sans-serif;
    background:#f4f6f8;
    color:#111;
}

.wrapper{
    max-width:760px;
    margin:30px auto;
    padding:16px;
}

.card{
    background:#fff;
    border-radius:14px;
    padding:28px;
    box-shadow:0 10px 24px rgba(0,0,0,.08);
}

h1{
    margin-top:0;
    font-size:28px;
}

.step{
    display:none;
}

.step.active{
    display:block;
}

.row{
    margin-bottom:18px;
}

label{
    display:block;
    font-weight:600;
    margin-bottom:7px;
}

input,select,textarea{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
    box-sizing:border-box;
}

.checkbox-group{
    display:flex;
    gap:14px;
    flex-wrap:wrap;
}

.checkbox-group label{
    font-weight:normal;
}

.inline{
    display:flex;
    align-items:center;
    gap:8px;
}

.actions{
    display:flex;
    gap:10px;
    margin-top:26px;
}

button{
    border:none;
    padding:12px 18px;
    border-radius:8px;
    cursor:pointer;
    font-size:15px;
}

.primary{
    background:#111;
    color:#fff;
}

.secondary{
    background:#e7ebef;
}

.contribution-item{
    border:1px solid #ddd;
    padding:14px;
    border-radius:10px;
    margin-bottom:12px;
}

.summary{
    background:#f8fafc;
    border-radius:10px;
    padding:18px;
    line-height:1.8;
    font-size:15px;
}

.small{
    color:#666;
    font-size:13px;
}
</style>
</head>
<body>

<div class="wrapper">
<div class="card">

<h1>Member Registration</h1>
<div class="small">Past Cricketers Association SSCK</div>

<form id="regForm" enctype="multipart/form-data">

<!-- ========================================================= -->
<!-- PAGE 1 -->
<!-- ========================================================= -->
<div class="step active">

<h2>Page 1</h2>

<div class="row">
<label>Name</label>
<input type="text" name="name" required>
</div>

<div class="row">
<label>Birthday</label>
<input type="date" name="birthday" required>
</div>

<div class="row">
<label>NIC</label>
<input type="text" name="nic" required>
</div>

<div class="row">
<label>Year of AL Batch</label>
<input type="number" name="al_batch" min="1950" max="2100">
</div>

<div class="row">
<label>Represented Years for College Cricket</label>

<div class="checkbox-group">
<label><input type="checkbox" name="cricket[]" value="U13"> U13</label>
<label><input type="checkbox" name="cricket[]" value="U15"> U15</label>
<label><input type="checkbox" name="cricket[]" value="U17"> U17</label>
<label><input type="checkbox" name="cricket[]" value="U19"> U19</label>
</div>

</div>

<div class="row inline">
<input type="checkbox" name="agreement" required>
<span>Hereby accepted the rules and regulations of the Past Cricketers Association SSCK</span>
</div>

</div>

<!-- ========================================================= -->
<!-- PAGE 2 -->
<!-- ========================================================= -->
<div class="step">

<h2>Page 2</h2>

<div class="row">
<label>Membership Year</label>
<select name="membership_year">
<option value="">Select Year</option>
<option>2026</option>
<option>2027</option>
<option>2028</option>
<option>2029</option>
</select>
</div>

<div class="row inline">
<input type="checkbox" name="proceed_membership">
<span>Proceed for Membership</span>
</div>

<div class="row">
<label>Bank Slip</label>
<input type="file" name="bank_slip" accept=".jpg,.jpeg,.png,.pdf">
</div>

</div>

<!-- ========================================================= -->
<!-- PAGE 3 -->
<!-- ========================================================= -->
<div class="step">

<h2>Page 3</h2>

<div class="row">
<label>Acceptance (Officer Confirmation)</label>
<select name="acceptance">
<option value="">Pending</option>
<option value="Approved">Approved</option>
<option value="Rejected">Rejected</option>
</select>
</div>

<div class="row">
<label>QR Code</label>
<div id="qrcodeBox">QR will appear after registration</div>
</div>

</div>

<!-- ========================================================= -->
<!-- PAGE 4 -->
<!-- ========================================================= -->
<div class="step">

<h2>Page 4</h2>

<div id="contributionArea"></div>

<button type="button" class="secondary" onclick="addContribution()">
Add More
</button>

</div>

<!-- ========================================================= -->
<!-- PAGE 5 -->
<!-- ========================================================= -->
<div class="step">

<h2>Page 5</h2>

<div class="summary" id="summaryBox"></div>

</div>

<!-- ========================================================= -->
<!-- ACTIONS -->
<!-- ========================================================= -->
<div class="actions">

<button type="button" class="secondary" onclick="prevStep()">
Previous
</button>

<button type="button" class="primary" onclick="nextStep()">
Next
</button>

<button type="submit" class="primary" id="submitBtn" style="display:none;">
Submit Registration
</button>

</div>

</form>

</div>
</div>

<script>
let currentStep = 0;
const steps = document.querySelectorAll(".step");

function showStep(index){

    steps.forEach((step,i)=>{
        step.classList.toggle("active", i === index);
    });

    document.getElementById("submitBtn").style.display =
        (index === 4) ? "inline-block" : "none";
}

function nextStep(){

    if(currentStep < steps.length - 1){
        currentStep++;
        if(currentStep === 4){
            buildSummary();
        }
        showStep(currentStep);
    }
}

function prevStep(){

    if(currentStep > 0){
        currentStep--;
        showStep(currentStep);
    }
}

function addContribution(){

    const div = document.createElement("div");
    div.className = "contribution-item";

    div.innerHTML = `
        <label>Description</label>
        <input type="text" name="contribution_desc[]">

        <label style="margin-top:10px;">Amount</label>
        <input type="number" name="contribution_amount[]">
    `;

    document.getElementById("contributionArea").appendChild(div);
}

function buildSummary(){

    const form = document.getElementById("regForm");
    const data = new FormData(form);

    let html = "";

    for (const pair of data.entries()){

        if(pair[0] === "bank_slip") continue;

        html += "<b>" + pair[0] + "</b>: " + pair[1] + "<br>";
    }

    document.getElementById("summaryBox").innerHTML = html;
}

/*
|--------------------------------------------------------------------------
| Submit Form
|--------------------------------------------------------------------------
*/
document.getElementById("regForm").addEventListener("submit", function(e){

    e.preventDefault();

    const formData = new FormData(this);

    /*
    ---------------------------------------------------------
    Replace URL with your API endpoint
    ---------------------------------------------------------
    */

    fetch("https://qrrest.technolide.xyz/v1/member/register", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(res => {

        if(res.success){

            if(res.data && res.data.qr_data_uri){
                document.getElementById("qrcodeBox").innerHTML =
                    '<img src="' + res.data.qr_data_uri + '" width="220">';
            }else{
                document.getElementById("qrcodeBox").innerHTML =
                    "Registration complete";
            }

            alert("Registration successful");

            currentStep = 2;
            showStep(currentStep);

        }else{
            alert(res.message || "Registration failed");
        }

    })
    .catch(() => {
        alert("Network error");
    });
});

/*
|--------------------------------------------------------------------------
| Initial contribution row
|--------------------------------------------------------------------------
*/
addContribution();
</script>

</body>
</html>