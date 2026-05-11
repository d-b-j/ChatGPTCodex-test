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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Member Contributions</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- Theme -->
    <link
        href="/assets/css/theme.css"
        rel="stylesheet"
    >

    <style>

        body {

            background:
                #f4f6f9;
        }

        /*
        |--------------------------------------------------------------------------
        | Topbar
        |--------------------------------------------------------------------------
        */
        .topbar {

            height: 72px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 28px;

            color: white;

            box-shadow:
                0 4px 14px rgba(0,0,0,0.08);
        }

        .brand {

            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {

            width: 44px;
            height: 44px;

            border-radius: 12px;

            background:
                rgba(255,255,255,0.12);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
        }

        .brand-title {

            font-size: 20px;
            font-weight: 700;
        }

        .brand-subtitle {

            font-size: 12px;

            opacity: 0.85;
        }

        /*
        |--------------------------------------------------------------------------
        | Layout
        |--------------------------------------------------------------------------
        */
        .page-wrapper {

            padding: 28px;
        }

        .page-card {

            background: white;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 8px 24px rgba(0,0,0,0.05);
        }

        .page-header {

            padding: 28px 32px;

            background:
                linear-gradient(
                    135deg,
                    #fff,
                    #fff5f4
                );

            border-bottom:
                1px solid #ececec;
        }

        .page-title {

            font-size: 28px;
            font-weight: 700;

            color:
                var(--primary-bg);
        }

        .page-subtitle {

            margin-top: 6px;

            color: #777;
        }

        .page-content {

            padding: 32px;
        }

        /*
        |--------------------------------------------------------------------------
        | Member Badge
        |--------------------------------------------------------------------------
        */
        .member-badge {

            display: inline-flex;

            align-items: center;

            gap: 10px;

            padding: 12px 16px;

            border-radius: 14px;

            background:
                #fff4f3;

            color:
                var(--primary-bg);

            font-weight: 600;

            margin-bottom: 28px;
        }

        /*
        |--------------------------------------------------------------------------
        | Form
        |--------------------------------------------------------------------------
        */
        .form-section {

            margin-bottom: 32px;
        }

        .section-title {

            font-size: 18px;
            font-weight: 700;

            margin-bottom: 20px;

            color:
                var(--primary-bg);

            display: flex;

            align-items: center;

            gap: 10px;
        }

        .form-label {

            font-weight: 600;

            margin-bottom: 8px;

            color: #444;
        }

        .form-control,
        .form-select {

            border-radius: 14px;

            border:
                1px solid #dcdcdc;

            padding: 12px 14px;

            min-height: 50px;
        }

        .form-control:focus,
        .form-select:focus {

            border-color:
                var(--primary-bg);

            box-shadow:
                0 0 0 0.2rem rgba(149,21,15,0.12);
        }

        textarea.form-control {

            min-height: 130px;
        }

        /*
        |--------------------------------------------------------------------------
        | Attachment Cards
        |--------------------------------------------------------------------------
        */
        .attachment-card {

            border:
                1px solid #ececec;

            border-radius: 18px;

            padding: 18px;

            background:
                #fafafa;

            margin-bottom: 16px;
        }

        /*
        |--------------------------------------------------------------------------
        | Buttons
        |--------------------------------------------------------------------------
        */
        .actions {

            display: flex;

            gap: 14px;

            flex-wrap: wrap;

            margin-top: 32px;
        }

        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */
        .loading-overlay {

            position: fixed;

            inset: 0;

            background:
                rgba(255,255,255,0.7);

            display: none;

            align-items: center;
            justify-content: center;

            z-index: 9999;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */
        @media(max-width: 768px) {

            .page-wrapper {

                padding: 18px;
            }

            .page-content {

                padding: 22px;
            }

            .page-header {

                padding: 22px;
            }

            .page-title {

                font-size: 24px;
            }
        }

    </style>

</head>
<body>

<!-- TOPBAR -->
<div class="topbar">

    <div class="brand">

        <div class="brand-logo">
            <img src="/assets/images/ssckpca.png" alt="Logo" class="img-fluid" style="">
        </div>

        <div>

            <div class="brand-title">
                PCA Member Portal
            </div>

            <!-- <div class="brand-subtitle">
                Member Contribution Management
            </div> -->

        </div>

    </div>

    <div>

        <button
            class="btn btn-light"
            onclick="history.back()"
        >
            <i class="bi bi-arrow-left"></i>
            Back
        </button>

    </div>

</div>

<!-- PAGE -->
<div class="page-wrapper">

    <div class="page-card">

        <!-- HEADER -->
        <div class="page-header">

            <div class="page-title">
                Add Contribution
            </div>

            <div class="page-subtitle">
                Record member contribution details and supporting documents
            </div>

        </div>

        <!-- CONTENT -->
        <div class="page-content">

            <!-- MEMBER -->
            <div class="member-badge">

                <i class="bi bi-person-badge"></i>

                Member ID:
                <span id="member-id-label">
                    <?php echo htmlspecialchars($memberId); ?>
                </span>

            </div>

            <!-- FORM -->
            <form id="contribution-form">

                <!-- Contribution Details -->
                <div class="form-section">

                    <div class="section-title">

                        <i class="bi bi-file-earmark-text"></i>

                        Contribution Details

                    </div>

                    <div class="row g-4">

                        <div class="col-md-8">

                            <label class="form-label">
                                Contribution Title
                            </label>

                            <input
                                type="text"
                                id="title"
                                class="form-control"
                                placeholder="Enter contribution title"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Amount (LKR)
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="amount"
                                class="form-control"
                                placeholder="0.00"
                                required
                            >

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                id="description"
                                class="form-control"
                                placeholder="Enter contribution description"
                            ></textarea>

                        </div>

                    </div>

                </div>

                <!-- Attachments -->
                <div class="form-section">

                    <div
                        class="d-flex justify-content-between align-items-center mb-4"
                    >

                        <div class="section-title mb-0">

                            <i class="bi bi-paperclip"></i>

                            Attachments

                        </div>

                        <button
                            type="button"
                            class="btn btn-secondary-theme"
                            onclick="addAttachmentField()"
                        >
                            <i class="bi bi-plus-circle"></i>
                            Add Attachment
                        </button>

                    </div>

                    <div id="attachments-container">

                        <div class="attachment-card">

                            <label class="form-label">
                                Attachment File
                            </label>

                            <input
                                type="file"
                                name="attachments[]"
                                class="form-control"
                            >

                        </div>

                    </div>

                </div>

                <!-- ACTIONS -->
                <div class="actions">

                    <button
                        type="submit"
                        class="btn btn-primary-theme"
                    >
                        <i class="bi bi-save"></i>
                        Save Contribution
                    </button>

                    <button
                        type="button"
                        class="btn btn-secondary-theme"
                        onclick="resetForm()"
                    >
                        <i class="bi bi-arrow-clockwise"></i>
                        Reset Form
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<div id="msg" class="mt-3"></div>

<!-- LOADING -->
<div
    id="loading-overlay"
    class="loading-overlay"
>

    <div
        class="spinner-border text-danger"
        role="status"
    >
    </div>

</div>

<script>

const memberId =
    "<?php echo htmlspecialchars($memberId); ?>";
const msg = document.getElementById('msg');
let contributionsCount = 0;

/* =========================
   Load Contributions
========================= */
async function loadContributions() {
    // try {
    //     const res = await fetch(`/v1/member/${memberId}/contributions`);
    //     const data = await res.json();

    //     let html = '';

    //     (data.data || []).forEach(c => {
    //         html += `
    //         <div class="border p-3 mb-2 rounded">
    //             <strong>${c.title}</strong>
    //             <div>Amount: ${c.amount}</div>
    //             <div class="text-muted">${c.description || ''}</div>
    //         </div>`;
    //     });

    //     document.getElementById('contributionList').innerHTML = html;

    // } catch (e) {
    //     msg.innerHTML = `<div class="alert alert-danger">${e.message}</div>`;
    // }

        const response =
            await fetch(
                `/v1/member/${memberId}/contributions`
            );

        const result =
            await response.json();

        contributionsCount = result.data.length;
        console.log(contributionsCount);

}



/*
|--------------------------------------------------------------------------
| Add Attachment Field
|--------------------------------------------------------------------------
*/
function addAttachmentField()
{
    const container =
        document.getElementById(
            'attachments-container'
        );

    const card =
        document.createElement('div');

    card.className =
        'attachment-card';

    card.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-3">

            <label class="form-label mb-0">
                Attachment File
            </label>

            <button
                type="button"
                class="btn btn-sm btn-outline-danger"
                onclick="removeAttachment(this)"
            >
                <i class="bi bi-trash"></i>
            </button>

        </div>

        <input
            type="file"
            name="attachments[]"
            class="form-control"
        >
    `;

    container.appendChild(card);
}

/*
|--------------------------------------------------------------------------
| Remove Attachment
|--------------------------------------------------------------------------
*/
function removeAttachment(button)
{
    button
        .closest('.attachment-card')
        .remove();
}

/*
|--------------------------------------------------------------------------
| Reset Form
|--------------------------------------------------------------------------
*/
function resetForm()
{
    document
        .getElementById(
            'contribution-form'
        )
        .reset();

    document.getElementById(
        'attachments-container'
    ).innerHTML = `
        <div class="attachment-card">

            <label class="form-label">
                Attachment File
            </label>

            <input
                type="file"
                name="attachments[]"
                class="form-control"
            >

        </div>
    `;
}

/*
|--------------------------------------------------------------------------
| Submit Form
|--------------------------------------------------------------------------
*/
document
    .getElementById('contribution-form')
    .addEventListener(
        'submit',
        async function(event) {

            event.preventDefault();

            const loading =
                document.getElementById(
                    'loading-overlay'
                );

            loading.style.display =
                'flex';

            try {

                const formData =
                    new FormData();

                    
                contributionID = contributionsCount + 1;

                formData.append(
                    'member_id',
                    memberId
                );

                formData.append('contribution_id', contributionID);

                formData.append(
                    'title',
                    document.getElementById(
                        'title'
                    ).value
                );

                formData.append(
                    'amount',
                    document.getElementById(
                        'amount'
                    ).value
                );

                formData.append(
                    'description',
                    document.getElementById(
                        'description'
                    ).value
                );

                formData.append('field_key', memberId + '_contribution_' + contributionID);

                document
                    .querySelectorAll(
                        'input[name="attachments[]"]'
                    )
                    .forEach(function(input) {

                        if (
                            input.files.length > 0
                        ) {

                            formData.append(
                                'attachments[]',
                                input.files[0]
                            );
                        }
                    });

                const response =
                    await fetch(
                        `/v1/member/${memberId}/contribution`,
                        {
                            method: 'POST',
                            body: formData
                        }
                    );

                const result =
                    await response.json();

                loading.style.display =
                    'none';

                if (result.success) {

                    msg.innerHTML = `<div class="alert alert-success">Contribution added. Reloading...</div>`;
                    // alert(
                    //     'Contribution saved successfully'
                    // );

                    // resetForm();

                    // msg.innerHTML = `<div class="alert alert-success">Contribution added. Reloading...</div>`;
                    // setTimeout(() => {
                    //     document.getElementById('contributionForm').reset();
                    //     location.reload();
                    // }, 3000);


                } else {

                    msg.innerHTML = `<div class="alert alert-danger">Failed to save contribution</div>`;
                    // alert(
                    //     result.message ||
                    //     'Failed to save contribution'
                    // );
                }

            } catch (error) {

                console.error(error);

                loading.style.display =
                    'none';

                alert(
                    'Network error occurred'
                );
            }
        }
    );

loadContributions();

</script>

</body>
</html>