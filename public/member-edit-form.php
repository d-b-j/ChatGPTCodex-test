<?php
// Expect: /member-edit-form.php?id=UUID
$memberId = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Member</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f4f6f8;
}
.form-control,
.form-select,
.form-control:focus,
.form-select:focus {
    background-color: #f8f9fa;
    border: 1px solid #75797d;
    box-shadow: none;
}
.card {
    border: 0;
    border-radius: 1.25rem;
}
.action-row {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

#pageAlert {
  position: fixed;
  top: 10px;
  right: 10px;
  z-index: 9999;
  background-color: #f1f1f1;
  padding: 10px;
  border: 1px solid #ccc;
}

#result {
  position: fixed;
  bottom: 10px;
  left: 10px;
  z-index: 9999;
  background-color: #f1f1f1;
  padding: 10px;
  border: 1px solid #ccc;
}
</style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 class="mb-1">Edit Member</h2>
                            <p class="text-muted mb-0">Update member details and manage membership documents.</p>
                        </div>
                        <span class="badge text-bg-secondary" id="memberIdBadge">ID: -</span>
                    </div>

                    <div id="pageAlert" class="alert alert-warning d-none"></div>
                    <div id="result" class="mb-3"></div>

                    <form id="memberEditForm">
                        <input type="hidden" id="member_id" value="<?php echo htmlspecialchars($memberId); ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="batch_year">Batch Year</label>
                                <input type="number" class="form-control" id="batch_year" name="batch_year">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="gender">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="border-bottom pb-2 mb-0">Profile Information</h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="birthday">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="nic">NIC</label>
                                <input type="text" class="form-control" id="nic" name="nic" maxlength="20">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="al_batch_year">A/L Batch Year</label>
                                <input type="text" class="form-control" id="al_batch_year" name="al_batch_year">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="membership_year">Membership Year</label>
                                <input type="text" class="form-control" id="membership_year" name="membership_year">
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="cricket_years">Represented Years for College Cricket</label>
                                <input type="text" class="form-control" id="cricket_years" name="cricket_years">
                                <small class="text-muted">Enter years separated by commas.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                            </div>

                            <div class="col-12 pt-2">
                                <!-- <button type="submit" class="btn btn-primary px-4">Update Member</button> -->
                            </div>

                            <div class="col-12 pt-2">
                                <div class="action-row">
                                    <!-- <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#membershipModal"
                                    >
                                        Membership
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        id="contributionsButton"
                                    >
                                        Contributions
                                    </button> -->

<div class="col-5">
<button type="button" class="btn btn-outline-primary w-100 py-2 mt-2" data-bs-toggle="modal" data-bs-target="#membershipModal">
Membership Fee
</button>
</div>

<div class="col-5">
<button type="button" class="btn btn-secondary w-100 py-2 mt-2" onclick="window.location.href='member-contributions-form.php?id=<?php echo htmlspecialchars($memberId); ?>'">
Contributions
</button>
</div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="membershipModal" tabindex="-1" aria-labelledby="membershipModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="memebrFeePaymentForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="membershipModalLabel">Membership Payment Evidence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="membershipResult"></div>
                    <div class="mb-3">
                        <label class="form-label" for="membership-bankslip-file">Upload Payment Evidence</label>
                        <input
                            type="file"
                            class="form-control"
                            id="membership-bankslip-file"
                            name="membership-bankslip-file"
                            accept=".pdf,.jpg,.jpeg,.png,.webp"
                            required
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button id="upload-payment-record" type="button" class="btn btn-primary" onclick="uploadPaymentRec();">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const memberId = document.getElementById('member_id').value;
    const pageAlert = document.getElementById('pageAlert');
    const result = document.getElementById('result');
    const memberEditForm = document.getElementById('memberEditForm');
    const membershipForm = document.getElementById('membershipForm');
    const membershipResult = document.getElementById('membershipResult');
    const membershipModal = document.getElementById('membershipModal');
    const modalInstance = bootstrap.Modal.getOrCreateInstance(membershipModal);

    const fields = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'batch_year',
        'gender',
        'status',
        'birthday',
        'nic',
        'al_batch_year',
        'membership_year',
        'cricket_years',
        'address'
    ];

    function showAlert(message) {
        pageAlert.textContent = message;
        pageAlert.classList.remove('d-none');
    }

    function setValue(id, value) {
        const element = document.getElementById(id);
        if (!element) {
            return;
        }

        element.value = value ?? '';
    }

    function fillMemberForm(member) {
        document.getElementById('memberIdBadge').textContent = `ID: ${memberId}`;

        fields.forEach((field) => {
            setValue(field, member[field]);
        });
    }

    async function loadMember() {
        if (!memberId) {
            showAlert('Missing member id. Open this page with a query like member-edit-form.php?id=MEMBER_ID');
            memberEditForm.querySelectorAll('input, select, textarea, button').forEach((el) => {
                el.disabled = true;
            });
            return;
        }

        try {
            const response = await fetch(`/v1/member/${memberId}`);
            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Failed to load member');
            }

            fillMemberForm(payload.data || {});

        } catch (error) {
            showAlert(error.message);
        }
    }

    async function uploadPaymentRec() {
        const fileInput = document.getElementById('membership-bankslip-file');

        const fd = new FormData();
        fd.append('member-id', memberId);
        fd.append('membership-bankslip-file', fileInput.files[0]);
        fd.append('field_key', 'member-fee-payment-record');
        // memebrFeePaymentForm
        try {
            const response = await fetch('/v1/member/upload', {
                method: 'POST',
                body: fd
            });        

            const data = await response.json();
                if (response.ok) {
                    showAlert('File uploaded successfully.');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('membershipModal'));
                    modal.hide();
                    document.getElementById('membershipModal').inert = true;
                    pageAlert.scrollIntoView({ behavior: 'smooth' });
                    // loadAttachments();
                }

                if (!response.ok) {
                    throw new Error(data.message || 'Request failed');
                }

        } catch (error) {
            showAlert('Error: ' + error.message);
        }

    }


// memberEditForm.addEventListener('submit', async (event) => {
//     event.preventDefault();
//     result.innerHTML = '';

//     if (!memberId) {
//         showAlert('Missing member id.');
//         return;
//     }

//     const payload = {};
//     fields.forEach((field) => {
//         payload[field] = document.getElementById(field).value.trim();
//     });

//     try {
//         const response = await fetch(`/v1/member/${encodeURIComponent(memberId)}`, {
//             method: 'PATCH',
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify(payload)
//         });

//         const data = await response.json();

//         if (!response.ok || !data.success) {
//             throw new Error(data.message || 'Failed to update member');
//         }

//         result.innerHTML = '<div class="alert alert-success mb-0">Member updated successfully.</div>';
//         fillMemberForm(data.data || payload);
//     } catch (error) {
//         result.innerHTML = `<div class="alert alert-danger mb-0">Error: ${error.message}</div>`;
//     }
// });

// membershipForm.addEventListener('submit', async (event) => {
//     event.preventDefault();
//     membershipResult.innerHTML = '';

//     if (!memberId) {
//         membershipResult.innerHTML = '<div class="alert alert-danger">Missing member id.</div>';
//         return;
//     }

//     const fileInput = document.getElementById('membership-bankslip-file');

//     if (!fileInput.files.length) {
//         membershipResult.innerHTML = '<div class="alert alert-danger">Select a file to upload.</div>';
//         return;
//     }

//     const fd = new FormData();
//     fd.append('membership-bankslip-file', fileInput.files[0]);

//     membershipResult.innerHTML = '<div class="alert alert-info">Upload endpoint is not connected yet. File selected and ready for backend integration.</div>';
// });

// document.getElementById('contributionsButton').addEventListener('click', () => {
//     result.innerHTML = '<div class="alert alert-secondary mb-0">Contributions action is not connected yet.</div>';
// });

loadMember();
</script>

</body>
</html>
