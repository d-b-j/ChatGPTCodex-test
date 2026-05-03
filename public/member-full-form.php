<?php
/**
 * member-full-form.php
 * Existing layout/style preserved.
 * Added PData fields + same POST JSON mechanism extended.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Member Registration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* keep existing styles if present */
.form-control{
    background-color: #f8f9fa;
    border: 1px solid #75797d;
}
</style>
</head>
<body class="bg-light">

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card shadow border-0 rounded-4">
<div class="card-body p-4">

<h2 class="mb-4">Member Registration</h2>

<form id="memberForm" enctype="multipart/form-data">

<!-- EXISTING FIELDS -->
<div class="row g-3">

<div class="col-md-6">
<label class="form-label">First Name</label>
<input type="text" class="form-control" id="first_name" required>
</div>

<div class="col-md-6">
<label class="form-label">Last Name</label>
<input type="text" class="form-control" id="last_name" required>
</div>

<div class="col-12">
<label class="form-label">Full Name</label>
<input type="text" class="form-control" id="full_name" required>
</div>

<div class="col-12">
<label class="form-label" for="address">Address</label>
<textarea class="form-control" id="address" name="address" rows="3"></textarea>
</div>

<div class="col-md-6">
<label class="form-label">Email</label>
<input type="email" class="form-control" id="email" required>
</div>

<div class="col-md-6">
<label class="form-label">Phone</label>
<input type="text" class="form-control" id="phone" required>
</div>

<div class="col-md-4">
<label class="form-label">Batch Year</label>
<input type="number" class="form-control" id="batch_year" required>
</div>

<div class="col-md-4">
<label class="form-label">Gender</label>
<select class="form-select" id="gender">
<option value="male">Male</option>
<option value="female">Female</option>
<option value="other">Other</option>
</select>
</div>

<div class="col-md-4">
<label class="form-label">Status</label>
<select class="form-select" id="status">
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>
</div>

<!-- NEW PROFILE DATA FIELDS -->

<div class="col-12 mt-4">
<h5 class="border-bottom pb-2">Profile Information</h5>
</div>

<div class="col-md-6">
<label class="form-label">Birthday</label>
<input type="date" class="form-control" id="birthday">
</div>

<div class="col-md-6">
<label class="form-label">NIC</label>
<input type="text" class="form-control" id="nic" maxlength="20">
</div>

<div class="col-md-6">
<label class="form-label">A/L Batch Year</label>
<input type="text" class="form-control" id="al_batch_year">
</div>

<div class="col-md-6">
<label class="form-label">Membership Year</label>
<input type="text" class="form-control" id="membership_year">
</div>

<div class="col-12">
<label class="form-label d-block">Represented Years for College Cricket</label>
<input type="text" class="form-control cricket_year" id="cricket_year">
<span><small class="text-muted">Enter years separated by commas (e.g. 2018,2019,2020)</small></span>
</div>


<div class="col-12">
<button type="submit" class="btn btn-primary w-100 py-2 mt-3" id="submitBtn">
Submit Member
</button>
</div>


<div class="modal min-vh-100 d-hide bg-light bg-gradient" id="viewProfileModal" tabindex="-1" >
    <div class="modal-dialog">
        <div class="container my-auto py-5">
        <div class="modal-content col-md-6 mx-auto">
            <button type="button" class="hide green btn btn-primary w-100 py-2 mt-3" id="loadProfileBtn">
            View Profile
            </button>
        </div>
        </div>
    </div>
</div>

</div>
</form>

<div id="result" class="mt-3"></div>

</div>
</div>

</div>
</div>
</div>

<script>
const form = document.getElementById('memberForm');
const result = document.getElementById('result');
const loadProfileBtn = document.getElementById('loadProfileBtn');
const modal = document.getElementById('viewProfileModal');

function selectedCricketYears() {
    return Array.from(
        document.querySelectorAll('.cricket_year:checked')
    ).map(el => el.value).join(',');
}

form.addEventListener('submit', async function(e) {
    e.preventDefault();

    const fd = new FormData();

    fd.append('first_name', first_name.value.trim());
    fd.append('last_name', last_name.value.trim());
    fd.append('full_name', full_name.value.trim());
    fd.append('email', email.value.trim());
    fd.append('phone', phone.value.trim());
    fd.append('batch_year', batch_year.value.trim());
    fd.append('gender', gender.value);
    fd.append('status', status.value);
    fd.append('address', address.value.trim());

    fd.append('birthday', birthday.value);
    fd.append('nic', nic.value.trim());
    fd.append('al_batch_year', al_batch_year.value.trim());
    fd.append('cricket_years', cricket_year.value.trim());
    fd.append('membership_year', new Date().getFullYear());

    try {
        const response = await fetch('/v1/member', {
            method: 'POST',
            body: fd
        });        

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }

        const memberId = data?.data?.member?.id || '';

        result.innerHTML =
            '<div class="alert alert-success">Member saved successfully.</div>';

        if (memberId) {

                $submitBtn = document.getElementById('submitBtn');
                loadProfileBtn.addEventListener('click', function() {
                    window.location.href = 'member-edit-form.php?id=' + encodeURIComponent(memberId);
                });
                // $message = document.getElementById('successMessage');



                // document.getElementById('successMessage').textContent = 'Member created successfully!';
                // $this->view->getElementById('successMessage')->show();

            setTimeout(() => {
                $submitBtn.style.display = "none";
                result.style.display = "none"; 

                modal.classList.remove('d-hide');
                modal.classList.add('d-flex');
                modal.classList.add('justify-content-center');
                modal.classList.add('align-items-center');
                // window.location.href =
                // '/member-edit-form.php?id=' + encodeURIComponent(memberId);
            }, 2000);
        }

    } catch (error) {
        result.innerHTML =
            '<div class="alert alert-danger">Error: ' +
            error.message +
            '</div>';
    }
});
</script>

</body>
</html>