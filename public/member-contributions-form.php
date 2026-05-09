<?php
$memberId = $_GET['id'] ?? '';
if (!$memberId) {
    die('Member ID required');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Member Contributions</title>
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
<div class="col-lg-10">

<div class="card shadow border-0 rounded-4">
<div class="card-body p-4">
<button onclick="history.back()">Go Back</button>
<!-- <a href="member.php?id=<?php echo htmlspecialchars($memberId); ?>"><-- go back</a> -->
<h3 class="mb-4">Member Contributions</h3>

<!-- EXISTING CONTRIBUTIONS -->
<div class="mb-4">
<h5>Existing Contributions</h5>
<div id="contributionList"></div>
</div>

<hr>

<!-- FORM -->
<form id="contributionForm" enctype="multipart/form-data">

<input type="hidden" id="member_id" value="<?php echo htmlspecialchars($memberId); ?>">

<div class="row g-3">

<div class="col-md-6">
<label class="form-label">Title</label>
<input type="text" class="form-control" id="title" required>
</div>

<div class="col-md-6">
<label class="form-label">Amount</label>
<input type="number" class="form-control" id="amount" required>
</div>

<div class="col-12">
<label class="form-label">Description</label>
<textarea class="form-control" id="description" rows="3"></textarea>
</div>

<!-- ATTACHMENTS -->
<div class="col-12 mt-3">
<h6>Attachments</h6>
<div id="attachmentRows"></div>

<button type="button"
        class="btn btn-sm btn-outline-primary mt-2"
        onclick="addAttachmentRow()">
Add Attachment
</button>
</div>

<div class="col-12">
<button class="btn btn-primary w-100 mt-3" id="saveContribution" type="submit" >Save Contribution</button>
</div>

</div>
</form>

<div id="msg" class="mt-3"></div>

</div>
</div>

</div>
</div>
</div>

<script>
const memberId = document.getElementById('member_id').value;
const msg = document.getElementById('msg');
let existingContributions = 0;

let attachIndex = 0;

/* =========================
   Dynamic Attachment Rows
========================= */
function addAttachmentRow() {
    const html = `
    <div class="border rounded p-2 mb-2 attachment-row">
        <div class="row g-2">
            <div class="col-md-10">
                <input type="file" class="form-control file" accept=".jpg,.jpeg,.png,.pdf">
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-sm btn-danger"
                    onclick="this.closest('.attachment-row').remove()">
                    Remove
                </button>
            </div>
        </div>
    </div>`;
    document.getElementById('attachmentRows')
        .insertAdjacentHTML('beforeend', html);
}

// initialize one row
addAttachmentRow();

/* =========================
   Load Contributions
========================= */
async function loadContributions() {
    try {
        const res = await fetch(`/v1/member/${memberId}/contributions`);
        const data = await res.json();

        let html = '';
        var nowContributions = existingContributions;

        (data.data || []).forEach(c => {
            nowContributions++;
            html += `
            <div class="border p-3 mb-2 rounded">
                <strong>${c.title}</strong>
                <div>Amount: ${c.amount}</div>
                <div class="text-muted">${c.description || ''}</div>`;
            attIdx = 0;
            c.attachments.forEach(a => {
                html += `<span>attachment ${++attIdx}</span> | `;
            });
                html += `<button type="button" class="btn btn-sm btn-warning"
                    onclick="">Remove</button>`;

            html += `</div>`;


        });

        document.getElementById('contributionList').innerHTML = html;
        existingContributions = nowContributions;
    } catch (e) {
        msg.innerHTML = `<div class="alert alert-danger">${e.message}</div>`;
    }
}

/* =========================
   Submit Form
========================= */
document.getElementById('contributionForm')
.addEventListener('submit', async e => {

    e.preventDefault();

    const fd = new FormData();

    contributionID = (existingContributions + 1);

    fd.append('member_id', memberId);
    fd.append('contribution_id', contributionID);
    fd.append('title', document.getElementById('title').value);
    fd.append('description', document.getElementById('description').value);
    fd.append('amount', document.getElementById('amount').value);
    fd.append('field_key', memberId + '_contribution_' + contributionID );


    // attachments
    document.querySelectorAll('.attachment-row').forEach(row => {
        const file = row.querySelector('.file').files[0];
        if (!file) return;

        fd.append('attachments[]', file);
    });

    try {
        const response = await fetch('/v1/member/contribution', {
            method: 'POST',
            body: fd
        });

        const data = await response.json();

        if (!response.ok) throw new Error(data.error || 'Failed');

        // console.log(data);
        msg.innerHTML = `<div class="alert alert-success">Contribution added. Reloading...</div>`;
        setTimeout(() => {
            document.getElementById('contributionForm').reset();
            location.reload();
        }, 3000);
        // document.getElementById('attachmentRows').innerHTML = '';
        // addAttachmentRow();

        // loadContributions();

    } catch (e) {
        msg.innerHTML = `<div class="alert alert-danger">${e.message}</div>`;
    }
});
loadContributions();
</script>

</body>
</html>