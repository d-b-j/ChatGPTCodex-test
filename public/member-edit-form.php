<?php

$memberId = $_GET['id'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Member</title>

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

            background: #efefef;
        }

        .member-name {

            font-size: 26px;
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

            background:
                #fff4f3;

            color:
                var(--primary-bg);

            font-size: 13px;
            font-weight: 700;
        }

        .sidebar-actions {

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
        | Form
        |--------------------------------------------------------------------------
        */
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

            min-height: 120px;
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        .stats-card {

            background:
                linear-gradient(
                    135deg,
                    #fff,
                    #fff6f5
                );

            border-radius: 18px;

            padding: 20px;

            border:
                1px solid #f1e2e0;
        }

        .stats-label {

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            color: #777;
        }

        .stats-value {

            font-size: 28px;
            font-weight: 800;

            margin-top: 8px;

            color:
                var(--primary-bg);
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
             <!-- <i class="bi bi-pencil-square"></i> -->
            <img src="/assets/images/ssckpca.png" alt="Logo" class="img-fluid" style="">
        </div>


        <div>

            <div class="brand-title">
                PCA Member Portal
            </div>

            <div class="brand-subtitle">
                Member Information Management Workspace
            </div>

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

<!-- APP -->
<div class="app-layout">

    <div class="row g-4">

        <!-- SIDEBAR -->
        <div class="col-lg-4">

            <div class="profile-sidebar">

                <div class="profile-cover"></div>

                <div class="profile-body">

                    <img
                        id="profile-photo-preview"
                        class="profile-photo"
                        src="https://via.placeholder.com/130"
                    >

                    <div
                        class="member-name"
                        id="member-name-preview"
                    >
                        Loading...
                    </div>

                    <div
                        class="member-number"
                        id="member-number-preview"
                    >
                    </div>

                    <div
                        class="member-status"
                        id="member-status-preview"
                    >
                    </div>

                    <div class="sidebar-actions">

                        <button
                            type="button"
                            class="btn btn-primary-theme"
                            onclick="submitMemberForm()"
                        >
                            <i class="bi bi-save"></i>
                            Save Changes
                        </button>

                        <!-- <button
                            type="button"
                            class="btn btn-secondary-theme"
                            onclick="resetFormData()"
                        >
                            <i class="bi bi-arrow-clockwise"></i>
                            Reset Form
                        </button> -->

                    </div>

                </div>

            </div>

            <!-- STATS -->
            <div class="dashboard-panel mt-4">

                <div class="panel-title">
                    <i class="bi bi-file-lock-fill"></i>
                    Locked Items
                </div>

                <!-- <div class="stats-card mb-3">
                    <div class="stats-label">
                        Membership Year
                    </div>
                    <div
                        class="stats-value"
                        id="membership-year-stat"
                    >
                        -
                    </div>

                </div> -->

                <!-- <div class="stats-card">

                    <div class="stats-label">
                        Batch Year
                    </div>

                    <div
                        class="stats-value"
                        id="batch-year-stat"
                    >
                        -
                    </div>

                </div> -->

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

                <form id="member-form">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                class="form-control"
                            >

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                id="full_name"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input
                                type="email"
                                id="email"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                id="phone"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                NIC
                            </label>

                            <input
                                type="text"
                                id="nic"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Birthday
                            </label>

                            <input
                                type="date"
                                id="birthday"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Gender
                            </label>

                            <select
                                id="gender"
                                class="form-select"
                            >
                                <option value="">
                                    Select Gender
                                </option>

                                <option value="male">
                                    Male
                                </option>

                                <option value="female">
                                    Female
                                </option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                id="status"
                                class="form-select"
                            >
                                <option value="active">
                                    Active
                                </option>

                                <option value="inactive">
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </form>

            </div>

            <!-- MEMBERSHIP -->
            <div class="dashboard-panel">

                <div class="panel-title">

                    <i class="bi bi-mortarboard"></i>

                    Membership Information

                </div>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Member Number
                        </label>

                        <input
                            type="text"
                            id="member_no"
                            class="form-control"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Membership Year
                        </label>

                        <input
                            type="number"
                            id="membership_year"
                            class="form-control"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Batch Year
                        </label>

                        <input
                            type="number"
                            id="batch_year"
                            class="form-control"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Cricket Years
                        </label>

                        <input
                            type="text"
                            id="cricket_years"
                            class="form-control"
                            placeholder="2000,2001,2002"
                        >

                    </div>

                </div>

            </div>

            <!-- ADDRESS -->
            <div class="dashboard-panel">

                <div class="panel-title">

                    <i class="bi bi-geo-alt"></i>

                    Address Information

                </div>

                <label class="form-label">
                    Address
                </label>

                <textarea
                    id="address"
                    class="form-control"
                ></textarea>

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
    >
    </div>

</div>

<script>

const memberId =
    "<?php echo htmlspecialchars($memberId); ?>";

let originalMemberData = null;

/*
|--------------------------------------------------------------------------
| Initialize
|--------------------------------------------------------------------------
*/
document.addEventListener(
    'DOMContentLoaded',
    async function () {

        await loadMember();
    }
);

/*
|--------------------------------------------------------------------------
| Load Member
|--------------------------------------------------------------------------
*/
async function loadMember()
{
    try {

        showLoading();

        const response =
            await fetch(`/v1/member/${memberId}`);

        const result =
            await response.json();

        const member =
            result.data;

        originalMemberData = member;

        populateForm(member);

        hideLoading();

    } catch (error) {

        hideLoading();

        // alert(
        //     'Unable to load member information'
        // );
    }
}

/*
|--------------------------------------------------------------------------
| Populate Form
|--------------------------------------------------------------------------
*/
function populateForm(member)
{
    const fields = [

        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'nic',
        'birthday',
        'gender',
        'status',
        'member_no',
        'membership_year',
        'batch_year',
        'cricket_years',
        'address'
    ];

    fields.forEach(function(field) {

        const element =
            document.getElementById(field);

        if (element) {

            element.value =
                member[field] || '';
        }
    });

    document.getElementById(
        'member-name-preview'
    ).innerHTML =
        member.full_name || '-';

    document.getElementById(
        'member-number-preview'
    ).innerHTML =
        member.member_no || '-';

    document.getElementById(
        'member-status-preview'
    ).innerHTML =
        (member.status || '').toUpperCase();

    document.getElementById(
        'membership-year-stat'
    ).innerHTML =
        member.membership_year || '-';

    document.getElementById(
        'batch-year-stat'
    ).innerHTML =
        member.batch_year || '-';

    if (member.profile_photo) {

        document.getElementById(
            'profile-photo-preview'
        ).src =
            member.profile_photo;
    }
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/
async function submitMemberForm()
{
    try {

        showLoading();

        const payload = {

            first_name:
                document.getElementById(
                    'first_name'
                ).value,

            last_name:
                document.getElementById(
                    'last_name'
                ).value,

            full_name:
                document.getElementById(
                    'full_name'
                ).value,

            email:
                document.getElementById(
                    'email'
                ).value,

            phone:
                document.getElementById(
                    'phone'
                ).value,

            nic:
                document.getElementById(
                    'nic'
                ).value,

            birthday:
                document.getElementById(
                    'birthday'
                ).value,

            gender:
                document.getElementById(
                    'gender'
                ).value,

            status:
                document.getElementById(
                    'status'
                ).value,

            member_no:
                document.getElementById(
                    'member_no'
                ).value,

            membership_year:
                document.getElementById(
                    'membership_year'
                ).value,

            batch_year:
                document.getElementById(
                    'batch_year'
                ).value,

            cricket_years:
                document.getElementById(
                    'cricket_years'
                ).value,

            address:
                document.getElementById(
                    'address'
                ).value
        };

        const response =
            await fetch(
                `/v1/member/${memberId}`,
                {
                    method: 'PUT',

                    headers: {
                        'Content-Type':
                            'application/json'
                    },

                    body:
                        JSON.stringify(payload)
                }
            );

        const result =
            await response.json();

        hideLoading();

        if (result.success) {

            alert(
                'Member updated successfully'
            );

            await loadMember();

        } else {

            alert(
                result.message ||
                'Failed to update member'
            );
        }

    } catch (error) {

        console.error(error);

        hideLoading();

        alert(
            'Network error occurred'
        );
    }
}

/*
|--------------------------------------------------------------------------
| Reset
|--------------------------------------------------------------------------
*/
function resetFormData()
{
    if (originalMemberData) {

        populateForm(
            originalMemberData
        );
    }
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

</script>

</body>
</html>