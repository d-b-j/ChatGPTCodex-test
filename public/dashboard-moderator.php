<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Management Dashboard
    </title>

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

            opacity: 0.82;
        }

        /*
        |--------------------------------------------------------------------------
        | Layout
        |--------------------------------------------------------------------------
        */
        .dashboard-layout {

            padding: 28px;
        }

        .dashboard-panel {

            background: white;

            border-radius: 24px;

            padding: 24px;

            box-shadow:
                0 8px 24px rgba(0,0,0,0.05);

            margin-bottom: 24px;
        }

        .panel-title {

            font-size: 20px;
            font-weight: 700;

            color:
                var(--primary-bg);

            display: flex;

            align-items: center;

            gap: 10px;

            margin-bottom: 22px;
        }

        /*
        |--------------------------------------------------------------------------
        | Stats
        |--------------------------------------------------------------------------
        */
        .stats-card {

            border-radius: 18px;

            padding: 20px;

            background:
                linear-gradient(
                    135deg,
                    #fff,
                    #fff4f3
                );

            border:
                1px solid #f1dddd;
        }

        .stats-label {

            font-size: 12px;

            text-transform: uppercase;

            color: #777;

            letter-spacing: 0.5px;
        }

        .stats-value {

            font-size: 30px;

            font-weight: 800;

            margin-top: 10px;

            color:
                var(--primary-bg);
        }

        /*
        |--------------------------------------------------------------------------
        | Member List
        |--------------------------------------------------------------------------
        */
        .member-list {

            display: grid;

            gap: 16px;
        }

        .member-card {

            border:
                1px solid #ececec;

            border-radius: 18px;

            padding: 18px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 18px;

            transition: 0.2s ease;
        }

        .member-card:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 8px 18px rgba(0,0,0,0.05);
        }

        .member-meta {

            display: flex;

            align-items: center;

            gap: 16px;
        }

        .member-avatar {

            width: 64px;
            height: 64px;

            border-radius: 50%;

            object-fit: cover;

            background: #efefef;
        }

        .member-name {

            font-size: 18px;
            font-weight: 700;
        }

        .member-sub {

            font-size: 14px;

            color: #777;
        }

        /*
        |--------------------------------------------------------------------------
        | Profile Viewer
        |--------------------------------------------------------------------------
        */
        .profile-photo {

            width: 140px;
            height: 140px;

            border-radius: 50%;

            object-fit: cover;

            border:
                5px solid #fff;

            box-shadow:
                0 6px 16px rgba(0,0,0,0.08);

            background: #efefef;
        }

        .info-table {

            width: 100%;
        }

        .info-table td {

            padding: 12px 10px;

            border-bottom:
                1px solid #f0f0f0;
        }

        .info-key {

            width: 220px;

            font-weight: 700;

            color:
                var(--primary-bg);
        }

        /*
        |--------------------------------------------------------------------------
        | Receipt
        |--------------------------------------------------------------------------
        */
        .receipt-box {

            border:
                1px solid #ececec;

            border-radius: 18px;

            padding: 16px;

            text-align: center;

            background:
                #fafafa;
        }

        .receipt-image {

            max-width: 100%;

            border-radius: 14px;

            margin-top: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */
        .empty-state {

            text-align: center;

            padding: 80px 20px;

            color: #777;
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

        @media(max-width: 992px) {

            .dashboard-layout {

                padding: 18px;
            }

            .member-card {

                flex-direction: column;

                align-items: flex-start;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Small Pending Meta
        |--------------------------------------------------------------------------
        */
        .pending-meta {

            font-size: 13px;

            color: #777;
        }

        /*
        |--------------------------------------------------------------------------
        | Modal Styling
        |--------------------------------------------------------------------------
        */
        .modal-content {

            border: none;

            border-radius: 22px;

            overflow: hidden;
        }

        .modal-header {

            background:
                linear-gradient(
                    135deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );

            color: white;

            border: none;

            padding: 20px 24px;
        }

        .modal-title {

            font-weight: 700;
        }

        .modal-subtitle {

            font-size: 13px;

            opacity: 0.82;
        }

        .btn-close {

            filter: invert(1);
        }

        /*
        |--------------------------------------------------------------------------
        | Table Header
        |--------------------------------------------------------------------------
        */
        .member-table-header {

            display: grid;

            grid-template-columns:
                2fr
                1fr
                1fr
                1fr
                120px;

            gap: 14px;

            padding:
                14px 20px;

            background:
                #faf4f3;

            border-bottom:
                1px solid #eee;

            font-size: 12px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            color:
                var(--primary-bg);
        }

        /*
        |--------------------------------------------------------------------------
        | Member List
        |--------------------------------------------------------------------------
        */
        .member-list {

            display: flex;

            flex-direction: column;
        }

        .member-card {

            display: grid;

            grid-template-columns:
                2fr
                1fr
                1fr
                1fr
                120px;

            gap: 14px;

            align-items: center;

            padding:
                12px 20px;

            border-bottom:
                1px solid #f3f3f3;

            border-radius: 0;

            transition: 0.2s ease;

            background: white;
        }

        .member-card:hover {

            background:
                #fafafa;
        }

        .member-meta {

            display: flex;

            align-items: center;

            gap: 12px;
        }

        .member-avatar {

            width: 42px;
            height: 42px;

            border-radius: 50%;

            object-fit: cover;
        }

        .member-name {

            font-size: 14px;

            font-weight: 700;

            line-height: 1.2;
        }

        .member-sub {

            font-size: 12px;

            color: #777;
        }

        .member-mini {

            font-size: 13px;

            color: #555;
        }

        .member-status-badge {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding:
                5px 10px;

            border-radius: 999px;

            background:
                #fff3cd;

            color:
                #856404;

            font-size: 12px;

            font-weight: 600;
        }

        /*
        |--------------------------------------------------------------------------
        | Small View Button
        |--------------------------------------------------------------------------
        */
        .btn-view-sm {

            padding:
                5px 12px;

            font-size: 12px;

            border-radius: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Viewer
        |--------------------------------------------------------------------------
        */
        .profile-photo {

            width: 110px;
            height: 110px;
        }

        @media(max-width: 992px) {

            .member-table-header {

                display: none;
            }

            .member-card {

                grid-template-columns: 1fr;

                gap: 10px;
            }
        }


    </style>

</head>
<body>

<!-- TOPBAR -->
<div class="topbar">

    <div class="brand">

        <div class="brand-logo">
            <!-- <i class="bi bi-shield-check"></i> -->
            <img src="/assets/images/ssckpca.png" alt="Logo" class="img-fluid" style="">
        </div>

        <div>

            <div class="brand-title">
                Management Dashboard
            </div>

            <!-- <div class="brand-subtitle">
                Membership Approval Workspace
            </div> -->

        </div>

    </div>

    <div>

        <button
            class="btn btn-light"
            onclick="loadPendingMembers()"
        >
            <i class="bi bi-arrow-clockwise"></i>
            Refresh
        </button>

    </div>

</div>

<!-- MAIN -->
<div class="dashboard-layout">
    <div class="row">

        <div class="col-12">

            <div class="dashboard-panel">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-center
                        flex-wrap
                        gap-3
                    "
                >

                    <div>

                        <div class="panel-title mb-1">

                            <i class="bi bi-hourglass-split"></i>

                            Pending Requests

                        </div>

                        <div
                            class="pending-meta"
                        >

                            Pending Memberships:
                            <span id="pending-count">
                                0
                            </span>

                        </div>

                    </div>

                    <div>

                        <button
                            class="btn btn-primary-theme"
                            onclick="openPendingRequestsModal()"
                        >
                            <i class="bi bi-search"></i>

                            Load Pending Requests
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ===================================================== -->
<!-- PENDING REQUESTS MODAL -->
<!-- ===================================================== -->

<div
    class="modal fade"
    id="pendingRequestsModal"
    tabindex="-1"
>

    <div
        class="
            modal-dialog
            modal-xl
            modal-dialog-scrollable
        "
    >

        <div class="modal-content">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title">

                        Pending Membership Requests

                    </h5>

                    <div class="modal-subtitle">

                        Review and moderate pending
                        alumni registrations

                    </div>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body p-0">

                <!-- TABLE HEADER -->
                <div class="member-table-header">

                    <div>
                        Member
                    </div>

                    <div>
                        Member No
                    </div>

                    <div>
                        Batch
                    </div>

                    <div>
                        Status
                    </div>

                    <div class="text-end">
                        Actions
                    </div>

                </div>

                <!-- LIST -->
                <div
                    id="member-list"
                    class="member-list"
                >

                    <div class="empty-state">

                        <i
                            class="bi bi-people"
                            style="font-size:40px;"
                        ></i>

                        <div class="mt-3">
                            No requests loaded
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ===================================================== -->
<!-- MEMBER VIEW MODAL -->
<!-- ===================================================== -->

<div
    class="modal fade"
    id="memberViewerModal"
    tabindex="-1"
>

    <div
        class="
            modal-dialog
            modal-xl
            modal-dialog-scrollable
        "
    >

        <div class="modal-content">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title">
                        Member Review
                    </h5>

                    <div class="modal-subtitle">
                        Membership approval workspace
                    </div>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body">

                <div id="member-viewer">

                    <div class="empty-state">

                        <i
                            class="bi bi-person-vcard"
                            style="font-size:48px;"
                        ></i>

                        <div class="mt-3">
                            Select a member
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- LOADING -->
<div
    id="loading-overlay"
    class="loading-overlay"
>

    <div
        class="spinner-border text-danger"
        role="status"
    ></div>

</div>

<script>

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/
let currentMember = null;
let pendingRequestsModal = null;
let memberViewerModal = null;
/*
|--------------------------------------------------------------------------
| Load Pending Members
|--------------------------------------------------------------------------
*/
async function loadPendingMembers()
{
    try {

        showLoading();

        const response =
            await fetch(
                '/v1/member/status/pending'
            );

        const result =
            await response.json();

        hideLoading();

        if (!result.success) {

            alert(
                result.message ||
                'Failed loading members'
            );

            return;
        }

        renderPendingMembers(
            result.data || []
        );

    } catch (error) {

        console.error(error);

        hideLoading();

        alert(
            'Network error'
        );
    }
}

/*
|--------------------------------------------------------------------------
| Render Pending Members
|--------------------------------------------------------------------------
*/
function renderPendingMembers(members)
{
    const container =
        document.getElementById(
            'member-list'
        );

    document.getElementById(
        'pending-count'
    ).innerHTML =
        members.length;

    if (!members.length) {

        container.innerHTML = `
            <div class="empty-state">

                <i
                    class="bi bi-check-circle"
                    style="font-size:48px;"
                ></i>

                <div class="mt-3">
                    No pending requests
                </div>

            </div>
        `;

        return;
    }

    container.innerHTML =
        members.map(member => `
            <div class="row">
                <div class="col">
                    <div class="member-card">
                        <div class="member-meta">
                            <div>
                                <div class="member-name">
                                ${member.first_name +" "+ member.last_name || '-'}
                                </div>
                                <div class="member-sub">
                                ${member.email || '-'}
                                </div>
                            </div>
                        </div>
                        <div class="row member-mini-row">
                            <div class="col member-mini">
                                ${member.member_no || '-'}
                            </div>
                            <div class="col member-mini">
                                ${member.batch_year || '-'}
                            </div>
                        </div>
                        <div class="row member-status-row">
                            <div class="col">
                                <span class="member-status-badge">
                                    Pending
                                </span>
                            </div>
                            <div class="col text-end">
                                <button class="btn btn-primary-theme btn-view-sm" onclick="viewMember('${member.member_id}')">
                                    <i class="bi bi-eye"></i>
                                    View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
}

/*
|--------------------------------------------------------------------------
| View Member
|--------------------------------------------------------------------------
*/
async function viewMember(memberId)
{
    try {

        showLoading();

        const response =
            await fetch(
                `/v1/member/${memberId}`
            );

        const result =
            await response.json();

        hideLoading();

        if (!result.success) {

            alert(
                result.message ||
                'Failed loading member'
            );

            return;
        }

        currentMember =
            result.data;

        renderMemberViewer(
            result.data
        );

        memberViewerModal.show();

    } catch (error) {

        console.error(error);

        hideLoading();

        alert(
            'Network error'
        );
    }
}

/*
|--------------------------------------------------------------------------
| Render Viewer
|--------------------------------------------------------------------------
*/
function renderMemberViewer(member)
{
    const container =
        document.getElementById(
            'member-viewer'
        );
    const memberId = member.member_id;

    container.innerHTML = `

        <div class="text-center mb-4">

            <h3 class="mt-4">
                ${member.first_name +" "+ member.last_name || '-'}
            </h3>

            <div class="text-muted">
                ${member.email || '-'}
            </div>

        </div>

        <table class="info-table">

            <tr>
                <td class="info-key">
                    Member No
                </td>

                <td>
                    ${member.member_no || '-'}
                </td>
            </tr>

            <tr>
                <td class="info-key">
                    Phone
                </td>

                <td>
                    ${member.phone || '-'}
                </td>
            </tr>

            <tr>
                <td class="info-key">
                    NIC
                </td>

                <td>
                    ${member.nic || '-'}
                </td>
            </tr>

            <tr>
                <td class="info-key">
                    Batch Year
                </td>

                <td>
                    ${member.batch_year || '-'}
                </td>
            </tr>

            <tr>
                <td class="info-key">
                    Membership Year
                </td>

                <td>
                    ${member.membership_year || '-'}
                </td>
            </tr>

            <tr>
                <td class="info-key">
                    Address
                </td>

                <td>
                    ${member.address || '-'}
                </td>
            </tr>

        </table>

        <div class="receipt-box mt-4">

            <h5>
                Registration Fee Receipt
            </h5>
            <img
                id="reg-receipt-image" 
                style="
                    width:100%;
                    height:100%;
                    display:none;
                    object-fit:contain;
                "
            >

        </div>

        <div class="d-flex gap-3 mt-4">

            <button
                class="btn btn-success flex-fill"
                onclick="registerMember()"
            >
                <i class="bi bi-check-circle"></i>
                Register
            </button>

            <button
                class="btn btn-warning flex-fill"
                onclick="messageMember()"
            >
                <i class="bi bi-chat-dots"></i>
                Message
            </button>

            <button
                class="btn btn-danger flex-fill"
                onclick="rejectMember()"
            >
                <i class="bi bi-x-circle"></i>
                Reject
            </button>

        </div>
    `;

    loadRegFeeReceipt(memberId);

}


async function loadRegFeeReceipt(memberId)
{
    try {

        const response =
            await fetch(
                `/v1/member/${memberId}/registration-payment-receipt`
            );
        const blob =
            await response.blob();
        const imageUrl =
            URL.createObjectURL(blob);
            // console.log(response);
            // console.log(blob);
        if (response.ok === false) {
            return;
        }    
        
        if (blob.size > 100) {
            document.getElementById(
                'reg-receipt-image'
            ).src =
                imageUrl;

            document.getElementById(
                'reg-receipt-image'
            ).style.display =
                'flex';
        }
    } catch (error) {
        console.error(error);
    }
}

/*
|--------------------------------------------------------------------------
| Register
|--------------------------------------------------------------------------
*/
async function registerMember()
{
    // if (!currentMember) {
    //     return;
    // }

    // const confirmed =
    //     confirm(
    //         'Approve and register this member?'
    //     );

    // if (!confirmed) {
    //     return;
    // }

    // try {

    //     showLoading();

    //     const response =
    //         await fetch(
    //             `/v1/member/${currentMember.id}/register`,
    //             {
    //                 method: 'POST'
    //             }
    //         );

    //     const result =
    //         await response.json();

    //     hideLoading();

    //     if (!result.success) {

    //         alert(
    //             result.message ||
    //             'Registration failed'
    //         );

    //         return;
    //     }

    //     alert(
    //         'Member registered successfully'
    //     );

    //     document.getElementById(
    //         'member-viewer'
    //     ).innerHTML = `
    //         <div class="empty-state">
    //             <i
    //                 class="bi bi-check-circle"
    //                 style="font-size:48px;"
    //             ></i>

    //             <div class="mt-3">
    //                 Member approved successfully
    //             </div>
    //         </div>
    //     `;

    //     loadPendingMembers();

    // } catch (error) {

    //     console.error(error);

    //     hideLoading();

    //     alert(
    //         'Network error'
    //     );
    // }
}

/*
|--------------------------------------------------------------------------
| Message
|--------------------------------------------------------------------------
*/
async function messageMember()
{
    // if (!currentMember) {
    //     return;
    // }

    // const message =
    //     prompt(
    //         'Enter moderator message'
    //     );

    // if (!message) {
    //     return;
    // }

    // try {

    //     showLoading();

    //     const response =
    //         await fetch(
    //             `/v1/member/${currentMember.id}/message`,
    //             {
    //                 method: 'POST',

    //                 headers: {
    //                     'Content-Type':
    //                         'application/json'
    //                 },

    //                 body: JSON.stringify({
    //                     message
    //                 })
    //             }
    //         );

    //     const result =
    //         await response.json();

    //     hideLoading();

    //     if (!result.success) {

    //         alert(
    //             result.message ||
    //             'Message failed'
    //         );

    //         return;
    //     }

    //     alert(
    //         'Message workflow placeholder completed'
    //     );

    // } catch (error) {

    //     console.error(error);

    //     hideLoading();

    //     alert(
    //         'Network error'
    //     );
    // }
}

/*
|--------------------------------------------------------------------------
| Reject
|--------------------------------------------------------------------------
*/
async function rejectMember()
{
    // if (!currentMember) {
    //     return;
    // }

    // const confirmed =
    //     confirm(
    //         'Reject this membership request?'
    //     );

    // if (!confirmed) {
    //     return;
    // }

    // try {

    //     showLoading();

    //     const response =
    //         await fetch(
    //             `/v1/member/${currentMember.id}/reject`,
    //             {
    //                 method: 'POST'
    //             }
    //         );

    //     const result =
    //         await response.json();

    //     hideLoading();

    //     if (!result.success) {

    //         alert(
    //             result.message ||
    //             'Reject failed'
    //         );

    //         return;
    //     }

    //     alert(
    //         'Member rejected successfully'
    //     );

    //     document.getElementById(
    //         'member-viewer'
    //     ).innerHTML = `
    //         <div class="empty-state">
    //             <i
    //                 class="bi bi-x-circle"
    //                 style="font-size:48px;"
    //             ></i>

    //             <div class="mt-3">
    //                 Membership rejected
    //             </div>
    //         </div>
    //     `;

    //     loadPendingMembers();

    // } catch (error) {

    //     console.error(error);

    //     hideLoading();

    //     alert(
    //         'Network error'
    //     );
    // }
}

/*
|--------------------------------------------------------------------------
| Initialize Modals
|--------------------------------------------------------------------------
*/
document.addEventListener(
    'DOMContentLoaded',
    () => {

        pendingRequestsModal =
            new bootstrap.Modal(
                document.getElementById(
                    'pendingRequestsModal'
                )
            );

        memberViewerModal =
            new bootstrap.Modal(
                document.getElementById(
                    'memberViewerModal'
                )
            );

        loadPendingMembers();
    }
);

/*
|--------------------------------------------------------------------------
| Open Pending Requests Modal
|--------------------------------------------------------------------------
*/
function openPendingRequestsModal()
{
    pendingRequestsModal.show();
}

/*
|--------------------------------------------------------------------------
| Loading
|--------------------------------------------------------------------------
*/
function showLoading()
{
    document.getElementById(
        'loading-overlay'
    ).style.display =
        'flex';
}

function hideLoading()
{
    document.getElementById(
        'loading-overlay'
    ).style.display =
        'none';
}

/*
|--------------------------------------------------------------------------
| Auto Load
|--------------------------------------------------------------------------
*/
loadPendingMembers();

</script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>