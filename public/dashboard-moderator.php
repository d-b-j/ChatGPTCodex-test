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

    <!-- STATS -->
    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="stats-card">

                <div class="stats-label">
                    Pending Memberships
                </div>

                <div
                    class="stats-value"
                    id="pending-count"
                >
                    0
                </div>

            </div>

        </div>

    </div>

    <div class="row g-4">

        <!-- LIST -->
        <div class="col-lg-5">

            <div class="dashboard-panel">

                <div class="panel-title">

                    <i class="bi bi-hourglass-split"></i>

                    Pending Requests

                </div>

                <div class="d-grid mb-4">

                    <button
                        class="btn btn-primary-theme"
                        onclick="loadPendingMembers()"
                    >
                        <i class="bi bi-search"></i>
                        Load Pending Requests
                    </button>

                </div>

                <div
                    id="member-list"
                    class="member-list"
                >

                    <div class="empty-state">

                        <i
                            class="bi bi-people"
                            style="font-size:48px;"
                        ></i>

                        <div class="mt-3">
                            No requests loaded
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- DETAILS -->
        <div class="col-lg-7">

            <div class="dashboard-panel">

                <div class="panel-title">

                    <i class="bi bi-person-lines-fill"></i>

                    Member Details

                </div>

                <div
                    id="member-viewer"
                    class="empty-state"
                >

                    <i
                        class="bi bi-person-vcard"
                        style="font-size:48px;"
                    ></i>

                    <div class="mt-3">
                        Select a pending member
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

            <div class="member-card">

                <div class="member-meta">

                    <div>

                        <div class="member-name">
                            ${member.first_name +" "+ member.last_name || 'Unnamed'}
                        </div>

                        <div class="member-sub">
                            ${member.email || '-'}
                        </div>

                        <div class="member-sub">
                            Batch ${member.batch_year || '-'}
                        </div>

                    </div>

                </div>

                <div>

                    <button
                        class="btn btn-primary-theme"
                        onclick="viewMember('${member.member_id}')"
                    >
                        <i class="bi bi-eye"></i>
                        View
                    </button>

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

    const receiptUrl =
        `/v1/member/${member.member_id}/registration-payment-receipt`;

    container.innerHTML = `

        <div class="text-center mb-4">

            <h3 class="mt-4">
                ${member.full_name || '-'}
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

</body>
</html>