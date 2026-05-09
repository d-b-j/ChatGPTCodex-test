<?php

$memberId = $_GET['id'] ?? '';
if (empty($memberId)) {
    die("Invalid member ID");
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

    <title>Member Profile</title>

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
        | Top Navigation
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

            opacity: 0.8;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Layout
        |--------------------------------------------------------------------------
        */
        .app-layout {

            padding: 28px;
        }

        .profile-sidebar {

            background: white;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 8px 24px rgba(0,0,0,0.05);
        }

        .profile-cover {

            height: 120px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );
        }

        .profile-body {

            padding: 0 28px 28px;
            text-align: center;
        }

        .profile-photo {

            width: 130px;
            height: 130px;

            border-radius: 50%;

            object-fit: cover;

            border: 6px solid white;

            margin-top: -65px;

            background: #f0f0f0;
        }

        .member-name {

            font-size: 28px;
            font-weight: 700;

            margin-top: 18px;
        }

        .member-number {

            color: #777;

            margin-top: 6px;
        }

        .member-status {

            display: inline-block;

            margin-top: 18px;

            padding: 10px 18px;

            border-radius: 999px;

            font-size: 13px;
            font-weight: 700;

            background:
                #fff3f2;

            color:
                var(--primary-bg);
        }

        .profile-actions {

            margin-top: 28px;

            display: grid;

            gap: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Panels
        |--------------------------------------------------------------------------
        */
        .dashboard-panel {

            background: white;

            border-radius: 24px;

            padding: 28px;

            box-shadow:
                0 8px 24px rgba(0,0,0,0.05);

            margin-bottom: 24px;
        }

        .panel-title {

            font-size: 20px;
            font-weight: 700;

            margin-bottom: 24px;

            color:
                var(--primary-bg);

            display: flex;
            align-items: center;
            gap: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Info Grid
        |--------------------------------------------------------------------------
        */
        .info-grid {

            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(220px, 1fr));

            gap: 18px;
        }

        .info-box {

            background:
                #fafafa;

            border:
                1px solid #ececec;

            border-radius: 18px;

            padding: 18px;
        }

        .info-label {

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            color: #888;

            margin-bottom: 10px;
        }

        .info-value {

            font-size: 17px;
            font-weight: 600;

            color: #222;

            word-break: break-word;
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Card
        |--------------------------------------------------------------------------
        */
        .payment-card {

            background:
                linear-gradient(
                    135deg,
                    #fff,
                    #fff7f6
                );
        }

        .payment-status {

            font-size: 28px;
            font-weight: 800;
        }

        /*
        |--------------------------------------------------------------------------
        | Contributions
        |--------------------------------------------------------------------------
        */
        .contribution-card {

            border:
                1px solid #ececec;

            border-radius: 18px;

            padding: 20px;

            margin-bottom: 16px;

            transition: 0.2s;

            background: white;
        }

        .contribution-card:hover {

            transform: translateY(-2px);

            box-shadow:
                0 6px 20px rgba(0,0,0,0.05);
        }

        .contribution-title {

            font-size: 19px;
            font-weight: 700;
        }

        .contribution-amount {

            font-size: 22px;
            font-weight: 800;

            color:
                var(--primary-bg);
        }

        .attachment-item {

            margin-top: 10px;
        }

        .attachment-link {

            text-decoration: none;

            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */
        .loading-state {

            padding: 100px 20px;

            text-align: center;

            color: #777;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */
        @media(max-width: 992px) {

            .app-layout {

                padding: 18px;
            }

            .dashboard-panel {

                padding: 22px;
            }
        }

    </style>

</head>
<body>

<!-- TOPBAR -->
<div class="topbar">

    <div class="brand">

        <div class="brand-logo">
            <!-- <i class="bi bi-mortarboard-fill"></i> -->
            <img src="/assets/images/ssckpca.png" alt="Logo" class="img-fluid" style="">
        </div>

        <div>

            <div class="brand-title">
                PCA Member Portal
            </div>

            <!-- <div class="brand-subtitle">
                QR Based Member Verification System
            </div> -->

        </div>

    </div>

    <div>

        <button
            class="btn btn-light"
            onclick="window.print()"
        >
            <i class="bi bi-printer"></i>
            Print
        </button>

    </div>

</div>

<div class="row g-4">
<!-- BACK BUTTON -->
    <button
        class="btn btn-light"
        onclick="history.back()"
    >
        <i class="bi bi-arrow-left"></i>
        Back
    </button>
</div>

<!-- LOADING -->
<div
    id="loading-state"
    class="loading-state"
>
    Loading member profile...
</div>

<!-- APP -->
<div
    id="app-container"
    class="app-layout"
    style="display:none;"
>

    <div class="row g-4">

        <!-- SIDEBAR -->
        <div class="col-lg-4">

            <div class="profile-sidebar">

                <div class="profile-cover"></div>

                <div class="profile-body">

                    <img
                        id="profile-photo"
                        class="profile-photo"
                        src="https://via.placeholder.com/130"
                    >

                    <div
                        class="member-name"
                        id="member-name"
                    >
                    </div>

                    <div
                        class="member-number"
                        id="member-number"
                    >
                    </div>

                    <div
                        class="member-status"
                        id="member-status"
                    >
                    </div>

                    <div class="profile-actions">
                        <button
                            class="btn btn-primary-theme"
                            id="edit-profile-btn"
                            onclick="window.location.href='member-edit-form.php?id=<?php echo htmlspecialchars($memberId); ?>'"
                        >
                            <i class="bi bi-receipt"></i>
                            Edit
                        </button>
                    </div>

                </div>

            </div>

        </div>

        <!-- MAIN -->
        <div class="col-lg-8">

            <!-- PERSONAL -->
            <div class="dashboard-panel">

                <div class="panel-title">
                    <i class="bi bi-person-badge"></i>
                    Personal Information
                </div>

                <div class="info-grid">

                    <div class="info-box">
                        <div class="info-label">Email</div>
                        <div class="info-value" id="member-email"></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Phone</div>
                        <div class="info-value" id="member-phone"></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">NIC</div>
                        <div class="info-value" id="member-nic"></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Birthday</div>
                        <div class="info-value" id="member-birthday"></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Gender</div>
                        <div class="info-value" id="member-gender"></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Batch Year</div>
                        <div class="info-value" id="member-batch-year"></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Membership Year</div>
                        <div class="info-value" id="member-membership-year"></div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Cricket Years</div>
                        <div class="info-value" id="member-cricket-years"></div>
                    </div>

                </div>

            </div>

            <!-- ADDRESS -->
            <div class="dashboard-panel">

                <div class="panel-title">
                    <i class="bi bi-geo-alt"></i>
                    Address
                </div>

                <div
                    class="info-value"
                    id="member-address"
                >
                </div>

            </div>

            <!-- PAYMENT -->
            <div class="dashboard-panel payment-card">

                <div class="panel-title">
                    <i class="bi bi-credit-card"></i>
                    Membership Payment
                </div>

                <div
                    class="payment-status"
                    id="payment-status"
                >
                    Checking...
                </div>
                <div
                    class="text-muted mt-2"
                    id="payment-date"
                >
                </div>
                <div class="d-flex justify-content-center">
                    <div class="text-center">                 
                        <button
                            class="btn btn-primary-theme"
                            id="view-payment-receipt-btn"
                            style="display:none;"
                        >
                            <i class="bi bi-receipt"></i>
                            View Payment Receipt
                        </button>
                    </div>
                </div>
            </div>

            <!-- CONTRIBUTIONS -->
            <div class="dashboard-panel">

                <div class="panel-title">
                    <i class="bi bi-cash-stack"></i>
                    Contributions
                </div>

                <div id="contributions-container">
                    <div class="text-muted">
                        Loading contributions...
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <div class="text-center">                
                            <button type="button" class="btn btn-primary-theme" onclick="window.location.href='member-contributions-form.php?id=<?php echo htmlspecialchars($memberId); ?>'">
                            Contributions
                            </button>                
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- RECEIPT MODAL -->
<div
    id="receipt-modal"
    class="custom-modal-overlay"
>

    <div class="custom-modal-content">

        <button
            class="btn btn-danger position-absolute"
            style="top:15px;right:15px;z-index:999;"
            onclick="closeReceiptModal()"
        >
            Close
        </button>

        <img
            id="receipt-image"
            style="
                width:100%;
                height:100%;
                object-fit:contain;
            "
        >

    </div>

</div>

<script>

const memberId =
    "<?php echo htmlspecialchars($memberId); ?>";

/*
|--------------------------------------------------------------------------
| Init
|--------------------------------------------------------------------------
*/
document.addEventListener(
    'DOMContentLoaded',
    async function () {

        await loadMember();

        await loadPaymentStatus();

        await loadContributions();
    }
);

/*
|--------------------------------------------------------------------------
| Member
|--------------------------------------------------------------------------
*/
async function loadMember()
{
    try {

        const response =
            await fetch(`/v1/member/${memberId}`);

        const result =
            await response.json();

        const member =
            result.data;

        document.getElementById(
            'member-name'
        ).innerHTML =
            member.full_name || '-';

        document.getElementById(
            'member-number'
        ).innerHTML =
            member.member_no || '-';

        document.getElementById(
            'member-status'
        ).innerHTML =
            (member.status || '').toUpperCase();

        document.getElementById(
            'member-email'
        ).innerHTML =
            member.email || '-';

        document.getElementById(
            'member-phone'
        ).innerHTML =
            member.phone || '-';

        document.getElementById(
            'member-nic'
        ).innerHTML =
            member.nic || '-';

        document.getElementById(
            'member-birthday'
        ).innerHTML =
            member.birthday || '-';

        document.getElementById(
            'member-gender'
        ).innerHTML =
            member.gender || '-';

        document.getElementById(
            'member-batch-year'
        ).innerHTML =
            member.batch_year || '-';

        document.getElementById(
            'member-membership-year'
        ).innerHTML =
            member.membership_year || '-';

        document.getElementById(
            'member-cricket-years'
        ).innerHTML =
            member.cricket_years || '-';

        document.getElementById(
            'member-address'
        ).innerHTML =
            member.address || '-';

        if (member.profile_photo) {

            document.getElementById(
                'profile-photo'
            ).src =
                member.profile_photo;
        }

        document.getElementById(
            'loading-state'
        ).style.display = 'none';

        document.getElementById(
            'app-container'
        ).style.display = 'block';

    } catch (error) {

        console.error(error);

        document.getElementById(
            'loading-state'
        ).innerHTML =
            'Unable to load member profile';
    }
}

/*
|--------------------------------------------------------------------------
| Payment Status
|--------------------------------------------------------------------------
*/
async function loadPaymentStatus()
{
    try {

        const response =
            await fetch(
                `/v1/member/${memberId}/payment-status`
            );

        const result =
            await response.json();

        if (
            result.data &&
            result.data.paid
        ) {

            document.getElementById(
                'payment-status'
            ).innerHTML =
                '<span class="status-paid">PAID</span>';

            document.getElementById(
                'payment-date'
            ).innerHTML =
                'Verified payment record found';

            const btn =
                document.getElementById(
                    'view-payment-receipt-btn'
                );

            btn.style.display = 'block';

            btn.onclick = function () {

                loadReceipt();
            };

        } else {

            document.getElementById(
                'payment-status'
            ).innerHTML =
                '<span class="status-unpaid">NOT PAID</span>';
        }

    } catch (error) {

        console.error(error);
    }
}

/*
|--------------------------------------------------------------------------
| Receipt
|--------------------------------------------------------------------------
*/
async function loadReceipt()
{
    try {

        const response =
            await fetch(
                `/v1/member/${memberId}/payment-receipt`
            );

        const blob =
            await response.blob();

        const imageUrl =
            URL.createObjectURL(blob);

        document.getElementById(
            'receipt-image'
        ).src =
            imageUrl;

        document.getElementById(
            'receipt-modal'
        ).style.display =
            'flex';

    } catch (error) {

        console.error(error);
    }
}

function closeReceiptModal()
{
    document.getElementById(
        'receipt-modal'
    ).style.display =
        'none';
}

/*
|--------------------------------------------------------------------------
| Contributions
|--------------------------------------------------------------------------
*/
async function loadContributions()
{
    try {

        const response =
            await fetch(
                `/v1/member/${memberId}/contributions`
            );

        const result =
            await response.json();

        const container =
            document.getElementById(
                'contributions-container'
            );

        if (
            !result.data ||
            result.data.length === 0
        ) {

            container.innerHTML =
                '<div class="text-muted">No contributions found</div>';

            return;
        }

        let html = '';

        result.data.forEach(function(item) {

            html += `
                <div class="contribution-card">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="contribution-title">
                                ${item.title}
                            </div>

                            <div class="text-muted mt-2">
                                ${item.description || ''}
                            </div>

                        </div>

                        <div class="contribution-amount">
                            Rs. ${item.amount}
                        </div>

                    </div>

                    <div class="small text-muted mt-3">
                        ${item.created_at}
                    </div>
            `;

            if (
                item.attachments &&
                item.attachments.length > 0
            ) {

                html += '<div class="mt-3">';

                item.attachments.forEach(function(file) {

                    html += `
                        <div class="attachment-item">

                            <a
                                href="${file.file_path}"
                                target="_blank"
                                class="attachment-link"
                            >
                                <i class="bi bi-paperclip"></i>
                                ${file.file_name}
                            </a>

                        </div>
                    `;
                });

                html += '</div>';
            }

            html += '</div>';
        });

        container.innerHTML = html;

    } catch (error) {

        console.error(error);
    }
}

</script>

</body>
</html>